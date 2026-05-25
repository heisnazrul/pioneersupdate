import { NextResponse } from "next/server";
import utilitiesData from "@/mocdata/utilities.json";

export async function GET() {
  return NextResponse.json(utilitiesData);
}
