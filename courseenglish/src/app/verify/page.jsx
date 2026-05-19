import Link from "next/link";
import AuthShell from "../components/AuthShell";

export default function VerifyPage() {
  return (
    <AuthShell
      title="Verify your account"
      subtitle="Enter the 4-digit code sent to your email."
    >
      <form className="mt-6 space-y-6">
        <div>
          <label className="text-sm font-normal text-slate-700">
            Verification code
          </label>
          <div className="mt-3 grid grid-cols-4 gap-3">
            {[0, 1, 2, 3].map((idx) => (
              <input
                key={idx}
                type="text"
                inputMode="numeric"
                maxLength={1}
                className="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 text-center text-lg font-extrabold text-slate-900 outline-none transition focus:border-[#1F63AE] focus:ring-2 focus:ring-[#1F63AE]/20"
              />
            ))}
          </div>
        </div>
        <button
          type="submit"
          className="w-full rounded-full bg-[#1F63AE] py-3 text-sm font-normal text-white shadow-md transition hover:bg-[#175093]"
        >
          Verify account
        </button>
      </form>

      <div className="mt-6 text-center text-sm text-slate-600">
        Didn&apos;t receive the code?{" "}
        <button type="button" className="font-normal text-[#1F63AE]">
          Resend
        </button>
      </div>
      <div className="mt-2 text-center text-sm text-slate-600">
        <Link href="/login" className="font-normal text-[#1F63AE]">
          Back to login
        </Link>
      </div>
    </AuthShell>
  );
}
