import HeroSection from "@/app/components/hero";
import StatsSection from "@/app/components/StatsSection";
import CertificatesSection from "@/app/components/certificate";
import Offer from "@/app/components/offer";
import Camps from "@/app/components/camps";
import Online from "@/app/components/online";
import Faqs from "@/app/components/faqs";
import Blogs from "@/app/components/blogs";
import StudentReviewsSection from "@/app/components/reviews";
import LanguageDestinations from "@/app/components/links";

export default function Home() {
    return (
        <div className="bg-white text-slate-900">
            <main>
                <HeroSection />
                <StatsSection />
                <CertificatesSection />
                <Offer />
                <Camps />
                <Online />
                <StudentReviewsSection />
                <LanguageDestinations />
                <Faqs />
                <Blogs />
            </main>
        </div>
    );
}
