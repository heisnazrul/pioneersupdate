"use client";

import Image from "next/image";
import Link from "next/link";

export default function BookingConfirmedPage() {
  return (
    <main className="min-h-screen bg-[#F0F7FC] pb-24 pt-6">
      <div className="container mx-auto px-4">
        <div className="lg:hidden">
          <div className="flex flex-col items-center text-center">
            <div className="h-20 w-20 rounded-full bg-[#E8F9EF] flex items-center justify-center">
              <div className="h-16 w-16 rounded-full bg-[#22C55E] flex items-center justify-center text-white text-3xl">✓</div>
            </div>
            <h1 className="mt-6 text-2xl font-semibold text-slate-900">Your request was sent successfully</h1>
            <p className="mt-3 text-sm text-slate-600 leading-6">
              Our team will review your request and contact you on WhatsApp to confirm details and help you choose the best option before final confirmation.
            </p>
          </div>

          <div className="mt-6 rounded-2xl bg-[#E8F9EF] px-4 py-3 text-center text-lg font-medium text-[#22C55E]">
            +966 5554545
          </div>

          <div className="mt-4 rounded-2xl bg-white px-4 py-4 shadow-sm border border-gray-200 flex items-center justify-between">
            <div className="flex items-center gap-3">
              <div className="h-10 w-10 rounded-full bg-[#EAF4FD] flex items-center justify-center">📋</div>
              <div className="text-sm font-medium text-slate-900">Booking #5145555</div>
            </div>
          </div>

          <div className="mt-6 rounded-3xl border border-gray-200 bg-white p-4 shadow-sm">
            <div className="flex items-center justify-between">
            <div className="text-left">
              <div className="text-sm font-medium text-slate-900">CES School - Cork</div>
              <div className="text-xs text-slate-500">United Kingdom, London</div>
            </div>
              <div className="relative h-16 w-16 overflow-hidden rounded-xl">
                <Image src="/assets/images/institute_london.png" fill className="object-cover" alt="Institute" />
              </div>
            </div>
          </div>

          <div className="mt-6 text-left text-lg font-semibold text-slate-900">Price Summary</div>
          <div className="mt-2 rounded-3xl border border-gray-200 bg-white p-4 shadow-sm">
            <div className="grid grid-cols-2 gap-2 text-sm">
              <div className="text-left text-slate-700">General English (12 Weeks)</div>
              <div className="text-right font-medium">﷼ 19,303.08</div>
              <div className="text-left text-slate-700">Homestay (12 Weeks)</div>
              <div className="text-right font-medium">﷼ 11,546.28</div>
              <div className="text-left text-slate-700">Registration Fee</div>
              <div className="text-right font-medium">﷼ 567.45</div>
              <div className="text-left text-green-600">Course Discount</div>
              <div className="text-right font-medium text-green-600">- ﷼ 20.00</div>
              <div className="text-left text-green-600">Foundation Discount</div>
              <div className="text-right font-medium text-green-600">- ﷼ 20.00</div>
              <div className="text-left text-red-500">Total Discount</div>
              <div className="text-right font-medium text-red-500">﷼ 1,930.32</div>
            </div>
            <div className="mt-4 border-t border-gray-200 pt-4 flex items-center justify-between">
              <div className="text-left">
                <div className="text-sm font-medium text-slate-900">Total</div>
                <div className="text-xs text-slate-500">( Total includes all fees )</div>
              </div>
              <div className="text-lg font-semibold text-[#0B5DB6]">﷼ 29486.49</div>
            </div>
          </div>

          <div className="mt-6 flex gap-3">
            <button className="flex-1 rounded-2xl border border-gray-200 bg-white py-3 text-sm font-medium text-slate-700">My Bookings</button>
            <Link href="/" className="flex-1 rounded-2xl bg-[#0B5DB6] py-3 text-center text-sm font-medium text-white">Back to Home</Link>
          </div>
        </div>

        <div className="hidden lg:block">
          <div className="text-center py-24 text-slate-600">Desktop success page TBD.</div>
        </div>
      </div>
    </main>
  );
}
