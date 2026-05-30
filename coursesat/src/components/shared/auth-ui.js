export const authInputClass =
  "w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-normal text-slate-900 shadow-sm outline-none transition-all placeholder:text-slate-400 focus:border-[#135FAE] focus:ring-4 focus:ring-blue-500/10";

export const authButtonClass =
  "w-full rounded-2xl bg-[#135FAE] py-4 text-base font-medium text-white shadow-lg shadow-blue-500/30 transition-all hover:bg-[#104a8a] active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-70";

export function AuthErrorBox({ message }) {
  if (!message) return null;

  return (
    <div className="flex items-center justify-center gap-2 rounded-xl border border-red-100 bg-red-50 p-4 text-center text-sm font-medium text-red-600">
      <svg className="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth="2"
          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        />
      </svg>
      {message}
    </div>
  );
}

export function AuthSuccessBox({ message }) {
  if (!message) return null;

  return (
    <div className="rounded-xl border border-green-100 bg-green-50 p-4 text-center text-sm font-medium text-green-700">
      {message}
    </div>
  );
}

export function PasswordToggleButton({ show, onToggle, isRtl }) {
  return (
    <button
      type="button"
      onClick={onToggle}
      className={`absolute top-1/2 -translate-y-1/2 text-slate-400 transition-colors hover:text-slate-600 ${isRtl ? "left-4" : "right-4"}`}
    >
      {show ? (
        <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        </svg>
      ) : (
        <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
      )}
    </button>
  );
}
