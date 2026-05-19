"use client";

import { useState } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPlus, faMinus } from '@fortawesome/free-solid-svg-icons';
import SectionHeading from './ui/SectionHeading';

interface FAQItem {
    question: string;
    answer: string;
}

interface GuidesFAQProps {
    data?: {
        title: string;
        subtitle: string;
        description: string;
        items: FAQItem[];
        cta?: {
            title: string;
            description: string;
            btn_text: string;
            btn_link: string;
        };
    };
}

export default function GuidesFAQ({ data }: GuidesFAQProps) {
    const [openIndex, setOpenIndex] = useState<number | null>(0);

    // Default static fallback if data isn't provided
    const items = data?.items || [
        {
            question: "How long does the study abroad application process take?",
            answer: "Typically, it takes 6-12 months. This includes researching universities, preparing for standardized tests (IELTS/TOEFL), gathering documents, applying for admission, and finally the visa process. We recommend starting at least a year in advance."
        }
    ];

    const title = data?.title || "Frequently Asked Questions";
    const subtitle = data?.subtitle || "Common Questions";
    const description = data?.description || "Have questions? We have answers. If you can't find what you're looking for, feel free to contact our expert team.";

    const ctaTitle = data?.cta?.title || "Still have questions?";
    const ctaDesc = data?.cta?.description || "Our counselors are ready to help you with your specific study abroad queries.";
    const ctaBtnText = data?.cta?.btn_text || "Contact Us";
    const ctaBtnLink = data?.cta?.btn_link || "/contact";

    const toggleFAQ = (index: number) => {
        setOpenIndex(openIndex === index ? null : index);
    };

    return (
        <section className="py-24 bg-gray-50">
            <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50">
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    <div className="lg:col-span-5">
                        <SectionHeading
                            subtitle={subtitle}
                            title={title}
                            description={description}
                            align="left"
                            className="mb-8"
                        />
                        {/* Decorative or CTA Image could go here */}
                        <div className="hidden lg:block p-8 bg-blue-600 rounded-2xl text-white mt-8 shadow-xl relative overflow-hidden">
                            <div className="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-8 -mt-8"></div>
                            <h4 className="text-xl font-bold mb-4">{ctaTitle}</h4>
                            <p className="mb-6 opacity-90">{ctaDesc}</p>
                            <a href={ctaBtnLink} className="block text-center bg-white text-blue-600 px-6 py-3 rounded-lg font-bold hover:bg-blue-50 transition-colors w-full">
                                {ctaBtnText}
                            </a>
                        </div>
                    </div>

                    <div className="lg:col-span-7">
                        <div className="space-y-4">
                            {items.map((faq, idx) => (
                                <div
                                    key={idx}
                                    className={`bg-white rounded-xl border transition-all duration-300 ${openIndex === idx ? 'border-blue-500 shadow-md ring-1 ring-blue-100' : 'border-gray-200 hover:border-blue-300'}`}
                                >
                                    <button
                                        onClick={() => toggleFAQ(idx)}
                                        className="w-full flex items-center justify-between p-6  focus:outline-none"
                                    >
                                        <span className={`font-bold text-lg ${openIndex === idx ? 'text-blue-700' : 'text-gray-800'}`}>
                                            {faq.question}
                                        </span>
                                        <span className={`flex-shrink-0 ml-4 w-8 h-8 rounded-full flex items-center justify-center transition-colors ${openIndex === idx ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-400'}`}>
                                            <FontAwesomeIcon icon={openIndex === idx ? faMinus : faPlus} className="text-sm" />
                                        </span>
                                    </button>

                                    <div
                                        className={`overflow-hidden transition-all duration-300 ease-in-out ${openIndex === idx ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0'}`}
                                    >
                                        <div className="p-6 pt-0 text-gray-600 leading-relaxed border-t border-transparent">
                                            {faq.answer}
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
