import { FormEvent, useEffect, useState } from "react";
import { createRoot } from "react-dom/client";
import "./customer.css";

type Customer = { id: number; name: string; nic: string | null; phone: string | null; email: string | null; loc: string | null; role: string };
type CustomerForm = { name: string; ref: string; nic: string; phone: string; email: string; loc: string };
const emptyForm: CustomerForm = { name: "", ref: "", nic: "", phone: "", email: "", loc: "" };

function Customers() {
  const [customers, setCustomers] = useState<Customer[]>([]);
  const [search, setSearch] = useState("");
  const [form, setForm] = useState(emptyForm);
  const [message, setMessage] = useState("");
  const [showForm, setShowForm] = useState(false);
  async function loadCustomers(term = search) { const response = await fetch(`/api/customers?search=${encodeURIComponent(term)}`); if (response.ok) setCustomers(await response.json() as Customer[]); }
  useEffect(() => { void loadCustomers(""); }, []);
  async function createCustomer(event: FormEvent) { event.preventDefault(); const response = await fetch("/api/customers", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(form) }); const result = await response.json() as { message?: string }; if (!response.ok) { setMessage(result.message ?? "Could not create customer"); return; } setForm(emptyForm); setShowForm(false); setMessage("Customer added successfully"); await loadCustomers(""); }
  return <main className="customer-shell"><header className="customer-header"><div><span className="eyebrow">Gold operations</span><h1>Customers</h1><p>Manage customer records and open their transaction workflows.</p></div><button onClick={() => setShowForm((visible) => !visible)}>{showForm ? "Close form" : "Add customer"}</button></header>{message && <div className="notice">{message}</div>}{showForm && <form className="customer-form" onSubmit={createCustomer}>{(Object.keys(form) as Array<keyof CustomerForm>).map((field) => <label key={field}>{field === "loc" ? "Location" : field.toUpperCase()}<input required={field === "name"} type={field === "email" ? "email" : field === "phone" ? "tel" : "text"} value={form[field]} onChange={(event) => setForm({ ...form, [field]: event.target.value })} /></label>)}<button type="submit">Save customer</button></form>}<section className="customer-list"><div className="search-row"><input value={search} onChange={(event) => setSearch(event.target.value)} placeholder="Search by name, NIC, or location" /><button onClick={() => void loadCustomers()}>Search</button></div><div className="table-wrap"><table><thead><tr><th>NIC</th><th>Name</th><th>Phone</th><th>Email</th><th>Location</th><th>Actions</th></tr></thead><tbody>{customers.map((customer) => <tr key={customer.id}><td>{customer.nic || "-"}</td><td>{customer.name}</td><td>{customer.phone || "-"}</td><td>{customer.email || "-"}</td><td>{customer.loc || "-"}</td><td><a href={`customer/newsell.php?id=${customer.id}`}>Debit</a><a href={`customer/newbuy.php?id=${customer.id}`}>Credit</a></td></tr>)}{customers.length === 0 && <tr><td colSpan={6} className="empty">No customers found.</td></tr>}</tbody></table></div></section></main>;
}

createRoot(document.getElementById("root")!).render(<Customers />);
