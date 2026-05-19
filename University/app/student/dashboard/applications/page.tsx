"use client";

import { useEffect, useState } from "react";
import AuthGate from "@/components/AuthGate";
import Button from "@/components/ui/Button";

interface Application {
    id: number;
    application_id: string;
    first_name: string;
    last_name: string;
    highest_education: string;
    status: string;
    created_at: string;
    destination_interest: string[];
}

import { useAuth } from "@/context/AuthContext";

export default function StudentApplications() {
    const [applications, setApplications] = useState<Application[]>([]);
    const [loading, setLoading] = useState(true);
    const { token, isAuthenticated } = useAuth();

    useEffect(() => {
        const fetchApplications = async () => {
            if (!token) return;

            try {
                const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000'}/api/my-applications`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (res.ok) {
                    const data = await res.json();
                    if (data.success) {
                        setApplications(data.data);
                    }
                }
            } catch (error) {
                console.error("Failed to fetch applications", error);
            } finally {
                setLoading(false);
            }
        };

        if (isAuthenticated) {
            fetchApplications();
        }
    }, [token, isAuthenticated]);

    const getStatusColor = (status: string) => {
        switch (status.toLowerCase()) {
            case 'pending': return 'bg-yellow-100 text-yellow-700';
            case 'reviewing': return 'bg-blue-100 text-blue-700';
            case 'contacted': return 'bg-purple-100 text-purple-700';
            case 'accepted': return 'bg-green-100 text-green-700';
            case 'rejected': return 'bg-red-100 text-red-700';
            default: return 'bg-gray-100 text-gray-700';
        }
    };

    return (
        <AuthGate allowedRoles={["uni_student"]}>
            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-extrabold text-slate-900">My Applications</h1>
                        <p className="text-slate-500">Track and manage your university applications.</p>
                    </div>
                    <Button href="/search/courses" variant="primary">
                        + New Application
                    </Button>
                </div>

                <div className="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    {/* List Header */}
                    <div className="grid grid-cols-12 gap-4 p-4 bg-slate-50/50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider hidden md:grid">
                        <div className="col-span-4">Application ID</div>
                        <div className="col-span-4">Destinations</div>
                        <div className="col-span-2 text-center">Status</div>
                        <div className="col-span-2 text-right">Date</div>
                    </div>

                    {loading ? (
                        <div className="p-12 text-center text-slate-400">Loading your applications...</div>
                    ) : applications.length > 0 ? (
                        <div className="divide-y divide-slate-50">
                            {applications.map((app) => (
                                <div key={app.application_id} className="grid grid-cols-1 md:grid-cols-12 gap-4 p-6 items-center hover:bg-slate-50 transition-colors">
                                    <div className="md:col-span-4 flex items-center gap-4">
                                        <div className="h-10 w-10 rounded-full bg-blue-50 text-[#1F63AE] flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            APP
                                        </div>
                                        <div>
                                            <div className="font-bold text-slate-900">#{app.application_id}</div>
                                            <div className="text-sm text-slate-500">{app.highest_education}</div>
                                        </div>
                                    </div>
                                    <div className="md:col-span-4 text-sm text-slate-600">
                                        {Array.isArray(app.destination_interest) ? app.destination_interest.join(', ') : 'Global'}
                                    </div>
                                    <div className="md:col-span-2 flex justify-start md:justify-center">
                                        <span className={`px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide ${getStatusColor(app.status)}`}>
                                            {app.status}
                                        </span>
                                    </div>
                                    <div className="md:col-span-2 text-left md:text-right text-sm text-slate-500 font-medium">
                                        {new Date(app.created_at).toLocaleDateString()}
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="p-12 text-center">
                            <p className="text-slate-400 mb-4">You haven't submitted any applications yet.</p>
                            <Button href="/apply-now" variant="outline">Start Application</Button>
                        </div>
                    )}
                </div>
            </div>
        </AuthGate>
    );
}
