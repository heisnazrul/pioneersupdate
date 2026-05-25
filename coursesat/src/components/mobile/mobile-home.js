"use client";

import MobileFooter from "./mobile-footer";
import MobileBottomNav from "./mobile-bottom-nav";
import MobileHeader from "./mobile-header";
import MobileHero from "./mobile-hero";
import MobileStats from "./mobile-stats";
import MobileCertificates from "./mobile-certificates";
import MobileOffers from "./mobile-offers";
import MobileSummer from "./mobile-summer";
import MobileOnline from "./mobile-online";
import MobileReviews from "./mobile-reviews";
import MobileLinks from "./mobile-links";
import MobileFaqs from "./mobile-faqs";
import MobileBlogs from "./mobile-blogs";

export default function MobileHome() {
  return (
    <div className="flex min-h-screen flex-col pb-[88px]">
      <MobileHeader />
      <main className="flex-1">
        <MobileHero />
        <MobileStats />
        <MobileCertificates />
        <MobileOffers />
        <MobileSummer />
        <MobileOnline />
        <MobileReviews />
        <MobileLinks />
        <MobileFaqs />
        <MobileBlogs />
      </main>
      <MobileFooter />
      <MobileBottomNav />
    </div>
  );
}
