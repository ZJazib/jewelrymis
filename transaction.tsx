import { FormEvent, useEffect, useMemo, useState } from "react";
import { createRoot } from "react-dom/client";
import "./transaction.css";

type Customer = { id: number; name: string; nic: string | null; loc: string | null };
type Line = { description: string; grossWeight: string; stoneWeight: string; purity: string; pureWeight: string; pricePerGram: string; makingCharge: string };
const blankLine = (): Line => ({ description: "", grossWeight: "", stoneWeight: "", purity: "0.750", pureWeight: "", pricePerGram: "", makingCharge: "" });
const number = (value: string) => Number(value) || 0;

function Transaction() {
  const params = new URLSearchParams(window.location.search);
  const customerId = params.get("customer");
  const direction = params.get("direction") === "credit" ? "credit" : "debit";
  const [customer, setCustomer] = useState<Customer | null>(null);
  const [reference, setReference] = useState("");
  const [paid, setPaid] = useState("Cash");
  const [tax, setTax] = useState("0");
  const [carryCharge, setCarryCharge] = useState("");
  const [lines, setLines] = useState<Line[]>([blankLine()]);
  const [message, setMessage] = useState("");
  const [saved, setSaved] = useState(false);
  const totals = useMemo(() => {
    const gross = lines.reduce((sum, line) => sum + number(line.grossWeight) + number(line.stoneWeight), 0);
    const pure = lines.reduce((sum, line) => sum + number(line.pureWeight), 0);
    const making = lines.reduce((sum, line) => sum + number(line.makingCharge), 0);
    const carry = gross * number(carryCharge) / 1000;
    const bill = making + carry + making * number(tax) / 100;
    return { gross, pure, making, carry, bill };
  }, [lines, carryCharge, tax]);

  useEffect(() => {
    if (!customerId) return;
    fetch(`/api/customers?search=`).then((response) => response.json() as Promise<Customer[]>).then((customers) => setCustomer(customers.find((item) => item.id === Number(customerId)) ?? null));
  }, [customerId]);

  function updateLine(index: number, field: keyof Line, value: string) {
    const next = [...lines];
    next[index] = { ...next[index], [field]: value };
    if (field === "grossWeight" || field === "purity") next[index].pureWeight = (number(next[index].grossWeight) * number(next[index].purity)).toFixed(3);
    setLines(next);
  }

  async function submit(event: FormEvent) {
    event.preventDefault();
    setMessage("");
    if (!customerId) { setMessage("A customer is required"); return; }
    const response = await fetch(`/api/customers/${customerId}/transactions`, { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ direction, reference, paid, totalGold: totals.gross, totalPure: totals.pure, bill: totals.bill, lines }) });
    const result = await response.json() as { message?: string };
    if (!response.ok) { setMessage(result.message ?? "Transaction could not be saved"); return; }
    setSaved(true);
    setMessage("Transaction saved atomically to all ledger tables.");
  }

  return <main className="transaction-shell"><header><div><span className="eyebrow">Customer ledger</span><h1>{direction === "debit" ? "Debit" : "Credit"} transaction</h1><p>{customer ? `${customer.name}${customer.nic ? ` · ${customer.nic}` : ""}` : "Select a customer from the customer list."}</p></div><span className={`direction ${direction}`}>{direction}</span></header><form onSubmit={submit}><section className="panel basics"><label>Reference<input value={reference} onChange={(event) => setReference(event.target.value)} /></label><label>Paid method<select value={paid} onChange={(event) => setPaid(event.target.value)}><option value="Cash">Cash</option></select></label><label>Tax %<input type="number" min="0" max="100" value={tax} onChange={(event) => setTax(event.target.value)} /></label><label>Carry charge<input type="number" min="0" step="any" value={carryCharge} onChange={(event) => setCarryCharge(event.target.value)} /></label></section><section className="panel"><div className="section-title"><h2>Gold information</h2><button type="button" className="secondary" onClick={() => setLines([...lines, blankLine()])}>Add line</button></div>{lines.map((line, index) => <div className="line" key={index}><div className="line-heading"><span>Line {index + 1}</span>{lines.length > 1 && <button type="button" className="remove" onClick={() => setLines(lines.filter((_, itemIndex) => itemIndex !== index))}>Remove</button>}</div><label>Description<input required value={line.description} onChange={(event) => updateLine(index, "description", event.target.value)} /></label><label>Gross weight<input required type="number" min="0" step="any" value={line.grossWeight} onChange={(event) => updateLine(index, "grossWeight", event.target.value)} /></label><label>Stone weight<input type="number" min="0" step="any" value={line.stoneWeight} onChange={(event) => updateLine(index, "stoneWeight", event.target.value)} /></label><label>Purity<select value={line.purity} onChange={(event) => updateLine(index, "purity", event.target.value)}><option value="0.585">14K · 0.585</option><option value="0.750">18K · 0.750</option><option value="0.875">21K · 0.875</option><option value="0.920">22K · 0.920</option><option value="0.999">24K · 0.999</option></select></label><label>Pure weight<input required type="number" min="0" step="any" value={line.pureWeight} onChange={(event) => updateLine(index, "pureWeight", event.target.value)} /></label><label>Price / gram<input type="number" min="0" step="any" value={line.pricePerGram} onChange={(event) => updateLine(index, "pricePerGram", event.target.value)} /></label><label>Making charge<input type="number" min="0" step="any" value={line.makingCharge} onChange={(event) => updateLine(index, "makingCharge", event.target.value)} /></label></div>)}</section><section className="panel totals"><div><span className="eyebrow">Totals</span><strong>{totals.gross.toFixed(3)} <small>GMS gross</small></strong></div><div><strong>{totals.pure.toFixed(3)} <small>GMS pure</small></strong></div><div><strong>{totals.bill.toFixed(3)} <small>USD bill</small></strong></div></section>{message && <div className={saved ? "success" : "error"}>{message}</div>}<button className="submit" disabled={saved}>{saved ? "Saved" : `Save ${direction} transaction`}</button></form></main>;
}

createRoot(document.getElementById("root")!).render(<Transaction />);
