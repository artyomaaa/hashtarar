<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRoles;
use App\Repositories\Contracts\IRoleRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\Judge\IJudgeRepository;
use App\Repositories\Contracts\Mediator\IMediatorRepository;
use Illuminate\Database\Seeder;

final class MediatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(
        IUserRepository     $userRepository,
        IMediatorRepository $mediatorRepository,
        IRoleRepository     $roleRepository,
    )
    {
        $users = [
            [
                'firstname' => 'Mediator1',
                'lastname' => 'Mediator1',
                'middlename' => 'Mediator1',
                'email' => 'medsddiator1@gmailll.com',
                'password' => '$2a$12$TJL4HqimGOCl8GYNLGYL5.3b8OIIw4Qt9tjLtefMM5YGfEtAzcGR2K',
                'role_id' => 2,
                'ssn' => '0000012',
                'phone' => '37441414141',
                'birthdate' => '2000-04-01'
            ],
            [
                'firstname' => 'Mediator2',
                'lastname' => 'Mediator2',
                'middlename' => 'Mediator2',
                'email' => 'meddsdiator2@gmailll.com',
                'password' => '$2a$12$TJL4HqimGOCl8GYNLGYL5.3b8OIIw4Qt9tjLtefMM5YGfEtAzcGR2',
                'role_id' => 2,
                'ssn' => '0000017',
                'phone' => '37441414141',
                'birthdate' => '2000-04-01'
            ],
            [
                'firstname' => 'Mediator3',
                'lastname' => 'Mediator3',
                'middlename' => 'Mediator3',
                'email' => 'medidsdsator3@gmailll.com',
                'password' => '$2a$12$TJL4HqimGOCl8GYNLGYL5.3b8OIIw4Qt9tjLtefMM5YGfEtAzcGR2',
                'role_id' => 2,
                'ssn' => '0000019',
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
        }
    }
}
