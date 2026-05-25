import { NextResponse } from "next/server";
import blogs from "@/mocdata/blogs.json";

export async function GET() {
  return NextResponse.json({ blogs: blogs });
}
