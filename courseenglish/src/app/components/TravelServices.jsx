"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faPlaneDeparture,
    faHotel,
    faPassport,
    faMapMarkedAlt,
    faUmbrellaBeach,
    faShip,
} from "@fortawesome/free-solid-svg-icons";

const ICONS = {
    faPlaneDeparture,
    faHotel,
    faPassport,
    faMapMarkedAlt,
    faUmbrellaBeach,
    faShip,
};

const mapIcon = (name) => ICONS[name] || faPlaneDeparture;

export default function TravelServices({ data = {}, isArabic = false }) {
    const { eyebrow, heading, description, items = [] } = data;
    const dir = isArabic ? "rtl" : "ltr";
    return (
        <section className="py-20 bg-white" dir={dir}>
            <div className="container mx-auto px-4">
                <div className="text-center mb-16">
                    {eyebrow && <span className="text-[#0057B7] font-medium tracking-wider uppercase text-sm">{eyebrow}</span>}
                    <h2 className="mt-2 text-3xl font-semibold text-slate-900 md:text-4xl">{heading}</h2>
                    <p className="mt-4 text-slate-500 max-w-2xl mx-auto">
                        {description}
                    </p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {items.map((service, idx) => (
                        <div key={idx} className="group p-8 rounded-3xl bg-slate-50 border border-slate-100 transition duration-300 hover:bg-white hover:shadow-xl hover:-translate-y-1">
                            <div className="w-14 h-14 rounded-2xl bg-[#E0EFF8] text-[#0057B7] flex items-center justify-center mb-6 transition group-hover:bg-[#0057B7] group-hover:text-white">
                                <FontAwesomeIcon icon={mapIcon(service.icon)} className="h-6 w-6" />
                            </div>
                            <h3 className="text-xl font-medium text-slate-900 mb-3">{service.title}</h3>
                            <p className="text-slate-600 leading-relaxed">{service.description}</p>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
