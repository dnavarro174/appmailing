
  <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <div class="nav-link">
              <div class="profile-image"> <img src="{{URL::route('home')}}/images/logo_ticketing_1.png" alt="Logo Ticketing" /> <span class="online-status online"></span> </div>
              <div class="profile-name">
                <p class="name">Ticketing V2.0</p>
                <!-- <p class="designation">UI/UX Designer</p> -->
              </div>
              <!-- <div class="notification-panel mt-4">
                <span><i class="mdi mdi-settings"></i></span>
                <span class="count-wrapper"><i class="mdi mdi-bell"></i><span class="count top-right bg-warning">4</span></span>
                <span><i class="mdi mdi-email"></i></span>
              </div> -->
            </div>
          </li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('home')}}"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/01.png" alt="menu icon"> <span class="menu-title">Inicio</span></a> </li>
          
          {{-- <li class="nav-item">
            <a class="nav-link {{ request()->is('cursos.index') ? 'active' : '' }}" data-toggle="collapse" href="#cursos-dropdown" aria-expanded="false" aria-controls="cursos-dropdown"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/08.png" alt="menu icon"> <span class="menu-title">Cursos</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="cursos-dropdown">
              <ul class="nav flex-column sub-menu">
                @if(in_array('cursos',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link {{ request()->is('cursos.index') ? 'active' : '' }}" href="{{ route('cursos.index')}}">Cursos</a> </li>
                @endif
                <li class="nav-item"> <a class="nav-link" href="{{ route('tc_sedes.index')}}">Sede</a> </li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('cat_cursos.index')}}">Categoría Curso</a> </li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('tc_modalidades.index')}}">Modalidad</a> </li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('tc_tipos.index')}}">Tipo</a> </li>
                <li class="nav-item"> <a class="nav-link" href="#">Consultas</a></li>
                @if(isset( session("permisosTotales")["cursos"]["permisos"]["reportes"]["permiso"]   ) &&  session("permisosTotales")["cursos"]["permisos"]["reportes"]["permiso"] == 1)
                <li class="nav-item"> <a class="nav-link" href="#">Reportes</a></li>
                @endif
                
                @if(isset( session("permisosTotales")["cursos"]["permisos"]["informes"]["permiso"]   ) && session("permisosTotales")["cursos"]["permisos"]["informes"]["permiso"]== 1 ) 
                <li class="nav-item"> <a class="nav-link" href="#">Informes</a></li>
                @endif
              </ul>
            </div>
          </li> --}}
          <li class="nav-item">
            <a class="nav-link {{ request()->is('programaciones.index') ? 'active' : '' }}" data-toggle="collapse" href="#apps-dropdown" aria-expanded="false" aria-controls="apps-dropdown"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/09.png" alt="menu icon"> <span class="menu-title">Programaciones</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="apps-dropdown">
              <ul class="nav flex-column sub-menu">
                @if(in_array('programaciones',session('permisosMenu')))
                <li class="nav-item"><a class="nav-link {{ request()->is('programaciones.index') ? 'active' : '' }}" href="{{ route('programaciones.index')}}">Programaciones</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->is('actividades') ? 'active' : '' }}" href="{{ route('actividades.index')}}">Actividades</a></li>
                @endif
                <li class="nav-item"> <a class="nav-link" href="#">Consultas</a></li>
                @if(isset( session("permisosTotales")["programaciones"]["permisos"]["reportes"]["permiso"]   ) && session("permisosTotales")["programaciones"]["permisos"]["reportes"]["permiso"]== 1 ) 
                <li class="nav-item"> <a class="nav-link" href="#">Reportes</a></li>
                @endif
       
                @if(isset( session("permisosTotales")["programaciones"]["permisos"]["informes"]["permiso"]   ) && session("permisosTotales")["programaciones"]["permisos"]["informes"]["permiso"]== 1 ) 
                <li class="nav-item"> <a class="nav-link" href="#">Informes</a></li>
                @endif
                
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('estudiantes') ? 'active' : '' }}" data-toggle="collapse" href="#page-layouts" aria-expanded="false" aria-controls="page-layouts"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/02.png" alt="menu icon"> <span class="menu-title">Leads / Registros</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="page-layouts">
              <ul class="nav flex-column sub-menu">
                @if(in_array('estudiantes',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link {{ request()->is('estudiantes.index') ? 'active' : '' }}" href="{{ route('estudiantes.index')}}">Registros</a></li>
                {{-- <li class="nav-item "> <a class="nav-link" href="{{ route('entidades.index') }}">Entidades</a></li> --}}
                <li class="nav-item"> <a class="nav-link" href="{{ route('tipo_doc.index') }}">Tipo de Documento</a></li>
                @endif
                {{-- <li class="nav-item"> <a class="nav-link" href="#">Ubigeo</a></li> --}}
                <li class="nav-item"> <a class="nav-link" href="#">Consultas</a></li>
                @if(isset( session("permisosTotales")["estudiantes"]["permisos"]["reportes"]["permiso"]   ) && session("permisosTotales")["estudiantes"]["permisos"]["reportes"]["permiso"]== 1 ) 
                <li class="nav-item"> <a class="nav-link" href="#">Reportes</a></li>
                @endif
                @if(isset( session("permisosTotales")["estudiantes"]["permisos"]["informes"]["permiso"]   ) && session("permisosTotales")["estudiantes"]["permisos"]["informes"]["permiso"]== 1 ) 
                <li class="nav-item"> <a class="nav-link" href="#">Informes</a></li>
                @endif
                
              </ul>
            </div>
          </li>
         
          {{-- @if(in_array('eventos',session('permisosMenu')))
          <li class="nav-item">
            <a class="nav-link {{ request()->is('eventos') ? 'active' : '' }}" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/17.png" alt="menu icon"> <span class="menu-title">Evento</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link {{ request()->is('eventos') ? 'active' : '' }}" href="{{ route('eventos.index')}}">Evento</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('tipo_eventos.index')}}">Tipo de Eventos</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">Consultas</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">Reportes</a></li>
              </ul>
            </div>
          </li>
          @endif --}}

          @if(in_array('historiaemail',session('permisosMenu')))
          <li class="nav-item">
            <a class="nav-link {{ request()->is('historiaemail') ? 'active' : '' }}" data-toggle="collapse" href="#historiaemail" aria-expanded="false" aria-controls="historiaemail"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/20.png" alt="menu icon"> <span class="menu-title">Mailing</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="historiaemail">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link {{ request()->is('historiaemail') ? 'active' : '' }}" href="{{ route('historiaemail.index')}}">Historia Email</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('plantillaemail.index')}}">Plantilla HTML</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('estudiantes.enviar_email')}}">Enviar Email</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('form_confirmacion.index')}}">Pre-Inscritos</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('newsletter.index')}}">Newsletter</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">Solicito Información</a></li>
              </ul>
            </div>
          </li>
          @endif
          @if(in_array('asistencia',session('permisosMenu')))
          <li class="nav-item">
            <a class="nav-link {{ request()->is('asistencia') ? 'active' : '' }}" data-toggle="collapse" href="#ui-advanced" aria-expanded="false" aria-controls="ui-advanced"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/07.png" alt="menu icon"> <span class="menu-title">Asistencia</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="ui-advanced">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link {{ request()->is('asistencia') ? 'active' : '' }}" href="{{ route('asistencia.index')}}">Registrar Asistencia</a></li>
              </ul>
            </div>
          </li>
          @endif
          @if(in_array('maestria',session('permisosMenu')))
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#maestria" aria-expanded="false" aria-controls="lead-advanced"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/14.png" alt="menu icon"> <span class="menu-title">Maestria </span><i class="menu-arrow"></i></a>
            <div class="collapse" id="maestria">
              <ul class="nav flex-column sub-menu">
                @if(in_array('maestria',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link" href="{{ route('maestria.index')}}">Maestria</a></li>
                @endif
              </ul>
            </div>
          </li>
          @endif
          @if(in_array('auditoria',session('permisosMenu')) )
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auditoria" aria-expanded="false" aria-controls="lead-advanced"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/15.png" alt="menu icon"> <span class="menu-title">Auditoría </span><i class="menu-arrow"></i></a>
            <div class="collapse" id="auditoria">
              <ul class="nav flex-column sub-menu">
                @if(in_array('auditoria',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link" href="{{ route('auditoriae.index')}}">Auditoría Leads</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('auditoriap.index')}}">Auditoría Programaciones</a></li>
                @endif
              </ul>
            </div>
          </li>
          @endif
          @if(in_array('usuarios',session('permisosMenu')) or in_array('roles',session('permisosMenu')) )
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#users-roles" aria-expanded="false" aria-controls="lead-advanced"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/05.png" alt="menu icon"> <span class="menu-title">Usuarios / Roles</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="users-roles">
              <ul class="nav flex-column sub-menu">
                @if(in_array('usuarios',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link" href="{{ route('usuarios.index')}}">Usuarios</a></li>
                @endif
                @if(in_array('roles',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link" href="{{ route('roles.index')}}">Roles</a></li>
                @endif
              </ul>
            </div>
          </li>
          @endif
          {{-- <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/19.png" alt="menu icon"> <span class="menu-title">Form elements</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="form-elements">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Basic Elements</a></li>
                <li class="nav-item"><a class="nav-link" href="pages/forms/advanced_elements.html">Advanced Elements</a></li>
                <li class="nav-item"><a class="nav-link" href="pages/forms/validation.html">Validation</a></li>
                <li class="nav-item"><a class="nav-link" href="pages/forms/wizard.html">Wizard</a></li>
              </ul>
            </div>
          </li> --}}
      
          
        </ul>
      </nav>