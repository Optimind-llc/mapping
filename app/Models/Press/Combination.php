<?php

namespace App\Models\Press;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Combination
 * @package App\Models
 */
class Combination extends Model
{
    protected $connection = 'press';
    protected $guarded = ['id'];

    public function scopeOnlyActive($query)
    {
        return $query->join('vehicles as v', function($join) {
            $join->on('combinations.vehicle_code', '=', 'v.code')
                ->where('v.status', '=', 1);
        })->join('lines as l', function($join) {
            $join->on('combinations.line_code', '=', 'l.code')
                ->where('l.status', '=', 1);
        })
        ->select(['v.sort', 'combinations.vehicle_code', 'combinations.line_code', 'combinations.pt_pn'])
        ->orderBy('v.sort');
    }

    public function type()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\FailureType',
            'type_id',
            'id'
        );
    }

    public function figure()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\Figure',
            'figure_id',
            'id'
        );
    }
}
