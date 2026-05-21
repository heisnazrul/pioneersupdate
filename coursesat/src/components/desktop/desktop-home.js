import DesktopFooter from "./desktop-footer";
import DesktopHeader from "./desktop-header";

export default function DesktopHome() {
  return (
    <div className="flex min-h-screen flex-col">
      <DesktopHeader />
      <main className="mx-auto flex w-full max-w-6xl flex-1 items-center px-8 py-12">
        <section className="grid w-full gap-8 overflow-hidden rounded-[2.5rem] border border-white/60 bg-surface shadow-[0_30px_80px_rgba(20,32,51,0.13)] backdrop-blur lg:grid-cols-[1.2fr_0.8fr]">
          <div className="px-8 py-12 md:px-12 md:py-16">
            <span className="text-xs font-semibold uppercase tracking-[0.36em] text-slate-500">
              Desktop Experience
            </span>
            <h1 className="mt-5 max-w-xl text-5xl font-extrabold tracking-[-0.04em] text-slate-950 md:text-6xl">
              CourseSat is coming soon.
            </h1>
            <p className="mt-6 max-w-lg text-base leading-8 text-slate-600">
              Desktop visitors now load a dedicated desktop shell with separate
              header and footer components.
            </p>
          </div>
          <div className="flex min-h-[260px] items-end bg-[linear-gradient(160deg,#1e293b_0%,#334155_45%,#cf7735_100%)] p-8 md:p-12">
            <div className="max-w-sm rounded-[1.75rem] border border-white/15 bg-white/10 p-6 text-white backdrop-blur-sm">
              <p className="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">
                Launch State
              </p>
              <p className="mt-4 text-2xl font-bold">Simple placeholder view</p>
              <p className="mt-3 text-sm leading-7 text-white/80">
                Replace this shell later with your real desktop sections.
              </p>
            </div>
          </div>
        </section>
      </main>
      <DesktopFooter />
    </div>
  );
}
