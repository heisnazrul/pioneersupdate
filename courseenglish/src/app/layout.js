import localFont from "next/font/local";
import { Geist, Geist_Mono } from "next/font/google";
import Script from "next/script";
import { cookies } from "next/headers";
import "./globals.css";
import "@fortawesome/fontawesome-free/css/all.min.css";
import SiteShell from "./layouts/site-shell";

const graphikArabic = localFont({
    src: [
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Thin.woff2", weight: "100", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Thin.woff", weight: "100", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Extralight.woff2", weight: "200", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Extralight.woff", weight: "200", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Light.woff2", weight: "300", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Light.woff", weight: "300", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Regular.woff2", weight: "400", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Regular.woff", weight: "400", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Medium.woff2", weight: "500", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Medium.woff", weight: "500", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Semibold.woff2", weight: "600", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Semibold.woff", weight: "600", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Bold.woff2", weight: "700", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Bold.woff", weight: "700", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Super.woff2", weight: "800", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Super.woff", weight: "800", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Black.woff2", weight: "900", style: "normal" },
        { path: "../../public/fonts/graphik-arabic/GraphikArabic-Black.woff", weight: "900", style: "normal" },
    ],
    variable: "--font-graphik-arabic",
    display: "swap",
    preload: true,
});

const geistSans = Geist({
    variable: "--font-geist-sans",
    subsets: ["latin"],
});

const geistMono = Geist_Mono({
    variable: "--font-geist-mono",
    subsets: ["latin"],
});

export const metadata = {
    title: "CourseEnglish",
    description: "English courses with live feedback, modern lessons, and clear results.",
};

const VALID_LANGS = new Set(["en", "ar"]);

function resolveInitialLanguage() {
    let cookieLang = null;
    try {
        const cookieStore = cookies();
        if (cookieStore && typeof cookieStore.get === "function") {
            const found = cookieStore.get("ce_language");
            cookieLang = typeof found === "string" ? found : found?.value;
        }
    } catch {
        // If cookies() is unavailable in a given runtime, fall back to default.
    }

    if (cookieLang && VALID_LANGS.has(cookieLang)) return cookieLang;
    return "ar";
}

export default function RootLayout({ children }) {
    const initialLanguage = resolveInitialLanguage();
    const dir = initialLanguage === "ar" ? "rtl" : "ltr";

    return (
        <html lang={initialLanguage} dir={dir} suppressHydrationWarning={true}>
            <head>
                <Script id="ce-lang-boot" strategy="beforeInteractive">{`
                    (function() {
                      try {
                        var match = document.cookie.match(/(?:^|;\\s*)ce_language=([^;]+)/);
                        var lang = match ? decodeURIComponent(match[1]) : (typeof localStorage !== 'undefined' ? localStorage.getItem('ce_language') : null);
                        lang = lang === 'en' ? 'en' : 'ar';
                        var dir = lang === 'ar' ? 'rtl' : 'ltr';
                        document.documentElement.lang = lang;
                        document.documentElement.dir = dir;
                      } catch (e) {
                        /* ignore boot errors */
                      }
                    })();
                `}</Script>
            </head>
            <body
                className={`${graphikArabic.variable} ${geistSans.variable} ${geistMono.variable} antialiased`}
                suppressHydrationWarning={true}
            >
                <SiteShell initialLanguage={initialLanguage}>{children}</SiteShell>
            </body>
        </html>
    );
}
