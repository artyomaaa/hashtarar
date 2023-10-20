<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Repositories\Contracts\IQualificationsRepository;
use Illuminate\Database\Seeder;

final class QualificationsSeeder extends Seeder
{

    public array $qualificationTypes = [
        [
            'title' => 'Ընտանեկան'
        ],
        [
            'title' => 'Քաղաքացիական'
        ],
        [
            'title' => 'Աշխատանքային'
        ],
        [
            'title' => 'Առևտրային'
        ],

    ];

    /**
     * Run the database seeds.
     *
     * @param IQualificationsRepository $qualificationsRepository
     * @return void
     */
    public function run(IQualificationsRepository $qualificationsRepository): void
    {
        $qualificationsRepository->deleteTableAllData();
        foreach ($this->qualificationTypes as $data) {
            $qualificationsRepository->create($data);
        }
    }
}
