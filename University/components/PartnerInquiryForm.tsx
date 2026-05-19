"use client";

import { useState } from 'react';

export default function PartnerInquiryForm() {
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [isSuccess, setIsSuccess] = useState(false);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);

        // Mock API call
        setTimeout(() => {
            setIsSubmitting(false);
            setIsSuccess(true);
        }, 1500);
    };

    if (isSuccess) {
        return (
            <div className="text-center p-8 bg-green-50 rounded-lg text-green-800">
                <h3 className="text-xl font-bold mb-2">Application Received!</h3>
                <p>Thank you for your interest in partnering with Pioneers Admissions. <br />Our partnership team will review your details and contact you within 48 hours.</p>
            </div>
        );
    }

    return (
        <div className="bg-white p-10 rounded-xl shadow-lg border border-gray-200">
            <h2 className="text-2xl font-bold text-primary mb-6 text-center">Become a Partner</h2>
            <form onSubmit={handleSubmit}>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label className="block text-sm font-semibold text-foreground mb-2">Company Name</label>
                        <input type="text" className="w-full px-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-secondary transition-colors" required placeholder="Global Edu..." />
                    </div>
                    <div>
                        <label className="block text-sm font-semibold text-foreground mb-2">Contact Person</label>
                        <input type="text" className="w-full px-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-secondary transition-colors" required placeholder="John Doe" />
                    </div>

                    <div className="col-span-1 md:col-span-2">
                        <label className="block text-sm font-semibold text-foreground mb-2">Business Email</label>
                        <input type="email" className="w-full px-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-secondary transition-colors" required placeholder="partner@company.com" />
                    </div>

                    <div>
                        <label className="block text-sm font-semibold text-foreground mb-2">Phone Number</label>
                        <input type="tel" className="w-full px-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-secondary transition-colors" required placeholder="+1 234..." />
                    </div>
                    <div>
                        <label className="block text-sm font-semibold text-foreground mb-2">Country</label>
                        <input type="text" className="w-full px-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-secondary transition-colors" required placeholder="India, Nigeria, etc." />
                    </div>

                    <div className="col-span-1 md:col-span-2">
                        <label className="block text-sm font-semibold text-foreground mb-2">Message / Questions</label>
                        <textarea className="w-full px-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-secondary transition-colors min-h-[120px]" placeholder="Tell us about your student recruitment experience..."></textarea>
                    </div>
                </div>

                <button
                    type="submit"
                    className="w-full bg-accent text-white py-4 rounded-md font-bold text-lg hover:bg-red-700 transition-colors mt-6 disabled:bg-gray-300 disabled:cursor-not-allowed"
                    disabled={isSubmitting}
                >
                    {isSubmitting ? 'Submitting...' : 'Submit Partnership Request'}
                </button>
            </form>
        </div>
    );
}
