<?php

namespace App\Repositories;

use App\Models\Press\Worker;

/**
 * Class WorkerRepository.
 */
class WorkerRepository
{
    public function formated()
    {
        return Worker::onlyActive()
            ->get()
            ->groupBy('choku_code')
            ->map(function ($choku) {
                return $choku->map(function ($w) {
                    return $w->name;
                });
            })
            ->toArray();
    }

    public function filteredByLine($line)
    {
        return Worker::onlyActive()
            ->whereHas('lines', function ($query) use($line) {
                $query->where('code', '=', $line);
            })
            ->get()
            ->groupBy('choku_code')
            ->map(function ($choku) {
                return $choku->map(function ($w) {
                    return $w->name;
                });
            })
            ->toArray();
    }
}
