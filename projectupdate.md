# Pioneers Edu – Backend Database Tables
> Analysed from `/Backend/database/migrations/`  
> Total tables: **~88**  
> Last analysed: 2026-04-27

---

## 📌 How to use this file
- Review each group below.
- Add your notes / proposed changes under each table (columns to add, remove, rename, or split).
- Mark tables you want to **keep ✅**, **drop ❌**, **merge 🔀**, or **redesign 🔁**.

---

## GROUP 1 — 👤 Users & Authentication

These tables manage user accounts, roles, authentication tokens, and sessions.

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 1 | `users` | id, name, email, username, role (admin/team/counsellor/uni_agent/agent/lg_agent/school/lg_student/uni_student), phone, avatar, status, last_login_at, google_id, password | Central auth table |
| 2 | `user_profiles` | id, user_id (FK), first_name, last_name, date_of_birth, gender, nationality_country_id, current_country_id, current_city_id, address_line, postal_code, secondary_email, alt_phone_e164 | Extended profile info |
| 3 | `user_otps` | id, user_id (FK), purpose, code, channel, expires_at, used_at, meta | OTP for email/phone verification |
| 4 | `sessions` | id, user_id (FK), ip_address, user_agent, payload, last_activity | Laravel session driver |
| 5 | `password_reset_tokens` | email (PK), token, created_at | Password reset |
| 6 | `personal_access_tokens` | id, tokenable_type, tokenable_id, name, token, abilities, last_used_at | Sanctum tokens |
| 7 | `oauth_access_tokens` | id, user_id, client_id, name, scopes, revoked, expires_at | Passport OAuth |
| 8 | `oauth_auth_codes` | id, user_id, client_id, scopes, revoked, expires_at | Passport auth codes |
| 9 | `oauth_clients` | id, user_id, name, secret, redirect, personal_access_client, password_client, revoked | Passport clients |
| 10 | `oauth_device_codes` | id, user_id, client_id, scopes, revoked, expires_at | Passport device codes |
| 11 | `oauth_refresh_tokens` | id, access_token_id, revoked, expires_at | Passport refresh tokens |

---

## GROUP 2 — 🏢 Agents & Agent-Student Relationships

Manages travel/education agents and their referred students.

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 12 | `agents` | id, user_id (FK), company_name, phone, status, referral_code, referral_discount, commission_percent, referral_joined_at, verified_at | Agent profile |
| 13 | `agent_students` | id, agent_id (FK), student_user_id (FK), name, email, phone, country, onboarding_token, onboarding_token_expires_at, onboarded_at | Students referred by agents |

---

## GROUP 3 — 🌍 Geography & Reference Data

Core lookup tables used across the whole system.

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 14 | `countries` | id, name, ar_name, slug, flag, country_code, is_popular, currency_code, phone_code, description, ar_description, capital, continent, display_order, is_active | |
| 15 | `cities` | id, name, ar_name, slug, description, ar_description, country_id (FK), latitude, longitude, display_order, is_active | |

---

## GROUP 4 — 🎓 University Module

Tables that power the University section (courses, campuses, fees, wishlists, applications).

### 4a — University Core

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 16 | `universities` | id, name, ar_name, slug, logo, cover_image, country_id (FK), city_id (FK), type, established_year, website, rank, famous_for, ar_famous_for, fees, ar_fees, is_featured, is_active, deleted_at | |
| 17 | `university_campuses` | id, university_id (FK), city_id (FK), name, ar_name, slug, address, ar_address, lat, lng, is_online, is_active | |
| 18 | `university_courses` | id, university_id (FK), level_id (FK), subject_area_id (FK), name, ar_name, slug, duration_value, duration_unit, overview, ar_overview, awarding_body, ar_awarding_body, is_active | |
| 19 | `university_course_intakes` | id, university_course_id (FK), intake_term_id (FK), deadline_date, start_date, is_active | Pivot: course ↔ intake |
| 20 | `university_course_fees` | id, course_id (FK), campus_id (FK), first_year_fee, currency, note, ar_note, is_active | |
| 21 | `university_course_catalogs` | id, (details from update migration) | Catalog grouping |
| 22 | `university_course_levels` | (from update migration, likely an alias/pivot for levels) | |
| 23 | `university_course_intake_term` | id, (pivot for course ↔ term) | |
| 24 | `university_course_tags` | id, key, name, ar_name, is_active | Tag lookup |
| 25 | `university_accommodation_rooms` | id, title, ar_title, slug, description, ar_description, price, features (JSON), image, details, ar_details | University housing |

### 4b — University Applications & Wishlists

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 26 | `university_wishlists` | id, user_id (FK), course_id (FK) | Student wishlist |
| 27 | `uni_applications` | id, course_id (FK), name, email, phone, intake, status | Quick uni application |

---

## GROUP 5 — 🗣️ Language School Module

The largest module — manages language schools, branches, courses, pricing add-ons, and bookings.

### 5a — School & Branch Core

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 28 | `language_schools` | id, name, ar_name, slug, description, ar_description, logo, accreditation_ids (JSON), rating, is_preferred | Top-level school entity |
| 29 | `language_school_branches` | id, language_school_id (FK), city_id (FK), slug, description, ar_description, gallery_urls (JSON), video_url | Branch per city |

### 5b — Language School Courses & Fees

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 30 | `language_school_courses` | id, branch_id (FK), language_course_type_id (FK), language_course_tag_id (FK), slug, name, ar_name, description, ar_description, start_day, required_level, study_time, lessons_per_week, min_age | Main course per branch |
| 31 | `language_school_course_fees` | id, language_school_course_id (FK), week_number, fee, valid_from, valid_to, price_split | Weekly fee pricing |
| 32 | `language_school_course_material_fees` | id, language_school_course_id (FK), amount, billing_unit, billing_count | Material/book fees |

### 5c — Branch-Level Fees & Add-ons

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 33 | `language_school_branch_registration_fees` | id, branch_id (FK), amount | One-time registration fee |
| 34 | `language_school_branch_high_season_fees` | id, branch_id (FK), week_start, week_end, fee | Peak season surcharge |
| 35 | `language_school_accommodations` | id, branch_id (FK), title, ar_title, slug, description, ar_description, price, currency, features (JSON), image, details, ar_details | Branch accommodation options |
| 36 | `language_school_supplements` | id, branch_id (FK), name, ar_name, amount, currency, billing_unit, billing_count | Optional extras |
| 37 | `language_school_pickups` | id, branch_id (FK), route, price, currency, notes | Airport/transport pickups |
| 38 | `language_school_insurance_fees` | id, branch_id (FK), amount, currency, billing_unit, billing_count | Insurance pricing |

### 5d — Discounts & Coupons

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 39 | `language_school_discounts` | id, name, ar_name, discount_percentage, applies_to_all_branches, applies_to_all_countries, school_branch_ids (JSON), country_ids (JSON), applies_to_user_country, start_date, end_date, is_active | General promotions |
| 40 | `language_school_coupons` | id, code, name, discount_type, discount_value, usage_limit, used_count, expiration_date, minimum_purchase_amount, is_active | Coupon codes |
| 41 | `language_school_pioneers_discounts` | id, name, ar_name, weeks, discount_amount, discount_full_for, is_active | Pioneers-specific discount (multi-week) |

### 5e — Language Course Products (Online / Summer / Training)

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 42 | `language_course_online_courses` | id, slug, language_school_id (FK), course_type_id (FK), tag_id (FK), name, ar_name, description, ar_description, required_level, study_time, lessons_per_week, min_age, start_date, fee_type, fee_amount, currency_code, registration_fee, thumbnail, visible, status, deleted_at | Online classes |
| 43 | `language_course_summer_camps` | id, slug, branch_id (FK), course_type_id (FK), tag_id (FK), name, ar_name, description, ar_description, required_level, study_time, lessons_per_week, age_range, start_date, payment_deadline, fee_type, fee_amount, registration_fee, thumbnail, visible, status, deleted_at | Summer camp programs |
| 44 | `language_course_summer_camp_details` | id, camp_id (FK, unique), overview, ar_overview, academics, ar_academics, activities, ar_activities, accommodation, ar_accommodation, safeguarding, ar_safeguarding | Detail/tab content for a camp |
| 45 | `language_course_training_courses` | id, slug, language_school_id (FK), branch_id (FK), course_type_id (FK), tag_id (FK), name, ar_name, description, ar_description, required_level, study_time, lessons_per_week, min_age, start_date, fee_type, fee_amount, currency_code, registration_fee, thumbnail, visible, status, deleted_at | Professional training |

### 5f — Language Course Lookup/Taxonomy

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 46 | `language_course_tags` | id, tag_code (unique), name, ar_name, description, ar_description, deleted_at | e.g. "General English", "IELTS Prep" |
| 47 | `language_course_types` | id, type_code (unique), name, ar_name, description, ar_description, deleted_at | e.g. "In-Person", "Online", "Summer Camp" |

### 5g — Accommodation Lookup Tables

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 48 | `meal_plans` | id, meal_code (unique), name, ar_name, description, ar_description, deleted_at | e.g. "Half Board", "Self Catering" |
| 49 | `bedroom_types` | id, bedroom_code (unique), name, ar_name, description, ar_description, deleted_at | e.g. "Single", "Twin", "Double" |
| 50 | `bathroom_types` | id, bathroom_code (unique), name, ar_name, description, ar_description, deleted_at | e.g. "En-suite", "Shared" |
| 51 | `accreditations` | id, name, ar_name, picture | e.g. British Council, EAQUALS |

### 5h — Language Course User Actions

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 52 | `language_course_wishlists` | id, user_id (FK), course_type, course_id | Polymorphic wishlist |
| 53 | `language_course_compares` | id, user_id (FK), course_type, course_id | Polymorphic compare |

---

## GROUP 6 — 📋 Bookings

Booking records for each product type. All link to `users`.

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 54 | `language_course_bookings` | id, user_id (FK), course_id (FK → language_school_courses), accommodation_id (FK), pickup_id (FK), insurance_id (FK), whatsapp, user_age, weeks, start_date, accommodation_weeks, supplements_ids (JSON), final_price, currency, status, assigned_to (FK) | In-person language school bookings |
| 55 | `online_course_bookings` | id, user_id (FK), course_id (FK → language_course_online_courses), whatsapp, weeks, start_date, final_price, status | Online course bookings |
| 56 | `summer_camps_bookings` | id, user_id (FK), camp_id (FK → language_course_summer_camps), whatsapp, weeks, start_date, special_supplements (JSON), final_price, status | Summer camp bookings |
| 57 | `training_course_bookings` | id, timestamps (stub – empty table) | ⚠️ Stub only, not implemented yet |

---

## GROUP 7 — 📚 Applications

Formal study applications (different from quick bookings).

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 58 | `applications` | id, application_id (unique), first_name, last_name, email, phone, citizenship, nationality, nationality_other, highest_education, grade_average, has_english_test, english_test_type, english_test_score, destination_interest (JSON), destinations_other, preferred_intake, budget_range, status, assigned_to (FK), assigned_role, status_notes | General university consultation applications |
| 59 | `uni_applications` | id, course_id (FK → university_courses), name, email, phone, intake, status | Quick per-course application |
| 60 | `scholarship_applications` | id, scholarship_id (FK), user_id (FK nullable), name, email, phone, country, city, education_level, grade_average, english_proficiency, status, notes | Scholarship-specific applications |

---

## GROUP 8 — 🏆 Scholarships

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 61 | `scholarships` | id, (full details from migration including title, ar_title, country, deadline, tags, etc.) | |
| 62 | `scholarship_applications` | *(also listed above in GROUP 7)* | |

---

## GROUP 9 — 🗺️ Destinations

Content pages about study destinations (countries/regions).

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 63 | `destinations` | id, country_id (FK), slug, name, ar_name, region, ar_region, description, ar_description, image_url, short_pitch, ar_short_pitch, tuition_range, visa_timeline, work_rights, scholarships_summary, entry_req_gpa, entry_req_language, university_count, is_active, deleted_at | Main destination page |
| 64 | `destination_features` | id, destination_id (FK), feature, ar_feature | Bullet highlights |
| 65 | `destination_stats` | id, destination_id (FK), label, ar_label, value, ar_value | Quick stats |
| 66 | `destination_intakes` | id, destination_id (FK), month, ar_month, event, ar_event | Intake calendar |
| 67 | `destination_faqs` | id, destination_id (FK), question, ar_question, answer, ar_answer | Destination-specific FAQs |
| 68 | `destination_requirements` | id, destination_id (FK), requirement, ar_requirement | Entry requirements |
| 69 | `destination_disciplines` | id, destination_id (FK), discipline, ar_discipline | Study fields available |
| 70 | `destination_guides` | id, destination_id (FK), title, ar_title, file_path, year, is_active | PDF guides |

---

## GROUP 10 — 📰 Blog & Content

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 71 | `blog_categories` | id, name, ar_name, slug, description, ar_description, color, display_order, is_active, deleted_at | |
| 72 | `blogs` | id, title, ar_title, slug, summary, ar_summary, content, ar_content, category_id (FK), audience_scope, featured_image, publisher_id (FK), published_at, deleted_at | |
| 73 | `blog_tags` | id, name, slug, ar_name, description, ar_description, color, display_order, is_active | |
| 74 | `blog_blog_tag` | id, blog_id (FK), blog_tag_id (FK) | Pivot: blog ↔ tags |

---

## GROUP 11 — ⭐ Reviews, FAQs & Contact

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 75 | `reviews` | id, name, ar_name, photo, institute_name, ar_institute_name, title, ar_title, review_text, ar_review_text, gender, rating, facebook_link, twitter_link, instagram_link, linkedin_link, screenshots (JSON), video, is_approved, university_name, course_name, country_name, video_url, video_iframe, thumbnail, is_active | Student testimonials |
| 76 | `faqs` | id, category, ar_category, question, ar_question, answer, ar_answer | General FAQs |
| 77 | `contact_submissions` | id, name, email, phone, subject, message, status | Contact form |

---

## GROUP 12 — 📄 CMS Pages

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 78 | `cms_pages` | id, app (courseenglish/university), slug, title, ar_title, content, ar_content, meta_title, meta_description, is_active, display_order | Static/managed pages per app |

---

## GROUP 13 — 🏛️ Reference / Taxonomy Tables

Shared lookup data used across modules.

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 79 | `levels` | id, name, ar_name, sort_order, is_active | Course study levels (Bachelor, Master, PhD…) |
| 80 | `subject_areas` | id, key, name, ar_name, slug, sort_order, is_active | Academic fields |
| 81 | `intake_terms` | id, key, name, ar_name, month_num, sort_order, is_active | e.g. September, January, Rolling |
| 82 | `language_tests` | id, key, name, ar_name, is_active | IELTS, TOEFL, Duolingo… |
| 83 | `certifications` | id, (details from migration) | School/program certifications |
| 84 | `accreditations` | *(also listed in GROUP 5f)* | |

---

## GROUP 14 — 🏠 Offices

Pioneers Edu physical offices.

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 85 | `offices` | id, slug, city, ar_city, country, ar_country, address, ar_address, phone, email, type, image, map_url, description, ar_description, hours, ar_hours | |

---

## GROUP 15 — 💰 Finance / Currency

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 86 | `exchange_rates` | id, base_currency, target_currency, rate | Live/manual exchange rates |
| 87 | `conversion_fees` | id, base_currency, target_currency, fee (%) | Fee applied on conversion |

---

## GROUP 16 — 🖼️ Media

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 88 | `galleries` | id, title, use_case, image_path, alt_text | General image gallery |

---

## GROUP 17 — ⭐ Featured Lists

| # | Table | Key Columns | Notes |
|---|-------|-------------|-------|
| 89 | `featured_lists` | id, key, name, ar_name, is_active | Named lists (e.g. "Top Universities", "Popular Schools") |

---

## GROUP 18 — ⚙️ System / Laravel Internal Tables

These are Laravel framework tables — generally not modified by the business logic.

| # | Table | Notes |
|---|-------|-------|
| 90 | `cache` | Laravel cache driver |
| 91 | `cache_locks` | Cache locking |
| 92 | `jobs` | Laravel Queue jobs |
| 93 | `job_batches` | Laravel batch jobs |
| 94 | `failed_jobs` | Failed queue jobs |
| 95 | `migrations` | Migration tracking |
| 96 | `settings` | App-level key-value settings |

---

## 🗒️ Your Notes / Planned Changes

> Add your structure update notes here for each group or table.

### Users & Auth (Group 1)
- [ ] ...

### Agents (Group 2)
- [ ] ...

### Geography (Group 3)
- [ ] ...

### University Module (Group 4)
- [ ] ...

### Language School Module (Group 5)
- [ ] ...

### Bookings (Group 6)
- [ ] ...

### Applications (Group 7)
- [ ] ...

### Scholarships (Group 8)
- [ ] ...

### Destinations (Group 9)
- [ ] ...

### Blog & Content (Group 10)
- [ ] ...

### Reviews, FAQs & Contact (Group 11)
- [ ] ...

### CMS Pages (Group 12)
- [ ] ...

### Reference / Taxonomy (Group 13)
- [ ] ...

### Offices (Group 14)
- [ ] ...

### Finance / Currency (Group 15)
- [ ] ...

### Media (Group 16)
- [ ] ...

### Featured Lists (Group 17)
- [ ] ...

### System Tables (Group 18)
- [ ] ...
