<?php

namespace App\Models\Press;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Figure
 * @package App\Models
 */
class Figure extends Model
{
    protected $connection = 'press';
    protected $guarded = ['id'];

    public function partType()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\PartType',
            'pt_pn',
            'pn'
        );
    }

    public function holeTypes()
    {
        return $this->hasMany(
            'App\Models\Vehicle950A\HoleType',
            'figure_id',
            'id'
        );
    }
}
