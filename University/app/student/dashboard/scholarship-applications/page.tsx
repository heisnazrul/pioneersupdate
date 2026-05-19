"use client";

import { useState, useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSpinner, faFileAlt, faClock, faCheckCircle, faTimesCircle, faExclamationCircle } from '@fortawesome/free-solid-svg-icons';
import { useAuth } from '@/context/AuthContext';
import AuthGate from '@/components/AuthGate';

export default function ScholarshipApplicationsPage() {
    const [applications, setApplications] = useState<any[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const { token, isAuthenticated } = useAuth();

    useEffect(() => {
        const fetchApps = async () => {
            if (!token) return;

            try {
                const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000'}/api/my-scholarship-applications`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    setApplications(data);
                } else {
                    setError('Failed to fetch applications');
                }
            } catch (err) {
                setError('Network error');
            } finally {
                setLoading(false);
            }
        };

        if (isAuthenticated) {
            fetchApps();
        }
    }, [token, isAuthenticated]);

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'approved': return 'text-green-600 bg-green-50 border-green-200';
            case 'rejected': return 'text-red-600 bg-red-50 border-red-200';
            case 'reviewing': return 'text-blue-600 bg-blue-50 border-blue-200';
            default: return 'text-yellow-600 bg-yellow-50 border-yellow-200';
        }
    };

    const getStatusIcon = (status: string) => {
        switch (status) {
            case 'approved': return faCheckCircle;
            case 'rejected': return faTimesCircle;
            case 'reviewing': return faClock;
            default: return faExclamationCircle;
        }
    };

    if (loading) return <div className="p-10 text-center text-gray-500"><FontAwesomeIcon icon={faSpinner} spin className="mr-2" /> Loading applications...</div>;

    return (
        <AuthGate allowedRoles={["uni_student"]}>
            <div className="space-y-6">
                <h1 className="text-2xl font-bold text-slate-800">My Scholarship Applications</h1>

                {applications.length === 0 ? (
                    <div className="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300">
                        <div className="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                            <FontAwesomeIcon icon={faFileAlt} />
                        </div>
                        <h3 className="text-lg font-bold text-gray-900 mb-2">No Applications Yet</h3>
                        <p className="text-gray-500 mb-6">You haven&apos;t applied for any scholarships yet.</p>
                    </div>
                ) : (
                    <div className="grid gap-4">
                        {applications.map((app) => (
                            <div key={app.id} className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-6 justify-between items-start md:items-center hover:shadow-md transition-shadow">
                                <div>
                                    <div className="flex items-center gap-2 mb-2">
                                        <span className="text-sm font-mono text-gray-400">#{app.application_id || 'N/A'}</span>
                                        <span className="text-xs text-gray-400">• {new Date(app.created_at).toLocaleDateString()}</span>
                                    </div>
                                    <h3 className="text-lg font-bold text-slate-900">{app.scholarship_title || 'Unknown Scholarship'}</h3>
                                    <p className="text-sm text-gray-500">Applicant: {app.first_name} {app.last_name}</p>
                                </div>

                                <div className={`px-4 py-2 rounded-xl border flex items-center gap-2 font-bold text-sm ${getStatusColor(app.status)}`}>
                                    <FontAwesomeIcon icon={getStatusIcon(app.status)} />
                                    <span className="capitalize">{app.status}</span>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </AuthGate>
    );
}
