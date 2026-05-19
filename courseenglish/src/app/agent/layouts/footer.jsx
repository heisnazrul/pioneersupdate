export default function Footer() {
  return (
    <footer className="border-t border-slate-200 bg-white px-8 py-4 text-sm text-slate-500">
      <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <p className="font-normal text-slate-700">CourseEnglish Agent</p>
        <div className="flex flex-wrap items-center gap-4">
          <span>Support SLAs</span>
          <span>Escalations</span>
          <span>Status</span>
          <span className="text-slate-400">Updated: a moment ago</span>
        </div>
      </div>
    </footer>
  );
}
