import { FormEvent, useEffect, useState } from "react";
import { createRoot } from "react-dom/client";
import "./balance.css";

type CustomerBalance = { customer_id: number; customer_name: string; customer_role: string; total_gold_balance: number | string | null; total_money_balance: number | string | null };
type BalanceResponse = { customers: CustomerBalance[]; storage: { money: number; gold: number } };
const format = new Intl.NumberFormat("en-US", { maximumFractionDigits: 3 });
const value = (amount: number | string | null) => Number(amount ?? 0);

function Balance() {
  const [fromDate, setFromDate] = useState("");
  const [toDate, setToDate] = useState("");
  const [data, setData] = useState<BalanceResponse | null>(null);
  const [error, setError] = useState("");
  const load = async (event?: FormEvent) => {
    event?.preventDefault();
    setError("");
    try {
      const query = new URLSearchParams();
      if (fromDate) query.set("from_date", fromDate);
      if (toDate) query.set("to_date", toDate);
      const response = await fetch(`/api/balance?${query}`);
      const result = await response.json() as BalanceResponse & { message?: string };
      if (!response.ok) throw new Error(result.message ?? "Unable to load balance");
      setData(result);
    } catch (requestError) { setError(requestError instanceof Error ? requestError.message : "Unable to load balance"); }
  };
  useEffect(() => { void load(); }, []);
  const rows = data?.customers ?? [];
  const money = rows.reduce((total, row) => total + value(row.total_money_balance), data?.storage.money ?? 0);
  const gold = rows.reduce((total, row) => total + value(row.total_gold_balance), data?.storage.gold ?? 0);
  return <main className="balance-shell"><header><span className="eyebrow">Financial reporting</span><h1>Balance statement</h1><p>Review money and metal positions by customer across a selected period.</p></header><form className="filters" onSubmit={load}><label>From<input type="date" value={fromDate} onChange={(event) => setFromDate(event.target.value)} /></label><label>To<input type="date" value={toDate} onChange={(event) => setToDate(event.target.value)} /></label><button>Apply filter</button></form>{error && <div className="error">{error}</div>}<section className="summary"><article><span className="eyebrow">Money balance</span><strong>{format.format(money)} <small>USD</small></strong></article><article><span className="eyebrow">Gold balance</span><strong>{format.format(gold)} <small>GMS</small></strong></article></section><section className="table-card"><table><thead><tr><th>Role</th><th>Name</th><th>Money position</th><th>Gold position</th></tr></thead><tbody><tr className="storage"><td>Storage</td><td>Storage</td><td>{format.format(data?.storage.money ?? 0)} USD</td><td>{format.format(data?.storage.gold ?? 0)} GMS</td></tr>{rows.map((row) => <tr key={row.customer_id}><td>{row.customer_role}</td><td>{row.customer_name}</td><td className={value(row.total_money_balance) < 0 ? "negative" : "positive"}>{format.format(value(row.total_money_balance))} USD</td><td className={value(row.total_gold_balance) < 0 ? "negative" : "positive"}>{format.format(value(row.total_gold_balance))} GMS</td></tr>)}{data && rows.length === 0 && <tr><td colSpan={4} className="empty">No customer balances found.</td></tr>}</tbody></table></section></main>;
}

createRoot(document.getElementById("root")!).render(<Balance />);
