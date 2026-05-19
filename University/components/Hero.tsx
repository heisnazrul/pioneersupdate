
import Image from 'next/image';
import SearchModule from '@/components/SearchModule';
import FadeIn from '@/components/FadeIn';

import { HeroApiData } from '@/lib/api';

interface HeroProps {
    heroData?: HeroApiData;
}

export default function Hero({ heroData }: HeroProps) {
    const bgImage = heroData?.background_image || "/hero.png";
    const figureImage = heroData?.figure_image || "/fig.png";

    return (
        <section className="relative w-full md:bg-[#E8F3FC] pt-10 min-h-[500px] lg:min-h-[540px] flex items-end">
            {/* Background Container - Handles clipping for images/bg without clipping dropdowns */}
            <div className="absolute inset-0 w-full h-full overflow-hidden pointer-events-none z-0">
                <div className="absolute inset-0 hidden md:block opacity-35">
                    <Image
                        src={bgImage}
                        alt=""
                        fill
                        priority
                        sizes="100vw"
                        className="object-center"
                    />
                </div>
                {/* Mobile background */}
                <div className="md:hidden absolute inset-0 bg-white"></div>
            </div>

            <div className="relative z-20 px-4 md:px-10 xl:px-20 2xl:px-40 flex flex-col xl:flex-row items-center w-full pointer-events-none gap-10">
                {/* LEFT: Text + Search Card (Pointer events enabled for this child) */}
                <div className="w-full xl:w-3/5 py-10 xl:py-16 md:px-10 xl:px-0 pointer-events-auto z-30">
                    <FadeIn>
                        <SearchModule heroData={heroData} />
                    </FadeIn>
                </div>

                {/* RIGHT: Figure (bottom-aligned student) */}
                <div className="relative hidden w-full xl:flex xl:w-2/5 h-[540px] self-end items-end justify-center pointer-events-none">
                    <FadeIn delay={0.4} className="w-full h-full relative">
                        <Image
                            src={figureImage}
                            alt="Student giving thumbs up"
                            fill
                            sizes="(min-width: 1280px) 40vw, 60vw"
                            className="object-contain object-bottom origin-bottom scale-125"
                            priority
                        />
                    </FadeIn>
                </div>
            </div>
        </section>
    );
}
