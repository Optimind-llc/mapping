<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use TCPDF;
use App\Models\Press\FailureType;

class GeneratePDF
{
    protected $now;
    protected $reportDate;
    protected $line;
    protected $choku = '';
    // protected $chokuType;

    public function __construct($reportDate, $line, $choku) {
        $this->now = Carbon::now();
        $this->reportDate = str_replace('-', '/', $reportDate);
        $this->line = $line;

        switch ($choku) {
            case 'W': $this->choku = '白直'; break;
            case 'Y': $this->choku = '黄直'; break;
            case 'B': $this->choku = '黒直'; break;
        }
    }

    protected function createTCPDF()
    {
        $fpdi = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $fpdi->SetMargins(0, 0, 0);
        $fpdi->setPrintHeader(false);
        $fpdi->setPrintFooter(false);
        $fpdi->SetMargins(0, 0, 0);
        $fpdi->SetCellPadding(0);
        $fpdi->SetAutoPageBreak(false);

        $fpdi->SetAuthor('Mapping system for TAKAOKA PRESS');
        $fpdi->SetTitle('Report');
        $fpdi->SetSubject('Mapping system Report');
        $fpdi->SetKeywords('PDF');

        return $fpdi;
    }

    protected function renderTitle($tcpdf)
    {
        $A3 = config('report.A3');

        $title = '直レポート　'.$this->line.'　'.$this->reportDate.'　'.$this->choku.'　印刷日時：'.$this->now->toDateTimeString();

        $tcpdf->SetFont('kozgopromedium', '', 8);
        $tcpdf->Text($A3['x0'], $A3['y0'], $title);
        $tcpdf->Cell(100,100,'社員コード',1,0,'L');

    }

    public function generate($irs)
    {
        $tcpdf = $this->createTCPDF();
        $A3 = config('report.A3');

        $tcpdf->AddPage('P', 'A3');
        $this->renderTitle($tcpdf);

        $allCount = $irs->count();
        $all_vehicle_sum = $irs->map(function($ir) {
            return $ir->partType->capacity;
        })->sum();

        $ft_ids = $irs->map(function($ir) {
            return unserialize($ir->ft_ids);
        })
        ->flatten()
        ->unique();

        $failureTypes = FailureType::whereIn('id', $ft_ids)
            ->orderBy('label')
            ->select(['id', 'name'])
            ->get();

        $allFailures = $irs->map(function($ir) {
            return $ir->failures;
        })
        ->flatten()
        ->groupBy('typeId')
        ->map(function($ft) {
            return $ft->map(function($f) {
                if ($f->mQty) {
                    return $f->mQty;
                }
                else {
                    return $f->fQty;
                }
            })->sum();
        });

        $cellWidth = 9;
        $cellHeight = 4;

        $tcpdf->SetFont('kozgopromedium', '', 6);

        $row = 1;
            $col = 3;
            foreach ($failureTypes as $ft) {
                $col = $col+1;
                $tcpdf->MultiCell($cellWidth, $cellHeight, $ft['name'], 0, 'C', 0, 1, $A3['x0'] + 38+($col-1)*$cellWidth, $A3['y1']+($row-1)*$cellHeight);
            }

        $tcpdf->Line($A3['x0'] + 38, $A3['y1'] + $cellHeight*$row - 0.5, $A3['x0'] + 38 + $cellWidth*$col, $A3['y1'] + $cellHeight*$row - 0.5, array('dash' => '3,1'));

        $row = 2;
            $col = 1;
            $tcpdf->MultiCell($cellWidth, $cellHeight, '加工総数', 0, 'C', 0, 1, $A3['x0'] + 38+($col-1)*$cellWidth, $A3['y1']+($row-1)*$cellHeight);
            $col = 2;
            $tcpdf->MultiCell($cellWidth, $cellHeight, $all_vehicle_sum, 0, 'C', 0, 1, $A3['x0'] + 38+($col-1)*$cellWidth, $A3['y1']+($row-1)*$cellHeight);
            $col = 3;
            $tcpdf->MultiCell($cellWidth, $cellHeight, '直不良数', 0, 'C', 0, 1, $A3['x0'] + 38+($col-1)*$cellWidth, $A3['y1']+($row-1)*$cellHeight);

            foreach ($failureTypes as $ft) {
                $col = $col+1;

                $failure_sum = 0;
                if ($allFailures->has($ft['id'])) {
                    $failure_sum = $allFailures[$ft['id']];
                }

                $tcpdf->MultiCell($cellWidth, $cellHeight, $failure_sum, 0, 'C', 0, 1, $A3['x0'] + 38+($col-1)*$cellWidth, $A3['y1']+($row-1)*$cellHeight);
            }

        $row = 3;
            $col = 3;
            $tcpdf->MultiCell($cellWidth, $cellHeight, '直不良率', 0, 'C', 0, 1, $A3['x0'] + 38+($col-1)*$cellWidth, $A3['y1']+($row-1)*$cellHeight);

            foreach ($failureTypes as $ft) {
                $col = $col+1;

                $failure_sum = 0;
                if ($allFailures->has($ft['id'])) {
                    $failure_sum = $allFailures[$ft['id']];
                }

                $parcent = number_format($failure_sum/$all_vehicle_sum*100, 1).' %';

                $tcpdf->MultiCell($cellWidth, $cellHeight, $parcent, 0, 'C', 0, 1, $A3['x0'] + 38+($col-1)*$cellWidth, $A3['y1']+($row-1)*$cellHeight);
            }

        $tcpdf->Line($A3['x0'] + 38, $A3['y1'] + $cellHeight*$row - 0.5, $A3['x0'] + 38 + $cellWidth*$col, $A3['y1'] + $cellHeight*$row - 0.5, array('dash' => '3,1'));


        // Render result
        $d1 = [4, 7, 14, 7, 12, 9, 12];
        $d2 = [10, 13, 14, 10, 13, 13, 8];

        // $grouped = $irs->groupBy('vehicle_code');
        $grouped = $irs->groupBy('pt_pn');

        $block = 0;
        foreach ($grouped as $vehicle_code => $irs) {
            if (($block + $irs->count())*4 > 360) {
                $tcpdf->AddPage('P', 'A3');
                $block = 0;
            }

                $vehicle_sum = $irs->map(function($ir) {
                    return $ir->partType->capacity;
                })->sum();

            $row = $block+1;
                $tcpdf->MultiCell($d1[0], $cellHeight*2, 'No.', 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,0)), $A3['y2']+($row-1)*$cellHeight);
                $tcpdf->MultiCell($d1[1], $cellHeight*2, '型No.', 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,1)), $A3['y2']+($row-1)*$cellHeight);
                $tcpdf->MultiCell($d1[2], $cellHeight*2, '品番', 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,2)), $A3['y2']+($row-1)*$cellHeight);
                $tcpdf->MultiCell($d1[3], $cellHeight*2, '車種', 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,3)), $A3['y2']+($row-1)*$cellHeight);
                $tcpdf->MultiCell($d1[4], $cellHeight*2, '検査者', 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,4)), $A3['y2']+($row-1)*$cellHeight);
                $tcpdf->MultiCell($d1[5], $cellHeight*2, 'パレット連番', 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,5)), $A3['y2']+($row-1)*$cellHeight);
                $tcpdf->MultiCell($d1[6], $cellHeight*2, '検査結果', 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,6)), $A3['y2']+($row-1)*$cellHeight);

                $col = 1;
                foreach ($failureTypes as $ft) {
                    $tcpdf->MultiCell($cellWidth, $cellHeight*2, $ft->name, 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth, $A3['y2']+($row-1)*$cellHeight);
                    $col = $col+1;
                }

                $tcpdf->MultiCell($d2[0], $cellHeight*2, 'コメント', 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,0)), $A3['y2']+($row-1)*$cellHeight);
                $tcpdf->MultiCell($d2[1], $cellHeight*2, '加工実績日時', 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,1)), $A3['y2']+($row-1)*$cellHeight);
                $tcpdf->MultiCell($d2[2], $cellHeight*2, '手直者', 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,2)), $A3['y2']+($row-1)*$cellHeight);
                $tcpdf->MultiCell($d2[3], $cellHeight*2, 'コメント', 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,3)), $A3['y2']+($row-1)*$cellHeight);
                $tcpdf->MultiCell($d2[4], $cellHeight*2, '手直日時', 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,4)), $A3['y2']+($row-1)*$cellHeight);
                $tcpdf->MultiCell($d2[5], $cellHeight*2, ' 納入先 引取時間', 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,5)), $A3['y2']+($row-1)*$cellHeight);
                $tcpdf->MultiCell($d2[6], $cellHeight*2, '納入先名称', 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,6)), $A3['y2']+($row-1)*$cellHeight);

            $row = $block+1.6;
            $tcpdf->Line($A3['x0'], $A3['y2'] + $cellHeight*$row, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,7)), $A3['y2'] + $cellHeight*$row, array('dash' => '3,1'));

            $row = $block+3;
                foreach ($irs as $i => $ir) {
                    $status = $ir->failures->count() > 0 ? '×' : '○';

                    $tcpdf->MultiCell($d1[0], $cellHeight, $i+1, 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,0)), $A3['y2']+($row-1)*$cellHeight);
                    $tcpdf->MultiCell($d1[1], $cellHeight, $ir->mold_type_num, 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,1)), $A3['y2']+($row-1)*$cellHeight);
                    $tcpdf->MultiCell($d1[2], $cellHeight, $ir->pt_pn, 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,2)), $A3['y2']+($row-1)*$cellHeight);
                    $tcpdf->MultiCell($d1[3], $cellHeight, $ir->vehicle_code, 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,3)), $A3['y2']+($row-1)*$cellHeight);
                    $tcpdf->MultiCell($d1[4], $cellHeight, $ir->inspected_by, 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,4)), $A3['y2']+($row-1)*$cellHeight);
                    $tcpdf->MultiCell($d1[5], $cellHeight, $ir->palet_num, 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,5)), $A3['y2']+($row-1)*$cellHeight);
                    $tcpdf->MultiCell($d1[6], $cellHeight, $status, 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,6)), $A3['y2']+($row-1)*$cellHeight);

                    $ir_failures = $ir->failures
                        ->groupBy('typeId')
                        ->map(function($ft) {
                            return $ft->map(function($f) {
                                if ($f->mQty) {
                                    return $f->mQty;
                                }
                                else {
                                    return $f->fQty;
                                }
                            })->sum();
                        });

                    $col = 1;
                    foreach ($failureTypes as $ft) {
                        $ir_failure_sum = 0;
                        if ($ir_failures->has($ft['id'])) {
                            $ir_failure_sum = $ir_failures[$ft['id']];
                        }

                        $tcpdf->MultiCell($cellWidth, $cellHeight, $ir_failure_sum, 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth, $A3['y2']+($row-1)*$cellHeight);
                        $col = $col+1;
                    }

                    $fComment = '';
                    if ($ir->f_comment) {
                        $fComment = mb_substr($ir->f_comment, 0, 4, 'UTF-8').'..';
                    }

                    $mComment = '';
                    if ($ir->m_comment) {
                        $mComment = mb_substr($ir->m_comment, 0, 4, 'UTF-8').'..';
                    }

                    $inspectedAt = $ir->inspected_at->format('m/d H:i');

                    $modificatedAt = '-';
                    if ($ir->modificated_at) {
                        $modificatedAt = $ir->modificated_at->format('m/d H:i');
                    }

                    $pickedAt = '-';
                    if ($ir->picked_at) {
                        $pickedAt = $ir->picked_at->format('m/d H:i');
                    }

                    $pickedBy = '-';
                    if ($ir->picked_by) {
                        $pickedBy = mb_substr($ir->picked_by, 0, 4, 'UTF-8');
                    }

                    $tcpdf->MultiCell($d2[0], $cellHeight, $fComment, 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,0)), $A3['y2']+($row-1)*$cellHeight);
                    $tcpdf->MultiCell($d2[1], $cellHeight, $inspectedAt, 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,1)), $A3['y2']+($row-1)*$cellHeight);
                    $tcpdf->MultiCell($d2[2], $cellHeight, $ir->modificated_by, 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,2)), $A3['y2']+($row-1)*$cellHeight);
                    $tcpdf->MultiCell($d2[3], $cellHeight, $mComment, 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,3)), $A3['y2']+($row-1)*$cellHeight);
                    $tcpdf->MultiCell($d2[4], $cellHeight, $modificatedAt, 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,4)), $A3['y2']+($row-1)*$cellHeight);
                    $tcpdf->MultiCell($d2[5], $cellHeight, $pickedAt, 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,5)), $A3['y2']+($row-1)*$cellHeight);
                    $tcpdf->MultiCell($d2[6], $cellHeight, $pickedBy, 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,6)), $A3['y2']+($row-1)*$cellHeight);

                    $row = $row+1;
                }
            
            $row = $row-1;
                $tcpdf->Line($A3['x0'], $A3['y2'] + $cellHeight*$row, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth + array_sum(array_slice($d2,0,7)), $A3['y2'] + $cellHeight*$row, array('dash' => '3,1'));

            $row = $row+1.3;
                $all_failures = $irs->map(function($ir) {
                    return $ir->failures;
                })
                ->flatten()
                ->groupBy('typeId')
                ->map(function($ft) {
                    return $ft->map(function($f) {
                        if ($f->mQty) {
                            return $f->mQty;
                        }
                        else {
                            return $f->fQty;
                        }
                    })->sum();
                });

                $all_failures_sum = $all_failures->sum();

                $tcpdf->MultiCell($d1[4], $cellHeight, '加工数', 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,4)), $A3['y2']+($row-1)*$cellHeight);
                $tcpdf->MultiCell($d1[5], $cellHeight, $vehicle_sum, 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,5)), $A3['y2']+($row-1)*$cellHeight);
                $tcpdf->MultiCell($d1[6], $cellHeight, '不良小計', 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,6)), $A3['y2']+($row-1)*$cellHeight);

                $col = 1;
                foreach ($failureTypes as $ft) {
                    $failure_sum = 0;
                    if ($all_failures->has($ft['id'])) {
                        $failure_sum = $all_failures[$ft['id']];
                    }

                    $parcent = 0;
                    if ($all_failures_sum !== 0) {
                        $parcent = number_format($failure_sum/$all_failures_sum*100, 1).' %';
                    }

                    $tcpdf->MultiCell($cellWidth, $cellHeight, $failure_sum, 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth, $A3['y2']+($row-1)*$cellHeight);
                    $col = $col+1;
                }

            $row = $row+1;
                $tcpdf->MultiCell($d1[6], $cellHeight, '不良率', 0, 'C', 0, 1, $A3['x0']+array_sum(array_slice($d1,0,6)), $A3['y2']+($row-1)*$cellHeight);

                $col = 1;
                foreach ($failureTypes as $ft) {
                    $failure_sum = 0;
                    if ($all_failures->has($ft['id'])) {
                        $failure_sum = $all_failures[$ft['id']];
                    }

                    $parcent = number_format($failure_sum/$vehicle_sum*100, 1).' %';

                    $tcpdf->MultiCell($cellWidth, $cellHeight, $parcent, 0, 'C', 0, 1, $A3['x0'] + array_sum($d1) + ($col-1)*$cellWidth, $A3['y2']+($row-1)*$cellHeight);
                    $col = $col+1;
                }

            $block = $row+3;
        }

        return $tcpdf;
    }
}
