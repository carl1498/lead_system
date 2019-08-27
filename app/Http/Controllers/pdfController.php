<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDF;
use App\student;
use App\class_students;
use App\book_type;
use App\books;
use Carbon\Carbon;

class pdfController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function studentPDF(Request $request){
        $id = $request->id;
        $student = student::with('course', 'departure_year', 'departure_month',
            'program', 'school', 'benefactor', 'company')->where('id', $id)->first();
            
        //BIRTHDATE -- START
        $birth = new Carbon($student->birthdate);
        $student->age = $birth->diffInYears(Carbon::now());
        //BIRTHDATE -- END


        //CLASS -- START

        $class_students = class_students::with('student', 'current_class.sensei')->where('stud_id', $student->id)
            ->orderBy('id', 'desc')->first();

        $class = '';

        if($class_students){
            if($class_students->end_date && $class_students->student->status != 'Back Out'){
                $class .= 'Complete ';
            }
            else if($class_students->end_date && $class_students->student->status == 'Back Out'){
                $class .= 'Back Out ';
            }
            else{
                $class .= 'Active ';
            }
    
            $class .= '(' . $class_students->current_class->sensei->fname . ' ' . $class_students->current_class->sensei->lname . ')';
        }

        $student->class = $class;

        //CLASS -- END

        //BOOKS -- START

        $book_student = ['Minna 1 (Student)', 'WB 1 (Student)', 'Minna 2 (Student)', 'WB 2 (Student)', 'Kanji (Student)'];
        $book_ssw = ['Minna 1 (SSW)', 'WB 1 (SSW)', 'Minna 2 (SSW)', 'WB 2 (SSW)', 'Kanji (SSW)'];
        $book_type = $book_student;

        if($student->program->name == 'SSV (Careworker)' || $student->program->name == 'SSV (Hospitality)'){
            $book_type = $book_ssw;
        }

        $get_book = book_type::where('name', $book_type[0])->first();
        $get_book_id = $get_book->id;
        $book = books::where('stud_id', $student->id)->where('book_type_id', $get_book_id)->first();
        $student->minna1 = ($book) ? $book->name : null;

        $get_book = book_type::where('name', $book_type[1])->first();
        $get_book_id = $get_book->id;
        $book = books::where('stud_id', $student->id)->where('book_type_id', $get_book_id)->first();
        $student->minna1wb = ($book) ? $book->name : null;

        $get_book = book_type::where('name', $book_type[2])->first();
        $get_book_id = $get_book->id;
        $book = books::where('stud_id', $student->id)->where('book_type_id', $get_book_id)->first();
        $student->minna2 = ($book) ? $book->name : null;

        $get_book = book_type::where('name', $book_type[3])->first();
        $get_book_id = $get_book->id;
        $book = books::where('stud_id', $student->id)->where('book_type_id', $get_book_id)->first();
        $student->minna2wb = ($book) ? $book->name : null;

        $get_book = book_type::where('name', $book_type[4])->first();
        $get_book_id = $get_book->id;
        $book = books::where('stud_id', $student->id)->where('book_type_id', $get_book_id)->first();
        $student->kanji = ($book) ? $book->name : '';


        //BOOKS -- END


        $view = \View::make('pages.studentpdf', compact('student'));
        $html = $view->render();

        PDF::changeFormat('A4');
        PDF::reset();
        PDF::SetTitle('Student Profile - '.$student->fname . ' ' . $student->lname);
        PDF::AddPage();
        PDF::setPageMark();
        PDF::writeHTML($html, true, false, true, false, '');

        PDF::Output('StudentProfile_'.$student->lname.'.pdf');
    }
}
