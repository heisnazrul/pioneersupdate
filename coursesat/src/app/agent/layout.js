import AuthGate from "@/components/shared/auth-gate";
import AgentShell from "@/components/agent/agent-shell";

export const metadata = {
  title: "Agent Dashboard | CourseSat",
};

export default function AgentLayout({ children }) {
  return (
    <AuthGate allowedRoles={["lg_agent"]}>
      <AgentShell>{children}</AgentShell>
    </AuthGate>
  );
}
