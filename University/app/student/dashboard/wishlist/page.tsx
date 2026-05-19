"use client";

import { useEffect, useState } from "react";
import AuthGate from "@/components/AuthGate";
import CourseCard from "@/components/search/CourseCard";
import { Course } from "@/lib/data/types";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faHeart } from "@fortawesome/free-solid-svg-icons";
import Link from "next/link";
import Button from "@/components/ui/Button";

export default function WishlistPage() {
    const [wishlist, setWishlist] = useState<any[]>([]);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        const token = localStorage.getItem('token');
        if (token) {
            fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL || 'http://127.0.0.1:8000'}/api/wishlist`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (Array.isArray(data)) {
                        setWishlist(data);
                    }
                })
                .catch(err => console.error("Failed to fetch wishlist", err))
                .finally(() => setIsLoading(false));
        } else {
            setIsLoading(false);
        }
    }, []);

    return (
        <AuthGate allowedRoles={["uni_student"]}>
            <div className="space-y-6">
                <div>
                    <h1 className="text-2xl font-extrabold text-slate-900">My Wishlist</h1>
                    <p className="text-slate-500">View and manage your saved courses.</p>
                </div>

                {isLoading ? (
                    <div className="flex items-center justify-center p-12">
                        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
                ) : wishlist.length > 0 ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {wishlist.map((item) => (
                            <CourseCard key={item.id} course={item.course} />
                        ))}
                    </div>
                ) : (
                    <div className="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm">
                        <div className="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                            <FontAwesomeIcon icon={faHeart} className="w-8 h-8" />
                        </div>
                        <h3 className="text-xl font-bold text-slate-900 mb-2">Your wishlist is empty</h3>
                        <p className="text-slate-500 mb-8 max-w-sm mx-auto">
                            You haven't saved any courses yet. Browse our course catalog to find your perfect match.
                        </p>
                        <Button href="/search/courses" variant="primary">
                            Browse Courses
                        </Button>
                    </div>
                )}
            </div>
        </AuthGate>
    );
}
