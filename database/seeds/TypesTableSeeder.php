<?php

use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('types')->insert([
            'name' => 'EQUITIES',
            'created_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('types')->insert([
            'name' => 'DEBT SECURITIES',
            'created_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('types')->insert([
            'name' => 'COMMODITIES',
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('types')->insert([
            'name' => 'REAL ESTATE',
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('types')->insert([
            'name' => 'PRIVATE EQUITY',
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('types')->insert([
            'name' => 'ALTERNATIVE INVESTMENTS',
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('types')->insert([
            'name' => 'CASH HOLDINGS',
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
