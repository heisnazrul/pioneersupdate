"use client";

import { useMemo, useState } from "react";
import Link from "next/link";

import { useLocale } from "@/components/providers/locale-provider";
import { useAgentApi } from "@/lib/agent-api";

export default function AgentStudentSelector({ value, onChange, textAlign = "text-left", isRtl = false }) {
  const { t } = useLocale();
  const l = (key, fallback = "") => t(`pages.institute_details.booking.${key}`, fallback);
  const [search, setSearch] = useState("");

  const { data, loading, error } = useAgentApi("/agent/students?per_page=100");
  const students = data?.data || [];

  const filteredStudents = useMemo(() => {
    const term = search.trim().toLowerCase();
    if (!term) return students;
    return students.filter((student) => {
      const name = String(student.name || "").toLowerCase();
      const email = String(student.email || "").toLowerCase();
      return name.includes(term) || email.includes(term);
    });
  }, [students, search]);

  const selectedStudent = students.find((student) => String(student.id) === String(value));

  if (loading) {
    return (
      <div className="flex min-h-[120px] items-center justify-center">
        <div className="h-8 w-8 animate-spin rounded-full border-4 border-[#0057B7] border-t-transparent" />
      </div>
    );
  }

  if (error) {
    return (
      <div className={`rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-600 ${textAlign}`}>
        {error.message || l("agentStudentsLoadError", "Unable to load your students.")}
      </div>
    );
  }

  if (!students.length) {
    return (
      <div className={`rounded-2xl border border-dashed border-gray-200 bg-[#F8FAFC] p-5 ${textAlign}`}>
        <p className="text-sm text-slate-600">{l("noStudents", "You have no students yet.")}</p>
        <Link href="/agent/students" className="mt-3 inline-block text-sm font-medium text-[#0057B7] hover:underline">
          {l("addStudentLink", "Add a student")}
        </Link>
      </div>
    );
  }

  return (
    <div className="space-y-4">
      <div>
        <label className={`mb-2 block text-sm font-medium text-[#102233] ${textAlign}`}>
          <span className="text-red-500">*</span>
          {" "}
          {l("selectStudent", "Select student")}
        </label>
        <input
          type="search"
          value={search}
          onChange={(event) => setSearch(event.target.value)}
          placeholder={l("searchStudents", "Search by name or email")}
          className={`h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm text-[#102233] outline-none transition-colors placeholder:text-gray-300 focus:border-[#0057B7] ${textAlign}`}
        />
      </div>

      <div className="max-h-56 overflow-y-auto rounded-2xl border border-gray-100 bg-[#F8FAFC]">
        {filteredStudents.length ? (
          filteredStudents.map((student) => {
            const isSelected = String(student.id) === String(value);
            return (
              <button
                key={student.id}
                type="button"
                onClick={() => onChange(student.id)}
                className={`flex w-full items-start gap-3 border-b border-gray-100 px-4 py-3 text-left transition last:border-b-0 hover:bg-white ${isSelected ? "bg-white ring-1 ring-inset ring-[#0057B7]/20" : ""}`}
                dir={isRtl ? "rtl" : "ltr"}
              >
                <span
                  className={`mt-1 flex h-4 w-4 shrink-0 items-center justify-center rounded-full border ${isSelected ? "border-[#0057B7] bg-[#0057B7]" : "border-gray-300 bg-white"}`}
                  aria-hidden
                >
                  {isSelected ? <span className="h-1.5 w-1.5 rounded-full bg-white" /> : null}
                </span>
                <span className="min-w-0 flex-1">
                  <span className="block text-sm font-semibold text-[#102233]">{student.name}</span>
                  <span className="mt-0.5 block truncate text-xs text-slate-500">{student.email}</span>
                  {student.phone ? (
                    <span className="mt-0.5 block text-xs text-slate-400">{student.phone}</span>
                  ) : null}
                </span>
              </button>
            );
          })
        ) : (
          <p className={`px-4 py-6 text-sm text-slate-500 ${textAlign}`}>
            {l("noStudentsMatch", "No students match your search.")}
          </p>
        )}
      </div>

      {selectedStudent ? (
        <div className={`rounded-2xl border border-[#0057B7]/15 bg-[#F0F7FF] p-5 ${textAlign}`}>
          <p className="text-xs font-medium uppercase tracking-wide text-[#0057B7]">{l("bookingFor", "Booking for")}</p>
          <p className="mt-1 text-lg font-semibold text-[#102233]">{selectedStudent.name}</p>
          <p className="mt-1 text-sm text-slate-500">{selectedStudent.email}</p>
        </div>
      ) : null}
    </div>
  );
}
