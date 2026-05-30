"use client";

import { Suspense } from "react";
import AuthLayout from "@/components/shared/auth-layout";
import LoginForm from "@/components/shared/login-form";
import { useLocale } from "@/components/providers/locale-provider";

function LoginPageContent() {
  const { t } = useLocale();

  return (
    <AuthLayout
      title={t("pages.login.title", "Welcome Back")}
      subtitle={t(
        "pages.login.subtitle",
        "Log in to track your applications and view your booking details."
      )}
    >
      <LoginForm />
    </AuthLayout>
  );
}

export default function LoginPage() {
  return (
    <Suspense fallback={null}>
      <LoginPageContent />
    </Suspense>
  );
}
