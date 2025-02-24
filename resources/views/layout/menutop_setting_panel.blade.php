<div class="theme-setting-wrapper">
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close mdi mdi-close"></i>
          <div class="d-flex align-items-center justify-content-between border-bottom">
            <p class="settings-heading font-weight-bold border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Preferencias</p>
          </div>
          <div class="sidebar-bg-options ">
            <ul class="list-inline">
             @if(isset( session("permisosTotales")["estudiantes"]["permisos"]["busq_avanzada"]["permiso"]   ) && session("permisosTotales")["estudiantes"]["permisos"]["busq_avanzada"]["permiso"]== 1 )               
              <li class="list-inline-item mostrar"><a href="#" id="bAdvance" class="btn btn-link">Mostrar búsqueda avanzada</a></li>
              <li class="list-inline-item ocultar"><a href="#" id="bAdvanceOcultar" class="btn btn-link ">Ocultar búsqueda avanzada</a></li>
              @endif
              {{-- <li class="list-inline-item"><a href="form_cargar_datos_estudiantes" id="ImportarExcel" class="btn btn-link">Importar Excel</a></li> --}}
            </ul>
            
          </div>
          <div class="d-flex align-items-center justify-content-between border-bottom">
            <p class="settings-heading font-weight-bold border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Personalizar - Colores</p>
          </div>
          <div class="sidebar-bg-options selected" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Oscuro</div>
          <div class="sidebar-bg-options" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Claro</div>




          <p class="settings-heading font-weight-bold mt-2">Fondo de Encabezado</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles primary"></div>
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles pink"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
      
      <div id="right-sidebar" class="settings-panel">
        <i class="settings-close mdi mdi-close"></i>
        <div class="d-flex align-items-center justify-content-between border-bottom">
          <p class="settings-heading font-weight-bold border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
        </div>
        <ul class="chat-list">
          <li class="list active">
            <div class="profile"><img src="{{URL::route('home')}}/images/faces/face1.jpg" alt="image"><span class="online"></span></div>
            <div class="info">
              <p>Thomas Douglas</p>
              <p>Available</p>
            </div>
            <small class="text-muted my-auto">19 min</small>
          </li>
          <li class="list">
            <div class="profile"><img src="{{URL::route('home')}}/images/faces/face1.jpg" alt="image"><span class="offline"></span></div>
            <div class="info">
              <div class="wrapper d-flex">
                <p>Catherine</p>
              </div>
              <p>Away</p>
            </div>
            <div class="badge badge-success badge-pill my-auto mx-2">4</div>
            <small class="text-muted my-auto">23 min</small>
          </li>
          <li class="list">
            <div class="profile"><img src="{{URL::route('home')}}/images/faces/face1.jpg" alt="image"><span class="online"></span></div>
            <div class="info">
              <p>Daniel Russell</p>
              <p>Available</p>
            </div>
            <small class="text-muted my-auto">14 min</small>
          </li>
          <li class="list">
            <div class="profile"><img src="{{URL::route('home')}}/images/faces/face1.jpg" alt="image"><span class="offline"></span></div>
            <div class="info">
              <p>James Richardson</p>
              <p>Away</p>
            </div>
            <small class="text-muted my-auto">2 min</small>
          </li>
          <li class="list">
            <div class="profile"><img src="{{URL::route('home')}}/images/faces/face1.jpg" alt="image"><span class="online"></span></div>
            <div class="info">
              <p>Madeline Kennedy</p>
              <p>Available</p>
            </div>
            <small class="text-muted my-auto">5 min</small>
          </li>
          <li class="list">
            <div class="profile"><img src="{{URL::route('home')}}/images/faces/face1.jpg" alt="image"><span class="online"></span></div>
            <div class="info">
              <p>Sarah Graves</p>
              <p>Available</p>
            </div>
            <small class="text-muted my-auto">47 min</small>
          </li>
        </ul>
      </div>