"use client";

import AuthLayout from "@/components/shared/auth-layout";
import SetPasswordForm from "@/components/shared/set-password-form";
import { useLocale } from "@/components/providers/locale-provider";

export default function SetPasswordPage() {
  const { t } = useLocale();

  return (
    <AuthLayout
      title={t("pages.set_password.title", "Set Your Password")}
      subtitle={t(
        "pages.set_password.subtitle",
        "To complete your account setup, please set a new password."
      )}
    >
      <SetPasswordForm />
    </AuthLayout>
  );
}
