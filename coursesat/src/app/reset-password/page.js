"use client";

import { Suspense } from "react";

import AuthLayout from "@/components/shared/auth-layout";
import ResetPasswordForm from "@/components/shared/reset-password-form";
import { useLocale } from "@/components/providers/locale-provider";

function ResetPasswordContent() {
  const { t } = useLocale();

  return (
    <AuthLayout
      title={t("pages.reset_password.title", "Set a New Password")}
      subtitle={t(
        "pages.reset_password.subtitle",
        "Choose a strong password for your account."
      )}
    >
      <ResetPasswordForm />
    </AuthLayout>
  );
}

export default function ResetPasswordPage() {
  return (
    <Suspense fallback={null}>
      <ResetPasswordContent />
    </Suspense>
  );
}
