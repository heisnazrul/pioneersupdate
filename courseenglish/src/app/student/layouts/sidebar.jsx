import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faTimes } from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/courseenglishApi";

const menu = [
  { title: "Dashboard", href: "/student/dashboard" },
  { title: "My Bookings", href: "/student/bookings" },
  { title: "My Wishlist", href: "/student/wishlist" },
  { title: "Compares", href: "/student/compare" },
];

export default function Sidebar({ isOpen, onClose }) {
  // Fetch branding for logo
  const { data: brandingData } = useApi("/courseenglish/home/branding");
  const logoSrc = brandingData?.branding?.header?.logo?.main || "/logo.png";

  return (
    <>
      {/* Mobile Backdrop */}
      {isOpen && (
        <div
          className="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"
          onClick={onClose}
        />
      )}

      {/* Sidebar */}
      <aside
        className={`fixed inset-y-0 left-0 z-50 w-[270px] border-r border-slate-200 bg-white px-6 py-6 transition-transform duration-300 lg:static lg:translate-x-0 ${isOpen ? "translate-x-0 shadow-2xl" : "-translate-x-full"
          }`}
      >
        <div className="flex items-center justify-between gap-3 pb-8">
          <Link href="/" className="flex items-center gap-3">
            <img
              src={logoSrc}
              alt="CourseEnglish"
              className="h-10 w-auto"
              loading="lazy"
            />
          </Link>

          {/* Close Button (Mobile Only) */}
          <button
            type="button"
            onClick={onClose}
            className="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 lg:hidden"
          >
            <FontAwesomeIcon icon={faTimes} />
          </button>
        </div>

        <nav className="space-y-1">
          {menu.map((item) => (
            <Link
              key={item.title}
              href={item.href}
              onClick={() => onClose?.()} // Close on navigation (mobile)
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
    </>
  );
}
