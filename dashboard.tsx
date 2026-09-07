import { useEffect, useState } from "react";
import { createRoot } from "react-dom/client";
import "./dashboard.css";

type MetricGroup = { today: number; total: number };
type DashboardData = {
  counts: { customers: number; suppliers: number; moneySuppliers: number; users: number };
  money: MetricGroup;
  gold: MetricGroup;
  silver: MetricGroup;
  statements: Array<{ se_date: string; se_type: string; se_tbill: string; se_tpurity: string; description: string | null; se_cus: number | string }>;
};

const number = new Intl.NumberFormat("en-US", { maximumFractionDigits: 3 });

function App() {
  const [data, setData] = useState<DashboardData | null>(null);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    fetch("/api/dashboard")
      .then((response) => {
        if (!response.ok) throw new Error("Dashboard data is unavailable");
        return response.json() as Promise<DashboardData>;
      })
      .then(setData)
      .catch((requestError: Error) => setError(requestError.message));
  }, []);

  if (error) return <main className="shell"><div className="error">{error}. Check the API and database configuration.</div></main>;
  if (!data) return <main className="shell"><div className="loading">Loading dashboard...</div></main>;

  const cards = [
    ["Customers", data.counts.customers, "#2364aa"],
    ["Suppliers", data.counts.suppliers, "#2a9d8f"],
    ["Money suppliers", data.counts.moneySuppliers, "#e9a23b"],
    ["Users", data.counts.users, "#d1495b"]
  ] as const;

  return <main className="shell">
    <header className="topbar"><div><span className="eyebrow">Jewellery management system</span><h1>Operations dashboard</h1></div><span className="date">Today&apos;s position</span></header>
    <section className="count-grid">{cards.map(([label, value, color]) => <article className="count-card" key={label}><span className="card-accent" style={{ backgroundColor: color }} /><span className="label">{label}</span><strong>{number.format(value)}</strong></article>)}</section>
    <section className="balance-grid">
      <Balance title="Cash position" value={data.money} unit="USD" accent="#2364aa" />
      <Balance title="Gold position" value={data.gold} unit="grams" accent="#e9a23b" />
      <Balance title="Silver position" value={data.silver} unit="grams" accent="#829ab1" />
    </section>
    <section className="activity"><div className="section-heading"><div><span className="eyebrow">Live ledger</span><h2>Today&apos;s statements</h2></div><span className="pill">{data.statements.length} entries</span></div><div className="table-wrap"><table><thead><tr><th>Date</th><th>Type</th><th>Bill</th><th>Purity</th><th>Description</th></tr></thead><tbody>{data.statements.map((statement, index) => <tr key={`${statement.se_date}-${index}`}><td>{statement.se_date}</td><td>{statement.se_type}</td><td>{statement.se_tbill}</td><td>{statement.se_tpurity}</td><td>{statement.description ?? "-"}</td></tr>)}{data.statements.length === 0 && <tr><td colSpan={5} className="empty">No statements recorded today.</td></tr>}</tbody></table></div></section>
  </main>;
}

function Balance({ title, value, unit, accent }: { title: string; value: MetricGroup; unit: string; accent: string }) {
  return <article className="balance-card" style={{ borderTopColor: accent }}><span className="eyebrow">{title}</span><strong>{number.format(value.total)} <small>{unit}</small></strong><span className="today">{number.format(value.today)} {unit} today</span></article>;
}

createRoot(document.getElementById("root")!).render(<App />);
