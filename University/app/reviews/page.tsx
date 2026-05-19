import Image from 'next/image';
import Link from 'next/link';
import { faStar, faPlay, faQuoteLeft } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import SectionHeading from '@/components/ui/SectionHeading';
import Button from '@/components/ui/Button';

// Reuse mock data (ideally should be in a shared data file)
const VIDEO_REVIEWS = [
    { id: 1, name: "Sarah Jenkins", university: "University of Oxford", course: "MSc Computer Science", country: "UK", thumbnail: "/assets/destinations/uk.png", duration: "1:45", quote: "The application process was so smooth!" },
    { id: 2, name: "Michael Chen", university: "Stanford University", course: "MBA", country: "USA", thumbnail: "/assets/destinations/usa.png", duration: "2:10", quote: "I got a full scholarship thanks to the team." },
    { id: 3, name: "Amara Patel", university: "University of Toronto", course: "BSc Psychology", country: "Canada", thumbnail: "/assets/destinations/canada.png", duration: "1:30", quote: "They helped me with my visa in record time." },
    { id: 4, name: "David Kim", university: "University of Melbourne", course: "BEng Civil", country: "Australia", thumbnail: "/assets/destinations/australia.png", duration: "1:55", quote: "Best decision I ever made!" },
    { id: 5, name: "Elena Rodriguez", university: "TU Munich", course: "MSc Engineering", country: "Germany", thumbnail: "/assets/destinations/germany.png", duration: "2:05", quote: "Highly recommended for engineering students." },
    { id: 6, name: "Omar Farooq", university: "University of Auckland", course: "BCom", country: "New Zealand", thumbnail: "/assets/destinations/newzealand.png", duration: "1:20", quote: "The counselors are incredibly supportive." },
    { id: 7, name: "Jessica Lee", university: "Imperial College London", course: "MSc Biotechnology", country: "UK", thumbnail: "/assets/destinations/uk.png", duration: "2:15", quote: "Found my dream course thanks to them!" },
    { id: 8, name: "Daniel Silva", university: "University of British Columbia", course: "BA Economics", country: "Canada", thumbnail: "/assets/destinations/canada.png", duration: "1:40", quote: "Seamless transition to student life in Canada." }
];

const TEXT_REVIEWS = [
    { id: 1, name: "Maria Garcia", role: "Engineering Student", title: "Life-changing Support", text: "Pioneers Admissions made my dream of studying Engineering in Germany a reality. The visa guidance was spot on!", rating: 5 },
    { id: 2, name: "John Smith", role: "MBA Candidate", title: "Smooth Process", text: "The team helped me select the perfect MBA program in the UK. I couldn't have done it without their expert counseling.", rating: 5 },
    { id: 3, name: "Li Wei", role: "Computer Science", title: "Top Notch Service", text: "Excellent support for my destination Canada application. They handled all the documentation perfectly.", rating: 5 },
    { id: 4, name: "Priya Sharma", role: "Data Science", title: "Highly Recommended", text: "I was confused about university choices, but their analysis helped me find the best fit in Australia.", rating: 4 },
    { id: 5, name: "Ahmed Khan", role: "Medical Student", title: "Great Experience", text: "Studying medicine abroad seemed impossible until I met the Pioneers team. They guided me every step of the way.", rating: 5 },
    { id: 6, name: "Sarah Jenkins", role: "Art History", title: "Personalized Care", text: "They really care about your goals. I felt supported throughout my application to Italy.", rating: 5 },
    { id: 7, name: "Emily Clark", role: "Law Student", title: "Fantastic Guidance", text: "From application to acceptance, they were there. Their interview prep was invaluable.", rating: 5 },
    { id: 8, name: "Rajesh Kumar", role: "Business Analytics", title: "Trustworthy Team", text: "Transparent and honest advice. No hidden costs or false promises. Highly recommend!", rating: 5 }
];

export default function ReviewsPage() {
    return (
        <main className="pb-20">
            {/* Hero Section */}
            <section className="relative bg-[#003B5C] text-white pt-32 pb-20 overflow-hidden">
                <div className="absolute inset-0 opacity-10">
                    <Image src="/hero.png" alt="Background" fill className="object-cover" />
                </div>
                <div className="px-4 md:px-10 xl:px-30 2xl:px-50 relative z-10 text-center">
                    <h1 className="text-4xl md:text-6xl font-extrabold mb-6">Success Stories</h1>
                    <p className="text-xl text-blue-100 max-w-2xl mx-auto">
                        Join thousands of students who have realized their study abroad dreams with us.
                    </p>
                </div>
            </section>

            {/* Video Reviews Grid */}
            <section className="py-20 bg-white">
                <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                    <SectionHeading
                        subtitle="Video Testimonials"
                        title="Watch their journey"
                        description="See what our students have to say about their experience studying abroad."
                    />

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        {VIDEO_REVIEWS.map((video) => (
                            <div key={video.id} className="group cursor-pointer">
                                <div className="relative aspect-[3/4] rounded-3xl overflow-hidden bg-gray-900 shadow-md group-hover:shadow-2xl transition-all duration-300 transform group-hover:-translate-y-2">
                                    <Image src={video.thumbnail} alt={video.name} fill className="object-cover opacity-90 group-hover:opacity-100 transition-opacity" />
                                    <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent" />

                                    <div className="absolute inset-0 flex items-center justify-center opacity-80 group-hover:opacity-100 transition-opacity">
                                        <div className="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border border-white/40">
                                            <div className="w-12 h-12 bg-white rounded-full flex items-center justify-center text-primary shadow-lg pl-1">
                                                <FontAwesomeIcon icon={faPlay} className="w-4 h-4" />
                                            </div>
                                        </div>
                                    </div>

                                    <div className="absolute bottom-0 left-0 right-0 p-6 text-white">
                                        <div className="flex items-center gap-2 mb-2">
                                            <span className="bg-white/20 backdrop-blur px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider">{video.country}</span>
                                        </div>
                                        <h3 className="text-xl font-bold leading-tight mb-1">{video.name}</h3>
                                        <p className="text-sm text-gray-300">{video.course} at {video.university}</p>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Text Reviews Grid */}
            <section className="py-20 bg-gray-50">
                <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                    <SectionHeading
                        subtitle="Written Reviews"
                        title="More success stories"
                    />

                    <div className="columns-1 md:columns-2 lg:columns-4 gap-8 space-y-8">
                        {TEXT_REVIEWS.map((review) => (
                            <div key={review.id} className="break-inside-avoid bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                <div className="flex gap-1 text-yellow-400 mb-4 text-xs">
                                    {[...Array(5)].map((_, i) => (
                                        <FontAwesomeIcon key={i} icon={faStar} className={i < review.rating ? "" : "text-gray-200"} />
                                    ))}
                                </div>
                                <h3 className="font-bold text-lg mb-2">{review.title}</h3>
                                <p className="text-gray-600 text-sm leading-relaxed mb-6">"{review.text}"</p>
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-primary font-bold">
                                        {review.name.charAt(0)}
                                    </div>
                                    <div>
                                        <div className="font-bold text-sm">{review.name}</div>
                                        <div className="text-xs text-gray-400">{review.role}</div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>

                    <div className="text-center mt-12">
                        <Button className="rounded-full px-10 py-4 shadow-xl shadow-primary/20">Apply Now & Write Your Own Story</Button>
                    </div>
                </div>
            </section>
        </main>
    );
}
