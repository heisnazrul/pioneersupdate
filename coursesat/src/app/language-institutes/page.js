import { headers } from "next/headers";
import { isMobileRequest } from "@/lib/device";

export const metadata = {
  title: "Language Institutes | Coursesat",
  description: "Browse language institutes and courses.",
};

export default async function LanguageInstitutesPage() {
  const headerStore = await headers();
  const userAgent = headerStore.get("user-agent") ?? "";

  const View = isMobileRequest(userAgent)
    ? (await import("@/components/mobile/mobile-language-institutes")).default
    : (await import("@/components/desktop/desktop-language-institutes")).default;

  return <View />;
}
