"use client";

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { motion, AnimatePresence } from 'framer-motion';
import { faSearch, faGlobe, faGraduationCap, faCalendarAlt, faUniversity } from '@fortawesome/free-solid-svg-icons';
import RichCombobox, { Option } from '@/components/ui/RichCombobox';
import { MOCK_DATA } from '@/lib/data/mocks';
import { fetchHeroData, HeroApiData } from '@/lib/api';

// Helper for Flags (using local assets)
const getFlag = (countryCode: string) => `/assets/flags/${countryCode.toLowerCase()}.svg`;

// Helper to get logos (fallback if data missing)
const getLogo = (name: string) => {
    const n = name.toLowerCase();
    if (n.includes('oxford')) return '/assets/universities/oxford.png';
    if (n.includes('cambridge')) return '/assets/universities/cambridge.png';
    return '/assets/universities/generic.png';
};

interface SearchModuleProps {
    heroData?: HeroApiData;
}

export default function SearchModule({ heroData }: SearchModuleProps) {
    const router = useRouter();
    const [activeTab, setActiveTab] = useState<'courses' | 'universities' | 'language'>('courses');
    const [lang, setLang] = useState<'en' | 'ar'>('en');

    // Data Loading
    const [countryOptions, setCountryOptions] = useState<Option[]>([]);
    const [courseOptions, setCourseOptions] = useState<Option[]>([]);
    const [universityOptions, setUniversityOptions] = useState<Option[]>([]);
    const [intakeOptions, setIntakeOptions] = useState<Option[]>([]);
    const [levelOptions, setLevelOptions] = useState<Option[]>([]);

    const [formData, setFormData] = useState({
        destination: '',
        level: '',
        intake: '',
        keyword: ''
    });
    const fallbackLevelOptions: Option[] = [
        { id: 'bachelor', label: 'Bachelor', value: 'Bachelor', icon: faGraduationCap },
        { id: 'master', label: 'Master', value: 'Master', icon: faGraduationCap },
        { id: 'phd', label: 'PhD', value: 'PhD', icon: faGraduationCap },
        { id: 'foundation', label: 'Foundation', value: 'Foundation', icon: faGraduationCap },
    ];

    useEffect(() => {
        const loadData = async () => {
            const currentLang = (localStorage.getItem('uni_language') || 'en').toLowerCase() === 'ar' ? 'ar' : 'en';
            setLang(currentLang);
            // Try API first
            const apiData = heroData || await fetchHeroData(currentLang);

            if (apiData) {
                // 1. Countries
                const countries = (apiData.feature_countries as any[])
                    .filter((c: any) => c.has_universities !== false) // Only show countries that have universities/courses
                    .map((c: any, idx: number) => ({
                        id: `api-c-${idx}`,
                        label: c.name,
                        value: c.name,
                        image: c.flag || getFlag('default')
                    }));
                setCountryOptions(countries);

                // 2. Courses - Using actual course list from API
                const courses = apiData.courses.map((c, idx) => ({
                    id: `api-c-${idx}`,
                    label: c.name,
                    value: c.name,
                    icon: faGraduationCap
                }));
                setCourseOptions(courses);

                // 3. Universities (Feature + All)
                // Filter distinct if needed, for now just combining or using one list. 
                // Let's use feature_universities for a curated list, or all universities.
                const universities = apiData.universities.map((u, idx) => ({
                    id: `api-u-${idx}`,
                    label: u.name,
                    subLabel: u.city_name || 'Global',
                    value: u.name,
                    image: u.logo || getLogo(u.name)
                }));
                setUniversityOptions(universities);

                // 4. Intakes (Dynamic)
                const intakes = apiData.intakes.map((i, idx) => ({
                    id: `api-i-${idx}`,
                    label: i.name,
                    value: i.name, // Or normalize if needed
                    icon: faCalendarAlt
                }));
                setIntakeOptions(intakes);

                const levels = apiData.levels.map((l, idx) => ({
                    id: `api-l-${idx}`,
                    label: l.name,
                    value: l.name,
                    icon: faGraduationCap
                }));
                setLevelOptions(levels);

            } else {
                // Fallback to MOCK_DATA
                console.log("Using Mock Data Fallback");

                // 1. Countries
                const countries = MOCK_DATA.destinations.map(d => ({
                    id: d.id,
                    label: d.name,
                    value: d.name,
                    image: getFlag(d.countryCode || 'us')
                }));
                setCountryOptions(countries);

                // 2. Courses
                const courses = MOCK_DATA.courses.map(c => ({
                    id: c.id,
                    label: c.title,
                    value: c.title,
                    icon: faGraduationCap
                }));
                setCourseOptions(courses);

                // 3. Universities
                const universities = MOCK_DATA.destinations.flatMap(d => d.topUniversities || []).map(u => ({
                    id: u.id,
                    label: u.name,
                    subLabel: `${u.location || 'Global'}`,
                    value: u.name,
                    image: u.logoUrl && !u.logoUrl.includes('placehold') ? u.logoUrl : getLogo(u.name)
                }));
                setUniversityOptions(universities);

                // 4. Intakes (Static Fallback)
                setIntakeOptions([
                    { id: 'jan25', label: 'Jan 2025', value: 'Jan-2025', icon: faCalendarAlt },
                    { id: 'may25', label: 'May 2025', value: 'May-2025', icon: faCalendarAlt },
                    { id: 'sep25', label: 'Sep 2025', value: 'Sep-2025', icon: faCalendarAlt },
                ]);

                setLevelOptions([
                    { id: 'bachelor', label: 'Bachelor', value: 'Bachelor', icon: faGraduationCap },
                    { id: 'master', label: 'Master', value: 'Master', icon: faGraduationCap },
                    { id: 'phd', label: 'PhD', value: 'PhD', icon: faGraduationCap },
                    { id: 'foundation', label: 'Foundation', value: 'Foundation', icon: faGraduationCap },
                ]);
            }
        };

        loadData();

        // Re-run when language changes
        const handleStorage = () => {
            const l = (localStorage.getItem('uni_language') || 'en').toLowerCase() === 'ar' ? 'ar' : 'en';
            setLang(l);
        };
        window.addEventListener('storage', handleStorage);
        return () => window.removeEventListener('storage', handleStorage);
    }, [heroData]);

    const handleSearch = (e?: React.FormEvent) => {
        if (e) e.preventDefault();
        const params = new URLSearchParams();
        if (formData.destination) params.append('destination', formData.destination);
        if (formData.level) params.append('level', formData.level);
        if (formData.intake) params.append('intake', formData.intake);
        if (formData.keyword) params.append('keyword', formData.keyword);

        if (activeTab === 'language') {
            window.open('https://courseenglish.com', '_blank');
            return;
        } else if (formData.destination || formData.level || formData.intake || formData.keyword) {
            router.push(`/search/${activeTab}?${params.toString()}`);
        } else {
            router.push(`/search/${activeTab}`);
        }
    };

    const handleValidChange = (field: string, value: string) => {
        setFormData(prev => ({ ...prev, [field]: value }));
    };

    return (
        <div className="w-full xl:max-w-3xl">
            {/* Heading */}
            <div className="my-6 md:mb-10  ">
                <h1 className="text-3xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.1]">
                    {heroData?.headline || 'Shape Your Future with World-Class Education'}
                </h1>
                <p className="hidden md:block text-lg text-slate-600 mt-2">{heroData?.subheadline || "Expert guidance and personalized support to help you secure admission at the world's leading universities."}</p>
            </div>

            {/* Form Card */}
            <form onSubmit={handleSearch} className="space-y-4">

                {/* Main Search Input */}
                <div className="mb-4">
                    <RichCombobox
                        label={heroData?.search_label || (activeTab === 'courses' ? 'Search Courses' : 'Search Universities')}
                        placeholder={activeTab === 'courses'
                            ? (heroData?.search_placeholder_courses || "e.g. Computer Science, MBA...")
                            : (heroData?.search_placeholder_universities || "e.g. Oxford, Harvard...")}
                        options={activeTab === 'courses' ? courseOptions : universityOptions}
                        value={formData.keyword}
                        onChange={(val) => handleValidChange('keyword', val)}
                        searchable={true}
                        mainIcon={faSearch}
                        grid={true}
                    />
                </div>

                <AnimatePresence mode="wait">
                    <motion.div
                        key={activeTab}
                        initial={{ opacity: 0, y: 10 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={{ opacity: 0, y: -10 }}
                        transition={{ duration: 0.2 }}
                        className="space-y-4"
                    >
                        {/* Grid row: Country, Level, Intake */}
                        <div className="grid gap-3 sm:grid-cols-3">
                            <RichCombobox
                                label={heroData?.country_label || "Country"}
                                placeholder={heroData?.country_placeholder || "All Countries"}
                                options={countryOptions}
                                value={formData.destination}
                                onChange={(val) => handleValidChange('destination', val)}
                                mainIcon={faGlobe}
                            />

                            <RichCombobox
                                label={heroData?.level_label || "Study Level"}
                                placeholder={heroData?.level_placeholder || "Select..."}
                                options={levelOptions.length > 0 ? levelOptions : fallbackLevelOptions}
                                value={formData.level}
                                onChange={(val) => handleValidChange('level', val)}
                                mainIcon={faGraduationCap}
                            />

                            <RichCombobox
                                label={heroData?.intake_label || "Intake"}
                                placeholder={heroData?.intake_placeholder || "Any Intake"}
                                options={intakeOptions}
                                value={formData.intake}
                                onChange={(val) => handleValidChange('intake', val)}
                                mainIcon={faCalendarAlt}
                            />
                        </div>

                        {/* Search Row: Tabs + Button */}
                        <div className="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div className="flex flex-wrap p-1 bg-[#E1E8F0]/30 rounded-xl backdrop-blur-sm gap-1">
                                <button
                                    type="button"
                                    onClick={() => setActiveTab('courses')}
                                    className={`px-4 py-2.5 rounded-lg font-bold transition-all whitespace-nowrap text-sm ${activeTab === 'courses' ? 'bg-[#135FAE] text-white shadow-md' : 'text-slate-600 hover:bg-white/50'}`}
                                >
                                    {heroData?.tab_courses || 'Find Courses'}
                                </button>
                                <button
                                    type="button"
                                    onClick={() => setActiveTab('universities')}
                                    className={`px-4 py-2.5 rounded-lg font-bold transition-all whitespace-nowrap text-sm ${activeTab === 'universities' ? 'bg-[#135FAE] text-white shadow-md' : 'text-slate-600 hover:bg-white/50'}`}
                                >
                                    {heroData?.tab_universities || 'Find Universities'}
                                </button>
                                <button
                                    type="button"
                                    onClick={() => window.open('https://courseenglish.com', '_blank')}
                                    className="px-4 py-2.5 rounded-lg font-bold transition-all whitespace-nowrap text-sm text-slate-600 hover:bg-white/50"
                                >
                                    🌐 {lang === 'ar' ? 'ابحث عن دورات اللغة' : 'Find Language Courses'}
                                </button>
                            </div>

                            <button
                                type="submit"
                                className="w-full sm:w-auto inline-flex items-center justify-center rounded-lg bg-[#135FAE] px-12 py-4 text-xl font-bold text-white shadow-xl hover:brightness-110 transition-all active:scale-95"
                            >
                                {heroData?.search_button_text || 'Search'}
                            </button>
                        </div>
                    </motion.div>
                </AnimatePresence>
            </form>
        </div>
    );
}
