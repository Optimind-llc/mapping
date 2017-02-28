<?php

namespace App\Models\Press;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Memo
 * @package App\Models
 */
class Memo extends Model
{
    protected $connection = 'press';
    protected $guarded = ['id'];

    public function scopeNarrow($query, $start, $end, $by, $chokus)
    {
        return $query->where('discarded', '=', 0)
            ->whereIn($by.'_choku', $chokus)
            ->where($by.'_at', '>=', $start)
            ->where($by.'_at', '<', $end)
            ->orderBy($by.'_at', 'asc');
    }

    public function scopeWithFailures($query)
    {
        return $query->with([
            'failures' => function($q) {
                $q->select([
                    'id',
                    'memo_id',
                    'type_id as typeId',
                    'x1',
                    'y1',
                    'x2',
                    'y2',
                    'palet_first as paletFirst',
                    'palet_last as paletLast',
                    'modificated_at as modificatedAt'
                ]);
            }
        ]);
    }

    public function scopeWithFigure($query)
    {
        return $query->with([
            'figure' => function($q) {
                $q->where('status', '=', 1)->select([
                    'id',
                    'path'
                ]);
            }
        ]);
    }

    public function scopeWithPair($query)
    {
        return $query->with([
            'partType' => function($q) {
                $q->select(['pn', 'capacity']);
            },
            'partType.leftPair' => function($q) {
                $q->select(['id', 'left_pn', 'right_pn']);
            },
            'partType.rightPair' => function($q) {
                $q->select(['id', 'left_pn', 'right_pn']);
            }
        ]);
    }

    public function partType()
    {
        return $this->belongsTo(
            'App\Models\Press\PartType',
            'pt_pn',
            'pn'
        );
    }

    public function failures()
    {
        return $this->hasMany(
            'App\Models\Press\MemoFailure',
            'memo_id',
            'id'
        );
    }

    public function figure()
    {
        return $this->belongsTo(
            'App\Models\Press\Figure',
            'figure_id',
            'id'
        );
    }
}
