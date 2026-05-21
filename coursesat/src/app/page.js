import { headers } from "next/headers";

import { isMobileRequest } from "@/lib/device";

export default async function HomePage() {
  const headerStore = await headers();
  const userAgent = headerStore.get("user-agent") ?? "";

  const View = isMobileRequest(userAgent)
    ? (await import("@/components/mobile/mobile-home")).default
    : (await import("@/components/desktop/desktop-home")).default;

  return <View />;
}
