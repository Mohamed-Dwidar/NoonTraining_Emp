# Project Progress

> Loaded into every session via CLAUDE.md — keep it short and current.
> Edit module sections in place. Session log: last 10 entries only (git has the rest).
> Cross-module connections live in CLAUDE.md → "Dependencies map" — not repeated here.

---

## Current status
- **Working on:** Month handling across payroll / students / reports. Last commits (2026-10-12 → 10-14): "exported file for students", "payroll by month", "months fix" — student commission now counts students by `students.month` (not `payment_date`), shared `layouts/month_select` dropdown replaced `<input type="month">`, `employee_commissions.student_id` renamed to `employee_id`.
- **Branch / working tree:** `main`, clean except the new memory files (CLAUDE.md, docs/).
- **Next up:** [to confirm with owner] (?). Owner still has to confirm the "(?)" items and "(proposed)" conventions in CLAUDE.md / this file, and supply project description + planned modules (none recorded yet).
- **Known issues:**
  - Git tracks **69 case-variant duplicate paths** (e.g. `ReportModule/app` + `App`, `AdminModule/Database` + `database`, `ReportModule/views/Admin` + `admin`). macOS hides this; on a case-sensitive Linux server, two copies can diverge or the wrong one load. Needs a cleanup decision.
  - `permission` middleware alias is registered only in `app/Http/Kernel.php` (Laravel 10 style, with a lowercase `\app\...` namespace); Laravel 12 uses `bootstrap/app.php`, so the alias is probably not active (?). Only used by an empty route group in ReportModule.
  - ReportModule `user/reports` group uses `auth:user` — no such guard (empty group, harmless for now).
  - Dead/leftover code (?): `ReportUserController` + `CourseExport`/`CourseRegExport`/`LogExport` (course-system leftovers, no routes); `EmployeeComission` model (misspelt duplicate of `EmployeeCommission`); `employees.stu_commission` still validated/edited though commission now comes from CommissionModule; `app/Helpers/FcmHelper.php`.
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
- **Pending:** Not enforced on any live route; middleware alias likely unregistered (see Known issues). No UI.

### Layout
- **Files:** `Modules/LayoutModule/resources/views/` — `layouts/home_layout` (RTL), `layout_main`, `header`, `footer`, `flash`, `month_select`, `custom_pagination`; `admin/nav`, `admin/modals`, `employee/nav`
- **How it works:** Shared Blade layout and navigation for both portals; theme assets in `public/admin-assets/`.

### Branch
- **Files:** `Modules/BranchModule/` — `BranchAdminController`, `Branch` model, views incl. `work_regulations*`
- **How it works:** CRUD; `type` = `normal` | `training`. Each branch stores work regulations that employees can view/print (if `employees.can_view_work_regulations`).

### Department
- **Files:** `Modules/DepartmentModule/` — `DepartmentAdminController`, `Department` model
- **How it works:** CRUD; linked to branch (`departments-by-branch` AJAX in Employee forms).

### Employee
- **Last updated:** 2026-10-08
- **Files:** `Modules/EmployeeModule/` — `EmployeeAdminController`, `EmployeeModuleController` (employee portal), `EmployeeService`, models `Employee`, `EmployeeCommission`
- **How it works:** Admin CRUD + status change + commission edit. Fields: salary, monthly working days, daily hours, status (`active`, `on_leave`, …), `hired_at`/`contract_ends_at`/`terminated_at`. Per-employee commission overrides synced into `employee_commissions` (only for training branches (?)). Employee portal: dashboard, my info, change password, work regulations (+PDF).
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
- **How it works:** CRUD of monthly deductions; can be linked to a Violation — amount auto-resolved from the violation's repeat tiers (`violation-amount` AJAX). Triggers payroll recalc.

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
- **Last updated:** 2026-10-12
- **Files:** `Modules/StudentModule/` — `StudentAdminController`, `Employee/StudentEmployeeController`, `StudentService`, `App/Imports/StudentImport.php`, `Exports/Students*Export.php`
- **How it works:** Student registrations (course, amounts, payment, `national_id`, `commission_id`, `month`) attributed to the registering employee. Admin: CRUD, Excel import/template, export. Employee portal: list/edit/delete/import own students (no create route despite `Employee/create` view (?)).
- **Pending:** `payment_date` is a free-text string (Report guards it with REGEXP).

### Payroll
- **Last updated:** 2026-10-14
- **Files:** `Modules/PayrollModule/` — `PayrollAdminController`, `PayrollModuleController` (employee), `PayrollService`, `PayrollRepository`, views `payslip_print`, `details_print`
- **How it works:** One row per employee + `YYYY-MM`. Opening the payroll page (per branch/month) recalculates every active/on-leave employee. `total = max(0, basic − (deductions + leave_days × daily_salary) + bonuses + students_commission)`. Commission = students of that `month` × (employee override ?? default rate; percentage applies to `paid_amount`). Payslip + details views and PDF for admin and employee.
- **Decisions:**
  - Recalc on read, not stored snapshots — because inputs change often; means past months change if rates/salary change (?).
  - Commission by `students.month` instead of `payment_date` — commit "payroll by month" (old logic left commented).
- **Pending:** `create`/`edit` views exist but no routes (?). No month locking/closing.

### Setting
- **Files:** `Modules/SettingModule/` — `SettingAdminController`, `Setting`, `Country` models, seeder
- **How it works:** Key/value settings edited in one form (e.g. default monthly working days / daily hours).

### Report
- **Last updated:** 2026-10-14
- **Files:** `Modules/ReportModule/` — `ReportAdminController`, `ReportService`, views `salary_report`, `employee_performance`, `employee_drilldown`
- **How it works:** Salary report (per month/branch) and employee performance for training-branch staff: distinct students (by `national_id`), admissions, revenue (`paid_amount`), rankings, 6-month trend, drill-down.
- **Pending:** Clean up legacy `User/ReportUserController` and course exports (?).

---

## Session log

### 2026-10-17 — Initial project memory setup
- **Changed:** `CLAUDE.md`, `docs/progress.md` — filled from codebase exploration (no code changes)
- **Why:** Give each session reliable project context
- **Affects:** —
- **Commit:** (not committed yet)
