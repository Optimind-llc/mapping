<?php

namespace App\Models\Press;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inspector
 * @package App\Models
 */
class Worker extends Model
{
    protected $connection = 'press';
    protected $table = 'workers';
    protected $guarded = ['id'];

    public function scopeOnlyActive($query)
    {
        return $query->where('status', '=', 1)
            ->orderBy('sort')
            ->select(['choku_code', 'name']);
    }

    public function lines()
    {
        return $this->belongsToMany(
            'App\Models\Press\Line',
            'worker_related',
            'worker_id',
            'line_code'
        )->withPivot('sort');
    }

    // public function groups()
    // {
    //     return $this->belongsTo(
    //         'App\Models\InspectorGroup',
    //         'group_code',
    //         'code'
    //     );
    // }


}
