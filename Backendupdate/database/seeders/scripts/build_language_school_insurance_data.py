#!/usr/bin/env python3
"""Parse insurance TSV and emit JSON for LanguageSchoolInsuranceSeeder."""

from __future__ import annotations

import csv
import json
import re
import sys
from pathlib import Path

SCHOOL_ALIASES = {
    'bright school of english': 'Bright School Of English',
    'lsi/ih portsmouth': 'LSI',
}


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


def parse_float(value: str) -> float:
    value = value.strip().strip('"')
    if value == '':
        return 0.0
    return float(value.replace(',', ''))


def parse_mandatory(value: str) -> str:
    value = value.strip().lower()
    if value in ('yes', 'y', '1', 'true'):
        return 'yes'
    return 'no'


def parse_row(cols: list[str]) -> dict | None:
    while len(cols) < 6:
        cols.append('')

    school = normalize_school(cols[0])
    if not school:
        return None

    city = normalize_city(cols[1])
    country = normalize_country(cols[2])
    weekly_fee = parse_float(cols[3])

    if weekly_fee <= 0:
        return None

    return {
        'school_en': school,
        'city': city,
        'country': country,
        'weekly_fee': weekly_fee,
        'admin_fee': parse_float(cols[4]),
        'is_mandatory': parse_mandatory(cols[5]),
    }


def main() -> int:
    root = Path(__file__).resolve().parents[1]
    src = root / 'data' / 'language_school_insurances_raw.tsv'
    out = root / 'data' / 'language_school_insurances.json'

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
    mandatory = sum(1 for r in rows if r['is_mandatory'] == 'yes')
    print(f'Wrote {len(rows)} insurance rows ({mandatory} mandatory) to {out.name}')
    return 0


if __name__ == '__main__':
    raise SystemExit(main())
