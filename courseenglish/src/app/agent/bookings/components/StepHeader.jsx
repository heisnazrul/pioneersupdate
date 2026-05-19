"use client";

export default function StepHeader({ steps, current, dir }) {
  return (
    <div className="flex flex-wrap items-center gap-2 text-sm font-normal" dir={dir}>
      {steps.map((step, idx) => {
        const active = current === idx;
        const done = idx < current;
        return (
          <div key={step} className="flex items-center gap-2">
            <div
              className={`h-8 w-8 rounded-full border text-center leading-8 ${
                active ? "bg-[#1277BE] text-white border-[#1277BE]" : done ? "bg-emerald-50 text-emerald-700 border-emerald-200" : "bg-white text-slate-600 border-slate-200"
              }`}
            >
              {idx + 1}
            </div>
            <span className={active ? "text-slate-900" : "text-slate-500"}>{step}</span>
            {idx !== steps.length - 1 && <span className="text-slate-300">/</span>}
          </div>
        );
      })}
    </div>
  );
}
