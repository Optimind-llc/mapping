<?php

namespace App\Repositories;

use App\Models\Press\Line;

/**
 * Class LineRepository.
 */
class LineRepository
{
    public function onlyActive()
    {
        return Line::onlyActive()->get()->map(function($l) {
        	return $l->code;
        });
    }

    public function getCodeByCodeInQR($code_inQR)
    {
        return Line::where('code_inQR', '=', $code_inQR)->first()->code;
    }
}
