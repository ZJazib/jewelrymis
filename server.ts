import "dotenv/config";
import bcrypt from "bcryptjs";
import cors from "cors";
import express from "express";
import session from "express-session";
import mysql, { type RowDataPacket } from "mysql2/promise";

interface CountRow extends RowDataPacket {
  count: number | string | null;
}

interface SumRow extends RowDataPacket {
  total: number | string | null;
}

interface StatementRow extends RowDataPacket {
  se_date: string;
  se_type: string;
  se_tbill: string;
  se_tpurity: string;
  description: string | null;
  se_cus: number | string;
}

interface UserRow extends RowDataPacket {
  user_name: string;
  password: string;
  user_role: string;
}

const pool = mysql.createPool({
  host: process.env.DB_HOST ?? "localhost",
  user: process.env.DB_USER ?? "hmajewellery_user",
  password: process.env.DB_PASSWORD ?? "",
  database: process.env.DB_NAME ?? "hmajewellery_dba",
  waitForConnections: true,
  connectionLimit: 10,
  dateStrings: true
});

const app = express();
app.use(cors());
app.use(express.json());
app.use(session({
  secret: process.env.SESSION_SECRET ?? "development-only-change-me",
  resave: false,
  saveUninitialized: false,
  cookie: { httpOnly: true, sameSite: "lax", secure: process.env.NODE_ENV === "production" }
}));

const numeric = (value: number | string | null): number => Number(value ?? 0);

async function sum(query: string): Promise<number> {
  const [rows] = await pool.query<SumRow[]>(query);
  return numeric(rows[0]?.total ?? 0);
}

async function count(query: string): Promise<number> {
  const [rows] = await pool.query<CountRow[]>(query);
  return numeric(rows[0]?.count ?? 0);
}

app.get("/api/health", (_request, response) => {
  response.json({ status: "ok" });
});

app.post("/api/auth/signin", async (request, response) => {
  const email = typeof request.body.email === "string" ? request.body.email.trim() : "";
  const password = typeof request.body.password === "string" ? request.body.password : "";
  if (!email || !password || !/^\S+@\S+\.\S+$/.test(email)) {
    response.status(400).json({ message: "Enter a valid email and password" });
    return;
  }

  try {
    const [rows] = await pool.query<UserRow[]>("SELECT user_name, password, user_role FROM users WHERE user_name = ? AND user_role = 'Admin' LIMIT 1", [email]);
    const user = rows[0];
    if (!user || !(await bcrypt.compare(password, user.password))) {
      response.status(401).json({ message: "Incorrect email or password" });
      return;
    }
    request.session.user = { username: user.user_name, role: user.user_role };
    response.json({ username: user.user_name, role: user.user_role });
  } catch (error) {
    console.error("Sign-in failed", error);
    response.status(503).json({ message: "Authentication is temporarily unavailable" });
  }
});

app.post("/api/auth/signout", (request, response) => {
  request.session.destroy((error) => {
    if (error) {
      response.status(500).json({ message: "Could not sign out" });
      return;
    }
    response.status(204).end();
  });
});

app.get("/api/dashboard", async (_request, response) => {
  try {
    const [customerCount, supplierCount, moneySupplierCount, userCount, todayCashDebit, cashDebit, todayCashCredit, cashCredit, todayOfficeCash, officeCash, todayGoldDebit, goldDebit, todayGoldCredit, goldCredit, todaySilverDebit, silverDebit, todaySilverCredit, silverCredit] = await Promise.all([
      count("SELECT COUNT(id) AS count FROM customer WHERE role = 'Customer'"),
      count("SELECT COUNT(id) AS count FROM customer WHERE role = 'Supplier'"),
      count("SELECT COUNT(id) AS count FROM customer WHERE role = 'Money Supplier'"),
      count("SELECT COUNT(user_id) AS count FROM users"),
      sum("SELECT SUM(st_price) AS total FROM storage WHERE method = 'Debit' AND type = 'Cash' AND DATE(st_date) = CURDATE()"),
      sum("SELECT SUM(st_price) AS total FROM storage WHERE method = 'Debit' AND type = 'Cash'"),
      sum("SELECT SUM(st_price) AS total FROM storage WHERE method = 'Credit' AND type = 'Cash' AND DATE(st_date) = CURDATE()"),
      sum("SELECT SUM(st_price) AS total FROM storage WHERE method = 'Credit' AND type = 'Cash'"),
      sum("SELECT SUM(of_money) AS total FROM office WHERE state = 'Cash' AND DATE(of_date) = CURDATE()"),
      sum("SELECT SUM(of_money) AS total FROM office WHERE state = 'Cash'"),
      sum("SELECT SUM(st_gold) AS total FROM storage WHERE method = 'Debit' AND type = 'Cash' AND name != 'SILVER' AND DATE(st_date) = CURDATE()"),
      sum("SELECT SUM(st_gold) AS total FROM storage WHERE method = 'Debit' AND type = 'Cash' AND name != 'SILVER'"),
      sum("SELECT SUM(st_gold) AS total FROM storage WHERE method = 'Credit' AND type = 'Cash' AND name != 'SILVER' AND DATE(st_date) = CURDATE()"),
      sum("SELECT SUM(st_gold) AS total FROM storage WHERE method = 'Credit' AND type = 'Cash' AND name != 'SILVER'"),
      sum("SELECT SUM(st_gold) AS total FROM storage WHERE method = 'Debit' AND type = 'Cash' AND name = 'SILVER' AND DATE(st_date) = CURDATE()"),
      sum("SELECT SUM(st_gold) AS total FROM storage WHERE method = 'Debit' AND type = 'Cash' AND name = 'SILVER'"),
      sum("SELECT SUM(st_gold) AS total FROM storage WHERE method = 'Credit' AND type = 'Cash' AND name = 'SILVER' AND DATE(st_date) = CURDATE()"),
      sum("SELECT SUM(st_gold) AS total FROM storage WHERE method = 'Credit' AND type = 'Cash' AND name = 'SILVER'")
    ]);

    const [statements] = await pool.query<StatementRow[]>(
      "SELECT se_date, se_type, se_tbill, se_tpurity, description, se_cus FROM statement WHERE DATE(se_date) = CURDATE() ORDER BY se_date DESC LIMIT 12"
    );

    response.json({
      counts: { customers: customerCount, suppliers: supplierCount, moneySuppliers: moneySupplierCount, users: userCount },
      money: { today: todayCashDebit + todayOfficeCash - todayCashCredit, total: cashDebit + officeCash - cashCredit },
      gold: { today: todayGoldDebit - todayGoldCredit, total: goldDebit - goldCredit },
      silver: { today: todaySilverDebit - todaySilverCredit, total: silverDebit - silverCredit },
      statements
    });
  } catch (error) {
    console.error("Dashboard query failed", error);
    response.status(503).json({ message: "Dashboard data is temporarily unavailable" });
  }
});

const port = Number(process.env.PORT ?? 3000);
app.listen(port, () => console.log(`Jewellery MIS API listening on http://localhost:${port}`));
