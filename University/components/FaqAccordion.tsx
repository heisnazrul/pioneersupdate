"use client";

import { useState } from 'react';
import { Faq } from '@/lib/data/types';

export default function FaqAccordion({ faqs }: { faqs: Faq[] }) {
    const [openId, setOpenId] = useState<string | null>(null);

    const toggle = (id: string) => {
        setOpenId(openId === id ? null : id);
    };

    return (
        <div className="max-w-3xl mx-auto">
            {faqs.map((faq) => (
                <div key={faq.id} className="border-b border-gray-200 mb-4 last:border-0">
                    <button
                        className="w-full py-5 flex justify-between items-center text-lg font-semibold text-foreground hover:text-primary transition-colors"
                        onClick={() => toggle(faq.id)}
                        aria-expanded={openId === faq.id}
                    >
                        {faq.question}
                        <span className={`transform transition-transform duration-200 ${openId === faq.id ? 'rotate-180' : ''}`}>
                            {openId === faq.id ? '−' : '+'}
                        </span>
                    </button>
                    <div className={`overflow-hidden transition-all duration-300 ${openId === faq.id ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0'}`}>
                        <div className="text-muted leading-relaxed pb-6">
                            {faq.answer}
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
}
