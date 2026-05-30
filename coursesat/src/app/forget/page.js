"use client";

import AuthLayout from "@/components/shared/auth-layout";
import ForgetForm from "@/components/shared/forget-form";
import { useLocale } from "@/components/providers/locale-provider";

export default function ForgetPage() {
  const { t } = useLocale();

  return (
    <AuthLayout
      title={t("pages.forget.title", "Reset Password")}
      subtitle={t(
        "pages.forget.subtitle",
        "Enter your registered email address to receive a password reset link."
      )}
    >
      <ForgetForm />
    </AuthLayout>
  );
}
