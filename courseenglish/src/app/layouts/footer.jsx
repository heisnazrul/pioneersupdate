"use client";

import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faFacebookF, faInstagram, faLinkedinIn, faTwitter } from "@fortawesome/free-brands-svg-icons";
import { faRobot, faPaperPlane } from "@fortawesome/free-solid-svg-icons";
import { useState } from "react";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";

export default function Footer() {
  const [email, setEmail] = useState("");
  const year = new Date().getFullYear();
  const { language } = useCourseEnglishSettings();
  const isAr = language === "ar";
  const locale = getCourseEnglishMessages(language);
  const footer = locale?.layouts?.footer ?? {};
  const navbar = locale?.layouts?.navbar ?? {};

  const submit = (e) => {
    e.preventDefault();
  };

  const linkCls =
    "block py-1.5 text-gray-300 hover:text-white focus:text-white transition-colors";

  const socialBtn =
    "flex h-15 w-15 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition";

  const content = {
    programs: footer?.columns?.programs || (isAr ? "البرامج" : "Programs"),
    company: footer?.columns?.company || (isAr ? "الشركة" : "Company"),
    stayLoop: footer?.subscribe?.heading || (isAr ? "ابقَ على اطلاع" : "Stay in the loop"),
    subscribe: footer?.subscribe?.button || (isAr ? "اشترك" : "Subscribe"),
    emailPlaceholder: footer?.subscribe?.placeholder || (isAr ? "أدخل بريدك الإلكتروني" : "Enter your email"),
    about: navbar?.main_nav?.about_us || (isAr ? "من نحن" : "About Us"),
    careers: footer?.careers || (isAr ? "الوظائف" : "Careers"),
    blog: footer?.blog || (isAr ? "المدونة" : "Blog"),
    contact: footer?.contact || (isAr ? "تواصل معنا" : "Contact"),
    languageInstitutes: navbar?.top_nav?.language_institutes || (isAr ? "معاهد اللغة" : "Language Institutes"),
    summerPrograms: navbar?.top_nav?.summer_programs || (isAr ? "البرامج الصيفية" : "Summer Programs"),
    distanceLearning: footer?.distance_learning || (isAr ? "التعلم عن بعد" : "Distance Learning"),
    travelTourism: navbar?.top_nav?.travel_tourism || (isAr ? "السفر والسياحة" : "Travel & Tourism"),
    tagline: footer?.tagline || (isAr
      ? "CourseEnglish يساعد المتعلمين في العثور على برامج اللغة الإنجليزية المناسبة، ومقارنة الخيارات، والحجز بثقة."
      : "CourseEnglish helps learners find the right English programs, compare options, and book with confidence."),
    rights: (footer?.rights || (isAr ? `جميع الحقوق محفوظة © ${year}` : `All rights reserved © ${year}`)).replace("2026", String(year)),
  };

  return (
    <footer className="bg-[#0f2230] text-white">
      <div className="px-4 md:px-10 xl:px-20 2xl:px-40 py-14">
        <div className="hidden md:flex gap-10 justify-between">
          <div className="w-2/8">
            <h3 className="mb-6 text-2xl font-normal">{content.stayLoop}</h3>
            <form onSubmit={submit} className="">
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder={content.emailPlaceholder}
                className="w-full rounded-full mb-6 bg-white px-5 py-3 text-gray-900 placeholder-gray-400 outline-none focus:ring-2 focus:ring-sky-400"
                required
              />
              <button
                type="submit"
                className="inline-flex items-center justify-center gap-2 rounded-full bg-[#5b96d1] px-6 py-3 text-white hover:bg-[#4f89c3] focus:outline-none focus:ring-2 focus:ring-[#5b96d1]/40"
              >
                <FontAwesomeIcon icon={faPaperPlane} className="text-sm" />
                <span>{content.subscribe}</span>
              </button>
            </form>
          </div>

          <div className="w-1/8">
            <h4 className="mb-4 text-xl font-normal">{content.programs}</h4>
            <ul className="text-sm leading-7">
              <li>
                <Link href="#" className={linkCls}>
                  {content.languageInstitutes}
                </Link>
              </li>
              <li>
                <Link href="#" className={linkCls}>
                  {content.summerPrograms}
                </Link>
              </li>
              <li>
                <Link href="#" className={linkCls}>
                  {content.distanceLearning}
                </Link>
              </li>
              <li>
                <Link href="#" className={linkCls}>
                  {content.travelTourism}
                </Link>
              </li>
            </ul>
          </div>

          <div className="w-1/8">
            <h4 className="mb-4 text-xl font-normal">{content.company}</h4>
            <ul className="text-sm leading-7">
              <li>
                <Link href="#" className={linkCls}>
                  {content.about}
                </Link>
              </li>
              <li>
                <Link href="#" className={linkCls}>
                  {content.careers}
                </Link>
              </li>
              <li>
                <Link href="#" className={linkCls}>
                  {content.blog}
                </Link>
              </li>
              <li>
                <Link href="#" className={linkCls}>
                  {content.contact}
                </Link>
              </li>
            </ul>
          </div>

          <div className="w-3/8 ">
            <p className="text-lg leading-7 text-gray-300">
              {content.tagline}
            </p>

            <div className="mt-6 flex items-center gap-3">
              {[faLinkedinIn, faFacebookF, faInstagram, faTwitter].map((icon) => (
                <a
                  key={icon.iconName}
                  href="#"
                  className={socialBtn}
                  aria-label={icon.iconName}
                >
                  <FontAwesomeIcon icon={icon} className="text-3xl" />
                </a>
              ))}
            </div>
          </div>
        </div>

        {/* mobiel section  */}
        <div className=" md:hidden" >
          <div className="py-2 text-center" >
            <div className="flex flex-col justify-between">
            <p className="text-sm leading-7 text-gray-300">
              {content.tagline}
            </p>

            <div className="my-6 flex items-center justify-center gap-3">
              {[faLinkedinIn, faFacebookF, faInstagram, faTwitter].map((icon) => (
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
          <div className="flex gap-4 justify-around px-4 mb-8">
              <div>
                <h4 className="mb-4 text-xl font-normal">{content.programs}</h4>
                <ul className="text-sm leading-7">
                  <li>
                    <Link href="#" className={linkCls}>
                      {content.languageInstitutes}
                    </Link>
                  </li>
                  <li>
                    <Link href="#" className={linkCls}>
                      {content.summerPrograms}
                    </Link>
                  </li>
                  <li>
                    <Link href="#" className={linkCls}>
                      {content.distanceLearning}
                    </Link>
                  </li>
                  <li>
                    <Link href="#" className={linkCls}>
                      {content.travelTourism}
                    </Link>
                  </li>
                </ul>
              </div>

              <div>
                <h4 className="mb-4 text-xl font-normal">{content.company}</h4>
                <ul className="text-xl leading-7">
                  <li>
                    <Link href="#" className={linkCls}>
                      {content.about}
                    </Link>
                  </li>
                  <li>
                    <Link href="#" className={linkCls}>
                      {content.careers}
                    </Link>
                  </li>
                  <li>
                    <Link href="#" className={linkCls}>
                      {content.blog}
                    </Link>
                  </li>
                  <li>
                    <Link href="#" className={linkCls}>
                      {content.contact}
                    </Link>
                  </li>
                </ul>
              </div>
          </div>
          
          {/* stay loop  */}
          <div className="px-2 text-center">
            <h3 className="mb-6 text-2xl font-normal">{content.stayLoop}</h3>
            <form onSubmit={submit} className="px-4">
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder={content.emailPlaceholder}
                className="w-full rounded-full bg-white px-5 py-2 mb-4 text-gray-900 placeholder-gray-400 outline-none focus:ring-2 focus:ring-sky-400"
                required
              />
              <button type="submit"
                className="inline-flex items-center justify-center gap-2 rounded-full bg-[#5b96d1] px-4 py-3 text-white hover:bg-[#4f89c3]"
              >
                <FontAwesomeIcon icon={faPaperPlane} className="text-sm" />
                <span>{content.subscribe}</span>
              </button>
            </form>
          </div>

          
        </div>
      </div>

      <div className="border-t border-white/10">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-5 text-sm text-gray-300">
          <div className="flex items-center gap-3">
            <span className="opacity-80">CourseEnglish</span>
          </div>
          <div className="opacity-80">{content.rights}</div>
        </div>
      </div>
    </footer>
  );
}
