"use client";

import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faFacebookF,
  faInstagram,
  faLinkedinIn,
  faXTwitter,
} from "@fortawesome/free-brands-svg-icons";
import { useState } from "react";

import { useLocale } from "@/components/providers/locale-provider";

const SOCIAL_ICONS = [faLinkedinIn, faFacebookF, faInstagram, faXTwitter];

export default function MobileFooter() {
  const { direction, t } = useLocale();
  const isRtl = direction === "rtl";
  const [email, setEmail] = useState("");
  const year = new Date().getFullYear();

  const linkCls =
    "block text-[0.70rem] text-gray-300 transition-colors hover:text-white focus:text-white";
  const socialBtn =
    "flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20";

  const content = {
    servicesTitle: t("pages.coursesat.mobile.footer.services_title", "Services"),
    companyTitle: t("pages.coursesat.mobile.footer.company_title", "About Course English"),
    stayLoop: t("pages.coursesat.mobile.footer.newsletter_title", "Subscribe to the Newsletter"),
    subscribe: t("pages.coursesat.mobile.footer.subscribe", "Subscribe"),
    emailPlaceholder: t("pages.coursesat.mobile.footer.email_placeholder", "Email address"),
    home: t("pages.coursesat.mobile.footer.home", "Home"),
    about: t("pages.coursesat.mobile.footer.about", "About Us"),
    team: t("pages.coursesat.mobile.footer.team", "Team"),
    blog: t("pages.coursesat.mobile.footer.blog", "Blog"),
    contact: t("pages.coursesat.mobile.footer.contact", "Contact Us"),
    terms: t("pages.coursesat.mobile.footer.terms", "Terms & Conditions"),
    privacy: t("pages.coursesat.mobile.footer.privacy", "Privacy Policy"),
    languageInstitutes: t("pages.coursesat.mobile.footer.language_institutes", "English Language Schools"),
    summerPrograms: t("pages.coursesat.mobile.footer.summer_programs", "Summer Program"),
    universityAdmissions: t("pages.coursesat.mobile.footer.university_admissions", "University Admissions"),
    tagline: t(
      "pages.coursesat.mobile.footer.tagline",
      "Join our ideal team in exploring study opportunities abroad! Get tailored guidance, accredited institutions, detailed course options, and exclusive offers - all designed to enrich your educational journey."
    ),
    rights: t(
      "pages.coursesat.mobile.footer.rights",
      "All rights reserved © 2026 Course English"
    ).replace("2026", String(year)),
  };

  const submit = (event) => {
    event.preventDefault();
  };

  return (
    <footer className="bg-[#0f2230] text-white" dir={direction}>
      <div className="px-4 py-12">
        <div className="py-2 text-center">
          <div className="flex flex-col justify-between">
            <p className="mx-auto max-w-[19rem] text-[0.80rem] leading-8 text-gray-300">
              {content.tagline}
            </p>

            <div className="my-6 flex items-center justify-center gap-3">
              {(isRtl ? [...SOCIAL_ICONS].reverse() : SOCIAL_ICONS).map((icon) => (
                <a
                  key={icon.iconName}
                  href="#"
                  className={socialBtn}
                  aria-label={icon.iconName}
                >
                  <FontAwesomeIcon icon={icon} />
                </a>
              ))}
            </div>
          </div>
        </div>

        <div className={`mb-8 flex px-4 gap-4 ${isRtl ? "text-right" : "text-left"}`}>
          <div className="w-3/5">
            <h4 className="mb-4 text-[1.3rem] font-bold">
              {content.companyTitle}
            </h4>
            <ul className="text-[0.80rem] leading-8">
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

          <div className="w-2/5">
            <h4 className="mb-4 text-[1.3rem] font-bold">
              {content.servicesTitle}
            </h4>
            <ul className="text-[0.80rem] leading-8">
              <li>
                <Link href="/#language-institutes" className={linkCls}>
                  {content.languageInstitutes}
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
        </div>

        <div className="px-2 text-center">
          <h3 className="mb-6 text-[1.3rem] font-semibold text-white">
            {content.stayLoop}
          </h3>
          <form onSubmit={submit} className="px-4">
            <input
              type="email"
              value={email}
              onChange={(event) => setEmail(event.target.value)}
              placeholder={content.emailPlaceholder}
              className={`mb-4 w-full rounded-full bg-white px-5 py-2 text-gray-900 outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-sky-400 ${
                isRtl ? "text-right" : "text-left"
              }`}
              required
            />
            <button
              type="submit"
              className="inline-flex min-w-[100px] items-center justify-center rounded-full bg-[#6aa6d9] px-6 py-3 text-[0.92rem] text-white hover:bg-[#5f9ccf]"
            >
              <span>{content.subscribe}</span>
            </button>
          </form>
        </div>
      </div>

      <div className="border-t border-white/10">
        <div className="px-4 py-5 text-center text-sm text-gray-300">
          <div className="opacity-80">{content.rights}</div>
        </div>
      </div>
    </footer>
  );
}
