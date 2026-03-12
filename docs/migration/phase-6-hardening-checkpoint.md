# Phase 6 — Hardening & Authorization Checkpoint

## 1) What changed
- Performed targeted hardening on high-risk approval/deletion endpoints to prevent unauthorized actions by unrelated authenticated users.
- `LupaAbsenController` hardening:
  - only assigned `atasan_id` can approve/reject requests
  - requester can only delete own request while status is `requested`
  - approval process wrapped in DB transaction to avoid partial state updates
- `CutiController` hardening:
  - atasan validation now requires real atasan relationship (`pegawais.atasan_langsung_id = auth user`)
  - pejabat validation requires either atasan relationship or `adminpusat`
  - pejabat validation blocked until atasan decision is made
- `TugasDinasController` hardening:
  - deletion restricted to assigned owner (`user_id`) or `adminpusat`
- Added regression tests that explicitly verify forbidden access for non-authorized users.

## 2) Files touched
- `app/Http/Controllers/LupaAbsenController.php`
- `app/Http/Controllers/CutiController.php`
- `app/Http/Controllers/TugasDinasController.php`
- `tests/Feature/Phase5HardeningTest.php`

## 3) Commands run + results
- `source ~/.bash_profile && php artisan test`
  - Initial run: failed due SQL ambiguity (`id` column) in new Cuti authorization join query.
  - Fix applied: qualified `cutis.id` in join-filter checks.
- `source ~/.bash_profile && php artisan test` (rerun)
  - Result: **PASS** (28 tests passed, 157 assertions).

## 4) Risks / blockers
- Role-based authorization is still heuristic-based (`atasan` relation and `adminpusat` username), because dedicated role/permission middleware has not yet been introduced.
- Functional hardening is complete for currently migrated thin-slice endpoints; broader policy centralization can be a future improvement.
- No blocker for production hardening continuation.

## 5) Next phase plan
- Proceed to release-readiness pass:
  - centralize authorization rules into policy/gate layer
  - tighten validation consistency and error UX
  - smoke-test critical workflows against real SSO sandbox
  - prepare deployment/runbook checklist.

## 6) GO / NO-GO
- **GO** — Phase 6 hardening objectives are complete and validated.
