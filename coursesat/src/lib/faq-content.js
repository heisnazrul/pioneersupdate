export function normalizeFaqs(rawFaqs, isArabic) {
  const list = Array.isArray(rawFaqs) ? rawFaqs : [];

  return list.map((item, idx) => ({
    id: item.id ?? idx,
    cat: isArabic
      ? item.ar_category || item.cat_ar || item.category || item.cat || "General"
      : item.category || item.cat || item.ar_category || item.cat_ar || "General",
    q: isArabic
      ? item.ar_question || item.q_ar || item.question || item.q || ""
      : item.question || item.q || item.ar_question || item.q_ar || "",
    a: isArabic
      ? item.ar_answer || item.a_ar || item.answer || item.a || ""
      : item.answer || item.a || item.ar_answer || item.a_ar || "",
  }));
}

export function normalizeFaqCategories(rawCategories, faqs, isArabic) {
  if (Array.isArray(rawCategories) && rawCategories.length) {
    return rawCategories.map((item) =>
      isArabic
        ? item.ar_category || item.category || "General"
        : item.category || item.ar_category || "General",
    );
  }

  if (!faqs.length) return [];
  return Array.from(new Set(faqs.map((item) => item.cat)));
}

export function getDefaultFaqCategory(categories, isArabic) {
  if (!categories.length) return "";

  const preferred = isArabic ? "عام" : "General";
  const match = categories.find((cat) => String(cat).toLowerCase() === preferred.toLowerCase());
  return match || categories[0];
}

export function resolveFaqCategory(activeCat, categories, isArabic) {
  if (activeCat && categories.includes(activeCat)) return activeCat;
  return getDefaultFaqCategory(categories, isArabic);
}
