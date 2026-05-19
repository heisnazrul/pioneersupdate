import Link from "next/link";
import AuthShell from "@/components/AuthShell";

export default function ForgotPasswordPage() {
  return (
    <AuthShell
      title="Reset your password"
      subtitle="Enter your email to receive a reset link."
    >
      <form className="mt-6 space-y-4">
        <div>
          <label className="text-sm font-semibold text-slate-700">Email</label>
          <input
            type="email"
            name="email"
            placeholder="you@email.com"
            className="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[#1F63AE] focus:ring-2 focus:ring-[#1F63AE]/20"
          />
        </div>
        <button
          type="submit"
          className="mt-2 w-full rounded-full bg-[#1F63AE] py-3 text-sm font-semibold text-white shadow-md transition hover:bg-[#175093]"
        >
          Send reset link
        </button>
      </form>

      <div className="mt-6 text-center text-sm text-slate-600">
        Remembered your password?{" "}
        <Link href="/login" className="font-semibold text-[#1F63AE]">
          Back to login
        </Link>
      </div>
    </AuthShell>
  );
}
