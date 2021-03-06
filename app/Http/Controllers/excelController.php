<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\SalaryTraits;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use App\student;
use App\tf_payment;
use App\tf_projected;
use App\sec_bond;
use App\class_settings;
use App\class_students;
use App\departure_year;
use App\departure_month;
use App\branch;
use App\expense;
use App\expense_type;
use App\lead_company_type;
use App\emp_salary;
use App\salary_monitoring;
use App\soa;
use Carbon\Carbon;

class excelController extends Controller
{
    use SalaryTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function excel_tf_breakdown(Request $request){

        $class_select = $request->class_hidden;
        $program_select = $request->program_hidden;
        $branch_select = $request->branch_hidden;
        $departure_year_select = $request->year_hidden;
        $departure_month_select = $request->month_hidden;

        //ALL DATA -- START
        $installment = 0;        
        $current_class = [];

        if($class_select != 'All'){
            $student_group = student::pluck('id');
            
            for($x = 0; $x < count($student_group); $x++){
                $class_students = class_students::where('id', $student_group[$x])
                    ->orderBy('id', 'desc')->first();

                if(!empty($class_students)){
                    if($class_students->class_settings_id == $class_select){
                        array_push($current_class, $class_students->stud_id);
                    }
                }
            }
        }

        $student = student::with('program', 'payment')
        ->when($class_select != 'All', function($query) use($current_class){
            $query->whereIn('id', $current_class);
        })
        ->when($program_select != 'All', function($query) use($program_select){
            $query->where('program_id', $program_select);
        })
        ->when($branch_select != 'All', function($query) use($branch_select){
            $query->where('branch_id', $branch_select);
        })
        ->when($departure_year_select != 'All', function($query) use($departure_year_select){
            $query->where('departure_year_id', $departure_year_select);
        })
        ->when($departure_month_select != 'All', function($query) use($departure_month_select){
            $query->where('departure_month_id', $departure_month_select);
        })->get();
        
        $total_tuition = 0;

        foreach($student as $s){
            $temp = tf_payment::where('stud_id', $s->id)->where('tf_name_id', 3)->count();
            if($temp > $installment){
                $installment = $temp;
            }
            $s->prof_fee = tf_payment::where('stud_id', $s->id)->where('tf_name_id', 1)->sum('amount');
            if($s->prof_fee != 0){
                $s->prof_fee_date = tf_payment::where('stud_id', $s->id)->orderBy('date', 'desc')->where('tf_name_id', 1)->value('date');
            }
            else{
                $s->prof_fee_date = '';
            }
            $s->total_payment = tf_payment::where('stud_id', $s->id)->where('tf_name_id', 3)->sum('amount');
            $s->balance = tf_projected::where('program_id', $s->program_id)->where('tf_name_id', 3)->sum('amount');
            $s->remaining_bal = $s->balance - $s->total_payment;
            $total_tuition += $s->balance;
        }

        $class = $class_select;
        if($class_select != 'All'){
            $class = class_settings::with('sensei', 'class_day')->where('id', $class_select)->first();
            
            $class = $class->sensei->fname . ' | ' . $class->start_date . ' - ' .
            (($class->end_date) ? $class->end_date : 'TBD');
        }

        //ALL DATA -- END



        //STYLE -- START
        $headerStyleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];

        $centerStyleArray = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];

        $allStyleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];
        //STYLE -- END

        /* SETTINGS
            TITLE = ROW 1-3 | A-LastColumn
            HEADER DEFAULT = ROW 5-6 | A-H
            HEADER INSTALLMENT = ROW 5-6 | A-BeforeLastColumn
            BALANCE = ROW 5-6 | AfterLastInstallment
        */

        //Initialize Sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //Title
        $sheet->setCellValue('A1', 'LEAD TRAINING AND BUSINESS SOLUTIONS / MANILA KOKUSAI ACADEMY INC.');
        $sheet->setCellValue('A2', 'Tuition Fee Monitoring');
        $sheet->setCellValue('A3', 'Class: ' . $class);

        //HEADER -- START

        //Header Defaults -- START
        $sheet->setCellValue('A5', 'No.')->mergeCells('A5:A6');
        $sheet->setCellValue('B5', 'Name')->mergeCells('B5:B6');
        $sheet->setCellValue('C5', 'Program')->mergeCells('C5:C6');
        $sheet->setCellValue('D5', 'Prof Fee')->mergeCells('D5:E5');
        $sheet->setCellValue('D6', 'Amount Paid')->setCellValue('E6', 'Payment Date');
        $sheet->setCellValue('F5', 'Total Tuition')->mergeCells('F5:F6');
        $sheet->setCellValue('G5', 'Total Payment')->mergeCells('G5:G6');
        //Header Defaults -- END

        //Header Installment -- START
        $col = 'H';
        $col2 = 'I';
        for($x = 8, $y = 1; $x < 8+($installment*2)+2; $x+=2, $y++){
            $sheet->setCellValueByColumnAndRow($x, 5, 'Installment '.$y)->mergeCells($col.'5:'.$col2.'5');
            $sheet->setCellValueByColumnAndRow($x, 6, 'Amount Paid');
            $sheet->setCellValueByColumnAndRow($x+1, 6, 'Payment Date');
            $col++;$col++;$col2++;$col2++;
        }

        $sheet->setCellValue($col.'5', 'Balance')->mergeCells($col.'5:'.$col.'6');
        //Header Installment -- END

        //HEADER -- END

        //BODY -- START

        $row = 7;
        $count = 1;
        foreach($student as $s){
            $sheet->setCellValue('A'.$row, $count);
            $sheet->setCellValue('B'.$row, $s->lname . ', ' . $s->fname . ' ' .
            (($s->mname) ? $s->mname : ''));
            $sheet->setCellValue('C'.$row, (($s->program) ? $s->program->name : ''));
            $sheet->setCellValue('D'.$row, $s->prof_fee);
            $sheet->setCellValue('E'.$row, $s->prof_fee_date);
            $sheet->getStyle('E'.$row)->applyFromArray($centerStyleArray);
            $sheet->setCellValue('F'.$row, $s->balance);
            $sheet->setCellValue('G'.$row, $s->total_payment);
            $col = 'H';
            $col2 = 'I';
            for($x = 8, $y = 0; $x < 8+($installment*2); $x+=2, $y++){
                $amount = '';
                $date = '';

                if(!empty($s->payment[$y])){
                    $amount = $s->payment[$y]->amount;
                    $date = $s->payment[$y]->date;
                }

                $sheet->setCellValueByColumnAndRow($x, $row, $amount);
                $sheet->setCellValueByColumnAndRow($x+1, $row, $date);
                $sheet->getStyle($col2.$row)->applyFromArray($centerStyleArray);
                $col++;$col++;$col2++;$col2++;
            }
            $col++;$col++;
            $sheet->setCellValue($col.$row, $s->remaining_bal);
            $row++;$count++;
        }

        //BODY -- END

        //FOOTER -- START

        $highestrow = $sheet->getHighestRow()+1;
        $sheet->setCellValue('A'.$highestrow, 'TOTAL')->mergeCells('A'.$highestrow.':'.'C'.$highestrow);
        $sheet->setCellValue('D'.$highestrow, '=sum(D7:D'.($sheet->getHighestRow()-1).')');
        $sheet->setCellValue('F'.$highestrow, '=sum(F7:F'.($sheet->getHighestRow()-1).')');
        $sheet->setCellValue('G'.$highestrow, '=sum(G7:G'.($sheet->getHighestRow()-1).')');
        $col = 'H';
        for($x = 8, $y = 0; $x < 8+($installment*2); $x+=2, $y++){
            $sheet->setCellValue($col.$highestrow, '=sum('.$col.'7:'.$col.($sheet->getHighestRow()-1).')');
            $col++;$col++;
        }
        $col++;$col++;
        $sheet->setCellValue($col.$highestrow, '=sum('.$col.'7:'.$col.($sheet->getHighestRow()-1).')');

        //FOOTER -- END

        $sheet->freezePane('D7');

        $last_col_row = $sheet->getHighestColumn() . $sheet->getHighestRow();

        $sheet->mergeCells('A1:'.$sheet->getHighestColumn().'1')->mergeCells('A2:'.$sheet->getHighestColumn().'2')
        ->mergeCells('A3:'.$sheet->getHighestColumn().'3');

        //Using Styles -- START

        //Set title

        $sheet->getStyle('A1:A'.$sheet->getHighestRow())->applyFromArray($centerStyleArray);
        $sheet->getStyle('A1:'.$sheet->getHighestColumn().'3')->getFont()->setSize(18);

        $highestcolumn = $sheet->getHighestColumn();
        $highestcolumn++;
        for($x = 'A'; $x != $highestcolumn; $x++){
            $sheet->getColumnDimension($x)->setAutoSize(TRUE);
        }

        $highestrow = $sheet->getHighestRow();
        for($x = 5; $x <= $highestrow; $x++){
            for($y = 'A'; $y != $highestcolumn; $y++){
                if($x < 7){
                    $sheet->getStyle($y.$x)->applyFromArray($headerStyleArray);
                }
                else{
                    $sheet->getStyle($y.$x)->applyFromArray($allStyleArray);
                }
            }
            $sheet->getRowDimension($x)->setRowHeight(30);
        }

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
        ->setPaperSize(PageSetup::PAPERSIZE_FOLIO);

        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setRight(0.7);
        $sheet->getPageMargins()->setLeft(0.25);
        $sheet->getPageMargins()->setBottom(0.75);

        $sheet->getPageSetup()->setFitToWidth(1);    
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getPageSetup()->setPrintArea('A1:'.$sheet->getHighestColumn().$sheet->getHighestRow());
        
        //Using Styles -- END

        $filename = 'Tuition Fee Breakdown.xlsx';

        //redirect output to client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
    }

    public function excel_tf_sb_summary(Request $request){

        $branch_select = $request->branch_hidden;
        $departure_year_select = $request->year_hidden;
        $departure_month_select = $request->month_hidden;

        //ALL DATA -- START

        $student = student::with('program', 'payment')
        ->when($branch_select != 'All', function($query) use($branch_select){
            $query->where('branch_id', $branch_select);
        })
        ->when($departure_year_select != 'All', function($query) use($departure_year_select){
            $query->where('departure_year_id', $departure_year_select);
        })
        ->when($departure_month_select != 'All', function($query) use($departure_month_select){
            $query->where('departure_month_id', $departure_month_select);
        })->get();

        foreach($student as $s){
            $tf_projected = tf_projected::where('program_id', $s->program_id);$tp_temp = tf_payment::where('stud_id', $s->id);

            $s->sec_bond = sec_bond::where('stud_id', $s->id)->sum('amount');
            $s->tf_su = ($tf_projected
            ->where(function($query){
                $query->where('tf_name_id', 1)->orWhere('tf_name_id', 3);
            })->sum('amount')) - 
            ($tp_temp
            ->where(function($query){
                $query->where('tf_name_id', 1)->orWhere('tf_name_id', 3);
            })->sum('amount'));
            $tf_projected = tf_projected::where('program_id', $s->program_id);$tp_temp = tf_payment::where('stud_id', $s->id);
            $s->visa = ($tf_projected->where('tf_name_id', 2)->sum('amount')) - ($tp_temp->where('tf_name_id', 2)->sum('amount'));
            $tf_projected = tf_projected::where('program_id', $s->program_id);$tp_temp = tf_payment::where('stud_id', $s->id);
            $s->docu = ($tf_projected->where('tf_name_id', 4)->sum('amount')) - ($tp_temp->where('tf_name_id', 4)->sum('amount'));
            $tf_projected = tf_projected::where('program_id', $s->program_id);$tp_temp = tf_payment::where('stud_id', $s->id);
            $s->select = ($tf_projected->where('tf_name_id', 5)->sum('amount')) - ($tp_temp->where('tf_name_id', 5)->sum('amount'));
            $tf_projected = tf_projected::where('program_id', $s->program_id);$tp_temp = tf_payment::where('stud_id', $s->id);
            $s->pdos = ($tf_projected->where('tf_name_id', 6)->sum('amount')) - ($tp_temp->where('tf_name_id', 6)->sum('amount'));
            $tf_projected = tf_projected::where('program_id', $s->program_id);$tp_temp = tf_payment::where('stud_id', $s->id);
            $s->air = ($tf_projected->where('tf_name_id', 7)->sum('amount')) - ($tp_temp->where('tf_name_id', 7)->sum('amount'));
            $tf_projected = tf_projected::where('program_id', $s->program_id);$tp_temp = tf_payment::where('stud_id', $s->id);
            $s->dhl = ($tf_projected->where('tf_name_id', 8)->sum('amount')) - ($tp_temp->where('tf_name_id', 8)->sum('amount'));
        }

        if($departure_year_select == 'All' && $departure_month_select == 'All'){
            $departure = 'All Departures - ';
        }
        else{
            $dep_year = ($departure_year_select != 'All') ? departure_year::find($departure_year_select) : 'All';
            $dep_month = ($departure_year_select != 'All') ? departure_month::find($departure_month_select): 'All';
    
            $departure = $dep_month->name . ' ' . $dep_year->name . ' Departure  - ';
        }

        $branch = ($branch_select != 'All') ? branch::where('id', $branch_select)->pluck('name') : 'All Branches';
        $departure .= $branch;

        //ALL DATA -- END

        //STYLE -- START
        
        $headerStyleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];

        $centerStyleArray = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];

        $allStyleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];

        //STYLE -- END

        //Initialize Sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //Title
        $sheet->setCellValue('A1', 'LEAD GROUP OF COMPANIES')->mergeCells('A1:Z1');
        $sheet->setCellValue('A2', $departure)->mergeCells('A2:Z2')->mergeCells('A3:Z3');

        //HEADER -- START

        //Header Defaults -- START
        $sheet->setCellValue('A4', 'No.');
        $sheet->setCellValue('B4', 'Name');
        $sheet->setCellValue('C4', 'Program');
        $sheet->setCellValue('D4', 'School');
        $sheet->setCellValue('E4', 'Sec Bond Amount');
        $sheet->setCellValue('F4', 'Tuition Fee / Sign Up Fee');
        $sheet->setCellValue('G4', 'Payment Date');
        $sheet->setCellValue('H4', 'VISA Fee');
        $sheet->setCellValue('I4', 'Payment Date');
        $sheet->setCellValue('J4', 'PDOS');
        $sheet->setCellValue('K4', 'Payment Date');
        $sheet->setCellValue('L4', 'Selection Fee');
        $sheet->setCellValue('M4', 'Payment Date');
        $sheet->setCellValue('N4', 'Airfare');
        $sheet->setCellValue('O4', 'Payment Date');
        $sheet->setCellValue('P4', 'DHL');
        $sheet->setCellValue('Q4', 'Payment Date');
        $sheet->setCellValue('R4', 'BC&ITR');
        $sheet->setCellValue('S4', 'Payment Date');
        $sheet->setCellValue('T4', 'Japan TF Short/Over');
        $sheet->setCellValue('U4', 'Total Deductions');
        $sheet->setCellValue('V4', 'To be Refunded');
        $sheet->setCellValue('W4', 'Refund Date');
        $sheet->setCellValue('X4', 'To be Cash-Out');
        $sheet->setCellValue('Y4', 'Payment Date');
        $sheet->setCellValue('Z4', 'Remarks');
        //Header Defaults -- END

        //HEADER -- END

        //BODY -- START
        
        $row = 5;
        $count = 1;
        foreach($student as $s){
            $sheet->setCellValue('A'.$row, $count);
            $sheet->setCellValue('B'.$row, $s->lname . ', ' . $s->fname . ' ' .
            (($s->mname) ? $s->mname : ''));
            $sheet->setCellValue('C'.$row, (($s->program) ? $s->program->name : ''));
            $sheet->setCellValue('D'.$row, (($s->school) ? $s->school->name : ''));
            $sheet->setCellValue('E'.$row, $s->sec_bond);
            $sheet->setCellValue('F'.$row, $s->tf_su);
            $row++;$count++;
        }

        //BODY -- END

        /*//FOOTER -- START

        $highestrow = $sheet->getHighestRow()+1;
        $sheet->setCellValue('A'.$highestrow, 'TOTAL')->mergeCells('A'.$highestrow.':'.'D'.$highestrow);
        $sheet->setCellValue('E'.$highestrow, '=sum(E5:E'.($sheet->getHighestRow()-1).')');
        $sheet->setCellValue('F'.$highestrow, '=sum(F5:F'.($sheet->getHighestRow()-1).')');
        
        //FOOTER -- END*/

        //Using Styles -- START

        $sheet->freezePane('D5');

        //Set Title
        
        $sheet->getStyle('A1:A3')->getFont()->setSize(18);
        
        $sheet->getStyle('A5:A'.$sheet->getHighestRow())->applyFromArray($centerStyleArray);

        $highestcolumn = $sheet->getHighestColumn();
        $highestcolumn++;
        for($x = 'E'; $x != $highestcolumn; $x++){
            $sheet->getColumnDimension($x)->setWidth(12);
            $sheet->getStyle($x.'4')->getAlignment()->setWrapText(TRUE);
        }

        $column = 'A';
        $column++;$column++;$column++;$column++;
        for($x = 'A'; $x != $column; $x++){
            $sheet->getColumnDimension($x)->setAutoSize(TRUE);
        }

        $highestrow = $sheet->getHighestRow();
        for($x = 4; $x <= $highestrow; $x++){
            for($y = 'A'; $y != $highestcolumn; $y++){
                if($x < 5){
                    $sheet->getStyle($y.$x)->applyFromArray($headerStyleArray);
                }
                else{
                    $sheet->getStyle($y.$x)->applyFromArray($allStyleArray);
                }
            }
            $sheet->getRowDimension($x)->setRowHeight(40);
        }
        
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
        ->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
        
        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setRight(0.7);
        $sheet->getPageMargins()->setLeft(0.25);
        $sheet->getPageMargins()->setBottom(0.75);
        
        $sheet->getPageSetup()->setFitToWidth(1);    
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getPageSetup()->setPrintArea('A1:'.$sheet->getHighestColumn().$sheet->getHighestRow());
        
        //Using Styles -- END

        $filename = 'Summary.xlsx';

        //redirect output to client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
    }

    public function excel_expense(Request $request){
        $start_date = $request->start_date_hidden;
        $end_date = $request->end_date_hidden;
        $date_counter = $request->date_counter_hidden;
        $branch = $request->branch_hidden;
        $company = $request->company_hidden;
        $expense_particular_type_total = [];
        $x = 0;

        //ALL DATA -- START

        $total = expense::
            when($company != 'All', function($query) use($company){
                $query->where('lead_company_type_id', $company);
            })
            ->when($branch != 'All', function($query) use ($branch){
                $query->where('branch_id', $branch);
            })
            ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                $query->whereBetween('date', [$start_date, $end_date]);
            })->sum('amount');

        $non_vat = expense::
            when($company != 'All', function($query) use($company){
                $query->where('lead_company_type_id', $company);
            })
            ->when($branch != 'All', function($query) use ($branch){
                $query->where('branch_id', $branch);
            })
            ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                $query->whereBetween('date', [$start_date, $end_date]);
            })->where('vat', 'NON-vat')->sum('amount');

        $vat = expense::
            when($company != 'All', function($query) use($company){
                $query->where('lead_company_type_id', $company);
            })
            ->when($branch != 'All', function($query) use ($branch){
                $query->where('branch_id', $branch);
            })
            ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                $query->whereBetween('date', [$start_date, $end_date]);
            })->where('vat', 'VAT')->sum('amount');

        $input_tax = expense::
            when($company != 'All', function($query) use($company){
                $query->where('lead_company_type_id', $company);
            })
            ->when($branch != 'All', function($query) use ($branch){
                $query->where('branch_id', $branch);
            })
            ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                $query->whereBetween('date', [$start_date, $end_date]);
            })->sum('input_tax');

        $expense_type = expense_type::all();
        $expense_type_count = $expense_type->count();

        $expense = expense::with('particular')
            ->when($company != 'All', function($query) use($company){
                $query->where('lead_company_type_id', $company);
            })
            ->when($branch != 'All', function($query) use ($branch){
                $query->where('branch_id', $branch);
            })
            ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                $query->whereBetween('date', [$start_date, $end_date]);
            })->groupBy('expense_particular_id')->get();

        foreach($expense_type as $et){
            $et->expense_type_total = expense::where('expense_type_id', $et->id)
                ->when($company != 'All', function($query) use($company){
                    $query->where('lead_company_type_id', $company);
                })
                ->when($branch != 'All', function($query) use ($branch){
                    $query->where('branch_id', $branch);
                })
                ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                    $query->whereBetween('date', [$start_date, $end_date]);
                })->sum('amount');
        }

        foreach($expense as $e){
            $e->total_invoice = expense::where('expense_particular_id', $e->expense_particular_id)
                ->when($company != 'All', function($query) use($company){
                    $query->where('lead_company_type_id', $company);
                })
                ->when($branch != 'All', function($query) use ($branch){
                    $query->where('branch_id', $branch);
                })
                ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                    $query->whereBetween('date', [$start_date, $end_date]);
                })->sum('amount');
            
            $e->non_vat_total = expense::where('expense_particular_id', $e->expense_particular_id)
                ->when($company != 'All', function($query) use($company){
                    $query->where('lead_company_type_id', $company);
                })
                ->when($branch != 'All', function($query) use ($branch){
                    $query->where('branch_id', $branch);
                })
                ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                    $query->whereBetween('date', [$start_date, $end_date]);
                })->where('vat', 'NON-VAT')->sum('amount');
            
            $e->vat_total = expense::where('expense_particular_id', $e->expense_particular_id)
                ->when($company != 'All', function($query) use($company){
                    $query->where('lead_company_type_id', $company);
                })
                ->when($branch != 'All', function($query) use ($branch){
                    $query->where('branch_id', $branch);
                })
                ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                    $query->whereBetween('date', [$start_date, $end_date]);
                })->where('vat', 'VAT')->sum('amount');
            
            $e->input_tax_total = expense::where('expense_particular_id', $e->expense_particular_id)
                ->when($company != 'All', function($query) use($company){
                    $query->where('lead_company_type_id', $company);
                })
                ->when($branch != 'All', function($query) use ($branch){
                    $query->where('branch_id', $branch);
                })
                ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                    $query->whereBetween('date', [$start_date, $end_date]);
                })->sum('input_tax');
        }

        foreach($expense as $e){
            $y = 0;
            foreach($expense_type as $et){
                $expense_particular_type_total[$x][$y] = expense::where('expense_type_id', $et->id)
                    ->where('expense_particular_id', $e->expense_particular_id)
                    ->when($company != 'All', function($query) use($company){
                        $query->where('lead_company_type_id', $company);
                    })
                    ->when($branch != 'All', function($query) use($branch){
                        $query->where('branch_id', $branch);
                    })
                    ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                        $query->whereBetween('date', [$start_date, $end_date]);
                    })->sum('amount');
                $y++;
            }
            $x++;
        }

        //ALL DATA -- END

        //STYLE -- START

        $headerStyleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];

        $centerStyleArray = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];

        $allStyleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];

        //STYLE -- END

        //Initialize Sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //Title
        $sheet->setCellValue('A1', 'Company')->mergeCells('A1:E1');
        $sheet->setCellValue('A2', 'Cash Disbursement Journal')->mergeCells('A2:E2');
        $sheet->setCellValue('A3', 'Month / Year')->mergeCells('A3:E3');

        //HEADER -- START

        //Header Defaults -- START

        $sheet->setCellValue('A5', 'Date')->mergeCells('A5:A6');
        $sheet->setCellValue('B5', 'Check Voucher')->mergeCells('B5:B6');
        $sheet->setCellValue('C5', 'Particulars')->mergeCells('C5:C6');
        $sheet->setCellValue('D5', 'TIN Number')->mergeCells('D5:D6');
        $sheet->setCellValue('E5', 'Address')->mergeCells('E5:E6');
        $sheet->setCellValue('F5', 'Total Invoice')->setCellValue('F6', $total);
        $sheet->setCellValue('G5', 'Non Vat')->setCellValue('G6', $non_vat);
        $sheet->setCellValue('H5', 'Vatable Amount')->setCellValue('H6', $vat);
        $sheet->setCellValue('I5', 'Input Tax')->setCellValue('I6', $input_tax);

        //Header Defaults -- END

        //Header Expense Types -- START

        $x = 10;

        foreach($expense_type as $et){
            $sheet->setCellValueByColumnAndRow($x, 5, $et->name);
            $sheet->setCellValueByColumnAndRow($x, 6, $et->expense_type_total);
            $x++;
        }

        //Header Expense Types -- END

        //HEADER -- END

        //BODY -- START
        
        $row = 7;
        $x = 0;
        foreach($expense as $e){
            $sheet->setCellValue('A'.$row, $e->date);
            $sheet->setCellValue('B'.$row, $e->check_voucher);
            $sheet->setCellValue('C'.$row, $e->particular->name);
            $sheet->setCellValue('D'.$row, $e->particular->tin);
            $sheet->setCellValue('E'.$row, $e->particular->address);
            $sheet->setCellValue('F'.$row, $e->total_invoice);
            $sheet->setCellValue('G'.$row, $e->non_vat_total);
            $sheet->setCellValue('H'.$row, $e->vat_total);
            $sheet->setCellValue('I'.$row, $e->input_tax_total);
            for($col = 'A'; $col != 'F'; $col++){
                $sheet->getStyle($col.$row)->getAlignment()->setWrapText(TRUE)->setVertical('center');
            }

            
            for($y = 0, $z = 10, $col = 'F'; $y < $expense_type_count; $y++, $z++, $col++){
                $sheet->setCellValueByColumnAndRow($z, $row, $expense_particular_type_total[$x][$y]);
                $sheet->getStyle($col.$row)->getAlignment()->setVertical('center');
            }
            $row++; $x++;
        }

        //BODY -- END

        $highestrow = $sheet->getHighestRow();
        $highestcolumn = $sheet->getHighestColumn();
        $highestcolumn++;
        for($x = 5; $x <= $highestrow; $x++){
            for($y = 'A'; $y != $highestcolumn; $y++){
                $sheet->getStyle($y.$x)->applyFromArray($allStyleArray);
            }
        }

        //FOOTER -- START
        $row+=2;

        $sheet->setCellValue('C'.$row, 'Expense Type')->getStyle('C'.$row)->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('D'.$row, 'Amount')->getStyle('D'.$row)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C'.$row)->applyFromArray($allStyleArray);
        $sheet->getStyle('D'.$row)->applyFromArray($allStyleArray);

        $row++;

        foreach($expense_type as $et){
            $sheet->setCellValue('C'.$row, $et->name);
            $sheet->setCellValue('D'.$row, $et->expense_type_total);
            $sheet->getStyle('C'.$row)->getAlignment()->setVertical('center')->setWrapText(TRUE);
            $sheet->getStyle('D'.$row)->getAlignment()->setVertical('center')->setWrapText(TRUE);
            $sheet->getStyle('C'.$row)->applyFromArray($allStyleArray);
            $sheet->getStyle('D'.$row)->applyFromArray($allStyleArray);
            $row++;
        }

        $sheet->setCellValue('C'.$row, 'TOTAL')->getStyle('C'.$row)->getAlignment()->setHorizontal('right');
        $sheet->setCellValue('D'.$row, $total);
        $sheet->getStyle('C'.$row)->applyFromArray($allStyleArray);
        $sheet->getStyle('D'.$row)->applyFromArray($allStyleArray);
        
        //FOOTER -- END

        $sheet->freezePane('F7');

        //Using Styles -- START

        //Set Title
        $sheet->getStyle('A1:A3')->getFont()->setSize(18);

        $highestcolumn = $sheet->getHighestColumn();
        $highestcolumn++;

        for($x = 'F'; $x != $highestcolumn; $x++){
            $sheet->getColumnDimension($x)->setWidth(15)->setAutoSize(FALSE);
            $sheet->getStyle($x.'5')->getAlignment()->setWrapText(TRUE);
        }

        for($x = 'A'; $x != $highestcolumn; $x++){
            $sheet->getStyle($x.'5')->applyFromArray($centerStyleArray);
        }


        $sheet->getColumnDimension('A')->setAutoSize(TRUE);
        $sheet->getColumnDimension('B')->setAutoSize(TRUE);
        $sheet->getColumnDimension('C')->setWidth(30)->setAutoSize(FALSE);
        $sheet->getColumnDimension('D')->setWidth(20)->setAutoSize(FALSE);
        $sheet->getColumnDimension('E')->setWidth(30)->setAutoSize(FALSE);

        $highestrow = $sheet->getHighestRow()+1;



        
        //Using Styles -- END

        $filename = 'Cash Disbursement.xlsx';

        //redirect output to client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
    }

    public function excel_fiscal_year(Request $request){
        $year = $request->year_hidden;
        $branch = $request->branch_hidden;
        $company = $request->company_hidden;
        $months = ['January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'];
        $i = 0;
        
        //ALL DATA -- START

        $type = expense_type::all();

        $expense_per_month = array();
        $total_per_type = array();
        $total_per_month = array();
        $total_all = 0;

        foreach($type as $t){
            for($x = 0; $x < 12; $x++){
                $expense_per_month[$i][$x] = expense::where('expense_type_id', $t->id)->whereMonth('date', $x+1)
                                                    ->when($company != 'All', function($query) use($company){
                                                        $query->where('lead_company_type_id', $company);
                                                    })
                                                    ->when($branch != 'All', function($query) use($branch){
                                                        $query->where('branch_id', $branch);
                                                    })->whereYear('date', $year)->sum('amount');
            }
            $total_per_type[$i] = expense::where('expense_type_id', $t->id)->whereYear('date', $year)
                                        ->when($company != 'All', function($query) use($company){
                                            $query->where('lead_company_type_id', $company);
                                        })
                                        ->when($branch != 'All', function($query) use($branch){
                                            $query->where('branch_id', $branch);
                                        })->sum('amount');
            $i++;
        }

        for($x = 0; $x < 12; $x++){
            $total_per_month[$x] = expense::whereMonth('date', $x+1)->whereYear('date', $year)
                                            ->when($company != 'All', function($query) use($company){
                                                $query->where('lead_company_type_id', $company);
                                            })
                                            ->when($branch != 'All', function($query) use($branch){
                                                $query->where('branch_id', $branch);
                                            })->sum('amount');
        }

        $total_all = expense::whereYear('date', $year)
                            ->when($company != 'All', function($query) use($company){
                                $query->where('lead_company_type_id', $company);
                            })
                            ->when($branch != 'All', function($query) use($branch){
                                $query->where('branch_id', $branch);
                            })->sum('amount');


        if($branch == 'All'){
            $branch = 'All Branches';
        }else{
            $branch = branch::find($branch);
            $branch = $branch->name;
        }
        if($company == 'All'){
            $company = '(LEAD | MILA | ANK)';
        }else{
            $company = lead_company_type::find($company);
            $company = '('.$company->name.')';
        }

        //ALL DATA -- END

        //STYLE -- START

        $headerStyleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];

        $centerStyleArray = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];

        $allStyleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];

        //STYLE -- END

        //Initialize Sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //TItle
        $sheet->setCellValue('A1', 'FISCAL YEAR '.$year.' - LEAD GROUP OF COMPANIES '.$company.' - ' . $branch)->mergeCells('A1:N1');

        //HEADER -- START

        $sheet->setCellValue('A3', 'Description')->getStyle('A3')->applyFromArray($headerStyleArray);

        foreach(range('B', 'M') as $key => $col){
            $sheet->setCellValue($col.'3', $months[$key])->getStyle($col.'3')->applyFromArray($headerStyleArray);
        }

        $sheet->setCellValue('N3', 'TOTAL')->getStyle('N3')->applyFromArray($headerStyleArray);

        //HEADER -- END

        //BODY -- START

        $row = 4;
        $x = 0;
        foreach($type as $t){
            $sheet->setCellValue('A'.$row, $t->name);
            foreach(range('B', 'M') as $key => $col){
                $sheet->setCellValue($col.$row, $expense_per_month[$x][$key]);
            }
            $sheet->setCellValue('N'.$row, $total_per_type[$x]);
            $row++; $x++;
        }

        $sheet->setCellValue('A'.$row, 'TOTAL');

        foreach(range('B', 'M') as $key => $col){
            $sheet->setCellValue($col.$row, $total_per_month[$key]);
        }

        $sheet->setCellValue('N'.$row, $total_all);

        //BODY -- END

        $sheet->freezePane('B4');

        //Using Styles -- START

        $sheet->getStyle('A1')->getFont()->setSize(12)->applyFromArray($centerStyleArray);
        $sheet->getStyle('A1')->applyFromArray($centerStyleArray);

        $sheet->getColumnDimension('A')->setWidth(40)->setAutoSize(FALSE);
        $sheet->getRowDimension('1')->setRowHeight(30);
        $sheet->getRowDimension('3')->setRowHeight(20);

        foreach(range('B', 'N') as $col){
            $sheet->getColumnDimension($col)->setWidth(14)->setAutoSize(FALSE);
        }

        for($x = 4; $x <= $row; $x++){
            foreach(range('A', 'N') as $col){
                $sheet->getStyle($col.$x)->applyFromArray($allStyleArray);
            }
        }

        //Uinsg Styles -- END

        if($company == '(LEAD | MILA | ANK)'){
            $company = '(LEAD - MILA - ANK)';
        }

        $filename = 'Fiscal Year - '.$year.' '.$company.' - '.$branch.'.xlsx';

        //redirect output to client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
    }

    public function excel_salary(Request $request){
        $start_date = $request->start_date_hidden;
        $end_date = $request->end_date_hidden;
        $date_counter = $request->date_counter_hidden;
        $branch = $request->branch_hidden;
        $company = $request->company_hidden;
        $status = $request->status_hidden;
        $role = $request->role_hidden;
       
        //ALL DATA -- START

        $salary = salary_monitoring::with('income', 'deduction', 'employee.branch', 'employee.company_type', 'employee.role')
                ->when($company != 'All', function($query) use($company) {
                    $query->whereHas('employee', function($query) use($company) {
                        $query->where('lead_company_type_id', $company);
                    });
                })->when($branch != 'All', function($query) use($branch) {
                    $query->whereHas('employee', function($query) use($branch) {
                        $query->where('branch_id', $branch);
                    });
                })->when($status != 'All', function($query) use($status) {
                    $query->whereHas('employee', function($query) use($status) {
                        $query->where('employment_status', $status);
                    });
                })->when($role, function($query) use($role) {
                    $query->whereHas('employee', function($query) use($role) {
                        $query->whereIn('role_id', $role);
                    });
                })->when($date_counter == 'true', function($query) use($start_date, $end_date) {
                    $query->whereBetween('pay_date', [$start_date, $end_date]);
                })->get();

        //ALL DATA -- END

        //STYLE -- START

        $companyStyleArray = [
            'font' => [
                'bold' => true,
                'size' => 10
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,   
            ]
        ];

        $receiptStyleArray = [
            'font' => [
                'bold' => true,
                'size' => 9
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,   
            ]
        ];

        $numberStyleArray = [
            'numberFormat' => [
                'formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ]
        ];

        $headerStyleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,   
            ]
        ];

        $daysStyleArray = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ];

        $borderStyleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];

        $borderMediumStyleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM
                ]
            ]
        ];

        //STYLE -- END

        //Initialize Sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $row = 1; $print_by_four = 0; $print_row = 0; $print_counter = 0;
        $salary_count = count($salary);
        $print_area = '';
        
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
        ->setPaperSize(PageSetup::PAPERSIZE_FOLIO);

        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setRight(0.7);
        $sheet->getPageMargins()->setLeft(0.25);
        $sheet->getPageMargins()->setBottom(0.75);
        $sheet->getColumnDimension('A')->setWidth(13);
        $sheet->getColumnDimension('H')->setWidth(13);

        foreach($salary as $s){
            //DEFAULTS
            $from = Carbon::parse($s->period_from)->format('F d, Y');
            $to = Carbon::parse($s->period_to)->format('F d, Y');
            $period = $from. ' - '. $to;
            $print_counter++; $print_by_four++;
            if($print_by_four == 1){
                $print_row = $row;
            }

            //Title
            switch($s->employee->company_type->name) {
                case 'LEAD':
                    $company_name = 'LEAD TRAINING AND BUSINESS SOLUTIONS';
                    break;
                case 'MILA':
                    $company_name = 'MANILA KOKUSAI ACADEMY INC.';
                    break;
                case 'ANK':
                    $company_name = 'ANK EDUCATION CONSULTANCY';
                    break;
            }

            // COMPANY TITLE
            $sheet->setCellValue('A'.$row, $company_name)->mergeCells('A'.$row.':F'.$row);
            $sheet->setCellValue('H'.$row, '=A'.$row)->mergeCells('H'.$row.':M'.$row);
            $sheet->getStyle('A'.$row)->applyFromArray($companyStyleArray);
            $sheet->getStyle('H'.$row)->applyFromArray($companyStyleArray);
            $row++;

            // SALARY RECEIPT
            $sheet->setCellValue('A'.$row, 'SALARY RECEIPT')->mergeCells('A'.$row.':F'.$row);
            $sheet->setCellValue('H'.$row, '=A'.$row)->mergeCells('H'.$row.':M'.$row);
            $sheet->getStyle('A'.$row)->applyFromArray($receiptStyleArray);
            $sheet->getStyle('H'.$row)->applyFromArray($receiptStyleArray);
            $row++;

            // EMPLOYEE NAME & RATE
            $name = $s->employee->lname . ', ' . $s->employee->fname . ' ' . $s->employee->mname;
            $position = $s->employee->role->name;
            $rate = $s->rate;
            $sheet->setCellValue('A'.$row, 'NAME of Employee')->setCellValue('H'.$row, '=A'.$row);
            $sheet->setCellValue('B'.$row, $name)->setCellValue('I'.$row, '=B'.$row);
            $sheet->mergeCells('B'.$row.':D'.$row)->mergeCells('I'.$row.':K'.$row);
            $sheet->getStyle('B'.$row.':D'.$row)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('I'.$row.':K'.$row)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->setCellValue('E'.$row, 'Rate:')->setCellValue('L'.$row, 'Rate:');
            $sheet->setCellValue('F'.$row, $rate)->setCellValue('M'.$row, '=F'.$row);
            $sheet->getStyle('F'.$row)->applyFromArray($numberStyleArray);
            $sheet->getStyle('M'.$row)->applyFromArray($numberStyleArray);
            $font_starting_row = $row;
            $row++;

            // POSITION & DAILY
            $daily = $s->daily;
            $sheet->setCellValue('A'.$row, 'Position/Dept.:')->setCellValue('H'.$row, '=A'.$row);
            $sheet->setCellValue('B'.$row, $position)->setCellValue('I'.$row, '=B'.$row);
            $sheet->mergeCells('B'.$row.':D'.$row)->mergeCells('I'.$row.':K'.$row);
            $sheet->getStyle('B'.$row.':D'.$row)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('I'.$row.':K'.$row)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->setCellValue('E'.$row, 'Daily:')->setCellValue('L'.$row, '=E'.$row);
            $sheet->setCellValue('F'.$row, $daily)->setCellValue('M'.$row, '=F'.$row);
            $sheet->getStyle('F'.$row)->applyFromArray($numberStyleArray);
            $sheet->getStyle('M'.$row)->applyFromArray($numberStyleArray);
            $row++;

            // PAY PERIOD
            $sheet->setCellValue('A'.$row, 'Pay Period:')->setCellValue('H'.$row, '=A'.$row);
            $sheet->mergeCells('B'.$row.':D'.$row)->mergeCells('I'.$row.':K'.$row);
            $sheet->setCellValue('B'.$row, $period)->setCellValue('I'.$row, '=B'.$row);
            $row++;
            $starting_row = $row;

            // HEADER
            $sheet->setCellValue('A'.$row, 'Particulars')->setCellValue('H'.$row, '=A'.$row);
            $sheet->setCellValue('B'.$row, 'No. of Days')->setCellValue('I'.$row, '=B'.$row);
            $sheet->setCellValue('C'.$row, 'Amount')->setCellValue('J'.$row, '=C'.$row);
            $sheet->setCellValue('D'.$row, 'Deductions')->setCellValue('K'.$row, '=D'.$row);
            $sheet->mergeCells('D'.$row.':E'.$row)->mergeCells('K'.$row.':L'.$row);
            $sheet->setCellValue('F'.$row, 'Amount')->setCellValue('M'.$row, '=F'.$row);
            foreach(range('A', 'M') as $key => $col){
                $sheet->getStyle($col.$row)->applyFromArray($headerStyleArray);
            }
            $body_starting_row = $row;
            $row++;
            $inc_ded_row = $row;
            $inc_row = $row;
            $ded_row = $row;

            //INCOME -- START

            // BASIC RATE
            $basic_days = $s->income->basic;
            $basic_amount = $this->calculate_all('basic', $s);
            $sheet->setCellValue('A'.$inc_row, 'Basic Rate')->setCellValue('H'.$inc_row, '=A'.$inc_row);
            $sheet->setCellValue('B'.$inc_row, $basic_days)->setCellValue('I'.$inc_row, '=B'.$inc_row);
            $sheet->setCellValue('C'.$inc_row, $basic_amount)->setCellValue('J'.$inc_row, '=C'.$inc_row);
            $row++; $inc_row++;

            // ACCOMMODATION ALLOWANCE
            $accom = $s->income->acc_allowance;
            $sheet->setCellValue('A'.$inc_row, 'Accommodation Allowance')->setCellValue('H'.$inc_row, '=A'.$inc_row);
            $sheet->setCellValue('C'.$inc_row, $accom)->setCellValue('J'.$inc_row, '=C'.$inc_row);
            $row++; $inc_row++;

            // TRANSPORATION ALLOWANCE
            $transpo = $s->income->transpo_allowance;
            $transpo_days = $s->income->transpo_days;
            $transpo_allowance = $s->income->transpo_allowance;
            $transpo = $transpo_days * $transpo_allowance;
            if($transpo){
                $sheet->setCellValue('A'.$inc_row, 'Transpo Allowance')->setCellValue('H'.$inc_row, '=A'.$inc_row);
                $sheet->setCellValue('B'.$inc_row, $transpo_days)->setCellValue('I'.$inc_row, '=B'.$inc_row);
                $sheet->setCellValue('C'.$inc_row, $transpo)->setCellValue('J'.$inc_row, '=C'.$inc_row);
                $row++; $inc_row++;
            }

            // COLA
            $cola = $s->income->cola;
            if($cola){
                $sheet->setCellValue('A'.$inc_row, 'COLA')->setCellValue('H'.$inc_row, '=A'.$inc_row);
                $sheet->setCellValue('C'.$inc_row, $cola)->setCellValue('J'.$inc_row, '=C'.$inc_row);
                $row++; $inc_row++;
            }

            // MARKETING COMMISSION
            $mktg = $s->income->market_comm;
            if($mktg){
                $sheet->setCellValue('A'.$inc_row, 'Marketing Commission')->setCellValue('H'.$inc_row, '=A'.$inc_row);
                $sheet->setCellValue('C'.$inc_row, $mktg)->setCellValue('J'.$inc_row, '=C'.$inc_row);
                $row++; $inc_row++;
            }

            // JAPAN COMMISSION
            $jap = $s->income->jap_comm;
            if($jap){
                $sheet->setCellValue('A'.$inc_row, 'Japan Commission')->setCellValue('H'.$inc_row, '=A'.$inc_row);
                $sheet->setCellValue('C'.$inc_row, $mktg)->setCellValue('J'.$inc_row, '=C'.$inc_row);
                $row++; $inc_row++;
            }

            // REG OT
            $reg_ot_hours = $s->income->reg_ot;
            $reg_ot_amount = ($reg_ot_hours) ? $this->calculate_all('reg_ot', $s) : '';
            $sheet->setCellValue('A'.$inc_row, 'Reg. OT')->setCellValue('H'.$inc_row, '=A'.$inc_row);
            $sheet->setCellValue('B'.$inc_row, $reg_ot_hours)->setCellValue('I'.$inc_row, '=B'.$inc_row);
            $sheet->setCellValue('C'.$inc_row, $reg_ot_amount)->setCellValue('J'.$inc_row, '=C'.$inc_row);
            $row++; $inc_row++; 

            // RD OT
            $rd_ot_hours = $s->income->rd_ot;
            $rd_ot_amount = ($rd_ot_hours) ? $this->calculate_all('rd_ot', $s) : '';
            $sheet->setCellValue('A'.$inc_row, 'RD OT')->setCellValue('H'.$inc_row, '=A'.$inc_row);
            $sheet->setCellValue('B'.$inc_row, $rd_ot_hours)->setCellValue('I'.$inc_row, '=B'.$inc_row);
            $sheet->setCellValue('C'.$inc_row, $rd_ot_amount)->setCellValue('J'.$inc_row, '=C'.$inc_row);
            $row++; $inc_row++;

            //  THIRTEENTH MONTH
            $thirteenth = $s->income->thirteenth;
            $sheet->setCellValue('A'.$inc_row, '13th Month')->setCellValue('H'.$inc_row, '=A'.$inc_row);
            $sheet->setCellValue('C'.$inc_row, $thirteenth)->setCellValue('J'.$inc_row, '=C'.$inc_row);
            $row++; $inc_row++;

            // ADJUSTMENTS
            $adjustments = $s->income->adjustments;
            $sheet->setCellValue('A'.$inc_row, 'Adjustments')->setCellValue('H'.$inc_row, '=A'.$inc_row);
            $sheet->setCellValue('C'.$inc_row, $adjustments)->setCellValue('J'.$inc_row, '=C'.$inc_row);
            $adjustment_cell = 'C'.$inc_row;
            $row++; $inc_row++;

            // LEG HOLIDAY
            $leg_days = $s->income->leg_hol;
            $leg_amount = ($leg_days) ? $this->calculate_all('leg', $s) : '';
            $sheet->setCellValue('A'.$inc_row, 'Leg Holiday')->setCellValue('H'.$inc_row, '=A'.$inc_row);
            $sheet->setCellValue('B'.$inc_row, $leg_days)->setCellValue('I'.$inc_row, '=B'.$inc_row);
            $sheet->setCellValue('C'.$inc_row, $leg_amount)->setCellValue('J'.$inc_row, '=C'.$inc_row);
            $row++; $inc_row++;

            // SPCL HOLIDAY
            $spcl_days = $s->income->spcl_hol;
            $spcl_amount = ($spcl_days) ? $this->calculate_all('spcl', $s) : '';
            $sheet->setCellValue('A'.$inc_row, 'Spcl Holiday')->setCellValue('H'.$inc_row, '=A'.$inc_row);
            $sheet->setCellValue('B'.$inc_row, $spcl_days)->setCellValue('I'.$inc_row, '=B'.$inc_row);
            $sheet->setCellValue('C'.$inc_row, $spcl_amount)->setCellValue('J'.$inc_row, '=C'.$inc_row);
            $row++; $inc_row++; 

            // LEG HOLIDAY OT
            $leg_ot_hours = $s->income->leg_hol_ot;
            $leg_ot_amount = ($leg_ot_hours) ? $this->calculate_all('leg_ot', $s) : '';
            $sheet->setCellValue('A'.$inc_row, 'Leg Hol OT')->setCellValue('H'.$inc_row, '=A'.$inc_row);
            $sheet->setCellValue('B'.$inc_row, $leg_ot_hours)->setCellValue('I'.$inc_row, '=B'.$inc_row);
            $sheet->setCellValue('C'.$inc_row, $leg_ot_amount)->setCellValue('J'.$inc_row, '=C'.$inc_row);
            $row++; $inc_row++; 

            // SPCL HOLIDAY OT
            $spcl_ot_hours = $s->income->spcl_hol_ot;
            $spcl_ot_amount = ($spcl_ot_hours) ? $this->calculate_all('spcl_ot', $s) : '';
            $sheet->setCellValue('A'.$inc_row, 'Spcl Hol OT')->setCellValue('H'.$inc_row, '=A'.$inc_row);
            $sheet->setCellValue('B'.$inc_row, $spcl_ot_hours)->setCellValue('I'.$inc_row, '=B'.$inc_row);
            $sheet->setCellValue('C'.$inc_row, $spcl_ot_amount)->setCellValue('J'.$inc_row, '=C'.$inc_row);
            $row++; $inc_row++;
            

            // INCOME -- END

            // DEDUCTION -- START

            // CASH ADVANCE
            $cash_advance = $s->deduction->cash_advance;
            $sheet->setCellValue('D'.$ded_row, 'Cash Advance')->setCellValue('K'.$ded_row, '=D'.$ded_row);
            $sheet->setCellValue('E'.$ded_row, '')->setCellValue('L'.$ded_row, '=E'.$ded_row);
            $sheet->setCellValue('F'.$ded_row, $cash_advance)->setCellValue('M'.$ded_row, '=F'.$ded_row);
            $ded_row++;

            // ABSENT
            $absence_days = $s->deduction->absence;
            $absence_amount = ($absence_days) ? $this->calculate_all('absence', $s) : '';
            $sheet->setCellValue('D'.$ded_row, 'Absent')->setCellValue('K'.$ded_row, '=D'.$ded_row);
            $sheet->setCellValue('E'.$ded_row, $absence_days)->setCellValue('L'.$ded_row, '=E'.$ded_row);
            $sheet->setCellValue('F'.$ded_row, $absence_amount)->setCellValue('M'.$ded_row, '=F'.$ded_row);
            $ded_row++;

            // LATE
            $late_hours = $s->deduction->late;
            $late_amount = ($late_hours) ? $this->calculate_all('late', $s) : '';
            $sheet->setCellValue('D'.$ded_row, 'Late')->setCellValue('K'.$ded_row, '=D'.$ded_row);
            $sheet->setCellValue('E'.$ded_row, $late_hours)->setCellValue('L'.$ded_row, '=E'.$ded_row);
            $sheet->setCellValue('F'.$ded_row, $late_amount)->setCellValue('M'.$ded_row, '=F'.$ded_row);
            $ded_row++;

            // SSS
            $sss = $s->deduction->sss;
            $sheet->setCellValue('D'.$ded_row, 'SSS')->setCellValue('K'.$ded_row, '=D'.$ded_row);
            //$sheet->mergeCells('D'.$ded_row.':E'.$ded_row)->mergeCells('K'.$ded_row.':L'.$ded_row);
            $sheet->setCellValue('F'.$ded_row, $sss)->setCellValue('M'.$ded_row, '=F'.$ded_row);
            $ded_row++;

            // PHIC
            $phic = $s->deduction->phic;
            $sheet->setCellValue('D'.$ded_row, 'PHIC')->setCellValue('K'.$ded_row, '=D'.$ded_row);
            //$sheet->mergeCells('D'.$ded_row.':E'.$ded_row)->mergeCells('K'.$ded_row.':L'.$ded_row);
            $sheet->setCellValue('F'.$ded_row, $phic)->setCellValue('M'.$ded_row, '=F'.$ded_row);
            $ded_row++;

            // HDMF
            $hdmf = $s->deduction->hdmf;
            $sheet->setCellValue('D'.$ded_row, 'HDMF')->setCellValue('K'.$ded_row, '=D'.$ded_row);
            ///$sheet->mergeCells('D'.$ded_row.':E'.$ded_row)->mergeCells('K'.$ded_row.':L'.$ded_row);
            $sheet->setCellValue('F'.$ded_row, $hdmf)->setCellValue('M'.$ded_row, '=F'.$ded_row);
            $ded_row++;

            // UNDERTIME
            $undertime_hours = $s->deduction->undertime;
            $undertime_amount = ($undertime_hours) ? $this->calculate_all('undertime', $s) : '';
            $sheet->setCellValue('D'.$ded_row, 'Undertime')->setCellValue('K'.$ded_row, '=D'.$ded_row);
            $sheet->setCellValue('E'.$ded_row, $undertime_hours)->setCellValue('L'.$ded_row, '=E'.$ded_row);
            $sheet->setCellValue('F'.$ded_row, $undertime_amount)->setCellValue('M'.$ded_row, '=F'.$ded_row);
            $ded_row++;

            // OTHERS
            $others = $s->deduction->others;
            if($others){
                $sheet->setCellValue('D'.$ded_row, 'Others')->setCellValue('K'.$ded_row, '=D'.$ded_row);
                $sheet->mergeCells('D'.$ded_row.':E'.$ded_row)->mergeCells('K'.$ded_row.':L'.$ded_row);
                $sheet->setCellValue('F'.$ded_row, $others)->setCellValue('M'.$ded_row, '=F'.$ded_row);
                $ded_row++;
            }

            // MANDATORY ALLOCATION
            $man_al = $s->deduction->man_allocation;
            if($man_al){
                $sheet->setCellValue('D'.$ded_row, 'Mandatory Allocation')->setCellValue('K'.$ded_row, '=D'.$ded_row);
                $sheet->mergeCells('D'.$ded_row.':E'.$ded_row)->mergeCells('K'.$ded_row.':L'.$ded_row);
                $sheet->setCellValue('F'.$ded_row, $man_al)->setCellValue('M'.$ded_row, '=F'.$ded_row);
                $ded_row++;
            }

            // TAX
            $tax = $s->deduction->tax;
            if($tax){
                $sheet->setCellValue('D'.$ded_row, 'Tax')->setCellValue('K'.$ded_row, '=D'.$ded_row);
                $sheet->mergeCells('D'.$ded_row.':E'.$ded_row)->mergeCells('K'.$ded_row.':L'.$ded_row);
                $sheet->setCellValue('F'.$ded_row, $tax)->setCellValue('M'.$ded_row, '=F'.$ded_row);
                $ded_row++;
            }
            
            if($inc_row <= $ded_row && $s->deduction->wfh){
                $inc_row = $ded_row+2;
                $higher_row = $ded_row+2;
            }else if($inc_row <= $ded_row && $s->deduction->wfh){
                $inc_row = $ded_row+1;
                $higher_row = $ded_row+1;
            }else{
                $higher_row = $inc_row;
            }

            // GROSS PAY
            $sheet->setCellValue('A'.$inc_row, 'Gross Pay')->setCellValue('H'.$inc_row, '=A'.$inc_row);
            $sheet->setCellValue('C'.$inc_row, '=sum(C'.$inc_ded_row.':C'.($inc_row-1).')')->setCellValue('J'.$inc_row, '=C'.$inc_row);

            // WFH
            if($s->deduction->wfh && $s->deduction->wfh > 0){
                $wfh = $s->deduction->wfh;
                $sheet->setCellValue('D'.$ded_row, 'WFH')->setCellValue('K'.$ded_row, '=D'.$ded_row);
                $sheet->setCellValue('E'.$ded_row, $wfh)->setCellValue('L'.$ded_row, '=E'.$ded_row);
                $sheet->setCellValue('F'.$ded_row, '=(C'.$inc_row.' - sum(F'.$inc_ded_row.':F'.($ded_row-1).') - '.$adjustment_cell.') * .'.(100-$wfh))->setCellValue('M'.$ded_row, '=F'.$ded_row);
                $ded_row++;
            }

            // TOTAL DEDUCTIONS
            $sheet->setCellValue('D'.$ded_row, 'Total Deduction')->setCellValue('K'.$ded_row, '=D'.$ded_row);
            $sheet->mergeCells('D'.$ded_row.':E'.$ded_row)->mergeCells('K'.$ded_row.':L'.$ded_row);
            $sheet->setCellValue('F'.$ded_row, '=sum(F'.$inc_ded_row.':F'.($ded_row-1).')')->setCellValue('M'.$ded_row, '=F'.$ded_row);

            // DEDUCTION -- END

            if($inc_row <= $ded_row){
                $inc_row = $ded_row+1;
                $higher_row = $ded_row+1;
            }else{
                $higher_row = $inc_row;
            }

            for($x = $ded_row; $x < $higher_row; $x++){
                $sheet->mergeCells('D'.$x.':E'.$x)->mergeCells('K'.$x.':L'.$x);
            }

            // NET PAY
            $sheet->setCellValue('D'.$inc_row, 'Net Pay')->setCellValue('K'.$inc_row, '=D'.$inc_row);
            $sheet->mergeCells('D'.$inc_row.':E'.$inc_row)->mergeCells('K'.$inc_row.':L'.$inc_row);
            $net = '=C'.$inc_row.' - F'.$ded_row;
            $sheet->setCellValue('F'.$inc_row, $net)->setCellValue('M'.$inc_row, '=F'.$inc_row);
            
            $footer_row = $inc_row;
            $row++; $footer_row++;
            
            $net = number_format($sheet->getCell('F'.$inc_row)->getCalculatedValue(), 2, '.', '');
            $received = 'Received the amount of '.$net.' pesos as my salary for the period of';
            $sheet->setCellValue('A'.$footer_row, $received)->setCellValue('H'.$footer_row, '=A'.$footer_row);
            $row++; $footer_row++;

            $release = Carbon::parse($s->pay_date)->format('F d, Y');
            $date_release = $period.' this '.$release;
            $sheet->setCellValue('A'.$footer_row, $date_release)->setCellValue('H'.$footer_row, '=A'.$footer_row);;
            $row++; $footer_row++;

            $sheet->setCellValue('E'.$footer_row, 'Received By:')->setCellValue('L'.$footer_row, 'Received By:');
            $row++; $footer_row++;
            $sheet->getStyle('E'.$footer_row.':F'.$footer_row)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('L'.$footer_row.':M'.$footer_row)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

            $row += 2;

            // STYLES -- START

            for($x = $body_starting_row; $x <= $higher_row; $x++){
                foreach(range('A', 'F') as $key => $col){
                    $sheet->getStyle($col.$x)->applyFromArray($borderStyleArray);
                }
                foreach(range('H', 'M') as $key => $col){
                    $sheet->getStyle($col.$x)->applyFromArray($borderStyleArray);
                }
            }

            $sheet->getStyle('A'.$body_starting_row.':F'.$higher_row)->applyFromArray($borderMediumStyleArray);
            $sheet->getStyle('H'.$body_starting_row.':M'.$higher_row)->applyFromArray($borderMediumStyleArray);

            $sheet->getStyle('A'.$font_starting_row.':M'.($footer_row-2))->getFont()->setSize(8);

            $sheet->getStyle('C'.$inc_ded_row.':C'.$higher_row)->applyFromArray($numberStyleArray);
            $sheet->getStyle('F'.$inc_ded_row.':F'.$higher_row)->applyFromArray($numberStyleArray);
            $sheet->getStyle('J'.$inc_ded_row.':J'.$higher_row)->applyFromArray($numberStyleArray);
            $sheet->getStyle('M'.$inc_ded_row.':M'.$higher_row)->applyFromArray($numberStyleArray);

            $sheet->getStyle('B'.$inc_ded_row.':B'.$higher_row)->applyFromArray($daysStyleArray);
            $sheet->getStyle('E'.$inc_ded_row.':E'.$higher_row)->applyFromArray($daysStyleArray);
            $sheet->getStyle('I'.$inc_ded_row.':I'.$higher_row)->applyFromArray($daysStyleArray);
            $sheet->getStyle('L'.$inc_ded_row.':L'.$higher_row)->applyFromArray($daysStyleArray);

            // STYLES -- END

            // PRINT

            if($print_by_four == 4 || $print_counter == $salary_count){
                $print_area .= 'A'.$print_row.':M'.($row-1);
                if($print_counter != $salary_count){
                    $print_area .= ',';
                }
                $print_by_four = 0;
            }
        }

        if($print_area != ''){
            $sheet->getPageSetup()->setPrintArea($print_area);
        }

        $filename = 'Payslip.xlsx';
        
        //redirect output to client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
    }

    
    public function excel_salary_summary(Request $request){
        $start_date = $request->s_start_date_hidden;
        $end_date = $request->s_end_date_hidden;
        $date_counter = $request->s_date_counter_hidden;
        $branch = $request->s_branch_hidden;
        $company = $request->s_company_hidden;
        $status = $request->s_status_hidden;
        $role = $request->s_role_hidden;

        //ALL DATA -- START

        $branch_query = branch::orderBy('name', 'asc')->get();

        $salary = emp_salary::
        join('employees', 'employees.id', '=', 'emp_salary.emp_id')
        ->join('branches', 'branches.id', '=', 'employees.branch_id')->orderBy('branches.name', 'asc')->orderBy('role_id', 'asc')
        ->with('monitoring.income', 'monitoring.deduction', 'employee.branch', 'employee.company_type', 'employee.role')
        ->when($company != 'All', function($query) use($company) {
            $query->whereHas('employee', function($query) use($company) {
                $query->where('lead_company_type_id', $company);
            });
        })->when($branch != 'All', function($query) use($branch) {
            $query->whereHas('employee', function($query) use($branch) {
                $query->where('branch_id', $branch);
            });
        })->when($status != 'All', function($query) use($status) {
            $query->whereHas('employee', function($query) use($status) {
                $query->where('employment_status', $status);
            });
        })->when($role, function($query) use($role) {
            $query->whereHas('employee', function($query) use($role) {
                $query->whereIn('role_id', $role);
            });
        })->get();

        //ALL DATA -- END
        
        //STYLE -- START

        $companyStyleArray = [
            'font' => [
                'bold' => true,
                'size' => 10
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,   
            ]
        ];

        $numberStyleArray = [
            'numberFormat' => [
                'formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ]
        ];

        $headerStyleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,  
                'wrapText' => true,   
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM
                ]
            ]
        ];

        $centerStyleArray = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,   
            ]
        ];

        $borderStyleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];

        $highlightStyleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM
                ]
            ]
        ];
        
        //STYLE -- END

        //Initialize Sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $row = 1; $print_by_four = 0; $print_row = 0; $print_counter = 0;
        $salary_count = count($salary);
        $print_area = '';
        
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
        ->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
        
        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setRight(0.7);
        $sheet->getPageMargins()->setLeft(0.25);
        $sheet->getPageMargins()->setBottom(0.75);

        //HEADER -- START
        //Title

        if($company != 'All'){
            $company = lead_company_type::find($company);
            switch($company->name) {
                case 'LEAD':
                    $company = 'LEAD TRAINING AND BUSINESS SOLUTIONS';
                    break;
                case 'MILA':
                    $company = 'MANILA KOKUSAI ACADEMY INC.';
                    break;
                case 'ANK':
                    $company = 'ANK EDUCATION CONSULTANCY';
                    break;
            }
        }else{
            $company = 'LEAD GROUP OF COMPANIES';
        }


        $sheet->setCellValue('A1', $company)->mergeCells('A1'.':AQ1');
        $sheet->getStyle('A1')->applyFromArray($companyStyleArray);
        
        $sheet->setCellValue('A2', 'PAYROLL MONITORING FORM')->mergeCells('A2'.':AQ2');
        $sheet->getStyle('A2')->applyFromArray($companyStyleArray);

        $sheet->setCellValue('B3', 'DATE COVERED:');
        $sheet->setCellValue('B4', 'DATE PREPARED:');

        $sheet->setCellValue('A6', 'No.')->mergeCells('A6:A7');
        $sheet->setCellValue('B6', 'Name of Employee')->mergeCells('B6:B7');
        $sheet->setCellValue('C6', 'Branch')->mergeCells('C6:C7');
        $sheet->setCellValue('D6', 'Mode of Salary')->mergeCells('D6:D7');
        $sheet->setCellValue('E6', 'Mode of Employment')->mergeCells('E6:E7');
        $sheet->setCellValue('F6', 'Daily Rate')->mergeCells('F6:F7');
        $sheet->setCellValue('G6', 'Monthly Rate')->mergeCells('G6:G7');
        $sheet->setCellValue('H6', 'Days Worked')->mergeCells('H6:H7');
        $sheet->setCellValue('I6', 'Total')->mergeCells('I6:I7');
        $sheet->setCellValue('J6', 'Regular OT')->mergeCells('J6:K6');
        $sheet->setCellValue('J7', 'Hrs')->setCellValue('K7', 'Amt');
        $sheet->setCellValue('L6', 'RD OT')->mergeCells('L6:M6');
        $sheet->setCellValue('L7', 'Hrs')->setCellValue('M7', 'Amt');
        $sheet->setCellValue('N6', 'Spcl Hol')->mergeCells('N6:O6');
        $sheet->setCellValue('N7', 'Hrs')->setCellValue('O7', 'Amt');
        $sheet->setCellValue('P6', 'Spcl Hol OT')->mergeCells('P6:Q6');
        $sheet->setCellValue('P7', 'Hrs')->setCellValue('Q7', 'Amt');
        $sheet->setCellValue('R6', 'Legal Hol')->mergeCells('R6'.':S6');
        $sheet->setCellValue('R7', 'Hrs')->setCellValue('S7', 'Amt');
        $sheet->setCellValue('T6', 'Legal Hol OT')->mergeCells('T6:U6');
        $sheet->setCellValue('T7', 'Hrs')->setCellValue('U7', 'Amt');
        $sheet->setCellValue('V6', 'Accommodation Allowance')->mergeCells('V6:V7');
        $sheet->setCellValue('W6', 'Transpo Allowance')->mergeCells('W6:W7');
        $sheet->setCellValue('X6', 'COLA')->mergeCells('X6:X7');
        $sheet->setCellValue('Y6', 'Mktg Comm')->mergeCells('Y6:Y7');
        $sheet->setCellValue('Z6', 'Japan Comm')->mergeCells('Z6:Z7');
        $sheet->setCellValue('AA6', '13th Month')->mergeCells('AA6:AA7');
        $sheet->setCellValue('AB6', 'Adjustments')->mergeCells('AB6:AB7');
        $sheet->setCellValue('AC6', 'Gross Salary')->mergeCells('AC6:AC7');
        $sheet->setCellValue('AD6', 'Statutory Deductions')->mergeCells('AD6:AH6');
        $sheet->setCellValue('AD7', 'SSS')->setCellValue('AE7', 'PHIC');
        $sheet->setCellValue('AF7', 'HDMF')->setCellValue('AG7', 'Tax')->setCellValue('AH7', 'CA');
        $sheet->setCellValue('AI6', 'Other Deductions')->mergeCells('AI6:AM6');
        $sheet->setCellValue('AI7', 'Mandatory')->setCellValue('AJ7', 'UT');
        $sheet->setCellValue('AK7', 'Absent')->setCellValue('AL7', 'Late')->setCellValue('AM7', 'Others');
        $sheet->setCellValue('AN6', 'WFH')->mergeCells('AN6:AN7');
        $sheet->setCellValue('AO6', 'Total Deductions')->mergeCells('AO6:AO7');
        $sheet->setCellValue('AP6', 'Net Salary')->mergeCells('AP6:AP7');
        $sheet->setCellValue('AQ6', 'Remarks')->mergeCells('AQ6:AQ7');

        //HEADER -- END

        //BODY -- START

        $no = 1;
        $row = 8;

        $rd_counter = false;
        $spcl_counter = false;
        $spcl_ot_counter = false;
        $legal_counter = false;
        $legal_ot_counter = false;
        $cola_counter = false;
        $mktg_counter = false;
        $jap_counter = false;


        foreach($salary as $s){
            $monitoring_counter = false;
            if($s->monitoring->isNotEmpty()){
                if($date_counter == 'true'){//check if pay date is in date range
                    foreach($s->monitoring as $m){
                        $start = Carbon::parse($start_date);
                        $end = Carbon::parse($end_date);
                        $pay_date = Carbon::parse($m->pay_date);
                        if($pay_date->between($start, $end)){
                            $monitoring_counter = true;
                        }
                        if($m->income->rd_ot){$rd_counter = true;}
                        if($m->income->spcl_hol){$spcl_counter = true;}
                        if($m->income->spcl_hol_ot){$spcl_ot_counter = true;}
                        if($m->income->leg_hol){$legal_counter = true;}
                        if($m->income->leg_hol_ot){$legal_ot_counter = true;}
                        if($m->income->cola){$cola_counter = true;}
                        if($m->income->market_comm){$mktg_counter = true;}
                        if($m->income->jap_comm){$jap_counter = true;}
                    }
                }
                if($monitoring_counter == true){
                    foreach($s->monitoring as $m){
                        if($date_counter == 'true'){
                            $start = Carbon::parse($start_date);
                            $end = Carbon::parse($end_date);
                            $pay_date = Carbon::parse($m->pay_date);
                            if(!$pay_date->between($start, $end)){
                                continue;
                            }
                        }
                        $sheet->setCellValue('A'.$row, $no);
                        $sheet->setCellValue('B'.$row, $s->employee->lname.', '.$s->employee->fname);
                        $sheet->setCellValue('C'.$row, $s->employee->branch->name);
                        $sheet->setCellValue('D'.$row, $s->sal_type);
                        $sheet->setCellValue('E'.$row, $s->employee->employment_type);
                        $sheet->setCellValue('F'.$row, $m->daily);
                        $sheet->setCellValue('G'.$row, $m->rate);
                        $sheet->setCellValue('H'.$row, $m->income->basic);
                        if($m->emp_id == 1){
                            $sheet->setCellValue('I'.$row, '=round(G'.$row.', 2)');
                        }
                        else if($m->sal_type == 'Monthly'){
                            $sheet->setCellValue('I'.$row, '=round(G'.$row.'/2, 2)');
                        }else if($m->sal_type == 'Daily'){
                            $sheet->setCellValue('I'.$row, '=round(F'.$row.'*H'.$row.', 2)');
                        }
                        $sheet->setCellValue('J'.$row, $m->income->reg_ot);
                        $sheet->setCellValue('K'.$row, '=round(F'.$row.'/8*1.25*J'.$row.', 2)');
                        $sheet->setCellValue('L'.$row, $m->income->rd_ot);
                        $sheet->setCellValue('M'.$row, '=round(F'.$row.'/8*1.3*L'.$row.', 2)');
                        $sheet->setCellValue('N'.$row, $m->income->spcl_hol);
                        $sheet->setCellValue('O'.$row, '=round((F'.$row.'*N'.$row.')+(F'.$row.'*0.3*N'.$row.'), 2)');
                        $sheet->setCellValue('P'.$row, $m->income->spcl_hol_ot);
                        $sheet->setCellValue('Q'.$row, '=round((F'.$row.'/8*P'.$row.')+(F'.$row.'/8*0.3*P'.$row.'), 2)');
                        $sheet->setCellValue('R'.$row, $m->income->leg_hol);
                        $sheet->setCellValue('S'.$row, '=round(F'.$row.'*2*R'.$row.', 2)');
                        $sheet->setCellValue('T'.$row, $m->income->leg_hol_ot);
                        $sheet->setCellValue('U'.$row, '=round(F'.$row.'/8*2*T'.$row.', 2)');
                        $sheet->setCellValue('V'.$row, $m->income->acc_allowance);
                        if($m->income->transpo_days){
                            $sheet->setCellValue('W'.$row, $m->income->transpo_allowance * $m->income->transpo_days);
                        }
                        $sheet->setCellValue('X'.$row, $m->income->cola);
                        $sheet->setCellValue('Y'.$row, $m->income->market_comm);
                        $sheet->setCellValue('Z'.$row, $m->income->jap_comm);
                        $sheet->setCellValue('AA'.$row, $m->income->thirteenth);
                        $sheet->setCellValue('AB'.$row, $m->income->adjustments);
                        $gross = '=round(sum(I'.$row.',K'.$row.',M'.$row.',O'.$row.',Q'.$row.',S'.$row
                        .',U'.$row.',V'.$row.':AB'.$row.'), 2)';
                        $sheet->setCellValue('AC'.$row, $gross);
                        
                        $sheet->setCellValue('AD'.$row, $m->deduction->sss);
                        $sheet->setCellValue('AE'.$row, $m->deduction->phic);
                        $sheet->setCellValue('AF'.$row, $m->deduction->hdmf);
                        $sheet->setCellValue('AG'.$row, $m->deduction->tax);
                        $sheet->setCellValue('AH'.$row, $m->deduction->cash_advance);
                        $sheet->setCellValue('AI'.$row, $m->deduction->man_allocation);
                        if($m->deduction->undertime){
                            $undertime = number_format($m->deduction->undertime * ($m->daily/8), 2, '.', '');
                            $sheet->setCellValue('AJ'.$row, $undertime);
                        }
                        if($m->deduction->absence){
                            $absence = number_format($m->deduction->absence * $m->daily, 2, '.', '');
                            $sheet->setCellValue('AK'.$row, $absence);
                        }
                        if($m->deduction->late){
                            $late = number_format($m->deduction->late * ($m->daily/8), 2, '.', '');
                            $sheet->setCellValue('AL'.$row, $late);
                        }
                        $sheet->setCellValue('AM'.$row, $m->deduction->others);
                        if($m->deduction->wfh){
                            $wfh = '=round((AC'.$row.'-AB'.$row.'-sum(AD'.$row.':AM'.$row.'))*'.((100 - $m->deduction->wfh)/100).', 2)';
                            $sheet->setCellValue('AN'.$row, $wfh);
                        }
                        $sheet->setCellValue('AO'.$row, '=round(sum(AD'.$row.':AN'.$row.'), 2)');
                        $sheet->setCellValue('AP'.$row, '=round(AC'.$row.'-AO'.$row.', 2)');
                        $no++; $row++;
                    }
                }
            }
            if($monitoring_counter == false){
                if($s->employee->id == 2){
                    continue;
                }
                $sheet->setCellValue('A'.$row, $no);
                $sheet->setCellValue('B'.$row, $s->employee->lname.', '.$s->employee->fname);
                $sheet->setCellValue('C'.$row, $s->employee->branch->name);
                $sheet->setCellValue('D'.$row, $s->sal_type);
                $sheet->setCellValue('E'.$row, $s->employee->employment_type);
                $sheet->setCellValue('F'.$row, $s->daily);
                $sheet->setCellValue('G'.$row, $s->rate);
                $sheet->setCellValue('H'.$row, 0);
                $sheet->setCellValue('I'.$row, 0);
                $sheet->setCellValue('K'.$row, '=round(F'.$row.'/8*1.25*J'.$row.', 2)');
                $sheet->setCellValue('M'.$row, '=round(F'.$row.'/8*1.3*L'.$row.', 2)');
                $sheet->setCellValue('O'.$row, '=round((F'.$row.'*N'.$row.')+(F'.$row.'*0.3*N'.$row.'), 2)');
                $sheet->setCellValue('Q'.$row, '=round((F'.$row.'/8*P'.$row.')+(F'.$row.'/8*0.3*P'.$row.'), 2)');
                $sheet->setCellValue('S'.$row, '=round(F'.$row.'*2*R'.$row.', 2)');
                $sheet->setCellValue('U'.$row, '=round(F'.$row.'/8*2*T'.$row.', 2)');
                $gross = '=round(sum(I'.$row.',K'.$row.',M'.$row.',O'.$row.',Q'.$row.',S'.$row
                .',U'.$row.',V'.$row.':AB'.$row.'), 2)';
                $sheet->setCellValue('AC'.$row, $gross);
                $sheet->setCellValue('AO'.$row, '=round(sum(AD'.$row.':AN'.$row.'), 2)');
                $sheet->setCellValue('AP'.$row, '=round(AC'.$row.'-AO'.$row.', 2)');
                $no++; $row++;
            }
        }
        
        $last_row = $row-1;
        $total_row = $row;

        $sheet->setCellValue('A'.$row, 'TOTAL')->mergeCells('A'.$row.':B'.$row);
        $sheet->setCellValue('G'.$row, '=sum(G8:G'.$last_row.')');
        $sheet->setCellValue('I'.$row, '=sum(I8:I'.$last_row.')');
        $sheet->setCellValue('K'.$row, '=sum(K8:K'.$last_row.')');
        $sheet->setCellValue('M'.$row, '=sum(M8:M'.$last_row.')');
        $sheet->setCellValue('O'.$row, '=sum(O8:O'.$last_row.')');
        $sheet->setCellValue('Q'.$row, '=sum(Q8:Q'.$last_row.')');
        $sheet->setCellValue('S'.$row, '=sum(S8:S'.$last_row.')');
        $sheet->setCellValue('U'.$row, '=sum(U8:U'.$last_row.')');
        $letter = 'V';
        while($letter !== 'AQ'){
            $sheet->setCellValue($letter.$row, '=sum('.$letter.'8:'.$letter.$last_row.')');
            $letter++;
        }

        $row+=2; $no = 1;

        $sheet->setCellValue('C'.$row, 'Note: 1 MINUTE IS EQUAL TO 30 MINUTES');
        $row++;
        $late_start_row = $row;
        $sheet->setCellValue('A'.$row, 'No');
        $sheet->setCellValue('B'.$row, 'Employee')->mergeCells('B'.$row.':D'.$row);
        $sheet->setCellValue('E'.$row, 'No. of Hrs.');
        $sheet->setCellValue('F'.$row, 'Amount');
        $row++;

        foreach($salary as $s){
            $monitoring_counter = false;
            if($s->monitoring->isNotEmpty()){
                if($date_counter == 'true'){//check if pay date is in date range
                    foreach($s->monitoring as $m){
                        $start = Carbon::parse($start_date);
                        $end = Carbon::parse($end_date);
                        $pay_date = Carbon::parse($m->pay_date);
                        if($pay_date->between($start, $end)){
                            $monitoring_counter = true;
                            break;
                        }
                    }
                }
                if($monitoring_counter == true){
                    foreach($s->monitoring as $m){
                        if($date_counter == 'true'){
                            $start = Carbon::parse($start_date);
                            $end = Carbon::parse($end_date);
                            $pay_date = Carbon::parse($m->pay_date);
                            if(!$pay_date->between($start, $end)){
                                continue;
                            }
                        }
                        $sheet->setCellValue('A'.$row, $no);
                        $sheet->setCellValue('B'.$row, $s->employee->lname.', '.$s->employee->fname)
                            ->mergeCells('B'.$row.':D'.$row);
                        if($m->deduction->late){
                            $late = number_format($m->deduction->late * ($m->daily/8), 2, '.', '');
                            $sheet->setCellValue('E'.$row, $m->deduction->late);
                            $sheet->setCellValue('F'.$row, $late);
                        }
                    }
                    $no++; $row++;
                }
            }
            if($monitoring_counter == false){
                if($s->employee->id == 2){
                    continue;
                }
                $sheet->setCellValue('A'.$row, $no);
                $sheet->setCellValue('B'.$row, $s->employee->lname.', '.$s->employee->fname)
                    ->mergeCells('B'.$row.':D'.$row);

                $no++; $row++;
            }
        }
        $late_end_row = $row-1;


        //BODY -- END


        // STYLES -- START

        $sheet->freezePane('C8');
        $highest = $sheet->getHighestRow();
        $sheet->getStyle('A6:AQ'.$total_row)->applyFromArray($borderStyleArray);
        $sheet->getStyle('A'.$late_start_row.':F'.$late_end_row)->applyFromArray($borderStyleArray);
        $sheet->getStyle('A6:AQ'.$total_row)->applyFromArray($highlightStyleArray);
        $sheet->getStyle('A'.$total_row.':AQ'.$total_row)->getBorders()->getTop()->setBorderStyle(Border::BORDER_DOUBLE);
        $sheet->getStyle('A6:AQ7')->applyFromArray($headerStyleArray);
        $sheet->getStyle('A8:A'.$highest)->applyFromArray($centerStyleArray);
        $sheet->getStyle('A1')->getFont()->setSize(12)->setName('Arial Narrow');
        $sheet->getStyle('A2')->getFont()->setSize(14)->setName('Arial Narrow');
        $sheet->getStyle('A3:AQ'.$highest)->getFont()->setSize(9)->setName('Arial Narrow');

        $twoDecimal = ['F', 'G', 'I', 'I', 'K', 'M', 'O', 'Q', 'S', 'U'];
        foreach($twoDecimal as $t){
            $sheet->getStyle($t.'8:'.$t.$total_row)->applyFromArray($numberStyleArray);
        }
        $sheet->getStyle('V8:AP'.$total_row)->applyFromArray($numberStyleArray);

        $daysHoursCenter = ['H', 'J', 'L', 'N', 'P', 'R', 'T'];
        foreach($daysHoursCenter as $d){
            $sheet->getStyle($d.'8:'.$d.$total_row)->applyFromArray($centerStyleArray);
        }

        $mediumBorder = ['I', 'AC', 'AO', 'AP'];
        foreach($mediumBorder as $m){
            $sheet->getStyle($m.'8:'.$m.$total_row)->applyFromArray($highlightStyleArray);
        }


        $sheet->getStyle('I6:I'.$total_row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFF2CC');
        $sheet->getStyle('AC6:AC'.$total_row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFCE4D6');
        $sheet->getStyle('AO6:AO'.$total_row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2EFDA');
        $sheet->getStyle('AP6:AP'.$total_row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8CBAD');

        if($rd_counter == false){
            $sheet->getColumnDimension('L')->setVisible(false);
            $sheet->getColumnDimension('M')->setVisible(false);
        }
        if($spcl_counter == false){
            $sheet->getColumnDimension('N')->setVisible(false);
            $sheet->getColumnDimension('O')->setVisible(false);
        }
        if($spcl_ot_counter == false){
            $sheet->getColumnDimension('P')->setVisible(false);
            $sheet->getColumnDimension('Q')->setVisible(false);
        }
        if($legal_counter == false){
            $sheet->getColumnDimension('R')->setVisible(false);
            $sheet->getColumnDimension('S')->setVisible(false);
        }
        if($legal_ot_counter == false){
            $sheet->getColumnDimension('T')->setVisible(false);
            $sheet->getColumnDimension('U')->setVisible(false);
        }
        if($cola_counter == false){
            $sheet->getColumnDimension('X')->setVisible(false);
        }
        if($mktg_counter == false){
            $sheet->getColumnDimension('Y')->setVisible(false);
        }
        if($jap_counter == false){
            $sheet->getColumnDimension('Z')->setVisible(false);
        }

        //Column Sizes
        $sheet->getColumnDimension('A')->setWidth(4); // No.
        $sheet->getColumnDimension('B')->setWidth(19); // Name of Employee
        $sheet->getColumnDimension('C')->setWidth(6); // Branch
        $sheet->getColumnDimension('D')->setWidth(6.5); // Mode of Salary
        $sheet->getColumnDimension('E')->setWidth(9.5); // Mode of Employment
        $sheet->getColumnDimension('F')->setWidth(8); // Daily Rate
        $sheet->getColumnDimension('G')->setWidth(9); // Monthly Rate
        $sheet->getColumnDimension('H')->setWidth(6); // Days Worked
        $sheet->getColumnDimension('J')->setWidth(4); // Reg OT
        $sheet->getColumnDimension('L')->setWidth(4); // RD OT
        $sheet->getColumnDimension('N')->setWidth(4); // Spcl
        $sheet->getColumnDimension('P')->setWidth(4); // Spcl OT
        $sheet->getColumnDimension('R')->setWidth(4); // Legal
        $sheet->getColumnDimension('T')->setWidth(4); // Legal OT
        $sheet->getColumnDimension('V')->setWidth(12); // Acccommodation Allowance
        $sheet->getColumnDimension('X')->setWidth(7); // COLA
        $sheet->getColumnDimension('Y')->setWidth(7); // Mktg Comm
        $sheet->getColumnDimension('Z')->setWidth(7); // Japan Comm
        $sheet->getColumnDimension('AA')->setWidth(7); // 13th Month
        $sheet->getColumnDimension('AD')->setWidth(6); // SSS
        $sheet->getColumnDimension('AE')->setWidth(6); // PHIC
        $sheet->getColumnDimension('AF')->setWidth(6); // HDMF
        $sheet->getColumnDimension('AG')->setWidth(6); // Tax
        $sheet->getColumnDimension('AH')->setWidth(6); // CA
        $sheet->getColumnDimension('AJ')->setWidth(6); // Undertime
        $sheet->getColumnDimension('AK')->setWidth(6); // Absent
        $sheet->getColumnDimension('AL')->setWidth(6); // Late
        $sheet->getColumnDimension('AM')->setWidth(6); // Others
        $sheet->getColumnDimension('AN')->setWidth(8); // WFH

        // STYLES -- END

        $filename = 'Payroll Summary.xlsx';
        
        //redirect output to client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
    }

    public function excel_soa(Request $request){
        $class = $request->class_hidden;
        $program = $request->program_hidden;
        $batch = $request->batch_hidden;
        $id = $request->soa_id_hidden;
        $counter = $request->counter; //if group excel or individual
        $current_class = [];

        //ALL DATA -- START

        if($class != 'All'){
            $student_group = student::pluck('id');
            
            for($x = 0; $x < count($student_group); $x++){
                $class_students = class_students::where('id', $student_group[$x])
                    ->orderBy('id', 'desc')->first();

                if(!empty($class_students)){
                    if($class_students->class_settings_id == $class){
                        array_push($current_class, $class_students->stud_id);
                    }
                }
            }
        }

        if($counter == true){
            $soa_ids = soa::groupBy('stud_id')->pluck('stud_id');
            $student = student::with('benefactor', 'branch')
            ->whereIn('id', $soa_ids)
            ->when($class != 'All', function($query) use($current_class){
                $query->whereIn('id', $current_class);
            })
            ->when($program != 'All', function($query) use($program){
                $query->where('program_id', $program);
            })
            ->when($batch != 'All', function($query) use($batch){
                $query->where('batch', $batch);
            })
            ->get();
        }
        else{
            $student = student::with('benefactor', 'branch')->where('id', $id)->first();
            $soa = soa::with('verified', 'description')->where('stud_id', $id)->get();
        }
        

        //ALL DATA -- END

        //STYLE -- START

        $tableStyleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true, 
            ],
            'font' => [
                'size' => 9
            ],
        ];

        $outlineStyleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM
                ]
            ]
        ];

        $headerStyleArray = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true, 
            ]
        ];
        
        $middleLeftStyleArray = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true, 
            ],
            'font' => [
                'size' => 9
            ],
        ];
        
        $numberStyleArray = [
            'numberFormat' => [
                'formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            ],
        ];

        //STYLE -- END

        //Initialize Sheet
        $spreadsheet = new Spreadsheet();

        if($counter == true){
            foreach($student as $key => $s){
                $soa = soa::with('verified', 'description')->where('stud_id', $s->id)->get();
                $this->soa_sheet($s, $soa, $spreadsheet, $tableStyleArray, $outlineStyleArray, $headerStyleArray, $middleLeftStyleArray, $numberStyleArray);
                if(isset($student[$key+1])){
                    $spreadsheet->createSheet();
                    $spreadsheet->setActiveSheetIndex($key+1);
                }
            }
        }else{
            $this->soa_sheet($student, $soa, $spreadsheet, $tableStyleArray, $outlineStyleArray, $headerStyleArray, $middleLeftStyleArray, $numberStyleArray);
        }
        
        if($counter == false){
            $filename = 'SOA - '.$student->lname.'.xlsx';
        }else{
            $filename = 'SOA.xlsx';
        }
        
        //redirect output to client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
    }

    public function soa_sheet($student, $soa, $spreadsheet, $tableStyleArray, $outlineStyleArray, $headerStyleArray, $middleLeftStyleArray, $numberStyleArray){
        $student_name = $student->lname.', '.$student->fname.(($student->mname) ? ' '.$student->mname : '');
        $pbb = 'SSW - '.$student->batch. ' | '.(($student->benefactor) ? $student->benefactor->name : '');//program batch benefactor
        
        $class_start = class_students::where('stud_id', $student->id)->orderBy('start_date', 'asc')->first();
        $class_start = (isset($class_start)) ? $class_start->start_date : '';
        $class_end = class_students::where('stud_id', $student->id)->orderBy('end_date', 'desc')->first();
        $class_end = (isset($class_end)) ? $class_end->end_date : '';

        $sheet = $spreadsheet->getActiveSheet()->setTitle($student->lname);
        $row = 1; $copy = "SCHOOL\rCOPY";
        $address = '3F Dela Rosa Square Bldg., Dela Rosa St.,cor. Chino Roces Avenue, Pio Del Pilar,  Makati City';
        $contact = 'E-mail: leadtraining2013@gmail.com　Telephone Number: 02-8777-2114';
        $acknowledgement = 'I, ____________________________ acknowledges the receipt of the above-mentioned amount as payment of my Nihongo Training and Living expense while I study at Manila Kokusai Academy Inc. Hereby acknowledge that the Daily Living expenseand Dormitory fees in the amount of JPY 168,000 shall be paid back to the company from _________________________ to ________________________ for a duration of 12 months in the amount of JPY 14,000 per month or until full paid.';

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
        ->setPaperSize(PageSetup::PAPERSIZE_A4);

        $sheet->getColumnDimension('A')->setWidth(24);
        $sheet->getColumnDimension('B')->setWidth(11.5);
        $sheet->getColumnDimension('C')->setWidth(11.5);
        $sheet->getColumnDimension('D')->setWidth(11.5);
        $sheet->getColumnDimension('E')->setWidth(11.5);
        $sheet->getColumnDimension('F')->setWidth(12.14);
        $sheet->getColumnDimension('G')->setWidth(15.14);

        for($i = 0; $i < 2; $i++){
            //HEADER -- START
            $sheet->getRowDimension($row)->setRowHeight(20);

            $sheet->setCellValue('G'.$row, $copy)->mergeCells('G'.$row.':G'.($row+1));
            $sheet->getStyle('G'.$row)->getAlignment()->setWrapText(true)
                ->setHorizontal('center')->setVertical('center');
            $sheet->getStyle('G'.$row)->getFont()->setSize(14)->setBold(true)
                ->getColor()->setARGB('FFAFABAB');

            $copy = "STUDENT\rCOPY";

            $row++;

            $sheet->getRowDimension($row)->setRowHeight(26);
            
            $sheet->setCellValue('B'.$row, 'MANILA KOKUSAI ACADEMY INC.')
                ->getStyle('B'.$row)->getFont()->setName('Arial Black')->setSize(14)
                ->getColor()->setARGB('FF0070C0');
            $row++;
            $sheet->setCellValue('B'.$row, $address)->getStyle('B'.$row)->getFont()->setSize(8);
            $row++;
            $sheet->setCellValue('B'.$row, $contact)->getStyle('B'.$row)->getFont()->setSize(8);
            $row += 3;
                
            //HEADER -- END

            //BODY -- START

            $info_rows = $row;
            $sheet->setCellValue('A'.$row, 'Student No.')->setCellValue('F'.$row, 'Branch');
            $sheet->setCellValue('B'.$row, '')->setCellValue('G'.$row, $student->branch->name);
            $row++;
            $sheet->setCellValue('A'.$row, 'Student Name')->setCellValue('F'.$row, 'Class Start');
            $sheet->setCellValue('B'.$row, $student_name)->setCellValue('G'.$row, $class_start);
            $sheet->getStyle('B'.$row)->getFont()->setBold(true);
            $row++;
            $sheet->setCellValue('A'.$row, 'Program | Batch | Benefactor')->setCellValue('F'.$row, 'Class Finished');
            $sheet->setCellValue('B'.$row, $pbb)->setCellValue('G'.$row, $class_end);
            $row += 2;
            
            $sheet->setCellValue('A'.$row, 'STATEMENT OF ACCOUNT')->mergeCells('A'.$row.':G'.$row)
                ->getStyle('A'.$row)->getFont()->setName('Arial Black')->setSize(12);
            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal('center');
            $row++; $table_head = $row;
            
            $sheet->setCellValue('A'.$row, 'Payment Description');
            $sheet->setCellValue('B'.$row, 'AMOUNT DUE');
            $sheet->setCellValue('C'.$row, 'AMOUNT PAID');
            $sheet->setCellValue('D'.$row, 'DATE OF PAYMENT');
            $sheet->setCellValue('E'.$row, 'BALANCE DUE');
            $sheet->setCellValue('F'.$row, 'VERIFIED BY');
            $sheet->setCellValue('G'.$row, 'REMARKS');
            $row++; $table_start = $row;

            foreach($soa as $s){
                $sheet->setCellValue('A'.$row, $s->description->name);
                $sheet->setCellValue('B'.$row, $s->amount_due);
                $sheet->setCellValue('C'.$row, $s->amount_paid);
                $sheet->setCellValue('D'.$row, $s->payment_date);
                $sheet->setCellValue('E'.$row, '=B'.$row.'-C'.$row);
                $sheet->setCellValue('F'.$row, ($s->verified) ? $s->verified->fname : ''); 
                $sheet->setCellValue('G'.$row, $s->remarks);
                $row++;
            }

            $total_row = $row;
            $sheet->setCellValue('A'.$row, 'Total Due');
            $sheet->setCellValue('B'.$row, '=sum(B'.$table_start.':B'.($row-1).')');
            $sheet->setCellValue('C'.$row, '=sum(C'.$table_start.':C'.($row-1).')');
            $sheet->setCellValue('E'.$row, '=sum(E'.$table_start.':E'.($row-1).')');
            $row += 2;

            $sheet->setCellValue('A'.$row, $acknowledgement)->mergeCells('A'.$row.':G'.($row+3));
            $sheet->getStyle('A'.$row)->applyFromArray($middleLeftStyleArray);
            $row += 5;
            
            $sheet->getStyle('F'.$row.':G'.$row)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $row++;
            $sheet->setCellValue('F'.$row, 'Signature over printed name')->mergeCells('F'.$row.':G'.$row);
            $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('F'.$row)->getFont()->setSize(10);
            $row += 2;

            $sheet->setCellValue('A'.$row, 'Prepared By');
            $sheet->setCellValue('C'.$row, 'Verified By')->mergeCells('C'.$row.':D'.$row);
            $sheet->setCellValue('G'.$row, 'Approved By');
            $sheet->getStyle('A'.$row.':G'.$row)->getFont()->setSize(9);
            $row++;

            $sheet->getStyle('A'.$row)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('C'.$row.':D'.$row)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('G'.$row)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $row++;

            $sheet->setCellValue('A'.$row, 'EIRENE A. PRADO');
            $sheet->setCellValue('C'.$row, 'BEVERLY BENEDICTO')->mergeCells('C'.$row.':D'.$row);
            $sheet->setCellValue('G'.$row, 'KRESTA N. ANCAJAS');
            $sheet->getStyle('A'.$row.':G'.$row)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A'.$row.':G'.$row)->getFont()->setSize(9)->setBold(true);
            $row++;

            $sheet->setCellValue('A'.$row, 'Finance Officer');
            $sheet->setCellValue('C'.$row, 'HR/Finance Dept. Head')->mergeCells('C'.$row.':D'.$row);
            $sheet->setCellValue('G'.$row, 'Finance Manager');
            $sheet->getStyle('A'.$row.':G'.$row)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A'.$row.':G'.$row)->getFont()->setSize(9);
            $row += 1;

            if($i == 0){
                $sheet->getStyle('A'.$row.':G'.$row)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUMDASHED);
                $row++;
                $logo2 = $row+1;
            }
            $row++;

            //BODY -- END

            //Using Styles -- START

            $sheet->getStyle('A'.$info_rows.':G'.($info_rows+2))->getFont()->setSize(10);
            
            $sheet->getStyle('B'.$info_rows.':B'.($info_rows+2))->getAlignment()->setHorizontal('center');
            $sheet->getStyle('G'.$info_rows.':G'.($info_rows+2))->getAlignment()->setHorizontal('center');

            $info_cells = 'B'.$info_rows.':B'.($info_rows+2);
            $info_cells_2 = 'G'.$info_rows.':G'.($info_rows+2);

            $sheet->getStyle($info_cells)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE7E6E6');
            $sheet->getStyle($info_cells_2)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE7E6E6');
            $sheet->getStyle('A'.$table_head.':G'.$table_head)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF9BC2E6');
            
            for($x = $info_rows; $x < $info_rows+3; $x++){
                $sheet->mergeCells('B'.$x.':D'.$x);
                $sheet->getRowDimension($x)->setRowHeight(18);
                $sheet->getStyle('B'.$x.':D'.$x)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('G'.$x)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            }

            $sheet->getRowDimension($table_head)->setRowHeight(25);
            
            $sheet->getStyle('A'.$table_head.':G'.$total_row)->applyFromArray($tableStyleArray);
            $sheet->getStyle('A'.$total_row.':G'.$total_row)->getBorders()->getTop()->setBorderStyle(Border::BORDER_DOUBLE);
            $sheet->getStyle('A'.$total_row.':G'.$total_row)->getFont()->setBold(true);
            $sheet->getStyle('A'.$table_head.':G'.$total_row)->applyFromArray($outlineStyleArray);
            $sheet->getStyle('A'.$table_head.':G'.$table_head)->applyFromArray($outlineStyleArray)->applyFromArray($headerStyleArray);

            
            $sheet->getStyle('B'.$table_head.':B'.$total_row)->applyFromArray($numberStyleArray);
            $sheet->getStyle('C'.$table_head.':C'.$total_row)->applyFromArray($numberStyleArray);
            $sheet->getStyle('E'.$table_head.':E'.$total_row)->applyFromArray($numberStyleArray);
            $sheet->getStyle('D'.$table_head.':D'.$total_row)->getAlignment()->setHorizontal('center')->setIndent(1);

            $row_colored = false;
            for($x = $table_start; $x <= $total_row; $x++){
                $sheet->getRowDimension($x)->setRowHeight(19);

                if($row_colored == false){
                    $row_colored = true;
                }
                else{
                    $sheet->getStyle('A'.$x.':G'.$x)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDDEBF7');
                    $row_colored = false;
                }
            }

            $sheet->getRowDimension($total_row)->setRowHeight(22);

            //Using Styles -- END
        }
        

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('img/mila.png'));
        $drawing->setHeight(125);
        $drawing->setOffsetX(20);
        $drawing->setOffsetY(10);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('img/mila.png'));
        $drawing->setHeight(125);
        $drawing->setOffsetX(20);
        $drawing->setOffsetY(10);
        $drawing->setCoordinates('A'.$logo2);
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $sheet->getPageSetup()->setPrintArea('A1:'.$sheet->getHighestColumn().$sheet->getHighestRow())
            ->setFitToWidth(1)->setFitToHeight(1);
    }
}
