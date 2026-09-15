<?php

namespace Modules\CommissionModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\CommissionModule\App\Http\Models\Commission;

class CommissionModuleDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Model::unguard();

        DB::table('commissions')->truncate();
        $arr_items = [
            ['name' => 'دورات تطورية', 'type' => 'fixed', 'value' => 50],
            ['name' => 'عروض عامة تطويري', 'type' => 'fixed', 'value' => 45],
            ['name' => 'عروض حرق تطويري', 'type' => 'fixed', 'value' => 30],
            ['name' => 'دورات تأهيليةاقل من 500', 'type' => 'fixed', 'value' => 25],
            ['name' => 'دورات تأهيليةاكثر من 500', 'type' => 'fixed', 'value' => 35],
            ['name' => 'دبلوم', 'type' => 'fixed', 'value' => 75],
            ['name' => 'عروض حرق fixed دبلوم', 'type' => 'fixed', 'value' => 30],
            ['name' => 'انجليزي3 شهور واكثر', 'type' => 'fixed', 'value' => 50],
            ['name' => 'انجليزي اقل من 3 شهور', 'type' => 'fixed', 'value' => 50],
            ['name' => 'طلاب ضمان اجتماعي', 'type' => 'fixed', 'value' => 15],
            ['name' => 'طلاب قدامى', 'type' => 'fixed', 'value' => 40],
            ['name' => 'دفعة طلاب قدامى', 'type' => 'percentage', 'value' => 3]
        ];
        Commission::insert($arr_items);
    }
}
