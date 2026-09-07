import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";

export default defineConfig({
  plugins: [react()],
  build: {
    rollupOptions: {
      input: {
        dashboard: "index.html",
        auth: "auth.html",
        balance: "balance.html",
        customer: "customer.html",
        transaction: "transaction.html",
        journal: "journal.html"
      }
    }
  },
  server: {
    port: 5173,
    proxy: {
      "/api": "http://localhost:3000"
    }
  }
});
