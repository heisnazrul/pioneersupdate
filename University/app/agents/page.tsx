import Link from 'next/link';
import { getAgentInfo } from '@/lib/data';
import PartnerInquiryForm from '@/components/PartnerInquiryForm';
import Image from 'next/image';

export const metadata = {
    title: 'For Agents | Pioneers Admissions',
    description: 'Partner with us to grow your student recruitment business.',
};

export default async function AgentsPage() {
    const data = await getAgentInfo();

    return (
        <div className="pb-20">
            {/* Hero */}
            <section className="bg-primary text-white py-20 md:py-32 text-center relative overflow-hidden mb-16">
                <div className="container max-w-4xl mx-auto px-4 relative z-10">
                    <h1 className="text-4xl md:text-6xl font-extrabold mb-6 leading-tight tracking-tight">{data.heroTitle}</h1>
                    <p className="text-xl md:text-2xl opacity-90 mb-10 max-w-2xl mx-auto">{data.heroSubtitle}</p>
                    <Link href="#inquiry" className="inline-block bg-white text-primary px-10 py-4 rounded-full font-bold text-lg transition-transform hover:-translate-y-1 hover:shadow-lg">
                        Become a Partner
                    </Link>
                </div>
                <div className="absolute bottom-[-50px] left-0 w-full h-[100px] bg-white -skew-y-2 origin-bottom-right"></div>
            </section>

            {/* Benefits */}
            <section className="container max-w-6xl mx-auto px-4 py-16">
                <h2 className="text-3xl md:text-4xl font-extrabold text-primary text-center mb-12">Why Partner With Us?</h2>
                <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {data.benefits.map(benefit => (
                        <div key={benefit.id} className="text-center p-8 border border-gray-200 rounded-xl bg-slate-50 hover:bg-white hover:shadow-lg hover:border-secondary transition-all group">
                            <div className="text-4xl text-secondary mb-4 group-hover:scale-110 transition-transform inline-block">✦</div>
                            <h3 className="text-xl font-bold mb-3 text-foreground">{benefit.title}</h3>
                            <p className="text-muted leading-relaxed">{benefit.description}</p>
                        </div>
                    ))}
                </div>
            </section>

            {/* Process */}
            <section className="container max-w-6xl mx-auto px-4 py-16">
                <h2 className="text-3xl md:text-4xl font-extrabold text-primary text-center mb-12">How It Works</h2>
                <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                    {data.processSteps.map(step => (
                        <div key={step.id} className="text-center p-6 relative">
                            <div className="w-14 h-14 bg-accent text-white font-bold text-xl rounded-full flex items-center justify-center mx-auto mb-6 shadow-md border-4 border-white ring-1 ring-slate-100">{step.stepNumber}</div>
                            <h3 className="text-lg font-bold mb-2">{step.title}</h3>
                            <p className="text-sm text-muted">{step.description}</p>
                        </div>
                    ))}
                </div>
            </section>

            {/* Requirements */}
            <section className="bg-background py-20 my-16">
                <div className="container max-w-4xl mx-auto px-4">
                    <h2 className="text-3xl md:text-4xl font-extrabold text-primary text-center mb-12">Partnership Requirements</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {data.requirements.map((req, idx) => (
                            <div key={idx} className="bg-white px-6 py-4 rounded-full font-semibold text-primary flex items-center shadow-sm border border-slate-100">
                                <span className="text-green-700 mr-3 font-extrabold">✓</span>
                                {req}
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Testimonials */}
            {data.testimonials && data.testimonials.length > 0 && (
                <section className="container max-w-6xl mx-auto px-4 py-16">
                    <h2 className="text-3xl md:text-4xl font-extrabold text-primary text-center mb-12">What Our Partners Say</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {data.testimonials.map(test => (
                            <div key={test.id} className="bg-white p-8 rounded-xl border border-gray-200 hover:shadow-md transition-shadow">
                                <p className="italic text-foreground text-lg mb-6">&quot;{test.content}&quot;</p>
                                <div className="flex items-center gap-4">
                                    <Image src={test.imageUrl} alt={test.partnerName} width={50} height={50} className="rounded-full object-cover bg-slate-200" />
                                    <div>
                                        <h5 className="font-bold text-primary">{test.partnerName}</h5>
                                        <span className="text-sm text-muted">{test.company}</span>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </section>
            )}

            {/* Inquiry Form */}
            <section id="inquiry" className="container max-w-4xl mx-auto px-4 py-16">
                <div className="pt-8">
                    <PartnerInquiryForm />
                </div>
            </section>
        </div>
    );
}
