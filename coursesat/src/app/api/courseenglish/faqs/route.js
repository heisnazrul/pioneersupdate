import { NextResponse } from "next/server";
import faqs from "@/mocdata/faqs.json";

export async function GET() {
  return NextResponse.json({ faqs: faqs });
}
