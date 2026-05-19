"use client";

import { useEffect, useState } from "react";
import Button from "@/components/ui/Button";
import AuthGate from "@/components/AuthGate";
import Image from "next/image";
import { useAuth } from "@/context/AuthContext";


export default function StudentDashboard() {
    const { user, token, isLoading: authLoading } = useAuth();
    const [stats, setStats] = useState([
        { label: "Active Applications", value: "0", color: "bg-blue-50 text-blue-600", key: "active_applications" },
        { label: "Saved Courses", value: "0", color: "bg-purple-50 text-purple-600", key: "saved_courses" },
        { label: "Messages", value: "0", color: "bg-orange-50 text-orange-600", key: "messages" },
    ]);
    const [recentActivity, setRecentActivity] = useState<any>(null);
    const [dashboardLoading, setDashboardLoading] = useState(true);

    useEffect(() => {
        if (token) {
            setDashboardLoading(true);
            fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL || 'http://127.0.0.1:8000'}/api/student/dashboard`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.stats) {
                        setStats(prevStats => prevStats.map(stat => ({
                            ...stat,
                            value: data.stats[stat.key]?.toString() || "0"
                        })));
                    }
                    if (data.recent_activity) {
                        setRecentActivity(data.recent_activity);
                    }
                })
                .catch(err => console.error("Failed to fetch dashboard data", err))
                .finally(() => setDashboardLoading(false));
        } else if (!authLoading) {
            setDashboardLoading(false);
        }
    }, [token, authLoading]);

    if (authLoading || (dashboardLoading && !user)) {
        return (
            <div className="flex items-center justify-center min-h-[60vh]">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
        );
    }

    if (!user) return null;

    return (
        <AuthGate allowedRoles={["uni_student"]}>
            <div className="space-y-8">
                {/* Welcome Header */}
                <div className="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-extrabold text-slate-900">
                            Welcome back, {user.name?.split(' ')[0]}! 👋
                        </h1>
                        <p className="text-slate-500 mt-2">
                            Here is what's happening with your applications today.
                        </p>
                    </div>
                    <div className="hidden md:block">
                        <div className="h-16 w-16 rounded-full overflow-hidden border-2 border-slate-100">
                            {user.avatar ? (
                                <img src={user.avatar} alt="Profile" className="h-full w-full object-cover" />
                            ) : (
                                <div className="h-full w-full bg-blue-600 flex items-center justify-center text-white font-bold text-xl">
                                    {user.name?.charAt(0)}
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {stats.map((stat) => (
                        <div key={stat.label} className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                            <div className="text-4xl font-extrabold text-slate-900 mb-2">
                                {dashboardLoading ? (
                                    <span className="inline-block w-8 h-8 rounded-full border-2 border-slate-200 border-t-slate-400 animate-spin"></span>
                                ) : stat.value}
                            </div>
                            <div className={`text-sm font-semibold px-3 py-1 rounded-full inline-block ${stat.color}`}>
                                {stat.label}
                            </div>
                        </div>
                    ))}
                </div>

                {/* Recent Activity / CTA */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div className="bg-gradient-to-br from-[#1F63AE] to-[#144275] rounded-3xl p-8 text-white relative overflow-hidden">
                        <div className="relative z-10">
                            <h3 className="text-2xl font-bold mb-3">Start a New Application</h3>
                            <p className="text-blue-100 mb-6 max-w-sm">
                                Ready to take the next step? Browse thousands of courses from top universities.
                            </p>
                            <Button href="/search/courses" variant="white" size="lg">
                                Browse Courses
                            </Button>
                        </div>
                        {/* Decorative Circle */}
                        <div className="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    </div>

                    <div className="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                        <h3 className="text-xl font-bold text-slate-900 mb-6">Application Status</h3>
                        {recentActivity ? (
                            <div className="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                                <div className="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                    <i className="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <h4 className="font-bold text-slate-900">{recentActivity.title}</h4>
                                    <p className="text-sm text-slate-500">Status: <span className="capitalize font-medium text-slate-700">{recentActivity.status}</span> • {recentActivity.date}</p>
                                </div>
                            </div>
                        ) : (
                            <div className="flex flex-col items-center justify-center h-40 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                <p>You haven't submitted any applications yet.</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AuthGate>
    );
}
