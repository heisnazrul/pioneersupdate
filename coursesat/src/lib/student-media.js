import { getImageUrl } from "@/lib/api";

export function normalizeImageUrl(value) {
  if (!value || typeof value !== "string") return "";
  const raw = value.trim();
  if (!raw) return "";
  if (/\/storage\/[a-z]$/i.test(raw)) return "";
  if (raw.startsWith("http://") || raw.startsWith("https://")) return raw;
  if (raw.startsWith("data:")) return raw;
  if (raw.startsWith("/")) return getImageUrl(raw) || raw;
  if (raw.startsWith("storage/")) return getImageUrl(`/${raw}`) || "";
  if (raw.startsWith("assets/")) return `/${raw}`;
  return getImageUrl(`/storage/${raw}`) || "";
}

export function pickBookingImage(item) {
  const candidates = [
    item?.course_image,
    item?.school_image,
    item?.school_logo,
    item?.branch_image,
    item?.image,
    item?.thumbnail,
    item?.logo,
    item?.gallery_image,
    item?.gallery_url,
    item?.course?.image,
    item?.course?.thumbnail,
    item?.course?.logo,
    item?.institute_image,
    item?.institute_logo,
    item?.main_image,
    item?.featured_image,
    item?.banner,
    item?.cover,
    Array.isArray(item?.course?.gallery) ? item.course.gallery[0] : null,
    Array.isArray(item?.gallery) ? item.gallery[0] : null,
    item?.course?.gallery?.[0]?.url,
    item?.gallery?.[0]?.url,
  ];

  for (const src of candidates) {
    const url = normalizeImageUrl(src);
    if (url) return url;
  }

  return "/assets/hero.png";
}
