<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;

class BulkImportExportController extends Controller
{
    /**
     * Map of resources we expose for bulk import/export.
     */
    private array $resources = [
        'Language Schools' => [
            'language_schools' => ['label' => 'Language Schools', 'table' => 'language_schools'],
            'language_school_branches' => ['label' => 'Language School Branches', 'table' => 'language_school_branches'],
            'language_school_courses' => ['label' => 'Language School Courses', 'table' => 'language_school_courses'],
            'language_school_course_fees' => ['label' => 'Language School Course Fees', 'table' => 'language_school_course_fees'],
            'language_school_course_material_fees' => ['label' => 'Language School Course Material Fees', 'table' => 'language_school_course_material_fees'],
            'language_school_branch_registration_fees' => ['label' => 'Language School Branch Registration Fees', 'table' => 'language_school_branch_registration_fees'],
            'language_school_branch_high_season_fees' => ['label' => 'Language School Branch High Season Fees', 'table' => 'language_school_branch_high_season_fees'],
            'language_school_accommodations' => ['label' => 'Language School Accommodations', 'table' => 'language_school_accommodations'],
            'language_school_supplements' => ['label' => 'Language School Supplements', 'table' => 'language_school_supplements'],
            'language_school_pickups' => ['label' => 'Language School Pickups', 'table' => 'language_school_pickups'],
            'language_school_insurance_fees' => ['label' => 'Language School Insurance Fees', 'table' => 'language_school_insurance_fees'],
        ],
        'Universities' => [
            'universities' => ['label' => 'Universities', 'table' => 'universities'],
            'university_campuses' => ['label' => 'University Campuses', 'table' => 'university_campuses'],
            'university_courses' => ['label' => 'University Courses', 'table' => 'university_courses'],
            'university_course_intake_term' => ['label' => 'University Course Intake Term', 'table' => 'university_course_intake_term'],
            'university_course_intakes' => ['label' => 'University Course Intakes', 'table' => 'university_course_intakes'],
        ],
        'Course Attributes' => [
            'levels' => ['label' => 'Levels', 'table' => 'levels'],
            'subject_areas' => ['label' => 'Subject Areas', 'table' => 'subject_areas'],
            'intake_terms' => ['label' => 'Intake Terms', 'table' => 'intake_terms'],
            'language_tests' => ['label' => 'Language Tests', 'table' => 'language_tests'],
            'university_course_tags' => ['label' => 'University Course Tags', 'table' => 'university_course_tags'],
        ],
        'University Course Catalog' => [
            'university_course_catalogs' => ['label' => 'Course Catalog', 'table' => 'university_course_catalogs'],
            'university_course_levels' => ['label' => 'Course Levels', 'table' => 'university_course_levels'],
        ],
        'Destinations' => [
            'destinations' => ['label' => 'Destinations', 'table' => 'destinations'],
            'destination_features' => ['label' => 'Destination Features', 'table' => 'destination_features'],
            'destination_stats' => ['label' => 'Destination Stats', 'table' => 'destination_stats'],
            'destination_intakes' => ['label' => 'Destination Intakes', 'table' => 'destination_intakes'],
            'destination_faqs' => ['label' => 'Destination FAQs', 'table' => 'destination_faqs'],
            'destination_requirements' => ['label' => 'Destination Requirements', 'table' => 'destination_requirements'],
            'destination_disciplines' => ['label' => 'Destination Disciplines', 'table' => 'destination_disciplines'],
        ],
        'Utilities' => [
            'countries' => ['label' => 'Countries', 'table' => 'countries'],
            'cities' => ['label' => 'Cities', 'table' => 'cities'],
            'offices' => ['label' => 'Offices', 'table' => 'offices'],
        ],
        'Blogs & CMS' => [
            'blog_categories' => ['label' => 'Blog Categories', 'table' => 'blog_categories'],
            'blogs' => ['label' => 'Blogs', 'table' => 'blogs'],
            'blog_tags' => ['label' => 'Blog Tags', 'table' => 'blog_tags'],
        ],
    ];

    public function index()
    {
        $groups = [];
        foreach ($this->resources as $groupLabel => $resources) {
            $items = [];
            foreach ($resources as $key => $meta) {
                if (! Schema::hasTable($meta['table'])) {
                    continue;
                }
                $items[] = [
                    'key' => $key,
                    'label' => $meta['label'],
                    'count' => DB::table($meta['table'])->count(),
                ];
            }
            $groups[] = ['label' => $groupLabel, 'items' => $items];
        }

        return view('admin.bulk-import-export.index', compact('groups'));
    }

    public function show(string $key)
    {
        $resource = $this->resolve($key);
        $columns = $this->filterColumns(Schema::getColumnListing($resource['table']));
        $rows = DB::table($resource['table'])->select($columns)->paginate(20);

        return view('admin.bulk-import-export.show', [
            'resourceKey' => $key,
            'resource' => $resource,
            'columns' => $columns,
            'rows' => $rows,
        ]);
    }

    public function exportBlank(string $key): StreamedResponse
    {
        $resource = $this->resolve($key);
        $columns = $this->filterColumns(Schema::getColumnListing($resource['table']));

        return $this->csvResponse($resource['label'].'-blank.csv', [$columns]);
    }

    public function exportAll(string $key): StreamedResponse
    {
        $resource = $this->resolve($key);
        $columns = $this->filterColumns(Schema::getColumnListing($resource['table']));
        $query = DB::table($resource['table'])->select($columns);

        return $this->csvResponse($resource['label'].'-all.csv', $query->cursor(), $columns);
    }

    public function importNew(Request $request, string $key)
    {
        $resource = $this->resolve($key);
        $request->validate([
            'file' => ['required', 'file', 'mimetypes:text/plain,text/csv,application/csv,application/vnd.ms-excel'],
        ]);

        $token = Str::uuid()->toString();
        Storage::makeDirectory('tmp');
        $storedPath = $request->file('file')->storeAs('tmp', $token.'.csv');
        $fullPath = Storage::path($storedPath);

        [$columns, $previewRows, $totalRows] = $this->previewCsv($fullPath, $resource['table']);

        return view('admin.bulk-import-export.review', [
            'resourceKey' => $key,
            'resource' => $resource,
            'columns' => $columns,
            'rows' => $previewRows,
            'totalRows' => $totalRows,
            'token' => $token,
        ]);
    }

    public function importUpdate(Request $request, string $key)
    {
        // Placeholder: review workflow will be added in next step.
        return back()->with('info', 'Import Updates is coming soon for '.$this->resolve($key)['label']);
    }

    private function resolve(string $key): array
    {
        foreach ($this->resources as $group) {
            if (isset($group[$key])) {
                abort_unless(Schema::hasTable($group[$key]['table']), 404);
                return $group[$key];
            }
        }
        abort(404);
    }

    private function csvResponse(string $filename, $rows, array $columns = null): StreamedResponse
    {
        $columns ??= [];
        return response()->streamDownload(function () use ($rows, $columns) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so Excel/Sheets read Arabic correctly.
            fwrite($handle, "\xEF\xBB\xBF");
            if ($columns) {
                fputcsv($handle, $columns);
            } elseif (is_iterable($rows)) {
                // If first row object exists, derive headings
                $first = null;
                foreach ($rows as $row) { $first = $row; break; }
                if ($first) {
                    fputcsv($handle, array_keys((array) $first));
                    fputcsv($handle, (array) $first);
                }
            }
            foreach ($rows as $row) {
                fputcsv($handle, (array) $row);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function commitImport(Request $request, string $key)
    {
        $resource = $this->resolve($key);
        $data = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $filePath = Storage::path('tmp/'.$data['token'].'.csv');
        if (! Storage::exists('tmp/'.$data['token'].'.csv')) {
            return redirect()->route('admin.bulk-ie.show', $key)
                ->with('error', 'Import session expired. Please upload again.');
        }

        [$rowsImported, $rowsSkipped, $errors] = $this->importCsv($filePath, $resource['table']);
        Storage::delete('tmp/'.$data['token'].'.csv');

        $message = "{$rowsImported} rows imported";
        if ($rowsSkipped) {
            $message .= ", {$rowsSkipped} skipped";
        }

        return redirect()->route('admin.bulk-ie.show', $key)
            ->with($errors ? 'warning' : 'success', $message)
            ->with('import_errors', $errors);
    }

    private function importCsv(string $path, string $table): array
    {
        $columns = $this->filterColumns(Schema::getColumnListing($table));
        if (($handle = fopen($path, 'r')) === false) {
            return [0, 0, ['Unable to read uploaded file']];
        }

        $firstLine = fgets($handle);
        if ($firstLine === false) {
            return [0, 0, ['Empty file']];
        }
        [$delimiter, $header] = $this->parseLine($firstLine);
        $header = array_map(fn($h) => trim($this->stripBom($h)), $header);
        $allowed = array_intersect($header, $columns);
        if (count($allowed) === 0) {
            return [0, 0, ['No matching columns found in file']];
        }

        $rows = [];
        $errors = [];
        $imported = 0;
        $skipped = 0;

        while (($line = fgets($handle)) !== false) {
            [$delimiter, $data] = [$delimiter, str_getcsv($line, $delimiter)];
            $row = [];
            foreach ($header as $idx => $col) {
                if (in_array($col, $allowed, true)) {
                    $row[$col] = $data[$idx] ?? null;
                }
            }

            // Skip blank rows
            if (count(array_filter($row, fn($v) => $v !== null && $v !== '')) === 0) {
                $skipped++;
                continue;
            }

            $rows[] = $row;

            if (count($rows) === 500) {
                [$imported, $errors] = $this->flushImport($table, $rows, $imported, $errors);
                $rows = [];
            }
        }
        fclose($handle);

        if ($rows) {
            [$imported, $errors] = $this->flushImport($table, $rows, $imported, $errors);
        }

        return [$imported, $skipped, $errors];
    }

    private function flushImport(string $table, array $rows, int $imported, array $errors): array
    {
        try {
            DB::table($table)->insert($rows);
            $imported += count($rows);
        } catch (\Throwable $e) {
            $errors[] = $e->getMessage();
        }

        return [$imported, $errors];
    }

    private function previewCsv(string $path, string $table): array
    {
        $columns = $this->filterColumns(Schema::getColumnListing($table));
        if (($handle = fopen($path, 'r')) === false) {
            return [$columns, [], 0];
        }

        $firstLine = fgets($handle);
        if ($firstLine === false) {
            return [$columns, [], 0];
        }
        [$delimiter, $header] = $this->parseLine($firstLine);
        $header = array_map(fn($h) => trim($this->stripBom($h)), $header);
        $allowed = array_intersect($header, $columns);

        $preview = [];
        $count = 0;
        while (($line = fgets($handle)) !== false) {
            $data = str_getcsv($line, $delimiter);
            $row = [];
            foreach ($header as $idx => $col) {
                if (in_array($col, $allowed, true)) {
                    $row[$col] = $data[$idx] ?? null;
                }
            }
            $preview[] = $row;
            $count++;
            if (count($preview) >= 20) {
                // keep preview small
                break;
            }
        }
        // count remaining lines quickly
        while (($data = fgetcsv($handle)) !== false) {
            $count++;
        }
        fclose($handle);

        return [$allowed ?: $columns, $preview, $count];
    }

    private function filterColumns(array $columns): array
    {
        $blocked = [
            'logo', 'thumbnail', 'image', 'image_url', 'banner', 'photo', 'gallery', 'gallery_urls', 'file_path',
        ];
        return array_values(array_filter($columns, fn ($col) => ! in_array($col, $blocked, true)));
    }

    private function stripBom(string $value): string
    {
        $bom = pack('H*', 'EFBBBF');
        return preg_replace("/^$bom/", '', $value);
    }

    private function parseLine(string $line): array
    {
        $line = $this->toUtf8($line);
        $delimiters = [',', ';', "\t", '|'];
        $bestDelimiter = ',';
        $bestCount = 0;
        foreach ($delimiters as $d) {
            $fields = str_getcsv($line, $d);
            if (count($fields) > $bestCount) {
                $bestCount = count($fields);
                $bestDelimiter = $d;
            }
        }
        return [$bestDelimiter, str_getcsv($line, $bestDelimiter)];
    }

    private function toUtf8(string $line): string
    {
        $enc = mb_detect_encoding($line, ['UTF-8', 'UTF-16LE', 'UTF-16BE', 'UTF-32', 'ISO-8859-1'], true);
        if ($enc && $enc !== 'UTF-8') {
            $line = mb_convert_encoding($line, 'UTF-8', $enc);
        }
        return $line;
    }
}
