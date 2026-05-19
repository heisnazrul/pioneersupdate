import Image from "next/image";
import Link from "next/link";
import { getCmsPage } from '@/services/cms';
import { getLang } from '@/lib/getLang';
import Button from "@/components/ui/Button";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    faBed,
    faWifi,
    faMapMarkerAlt,
    faCheckCircle,
    faBolt,
    faShieldAlt,
    faUsers,
    faMapMarkedAlt,
    faArrowRight
} from '@fortawesome/free-solid-svg-icons';
import SectionHeading from "@/components/ui/SectionHeading";

// Map for fixed amenities icons to maintain design quality
const amenitiesIcons = [faBolt, faShieldAlt, faMapMarkedAlt, faWifi, faBed, faUsers];
const amenitiesColors = [
    "bg-yellow-50 text-yellow-600",
    "bg-blue-50 text-blue-600",
    "bg-red-50 text-red-600",
    "bg-indigo-50 text-indigo-600",
    "bg-green-50 text-green-600",
    "bg-purple-50 text-purple-600"
];

interface AccommodationRoom {
    id: number;
    title: string;
    slug: string;
    description: string;
    price: string;
    location: string;
    image_url: string;
    features: string[] | string;
    is_active: boolean;
}

interface CmsContent {
    hero?: {
        badge: string;
        title: string;
        description: string;
    };
    why_choose_us?: {
        title: string;
        items: Array<{
            title: string;
            description: string;
        }>;
    };
    booking_process?: {
        title: string;
        steps: Array<{
            number: string;
            title: string;
            description: string;
        }>;
    };
    cta?: {
        title: string;
        description: string;
        button_text: string;
        button_link: string;
    };
}

export async function generateMetadata() {
    const lang = await getLang();
    const page = await getCmsPage('accommodation', lang);
    return {
        title: page?.meta_title || (lang === 'ar' ? 'السكن الطلابي | Pioneers Admissions' : 'Student Accommodation | Pioneers Admissions'),
        description: page?.meta_description || (lang === 'ar' ? 'اعثر على مسكنك المثالي بالقرب من جامعتك.' : 'Find your perfect student housing near your university.')
    };
}

export default async function AccommodationPage() {
    const lang = await getLang();
    const pageData = await getCmsPage('accommodation', lang);
    const cmsContent = (pageData?.content || {}) as CmsContent;

    let rooms: AccommodationRoom[] = [];
    try {
        const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000';
        const roomsRes = await fetch(`${apiUrl}/api/accommodation-rooms`, {
            next: { revalidate: 60 }
        });
        const roomsJson = await roomsRes.json();

        let roomsData = [];
        if (Array.isArray(roomsJson)) {
            roomsData = roomsJson;
        } else if (roomsJson.status === 'success' && Array.isArray(roomsJson.data)) {
            roomsData = roomsJson.data;
        } else if (roomsJson.data && Array.isArray(roomsJson.data)) {
            roomsData = roomsJson.data;
        }

        rooms = roomsData.map((room: any) => ({
            ...room,
            image_url: room.image_url || room.image || ''
        }));
    } catch (error) {
        console.error("Failed to fetch rooms data", error);
    }

    const { hero, why_choose_us, booking_process, cta } = cmsContent;

    return (
        <main className="pb-20">
            {/* Hero Section - Restored Premium Design */}
            <section className="relative bg-[#001f3f] text-white pt-32 pb-24 overflow-hidden">
                <div className="absolute inset-0">
                    <Image src="/assets/accommodation/hero.png" alt="Student Accommodation" fill className="object-cover" priority />
                    <div className="absolute inset-0 bg-gradient-to-r from-[#001f3f]/90 via-[#001f3f]/70 to-[#001f3f]/40"></div>
                </div>

                <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50 relative z-10">
                    <div className="flex flex-col md:flex-row items-center gap-12">
                        <div className="w-full md:w-1/2 text-left">
                            <span className="inline-block py-1 px-3 rounded-full bg-teal-500/20 text-teal-200 text-sm font-bold tracking-wide uppercase mb-4 border border-teal-500/30">
                                {hero?.badge || "Student Housing"}
                            </span>
                            <h1 className="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                                {hero?.title || "Find Your Home Away From Home"}
                            </h1>
                            <p className="text-lg text-gray-300 mb-8 leading-relaxed max-w-xl">
                                {hero?.description || "Safe, affordable, and comfortable accommodation options near top universities."}
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4">
                                <Button href="/contact" variant="primary" size="lg" className="!bg-[#135FAE] hover:!bg-[#0e4b8a] shadow-xl px-10 border-0">
                                    Find Accommodation
                                </Button>
                                <Button href="#options" variant="outline" size="lg" className="border-white/30 text-white hover:bg-white/10 hover:border-white">
                                    View Options
                                </Button>
                            </div>
                        </div>

                        {/* Restored Rotating Card */}
                        <div className="w-full md:w-1/2 relative hidden md:block">
                            <div className="relative rounded-2xl p-2 bg-white/10 backdrop-blur-md border border-white/20 transform hover:-translate-y-2 transition-transform duration-500 hover:rotate-0 rotate-2">
                                <Image
                                    src="/assets/accommodation/ensuite.png"
                                    alt="Modern Room"
                                    width={600}
                                    height={400}
                                    className="rounded-xl w-full object-cover aspect-video shadow-2xl"
                                />
                                <div className="absolute bottom-6 left-6 right-6">
                                    <div className="bg-white rounded-lg p-4 shadow-lg flex items-center justify-between">
                                        <div>
                                            <div className="text-xs text-gray-500 uppercase font-bold tracking-wider">Starting From</div>
                                            <div className="text-xl font-extrabold text-[#135FAE]">£150<span className="text-sm font-normal text-gray-400">/week</span></div>
                                        </div>
                                        <div className="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                            <FontAwesomeIcon icon={faCheckCircle} />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Restored Amenities Section (Why Choose Us) */}
            {why_choose_us && (
                <section className="py-24 bg-gray-50">
                    <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50">
                        <SectionHeading
                            subtitle="Living Experience"
                            title={why_choose_us.title}
                            description="We partner with providers that offer all-inclusive packages so you can focus on your studies."
                        />

                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {why_choose_us.items.map((item, idx) => {
                                // Cycle through icons and colors to maintain variety
                                const Icon = amenitiesIcons[idx % amenitiesIcons.length];
                                const colorClass = amenitiesColors[idx % amenitiesColors.length];

                                return (
                                    <div key={idx} className="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group hover:-translate-y-1">
                                        <div className={`w-14 h-14 rounded-xl ${colorClass} flex items-center justify-center mb-6 group-hover:scale-110 transition-transform`}>
                                            <FontAwesomeIcon icon={Icon} className="text-2xl" />
                                        </div>
                                        <h3 className="text-xl font-bold text-gray-900 mb-3">{item.title}</h3>
                                        <p className="text-gray-600 leading-relaxed text-sm">
                                            {item.description}
                                        </p>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </section>
            )}

            {/* Room Options (Dynamic List) */}
            <section id="options" className="py-24 bg-white">
                <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50">
                    <SectionHeading
                        subtitle="Choices"
                        title="Choose Your Space"
                        description="From private studios to social shared apartments, pick what suits your lifestyle."
                    />

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                        {rooms.map((room, idx) => {
                            let featuresList: string[] = [];
                            if (typeof room.features === 'string') {
                                try { featuresList = JSON.parse(room.features); } catch (e) { featuresList = room.features.split(',').map(s => s.trim()); }
                            } else if (Array.isArray(room.features)) {
                                featuresList = room.features;
                            }

                            return (
                                <div key={idx} className="group rounded-2xl overflow-hidden border border-gray-100 shadow-lg hover:shadow-2xl transition-all duration-300 bg-white">
                                    <div className="relative h-64 overflow-hidden">
                                        <Image
                                            src={room.image_url || '/placeholder-room.jpg'}
                                            alt={room.title}
                                            fill
                                            className="object-cover group-hover:scale-105 transition-transform duration-700"
                                            unoptimized={true}
                                        />
                                        <div className="absolute top-4 right-4 bg-white/90 backdrop-blur text-[#135FAE] px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                                            {room.price}
                                        </div>
                                    </div>
                                    <div className="p-6">
                                        <div className="mb-4">
                                            <h3 className="text-xl font-bold text-gray-900 mb-1">{room.title}</h3>
                                            <span className="text-sm text-gray-400 flex items-center">
                                                <FontAwesomeIcon icon={faMapMarkerAlt} className="mr-1" /> {room.location}
                                            </span>
                                        </div>

                                        <p className="text-gray-500 text-sm mb-6 line-clamp-2">{room.description}</p>

                                        <div className="space-y-2 mb-8">
                                            {featuresList.slice(0, 3).map((feat, fIdx) => (
                                                <div key={fIdx} className="flex items-center text-sm text-gray-600">
                                                    <FontAwesomeIcon icon={faCheckCircle} className="text-green-500 mr-2 w-4" />
                                                    {feat}
                                                </div>
                                            ))}
                                        </div>

                                        <Button href={`/services/accommodation/${room.slug}`} variant="outline" fullWidth className="hover:bg-[#135FAE] hover:text-white hover:border-[#135FAE]">
                                            Check Availability
                                        </Button>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </div>
            </section>

            {/* Restored "How It Works" Design */}
            {booking_process && (
                <section className="py-24 bg-[#001f3f] text-white relative overflow-hidden">
                    <div className="container mx-auto px-4 relative z-10 text-center">
                        <SectionHeading
                            title={booking_process.title}
                            subtitle="Process"
                            align="center"
                            className="text-white mb-16"
                        />

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto mb-16 text-left md:text-center">
                            {booking_process.steps.map((step, idx) => (
                                <div key={idx} className="relative group">
                                    <div className="text-[120px] font-black text-white/5 absolute -top-10 -left-4 z-0 group-hover:text-white/10 transition-colors duration-500">
                                        {step.number}
                                    </div>
                                    <div className="relative z-10 pt-8 pl-4">
                                        <h4 className="text-2xl font-bold mb-3 text-white group-hover:text-teal-400 transition-colors">{step.title}</h4>
                                        <p className="text-blue-200 text-sm leading-relaxed">{step.description}</p>
                                    </div>
                                </div>
                            ))}
                        </div>

                        <Button href="/contact" variant="primary" size="lg" className="!bg-[#135FAE] hover:!bg-[#0e4b8a] rounded-full px-12 py-4 shadow-xl border-0">
                            Start Your Search
                        </Button>
                    </div>
                </section>
            )}

            {/* CTA Section */}
            {cta && (
                <section className="py-24 bg-white text-center">
                    <div className="container mx-auto px-4 md:px-10">
                        <div className="max-w-3xl mx-auto bg-blue-50 rounded-3xl p-12 border border-blue-100">
                            <h2 className="text-3xl font-bold mb-6 text-gray-900">{cta.title}</h2>
                            <p className="text-gray-600 mb-10 text-lg">
                                {cta.description}
                            </p>
                            <Link href={cta.button_link}>
                                <Button className="bg-[#135FAE] text-white hover:bg-[#0e4b8a] rounded-full px-10 py-5 text-xl font-bold shadow-xl">
                                    {cta.button_text}
                                </Button>
                            </Link>
                        </div>
                    </div>
                </section>
            )}
        </main>
    );
}
