import Link from "next/link";

const LINKS = [
  { href: "/admin/api-cache/home", label: "Home" },
  { href: "/admin/api-cache/articles", label: "Articles" },
];

export default function ApiCacheLayout({ children }) {
  return (
    <div className="space-y-6">
      <div className="flex flex-wrap gap-2">
        {LINKS.map((link) => (
          <Link
            key={link.href}
            href={link.href}
            className="rounded-full bg-slate-100 px-4 py-2 text-sm font-normal text-slate-700 hover:bg-slate-200"
          >
            {link.label}
          </Link>
        ))}
      </div>
      {children}
    </div>
  );
}
