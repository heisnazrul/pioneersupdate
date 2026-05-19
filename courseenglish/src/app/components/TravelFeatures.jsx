"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faHeadset, faTags, faGlobeAmericas, faUserShield } from "@fortawesome/free-solid-svg-icons";

const ICONS = { faHeadset, faTags, faGlobeAmericas, faUserShield };

const mapIcon = (name) => ICONS[name] || faHeadset;

export default function TravelFeatures({ items = [], isArabic = false }) {
    const dir = isArabic ? "rtl" : "ltr";
    return (
        <section className="py-16 md:py-24 bg-white border-t border-gray-100" dir={dir}>
            <div className="container mx-auto px-4">
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
                    {items.map((item, idx) => (
                        <div key={idx} className="flex gap-4 items-start">
                            <div className="flex-none p-3 rounded-full bg-[#E0EFF8] text-[#0057B7]">
                                <FontAwesomeIcon icon={mapIcon(item.icon)} className="h-6 w-6" />
                            </div>
                            <div>
                                <h4 className="text-lg font-medium text-slate-900 mb-1">{item.title}</h4>
                                <p className="text-sm text-slate-500 leading-relaxed">{item.description}</p>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
