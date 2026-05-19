import { cookies } from 'next/headers';
import { fetchHeroData, fetchCertificates, fetchHomeCms, fetchReviews, fetchScholarships, fetchDestinations, fetchUniversities, fetchFaqs, fetchHomeBlogs } from '@/lib/api';
import Hero from '@/components/Hero';
import StatsSection from '@/components/StatsSection';
import CertificatesSection from '@/components/CertificatesSection';
import DestinationsSection from '@/components/DestinationsSection';
import UniversitiesSection from "@/components/UniversitiesSection";
import VideoReviewsSection from "@/components/VideoReviewsSection";
import StudentReviewsSection from "@/components/StudentReviewsSection";
import ScholarshipsSection from "@/components/ScholarshipsSection";

import TrustSection from "@/components/TrustSection";
import FaqSection from "@/components/FaqSection";
import BlogNewsSection from "@/components/BlogNewsSection";

export default async function Home() {
  const cookieStore = await cookies();
  const lang = (cookieStore.get('uni_language')?.value || 'en').toLowerCase() === 'ar' ? 'ar' : 'en';

  const heroData = await fetchHeroData(lang);
  const homeCms = await fetchHomeCms(lang);
  const certificates = await fetchCertificates(lang);
  const reviewsData = await fetchReviews(lang);
  const scholarships = await fetchScholarships(lang);
  const destinations = await fetchDestinations(lang);
  const universities = await fetchUniversities(lang);
  const faqs = await fetchFaqs(lang);
  const blogs = await fetchHomeBlogs(lang);

  return (
    <main className="min-h-screen bg-slate-50">
      <Hero heroData={heroData || undefined} />
      <StatsSection copy={homeCms?.stats} />
      <CertificatesSection data={certificates} copy={homeCms?.certificates} />
      <DestinationsSection destinations={destinations || []} copy={homeCms?.destinations} />
      <UniversitiesSection universities={universities || []} copy={homeCms?.universities} lang={lang} />
      <VideoReviewsSection reviews={reviewsData?.video_reviews} copy={homeCms?.reviews} lang={lang} />
      <StudentReviewsSection reviews={reviewsData?.reviews} copy={homeCms?.reviews} />
      <ScholarshipsSection scholarships={scholarships || []} copy={homeCms?.scholarships} />
      <TrustSection copy={homeCms?.trust} lang={lang} />
      <FaqSection faqs={faqs} copy={homeCms?.faq} lang={lang} />
      <BlogNewsSection blogs={blogs} copy={homeCms?.blogs} lang={lang} />
    </main>
  );
}
