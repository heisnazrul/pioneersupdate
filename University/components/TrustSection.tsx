"use client";

import { motion } from 'framer-motion';
import SectionHeading from '@/components/ui/SectionHeading';

const BENEFITS_EN = [
    { id: 1, title: "100% Free Service", description: "We do not charge students any fees for counseling or application processing.", icon: "gift" },
    { id: 2, title: "100% Transparency", description: "No hidden costs or bias. We help you choose what is truly best for you.", icon: "eye" },
    { id: 3, title: "Expert Counselors", description: "Our team comprises alumni from top global universities.", icon: "user-check" },
    { id: 4, title: "98% Visa Success", description: "Proven track record of success in difficult cases.", icon: "check-circle" },
    { id: 5, title: "End-to-End Support", description: "From counseling to pre-departure briefing.", icon: "globe" },
];

const BENEFITS_AR = [
    { id: 1, title: "خدمة مجانية 100%", description: "لا نفرض على الطلاب أي رسوم مقابل الاستشارة أو معالجة الطلبات.", icon: "gift" },
    { id: 2, title: "شفافية 100%", description: "لا تكاليف خفية ولا تحيز. نساعدك على اختيار ما هو الأفضل لك.", icon: "eye" },
    { id: 3, title: "مستشارون متخصصون", description: "فريقنا من خريجي أبرز الجامعات العالمية.", icon: "user-check" },
    { id: 4, title: "نجاح التأشيرة 98%", description: "سجل حافل من النجاح حتى في الحالات الصعبة.", icon: "check-circle" },
    { id: 5, title: "دعم متكامل", description: "من الاستشارة الأولى حتى إحاطة ما قبل السفر.", icon: "globe" },
];

interface TrustSectionProps {
    copy?: any;
    lang?: 'en' | 'ar';
}

export default function TrustSection({ copy, lang = 'en' }: TrustSectionProps) {
    const isAr = lang === 'ar';
    const defaultBenefits = isAr ? BENEFITS_AR : BENEFITS_EN;
    const benefits = Array.isArray(copy?.items) && copy.items.length > 0 ? copy.items : defaultBenefits;

    return (
        <section className="py-24 bg-white relative overflow-hidden">
            {/* Background Pattern */}
            <div className="absolute inset-0 opacity-[0.03] pointer-events-none"
                style={{
                    backgroundImage: 'radial-gradient(#135FAE 1px, transparent 1px)',
                    backgroundSize: '24px 24px'
                }}>
            </div>

            <div className="w-full px-4 md:px-10 xl:px-20 2xl:px-40 relative z-10">
                <SectionHeading
                    title={copy?.title || (isAr ? 'لماذا يثق بنا الطلاب' : 'Why Students Trust Us')}
                    subtitle={copy?.subtitle || (isAr ? 'قيمتنا' : 'OUR VALUE')}
                    align="center"
                    className="mb-16"
                />

                <div className="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-6 md:gap-8">
                    {benefits.map((benefit: any, idx: number) => (
                        <motion.div
                            key={benefit.id || idx}
                            initial={{ opacity: 0, y: 20 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            viewport={{ once: true }}
                            transition={{ delay: idx * 0.1, duration: 0.5 }}
                            className="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 h-full flex flex-col items-center text-center group"
                        >
                            <div className="w-16 h-16 bg-blue-50/80 rounded-full flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-blue-100 transition-all duration-300">
                                {
                                    benefit.icon === 'gift' ? '🎁' :
                                        benefit.icon === 'eye' ? '👁️' :
                                            benefit.icon === 'user-check' ? '👨‍🏫' :
                                                benefit.icon === 'check-circle' ? '✅' :
                                                    benefit.icon === 'globe' ? '🌐' : '✨'
                                }
                            </div>
                            <h3 className="text-lg font-bold text-[#0B3D66] mb-3 group-hover:text-[#135FAE] transition-colors">
                                {benefit.title}
                            </h3>
                            <p className="text-gray-500 text-sm leading-relaxed">
                                {benefit.description}
                            </p>
                        </motion.div>
                    ))}
                </div>
            </div>
        </section>
    );
}
