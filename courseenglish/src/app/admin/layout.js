import AuthGate from "../components/AuthGate";
import Header from "./layouts/header";
import Sidebar from "./layouts/sidebar";
import Footer from "./layouts/footer";

export const metadata = {
  title: "CourseEnglish Admin",
  description: "Admin workspace for CourseEnglish",
};

export default function AdminLayout({ children }) {
  return (
    <AuthGate allowedRoles={["admin"]}>
      <div className="min-h-screen bg-slate-50 text-slate-900">
        <div className="flex min-h-screen">
          <Sidebar />
          <div className="flex min-h-screen flex-1 flex-col">
            <Header />
            <main className="flex-1 px-8 py-6">{children}</main>
            <Footer />
          </div>
        </div>
      </div>
    </AuthGate>
  );
}
