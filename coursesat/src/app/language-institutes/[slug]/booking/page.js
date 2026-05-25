import DesktopInstituteBooking from "@/components/desktop/desktop-institute-booking";
import MobileInstituteBooking from "@/components/mobile/mobile-institute-booking";
import DesktopHeader from "@/components/desktop/desktop-header";
import DesktopFooter from "@/components/desktop/desktop-footer";

export default function InstituteBookingPage({ params }) {
    return (
        <main className="min-h-screen bg-white lg:bg-[#F8FAFC]">
            <div className="hidden md:block">
                <DesktopHeader />
                <DesktopInstituteBooking params={params} />
                <DesktopFooter />
            </div>
            <div className="md:hidden">
                <MobileInstituteBooking params={params} />
            </div>
        </main>
    );
}
