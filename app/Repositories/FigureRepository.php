<?php

namespace App\Repositories;

use App\Models\Press\Figure;

/**
 * Class FigureRepository.
 */
class FigureRepository
{
    public function onlyActive($pn)
    {
        return Figure::where('pt_pn', '=', $pn)->where('status', '=', 1)->first();
    }

    public function create($ir_id, $param)
    {
        $new = new Failure;
        $new->ir_id = $ir_id;
        $new->figure_id = $param['figureId'];
        $new->x = $param['x'];
        $new->y = $param['y'];
        $new->type_id = $param['failureTypeId'];
        $new->save();

        return $new;
    }

    public function deleteByIds($ids)
    {
        $deleted = Failure::whereIn('id', $ids)->delete();
        return $deleted;
    }
}
