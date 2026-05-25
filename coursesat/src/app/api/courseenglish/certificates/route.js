import { NextResponse } from "next/server";
import certData from "@/mocdata/certificates.json";

export async function GET() {
  return NextResponse.json(certData);
}
