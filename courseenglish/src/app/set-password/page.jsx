"use client";

import { useState, useEffect } from "react";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { buildApiUrl } from "@/lib/courseenglishApi";
import { useRouter } from "next/navigation";

export default function SetPasswordPage() {
    const { isArabic } = useCourseEnglishSettings();
    const router = useRouter();
    const [password, setPassword] = useState("");
    const [passwordConfirmation, setPasswordConfirmation] = useState("");
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState("");

    // Check auth on mount
    useEffect(() => {
        const token = localStorage.getItem("auth_token");
        if (!token) {
            router.push("/login");
        }
    }, [router]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError("");

        if (password !== passwordConfirmation) {
            setError(isArabic ? "كلمات المرور غير متطابقة" : "Passwords do not match");
            return;
        }

        if (password.length < 6) {
            setError(isArabic ? "كلمة المرور يجب أن تكون 6 أحرف على الأقل" : "Password must be at least 6 characters");
            return;
        }

        setLoading(true);

        try {
            const token = localStorage.getItem("auth_token");
            const res = await fetch(buildApiUrl("/courseenglish/booking/set-password"), {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": `Bearer ${token}`
                },
                body: JSON.stringify({
                    password,
                    password_confirmation: passwordConfirmation
                }),
            });

            const json = await res.json();

            if (!res.ok) {
                throw new Error(json.message || "Failed to set password");
            }

            alert(isArabic ? "تم تعيين كلمة المرور بنجاح" : "Password set successfully");
            window.location.href = "/dashboard";

        } catch (e) {
            setError(e.message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <main className="min-h-screen flex items-center justify-center bg-[#FAFCFE] p-4" dir={isArabic ? "rtl" : "ltr"}>
            <div className="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <div className="text-center mb-8">
                    <h1 className="text-2xl font-medium text-slate-900 mb-2">
                        {isArabic ? "تعيين كلمة المرور" : "Set Your Password"}
                    </h1>
                    <p className="text-slate-500 text-sm">
                        {isArabic
                            ? "لإكمال إعداد حسابك، يرجى تعيين كلمة مرور جديدة."
                            : "To complete your account setup, please set a new password."}
                    </p>
                </div>

                <form onSubmit={handleSubmit} className="space-y-6">
                    <div>
                        <label className="block text-sm font-medium text-slate-700 mb-2">
                            {isArabic ? "كلمة المرور الجديدة" : "New Password"}
                        </label>
                        <input
                            type="password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            className="w-full h-12 rounded-xl border border-gray-200 px-4 focus:border-[#0057B7] outline-none"
                            placeholder="******"
                            required
                        />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-slate-700 mb-2">
                            {isArabic ? "تأكيد كلمة المرور" : "Confirm Password"}
                        </label>
                        <input
                            type="password"
                            value={passwordConfirmation}
                            onChange={(e) => setPasswordConfirmation(e.target.value)}
                            className="w-full h-12 rounded-xl border border-gray-200 px-4 focus:border-[#0057B7] outline-none"
                            placeholder="******"
                            required
                        />
                    </div>

                    {error && (
                        <div className="p-3 rounded-lg bg-red-50 text-red-600 text-sm text-center">
                            {error}
                        </div>
                    )}

                    <button
                        type="submit"
                        disabled={loading}
                        className="w-full h-12 rounded-xl bg-[#0057B7] text-white font-medium hover:bg-[#004494] transition disabled:opacity-50"
                    >
                        {loading ? "Processing..." : (isArabic ? "حفظ ومتابعة" : "Save & Continue")}
                    </button>

                    <button
                        type="button"
                        onClick={() => window.location.href = "/dashboard"}
                        className="w-full text-center text-sm text-slate-400 hover:text-slate-600 mt-4"
                    >
                        {isArabic ? "تخطي (سأقوم بذلك لاحقاً)" : "Skip (I'll do this later)"}
                    </button>
                </form>
            </div>
        </main>
    );
}
