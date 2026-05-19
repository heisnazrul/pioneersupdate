"use client";

import { useEffect, useState } from 'react';
import Image from 'next/image';
import { useParams } from 'next/navigation';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCheckCircle, faArrowLeft } from '@fortawesome/free-solid-svg-icons';
import SectionHeading from '@/components/ui/SectionHeading';
import Button from '@/components/ui/Button';
import { getAccommodationRoomBySlug } from '@/lib/api';

export default function AccommodationRoomPage() {
    const params = useParams();
    const slug = params.slug as string;
    const [room, setRoom] = useState<any>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        async function fetchRoom() {
            if (slug) {
                const data = await getAccommodationRoomBySlug(slug);
                setRoom(data);
            }
            setLoading(false);
        }
        fetchRoom();
    }, [slug]);

    if (loading) {
        return (
            <main className="pt-32 pb-20 container mx-auto px-4 min-h-screen text-center">
                <p>Loading details...</p>
            </main>
        );
    }

    if (!room) {
        return (
            <main className="pt-32 pb-20 container mx-auto px-4 min-h-screen text-center">
                <h1 className="text-3xl font-bold mb-4">Room Not Found</h1>
                <Button href="/services/accommodation" variant="primary">Back to Accommodation</Button>
            </main>
        );
    }

    return (
        <main className="pb-20 pt-32 bg-gray-50 min-h-screen">
            <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50">
                <Button href="/services/accommodation" variant="outline" className="mb-8 border-gray-300 text-gray-600 hover:bg-gray-100">
                    <FontAwesomeIcon icon={faArrowLeft} className="mr-2" />
                    Back to Options
                </Button>

                <div className="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                    <div className="grid grid-cols-1 lg:grid-cols-2">
                        {/* Image Section */}
                        <div className="relative h-64 lg:h-auto min-h-[400px]">
                            <Image
                                src={room.image ? `${process.env.NEXT_PUBLIC_BACKEND_URL}${room.image}` : '/assets/placeholder.png'}
                                alt={room.title}
                                fill
                                className="object-cover"
                                unoptimized
                            />
                            <div className="absolute top-6 left-6 bg-white/95 backdrop-blur-sm px-4 py-2 rounded-lg shadow-sm">
                                <span className="text-sm font-bold text-gray-500 uppercase tracking-wider block mb-1">Price</span>
                                <span className="text-xl font-extrabold text-[#135FAE]">{room.price}</span>
                            </div>
                        </div>

                        {/* Content Section */}
                        <div className="p-8 lg:p-12 flex flex-col justify-center">
                            <h1 className="text-3xl md:text-4xl font-extrabold text-[#001f3f] mb-4">{room.title}</h1>
                            <p className="text-lg text-gray-600 mb-8 leading-relaxed">
                                {room.description}
                            </p>

                            <div className="mb-8">
                                <h3 className="text-lg font-bold text-gray-900 mb-4">Key Features</h3>
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    {room.features && Array.isArray(room.features) && room.features.map((feat: string, idx: number) => (
                                        <div key={idx} className="flex items-center text-gray-700 bg-gray-50 p-2 rounded-lg">
                                            <FontAwesomeIcon icon={faCheckCircle} className="text-teal-500 mr-3" />
                                            {feat}
                                        </div>
                                    ))}
                                </div>
                            </div>

                            {room.details && (
                                <div className="prose prose-blue max-w-none text-gray-600 mb-8" dangerouslySetInnerHTML={{ __html: room.details }}></div>
                            )}

                            <div className="mt-auto pt-6 border-t border-gray-100">
                                <h3 className="font-bold text-gray-900 mb-4">Interested in this room?</h3>
                                <div className="flex flex-col sm:flex-row gap-4">
                                    <Button href="/contact" variant="primary" fullWidth className="!bg-[#135FAE] hover:!bg-[#0e4b8a]">
                                        Enquire Now
                                    </Button>
                                    <Button href="https://wa.me/1234567890" variant="outline" fullWidth className="border-green-500 text-green-600 hover:bg-green-50">
                                        WhatsApp Us
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    );
}
