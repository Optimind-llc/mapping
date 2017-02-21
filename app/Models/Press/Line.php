<?php

namespace App\Models\Press;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Line
 * @package App\Models
 */
class Line extends Model
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
}
