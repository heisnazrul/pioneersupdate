"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import AuthGate from "@/components/AuthGate";
import Button from "@/components/ui/Button";

export default function CompleteProfile() {
    const router = useRouter();
    const [user, setUser] = useState<any>(null);
    const [formData, setFormData] = useState({
        phone: "",
        nationality: "",
        dob: "",
        studyLevel: "Bachelor",
    });

    useEffect(() => {
        const userStr = localStorage.getItem("auth_user");
        if (userStr) {
            setUser(JSON.parse(userStr));
        }
    }, []);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        try {
            const token = localStorage.getItem("auth_token"); // Assuming token is stored here
            if (!token) throw new Error("No auth token found");

            const response = await import("@/lib/api").then(mod => mod.updateProfile(token, formData));

            if (response.success) {
                // Update local user object
                const updatedUser = { ...user, ...formData, profile_completed: true };
                localStorage.setItem("auth_user", JSON.stringify(updatedUser)); // Update local storage

                // Trigger event so Navbar knows
                window.dispatchEvent(new Event('auth-update'));

                alert("Profile complete! redirecting...");
                router.push("/student/dashboard");
            } else {
                alert(response.message || "Failed to update profile");
            }
        } catch (error) {
            console.error("Profile update error:", error);
            alert("An error occurred. Please try again.");
        }
    };

    return (
        <AuthGate allowedRoles={["uni_student"]}>
            <div className="min-h-screen bg-slate-50 flex items-center justify-center p-4">
                <div className="max-w-md w-full bg-white rounded-3xl shadow-xl p-8 border border-slate-100">
                    <div className="text-center mb-8">
                        <h1 className="text-2xl font-extrabold text-slate-900">Complete your profile</h1>
                        <p className="text-slate-500 mt-2">We need a few more details to get you started.</p>
                    </div>

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <label className="block text-sm font-semibold text-slate-700 mb-1">Full Name</label>
                            <input
                                type="text"
                                value={user?.name || ""}
                                disabled
                                className="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-500 cursor-not-allowed"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                            <input
                                type="email"
                                value={user?.email || ""}
                                disabled
                                className="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-500 cursor-not-allowed"
                            />
                        </div>

                        <div>
                            <label className="block text-sm font-semibold text-slate-700 mb-1">Phone Number</label>
                            <input
                                type="tel"
                                name="phone"
                                required
                                placeholder="+1 234 567 8900"
                                onChange={handleChange}
                                className="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-[#1F63AE] focus:ring-2 focus:ring-[#1F63AE]/20 outline-none transition"
                            />
                        </div>

                        <div>
                            <label className="block text-sm font-semibold text-slate-700 mb-1">Nationality</label>
                            <input
                                type="text"
                                name="nationality"
                                required
                                placeholder="Your Nationality"
                                onChange={handleChange}
                                className="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-[#1F63AE] focus:ring-2 focus:ring-[#1F63AE]/20 outline-none transition"
                            />
                        </div>

                        <Button type="submit" variant="primary" fullWidth size="lg">
                            Complete Profile
                        </Button>
                    </form>
                </div>
            </div>
        </AuthGate>
    );
}
