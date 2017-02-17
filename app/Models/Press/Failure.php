<?php

namespace App\Models\Press;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Failure
 * @package App\Models
 */
class Failure extends Model
{
    protected $connection = 'press';
    protected $guarded = ['id'];

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
