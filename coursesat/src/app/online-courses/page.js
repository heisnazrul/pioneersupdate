import { headers } from "next/headers";
import { isMobileRequest } from "@/lib/device";

export const metadata = {
  title: "Online Courses | Coursesat",
  description: "Browse and book online language courses.",
};

export default async function OnlineCoursesPage() {
  const headerStore = await headers();
  const userAgent = headerStore.get("user-agent") ?? "";

  const View = isMobileRequest(userAgent)
    ? (await import("@/components/desktop/desktop-online-courses")).default
    : (await import("@/components/desktop/desktop-online-courses")).default;

  return <View />;
}
