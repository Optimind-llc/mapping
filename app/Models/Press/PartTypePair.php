<?php

namespace App\Models\Press;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PartTypePair
 * @package App\Models
 */
class PartTypePair extends Model
{
    protected $connection = 'press';
    protected $table = 'part_type_pair';
    protected $guarded = ['id'];

    public function figures()
    {
        return $this->hasMany(
            'App\Models\Vehicle950A\Figure',
            'pt_pn',
            'pn'
        );
    }
}
