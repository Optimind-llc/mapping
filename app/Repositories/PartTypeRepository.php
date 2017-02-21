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
        $combination = PartType::all();

        return $combination;
    }
}
