"use client";

import DesktopFooter from "./desktop-footer";
import DesktopHeader from "./desktop-header";
import DesktopHero from "./desktop-hero";
import DesktopStats from "./desktop-stats";
import DesktopCertificates from "./desktop-certificates";
import DesktopOffers from "./desktop-offers";
import DesktopSummer from "./desktop-summer";
import DesktopOnline from "./desktop-online";
import DesktopReviews from "./desktop-reviews";
import DesktopLinks from "./desktop-links";
import DesktopFaqs from "./desktop-faqs";
import DesktopBlogs from "./desktop-blogs";

export default function DesktopHome() {
  return (
    <div className="flex min-h-screen flex-col">
      <DesktopHeader />
      <main className="flex-1">
        <DesktopHero />
        <DesktopStats />
        <DesktopCertificates />
        <DesktopOffers />
        <DesktopSummer />
        <DesktopOnline />
        <DesktopReviews />
        <DesktopLinks />
        <DesktopFaqs />
        <DesktopBlogs />
      </main>
      <DesktopFooter />
    </div>
  );
}
