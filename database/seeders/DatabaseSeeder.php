<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('game')->insert([
            'date_ini' => Carbon::now()->addWeeks(1)->format('Y-m-d H:i:s'),
            'b_rate' => '1-15',
            'i_rate' => '16-30',
            'n_rate' => '31-45',
            'g_rate' => '46-60',
            'o_rate' => '61-75',
            'game_track' => json_encode([]),
            'status' => true,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
