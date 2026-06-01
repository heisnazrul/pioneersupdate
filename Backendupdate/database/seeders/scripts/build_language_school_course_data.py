#!/usr/bin/env python3
"""Parse language school course TSV and emit JSON for seeders."""

from __future__ import annotations

import json
import re
import sys
from pathlib import Path

SCHOOL_ALIASES = {
    'bright school of english': 'Bright School Of English',
}

COUNTRY_MAP = {'UK': 'GB'}


def normalize_school(name: str) -> str:
    key = re.sub(r'\s+', ' ', name.strip().lower())
    return SCHOOL_ALIASES.get(key, name.strip())


def normalize_city(city: str) -> str:
    city = city.strip()
    return 'Paris' if city.lower() == 'pairs' else city


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


def parse_row(cols: list[str]) -> dict:
    while len(cols) < 30:
        cols.append('')

    tiers = []
    for i in range(7):
        w_cat = cols[16 + i * 2].strip()
        fee = cols[17 + i * 2].strip()
        if fee == '':
            continue
        tiers.append({
            'week_category': parse_int(w_cat) if w_cat else 1,
            'weekly_fee': parse_float(fee),
        })

    promo_pct = cols[13].strip()
    promo = None
    if promo_pct:
        promo = {
            'promotion_percentage': parse_float(promo_pct),
            'promo_from': cols[14].strip() or None,
            'promo_to': cols[15].strip() or None,
        }

    mandatory_name = cols[11].strip() or None

    return {
        'school_en': normalize_school(cols[0]),
        'city': normalize_city(cols[1]),
        'country': cols[2].strip(),
        'course_type': cols[3].strip(),
        'course_name_from_school': cols[4].strip(),
        'hours_per_week': parse_float(cols[5]),
        'lessons_per_week': parse_int(cols[6]),
        'min_level': cols[7].strip() or None,
        'min_age': parse_int(cols[8]),
        'material_books_fee': parse_float(cols[9]) or 0,
        'registration_admin_fee': parse_float(cols[10]) or 0,
        'mandatory_additional_fee_name': mandatory_name,
        'mandatory_additional_fee': parse_float(cols[12]) or 0,
        'promotion': promo,
        'tiers': tiers,
    }


def main() -> int:
    src = Path(sys.argv[1]) if len(sys.argv) > 1 else Path(__file__).resolve().parent.parent / 'data' / 'language_school_courses_raw.tsv'
    out = Path(__file__).resolve().parent.parent / 'data' / 'language_school_courses.json'

    rows = []
    for line in src.read_text(encoding='utf-8').splitlines():
        line = line.rstrip('\n')
        if not line.strip():
            continue
        rows.append(parse_row(line.split('\t')))

    out.write_text(json.dumps(rows, ensure_ascii=False, indent=2), encoding='utf-8')
    print(f'Wrote {len(rows)} courses to {out}')
    return 0


if __name__ == '__main__':
    raise SystemExit(main())
