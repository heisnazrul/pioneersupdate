export const metadata = {
    title: 'Privacy Policy | Pioneers Admissions',
    description: 'Our commitment to protecting your privacy and personal data.',
};

export default function PrivacyPolicyPage() {
    return (
        <main className="bg-white min-h-screen pb-20 pt-32 px-4 md:px-10 xl:px-30 2xl:px-50">
            <h1 className="text-4xl md:text-5xl font-extrabold text-slate-900 mb-8">Privacy Policy</h1>
            <p className="text-slate-500 mb-12">Last Updated: January 18, 2026</p>

            <div className="prose prose-lg prose-blue max-w-none text-slate-600">
                <p>
                    Welcome to Pioneers Educational Admission Consultancy (Pioneers EDU). We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you as to how we look after your personal data when you visit our website (regardless of where you visit it from) and tell you about your privacy rights and how the law protects you.
                </p>

                <h3>1. Important Information and Who We Are</h3>
                <p>
                    Pioneers EDU is the controller and responsible for your personal data. We have appointed a data privacy manager who is responsible for overseeing questions in relation to this privacy policy. If you have any questions about this privacy policy, please contact them using the details set out below.
                </p>

                <h3>2. The Data We Collect About You</h3>
                <p>
                    We may collect, use, store and transfer different kinds of personal data about you which we have grouped together follows:
                </p>
                <ul>
                    <li><strong>Identity Data</strong> includes first name, maiden name, last name, username or similar identifier, marital status, title, date of birth and gender.</li>
                    <li><strong>Contact Data</strong> includes billing address, delivery address, email address and telephone numbers.</li>
                    <li><strong>Technical Data</strong> includes internet protocol (IP) address, your login data, browser type and version, time zone setting and location, browser plug-in types and versions, operating system and platform and other technology on the devices you use to access this website.</li>
                </ul>

                <h3>3. How We Use Your Personal Data</h3>
                <p>
                    We will only use your personal data when the law allows us to. Most commonly, we will use your personal data in the following circumstances:
                </p>
                <ul>
                    <li>Where we need to perform the contract we are about to enter into or have entered into with you.</li>
                    <li>Where it is necessary for our legitimate interests (or those of a third party) and your interests and fundamental rights do not override those interests.</li>
                    <li>Where we need to comply with a legal or regulatory obligation.</li>
                </ul>

                <h3>4. Data Security</h3>
                <p>
                    We have put in place appropriate security measures to prevent your personal data from being accidentally lost, used or accessed in an unauthorized way, altered or disclosed. In addition, we limit access to your personal data to those employees, agents, contractors and other third parties who have a business need to know.
                </p>

                <h3>5. Contact Us</h3>
                <p>
                    If you have any questions about this privacy policy or our privacy practices, please contact us at: <a href="mailto:privacy@pioneers.edu">privacy@pioneers.edu</a>.
                </p>
            </div>
        </main>
    );
}
