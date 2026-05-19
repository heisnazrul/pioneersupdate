import Link from 'next/link';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faFilePdf, faFileWord, faDownload, faFileExcel, faFile } from '@fortawesome/free-solid-svg-icons';
import SectionHeading from './ui/SectionHeading';

const ICON_MAP: any = {
    faFileWord,
    faFilePdf,
    faFileExcel,
    faFile
};

interface Tool {
    title: string;
    type: string;
    size?: string;
    year?: number;
    destination?: string;
    icon: string;
    color: string;
    bg: string;
    link?: string;
    file_url?: string;
}

interface ToolsSectionProps {
    data?: {
        title: string;
        subtitle: string;
        description: string;
        items: Tool[];
    };
    items?: Tool[];
}

export default function ToolsResourcesSection({ data, items }: ToolsSectionProps) {
    // Prefer items passed from API (items), otherwise fall back to CMS data
    const resources = items && items.length > 0 ? items : (data?.items || []);

    const title = data?.title || "Tools & Resources";
    const subtitle = data?.subtitle || "Free Downloads";
    const description = data?.description || "Essential downloadable resources for your journey.";

    if (!resources || resources.length === 0) return null;

    return (
        <section className="py-24 bg-white relative overflow-hidden">
            {/* Background Decoration */}
            <div className="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-40">
                <div className="absolute -top-24 -left-24 w-96 h-96 bg-blue-50 rounded-full blur-3xl"></div>
                <div className="absolute top-1/2 right-0 w-64 h-64 bg-yellow-50 rounded-full blur-3xl"></div>
            </div>

            <div className="px-4 md:px-10 xl:px-30 2xl:px-50 relative z-10">
                <SectionHeading
                    subtitle={subtitle}
                    title={title}
                    description={description}
                    align="center"
                />

                <div className="flex flex-wrap justify-center gap-8 mt-12">
                    {resources.map((resource, idx) => {
                        const Icon = ICON_MAP[resource.icon] || faFile;

                        // API returns file_url, CMS returns link
                        // Use file_url if available, else link
                        const link = resource.file_url || resource.link || '#';
                        const target = link.startsWith('http') ? '_blank' : undefined;

                        return (
                            <Link
                                href={link}
                                key={idx}
                                className="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group cursor-pointer w-full md:w-60 block"
                                target={target}
                            >
                                <div className="flex items-start justify-between mb-6">
                                    <div className={`w-14 h-14 rounded-xl ${resource.bg || 'bg-gray-100'} flex items-center justify-center`}>
                                        <FontAwesomeIcon icon={Icon} className={`text-2xl ${resource.color || 'text-gray-500'}`} />
                                    </div>
                                    <span className="bg-gray-100 text-gray-500 text-xs font-bold px-2 py-1 rounded-md">
                                        {resource.type || 'FILE'}
                                    </span>
                                </div>

                                <h3 className="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                                    {resource.title}
                                </h3>

                                {resource.year ? (
                                    <p className="text-sm text-gray-400 mb-6">Year: {resource.year}</p>
                                ) : (
                                    <p className="text-sm text-gray-400 mb-6">{resource.size && resource.size !== 'Unknown' ? `File Size: ${resource.size}` : (resource.destination || 'Download')}</p>
                                )}

                                <div className="w-full py-3 rounded-lg border border-dashed border-gray-300 flex items-center justify-center text-gray-500 font-medium group-hover:border-blue-500 group-hover:text-blue-600 transition-colors">
                                    <FontAwesomeIcon icon={faDownload} className="mr-2" />
                                    Download
                                </div>
                            </Link>
                        );
                    })}
                </div>
            </div>
        </section>
    );
}
