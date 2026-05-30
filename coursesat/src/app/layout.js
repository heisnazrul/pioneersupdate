import localFont from "next/font/local";
import { cookies, headers } from "next/headers";

import { LocaleProvider } from "@/components/providers/locale-provider";
import { CurrencyProvider } from "@/components/providers/currency-provider";
import { InteractionsProvider } from "@/lib/interactions";
import ReferralCapture from "@/components/shared/referral-capture";
import { getPreferredLanguage, isRtlLanguage } from "@/lib/locale";

import "./globals.css";

const graphikArabic = localFont({
  src: [
    {
      path: "../../public/fonts/graphik-arabic/GraphikArabic-Thin.woff2",
      weight: "100",
      style: "normal",
    },
    {
      path: "../../public/fonts/graphik-arabic/GraphikArabic-Extralight.woff2",
      weight: "200",
      style: "normal",
    },
    {
      path: "../../public/fonts/graphik-arabic/GraphikArabic-Light.woff2",
      weight: "300",
      style: "normal",
    },
    {
      path: "../../public/fonts/graphik-arabic/GraphikArabic-Regular.woff2",
      weight: "400",
      style: "normal",
    },
    {
      path: "../../public/fonts/graphik-arabic/GraphikArabic-Medium.woff2",
      weight: "500",
      style: "normal",
    },
    {
      path: "../../public/fonts/graphik-arabic/GraphikArabic-Semibold.woff2",
      weight: "600",
      style: "normal",
    },
    {
      path: "../../public/fonts/graphik-arabic/GraphikArabic-Bold.woff2",
      weight: "700",
      style: "normal",
    },
    {
      path: "../../public/fonts/graphik-arabic/GraphikArabic-Super.woff2",
      weight: "800",
      style: "normal",
    },
    {
      path: "../../public/fonts/graphik-arabic/GraphikArabic-Black.woff2",
      weight: "900",
      style: "normal",
    },
  ],
  variable: "--font-graphik-arabic",
  display: "swap",
});

export const metadata = {
  title: "CourseSat",
  description: "CourseSat is coming soon.",
};

export default async function RootLayout({ children }) {
  const cookieStore = await cookies();
  const savedLocale = cookieStore.get("locale")?.value;
  const language = savedLocale === "en" ? "en" : "ar";
  const direction = isRtlLanguage(language) ? "rtl" : "ltr";

  return (
    <html lang={language} dir={direction} className={graphikArabic.variable}>
      <body>
        <LocaleProvider initialLanguage={language}>
          <CurrencyProvider>
            <InteractionsProvider>
              <ReferralCapture />
              {children}
            </InteractionsProvider>
          </CurrencyProvider>
        </LocaleProvider>
      </body>
    </html>
  );
}
