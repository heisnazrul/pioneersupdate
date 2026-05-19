"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faUserGraduate,
    faUniversity,
} from "@fortawesome/free-solid-svg-icons";

interface StatsSectionProps {
    copy?: any;
}

export default function StatsSection({ copy }: StatsSectionProps) {
    const items = Array.isArray(copy?.items) ? copy.items : [];
    const students = items[0] || {};
    const universities = items[1] || {};

    return (
        <section className="w-full bg-white">
            {/* ===== DESKTOP / LARGE ===== */}
            <div className="hidden lg:block">
                <div className="mx-auto flex items-center justify-between gap-16 px-4 md:px-10 xl:px-20 2xl:px-40 py-16">
                    {/* Heading + description */}
                    <div className="max-w-sm lg:max-w-md xl:max-w-xl 2xl:max-w-2xl  ">
                        <h2 className="text-4xl lg:text-5xl 2xl:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.1]">
                            {copy?.heading || 'Our achievements in numbers'}
                        </h2>
                        <p className="hidden xl:block mt-4 text-sm lg:text-base text-slate-600 leading-relaxed">
                            {copy?.body || 'Thanks to our trusted partners and leading educational institutions around the world, we’ve helped thousands of students achieve their dream of learning English in accredited international environments.'}
                        </p>
                    </div>

                    {/* Stats */}
                    <div className="flex gap-10">
                        {/* +15,000 students */}
                        <div className="flex flex-col">
                            <p className="text-4xl lg:text-5xl font-extrabold text-[#135FAE] mb-2">
                                {students?.value || '+15,000'}
                            </p>
                            <p className="max-w-xs text-sm lg:text-base text-slate-700 font-medium leading-snug">
                                {students?.label || 'Students enrolled in accredited English programs abroad'}
                            </p>
                        </div>

                        {/* +50 universities */}
                        <div className="flex flex-col ">
                            <p className="text-4xl lg:text-5xl font-extrabold text-[#135FAE] mb-2">
                                {universities?.value || '+50'}
                            </p>
                            <p className="max-w-xs text-sm lg:text-base text-slate-700 font-medium leading-snug">
                                {universities?.label || 'Partner universities offering accredited English study programs abroad'}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {/* ===== MOBILE / SMALL ===== */}
            <div className="lg:hidden bg-[#E8F3FC] py-20">
                <div className="mx-auto w-full px-4">
                    {/* Mobile heading */}
                    <h2 className="text-center text-2xl sm:text-4xl font-extrabold leading-snug text-slate-900 mb-10">
                        {copy?.mobile_heading || copy?.heading || 'Our achievements and accreditations'}
                    </h2>

                    {/* Cards row */}
                    <div className="grid grid-cols-2 gap-4 sm:gap-6 md:gap-20 sm:px-10 md:px-20">
                        {/* Students card */}
                        <div className="rounded-2xl bg-white px-5 py-6 shadow-[0_20px_40px_rgba(15,23,42,0.06)] flex flex-col items-center text-center">
                            <div className="mb-4">
                                <span className="inline-flex h-12 w-12 items-center justify-center rounded-full bg-[#E3F0FF] text-[#135FAE]">
                                    <FontAwesomeIcon icon={faUserGraduate} size="lg" />
                                </span>
                            </div>
                            <p className="text-sm font-semibold text-slate-500 uppercase tracking-wider">{students?.label || 'Students'}</p>
                            <p className="mt-2 text-2xl font-black text-slate-900">
                                {students?.value || '+15,000'}
                            </p>
                        </div>

                        {/* Universities card */}
                        <div className="rounded-2xl bg-white px-5 py-6 shadow-[0_20px_40px_rgba(15,23,42,0.06)] flex flex-col items-center text-center">
                            <div className="mb-4">
                                <span className="inline-flex h-12 w-12 items-center justify-center rounded-full bg-[#E3F0FF] text-[#135FAE]">
                                    <FontAwesomeIcon icon={faUniversity} size="lg" />
                                </span>
                            </div>
                            <p className="text-sm font-semibold text-slate-500 uppercase tracking-wider">{universities?.label || 'Universities'}</p>
                            <p className="mt-2 text-2xl font-black text-slate-900">
                                {universities?.value || '+50'}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
