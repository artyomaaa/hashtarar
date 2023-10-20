<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Repositories\Contracts\MediatorCompany\IMediatorCompanyRepository;
use Illuminate\Database\Seeder;

final class MediatorCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param IMediatorCompanyRepository $mediatorCompanyRepository
     * @return void
     */
    public function run(
        IMediatorCompanyRepository $mediatorCompanyRepository,
    ): void
    {
        $mediatorCompanies = [
            [
                'company_name' => '<<Էյդիար փարթներս>> ՍՊԸ-ի մեդիացիայի և արբիտրաժի կենտրոն',
            ],
            [
                'company_name' => '<<Բեսթ Սոլյուշնս>> ՍՊԸ',
            ],

        ];
        $mediatorCompanyRepository->deleteTableAllData();

        foreach ($mediatorCompanies as $mediatorCompany) {
            $mediatorCompanyRepository->create($mediatorCompany);
        }
    }
}
