"use client";

import { useApi } from "@/lib/api";

export function mapCourseTypeOptions(types, language) {
  return (types ?? []).map((type) => ({
    label: language === "ar" ? type.ar_name || type.name : type.name || type.ar_name,
    value: type.slug || type.name,
  }));
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
