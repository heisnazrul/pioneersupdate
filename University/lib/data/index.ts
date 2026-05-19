export * from './types';
export * from './mocks';
// Export specific functions from api.ts to match expected aliases
export {
    getContactInfo,
    getOffices,
    getOfficeBySlug,
    submitLead,
    getServices,
    getTestimonials,
    getFaqs,
    getAgentInfo,
    getTrustPartners,
    getProcessSteps,
    getBenefits,
    getBlogs,
    getBlogBySlug,
    getAbout,
    submitApplicationLead,
    getCoursesByLocation,
    fetchDestinationBySlug as getDestinationBySlug,
    fetchScholarships as getScholarships,
    fetchScholarshipBySlug as getScholarshipBySlug
} from '../api';
