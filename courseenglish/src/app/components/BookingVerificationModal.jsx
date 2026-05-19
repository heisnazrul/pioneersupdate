"use client";

import { useEffect, useState } from "react";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { buildApiUrl } from "@/lib/courseenglishApi";

export default function BookingVerificationModal({ isOpen, onClose, phone, onVerified }) {
    const { isArabic } = useCourseEnglishSettings();
    const [step, setStep] = useState(1); // 1: Confirm Phone, 2: Input OTP
    const [otp, setOtp] = useState(["", "", "", "", "", ""]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState("");
    const [debugOtp, setDebugOtp] = useState(null);

    useEffect(() => {
        if (isOpen) {
            setStep(1);
            setOtp(["", "", "", "", "", ""]);
            setError("");
            setDebugOtp(null);
        }
    }, [isOpen]);

    const handleSendOtp = async () => {
        setLoading(true);
        setError("");
        try {
            const res = await fetch(buildApiUrl("/courseenglish/booking/send-otp"), {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ phone }),
            });
            const json = await res.json();
            if (!res.ok) throw new Error(json.message || "Failed to send OTP");

            setDebugOtp(json.debug_otp); // For testing
            setStep(2);
        } catch (e) {
            setError(e.message);
        } finally {
            setLoading(false);
        }
    };

    const handleVerifyOtp = async () => {
        setLoading(true);
        setError("");
        const code = otp.join("");
        try {
            const res = await fetch(buildApiUrl("/courseenglish/booking/verify-otp"), {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ phone, otp: code }),
            });
            const json = await res.json();
            if (!res.ok) throw new Error(json.message || "Invalid OTP");

            onVerified(json.verification_token);
            onClose();
        } catch (e) {
            setError(e.message);
        } finally {
            setLoading(false);
        }
    };

    const handleOtpChange = (index, value) => {
        if (isNaN(value)) return;
        const newOtp = [...otp];
        newOtp[index] = value;
        setOtp(newOtp);

        // Auto move to next input
        if (value && index < 5) {
            document.getElementById(`otp-input-${index + 1}`).focus();
        }
    };

    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 animate-in fade-in duration-200">
            <div className="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl" dir={isArabic ? "rtl" : "ltr"}>
                <div className="mb-4 flex items-center justify-between">
                    <h3 className="text-xl font-medium text-slate-900">
                        {isArabic ? "تأكيد رقم الهاتف" : "Verify Phone Number"}
                    </h3>
                    <button onClick={onClose} className="text-slate-400 hover:text-slate-600">
                        ✕
                    </button>
                </div>

                <p className="mb-6 text-sm text-slate-600">
                    {step === 1
                        ? (isArabic ? `سيتم إرسال رمز التحقق إلى ${phone}` : `We will send a verification code to ${phone}`)
                        : (isArabic ? `أدخل الرمز المرسل إلى ${phone}` : `Enter the code sent to ${phone}`)
                    }
                </p>

                {error && (
                    <div className="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-600">
                        {error}
                    </div>
                )}

                {debugOtp && (
                    <div className="mb-4 rounded-lg bg-blue-50 p-3 text-xs text-blue-600 font-mono">
                        DEBUG OTP: {debugOtp}
                    </div>
                )}

                {step === 1 && (
                    <div className="flex justify-end gap-3">
                        <button
                            onClick={onClose}
                            className="rounded-lg px-4 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50"
                        >
                            {isArabic ? "إلغاء" : "Cancel"}
                        </button>
                        <button
                            onClick={handleSendOtp}
                            disabled={loading}
                            className="rounded-lg bg-[#0057B7] px-6 py-2 text-sm font-medium text-white hover:bg-[#004494] disabled:opacity-50"
                        >
                            {loading ? "In Progress..." : (isArabic ? "أرسل الرمز" : "Send Code")}
                        </button>
                    </div>
                )}

                {step === 2 && (
                    <div>
                        <div className="mb-6 flex justify-center gap-2" dir="ltr">
                            {otp.map((digit, i) => (
                                <input
                                    key={i}
                                    id={`otp-input-${i}`}
                                    type="text"
                                    maxLength={1}
                                    value={digit}
                                    onChange={(e) => handleOtpChange(i, e.target.value)}
                                    className="h-12 w-12 rounded-lg border border-slate-300 text-center text-xl font-medium focus:border-[#0057B7] focus:outline-none focus:ring-1 focus:ring-[#0057B7]"
                                />
                            ))}
                        </div>
                        <div className="flex justify-end gap-3">
                            <button
                                onClick={() => setStep(1)}
                                className="rounded-lg px-4 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50"
                            >
                                {isArabic ? "رجوع" : "Back"}
                            </button>
                            <button
                                onClick={handleVerifyOtp}
                                disabled={loading || otp.join("").length < 6}
                                className="rounded-lg bg-[#0057B7] px-6 py-2 text-sm font-medium text-white hover:bg-[#004494] disabled:opacity-50"
                            >
                                {loading ? "Verifying..." : (isArabic ? "تحقق" : "Verify")}
                            </button>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
