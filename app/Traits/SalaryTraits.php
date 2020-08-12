<?php
namespace App\Traits;

trait SalaryTraits {
    public function calculate_all($type, $d){
        $rate = $d->rate;
        $sal_type = $d->sal_type;
        $daily = $d->daily;

        $income_arr = ['basic', 'transpo', 'gross', 'reg_ot', 'rd_ot', 'spcl', 'leg',
                        'spcl_ot', 'leg_ot', 'net'];

        $deduction_arr = ['deduction', 'absence', 'late', 'undertime',
                            'net'];

        if(in_array($type, $income_arr)){
            $inc = $d->income;
            $gross = 0;

            if($d->sal_type == 'Monthly'){
                $gross += $rate / 2;
            }
            else if($d->sal_type == 'Daily'){
                $basic_days = $inc->basic;
                $gross += $daily * $basic_days; 
            }

            if($type == 'basic'){
                return number_format($gross, 2, '.', '');
            }

            $transpo_amount = $inc->transpo_days * $inc->transpo_allowance;
            $transpo_amount = number_format($transpo_amount, 2, '.', '');
            if($type == 'transpo'){
                return $transpo_amount;
            }
            
            $gross += $inc->cola;
            $gross += $inc->acc_allowance;
            $gross += $inc->adjustments;
            $gross += $transpo_amount;
            $gross += $inc->market_comm;
            $gross += $inc->jap_comm;
            $gross += $inc->thirteenth;
            
            $ot_hours = [$inc->reg_ot, $inc->rd_ot, $inc->leg_hol, 
            $inc->spcl_hol, $inc->leg_hol_ot, $inc->spcl_hol_ot];

            $ot = ['reg', 'rd', 'leg', 'spcl', 'leg_ot', 'spcl_ot'];

            for($x = 0; $x < count($ot); $x++){
                $amount = ($daily / 8) * $ot_hours[$x];

                if($ot[$x] == 'reg'){
                    $amount = number_format($amount * 1.25, 2, '.', '');
                    if($type == 'reg_ot') return $amount;
                }
                if($ot[$x] == 'rd'){
                    $amount = number_format($amount * 1.3, 2, '.', '');
                    if($type == 'rd_ot') return $amount;
                }
                else if($ot[$x] == 'spcl' || $ot[$x] == 'spcl_ot'){
                    $amount = number_format($amount + ($amount *0.3), 2, '.', '');
                    if($type == 'spcl') return $amount;
                    if($type == 'spcl_ot') return $amount;
                }
                else if($ot[$x] == 'leg' || $ot[$x] == 'leg_ot'){
                    $amount = number_format($amount * 2, 2, '.', '');
                    if($type == 'leg') return $amount;
                    if($type == 'leg_ot') return $amount;
                }

                $gross += $amount;
            }

            if($type == 'gross'){
                return number_format($gross, 2, '.', '');
            }
        }
        if(in_array($type, $deduction_arr)){
            $ded = $d->deduction;
            $deduction = 0;

            $deduction += $ded->cash_advance;
            $deduction += $ded->sss;
            $deduction += $ded->phic;
            $deduction += $ded->hdmf;
            $deduction += $ded->others;
            $deduction += $ded->tax;
            $deduction += $ded->man_allocation;

            $absence = number_format($daily * $ded->absence, 2, '.', '');
            if($type == 'absence') return $absence;
            $late = number_format(($daily / 8) * $ded->late, 2, '.', '');
            if($type == 'late') return $late;
            $undertime = number_format(($daily / 8) * $ded->undertime, 2, '.', '');
            if($type == 'undertime') return $undertime;
            
            $deduction += $absence + $late + $undertime;

            if($type == 'deduction'){
                return number_format($deduction, 2, '.', '');
            }
        }
        if($type == 'net'){
            $net = $gross - $deduction;
            if($d->deduction->wfh && $d->deduction->wfh > 0){
                $net = $net * ($d->deduction->wfh / 100);
            }

            return number_format($net, 2, '.', '');
        }
    }
}