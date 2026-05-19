export const metadata = {
    title: 'Terms of Service | Pioneers Admissions',
    description: 'Read our terms and conditions for using our services.',
};

export default function TermsPage() {
    return (
        <main className="bg-white min-h-screen pb-20 pt-32 px-4 md:px-10 xl:px-30 2xl:px-50">
            <h1 className="text-4xl md:text-5xl font-extrabold text-slate-900 mb-8">Terms of Service</h1>
            <p className="text-slate-500 mb-12">Last Updated: January 18, 2026</p>

            <div className="prose prose-lg prose-blue max-w-none text-slate-600">
                <p>
                    These terms and conditions outline the rules and regulations for the use of Pioneers Educational Admission Consultancy's Website.
                </p>

                <h3>1. Acceptance of Terms</h3>
                <p>
                    By accessing this website we assume you accept these terms and conditions. Do not continue to use Pioneers EDU if you do not agree to take all of the terms and conditions stated on this page.
                </p>

                <h3>2. License</h3>
                <p>
                    Unless otherwise stated, Pioneers EDU and/or its licensors own the intellectual property rights for all material on Pioneers EDU. All intellectual property rights are reserved. You may access this from Pioneers EDU for your own personal use subjected to restrictions set in these terms and conditions.
                </p>
                <p>You must not:</p>
                <ul>
                    <li>Republish material from Pioneers EDU</li>
                    <li>Sell, rent or sub-license material from Pioneers EDU</li>
                    <li>Reproduce, duplicate or copy material from Pioneers EDU</li>
                    <li>Redistribute content from Pioneers EDU</li>
                </ul>

                <h3>3. User Comments</h3>
                <p>
                    Parts of this website offer an opportunity for users to post and exchange opinions and information in certain areas of the website. Pioneers EDU does not filter, edit, publish or review Comments prior to their presence on the website. Comments do not reflect the views and opinions of Pioneers EDU, its agents and/or affiliates.
                </p>

                <h3>4. Hyperlinking to our Content</h3>
                <p>
                    The following organizations may link to our Website without prior written approval:
                </p>
                <ul>
                    <li>Government agencies;</li>
                    <li>Search engines;</li>
                    <li>News organizations;</li>
                </ul>

                <h3>5. Disclaimer</h3>
                <p>
                    To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website. Nothing in this disclaimer will:
                </p>
                <ul>
                    <li>limit or exclude our or your liability for death or personal injury;</li>
                    <li>limit or exclude our or your liability for fraud or fraudulent misrepresentation;</li>
                    <li>limit any of our or your liabilities in any way that is not permitted under applicable law; or</li>
                    <li>exclude any of our or your liabilities that may not be excluded under applicable law.</li>
                </ul>
            </div>
        </main>
    );
}
