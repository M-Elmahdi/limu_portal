<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\sis\Batch;
use App\Models\sis\BatchCourse;
use App\Models\sis\Course;
use App\Models\sis\MainExam;
use App\Models\sis\MainExamStudentResult;
use Illuminate\Http\Request;

class FacultyHomeController extends Controller
{


    public function showBatchCourses(Request $request, $id){

        $batch = Batch::where('batch_id', $id)->first();

        $batch_courses = BatchCourse::with(['course', 'batch'])
            ->withCount(['main_exam' => function($query) use($batch) {
                $query->where('batch_id', $batch->batch_id);
            }])
            ->where('faculty_id', auth()->user()->faculty->faculty_id)
            ->where('batch_id', $id)
            ->get();

        return view('faculty.batch_courses', compact('batch_courses', 'batch'));
    }


    public function showExams($batch_id, $course_id){

        $course = Course::where('course_id', $course_id)->first();
        $batch = Batch::where('batch_id', $batch_id)->first();

        $exams = MainExam::with('sub_exams', 'course', 'batch')
                    ->where('batch_id', $batch_id)
                    ->where('course_id', $course_id)
                    ->get();
        
        return view('faculty.course-exams', compact('exams', 'course', 'batch'));
    }


    public function showBatches(){
        
        $batches = Batch::withCount('batch_course')
            ->orderBy('batch_name')
            ->where('faculty_id', auth()->user()->faculty->faculty_id)
            ->where('isActive', 'Y')
            ->get();

        return view('faculty.batches', compact('batches'));
    }

    public function showStudentResults($exam_id, $batch_id, $course_id){
        
        $exams = MainExamStudentResult::with('student', 'course', 'main_exam')
            ->where('exam_id', $exam_id)
            ->paginate(15);

        $main_exam = MainExam::where('id', $exam_id)->first();
        $batch = Batch::where('batch_id', $batch_id)->first();
        $course = Course::where('course_id', $course_id)->first();

        return view('faculty.main-exam-results', compact('exams', 'main_exam', 'batch', 'course'));
    }
}
