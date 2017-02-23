<?php

namespace App\Repositories;

use App\Models\Press\MemoFailure;

/**
 * Class MemoFailureRepository.
 */
class MemoFailureRepository
{
    public function create($param)
    {
        $new = new MemoFailure;
        $new->type_id = $param['type_id'];
        $new->memo_id = $param['memo_id'];
        $new->figure_id = $param['figure_id'];
        $new->x1 = $param['x1'];
        $new->y1 = $param['y1'];
        $new->x2 = $param['x2'];
        $new->y2 = $param['y2'];
        $new->palet_first = $param['palet_first'];
        $new->palet_last = $param['palet_last'];
        $new->save();

        return $new;
    }

    public function update($id, $param)
    {
        $f = MemoFailure::find($id);
        $f->f_qty = $param['f_qty'];
        $f->m_qty = $param['m_qty'];
        $f->responsible_for = $param['responsible_for'];
        $f->save();

        return $f;
    }

    public function deleteByIds($ids)
    {
        $deleted = MemoFailure::whereIn('id', $ids)->delete();
        return $deleted;
    }
}
