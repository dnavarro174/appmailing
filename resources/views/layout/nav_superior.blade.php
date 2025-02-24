<nav class="navbar horizontal-layout-2 col-lg-12 col-12 p-0 d-flex flex-row align-items-start" style="
">
      <div class="container">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
          <a class="navbar-brand brand-logo" href="{{ route('home')}}">
            @if(session('logo') == "")
            <img src="{{URL::route('home')}}/images/logo_ticketing.png" class="img-fluid" alt="logo tkt">
            @else
            <img src="{{ asset('images/form/a')}}/{{session('logo')}}" class="img-fluid" alt="logo tkt">
            @endif
          </a>
          <a class="navbar-brand brand-logo-mini img-fluid" href="{{ route('home')}}">
            <img src="{{URL::route('home')}}/images/logo_ticketing_1.png" alt="logo tkt mini">
          </a>

        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center pr-0">
          
          <ul class="navbar-nav header-links">
            @if(in_array('usuarios',session('permisosMenu')))
            <li class="nav-item active">
              <a href="{{ route('reportes.modulos')}}" class="nav-link"><i class="mdi mdi-elevation-rise"></i>Reportes</a>
            </li>
            {{-- <li class="nav-item">
              <a class="nav-link" id="revenue-dropdown" href="{{ route('landingpage.index')}}" >{{ route('landing_form.create',['eventos_id'=>1])}}
                Landing Page
              </a>
            </li> --}}
            @endif
            <li class="nav-item">
              <a class="nav-link" id="revenue-dropdown" href="{{ route('modulos.index') }}" >Modulos</a>
            </li>
          </ul>
          
          <ul class="navbar-nav ml-auto dropdown-menus">
            
            <!-- Authentication Links -->
          @guest
            <li class="nav-item dropdown d-none d-xl-inline-block">
              <a class="nav-link" href="{{ route('login') }}"><span class="mr-3">{{ __('Login') }}</span></a>
            </li>
            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
          @else

          <li class="nav-item dropdown d-none d-xl-inline-block">
            <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              <span class="mr-3">Hola, {{ Auth::user()->name }} !</span><img class="img-xs rounded-circle" src="{{ asset('images/logo_ticketing_1.png')}}" alt="Profile image">
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <a class="dropdown-item p-0">
                <div class="d-flex border-bottom">
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center"><i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i></div>
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right"><i class="mdi mdi-account-outline mr-0 text-gray"></i></div>
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center"><i class="mdi mdi-alarm-check mr-0 text-gray"></i></div>
                </div>
              </a>
              <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
          </li>
          <li class="d-block d-md-none">
            <a class="dropdown-item" href="{{ route('logout') }}"
                 onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
                 <i class="mdi mdi-logout text-white"></i>
            </a>
          </li>

          @endguest

          <!-- Authentication Links -->
          </ul>
          <button type="button" class="navbar-toggler d-block d-md-none"><i class="mdi mdi-menu"></i></button>
        </div>
      </div>
      <div class="container">
        <div class="nav-bottom border-bottom border-secondary">
          <ul class="navbar-nav">
            <li class="nav-item dropdown {{ request()->is('/') ? 'active' : '' }}">
              <a class="nav-link count-indicator" href="https://www.enc-ticketing.org/">{{-- {{ route('home') }} --}}Inicio</a>
            </li>
            @if(in_array('bd',session('permisosMenu')))
            <li class="nav-item dropdown {{ setActive('bd.index') }}">
              <a class="nav-link count-indicator" title="Base de Datos" id="project-dropdown" href="{{ route('bd.index')}}">B.D</a>
            </li>
            @endif
            @if(in_array('estudiantes',session('permisosMenu')))
            <li class="nav-item dropdown {{ setActive('caii.index') }}">
              <a class="nav-link count-indicator" id="finance-dropdown" href="{{ route('caii.index') }}" >CAII</a>
            </li>
            @endif
            @if(in_array('eventos',session('permisosMenu')))
            <li class="nav-item dropdown {{ setActive('eventos.index') }}">
              <a class="nav-link count-indicator" id="project-dropdown" href="{{route('eventos.index')}}">Eventos</a>
            </li>
            @endif
            @if(in_array('eventos-es',session('permisosMenu')))
            <li class="nav-item dropdown {{ setActive('eventos-es.index') }}">
              <a class="nav-link count-indicator" id="project-dropdown" title="Eventos Especiales" href="{{route('eventos-es.index')}}">Ev.Especiales</a>
            </li>
            @endif
            @if(in_array('crm',session('permisosMenu')))
            <li class="nav-item dropdown {{ setActive('campanias.index') }}">
              <a class="nav-link count-indicator" id="project-dropdown" href="{{route('campanias.index')}}">{{-- mailing --}}Mailing</a>
            </li>
            @endif
            {{-- <li class="nav-item dropdown {{ setActive('academico.index') }}">
              <a class="nav-link" id="hr-dropdown" href="#"> {{route('academico.index')}}A.Academicas</a>
            </li> --}}
            @if(in_array('maestria',session('permisosMenu')))
            <li class="nav-item dropdown {{ setActive('grupo-maestria.index') }}">
              <a href="{{ route('grupo-maestria.index')}}" class="nav-link count-indicator dropdown-toggle" >Maestría</a>
            </li>
            @endif
            @if(in_array('dj',session('permisosMenu')))
            <li class="nav-item dropdown {{ setActive('grupo-dj.index') }}">
              <a href="{{ route('grupo-dj.index')}}" class="nav-link count-indicator dropdown-toggle" title="Declaración Jurada">DJ.OCI</a>
            </li>
            @endif
            @if(in_array('dj',session('permisosMenu')))
            <li class="nav-item dropdown {{ setActive('grupo-djcgr.index') }}">
              <a href="{{ route('grupo-djcgr.index')}}" class="nav-link count-indicator dropdown-toggle" title="Declaración Jurada">DJ.CGR</a>
            </li>
            @endif
            @if(in_array('formdoc',session('permisosMenu')))
            <li class="nav-item dropdown {{ setActive('grupo-doc.index') }}">
              <a href="{{ route('grupo-doc.index')}}" class="nav-link count-indicator dropdown-toggle" title="Formulario Docentes">Form.Doc</a>
            </li>
            @endif
            @if(in_array('einvestigacion',session('permisosMenu')))
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator" id="revenue-dropdown" href="{{ route('grupo-estudio-investigacion.index')}}" >Estudios e Investigaciones</a>
            </li>
            @endif
            @if(in_array('correos',session('permisosMenu')))
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator" id="revenue-dropdown" href="{{ route('correos.index')}}" >Correos ENC</a>
            </li>
            @endif
            
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="usuarios" href="#" data-toggle="dropdown">
               <i class="mdi mdi-settings"></i>
              </a>
              <div class="dropdown-menu dropdown-left navbar-dropdown" aria-labelledby="usuarios">
                <ul>
                  @if(in_array('usuarios',session('permisosMenu')))
                  <li class="dropdown-item"><a href="{{ route('ajustes.edit',['ajuste'=>'1']) }}" class="dropdown-link">Ajustes Generales</a></li>
                  <li class="dropdown-item"><a href="{{ route('usuarios.index') }}" class="dropdown-link">Usuarios</a></li>
                  @endif
                  @if(in_array('roles',session('permisosMenu')))
                  <li class="dropdown-item"><a href="{{ route('roles.index') }}" class="dropdown-link">Roles</a></li>
                  @endif
                  @if(in_array('auditoria',session('permisosMenu')))
                  <li class="dropdown-item"><a href="{{ route('auditoriae.index')}}" class="dropdown-link">Auditoría Leads</a></li>
                  @endif
                  <li class="dropdown-item"><a href="{{ route('msn.whats')}}" class="dropdown-link">Mensajes MSN</a></li>
                  <li class="dropdown-item"><a href="{{ route('maestria.index')}}" class="dropdown-link">Formato de Requeriemientos</a></li>
                </ul>
              </div>
            </li>
            
          </ul>
          {{-- <ul class="navbar-nav ml-auto d-none d-lg-block">
            <li class="nav-item mr-4">
              <form action="#">
                <div class="form-group mb-0">
                  <div class="input-group search-field">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search">
                  </div>
                </div>
              </form>
            </li>
          </ul> --}}
        </div>
      </div>
    </nav>