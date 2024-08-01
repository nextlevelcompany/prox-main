<header id="page-topbar">
  <div class="layout-width">
      <div class="navbar-header">
          <div class="d-flex">
              <!-- LOGO -->
              <div class="navbar-brand-box horizontal-logo">
                  <a href="/" class="logo logo-dark">
                      <span class="logo-sm">
                          <img src="{{ URL::asset('logo/logo.jpg') }}" alt="" height="22">
                      </span>
                      <span class="logo-lg">
                          <img src="{{ URL::asset('logo/logo.jpg') }}" alt="" height="17">
                      </span>
                  </a>

                  <a href="/" class="logo logo-light">
                      <span class="logo-sm">
                          <img src="{{ URL::asset('logo/logo.jpg') }}" alt="" height="22">
                      </span>
                      <span class="logo-lg">
                          <img src="{{ URL::asset('logo/logo.jpg') }}" alt="" height="17">
                      </span>
                  </a>
              </div>

              <system-sidebar-size></system-sidebar-size>

          </div>

          <div class="d-flex align-items-center">

              <system-fullscreen-mode></system-fullscreen-mode>
              <system-light-dark-mode></system-light-dark-mode>

              <div class="dropdown ms-sm-3 header-item topbar-user">
                  <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="d-flex align-items-center">
                          <i class="ri-account-circle-fill text-muted fs-24 align-middle me-1"></i>
                          <span class="text-start ms-xl-2">
                              <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{Auth::user()->name}}</span>
                              <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">{{Auth::user()->email}}</span>
                          </span>
                      </span>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item"
                            href="{{ route('system.users.create') }}">
                            <i class="ri-account-circle-fill text-muted fs-16 align-middle me-1"></i>
                            <span class="align-middle">Perfil</span>
                        </a>
                        <a class="dropdown-item "
                            href="javascript:void();"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            <i class="ri-shut-down-line text-muted fs-16 align-middle me-1"></i>
                            <span key="t-logout" class="align-middle">Salir</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</header>

<!-- removeNotificationModal -->
<div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
          </div>
          <div class="modal-body">
              <div class="mt-2 text-center">
                  {{-- <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon> --}}
                  <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                      <h4>Are you sure ?</h4>
                      <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                  </div>
              </div>
              <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                  <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!</button>
              </div>
          </div>

      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
