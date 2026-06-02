#!/usr/bin/env python3
"""Parse pickup TSV and emit JSON for LanguageSchoolPickupSeeder."""

from __future__ import annotations

import csv
import json
import re
import sys
from pathlib import Path

SCHOOL_ALIASES = {
    'bright school of english': 'Bright School Of English',
}

LOCATION_ALIASES = {
    'new yark airport': 'New York Airport',
}

SKIP_LOCATIONS = {'-', '—', '–', ''}


def normalize_school(name: str) -> str:
    key = re.sub(r'\s+', ' ', name.strip().lower())
    return SCHOOL_ALIASES.get(key, name.strip())


def normalize_city(city: str) -> str:
    city = re.sub(r'\s+', ' ', city.strip())
    return 'Paris' if city.lower() == 'pairs' else city


def normalize_country(code: str) -> str:
    code = code.strip().upper()
    if code == 'IR':
        return 'IE'
    return code


def normalize_location(value: str) -> str | None:
    value = re.sub(r'\s+', ' ', value.strip().strip('"'))
    if value in SKIP_LOCATIONS:
        return None
    key = value.lower()
    return LOCATION_ALIASES.get(key, value)


def parse_fee(value: str) -> float | None:
    value = value.strip().strip('"')
    if value == '':
        return None
    value = value.replace('€', '').replace(',', '').strip()
    return float(value)


def parse_branch_row(cols: list[str]) -> list[dict]:
    while len(cols) < 19:
        cols.append('')

    school = normalize_school(cols[0])
    city = normalize_city(cols[1])
    country = normalize_country(cols[2])

    pickups: list[dict] = []
    for slot in range(8):
        location = normalize_location(cols[3 + slot * 2])
        fee = parse_fee(cols[4 + slot * 2])
        if location is None:
            continue
        if fee is None:
            continue
        pickups.append({
            'school_en': school,
            'city': city,
            'country': country,
            'pickup_location': location,
            'fee': fee,
        })

    return pickups


def main() -> int:
    root = Path(__file__).resolve().parents[1]
    src = root / 'data' / 'language_school_pickups_raw.tsv'
    out = root / 'data' / 'language_school_pickups.json'

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
            rows.extend(parse_branch_row(cols))

    out.write_text(json.dumps(rows, indent=2, ensure_ascii=False) + '\n', encoding='utf-8')
    branches = len({(r['school_en'], r['city'], r['country']) for r in rows})
    print(f'Wrote {len(rows)} pickups across {branches} branches to {out.name}')
    return 0


if __name__ == '__main__':
    raise SystemExit(main())
