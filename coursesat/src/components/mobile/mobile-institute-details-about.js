"use client";

/* eslint-disable @next/next/no-img-element */

export default function MobileInstituteDetailsAbout({
  description,
  accreditations = [],
  isArabic = false,
  aboutLabel,
  accreditedByLabel,
}) {
  const aboutText = String(description ?? "").trim();
  const hasDescription = aboutText.length > 0;
  const hasAccreditations = accreditations.length > 0;

  if (!hasDescription && !hasAccreditations) return null;

  const loc = (en, ar) => (isArabic && ar ? ar : en);

  return (
    <section className="space-y-6">
      {hasDescription ? (
        <div>
          <h2 className="mb-3 text-start text-base font-bold text-slate-900">{aboutLabel}</h2>
          <p
            className="max-h-[120px] overflow-y-auto text-start text-sm leading-7 text-slate-600 scrollbar-thin"
            dir={isArabic ? "rtl" : "ltr"}
          >
            {aboutText}
          </p>
        </div>
      ) : null}

      {hasAccreditations ? (
        <div>
          <h2 className="mb-3 text-start text-base font-bold text-slate-900">{accreditedByLabel}</h2>
          <div className="flex flex-wrap gap-3">
            {accreditations.map((acc, idx) => (
              <div
                key={acc.id ?? idx}
                className="flex h-9 w-[72px] items-center justify-center rounded-[10px] border border-gray-200 bg-white p-1.5 shadow-sm"
              >
                {acc.logo ? (
                  <img
                    src={acc.logo}
                    alt={loc(acc.name, acc.ar_name) || "Accreditation"}
                    className="h-full w-full object-contain"
                    loading="lazy"
                  />
                ) : (
                  <span className="px-1 text-center text-[10px] font-medium text-slate-500">
                    {loc(acc.name, acc.ar_name)}
                  </span>
                )}
              </div>
            ))}
          </div>
        </div>
      ) : null}
    </section>
  );
}
