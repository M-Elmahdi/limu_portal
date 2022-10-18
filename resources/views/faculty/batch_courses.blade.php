@extends('layouts.faculty.faculty-layout')

@section('content-bla')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">{{ Auth::user()->faculty->faculty_name }} / {{ $batch->batch_name }} / </span> Courses
</h4>

<!-- Basic Bootstrap Table -->
<div class="card">
  {{-- <h5 class="card-header">Table Basic</h5> --}}
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Id</th>
          <th>Name</th>
          <th>Code</th>
          <th>Type</th>
          <th>Exams</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        
        @foreach ($batch_courses as $course)
            <tr>
                <td>
                    {{ $course->course->course_id }}
                </td>

                <td>
                  <div class="fw-semibold">
                    {{ $course->course->course_name }}
                  </div>  
                </td>

                <td>
                    {{ $course->course->course_code }}
                </td>

                <td>
                    <span class="badge bg-label-primary me-1">{{ $course->course->type }}</span>
                </td>

                <td>
                  <span class="badge bg-label-dark me-1">
                    {{ $course->main_exam_count }}
                  </span> 
                </td>

                <td>
                    <a class="btn btn-outline-dark" href="{{ route('faculty.exams', ['batch_id' => $batch->batch_id, 'course_id' => $course->course->course_id]) }}" role="button">
                        <i class="bx bx-show lg"></i>
                    </a>
                    {{-- <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);"
                            ><i class="bx bx-show-alt me-1"></i> View</a
                        >
                        <a class="dropdown-item" href="javascript:void(0);"
                            ><i class="bx bx-trash me-1"></i> Delete</a
                        >
                        </div>
                    </div> --}}
                </td>
            </tr>
        @endforeach

      </tbody>
    </table>
  </div>
</div>
<!--/ Basic Bootstrap Table -->
@endsection
