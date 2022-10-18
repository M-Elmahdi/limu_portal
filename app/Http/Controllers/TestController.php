<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\sis\BatchCourse;
use App\Models\sis\MainExam;
use App\Models\sis\MainExamReset;
use App\Models\sis\PreviousBatchYear;
use App\Models\sis\StudentDetail;
use App\Models\sis\StudentRepeatCourse;
use App\Models\sis\StudentResetCourse;
use Illuminate\Database\Eloquent\Collection;

class TestController extends Controller
{
    public function bla(){

        $student = StudentDetail::with('batch', 'year')->where('std_id', auth()->user()->std_id)->first();

        $batch_years = PreviousBatchYear::with('batch', 'year')
                                        ->where('std_id', auth()->user()->std_id)
                                        ->orderBy('year_id', 'ASC')
                                        ->get();

        $year_courses = new Collection();

        foreach($batch_years as $batch_year){

            $courses = BatchCourse::with(['course' ])->where('batch_id', $batch_year->batch_id)->where('semester_id', $batch_year->year_id)->get();

            $repeated_courses = StudentRepeatCourse::with(['course' ])->where('batch_id', $batch_year->batch_id)
                                    ->where('year_id', $batch_year->year_id)
                                    ->where('std_id', auth()->user()->std_id)
                                    ->get();

            $reset_courses = StudentResetCourse::with(['course' ])->where('batch_id', $batch_year->batch_id)
                ->where('year_id', $batch_year->year_id)
                ->where('std_id', auth()->user()->std_id)
                ->get();

            $batch_courses = new Collection();
            $course_result = 0;
            $total_benchmark = 0;
            $course_count = 0;
            $main_exams = null;

            foreach ($courses as $course) {

                if ($course->course != null) {

                    $course_count++;

                    $main_exams = MainExam::with(['main_exam_student_results' => function($query){ $query->where('std_id', auth()->user()->std_id); },
                                            'main_exam_student_results.main_exam'])
                                        ->with(['sub_exam_student_results' => function($query){ $query->where('std_id', auth()->user()->std_id); },
                                            'sub_exam_student_results.sub_exam_eval'])
                                        ->with(['sub_exam_reset_student_results' => function($query){ $query->where('std_id', auth()->user()->std_id); },
                                            'sub_exam_reset_student_results.sub_reset_exam_eval'])
                                        ->where('course_id', $course->course->course_id)
                                        ->where('batch_id', $batch_year->batch->batch_id)
                                        ->where('year_id', $batch_year->year->semester_id)
                                        ->get();


                    $reset_exams = MainExamReset::with(['main_exam_reset_student_results' => function($query){ $query->where('std_id', auth()->user()->std_id); },
                                            'sub_exam_reset_student_results' => function($query) { $query->where('std_id', auth()->user()->std_id); },
                                            'sub_exam_reset_student_results.sub_reset_exam_eval'])
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
                        'reset_exams' => $reset_exams,
                        'ects' => ( $course->credit_hours * 30 ) / 30,
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
                                                        'batch_id' => $batch_year->batch_id,
                                                        'year_id' => $batch_year->year_id,
                                                        'courses' => $batch_courses,
                                                        'total_ects' => $batch_courses->sum('ects'),
                                                        'average' => $batch_courses->sum('course_actual_mark') * 100 / ($course_count * 100),
                                                        'reapeated_courses' => $repeated_courses,
                                                        'reset_courses' => $reset_courses,
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
        $course_count = 0;
        $main_exams = new Collection();

        foreach($current_year_courses as $course) {

            if ($course->course != null) {

                $course_count++;

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
                                                'average' => $current_year->sum('course_actual_mark') / $current_year->count(),
                                                'course_count' => $batch_courses->sum('course_actual_mark') * 100 / ($course_count * 100)
                                            ]
        ]);

        dd($year_courses);

    }
}
