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

    public function getByIds($ids)
    {
        return FailureType::whereIn('id', $ids)
            ->select(['id', 'name'])
            ->orderBy('id')
            ->get();
    }

    public function onlyActive()
    {
        return FailureType::onlyActive()->get()->toArray();
    }

    public function withDeactive()
    {
        return FailureType::select(['id', 'name'])->get();
    }
}
