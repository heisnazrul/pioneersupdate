"use client";

import AuthLayout from "@/components/shared/auth-layout";
import VerifyForm from "@/components/shared/verify-form";
import { useLocale } from "@/components/providers/locale-provider";

export default function VerifyPage() {
  const { t } = useLocale();

  return (
    <AuthLayout
      title={t("pages.verify.title", "Verify your account")}
      subtitle={t("pages.verify.subtitle", "Enter the 4-digit code sent to your email.")}
    >
      <VerifyForm />
    </AuthLayout>
  );
}
