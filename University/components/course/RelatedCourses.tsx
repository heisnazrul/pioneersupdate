"use client";

import Link from 'next/link';
import { Course } from '@/lib/data/types';
import { motion } from 'framer-motion';
import { useLang } from '@/hooks/useLang';

interface RelatedCoursesProps {
    courses: Course[];
    lang?: 'en' | 'ar';
}

export default function RelatedCourses({ courses, lang: langProp }: RelatedCoursesProps) {
    const { isAr: isArHook } = useLang();
    const isAr = langProp ? langProp === 'ar' : isArHook;

    if (!courses || courses.length === 0) return null;

    return (
        <div className="mt-8" dir={isAr ? 'rtl' : 'ltr'}>
            <h3 className="text-lg font-bold text-primary mb-4">
                {isAr ? 'دورات ذات صلة' : 'Related Courses'}
            </h3>
            <div className="space-y-4">
                {courses.map((course, idx) => (
                    <motion.div
                        initial={{ opacity: 0, y: 10 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        transition={{ delay: idx * 0.05 }}
                        key={course.id}
                        viewport={{ once: true }}
                    >
                        <Link href={`/courses/${course.id}`} className="block group bg-white border border-gray-100 rounded-xl p-4 hover:shadow-md transition-all hover:border-secondary/20">
                            <span className="text-[10px] font-bold text-secondary uppercase mb-1 block">{course.level}</span>
                            <h4 className="font-bold text-sm text-foreground mb-1 group-hover:text-primary transition-colors line-clamp-2">{course.name}</h4>
                            <p className="text-xs text-gray-500 mb-2">{course.university}</p>
                            <div className="flex justify-between items-center text-xs font-medium text-gray-400 border-t border-gray-50 pt-2">
                                <span className="truncate max-w-[50%]">{course.location}</span>
                                <span className="text-foreground">{course.duration}</span>
                            </div>
                        </Link>
                    </motion.div>
                ))}
            </div>
        </div>
    );
}
