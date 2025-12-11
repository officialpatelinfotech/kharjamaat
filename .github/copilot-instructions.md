# kharjamaat AI Coding Guidelines (CodeIgniter 3)

Concise, project-specific instructions for AI assistants working in this repo. Focus on existing, observable patterns; do not introduce speculative frameworks. Keep changes surgical and consistent.

## Architecture Overview
- Framework: CodeIgniter 3 (core under `system/`, app code under `application/`). Entry point: root `index.php`.
- Pattern: Classic MVC. Controllers in `application/controllers/`, models in `application/models/`, views in `application/views/`.
- Controllers: PascalCase, extend `CI_Controller` (e.g. `Accounts`, `Migrate`). Models mostly suffixed with `M` (e.g. `AccountM`, `AdminM`). Views grouped by functional area (`Accounts/`, `Home/`, etc.).
- Routing: Defined in `application/config/routes.php`. Default controller: `Home`. Add new route by appending `$route['slug'] = 'Controller/method';`.
- Session usage: Direct `$_SESSION` checks (e.g. `if (empty($_SESSION['user'])) redirect(...)`), not CodeIgniter session library. Maintain this pattern unless refactoring holistically.

## Database & Data Access Patterns
- Data Layer: Mixed use of Query Builder (`$this->db->select()->from()...`) and raw SQL (`$this->db->query($sql, $params)`). Prefer parameter binding or `$this->db->escape()` when extending raw SQL to avoid injection. Follow existing COALESCE + aggregation style in financial queries.
- Tables referenced (non-exhaustive but recurring): `login`, `user`, `raza`, `miqaat`, `miqaat_invoice`, `miqaat_payment`, `fmb_takhmeen`, `fmb_takhmeen_payments`, `fmb_weekly_signup`, `menu`, `menu_items_map`, `menu_item`, `hijri_calendar`, `sabeel_takhmeen`, `sabeel_takhmeen_payments`, `general_rsvp`, `rsvp`, `chat`, `guest_rsvp`, `vasan_request`, `raza_type`.
- JSON storage: `raza.razadata` holds JSON-encoded field payloads; generators build HTML emails from dynamic field lists.
- ID generation: `AccountM::generate_raza_id($year)` yields `YYYY-N` sequential IDs; keep same pattern when adding related features.
- Financial calculations: Split takhmeen vs payments queries to avoid double counting (see `get_member_total_fmb_due`, `viewfmbtakhmeen`). When adding sums, mirror COALESCE safeguards and avoid joining payments in the same SUM unless intentionally aggregating.

## Date & Calendar Logic
- Hijri integration via `HijriCalendar` model methods (e.g. `get_hijri_date`, `hijri_month_name`, `get_hijri_days_for_month_year`). Controllers compute cycles: Hijri months 09–12 plus next year 01–08 define a takhmeen financial year boundary.
- Month menus & miqaats: Methods `get_hijri_month_menu`, `get_month_wise_menu` return enriched day objects with `menu`, `miqaats`, `is_holiday` flags.
- When extending date logic, reuse existing helpers instead of re-deriving calendar rules.

## Migrations & CLI
- Migrations live in `application/migrations/NNN_description.php` (sequential numbering). Applied via `php index.php migrate` (controller `Migrate` enforces `is_cli()` guard). Add new migration with incremental numeric prefix.

## Authentication & Authorization
- Login flow: `Accounts::login()` checks credentials against `login` table using MD5-hashed password (legacy). Do NOT silently switch to stronger hashing without a coordinated migration; if adding new password flows, flag clearly.
- Role-based redirects: Integer `role` field drives controller redirection (1=admin, 2=amilsaheb, 3=anjuman, 16=MasoolMusaid, 4–15=Umoor). Maintain consistent branching for new roles.

## Payments & Contributions
- FMB & Sabeel takhmeen: Aggregation methods compute totals, paid, due, and excess; avoid duplication by separating takhmeen and payments queries.
- General contributions & Miqaat invoices: Use history fetch patterns (`get_user_miqaat_invoice_history`, `get_user_gc_payment_history`). When adding similar endpoints, return structured JSON: `{ success, invoice, payments, totals... }`.

## External Integrations
- PhonePe: Custom library `application/libraries/Phonepe_PG.php` wraps SDK (`StandardCheckoutClient`). Current credentials are hard-coded; prefer moving to `.env` (library `vlucas/phpdotenv` available). Initiation pattern: build request via `StandardCheckoutPayRequestBuilder`, then `redirect($response->getRedirectUrl())` if `PENDING`.
- PDF: `Dompdf_lib::load()` returns a Dompdf instance after requiring vendor autoload. For new PDF generation, call `$this->dompdf_lib->load()` and stream or save.

## Views & Presentation
- Common header views: `Home/Header`, `Accounts/Header`; data arrays include keys like `user_name`, `member_name`, `sector`. Reuse header view loading rather than duplicating markup.
- Data enrichment pattern: Controllers hydrate arrays with computed labels before calling `$this->load->view()`. Maintain pre-processing in controllers rather than pushing logic into views.

## AJAX & JSON Responses
- Pattern: `$this->output->set_content_type('application/json')->set_output(json_encode($payload));` for structured responses. Reuse this for new API-style endpoints.
- Chat & status endpoints: Rely on model fetch + direct output; maintain ordering (e.g. chat by `created_at ASC`).

## Email Notifications
- Uses `$this->email` library (config in `application/config/email.php`). Multi-recipient sends are performed sequentially (multiple `->from()->to()->subject()->message()->send()` chains). For new notifications, consider batching only if necessary but keep current explicit style.
- Dynamic email template at `assets/email.php` with `{%placeholder%}` substitution. Preserve placeholder convention when extending.

## Example: Adding a New Feature (Events Module)
1. Create `application/controllers/Events.php` extending `CI_Controller`; guard entry with session check like existing controllers.
2. Add `application/models/EventsM.php` with data access using parameterized queries.
3. Create views under `application/views/Events/` and load headers similarly: `$this->load->view('Accounts/Header', $data);` (or `Home/Header` depending on audience).
4. Append route: `$route['events'] = 'events/index';` in `routes.php`.
5. For date handling, reuse `HijriCalendar` helpers instead of re-implementing conversions.

## Security & Consistency Notes
- Input sanitation is minimal; existing pattern replaces newlines and uses `htmlspecialchars` selectively. Follow existing approach; highlight if introducing stricter validation.
- Preserve `is_cli()` checks for CLI-only controllers (migrations). Do not expose migration endpoints to web.
- When modifying financial calculations, verify no double counting (avoid joining payment tables in SUM that repeat rows).

## Environment & Testing
- Dependencies managed via Composer (`composer.json`). Run tests (if/when added) with: `vendor/bin/phpunit` or `composer run test:coverage`. Older PHPUnit versions allowed; ensure compatibility before upgrading.
- Introduce secrets via `.env` (dotenv included). Access via `getenv()` or `$this->config->item()` after loading.

## Do / Avoid Summary
- DO mirror existing controller structure, session checks, and model naming.
- DO use Query Builder or parameterized queries for new DB interactions.
- DO reuse Hijri helpers for calendar logic.
- AVOID refactoring password hashing or session storage without stakeholder approval.
- AVOID introducing new global state patterns; keep data passed explicitly to views.

Suggest clarifications or request deeper coverage (e.g. specific table schema) if needed.
