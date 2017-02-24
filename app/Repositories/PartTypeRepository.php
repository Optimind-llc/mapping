<?php

namespace App\Repositories;

use App\Models\Press\PartType;

/**
 * Class PartTypeRepository.
 */
class PartTypeRepository
{
    public function hasPair($pn)
    {
        $pt = PartType::with([
            'leftPair' => function($q) {
                $q->select(['id', 'left_pn', 'right_pn']);
            },
            'rightPair' => function($q) {
                $q->select(['id', 'left_pn', 'right_pn']);
            }
        ])
        ->find($pn);

        if ($pt->leftPair === null && $pt->rightPair === null) {
            return false;
        }
        elseif($pt->leftPair) {
            return [
                'self' => 'left',
                'pairPn' => $pt->leftPair->right_pn,
            ];
        }
        elseif($pt->rightPair) {
            return [
                'self' => 'right',
                'pairPn' => $pt->rightPair->right_pn,
            ];
        }
    }

    public function onlyActive()
    {
        return PartType::onlyActive()->get()->map(function($p) {
            return $p->pn;
        });
    }

    public function all()
    {
        $pt = PartType::all();

        return $pt;
    }

    public function updateCapacityByQRcode($QRcode)
    {
        $pt_pn = substr($QRcode, 26, 10);
        $capacity = intval(substr($QRcode, 45, 5));

        $pt = PartType::find($pt_pn);
        $pt->capacity = $capacity;
        $pt->save();
    }
}
