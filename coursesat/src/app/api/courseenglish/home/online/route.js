import { NextResponse } from "next/server";
import onlineCourses from "@/mocdata/onlineCourses.json";

export async function GET() {
  return NextResponse.json({ online_courses: onlineCourses });
}
