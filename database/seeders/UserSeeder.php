<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRoles;
use App\Repositories\Contracts\IRoleRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\Judge\IJudgeRepository;
use App\Repositories\Contracts\Mediator\IMediatorRepository;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(
        IUserRepository     $userRepository,
        IMediatorRepository $mediatorRepository,
        IJudgeRepository    $judgeRepository,
        IRoleRepository     $roleRepository,
    )
    {
        $users = [
            [
                'firstname' => 'Admin',
                'lastname' => 'Admin',
                'middlename' => 'Admin',
                'email' => 'admin1@gmailll.com',
                'password' => '$2a$12$7YTvw0XPYyLB0czVO/Vhj.yEoKNkCtXnz33Dlx8hur44gyrHUNrOK',
                'role_id' => 3,
                'ssn' => '0000111',
                'phone' => '37441414141',
                'birthdate' => '2000-04-01'
            ],
            [
                'firstname' => 'Employee',
                'lastname' => 'Employee',
                'middlename' => 'Employee',
                'email' => 'empolyee123@gmailll.com',
                'password' => '$2a$12$7YTvw0XPYyLB0czVO/Vhj.yEoKNkCtXnz33Dlx8hur44gyrHUNrOK',
                'role_id' => 4,
                'ssn' => '0001112',
                'phone' => '37441414141',
                'birthdate' => '2000-04-01'
            ],
            [
                'firstname' => 'Citizen',
                'lastname' => 'Citizen',
                'middlename' => 'Citizen',
                'email' => 'citizen1@gmailll.com',
                'password' => '$2a$12$7YTvw0XPYyLB0czVO/Vhj.yEoKNkCtXnz33Dlx8hur44gyrHUNrOK',
                'role_id' => 4,
                'ssn' => '0002343',
                'phone' => '37441414141',
                'birthdate' => '2000-04-01'
            ],
            [
                'firstname' => 'Judge',
                'lastname' => 'Judge',
                'middlename' => 'Judge',
                'email' => 'judge123@gmailll.com',
                'password' => '$2a$12$7YTvw0XPYyLB0czVO/Vhj.yEoKNkCtXnz33Dlx8hur44gyrHUNrOK',
                'role_id' => 4,
                'ssn' => '0012434',
                'phone' => '37441414141',
                'birthdate' => '2000-04-01'
            ],
            [
                'firstname' => 'Mediator',
                'lastname' => 'Mediator',
                'middlename' => 'Mediator',
                'email' => 'mediator3212@gmailll.com',
                'password' => '$2a$12$7YTvw0XPYyLB0czVO/Vhj.yEoKNkCtXnz33Dlx8hur44gyrHUNrOK',
                'role_id' => 4,
                'ssn' => '0123455',
                'phone' => '37441414141',
                'birthdate' => '2000-04-01'
            ]
        ];

        $roleNames = $roleRepository->getRoleNames();

        foreach ($users as $data) {
            $user = $userRepository->create($data);


            if (in_array(UserRoles::MEDIATOR->value, $roleNames)) {
                $mediatorRepository->create([
                    'user_id' => $user->id
                ]);
            }

            if (in_array(UserRoles::JUDGE->value, $roleNames)) {
                $judgeRepository->create([
                    'user_id' => $user->id
                ]);
            }
        }
    }
}
