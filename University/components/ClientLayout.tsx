"use client";

import { usePathname } from "next/navigation";
import Navbar from "@/components/Navbar";
import MainLayout from "@/components/MainLayout";
import { AuthProvider } from "@/context/AuthContext";
import LanguageSync from "@/components/LanguageSync";
import WhatsAppButton from "@/components/WhatsAppButton";

export default function ClientLayout({
    children,
    footer,
    lang = 'en'
}: {
    children: React.ReactNode;
    footer: React.ReactNode;
    lang?: 'en' | 'ar';
}) {
    const pathname = usePathname();

    // Pages where Navbar and Footer should be hidden
    const hideLayout =
        pathname.startsWith("/login") ||
        pathname.startsWith("/signup") ||
        pathname.startsWith("/student") ||
        pathname.startsWith("/complete-profile") ||
        pathname.startsWith("/forget") ||
        pathname.startsWith("/verify");

    if (hideLayout) {
        return (
            <AuthProvider>
                <LanguageSync />
                {children}
                <WhatsAppButton />
            </AuthProvider>
        );
    }

    return (
        <AuthProvider>
            <LanguageSync />
            <div className="flex flex-col min-h-screen">
                <Navbar initialLang={lang} />
                <MainLayout>
                    {children}
                </MainLayout>
                {footer}
            </div>
            <WhatsAppButton />
        </AuthProvider>
    );
}
