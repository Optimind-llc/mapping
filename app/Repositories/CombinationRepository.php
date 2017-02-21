<?php

namespace App\Repositories;

use App\Models\Press\Combination;

/**
 * Class CombinationRepository.
 */
class CombinationRepository
{
    public function onlyActive()
    {
        $combination = Combination::onlyActive()->get()->map(function($c) {
            return [
                'v' => $c->vehicle_code,
                'l' => $c->line_code,
                'p' => $c->pt_pn
            ];
        });

        return $combination;
    }

    public function identifyVehicle($line_code, $pt_pn)
    {
        $vehicle_code = Combination::where('line_code', '=', $line_code)
            ->where('pt_pn', '=', $pt_pn)
            ->first()
            ->vehicle_code;

        return $vehicle_code;
    }
}
