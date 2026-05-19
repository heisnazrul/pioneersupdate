import SkeletonSearch from '@/components/search/SkeletonSearch';

export default function Loading() {
    return (
        <div className="min-h-screen bg-[#F9FAFB]">
            <div className="h-20 bg-white border-b border-gray-100 mb-8 animate-pulse" />
            <div className="px-4 md:px-10 xl:px-30 2xl:px-50 py-8">
                <div className="flex flex-col lg:flex-row gap-8">
                    <SkeletonSearch type="sidebar" />
                    <div className="flex-1">
                        <SkeletonSearch type="results" />
                    </div>
                </div>
            </div>
        </div>
    );
}
