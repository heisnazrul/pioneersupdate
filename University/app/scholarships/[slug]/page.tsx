import Link from 'next/link';
import { notFound } from 'next/navigation';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faMapMarkerAlt, faCalendarAlt, faMoneyBillWave, faCheckCircle } from '@fortawesome/free-solid-svg-icons';
import Button from '@/components/ui/Button';
import { getScholarshipBySlug } from '@/lib/data';
import ScholarshipSidebar from '@/components/scholarships/ScholarshipSidebar';
import { getLang } from '@/lib/getLang';
import { t } from '@/lib/i18n';

interface Props {
    params: Promise<{ slug: string }>;
}

export async function generateMetadata(props: Props) {
    const params = await props.params;
    const scholarship = await getScholarshipBySlug(params.slug);
    if (!scholarship) return { title: 'Not Found' };

    return {
        title: `${scholarship.title} | Pioneers Admissions`,
        description: `Apply for ${scholarship.title}. Amount: ${scholarship.amount}. Deadline: ${scholarship.deadline}.`,
    };
}

export default async function ScholarshipDetailPage(props: Props) {
    const params = await props.params;
    const lang = await getLang();
    const scholarship = await getScholarshipBySlug(params.slug);

    if (!scholarship) {
        notFound();
    }

    // Default values for missing API fields
    const country = scholarship.country || "International";
    const type = scholarship.type || "All Levels";
    const description = scholarship.description || "This prestigious scholarship offers a life-changing opportunity for students to pursue their higher education goals.";

    return (
        <main className="bg-white min-h-screen pt-32 pb-20">
            <div className="container mx-auto px-4 md:px-8 lg:px-12 xl:px-20">
                {/* Breadcrumb */}
                <div className="mb-8 flex items-center gap-2 text-sm text-gray-500 flex-wrap">
                    <Link href="/" className="hover:text-blue-600">{t('common.home', lang)}</Link>
                    <span>/</span>
                    <Link href="/scholarships" className="hover:text-blue-600">{t('nav.scholarships', lang)}</Link>
                    <span>/</span>
                    <span className="text-gray-900 font-medium truncate max-w-[200px]">{scholarship.title}</span>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    {/* Main Content */}
                    <div className="lg:col-span-2">
                        <div className="bg-white border border-gray-100 rounded-3xl p-8 md:p-10 shadow-sm mb-8">
                            <span className="inline-block bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider mb-6">
                                {type}
                            </span>
                            <h1 className="text-3xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
                                {scholarship.title}
                            </h1>

                            <div className="flex flex-wrap gap-6 mb-8 text-gray-600">
                                <div className="flex items-center gap-2">
                                    <FontAwesomeIcon icon={faMapMarkerAlt} className="text-blue-400" />
                                    <span className="font-medium text-gray-900">{country}</span>
                                </div>
                                <div className="flex items-center gap-2">
                                    <FontAwesomeIcon icon={faCalendarAlt} className="text-blue-400" />
                                    <span className="font-medium text-gray-900">{t('scholarships.deadline', lang)}: {scholarship.deadline}</span>
                                </div>
                                <div className="flex items-center gap-2">
                                    <FontAwesomeIcon icon={faMoneyBillWave} className="text-green-500" />
                                    <span className="font-bold text-green-700">{scholarship.amount}</span>
                                </div>
                            </div>

                            <hr className="border-gray-100 mb-8" />

                            <div className="prose prose-lg max-w-none text-gray-600">
                                <h3 className="text-gray-900 font-bold mb-4">About the Scholarship</h3>
                                <p className="mb-6">{description}</p>

                                {scholarship.content && (
                                    <div dangerouslySetInnerHTML={{ __html: scholarship.content }} />
                                )}

                                {/* Fallback content if API specific fields are missing */}
                                {!scholarship.content && !scholarship.requirements && (
                                    <>
                                        <p className="mb-6">
                                            Recipients will join a global network of future leaders and change-makers. The program emphasizes academic excellence, leadership potential, and cross-cultural understanding.
                                        </p>

                                        <h3 className="text-gray-900 font-bold mb-4">Eligibility Criteria</h3>
                                        <ul className="space-y-2 mb-6 list-none pl-0">
                                            <li className="flex items-start gap-3">
                                                <FontAwesomeIcon icon={faCheckCircle} className="text-green-500 mt-1" />
                                                <span>Must be a citizen of an eligible country.</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <FontAwesomeIcon icon={faCheckCircle} className="text-green-500 mt-1" />
                                                <span>Hold an undergraduate degree with excellent academic records.</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <FontAwesomeIcon icon={faCheckCircle} className="text-green-500 mt-1" />
                                                <span>Meet the English language requirements (IELTS/TOEFL).</span>
                                            </li>
                                        </ul>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Sidebar / CTA */}
                    <div className="lg:col-span-1">
                        <ScholarshipSidebar scholarship={scholarship} />
                    </div>
                </div>
            </div>
        </main>
    );
}
