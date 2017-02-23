<?php

namespace App\Models\Press;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Failure
 * @package App\Models
 */
class MemoFailure extends Model
{
    protected $connection = 'press';
    protected $guarded = ['id'];
    protected $table = 'memo_failures';

    public function figure()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\Figure',
            'figure_id',
            'id'
        );
    }
}
