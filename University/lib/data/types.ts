export interface HeroData {
    title: string;
    subtitle: string;
    primaryCtaText: string;
    primaryCtaLink: string;
    secondaryCtaText: string;
    secondaryCtaLink: string;
    backgroundImage: string;
}

export interface TrustPartner {
    name: string;
    logoUrl: string;
}

export interface ProcessStep {
    id: string;
    title: string;
    description: string;
    icon: string;
    stepNumber: number;
}

export interface Benefit {
    id: string;
    title: string;
    description: string;
    icon: string;
}

export interface Stat {
    id: string;
    value: string;
    label: string;
}

// --- New/Updated Types for Generated Data ---

export interface Country {
    id: string;
    name: string;
    code: string;
    region: string;
    currency: string;
    slug: string;
    isPopular?: boolean;
    popularityRank?: number;
    heroFeatured?: boolean;
}

export interface City {
    id: string;
    name: string;
    countryCode: string;
    slug: string;
    country_id?: number;
}

export interface Intake {
    id: number;
    name: string;
    key: string;
    month_num: number;
    // Backward compatibility
    label?: string;
}

export interface Level {
    id: number | string;
    name: string;
    key: string;
    ar_name?: string;
    sort_order?: number;
}

export interface University {
    id: string;
    name: string;
    countryCode: string;
    cityId: string;
    worldRank: number;
    logoUrl: string;
    slug: string;
    // Computed/Mapped properties for UI compatibility
    rank?: string;
    location?: string;
    countryName?: string;
    isFeatured?: boolean;
    topDisciplines?: string[];
    courses?: Course[]; // Embedded courses from API
    coverImageUrl?: string; // Cover image from API
    // Individual ranking systems
    qsRanking?: number | null;
    theRanking?: number | null;
    shanghaiRanking?: number | null;
    /** Grouped catalog view: each catalog with its available levels for this university */
    coursesCatalog?: {
        catalogId: number;
        name: string;
        slug?: string;
        discipline?: string;
        levels: {
            courseId: string;
            levelName: string;
            levelKey: string;
            duration: string;
        }[];
    }[];
}

export interface Course {
    id: string;
    title: string;
    universityId: string;
    level: string;
    durationMonths: number;
    tuitionMin: number;
    tuitionMax: number;
    ieltsMin: number;
    intakes: string[]; // IDs
    discipline: string;
    slug: string;
    // Computed/Mapped properties for UI compatibility
    name?: string;
    university?: string;
    universityLogo?: string; // University logo URL from API
    location?: string;
    duration?: string;
    intake?: string[]; // Legacy strings for UI
    tuition?: number | string; // Tuition fee from API
    currency?: string;
    countryName?: string;
    countryCode?: string; // Country code from API
    description?: string;
    degreeRequirement?: string;
    languageRequirement?: string;
    applicationDeadline?: string;
}

export interface Scholarship {
    id: string;
    title: string;
    countryCode: string;
    universityId: string | null;
    amountMin: number;
    amountMax: number;
    currency: string;
    eligibility: string;
    deadline: string;
    slug: string;
    // Backward compatibility
    amount?: string;
    country?: string;
    tags?: string[];
}

export interface Testimonial {
    id: string;
    name: string;
    destinationCountry: string;
    program: string;
    quote: string;
    // Backward compatibility
    author?: string;
    role?: string;
    content?: string;
    avatarUrl?: string;
}
export interface BlogCategory {
    id: string;
    name: string;
    slug: string;
    blogs_count?: number;
    ar_name?: string;
    name_ar?: string;
}

export interface BlogPost {
    id: string;
    slug: string;
    title: string;
    excerpt: string;
    content: string;
    author: string;
    date: string;
    category: string | BlogCategory; // Allow object or string
    imageUrl: string;
    tags: string[];
    readTime: string;
}

export interface Office {
    id: string;
    slug: string;
    city: string;
    country: string;
    type: string;
    address: string;
    phone: string;
    email: string;
    hours: string;
    description: string;
    image: string;
    mapUrl: string;
}

// ------------------------------------------

// --- Generic Response ---
export interface PaginatedResponse<T> {
    items: T[];
    total: number;
    page: number;
    pageSize: number;
}

// ------------------------------------------

export interface SearchFilters {
    // Filters
    destination?: string;
    city?: string;
    university?: string;
    level?: string;
    intake?: string;
    keyword?: string;
    discipline?: string;
    tuitionMin?: number;
    tuitionMax?: number;
    ieltsMin?: number;
    durationMin?: number;
    durationMax?: number;
    rankingMin?: number;
    rankingMax?: number;
    hasScholarship?: boolean;
    // Pagination & Sort
    page?: number;
    pageSize?: number;
    sort?: string;
}

export interface Program {
    name: string;
    duration: string;
    level: string;
    slug?: string; // Optional/merged
}

export interface Destination {
    id: string;
    slug: string;
    name: string;
    countryCode?: string; // e.g. 'us', 'gb'
    region: string;
    description: string;
    imageUrl: string;
    features: string[];
    shortPitch: string;
    tuitionRange: string;
    visaTimeline: string;
    workRights: string;
    scholarships: string;
    popularPrograms: Program[];
    topUniversities: University[];
    intakeTimeline: { month: string; event: string }[];
    requirements: string[];
    stats: { label: string; value: string }[];
    faqs: Faq[];
    // Added fields to match API
    scholarshipsSummary?: string;
    universityCount?: number;
    guide?: Guide;
}

export interface Guide {
    title: string;
    fileUrl: string;
    year: number;
}

export interface Service {
    id: string;
    title: string;
    description: string;
    icon: any; // Changed from string to any to support FontAwesomeIcon definition or string
    link?: string; // Added optional link
    color?: string; // Added optional color
    bg?: string; // Added optional bg
    audience: 'student' | 'agent';
}

export interface Faq {
    id: string;
    question: string;
    answer: string;
}

export interface AgentBenefit {
    id: string;
    title: string;
    description: string;
    icon: string;
}

export interface PartnerTestimonial {
    id: string;
    partnerName: string;
    company: string;
    content: string;
    imageUrl: string;
}

export interface AgentInfo {
    heroTitle: string;
    heroSubtitle: string;
    benefits: AgentBenefit[];
    processSteps: ProcessStep[];
    requirements: string[];
    testimonials: PartnerTestimonial[];
}

export interface ContactInfo {
    email: string;
    phone: string;
    address: string;
    socialLinks: {
        platform: string;
        url: string;
    }[];
}

export interface CoreValue {
    id: string;
    title: string;
    description: string;
    icon: string;
}

export interface TeamMember {
    id: string;
    name: string;
    role: string;
    bio: string;
    imageUrl: string;
    desc?: string; // Added desc
}

export interface AboutPageData {
    mission: string;
    vision: string;
    values: CoreValue[];
    team: TeamMember[];
    trustFactors: { title: string; description: string; icon: string }[];
    heroTitle?: string;
    heroDescription?: string;
    story?: string;
}

export interface LeadPayload {
    name: string;
    email: string;
    phone: string;
    topic?: string;
    message?: string;
    source?: string;
    destinationInterest?: string;
    destination?: string;
}

export interface SubmissionResponse {
    success: boolean;
    message: string;
    // ...
}

export interface ApplicationPayload {
    firstName: string;
    lastName: string;
    email: string;
    phone: string;
    citizenship: string;
    nationality?: string; // New
    nationalityOther?: string; // New
    highestEducation: string;
    gradeAverage: string;
    hasEnglishTest: boolean;
    englishTestType?: string;
    englishTestScore?: string;
    destinationInterest: string[];
    destinationsOther?: string; // New
    preferredIntake: string;
    budgetRange: string;
    status: 'draft' | 'submitted' | string;
}

export interface SiteData {
    hero: HeroData;
    courses: Course[];
    about: AboutPageData;
    trustPartners: TrustPartner[];
    processSteps: ProcessStep[];
    benefits: Benefit[];
    stats: Stat[];
    destinations: Destination[];
    services: Service[];
    testimonials: Testimonial[];
    faqs: Faq[];
    scholarships: Scholarship[];
    agentInfo: AgentInfo;
    contactInfo: ContactInfo;
    blogs: BlogPost[];
    offices: Office[];
}

export interface Certificate {
    id: string;
    title: string;
    ar_title: string;
    subtitle?: string;
    ar_subtitle?: string;
    certificate_image: string;
    certification_link?: string;
}

// --- Auth Types ---

export interface User {
    id: string;
    name: string;
    email: string;
    phone?: string;
    role?: string;
    avatar?: string;
    status?: string;
    roles?: string[];
    access?: {
        courseenglish: boolean;
        university: boolean;
    };
    profile?: Record<string, unknown> | null;
}

export interface AuthResponse {
    token: string;
    tokenType?: string;
    user: User;
    message?: string;
    roles?: string[];
    access?: {
        courseenglish: boolean;
        university: boolean;
    };
    app?: string;
}

export interface LoginPayload {
    email?: string;
    password?: string;
    phone?: string;
    otp?: string;
}

export interface RegisterPayload {
    name: string;
    email: string;
    phone?: string;
    password?: string;
    password_confirmation?: string;
    role?: 'uni_student' | 'uni_agent';
}
