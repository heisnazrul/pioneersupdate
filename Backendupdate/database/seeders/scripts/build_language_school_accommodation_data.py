#!/usr/bin/env python3
"""Parse accommodation TSV and emit JSON for LanguageSchoolAccommodationSeeder."""

from __future__ import annotations

import csv
import json
import re
import sys
from pathlib import Path

SCHOOL_ALIASES = {
    'bright school of english': 'Bright School Of English',
}

MEAL_MAP = {
    'HB': 'Halfboard',
    'FB': 'Fullboard',
    'SC': 'Self Catering',
}

BEDROOM_MAP = {
    'Single': 'Single Room',
    'Twin': 'Twin Room',
    'Studio': 'Studio',
    'Private': 'Private Room',
    'Shared': 'Shared Room',
}

BATHROOM_MAP = {
    'Shared': 'Shared Bathroom',
    'Private': 'Private Bathroom',
}


def normalize_school(name: str) -> str:
    key = re.sub(r'\s+', ' ', name.strip().lower())
    return SCHOOL_ALIASES.get(key, name.strip())


def normalize_city(city: str) -> str:
    city = re.sub(r'\s+', ' ', city.strip())
    return 'Paris' if city.lower() == 'pairs' else city


def normalize_acc_type(value: str) -> str:
    return re.sub(r'\s+', ' ', value.strip())


def parse_float(value: str) -> float | None:
    value = value.strip().strip('"')
    if value == '':
        return None
    return float(value.replace(',', ''))


def parse_int(value: str) -> int | None:
    value = value.strip().strip('"')
    if value == '':
        return None
    return int(float(value))


def parse_date(value: str) -> str | None:
    value = value.strip().strip('"')
    if value == '':
        return None
    parts = value.split('/')
    if len(parts) != 3:
        return None
    d, m, y = parts
    d = d.zfill(2)
    m = m.zfill(2)
    if len(y) == 2:
        y = '20' + y
    return f'{y}-{m}-{d}'


def parse_row(cols: list[str]) -> dict | None:
    while len(cols) < 23:
        cols.append('')

    school = normalize_school(cols[0])
    city = normalize_city(cols[1])
    country = cols[2].strip().upper()
    acc_type = normalize_acc_type(cols[3])
    name = re.sub(r'\s+', ' ', cols[4].strip())
    bedroom = cols[5].strip()
    bathroom = cols[6].strip()
    meal = cols[7].strip().upper()

    weekly_fee = parse_float(cols[8])

    if not school or not city or not acc_type or not name:
        return None

    if weekly_fee is None:
        return None

    meal_en = MEAL_MAP.get(meal) if meal else None
    bedroom_en = BEDROOM_MAP.get(bedroom) if bedroom else None
    bathroom_en = BATHROOM_MAP.get(bathroom) if bathroom else None

    if meal and not meal_en:
        raise ValueError(f'Unknown meal code: {meal!r} ({school}/{city}/{name})')
    if bedroom and not bedroom_en:
        raise ValueError(f'Unknown bedroom: {bedroom!r} ({school}/{city}/{name})')
    if bathroom and not bathroom_en:
        raise ValueError(f'Unknown bathroom: {bathroom!r} ({school}/{city}/{name})')

    other_name = cols[19].strip().strip('"')
    other_name = re.sub(r'\s+', ' ', other_name) if other_name else None

    return {
        'school_en': school,
        'city': city,
        'country': country,
        'accommodation_type': acc_type,
        'name': name,
        'bedroom_type': bedroom_en,
        'bathroom_type': bathroom_en,
        'meal_plan': meal_en,
        'weekly_fee': weekly_fee,
        'admin_fee': parse_float(cols[9]) or 0,
        'security_deposit': parse_float(cols[10]) or 0,
        'min_age': parse_int(cols[11]),
        'under_18_supplement_fee': parse_float(cols[12]) or 0,
        'summer_supplement_fee': parse_float(cols[13]) or 0,
        'summer_start_date': parse_date(cols[14]),
        'summer_end_date': parse_date(cols[15]),
        'winter_supplement_fee': parse_float(cols[16]) or 0,
        'winter_start_date': parse_date(cols[17]),
        'winter_end_date': parse_date(cols[18]),
        'other_supplement_name': other_name,
        'other_supplement_fee': parse_float(cols[20]) or 0,
        'other_start_date': parse_date(cols[21]),
        'other_end_date': parse_date(cols[22]),
    }


def main() -> int:
    root = Path(__file__).resolve().parents[1]
    src = root / 'data' / 'language_school_accommodations_raw.tsv'
    out = root / 'data' / 'language_school_accommodations.json'

    if not src.exists():
        print(f'Missing {src}', file=sys.stderr)
        return 1

    rows: list[dict] = []
    with src.open(encoding='utf-8', newline='') as handle:
        reader = csv.reader(handle, delimiter='\t', quotechar='"')
        for line_no, cols in enumerate(reader, 1):
            if not cols or not cols[0].strip():
                continue
            if cols[0].strip().startswith('school_name'):
                continue
            try:
                row = parse_row(cols)
            except ValueError as exc:
                print(f'Row {line_no}: {exc}', file=sys.stderr)
                return 1
            if row:
                rows.append(row)

    out.write_text(json.dumps(rows, indent=2, ensure_ascii=False) + '\n', encoding='utf-8')
    types = sorted({r['accommodation_type'] for r in rows})
    print(f'Wrote {len(rows)} accommodations to {out.name}')
    print('Types:', ', '.join(types))
    return 0


if __name__ == '__main__':
    raise SystemExit(main())
