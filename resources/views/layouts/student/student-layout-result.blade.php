@extends('layouts.template-assets')

@section('content')
     <!-- Layout wrapper -->
     <div class="layout-wrapper layout-content-navbar layout-without-menu">
        <div class="layout-container">
          <!-- Layout container -->
          <div class="layout-page">
            <!-- Navbar -->
  
            <!-- Navbar -->

          <nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar"
        >
          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

            <ul class="navbar-nav flex-row align-items-center ms-auto">

              <!-- Title of the Page -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center fw-bold">
                  <img class="col-10 col-md-4 col-sm-2" src="{{ asset('assets/img/limu_logos/limu_wide_logo.png') }}" alt="">
                </div>
              </div>
              <!-- /Title of the Page -->

              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="{{asset('assets/img/avatars/bx-user-circle.svg')}}" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-grow-1">
                          <div class="row fw-semibold d-block" style="word-break: break-word">{{ auth()->user()->student->std_english_name }}</div>
                          <small class="text-muted">{{auth()->user()->getRoleNames()->first()}}</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="">
                      <i class="bx bx-cog me-2"></i>
                      <span class="align-middle">Settings</span>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    @method('POST')
                      <button class="dropdown-item">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </button>
                    </form>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>

        <!-- / Navbar -->
  
            <!-- / Navbar -->
  
            <!-- Content wrapper -->
            <div class="content-wrapper">

              <!-- Content -->
                @yield('content-bla')
              <!-- / Content -->

              <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
          </div>
          <!-- / Layout page -->
        </div>
      </div>
      <!-- / Layout wrapper -->
@endsection