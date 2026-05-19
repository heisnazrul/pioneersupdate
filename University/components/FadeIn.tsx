"use client";

import { motion, useReducedMotion } from 'framer-motion';
import { ReactNode } from 'react';

interface FadeInProps {
    children: ReactNode;
    delay?: number;
    className?: string;
    direction?: 'up' | 'down' | 'left' | 'right';
    fullWidth?: boolean;
}

export default function FadeIn({
    children,
    delay = 0,
    className = "",
    direction = 'up',
    fullWidth = false
}: FadeInProps) {
    const shouldReduceMotion = useReducedMotion();

    const variants = {
        hidden: {
            opacity: 0,
            y: shouldReduceMotion ? 0 : 12, // Reduced from 30 to 12
            x: 0 // Removed x-axis motion to be stricter/minimal
        },
        visible: {
            opacity: 1,
            y: 0,
            x: 0,
            transition: {
                duration: 0.45, // Spec: 0.45s
                ease: "easeOut", // Spec: ease-out
                delay: delay
            }
        }
    };

    return (
        <motion.div
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, margin: "-50px" }}
            variants={variants as any}
            className={`${fullWidth ? 'w-full' : ''} ${className}`}
        >
            {children}
        </motion.div>
    );
}
