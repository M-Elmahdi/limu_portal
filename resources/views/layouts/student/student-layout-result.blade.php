@extends('layouts.template-assets')

@section('content')
     <!-- Layout wrapper -->
     <div class="layout-wrapper layout-content-navbar layout-without-menu">
        <div class="layout-container">
          <!-- Layout container -->
          <div class="layout-page">
            <!-- Navbar -->
  
            <nav
              class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
              id="layout-navbar"
            >
              <div class="navbar-nav-right d-flex" id="navbar-collapse">
                <div class="nav-item d-flex align-items-end">
                    Student-No \ <span class="fw-bold">{{ auth()->user()->student->std_id }}</span>
                </div>
              </div>

              <div class="navbar-nav-left d-flex" id="navbar-collapse">
                <div class="nav-item d-flex align-items-end">
                  <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" >Logout</a>
                  </form>
                </div>
              </div>
            </nav>
  
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