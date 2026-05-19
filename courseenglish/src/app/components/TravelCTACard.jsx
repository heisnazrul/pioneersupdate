"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faWhatsapp } from "@fortawesome/free-brands-svg-icons";
import { faPhone, faEnvelope } from "@fortawesome/free-solid-svg-icons";

export default function TravelCTACard({ cta = {}, isArabic = false }) {
    const dir = isArabic ? "rtl" : "ltr";
    return (
        <section className="relative z-20 -mt-16 px-4" dir={dir}>
            <div className="container mx-auto">
                <div className="mx-auto max-w-5xl rounded-3xl bg-white p-8 shadow-xl border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">

                    {/* Text Content */}
                    <div className={isArabic ? "text-center md:text-right" : "text-center md:text-left"}>
                        <h3 className="text-2xl font-semibold text-slate-900">{cta.heading}</h3>
                        <p className="mt-1 text-slate-500 font-normal">{cta.subheading}</p>
                    </div>

                    {/* Action Buttons */}
                    <div className="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                        {cta.whatsapp_text && (
                            <a
                                href={cta.whatsapp_url || "#"}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="flex items-center justify-center gap-2 rounded-xl bg-[#25D366] px-6 py-3 font-medium text-white transition hover:bg-[#20bd5a]"
                            >
                                <FontAwesomeIcon icon={faWhatsapp} className="h-5 w-5" />
                                <span>{cta.whatsapp_text}</span>
                            </a>
                        )}

                        {cta.call_text && (
                            <a
                                href={cta.call_url || "#"}
                                className="flex items-center justify-center gap-2 rounded-xl bg-slate-100 px-6 py-3 font-medium text-slate-700 transition hover:bg-slate-200"
                            >
                                <FontAwesomeIcon icon={faPhone} className="h-4 w-4" />
                                <span>{cta.call_text}</span>
                            </a>
                        )}

                        {cta.inquire_text && (
                            <a
                                href={cta.inquire_url || "#inquiry-form"}
                                className="flex items-center justify-center gap-2 rounded-xl border-2 border-[#0057B7] px-6 py-3 font-medium text-[#0057B7] transition hover:bg-[#0057B7] hover:text-white"
                            >
                                <FontAwesomeIcon icon={faEnvelope} className="h-4 w-4" />
                                <span>{cta.inquire_text}</span>
                            </a>
                        )}
                    </div>

                </div>
            </div>
        </section>
    );
}
