@extends('layouts.faculty.faculty-layout')

@section('content-bla')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">{{ Auth::user()->faculty->faculty_name }} / {{ $batch->batch_name }} / {{ $course->course_name }} / {{ $main_exam->exam_name }} </span> / Results 
</h4>

@if (session('error'))
  <div class=" m-3">

    <div class="bs-toast toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class="bx bx-bell me-2"></i>
        <div class="me-auto fw-semibold">Error</div>
        <small>Just now</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        {{ session('error') }}    
      </div>
    </div>

  </div>
@endif

<!-- Basic Bootstrap Table -->
<div class="card">
  {{-- <h5 class="card-header">Table Basic</h5> --}}
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Student Id</th>
          <th>Name</th>
          <th>Minimum</th>
          <th>Maximum</th>
          <th>Mark</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        
        @foreach ($exams as $exam)
            @if ($exam->std_id == 0)
                @continue
            @endif
            <tr>
                <td>
                    {{ $exam->student->std_id}}
                </td>

                <td>
                  <div class="fw-semibold">
                    {{ $exam->student->std_fname }}
                  </div>  
                </td>

                <td>
                    {{ $exam->main_exam->min_weightage == null ? $exam->main_exam->min_marks : $exam->main_exam->min_weightage }}
                </td>

                <td>
                    {{ $exam->main_exam->max_weightage == null ? $exam->main_exam->max_marks : $exam->main_exam->max_weightage }}
                </td>

                <td>
                    <span class="badge bg-label-primary me-1">{{ $exam->marks }} / {{ $exam->main_exam->max_weightage == null ? $exam->main_exam->max_marks : $exam->main_exam->max_weightage }}</span>
                </td>

                <td>
                    <a class="btn btn-outline-dark btn-sm" href="" role="button">
                        <i class="bx bx-book-add me-1"></i> Publish
                    </a>
                    {{-- <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                          
                          <a class="dropdown-item" href="{{ route('faculty.main-exam.results', $exam->id) }}"
                              ><i class="bx bx-show-alt me-1"></i> Show Student Results</a>

                          <a class="dropdown-item" href="javascript:void(0);"
                              ><i class="bx bx-book-add me-1"></i> Publish Exam Results</a>

                        </div>
                    </div> --}}
                </td>
            </tr>
        @endforeach

        

      </tbody>
    </table>
    <div class="d-flex justify-content-center mt-2">
      {{ $exams->links() }}
    </div>
    
  </div>
</div>
<!--/ Basic Bootstrap Table -->
@endsection
