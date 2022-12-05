@extends('layouts.faculty.faculty-layout')

@section('content-bla')

<div class="container-xxl flex-grow-1 container-p-y">

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

<div class="card m-1">
    <div class="list-group">
        <div class="list-group-item">Student Number\ <span class="text-primary">{{ $student->std_id }}</span></div>
        <div class="list-group-item">English name\ <span class="text-primary">{{ $student->std_english_name }}</span></div>
        <div class="list-group-item">Arabic name\ <span class="text-primary">{{ $student->std_fname }}</span></div>
        <div class="list-group-item">Gender\ <span class="text-primary">{{ $student->std_gender }}</span></div>
    </div>
</div>

<div class="card">
    
    <div class="row d-flex justify-content-center">
      <div class="col">
        <div class="nav-align-top mb-2 mt-4">
          <ul class="nav nav-tabs d-flex justify-content-center m-1 rounded" role="tablist">

          <li class="nav-item nav-pills">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#overview" aria-controls="navs-top-home" aria-selected="true">
              Overview
            </button>
          </li>

          @foreach ($year_courses as $year => $year_course)

            @foreach($year_course as $year => $course)

              <li class="nav-item nav-pills">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#{{str_replace(' ', '-', $year)}}" aria-controls="navs-top-home" aria-selected="true">
                  {{ $year }}
                </button>
              </li>

            @endforeach

          @endforeach
            
          </ul>


          <div class="shadow-none tab-content">

            <div class="tab-pane fade active show" id="overview" role="tabpanel">
              <div class="container">
                <div class="row">
                @foreach ($year_courses as $year_course => $year)
                    @foreach ($year as $year_key => $year_info)
                      
                        <div class="col-md-6 col-xl-4">
                          <div class="card shadow-none bg-transparent border border-primary mb-3">
                            <div class="card-body">
                              <h5 class="card-title">{{ $year_key }}</h5>
                                <ul class="list-group">
                                  
                                  <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Accumulative Average
                                    <span class="badge bg-primary">{{ round($year_info['new_average'], 3) }}%</span>
                                  </li>

                                  <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total ECTS
                                    <span class="badge bg-primary">{{ $year_info['total_ects'] }}</span>
                                  </li>

                                  <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Courses
                                    <span class="badge bg-primary">{{ $year_info['courses']->count() }}</span>
                                  </li>

                                </ul>
                            </div>
                          </div>
                        </div>
                      
                    @endforeach
                @endforeach
                </div>
              </div>
            </div>

            @foreach ($year_courses as $year_course)

              @foreach($year_course as $year => $courses)

                <div class="tab-pane fade" id="{{str_replace(' ', '-', $year)}}" role="tabpanel">

                  <div class="row justify-content-center">
                    <div class="col-auto">
                      <div class="badge bg-primary m-1">
                        ECTS Acquired <i class="bx bx-arrow-from-left lg mb-1"></i> <span>{{ $courses['total_ects'] }}</span>
                      </div>
                    </div>

                    <div class="col-auto">
                      <div class="badge bg-primary m-1">
                        Accumulative Average <i class="bx bx-arrow-from-left lg mb-1"></i> <span>{{ round($courses['new_average'], 2) }}</span>%
                      </div>
                    </div>
                  </div>
                  
                  <ul class="p-0">
                    @foreach ($courses['courses'] as $course)
                 
                    <div class="accordion mt-3" id="accordionExample">
                      <div class="card accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                          <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#course_{{ $course['course_id'] }}" aria-expanded="false" aria-controls="accordionOne">
                            {{-- @if ($course['credit_type'] == 'uncredit')
                              {{ $course['course_name'] }} <span class="ms-2 badge bg-secondary">{{ round($course['raw_mark'], 2) }}/100</span>
                            @else
                              {{ $course['course_name'] }} <span class="ms-2 badge bg-secondary">{{ round($course['course_mark'], 2) }}/100</span>
                            @endif --}}
                            {{ $course['course_name'] }} <span class="ms-2 badge bg-secondary">{{ round($course['raw_mark'], 2) }}/100</span>
                          </button>
                        </h2>
  
                        <div id="course_{{ $course['course_id'] }}" class="accordion-collapse collapse" style="">
                          <div class="accordion-body">

                            <!-- Main Exams -->
                            <ul class="list">
                              @foreach ($course['main_exams'] as $exam)
                                
                                <li class="text-uppercase">
                                  <span class="fw-bold">{{ $exam->exam_name }}</span>

                                  <ul class="list">
                                    @if ($exam->sub_exams == 'Y')
                                      @foreach ($exam->sub_exam_student_results as $sub_exam)
                                      <li class="list-item">
                                        {{$sub_exam->sub_exam_eval->exam_name}} : <span class="ms-2 badge bg-primary">{{ $sub_exam->marks }}/{{ $sub_exam->sub_exam_eval->max_marks }}</span>
                                      </li>  
                                      @endforeach 
                                    @endif

                                    @if ($exam->sub_exams == 'N')
                                      @foreach ($exam->main_exam_student_results as $main_exam_result)
                                      <li class="list-item">
                                        <span class="badge bg-primary">{{ $main_exam_result->marks }}/{{ $exam->max_marks }}</span>
                                      </li>  
                                      @endforeach
                                    @endif
                                  </ul>
                                </li>

                              @endforeach
                            </ul>
                            <!-- End Main Exams -->

                          </div>
                        </div>
                      </div>
                    </div>


                    @endforeach
                  </ul>
                </div>
                

              @endforeach

            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Basic Bootstrap Table -->

</div>

@endsection