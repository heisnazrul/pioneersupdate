"use client";

import { useEffect, useState } from "react";
import AuthGate from "@/components/AuthGate";
import Button from "@/components/ui/Button";

export default function StudentProfile() {
    const [user, setUser] = useState<any>(null);
    const [isEditing, setIsEditing] = useState(false);
    const [formData, setFormData] = useState<any>({});

    useEffect(() => {
        const token = localStorage.getItem('token');
        const userStr = localStorage.getItem("auth_user");
        if (userStr) {
            const parsed = JSON.parse(userStr);
            setUser(parsed);
            // Initialize form with existing data + profile data if available
            setFormData({
                name: parsed.name,
                email: parsed.email,
                phone: parsed.phone || parsed.profile?.phone || "",
                nationality: parsed.profile?.nationality || "", // accessing profile relation
                studyLevel: parsed.profile?.study_level || "",
                dob: parsed.profile?.date_of_birth || "",
            });
        }

        // Fetch latest user data from API to get profile
        if (token) {
            fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL || 'http://127.0.0.1:8000'}/api/university/user`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    setUser(data);
                    localStorage.setItem('auth_user', JSON.stringify(data));
                    setFormData({
                        name: data.name,
                        email: data.email,
                        phone: data.phone || data.profile?.phone || "",
                        nationality: data.profile?.nationality || "",
                        studyLevel: data.profile?.study_level || "",
                        dob: data.profile?.date_of_birth || "",
                    });
                })
                .catch(err => console.error("Failed to fetch user profile", err));
        }
    }, []);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSave = async () => {
        const token = localStorage.getItem('token');
        if (!token) {
            alert("You are not logged in.");
            return;
        }

        try {
            const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL || 'http://127.0.0.1:8000'}/api/profile/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    phone: formData.phone,
                    nationality: formData.nationality,
                    dob: formData.dob,
                    studyLevel: formData.studyLevel
                })
            });

            const data = await response.json();

            if (data.success) {
                localStorage.setItem("auth_user", JSON.stringify(data.user));
                setUser(data.user);
                setIsEditing(false);
                window.dispatchEvent(new Event('auth-update')); // Sync navbar
                alert("Profile updated successfully!");
            } else {
                alert(data.message || "Failed to update profile.");
            }
        } catch (e) {
            console.error("Profile update error", e);
            alert("An error occurred while saving.");
        }
    };

    if (!user) return null;

    return (
        <AuthGate allowedRoles={["uni_student"]}>
            <div className="max-w-4xl space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-extrabold text-slate-900">My Profile</h1>
                        <p className="text-slate-500">Manage your personal information.</p>
                    </div>
                    <Button
                        onClick={() => isEditing ? handleSave() : setIsEditing(true)}
                        variant={isEditing ? "primary" : "outline"}
                    >
                        {isEditing ? "Save Changes" : "Edit Profile"}
                    </Button>
                </div>

                <div className="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                    {/* Header with Avatar */}
                    <div className="flex items-center gap-6 mb-8 pb-8 border-b border-slate-50">
                        <div className="h-24 w-24 rounded-full overflow-hidden border-4 border-slate-50 shadow-inner bg-slate-100 flex-shrink-0 relative group">
                            {user.avatar ? (
                                <img src={user.avatar} alt="Profile" className="h-full w-full object-cover" />
                            ) : (
                                <div className="h-full w-full bg-[#1F63AE] flex items-center justify-center text-white text-3xl font-bold">
                                    {user.name?.charAt(0)}
                                </div>
                            )}
                        </div>
                        <div>
                            <h2 className="text-xl font-bold text-slate-900">{user.name}</h2>
                            <p className="text-slate-500">{user.email}</p>
                            <div className="mt-2 text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded inline-block uppercase">
                                {user.role?.replace(/_/g, " ")}
                            </div>
                        </div>
                    </div>

                    {/* Form Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Full Name</label>
                            <input
                                type="text"
                                name="name"
                                value={formData.name || ""}
                                onChange={handleChange}
                                disabled={!isEditing}
                                className="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 font-medium outline-none disabled:text-slate-500 disabled:cursor-not-allowed focus:bg-white focus:border-[#1F63AE] focus:ring-2 focus:ring-[#1F63AE]/20 transition-all"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Email Address</label>
                            <input
                                type="email"
                                value={formData.email || ""}
                                disabled
                                className="w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-slate-500 font-medium cursor-not-allowed"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Phone Number</label>
                            <input
                                type="tel"
                                name="phone"
                                value={formData.phone || ""}
                                onChange={handleChange}
                                disabled={!isEditing}
                                placeholder={isEditing ? "Enter phone number" : "Not set"}
                                className="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 font-medium outline-none disabled:text-slate-500 disabled:cursor-not-allowed focus:bg-white focus:border-[#1F63AE] focus:ring-2 focus:ring-[#1F63AE]/20 transition-all"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Nationality</label>
                            <input
                                type="text"
                                name="nationality"
                                value={formData.nationality || ""}
                                onChange={handleChange}
                                disabled={!isEditing}
                                placeholder={isEditing ? "Enter nationality" : "Not set"}
                                className="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 font-medium outline-none disabled:text-slate-500 disabled:cursor-not-allowed focus:bg-white focus:border-[#1F63AE] focus:ring-2 focus:ring-[#1F63AE]/20 transition-all"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </AuthGate>
    );
}
