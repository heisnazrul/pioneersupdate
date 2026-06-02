import { Suspense } from "react";
import MobileInstituteBookingCheckout from "@/components/mobile/mobile-institute-booking-checkout";

function CheckoutFallback() {
  return (
    <div className="flex min-h-[50vh] items-center justify-center bg-white">
      <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#0057B7] border-t-transparent" />
    </div>
  );
}

export default async function InstituteBookingCheckoutPage({ params }) {
  const { slug } = await params;

  return (
    <main className="min-h-screen bg-white md:hidden">
      <Suspense fallback={<CheckoutFallback />}>
        <MobileInstituteBookingCheckout slug={slug} />
      </Suspense>
    </main>
  );
}
