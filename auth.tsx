import { FormEvent, useState } from "react";
import { createRoot } from "react-dom/client";
import "./auth.css";

function Auth() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [message, setMessage] = useState("");
  const [busy, setBusy] = useState(false);

  async function signIn(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setBusy(true);
    setMessage("");
    try {
      const response = await fetch("/api/auth/signin", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ email, password }) });
      const result = await response.json() as { message?: string };
      if (!response.ok) throw new Error(result.message ?? "Sign-in failed");
      window.location.assign("/");
    } catch (error) {
      setMessage(error instanceof Error ? error.message : "Sign-in failed");
    } finally {
      setBusy(false);
    }
  }

  return <main className="auth-shell"><section className="auth-panel"><span className="auth-mark">ZMIS</span><span className="eyebrow">Jewellery management system</span><h1>Welcome back</h1><p>Sign in to continue to your operations dashboard.</p>{message && <div className="auth-error" role="alert">{message}</div>}<form onSubmit={signIn}><label>Email<input type="email" value={email} onChange={(event) => setEmail(event.target.value)} autoComplete="username" required /></label><label>Password<input type="password" value={password} onChange={(event) => setPassword(event.target.value)} autoComplete="current-password" required /></label><button disabled={busy}>{busy ? "Signing in..." : "Sign in"}</button></form></section></main>;
}

createRoot(document.getElementById("root")!).render(<Auth />);
