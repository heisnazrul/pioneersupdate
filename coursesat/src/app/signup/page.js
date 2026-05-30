"use client";

import AuthLayout from "@/components/shared/auth-layout";
import SignupForm from "@/components/shared/signup-form";
import { useLocale } from "@/components/providers/locale-provider";

export default function SignupPage() {
  const { t } = useLocale();

  return (
    <AuthLayout
      title={t("pages.signup.title", "Create Your Account")}
      subtitle={t(
        "pages.signup.subtitle",
        "Create your account to send applications and track your bookings easily."
      )}
    >
      <SignupForm />
    </AuthLayout>
  );
}
