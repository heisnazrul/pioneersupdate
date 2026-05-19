
const BACKEND_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'https://backend.pioneersedu.com';

function withLang(path: string, lang?: string): string {
    if (!lang) return path;
    const sep = path.includes('?') ? '&' : '?';
    return `${path}${sep}lang=${encodeURIComponent(lang)}`;
}

export interface HeroApiData {
    headline?: string;
    subheadline?: string;
    background_image?: string;
    figure_image?: string;
    search_label?: string;
    search_placeholder_courses?: string;
    search_placeholder_universities?: string;
    country_label?: string;
    country_placeholder?: string;
    level_label?: string;
    level_placeholder?: string;
    intake_label?: string;
    intake_placeholder?: string;
    tab_courses?: string;
    tab_universities?: string;
    search_button_text?: string;
    feature_universities: { name: string; city_name: string | null; logo: string | null }[];
    universities: { name: string; city_name: string | null; logo: string | null }[];
    feature_countries: { name: string; flag: string | null }[];
    levels: { name: string }[];
    courses: { name: string }[];
    intakes: { name: string }[];
}

export interface HomeCmsData {
    hero?: Record<string, any>;
    stats?: Record<string, any>;
    certificates?: Record<string, any>;
    destinations?: Record<string, any>;
    universities?: Record<string, any>;
    reviews?: Record<string, any>;
    scholarships?: Record<string, any>;
    trust?: Record<string, any>;
    faq?: Record<string, any>;
    blogs?: Record<string, any>;
}

export interface CertificateData {
    title: string;
    image: string | null;
    link: string | null;
}

export async function fetchHeroData(lang?: string): Promise<HeroApiData | null> {
    try {
        const response = await fetch(withLang(`${BACKEND_URL}/api/home/hero`, lang), {
            next: { revalidate: 3600 },
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error("Failed to fetch hero data from API:", error);
        return null; // Return null to signal fallback needed
    }
}

export async function fetchHomeCms(lang?: string): Promise<HomeCmsData | null> {
    try {
        const response = await fetch(withLang(`${BACKEND_URL}/api/home/cms`, lang), {
            next: { revalidate: 3600 },
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        const json = await response.json();
        return json?.data || null;
    } catch (error) {
        console.error("Failed to fetch home CMS data from API:", error);
        return null;
    }
}

export async function fetchCertificates(lang?: string): Promise<CertificateData[] | null> {
    try {
        const response = await fetch(withLang(`${BACKEND_URL}/api/home/certificate`, lang), {
            next: { revalidate: 3600 },
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error("Failed to fetch certificates from API:", error);
        return null;
    }
}

export interface DestinationData {
    name: string;
    image: string | null;
    slug: string;
}

export async function fetchDestinations(lang?: string): Promise<DestinationData[] | null> {
    try {
        const response = await fetch(withLang(`${BACKEND_URL}/api/home/destinations`, lang), {
            next: { revalidate: 3600 },
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error("Failed to fetch destinations from API:", error);
        return null;
    }
}

import { Destination } from './data/types';

export async function fetchFullDestinations(lang?: string): Promise<Destination[] | null> {
    try {
        const response = await fetch(withLang(`${BACKEND_URL}/api/destinations`, lang), {
            next: { revalidate: 3600 },
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error("Failed to fetch full destinations from API:", error);
        return null;
    }
}

export async function fetchDestinationBySlug(slug: string, lang?: string): Promise<any | null> {
    try {
        const response = await fetch(withLang(`${BACKEND_URL}/api/destinations/${slug}`, lang), {
            next: { revalidate: 3600 },
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error(`Failed to fetch destination ${slug} from API:`, error);
        return null;
    }
}

export interface UniversityApiData {
    id: number;
    name: string;
    slug: string;
    logo: string | null;
    rank: number | null;
    address: string | null;
    famous_for: string | null;
}

import { University } from './data/types';

export async function fetchUniversities(lang?: string): Promise<University[] | null> {
    try {
        const response = await fetch(withLang(`${BACKEND_URL}/api/home/universities`, lang), {
            next: { revalidate: 3600 },
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        const data: UniversityApiData[] = await response.json();

        return data.map(u => ({
            id: u.id.toString(),
            name: u.name,
            slug: u.slug,
            logoUrl: u.logo || '',
            rank: u.rank ? u.rank.toString() : '',
            location: u.address || '',
            topDisciplines: u.famous_for ? u.famous_for.split(',').map(s => s.trim()) : [],
            countryCode: '', // Default as API doesn't return this yet
            cityId: '',      // Default as API doesn't return this yet
            worldRank: u.rank || 0,
        }));
    } catch (error) {
        console.error("Failed to fetch universities from API:", error);
        return null;
    }
}

export interface VideoReviewApiData {
    id: number;
    name: string;
    university_name: string | null;
    course_name: string | null;
    country_name: string | null;
    thumbnail: string | null;
    duration: string | null;
    review_text: string | null;
    video_url: string | null;
    video_iframe: string | null;
}

export interface StudentReviewApiData {
    id: number;
    name: string;
    role: string;
    title: string;
    text: string;
    rating: number;
}

export interface ReviewsResponse {
    video_reviews: VideoReviewApiData[];
    reviews: StudentReviewApiData[];
}

export async function fetchReviews(lang?: string): Promise<ReviewsResponse | null> {
    try {
        const response = await fetch(withLang(`${BACKEND_URL}/api/home/reviews`, lang), {
            next: { revalidate: 3600 },
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error("Failed to fetch reviews from API:", error);
        return null;
    }
}
export interface ScholarshipApiData {
    id: number;
    title: string;
    slug: string; // Added field
    amount: string;
    deadline: string;
    tags: string[];
}

export async function fetchScholarships(lang?: string): Promise<ScholarshipApiData[] | null> {
    try {
        const response = await fetch(withLang(`${BACKEND_URL}/api/scholarships`, lang), {
            next: { revalidate: 3600 },
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error("Failed to fetch scholarships from API:", error);
        return null;
    }
}

export async function fetchUniversityBySlug(slug: string): Promise<any | null> {
    const url = `${BACKEND_URL}/api/universities/${slug}`;
    // console.log(`[DEBUG] Fetching University: ${url}`);

    try {
        const response = await fetch(url, {
            next: { revalidate: 3600 },
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error(`Failed to fetch university ${slug} from API:`, error);
        return null;
    }
}


import { mapScholarshipDetail } from './mappers';

export async function fetchScholarshipBySlug(slug: string): Promise<any | null> {
    try {
        const response = await fetch(`${BACKEND_URL}/api/scholarships/${slug}`, {
            next: { revalidate: 3600 },
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        const data = await response.json();
        return mapScholarshipDetail(data);
    } catch (error) {
        console.error(`Failed to fetch scholarship ${slug} from API:`, error);
        return null;
    }
}

export async function fetchBlogBySlug(slug: string): Promise<any | null> {
    try {
        const response = await fetch(`${BACKEND_URL}/api/blogs/${slug}`, {
            next: { revalidate: 3600 },
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error(`Failed to fetch blog ${slug} from API:`, error);
        return null;
    }
}


export async function submitApplicationLead(payload: ApplicationPayload): Promise<SubmissionResponse> {
    try {
        const response = await fetch(`${BACKEND_URL}/api/applications`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json();

        if (response.ok) {
            return { success: true, message: 'Application submitted successfully.', ...data }; // Pass back any extra data like ID
        } else {
            return { success: false, message: data.message || 'Submission failed.' };
        }
    } catch (error) {
        console.error('Error submitting application:', error);
        return { success: false, message: 'Network error occurred.' };
    }
}

export async function fetchMyApplications(token: string): Promise<any> {
    try {
        const response = await fetch(`${BACKEND_URL}/api/my-applications`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            next: { revalidate: 0 },
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error("Failed to fetch my applications:", error);
        return null;
    }
}

export async function updateProfile(token: string, data: any): Promise<any> {
    try {
        const response = await fetch(`${BACKEND_URL}/api/profile/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data),
        });

        return await response.json();
    } catch (error) {
        console.error("Failed to update profile:", error);
        return { success: false, message: 'Network error' };
    }
}

// --- Static Page Helpers ---
import { MOCK_DATA } from './data/mocks';
import { ContactInfo, Office, Service, Testimonial, Faq, AgentInfo, TrustPartner, ProcessStep, Benefit, BlogPost, BlogCategory, LeadPayload, SubmissionResponse, ApplicationPayload, AboutPageData } from './data/types';

export async function getContactInfo(): Promise<ContactInfo> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/contact`, { next: { revalidate: 3600 } });
        const data = await res.json();
        if (data && data.address) return data;
    } catch (e) {
        // console.error("Failed to fetch contact info", e);
    }
    return MOCK_DATA.contactInfo;
}

export async function getOffices(): Promise<Office[]> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/offices`, { next: { revalidate: 3600 } });
        const data = await res.json();
        if (Array.isArray(data)) return data;
    } catch (e) {
        // console.error("Failed to fetch offices", e);
    }
    return MOCK_DATA.offices || [];
}

export async function getOfficeBySlug(slug: string): Promise<Office | undefined> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/offices/${slug}`, { next: { revalidate: 3600 } });
        const data = await res.json();
        if (data && data.city) return data;
    } catch (e) {
        // console.error(`Failed to fetch office ${slug}`, e);
    }
    return MOCK_DATA.offices?.find(o => o.slug === slug);
}

export async function submitLead(payload: LeadPayload): Promise<SubmissionResponse> {
    try {
        const body = {
            ...payload,
            subject: payload.topic, // Map topic to subject for backend
        };
        const res = await fetch(`${BACKEND_URL}/api/contact/submit`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body),
        });
        const data = await res.json();
        if (res.ok) {
            return { success: true, message: data.message || 'Message sent successfully.' };
        } else {
            return { success: false, message: data.message || 'Submission failed.' };
        }
    } catch (error) {
        console.error('Error submitting lead:', error);
        return { success: false, message: 'Network error occurred.' };
    }
}

export async function getServices(): Promise<Service[]> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/services`, { next: { revalidate: 3600 } });
        const data = await res.json();
        if (Array.isArray(data)) return data;
    } catch (e) {
        // console.error("Failed to fetch services", e);
    }
    return MOCK_DATA.services;
}

export async function getTestimonials(): Promise<Testimonial[]> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/testimonials`, { next: { revalidate: 3600 } });
        const data = await res.json();
        if (Array.isArray(data)) return data;
    } catch (e) {
        // console.error("Failed to fetch testimonials", e);
    }
    return MOCK_DATA.testimonials;
}

export async function getFaqs(): Promise<Faq[]> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/faqs`, { next: { revalidate: 3600 } });
        const data = await res.json();
        if (Array.isArray(data)) return data;
    } catch (e) {
        // console.error("Failed to fetch faqs", e);
    }
    return MOCK_DATA.faqs;
}

export async function getAgentInfo(): Promise<AgentInfo> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/agent-info`, { next: { revalidate: 3600 } });
        const data = await res.json();
        if (data && data.heroTitle) return data;
    } catch (e) {
        // console.error("Failed to fetch agent info", e);
    }
    return MOCK_DATA.agentInfo;
}

export async function getTrustPartners(): Promise<TrustPartner[]> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/trust-partners`, { next: { revalidate: 3600 } });
        const data = await res.json();
        if (Array.isArray(data)) return data;
    } catch (e) {
        // console.error("Failed to fetch trust partners", e);
    }
    return MOCK_DATA.trustPartners;
}

export async function getProcessSteps(): Promise<ProcessStep[]> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/process-steps`, { next: { revalidate: 3600 } });
        const data = await res.json();
        if (Array.isArray(data)) return data;
    } catch (e) {
        // console.error("Failed to fetch process steps", e);
    }
    return MOCK_DATA.processSteps;
}

export async function getBenefits(): Promise<Benefit[]> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/benefits`, { next: { revalidate: 3600 } });
        const data = await res.json();
        if (Array.isArray(data)) return data;
    } catch (e) {
        // console.error("Failed to fetch benefits", e);
    }
    return MOCK_DATA.benefits;
}

import { normalizeBlog, normalizeBlogs } from './blogs';

export async function getBlogs(lang?: string): Promise<BlogPost[]> {
    try {
        const res = await fetch(withLang(`${BACKEND_URL}/api/blogs`, lang), { next: { revalidate: 3600 } });
        const data = await res.json();

        let rawItems = [];
        if (data && Array.isArray(data.data)) {
            rawItems = data.data;
        } else if (Array.isArray(data)) {
            rawItems = data;
        }

        if (rawItems.length > 0) {
            return normalizeBlogs(rawItems, lang);
        }
    } catch (e) {
        // console.error("Failed to fetch blogs", e);
    }
    return MOCK_DATA.blogs || [];
}

export async function getBlogBySlug(slug: string, lang?: string): Promise<BlogPost | undefined> {
    try {
        const res = await fetch(withLang(`${BACKEND_URL}/api/blogs/${slug}`, lang), { next: { revalidate: 3600 } });
        const data = await res.json();
        if (data && data.title) return normalizeBlog(data, lang);
    } catch (e) {
        // console.error(`Failed to fetch blog ${slug}`, e);
    }
    return MOCK_DATA.blogs?.find(b => b.slug === slug);
}

export async function getBlogCategories(): Promise<BlogCategory[]> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/blog-categories`, { next: { revalidate: 3600 } });
        const data = await res.json();
        if (Array.isArray(data)) return data;
    } catch (e) {
        // console.error("Failed to fetch blog categories", e);
    }
    // Extract from mock blogs if available, or return empty
    // For now returning empty array as mock blogs are empty
    return [];
}

export async function getCoursesByLocation(location: string): Promise<any[]> {
    try {
        const url = `${BACKEND_URL}/api/courses?destination=${encodeURIComponent(location)}&per_page=100`;
        const res = await fetch(url, { next: { revalidate: 600 } });
        if (!res.ok) throw new Error(`getCoursesByLocation: ${res.status}`);
        const data = await res.json();
        const items: any[] = Array.isArray(data) ? data : (data.data ?? []);
        if (items.length > 0) return items;
    } catch (e) {
        console.error('getCoursesByLocation API failed, using mock fallback:', e);
    }
    return MOCK_DATA.courses.filter(c => c.countryName === location || c.location?.includes(location)) || [];
}

export async function getAbout(): Promise<AboutPageData | undefined> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/about`, { next: { revalidate: 3600 } });
        const data = await res.json();
        if (data && data.heroTitle) return data;
    } catch (e) {
        // console.error("Failed to fetch about page data", e);
    }
    return MOCK_DATA.about;
}

export async function submitScholarshipApplication(data: any): Promise<any> {
    try {
        const response = await fetch(`${BACKEND_URL}/api/scholarship-applications`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                // Add Authorization header if token exists (client-side logic handles this if needed, or rely on cookie/session)
                // For this implementation, we might need to pass the token or rely on valid session if using Sanctum stateful
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Submission failed');
        }

        return await response.json();
    } catch (error) {
        console.error("Failed to submit scholarship application", error);
        throw error;
    }
}

export async function getAccommodationRooms(): Promise<any[]> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/accommodation-rooms`, { next: { revalidate: 3600 } });
        if (!res.ok) throw new Error('Failed to fetch accommodation rooms');
        const data = await res.json();
        return data;
    } catch (e) {
        console.error("Failed to fetch accommodation rooms", e);
        return [];
    }
}

export async function getAccommodationRoomBySlug(slug: string): Promise<any | null> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/accommodation-rooms/${slug}`, { next: { revalidate: 3600 } });
        if (!res.ok) return null;
        return await res.json();
    } catch (e) {
        console.error(`Failed to fetch accommodation room ${slug}`, e);
        return null;
    }
}

export async function fetchFaqs(lang?: string): Promise<any[]> {
    try {
        const res = await fetch(withLang(`${BACKEND_URL}/api/home/faqs`, lang), { next: { revalidate: 3600 } });
        if (!res.ok) throw new Error('Failed to fetch FAQs');
        const json = await res.json();
        return Array.isArray(json) ? json : (Array.isArray(json.data) ? json.data : []);
    } catch (e) {
        console.error("fetchFaqs exception:", e);
        return [];
    }
}

export async function fetchHomeBlogs(lang?: string): Promise<any[]> {
    try {
        const res = await fetch(withLang(`${BACKEND_URL}/api/home/blogs`, lang), { next: { revalidate: 3600 } });
        if (!res.ok) throw new Error('Failed to fetch Home Blogs');
        const json = await res.json();
        return Array.isArray(json) ? json : (Array.isArray(json.data) ? json.data : []);
    } catch (e) {
        console.error("fetchHomeBlogs exception:", e);
        return [];
    }
}
