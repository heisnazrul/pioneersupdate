import AuthGate from "../components/AuthGate";
import AgentShell from "./layouts/AgentShell";

export const metadata = {
  title: "CourseEnglish Agent",
  description: "Agent workspace to support learners and manage leads.",
};

export default function AgentLayout({ children }) {
  return (
    <AuthGate allowedRoles={["lg_agent"]}>
      <AgentShell>{children}</AgentShell>
    </AuthGate>
  );
}
