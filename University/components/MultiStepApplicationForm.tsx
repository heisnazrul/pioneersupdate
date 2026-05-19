"use client";

import { useState, useEffect } from 'react';
import { useAuth } from '@/context/AuthContext';
import { submitApplicationLead } from '@/lib/api';
import { ApplicationPayload } from '@/lib/data/types';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCheck, faChevronDown, faCopy } from '@fortawesome/free-solid-svg-icons';
import { faWhatsapp as faWhatsappBrand } from '@fortawesome/free-brands-svg-icons';
import Button from '@/components/ui/Button'; // Updated import (Capital B)
import { useRouter } from 'next/navigation';

// Extended payload interface
interface ExtendedPayload extends ApplicationPayload {
    nationality: string;
    nationalityOther?: string;
    englishTestType?: string;
    englishTestScore?: string;
    destinationsOther?: string;
}

const INITIAL_DATA: ExtendedPayload = {
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    citizenship: '', // Keeping for backward compatibility or mapping
    nationality: '',
    nationalityOther: '',
    highestEducation: '',
    gradeAverage: '',
    hasEnglishTest: false,
    englishTestType: '',
    englishTestScore: '',
    destinationInterest: [],
    destinationsOther: '',
    preferredIntake: '',
    budgetRange: '',
    status: 'draft'
};

const STEPS = ['Personal Details', 'Academic Profile', 'Preferences', 'Review'];

export default function MultiStepApplicationForm() {
    const router = useRouter();
    const { user } = useAuth();
    const [step, setStep] = useState(1);
    const [formData, setFormData] = useState<ExtendedPayload>(INITIAL_DATA);
    const [isLoaded, setIsLoaded] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [intakes, setIntakes] = useState<any[]>([]);

    // Modal States
    const [showModal, setShowModal] = useState(false);
    const [modalStep, setModalStep] = useState<'prompt' | 'create_account' | 'guest_success' | 'success_authenticated'>('prompt');
    const [applicationId, setApplicationId] = useState('');

    // Load from local storage
    useEffect(() => {
        const saved = localStorage.getItem('pioneers_application_draft');
        if (saved) {
            try {
                setFormData(JSON.parse(saved));
            } catch (e) {
                console.error("Failed to parse saved application", e);
            }
        }
        setIsLoaded(true);

        // Fetch Intakes
        fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL || 'http://127.0.0.1:8000'}/api/intakes`)
            .then(res => res.json())
            .then(data => {
                if (Array.isArray(data)) {
                    setIntakes(data);
                }
            })
            .catch(err => console.error("Failed to fetch intakes", err));
    }, []);

    // Save to local storage
    useEffect(() => {
        if (isLoaded && !showModal) {
            localStorage.setItem('pioneers_application_draft', JSON.stringify(formData));
        }
    }, [formData, isLoaded, showModal]);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        const { name, value, type } = e.target as HTMLInputElement;
        if (type === 'checkbox') {
            const checked = (e.target as HTMLInputElement).checked;
            setFormData(prev => ({ ...prev, [name]: checked }));
        } else {
            setFormData(prev => ({ ...prev, [name]: value }));
        }
    };

    const handleMultiSelect = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { value, checked } = e.target;
        setFormData(prev => {
            const current = prev.destinationInterest || [];
            if (checked) return { ...prev, destinationInterest: [...current, value] };
            return { ...prev, destinationInterest: current.filter(item => item !== value) };
        });
    };

    const nextStep = () => setStep(prev => Math.min(prev + 1, 4));
    const prevStep = () => setStep(prev => Math.max(prev - 1, 1));

    const generateAppId = () => {
        // 4 random + 4 ID (mock logic for now as we don't have real DB ID here yet)
        const randomPart = Math.floor(1000 + Math.random() * 9000);
        const idPart = "0042"; // Mock ID padding
        return `${randomPart}${idPart}`;
    };

    const handleSubmit = async () => {
        setIsSubmitting(true);
        try {
            const response = await submitApplicationLead({
                ...formData,
                status: 'submitted' // Initial status
            });

            if (response.success) {
                // Use real ID if available, or fallback to generated info for guest
                // The API controller returns 'application_id'
                const appId = (response as any).application_id || generateAppId();
                setApplicationId(appId);
                setShowModal(true);
                if (user) {
                    setModalStep('success_authenticated');
                } else {
                    setModalStep('prompt');
                }
                localStorage.removeItem('pioneers_application_draft');
            } else {
                alert("Submission failed. Please try again.");
            }
        } catch (error) {
            console.error("Submission error:", error);
            alert("An error occurred. Please try again.");
        }
        setIsSubmitting(false);
    };

    const [password, setPassword] = useState('');

    const handleCreateAccount = async () => {
        if (password.length < 8) {
            alert("Password must be at least 8 characters long.");
            return;
        }

        try {
            const res = await fetch(`${process.env.NEXT_PUBLIC_API_BASE_URL || 'http://127.0.0.1:8000/api'}/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: `${formData.firstName} ${formData.lastName}`,
                    email: formData.email,
                    password: password,
                    role: 'uni_student'
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                localStorage.setItem('auth_token', data.access_token);
                localStorage.setItem('auth_user', JSON.stringify(data.user));
                window.dispatchEvent(new Event('auth-update')); // Sync navbar
                router.push('/complete-profile');
            } else {
                if (data.errors) {
                    // Extract first error message
                    const firstError = Object.values(data.errors).flat()[0];
                    alert((firstError as string) || 'Registration failed');
                } else {
                    alert(data.message || 'Registration failed');
                }
            }
        } catch (error) {
            console.error("Registration error", error);
            alert("An error occurred during registration. Please check your connection.");
        }
    };

    // ... inside renderModal ...


    if (!isLoaded) return null;

    // ----- MODAL RENDER -----
    const renderModal = () => {
        if (!showModal) return null;

        return (
            <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                <div className="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in duration-300">

                    {modalStep === 'success_authenticated' && (
                        <div className="p-8 text-center">
                            <div className="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                                <FontAwesomeIcon icon={faCheck} className="text-2xl" />
                            </div>
                            <h3 className="text-2xl font-bold text-slate-900 mb-2">Application Submitted!</h3>
                            <p className="text-slate-500 mb-6">
                                Your application has been successfully submitted. You can track its status in your dashboard.
                            </p>

                            <div className="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-6">
                                <div className="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Application ID</div>
                                <div className="text-3xl font-mono font-bold text-slate-900 tracking-wider flex items-center justify-center gap-3">
                                    {applicationId}
                                    <button onClick={() => navigator.clipboard.writeText(applicationId)} className="text-slate-400 hover:text-[#1F63AE] transition-colors">
                                        <FontAwesomeIcon icon={faCopy} className="w-5 h-5" />
                                    </button>
                                </div>
                            </div>

                            <Button
                                href="/student/dashboard"
                                fullWidth
                                variant="primary"
                                size="lg"
                            >
                                Go to Dashboard
                            </Button>
                        </div>
                    )}

                    {modalStep === 'prompt' && (
                        <div className="p-8 text-center">
                            <div className="w-16 h-16 bg-blue-100 text-[#1F63AE] rounded-full flex items-center justify-center mx-auto mb-6 text-2xl">
                                📊
                            </div>
                            <h3 className="text-2xl font-bold text-slate-900 mb-3">Application Received!</h3>
                            <p className="text-slate-500 mb-8">
                                Would you like to create an account to <strong>track your application progress</strong> and upload documents?
                            </p>

                            <div className="space-y-3">
                                <Button
                                    onClick={() => setModalStep('create_account')}
                                    fullWidth
                                    variant="primary"
                                    size="lg"
                                >
                                    Yes, Create Account & Track Progress
                                </Button>
                                <button
                                    onClick={() => setModalStep('guest_success')}
                                    className="text-slate-400 font-medium text-sm hover:text-slate-600 transition-colors"
                                >
                                    No thanks, I'm not interested
                                </button>
                            </div>
                        </div>
                    )}

                    {modalStep === 'create_account' && (
                        <div className="p-8">
                            <h3 className="text-xl font-bold text-slate-900 mb-6">Create Your Account</h3>
                            <form onSubmit={(e) => { e.preventDefault(); handleCreateAccount(); }} className="space-y-4">
                                <div>
                                    <label className="block text-sm font-bold text-slate-700 mb-1">Email</label>
                                    <input type="email" value={formData.email} disabled className="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-slate-500" />
                                </div>
                                <div>
                                    <label className="block text-sm font-bold text-slate-700 mb-1">Create Password</label>
                                    <input
                                        type="password"
                                        value={password}
                                        onChange={(e) => setPassword(e.target.value)}
                                        placeholder="Min 8 characters"
                                        className="w-full border border-slate-300 rounded-xl px-4 py-3 focus:border-[#1F63AE] outline-none"
                                        autoFocus
                                        minLength={8}
                                        required
                                    />
                                    <p className="text-xs text-slate-400 mt-1">Must be at least 8 characters long.</p>
                                </div>
                                <Button type="submit" fullWidth variant="primary" size="lg" className="mt-2">
                                    Complete Setup
                                </Button>
                            </form>
                        </div>
                    )}

                    {modalStep === 'guest_success' && (
                        <div className="p-8 text-center">
                            <div className="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                                <FontAwesomeIcon icon={faCheck} className="text-2xl" />
                            </div>
                            <h3 className="text-2xl font-bold text-slate-900 mb-2">Success!</h3>
                            <p className="text-slate-500 mb-6">
                                Your application has been successfully submitted. We will review it and contact you shortly.
                            </p>

                            <div className="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-6">
                                <div className="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Application ID</div>
                                <div className="text-3xl font-mono font-bold text-slate-900 tracking-wider flex items-center justify-center gap-3">
                                    {applicationId}
                                    <button onClick={() => navigator.clipboard.writeText(applicationId)} className="text-slate-400 hover:text-[#1F63AE] transition-colors">
                                        <FontAwesomeIcon icon={faCopy} className="w-5 h-5" />
                                    </button>
                                </div>
                            </div>

                            <p className="text-sm text-slate-500 mb-6">
                                For urgent queries, contact us on WhatsApp:
                            </p>

                            <a
                                href="https://wa.me/1234567890" // Replace with real number
                                target="_blank"
                                rel="noopener noreferrer"
                                className="flex items-center justify-center gap-2 bg-[#25D366] text-white font-bold py-3 px-6 rounded-xl hover:bg-[#20bd5a] transition-colors w-full"
                            >
                                <FontAwesomeIcon icon={faWhatsappBrand} className="w-5 h-5" />
                                Chat on WhatsApp
                            </a>
                        </div>
                    )}
                </div>
            </div>
        );
    };

    return (
        <div className="max-w-2xl mx-auto bg-white p-8 md:p-12 rounded-3xl shadow-xl border border-slate-100">
            {renderModal()}

            {/* Steps Progress */}
            <div className="mb-10">
                <div className="flex justify-between relative">
                    {/* Line */}
                    <div className="absolute top-1/2 left-0 w-full h-1 bg-slate-100 -z-0 -translate-y-1/2 rounded-full"></div>
                    <div className="absolute top-1/2 left-0 h-1 bg-green-500 -z-0 -translate-y-1/2 rounded-full transition-all duration-500" style={{ width: `${((step - 1) / 3) * 100}%` }}></div>

                    {STEPS.map((label, idx) => {
                        const sNum = idx + 1;
                        const isCompleted = sNum < step;
                        const isCurrent = sNum === step;
                        return (
                            <div key={idx} className="relative z-10 flex flex-col items-center gap-2">
                                <div className={`w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg transition-all duration-300 border-4 ${isCompleted ? 'bg-green-500 border-green-500 text-white' :
                                    isCurrent ? 'bg-[#1F63AE] border-[#1F63AE] text-white shadow-lg scale-110' :
                                        'bg-white border-slate-200 text-slate-300'
                                    }`}>
                                    {isCompleted ? <FontAwesomeIcon icon={faCheck} className="w-4 h-4" /> : sNum}
                                </div>
                                <span className={`text-xs font-bold uppercase tracking-wide hidden sm:block ${isCurrent ? 'text-[#1F63AE]' : 'text-slate-400'}`}>
                                    {label}
                                </span>
                            </div>
                        );
                    })}
                </div>
            </div>

            <h2 className="text-3xl font-bold text-slate-900 mb-8 text-center">{STEPS[step - 1]}</h2>

            {/* Step 1: Personal */}
            {step === 1 && (
                <div className="space-y-4 animate-in fade-in slide-in-from-right-4 duration-300">
                    <div className="grid grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-bold text-slate-700 mb-1">First Name</label>
                            <input name="firstName" value={formData.firstName} onChange={handleChange} className="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-[#1F63AE] focus:ring-4 focus:ring-[#1F63AE]/10 outline-none transition-all" required />
                        </div>
                        <div>
                            <label className="block text-sm font-bold text-slate-700 mb-1">Last Name</label>
                            <input name="lastName" value={formData.lastName} onChange={handleChange} className="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-[#1F63AE] focus:ring-4 focus:ring-[#1F63AE]/10 outline-none transition-all" required />
                        </div>
                    </div>
                    <div>
                        <label className="block text-sm font-bold text-slate-700 mb-1">Email Address</label>
                        <input name="email" type="email" value={formData.email} onChange={handleChange} className="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-[#1F63AE] focus:ring-4 focus:ring-[#1F63AE]/10 outline-none transition-all" required />
                    </div>
                    <div>
                        <label className="block text-sm font-bold text-slate-700 mb-1">Phone Number</label>
                        <input name="phone" type="tel" value={formData.phone} onChange={handleChange} className="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-[#1F63AE] focus:ring-4 focus:ring-[#1F63AE]/10 outline-none transition-all" required />
                    </div>

                    {/* Nationality */}
                    <div>
                        <label className="block text-sm font-bold text-slate-700 mb-1">Nationality</label>
                        <select name="nationality" value={formData.nationality} onChange={handleChange} className="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-[#1F63AE] focus:ring-4 focus:ring-[#1F63AE]/10 outline-none transition-all bg-white appearance-none">
                            <option value="">Select your nationality</option>
                            <option value="Bangladeshi">🇧🇩 Bangladeshi</option>
                            <option value="Saudi Arabian">🇸🇦 Saudi Arabian</option>
                            <option value="Indian">🇮🇳 Indian</option>
                            <option value="Pakistani">🇵🇰 Pakistani</option>
                            <option value="Other">🏳️ Other</option>
                        </select>
                    </div>
                    {formData.nationality === 'Other' && (
                        <div className="animate-in fade-in slide-in-from-top-2">
                            <input name="nationalityOther" placeholder="Type your nationality..." value={formData.nationalityOther} onChange={handleChange} className="mt-2 w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-[#1F63AE] focus:ring-4 focus:ring-[#1F63AE]/10 outline-none transition-all" />
                        </div>
                    )}
                </div>
            )}

            {/* Step 2: Academics */}
            {step === 2 && (
                <div className="space-y-4 animate-in fade-in slide-in-from-right-4 duration-300">
                    <div>
                        <label className="block text-sm font-bold text-slate-700 mb-1">Highest Education Level</label>
                        <select name="highestEducation" value={formData.highestEducation} onChange={handleChange} className="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-[#1F63AE] focus:ring-4 focus:ring-[#1F63AE]/10 outline-none transition-all bg-white">
                            <option value="">Select...</option>
                            <option value="O-Level">O-Level</option>
                            <option value="A-Level">A-Level</option>
                            <option value="Option">High School (12th)</option>
                            <option value="Bachelors">Bachelor's Degree</option>
                            <option value="Masters">Master's Degree</option>
                            <option value="PhD">PhD</option>
                        </select>
                    </div>
                    <div>
                        <label className="block text-sm font-bold text-slate-700 mb-1">Grade Average / GPA</label>
                        <input name="gradeAverage" value={formData.gradeAverage} onChange={handleChange} className="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-[#1F63AE] focus:ring-4 focus:ring-[#1F63AE]/10 outline-none transition-all" placeholder="e.g. 3.5/4.0 or 85%" />
                    </div>
                    <div className="pt-4 border-t border-slate-100">
                        <label className="flex items-center gap-3 cursor-pointer group">
                            <div className={`w-6 h-6 rounded-md border-2 flex items-center justify-center transition-colors ${formData.hasEnglishTest ? 'bg-[#1F63AE] border-[#1F63AE] text-white' : 'border-slate-300 bg-white group-hover:border-[#1F63AE]'}`}>
                                {formData.hasEnglishTest && <FontAwesomeIcon icon={faCheck} className="w-3 h-3" />}
                            </div>
                            <input type="checkbox" name="hasEnglishTest" checked={formData.hasEnglishTest} onChange={handleChange} className="hidden" />
                            <span className="text-slate-700 font-bold">I have taken an English Proficiency Test</span>
                        </label>
                    </div>
                    {formData.hasEnglishTest && (
                        <div className="grid grid-cols-2 gap-4 animate-in fade-in slide-in-from-top-2">
                            <div>
                                <label className="block text-sm font-bold text-slate-700 mb-1">Test Type</label>
                                <select name="englishTestType" value={formData.englishTestType} onChange={handleChange} className="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-[#1F63AE] bg-white">
                                    <option value="">Select...</option>
                                    <option value="IELTS">IELTS</option>
                                    <option value="TOEFL">TOEFL</option>
                                    <option value="Duolingo">Duolingo</option>
                                    <option value="PTE">PTE</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label className="block text-sm font-bold text-slate-700 mb-1">Score</label>
                                <input name="englishTestScore" value={formData.englishTestScore || ''} onChange={handleChange} className="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-[#1F63AE]" placeholder="e.g. 7.5" />
                            </div>
                        </div>
                    )}
                </div>
            )}

            {/* Step 3: Preferences */}
            {step === 3 && (
                <div className="space-y-6 animate-in fade-in slide-in-from-right-4 duration-300">
                    <div>
                        <label className="block text-sm font-bold text-slate-700 mb-3">Destinations of Interest</label>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            {/* Column 1 */}
                            <div className="space-y-3">
                                {['USA', 'UK', 'Canada', 'Australia'].map(dest => (
                                    <label key={dest} className="flex items-center gap-3 cursor-pointer group p-3 rounded-xl border border-slate-200 hover:border-[#1F63AE] hover:bg-blue-50/50 transition-all">
                                        <div className={`w-5 h-5 rounded border flex items-center justify-center ${formData.destinationInterest.includes(dest) ? 'bg-[#1F63AE] border-[#1F63AE] text-white' : 'border-slate-300 bg-white'}`}>
                                            {formData.destinationInterest.includes(dest) && <FontAwesomeIcon icon={faCheck} className="w-3 h-3" />}
                                        </div>
                                        <input type="checkbox" value={dest} checked={formData.destinationInterest.includes(dest)} onChange={handleMultiSelect} className="hidden" />
                                        <span className="font-bold text-slate-700">{dest}</span>
                                    </label>
                                ))}
                            </div>
                            {/* Column 2 */}
                            <div className="space-y-3">
                                {['Germany', 'Cyprus', 'Hungary'].map(dest => (
                                    <label key={dest} className="flex items-center gap-3 cursor-pointer group p-3 rounded-xl border border-slate-200 hover:border-[#1F63AE] hover:bg-blue-50/50 transition-all">
                                        <div className={`w-5 h-5 rounded border flex items-center justify-center ${formData.destinationInterest.includes(dest) ? 'bg-[#1F63AE] border-[#1F63AE] text-white' : 'border-slate-300 bg-white'}`}>
                                            {formData.destinationInterest.includes(dest) && <FontAwesomeIcon icon={faCheck} className="w-3 h-3" />}
                                        </div>
                                        <input type="checkbox" value={dest} checked={formData.destinationInterest.includes(dest)} onChange={handleMultiSelect} className="hidden" />
                                        <span className="font-bold text-slate-700">{dest}</span>
                                    </label>
                                ))}
                                {/* Other Destination */}
                                <div>
                                    <input name="destinationsOther" placeholder="Other (Type here...)" value={formData.destinationsOther} onChange={handleChange} className="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-[#1F63AE] outline-none text-sm" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-bold text-slate-700 mb-1">Preferred Intake</label>
                            <select name="preferredIntake" value={formData.preferredIntake} onChange={handleChange} className="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-[#1F63AE] bg-white">
                                <option value="">Select...</option>
                                {intakes.length > 0 ? (
                                    intakes.map((intake) => (
                                        <option key={intake.id} value={intake.name}>{intake.name}</option>
                                    ))
                                ) : (
                                    <>
                                        <option value="September 2025">September 2025</option>
                                        <option value="January 2026">January 2026</option>
                                        <option value="September 2026">September 2026</option>
                                    </>
                                )}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-bold text-slate-700 mb-1">Annual Budget</label>
                            <select name="budgetRange" value={formData.budgetRange} onChange={handleChange} className="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-[#1F63AE] bg-white">
                                <option value="">Select...</option>
                                <option value="Sponsored">Sponsored</option>
                                <option value="Under $15k">Under $15k</option>
                                <option value="$15k - $30k">$15k - $30k</option>
                                <option value="$30k - $50k">$30k - $50k</option>
                                <option value="$50k+">$50k+</option>
                            </select>
                        </div>
                    </div>
                </div>
            )}

            {/* Step 4: Review */}
            {step === 4 && (
                <div className="space-y-6 animate-in fade-in slide-in-from-right-4 duration-300">
                    <div className="bg-slate-50 rounded-2xl p-6 border border-slate-200">
                        <h3 className="font-bold text-lg text-slate-900 mb-4 border-b border-slate-200 pb-2">Review Your Information</h3>

                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 text-sm">
                            <div>
                                <span className="block text-slate-400 font-bold uppercase text-xs">Name</span>
                                <span className="text-slate-700 font-medium">{formData.firstName} {formData.lastName}</span>
                            </div>
                            <div>
                                <span className="block text-slate-400 font-bold uppercase text-xs">Email</span>
                                <span className="text-slate-700 font-medium">{formData.email}</span>
                            </div>
                            <div>
                                <span className="block text-slate-400 font-bold uppercase text-xs">Nationality</span>
                                <span className="text-slate-700 font-medium">{formData.nationality === 'Other' ? formData.nationalityOther : formData.nationality}</span>
                            </div>
                            <div>
                                <span className="block text-slate-400 font-bold uppercase text-xs">Education</span>
                                <span className="text-slate-700 font-medium">{formData.highestEducation} ({formData.gradeAverage})</span>
                            </div>
                            <div>
                                <span className="block text-slate-400 font-bold uppercase text-xs">English Test</span>
                                <span className="text-slate-700 font-medium">{formData.hasEnglishTest ? `${formData.englishTestType} - ${formData.englishTestScore}` : 'None'}</span>
                            </div>
                            <div>
                                <span className="block text-slate-400 font-bold uppercase text-xs">Destinations</span>
                                <span className="text-slate-700 font-medium">{formData.destinationInterest.join(', ')} {formData.destinationsOther}</span>
                            </div>
                            <div>
                                <span className="block text-slate-400 font-bold uppercase text-xs">Budget</span>
                                <span className="text-slate-700 font-medium">{formData.budgetRange}</span>
                            </div>
                        </div>
                    </div>
                    <div className="flex items-center gap-3 text-sm text-slate-500 bg-blue-50 p-4 rounded-xl">
                        <div className="w-5 h-5 bg-[#1F63AE] text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">i</div>
                        <p>By submitting, you agree to our Terms of Service and Privacy Policy. Your information will be reviewed by our counseling team.</p>
                    </div>
                </div>
            )}

            {/* Action Buttons */}
            <div className="flex justify-between mt-10 pt-8 border-t border-slate-100">
                {step > 1 ? (
                    <button onClick={prevStep} className="text-slate-500 font-bold hover:text-slate-800 transition-colors px-4 py-2">
                        Back
                    </button>
                ) : <div></div>}

                {step < 4 ? (
                    <Button onClick={nextStep} variant="primary" size="lg" className="px-8 shadow-lg shadow-blue-900/10">
                        Continue
                    </Button>
                ) : (
                    <Button onClick={handleSubmit} disabled={isSubmitting} variant="primary" size="lg" className="px-8 shadow-lg shadow-blue-900/20">
                        {isSubmitting ? (
                            <span className="flex items-center gap-2">
                                <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                Submitting...
                            </span>
                        ) : 'Submit Application'}
                    </Button>
                )}
            </div>
        </div>
    );
}

