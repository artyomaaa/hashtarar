<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Repositories\Contracts\Mediator\IMediatorApplicationCaseTypeRepository;
use Illuminate\Database\Seeder;

final class MediatorApplicationCaseTypeSeeder extends Seeder
{
    public array $caseTypes = [
        [
            'name' => 'Որակավորման կասեցում',
        ],
        [
            'name' => 'Որակավորման դադարեցում',
        ],
        [
            'name' => 'Մասնակցություն ցանկ 1-ում',
        ],
        [
            'name' => 'Մասնակցություն ցանկ 2-ում',
        ],
        [
            'name' => 'Ցանկ 1-ից մասնակցության կասեցում',
        ],
        [
            'name' => 'Ցանկ 2-ից մասնակցության կասեցում',
        ],
        [
            'name' => 'Դառնալ հաշտարար',
        ],
        [
            'name' => 'Որակավորման ակտիվացում',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(IMediatorApplicationCaseTypeRepository $caseTypeRepository)
    {
        $caseTypeRepository->deleteTableAllData();
        foreach ($this->caseTypes as $data) {
            $caseTypeRepository->create($data);
        }
    }
}
