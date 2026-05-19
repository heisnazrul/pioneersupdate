"use client";

import { useState } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowRight } from '@fortawesome/free-solid-svg-icons';
import Button from '@/components/ui/Button';
import Link from 'next/link';
import ApplicationFormModal from './ApplicationFormModal';

interface Props {
    scholarship: any;
}

export default function ScholarshipSidebar({ scholarship }: Props) {
    const [isModalOpen, setIsModalOpen] = useState(false);

    return (
        <div className="sticky top-32 bg-gray-50 rounded-3xl p-8 border border-gray-200">
            <h3 className="text-xl font-bold text-gray-900 mb-2">Ready to Apply?</h3>
            <p className="text-gray-500 mb-6 text-sm">
                Don&apos;t miss the deadline! Our experts can help you craft a winning application.
            </p>

            <Button
                onClick={() => setIsModalOpen(true)}
                className="w-full justify-center mb-6 py-4 text-lg shadow-blue-200 shadow-xl"
            >
                Apply Now
            </Button>

            <div className="mt-8 pt-8 border-t border-gray-200">
                <h4 className="font-bold text-gray-900 mb-4">Need Assistance?</h4>
                <p className="text-sm text-gray-500 mb-4">
                    Schedule a free consultation with our scholarship advisors.
                </p>
                <Link href="/contact" className="text-blue-600 font-bold text-sm hover:underline flex items-center gap-1">
                    Book a Consultation <FontAwesomeIcon icon={faArrowRight} />
                </Link>
            </div>

            <ApplicationFormModal
                scholarship={scholarship}
                isOpen={isModalOpen}
                onClose={() => setIsModalOpen(false)}
            />
        </div>
    );
}
