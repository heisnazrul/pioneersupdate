'use client';

import { motion } from 'framer-motion';
import Link from 'next/link';
import Image from 'next/image';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faMapMarkerAlt, faTrophy, faArrowRight } from '@fortawesome/free-solid-svg-icons';
import { University } from '@/lib/data/types';

interface UniversityCardProps {
    university: University;
    index?: number;
}

const GRADIENTS = [
    "from-blue-600 to-indigo-700",
    "from-emerald-500 to-teal-700",
    "from-orange-500 to-red-600",
    "from-purple-600 to-indigo-600",
    "from-cyan-600 to-blue-700",
];

export default function UniversityCard({ university, index = 0 }: UniversityCardProps) {
    // Pick a gradient deterministically or via index
    const gradient = GRADIENTS[index % GRADIENTS.length] || GRADIENTS[0];

    return (
        <motion.div
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full overflow-hidden border border-gray-100"
        >
            {/* Banner Area */}
            <div className={`h-24 bg-gradient-to-r ${gradient} relative`}>
                {/* Rank Badge - Only show if rank exists */}
                {university.rank && (
                    <div className="absolute top-3 right-3 bg-white/20 backdrop-blur-md border border-white/20 text-white px-3 py-1 rounded-full flex items-center gap-1.5 shadow-sm">
                        <FontAwesomeIcon icon={faTrophy} className="text-yellow-300 text-xs" />
                        <span className="text-xs font-bold">#{university.rank}</span>
                    </div>
                )}
            </div>

            {/* Content Container */}
            <div className="px-6 pb-6 pt-0 flex-1 flex flex-col">
                {/* Logo Overlapping Banner */}
                <div className="relative -mt-10 mb-4">
                    <div className="w-20 h-20 bg-white rounded-xl shadow-md border-2 border-white flex items-center justify-center p-3 overflow-hidden">
                        {university.logoUrl ? (
                            <Image
                                src={university.logoUrl}
                                alt={university.name}
                                width={60}
                                height={60}
                                unoptimized
                                className="object-contain w-full h-full"
                            />
                        ) : (
                            <div className="text-2xl font-bold text-gray-300">
                                {university.name.charAt(0)}
                            </div>
                        )}
                    </div>
                </div>

                {/* Info */}
                <div className="mb-2">
                    <h3 className="text-xl font-bold text-gray-900 group-hover:text-[#135FAE] transition-colors leading-tight mb-1 line-clamp-2">
                        {university.name}
                    </h3>
                    <div className="flex items-center gap-2 text-sm text-gray-500">
                        <FontAwesomeIcon icon={faMapMarkerAlt} className="text-gray-400" />
                        <span>{university.location || 'Location Not Available'}</span>
                    </div>
                </div>

                {/* Key Disciplines */}
                <div className="mt-4 space-y-3">
                    <div className="space-y-1.5">
                        <span className="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Top Disciplines</span>
                        <div className="flex flex-wrap gap-1.5">
                            {(university.topDisciplines || ['Computer Science', 'Business', 'Medicine']).slice(0, 3).map((disc, i) => (
                                <span key={i} className="text-[11px] font-medium text-gray-600 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-100">
                                    {disc}
                                </span>
                            ))}
                        </div>
                    </div>

                    <div className="pt-3 border-t border-gray-50">
                        <Link
                            href={`/universities/${university.slug}`}
                            className="flex items-center justify-center w-full gap-2 px-4 py-2 rounded-lg bg-blue-50 text-[#135FAE] font-bold text-sm hover:bg-[#135FAE] hover:text-white transition-all duration-300 group-hover:translate-x-1"
                        >
                            View Details
                            <FontAwesomeIcon icon={faArrowRight} className="text-xs" />
                        </Link>
                    </div>
                </div>
            </div>
        </motion.div>
    );
}
