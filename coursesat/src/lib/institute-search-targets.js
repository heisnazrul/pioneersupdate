export function destinationKey(destination) {
  if (!destination?.type) return "";
  if (destination.type === "school_country") {
    return `school_country:${destination.school_slug}|${destination.country_slug}`;
  }
  return `${destination.type}:${destination.slug}`;
}

export function encodeSearchTargets(destinations = []) {
  return destinations
    .map((destination) => destinationKey(destination))
    .filter(Boolean)
    .join(",");
}

export function appendDestination(destinations, next) {
  const key = destinationKey(next);
  if (!key) return destinations;
  if (destinations.some((item) => destinationKey(item) === key)) {
    return destinations;
  }
  return [...destinations, next];
}

export function removeDestination(destinations, key) {
  return destinations.filter((item) => destinationKey(item) !== key);
}

export function buildDestinationLabel(destination, isArabic) {
  if (!destination) return "";
  if (destination.name) return destination.name;
  if (destination.type === "school_country") {
    const school = isArabic
      ? destination.school_ar_name || destination.school_name
      : destination.school_name || destination.school_ar_name;
    const country = isArabic
      ? destination.country_ar_name || destination.country_name
      : destination.country_name || destination.country_ar_name;
    return [school, country].filter(Boolean).join(" - ");
  }
  return destination.slug || "";
}

export function parseLegacyDestinationParams(searchParams, searchData, isArabic) {
  const encoded = searchParams.get("search_targets");
  if (encoded) {
    return encoded.split(",").map((segment) => {
      const trimmed = segment.trim();
      if (trimmed.startsWith("school_country:")) {
        const payload = trimmed.replace("school_country:", "");
        const [school_slug, country_slug] = payload.split("|");
        const match = searchData?.branches?.find(
          (item) => item.school_slug === school_slug && item.country_slug === country_slug
        );
        return {
          type: "school_country",
          school_slug,
          country_slug,
          name: match
            ? (isArabic ? match.ar_name || match.name : match.name)
            : `${school_slug} - ${country_slug}`,
          school_name: match?.school_name,
          school_ar_name: match?.school_ar_name,
          country_name: match?.country_name,
          country_ar_name: match?.country_ar_name,
        };
      }

      const [type, slug] = trimmed.split(":");
      if (type === "branch") {
        const match = searchData?.branches?.find((item) => item.slug === slug);
        return {
          type,
          slug,
          name: match ? (isArabic ? match.ar_name || match.name : match.name) : slug,
          ...match,
        };
      }
      if (type === "school") {
        const match = searchData?.schools?.find((item) => item.slug === slug);
        return {
          type,
          slug,
          name: match ? (isArabic ? match.ar_name || match.name : match.name) : slug,
          ...match,
        };
      }
      if (type === "city") {
        const match = searchData?.cities?.find((item) => item.slug === slug);
        return {
          type,
          slug,
          name: match ? (isArabic ? match.ar_name || match.name : match.name) : slug,
          ...match,
        };
      }
      if (type === "country") {
        const match = searchData?.countries?.find((item) => item.slug === slug);
        return {
          type,
          slug,
          name: match ? (isArabic ? match.ar_name || match.name : match.name) : slug,
          ...match,
        };
      }
      return null;
    }).filter(Boolean);
  }

  const destinations = [];
  if (!searchData) return destinations;

  const branchSlug = searchParams.get("branch_slug");
  if (branchSlug) {
    const match = searchData.branches?.find((item) => item.slug === branchSlug);
    if (match) {
      destinations.push({
        type: "branch",
        slug: match.slug,
        name: isArabic ? match.ar_name || match.name : match.name,
        ...match,
      });
      return destinations;
    }
  }

  const schoolSlug = searchParams.get("school_slug");
  const citySlug = searchParams.get("city_slug");
  const countrySlug = searchParams.get("country_slug");

  if (schoolSlug && citySlug) {
    const match = searchData.branches?.find(
      (item) => item.school_slug === schoolSlug && item.city_slug === citySlug
    );
    if (match) {
      destinations.push({
        type: "branch",
        slug: match.slug,
        name: isArabic ? match.ar_name || match.name : match.name,
        ...match,
      });
      return destinations;
    }
  }

  if (schoolSlug && countrySlug) {
    destinations.push({
      type: "school_country",
      school_slug: schoolSlug,
      country_slug: countrySlug,
      name: schoolSlug,
    });
    return destinations;
  }

  if (schoolSlug) {
    const match = searchData.schools?.find((item) => item.slug === schoolSlug);
    if (match) {
      destinations.push({
        type: "school",
        slug: schoolSlug,
        name: isArabic ? match.ar_name || match.name : match.name,
        ...match,
      });
    }
  } else if (citySlug) {
    const match = searchData.cities?.find((item) => item.slug === citySlug);
    if (match) {
      destinations.push({
        type: "city",
        slug: citySlug,
        name: isArabic ? match.ar_name || match.name : match.name,
        ...match,
      });
    }
  } else if (countrySlug) {
    const match = searchData.countries?.find((item) => item.slug === countrySlug);
    if (match) {
      destinations.push({
        type: "country",
        slug: countrySlug,
        name: isArabic ? match.ar_name || match.name : match.name,
        ...match,
      });
    }
  }

  return destinations;
}

export function applyDestinationsToSearchParams(params, destinations = []) {
  params.delete("school_slug");
  params.delete("city_slug");
  params.delete("country_slug");
  params.delete("branch_slug");
  params.delete("search_targets");

  if (destinations.length === 0) return params;

  if (destinations.length === 1) {
    const only = destinations[0];
    if (only.type === "branch") {
      params.set("branch_slug", only.slug);
      return params;
    }
    if (only.type === "school") {
      params.set("school_slug", only.slug);
      return params;
    }
    if (only.type === "city") {
      params.set("city_slug", only.slug);
      return params;
    }
    if (only.type === "country") {
      params.set("country_slug", only.slug);
      return params;
    }
    if (only.type === "school_country") {
      params.set("school_slug", only.school_slug);
      params.set("country_slug", only.country_slug);
      return params;
    }
  }

  params.set("search_targets", encodeSearchTargets(destinations));
  return params;
}
