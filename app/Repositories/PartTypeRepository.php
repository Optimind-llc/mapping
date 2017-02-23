<?php

namespace App\Repositories;

use App\Models\Press\PartType;

/**
 * Class PartTypeRepository.
 */
class PartTypeRepository
{
    public function all()
    {
        $pt = PartType::all();

        return $pt;
    }

    public function updateCapacityByQRcode($QRcode)
    {
        $pt_pn = substr($QRcode, 26, 10);
        $capacity = intval(substr($QRcode, 45, 5));

        $pt = PartType::find($pt_pn);
        $pt->capacity = $capacity;
        $pt->save();
    }
}
