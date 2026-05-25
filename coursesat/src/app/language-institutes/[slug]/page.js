import DesktopInstituteDetails from "@/components/desktop/desktop-institute-details";
import MobileInstituteDetails from "@/components/mobile/mobile-institute-details";
import DesktopHeader from "@/components/desktop/desktop-header";
import DesktopFooter from "@/components/desktop/desktop-footer";

export default function InstituteDetailsPage({ params }) {
    return (
        <main className="min-h-screen bg-white lg:bg-[#F8FAFC]">
            <div className="hidden md:block">
                <DesktopHeader />
                <DesktopInstituteDetails params={params} />
                <DesktopFooter />
            </div>
            <div className="md:hidden">
                <MobileInstituteDetails params={params} />
            </div>
        </main>
    );
}
