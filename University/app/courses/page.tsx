import { Suspense } from 'react';
import { searchCourses } from '@/services/publicData';
import { SearchFilters } from '@/lib/data/types';
import CourseSearchResults from '@/components/search/CourseSearchResults';

export const dynamic = 'force-dynamic'; // Ensure new params trigger re-render

export default async function CoursesPage({ searchParams }: { searchParams: Promise<any> }) {
    const params = await searchParams;
    const page = Number(params.page) || 1;
    const pageSize = 12;

    const filters: SearchFilters = {
        destination: params.destination as string,
        city: params.city as string,
        level: params.level as string,
        intake: params.intake as string,
        keyword: params.keyword as string,
        discipline: params.discipline as string,
        tuitionMin: params.tuitionMin ? Number(params.tuitionMin) : undefined,
        tuitionMax: params.tuitionMax ? Number(params.tuitionMax) : undefined,
        ieltsMin: params.ieltsMin ? Number(params.ieltsMin) : undefined,
        durationMin: params.durationMin ? Number(params.durationMin) : undefined,
        durationMax: params.durationMax ? Number(params.durationMax) : undefined,
        page,
        pageSize,
        sort: params.sort as string
    };

    // Fetch data using the clean data layer
    const response = await searchCourses(filters);


    return (
        <Suspense fallback={<div className="container mx-auto p-16 text-center">Loading search results...</div>}>
            <CourseSearchResults
                initialCourses={response.items}
                total={response.total}
                currentPage={response.page}
                pageSize={response.pageSize}
            />
        </Suspense>
    );
}
