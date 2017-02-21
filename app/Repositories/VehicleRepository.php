<?php

namespace App\Repositories;

use App\Models\Press\Vehicle;

/**
 * Class VehicleRepository.
 */
class VehicleRepository
{
    public function onlyActive()
    {
        return Vehicle::onlyActive()->get()->map(function($v) {
        	return $v->code;
        });
    }
}
