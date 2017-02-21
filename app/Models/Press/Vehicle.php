<?php

namespace App\Models\Press;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Vehicle
 * @package App\Models
 */
class Vehicle extends Model
{
    protected $connection = 'press';
    protected $primaryKey = 'code';
    protected $guarded = ['code'];
    public $incrementing = false;

    public function scopeOnlyActive($query)
    {
        return $query->where('status', '=', 1)
            ->orderBy('sort')
            ->select(['code']);
    }

    // public function figures()
    // {
    //     return $this->hasMany(
    //         'App\Models\Vehicle950A\Figure',
    //         'pt_pn',
    //         'pn'
    //     );
    // }

    // public function parts()
    // {
    //     return $this->hasMany(
    //         'App\Models\Vehicle950A\Part',
    //         'type_id',
    //         'id'
    //     );
    // }

    // public function holes()
    // {
    //     return $this->hasMany(
    //         'App\Models\Hole',
    //         'part_type_id',
    //         'id'
    //     );
    // }

    // public function inlines()
    // {
    //     return $this->hasMany(
    //         'App\Models\Inline',
    //         'part_type_id',
    //         'id'
    //     );
    // }
}
