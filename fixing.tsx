import { FormEvent, useEffect, useState } from "react";
import { createRoot } from "react-dom/client";
import "./fixing.css";

type Person = { id: number; name: string; role: string; nic: string | null };
function Fixing() {
  const [people, setPeople] = useState<Person[]>([]);
  const [customerId, setCustomerId] = useState("");
  const [newPerson, setNewPerson] = useState(false);
  const [role, setRole] = useState("Customer");
  const [type, setType] = useState("Buy");
  const [reference, setReference] = useState("");
  const [gold, setGold] = useState("");
  const [ounce, setOunce] = useState("");
  const [total, setTotal] = useState("");
  const [name, setName] = useState("");
  const [message, setMessage] = useState("");
  useEffect(() => { fetch("/api/customers?search=").then((response) => response.json() as Promise<Person[]>).then(setPeople); }, []);
  async function submit(event: FormEvent) { event.preventDefault(); const response = await fetch("/api/fixing", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ customerId: Number(customerId), type, reference, gold: Number(gold), ounce: Number(ounce), total: Number(total), newCustomer: newPerson ? { name, role } : undefined }) }); const result = await response.json() as { message?: string }; setMessage(response.ok ? "Fixing transaction saved atomically." : result.message ?? "Fixing transaction failed"); }
  return <main className="fixing-shell"><header><span className="eyebrow">Metal settlement</span><h1>Fixing transaction</h1><p>Record a gold fixing against a customer or supplier.</p></header><form onSubmit={submit}><section className="panel"><div className="toggle"><button type="button" className={!newPerson ? "active" : ""} onClick={() => setNewPerson(false)}>Existing person</button><button type="button" className={newPerson ? "active" : ""} onClick={() => setNewPerson(true)}>New person</button></div>{newPerson ? <><label>Name<input required value={name} onChange={(event) => setName(event.target.value)} /></label><label>Role<select value={role} onChange={(event) => setRole(event.target.value)}><option>Customer</option><option>Supplier</option></select></label></> : <label>Person<select required value={customerId} onChange={(event) => setCustomerId(event.target.value)}><option value="">Select person</option>{people.map((person) => <option value={person.id} key={person.id}>{person.name} · {person.role}</option>)}</select></label>}<label>Transaction<select value={type} onChange={(event) => setType(event.target.value)}><option>Buy</option><option>Sell</option></select></label><label>Reference<input required value={reference} onChange={(event) => setReference(event.target.value)} /></label><label>Gold weight<input required type="number" min="0" step="any" value={gold} onChange={(event) => setGold(event.target.value)} /></label><label>Ounce<input required type="number" min="0" step="any" value={ounce} onChange={(event) => setOunce(event.target.value)} /></label><label>Total money<input required type="number" min="0" step="any" value={total} onChange={(event) => setTotal(event.target.value)} /></label></section>{message && <div className="notice">{message}</div>}<button className="submit">Save fixing</button></form></main>;
}
createRoot(document.getElementById("root")!).render(<Fixing />);
