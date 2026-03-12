# Phase 0 — Discovery & Mapping (Legacy SITPOLTERA -> Laravel 12 HRPOLTERA)

Date: 2026-03-12

## Scope
- Legacy source: `/Applications/MAMP/htdocs/sitpoltera`
- Target app: `/Applications/MAMP/htdocs/hrpoltera`
- Migration objective: preserve behavior parity while replacing local authentication with SSO (`https://sso.poltera.ac.id`).

## Legacy Inventory

### Routes
- Web routes file: `routes/web.php`
- API routes file: `routes/api.php`
- Broadcast channels: `routes/channels.php`

Observed web surface:
- Root + custom endpoints for Surat/Disposisi/Cetak/Monitor/Import/Ganti Password
- `Auth::routes()` enabled (local login/register/password reset flow)
- Resource controllers:
  - `aktivitas_harians`, `jabatans`, `golongans`, `pangkats`, `kampuses`, `shifts`, `unit_kerjas`, `pegawais`,
    `jabatan_user_units`, `users`, `absensis`, `lupa_absens`, `liburs`, `roles`, `cutis`, `tugas_dinas`

Observed API surface:
- Public endpoints: `POST /api/register`, `POST /api/login`
- Token-guarded endpoints: disposisi/surat/user operations via `auth:api`

### Controllers
- Total discovered controllers (excluding base):
  - `AbsensiController`, `AktivitasHarianController`, `ApiController`, `CutiController`, `GolonganController`,
    `HariLiburController`, `HomeController`, `JabatanController`, `JabatanUserUnitController`, `KampusController`,
    `LupaAbsenController`, `PangkatController`, `PegawaiController`, `RoleController`, `ShiftController`,
    `TipeUserController`, `TugasDinasController`, `UnitKerjaController`, `UserController`
- Auth scaffold controllers present:
  - `Auth/LoginController`, `Auth/RegisterController`, `Auth/ForgotPasswordController`,
    `Auth/ResetPasswordController`, `Auth/VerificationController`

### Models / Domain Classes
- Legacy uses `app/*.php` model pattern (not `app/Models`):
  - `User`, `Pegawai`, `Absensi`, `Cuti`, `Surat`, `Disposisi`, `LupaAbsen`, `AktivitasHarian`,
    `Jabatan`, `UnitKerja`, `Pangkat`, `Golongan`, `Shift`, `Role`, `Kampus`, `TugasDinas`, etc.

### Middleware/Auth Kernel
- Middleware aliases include `auth`, `guest`, `throttle`, etc. (modern alias style already)
- API group uses `throttle:api` + bindings
- Auth guards in config:
  - `web` (session)
  - `api` (`token` driver, user provider)

### Database Migrations
- Legacy migration set includes core HR modules:
  - users, jabatan, unit_kerjas, tipe_users, surat/disposisi, pegawais, absensis, cutis, lupa_absens,
    aktivitas_harians, shifts, roles, tugas_dinas, and related reference tables.
- Uses older migration style (`bigIncrements`, string date fields, legacy naming conventions)

### Views & Frontend
- Blade directories for modules: `absensis`, `aktivitas_harian`, `cutis`, `pegawai`, `role`, `shifts`, `units`, `user`, etc.
- Auth views present: `resources/views/auth/*` (login/register/password/verify)
- Layout tightly coupled to authenticated user and role checks (`Auth::user()` in sidebar/menu)
- Frontend build is Laravel Mix + Vue 2 + Bootstrap 4 (`webpack.mix.js`, `resources/js/app.js`, `resources/sass/app.scss`)

### Packages (Legacy)
- PHP:
  - `laravel/framework:^12.0`
  - `laravel/ui:^4.6`
  - `maatwebsite/excel:^3.1`
  - `barryvdh/laravel-dompdf:^3.1`
  - `doctrine/dbal:^3.8`
- JS:
  - `laravel-mix:^4`, `vue:^2.5`, `bootstrap:^4.1`, `axios:^0.18`, `jquery`, `popper.js`

## Auth Touchpoints To Remove/Replace (SSO)

### Direct local auth usage
- `Auth::routes()` in `routes/web.php`
- Auth controllers and auth blades (`login/register/password reset`)
- `PegawaiController@gantipassword` + `/gantipassword` endpoints
- Sidebar/logout links expecting local `route('login')` and `route('logout')`

### API token auth dependencies
- `config/auth.php` defines `api` token guard
- `ApiController@login` issues `api_token`; `ApiController@register` creates local credentials
- API endpoints protected by `auth:api`

### User schema impacts
- `users` table currently stores local password + token fields (`password`, `api_token`)
- App logic relies on local profile fields stored in users table
- Affiliation-like organizational fields currently local (`unit_kerja_id`, hierarchy references), must become SSO-authoritative read-only where mapped.

## Dependency Compatibility Matrix (Phase 0 decision)

| Component | Current | Decision | Rationale |
|---|---|---|---|
| Laravel framework | ^12.0 | Keep | Already target baseline. |
| laravel/ui | ^4.6 | Remove in Phase 5 | Local auth scaffolding conflicts with SSO-only UX. |
| maatwebsite/excel | ^3.1 | Keep (conditional) | Needed by Pegawai import and attendance exports. |
| barryvdh/laravel-dompdf | ^3.1 | Keep (conditional) | Needed by PDF export flows. |
| doctrine/dbal | ^3.8 | Remove if unused | Keep only when schema changes require DBAL features. |
| Laravel Mix + Vue2 + Bootstrap4 stack | mix4/vue2/bootstrap4 | Replace incrementally | Laravel 12 default is Vite; migrate module-by-module to avoid breakage. |
| API token guard (`auth:api`) | token driver | Replace | Move to SSO-backed session / sanctioned API auth strategy. |

## Migration Map (Old -> Target Plan)

### Authentication routes
- `GET /login` -> `GET /auth/sso/redirect`
- `POST /login` -> removed (backend OAuth token exchange in callback)
- `POST /logout` -> `POST /auth/sso/logout`
- `POST /register` (web) -> removed
- `password/*` routes -> removed

### New mandatory SSO routes (to add in Phase 3)
- `GET /auth/sso/redirect` -> `SsoAuthController@redirect`
- `GET /auth/sso/callback` -> `SsoAuthController@callback`
- `POST /auth/sso/logout` -> `SsoAuthController@logout`
- `POST /auth/sso/backchannel/logout` -> `SsoBackchannelController@logout`

### Existing business routes
- Preserve existing URIs where feasible for parity:
  - `/`, `/absensis*`, `/aktivitas_harians*`, `/cutis*`, `/surat*`, `/disposisi*`, `/tugas_dinas*`, `/users*`, etc.
- Update controller namespace imports and route declarations to Laravel 12 style (FQCN) during module migration.

## Module Priority (Core-first)
1. Authentication foundation (SSO) and session lifecycle
2. Attendance/Absensi
3. Surat + Disposisi
4. Cuti
5. Aktivitas Harian
6. Master data (`jabatan`, `golongan`, `pangkat`, `unit_kerja`, `role`, etc.)

## Risk Register

| ID | Risk | Impact | Mitigation |
|---|---|---|---|
| R1 | Legacy local-auth assumptions are spread across blades/controllers | High | Build centralized SSO session helper + remove local auth routes in controlled phase. |
| R2 | Affiliation fields currently editable locally | High | Enforce server-side read-only policy and sync from SSO userinfo only. |
| R3 | API currently depends on `auth:api` token guard | High | Define migration path for API auth (session for first-party + later token strategy if needed). |
| R4 | Legacy JS stack (Mix/Vue2) diverges from Vite defaults | Medium | Run compatibility bridge first; migrate assets module-by-module. |
| R5 | Data type/constraint drift in old migrations | Medium | Rebuild schema with modern migration syntax and constraint validation in Phase 2. |
| R6 | Backchannel logout must return quickly and be signature-safe | High | Implement dedicated lightweight endpoint with HMAC + timestamp checks and async-safe session invalidation. |
| R7 | Secret leakage risk in frontend | Critical | Ensure `SSO_CLIENT_SECRET` only used in backend services and never exposed to JS/env-injected client bundles. |

## Assumptions
- SSO server supports OAuth Authorization Code and provides stable `sub` for user identity.
- SSO userinfo includes authoritative affiliation fields needed by this app.
- Existing DB data can be mapped to `sso_sub` with a deterministic backfill strategy.
- Existing module URLs should remain stable unless explicitly replaced.

## Phase Exit Criteria (Phase 0)
- Inventory completed: routes/controllers/models/middleware/migrations/views/assets/packages.
- Auth touchpoints identified for replacement with SSO.
- Compatibility matrix and prioritized module order documented.
- Risks and assumptions documented.
