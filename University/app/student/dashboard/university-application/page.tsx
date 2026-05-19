"use client";

import { useEffect, useState } from "react";
import Button from "@/components/ui/Button";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faSearch, faUniversity, faFileLines, faCalendarAlt, faGraduationCap } from "@fortawesome/free-solid-svg-icons";
import { useAuth } from "@/context/AuthContext";
import Link from "next/link";
import AuthGate from "@/components/AuthGate";

interface UniApplication {
    id: number;
    course: {
        id: number;
        name: string;
        university?: {
            name: string;
            logo_url?: string;
        };
    };
    intake: string;
    status: string;
    created_at: string;
}

export default function UniversityApplicationPage() {
    const { token, isAuthenticated } = useAuth();
    const [applications, setApplications] = useState<UniApplication[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchApps = async () => {
            if (!token) return;
            try {
                const API_URL = process.env.NEXT_PUBLIC_API_URL || "http://127.0.0.1:8000";
                const res = await fetch(`${API_URL}/api/my-uni-applications`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: "application/json",
                    },
                });
                const data = await res.json();
                if (data.success) {
                    setApplications(data.data);
                }
            } catch (err) {
                console.error("Failed to fetch university applications", err);
            } finally {
                setLoading(false);
            }
        };

        if (isAuthenticated) {
            fetchApps();
        }
    }, [token, isAuthenticated]);

    const getStatusColor = (status: string) => {
        const s = status?.toLowerCase() || 'pending';
        switch (s) {
            case 'approved': return 'bg-green-100 text-green-700';
            case 'rejected': return 'bg-red-100 text-red-700';
            case 'processing': return 'bg-blue-100 text-blue-700';
            default: return 'bg-yellow-100 text-yellow-700';
        }
    };

    return (
        <AuthGate allowedRoles={["uni_student"]}>
            <div className="space-y-8">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-slate-900">University Applications</h1>
                        <p className="text-slate-500 mt-1">Manage your course applications and track status.</p>
                    </div>
                </div>

                {loading ? (
                    <div className="p-12 text-center text-slate-400">Loading applications...</div>
                ) : applications.length > 0 ? (
                    <div className="space-y-6">
                        {/* List of Applications */}
                        <div className="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <div className="grid grid-cols-12 gap-4 p-4 bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider hidden md:grid">
                                <div className="col-span-5">Course & University</div>
                                <div className="col-span-3">Intake</div>
                                <div className="col-span-2 text-center">Status</div>
                                <div className="col-span-2 text-right">Applied Date</div>
                            </div>
                            <div className="divide-y divide-slate-50">
                                {applications.map((app) => (
                                    <div key={app.id} className="grid grid-cols-1 md:grid-cols-12 gap-4 p-6 items-center hover:bg-slate-50/50 transition-colors">
                                        <div className="md:col-span-5 flex items-center gap-4">
                                            <div className="h-12 w-12 rounded-lg bg-white border border-slate-200 p-1 flex items-center justify-center flex-shrink-0">
                                                {app.course?.university?.logo_url ? (
                                                    <img src={app.course.university.logo_url} alt={app.course.university.name} className="h-full w-full object-contain" />
                                                ) : (
                                                    <FontAwesomeIcon icon={faUniversity} className="text-slate-300 h-5 w-5" />
                                                )}
                                            </div>
                                            <div>
                                                <h3 className="font-bold text-slate-900 line-clamp-1">{app.course?.name || 'Unknown Course'}</h3>
                                                <p className="text-sm text-slate-500">{app.course?.university?.name || 'Unknown University'}</p>
                                            </div>
                                        </div>
                                        <div className="md:col-span-3 text-sm text-slate-600 flex items-center gap-2">
                                            <FontAwesomeIcon icon={faCalendarAlt} className="text-slate-400" />
                                            {app.intake}
                                        </div>
                                        <div className="md:col-span-2 flex justify-start md:justify-center">
                                            <span className={`px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide ${getStatusColor(app.status)}`}>
                                                {app.status || 'Pending'}
                                            </span>
                                        </div>
                                        <div className="md:col-span-2 text-left md:text-right text-sm text-slate-500">
                                            {new Date(app.created_at).toLocaleDateString()}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* 'Start New' Button Section (Small) */}
                        <div className="bg-blue-50/50 rounded-2xl border border-blue-100 p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                            <div>
                                <h3 className="font-bold text-blue-900">Want to apply for another course?</h3>
                                <p className="text-sm text-blue-700">Browse thousands of courses from top universities.</p>
                            </div>
                            <Button href="/courses" variant="primary" className="bg-[#1F63AE] hover:bg-[#175093]">
                                Browse Courses
                            </Button>
                        </div>
                    </div>
                ) : (
                    <div className="bg-white rounded-2xl border border-slate-100 p-12 text-center shadow-sm">
                        <div className="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <FontAwesomeIcon icon={faUniversity} className="w-10 h-10 text-[#1F63AE]" />
                        </div>
                        <h2 className="text-2xl font-bold text-slate-900 mb-2">No Applications Yet</h2>
                        <p className="text-slate-500 max-w-md mx-auto mb-8">
                            You haven't applied to any university courses yet. Browse our catalog and start your journey today.
                        </p>
                        <div className="flex flex-col sm:flex-row justify-center gap-4">
                            <Button href="/courses" variant="primary" size="lg">
                                <FontAwesomeIcon icon={faSearch} className="mr-2" />
                                Browse Courses
                            </Button>
                            <Button href="/universities" variant="outline" size="lg">
                                <FontAwesomeIcon icon={faUniversity} className="mr-2" />
                                Find Universities
                            </Button>
                        </div>
                    </div>
                )}
            </div>
        </AuthGate>
    );
}
