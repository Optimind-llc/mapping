<?php

namespace App\Models\Press;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Failure
 * @package App\Models
 */
class FailureType extends Model
{
    protected $connection = 'press';
    protected $guarded = ['id'];

    public function scopeOnlyActive($query)
    {
        return $query->where('status', '=', 1)
            ->orderBy('label')
            ->select(['id', 'name', 'label']);
    }
}
