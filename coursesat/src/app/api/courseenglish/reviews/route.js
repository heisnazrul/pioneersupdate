import { NextResponse } from "next/server";
import reviews from "@/mocdata/reviews.json";

export async function GET() {
  return NextResponse.json({ reviews: reviews });
}
