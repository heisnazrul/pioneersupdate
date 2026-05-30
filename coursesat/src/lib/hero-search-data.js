"use client";

import { useApi } from "@/lib/api";

export function mapCourseTypeOptions(types, language) {
  return (types ?? []).map((type) => ({
    label: language === "ar" ? type.ar_name || type.name : type.name || type.ar_name,
    value: type.slug || type.name,
  }));
}

export function useHeroSearchData() {
  const { data, loading, error } = useApi("/courseenglish/utilities");

  return {
    schools: data?.schools ?? [],
    countries: data?.countries ?? [],
    cities: data?.cities ?? [],
    courseTypes: data?.language_course_types ?? [],
    loading,
    error,
  };
}
