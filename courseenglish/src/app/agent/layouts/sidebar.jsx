import Link from "next/link";

const menu = [
  { title: "Dashboard", href: "/agent/dashboard" },
  { title: "Students", href: "/agent/students" },
  { title: "Referrals", href: "/agent/referrals" },
  { title: "Conversations", href: "#" },
  { title: "Knowledge base", href: "#" },
];

export default function Sidebar() {
  return (
    <aside className="hidden w-[270px] border-r border-slate-200 bg-white px-6 py-8 lg:block">
      <div className="flex items-center gap-3 pb-8">
        <div>
          <p className="text-sm font-normal text-slate-900">CourseEnglish</p>
          <p className="text-xs text-slate-500">Agent workspace</p>
        </div>
      </div>

      <nav className="space-y-1">
        {menu.map((item) => (
          <Link
            key={item.title}
            href={item.href}
            className="flex items-center justify-between rounded-xl px-3 py-3 text-sm font-normal text-slate-700 transition hover:bg-slate-50 hover:text-slate-900"
          >
            <span>{item.title}</span>
            <span aria-hidden className="text-slate-300">
              →
            </span>
          </Link>
        ))}
      </nav>
    </aside>
  );
}
