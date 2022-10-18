@extends('layouts.student.student-layout-result')

@section('content-bla')
    
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Basic Bootstrap Table -->
<div class="card">
     
    <h5 class="card-header text-end display-7">
      {{ auth()->user()->student->std_fname }}
    </h5>
    
    <div class="row d-flex justify-content-center">
      <div class="col">
        <div class="nav-align-top mb-4">
          <ul class="nav nav-tabs m-auto" role="tablist">

          <li class="nav-item">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#overview" aria-controls="navs-top-home" aria-selected="true">
              Overview
            </button>
          </li>

          @foreach ($year_courses as $year => $year_course)

            @foreach($year_course as $year => $course)

              <li class="nav-item">
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
                The Overview
              </div>
            </div>

            @foreach ($year_courses as $year_course)

              @foreach($year_course as $year => $courses)

                <div class="tab-pane fade" id="{{str_replace(' ', '-', $year)}}" role="tabpanel">

                  <div class="row justify-content-center">
                    <div class="col-auto">
                      <div class="badge bg-primary">
                        ECTS Acquired <i class="bx bx-arrow-from-left lg mb-1"></i> <span>{{ $courses['total_ects'] }}</span>
                      </div>
                    </div>

                    <div class="col-auto">
                      <div class="badge bg-primary">
                        Accumulative Average <i class="bx bx-arrow-from-left lg mb-1"></i> <span>{{ round($courses['average'], 2) }}</span>%
                      </div>
                    </div>
                  </div>
                  

                  <ul>
                    @foreach ($courses['courses'] as $course)
                 
                    <div class="accordion mt-3" id="accordionExample">
                      <div class="card accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                          <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#course_{{ $course['course_id'] }}" aria-expanded="false" aria-controls="accordionOne">
                            {{ $course['course_name'] }} <span class="ms-2 badge bg-secondary">{{ round($course['course_actual_mark'], 2) }}/100</span>
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