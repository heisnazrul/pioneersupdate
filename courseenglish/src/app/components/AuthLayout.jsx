"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import Image from "next/image";
import { useApi } from "@/lib/courseenglishApi";
import {
    clearCourseEnglishAuthSession,
    fetchCourseEnglishMe,
    getCourseEnglishDashboardPath,
    getStoredAuthToken,
    getStoredAuthUser,
    isCourseEnglishRole,
    saveCourseEnglishAuthSession,
} from "@/lib/courseenglishAuth";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowLeft } from "@fortawesome/free-solid-svg-icons";

export default function AuthLayout({
    title,
    subtitle,
    children,
    sideTitle = "نساعدك في اختيار معهد اللغة الأنسب لك، بثقة ووضوح",
    sideText = "منصة متخصصة في عرض ومقارنة معاهد اللغة المعتمدة حول العالم، ومساعدتك في اتخاذ القرار الأنسب قبل الحجز.",
}) {
    const [authed, setAuthed] = useState(false);
    const [dashboardPath, setDashboardPath] = useState("/");

    // Fetch branding for logo
    const { data } = useApi("/courseenglish/home/branding");
    const branding = data?.branding ?? {};

    useEffect(() => {
        const run = async () => {
            try {
                const token = getStoredAuthToken();
                const user = getStoredAuthUser();
                if (!token || !isCourseEnglishRole(user?.role)) {
                    setAuthed(false);
                    return;
                }

                const meJson = await fetchCourseEnglishMe(token);
                const role = meJson?.user?.role || user.role;
                if (!isCourseEnglishRole(role)) {
                    clearCourseEnglishAuthSession();
                    setAuthed(false);
                    return;
                }
                saveCourseEnglishAuthSession(meJson);
                setAuthed(true);
                setDashboardPath(getCourseEnglishDashboardPath(role));
            } catch {
                clearCourseEnglishAuthSession();
                setAuthed(false);
            }
        };

        run();
    }, []);

    const { language } = useCourseEnglishSettings();

    // Translations for AuthLayout
    const t = {
        en: {
            backToHome: "Back to Home",
            defaultSideTitle: "We help you choose the best language institute for you, with confidence and clarity.",
            defaultSideText: "A specialized platform for displaying and comparing accredited language institutes around the world, helping you make the most appropriate decision before booking.",
            loggedIn: "You are already logged in",
            goToDashboard: "Go to Dashboard",
            dashboardDesc: "You can go directly to your dashboard.",
        },
        ar: {
            backToHome: "العودة الى الموقع",
            defaultSideTitle: "نساعدك في اختيار معهد اللغة الأنسب لك، بثقة ووضوح",
            defaultSideText: "منصة متخصصة في عرض ومقارنة معاهد اللغة المعتمدة حول العالم، ومساعدتك في اتخاذ القرار الأنسب قبل الحجز.",
            loggedIn: "أنت مسجل دخولك بالفعل",
            goToDashboard: "الذهاب للوحة التحكم",
            dashboardDesc: "يمكنك الانتقال مباشر إلى لوحة التحكم الخاصة بك",
        }
    };

    const currentT = t[language] || t.en;
    const isRTL = language === "ar";

    // Use props or default translations
    const finalSideTitle = sideTitle === "نساعدك في اختيار معهد اللغة الأنسب لك، بثقة ووضوح" ? currentT.defaultSideTitle : sideTitle;
    const finalSideText = sideText === "منصة متخصصة في عرض ومقارنة معاهد اللغة المعتمدة حول العالم، ومساعدتك في اتخاذ القرار الأنسب قبل الحجز." ? currentT.defaultSideText : sideText;


    // Full page layout with background
    return (
        <div className="min-h-screen w-full bg-[#EBF5FF] flex items-center justify-center p-4 lg:p-8 relative overflow-hidden" dir={isRTL ? "rtl" : "ltr"}>

            {/* Background Gradient & Shapes (Global) */}
            <div className="absolute inset-0 z-0 pointer-events-none">
                <div className="absolute top-[-20%] left-[-20%] w-[800px] h-[800px] bg-blue-200/40 rounded-full blur-[120px]" />
                <div className="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-blue-300/30 rounded-full blur-[100px]" />
                <div className="absolute top-[40%] left-[20%] w-[400px] h-[400px] bg-white/40 rounded-full blur-[80px]" />
            </div>

            {/* Top Header (Absolute) */}
            <div className="absolute top-0 left-0 w-full p-6 lg:p-10 z-20 flex justify-between items-center">
                {/* Logo (Start) */}
                <div className="relative w-40 h-12">
                    <Image
                        src={branding?.header?.logo?.main || "/assets/logo/logo.png"}
                        alt="Course English"
                        fill
                        className={`object-contain ${isRTL ? 'object-right' : 'object-left'}`}
                        unoptimized
                    />
                </div>

                {/* Back Button (End) */}
                <Link href="/" className="group flex items-center gap-2 transition hover:opacity-80">
                    <div className="w-8 h-8 flex items-center justify-center text-slate-700 transition group-hover:scale-105">
                        <FontAwesomeIcon icon={faArrowLeft} className={`text-base ${isRTL ? 'rotate-180' : ''}`} />
                    </div>
                    <span className="text-slate-600 font-medium text-sm group-hover:text-slate-900 transition hidden md:inline-block">
                        {currentT.backToHome}
                    </span>
                </Link>
            </div>

            <div className="w-full max-w-6xl grid lg:grid-cols-2 gap-12 relative z-10 items-center mt-20 lg:mt-0">

                {/* Form Side */}
                {/* 
                    RTL: Form is Right (order-1), Text is Left (order-2).
                    LTR: Form should be Left (order-1), Text should be Right (order-2).
                    Order is controlled by flex/grid order.
                    In LTR, order-1 is Left, order-2 is Right.
                    In RTL, order-1 is Right, order-2 is Left.
                    So simply keeping order-1/order-2 works automatically for mirroring!
                */}
                <div className="w-full max-w-md mx-auto order-1">
                    <div className="bg-white rounded-[2rem] shadow-xl shadow-blue-900/5 p-8 md:p-12 border border-blue-50 relative">
                        <div className="text-center mb-8">
                            <h1 className="text-2xl font-semibold text-slate-900 mb-3">{title}</h1>
                            <p className="text-slate-500 font-normal text-sm leading-relaxed">{subtitle}</p>
                        </div>

                        {authed ? (
                            <div className="space-y-4">
                                <div className="rounded-2xl border border-blue-100 bg-blue-50 px-6 py-4 text-center">
                                    <p className="font-medium text-[#0057B7] mb-2">{currentT.loggedIn}</p>
                                    <p className="text-xs text-slate-500 mb-4">{currentT.dashboardDesc}</p>
                                    <Link
                                        href={dashboardPath}
                                        className="inline-flex w-full items-center justify-center rounded-xl bg-[#0057B7] py-3 text-sm font-medium text-white shadow-lg transition transform hover:scale-[1.02] active:scale-95"
                                    >
                                        {currentT.goToDashboard}
                                    </Link>
                                </div>
                            </div>
                        ) : (
                            children
                        )}
                    </div>
                </div>

                {/* Text Side */}
                <div className={`hidden lg:block ${isRTL ? 'text-right' : 'text-left'} order-2`}>
                    <h2 className="text-5xl font-semibold text-slate-900 leading-[1.3] mb-8 drop-shadow-sm">
                        {finalSideTitle}
                    </h2>
                    <p className="text-xl text-slate-600 font-normal leading-relaxed max-w-lg">
                        {finalSideText}
                    </p>
                </div>

            </div>
        </div>
    );
}
