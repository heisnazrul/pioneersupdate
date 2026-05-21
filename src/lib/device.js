const MOBILE_USER_AGENT_PATTERN =
  /iphone|ipod|android.+mobile|windows phone|blackberry|opera mini|mobile\b/i;

export function isMobileRequest(userAgent = "") {
  return MOBILE_USER_AGENT_PATTERN.test(userAgent);
}
