"use client";

import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faFacebookF,
  faInstagram,
  faLinkedinIn,
  faWhatsapp,
  faXTwitter,
} from "@fortawesome/free-brands-svg-icons";
import { useState } from "react";

import { useLocale } from "@/components/providers/locale-provider";

const SOCIAL_ICONS = [faLinkedinIn, faFacebookF, faInstagram, faXTwitter];

export default function DesktopFooter() {
  const { direction, t } = useLocale();
  const isRtl = direction === "rtl";
  const [email, setEmail] = useState("");
  const year = new Date().getFullYear();

  const linkCls =
    "block py-1 text-[0.9rem] font-normal text-gray-300 transition-colors hover:text-white focus:text-white";
  const socialBtn =
    "flex h-14 w-14 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20";

  const content = {
    programs: t("pages.coursesat.desktop.footer.services_title", "Services"),
    company: t("pages.coursesat.desktop.footer.company_title", "About Course English"),
    stayLoop: t("pages.coursesat.desktop.footer.newsletter_title", "Subscribe to the Newsletter"),
    subscribe: t("pages.coursesat.desktop.footer.subscribe", "Subscribe"),
    emailPlaceholder: t("pages.coursesat.desktop.footer.email_placeholder", "Email address"),
    home: t("pages.coursesat.desktop.footer.home", "Home"),
    about: t("pages.coursesat.desktop.footer.about", "About Us"),
    team: t("pages.coursesat.desktop.footer.team", "Team"),
    blog: t("pages.coursesat.desktop.footer.blog", "Blog"),
    contact: t("pages.coursesat.desktop.footer.contact", "Contact Us"),
    terms: t("pages.coursesat.desktop.footer.terms", "Terms & Conditions"),
    privacy: t("pages.coursesat.desktop.footer.privacy", "Privacy Policy"),
    languageInstitutes: t("pages.coursesat.desktop.footer.language_institutes", "English Language Schools"),
    summerPrograms: t("pages.coursesat.desktop.footer.summer_programs", "Summer Program"),
    universityAdmissions: t("pages.coursesat.desktop.footer.university_admissions", "University Admissions"),
    tagline: t(
      "pages.coursesat.desktop.footer.tagline",
      "Join our ideal team in exploring study opportunities abroad! Get tailored guidance, accredited institutions, detailed course options, housing, and exclusive offers - all designed to enrich your educational journey."
    ),
    rights: t("pages.coursesat.desktop.footer.rights", "All rights reserved © 2026 Course English").replace(
      "2026",
      String(year)
    ),
    whatsapp: t("pages.contact_us.cards.whatsapp", "WhatsApp"),
  };

  const submit = (event) => {
    event.preventDefault();
  };

  return (
    <footer className="bg-[#0f2230] text-white" dir={direction}>
      <div className="mx-auto max-w-[1720px] px-6 py-16 md:px-10 xl:px-16 2xl:px-24">
        <div
          className={`flex items-start justify-between gap-12 ${
            isRtl ? "flex-row-reverse" : ""
          } ${
            isRtl ? "text-right" : "text-left"
          }`}
        >
          <div className="basis-[25%] pt-1">
            <h3 className="mb-8 text-[1.8rem] font-semibold leading-tight">
              {content.stayLoop}
            </h3>
            <form
              onSubmit={submit}
              className={`flex max-w-[430px] flex-col ${
                isRtl ? "items-start" : "items-end"
              }`}
            >
              <input
                type="email"
                value={email}
                onChange={(event) => setEmail(event.target.value)}
                placeholder={content.emailPlaceholder}
                className={`mb-9 h-[58px] w-full rounded-full bg-white px-7 text-[0.95rem] text-gray-900 outline-none placeholder:text-[0.95rem] placeholder:text-gray-400 focus:ring-2 focus:ring-sky-400 ${
                  isRtl ? "text-right" : "text-left"
                }`}
                required
              />
              <button
                type="submit"
                className=" min-w-[174px] justify-center rounded-full bg-[#6fa8d8] px-8 py-3.5 text-[0.95rem] font-semibold text-white hover:bg-[#639dce] focus:outline-none focus:ring-2 focus:ring-[#5b96d1]/40"
              >
                <span>{content.subscribe}</span>
              </button>
            </form>
          </div>

          <div className="basis-[14%] pt-1">
            <h4 className="mb-7 text-[1.2rem] font-semibold">{content.programs}</h4>
            <ul className="space-y-0.5 text-[0.9rem] leading-8">
              <li>
                <Link href="/language-institutes" className={linkCls}>
                  {content.languageInstitutes}
                </Link>
              </li>
              <li>
                <Link href="/online-courses" className={linkCls}>
                  {t("layouts.navbar.top_nav.online_courses", "Online Courses")}
                </Link>
              </li>
              <li>
                <Link href="/#summer-programs" className={linkCls}>
                  {content.summerPrograms}
                </Link>
              </li>
              <li>
                <Link href="/#university-admissions" className={linkCls}>
                  {content.universityAdmissions}
                </Link>
              </li>
              <li>
                <Link href="/articles" className={linkCls}>
                  {content.blog}
                </Link>
              </li>
            </ul>
          </div>

          <div className="basis-[14%] pt-1">
            <h4 className="mb-7 text-[1.2rem] font-semibold">{content.company}</h4>
            <ul className="space-y-0.5 text-[0.9rem] leading-8">
              <li>
                <Link href="/" className={linkCls}>
                  {content.home}
                </Link>
              </li>
              <li>
                <Link href="/about-us" className={linkCls}>
                  {content.about}
                </Link>
              </li>
              <li>
                <Link href="/contact-us" className={linkCls}>
                  {content.contact}
                </Link>
              </li>
              <li>
                <Link href="/#team" className={linkCls}>
                  {content.team}
                </Link>
              </li>
              <li>
                <Link href="/#terms" className={linkCls}>
                  {content.terms}
                </Link>
              </li>
              <li>
                <Link href="/#privacy" className={linkCls}>
                  {content.privacy}
                </Link>
              </li>
            </ul>
          </div>

          <div className="basis-[33%] pt-1">
            <p className="max-w-[34rem] text-[0.95rem] leading-[2rem] text-gray-300">
              {content.tagline}
            </p>

            <div
              dir="ltr"
              className={`mt-12 flex items-center gap-4 ${
                isRtl ? "justify-end" : ""
              }`}
            >
              {SOCIAL_ICONS.map((icon) => (
                <a
                  key={icon.iconName}
                  href="#"
                  className={socialBtn}
                  aria-label={icon.iconName}
                >
                  <FontAwesomeIcon icon={icon} className="text-[1.45rem]" />
                </a>
              ))}
            </div>
          </div>
        </div>
      </div>

      <div className="border-t border-white/10">
        <div
          className=" max-w-7xl px-4 py-5 text-center text-sm text-gray-300"
        >
          <div className="opacity-80">{content.rights}</div>
        </div>
      </div>

      <a
        href="https://wa.me/966550027268"
        target="_blank"
        rel="noreferrer"
        aria-label={content.whatsapp}
        title={content.whatsapp}
        className="fixed bottom-6 right-6 z-30 flex h-20 w-20 items-center justify-center rounded-full bg-[#25D366] text-white shadow-[0_18px_40px_rgba(0,0,0,0.28)] transition hover:scale-[1.03] hover:bg-[#1fbe59] focus:outline-none focus:ring-4 focus:ring-[#25D366]/35"
      >
        <FontAwesomeIcon icon={faWhatsapp} className="text-[2.55rem]" />
      </a>
    </footer>
  );
}
