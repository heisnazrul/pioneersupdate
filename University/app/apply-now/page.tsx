import MultiStepApplicationForm from '@/components/MultiStepApplicationForm';

export const metadata = {
    title: 'Apply Now | Pioneers Admissions',
    description: 'Start your study abroad application.',
};

export default function ApplyPage() {
    return (
        <div className="bg-slate-50 min-h-screen py-16 pb-24">
            <div className="container max-w-6xl mx-auto px-4">
                <header className="text-center mb-12 max-w-2xl mx-auto">
                    <h1 className="text-4xl font-extrabold text-primary mb-4 tracking-tight">Start Your Application</h1>
                    <p className="text-xl text-muted">Complete your profile in 3 simple steps. Your progress is saved automatically.</p>
                </header>

                <MultiStepApplicationForm />
            </div>
        </div>
    );
}
