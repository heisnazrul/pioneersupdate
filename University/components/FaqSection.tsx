"use client";

import { useMemo, useState, useEffect } from "react";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronDown, faChevronUp, faArrowRight } from "@fortawesome/free-solid-svg-icons";

const BLUE = "#135AA3";
const BLUE_SOFT = "rgba(255,255,255,.14)";
const WHITE_80 = "rgba(255,255,255,.85)";
const RING = "rgba(255,255,255,.18)";
const SHADOW = "0 6px 18px rgba(0,0,0,.10)";

type NormalizedFaq = {
    id: string | number;
    cat: string;
    q: string;
    a: string;
};



function Pill({
    active,
    children,
    onClick,
}: {
    active: boolean;
    children: React.ReactNode;
    onClick: () => void;
}) {
    return (
        <button
            type="button"
            onClick={onClick}
            className={`h-10 rounded-full px-5 text-sm transition ${active ? "bg-white text-[#135AA3]" : "text-white"}`}
            style={{
                border: active ? "1px solid rgba(19,90,163,.08)" : `1px solid ${RING}`,
                background: active ? "white" : "transparent",
                boxShadow: active ? SHADOW : "none",
            }}
        >
            {children}
        </button>
    );
}

function QAItem({
    item,
    open,
    onToggle,
}: {
    item: NormalizedFaq;
    open: boolean;
    onToggle: () => void;
}) {
    return (
        <div className="rounded-2xl p-0.5" style={{ background: "transparent" }}>
            <button
                type="button"
                onClick={onToggle}
                aria-expanded={open}
                className="flex w-full items-center justify-between rounded-2xl px-5 py-4  text-white"
                style={{
                    background: BLUE_SOFT,
                    border: `1px solid ${RING}`,
                    boxShadow: SHADOW,
                }}
            >
                <span className="text-base font-medium md:text-[17px]">{item.q}</span>
                <FontAwesomeIcon icon={open ? faChevronUp : faChevronDown} className="text-white/90" />
            </button>

            {open && (
                <div
                    role="region"
                    className="mt-2 rounded-2xl px-5 py-4 text-[15px] leading-7"
                    style={{ background: "rgba(255,255,255,.10)", color: WHITE_80, border: `1px solid ${RING}` }}
                >
                    {item.a}
                </div>
            )}
        </div>
    );
}

interface FaqSectionProps {
    faqs?: any[];
    copy?: any;
    lang?: 'en' | 'ar';
}

export default function FaqSection({ faqs: initialFaqs = [], copy, lang: langProp }: FaqSectionProps) {
    const [cat, setCat] = useState("General");
    const [openId, setOpenId] = useState<string | number | null>(null);
    const [visible, setVisible] = useState(3);
    
    // Normalize FAQs if they come directly from the API shape
    const faqs: NormalizedFaq[] = useMemo(() => {
        return initialFaqs.map((item: any) => ({
            id: item.id,
            cat: item.category,
            q: item.question,
            a: item.answer,
        }));
    }, [initialFaqs]);

    const categories = useMemo(() => {
        return Array.from(new Set(faqs.map((f: any) => f.cat))) as string[];
    }, [faqs]);

    useEffect(() => {
        if (categories.length > 0 && !categories.includes("General")) {
            setCat(categories[0]);
        }
        if (faqs.length > 0 && openId === null) {
            setOpenId(faqs[0].id);
        }
    }, [categories, faqs, openId]);

    const list = useMemo(() => faqs.filter((f) => f.cat === cat), [faqs, cat]);

    useEffect(() => {
        setVisible(3);
        if (list.length) setOpenId(list[0].id);
    }, [cat, list]);

    const shown = list.slice(0, visible);

    if (!faqs.length) return null;

    return (
        <section className="py-14 md:py-20" style={{ background: BLUE }}>
            <div className="grid grid-cols-1 gap-4 md:gap-10 xl:gap-20 px-4 md:grid-cols-2 md:px-10 xl:px-40 2xl:px-60">
                <div className="order-1 md:order-none">
                    <h2 className="text-4xl font-extrabold text-white md:text-5xl">{copy?.heading || (langProp === 'ar' ? 'أسئلة وأجوبة' : 'Questions & Answers')}</h2>
                    <p className="mt-4 max-w-xl text-white/80">
                        {copy?.subheading || (langProp === 'ar' ? 'نساعدك على اتخاذ قرارك بثقة. هذه أكثر الأسئلة شيوعاً التي يطرحها الطلاب.' : 'We help you decide with confidence. These are the most common questions prospective students ask us.')}
                    </p>

                    {categories.length > 0 && (
                        <div className="mt-6 flex flex-wrap gap-2 md:gap-3">
                            {categories.map((c) => (
                                <Pill key={c} active={c === cat} onClick={() => setCat(c)}>
                                    {c}
                                </Pill>
                            ))}
                        </div>
                    )}

                    <div className="mt-6 md:mt-16">
                        <h3 className="text-2xl font-bold text-white">{copy?.cta_title || (langProp === 'ar' ? 'هل لديك سؤال آخر؟' : 'Still have a question?')}</h3>
                        <p className="mt-1 text-white/80">{copy?.cta_text || (langProp === 'ar' ? 'نحن هنا لمساعدتك.' : 'We’re here to help you.')}</p>

                        <Link
                            href="/contact"
                            className="mt-5 grid h-12 w-12 place-items-center rounded-full bg-white text-[#135AA3] transition hover:opacity-90"
                            aria-label="Contact support"
                            title="Contact support"
                            style={{ boxShadow: SHADOW }}
                        >
                            <FontAwesomeIcon icon={faArrowRight} />
                        </Link>
                    </div>
                </div>

                <div className="space-y-4 xl:px-10">
                    {shown.map((it) => (
                        <QAItem
                            key={it.id}
                            item={it}
                            open={openId === it.id}
                            onToggle={() => setOpenId(openId === it.id ? -1 : it.id)}
                        />
                    ))}

                    {visible < list.length && (
                        <button
                            type="button"
                            onClick={() => setVisible((v) => v + 3)}
                            className="mt-2 w-full rounded-2xl bg-white px-2 py-4 text-center text-[15px] font-medium text-[#0F172A] transition hover:opacity-95"
                            style={{ boxShadow: SHADOW }}
                        >
                            {copy?.show_more || (langProp === 'ar' ? 'عرض المزيد' : 'Show more')}
                        </button>
                    )}
                </div>
            </div>
        </section>
    );
}
