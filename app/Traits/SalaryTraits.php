<?php
namespace App\Traits;

trait SalaryTraits {
    public function calculate_all($type, $d){
        $rate = $d->rate;
        $sal_type = $d->sal_type;
        $daily = $d->daily;

        $income_arr = ['basic', 'transpo', 'gross', 'reg_ot', 'rd_ot', 'spcl', 'leg',
                        'spcl_ot', 'leg_ot', 'wfh', 'deduction', 'net']; //included deduction for WFH

        $deduction_arr = ['deduction', 'absence', 'late', 'undertime', 'wfh',
                            'net'];

        if(in_array($type, $income_arr)){
            $inc = $d->income;
            $gross = 0;

            if($d->emp_id == 1){
                $gross += $rate;
            }
            else if($d->sal_type == 'Monthly'){
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
            $adjustments = $inc->adjustments;
            $gross += $transpo_amount;
            $gross += $inc->market_comm;
            $gross += $inc->jap_comm;
            $gross += $inc->thirteenth;
            $gross += ($inc->daily * $inc->leg_hol) * 2;
            info($daily);
            info($inc->leg_hol);
            info(($daily * $inc->leg_hol) * 2);
            if($type == 'leg') return ($daily * $inc->leg_hol) * 2;
            $gross += ($inc->daily  * $inc->spcl_hol) + (($daily  * $inc->spcl_hol) * 0.3);
            if($type == 'spcl') return ($daily  * $inc->spcl_hol) + (($daily  * $inc->spcl_hol) * 0.3);
            
            $ot_hours = [$inc->reg_ot, $inc->rd_ot, $inc->leg_hol_ot, $inc->spcl_hol_ot];

            $ot = ['reg', 'rd', 'leg_ot', 'spcl_ot'];

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
                else if($ot[$x] == 'spcl_ot'){
                    $amount = number_format($amount + ($amount *0.3), 2, '.', '');
                    if($type == 'spcl_ot') return $amount;
                }
                else if($ot[$x] == 'leg_ot'){
                    $amount = number_format($amount * 2, 2, '.', '');
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

            if($type == 'deduction' || $type == 'wfh' || $type == 'net'){
                $wfh = 0;
                if($ded->wfh && $ded->wfh > 0) {
                    $wfh = number_format(($gross - $deduction - $adjustments) * ((100 - $ded->wfh) / 100), 2, '.', '');
                    if($type == 'wfh'){
                        $ded->wfh = ($ded->wfh == floor($ded->wfh)) ? floor($ded->wfh) : $ded->wfh;
                        return '('.$ded->wfh.'%) '.$wfh;
                    }
                }
    
                $deduction += $wfh; // OVERALL DEDUCTION
            }

            if($type == 'deduction'){
                return number_format($deduction, 2, '.', '');
            }
        }
        if($type == 'net'){
            $net = $gross - $deduction;

            return number_format($net, 2, '.', '');
        }
    }
}