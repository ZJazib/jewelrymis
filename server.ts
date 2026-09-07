import "dotenv/config";
import bcrypt from "bcryptjs";
import cors from "cors";
import express from "express";
import session from "express-session";
import mysql, { type ResultSetHeader, type RowDataPacket } from "mysql2/promise";

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

interface BalanceRow extends RowDataPacket {
  customer_id: number;
  customer_name: string;
  customer_role: string;
  total_debit_gold: number | string | null;
  total_credit_gold: number | string | null;
  total_gold_balance: number | string | null;
  total_debit_money: number | string | null;
  total_credit_money: number | string | null;
  total_money_balance: number | string | null;
}

interface CustomerRow extends RowDataPacket {
  id: number;
  name: string;
  nic: string | null;
  phone: string | null;
  email: string | null;
  loc: string | null;
  role: string;
}

interface TransactionLine {
  description: string;
  grossWeight: number;
  stoneWeight: number;
  purity: number;
  pureWeight: number;
  pricePerGram: number;
  makingCharge: number;
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

app.get("/api/balance", async (request, response) => {
  const fromDate = typeof request.query.from_date === "string" ? request.query.from_date : "";
  const toDate = typeof request.query.to_date === "string" ? request.query.to_date : "";
  if ((fromDate && !/^\d{4}-\d{2}-\d{2}$/.test(fromDate)) || (toDate && !/^\d{4}-\d{2}-\d{2}$/.test(toDate))) {
    response.status(400).json({ message: "Dates must use YYYY-MM-DD format" });
    return;
  }

  try {
    let query = `SELECT c.id AS customer_id, c.name AS customer_name, c.role AS customer_role,
      SUM(CASE WHEN s.se_type = 'Debit' THEN s.se_tpurity ELSE 0 END) AS total_debit_gold,
      SUM(CASE WHEN s.se_type = 'Credit' THEN s.se_tpurity ELSE 0 END) AS total_credit_gold,
      SUM(CASE WHEN s.se_type = 'Credit' THEN s.se_tpurity ELSE 0 END) - SUM(CASE WHEN s.se_type = 'Debit' THEN s.se_tpurity ELSE 0 END) AS total_gold_balance,
      SUM(CASE WHEN s.se_type = 'Debit' THEN s.se_tbill ELSE 0 END) AS total_debit_money,
      SUM(CASE WHEN s.se_type = 'Credit' THEN s.se_tbill ELSE 0 END) AS total_credit_money,
      SUM(CASE WHEN s.se_type = 'Credit' THEN s.se_tbill ELSE 0 END) - SUM(CASE WHEN s.se_type = 'Debit' THEN s.se_tbill ELSE 0 END) AS total_money_balance
      FROM customer c LEFT JOIN statement s ON c.id = s.se_cus WHERE s.se_cus > 2`;
    const parameters: string[] = [];
    if (fromDate) { query += " AND s.se_date >= ?"; parameters.push(fromDate); }
    if (toDate) { query += " AND s.se_date <= ?"; parameters.push(toDate); }
    query += " GROUP BY c.id ORDER BY c.role, c.name";
    const [customers] = await pool.query<BalanceRow[]>(query, parameters);
    const [storage] = await pool.query<SumRow[]>("SELECT SUM(CASE WHEN method = 'Debit' THEN st_price ELSE 0 END) - SUM(CASE WHEN method = 'Credit' THEN st_price ELSE 0 END) AS total FROM storage WHERE type = 'Cash'");
    const [gold] = await pool.query<SumRow[]>("SELECT SUM(CASE WHEN method = 'Credit' THEN st_gold ELSE 0 END) - SUM(CASE WHEN method = 'Debit' THEN st_gold ELSE 0 END) AS total FROM storage WHERE type = 'Cash'");
    response.json({ customers, storage: { money: numeric(storage[0]?.total ?? 0), gold: numeric(gold[0]?.total ?? 0) } });
  } catch (error) {
    console.error("Balance query failed", error);
    response.status(503).json({ message: "Balance data is temporarily unavailable" });
  }
});

app.get("/api/customers", async (request, response) => {
  const search = typeof request.query.search === "string" ? request.query.search.trim() : "";
  try {
    const [rows] = await pool.query<CustomerRow[]>(
      "SELECT id, name, nic, phone, email, loc, role FROM customer WHERE role = 'Customer' AND (? = '' OR name LIKE ? OR nic LIKE ? OR loc LIKE ?) ORDER BY date DESC",
      [search, `%${search}%`, `%${search}%`, `%${search}%`]
    );
    response.json(rows);
  } catch (error) {
    console.error("Customer list query failed", error);
    response.status(503).json({ message: "Customer data is temporarily unavailable" });
  }
});

app.post("/api/customers", async (request, response) => {
  const fields = ["name", "ref", "nic", "phone", "email", "loc"] as const;
  const values = fields.map((field) => typeof request.body[field] === "string" ? request.body[field].trim() : "");
  if (!values[0]) {
    response.status(400).json({ message: "Customer name is required" });
    return;
  }
  try {
    const [result] = await pool.execute<ResultSetHeader>("INSERT INTO customer (name, phone, email, nic, ref, loc, role) VALUES (?, ?, ?, ?, ?, ?, 'Customer')", values);
    response.status(201).json({ id: result.insertId });
  } catch (error) {
    console.error("Customer creation failed", error);
    response.status(503).json({ message: "Customer could not be created" });
  }
});

app.post("/api/customers/:id/transactions", async (request, response) => {
  const customerId = Number(request.params.id);
  const direction = request.body.direction === "debit" ? "Debit" : request.body.direction === "credit" ? "Credit" : "";
  const paid = request.body.paid === "Cash" ? "Cash" : "";
  const reference = typeof request.body.reference === "string" ? request.body.reference.trim() : "";
  const totalGold = Number(request.body.totalGold);
  const totalPure = Number(request.body.totalPure);
  const bill = Number(request.body.bill);
  const lines = Array.isArray(request.body.lines) ? request.body.lines as TransactionLine[] : [];

  if (!Number.isInteger(customerId) || customerId < 1 || !direction || !paid || !Number.isFinite(totalGold) || !Number.isFinite(totalPure) || !Number.isFinite(bill) || lines.length === 0) {
    response.status(400).json({ message: "Complete the customer, payment, totals, and at least one gold line" });
    return;
  }
  if (lines.some((line) => !line.description.trim() || !Number.isFinite(Number(line.grossWeight)) || !Number.isFinite(Number(line.pureWeight)))) {
    response.status(400).json({ message: "Each gold line needs a description, gross weight, and pure weight" });
    return;
  }

  const connection = await pool.getConnection();
  try {
    await connection.beginTransaction();
    const [customerRows] = await connection.execute<CustomerRow[]>("SELECT id FROM customer WHERE id = ? AND role = 'Customer' LIMIT 1", [customerId]);
    if (!customerRows[0]) {
      await connection.rollback();
      response.status(404).json({ message: "Customer not found" });
      return;
    }
    const state = direction;
    await connection.execute("INSERT INTO debit (dep_cus, ref, dep_tgold, dep_tbill, dep_tpurity, sate, com, typec) VALUES (?, ?, ?, ?, ?, ?, 0, ?)", [customerId, reference, totalGold, bill, totalPure, state, paid]);
    await connection.execute("INSERT INTO account (cus_id, ref, amount, amo_cre, gold, role, state, type, com) VALUES (?, ?, ?, 0, ?, 'Customer', ?, ?, 0)", [customerId, reference, bill, totalPure, paid, state]);
    await connection.execute("INSERT INTO storage (st_cus, st_price, st_gold, type, method, name) VALUES (?, 0, ?, ?, ?, 'gold')", [customerId, totalPure, paid, state]);
    const description = `JEWELLERY SOLD${direction === "Credit" ? " REVISED" : ""} <br> (GROSS WT - ${totalGold} GMS)`;
    await connection.execute("INSERT INTO statement (se_cus, ref, se_tgolg, se_tpurity, se_tbill, se_type, se_method, dis) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [customerId, reference, totalGold, totalPure, bill, state, paid, description]);
    await connection.commit();
    response.status(201).json({ customerId, direction, reference, totalGold, totalPure, bill, lines });
  } catch (error) {
    await connection.rollback();
    console.error("Customer transaction failed", error);
    response.status(503).json({ message: "Transaction could not be saved; no ledger entries were committed" });
  } finally {
    connection.release();
  }
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
