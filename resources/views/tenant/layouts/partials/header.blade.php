@php
  $path_logo = ($vc_company->logo) ? 'storage/uploads/logos/'.$vc_company->logo : 'logo/tulogo.png'
@endphp
<header id="page-topbar">
  <div class="layout-width">
      <div class="navbar-header">
          <div class="d-flex">
              <div class="navbar-brand-box horizontal-logo">
                  <a href="/" class="logo logo-dark">
                      <span class="logo-sm">
                          <img src="{{ URL::asset($path_logo) }}" alt="" height="22">
                      </span>
                      <span class="logo-lg">
                          <img src="{{ URL::asset($path_logo) }}" alt="" height="17">
                      </span>
                  </a>
                  <a href="/" class="logo logo-light">
                      <span class="logo-sm">
                          <img src="{{ URL::asset($path_logo) }}" alt="" height="22">
                      </span>
                      <span class="logo-lg">
                          <img src="{{ URL::asset($path_logo) }}" alt="" height="17">
                      </span>
                  </a>
              </div>
              <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                  <span class="hamburger-icon">
                      <span></span>
                      <span></span>
                      <span></span>
                  </span>
              </button>
              {{-- doc --}}
              <tenant-header-menu-docs class="d-none d-md-block"></tenant-header-menu-docs>
          </div>

          {{-- publicidad --}}
          <div class="d-none d-md-block">
            @if ($tenant_show_ads && $url_tenant_image_ads)
              <div class="ml-3 mr-3">
                <img src="{{$url_tenant_image_ads}}" style="max-height: 50px; max-width: 500px;">
              </div>
            @endif
          </div>

          <div class="d-flex align-items-center">
              {{-- doc --}}
              <tenant-header-menu-docs class="d-block d-md-none"></tenant-header-menu-docs>

              {{-- menu acceso directos - apps --}}
              <tenant-header-menu-directs></tenant-header-menu-directs>

              {{-- notificaciones --}}
              @if($vc_orders > 0)
                <a href="{{ route('tenant_orders_index') }}" type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="tooltip" data-placement="bottom" title="Pedidos pendientes">
                  <i class='ri-shopping-cart-line fs-22'></i>
                  <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-info">{{ $vc_orders }}<span class="visually-hidden">Pedidos pendientes</span></span>
                </a>
              @endif
              @if($vc_document > 0)
                <a href="{{route('tenant.documents.not_sent')}}" type="button" class="btn btn-icon btn-topbar btn-ghost-danger rounded-circle" data-toggle="tooltip" data-placement="bottom" title="Comprobantes no enviados/por enviar">
                  <i class='bx bx-bell fs-22'></i>
                  <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">{{ $vc_document }}<span class="visually-hidden">Comprobantes no enviados/por enviar</span></span>
                </a>
              @endif
              @if($vc_document_regularize_shipping > 0)
                <a href="{{route('tenant.documents.regularize_shipping')}}" type="button" class="btn btn-icon btn-topbar btn-ghost-warning rounded-circle" data-toggle="tooltip" data-placement="bottom" title="Comprobantes pendientes de rectificación">
                  <i class='ri-alert-line fs-22'></i>
                  <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-warning">{{ $vc_document_regularize_shipping }}<span class="visually-hidden">Comprobantes pendientes de rectificación</span></span>
                </a>
              @endif
              @if($vc_finished_downloads > 0)
                <a href="{{route('tenant.reports.download-tray.index')}}" type="button" class="btn btn-icon btn-topbar btn-ghost-primary rounded-circle" data-toggle="tooltip" data-placement="bottom" title="Bandeja de descargas (Reportes procesados)">
                  <i class='ri-file-download-line fs-22'></i>
                  <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-primary">{{ $vc_finished_downloads }}<span class="visually-hidden">Bandeja de descargas (Reportes procesados)</span></span>
                </a>
              @endif

              {{-- entorno --}}
              @if($vc_company->soap_type_id == "01")
                <div class="ms-1 header-item d-none d-sm-flex" data-toggle="tooltip" data-placement="bottom" title="SUNAT: ENTORNO DE DEMOSTRACIÓN, pulse para ir a configuración">
                  <a
                    href="{{in_array('configuration', $vc_modules) ? route('tenant.companies.create') :'#'}}"
                    type="button"
                    class="btn btn-info btn-label btn-sm waves-effect waves-light rounded-pill">
                    <i class="ri-user-smile-line label-icon align-middle rounded-pill fs-16 me-2"></i>DEMO
                  </a>
                </div>
              @elseif($vc_company->soap_type_id == "02")
                <div class="ms-1 header-item d-none d-sm-flex" data-toggle="tooltip" data-placement="bottom" title="SUNAT: ENTORNO DE PRODUCCIÓN, pulse para ir a configuración">
                  <a
                    href="{{in_array('configuration', $vc_modules) ? route('tenant.companies.create') :'#'}}"
                    type="button"
                    class="btn btn-primary btn-label btn-sm waves-effect waves-light rounded-pill">
                    <i class="ri-user-smile-line label-icon align-middle rounded-pill fs-16 me-2"></i>PROD
                  </a>
                </div>
              @else
                <div class="ms-1 header-item d-none d-sm-flex" data-toggle="tooltip" data-placement="bottom" title="INTERNO: ENTORNO DE PRODUCCIÓN, pulse para ir a configuración">
                  <a
                    href="{{in_array('configuration', $vc_modules) ? route('tenant.companies.create') :'#'}}"
                    type="button"
                    class="btn btn-primary btn-label btn-sm waves-effect waves-light rounded-pill">
                    <i class="ri-user-smile-line label-icon align-middle rounded-pill fs-16 me-2"></i>INT
                  </a>
                </div>
              @endif

              <div class="ms-1 header-item d-none d-sm-flex">
                  <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen" data-toggle="tooltip" data-placement="bottom" title="Ver en pantalla completa">
                      <i class='bx bx-fullscreen fs-22'></i>
                  </button>
              </div>

              <div class="ms-1 header-item d-none d-sm-flex">
                <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas" data-toggle="tooltip" data-placement="bottom" title="Cambiar configuración del tema">
                    <i class='ri-brush-3-fill fs-22'></i>
                </button>
              </div>

              <div class="ms-1 header-item d-none d-sm-flex">
                  <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode" data-toggle="tooltip" data-placement="bottom" title="Activar/desactivar tema oscuro">
                      <i class='bx bx-moon fs-22'></i>
                  </button>
              </div>

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
                        <!-- item-->
                        @if(in_array('cuenta', $vc_modules))
                            @if(in_array('account_users_list', $vc_module_levels))
                                <a class="dropdown-item" href="{{route('tenant.payment.index')}}">
                                    <i class="ri-wallet-line text-muted fs-16 align-middle me-1"></i>
                                    <span class="align-middle">
                                        Mis Pagos
                                    </span>
                                </a>
                            @endif
                        @endif
                        <a class="dropdown-item " href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ri-shut-down-line text-muted fs-16 align-middle me-1"></i>
                            <span key="t-logout" class="align-middle">
                                Salir
                            </span>
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