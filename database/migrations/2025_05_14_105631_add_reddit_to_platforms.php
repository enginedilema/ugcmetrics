<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('platforms')->updateOrInsert(
            ['name' => 'Reddit'],
            ['base_url' => 'https://www.reddit.com/user/', 'created_at' => now(), 'updated_at' => now()]
        );
    }

    public function down()
    {
        DB::table('platforms')->where('name', 'Reddit')->delete();
    }
};