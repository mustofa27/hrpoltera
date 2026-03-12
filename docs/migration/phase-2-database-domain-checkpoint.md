# Phase 2 — Database & Domain Base Checkpoint

Date: 2026-03-12

## Objective
Port legacy schema and core domain model layer into Laravel 12 with parity-first behavior and passing migration/test gates.

## Deliverables Completed
- Legacy migration set imported and running on Laravel 12.
- Default skeleton users migration removed to prevent schema conflict with legacy users structure.
- Legacy core models imported to `app/Models` and namespace-aligned to `App\Models\*`.
- Relationship namespace strings updated across imported models.
- Parity fixes applied:
  - `disposisis` columns aligned to legacy controller expectations (`id_surat`, `id_user_asal`, `id_user_tujuan`).
  - `jabatan_user_units.surat_internal_id` foreign table corrected to `surat_internals`.
  - `User` model fillable aligned to legacy column `nama`.
- Added schema-level feature test for critical tables/columns/foreign keys.

## Files Changed (Phase 2 scope)
- `database/migrations/*` (legacy migration set added)
- `database/migrations/0001_01_01_000000_create_users_table.php` (removed)
- `app/Models/*.php` (legacy domain models imported + namespace adjustments)
- `tests/Feature/SchemaMigrationTest.php`

## Verification Commands & Results
Executed with profile activation:
- `source ~/.bash_profile && php artisan migrate:fresh` -> PASS
- `source ~/.bash_profile && php artisan test` -> PASS
  - `Tests\Feature\SchemaMigrationTest` -> PASS
- Combined verification rerun after parity fixes:
  - `source ~/.bash_profile && php artisan migrate:fresh && php artisan test` -> PASS

## Security/Compatibility Notes
- No SSO secret handling logic added in this phase.
- Schema is now parity-aligned for Phase 4 module migrations.
- Some legacy migrations keep historical `down()` methods with no-op column rollback; acceptable for parity, can be hardened post-cutover.

## Phase 2 Exit Decision
- **GO** for Phase 3 (Mandatory SSO Authentication Integration).
