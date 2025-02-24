@extends('layout.home')

@section('content')

<div class="horizontal-menu bg_fondo" >
    <!-- partial:partials/_navbar.html -->

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- end menu_right -->
      <!-- partial -->

      <div class="main-panel">
        <div class="content-wrapper pt-0" style="background: none;">
          <div class="container">
            <div class="row justify-content-center">{{-- $datos->activo == 1 --}}
              <div class="col-xs-12 col-md-12 col-lg-12">
                <form class="forms-sample" id="maestriaForm" action="{{ route('forme_link.store') }}" method="post"  autocomplete="on" enctype="multipart/form-data">{{--  --}}

                  {!! csrf_field() !!}

                  <div class="row ">
                    @if($datos->imagen == 1)
                      <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                        <div class="card">
                          <img src="{{ asset('images/form')}}/{{$datos->img_cabecera}}" alt="{{$datos->nombre_evento}} {{date('Y')}}" class="img-fluid">
                          
                          <!--card-img-top -->
                          <div class="card-body">
                            <h1 class="card-title text-center mb-3" style="color: #dc3545;">{!!$datos->nombre_evento!!}</h1>
                            <p>
                              {!! $datos->descripcion_form !!}
                            </p>

                            @if(Session::has('dni'))
                            <p class="alert alert-danger">{{ Session::get('dni') }}</p>
                            @endif
                            @if(Session::has('dni_registrado'))
                            <p class="alert alert-warning">{{ Session::get('dni_registrado') }}</p>
                            @endif
                          </div>
                        </div>
                      </div>
                    @endif

                    <div class="col-sm-12 col-md-12  grid-margin stretch-card">

                      <div class="card">
                        <div class="card-body">
                          @if($datos->imagen != 1)
                          <h1 class="card-title text-center mb-1 display-4" style="color: #dc3545;">{!!$datos->nombre_evento!!}</h1>
                          <h2 class="display-5 text-center mb-3">{!!$datos->descripcion!!}</h2>
                          <p>
                            {!! $datos->descripcion_form !!}
                          </p>
                          @endif

                          <h4 class="card-title">Acerca de la contribución / About the contribution</h4>
                          <p class="card-text">
                             <strong class="text-danger">* Campos obligatorios </strong>
                          </p>

                          <div class="form-group row">
                            <div class="col-sm-12">

                              @if(count($errors)>0)
                                <div class="alert alert-danger">
                                  Error:<br>
                                  <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                  </ul>
                                </div>
                              @endif
                            </div>
                          </div>

                          <div class="row">
                            @if($datos->tipo_doc == 1)
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="title">Título de documento de trabajo / Title of the paper <span class="text-danger">*</span></label>
                                <input class="form-control text-uppercase" type="text" id="title" name="title" required=""  placeholder="" value="{{ old('title') }}" />
                              </div>
                            </div>
                            @endif
                            @if($datos->dni == 1)
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group ">
                                <label for="keywords">3-5 Palabras clave / keywords <span class="text-danger">*</span></label>
                                <input class="form-control text-uppercase" type="text" id="keywords" name="keywords" required=""  placeholder="keywords" value="{{ old('keywords') }}" />
                              </div>
                            </div>
                            @endif

                            @if($datos->grupo == 1)
                            <div class="col-sm-12 col-md-12">
                              <div class="form-group">
                                <label for="abstract">Resumen / Abstract </label>
                                <textarea class="form-control" id="abstract" name="abstract" placeholder="Resumen / Abstract" rows="4">{{ old('abstract') }}</textarea>
                              </div>
                            </div>
                            @endif
                            @if($datos->cv == 1)
                            <div class="col-sm-12 col-md-12">
                              <div class="form-group">
                                <label for="investigation">Cargar la investigación en formato pdf <span class="text-danger pl-3">* Máx. Peso 20Mb </span></label>
                                  <input id="investigation" class="form-control" type="file" name="investigation"  placeholder="" value="{{ old('investigation') }}" accept="image/jpg, image/jpeg" required>
                                  @if($errors->has('investigation'))
                                    <div class="error">{{ $errors->first('investigation') }}</div>
                                  @endif
                              </div>
                            </div>
                            @endif

                            <div class="col-sm-12 col-md-12">
                              <h4 class="card-title my-4">Autor principal / Main author </h4>
                            </div>

                            @if($datos->ap_paterno == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="last_name">Apellidos / Last name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="last_name" name="last_name"  placeholder="Apellido Paterno / Last Name" required="" value="{{ old('last_name') }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->nombres == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="first_name">Nombre / First name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="first_name" name="first_name"  placeholder="Nombres / Name" required="" value="{{ old('first_name') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->entidad == 1)
                            <div class="col-sm-12 col-md-4 ap_materno">
                              <div class="form-group ">
                                <label for="organization">Organización / Organization <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="organization" name="organization" required=""  placeholder="" value="{{ old('organization') }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->departamentos == 1)
                            <div class="col-sm-12 col-md-4 ap_materno">
                              <div class="form-group ">
                                <label for="department">Departamento, equipo de investigación / Department, research group <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="department" name="department" required="" placeholder="" value="{{ old('department') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->pais == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="country">País / Country <span class="text-danger">*</span></label>
                                    <select class="form-control" required="" name="country" id="country" class="country text-uppercase">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      <option value="PERU" >PERU</option>
                                      @foreach($countrys as $country)
                                      <option value="{{$country->name}}">{{$country->name}}</option>
                                      @endforeach
                                    </select>
                              </div>
                            </div>
                            @endif

                            @if($datos->email == 1)
                            <div class="col-sm-12 col-md-4 ap_materno">
                              <div class="form-group ">
                                <label for="email">Correo electrónico institucional / Institutional email addre <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email10" name="email" required=""  placeholder="" value="{{ old('email') }}">
                              </div>
                            </div>
                            @endif


                            <div class="col-sm-12 col-md-12">
                              <h4 class="card-title mt-4 mb-2 text-danger">Autor ponente / Presenting author </h4>
                              <h5 class="card-title mb-4">DATOS PERSONALES / PERSONAL DETAILS  </h5>
                            </div>


                            @if($datos->distrito == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="p_title">Título / Title <span class="text-danger" id="required_p_title">*</span></label>
                                  <select class="form-control text-uppercase" id="p_title" name="p_title" required>{{-- dynamic --}}
                                    <option value="">SELECCIONE / CHANGE</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Dr.">Dr.</option>
                                  </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->ap_materno == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="p_last_name">Apellidos / Last name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="p_last_name" name="p_last_name"  placeholder="Apellido Paterno / Last Name" required="" value="{{ old('p_last_name') }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->ap_materno == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="p_first_name">Nombre / First name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="p_first_name" name="p_first_name"  placeholder="Nombres / Name" required="" value="{{ old('p_first_name') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->compago == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="p_date_birth">Fecha de nacimiento / Date of birth <span class="text-danger">*</span></label>
                                <input class="form-control" required type="date" id="p_date_birth" name="p_date_birth"  placeholder="" value="{{ old('p_date_birth') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->decjur == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="p_country_birth">País de nacimiento / Country of birth <span class="text-danger">*</span></label>
                                <select class="form-control" required="" name="p_country_birth" id="p_country_birth" class="pais text-uppercase">
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option value="PERU" >PERU</option>
                                  @foreach($countrys as $country)
                                  <option value="{{$country->name}}">{{$country->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->ficins == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="p_country_residence">País de residencia / Country of residence <span class="text-danger">*</span></label>
                                <select class="form-control" required="" name="p_country_residence" id="p_country_residence" class="pais text-uppercase">
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option value="PERU" >PERU</option>
                                  @foreach($countrys as $country)
                                  <option value="{{$country->name}}">{{$country->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            @endif

                            @if($datos->direccion == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="p_nacionality">Nacionalidad / Nacionality <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="p_nacionality" name="p_nacionality" required  placeholder="Nacionalidad" value="{{ old('p_nacionality') }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->nvoucher == 1)
                            <div class="col-sm-12 col-md-8">
                              <div class="form-group ">
                                <label for="p_passport_number">Número de pasaporte / Passport number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="p_passport_number" name="p_passport_number" required=""  placeholder="Número de pasaporte / Passport number " value="{{ old('p_passport_number') }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->gradoprof == 1)
                            <div class="col-sm-12 col-md-12">
                              <div class="form-group">
                                <label for="p_passport_photo">Adjunte foto del pasaporte / Upload a photo of your passport <span class="text-danger pl-3">* Máx. Peso 300Kb</span></label>
                                  <input id="p_passport_photo" class="form-control" type="file" name="p_passport_photo"  placeholder="" value="{{ old('p_passport_photo') }}" accept="image/jpg, image/jpeg" required>

                                  @if($errors->has('p_passport_photo'))
                                    <div class="error">{{ $errors->first('p_passport_photo') }}</div>
                                  @endif
                              </div>
                            </div>
                            @endif
                            @if($datos->discapacidad == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="p_personal_address">Dirección personal / Personal Address </label>
                                  <input class="form-control text-uppercase" type="text" id="p_personal_address" name="p_personal_address"  placeholder="" value="{{ old('p_personal_address') }}">
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="p_expiration_date">Fecha de expiración / Expiration date <span class="text-danger">*</span></label>
                                <input class="form-control" required type="date" id="p_expiration_date" name="p_expiration_date"  placeholder="" value="{{ old('p_expiration_date') }}">
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="p_email">Correo electrónico personal / Personal email address </label>
                                <input class="form-control"  type="email" id="p_email" name="p_email"  placeholder="" value="{{ old('p_email') }}">
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-12">
                              <div class="form-group ">
                                <label for="short_biograph">Breve biografía / A short Biography <span class="text-danger">*</span></label>
                                <textarea name="short_biograph" class="form-control" required id="short_biograph" rows="10">{{ old('short_biograph') }}</textarea>
                              </div>
                            </div>


                            @endif




                           
                            @if($datos->fechadepo == 1)
                            <div class="col-sm-12 col-md-12">
                              <div class="form-">
                                <label for="email_labor" class="h4">DATOS DE LA ORGANIZACIÓN / ORGANIZATION DETAILS </label>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-4 pb-4">
                              <div class="custom-control custom-radio form-radio"><!-- custom-radio -->
                                <input type="radio" class="custom-control-input" id="university" name="type" required="" value="University">
                                <label class="custom-control-label" for="university">Universidad / University </label>
                              </div>
                              <div class="custom-control custom-radio form-radio">
                                <input type="radio" class="custom-control-input" id="supreme" name="type" required="" value="Supreme Audit Institution">
                                <label class="custom-control-label" for="supreme">Entidad Fiscalizadora Superior / Supreme Audit Institution </label>
                              </div>
                              <div class="custom-control custom-radio form-radio">
                                <input type="radio" class="custom-control-input" id="agency" name="type" required="" value="International development agency">
                                <label class="custom-control-label" for="agency">Agencia de desarrollo internacional / International development agency </label>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-4 pb-4">
                              <div class="custom-control custom-radio form-radio">
                                <input type="radio" class="custom-control-input" id="research_centre" name="type" required="" value="Research centre">
                                <label class="custom-control-label" for="research_centre">Centro de Investigación / Research centre </label>
                              </div>
                              <div class="custom-control custom-radio form-radio">
                                <input type="radio" class="custom-control-input" id="other_public" name="type" required="" value="Other public administration">
                                <label class="custom-control-label" for="other_public">Administración Pública / Other public administration </label>
                              </div>
                              <div class="custom-control custom-radio form-radio">
                                <input type="radio" class="custom-control-input" id="civil" name="type" required="" value="Civil society organisation (SCO)">
                                <label class="custom-control-label" for="civil">Organización de la Sociedad Civil / Civil Society Organisation (SCO) </label>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-4 pb-4">
                              <div class="custom-control custom-radio form-radio">
                                <input type="radio" class="custom-control-input" id="private_company" name="type" required="" value="Private company">
                                <label class="custom-control-label" for="private_company">Compañía privada / Private company </label>
                              </div>
                              <div class="custom-control custom-radio form-radio">
                                <input type="radio" class="custom-control-input" id="media" name="type" required="" value="Media">
                                <label class="custom-control-label" for="media">Media / Media </label>
                              </div>
                              <div class="custom-control custom-radio form-radio">
                                <input type="radio" class="custom-control-input" id="other" name="type" required="" value="Other">
                                <label class="custom-control-label" for="other">Otro / Other </label>
                              </div>
                            </div>
                            @endif

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="o_position_title">Nombre del cargo / Position title <span class="text-danger">*</span></label>
                                  <input class="form-control text-uppercase" type="text" id="o_position_title" name="o_position_title"  placeholder="" value="{{ old('o_position_title') }}" required>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="o_organization_name">Nombre de la organización / Organization name <span class="text-danger">*</span></label>
                                  <input class="form-control text-uppercase" type="text" id="o_organization_name" name="o_organization_name"  placeholder="" value="{{ old('o_organization_name') }}" required>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="o_department">Departamento, Equipo de investigación, Oficina / Department, Research Group, Office <span class="text-danger">*</span></label>
                                  <input class="form-control text-uppercase" type="text" id="o_department" name="o_department"  placeholder="" value="{{ old('o_department') }}" required>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="o_visitin_address">Dirección / Visiting address <span class="text-danger">*</span></label>
                                  <input class="form-control text-uppercase" type="text" id="o_visitin_address" name="o_visitin_address"  placeholder="" value="{{ old('o_visitin_address') }}" required>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="o_city_postal_code">Ciudad y código postal / City and postal code <span class="text-danger">*</span></label>
                                <input class="form-control text-uppercase" type="text" id="o_city_postal_code" name="o_city_postal_code"  placeholder="" value="{{ old('o_city_postal_code') }}" required>
                                {{-- <select class="form-control text-uppercase" required name="o_city_postal_code" id="o_city_postal_code" class="o_city_postal_code">
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option value="51" selected="">PERU</option>
                                  @foreach($countrys as $country)
                                  <option value="{{$country->phonecode}}">{{$country->name}}</option>
                                  @endforeach
                                </select> --}}
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="o_country">País / Country <span class="text-danger">*</span></label>
                                    <select class="form-control" required="" name="o_country" id="o_country" class="o_country text-uppercase">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      <option value="PERU" >PERU</option>
                                      @foreach($countrys as $country)
                                      <option value="{{$country->name}}">{{$country->name}}</option>
                                      @endforeach
                                    </select>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="o_phone_number">Número de teléfono / Phone number <span class="text-danger">*</span> <a href="#" id="editCel" style='display:none;'>Editar</a></label>
                                  <input class="form-control" type = "text"  id="o_phone_number" name="o_phone_number"  placeholder="" value="{{ old('o_phone_number') }}" required>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="o_mobile_number">Celular / Mobile <span class="text-danger">*</span> <a href="#" id="editCel" style='display:none;'>Editar</a></label>
                                  <input class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number"  maxlength = "9" id="o_mobile_number" name="o_mobile_number"  placeholder="999888777" value="{{ old('o_mobile_number') }}" required>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="o_institucion_email">Correo electrónico institucional / Institutional email address <span class="text-danger">*</span></label>
                                  <input class="form-control" type="email" id="o_institucion_email" name="o_institucion_email"  placeholder="" value="{{ old('o_institucion_email') }}" required>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="o_website">Página web / Website </label>
                                  <input class="form-control" type="text" id="o_website" name="o_website"  placeholder="www.enc.edu.pe" value="{{ old('o_website') }}">
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-12">
                              <h4 class="card-title my-4">ITINERARIO DE VIAJES PREFERENCIA/ TRAVEL ITINERARY PREFERENCE  </h4>
                            </div>

                         
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group ">
                                <label for="t_city">Lugar de salida: Ciudad / Place of departure: City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="t_city" name="t_city"  placeholder="" required="" value="{{ old('t_city') }}">
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="t_country">País / Country <span class="text-danger">*</span></label>
                                    <select class="form-control" required="" name="t_country" id="t_country" class="t_country text-uppercase">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      <option value="PERU" >PERU</option>
                                      @foreach($countrys as $country)
                                      <option value="{{$country->name}}">{{$country->name}}</option>
                                      @endforeach
                                    </select>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-8">
                              <div class="form-group ">
                                <label for="text_lugar">¿El lugar de retorno es diferente del lugar de partida? / Is the place of return different from the place of departure? <span class="text-danger">*</span></label>
                                
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1">
                              <div class="form-group ">
                                <div class="custom-control custom-radio form-radio">
                                  <input type="radio" class="custom-control-input" id="check_si" name="check" required="" value="YES">
                                  <label class="custom-control-label h6" for="check_si">Si/Yes </label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1">
                              <div class="form-group ">
                                <div class="custom-control custom-radio form-radio">
                                  <input type="radio" class="custom-control-input" id="check_no" checked="" name="check" required="" value="NO">
                                  <label class="custom-control-label h6" for="check_no">No </label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-2"></div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group ">
                                <label for="t_city_2">Lugar de retorno: Ciudad / Place of return: City <span class="text-danger _css_check">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="t_city_2" name="t_city_2"  placeholder="" required="" value="{{ old('t_city_2') }}">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="t_country_2">País / Country <span class="text-danger _css_check">*</span></label>
                                    <select class="form-control" required="" name="t_country_2" id="t_country_2" class="t_country_2 text-uppercase">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      <option value="PERU" >PERU</option>
                                      @foreach($countrys as $country)
                                      <option value="{{$country->name}}">{{$country->name}}</option>
                                      @endforeach
                                    </select>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="t_departure_date">Fecha de salida / Departure date <span class="text-danger">*</span></label>
                                  <input class="form-control" required type="date" id="t_departure_date" name="t_departure_date"  placeholder="" value="{{ old('t_departure_date') }}">
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="t_return_date">Fecha de regreso / Return date <span class="text-danger">*</span></label>
                                  <input class="form-control" required type="date" id="t_return_date" name="t_return_date"  placeholder="" value="{{ old('t_return_date') }}">
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-12">
                              <div class="form-group">
                                <label class="text-dark">(*) Si no es el mismo que el lugar de salida / If not the same as place of departure.</label>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-12">
                              <h4 class="card-title mt-4 mb-2">CONTACTO PERSONA DETALLES / CONTACT PERSON DETAILS</h4>
                              <p class="text-dark">
                                 (En caso de que la persona de contacto sea diferente del autor ponente / In case the contact person is different from the presenting autor) 
                              </p>
                            </div>

                            @if($datos->cargo == 1)
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group ">
                                <label for="c_full_name">Nombre completo / Full name </label>
                                <input type="text" class="form-control text-uppercase" id="c_full_name" name="c_full_name"  placeholder="Nombres / Name" value="{{ old('c_full_name') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->profesion == 1)
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group ">
                                <label for="c_position">Posición / Position </label>
                                <input type="text" class="form-control text-uppercase" id="c_position" name="c_position"  placeholder="Posición / Position" value="{{ old('c_position') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->profesion == 1)
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group ">
                                <label for="c_organization">Organización y departamento / Organization and department </label>
                                <input type="text" class="form-control text-uppercase" id="c_organization" name="c_organization"  placeholder="" value="{{ old('c_organization') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->celular == 1)
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group ">
                                <label for="c_phone_number">Número de teléfono / Phone number </label>
                                <input type="text" class="form-control text-uppercase" id="c_phone_number" name="c_phone_number"  placeholder="" value="{{ old('c_phone_number') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->email_labor == 1)
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group ">
                                <label for="c_email">Email / Email  </label>
                                <input type="email" class="form-control" id="c_email" name="c_email"  placeholder="" value="{{ old('c_email') }}">
                              </div>
                            </div>
                            @endif

                            <div class="col-sm-12 col-md-12">
                              <div class="form-group">
                                <label class="text-danger">* Los datos proporcionados no serán divulgados / The data provided will not be disclosed. </label>
                              </div>
                            </div>







                            @if($datos->terminos == 1)
                            <div class="col-sm-12 col-md-8">
                              <div class="form-check">
                                    <input type="checkbox" id="enc" name="check_auto" class="form-check-input check_click" required="">
                                  <label class="form-check-label" for="enc">
                                    He leído y acepto los <a href="#" onclick="eximForm()" data-toggle="modal">Término y Condiciones</a>
                                  
                                </label>
                                  <span class="small" style="position: relative;right: -9px;">
                                    Autorizo de manera expresa que mis datos sean cedidos a la Escuela Nacional de Control con la finalidad de poder recibir información de las actividades académicas y culturales
                                  </span>
                                </div>
                            </div>
                            @endif

                            <input type="hidden" id="eventos_id" name="eventos_id" value="{{ $id_evento }}">
                            <input type="hidden" id="fecha_inicial" name="fecha_inicial" value="{{ $fecha_inicial }}">
                            <input type="hidden" id="fecha_final" name="fecha_final" value="{{ $fecha_final }}">


                            <div class="col-sm-12 col-md-12">
                              <div class="form-group ">
                                
                                <div class="col-sm-12 col-md-12 pt-4 text-center">
                                  <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class="mdi mdi-checkbox-marked-circle "></i>ENVIAR / SEND</button>

                                  <div class="bar-loader d-none">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                  </div>

                                </div>
                                
                                <div class="col-sm-12 col-md-12 p-0 mt-3 text-center">
                                  @if($datos->imagen == 1)
                                  <img src="{{ asset('images/form')}}/{{$datos->img_footer}}" alt="{{$datos->nombre_evento}} {{date('Y')}}" class="img-fluid">
                                  @endif
                                  
                                </div>
                              </div>
                            </div>
                          </div> {{-- end row --}}

                        </div>
                      </div>
                    </div>

                    <div class="col-sm-12 col-md-12  grid-margin stretch-card"></div>

                  </div>

                </form>

                

              </div>
            </div>
          </div>
        </div>

        @if($datos->terminos == 1)
          @include('termino-condiciones.index')
        @endif

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->

        @include('layout.footer')
        <!-- end footer.php -->

        <!-- partial -->
      </div>

      <!-- main-panel ends -->
    </div>

    <!-- page-body-wrapper ends -->
  </div>

  <!-- container-scroller -->

{{-- <script src="{{ asset('js_a/vendor.bundle.base.js')}}"></script>  
<script src="{{ asset('js_a/vendor.bundle.addons.js')}}"></script> --}}

@endsection

@section('scripts')
<style>
.wizard > .content > .body{position: relative;}
.form-control2 label.form-radio{font-weight: bold;font-size: 14px;}
.form-control2 label.form-radio em{color:#21AFAF;font-style: normal;}
.form-control2 label.form-radio span{color:#556685;}
.texto_foros p{padding-left: 25px;}
.wizard > .content > .body input{display: inline-block;}

h1.card-title{
  font-family: Arial,Helvetica Neue,Helvetica;
  letter-spacing: -1px;
}
.card-body div strong{font-weight: 800;}

.form-radio label{font-size: 0.8rem;line-height: 23px;}

</style>

<script>
  $(document).ready(function(){

    $('.dynamic').change(function(){
      if($(this).val() != '')
      {
      var select = $(this).attr("id");
      if(select == "dpto"){
        select = "departamento";
      }
      var value = $(this).val();
      var dependent = $(this).data('dependent');
      var _token = $('input[name="_token"]').val();
      
      $.ajax({
          url:"{{ route('ubigeo.fetch') }}",
          method:"GET",//POST
          //data:{select:select, value:value, _token:_token, dependent:dependent},
          data:{select:select, value:value, dependent:dependent},
          success:function(result)
          {
          $('#'+dependent).html(result);
          }
      })
      }
    });

    $('#dpto').val('');
    $('#dpto').change(function(){
        $('#provincia').val('');
        $('#distrito').val('');
    });

    $('#provincia').change(function(){
        $('#distrito').val('');
    });

    var $form = $('#maestriaForm');
    var $btn = $('#actionSubmit');
    var $loader = $('.bar-loader');

    $($form).submit(function(e){
      //e.preventDefault();
      
      $loader.addClass('d-block');
      $btn.html('Procesando...').prop('disabled','disabled');
      $form.sleep(1000).submit();
      
    });

    var $nom = $("#t_city_2");
    var $country = $("#t_country_2");
    var $div = $("._css_check");
    // seleccionar todos
    $('#check_si').click(function() {
      $nom.attr({'disabled': true, 'required': false});
      $country.attr({'disabled': true, 'required': false});
      $div.css('display','none');
    });

    $('#check_no').click(function() {

      $nom.attr({'disabled': false, 'required': true});
      $country.attr({'disabled': false, 'required': true});
      $div.css('display','inline-block');
 
    });
      
  });

  /*(function(seconds) {
    var refresh,       
        intvrefresh = function() {
            clearInterval(refresh);
            refresh = setTimeout(function() {
               location.href = location.href;
            }, seconds * 1000);
        };

    $(document).on('keypress click', function() { intvrefresh() });
    intvrefresh();

  }(60*3));*/ // define here seconds
  
</script>
@endsection