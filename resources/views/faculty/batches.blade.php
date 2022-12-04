@extends('layouts.faculty.faculty-layout')

@section('content-bla')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ Auth::user()->faculty->faculty_name }} /</span> Batches</h4>

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

  <h5 class="card-header"></h5>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Id</th>
          <th>Name</th>
          <th>Session Start</th>
          <th>Session End</th>
          <th>Courses</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        
        @foreach ($batches as $batch)
            <tr>
                <td>
                    {{ $batch->batch_id }}
                </td>

                <td>
                  <div class="fw-semibold">
                    {{ $batch->batch_name }}
                  </div>  
                </td>

                <td>
                    <span class="badge bg-label-primary me-1">{{ $batch->session_start_date }}</span>
                </td>

                <td>
                    <span class="badge bg-label-primary me-1">{{ $batch->session_end_date }}</span>
                </td>

                <td>
                    <span class="badge bg-label-primary me-1">{{ $batch->batch_course_count }}</span>
                </td>

                <td>
                    <a class="btn btn-outline-dark {{ $batch->batch_course_count == 0 ? 'disabled' : '' }}" href="{{ route('faculty.index', $batch->batch_id) }}" role="button">
                        <i class="{{ $batch->batch_course_count == 0 ? 'bx bx-x lg' : 'bx bx-show lg' }}"></i>
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
