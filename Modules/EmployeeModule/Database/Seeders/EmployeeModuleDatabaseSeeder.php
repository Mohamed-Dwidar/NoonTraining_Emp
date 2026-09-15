<?php

namespace Modules\EmployeeModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\EmployeeModule\App\Http\Models\Employee;

class EmployeeModuleDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * Branches : 1, 2, 3
     * Departments:
     *   Branch 1 → 8 (التسويق)  , 9 (خدمة العملاء)
     *   Branch 2 → 1 (التسويق)  , 3 (الحسابات)    , 4 (الدعم الفني)
     *   Branch 3 → 5 (التسويق)  , 6 (الحسابات)    , 7 (الدعم الفني)
     *
     * 30 employees — 15 ذكور / 15 إناث (5 of each per branch)
     * Each employee gets a user account with password = 123456
     */
    public function run(): void {
        Model::unguard();

        // Clean up existing data
        DB::table('users')->where('userable_type', Employee::class)->delete();
        DB::table('employees')->truncate();

        $employees = [

            // ═══════════════════════════════════════════════════
            //  الفرع الأول  (branch 1 | depts 8, 9)
            // ═══════════════════════════════════════════════════
            ['name' => 'محمد أحمد العمري',     'email' => 'm.alamri@noon.sa',     'branch_id' => 1, 'department_id' => 8, 'job' => 'مسؤول تسويق',       'basic_salary' => 6000, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'علي حسن الزهراني',     'email' => 'a.alzahrani@noon.sa',   'branch_id' => 1, 'department_id' => 8, 'job' => 'مدير فرع',          'basic_salary' => 9500, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'أحمد سعيد القحطاني',   'email' => 'a.alqahtani@noon.sa',   'branch_id' => 1, 'department_id' => 9, 'job' => 'مسؤول خدمة عملاء', 'basic_salary' => 5500, 'monthly_working_days' => 24, 'daily_working_hours' => 8],
            ['name' => 'عمر خالد الغامدي',     'email' => 'o.alghamdi@noon.sa',    'branch_id' => 1, 'department_id' => 8, 'job' => 'مشرف تسويق',       'basic_salary' => 7000, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'يوسف إبراهيم الدوسري', 'email' => 'y.aldosari@noon.sa',    'branch_id' => 1, 'department_id' => 9, 'job' => 'مسؤول خدمة عملاء', 'basic_salary' => 5000, 'monthly_working_days' => 24, 'daily_working_hours' => 8],
            ['name' => 'فاطمة محمد السهلي',    'email' => 'f.alsahli@noon.sa',     'branch_id' => 1, 'department_id' => 8, 'job' => 'مسؤولة تسويق',      'basic_salary' => 5800, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'نور أحمد الشمري',      'email' => 'n.alshammari@noon.sa',  'branch_id' => 1, 'department_id' => 8, 'job' => 'مديرة قسم',         'basic_salary' => 8500, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'سارة خالد العتيبي',    'email' => 's.alotaibi@noon.sa',    'branch_id' => 1, 'department_id' => 9, 'job' => 'مسؤولة خدمة عملاء', 'basic_salary' => 5000, 'monthly_working_days' => 24, 'daily_working_hours' => 8],
            ['name' => 'هند علي المطيري',      'email' => 'h.almutairi@noon.sa',   'branch_id' => 1, 'department_id' => 8, 'job' => 'منسقة تسويق',       'basic_salary' => 5500, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'رنا سعد الحربي',       'email' => 'r.alharbi@noon.sa',     'branch_id' => 1, 'department_id' => 9, 'job' => 'مسؤولة خدمة عملاء', 'basic_salary' => 4800, 'monthly_working_days' => 22, 'daily_working_hours' => 8],

            // ═══════════════════════════════════════════════════
            //  الفرع الثاني  (branch 2 | depts 1, 3, 4)
            // ═══════════════════════════════════════════════════
            ['name' => 'خالد طارق السلمي',     'email' => 'k.alsalmi@noon.sa',     'branch_id' => 2, 'department_id' => 1, 'job' => 'مسؤول تسويق',  'basic_salary' => 6200, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'سعد فيصل البقمي',      'email' => 's.albaqmi@noon.sa',     'branch_id' => 2, 'department_id' => 3, 'job' => 'محاسب',        'basic_salary' => 6800, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'ياسر سامي الرشيدي',    'email' => 'y.alrashidi@noon.sa',   'branch_id' => 2, 'department_id' => 4, 'job' => 'فني دعم',      'basic_salary' => 5500, 'monthly_working_days' => 24, 'daily_working_hours' => 8],
            ['name' => 'عبدالله عمر الجهني',   'email' => 'a.aljohani@noon.sa',    'branch_id' => 2, 'department_id' => 1, 'job' => 'مدير فرع',     'basic_salary' => 9000, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'إبراهيم محمد الزيد',   'email' => 'i.alzaid@noon.sa',      'branch_id' => 2, 'department_id' => 3, 'job' => 'محاسب أول',    'basic_salary' => 7500, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'منى يوسف الشهري',      'email' => 'm.alshahri@noon.sa',    'branch_id' => 2, 'department_id' => 1, 'job' => 'مسؤولة تسويق', 'basic_salary' => 6000, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'ليلى إبراهيم القرني',  'email' => 'l.alqarni@noon.sa',     'branch_id' => 2, 'department_id' => 3, 'job' => 'محاسبة',       'basic_salary' => 6500, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'أمل طارق العسيري',     'email' => 'a.alasiri@noon.sa',     'branch_id' => 2, 'department_id' => 4, 'job' => 'فنية دعم',     'basic_salary' => 5200, 'monthly_working_days' => 24, 'daily_working_hours' => 8],
            ['name' => 'ريم فيصل الأسمري',     'email' => 'r.alasmari@noon.sa',    'branch_id' => 2, 'department_id' => 1, 'job' => 'منسقة تسويق',  'basic_salary' => 5500, 'monthly_working_days' => 22, 'daily_working_hours' => 8],
            ['name' => 'دانا ياسر البلوي',      'email' => 'd.albalawi@noon.sa',    'branch_id' => 2, 'department_id' => 3, 'job' => 'محاسبة أولى', 'basic_salary' => 7000, 'monthly_working_days' => 26, 'daily_working_hours' => 8],

            // ═══════════════════════════════════════════════════
            //  الفرع الثالث  (branch 3 | depts 5, 6, 7)
            // ═══════════════════════════════════════════════════
            ['name' => 'طارق يوسف العنزي',     'email' => 't.alanazi@noon.sa',     'branch_id' => 3, 'department_id' => 5, 'job' => 'مسؤول تسويق',  'basic_salary' => 6000, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'فيصل علي الحمدان',     'email' => 'f.alhamdan@noon.sa',    'branch_id' => 3, 'department_id' => 6, 'job' => 'محاسب',        'basic_salary' => 7000, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'حسن أحمد المحمد',      'email' => 'h.almohammad@noon.sa',  'branch_id' => 3, 'department_id' => 7, 'job' => 'فني دعم',      'basic_salary' => 5500, 'monthly_working_days' => 24, 'daily_working_hours' => 8],
            ['name' => 'سلطان خالد الفيصل',    'email' => 's.alfaisal@noon.sa',    'branch_id' => 3, 'department_id' => 5, 'job' => 'مدير فرع',     'basic_salary' => 9200, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'سامي عبدالله الراشد',  'email' => 's.alrashed@noon.sa',    'branch_id' => 3, 'department_id' => 6, 'job' => 'محاسب أول',    'basic_salary' => 7800, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'شيماء سلطان الكثيري',  'email' => 'sh.alkathiri@noon.sa',  'branch_id' => 3, 'department_id' => 5, 'job' => 'مسؤولة تسويق', 'basic_salary' => 5800, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'لمى عمر السبيعي',       'email' => 'l.alsobihi@noon.sa',    'branch_id' => 3, 'department_id' => 6, 'job' => 'محاسبة',       'basic_salary' => 6500, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
            ['name' => 'مها حسن الدغيثر',       'email' => 'm.aldghithr@noon.sa',   'branch_id' => 3, 'department_id' => 7, 'job' => 'فنية دعم',     'basic_salary' => 5000, 'monthly_working_days' => 24, 'daily_working_hours' => 8],
            ['name' => 'سمر سامي الزيراني',     'email' => 'sm.alzairani@noon.sa',  'branch_id' => 3, 'department_id' => 5, 'job' => 'منسقة تسويق',  'basic_salary' => 5300, 'monthly_working_days' => 22, 'daily_working_hours' => 8],
            ['name' => 'وفاء عبدالله الدوسري',  'email' => 'w.aldosari@noon.sa',    'branch_id' => 3, 'department_id' => 6, 'job' => 'محاسبة أولى',  'basic_salary' => 7200, 'monthly_working_days' => 26, 'daily_working_hours' => 8],
        ];

        $hashedPassword = Hash::make('123456');
        $now = now();

        foreach ($employees as $data) {
            $email = $data['email'];
            unset($data['email']);
            $data['created_at'] = $now;
            $data['updated_at'] = $now;

            $id = DB::table('employees')->insertGetId($data);

            DB::table('users')->insert([
                'email'         => $email,
                'password'      => $hashedPassword,
                'userable_id'   => $id,
                'userable_type' => Employee::class,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }
}
