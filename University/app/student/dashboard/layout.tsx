import DashboardLayoutClient from "@/components/dashboard/DashboardLayoutClient";

export default function StudentDashboardLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    return (
        <DashboardLayoutClient>
            {children}
        </DashboardLayoutClient>
    );
}
