"use client";

import { useSearchParams, useRouter, usePathname } from 'next/navigation';
import { useState, useEffect } from 'react';
import { Country, City, Intake, Level } from '@/lib/data/types';
import { getCountries, getIntakes, getLevels } from '@/services/publicData';
import { useLang } from '@/hooks/useLang';

interface SearchSidebarProps {
    type: 'courses' | 'universities';
}

// Static fallback — keys MUST match backend level key field
const FALLBACK_LEVELS: Level[] = [
    { name: 'Foundation', key: 'foundation', id: 'foundation' },
    { name: 'Bachelor', key: 'bachelor', id: 'bachelor' },
    { name: 'Bachelor Top-Up', key: 'bachelor-top-up', id: 'bachelor-top-up' },
    { name: 'Integrated Master', key: 'integrated-master', id: 'integrated-master' },
    { name: 'Pre-Masters', key: 'pre-masters', id: 'pre-masters' },
    { name: 'Masters', key: 'masters', id: 'masters' },
    { name: 'MBA', key: 'mba', id: 'mba' },
    { name: 'MRes', key: 'mres', id: 'mres' },
    { name: 'PhD / Doctorate', key: 'phd-doctorate', id: 'phd-doctorate' },
    { name: 'Professional Doctorate', key: 'professional-doctorate', id: 'professional-doctorate' },
    { name: 'PG Diploma', key: 'postgraduate-diploma', id: 'postgraduate-diploma' },
    { name: 'PG Certificate', key: 'postgraduate-certificate', id: 'postgraduate-certificate' },
    { name: 'Graduate Diploma', key: 'graduate-diploma', id: 'graduate-diploma' },
    { name: 'Diploma', key: 'diploma', id: 'diploma' },
    { name: 'Certificate', key: 'certificate', id: 'certificate' },
    { name: 'Short Course', key: 'short-course', id: 'short-course' },
    { name: 'Distance Learning', key: 'distance-learning', id: 'distance-learning' },
];

export default function SearchSidebar({ type }: SearchSidebarProps) {
    const router = useRouter();
    const searchParams = useSearchParams();
    const pathname = usePathname();

    const [countries, setCountries] = useState<Country[]>([]);
    const [intakes, setIntakes] = useState<Intake[]>([]);
    const [levels, setLevels] = useState<Level[]>(FALLBACK_LEVELS);
    const [cities, setCities] = useState<City[]>([]);
    const { isAr } = useLang();

    useEffect(() => {
        getCountries().then(setCountries);
        getIntakes().then(setIntakes);
        getLevels().then((remote) => {
            if (remote && remote.length) {
                setLevels(remote.map(l => ({ ...l, key: l.key || l.name?.toLowerCase?.() || String(l.id) })));
            }
        });
    }, []);

    // Fetch cities when destination (country) changes
    const selectedCountry = searchParams.get('destination');
    useEffect(() => {
        import('@/services/publicData').then(mod => {
            mod.getCities(selectedCountry || undefined).then(setCities);
        });
    }, [selectedCountry]);

    const updateFilter = (key: string, value: string | boolean | undefined) => {
        const params = new URLSearchParams(searchParams.toString());
        if (value === undefined || value === '' || value === false) {
            params.delete(key);
        } else {
            params.set(key, String(value));
        }
        params.delete('page');
        router.push(`${pathname}?${params.toString()}`, { scroll: false });
    };

    const FilterSection = ({ title, children }: { title: string, children: React.ReactNode }) => (
        <div className="py-6 border-b border-gray-100 last:border-0">
            <h4 className="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">{title}</h4>
            <div className="space-y-3">
                {children}
            </div>
        </div>
    );

    return (
        <aside className="w-full lg:w-72 shrink-0 space-y-2 lg:sticky lg:top-24 h-fit p-1">
            <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div className="flex items-center justify-between mb-4 lg:hidden">
                    <h3 className="font-bold text-lg">Filters</h3>
                    <button
                        className="text-primary font-bold text-sm"
                        onClick={() => router.push(`${pathname}?level=bachelor`)}
                    >
                        Clear All
                    </button>
                </div>

                <FilterSection title={isAr ? 'الموقع' : 'Location'}>
                    <select
                        value={searchParams.get('destination') || ''}
                        onChange={(e) => updateFilter('destination', e.target.value)}
                        className="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-primary/20"
                    >
                        <option value="">{isAr ? 'جميع الدول' : 'All Countries'}</option>
                        {countries.map(c => <option key={c.id} value={c.name}>{c.name}</option>)}
                    </select>

                    <select
                        value={searchParams.get('city') || ''}
                        onChange={(e) => updateFilter('city', e.target.value)}
                        className="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-primary/20"
                    >
                        <option value="">{isAr ? 'جميع المدن' : 'All Cities'}</option>
                        {cities.map(c => <option key={c.id} value={c.name}>{c.name}</option>)}
                    </select>
                </FilterSection>

                {type === 'courses' ? (
                    <>
                        <FilterSection title={isAr ? 'المرحلة الدراسية' : 'Study Level'}>
                            {levels.map(({ name, key, id }) => {
                                const currentLevel = searchParams.get('level') || 'bachelor';
                                const value = key || String(id);
                                const isSelected = currentLevel === value;
                                return (
                                    <label key={value} className="flex items-center gap-3 cursor-pointer group">
                                        <input
                                            type="radio"
                                            name="study-level"
                                            value={value}
                                            checked={isSelected}
                                            onChange={() => updateFilter('level', value)}
                                            className="w-4 h-4 border-gray-300 text-primary focus:ring-primary cursor-pointer accent-primary"
                                        />
                                        <span className={`text-sm transition-colors ${isSelected ? 'text-primary font-semibold' : 'text-gray-600 group-hover:text-primary'}`}>
                                            {name}
                                        </span>
                                    </label>
                                );
                            })}
                        </FilterSection>

                        <FilterSection title={isAr ? 'موعد القبول' : 'Intake'}>
                            <select
                                value={searchParams.get('intake') || ''}
                                onChange={(e) => updateFilter('intake', e.target.value)}
                                className="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none"
                            >
                                <option value="">{isAr ? 'أي موعد' : 'Any Intake'}</option>
                                {intakes.map(i => <option key={i.id} value={i.name}>{i.name}</option>)}
                            </select>
                        </FilterSection>

                        <FilterSection title={isAr ? 'نطاق الرسوم' : 'Tuition Range'}>
                            <div className="space-y-4">
                                <div>
                                    <label className="text-[10px] text-gray-400 font-bold block mb-1">{isAr ? 'الحد الأقصى للميزانية' : 'MAX BUDGET'}</label>
                                    <select
                                        value={searchParams.get('tuitionMax') || ''}
                                        onChange={(e) => updateFilter('tuitionMax', e.target.value)}
                                        className="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm"
                                    >
                                        <option value="">{isAr ? 'أي مبلغ' : 'Any'}</option>
                                        <option value="15000">$15,000</option>
                                        <option value="25000">$25,000</option>
                                        <option value="40000">$40,000</option>
                                        <option value="60000">$60,000+</option>
                                    </select>
                                </div>
                            </div>
                        </FilterSection>
                    </>
                ) : (
                    <>
                        <FilterSection title={isAr ? 'التصنيف العالمي' : 'World Ranking'}>
                            <div className="grid grid-cols-2 gap-2">
                                <input type="number" placeholder={isAr ? 'أدنى' : 'Min'} value={searchParams.get('rankingMin') || ''} onChange={(e) => updateFilter('rankingMin', e.target.value)} className="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none" />
                                <input type="number" placeholder={isAr ? 'أعلى' : 'Max'} value={searchParams.get('rankingMax') || ''} onChange={(e) => updateFilter('rankingMax', e.target.value)} className="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none" />
                            </div>
                        </FilterSection>

                        <FilterSection title={isAr ? 'خيارات' : 'Options'}>
                            <label className="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" checked={searchParams.get('hasScholarship') === 'true'} onChange={(e) => updateFilter('hasScholarship', e.target.checked)} className="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer" />
                                <span className="text-sm text-gray-600 group-hover:text-primary transition-colors">{isAr ? 'منحة دراسية متاحة' : 'Scholarship Available'}</span>
                            </label>
                        </FilterSection>
                    </>
                )}

                <button
                    onClick={() => router.push(`${pathname}?level=bachelor`)}
                    className="w-full mt-6 py-2.5 bg-gray-50 text-gray-500 rounded-xl text-sm font-bold hover:bg-gray-100 hover:text-gray-700 transition-all border border-gray-100"
                >
                    {isAr ? 'مسح جميع الفلاتر' : 'Clear All Filters'}
                </button>
            </div>

            <div className="bg-red-50 rounded-2xl p-6 border border-red-100">
                <h4 className="font-bold text-red-600 mb-2 text-sm">{isAr ? 'هل تحتاج مشورة خبير؟' : 'Need Expert Advice?'}</h4>
                <p className="text-xs text-red-600/70 mb-4 leading-relaxed">{isAr ? 'مستشارونا المتخصصون هنا لمساعدتك في اختيار الأنسب لك.' : 'Our Ivy-League counselors are here to help you choose the best fit.'}</p>
                <a
                    href="/apply-now"
                    className="block w-full py-3 bg-red-600 text-white rounded-lg text-sm font-bold shadow-lg hover:shadow-xl hover:bg-red-700 transition-all text-center"
                >
                    {isAr ? 'احجز جلسة مجانية' : 'Book Free Session'}
                </a>
            </div>
        </aside>
    );
}
