<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Repositories\Contracts\IUserRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class JudgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(
        IUserRepository     $userRepository,
    ): void
    {
        $judges = [
            [
                'firstname' => 'Judge',
                'lastname' => 'Judge',
                'middlename' => 'Judge',
                'email' => 'judge@gmail.com',
                'password' => Hash::make('123456'),
                'role_id' => 5,
                'ssn' => '0000111',
                'phone' => '37441414141',
                'birthdate' => '2000-04-01'
            ],
            [
                'firstname' => 'Judge1',
                'lastname' => 'Judge1',
                'middlename' => 'Judge',
                'email' => 'judge1@gmail.com',
                'password' => Hash::make('123456'),
                'role_id' => 5,
                'ssn' => '0000211',
                'phone' => '37441414141',
                'birthdate' => '2000-04-01'
            ],
            [
                'firstname' => 'Judge2',
                'lastname' => 'Judge2',
                'middlename' => 'Judge',
                'email' => 'judge2@gmail.com',
                'password' => Hash::make('123456'),
                'role_id' => 5,
                'ssn' => '0000811',
                'phone' => '37441414141',
                'birthdate' => '2000-04-01'
            ]
        ];


        foreach ($judges as $data) {
            $userRepository->create($data);
        }

    }
}
