<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Repositories\Contracts\ICourtRepository;
use Illuminate\Database\Seeder;

final class CourtSeeder extends Seeder
{
    private array $courts = [
        [
            'name' => 'Երևան քաղաքի առաջին ատյանի ընդհանուր իրավասության քաղաքացիական դատարան',
        ],
        [
            'name' => 'Կոտայքի մարզի առաջին ատյանի ընդհանուր իրավասության դատարան',
        ],
        [
            'name' => 'Արարատի և Վայոց ձորի մարզերի առաջին ատյանի ընդհանուր իրավասության դատարան',
        ],
        [
            'name' => 'Արմավիրի մարզի առաջին ատյանի ընդհանուր իրավասության դատարան',
        ],
        [
            'name' => 'Արագածոտնի մարզի առաջին ատյանի ընդհանուր իրավասության դատարան',
        ],
        [
            'name' => 'Շիրակի մարզի առաջին ատյանի ընդհանուր իրավասության դատարան',
        ],
        [
            'name' => 'Տավուշի մարզի առաջին ատյանի ընդհանուր իրավասության դատարան',
        ],
        [
            'name' => 'Լոռու մարզի առաջին ատյանի ընդհանուր իրավասության դատարան',
        ],
        [
            'name' => 'Գեղարքունիքի մարզի առաջին ատյանի ընդհանուր իրավասության դատարան',
        ],
        [
            'name' => 'Սյունիքի մարզի առաջին ատյանի ընդհանուր իրավասության դատարանը',
        ],
        [
            'name' => 'Վերաքննիչ քաղաքացիական դատարան',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(ICourtRepository $courtRepository)
    {
        $courtRepository->deleteTableAllData();
        foreach ($this->courts as $data) {
            $courtRepository->create($data);
        }
    }
}
