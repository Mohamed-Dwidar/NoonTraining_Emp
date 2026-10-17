# Project: Noon Training HR (Hr_NoonTraining) (?)

@docs/progress.md

HR + payroll system for a training company: branches, departments, employees, monthly payroll
(deductions, bonuses, leaves, student commissions), student registrations, attendance, reports.
Two portals: **admin** (`/admin`) and **employee** (`/employee`). UI is Arabic RTL.

## Before you start (every session)
1. `docs/progress.md` is loaded above — treat it as the latest state, decisions, and cross-module dependencies.
2. Run `git log --oneline -15` to see recent changes.
3. If the task touches a module listed under "Dependencies map", check the related files before editing.

## When a task or feature is done
When a task is finished, or when I say "update progress" or run /checkpoint, update `docs/progress.md`:
- Edit the relevant module section (files, how it works, dependencies, decisions, pending) — don't just append.
- Add one entry to the session log; keep only the last 10 entries.
- Update "Current status".
Then suggest a commit message.

## Stack
- Framework: Laravel 12 / PHP ^8.2, modular via `nwidart/laravel-modules` ^12
- Database: MySQL (`DB_CONNECTION`; `.env.example` still has the sqlite default). Raw SQL uses MySQL `REGEXP`/`CONCAT`.
- Frontend: Blade + Bootstrap/jQuery admin theme (`public/admin-assets/`, select2 from CDN) loaded by LayoutModule; Vite 7 + Tailwind 4 configured but unused — root `resources/` is the stock skeleton (?)
- Auth: custom session guards `admin` / `employee` (+ default `web`), all on one `users` provider (`UserModule` `User` model, polymorphic `userable` → `Admin` | `Employee`)
- Permissions: custom roles/permissions tables in PermissionModule + `User::hasPermission()`; `spatie/laravel-permission` is installed but not used (?)
- Key packages: `prettus/l5-repository` (repositories), `maatwebsite/excel` (student import/export), `niklasravnsborg/laravel-pdf` (payslip / work-regulations PDFs), `laravel/sanctum`
- Env vars of note: `APP_*`, `DB_*`, `MAIL_*`, `QUEUE_CONNECTION`, `FILESYSTEM_DISK`, `AWS_*`

## Commands
- Dev server: `php artisan serve` (or `composer dev` = serve + queue + pail + vite)
- Assets: `npm run dev` / `npm run build`
- Migrate: `php artisan migrate` (module migrations auto-loaded); seed a module: `php artisan module:seed <Module>`
- Tests: `php artisan test` (only Laravel example tests exist)
- Clear caches: `php artisan optimize:clear`
- Enable/disable modules: `modules_statuses.json`

## Project structure
- `Modules/<Name>Module/` — one module per domain (18 modules, all enabled)
  - `App/Http/Controllers/Admin/<X>AdminController.php` — admin side; `<X>ModuleController.php` — employee side / misc
  - `App/Http/Models/` — Eloquent models (not `Entities/`; PermissionModule has both)
  - `Services/<X>Service.php` — business logic; `Repository/<X>Repository.php` — prettus `BaseRepository`
  - `routes/web.php`, `database/migrations`, `database/seeders`, `resources/views/{Admin,Employee}/`
- `Modules/LayoutModule/resources/views/` — shared layout: `layouts/home_layout` (RTL master), `admin/nav`, `employee/nav`, `layouts/flash`, `layouts/month_select`, `layouts/custom_pagination`
- `app/Http/Middleware/CheckPermission.php` — `permission:<name>` middleware (?) see Known issues
- `app/Helpers/` — `UploaderHelper`, `ApiResponseHelper`, `FcmHelper` (likely carried over from another project (?))
- `routes/web.php` — empty; all routes live in modules

## Conventions
- Layering: Controller → Service → Repository → Model. Cross-module calls go through the other module's Repository/Service (e.g. Bonuse/Deduction/Leave services call `PayrollRepository::recalculateForEmployee`).
- Models: `$guarded = []`; filtering via `scopeFilter($query, $request = [])` on the model, exposed as `Service::filter()`.
- Route names: `admin.<plural>.<action>` and `employee.<plural>.<action>`; URL prefix `admin/<singular>` / `employee/<singular>`. Always use named routes.
- Route verbs are **inconsistent**: older modules (Branch, Department, Employee, Commission) use `POST update` + `POST delete/{id}`; newer ones (Leave, Bonuse, Deduction, Violation, Task, Student) use `POST /{id}` + `DELETE /{id}`. New modules: follow the newer style (proposed).
- Validation: inline in controllers (`Validator::make` / `$request->validate`) with Arabic messages — no Form Requests. Keep that for consistency (proposed).
- Months: stored as `'YYYY-MM'` strings (`string('month', 7)`) in payrolls, leaves, deductions, bonuses, students; **attendances.month is a `date` (`YYYY-MM-01`)**. Month pickers use `@include('layoutmodule::layouts.month_select')`.
- Views: `<module>::Admin.index` etc. Folder case is inconsistent (`Admin` vs `admin`) — match the module you're in.
- Language / direction: UI text, flash and validation messages in Arabic, RTL layout; code identifiers and DB columns in English.
- Naming: DB columns snake_case; module names keep historical spellings (`BonuseModule`, `Bonuse` model) — don't "fix" without a full rename.
- Selected filters (month/branch) persisted in session (`payroll_month`, `attendance_branch_id`, …).

## Dependencies map (what affects what)
- **UserModule** ↔ AdminModule, EmployeeModule: `users.userable` morph; `UserAuthController::login` routes to `admin` or `employee` guard by `userable_type`; blocks suspended/resigned/terminated employees.
- **PermissionModule** ↔ UserModule: `User::roles()/permissions()` via `model_has_*` tables; `CheckPermission` middleware.
- **BranchModule** ↔ Employee, Attendance, Payroll, Report: branch `type` (`normal`|`training`) — student commissions/reports apply to training branches; branch holds work regulations shown to employees.
- **DepartmentModule** ↔ BranchModule, EmployeeModule (departments-by-branch lookup).
- **EmployeeModule** ↔ almost everything: `basic_salary`, `monthly_working_days`, `status`; `employee_commissions` (per-employee rate overrides of CommissionModule).
- **PayrollModule** ← Deduction, Bonuse, Leave, Student, Commission, Employee: `PayrollRepository::recalculateForEmployee($employeeId, 'YYYY-MM')` is the single formula. Called on create/update/delete in Bonuse/Deduction/Leave services and on payroll page load.
- **StudentModule** → PayrollModule (commission by `students.month` + `commission_id`), ReportModule (performance metrics). Student changes do **not** trigger recalc directly — payroll recalcs on view.
- **CommissionModule** → StudentModule (`students.commission_id`), EmployeeModule (overrides), PayrollModule.
- **ViolationModule** → DeductionModule (`resolveViolationDeduction` picks amount by repeat count).
- **AttendanceModule** — monthly records per branch; not yet feeding payroll (`days_late` hard-coded 0).
- **SettingModule** — key/value settings (`emp_monthly_working_days`, `emp_daily_working_hours`, …) used as defaults in the employee create form.
- **ReportModule** ← Employee, Student, Payroll (salary report), Attendance.
- **LayoutModule** — shared by every view; nav files list all admin/employee routes. Changing a route name → update the nav.

## Rules
- Before renaming anything shared (routes, components, config keys), search the whole project for references and update them all.
- Never edit migrations that already ran — create a new migration.
- Ask before deleting files or changing shared layouts.
- Keep changes minimal and scoped to the task.
