
import { Suspense } from 'react';
import { searchUniversities } from '@/services/publicData';
import { SearchFilters } from '@/lib/data/types';
import UniversitySearchResults from '@/components/search/UniversitySearchResults';
import { getLang } from '@/lib/getLang';

export const dynamic = 'force-dynamic';

export default async function UniversitiesPage({ searchParams }: { searchParams: Promise<any> }) {
    const params = await searchParams;
    const page = Number(params.page) || 1;
    const pageSize = 12; // Grid of 3 cols

    const filters: SearchFilters = {
        destination: params.destination as string,
        city: params.city as string,
        keyword: params.keyword as string,
        rankingMin: params.rankingMin ? Number(params.rankingMin) : undefined,
        rankingMax: params.rankingMax ? Number(params.rankingMax) : undefined,
        hasScholarship: params.hasScholarship === 'true',
        page,
        pageSize,
        sort: params.sort as string
    };

    const lang = await getLang();
    const response = await searchUniversities(filters, lang);

    return (
        <Suspense fallback={<div className="container mx-auto p-16 text-center">Loading university results...</div>}>
            <UniversitySearchResults
                initialUniversities={response.items}
                total={response.total}
                currentPage={response.page}
                pageSize={response.pageSize}
            />
        </Suspense>
    );
}
