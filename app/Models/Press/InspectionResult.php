<?php

namespace App\Models\Press;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class InspectionResult
 * @package App\Models
 */
class InspectionResult extends Model
{
    protected $connection = 'press';
    protected $guarded = ['id'];
    protected $dates = ['processed_at', 'inspected_at', 'modificated_at', 'exported_at'];

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
                    'ir_id',
                    'type_id as typeId',
                    'x1',
                    'y1',
                    'x2',
                    'y2',
                    'f_qty as fQty',
                    'm_qty as mQty',
                    'responsible_for as responsibleFor'
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
                $q->select(['pn']);
            },
            'partType.leftPair' => function($q) {
                $q->select(['id', 'left_pn', 'right_pn']);
            },
            'partType.rightPair' => function($q) {
                $q->select(['id', 'left_pn', 'right_pn']);
            }
        ]);
    }

    public function scopeHasFailures($query)
    {
        return $query->whereHas('failures', function($q) {
            $q->whereNull('m_qty');
        });
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
            'App\Models\Press\Failure',
            'ir_id',
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
