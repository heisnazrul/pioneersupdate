"use client";

import { useApi } from "@/lib/api";

export function mapCourseTypeOptions(types, language) {
  return (types ?? []).map((type) => ({
    label: language === "ar" ? type.ar_name || type.name : type.name || type.ar_name,
    value: type.slug || type.name,
  }));
}

/** Countries with the most courses/branches appear first (matches API sort). */
export function sortSearchCountries(countries = []) {
  return [...countries].sort((a, b) => {
    const courseDiff = (b.course_count ?? 0) - (a.course_count ?? 0);
    if (courseDiff !== 0) return courseDiff;

    const branchDiff = (b.branch_count ?? 0) - (a.branch_count ?? 0);
    if (branchDiff !== 0) return branchDiff;

    const nameA = a.name || a.ar_name || "";
    const nameB = b.name || b.ar_name || "";
    return nameA.localeCompare(nameB, undefined, { sensitivity: "base" });
  });
}

export function useHeroSearchData() {
  const { data, loading, error } = useApi("/coursesat/home");
  const searchData = data?.hero?.search_data;

  return {
    schools: searchData?.schools ?? [],
    countries: searchData?.countries ?? [],
    cities: searchData?.cities ?? [],
    courseTypes: searchData?.course_types ?? [],
    loading,
    error,
  };
}
