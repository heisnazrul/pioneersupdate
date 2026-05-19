const stats = [
  { label: "Active learners", value: "1,240", change: "+6.4%" },
  { label: "Completion rate", value: "87%", change: "+3.2%" },
  { label: "Live sessions this week", value: "128", change: "8 booked" },
  { label: "CSAT", value: "4.8/5", change: "stable" },
];

const sessions = [
  {
    title: "Speaking Lab · Intermediate",
    coach: "Jessica Lee",
    time: "Today, 3:00 PM",
  },
  {
    title: "Business Writing · Cohort 12",
    coach: "Priya Raman",
    time: "Today, 5:30 PM",
  },
  {
    title: "Interview Prep · 1:1",
    coach: "Marcus Reid",
    time: "Tomorrow, 9:00 AM",
  },
];

const updates = [
  {
    heading: "Curriculum refresh",
    detail: "New B2 vocabulary drills and pronunciation clips are live.",
  },
  {
    heading: "Cohort health",
    detail: "3 cohorts flagged for low attendance. Sending reminders tonight.",
  },
  {
    heading: "Support",
    detail: "Average reply time improved to 8 minutes this week.",
  },
];

export default function AdminDashboardPage() {
  return (
    <div className="space-y-8">
      <div className="flex flex-col gap-3">
        <p className="text-xs font-normal uppercase tracking-[0.2em] text-slate-600">
          Admin dashboard
        </p>
        <h1 className="text-2xl font-normal text-slate-900">
          Welcome back. Here&apos;s what&apos;s happening today.
        </h1>
        <p className="max-w-3xl text-slate-600">
          Track learner momentum, keep sessions healthy, and nudge the right
          cohorts—all from a clean, focused workspace.
        </p>
      </div>

      <section className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {stats.map((stat) => (
          <div
            key={stat.label}
            className="rounded-2xl border border-slate-200 bg-white px-4 py-5 shadow-[0_14px_48px_-32px_rgba(15,23,42,0.35)]"
          >
            <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-500">
              {stat.label}
            </p>
            <p className="pt-3 text-3xl font-normal text-slate-900">
              {stat.value}
            </p>
            <p className="text-sm font-normal text-emerald-600">
              {stat.change}
            </p>
          </div>
        ))}
      </section>

      <section className="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
        <div className="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_22px_60px_-36px_rgba(15,23,42,0.35)]">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-600">
                Sessions
              </p>
              <h2 className="text-lg font-normal text-slate-900">
                Live today
              </h2>
            </div>
            <button className="rounded-full border border-slate-200 px-3 py-2 text-xs font-normal text-slate-700 transition hover:border-slate-300">
              Export
            </button>
          </div>
          <div className="space-y-3">
            {sessions.map((session) => (
              <div
                key={session.title}
                className="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4"
              >
                <div>
                  <p className="text-sm font-normal text-slate-900">
                    {session.title}
                  </p>
                  <p className="text-xs text-slate-500">
                    Coach {session.coach}
                  </p>
                </div>
                <span className="rounded-full bg-slate-900 px-3 py-2 text-xs font-normal text-white">
                  {session.time}
                </span>
              </div>
            ))}
          </div>
        </div>

        <div className="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_22px_60px_-36px_rgba(15,23,42,0.35)]">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-600">
                Signals
              </p>
              <h2 className="text-lg font-normal text-slate-900">
                What needs attention
              </h2>
            </div>
            <span className="rounded-full bg-amber-100 px-3 py-2 text-xs font-normal text-amber-700">
              3 alerts
            </span>
          </div>
          <div className="space-y-3">
            {updates.map((item) => (
              <div
                key={item.heading}
                className="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4"
              >
                <p className="text-sm font-normal text-slate-900">
                  {item.heading}
                </p>
                <p className="text-xs text-slate-600">{item.detail}</p>
              </div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}
