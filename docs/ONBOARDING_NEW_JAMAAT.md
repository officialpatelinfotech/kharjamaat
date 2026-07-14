# Onboarding a New Jamaat

This codebase runs multiple jamaats from a single deployment. Each jamaat gets its own
subdomain and its own database; the same PHP code serves all of them, and the hostname
in the incoming request decides which database/branding to use. This doc is the
step-by-step checklist for adding one more, with exact code to insert — a developer
with no prior context on this repo should be able to follow it end to end.

Reference case: `alezz.tanzeem.in` was onboarded following this exact process
(2026-07). Its blocks are shown below as the worked example — **copy that pattern**,
don't reinvent it. Replace `NEWDOMAIN` / `newjamaat` placeholders below with your
actual new domain and a short DB-safe slug for it (lowercase, no dots/dashes ideally,
e.g. `alezz`, `karachi`, `pune2`).

---

## 0. Architecture in one paragraph

CodeIgniter 3. `ENVIRONMENT` is `production` for any non-localhost host
(`index.php`). Production config lives in `application/config/production/`.
`database.php` and `config.php` in that folder both switch on
`$_SERVER['HTTP_HOST']` to pick a DB and `base_url` per domain. Per-jamaat branding
(name, address, emails, receipt text, notification toggles) lives in the `app_settings`
table inside *that jamaat's own database* and is edited via **Admin → Preferences** —
no code changes needed for that part. Deployment is GitHub Actions
(`.github/workflows/deploy.yml`), FTP, triggered on every push to `main`.

---

## 1. Hosting

1. In cPanel, create the subdomain (e.g. `alezz.tanzeem.in`). This creates its docroot
   folder, e.g. `/home3/kharjam1/alezz.tanzeem.in/`, containing only cPanel defaults
   (`.well-known/`, `cgi-bin/`, a placeholder `index.php`). **Don't delete
   `.well-known/`** — AutoSSL/Let's Encrypt uses it for domain validation.
2. In cPanel → MySQL Databases: create a new database and a new database user for
   this jamaat (e.g. database `kharjam1_newjamaat`, user `kharjam1_newjamaat`, a fresh
   password), and add the user to the database with **All Privileges**.

## 2. Database schema

The `migrations` table bookkeeping matters here — read this carefully before doing
anything else, it will save you a broken first page load.

- Roughly 30 of this app's tables (`user`, `login`, `roles`, `raza`, `miqaat`,
  `hijri_calendar`, `sabeel_takhmeen*`, etc.) **predate the migration system** and are
  not created by any file in `application/migrations/`. Running migrations against a
  truly empty DB will NOT produce a working schema — the app will fatal the moment it
  queries `user` or `login`.
- The correct source of truth is a **structure-only dump of an existing, fully
  migrated jamaat DB.** In phpMyAdmin: open an existing jamaat's DB (e.g.
  `kharjam1_tanzeem`) → Export → Custom → under "Object creation options" tick only
  "Structure" (uncheck "Data") → Go. Save that `.sql` file.

Steps, in order:

1. Import that structure-only `.sql` file into the new jamaat's empty DB (phpMyAdmin
   → select the new DB → Import → choose file → Go).
2. Insert the migration bookkeeping row so CodeIgniter doesn't try to replay
   `application/migrations/001*.php` through `064*.php` against a schema that already
   has those changes. Several of those migration files do non-idempotent
   `ALTER TABLE ADD COLUMN` — replaying them fatals with "duplicate column". Also, the
   migrations folder has a genuine numbering gap (`012_...php` jumps straight to
   `015_...php` — files 013/014 don't exist anywhere in this repo) — CodeIgniter's
   sequential migration mode throws `"There is a gap in the migration sequence near
   version number: 015"` if it ever tries to walk the full history instead of
   short-circuiting at "already current". Run in the new DB's SQL tab:

   ```sql
   DELETE FROM `migrations`;
   INSERT INTO `migrations` (`version`) VALUES (64);
   ```

   Check `application/config/migration.php`'s `$config['migration_version'] = 64;`
   line first — use whatever number is actually there if it's since changed.

## 3. Seed data

Run all of these in the new jamaat's database (phpMyAdmin → SQL tab), in order.

### 3a. `app_settings` — jamaat branding

All of these are also editable later via **Admin → Preferences**, but the app needs
a `login` row to exist before you can log in to reach that screen, so seed via SQL
first.

```sql
INSERT INTO `app_settings` (`key`, `value`) VALUES
  ('jamaat_name',          'REPLACE — e.g. Al-Ezz Jamaat'),
  ('jamaat_place',         'REPLACE — e.g. Al-Ezz'),
  ('address_line',         'REPLACE — street address'),
  ('city_state',           'REPLACE — city, state'),
  ('pincode',              'REPLACE — pincode'),
  ('support_email',        'REPLACE — e.g. anjuman@newjamaat.example.com'),
  ('registration_email',   'REPLACE — e.g. anjuman@newjamaat.example.com'),
  ('admin_emails',         'REPLACE — admin1@example.com,\nadmin2@example.com'),
  ('amil_whatsapp',        'REPLACE — +91XXXXXXXXXX'),
  ('receipt_jamaat_name',  'REPLACE — name printed on receipts'),
  ('trust_regn_no',        'REPLACE — trust registration no.'),
  ('managed_by',           'REPLACE — e.g. Anjuman-e-Saifee')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
```

`jamaat_place` matters beyond display — several code paths (see §4 file 3, and
`Admin.php`'s bulk-reset action) build strings like `'Residing in ' . jamaat_place()`
and compare them against stored data. **Pick this value once and keep it stable** —
if you rename it later in Preferences, you must also re-run the `status_options`
rename in §3b with the new value, or the auto active/inactive trigger stops matching.

### 3b. `status_options` — deeni/health/residential status dropdowns

Not created by the structure-only dump's data (there is none) and not created by
migrations either (see §2) if you skipped replaying them — insert the full default
set. Replace `<Place>` with the exact same value you used for `jamaat_place` above:

```sql
INSERT INTO `status_options` (`type`, `status_key`, `status_label`, `is_inactive_trigger`) VALUES
('deeni', 'Normal', 'Normal (Active)', 0),
('deeni', 'Deen Badli Lidu che', 'Deen Badli Lidu che (Inactive)', 1),
('deeni', 'Married Outside', 'Married Outside (Inactive)', 1),
('deeni', 'Misaq Not Given', 'Not given Misaq to Syedna Mufaddal Saifuddin AQA tus after Takht Nashini (Inactive)', 1),
('deeni', 'Mustajeeb', 'Mustajeeb (Inactive)', 1),
('deeni', 'No Ashara / LQ', 'No Ashara / LQ attended for past 3 years (Inactive)', 1),
('deeni', 'No Vajebaat / Sabeel', 'Not paid Sila Fitra / Vajeebaat / Sabeel for last 3 years (Inactive)', 1),
('deeni', 'Zero Days Scanned in Ashara Mubaraka', 'Zero Days Scanned in Ashara Mubaraka (Inactive)', 1),
('health', 'Healthy', 'Fit & Healthy (Active)', 0),
('health', 'Medically Unfit', 'Handicapped Medically Unfit (Active)', 0),
('health', 'Hospitalised', 'Major Disease Patient (Active)', 0),
('health', 'Lazimul Firash', 'Lazimul Firash / Bedridden (Active)', 0),
('health', 'Wafaat', 'Wafaat (Inactive)', 1),
('residential', 'Residing in <Place>', 'Residing in <Place> (Active)', 0),
('residential', 'Madresa in <Place>', 'Madresa in <Place> (Active)', 0),
('residential', 'FMB Thaali in <Place>', 'FMB Thaali in <Place> (Active)', 0),
('residential', 'Moved for Job', 'Moved for Job (Inactive)', 1),
('residential', 'Moved for Studies', 'Moved for Studies (Inactive)', 1),
('residential', 'Moved after Marriage', 'Permanently moved after Marriage (Inactive)', 1),
('residential', 'Permanently Migrated', 'Permanently Migrated (Inactive)', 1),
('residential', 'Unknown or Not Traceable', 'Unknown or Not Traceable (Inactive)', 1),
('residential', 'Moved Permanently but not taken transfer', 'Moved Permanently but not taken transfer (Inactive)', 1),
('residential', 'Permanently moved but ITS not Transferred', 'Permanently moved but ITS not Transferred (Inactive)', 1),
('residential', 'Permanently Moved and ITS also Transferred', 'Permanently Moved and ITS also Transferred (Inactive)', 1)
ON DUPLICATE KEY UPDATE status_label = VALUES(status_label), is_inactive_trigger = VALUES(is_inactive_trigger);
```

Manageable later at **Admin → Manage Status Options**.

### 3c. Bootstrap `login` accounts

Password hashing is plain `MD5(password)`, no salt (see `Accounts.php`,
`md5($password)` at the login-check call sites — nothing to configure, this is just
how the comparison works). Role numbers are fixed by the app — every controller
checks `$_SESSION['user']['role']` against a literal integer, there is no
role-name-to-ID lookup table involved in access control, so you must use exactly
this scheme:

| role | meaning |
|---|---|
| 1 | Admin |
| 2 | Amilsaheb |
| 3 | Jamaat/Anjuman |
| 4–15 | the 12 Umoor departments, in this order: Deeniyah, Talimiyah, Marafiq Burhaniyah, Maaliyah, Mawarid Bashariyah, Dakheliyah, Kharejiyah, Iqtesadiyah, FMB, Al-Qaza, Al-Amlaak, Al-Sehhat |
| 16 | Sector/group login (naming is jamaat-specific, optional — skip unless the new jamaat wants an equivalent grouping feature) |
| 0 | default role for self-registered members, pending admin promotion |

Bootstrap set — username = password here (MD5-hashed) purely so you can log in once;
**change every one of these passwords via the app's own password-change screen before
real launch**, do not leave them like this:

```sql
INSERT INTO `login` (`username`, `password`, `role`, `hof`, `active`) VALUES
  ('admin',                  MD5('admin'),                  1,  0, 1),
  ('amilsaheb',              MD5('amilsaheb'),              2,  0, 1),
  ('jamaat',                 MD5('jamaat'),                 3,  0, 1),
  ('UmoorDeeniyah',          MD5('UmoorDeeniyah'),          4,  0, 1),
  ('UmoorTalimiyah',         MD5('UmoorTalimiyah'),         5,  0, 1),
  ('UmoorMarafiqBurhaniyah', MD5('UmoorMarafiqBurhaniyah'), 6,  0, 1),
  ('UmoorMaaliyah',          MD5('UmoorMaaliyah'),          7,  0, 1),
  ('UmoorMawaridBashariyah', MD5('UmoorMawaridBashariyah'), 8,  0, 1),
  ('UmoorDakheliyah',        MD5('UmoorDakheliyah'),        9,  0, 1),
  ('UmoorKharejiyah',        MD5('UmoorKharejiyah'),        10, 0, 1),
  ('UmoorIqtesadiyah',       MD5('UmoorIqtesadiyah'),       11, 0, 1),
  ('UmoorFMB',               MD5('UmoorFMB'),               12, 0, 1),
  ('UmoorAlQaza',            MD5('UmoorAlQaza'),            13, 0, 1),
  ('UmoorAlAmlaak',          MD5('UmoorAlAmlaak'),          14, 0, 1),
  ('UmoorAlSehhat',          MD5('UmoorAlSehhat'),          15, 0, 1);
```

`roles` / `user_roles` tables: **do not bother seeding these.** Verified they play no
part in session/login authorization (that's the `login.role` integer, compared
directly in each controller) — they only back two optional fallback lookups
(Amilsaheb auto-contact in `settings_helper.php`, FMB notification recipients in
`CommonM.php::get_umoor_fmb_users()`) that degrade to "no recipients found" gracefully
when empty. Nothing in the codebase ever writes to them either.

### 3d. Once you can log in as admin, default every imported member's status

If you're bulk-importing member data, set the baseline status fields (skip if you're
starting with zero members and will add them one by one through the UI, which sets
these per-form already):

```sql
UPDATE `user`
SET deeni_status = 'Normal',
    health_status = 'Healthy',
    residential_status = 'Residing in <Place>';
```

(`activity_status` recalculates automatically — `trg_auto_activity_status`, a
`BEFORE UPDATE` trigger on `user`, checks the new values against `status_options`.)

## 4. Code changes — 4 files, all hostname-keyed

Every file below already has an `alezz.tanzeem.in` block. **Add your new domain as one
more branch, right next to it** — do not replace the alezz block, every existing
jamaat's branch must stay working.

**Ordering rule, applies to every file in this section:** if your new domain is a
subdomain of an already-branched domain (e.g. adding `karachi.tanzeem.in` when
`tanzeem.in` is already branched), your new, more specific check **must be placed
above** the shorter one. `strpos($host, 'tanzeem.in')` matches
`karachi.tanzeem.in` too, since it's just a substring search — a generic check placed
first will silently swallow your new subdomain's requests and route them to the
wrong database. If your new domain is a brand new apex domain (not a subdomain of
anything already listed), ordering doesn't matter — put it anywhere in the chain.

---

### File 1 — `application/config/production/database.php`

Picks which DB credentials to use. Current state (lines ~86-105):

```php
$currentHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$defaultDbUser = 'kharjam1_kharjamaat';
$defaultDbPass = 'khar@2024';
$defaultDbName = 'kharjam1_kharjamaat';

if (strpos($currentHost, 'alezz.tanzeem.in') !== false) {
	// NOTE: must come before the generic 'tanzeem.in' check below, since
	// 'alezz.tanzeem.in' contains 'tanzeem.in' as a substring.
	$defaultDbUser = 'kharjam1_alezz';
	$defaultDbPass = 'kharjam1_alezz'; // TODO: set the real DB password before deploying
	$defaultDbName = 'kharjam1_alezz';
} elseif (strpos($currentHost, 'tanzeem.in') !== false) {
	$defaultDbUser = 'kharjam1_tanzeem';
	$defaultDbPass = 'kharjam1_tanzeem';
	$defaultDbName = 'kharjam1_tanzeem';
} elseif (strpos($currentHost, 'jamalipoona.in') !== false) {
	$defaultDbUser = 'kharjam1_jamalipoona';
	$defaultDbPass = 'kharjam1_jamalipoona';
	$defaultDbName = 'kharjam1_jamalipoona';
}
```

**Insert a new `elseif` branch.** If your new domain is *not* a subdomain of anything
already here, add it anywhere in the chain, e.g. right after the `alezz.tanzeem.in`
block:

```php
if (strpos($currentHost, 'alezz.tanzeem.in') !== false) {
	// ...existing alezz block, unchanged...
} elseif (strpos($currentHost, 'NEWDOMAIN') !== false) {
	$defaultDbUser = 'kharjam1_newjamaat';
	$defaultDbPass = 'REAL_DB_PASSWORD_HERE';
	$defaultDbName = 'kharjam1_newjamaat';
} elseif (strpos($currentHost, 'tanzeem.in') !== false) {
	// ...existing tanzeem.in block, unchanged...
```

If your new domain **is** a subdomain of one already listed (e.g. `.tanzeem.in`),
put your new `elseif` **above** that shorter check instead, exactly the way the
`alezz.tanzeem.in` block sits above the plain `tanzeem.in` one — copy that
positioning.

---

### File 2 — `application/config/production/config.php`

Picks `base_url`. Current state (lines ~26-37):

```php
$currentHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
if (strpos($currentHost, 'alezz.tanzeem.in') !== false) {
    // NOTE: must come before the generic 'tanzeem.in' check below, since
    // 'alezz.tanzeem.in' contains 'tanzeem.in' as a substring.
    $config['base_url'] = 'https://alezz.tanzeem.in/';
} elseif (strpos($currentHost, 'tanzeem.in') !== false) {
    $config['base_url'] = 'https://tanzeem.in/';
} elseif (strpos($currentHost, 'jamalipoona.in') !== false) {
    $config['base_url'] = 'https://jamalipoona.in/';
} else {
    $config['base_url'] = 'https://kharjamaat.in/';
}
```

**Insert a new `elseif` branch**, same ordering rule as File 1:

```php
if (strpos($currentHost, 'alezz.tanzeem.in') !== false) {
    // ...existing alezz block, unchanged...
} elseif (strpos($currentHost, 'NEWDOMAIN') !== false) {
    $config['base_url'] = 'https://NEWDOMAIN/';
} elseif (strpos($currentHost, 'tanzeem.in') !== false) {
    // ...existing tanzeem.in block, unchanged...
```

`else` at the bottom is kharjamaat.in's own fallback — never touch it, it must
remain last.

---

### File 3 — `application/config/notifications.php`

This file is loaded by **every** jamaat and has no per-jamaat database override for
these 3 recipient lists — it's currently a simple `if (alezz) {...} else {...}`, where
`else` covers every jamaat that isn't alezz (i.e. kharjamaat.in + tanzeem.in +
jamalipoona.in all share the `else` branch today). Current state (lines ~13-35):

```php
$currentHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

if (strpos($currentHost, 'alezz.tanzeem.in') !== false) {
	// TODO: replace with alezz jamaat's actual admin/Amilsaheb WhatsApp numbers and email.
	$config['admin_whatsapp_recipients'] = [];
	$config['amilsaheb_appointments_digest_recipients'] = [];
	$config['amilsaheb_event_reminder_recipients'] = [];
} else {
	// Admin WhatsApp recipients (digits or +91...); optional.
	// If empty, code may fallback to looking up admin mobiles by email.
	$config['admin_whatsapp_recipients'] = [
		'+919372415351',
		'+919820150617',
		'+919820291857',
	];

	// Amil Saheb appointment digest
	$config['amilsaheb_appointments_digest_recipients'] = ['kharamilsaheb@gmail.com'];

	// Amil Saheb event reminders (Miqaat Public Event / Kaaraj Private Event)
	// If empty, code falls back to `amilsaheb_appointments_digest_recipients`.
	$config['amilsaheb_event_reminder_recipients'] = ['kharamilsaheb@gmail.com'];
}
```

**You must convert the trailing `else` into an `elseif` + new `else`** — otherwise
your new jamaat silently falls into Khar's `else` branch and its notifications go to
Khar's admins. Replace the whole block with:

```php
$currentHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

if (strpos($currentHost, 'alezz.tanzeem.in') !== false) {
	// TODO: replace with alezz jamaat's actual admin/Amilsaheb WhatsApp numbers and email.
	$config['admin_whatsapp_recipients'] = [];
	$config['amilsaheb_appointments_digest_recipients'] = [];
	$config['amilsaheb_event_reminder_recipients'] = [];
} elseif (strpos($currentHost, 'NEWDOMAIN') !== false) {
	// TODO: replace with the new jamaat's actual admin/Amilsaheb WhatsApp numbers and email.
	$config['admin_whatsapp_recipients'] = ['+91XXXXXXXXXX'];
	$config['amilsaheb_appointments_digest_recipients'] = ['amil@newjamaat.example.com'];
	$config['amilsaheb_event_reminder_recipients'] = ['amil@newjamaat.example.com'];
} else {
	// Admin WhatsApp recipients (digits or +91...); optional.
	// If empty, code may fallback to looking up admin mobiles by email.
	$config['admin_whatsapp_recipients'] = [
		'+919372415351',
		'+919820150617',
		'+919820291857',
	];

	// Amil Saheb appointment digest
	$config['amilsaheb_appointments_digest_recipients'] = ['kharamilsaheb@gmail.com'];

	// Amil Saheb event reminders (Miqaat Public Event / Kaaraj Private Event)
	// If empty, code falls back to `amilsaheb_appointments_digest_recipients`.
	$config['amilsaheb_event_reminder_recipients'] = ['kharamilsaheb@gmail.com'];
}
```

Don't leave these arrays empty (unlike the temporary alezz placeholder) if you have
real numbers/emails at onboarding time — fill them in directly here so notifications
work from day one.

---

### File 4 — `.github/workflows/deploy.yml`

Ships code to each jamaat's docroot via FTP on every push to `main`. Current state
already has 3 steps (public_html, tanzeem.in, alezz.tanzeem.in), each identical except
`name` and `server-dir`. Add a 4th step, copy-pasting the pattern exactly:

```yaml
      # Deploy to NEWDOMAIN
      - name: Deploy to NEWDOMAIN
        uses: SamKirkland/FTP-Deploy-Action@v4.3.5
        with:
          server: ${{ secrets.FTP_SERVER }}
          username: ${{ secrets.FTP_USERNAME }}
          password: ${{ secrets.FTP_PASSWORD }}
          protocol: ftp
          port: 21
          local-dir: ./
          server-dir: /NEWDOMAIN/
          exclude: |
            **/.git*
            **/.git*/**
            **/node_modules/**
            .github/**
            README.md
```

Paste this as a new step at the end of the `steps:` list (after the alezz.tanzeem.in
step), keeping the same indentation as the other steps (6 spaces before `- name:`).
`server-dir` must exactly match the subdomain's docroot folder name from §1 step 1.

---

### Optional / legacy — only touch if you've confirmed it's actually used

`.cpanel.yml` (repo root) and a `deploy.sh` script living in cPanel File Manager
appear to be a leftover/parallel deploy mechanism from before the GitHub Actions FTP
workflow (File 4 above) was set up — the FTP workflow is what's actually live for
this project as of the alezz onboarding. If you've confirmed with whoever manages
hosting that these aren't used, skip them. If you want to keep them in sync anyway
(low cost, in case something still triggers them):

`.cpanel.yml` — add one more rsync line, same pattern as the existing ones:
```yaml
    - /bin/rsync -av --delete --exclude='.git' --exclude='.env' ./ /home3/kharjam1/NEWDOMAIN/
```

`deploy.sh` (cPanel File Manager) — add a new `LIVE` variable and a matching
`rsync` block, following the existing `LIVE1`/`LIVE2`/`LIVE3` pattern already in that
file.

## 5. `.env`

Deployment explicitly excludes `.env` from both the FTP workflow's `exclude:` list
and (if used) the rsync `--exclude`. It is never uploaded, and never deleted by the
sync. You must place one manually in the new docroot:
`/home3/kharjam1/NEWDOMAIN/.env`. If SMTP/WhatsApp credentials are the same across all
jamaats, just copy the existing `.env` from another jamaat's docroot over FTP/File
Manager. If this jamaat needs its own sender email or WhatsApp API token, edit the
copy before placing it — those are process-wide env vars read at bootstrap, there is
no per-hostname branching for them anywhere in code.

## 6. Deploy

Commit the files changed in §4 (and §4's optional section if you touched those too),
push to `main`. GitHub Actions fires automatically and FTPs the full app to every
`server-dir` configured in `deploy.yml`, including the new one you just added. The
first request to the new domain replaces cPanel's placeholder `index.php`
automatically (same filename, plain FTP overwrite) — no manual cleanup needed there.

## 7. First login and branding

1. Visit the new domain, log in with a bootstrap account from §3c (e.g. `admin` /
   `admin`).
2. Go to **Admin → Preferences**, fill in/confirm everything from §3a's `app_settings`
   list through the UI instead of SQL from now on.
3. Go to **Admin → Manage Status Options**, confirm the 3 residential "in `<place>`"
   rows read correctly.
4. Change every bootstrap account's password (§3c) to something real via the app's
   own change-password flow — they're deliberately weak (username = password) for
   first access only, and sit in a `login` table reachable by anyone who knows the
   username.

## 8. Known gaps — not yet generic, needs a decision before it matters

- **CCAvenue payment gateway** — `Payment.php`'s `ccavenue_takhmeen()` method has the
  merchant ID/working key/access code hardcoded inline
  (`$working_key = '3192DCC09548EAC34B7492AD528DEABB';` etc.), not read from
  `.env`/`application/config/payment.php` despite that plumbing already existing
  there unused. Fine while every jamaat shares one merchant account; the day any
  jamaat needs its own account, this needs a real fix — hostname- or DB-driven
  credential selection, not another hardcoded branch.
- **`its_sabeel_match`** (`application/models/MemberStatusM.php`) — a Khar-specific
  concept: cross-checking a member's presence in the official ITS jamaat records
  against Khar's own separate local Sabeel/mosque congregation roll, producing values
  like `its_sabeel_both_khar`. Only relevant if the new jamaat has an equivalent
  second membership list to reconcile against; otherwise it's harmless dead weight —
  the field just stays unset.

---

*Last updated after onboarding `alezz.tanzeem.in`. If you hit something not covered
here during the next onboarding, add it — that's the point of this file.*
