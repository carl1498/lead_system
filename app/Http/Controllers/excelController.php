<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
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

class excelController extends Controller
{
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
        
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
        ->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
        
        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setRight(0.7);
        $sheet->getPageMargins()->setLeft(0.25);
        $sheet->getPageMargins()->setBottom(0.75);
        
        $sheet->getPageSetup()->setFitToWidth(1);    
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getPageSetup()->setPrintArea('A1:'.$sheet->getHighestColumn().$sheet->getHighestRow());
        
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
}
