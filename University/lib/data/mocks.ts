import { SiteData } from './types';

export const MOCK_DATA: SiteData = {
    hero: {
        title: "Shape Your Future with World-Class Education",
        subtitle: "We guide you through every step of your international study journey. From application to visa, we're with you.",
        primaryCtaText: "Apply Now",
        primaryCtaLink: "/contact",
        secondaryCtaText: "Talk to a Counselor",
        secondaryCtaLink: "/contact",
        backgroundImage: "/assets/hero.png",
    },
    about: {
        mission: "To democratize access to global education by providing transparent, ethical, and personalized guidance to every student.",
        vision: "To be the world's most trusted partner in international education, bridging the gap between talent and opportunity.",
        values: [
            { id: '1', title: 'Integrity', description: 'We uphold the highest ethical standards in all our counsel.', icon: 'shield' },
            { id: '2', title: 'Student-First', description: 'Your career goals and best interests are our only priority.', icon: 'user' },
            { id: '3', title: 'Transparency', description: 'No hidden costs, no false promises—just honest advice.', icon: 'eye' },
            { id: '4', title: 'Excellence', description: 'We strive for perfection in every application we handle.', icon: 'star' }
        ],
        team: [
            { id: '1', name: 'Dr. Sarah Mitchell', role: 'Founder & CEO', bio: 'Former Admissions Dean at Ivy League University with 15+ years experience.', imageUrl: '/assets/avatar-sj.png' },
            { id: '2', name: 'Raj Patel', role: 'Head of Counseling', bio: 'Helped 1000+ students secure scholarships in UK and Canada.', imageUrl: '/assets/avatar-aa.png' },
            { id: '3', name: 'Elena Rodriguez', role: 'Visa Expert', bio: 'Specialist in complex visa cases with a 99% success rate.', imageUrl: '/assets/avatar-sj.png' }
        ],
        trustFactors: [
            { title: 'Certified Agents', description: 'AIRC Certified & ICEF Trained Counselors', icon: 'award' },
            { title: 'Global Presence', description: 'Offices in 5 countries for local support', icon: 'map-pin' },
            { title: 'Data Privacy', description: 'GDPR Compliant & Secure Data Handling', icon: 'lock' }
        ]
    },
    trustPartners: [
        { name: "Edu Partners", logoUrl: "/assets/partner-2.png" },
        { name: "Study Abroad", logoUrl: "/assets/partner-3.png" },
        { name: "Visa Experts", logoUrl: "/assets/partner-4.png" },
        { name: "Future Pathways", logoUrl: "/assets/partner-5.png" },
    ],
    processSteps: [
        { id: '1', stepNumber: 1, title: 'Profile Analysis', description: 'We evaluate your academic background and career goals.', icon: 'clipboard' },
        { id: '2', stepNumber: 2, title: 'University Selection', description: 'Shortlisting the best universities that match your profile.', icon: 'school' },
        { id: '3', stepNumber: 3, title: 'Application Support', description: 'Assistance with SOPs, LORs, and application forms.', icon: 'edit' },
        { id: '4', stepNumber: 4, title: 'Visa Guidance', description: 'Expert mock interviews and document verification.', icon: 'passport' },
    ],
    benefits: [
        { id: 'b1', title: '100% Free Service', description: 'We do not charge students any fees for counseling or application processing.', icon: 'gift' },
        { id: 'b2', title: '100% Transparency', description: 'No hidden costs or bias. We help you choose what is truly best for you.', icon: 'eye' },
        { id: '1', title: 'Expert Counselors', description: 'Our team comprises alumni from top global universities.', icon: 'user-check' },
        { id: '2', title: '98% Visa Success', description: 'Proven track record of success in difficult cases.', icon: 'check-circle' },
        { id: '3', title: 'End-to-End Support', description: 'From counseling to pre-departure briefing.', icon: 'layers' },
        { id: '4', title: 'Global Network', description: 'Direct partnerships with 500+ universities.', icon: 'globe' },
    ],
    stats: [
        { id: '1', value: "10+", label: "Years Experience" },
        { id: '2', value: "5000+", label: "Students Placed" },
        { id: '3', value: "500+", label: "Partner Universities" },
        { id: '4', value: "98%", label: "Visa Success Rate" },
    ],
    destinations: [], // Simplified for brevity in mock data file, assumes API fetch for detailed destinations
    services: [
        // Student Services
        {
            id: '1',
            title: "Admission Counseling",
            description: "Expert guidance to choose the right course and university based on your profile.",
            icon: "graduation-cap",
            audience: 'student'
        },
        {
            id: '2',
            title: "Visa Assistance",
            description: "Complete support for visa documentation, application, and mock interviews.",
            icon: "passport",
            audience: 'student'
        },
        {
            id: '3',
            title: "Test Preparation",
            description: "Coaching for IELTS, TOEFL, PTE, GRE, and GMAT by certified trainers.",
            icon: "book",
            audience: 'student'
        },
        {
            id: '4',
            title: "Scholarship Guidance",
            description: "Help finding and applying for financial aid and merit-based scholarships.",
            icon: "award",
            audience: 'student'
        },
        // Agent Services
        {
            id: 'a1',
            title: "Partner Network Access",
            description: "Gain access to our portal of 500+ partner universities globally.",
            icon: "globe",
            audience: 'agent'
        },
        {
            id: 'a2',
            title: "Commission Management",
            description: "Transparent and timely commission processing for all successful placements.",
            icon: "briefcase",
            audience: 'agent'
        },
        {
            id: 'a3',
            title: "Marketing Support",
            description: "Co-branded marketing materials and digital assets to help you convert leads.",
            icon: "megaphone",
            audience: 'agent'
        },
        {
            id: 'a4',
            title: "Training & Certification",
            description: "Regular webinars and training sessions to keep you updated on study abroad trends.",
            icon: "certificate",
            audience: 'agent'
        }
    ],
    testimonials: [
        {
            id: '1',
            name: "Sarah Johnson",
            destinationCountry: "UK",
            program: "Master's",
            quote: "Pioneers Admissions made my dream of studying in London a reality. Their support was incredible.",
            avatarUrl: "/assets/avatar-sj.png"
        },
        {
            id: '2',
            name: "Ahmed Ali",
            destinationCountry: "Canada",
            program: "Engineering",
            quote: "The team helped me navigate the complex visa process with ease. Highly recommended!",
            avatarUrl: "/assets/avatar-aa.png"
        }
    ],
    faqs: [
        {
            id: '1',
            question: "When should I start the application process?",
            answer: "Ideally, you should start 1 year to 8 months before your intended intake to ensure enough time for test prep, documentation, and visa processing."
        },
        {
            id: '2',
            question: "Do you offer scholarship assistance?",
            answer: "Yes, we help identify and apply for scholarships that match your academic profile and financial needs, potentially reducing your tuition burden."
        },
        {
            id: '3',
            question: "Which countries do you cover?",
            answer: "We cover major study destinations including the USA, UK, Canada, Australia, Ireland, New Zealand, and select European countries."
        },
        {
            id: '4',
            question: "What tests do I need to take?",
            answer: "This depends on the country and course. Typically, you might need IELTS/TOEFL/PTE for language proficiency and GRE/GMAT/SAT for academic aptitude."
        },
        {
            id: '5',
            question: "How long does the student visa process take?",
            answer: "Visa processing times vary by country. It can range from 2 weeks (UK/Australia) to several months (Canada/USA depending on backlog)."
        },
        {
            id: '6',
            question: "Do you charge a consultation fee?",
            answer: "Our initial consultation is completely free! We want to understand your goals first. Fees for specific services can be discussed during the meeting."
        }
    ],
    agentInfo: {
        heroTitle: "Grow your student placements with Pioneers Admissions",
        heroSubtitle: "Partner with a global leader in international education. We provide the tools, support, and network you need to succeed.",
        benefits: [
            { id: '1', title: "Lucrative Commissions", description: "Competitive commission structures with timely payouts.", icon: "dollar-sign" },
            { id: '2', title: "Real-time Tracking", description: "Track your applications and status updates via our dedicated portal.", icon: "activity" },
            { id: '3', title: "Marketing Support", description: "Access co-branded materials and digital assets to boost your leads.", icon: "megaphone" },
            { id: '4', title: "Dedicated Support", description: "Direct access to regional managers for priority query resolution.", icon: "users" }
        ],
        processSteps: [
            { id: 'p1', stepNumber: 1, title: 'Register', description: 'Fill out the partnership inquiry form.', icon: 'file-text' },
            { id: 'p2', stepNumber: 2, title: 'Verification', description: 'Our team verifies your credentials and business details.', icon: 'shield' },
            { id: 'p3', stepNumber: 3, title: 'Onboarding', description: 'Receive training on our portal and processes.', icon: 'play-circle' },
            { id: 'p4', stepNumber: 4, title: 'Start Recruiting', description: 'Begin submitting applications and earning commissions.', icon: 'briefcase' }
        ],
        requirements: [
            "Valid Business Registration License",
            "Minimum 2 years of experience in student recruitment",
            "Physical office infrastructure",
            "References from 2 university partners"
        ],
        testimonials: [
            { id: 't1', partnerName: "David Chen", company: "Future Links Edu", content: "Pioneers Admissions has transformed our business. Their portal is intuitive and the support is unmatched.", imageUrl: "/assets/avatar-aa.png" },
            { id: 't2', partnerName: "Maria Garcia", company: "Global Study Point", content: "Reliable payments and excellent university options. Best partner we have worked with.", imageUrl: "/assets/avatar-sj.png" }
        ]
    },
    contactInfo: {
        email: "info@pioneersedu.com",
        phone: "+1 (555) 123-4567",
        address: "123 Education Lane, Knowledge City, 10001",
        socialLinks: [
            { platform: "Facebook", url: "#" },
            { platform: "Instagram", url: "#" },
            { platform: "LinkedIn", url: "#" }
        ]
    },
    courses: [],
    scholarships: [],
    blogs: [],
    offices: [
        {
            id: '1',
            slug: 'london-hq',
            city: "London",
            country: "United Kingdom",
            type: "Global HQ",
            address: "123 Oxford Street, London, W1D 1LT",
            phone: "+44 20 7123 4567",
            email: "london@pioneers.edu",
            hours: "Mon-Fri: 9am - 6pm",
            description: "Our global headquarters located in the heart of London, serving students from across Europe and beyond.",
            image: "/assets/destinations/uk.png",
            mapUrl: "https://maps.google.com"
        },
        {
            id: '2',
            slug: 'dubai-branch',
            city: "Dubai",
            country: "UAE",
            type: "Regional Hub",
            address: "Office 45, Knowledge Park, Dubai",
            phone: "+971 4 123 4567",
            email: "dubai@pioneers.edu",
            hours: "Sun-Thu: 9am - 6pm",
            description: "Serving our students in the Middle East with expert counseling for UK, USA, and Canadian universities.",
            image: "/assets/destinations/australia.png",
            mapUrl: "https://maps.google.com"
        },
        {
            id: '3',
            slug: 'delhi-branch',
            city: "New Delhi",
            country: "India",
            type: "Support Center",
            address: "Connaught Place, New Delhi, 110001",
            phone: "+91 11 1234 5678",
            email: "delhi@pioneers.edu",
            hours: "Mon-Sat: 10am - 7pm",
            description: "Our largest support center providing comprehensive documentation and visa assistance.",
            image: "/assets/destinations/usa.png",
            mapUrl: "https://maps.google.com"
        },
        {
            id: '4',
            slug: 'new-york-office',
            city: "New York",
            country: "USA",
            type: "North America Office",
            address: "5th Avenue, New York, NY 10001",
            phone: "+1 212 555 0199",
            email: "nyc@pioneers.edu",
            hours: "Mon-Fri: 9am - 5pm",
            description: "Connecting students with top Ivy League and US institutions directly from New York.",
            image: "/assets/destinations/usa.png",
            mapUrl: "https://maps.google.com"
        }
    ]
};
