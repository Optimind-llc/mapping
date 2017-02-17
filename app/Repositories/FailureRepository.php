<?php

namespace App\Repositories;

use App\Models\Press\Failure;

/**
 * Class FailureRepository.
 */
class FailureRepository
{
    public function create($param)
    {
        $new = new Failure;
        $new->type_id = $param['type_id'];
        $new->ir_id = $param['ir_id'];
        $new->figure_id = $param['figure_id'];
        $new->x = $param['x'];
        $new->y = $param['y'];
        $new->sub_x = $param['sub_x'];
        $new->sub_y = $param['sub_y'];
        $new->f_qty = $param['f_qty'];
        $new->responsible_for = $param['responsible_for'];
        $new->save();

        return $new;
    }

    public function update($id, $param)
    {
        $f = Failure::find($id);
        $f->f_qty = $param['f_qty'];
        $f->m_qty = $param['m_qty'];
        $f->responsible_for = $param['responsible_for'];
        $f->save();

        return $f;
    }

    public function deleteByIds($ids)
    {
        $deleted = Failure::whereIn('id', $ids)->delete();
        return $deleted;
    }
}
