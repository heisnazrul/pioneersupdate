import { NextResponse } from "next/server";
import path from "path";
import { promises as fs } from "fs";

export async function GET(request) {
  try {
    const { searchParams } = new URL(request.url);
    const mockFilePath = path.join(process.cwd(), "src", "mocdata", "institutes.json");
    const fileContents = await fs.readFile(mockFilePath, "utf8");
    const courses = JSON.parse(fileContents);

    // Filter by tags or whatever we need for basic mocking
    const tag = searchParams.get("tag");
    let filtered = courses;
    if (tag) {
      filtered = filtered.filter((c) => c.tags && c.tags.includes(tag));
    }

    // Extract unique tags for the filter dropdown
    const allTags = new Set();
    courses.forEach(c => {
      if (c.tags) {
        c.tags.forEach(t => allTags.add(t));
      }
    });

    return NextResponse.json({
      courses: filtered,
      tags: Array.from(allTags)
    });
  } catch (error) {
    console.error("Failed to read institutes.json", error);
    return NextResponse.json({ error: "Failed to load mock data" }, { status: 500 });
  }
}
