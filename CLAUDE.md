# Project: Noon Training HR

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
- Frontend: Blade + Bootstrap/jQuery admin theme (`public/admin-assets/`, select2 from CDN) loaded by LayoutModule. No build step — Vite/Tailwind were removed (2026-10-18); add JS/CSS as static files under `public/admin-assets/`.
- Auth: custom session guards `admin` / `employee` (+ default `web`), all on one `users` provider (`UserModule` `User` model, polymorphic `userable` → `Admin` | `Employee`)
- Permissions: `spatie/laravel-permission` v6 (same approach as the owner's Acc_NoonTraining project) — per-employee permissions on guard `employee` (`User::$guard_name`), no roles in use; admins bypass every check (`Gate::before` in `PermissionModuleServiceProvider`). Tables are the older custom PermissionModule ones (spatie-compatible; `permissions.label` = Arabic name, required)
- Key packages: `prettus/l5-repository` (repositories), `maatwebsite/excel` (student import/export), `niklasravnsborg/laravel-pdf` (payslip / work-regulations PDFs), `laravel/sanctum`
- Env vars of note: `APP_*`, `DB_*`, `MAIL_*`, `QUEUE_CONNECTION`, `FILESYSTEM_DISK`, `AWS_*`

## Commands
- Dev server: `php artisan serve` (or `composer dev` = serve + queue + pail)
- Assets: none to build (static files in `public/admin-assets/`)
- Migrate: `php artisan migrate` (module migrations auto-loaded); seed a module: `php artisan module:seed <Module>`
- Tests: `php artisan test` (only Laravel example tests exist)
- Clear caches: `php artisan optimize:clear` (+ `php artisan permission:cache-reset` after changing permissions in the DB by hand)
- Permissions: `php artisan module:seed PermissionModule` — idempotent, creates the employee permissions
- Enable/disable modules: `modules_statuses.json`

## Project structure
- `Modules/<Name>Module/` — one module per domain (18 modules, all enabled)
  - `App/Http/Controllers/Admin/<X>AdminController.php` — admin side; `<X>ModuleController.php` — employee side / misc
  - `App/Http/Models/` — Eloquent models (not `Entities/`; PermissionModule has both)
  - `Services/<X>Service.php` — business logic; `Repository/<X>Repository.php` — prettus `BaseRepository`
  - `routes/web.php`, `database/migrations`, `database/seeders`, `resources/views/{Admin,Employee}/`
- `Modules/LayoutModule/resources/views/` — shared layout: `layouts/home_layout` (RTL master), `admin/nav`, `employee/nav`, `layouts/flash`, `layouts/month_select`, `layouts/custom_pagination`
- `permission:<name>` middleware = spatie `PermissionMiddleware` (alias in `bootstrap/app.php`, also `role`, `role_or_permission`). Must run after `auth:<guard>` (that sets the default guard). `app/Http/Middleware/CheckPermission.php` is the old custom one — no longer used.
- `bootstrap/app.php` — middleware aliases live here. `app/Http/Kernel.php` is a Laravel 10 leftover that Laravel 12 never loads; don't register anything there.
- `app/Helpers/` — `UploaderHelper`, `ApiResponseHelper`
- `routes/web.php` — empty; all routes live in modules
- `resources/views/` — intentionally empty (`.gitkeep`); keep the folder — `view:cache` / `optimize` fail without it

## Conventions
- **Layering (required, every module): Controller → Service → Repository → Model.**
  - Controllers: validate the request, call **services only**, return view/redirect/JSON. No `Model::where/find/create…`, no `DB::`, no repositories injected. Using `Model::class` as a constant (morph type checks, validation rules) is fine.
  - Services: business logic; inject **repositories** (own module + other modules' repositories for cross-module data, e.g. Bonuse/Deduction/Leave services call `PayrollRepository::recalculateForEmployee`). No direct model queries — model constants / pure static helpers (e.g. `Attendance::computeRate`) are fine.
  - Repositories: the only layer that queries models (prettus `BaseRepository`). Any new query = a named repository method (e.g. `getByEmployeeAndMonth`, `duplicateNationalIdCounts`). Use `findWhere([...])->first()` when "not found" must return null — prettus `find()` throws.
  - Constructor injection everywhere (resolved by the container) — never `new XService`.
- Models: `$guarded = []`; filtering via `scopeFilter($query, $request = [])` on the model, exposed as `Service::filter()`.
- Route names: `admin.<plural>.<action>` and `employee.<plural>.<action>`; URL prefix `admin/<singular>` / `employee/<singular>`. Always use named routes.
- Route verbs are **inconsistent**: older modules (Branch, Department, Employee, Commission) use `POST update` + `POST delete/{id}`; newer ones (Leave, Bonuse, Deduction, Violation, Task, Student) use `POST /{id}` + `DELETE /{id}`. New modules: follow the newer style (proposed).
- Validation: inline in controllers (`Validator::make` / `$request->validate`) with Arabic messages — no Form Requests. Keep that for consistency (proposed).
- Months: stored as `'YYYY-MM'` strings (`string('month', 7)`) in payrolls, leaves, deductions, bonuses, students; **attendances.month is a `date` (`YYYY-MM-01`)**. Month pickers use `@include('layoutmodule::layouts.month_select')`.
- Views: `<module>::Admin.index` etc. Folder case is inconsistent (`Admin` vs `admin`) — match the module you're in.
- Language / direction: UI text, flash and validation messages in Arabic, RTL layout; code identifiers and DB columns in English.
- Naming: DB columns snake_case; module names keep historical spellings (`BonuseModule`, `Bonuse` model) — don't "fix" without a full rename.
- Permission checks: route middleware `permission:<name>` for access, `@can('<name>')` / `$user->can('<name>')` to show/hide UI — never a boolean column on `employees` (that's how `can_view_work_regulations` used to work).
- Selected filters (month/branch) persisted in session (`payroll_month`, `attendance_branch_id`, …).

## Dependencies map (what affects what)
- **UserModule** ↔ AdminModule, EmployeeModule: `users.userable` morph; `UserAuthController::login` routes to `admin` or `employee` guard by `userable_type`; blocks suspended/resigned/terminated employees.
- **PermissionModule** ↔ UserModule, EmployeeModule, StudentModule, LayoutModule: spatie `HasRoles` on `User` (guard `employee`). Permissions are assigned per employee on the admin employee create/edit form (checkboxes listed from the DB → `EmployeeService` → `UserRepository::syncPermissions`). Checks: route `permission:<name>` (employee student edit/update = `can_edit_students`, delete/bulk delete = `can_delete_students`, work regulations + PDF = `can_view_work_regulations`), `@can` in Blade (employee nav, employee students page). New permission = add to `PermissionSeederTableSeeder::PERMISSIONS` (+ a data migration for existing DBs) and a check; the form picks it up automatically.
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
