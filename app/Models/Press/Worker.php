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

    // public function groups()
    // {
    //     return $this->belongsTo(
    //         'App\Models\InspectorGroup',
    //         'group_code',
    //         'code'
    //     );
    // }

    // public function inspectionGroup()
    // {
    //     return $this->belongsToMany(
    //         'App\Models\InspectionGroup',
    //         'inspector_inspection_group',
    //         'inspector_id',
    //         'inspection_g_id'
    //     )->withPivot('sort');
    // }
}
