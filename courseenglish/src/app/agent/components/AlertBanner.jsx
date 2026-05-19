"use client";

export default function AlertBanner({ tone = "info", message, onRetry, dir = "ltr" }) {
  if (!message) return null;
  const tones = {
    info: { bg: "bg-sky-50", text: "text-sky-700", border: "border-sky-100" },
    success: { bg: "bg-emerald-50", text: "text-emerald-700", border: "border-emerald-100" },
    error: { bg: "bg-rose-50", text: "text-rose-700", border: "border-rose-100" },
  };
  const t = tones[tone] || tones.info;
  return (
    <div
      className={`flex items-start gap-3 rounded-2xl border px-4 py-3 text-sm ${t.bg} ${t.text} ${t.border}`}
      dir={dir}
    >
      <span aria-hidden>⚠️</span>
      <div className="flex-1">{message}</div>
      {onRetry ? (
        <button
          type="button"
          onClick={onRetry}
          className="text-xs font-medium underline decoration-2 underline-offset-2 hover:opacity-80"
        >
          Retry
        </button>
      ) : null}
    </div>
  );
}
