
import {
    Country,
    City,
    Intake,
    Level,
    Course,
    University,
    Scholarship,
    PaginatedResponse,
    SearchFilters
} from '@/lib/data/types';

// Import JSON data for local fallback
// Adjusted imports for University structure (lib/data)
import countriesData from '@/lib/data/countries.json';
import citiesData from '@/lib/data/cities.json';
import intakesData from '@/lib/data/intakes.json';
import levelsData from '@/lib/data/levels.json';

import universitiesData from '@/lib/data/universities.json';
import coursesData from '@/lib/data/courses.json';
import scholarshipsData from '@/lib/data/scholarships.json';
import destinationsData from '@/lib/data/destinations.json';

const API_BASE_URL = (process.env.NEXT_PUBLIC_BACKEND_URL || 'https://backend.pioneersedu.com') + '/api';

// --- Helpers ---

// Check if we should use API
const useApi = () => !!API_BASE_URL;

// Generic Fetcher
async function fetchFromApi<T>(endpoint: string, params?: Record<string, string | number>): Promise<T | null> {
    try {
        const url = new URL(`${API_BASE_URL}${endpoint}`);
        if (params) {
            Object.entries(params).forEach(([key, value]) => {
                if (value !== undefined && value !== null) {
                    url.searchParams.append(key, String(value));
                }
            });
        }
        const res = await fetch(url.toString(), { cache: 'no-store' });
        if (!res.ok) throw new Error(`API call failed: ${res.statusText}`);
        return await res.json();
    } catch (error) {
        console.error(`Error fetching ${endpoint}:`, error);
        return null; // Fallback to local will happen in caller
    }
}

// Local Paginator
function paginate<T>(items: T[], page: number = 1, pageSize: number = 10): PaginatedResponse<T> {
    const start = (page - 1) * pageSize;
    const end = start + pageSize;
    const paginatedItems = items.slice(start, end);

    return {
        items: paginatedItems,
        total: items.length,
        page,
        pageSize
    };
}

// Data Mappers (Same as publicApi but cleaner if needed, reusing logic)
// For local data, we need to join relationships manually to return rich objects
function mapUniversityLocal(uni: any): University {
    const city = (citiesData as City[]).find(c => c.id === uni.cityId);
    const country = (countriesData as Country[]).find(c => c.code === uni.countryCode);

    // Calculate rank string if not present
    const rankStr = uni.rank || `#${uni.worldRank}`;

    return {
        ...uni,
        rank: rankStr,
        location: uni.location || `${city?.name || ''}, ${country?.name || ''}`,
        countryName: country?.name
    };
}

function mapCourseLocal(course: any): Course {
    const uni = (universitiesData as any[]).find(u => u.id === course.universityId);
    const mappedUni = uni ? mapUniversityLocal(uni) : null;

    const durationStr = course.duration || `${Math.floor(course.durationMonths / 12)} Years`;

    // Map intake IDs to labels
    const mappedIntakes = course.intakes.map((id: string) => {
        const i = (intakesData as unknown as Intake[]).find(int => ((int as any).id || int.id) === id);
        return i ? i.label : id;
    });

    return {
        ...course,
        name: course.title, // Map title to name for UI
        university: mappedUni?.name || 'Unknown University',
        location: mappedUni?.location || 'Unknown Location',
        duration: course.durationMonths < 12 ? `${course.durationMonths} Months` : `${Math.floor(course.durationMonths / 12)} Years`,
        intake: mappedIntakes, // Array of strings
        currency: (countriesData as Country[]).find(c => c.code === mappedUni?.countryCode)?.currency || '$',
        countryName: mappedUni?.countryName
    };
}

function mapScholarshipLocal(sch: any): Scholarship {
    const country = (countriesData as Country[]).find(c => c.code === sch.countryCode);
    const amountStr = sch.amount || `${sch.currency} ${sch.amountMin} - ${sch.amountMax}`;

    return {
        ...sch,
        amount: amountStr,
        country: country?.name || sch.countryCode, // Map code to name
        tags: sch.tags || [sch.eligibility]
    };
}

// --- Public Data Functions ---

export async function getCountries(): Promise<Country[]> {
    if (useApi()) {
        const res = await fetchFromApi<any>('/countries');
        const data = res?.data || res;
        if (Array.isArray(data)) return data;
    }
    return countriesData as Country[];
}

export async function getCitiesByCountry(countryCode: string): Promise<City[]> {
    if (useApi()) {
        const data = await fetchFromApi<City[]>(`/cities`, { country: countryCode });
        if (data) return data;
    }
    return (citiesData as City[]).filter(c => c.countryCode === countryCode);
}

export async function getCities(countryName?: string): Promise<City[]> {
    if (useApi()) {
        const data = await fetchFromApi<City[]>('/cities'); // Fetch all cities
        if (data) {
            if (countryName) {
                // We need to filter by country. First find the country ID.
                // We can't rely on local countriesData if we are using API, 
                // but getCountries() handles that abstraction.
                const countries = await getCountries();
                const country = countries.find(c => c.name.toLowerCase() === countryName.toLowerCase());

                if (country) {
                    return data.filter(c => c.country_id && String(c.country_id) === country.id);
                }
            }
            return data;
        }
    }

    // Local fallback - filter cities that have universities with courses
    let cities = citiesData as City[];

    if (countryName) {
        const country = (countriesData as Country[]).find(
            c => c.name.toLowerCase() === countryName.toLowerCase()
        );
        if (country) {
            cities = cities.filter(c => c.countryCode === country.code);
        }
    }

    // Filter to only cities with universities that have courses
    cities = cities.filter(city => {
        const hasUniversityWithCourses = (universitiesData as any[]).some(uni =>
            uni.cityId === city.id &&
            (coursesData as any[]).some(course => course.universityId === uni.id)
        );
        return hasUniversityWithCourses;
    });

    return cities;
}

export async function getIntakes(): Promise<Intake[]> {
    if (useApi()) {
        const data = await fetchFromApi<Intake[]>('/intakes');
        if (data) return data;
    }
    return intakesData as unknown as Intake[];
}

export async function getLevels(): Promise<Level[]> {
    if (useApi()) {
        const data = await fetchFromApi<Level[]>('/levels');
        if (data) return data;
    }
    return levelsData as unknown as Level[];
}

export async function searchCourses(filters: SearchFilters = {}, lang?: string): Promise<PaginatedResponse<Course>> {
    const { page = 1, pageSize = 10, sort, ...queryFitlers } = filters;

    if (useApi()) {
        const response = await fetchFromApi<any>('/courses', {
            page,
            pageSize,
            sort: sort || '',
            destination: queryFitlers.destination || '',
            city: queryFitlers.city || '',
            university: queryFitlers.university || '',
            level: queryFitlers.level || '',
            intake: queryFitlers.intake || '',
            keyword: queryFitlers.keyword || '',
            discipline: queryFitlers.discipline || '',
            durationMin: queryFitlers.durationMin || '',
            durationMax: queryFitlers.durationMax || '',
            ...(lang ? { lang } : {}),
        });

        if (response && Array.isArray(response.data)) {
            return {
                items: response.data,
                total: response.total || response.meta?.total || response.data.length,
                page: response.current_page || response.meta?.current_page || page,
                pageSize: response.per_page || response.meta?.per_page || pageSize
            };
        }
    }

    // Local Filtering
    let results = (coursesData as any[]).map(mapCourseLocal);

    if (queryFitlers.keyword) {
        const lowerK = queryFitlers.keyword.trim().toLowerCase();
        results = results.filter(c =>
            (c.title || '').toLowerCase().includes(lowerK) ||
            (c.university || '').toLowerCase().includes(lowerK)
        );
    }
    if (queryFitlers.level) {
        // Handle multiple levels if comma separated (e.g. "Bachelor,Master")
        const levels = queryFitlers.level.split(',').map(l => l.trim().toLowerCase());
        results = results.filter(c => levels.some(l => (c.level || '').toLowerCase() === l));
    }
    if (queryFitlers.destination) {
        const dest = queryFitlers.destination.trim().toLowerCase();
        results = results.filter(c =>
            (c.location || '').toLowerCase().includes(dest) ||
            (c.university || '').toLowerCase().includes(dest) || // sometimes mapped uni has country
            (c.countryName || '').toLowerCase().includes(dest)
        );
    }
    if (queryFitlers.city) {
        const city = queryFitlers.city.trim().toLowerCase();
        results = results.filter(c => (c.location || '').toLowerCase().includes(city));
    }
    if (queryFitlers.intake) {
        const iQ = queryFitlers.intake.trim().toLowerCase();
        results = results.filter(c =>
            c.intakes?.some(i => i.toLowerCase().includes(iQ)) ||
            c.intake?.some(i => i.toLowerCase().includes(iQ))
        );
    }
    if (queryFitlers.discipline) {
        results = results.filter(c => c.discipline.toLowerCase() === queryFitlers.discipline?.toLowerCase());
    }
    if (queryFitlers.tuitionMin !== undefined) {
        results = results.filter(c => c.tuitionMin >= queryFitlers.tuitionMin!);
    }
    if (queryFitlers.tuitionMax !== undefined) {
        results = results.filter(c => c.tuitionMin <= queryFitlers.tuitionMax!);
    }
    if (queryFitlers.ieltsMin !== undefined) {
        results = results.filter(c => c.ieltsMin >= queryFitlers.ieltsMin!);
    }
    if (queryFitlers.durationMin !== undefined) {
        results = results.filter(c => c.durationMonths >= queryFitlers.durationMin!);
    }
    if (queryFitlers.durationMax !== undefined) {
        results = results.filter(c => c.durationMonths <= queryFitlers.durationMax!);
    }

    // Sorting
    if (sort) {
        results.sort((a, b) => {
            if (sort === 'duration_asc') return a.durationMonths - b.durationMonths;
            if (sort === 'duration_desc') return b.durationMonths - a.durationMonths;
            return 0;
        });
    }

    return paginate(results, page, pageSize);
}

export async function searchUniversities(filters: SearchFilters = {}, lang?: string): Promise<PaginatedResponse<University>> {
    const { page = 1, pageSize = 10, sort, ...queryFitlers } = filters;

    if (useApi()) {
        const response = await fetchFromApi<any>('/universities', {
            page,
            pageSize,
            sort: sort || '',
            destination: queryFitlers.destination || '',
            city: queryFitlers.city || '',
            keyword: queryFitlers.keyword || '',
            rankingMin: queryFitlers.rankingMin || '',
            rankingMax: queryFitlers.rankingMax || '',
            hasScholarship: queryFitlers.hasScholarship ? 'true' : ''
        });

        if (response && Array.isArray(response.data)) {
            return {
                items: response.data,
                total: response.total || response.meta?.total || response.data.length,
                page: response.current_page || response.meta?.current_page || page,
                pageSize: response.per_page || response.meta?.per_page || pageSize
            };
        }
    }

    let results = (universitiesData as any[]).map(mapUniversityLocal);

    if (queryFitlers.keyword) {
        const lowerK = queryFitlers.keyword.toLowerCase();
        results = results.filter(u => u.name.toLowerCase().includes(lowerK));
    }
    if (queryFitlers.destination) {
        results = results.filter(u => u.location?.toLowerCase().includes(queryFitlers.destination!.toLowerCase()));
    }
    if (queryFitlers.rankingMin !== undefined) {
        results = results.filter(u => u.worldRank >= queryFitlers.rankingMin!);
    }
    if (queryFitlers.rankingMax !== undefined) {
        results = results.filter(u => u.worldRank <= queryFitlers.rankingMax!);
    }
    if (queryFitlers.hasScholarship) {
        // Check if any scholarship exists for this university in scholarshipsData
        results = results.filter(u => scholarshipsData.some(s => s.universityId === u.id || (s.countryCode === u.countryCode && !s.universityId)));
    }

    // Sorting
    if (sort) {
        results.sort((a, b) => {
            if (sort === 'rank_asc') return a.worldRank - b.worldRank;
            if (sort === 'rank_desc') return b.worldRank - a.worldRank;
            return 0;
        });
    }

    return paginate(results, page, pageSize);
}

export async function getCourseById(id: string): Promise<Course | undefined> {
    if (useApi()) {
        const data = await fetchFromApi<Course>(`/courses/${id}`);
        if (data) return data; // Assume undefined/null handling
    }

    const c = (coursesData as any[]).find(x => x.id === id);
    return c ? mapCourseLocal(c) : undefined;
}

export async function getUniversityById(id: string): Promise<University | undefined> {
    if (useApi()) {
        const data = await fetchFromApi<University>(`/universities/${id}`);
        if (data) return data;
    }

    const u = (universitiesData as any[]).find(x => x.id === id);
    return u ? mapUniversityLocal(u) : undefined;
}

export async function getCourseBySlug(slug: string, lang?: string): Promise<Course | undefined> {
    if (useApi()) {
        const response = await fetchFromApi<any>(`/courses/${slug}`, lang ? { lang } : undefined);
        if (response?.data) {
            return response.data;
        }
        // If response is direct object (not wrapped in data)
        if (response && response.id) {
            return response;
        }
    }

    const c = (coursesData as any[]).find(x => x.slug === slug);
    return c ? mapCourseLocal(c) : undefined;
}

export async function getUniversityBySlug(slug: string, lang?: string): Promise<University | undefined> {
    if (useApi()) {
        const response = await fetchFromApi<any>(`/universities/${slug}`, lang ? { lang } : undefined);
        if (response?.data) {
            return response.data;
        }
        // If response is direct object (not wrapped in data)
        if (response && response.id) {
            return response;
        }
    }

    const u = (universitiesData as any[]).find(x => x.slug === slug);
    return u ? mapUniversityLocal(u) : undefined;
}

export async function getScholarships(filters: SearchFilters = {}, lang?: string): Promise<PaginatedResponse<Scholarship>> {
    const { page = 1, pageSize = 10, sort, ...queryFitlers } = filters;

    if (useApi()) {
        const response = await fetchFromApi<any>('/scholarships', {
            page,
            pageSize,
            sort: sort || '',
            destination: queryFitlers.destination || '',
            level: queryFitlers.level || '',
            intake: queryFitlers.intake || '',
            keyword: queryFitlers.keyword || '',
            discipline: queryFitlers.discipline || '',
            ...(lang ? { lang } : {}),
        });

        if (response && Array.isArray(response.data)) {
            return {
                items: response.data,
                total: response.total || response.meta?.total || response.data.length,
                page: response.current_page || response.meta?.current_page || page,
                pageSize: response.per_page || response.meta?.per_page || pageSize
            };
        }
    }

    let results = (scholarshipsData as any[]).map(mapScholarshipLocal);

    if (queryFitlers.destination) {
        results = results.filter(s => s.countryCode.toLowerCase() === queryFitlers.destination?.toLowerCase() || s.country?.toLowerCase().includes(queryFitlers.destination!.toLowerCase()));
    }

    // Add sorting if needed

    return paginate(results, page, pageSize);
}

export async function getScholarshipById(id: string, lang?: string): Promise<Scholarship | undefined> {
    if (useApi()) {
        const data = await fetchFromApi<Scholarship>(`/scholarships/${id}`, lang ? { lang } : undefined);
        if (data) return data;
    }

    const s = (scholarshipsData as any[]).find(x => x.id === id);
    return s ? mapScholarshipLocal(s) : undefined;
}

/**
 * Returns the top popular countries based on isPopular flag and popularityRank.
 */
export async function getPopularCountries(limit: number = 10): Promise<Country[]> {
    const countries = await getCountries();
    return countries
        .filter(c => c.isPopular)
        .sort((a, b) => (a.popularityRank || 999) - (b.popularityRank || 999))
        .slice(0, limit);
}

/**
 * Returns featured countries for hero section/chips.
 */
export async function getHeroFeaturedCountries(): Promise<Country[]> {
    const countries = await getCountries();
    return countries
        .filter(c => c.heroFeatured)
        .sort((a, b) => (a.popularityRank || 999) - (b.popularityRank || 999));
}
