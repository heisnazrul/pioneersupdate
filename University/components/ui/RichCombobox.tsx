"use client";

import { useState, useRef, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faChevronDown, faSearch, faCheck } from '@fortawesome/free-solid-svg-icons';
import Image from 'next/image';

export interface Option {
    id: string;
    label: string;
    subLabel?: string;
    value: string;
    icon?: any; // IconDefinition
    image?: string; // URL
}

interface RichComboboxProps {
    options: Option[];
    value: string;
    onChange: (value: string) => void;
    placeholder?: string;
    label?: string;
    searchable?: boolean;
    mainIcon?: any;
    grid?: boolean;
}

export default function RichCombobox({
    options,
    value,
    onChange,
    placeholder = "Select...",
    label,
    searchable = false,
    mainIcon,
    grid = false
}: RichComboboxProps) {
    const [isOpen, setIsOpen] = useState(false);
    const [searchTerm, setSearchTerm] = useState("");
    const containerRef = useRef<HTMLDivElement>(null);

    // Initial selected option
    const selectedOption = options.find(o => o.value === value);

    // Close on click outside
    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            if (containerRef.current && !containerRef.current.contains(event.target as Node)) {
                setIsOpen(false);
            }
        };
        document.addEventListener("mousedown", handleClickOutside);
        return () => document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    // Filter options
    const filteredOptions = options.filter(option =>
        option.label.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <div className="relative w-full" ref={containerRef}>
            {label && (
                <p className="text-sm font-bold text-slate-700 uppercase tracking-wider mb-2 px-2">
                    {label}
                </p>
            )}

            <div
                onClick={() => setIsOpen(!isOpen)}
                className={`relative flex items-center w-full rounded-lg border bg-white px-4 md:px-6 py-4 md:py-6  shadow-sm cursor-pointer transition-all ${isOpen ? 'border-[#135FAE] ring-1 ring-[#135FAE]' : 'border-[#E1E8F0] hover:border-slate-300'
                    }`}
            >
                {/* Main Icon (optional) */}
                {/* If selected has image/icon, show that instead of generic mainIcon */}
                {selectedOption?.image ? (
                    <div className="mr-3 w-6 h-6 relative flex-shrink-0">
                        <Image src={selectedOption.image} alt="" fill className="object-contain" unoptimized />
                    </div>
                ) : selectedOption?.icon ? (
                    <div className="mr-3 text-slate-400">
                        <FontAwesomeIcon icon={selectedOption.icon} />
                    </div>
                ) : mainIcon ? (
                    <div className="mr-3 text-slate-400">
                        <FontAwesomeIcon icon={mainIcon} />
                    </div>
                ) : null}

                {/* Input / Display Text */}
                <div className="flex-1 truncate">
                    {selectedOption ? (
                        <span className="text-slate-900 font-medium">{selectedOption.label}</span>
                    ) : (
                        <span className="text-slate-400">{placeholder}</span>
                    )}
                </div>

                <div className="ml-2 text-slate-400">
                    <FontAwesomeIcon icon={faChevronDown} className={`w-3 h-3 transition-transform ${isOpen ? 'rotate-180' : ''}`} />
                </div>
            </div>

            {/* Dropdown */}
            <AnimatePresence>
                {isOpen && (
                    <motion.div
                        initial={{ opacity: 0, y: 5, scale: 0.98 }}
                        animate={{ opacity: 1, y: 0, scale: 1 }}
                        exit={{ opacity: 0, y: 5, scale: 0.98 }}
                        transition={{ duration: 0.15 }}
                        className="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden"
                    >
                        {/* Search Input inside dropdown */}
                        {searchable && (
                            <div className="p-2 border-b border-slate-50 sticky top-0 bg-white z-10">
                                <div className="relative">
                                    <FontAwesomeIcon icon={faSearch} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 w-3 h-3" />
                                    <input
                                        type="text"
                                        placeholder="Search..."
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                        onClick={(e) => e.stopPropagation()}
                                        className="w-full pl-9 pr-3 py-2 bg-slate-50 border-none rounded-lg text-sm focus:ring-1 focus:ring-blue-500 text-slate-700"
                                        autoFocus
                                    />
                                </div>
                            </div>
                        )}

                        <div className="max-h-60 overflow-y-auto p-1 custom-scrollbar">
                            {filteredOptions.length > 0 ? (
                                <div className={grid ? "grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-2" : "flex flex-col gap-1"}>
                                    {filteredOptions.slice(0, 12).map((option) => (
                                        <div
                                            key={option.id}
                                            onClick={() => {
                                                onChange(option.value);
                                                setIsOpen(false);
                                                setSearchTerm("");
                                            }}
                                            className={`flex items-center px-3 py-2.5 rounded-lg cursor-pointer transition-colors group ${value === option.value
                                                ? 'bg-blue-50 border-blue-100'
                                                : 'border-transparent hover:bg-slate-50 hover:border-slate-100'
                                                } border`}
                                        >
                                            {/* Option Image/Icon */}
                                            <div className="mr-3 flex justify-center items-center flex-shrink-0">
                                                {option.image ? (
                                                    <div className="relative w-8 h-8">
                                                        <Image src={option.image} alt="" fill className="object-contain" unoptimized />
                                                    </div>
                                                ) : option.icon ? (
                                                    <FontAwesomeIcon icon={option.icon} className={`${value === option.value ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600'}`} />
                                                ) : null}
                                            </div>

                                            <div className="flex-1 min-w-0">
                                                <p className={`text-sm font-medium whitespace-normal break-words ${value === option.value ? 'text-blue-700' : 'text-slate-700'}`}>
                                                    {option.label}
                                                </p>
                                                {option.subLabel && (
                                                    <p className="text-xs text-slate-400 truncate">{option.subLabel}</p>
                                                )}
                                            </div>

                                            {value === option.value && (
                                                <FontAwesomeIcon icon={faCheck} className="text-blue-600 w-3 h-3 ml-2" />
                                            )}
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <div className="p-4 text-center text-sm text-slate-400">No results found</div>
                            )}
                        </div>
                    </motion.div>
                )}
            </AnimatePresence>
        </div>
    );
}
