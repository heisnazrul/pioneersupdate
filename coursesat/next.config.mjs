/** @type {import('next').NextConfig} */
const backendOrigin =
  process.env.BACKEND_ORIGIN?.replace(/\/$/, "") ||
  (process.env.VERCEL === "1"
    ? "https://app.pioneersedu.com"
    : "http://127.0.0.1:8000");

const nextConfig = {
  images: {
    remotePatterns: [
      { protocol: "https", hostname: "images.pexels.com" },
      { protocol: "http", hostname: "127.0.0.1", port: "8000" },
      { protocol: "http", hostname: "localhost", port: "8000" },
      { protocol: "https", hostname: "app.pioneersedu.com" },
      { protocol: "https", hostname: "backend.pioneersedu.com" },
      { protocol: "https", hostname: "www.courseenglish.com" },
      { protocol: "https", hostname: "courseenglish.com" },
    ],
    dangerouslyAllowSVG: true,
    localPatterns: [{ pathname: "/assets/**" }],
  },
  async rewrites() {
    return [
      {
        source: "/api/:path*",
        destination: `${backendOrigin}/api/:path*`,
      },
      {
        source: "/storage/:path*",
        destination: `${backendOrigin}/storage/:path*`,
      },
    ];
  },
};

export default nextConfig;
