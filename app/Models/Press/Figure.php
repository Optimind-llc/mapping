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
            'App\Models\Press\PartType',
            'pt_pn',
            'pn'
        );
    }
}
