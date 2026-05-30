import AuthGate from "@/components/shared/auth-gate";
import StudentShell from "@/components/student/student-shell";

export const metadata = {
  title: "Student Dashboard | CourseSat",
};

export default function StudentLayout({ children }) {
  return (
    <AuthGate allowedRoles={["lg_student"]}>
      <StudentShell>{children}</StudentShell>
    </AuthGate>
  );
}
