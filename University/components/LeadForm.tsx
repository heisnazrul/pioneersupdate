"use client";

import { useState } from 'react';
import { submitLead, LeadPayload } from '@/lib/data';

interface Props {
    source?: string;
    topic?: string; // Pre-selected topic
    compact?: boolean; // For sidebar usage
    title?: string;
}

export default function LeadForm({ source = 'contact', topic = '', compact = false, title = 'Send us a Message' }: Props) {
    const [formData, setFormData] = useState<LeadPayload>({
        name: '',
        email: '',
        phone: '',
        topic: topic,
        message: '',
        source: source,
    });

    const [isSubmitting, setIsSubmitting] = useState(false);
    const [isSuccess, setIsSuccess] = useState(false);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);

        const result = await submitLead(formData);

        if (result.success) {
            setIsSuccess(true);
        } else {
            alert('Something went wrong. Please try again.');
        }
        setIsSubmitting(false);
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        setFormData(prev => ({ ...prev, [e.target.name]: e.target.value }));
    };

    if (isSuccess) {
        return (
            <div className={`bg-white rounded-lg ${compact ? 'p-6' : 'p-8 shadow-sm border border-gray-200'}`}>
                <div className="text-center p-8 bg-green-50 rounded-lg text-green-800">
                    <h3 className="text-xl font-bold mb-2">Message Sent!</h3>
                    <p>We will get back to you shortly.</p>
                </div>
            </div>
        );
    }

    return (
        <div className={`bg-white rounded-lg ${compact ? 'p-6' : 'p-8 shadow-sm border border-gray-200'}`}>
            <h3 className={`font-bold text-primary mb-6 ${compact ? 'text-xl' : 'text-2xl'}`}>{title}</h3>
            <form onSubmit={handleSubmit}>
                <div className="mb-4">
                    <label className="block text-sm font-semibold text-foreground mb-2">Full Name</label>
                    <input
                        type="text"
                        name="name"
                        value={formData.name}
                        onChange={handleChange}
                        className="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-secondary transition-colors"
                        required
                        placeholder="John Doe"
                    />
                </div>

                <div className="mb-4">
                    <label className="block text-sm font-semibold text-foreground mb-2">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        value={formData.email}
                        onChange={handleChange}
                        className="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-secondary transition-colors"
                        required
                        placeholder="john@example.com"
                    />
                </div>

                <div className="mb-4">
                    <label className="block text-sm font-semibold text-foreground mb-2">Phone Number</label>
                    <input
                        type="tel"
                        name="phone"
                        value={formData.phone}
                        onChange={handleChange}
                        className="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-secondary transition-colors"
                        required
                        placeholder="+1 234 567 890"
                    />
                </div>

                {!compact && (
                    <div className="mb-4">
                        <label className="block text-sm font-semibold text-foreground mb-2">Topic</label>
                        <select
                            name="topic"
                            value={formData.topic}
                            onChange={handleChange}
                            className="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-secondary transition-colors"
                        >
                            <option value="">Select a Topic...</option>
                            <option value="Admissions">Admissions</option>
                            <option value="Visa">Visa Inquiry</option>
                            <option value="Partnership">Partnership</option>
                            <option value="General">General Inquiry</option>
                        </select>
                    </div>
                )}

                {compact && (
                    <div className="mb-4">
                        <label className="block text-sm font-semibold text-foreground mb-2">Preferred Intake</label>
                        <select
                            name="topic" // Using topic field for intake in this case for simplicity
                            value={formData.topic}
                            onChange={handleChange}
                            className="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-secondary transition-colors"
                        >
                            <option value="Fall 2024">Fall 2024</option>
                            <option value="Spring 2025">Spring 2025</option>
                        </select>
                    </div>
                )}

                <div className="mb-6">
                    <label className="block text-sm font-semibold text-foreground mb-2">Message</label>
                    <textarea
                        name="message"
                        value={formData.message}
                        onChange={handleChange}
                        className="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-secondary transition-colors min-h-[120px]"
                        placeholder="How can we help you?"
                    ></textarea>
                </div>

                <button
                    type="submit"
                    className={`w-full bg-red-600 text-white font-bold rounded-md hover:bg-red-700 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] disabled:bg-gray-300 disabled:cursor-not-allowed disabled:transform-none ${compact ? 'py-3 text-base' : 'py-4 text-lg'}`}
                    disabled={isSubmitting}
                >
                    {isSubmitting ? 'Sending...' : 'Send Message'}
                </button>
            </form>
        </div>
    );
}
