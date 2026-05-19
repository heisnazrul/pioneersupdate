import Link from 'next/link';
import Image from 'next/image';
import { Destination } from '@/lib/data/types';

interface DestinationCardProps {
    destination: Destination;
    lang?: 'en' | 'ar';
}

export default function DestinationCard({ destination, lang = 'en' }: DestinationCardProps) {
    const isAr = lang === 'ar';
    return (
        <div className="bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            {/* Image */}
            <div className="relative h-64 overflow-hidden">
                <Image
                    src={destination.imageUrl || '/assets/placeholder.jpg'}
                    alt={destination.name}
                    fill
                    unoptimized // Bypass Next.js optimization to fix localhost image loading issues
                    className="object-cover group-hover:scale-110 transition-transform duration-700"
                    sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" />

                {/* Floating Stats */}
                <div className="absolute top-4 right-4 flex flex-col gap-2">
                    {destination.stats?.[0] && (
                        <span className="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold text-slate-800 shadow-sm">
                            {destination.stats[0].value} {destination.stats[0].label}
                        </span>
                    )}
                </div>

                <div className="absolute bottom-4 left-4 text-white">
                    <p className="text-sm font-medium text-white/80 uppercase tracking-widest mb-1">{destination.region}</p>
                    <h3 className="text-2xl font-bold">{isAr ? `ادرس في ${destination.name}` : `Study in ${destination.name}`}</h3>
                </div>
            </div>

            {/* Content */}
            <div className="p-6">
                <p className="text-slate-600 text-sm line-clamp-3 mb-4 min-h-[60px]">
                    {destination.description}
                </p>

                {/* Features/Tags */}
                {destination.features && destination.features.length > 0 && (
                    <div className="flex flex-wrap gap-2 mb-6">
                        {destination.features.slice(0, 3).map((feature, i) => (
                            <span key={i} className="px-2 py-1 bg-slate-100 text-slate-600 text-xs rounded-md font-medium">
                                {feature}
                            </span>
                        ))}
                    </div>
                )}

                <div className="mt-auto pt-6 border-t border-gray-50 flex items-center justify-between">
                    <div className="flex -space-x-2">
                        {[1, 2, 3].map(i => (
                            <div key={i} className="w-8 h-8 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[10px] text-gray-500 font-bold">U{i}</div>
                        ))}
                    </div>
                    <Link
                        href={`/destinations/${destination.slug}`}
                        className="px-6 py-3 rounded-lg font-bold transition-all text-base bg-[#135FAE] text-white shadow-md hover:shadow-lg hover:bg-opacity-90 inline-flex items-center gap-2"
                    >
                        {isAr ? 'استكشف' : 'Explore'} <span>→</span>
                    </Link>
                </div>
            </div>
        </div>
    );
}
