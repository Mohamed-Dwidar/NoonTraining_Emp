# Project Progress

> Loaded into every session via CLAUDE.md — keep it short and current.
> Edit module sections in place. Session log: last 10 entries only (git has the rest).
> Cross-module connections live in CLAUDE.md → "Dependencies map" — not repeated here.

---

## Current status
- **Working on:** Mobile/tablet responsive layout (both portals) — done 2026-11-12. Before that: payroll salary transfer status (تم التحويل + تاريخ الحوالة) on the admin payroll list — done 2026-11-11. Before that: employee permissions with spatie like Acc_NoonTraining — done 2026-11-10 (`can_edit_students`, `can_delete_students`, `can_view_work_regulations`; local DB migrated). Employees with `can_edit_students` can now edit their own students. Before that: bulk delete of students + keep filters after delete. Flagged: employee single delete still lacks an ownership check (Student → Pending). Before that: enforcing Controller → Service → Repository in every module. Done: no controller or service queries models directly anymore (rule in CLAUDE.md → Conventions). Still waiting on the owner: should text dates like `02/08/2026` be read as day/month (see Student → Pending)?
- **Branch / working tree:** `main`; through 851fca3 committed. Uncommitted: responsive layout (`layouts/header.blade.php`, `custom-rtl.css`, `custom.js`) + this file.
- **Before deploying permissions:** commit the new PermissionModule files under **both** `Database/...` and `database/...` (git tracks both cases; the provider loads `Database/migrations`, seeders namespace `Database\Seeders`) — e.g. `git add Modules/PermissionModule/Database/migrations/2026_11_10_000001_move_employee_permissions_to_spatie.php Modules/PermissionModule/database/migrations/2026_11_10_000001_move_employee_permissions_to_spatie.php` (same for `2026_11_10_000002_rename_student_permissions.php` and the two seeders). Same for `Modules/PayrollModule/{Database,database}/migrations/2026_11_11_000001_add_transfer_status_to_payrolls_table.php`. On the server: `php artisan migrate` → `php artisan permission:cache-reset` → `php artisan optimize:clear`.
- **Next up:** Answer the date-format question for imports. Owner confirmed project name, keep spatie, fix permission alias, drop FcmHelper, drop Vite/Tailwind. Still open: the other "(?)" items, the "(proposed)" conventions, and planned modules (none recorded yet).
- **Known issues:**
  - **Broken pages (found 2026-11-12, not fixed):** `/admin/task` and `/admin/leave/create` throw `Class "...\Controllers\Admin\Task|Leave" not found` — `TaskAdminController.php:42` / `LeaveAdminController` still use the model but its `use` import was dropped in the 2026-11-10 layering refactor. Fix = re-add the `use ...\Models\Task;` / `Leave;` import.
  - Git tracks **69 case-variant duplicate paths** (e.g. `ReportModule/app` + `App`, `AdminModule/Database` + `database`, `ReportModule/views/Admin` + `admin`). macOS hides this; on a case-sensitive Linux server, two copies can diverge or the wrong one load. Needs a cleanup decision.
  - `app/Http/Kernel.php` is dead (Laravel 12 binds the framework kernel). `app/Http/Middleware/CheckPermission.php` is unused since the spatie switch (2026-11-10) — delete both? (ask owner).
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
- **Last updated:** 2026-11-10
- **Files:** `config/permission.php` (spatie defaults), `Modules/UserModule/App/Http/Models/User.php` (`HasRoles`, `$guard_name = 'employee'`), `PermissionModule/App/Providers/PermissionModuleServiceProvider.php` (`Gate::before` admin bypass), `PermissionModule/database/migrations/2026_11_10_000001_move_employee_permissions_to_spatie.php` + `2026_11_10_000002_rename_student_permissions.php` (can_edit/can_delete → `*_students`, in place), `database/seeders/PermissionSeederTableSeeder.php` (list + labels), `Repository/PermissionRepository::forGuard`, `UserModule/Repository/UserRepository::syncPermissions`, `bootstrap/app.php` (spatie middleware aliases)
- **How it works:** Same approach as Acc_NoonTraining: spatie, permissions given **per employee** (no roles), guard `employee`. Permissions (guard `employee`): `can_edit_students` — امكانية تعديل الطلاب, `can_delete_students` — امكانية حذف الطلاب, `can_view_work_regulations` — عرض لائحة العمل. Admin ticks them on the employee create/edit form (listed from the DB with `label`, value = name; create = all ticked by default). Checks: route `permission:` middleware (403) + `@can` in employee nav / students page. Admin users pass every check.
- **Decisions:**
  - Switch to spatie like Acc — owner request 2026-11-10 (replaces the custom `hasPermission()` / `CheckPermission`).
  - Form lists permissions from the DB by name instead of Acc's hard-coded ids — Acc's ids break when production ids differ.
  - Names end in `_students` (renamed from `can_edit`/`can_delete`) — owner request, clearer when more permissions come.
  - Existing employees got edit + delete students; `can_view_work_regulations` copied from the old `employees.can_view_work_regulations` column, which the migration drops (`down()` restores it) — owner's call.
  - Kept the old custom tables (spatie-compatible). Old exam permissions (guard `user`, ids 1–7) deleted by the owner on the local DB (2026-11-10). Still left locally: 7 orphaned `role_has_permissions` rows + roles "Admin"/"Moderator" (guard `user`, unused). **Production:** delete old permissions by guard, not id: `DELETE FROM role_has_permissions WHERE permission_id IN (SELECT id FROM permissions WHERE guard_name='user'); DELETE FROM model_has_permissions WHERE permission_id IN (SELECT id FROM permissions WHERE guard_name='user'); DELETE FROM permissions WHERE guard_name='user';` (+ optionally roles with guard `user`), then `php artisan permission:cache-reset`.
- **Pending:** No roles UI (not needed yet). `UserService` + `UserAdminModuleController` (no routes) still have old custom permission code paths (?).

### Layout
- **Last updated:** 2026-11-12
- **Files:** `Modules/LayoutModule/resources/views/` — `layouts/home_layout` (RTL), `layout_main`, `header`, `footer`, `flash`, `month_select`, `custom_pagination`; `admin/nav`, `admin/modals`, `employee/nav`; styles `public/admin-assets/css-rtl/custom-rtl.css`, JS `public/admin-assets/js/custom.js`
- **How it works:** Shared Blade layout and navigation for both portals; theme assets are static files in `public/admin-assets/` (no Vite/npm build). Theme is Robust on **Bootstrap 4.0.0-alpha.4** (`col-xs-*`, `float-xs-*`; `.input-group` is `display: table`). Note: `css/style-admin.css` / `css/colors-admin.css` linked in `layout_main` don't exist (404) — `custom-rtl.css` is the real custom stylesheet.
- **Responsive (2026-11-12):** one block at the end of `custom-rtl.css` (keep it last). ≤991px: compact fixed 64px header (logo · title · user), tables don't wrap and scroll sideways, inline `style="width:…"` inputs capped at 100%. ≤767px: title hidden, hamburger `.mobile-nav-toggle` (in `header.blade.php`) opens `#primary-menu` as a fixed panel under the header (`body.mobile-nav-open`), sub-menus are a tap accordion (`li.open`). `custom.js`: toggle/close-on-outside-tap, menu items with a sub-menu open on tap at every width (works on tablets too), and **every `.content-wrapper table` without a `.table-responsive` parent is wrapped in one automatically** — new pages don't need the wrapper. Desktop (≥992px) unchanged. Login page (`home_layout`) was already responsive.
- **Decisions:** Fixed it in the shared layout + CSS/JS, not page by page — owner asked for the whole project; per-page markup untouched.

### Branch
- **Files:** `Modules/BranchModule/` — `BranchAdminController`, `Branch` model, views incl. `work_regulations*`
- **How it works:** CRUD; `type` = `normal` | `training`. Each branch stores work regulations that employees can view/print (if `employees.can_view_work_regulations`).

### Department
- **Files:** `Modules/DepartmentModule/` — `DepartmentAdminController`, `Department` model
- **How it works:** CRUD; linked to branch (`departments-by-branch` AJAX in Employee forms).

### Employee
- **Last updated:** 2026-11-10
- **Files:** `Modules/EmployeeModule/` — `EmployeeAdminController`, `EmployeeModuleController` (employee portal), `EmployeeService`, models `Employee`, `EmployeeCommission`
- **How it works:** Admin CRUD + status change + commission edit. Fields: salary, monthly working days, daily hours, status (`active`, `on_leave`, …), `hired_at`/`contract_ends_at`/`terminated_at`. Per-employee commission overrides synced into `employee_commissions` (only for training branches (?); via `EmployeeRepository::deleteCommissions/saveCommission`, branch via `BranchRepository`). Employee portal: dashboard, my info, change password, work regulations (+PDF, needs permission `can_view_work_regulations`). Create/edit form has a «الصلاحيات» section (see Permission); `EmployeeService::employeePermissions()` / `permissionNamesOf()`; `Employee::user()` is **morphMany** — use `->user->first()` / `->user()->first()`.
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
- **Last updated:** 2026-11-10
- **Admin list period filter (2026-11-11):** two month dropdowns «شهر المحاسبة من / إلى» (`month_from` / `month_to`, «الكل» = no limit) filter `students.month` in `Student::scopeFilter` (swapped if reversed, ignored unless `YYYY-MM`). Replaced the old date pickers `date_from`/`date_to`, which filtered `created_at`. Excel file name shows the month range. Month list built in the view (shared `month_select` has no «الكل» option).
- **Excel export (admin + employee, 2026-11-11):** `StudentsExport` = `WithMultipleSheets`, one `StudentsSheetExport` per `students.commission_id`, sheet named after the commission (ordered by id); students whose commission is missing/deleted → «بدون عمولة» (last); nothing to export → one empty «الطلاب» sheet. Same columns as before (view `Admin/exports/students_export`, used by both portals). Sheet-name rules shared with the template via trait `Exports/UniqueSheetTitles`.
- **Employee edit:** edit button on `Employee/index` when `can_edit_students` (actions column shows with edit or delete permission). `edit`/`update` only load the employee's own students (`StudentService::findForEmployee`, else 404); the form has no branch/department/employee pickers and `update` forces `employee_id` = logged-in employee. Edit link carries `redirect_to` → cancel and save return to the same filtered list page (`studentListUrl()` in the trait).
- **Bulk delete (admin + employee):** checkbox per row + "select all" (current page) on `Admin/index` and `Employee/index` → `DELETE admin|employee/student/bulk` (`*.students.bulk-destroy`, declared before `/{id}`) → `StudentService::deleteMany($ids, ?$employeeId)` → `StudentRepository::deleteByIds` (one `whereIn` delete; Student has no soft deletes/events). Employee side passes its own `employee_id`, so other employees' students are never deleted.
- **Return to same list after delete (single + bulk, both portals):** delete forms post hidden `redirect_to = request()->fullUrl()`; controllers use trait `App/Http/Controllers/Concerns/RedirectsToStudentList` — `redirectToStudentList()` accepts only this portal's `*.students.index` URL (no open redirect, else plain index); `redirectIfPageOutOfRange()` in `index()` sends a now-empty page N to the last page with the same filters (reads the raw `QUERY_STRING`, because the employee index merges forced filters into `$request`).
- **Import (validate-then-save):** `StudentImport::collection()` only collects rows (sheet title + Excel row number); controllers call `errors()` → if any, `back()->withErrors()` listing «sheet» + row + messages (max 50) and nothing is saved; else `save()` inserts all rows in one `DB::transaction`. Used by both Admin and Employee `importStudents`. Fully empty rows are skipped; required: name, mobile, national_id, course_name, payment_method, payment_date (valid date); amounts integer ≥ 0 (empty → 0). Each error shows the first message per field + a valid example from `StudentImport::EXAMPLES` (based on real data: 9-digit mobile starting 5, 10-digit national ID starting 1, methods كاش/حوالة/تمارا/تابي, date `02/08/2026`).
- **Decisions:**
  - Validate the whole file before saving anything, all-or-nothing — owner request, 2026-10-18.
- **Files:** `Modules/StudentModule/` — `StudentAdminController`, `Employee/StudentEmployeeController`, `StudentService`, `App/Imports/StudentImport.php`, `Exports/Students*Export.php`
- **How it works:** Student registrations (course, amounts, payment, `national_id`, `commission_id`, `month`) attributed to the registering employee. Admin: CRUD, Excel import/template, export. Employee portal: list/edit/delete/import own students (no create route despite `Employee/create` view (?)).
- **Pending:**
  - **Security gap:** employee single `destroy` doesn't check that the student belongs to the logged-in employee (needs `can_delete_students`). Edit/update (2026-11-10) and bulk delete are scoped. Fix suggested, not done yet.
  - **Open question (asked owner, no answer yet):** `parseDate()` uses `Carbon::parse`, which reads text dates with slashes as m/d/Y — `02/08/2026` → 8 Feb, and `25/08/2026` is rejected. If the example means 2 August (d/m/Y), switch text parsing to `d/m/Y` (Excel date cells and `Y-m-d` text are unaffected).
  - `payment_date` is a free-text string. Data issues seen 2026-11-11: Alaa's (employee 32) imported dates are day/month-swapped (e.g. 2026-12-08), one of Sara's is 2029-09-17. Owner: don't change the import parsing now.

### Payroll
- **Last updated:** 2026-11-11
- **Salary transfer:** `payrolls.transfer_status` (`pending` | `sent`, `Payroll::TRANSFER_STATUSES` with Arabic labels) + `transfer_date` (تاريخ الحوالة). Admin list column «حالة التحويل»: badge, date under it and green cell when sent; «تغيير» opens a modal → `POST admin/payroll/{id}/transfer` (`admin.payrolls.transfer`) → `PayrollService::updateTransfer` → `PayrollRepository::updateTransfer`. Saving = sent, date required; «إلغاء التحويل» (shown when sent) = pending, clears the date. Redirects back (keeps month/branch/page). Recalculation does not touch these columns. List table has its own compact CSS (`@push('styles')`: cell padding .6rem .5rem instead of the theme's 2rem, transfer column width = content, action buttons stacked).
- **Files:** `Modules/PayrollModule/` — `PayrollAdminController`, `PayrollModuleController` (employee), `PayrollService`, `PayrollRepository`, views `payslip_print`, `details_print`
- **How it works:** Details popup/PDF data comes from `PayrollService::monthDetails()` (bonuses, deductions, leaves, students, commission breakdown via each module's repository `getByEmployeeAndMonth`); employee "my payrolls" months from `monthsSinceFirstStudent()`. One row per employee + `YYYY-MM`. Opening the payroll page (per branch/month) recalculates every active/on-leave employee. `total = max(0, basic − (deductions + leave_days × daily_salary) + bonuses + students_commission)`. Commission = students of that `month` × (employee override ?? default rate; percentage applies to `paid_amount`). Payslip + details views and PDF for admin and employee.
- **Decisions:**
  - Recalc on read, not stored snapshots — because inputs change often; means past months change if rates/salary change (?).
  - Commission by `students.month` instead of `payment_date` — commit "payroll by month" (old logic left commented).
- **Pending:** `create`/`edit` views exist but no routes (?). No month locking/closing — a payroll marked «تم التحويل» is still recalculated on view, so its total can change after the money was sent (?). Transfer status not shown in the employee portal yet.

### Setting
- **Last updated:** 2026-11-10
- **Files:** `Modules/SettingModule/` — `SettingAdminController`, `Setting`, `Country` models, seeder
- **How it works:** Key/value settings edited in one form (e.g. default monthly working days / daily hours). Controller → `SettingService` (`findAll`, `create`, `updateValues`) → `SettingRepository::updateValueByKey`.

### Report
- **Last updated:** 2026-11-11
- **Files:** `Modules/ReportModule/` — `ReportAdminController`, `ReportService`, views `salary_report`, `employee_performance`, `employee_drilldown`
- **How it works:** Performance period = **`students.month` (شهر المحاسبة)**, like payroll: `ReportService::resolveMonthRange()` → [fromMonth, toMonth] for الشهر الحالي / السابق / شهر محدد / فترة مخصصة (`month_from`/`month_to` month lists, swapped if reversed). `ReportService` holds the logic; the student queries (training-branch filter, month range, distinct-student SQL) live in `StudentRepository` (`trainingQueryByMonth`, `performanceByEmployee`, `performanceTotals`, `trainingRecordsByMonth`), employee lookup in `EmployeeRepository::findWithTrashedByIds`. Salary report (per month/branch) and employee performance for training-branch staff: distinct students (by `national_id`), admissions, revenue (`paid_amount`), rankings, 6-month trend, drill-down.
- **Decisions:**
  - Period by `students.month`, not `payment_date` — owner, 2026-11-11. Employees upload last month's payments (Esraa's October upload = September payments), so by payment date they appeared partly or not at all; Alaa's imported dates are also day/month-swapped.
- **Pending:** Clean up legacy `User/ReportUserController` and course exports (?).

---

## Session log

### 2026-11-12 — Responsive layout for mobile / tablet
- **Changed:** `LayoutModule/.../layouts/header.blade.php` (hamburger button, `header-title` / `header-user-menu` classes, inline margin moved to CSS), `public/admin-assets/css-rtl/custom-rtl.css` (old nav-only media blocks replaced by one responsive section at the end), `public/admin-assets/js/custom.js` (mobile menu, tap-to-open dropdowns, auto `.table-responsive` wrapper)
- **Why:** Owner request — make the project usable on phones. Before: page scrolled sideways (up to 452px on a 390px screen), menu took half the screen with hover-only dropdowns, tables squeezed to one word per line.
- **Affects:** LayoutModule (every page of both portals). Verified with headless Chrome at 390 / 820 / 1440px: all admin + employee index/create pages = no sideways scroll; menu, sub-menu, user dropdown, close-on-outside-tap work; desktop looks the same as before.
- **Commit:** — (not committed yet)

### 2026-11-11 — Performance report by accounting month; payroll/student list tweaks
- **Changed:** `ReportService` (`resolveMonthRange` replaces `resolveDateRange`, trend + drilldown by month), `StudentRepository` (payment-date report queries → month), `ReportAdminController` (`month_from`/`month_to`), `employee_performance` + `employee_drilldown` views (custom period = month lists, drilldown shows «شهر المحاسبة»). Same day: employee payroll list transfer status (green + date / red), transfer popup shows salary, student lists: admin period filter = month lists on `students.month`, «تاريخ تسجيل الطالب» column in admin list, «شهر المحاسبة» column in employee list.
- **Why:** Owner: performance report showed only part of the employees (Esraa missing) — it grouped by payment date while uploads/payroll use شهر المحاسبة.
- **Affects:** ReportModule, StudentModule (repository), PayrollModule (employee view). Verified by rendering: October = Esraa 45, Kirollos 6, Sara 4; 08–10 = all 245 students; drilldown/data/salary pages 200.
- **Commit:** — (not committed yet)

### 2026-11-11 — Payroll salary transfer status
- **Changed:** new migration `PayrollModule/.../2026_11_11_000001_add_transfer_status_to_payrolls_table.php`; `Payroll` model (constants, `isTransferred()`, `transferStatusLabel()`, date cast); `PayrollRepository/PayrollService::updateTransfer`; `PayrollAdminController::updateTransfer`; route `admin.payrolls.transfer`; `Admin/index.blade.php` (status column, green highlight + date, transfer modal; compact column widths)
- **Why:** Owner request — see and change whether each salary was transferred, with a required transfer date.
- **Affects:** PayrollModule only. Verified via HTTP: date required, sent shows date + green, cancel clears, invalid status rejected, recalculation keeps the status.
- **Commit:** — (not committed yet)

### 2026-11-10 — Rename student permissions; employees can edit their students
- **Changed:** new migration `PermissionModule/.../2026_11_10_000002_rename_student_permissions.php`; seeder names; `StudentModule/routes/web.php`; `Employee/index.blade.php` (edit button, `@canany` actions column); `Employee/edit.blade.php` (employee routes, pickers removed, `redirect_to`); `StudentEmployeeController::edit/update` (own students only, `employee_id` forced); `StudentService/StudentRepository::findForEmployee`; trait `studentListUrl()`
- **Why:** Owner request — `can_edit_students` / `can_delete_students` names, and actually let employees with the edit permission edit (there was no edit button; the edit page posted to the admin route).
- **Affects:** StudentModule (employee side), PermissionModule. Verified via HTTP: own student edit 200 + save returns to filtered list, other employee's student 404, `employee_id` can't be changed, buttons follow each permission, renamed permissions kept all assignments.
- **Commit:** — (not committed yet)

### 2026-11-10 — Employee permissions with spatie (like Acc_NoonTraining)
- **Changed:** `User` model (spatie `HasRoles`, guard `employee`, custom role/permission methods removed), `config/permission.php`, `bootstrap/app.php` (spatie middleware aliases), `PermissionModuleServiceProvider` (admin `Gate::before`), new migration `2026_11_10_000001_move_employee_permissions_to_spatie`, PermissionModule seeders rewritten (old exam seeders truncated `permissions`), `PermissionRepository::forGuard`, `UserRepository::syncPermissions`, `EmployeeService` + `EmployeeAdminController` (permissions on create/edit + validation), employee `Admin/create|edit` views, `EmployeeModuleController` + routes (work regulations), `StudentModule/routes/web.php` (employee edit/update/delete/bulk), `Employee/index.blade.php` (`@can('can_delete')`), `employee/nav` (`can('can_view_work_regulations')`)
- **Why:** Owner wants the same permission approach as Acc_NoonTraining; permissions chosen: edit students, delete students, view work regulations (moved from a boolean column).
- **Affects:** EmployeeModule, StudentModule (employee side), LayoutModule (employee nav), UserModule. Verified: migrate/rollback/migrate keeps data (employee #36 without regs stays without); employee without a permission gets 403 and the UI hides the action; admin bypass; form saves via service; invalid names rejected. **Deploy:** see Current status.
- **Commit:** — (not committed yet)

### 2026-11-10 — Keep filters + page after deleting students
- **Changed:** new `StudentModule/App/Http/Controllers/Concerns/RedirectsToStudentList.php`; `destroy`/`bulkDestroy`/`index` in `Admin/StudentAdminController` + `Employee/StudentEmployeeController`; hidden `redirect_to` in delete forms of `views/{Admin,Employee}/index.blade.php`
- **Why:** Owner request — after deleting, stay on the same page with the same filters (single delete used to go to the unfiltered index; deleting the last rows of a page left an empty page).
- **Affects:** StudentModule only. Verified via HTTP kernel: single/bulk redirect to the filtered URL, foreign `redirect_to` falls back to index, page 999 → last page.
- **Commit:** — (not committed yet)

### 2026-11-10 — Bulk delete of students (admin + employee)
- **Changed:** `StudentModule/routes/web.php` (`admin.students.bulk-destroy`, `employee.students.bulk-destroy`), `bulkDestroy` in `Admin/StudentAdminController` and `Employee/StudentEmployeeController`, `StudentService::deleteMany`, `StudentRepository::deleteByIds` (optional employee scope), `resources/views/{Admin,Employee}/index.blade.php` (checkbox column, select-all with indeterminate state, "حذف المحدد (n)" button + confirm)
- **Why:** Owner request — delete many students at once, in both portals. Employee bulk delete only touches the employee's own students.
- **Affects:** PayrollModule / ReportModule (fewer students → commission/metrics; payroll recalcs on view, as with single delete)
- **Commit:** — (not committed yet)

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
