
import React from 'react';
import Link from 'next/link';

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
    variant?: 'primary' | 'secondary' | 'outline' | 'ghost' | 'white';
    size?: 'sm' | 'md' | 'lg';
    href?: string;
    fullWidth?: boolean;
    className?: string; // allow overrides
}

export default function Button({
    children,
    variant = 'primary',
    size = 'md',
    href,
    fullWidth = false,
    className = '',
    ...props
}: ButtonProps) {

    // Spec: scale 1.02, duration 0.15s
    const baseStyles = "inline-flex items-center justify-center font-bold rounded-lg transition-transform duration-150 ease-out hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed";

    const variants = {
        primary: "bg-[#E31E24] text-white hover:bg-[#c4191e] shadow-md hover:shadow-lg focus:ring-[#E31E24]",
        secondary: "bg-[#1C355E] text-white hover:bg-[#152745] shadow-md hover:shadow-lg focus:ring-[#1C355E]",
        outline: "border-2 border-[#1C355E] text-[#1C355E] hover:bg-[#1C355E]/5 focus:ring-[#1C355E] bg-transparent",
        ghost: "text-[#1C355E] hover:bg-[#1C355E]/5",
        white: "bg-white text-[#1C355E] hover:bg-gray-50 shadow-sm"
    };

    const sizes = {
        sm: "px-4 py-1.5 text-sm",
        md: "px-6 py-2.5 text-sm",
        lg: "px-8 py-3.5 text-base"
    };

    const classes = `
        ${baseStyles}
        ${variants[variant]}
        ${sizes[size]}
        ${fullWidth ? 'w-full' : ''}
        ${className}
    `.replace(/\s+/g, ' ').trim();

    if (href) {
        return (
            <Link href={href} className={classes}>
                {children}
            </Link>
        );
    }

    return (
        <button className={classes} {...props}>
            {children}
        </button>
    );
}
