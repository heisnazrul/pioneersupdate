"use client";

import { useState } from "react";
import Image from "next/image";

const COUNTRIES = [
  { code: "SA", dialCode: "+966", flagFile: "sa.svg", name: "Saudi Arabia" },
  { code: "GB", dialCode: "+44", flagFile: "gb.svg", name: "United Kingdom" },
  { code: "US", dialCode: "+1", flagFile: "us.svg", name: "United States" },
  // add more as needed...
];

function BottomSheet({ isOpen, onClose, children }) {
  return (
    <>
      {/* backdrop */}
      <div
        className={`fixed inset-0 z-[70] bg-black/40 transition-opacity ${isOpen ? "opacity-100 pointer-events-auto" : "opacity-0 pointer-events-none"
          }`}
        onClick={onClose}
      />

      {/* sheet */}
      <div
        className={`fixed inset-0 z-[80] flex items-end transition-transform duration-300 ease-out ${isOpen ? "translate-y-0" : "translate-y-full"
          }`}
        onClick={onClose}
      >
        <div
          className="w-full"
          onClick={(e) => e.stopPropagation()}
        >
          <div className="mx-auto max-w-md rounded-t-3xl bg-white p-5 pb-[calc(env(safe-area-inset-bottom,0px)+20px)] shadow-2xl">
            {/* drag handle */}
            <div className="mx-auto mb-4 h-1 w-12 rounded-full bg-gray-300" />
            {children}
          </div>
        </div>
      </div>
    </>
  );
}

/* ---------------------- PHONE SIGN-UP SHEET ---------------------- */

export function PhoneSignUpSheet({
  isOpen,
  onClose,
  onOpenLogin,
  onOpenEmailSignUp,
}) {
  const [selectedCountry, setSelectedCountry] = useState(COUNTRIES[0]);
  const [phone, setPhone] = useState("");
  const [showCountryList, setShowCountryList] = useState(false);

  return (
    <BottomSheet isOpen={isOpen} onClose={onClose}>
      <div className="space-y-5">
        <div className="text-center">
          <h2 className="text-lg font-normal">Welcome 👋</h2>
          <p className="mt-1 text-sm text-gray-500">
            Create your account to continue your journey.
          </p>
        </div>

        {/* phone input */}
        <div className="flex rounded-2xl border border-gray-300 bg-gray-50 px-3 py-3">
          {/* phone number */}
          <input
            type="tel"
            className="flex-1 bg-transparent pr-3 text-sm outline-none"
            placeholder="55 233 9595"
            value={phone}
            onChange={(e) => setPhone(e.target.value)}
          />

          <div className="mx-2 w-px bg-gray-300" />

          {/* country selector */}
          <button
            type="button"
            className="flex items-center gap-2 text-sm font-normal text-gray-900"
            onClick={() => setShowCountryList((v) => !v)}
          >
            <span className="flex items-center justify-center overflow-hidden  border border-gray-200">
              <Image
                src={`/assets/flags/${selectedCountry.flagFile}`}
                alt={selectedCountry.name}
                width={20}
                height={20}
              />
            </span>
            <span>{selectedCountry.dialCode}</span>
            <span className="text-xs text-gray-500">▼</span>
          </button>
        </div>

        {/* country dropdown */}
        {showCountryList && (
          <div className="max-h-48 overflow-y-auto rounded-2xl border border-gray-200 bg-white text-sm shadow-md">
            {COUNTRIES.map((country) => (
              <button
                key={country.code}
                type="button"
                className="flex w-full items-center gap-2 px-3 py-2  hover:bg-gray-50"
                onClick={() => {
                  setSelectedCountry(country);
                  setShowCountryList(false);
                }}
              >
                <span className="flex items-center justify-center overflow-hidden border border-gray-200">
                  <Image
                    src={`/assets/flags/${country.flagFile}`}
                    alt={country.name}
                    width={18}
                    height={18}
                  />
                </span>
                <span className="flex-1">{country.name}</span>
                <span className="font-normal">{country.dialCode}</span>
              </button>
            ))}
          </div>
        )}

        {/* primary CTA */}
        <button
          type="button"
          className="flex w-full items-center justify-center rounded-2xl bg-blue-600 py-3 text-sm font-normal text-white hover:bg-blue-700"
        >
          Continue with phone
        </button>

        {/* email signup button */}
        <button
          type="button"
          onClick={onOpenEmailSignUp}
          className="flex w-full items-center justify-center rounded-2xl border border-gray-300 bg-white py-3 text-sm font-normal text-gray-800 hover:bg-gray-50"
        >
          Sign up with email instead
        </button>

        {/* link to login */}
        <button
          type="button"
          onClick={onOpenLogin}
          className="mx-auto block text-xs font-normal text-gray-500 underline"
        >
          Already have an account? Log in
        </button>
      </div>
    </BottomSheet>
  );
}

/* ------------------------- EMAIL LOGIN SHEET ------------------------- */

export function EmailSignUpSheet({ isOpen, onClose, onUsePhone }) {
  return (
    <BottomSheet isOpen={isOpen} onClose={onClose}>
      <form
        className="space-y-5"
        onSubmit={(e) => {
          e.preventDefault();
        }}
      >
        <div className="text-center">
          <h2 className="text-lg font-normal">Create your account</h2>
          <p className="mt-1 text-sm text-gray-500">
            Sign up with your email to continue your journey.
          </p>
        </div>

        <div className="space-y-3">
          <div className="space-y-1">
            <label className="text-xs font-normal text-gray-600">Email</label>
            <input
              type="email"
              className="w-full rounded-2xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-blue-500"
              placeholder="you@example.com"
            />
          </div>

          <div className="space-y-1">
            <label className="text-xs font-normal text-gray-600">Password</label>
            <input
              type="password"
              className="w-full rounded-2xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-blue-500"
              placeholder="••••••••"
            />
          </div>

          <div className="space-y-1">
            <label className="text-xs font-normal text-gray-600">Confirm Password</label>
            <input
              type="password"
              className="w-full rounded-2xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-blue-500"
              placeholder="••••••••"
            />
          </div>
        </div>

        <button
          type="submit"
          className="flex w-full items-center justify-center rounded-2xl bg-blue-600 py-3 text-sm font-normal text-white hover:bg-blue-700"
        >
          Sign up with email
        </button>

        <button
          type="button"
          onClick={onUsePhone}
          className="mx-auto block text-xs font-normal text-gray-500 underline"
        >
          Use phone instead
        </button>
      </form>
    </BottomSheet>
  );
}

export function EmailLoginSheet({ isOpen, onClose, onUsePhone }) {
  return (
    <BottomSheet isOpen={isOpen} onClose={onClose}>
      <form
        className="space-y-5"
        onSubmit={(e) => {
          e.preventDefault();
        }}
      >
        <div className="text-center">
          <h2 className="text-lg font-normal">Welcome back 👋</h2>
          <p className="mt-1 text-sm text-gray-500">
            Log in to continue your journey.
          </p>
        </div>

        <div className="space-y-3">
          <div className="space-y-1">
            <label className="text-xs font-normal text-gray-600">Email</label>
            <input
              type="email"
              className="w-full rounded-2xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-blue-500"
              placeholder="you@example.com"
            />
          </div>

          <div className="space-y-1">
            <label className="text-xs font-normal text-gray-600">Password</label>
            <input
              type="password"
              className="w-full rounded-2xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-blue-500"
              placeholder="••••••••"
            />
          </div>
        </div>

        <button
          type="submit"
          className="flex w-full items-center justify-center rounded-2xl bg-blue-600 py-3 text-sm font-normal text-white hover:bg-blue-700"
        >
          Log in
        </button>

        <button
          type="button"
          onClick={onUsePhone}
          className="mx-auto block text-xs font-normal text-gray-500 underline"
        >
          Use phone instead
        </button>
      </form>
    </BottomSheet>
  );
}
