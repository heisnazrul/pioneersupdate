import AuthGate from "../components/AuthGate";
import StudentShell from "./layouts/StudentShell";

export const metadata = {
  title: "CourseEnglish Student",
  description: "Student learning hub with lessons, progress, and live sessions.",
};

export default function StudentLayout({ children }) {
  return (
    <AuthGate allowedRoles={["lg_student"]}>
      <StudentShell>{children}</StudentShell>
    </AuthGate>
  );
}
