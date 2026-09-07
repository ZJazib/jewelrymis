import { FormEvent, useEffect, useState } from "react";
import { createRoot } from "react-dom/client";
import "./journal.css";

type JournalRow = { se_id: number; se_date: string; se_type: "Debit" | "Credit"; se_tbill: number | string | null; se_tpurity: number | string | null; dis: string | null; customer_name: string | null };
type JournalData = { rows: JournalRow[]; totals: { debit: number; credit: number; goldDebit: number; goldCredit: number } };
const format = new Intl.NumberFormat("en-US", { maximumFractionDigits: 3 });
const amount = (value: number | string | null) => Number(value ?? 0);

function Journal() {
  const [fromDate, setFromDate] = useState("");
  const [toDate, setToDate] = useState("");
  const [data, setData] = useState<JournalData | null>(null);
  const [error, setError] = useState("");
  async function load(event?: FormEvent) {
    event?.preventDefault();
    const query = new URLSearchParams();
    if (fromDate) query.set("from_date", fromDate);
    if (toDate) query.set("to_date", toDate);
    const response = await fetch(`/api/journal?${query}`);
    const result = await response.json() as JournalData & { message?: string };
    if (!response.ok) { setError(result.message ?? "Unable to load journal"); return; }
    setError(""); setData(result);
  }
  useEffect(() => { void load(); }, []);
  const totals = data?.totals ?? { debit: 0, credit: 0, goldDebit: 0, goldCredit: 0 };
  return <main className="journal-shell"><header><div><span className="eyebrow">Financial reporting</span><h1>Daily journal</h1><p>Review every statement and the running money and gold position.</p></div><span className="record-count">{data?.rows.length ?? 0} entries</span></header><form className="filters" onSubmit={load}><label>From<input type="date" value={fromDate} onChange={(event) => setFromDate(event.target.value)} /></label><label>To<input type="date" value={toDate} onChange={(event) => setToDate(event.target.value)} /></label><button>Apply filter</button></form>{error && <div className="error">{error}</div>}<section className="journal-card"><div className="table-wrap"><table><thead><tr><th>Date</th><th>Name</th><th>Description</th><th>Debit USD</th><th>Credit USD</th><th>Debit GMS</th><th>Credit GMS</th></tr></thead><tbody>{data?.rows.map((row) => <tr key={row.se_id}><td>{new Date(row.se_date).toLocaleDateString()}</td><td>{row.customer_name ?? "-"}</td><td>{row.dis ?? "-"}</td><td className="debit">{row.se_type === "Debit" && amount(row.se_tbill) > 0 ? `(${format.format(amount(row.se_tbill))})` : ""}</td><td className="credit">{row.se_type === "Credit" && amount(row.se_tbill) > 0 ? format.format(amount(row.se_tbill)) : ""}</td><td className="debit">{row.se_type === "Debit" && amount(row.se_tpurity) > 0 ? `(${format.format(amount(row.se_tpurity))})` : ""}</td><td className="credit">{row.se_type === "Credit" && amount(row.se_tpurity) > 0 ? format.format(amount(row.se_tpurity)) : ""}</td></tr>)}{data && data.rows.length === 0 && <tr><td colSpan={7} className="empty">No statements found for this period.</td></tr>}</tbody><tfoot><tr><th colSpan={3}>Total</th><th className="debit">({format.format(totals.debit)})</th><th className="credit">{format.format(totals.credit)}</th><th className="debit">({format.format(totals.goldDebit)})</th><th className="credit">{format.format(totals.goldCredit)}</th></tr></tfoot></table></div><div className="summary"><span>Money balance: <strong>{format.format(totals.debit - totals.credit)} USD</strong></span><span>Gold balance: <strong>{format.format(totals.goldDebit - totals.goldCredit)} GMS</strong></span></div></section></main>;
}

createRoot(document.getElementById("root")!).render(<Journal />);
