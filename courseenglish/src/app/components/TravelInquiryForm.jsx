"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faWhatsapp } from "@fortawesome/free-brands-svg-icons";
import { faPaperPlane } from "@fortawesome/free-solid-svg-icons";

export default function TravelInquiryForm({ inquiry = {}, isArabic = false }) {
    const dir = isArabic ? "rtl" : "ltr";
    const bullets = inquiry.bullets || [];
    return (
        <section id="inquiry-form" className="py-16 md:py-24" dir={dir}>
            <div className="container mx-auto px-4">
                <div className="mx-auto max-w-4xl overflow-hidden rounded-3xl bg-white shadow-xl sm:grid sm:grid-cols-2">

                    {/* Left Side: Contact Info & CTA */}
                    <div className="bg-[#0057B7] p-8 text-white sm:p-12 flex flex-col justify-between">
                        <div>
                            <h2 className="mb-4 text-3xl font-semibold">{inquiry.title}</h2>
                            <p className="mb-6 opacity-90">
                                {inquiry.description}
                            </p>
                            <div className="space-y-4">
                                {bullets.map((b, idx) => (
                                    <div key={idx} className="flex items-start gap-4">
                                        <div className="mt-1 h-2 w-2 rounded-full bg-white opacity-50"></div>
                                        <p className="font-normal">{b}</p>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <div className="mt-12">
                            {inquiry.whatsapp_text && (
                                <>
                                    <p className="mb-2 text-sm font-normal opacity-75">{isArabic ? "تفضل بالدردشة؟" : "Prefer to chat?"}</p>
                                    <a
                                        href={inquiry.whatsapp_url || "#"}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="flex items-center justify-center gap-2 rounded-xl bg-[#25D366] py-3 font-medium text-white transition hover:bg-[#20bd5a]"
                                    >
                                        <FontAwesomeIcon icon={faWhatsapp} className="h-5 w-5" />
                                        <span>{inquiry.whatsapp_text}</span>
                                    </a>
                                </>
                            )}
                        </div>
                    </div>

                    {/* Right Side: Form */}
                    <div className="p-8 sm:p-12">
                        <form className="space-y-5">
                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">{inquiry.form_name_label}</label>
                                <input
                                    type="text"
                                    placeholder={inquiry.form_name_placeholder}
                                    className="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-sm outline-none transition focus:border-[#0057B7] focus:bg-white"
                                />
                            </div>
                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">{inquiry.form_phone_label}</label>
                                <input
                                    type="tel"
                                    placeholder={inquiry.form_phone_placeholder}
                                    className="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-sm outline-none transition focus:border-[#0057B7] focus:bg-white"
                                />
                            </div>
                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">{inquiry.form_destination_label}</label>
                                <input
                                    type="text"
                                    placeholder={inquiry.form_destination_placeholder}
                                    className="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-sm outline-none transition focus:border-[#0057B7] focus:bg-white"
                                />
                            </div>
                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">{inquiry.form_date_label}</label>
                                <input
                                    type="date"
                                    className="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-sm outline-none transition focus:border-[#0057B7] focus:bg-white text-gray-500"
                                />
                            </div>
                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">{inquiry.form_message_label}</label>
                                <textarea
                                    rows="3"
                                    placeholder={inquiry.form_message_placeholder}
                                    className="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-sm outline-none transition focus:border-[#0057B7] focus:bg-white"
                                ></textarea>
                            </div>

                            <button className="flex w-full items-center justify-center gap-2 rounded-xl bg-[#0F172A] py-3 font-medium text-white transition hover:bg-[#1E293B]">
                                <span>{inquiry.form_submit_text}</span>
                                <FontAwesomeIcon icon={faPaperPlane} className="h-4 w-4" />
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </section>
    );
}
