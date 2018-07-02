<?php

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            'name' => 'Afghanistan',
            'code' => 'AF',
            'currency_id' => 1,
            'phone_code' => '+93',
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('countries')->insert([
            'name' => 'Åland Islands',
            'code' => 'AX',
            'currency_id' => 2,
            'phone_code' => '+358',
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('countries')->insert([
            'name' => 'Albania',
            'code' => 'AL',
            'currency_id' => 1,
            'phone_code' => '+355',
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('countries')->insert([
            'name' => 'Algeria',
            'code' => 'DZ',
            'currency_id' => 1,
            'phone_code' => '+213',
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
