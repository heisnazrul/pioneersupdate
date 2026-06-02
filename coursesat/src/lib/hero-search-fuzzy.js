function normalizeSearchText(value) {
  return String(value ?? "")
    .toLowerCase()
    .replace(/[^\w\s\u0600-\u06FF]/g, " ")
    .replace(/\s+/g, " ")
    .trim();
}

function levenshtein(a, b) {
  if (a === b) return 0;
  if (!a.length) return b.length;
  if (!b.length) return a.length;

  const matrix = Array.from({ length: a.length + 1 }, () => new Array(b.length + 1).fill(0));

  for (let i = 0; i <= a.length; i += 1) matrix[i][0] = i;
  for (let j = 0; j <= b.length; j += 1) matrix[0][j] = j;

  for (let i = 1; i <= a.length; i += 1) {
    for (let j = 1; j <= b.length; j += 1) {
      const cost = a[i - 1] === b[j - 1] ? 0 : 1;
      matrix[i][j] = Math.min(
        matrix[i - 1][j] + 1,
        matrix[i][j - 1] + 1,
        matrix[i - 1][j - 1] + cost
      );
    }
  }

  return matrix[a.length][b.length];
}

function tokenFuzzyMatch(queryToken, targetToken) {
  if (!queryToken) return true;
  if (!targetToken) return false;
  if (targetToken.includes(queryToken) || queryToken.includes(targetToken)) return true;

  const maxDistance = queryToken.length <= 4 ? 1 : 2;
  return levenshtein(queryToken, targetToken) <= maxDistance;
}

export function buildSearchHaystack(item, extraFields = []) {
  const parts = [
    item.name,
    item.ar_name,
    item.slug,
    item.school_name,
    item.school_ar_name,
    item.city_name,
    item.city_ar_name,
    item.country_name,
    item.country_ar_name,
    item.country_code,
    item.search_text,
    ...extraFields,
  ];

  return normalizeSearchText(parts.filter(Boolean).join(" "));
}

export function fuzzyMatchSearch(query, haystack) {
  const normalizedQuery = normalizeSearchText(query);
  const normalizedHaystack = normalizeSearchText(haystack);

  if (!normalizedQuery) return true;
  if (!normalizedHaystack) return false;
  if (normalizedHaystack.includes(normalizedQuery)) return true;

  const queryTokens = normalizedQuery.split(" ").filter(Boolean);
  const targetTokens = normalizedHaystack.split(" ").filter(Boolean);

  if (queryTokens.length === 0) return true;

  return queryTokens.every((queryToken) =>
    targetTokens.some((targetToken) => tokenFuzzyMatch(queryToken, targetToken))
  );
}

export function rankSearchMatch(query, haystack) {
  const normalizedQuery = normalizeSearchText(query);
  const normalizedHaystack = normalizeSearchText(haystack);

  if (!normalizedQuery) return 0;
  if (!normalizedHaystack) return -1;
  if (normalizedHaystack === normalizedQuery) return 1000;
  if (normalizedHaystack.startsWith(normalizedQuery)) return 900;
  if (normalizedHaystack.includes(normalizedQuery)) return 800;

  const queryTokens = normalizedQuery.split(" ").filter(Boolean);
  const matchedTokens = queryTokens.filter((token) =>
    normalizedHaystack.split(" ").some((target) => tokenFuzzyMatch(token, target))
  );

  if (matchedTokens.length === 0) return -1;
  return 500 + matchedTokens.length * 50 - levenshtein(normalizedQuery, normalizedHaystack);
}

export function filterAndRankSearchItems(items, query, extraFieldsBuilder = null) {
  if (!query?.trim()) return items;

  return items
    .map((item) => {
      const extra = extraFieldsBuilder ? extraFieldsBuilder(item) : [];
      const haystack = buildSearchHaystack(item, extra);
      const score = rankSearchMatch(query, haystack);
      return { item, score };
    })
    .filter(({ score }) => score >= 0)
    .sort((a, b) => b.score - a.score)
    .map(({ item }) => item);
}
