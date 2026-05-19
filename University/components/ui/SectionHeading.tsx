import React from 'react';

interface SectionHeadingProps {
    title: string;
    subtitle?: string;
    description?: string;
    align?: 'left' | 'center';
    className?: string;
    light?: boolean;
}

export default function SectionHeading({ title, subtitle, description, align = 'center', className = '', light = false }: SectionHeadingProps) {
    return (
        <div className={`mb-12 ${align === 'center' ? 'text-center' : 'text-start'} ${className}`}>
            {subtitle && (
                <span className={`block text-sm font-bold uppercase tracking-widest mb-3 ${light ? 'text-blue-100' : 'text-[#0B3D66]'}`}>
                    {subtitle}
                </span>
            )}
            <h2 className={`text-3xl md:text-4xl font-extrabold ${light ? 'text-white' : 'text-[#0B3D66]'}`}>
                {title}
            </h2>
            {description && (
                <p className={`mt-4 text-gray-500 leading-relaxed ${align === 'center' ? 'mx-auto max-w-2xl' : ''} ${light ? 'text-gray-300' : ''}`}>
                    {description}
                </p>
            )}
            <div className={`h-1.5 w-20 bg-[#135FAE] mt-4 rounded-full ${align === 'center' ? 'mx-auto' : ''}`} />
        </div>
    );
}
