<?php

namespace App\Repositories;

use App\Models\Press\FailureType;

/**
 * Class FailureTypeRepository.
 */
class FailureTypeRepository
{
    public function activeIds()
    {
        return FailureType::onlyActive()->get()->map(function($ft) {
            return $ft->id;
        });
    }

    public function onlyActive()
    {
        return FailureType::onlyActive()->get()->toArray();
    }
}
