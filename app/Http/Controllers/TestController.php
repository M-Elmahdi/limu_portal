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
            $reset_course_result = 0;
            $reset_total_benchmark = 0;
            $course_count = 0;
            $main_exams = null;
            $overall_weight = 0;
            $isSubExamBased = false;

            foreach ($courses as $course) {

                if ($course->course != null) {

                    if ($course->course->type !== 'uncredit') {
                        $course_count++;
                    }
                    
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

                    //MAIN EXAMS
                    foreach ($main_exams as $main_exam) {

                        if ($main_exam->sub_exams == 'N') {
                            $isSubExamBased = false;

                            foreach($main_exam->main_exam_student_results as $exam){
        
                                $course_result += $exam->marks;
                                $total_benchmark += $main_exam->max_marks;
                                
                            }
                        }
        
                        if ($main_exam->sub_exams == 'Y') {
                            $isSubExamBased = true;
                            $exam_sum = 0;
                            $exam_benchmark = 0;

                            foreach($main_exam->sub_exam_student_results as $exam){

                                $exam_sum += $exam->marks;
                                $exam_benchmark += $exam->sub_exam_eval->max_marks;

                            }

                            // Factoring out the overall summetion of the weight in regards to a 100 percentile
                            $overall_weight += $main_exam->max_weightage * 100 / $main_exams->sum('max_weightage');

                            // Factoring out the current weight in regards to a 100 percentile
                            $current_weight = $main_exam->max_weightage * 100 / $main_exams->sum('max_weightage');

                            $course_result += $exam_sum * $current_weight / $exam_benchmark;

                            $total_benchmark += $exam_benchmark;
                        }
                    }     

                    //RESET EXAMS
                    // foreach ($reset_exams as $reset_exam) {

                    //     if ($reset_exam->sub_exams == 'N') {

                    //         foreach($exam->main_exam_reset_student_results as $exam){
                    //             $reset_course_result += $exam->marks;
                    //             $reset_total_benchmark += $reset_exam->max_weightage == null ? $reset_exam->max_marks : $reset_exam->max_weightage;
                                
                    //         }
                    //     }
        
                    //     if ($reset_exam->sub_exams == 'Y') {

                    //         foreach($exam->sub_exam_reset_student_results as $exam){

                    //             $reset_course_result += $exam->mtarks;
                    //             $reset_total_benchmark += $exam->sub_reset_exam_eval->max_marks;

                    //         }   
                    //     }
                    // }
                
                    $total_benchmark = $total_benchmark == 0 ? 1 : $total_benchmark;
                    $reset_total_benchmark = $reset_total_benchmark == 0 ? 1 : $reset_total_benchmark;
                    $overall_weight = $overall_weight == 0 ? 1 : $overall_weight;

                    $calculated_result = 0;
                    $calcaulated_ects = 0;

                    if ($course->course->type !== 'uncredit') {
                        $calcaulated_ects = ( $course->credit_hours * 30 ) / 30;
                        $calculated_result = $isSubExamBased ? $course_result * $calcaulated_ects : $course_result * $calcaulated_ects;
                    }

                    $batch_courses->push([
                        'course_name' => $course->course->course_name,
                        'course_id' => $course->course->course_id,
                        'main_exams' => $main_exams,
                        'reset_exams' => $reset_exams,
                        'ects' => $calcaulated_ects,
                        'raw_ects' => ( $course->credit_hours * 30 ) / 30,
                        'credit_type' => $course->course->type,
                        'status' => $course->status,
                        'overall_weight' => $overall_weight,
                        'course_mark' => $calculated_result,
                        'raw_mark' => $isSubExamBased ? $course_result : $course_result * 100 / $total_benchmark,
                        'total_benchmark' => $total_benchmark,
                        'reset_course_mark' => $reset_course_result * 100 / $reset_total_benchmark,
                    ]);

                    $course_result = 0;
                    $overall_weight = 0;
                    $total_benchmark = 0;
                    $reset_course_result = 0;
                    $reset_total_benchmark = 0;

                }

            }

            $year_courses->push([
                $batch_year->year->year_semester => [
                                                        'batch_id' => $batch_year->batch_id,
                                                        'year_id' => $batch_year->year_id,
                                                        'courses' => $batch_courses,
                                                        'total_ects' => $batch_courses->sum('ects'),
                                                        'average' => $batch_courses->sum('course_mark') * 100 / ($course_count * 100),
                                                        'new_average' => $batch_courses->sum('course_mark') / $batch_courses->sum('ects'),
                                                        'ects_total' => $batch_courses->sum('ects'),
                                                        'total_marks' => $batch_courses->sum('course_mark'),
                                                        'reset_average' => $batch_courses->sum('reset_course_mark') * 100 / ($course_count * 100),
                                                        'repeated_courses' => $repeated_courses,
                                                        'reset_courses' => $reset_courses,
                                                        'course_count' => $course_count,
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
        $overall_weight = 0;
        $isSubExamBased = false;
        $main_exams = new Collection();

        foreach($current_year_courses as $course) {

            if ($course->course != null) {


                if ($course->course->type !== 'uncredit') {
                    $course_count++;
                }

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
                        $isSubExamBased = false;
                        
                        foreach($main_exam->main_exam_student_results as $exam){
                            $course_result += $exam->marks;
                            $total_benchmark += $main_exam->max_marks;
                        }
                    }
    
                    if ($main_exam->sub_exams == 'Y') {
                        $isSubExamBased = true;
                        $exam_sum = 0;
                        $exam_benchmark = 0;

                        foreach($main_exam->sub_exam_student_results as $exam){

                            $exam_sum += $exam->marks;
                            $exam_benchmark += $exam->sub_exam_eval->max_marks;

                        }

                        // Factoring out the overall summetion of the weight in regards to a 100 percentile
                        $overall_weight += $main_exam->max_weightage * 100 / $main_exams->sum('max_weightage');

                        // Factoring out the current weight in regards to a 100 percentile
                        $current_weight = $main_exam->max_weightage * 100 / $main_exams->sum('max_weightage');

                        $course_result += $exam_sum * $current_weight / $exam_benchmark;

                        $total_benchmark += $exam_benchmark;
                    }
                }

                $total_benchmark = $total_benchmark == 0 ? 1 : $total_benchmark;
                $overall_weight = $overall_weight == 0 ? 1 : $overall_weight;

                $calculated_result = 0;
                $calcaulated_ects = 0;

                if ($course->course->type !== 'uncredit') {
                    $calcaulated_ects = ( $course->credit_hours * 30 ) / 30;
                    $calculated_result = $isSubExamBased ? $course_result * $calcaulated_ects : ($course_result * 100 / $total_benchmark)  * $calcaulated_ects;
                }

                $current_year->push([
                    'course_name' => $course->course->course_name,
                    'course_id' => $course->course->course_id,
                    'main_exams' => $main_exams,
                    'ects' => $calcaulated_ects,
                    'credit_type' => $course->course->type,
                    'status' => $course->status,
                    'overall_weight' => $overall_weight,
                    'course_mark' => $calculated_result,
                    'raw_mark' => $isSubExamBased ? $course_result * 100 / $overall_weight : $course_result * 100 / $total_benchmark,
                    'total_benchmark' => $total_benchmark,
                    'course_actual_mark' => $course_result * 100 / $total_benchmark
                ]);

                $course_result = 0;
                $overall_weight = 0;
                $total_benchmark = 0;

            }
        }

        $year_courses->push([
            $student->year->year_semester => [
                                                'courses' => $current_year,
                                                'total_ects' => $current_year->sum('ects'),
                                                'new_average' => $current_year->sum('course_mark') / $current_year->sum('ects'),
                                                'average' => $current_year->sum('course_actual_mark') / $current_year->count(),
                                                'course_count' => $course_count,
                                                'ects_sum' => $current_year->sum('ects'),
                                                'overall_marks' => $current_year->sum('course_mark'), 
                                                'course_mark' => $current_year->sum('total_benchmark')
                                            ]
        ]);

        dd($year_courses);

    }
}
