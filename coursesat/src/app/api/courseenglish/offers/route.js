import { NextResponse } from "next/server";
import offersData from "@/mocdata/offers.json";

export async function GET() {
  return NextResponse.json(offersData);
}
