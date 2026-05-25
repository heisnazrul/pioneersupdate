import { NextResponse } from "next/server";
import summerCamps from "@/mocdata/summerCamps.json";

export async function GET() {
  return NextResponse.json({ summer_camps: summerCamps });
}
