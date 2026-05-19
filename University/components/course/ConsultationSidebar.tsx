"use client";

import { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import { useAuth } from '@/context/AuthContext';
import { useLang } from '@/hooks/useLang';

interface LevelOption {
    courseId: string;
    levelName: string;
    levelKey: string;
    duration: string;
}

interface CatalogOption {
    catalogId: number;
    name: string;
    slug?: string;
    discipline?: string;
    levels: LevelOption[];
}

interface ConsultationSidebarProps {
    courseId?: string | number;
    courseName?: string;
    universityName?: string;
    universityLogo?: string;
    intakes?: string[];
    courses?: { id: string; name: string; slug: string }[];
    coursesCatalog?: CatalogOption[];
    lang?: 'en' | 'ar';
}

export default function ConsultationSidebar({
    courseId,
    courseName,
    universityName,
    universityLogo,
    intakes = [],
    courses = [],
    coursesCatalog = [],
    lang: langProp,
}: ConsultationSidebarProps) {
    const { user } = useAuth();
    const { isAr: isArHook } = useLang();
    // Prefer server-passed lang prop, fall back to client hook
    const isAr = langProp ? langProp === 'ar' : isArHook;

    const [selectedCatalogId, setSelectedCatalogId] = useState<string>('');
    const [selectedCourseId, setSelectedCourseId] = useState<string>(courseId ? String(courseId) : '');

    const [formData, setFormData] = useState({ name: '', email: '', phone: '', intake: '' });
    const [status, setStatus] = useState<'idle' | 'submitting' | 'success' | 'error'>('idle');
    const [errorMessage, setErrorMessage] = useState('');

    useEffect(() => {
        if (user) {
            setFormData(prev => ({ ...prev, name: user.name || prev.name, email: user.email || prev.email, phone: user.phone || prev.phone }));
        }
    }, [user]);

    const handleCatalogChange = (catalogIdStr: string) => {
        setSelectedCatalogId(catalogIdStr);
        setSelectedCourseId('');
    };

    const availableLevels: LevelOption[] = coursesCatalog.find(c => String(c.catalogId) === selectedCatalogId)?.levels ?? [];
    const isCatalogMode = coursesCatalog.length > 0;
    const finalCourseId = isCatalogMode ? selectedCourseId : (courseId ? String(courseId) : '');

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!finalCourseId) {
            setErrorMessage(isAr ? 'يرجى اختيار الدورة والمستوى.' : 'Please select a course and level.');
            setStatus('error');
            return;
        }
        setStatus('submitting');
        setErrorMessage('');

        try {
            const API_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://127.0.0.1:8000';
            const response = await fetch(`${API_URL}/api/uni-applications`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ course_id: finalCourseId, name: formData.name, email: formData.email, phone: formData.phone, intake: formData.intake }),
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Submission failed');
            }
            setStatus('success');
        } catch (error) {
            console.error('Application Error:', error);
            setStatus('error');
            setErrorMessage(isAr ? 'حدث خطأ ما. يرجى المحاولة مجدداً.' : 'Something went wrong. Please try again.');
        }
    };

    return (
        <div className="bg-white rounded-xl shadow-lg border border-gray-100 p-6" dir={isAr ? 'rtl' : 'ltr'}>
            <div className="flex items-center gap-4 mb-6 relative">
                {universityLogo ? (
                    <div className="w-14 h-14 rounded-lg bg-white border border-gray-100 p-1 flex-shrink-0 flex items-center justify-center overflow-hidden shadow-sm">
                        <img src={universityLogo} alt={universityName} className="w-full h-full object-contain" />
                    </div>
                ) : (
                    <div className="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center text-secondary flex-shrink-0">
                        <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                )}
                <div className="flex-1 min-w-0">
                    <h3 className="text-base font-bold text-primary leading-tight truncate pr-4">
                        {universityName || (isAr ? 'احصل على استشارة مجانية' : 'Get Free Counseling')}
                    </h3>
                    <p className="text-xs text-gray-500 mt-1 line-clamp-1">
                        {isAr ? 'توجيه متخصص في رحلتك' : 'Expert guidance for your journey'}
                    </p>
                </div>
            </div>

            {status === 'success' ? (
                <motion.div initial={{ opacity: 0, scale: 0.9 }} animate={{ opacity: 1, scale: 1 }} className="text-center py-8">
                    <div className="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">✓</div>
                    {user ? (
                        <>
                            <h4 className="font-bold text-gray-800 mb-2">{isAr ? 'تم إرسال الطلب!' : 'Application Sent!'}</h4>
                            <p className="text-sm text-gray-500 mb-6">{isAr ? 'يمكنك متابعة حالة طلبك من لوحة التحكم.' : 'You can track your application status in your dashboard.'}</p>
                            <a href="/student/dashboard/university-application" className="inline-block w-full py-3 bg-[#1F63AE] text-white font-bold rounded-lg hover:bg-[#175093] transition-colors shadow-md">
                                {isAr ? 'الذهاب إلى لوحة التحكم' : 'Go to Dashboard'}
                            </a>
                        </>
                    ) : (
                        <>
                            <h4 className="font-bold text-gray-800 mb-2">{isAr ? 'تم استلام الطلب!' : 'Application Received!'}</h4>
                            <p className="text-sm text-gray-500 mb-6">{isAr ? 'هل تريد إنشاء حساب لمتابعة تقدم طلبك؟' : 'Would you like to create an account to track your application progress?'}</p>
                            <div className="space-y-3">
                                <a href="/login" className="inline-block w-full py-3 bg-[#D32F2F] text-white font-bold rounded-lg hover:bg-[#B71C1C] transition-colors shadow-md">
                                    {isAr ? 'نعم، إنشاء حساب ومتابعة التقدم' : 'Yes, Create Account & Track Progress'}
                                </a>
                                <button onClick={() => setStatus('idle')} className="text-sm text-gray-500 hover:text-gray-700 underline">
                                    {isAr ? 'لا شكراً، لست مهتماً' : "No thanks, I'm not interested"}
                                </button>
                            </div>
                        </>
                    )}
                </motion.div>
            ) : (
                <form onSubmit={handleSubmit} className="space-y-4">
                    {status === 'error' && (
                        <div className="p-3 text-xs text-red-600 bg-red-50 rounded-lg border border-red-100">{errorMessage}</div>
                    )}

                    {isCatalogMode && (
                        <>
                            <div>
                                <label className="block text-xs font-bold text-gray-500 uppercase mb-1">{isAr ? 'اختر الدورة' : 'Select Course'}</label>
                                <select required value={selectedCatalogId} onChange={e => handleCatalogChange(e.target.value)} className="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-secondary text-sm">
                                    <option value="">{isAr ? 'اختر دورة' : 'Select a Course'}</option>
                                    {coursesCatalog.map(cat => (<option key={cat.catalogId} value={String(cat.catalogId)}>{cat.name}</option>))}
                                </select>
                            </div>
                            {selectedCatalogId && (
                                <div>
                                    <label className="block text-xs font-bold text-gray-500 uppercase mb-1">{isAr ? 'اختر المستوى' : 'Select Level'}</label>
                                    <select required value={selectedCourseId} onChange={e => setSelectedCourseId(e.target.value)} className="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-secondary text-sm">
                                        <option value="">{isAr ? 'اختر مستوى' : 'Select a Level'}</option>
                                        {availableLevels.map(level => (<option key={level.courseId} value={level.courseId}>{level.levelName} {level.duration ? `· ${level.duration}` : ''}</option>))}
                                    </select>
                                </div>
                            )}
                        </>
                    )}

                    {!isCatalogMode && courses.length > 0 && (
                        <div>
                            <label className="block text-xs font-bold text-gray-500 uppercase mb-1">{isAr ? 'اختر الدورة' : 'Select Course'}</label>
                            <select required value={finalCourseId} onChange={e => setSelectedCourseId(e.target.value)} className="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-secondary text-sm">
                                <option value="">{isAr ? 'اختر دورة' : 'Select a Course'}</option>
                                {courses.map(course => (<option key={course.id} value={course.id}>{course.name}</option>))}
                            </select>
                        </div>
                    )}

                    <div>
                        <label className="block text-xs font-bold text-gray-500 uppercase mb-1">{isAr ? 'الاسم الكامل' : 'Full Name'}</label>
                        <input type="text" required value={formData.name} onChange={e => setFormData({ ...formData, name: e.target.value })} className="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-secondary text-sm" placeholder={isAr ? 'محمد أحمد' : 'John Doe'} />
                    </div>
                    <div>
                        <label className="block text-xs font-bold text-gray-500 uppercase mb-1">{isAr ? 'البريد الإلكتروني' : 'Email Address'}</label>
                        <input type="email" required value={formData.email} onChange={e => setFormData({ ...formData, email: e.target.value })} className="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-secondary text-sm" placeholder={isAr ? 'name@example.com' : 'john@example.com'} />
                    </div>
                    <div>
                        <label className="block text-xs font-bold text-gray-500 uppercase mb-1">{isAr ? 'رقم الهاتف' : 'Phone Number'}</label>
                        <input type="tel" required value={formData.phone} onChange={e => setFormData({ ...formData, phone: e.target.value })} className="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-secondary text-sm" placeholder="+1 (555) 000-0000" />
                    </div>
                    <div>
                        <label className="block text-xs font-bold text-gray-500 uppercase mb-1">{isAr ? 'موعد القبول المفضّل' : 'Preferred Intake'}</label>
                        <select required value={formData.intake} onChange={e => setFormData({ ...formData, intake: e.target.value })} className="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-secondary text-sm">
                            <option value="">{isAr ? 'اختر موعد القبول' : 'Select Intake'}</option>
                            {intakes.length > 0 ? (
                                intakes.map((intake, i) => <option key={i} value={intake}>{intake}</option>)
                            ) : (
                                <>
                                    <option value="Sep 2025">{isAr ? 'سبتمبر 2025' : 'September 2025'}</option>
                                    <option value="Jan 2026">{isAr ? 'يناير 2026' : 'January 2026'}</option>
                                </>
                            )}
                        </select>
                    </div>

                    <button type="submit" disabled={status === 'submitting'} className="w-full py-3 bg-[#D32F2F] text-white font-bold rounded-lg hover:bg-[#B71C1C] transition-colors shadow-md active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed uppercase tracking-wide border-2 border-[#D32F2F] hover:border-[#B71C1C]">
                        {status === 'submitting' ? (isAr ? 'جارٍ الإرسال...' : 'Applying...') : (isAr ? 'تقدّم الآن' : 'Apply Now')}
                    </button>

                    <p className="text-xs text-center text-gray-400 mt-3">
                        {isAr ? 'بالتقديم، تطلب استشارة مجانية لهذه الدورة.' : 'By applying, you request free counseling for this course.'}
                    </p>
                </form>
            )}
        </div>
    );
}
