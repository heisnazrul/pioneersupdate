"use client";

export default function SkeletonSearch({ type = 'results' }: { type?: 'results' | 'sidebar' }) {
    if (type === 'sidebar') {
        return (
            <div className="w-72 space-y-6">
                {[1, 2, 3].map(i => (
                    <div key={i} className="bg-white rounded-2xl p-6 border border-gray-100 space-y-4">
                        <div className="h-3 w-20 bg-gray-100 rounded animate-pulse" />
                        <div className="h-10 w-full bg-gray-50 rounded animate-pulse" />
                        <div className="h-10 w-full bg-gray-50 rounded animate-pulse" />
                    </div>
                ))}
            </div>
        );
    }

    return (
        <div className="space-y-6">
            {[1, 2, 3].map(i => (
                <div key={i} className="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm space-y-6 animate-pulse">
                    <div className="flex justify-between items-start">
                        <div className="space-y-3 flex-1">
                            <div className="h-4 w-24 bg-gray-50 rounded" />
                            <div className="h-8 w-3/4 bg-gray-50 rounded" />
                            <div className="h-4 w-1/2 bg-gray-50 rounded" />
                        </div>
                        <div className="h-12 w-24 bg-gray-50 rounded" />
                    </div>
                    <div className="grid grid-cols-4 gap-6 pt-6 border-t border-gray-50">
                        {[1, 2, 3, 4].map(j => (
                            <div key={j} className="h-12 bg-gray-50 rounded" />
                        ))}
                    </div>
                </div>
            ))}
        </div>
    );
}
