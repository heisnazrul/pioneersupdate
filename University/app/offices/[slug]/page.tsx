import { getCmsPage } from '@/services/cms';
import { getOffice } from '@/services/offices';
import { notFound } from 'next/navigation';
import Image from 'next/image';
import Link from 'next/link';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    faMapMarkerAlt,
    faPhone,
    faEnvelope,
    faClock,
    faCheckCircle,
    faCalendarCheck,
    faArrowRight
} from '@fortawesome/free-solid-svg-icons';
import Button from '@/components/ui/Button';

export async function generateMetadata({ params }: { params: Promise<{ slug: string }> }) {
    const { slug } = await params;
    const office = await getOffice(slug);

    if (!office) return { title: 'Office Not Found' };

    return {
        title: `${office.city} Office | Pioneers Admissions`,
        description: `Visit our ${office.city} office for expert study abroad counseling.`,
    };
}

export default async function OfficeDetailsPage({ params }: { params: Promise<{ slug: string }> }) {
    const { slug } = await params;
    const [office, aboutPage] = await Promise.all([
        getOffice(slug),
        getCmsPage('about')
    ]);

    if (!office) {
        notFound();
    }

    // Reuse general team and shuffle/slice for mock "local team" if no local team data
    // aboutPage might be mock data, ensure it has team
    const localTeam = aboutPage?.content?.team?.members ? aboutPage.content.team.members.slice(0, 2) : [];

    return (
        <main className="bg-white min-h-screen pb-20">
            {/* Hero Section */}
            <div className="relative h-[50vh] min-h-[400px] w-full bg-slate-900 overflow-hidden">
                {office.image && (
                    <Image
                        src={office.image}
                        alt={office.city}
                        fill
                        className="object-cover opacity-60"
                        priority
                        unoptimized
                    />
                )}
                {/* Fallback image if no office image */}
                {!office.image && (
                    <Image
                        src="/hero.png"
                        alt={office.city}
                        fill
                        className="object-cover opacity-60"
                        priority
                    />
                )}

                <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                <div className="absolute bottom-0 left-0 right-0 p-8 md:p-16 xl:p-24 2xl:px-50 text-white">
                    <div className="animate-fade-in-up">
                        <span className="inline-block py-1 px-3 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-sm font-bold uppercase tracking-wider mb-4">
                            {office.type}
                        </span>
                        <h1 className="text-5xl md:text-7xl font-extrabold mb-4">{office.city}</h1>
                        <p className="text-xl md:text-2xl opacity-90 max-w-2xl font-light">
                            {office.country}
                        </p>
                    </div>
                </div>
            </div>

            <div className="px-4 md:px-10 xl:px-30 2xl:px-50 -mt-10 relative z-10 flex flex-col lg:flex-row gap-12">
                {/* Main Content */}
                <div className="flex-1">
                    <div className="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-gray-100 mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-6">About this Office</h2>
                        <p className="text-lg text-gray-600 leading-relaxed mb-8">
                            {office.description}
                            {" This office serves as a dedicated hub for students in the region, providing personalized counseling, visa application support, and pre-departure briefings. Our expert team is committed to making your study abroad journey seamless and successful."}
                        </p>

                        <h3 className="text-xl font-bold text-gray-900 mb-4">Services Available Here</h3>
                        <ul className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {['University Admissions', 'Visa Application Filing', 'IELTS/TOEFL Coaching', 'Accommodation Assistance', 'Scholarship Guidance', 'Pre-Departure Briefing'].map((service, idx) => (
                                <li key={idx} className="flex items-center gap-3 text-gray-700 font-medium">
                                    <FontAwesomeIcon icon={faCheckCircle} className="text-green-500" />
                                    {service}
                                </li>
                            ))}
                        </ul>
                    </div>

                    {/* Team Section */}
                    {localTeam.length > 0 && (
                        <div className="mb-12">
                            <h2 className="text-3xl font-bold text-gray-900 mb-8">Meet the Team</h2>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {localTeam.map((member: any) => (
                                    <div key={member.name} className="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-lg transition-all flex items-center">
                                        <div className="relative w-32 h-full min-h-[120px] bg-gray-200">
                                            <Image src={member.image} alt={member.name} fill className="object-cover" unoptimized />
                                        </div>
                                        <div className="p-6">
                                            <h4 className="text-lg font-bold text-gray-900 mb-1">{member.name}</h4>
                                            <p className="text-sm text-blue-600 font-medium uppercase tracking-wide mb-2">{member.role}</p>
                                            <p className="text-xs text-gray-500 line-clamp-2">{member.bio || "Experinced Education Counselor"}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>

                {/* Sidebar */}
                <div className="w-full lg:w-[400px] shrink-0 space-y-8">
                    {/* Quick Info Card */}
                    <div className="bg-white rounded-3xl p-8 shadow-lg border border-gray-100 sticky top-24">
                        <h3 className="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">Contact Information</h3>

                        <div className="space-y-6">
                            <div className="flex gap-4">
                                <div className="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                                    <FontAwesomeIcon icon={faMapMarkerAlt} />
                                </div>
                                <div>
                                    <p className="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Address</p>
                                    <p className="text-gray-800 font-medium leading-snug">{office.address}</p>
                                    {office.map_url && (
                                        <a
                                            href={office.map_url}
                                            target="_blank"
                                            className="text-xs text-blue-600 font-bold mt-2 inline-block hover:underline"
                                        >
                                            View on Map
                                        </a>
                                    )}
                                </div>
                            </div>

                            <div className="flex gap-4">
                                <div className="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 shrink-0">
                                    <FontAwesomeIcon icon={faPhone} />
                                </div>
                                <div>
                                    <p className="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Phone</p>
                                    <a href={`tel:${office.phone}`} className="text-gray-800 font-medium hover:text-blue-600 transition-colors">{office.phone}</a>
                                </div>
                            </div>

                            <div className="flex gap-4">
                                <div className="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 shrink-0">
                                    <FontAwesomeIcon icon={faEnvelope} />
                                </div>
                                <div>
                                    <p className="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Email</p>
                                    <a href={`mailto:${office.email}`} className="text-gray-800 font-medium hover:text-blue-600 transition-colors">{office.email}</a>
                                </div>
                            </div>

                            <div className="flex gap-4">
                                <div className="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 shrink-0">
                                    <FontAwesomeIcon icon={faClock} />
                                </div>
                                <div>
                                    <p className="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Opening Hours</p>
                                    <p className="text-gray-800 font-medium">{office.hours || "9:00 AM - 6:00 PM (Mon-Sat)"}</p>
                                </div>
                            </div>
                        </div>

                        <div className="mt-8 pt-6 border-t border-gray-100 flex flex-col gap-6">
                            {/* Map Placeholder */}
                            <div className="bg-gray-200 rounded-2xl h-64 w-full flex items-center justify-center text-gray-400 font-bold shadow-inner">
                                <div className="text-center">
                                    <FontAwesomeIcon icon={faMapMarkerAlt} size="2x" className="mb-2 opacity-50" />
                                    <p>Map View</p>
                                </div>
                            </div>

                            <Link href="/contact" className="block w-full">
                                <Button className="w-full rounded-xl py-4 flex items-center justify-center gap-2">
                                    <FontAwesomeIcon icon={faCalendarCheck} />
                                    Book Appointment
                                </Button>
                            </Link>
                            <p className="text-center text-xs text-gray-400 mt-0">
                                Walk-ins are welcome during business hours.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    );
}
