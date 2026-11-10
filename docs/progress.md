# Project Progress

> Loaded into every session via CLAUDE.md — keep it short and current.
> Edit module sections in place. Session log: last 10 entries only (git has the rest).
> Cross-module connections live in CLAUDE.md → "Dependencies map" — not repeated here.

---

## Current status
- **Working on:** Enforcing Controller → Service → Repository in every module (2026-11-10). Done: no controller or service queries models directly anymore (rule in CLAUDE.md → Conventions). Still waiting on the owner: should text dates like `02/08/2026` be read as day/month (see Student → Pending)?
- **Branch / working tree:** `main`; through c63ee4b committed. Uncommitted: layering refactor + CLAUDE.md/progress.md updates.
- **Next up:** Answer the date-format question for imports. Owner confirmed project name, keep spatie, fix permission alias, drop FcmHelper, drop Vite/Tailwind. Still open: the other "(?)" items, the "(proposed)" conventions, and planned modules (none recorded yet).
- **Known issues:**
  - Git tracks **69 case-variant duplicate paths** (e.g. `ReportModule/app` + `App`, `AdminModule/Database` + `database`, `ReportModule/views/Admin` + `admin`). macOS hides this; on a case-sensitive Linux server, two copies can diverge or the wrong one load. Needs a cleanup decision.
  - `app/Http/Kernel.php` is dead (Laravel 12 binds the framework kernel). Its `permission` entry was never active; now registered in `bootstrap/app.php` (2026-10-18).
  - ReportModule `user/reports` group uses `auth:user` — no such guard (empty group, harmless for now).
  - Dead/leftover code (?): `ReportUserController` + `CourseExport`/`CourseRegExport`/`LogExport` (course-system leftovers, no routes); `EmployeeComission` model (misspelt duplicate of `EmployeeCommission`); `employees.stu_commission` still validated/edited though commission now comes from CommissionModule.
  - No real tests (only Laravel example tests).

---

## Modules

### Admin
- **Last updated:** 2026-09-15 (?)
- **Files:** `Modules/AdminModule/` — `AdminAuthController`, `AdminModuleController` (dashboard), `Admin` model, `AdminService`
- **How it works:** Admin profile is the `userable` of a `User`. Login form at `/admin`, but the form posts to `UserAuthController::login`. Change-password page.
- **Pending:** —

### User (auth)
- **Last updated:** 2026-05 (?)
- **Files:** `Modules/UserModule/` — `UserAuthController`, `User` model (roles/permissions helpers), `UserAdminModuleController`
- **How it works:** Single `users` table for both portals. Login checks credentials then logs into the `admin` or `employee` guard by `userable_type`; blocks suspended/resigned/terminated/deleted employees. `/login` is the shared login route.
- **Pending:** `UserAdminModuleController` + `user/*` views have no routes — unused (?).

### Permission
- **Files:** `Modules/PermissionModule/` (models in both `App/Http/Models` and `Entities`), seeders for roles/permissions, `app/Http/Middleware/CheckPermission.php`
- **How it works:** Custom `roles`, `permissions`, `model_has_*`, `role_has_permissions` tables.
- **Last updated:** 2026-10-18
- **Decisions:**
  - `permission` alias registered in `bootstrap/app.php` — because Laravel 12 never loads `app/Http/Kernel.php`.
  - Keep `spatie/laravel-permission` installed even though the custom tables are used — owner's call.
- **Pending:** Not enforced on any live route yet (only the empty ReportModule `user/reports` group). `hasPermission()` ignores role-inherited permissions (`getAllPermissions()` exists if needed). No UI.

### Layout
- **Files:** `Modules/LayoutModule/resources/views/` — `layouts/home_layout` (RTL), `layout_main`, `header`, `footer`, `flash`, `month_select`, `custom_pagination`; `admin/nav`, `admin/modals`, `employee/nav`
- **How it works:** Shared Blade layout and navigation for both portals; theme assets are static files in `public/admin-assets/` (no Vite/npm build).

### Branch
- **Files:** `Modules/BranchModule/` — `BranchAdminController`, `Branch` model, views incl. `work_regulations*`
- **How it works:** CRUD; `type` = `normal` | `training`. Each branch stores work regulations that employees can view/print (if `employees.can_view_work_regulations`).

### Department
- **Files:** `Modules/DepartmentModule/` — `DepartmentAdminController`, `Department` model
- **How it works:** CRUD; linked to branch (`departments-by-branch` AJAX in Employee forms).

### Employee
- **Last updated:** 2026-10-08
- **Files:** `Modules/EmployeeModule/` — `EmployeeAdminController`, `EmployeeModuleController` (employee portal), `EmployeeService`, models `Employee`, `EmployeeCommission`
- **How it works:** Admin CRUD + status change + commission edit. Fields: salary, monthly working days, daily hours, status (`active`, `on_leave`, …), `hired_at`/`contract_ends_at`/`terminated_at`. Per-employee commission overrides synced into `employee_commissions` (only for training branches (?); via `EmployeeRepository::deleteCommissions/saveCommission`, branch via `BranchRepository`). Employee portal: dashboard, my info, change password, work regulations (+PDF).
- **Pending:** Remove/clarify `stu_commission` and `EmployeeComission` (?).

### Attendance
- **Last updated:** 2026-06-06
- **Files:** `Modules/AttendanceModule/` — `AttendanceAdminController` (index/store only), `AttendanceService`, `Attendance` model
- **How it works:** Per branch + month, creates a monthly record per employee then lets admin edit days/rate/status in bulk.
- **Pending:** Not connected to payroll (`days_late` always 0, absences come from Leaves). `month` is a `date`, unlike other modules.

### Leave
- **Files:** `Modules/LeaveModule/` — `LeaveAdminController`, `LeaveService`, `Leave` model
- **How it works:** CRUD; `days` × daily salary is deducted in payroll. Month derived from `start_date`. Triggers payroll recalc.

### Bonuse (bonuses)
- **Files:** `Modules/BonuseModule/` — `BonuseAdminController`, `BonuseService`, `Bonuse` model
- **How it works:** CRUD of monthly bonus amounts per employee; triggers payroll recalc (also for the old employee/month on update).

### Deduction
- **Files:** `Modules/DeductionModule/` — `DeductionAdminController`, `DeductionService`, `Deduction` model
- **How it works:** CRUD of monthly deductions; can be linked to a Violation — amount auto-resolved from the violation's repeat tiers (`violation-amount` AJAX → `DeductionService::violationAmount`, tiers via `ViolationRepository::getRepeats`). Triggers payroll recalc.

### Violation
- **Files:** `Modules/ViolationModule/` — `ViolationAdminController`, `ViolationService`, models `Violation`, `ViolationRepeat`
- **How it works:** Catalogue of violations with escalating penalties per repeat.

### Task
- **Files:** `Modules/TaskModule/` — `TaskAdminController`, `TaskService`, `Task` model
- **How it works:** Admin CRUD of tasks + status patch.
- **Pending:** No employee-side view (?).

### Commission
- **Last updated:** 2026-09-15
- **Files:** `Modules/CommissionModule/` — `CommissionAdminController`, `Commission` model (`type` fixed|percentage, `value`)
- **How it works:** Commission types assigned to students; employee-specific rates override defaults.

### Student
- **Last updated:** 2026-10-18
- **Import (validate-then-save):** `StudentImport::collection()` only collects rows (sheet title + Excel row number); controllers call `errors()` → if any, `back()->withErrors()` listing «sheet» + row + messages (max 50) and nothing is saved; else `save()` inserts all rows in one `DB::transaction`. Used by both Admin and Employee `importStudents`. Fully empty rows are skipped; required: name, mobile, national_id, course_name, payment_method, payment_date (valid date); amounts integer ≥ 0 (empty → 0). Each error shows the first message per field + a valid example from `StudentImport::EXAMPLES` (based on real data: 9-digit mobile starting 5, 10-digit national ID starting 1, methods كاش/حوالة/تمارا/تابي, date `02/08/2026`).
- **Decisions:**
  - Validate the whole file before saving anything, all-or-nothing — owner request, 2026-10-18.
- **Files:** `Modules/StudentModule/` — `StudentAdminController`, `Employee/StudentEmployeeController`, `StudentService`, `App/Imports/StudentImport.php`, `Exports/Students*Export.php`
- **How it works:** Student registrations (course, amounts, payment, `national_id`, `commission_id`, `month`) attributed to the registering employee. Admin: CRUD, Excel import/template, export. Employee portal: list/edit/delete/import own students (no create route despite `Employee/create` view (?)).
- **Pending:**
  - **Open question (asked owner, no answer yet):** `parseDate()` uses `Carbon::parse`, which reads text dates with slashes as m/d/Y — `02/08/2026` → 8 Feb, and `25/08/2026` is rejected. If the example means 2 August (d/m/Y), switch text parsing to `d/m/Y` (Excel date cells and `Y-m-d` text are unaffected).
  - `payment_date` is a free-text string (Report guards it with REGEXP).

### Payroll
- **Last updated:** 2026-11-10
- **Files:** `Modules/PayrollModule/` — `PayrollAdminController`, `PayrollModuleController` (employee), `PayrollService`, `PayrollRepository`, views `payslip_print`, `details_print`
- **How it works:** Details popup/PDF data comes from `PayrollService::monthDetails()` (bonuses, deductions, leaves, students, commission breakdown via each module's repository `getByEmployeeAndMonth`); employee "my payrolls" months from `monthsSinceFirstStudent()`. One row per employee + `YYYY-MM`. Opening the payroll page (per branch/month) recalculates every active/on-leave employee. `total = max(0, basic − (deductions + leave_days × daily_salary) + bonuses + students_commission)`. Commission = students of that `month` × (employee override ?? default rate; percentage applies to `paid_amount`). Payslip + details views and PDF for admin and employee.
- **Decisions:**
  - Recalc on read, not stored snapshots — because inputs change often; means past months change if rates/salary change (?).
  - Commission by `students.month` instead of `payment_date` — commit "payroll by month" (old logic left commented).
- **Pending:** `create`/`edit` views exist but no routes (?). No month locking/closing.

### Setting
- **Last updated:** 2026-11-10
- **Files:** `Modules/SettingModule/` — `SettingAdminController`, `Setting`, `Country` models, seeder
- **How it works:** Key/value settings edited in one form (e.g. default monthly working days / daily hours). Controller → `SettingService` (`findAll`, `create`, `updateValues`) → `SettingRepository::updateValueByKey`.

### Report
- **Last updated:** 2026-11-10
- **Files:** `Modules/ReportModule/` — `ReportAdminController`, `ReportService`, views `salary_report`, `employee_performance`, `employee_drilldown`
- **How it works:** `ReportService` holds the logic; the student queries (training-branch filter, valid `payment_date` REGEXP, distinct-student SQL) live in `StudentRepository` (`performanceByEmployee`, `performanceTotals`, `trainingRecordsByPaymentDate`), employee lookup in `EmployeeRepository::findWithTrashedByIds`. Salary report (per month/branch) and employee performance for training-branch staff: distinct students (by `national_id`), admissions, revenue (`paid_amount`), rankings, 6-month trend, drill-down.
- **Pending:** Clean up legacy `User/ReportUserController` and course exports (?).

---

## Session log

### 2026-11-10 — Enforce Controller → Service → Repository in all modules
- **Changed:** Controllers no longer touch models: `PayrollAdminController`, `PayrollModuleController` (→ `PayrollService::monthDetails`, `monthsSinceFirstStudent`), `DeductionAdminController::violationAmount` (→ `DeductionService::violationAmount`), `SettingAdminController` (→ `SettingService`); unused model imports dropped in Leave/Task/Report admin controllers. Services no longer query models: `ReportService`, `EmployeeService`, `DeductionService`, `StudentService`, `UserService`. New repository methods: `getByEmployeeAndMonth` (Bonuse/Deduction/Leave/Student), `StudentRepository::firstMonthForEmployee/duplicateNationalIdCounts/performanceByEmployee/performanceTotals/trainingRecordsByPaymentDate`, `EmployeeRepository::findWithTrashedByIds/deleteCommissions/saveCommission`, `ViolationRepository::getRepeats`, `PermissionRepository::idsByNames`, `SettingRepository::updateValueByKey`. CLAUDE.md Conventions → layering rule spelled out.
- **Why:** Owner wants the same structure in every module: controller calls service, service calls repository.
- **Affects:** Payroll, Report, Deduction/Violation, Employee/Branch, Student, User/Permission, Setting. No behaviour change intended; verified services/controllers resolve and the new paths return data (payroll details, reports, violation amount, settings).
- **Commit:** — (not committed yet)

### 2026-10-18 — Student import: validate whole file before saving
- **Changed:** `StudentModule/App/Imports/StudentImport.php` (collect → `errors()` → `save()`, per-field valid examples in error messages), `Employee/StudentEmployeeController::importStudents`, `Admin/StudentAdminController::importStudents`
- **Why:** Errors should be reported per sheet/row with nothing saved. Before, partially-filled rows were silently skipped, and an empty payment method/date (NOT NULL columns) threw a SQL error mid-file, leaving earlier rows saved.
- **Affects:** PayrollModule (only valid rows reach commission calc), ReportModule
- **Commit:** c63ee4b (follow-up doc update not yet committed)

### 2026-10-18 — Confirm memory items, fix permission alias, remove Vite/Tailwind
- **Changed:** `bootstrap/app.php` — registered `permission` → `CheckPermission`; deleted unused `app/Helpers/FcmHelper.php`; removed Vite/Tailwind: root `vite.config.js`, `resources/css`, `resources/js`, `welcome.blade.php`, every module's `vite.config.js` + `package.json` + empty `resources/assets/*` stubs, commented `module_vite(...)` lines in module stub layouts; root `package.json` now only `concurrently`; `composer dev`/`setup` scripts no longer run vite / `npm run build`; kept empty `resources/views/.gitkeep`. `CLAUDE.md` updated.
- **Why:** Owner reviewed the "(?)" items; alias was never active under Laravel 12; Vite/Tailwind were unused (UI uses static `public/admin-assets/`)
- **Affects:** PermissionModule, ReportModule (`permission:show_reports` group), Layout/all module stub layouts, dev tooling. Run `npm install` again to prune `node_modules` (not tracked) if present.
- **Commit:** c63ee4b

### 2026-10-17 — Initial project memory setup
- **Changed:** `CLAUDE.md`, `docs/progress.md` — filled from codebase exploration (no code changes)
- **Why:** Give each session reliable project context
- **Affects:** —
- **Commit:** ca65c0f "CLAUDE.md"
