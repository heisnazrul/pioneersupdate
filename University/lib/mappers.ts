import { Destination, Course } from './data/types';
import { MOCK_DATA } from './data/mocks';

// Helper to map API response to Destination interface
export function mapDestination(item: any): Destination {
    // Sanitize image URL to handle potential double-prefixing from backend
    let imageUrl = item.image_url || item.imageUrl || '';
    if (imageUrl.includes('http://localhost:8000/storage/http')) {
        imageUrl = imageUrl.replace('http://localhost:8000/storage/', '');
    }

    return {
        id: item.id.toString(),
        slug: item.slug,
        name: item.name,
        imageUrl: imageUrl,
        region: item.region,
        description: item.description || item.short_pitch || item.shortPitch || '',
        shortPitch: item.short_pitch || item.shortPitch || '',
        tuitionRange: item.tuition_range || item.tuitionRange || '',
        visaTimeline: item.visa_timeline || item.visaTimeline || '',
        workRights: item.work_rights || item.workRights || '',
        scholarshipsSummary: item.scholarships_summary || item.scholarships || '',
        features: Array.isArray(item.features) ? item.features.map((f: any) => typeof f === 'string' ? f : (f.feature || f.name || '')) : [],
        stats: Array.isArray(item.stats) ? item.stats.map((s: any) => ({
            label: s.label,
            value: s.value
        })) : [],
        universityCount: item.university_count || 0,
        popularPrograms: [],
        topUniversities: Array.isArray(item.topUniversities) ? item.topUniversities.map((u: any) => ({
            id: u.id.toString(),
            name: u.name,
            slug: u.slug,
            logoUrl: u.logoUrl || u.logo || '',
            rank: u.rank?.toString() || '',
            location: u.location || '',
            countryCode: u.countryCode || '',
            cityId: u.cityId?.toString() || '',
            worldRank: u.worldRank || 0,
        })) : [],
        scholarships: Array.isArray(item.scholarships) ? item.scholarships.map((s: any) => s.name || s).join(', ') : (item.scholarships_summary || item.scholarships || ''),
        intakeTimeline: Array.isArray(item.intakeTimeline) ? item.intakeTimeline : (Array.isArray(item.intakes) ? item.intakes.map((i: any) => ({ month: i.month || '', event: i.event || i.label || '' })) : []),
        requirements: Array.isArray(item.requirements) ? item.requirements.map((r: any) => typeof r === 'string' ? r : (r.requirement || r.name || '')) : [],
        faqs: Array.isArray(item.faqs) ? item.faqs.map((f: any) => ({
            id: f.id?.toString(),
            question: f.question,
            answer: f.answer
        })) : [],
        guide: item.guide ? {
            title: item.guide.title,
            fileUrl: item.guide.fileUrl,
            year: item.guide.year
        } : undefined
    };
}

// Safe mapper for API course data that handles nested objects
export function mapCourseFromApi(course: any): Course {
    return {
        id: course.id.toString(),
        slug: course.slug,
        title: course.name || course.title || 'Untitled Course',
        name: course.name || course.title || 'Untitled Course', // Legacy support
        universityId: course.university_id ? course.university_id.toString() : '0',
        university: typeof course.university === 'object' ? (course.university.name || 'Unknown University') : (course.university || 'Unknown University'),
        location: typeof course.location === 'object' ? (course.location.name || 'Unknown Location') : (course.location || 'Unknown Location'),
        level: typeof course.level === 'object' ? (course.level.name || '') : (course.level || ''),
        duration: typeof course.duration === 'object' ? (course.duration.name || '') : (course.duration || ''),
        durationMonths: typeof course.duration_months === 'number' ? course.duration_months : 0,
        tuitionMin: typeof course.tuition_min === 'number' ? course.tuition_min : 0,
        tuitionMax: typeof course.tuition_max === 'number' ? course.tuition_max : 0,
        ieltsMin: typeof course.ielts_min === 'number' ? course.ielts_min : 0,
        discipline: typeof course.discipline === 'object' ? (course.discipline.name || 'General') : (course.discipline || 'General'),
        intakes: [],
        intake: Array.isArray(course.intakes) ? course.intakes.map((i: any) => typeof i === 'object' ? (i.month || i.name) : i) : []
    };
}


export function mapScholarshipDetail(item: any): any {
    return {
        id: item.id,
        title: item.name || item.title,
        slug: item.slug,
        amount: item.amount_display || item.amount,
        deadline: item.deadline,
        country: item.country || (item.university ? item.university.country : 'International'), // Try to get country from uni
        type: item.type || 'All Levels',
        description: item.description || item.summary || '',
        content: item.content || (item.eligibility_text ? `<p>${item.eligibility_text}</p>` : ''),
        requirements: item.requirements || (item.eligibility_text ? [item.eligibility_text] : []),
        applyLink: item.apply_link
    };
}
