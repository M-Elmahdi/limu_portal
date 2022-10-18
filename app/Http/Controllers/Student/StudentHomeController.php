<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\sis\BatchCourse;
use App\Models\sis\MainExam;
use App\Models\sis\Course;
use App\Models\sis\PreviousBatchYear;
use App\Models\sis\StudentDetail;
use App\Models\sis\StudentRepeatCourse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class StudentHomeController extends Controller
{
    public function index(Request $request){

        $student = StudentDetail::with('batch', 'year')->where('std_id', auth()->user()->std_id)->first();

        $batch_years = PreviousBatchYear::with('batch', 'year')
                                            ->where('std_id', auth()->user()->std_id)
                                            ->orderBy('year_id', 'ASC')
                                            ->get();

        

        
        $year_courses = new Collection();

        foreach($batch_years as $batch_year){

            $courses = BatchCourse::with(['course' ])->where('batch_id', $batch_year->batch_id)->where('semester_id', $batch_year->year_id)->get();

            $repeated_courses = StudentRepeatCourse::where('batch_id', $batch_year->batch_id)
                                    ->where('year_id', $batch_year->year_id)
                                    ->where('std_id', auth()->user()->std_id)
                                    ->get();

            $batch_courses = new Collection();
            $course_result = 0;
            $total_benchmark = 0;
            $main_exams = null;

            foreach ($courses as $course) {

                if ($course->course != null) {

                    $main_exams = MainExam::with(['main_exam_student_results' => function($query){ $query->where('std_id', auth()->user()->std_id); },
                                        'main_exam_student_results.main_exam'])
                                    ->with(['sub_exam_student_results' => function($query){ $query->where('std_id', auth()->user()->std_id); },
                                    'sub_exam_student_results.sub_exam_eval'])
                                    ->with(['sub_exam_reset_student_results' => function($query){ $query->where('std_id', auth()->user()->std_id); },
                                        'sub_exam_reset_student_results.sub_exam_eval'])
                                    ->where('course_id', $course->course->course_id)
                                    ->where('batch_id', $batch_year->batch->batch_id)
                                    ->where('year_id', $batch_year->year->semester_id)
                                    ->get();

                    foreach ($main_exams as $main_exam) {

                        if ($main_exam->sub_exams == 'N') {
                            foreach($main_exam->main_exam_student_results as $exam){
        
                                $course_result += $exam->marks;
                                $total_benchmark += $main_exam->max_marks;
                                
                            }
                        }
        
                        if ($main_exam->sub_exams == 'Y') {
                            foreach($main_exam->sub_exam_student_results as $exam){
                                
                                $course_result += $exam->marks;
                                $total_benchmark += $exam->sub_exam_eval->max_marks;
                            }
                        }
                    }   
                
                    $total_benchmark = $total_benchmark == 0 ? 1 : $total_benchmark;

                    $batch_courses->push([
                        'course_name' => $course->course->course_name,
                        'course_id' => $course->course->course_id,
                        'main_exams' => $main_exams,
                        'ects' => ( $course->credit_hours * 30 ) / 30,
                        'credit_hours' => $course->credit_hours,
                        'credit_type' => $course->course->type,
                        'status' => $course->status,
                        'course_mark' => $course_result,
                        'total_benchmark' => $total_benchmark,
                        'course_actual_mark' => $course_result * 100 / $total_benchmark
                    ]);

                    $course_result = 0;
                    $total_benchmark = 0;

                }

            }

            $year_courses->push([
                $batch_year->year->year_semester => [
                                                    'courses' => $batch_courses,
                                                    'total_ects' => $batch_courses->sum('ects'),
                                                    'average' => $batch_courses->sum('course_actual_mark') / $batch_courses->count()
                                                    ]
            ]);
        }

        // CURRENT STUDENT SEMESTER 
        $current_year_courses = BatchCourse::with(['course' ])
            ->where('batch_id', auth()->user()->student->std_batch_id)
            ->where('semester_id', auth()->user()->student->std_semester_id)
            ->get();

        $current_year = new Collection();

        $course_result = 0;
        $total_benchmark = 0;
        $main_exams = new Collection();

        foreach($current_year_courses as $course) {

            if ($course->course != null) {

                $main_exams = MainExam::with(['main_exam_student_results' => function($query){ $query->where('std_id', auth()->user()->std_id); },
                                    'main_exam_student_results.main_exam'])
                                    ->with(['sub_exam_student_results' => function($query){ $query->where('std_id', auth()->user()->std_id); },
                                            'sub_exam_student_results.sub_exam_eval'])
                                    ->with(['sub_exam_reset_student_results' => function($query){ $query->where('std_id', auth()->user()->std_id); },
                                            'sub_exam_reset_student_results.sub_exam_eval'])
                                    ->where('course_id', $course->course->course_id)
                                    ->where('batch_id', $student->std_batch_id)
                                    ->where('year_id', $student->std_semester_id)
                                    ->get();

            
                
                foreach ($main_exams as $main_exam) {

                    if ($main_exam->sub_exams == 'N') {
                        foreach($main_exam->main_exam_student_results as $exam){
    
                            $course_result += $exam->marks;
                            $total_benchmark += $main_exam->max_marks;
                        }
                    }
    
                    if ($main_exam->sub_exams == 'Y') {
                        foreach($main_exam->sub_exam_student_results as $exam){
    
                            $course_result += $exam->marks;
                            $total_benchmark += $exam->sub_exam_eval->max_marks;
                        }
                    }
                }

                $total_benchmark = $total_benchmark == 0 ? 1 : $total_benchmark;

                $current_year->push([
                    'course_name' => $course->course->course_name,
                    'course_id' => $course->course->course_id,
                    'main_exams' => $main_exams,
                    'ects' => ( $course->credit_hours * 30 ) / 30,
                    'credit_hours' => $course->credit_hours,
                    'credit_type' => $course->course->type,
                    'status' => $course->status,
                    'course_mark' => $course_result,
                    'total_benchmark' => $total_benchmark,
                    'course_actual_mark' => $course_result * 100 / $total_benchmark
                ]);

            }
        }

        $year_courses->push([
            $student->year->year_semester => [
                                                'courses' => $current_year,
                                                'total_ects' => $current_year->sum('ects'),
                                                'average' => $current_year->sum('course_actual_mark') / $current_year->count()
                                            ]
        ]);

        return view('student.home')->with('year_courses', $year_courses);
    }

    private function calculateECTS($student){

        //Fetch student course ids
        $course_ids = $student->student->main_exam_results->pluck('course_id')->unique()->toArray();

        //Fetch the courses to prepare for calculation
        $student_courses = Course::whereIn('course_id', $course_ids)->get();

        //Fetch Total Student Marks
        $student_mark = $student->student->main_exam_results->pluck('marks');

        //Student total mark initialisation
        $total_student_mark = $student_mark->sum();

        // ECTS initialisation
        $ects = 0;

        $ect_collection = new Collection();

        $total_credit_hours = 0;

        foreach($student_courses as $course){
            
            if ($course->type == 'uncredit') {
                continue;
            }

            $ect_collection->push([
                'course_name' => $course->course_name,
                'ects' => ( $course->credit_hours * 30 ) / 30,
                'credit_hours' => $course->credit_hours,
                'credit_type' => $course->type
            ]);

           $ects += ( $course->credit_hours * 30 ) / 30;

           $total_credit_hours += $course->credit_hours * 30;
        }

        dd(
            ( $total_student_mark * $ects) / $total_credit_hours
        );

    }
}
