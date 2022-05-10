<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array(
                ['name' => 'admin', 'code' => 101], 
                ['name' => 'client', 'code' => 102]
            );
        foreach ($roles as $key => $role) {
            Role::create($role);
        }
    }
}
