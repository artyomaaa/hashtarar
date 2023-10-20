<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRoles;
use App\Repositories\Contracts\IRoleRepository;
use Illuminate\Database\Seeder;

final class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(IRoleRepository $roleRepository)
    {
        $roles = UserRoles::cases();
        $roleRepository->deleteTableAllData();

        foreach ($roles as $role) {
            $roleRepository->create([
                'name' => $role->value
            ]);
        }
    }
}
