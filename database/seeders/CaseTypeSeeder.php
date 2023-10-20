<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CaseTypeGroups;
use App\Repositories\Contracts\ICaseTypeRepository;
use Illuminate\Database\Seeder;

final class CaseTypeSeeder extends Seeder
{
    public array $caseTypes = [
        [
            'name' => 'Ամուսնալուծություն',
            'group_id' => CaseTypeGroups::LIST_2,
        ],
        [
            'name' => 'Երեխայի բնակության վայրի որոշում',
            'group_id' => CaseTypeGroups::LIST_2,
        ],
        [
            'name' => 'Ալիմենտի (ապրուստավճար) բռնագանձում',
            'group_id' => CaseTypeGroups::LIST_2,
        ],
        [
            'name' => 'Ամուսինների ընդհանուր սեփականությունը համարվող գույքի բաժանում',
            'group_id' => CaseTypeGroups::LIST_2,
        ],
        [
            'name' => 'Ծնողական իրավունքների իրականացում',
            'group_id' => CaseTypeGroups::LIST_2,
        ],
        [
            'name' => 'Երեխայի մերձավոր ազգականների՝ երեխայի հետ շփվելու արգելքները վերացնելու մասին որոշում',
            'group_id' => CaseTypeGroups::LIST_2,
        ],
        [
            'name' => 'Երեխայի տեսակցության կարգ սահմանելու մասին որոշում',
            'group_id' => CaseTypeGroups::LIST_2,
        ],
        [
            'name' => 'Ամուսնական պայմանագրի վերաբերյալ գործերով որոշում',
            'group_id' => CaseTypeGroups::LIST_2,
        ],
        [
            'name' => 'Ամուսնական պայմանագրի  լուծման վերաբերյալ գործերով որոշում',
            'group_id' => CaseTypeGroups::LIST_2,
        ],
        [
            'name' => 'Այլ',
            'group_id' => CaseTypeGroups::LIST_1,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(ICaseTypeRepository $caseTypeRepository)
    {
        $caseTypeRepository->deleteTableAllData();
        foreach ($this->caseTypes as $data) {
            $caseTypeRepository->create($data);
        }
    }
}
