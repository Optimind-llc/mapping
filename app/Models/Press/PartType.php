<?php

namespace App\Models\Press;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PartType
 * @package App\Models
 */
class PartType extends Model
{
    protected $connection = 'press';
    protected $primaryKey = 'pn';
    protected $guarded = ['pn'];
    public $incrementing = false;

    public function figures()
    {
        return $this->hasMany(
            'App\Models\Vehicle950A\Figure',
            'pt_pn',
            'pn'
        );
    }

    public function parts()
    {
        return $this->hasMany(
            'App\Models\Vehicle950A\Part',
            'type_id',
            'id'
        );
    }
}
