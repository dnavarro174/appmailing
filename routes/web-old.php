<?php
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
/* Route::get('qrcode', function () {
    return QrCode::size(250)
        ->backgroundColor(255, 255, 204)
        ->generate('123');
});*/

Route::get('test', fn () => phpinfo());

// 1er MOD BDatos
Route::get('bd', ['as'=>'bd.index','uses'=>'App\Http\Controllers\bdController@index']);
Route::post('eliminar_bd', ['as'=>'bd.eliminarVarios','uses'=>'App\Http\Controllers\bdController@eliminarVarios']);
Route::get('bd/{id}/edit', ['as'=>'bd.edit','uses'=>'App\Http\Controllers\bdController@edit']);
Route::put('bd/{id}', ['as'=>'bd.update','uses'=>'App\Http\Controllers\bdController@update']);
 
//Route::resource
// CRUD TIPO_DOC 
Route::get('tipo_doc', ['as'=>'tipo_doc.index','uses'=>'App\Http\Controllers\TipoDocController@index']);
Route::get('tipo_doc/create', ['as'=>'tipo_doc.create','uses'=>'App\Http\Controllers\TipoDocController@create'])->middleware('VerificarToken');
Route::post('tipo_doc', ['as'=>'tipo_doc.store','uses'=>'App\Http\Controllers\TipoDocController@store'])->middleware('VerificarToken');
Route::get('tipo_doc/{id}', ['as'=>'tipo_doc.show','uses'=>'App\Http\Controllers\TipoDocController@show'])->middleware('VerificarToken');// ruta ver
Route::get('tipo_doc/{id}/edit', ['as'=>'tipo_doc.edit','uses'=>'App\Http\Controllers\TipoDocController@edit'])->middleware('VerificarToken');
Route::put('tipo_doc/{id}', ['as'=>'tipo_doc.update','uses'=>'App\Http\Controllers\TipoDocController@update'])->middleware('VerificarToken');
Route::delete('tipo_doc/{id}', ['as'=>'tipo_doc.destroy','uses'=>'App\Http\Controllers\TipoDocController@destroy']);
Route::post('tipo_doc2', ['as'=>'tipo_doc.eliminarVarios','uses'=>'App\Http\Controllers\TipoDocController@eliminarVarios']);

// CRUD ESTUDIANTES
// Recordatorio
Route::get('estudiantes/recor/{id}/{modulo}', ['as'=>'estudiantes.recordatorio', 'uses'=>'App\Http\Controllers\RecordatorioController@recordatorio']);
// Route::resource('estudiantes','EstudianteController');
Route::get('estudiantes', ['as'=>'estudiantes.index','uses'=>'App\Http\Controllers\EstudianteController@index']);
Route::get('estudiantes/create', ['as'=>'estudiantes.create','uses'=>'App\Http\Controllers\EstudianteController@create'])->middleware('VerificarToken');
Route::post('estudiantes', ['as'=>'estudiantes.store','uses'=>'App\Http\Controllers\EstudianteController@store'])->middleware('VerificarToken');
Route::get('estudiantes/{id}', ['as'=>'estudiantes.show','uses'=>'App\Http\Controllers\EstudianteController@show'])->middleware('VerificarToken');// ruta ver
Route::get('estudiantes/{id}/edit', ['as'=>'estudiantes.edit','uses'=>'App\Http\Controllers\EstudianteController@edit'])->middleware('VerificarToken');
Route::put('estudiantes/{id}', ['as'=>'estudiantes.update','uses'=>'App\Http\Controllers\EstudianteController@update'])->middleware('VerificarToken');
Route::delete('estudiantes/{id}', ['as'=>'estudiantes.destroy','uses'=>'App\Http\Controllers\EstudianteController@destroy']);
Route::post('eliminar_estudiantes', ['as'=>'estudiantes.eliminarVarios','uses'=>'App\Http\Controllers\EstudianteController@eliminarVarios']);
Route::get('estudiantes/{id}/s/{dni}/{evento}/{tipo}', ['as'=>'estudiantes.solicitud', 'uses'=>'App\Http\Controllers\EstudianteController@solicitud']);

// NEWSLETTER
Route::get('enviar_email', ['as'=>'estudiantes.enviar_email','uses'=>'App\Http\Controllers\NewsletterController@enviar_email']);
Route::post('email_estudiantes', ['as'=>'estudiantes.EmailEstudiantes','uses'=>'App\Http\Controllers\NewsletterController@EmailEstudiantes']);

// envio email mediante vista
Route::get('envio_email', ['as' => 'estudiantes.envio_email', 'uses' => 'App\Http\Controllers\EnvioEmailController@envio_email']);

Route::resource('newsletter','App\Http\Controllers\Newsletter_modController');
Route::post('eliminar_newsletter', ['as'=>'newsletter.eliminar_newsletter','uses'=>'App\Http\Controllers\Newsletter_modController@eliminar_newsletter']);

Route::get('verHTML/{id}', 'App\Http\Controllers\NewsletterController@verHTML');
Route::get('newsletter/confirmacion/{id}', 'App\Http\Controllers\NewsletterController@NewsletterConfirmacion');
Route::post('newsletter/gracias', ['as'=>'estudiantes.gracias','uses'=>'App\Http\Controllers\NewsletterController@NewsletterGracias']);

Route::get('estudiantes/ubigeo/{id}','App\Http\Controllers\EstudianteController@getDepartamentos');
Route::get('estudiantes/{aa}/ubigeo/{id}', 'App\Http\Controllers\EstudianteController@getDepartamentos');
Route::get('estudiantes/ubigeo2/{id}','App\Http\Controllers\EstudianteController@getDistritos');
Route::get('estudiantes/{aa}/ubigeo2/{id}','App\Http\Controllers\EstudianteController@getDistritosEdit');
Route::get('estudiantes/vdni/{id}/{eventos_id}','App\Http\Controllers\EstudianteController@getDNI');
Route::get('buscar_usuarios/{pais}/{dato?}', 'App\Http\Controllers\EstudianteController@buscar_estudiantes');


// export 
Route::get('export2', ['as' => 'estudiantes.export2', 'uses' => 'App\Http\Controllers\EstudianteController@EstudianteExport2']);
Route::post('import', ['as' => 'estudiantes.import', 'uses' => 'App\Http\Controllers\EstudianteController@EstudianteImport']);
Route::post('importsave', ['as' => 'estudiantes.importsave', 'uses' => 'App\Http\Controllers\EstudianteController@EstudianteImportSave']);
Route::get('importresults', ['as' => 'estudiantes.importresults', 'uses' => 'App\Http\Controllers\EstudianteController@EstudianteImportResults']);
Route::post('enviarI', ['as' => 'estudiantes.enviarI', 'uses' => 'App\Http\Controllers\EstudianteController@enviarInvitacionE']);

// usuario
Route::resource('usuarios','App\Http\Controllers\UsuariosController');
Route::post('eliminar_usuarios', ['as'=>'usuarios.eliminarVarios','uses'=>'App\Http\Controllers\UsuariosController@eliminarVarios']);
Route::get('usuarios/{id}/roles', ['as'=>'usuarios.roles','uses'=>'App\Http\Controllers\UsuariosController@roles'])->middleware('VerificarToken');
Route::post('usuarios_storeroles', ['as'=>'usuarios.storeRoles','uses'=>'App\Http\Controllers\UsuariosController@storeRoles']);


// ACTIVIDADES CAII
Route::resource('actividades','App\Http\Controllers\ActividadesController');
Route::post('eliminar_actividades', ['as'=>'actividades.eliminarVarios','uses'=>'App\Http\Controllers\ActividadesController@eliminarVarios']);

Route::get('actividades_fecha/{evento_id}/{fecha}', ['as'=>'actividades.actividades_fecha','uses'=>'App\Http\Controllers\EventosActividadesController@actividadesXfecha']);

Route::get('eventos_actividades/{evento_id}', ['as'=>'actividades.list_index','uses'=>'App\Http\Controllers\EventosActividadesController@index']);
Route::get('eventos_list_dias/{evento_id}', ['as'=>'actividades.list_dias','uses'=>'App\Http\Controllers\EventosActividadesController@list_dias']);
Route::get('eventos_form_actividad/{evento_id}/{dia}/{actividad_id}/{num}', ['as'=>'actividades.form_actividad','uses'=>'App\Http\Controllers\EventosActividadesController@form_actividad']);
Route::post('actividades_store', ['as'=>'actividades_form.store','uses'=>'App\Http\Controllers\EventosActividadesController@store'])->middleware('VerificarToken'); 


// ASISTENCIA
Route::get('asistencia/actividades', ['as'=>'asistencia.act','uses'=>'App\Http\Controllers\AsistenciaController@asistencia_act']);

Route::resource('asistencia','App\Http\Controllers\AsistenciaController');
Route::post('asistencia/store_act', ['as'=>'asistencia.store_act','uses'=>'App\Http\Controllers\AsistenciaController@store_act']);
Route::post('e_asistencia', ['as'=>'asistencia.eliminarVarios','uses'=>'App\Http\Controllers\AsistenciaController@eliminarVarios']);
Route::post('export_asistencia', ['as' => 'asistencia.export', 'uses' => 'App\Http\Controllers\AsistenciaController@AsistenciaExport']);
Route::get('exp_asistencia/{id}', ['as' => 'asistencia.exp', 'uses' => 'App\Http\Controllers\AsistenciaController@exp_asistencia']);

Route::get('asistencia/consulta/{dni}', ['as'=>'asistencia.validar_dni','uses'=>'App\Http\Controllers\AsistenciaController@validar_dni']);
Route::get('asistencia/consultax/{dni}', ['as'=>'asistencia.validar_dni_x_hora','uses'=>'App\Http\Controllers\AsistenciaController@validar_dni_x_hora']);
Route::get('inscritos_por_actividades', ['as'=>'asistencia.inscritos_por_actividades','uses'=>'App\Http\Controllers\AsistenciaController@inscritos_foros']);

// ASIGNAR FOROS MANUALMENTE
Route::get('asignar_programacion', ['as'=>'asistencia.asignar_programacion','uses'=>'App\Http\Controllers\AsignarForosController@asignar_programacion']);
Route::get('asignar_foros', ['as'=>'asistencia.asignar_foros','uses'=>'App\Http\Controllers\AsignarForosController@asignar_foros']);
Route::post('asignar_foros_store', ['as'=>'asistencia.store_foros','uses'=>'App\Http\Controllers\AsignarForosController@store']);

Route::get('exportCaii', ['as' => 'asistencia.exportcaii', 'uses' => 'App\Http\Controllers\AsignarForosController@EstudianteCaiiExport']);
Route::get('exportEstudiantes', ['as' => 'asistencia.export_confirmar_participantes', 'uses' => 'App\Http\Controllers\AsignarForosController@ParticipantesxConfirmar']);



// Plantilla HTML
Route::resource('plantillaemail','App\Http\Controllers\PlantillaemailController');
Route::post('eliminar_plantillaemail', ['as'=>'plantillaemail.eliminarVarios','uses'=>'App\Http\Controllers\PlantillaemailController@eliminarVarios']);
Route::get('plantillaemail/procesaremailsxlote/{id}','App\Http\Controllers\PlantillaemailController@procesarEmail');
Route::get('pruebaHTML','App\Http\Controllers\PlantillaemailController@procesarPlantilla');

// HISTORIA EMAIL
Route::resource('historiaemail','App\Http\Controllers\HistoriaemailController');
Route::post('eliminar_historiaemail', ['as'=>'historiaemail.eliminarVarios','uses'=>'App\Http\Controllers\HistoriaemailController@eliminarVarios']);


// ROLES
//Route::resource('roles','App\Http\Controllers\RolesController');

Route::get('roles', ['as'=>'roles.index','uses'=>'App\Http\Controllers\RolesController@index']);
Route::get('roles/create', ['as'=>'roles.create','uses'=>'App\Http\Controllers\RolesController@create'])->middleware('VerificarToken');
Route::post('roles', ['as'=>'roles.store','uses'=>'App\Http\Controllers\RolesController@store'])->middleware('VerificarToken');

Route::post('roles/storepermisos', ['as'=>'roles.storepermisos','uses'=>'App\Http\Controllers\RolesController@storepermisos'])->middleware('VerificarToken');
Route::post('roles/storepermisosall', ['as'=>'roles.storepermisosall','uses'=>'App\Http\Controllers\RolesController@storepermisosall'])->middleware('VerificarToken');

Route::get('roles/{id}', ['as'=>'roles.show','uses'=>'App\Http\Controllers\RolesController@show'])->middleware('VerificarToken');// ruta ver
Route::get('roles/{id}/edit', ['as'=>'roles.edit','uses'=>'App\Http\Controllers\RolesController@edit'])->middleware('VerificarToken');
Route::get('roles/{id}/permisos', ['as'=>'roles.permisos','uses'=>'App\Http\Controllers\RolesController@permisos'])->middleware('VerificarToken');
Route::put('roles/{id}', ['as'=>'roles.update','uses'=>'App\Http\Controllers\RolesController@update'])->middleware('VerificarToken');
Route::delete('roles/{id}', ['as'=>'roles.destroy','uses'=>'App\Http\Controllers\RolesController@destroy']);
Route::post('eliminar_roles', ['as'=>'roles.eliminarVarios','uses'=>'App\Http\Controllers\RolesController@eliminarVarios']);


//FORM EVENTO CAII
Route::get('evento', ['as' => 'caii.login', 'uses' => 'App\Http\Controllers\CaiiController@index']);
Route::post('evento/caii', ['as'=>'caii.validacionlogin','uses'=>'App\Http\Controllers\CaiiController@validacionlogin']);//foros_datos
Route::get('evento/foros_datos', ['as' => 'caii.foros_datos', 'uses' => 'CaiiController@foros_datos']);
Route::get('evento/caii/{id}/edit', ['as'=>'caii.edit','uses'=>'App\Http\Controllers\CaiiController@edit']);
Route::put('evento/caii/{id}', ['as'=>'caii.update','uses'=>'App\Http\Controllers\CaiiController@update']);//
Route::get('evento/seleccionar_act', ['as'=>'caii.seleccionar','uses'=>'App\Http\Controllers\CaiiController@seleccionar_act']);
Route::get('evento/seleccionar_act_demo', ['as'=>'caii.seleccionar_demo','uses'=>'App\Http\Controllers\CaiiController@seleccionar_act_demo']);

// con departamentos
Route::get('evento/caii/{aa}/ubigeo/{id}', 'App\Http\Controllers\CaiiController@getDepartamentos');
Route::get('evento/confirmacion', ['as' => 'caii.confirmacion', 'uses' => 'App\Http\Controllers\CaiiController@confirmacion']);
Route::post('evento/confirmar', ['as'=>'caii.confirmar','uses'=>'App\Http\Controllers\CaiiController@confirmar_act']);
// con login baja
Route::get('login_baja', ['as' => 'caii.baja_login', 'uses' => 'App\Http\Controllers\CaiiController@login_baja']);
Route::post('baja_validacion', ['as' => 'caii.baja', 'uses' => 'App\Http\Controllers\CaiiController@baja']);
Route::get('baja/create', ['as' => 'caii.baja_create', 'uses' => 'App\Http\Controllers\CaiiController@baja_create']);
Route::get('estudiantes/{dni}/{evento}', ['as' => 'caii.baja_manual', 'uses' => 'App\Http\Controllers\CaiiController@baja_manual']);
Route::post('baja', ['as' => 'caii.baja_store', 'uses' => 'App\Http\Controllers\CaiiController@baja_store']);
Route::get('adios', ['as' => 'caii.baja_adios', 'uses' => 'App\Http\Controllers\CaiiPGController@adios_baja']);

// MOD FORM BAJAS
Route::get('bajas', ['as'=>'bajas.index','uses'=>'App\Http\Controllers\BajasController@index']);
Route::get('upgrade', ['as'=>'upgrate.index','uses'=>'App\Http\Controllers\BajasController@upgrate']);
Route::get('bajas/{id}/edit', ['as'=>'bajas.edit','uses'=>'App\Http\Controllers\BajasController@edit']);


// LINK INVITACION Y CONFIRMACION 
Route::get('evento/html/invit', ['as' => 'caii.invitacion', 'uses' => 'App\Http\Controllers\CaiiController@invitacion']);
Route::get('evento/html/conf', ['as'=>'caii.confirmacion2', 'uses'=>'App\Http\Controllers\CaiiController@confirmacion2']);

// CONFIRMACION FORMULARIO SI NO
Route::get('form_confirmacion', ['as'=>'form_confirmacion.index','uses'=>'App\Http\Controllers\ConfirmacionController@index']);
Route::get('form_confirmacion/create', ['as'=>'form_confirmacion.create','uses'=>'App\Http\Controllers\ConfirmacionController@create']);
Route::post('form_confirmacion', ['as'=>'form_confirmacion.store','uses'=>'App\Http\Controllers\ConfirmacionController@store']);
Route::get('form_confirmacion/{id}', ['as'=>'form_confirmacion.show','uses'=>'App\Http\Controllers\ConfirmacionController@show']);
Route::get('form_confirmacion/{id}/edit', ['as'=>'form_confirmacion.edit','uses'=>'App\Http\Controllers\ConfirmacionController@edit']);
Route::put('form_confirmacion/{id}', ['as'=>'form_confirmacion.update','uses'=>'App\Http\Controllers\ConfirmacionController@update']);
Route::delete('form_confirmacion/{id}', ['as'=>'form_confirmacion.destroy','uses'=>'App\Http\Controllers\ConfirmacionController@destroy']);
Route::post('eliminar_form_confirmacion', ['as'=>'form_confirmacion.eliminarVarios','uses'=>'App\Http\Controllers\ConfirmacionController@eliminarVarios']);

//FORM CAII PG(PUBLICO GENERAL)
Route::resource('evento/caii_pg','App\Http\Controllers\CaiiPGController');
//Route::get('evento/caii_pg/create', ['as'=>'caii_pg.create','uses'=>'App\Http\Controllers\CaiiPGController@create']);
Route::get('pre-inscripcion/{id}/{tipo}/', ['as'=>'caii_pg_presencial.create','uses'=>'App\Http\Controllers\CaiiPGController@create_presencial']);
Route::get('pre-inscripcion-virtual/{id}/{tipo}/', ['as'=>'caii_pg_virtual.create','uses'=>'App\Http\Controllers\CaiiPGController@create_virtual']);
Route::get('evento/caii_pg/ubigeo/{id}','App\Http\Controllers\CaiiPGController@getDepartamentos');
Route::get('evento/caii_pg/vdni/{id}/{evento}/','App\Http\Controllers\CaiiPGController@getDNI');
Route::get('evento/gracias_pg', ['as' => 'caii_pg.gracias_pg', 'uses' => 'App\Http\Controllers\CaiiPGController@gracias_pg']);

//Primer MODULO CAII 
Route::get('caii', ['as' => 'caii.index', 'uses' => 'App\Http\Controllers\ModuloCaiiController@index']);
/* Route::get('caii_json', ['as' => 'caii.create_json', 'uses' => 'App\Http\Controllers\ModuloCaiiController@create_json']);
Route::post('caii_json', ['as'=>'caii.store_json','uses'=>'App\Http\Controllers\ModuloCaiiController@store_json']);  */

Route::get('caiieventos', ['as'=>'caiieventos.index','uses'=>'App\Http\Controllers\ModuloCaiiController@index']);
Route::get('caiieventos/create', ['as'=>'caiieventos.create','uses'=>'App\Http\Controllers\ModuloCaiiController@create']);
Route::post('caiieventos', ['as'=>'caiieventos.store','uses'=>'App\Http\Controllers\ModuloCaiiController@store']);

Route::get('caiieventos/plant/create', ['as'=>'caii_plantilla.create','uses'=>'App\Http\Controllers\ModuloCaiiController@createPlantilla']);
Route::post('caiieventos/plant', ['as'=>'caii_plantilla.store','uses'=>'App\Http\Controllers\ModuloCaiiController@storePlantilla']);
Route::get('caiieventos/plant/{id}/edit', ['as'=>'caii_plantilla.edit','uses'=>'App\Http\Controllers\ModuloCaiiController@editPlantilla']);
Route::put('caiieventos/plant/{id}', ['as'=>'caii_plantilla.update','uses'=>'App\Http\Controllers\ModuloCaiiController@updatePlantilla']);
Route::get('caiieventos/plant/{id}/verHTML/{id2}/{id3}', 'App\Http\Controllers\ModuloCaiiController@verHTML');
Route::get('caiieventos/{id}/verHTML/{id2}/{id3}', 'App\Http\Controllers\ModuloCaiiController@verHTML_e');

Route::get('caiieventos/form/create', ['as'=>'caii_form.create','uses'=>'App\Http\Controllers\ModuloCaiiController@createForm']);
Route::post('caiieventos/form', ['as'=>'caii_form.store','uses'=>'App\Http\Controllers\ModuloCaiiController@storeForm']);

Route::get('caiieventos/form/{id}/edit', ['as'=>'caii_form.edit','uses'=>'App\Http\Controllers\ModuloCaiiController@editForm']);
Route::put('caiieventos/form/{id}', ['as'=>'caii_form.update','uses'=>'App\Http\Controllers\ModuloCaiiController@updateForm']);


Route::get('caiieventos/{id}', ['as'=>'caiieventos.show','uses'=>'App\Http\Controllers\ModuloCaiiController@show']);
Route::get('caiieventos/{id}/edit', ['as'=>'caiieventos.edit','uses'=>'App\Http\Controllers\ModuloCaiiController@edit']);
Route::put('caiieventos/{id}', ['as'=>'caiieventos.update','uses'=>'App\Http\Controllers\ModuloCaiiController@update']);
Route::delete('caiieventos/{id}', ['as'=>'caiieventos.destroy','uses'=>'App\Http\Controllers\ModuloCaiiController@destroy']);
Route::post('eliminar_caiieventos', ['as'=>'caiieventos.eliminarVarios','uses'=>'App\Http\Controllers\ModuloCaiiController@eliminarVarios']);

Route::get('actividades_id/{id}', ['as' => 'caiieventos.idAct', 'uses' => 'ModuloCaiiController@idAct']);
Route::post('actividades_id/enviar', ['as'=>'caiieventos.enviar','uses'=>'App\Http\Controllers\ModuloCaiiController@idAct_enviar']);

Route::get('msn_whatsapp', ['as'=>'msn.whats', 'uses'=>'App\Http\Controllers\PaqueteMsnController@msn_whatsapp']);

// REPORTES
Route::get('reportes/general_pre',['as'=>'reportes.general','uses'=>'App\Http\Controllers\CaiiReporteController@general']);
Route::get('reportes_modulos',['as'=>'reportes.modulos','uses'=>'App\Http\Controllers\AjustesController@index']);
Route::get('reportes_modulos/excel',['as'=>'reportes.exporta','uses'=>'App\Http\Controllers\AjustesController@excel']);
Route::get('reportes_modulos/exportar_excel', ['as' => 'exportar_excel', 'uses' => 'App\Http\Controllers\AjustesController@exportar_excel']);
// AJUSTES
Route::resource('ajustes', 'App\Http\Controllers\AjustesController')->only(['edit','update']);

// EXPORTAR
Route::get('reportes/g_exp',['as'=>'reportes.g_exp','uses'=>'App\Http\Controllers\CaiiReporteController@g_exp']);

Route::get('reportes/general_inv',['as'=>'reportes.general_inv','uses'=>'App\Http\Controllers\CaiiReporteController@general_inv']);
Route::get('reportes/registrados', ['as'=>'reportes.registrados', 'uses'=>'App\Http\Controllers\CaiiReporteController@registrados']);
Route::get('reportes/registrados_eventos', ['as'=>'reportes.registrados_eventos', 'uses'=>'App\Http\Controllers\CaiiReporteController@registrados_Mod_eventos']);
Route::get('reportes/r_actividad', ['as'=>'reportes.r_actividad', 'uses'=>'App\Http\Controllers\CaiiReporteController@r_actividad']);

Route::get('reportes/a_general',['as'=>'reportes.a_general','uses'=>'App\Http\Controllers\CaiiReporteController@a_general']);
Route::get('reportes/a_actividad',['as'=>'reportes.a_actividad','uses'=>'App\Http\Controllers\CaiiReporteController@a_actividad']);

Route::get('reportes/asist_exportar',['as'=>'reportes.asist_exportar','uses'=>'App\Http\Controllers\CaiiReporteController@asist_exportar']);

Route::get('reportes/preinscritos_aceptados',['as'=>'reportes.pre_aprobados','uses'=>'App\Http\Controllers\CaiiReporteController@rep_preinscritos_aprobados']);

//Segundo MOD EVENTOS 
//Route::get('eventos', ['as' => 'caii.index', 'uses' => 'ModuloCaiiController@index']);
Route::get('eventos', ['as'=>'eventos.index','uses'=>'App\Http\Controllers\ModuloEventosController@index']);
Route::get('eventos/create', ['as'=>'eventos.create','uses'=>'App\Http\Controllers\ModuloEventosController@create']);
Route::post('eventos', ['as'=>'eventos.store','uses'=>'App\Http\Controllers\ModuloEventosController@store']);
Route::get('eventos/{id}', ['as'=>'eventos.show','uses'=>'App\Http\Controllers\ModuloEventosController@show']);
Route::get('eventos/{id}/edit', ['as'=>'eventos.edit','uses'=>'App\Http\Controllers\ModuloEventosController@edit']);
Route::put('eventos/{id}', ['as'=>'eventos.update','uses'=>'App\Http\Controllers\ModuloEventosController@update']);
Route::delete('eventos/{id}', ['as'=>'eventos.destroy','uses'=>'App\Http\Controllers\ModuloEventosController@destroy']);
Route::post('eliminar_eventos', ['as'=>'eventos.eliminarVarios','uses'=>'App\Http\Controllers\ModuloEventosController@eliminarVarios']);
Route::get('show/verHTML/{id}', ['as'=>'eventos.htm','uses'=>'App\Http\Controllers\ModuloEventosController@verHTML']);
// ver gafete en html
Route::get('eventos/{id}/verHTML/{id2}/{id3}', 'ModuloEventosController@verHTML_e');
Route::get('eventos/form/create', ['as'=>'eventos_form.create','uses'=>'App\Http\Controllers\ModuloEventosController@createForm']);
Route::post('eventos/form', ['as'=>'eventos_form.store','uses'=>'App\Http\Controllers\ModuloEventosController@storeForm']);

Route::get('eventos/form/{id}/edit', ['as'=>'eventos_form.edit','uses'=>'App\Http\Controllers\ModuloEventosController@editForm']);
Route::put('eventos/form/{id}', ['as'=>'eventos_form.update','uses'=>'App\Http\Controllers\ModuloEventosController@updateForm']);

// LINK EVENTOS
Route::get('eventos/ev/create', ['as'=>'ev.create','uses'=>'App\Http\Controllers\EvInscripcionController@create']);
Route::post('eventos/ev', ['as'=>'ev.store','uses'=>'App\Http\Controllers\EvInscripcionController@store']);
Route::get('getUbigeo/{id}','App\Http\Controllers\EvInscripcionController@getDepartamentos');
Route::get('getDNI/{id}/{evento}/','App\Http\Controllers\EvInscripcionController@getDNI');
Route::get('eventos/gracias', ['as' => 'ev.gracias', 'uses' => 'App\Http\Controllers\EvInscripcionController@gracias']);


// LEADS
Route::get('leads', ['as'=>'leads.index','uses'=>'App\Http\Controllers\EEstudianteController@index']);
Route::get('leads/create', ['as'=>'leads.create','uses'=>'App\Http\Controllers\EEstudianteController@create'])->middleware('VerificarToken');
Route::post('leads', ['as'=>'leads.store','uses'=>'App\Http\Controllers\EEstudianteController@store'])->middleware('VerificarToken');
Route::get('leads/{id}', ['as'=>'leads.show','uses'=>'App\Http\Controllers\EEstudianteController@show'])->middleware('VerificarToken');
Route::get('leads/{id}/edit', ['as'=>'leads.edit','uses'=>'App\Http\Controllers\EEstudianteController@edit'])->middleware('VerificarToken');
Route::put('leads/{id}', ['as'=>'leads.update','uses'=>'App\Http\Controllers\EEstudianteController@update'])->middleware('VerificarToken');
Route::delete('leads/{id}', ['as'=>'leads.destroy','uses'=>'App\Http\Controllers\EEstudianteController@destroy']);
Route::post('eliminar_leads', ['as'=>'leads.eliminarVarios','uses'=>'App\Http\Controllers\EEstudianteController@eliminarVarios']);
Route::get('leads/{id}/s/{dni}/{evento}/{tipo}', ['as'=>'leads.solicitud', 'uses'=>'App\Http\Controllers\EEstudianteController@solicitud']);

Route::get('file/{doc}/{file}/{tipo}', ['as'=>'preview.file','uses'=>'App\Http\Controllers\PreviewController@preview']);

// REPORTES EVENTOS - REGISTRADOS
Route::get('reportes/e_registrados', ['as'=>'reportes.e_registrados', 'uses'=>'App\Http\Controllers\EventosReporteController@registrados']);
Route::get('reportes/excel_registrados',['as'=>'reportes.excel_ev_registrados','uses'=>'App\Http\Controllers\EventosReporteController@excel_registrados']);
# se borro xk no se usa 2023
//Route::get('reportes/all',['as'=>'reportes.all','uses'=>'App\Http\Controllers\EventosReporteController@all']);

// NEWSLETTER
//Route::get('enviar_email', ['as'=>'leads.enviar_email','uses'=>'App\Http\Controllers\NewsletterController@enviar_email']);
Route::get('mailing', ['as'=>'mailing.index','uses'=>'App\Http\Controllers\MailingController@index']);
Route::post('mailing', ['as'=>'mailing.store','uses'=>'App\Http\Controllers\MailingController@store']);

Route::resource('newsletter','App\Http\Controllers\Newsletter_modController');
Route::post('eliminar_newsletter', ['as'=>'newsletter.eliminar_newsletter','uses'=>'App\Http\Controllers\Newsletter_modController@eliminar_newsletter']);


Route::get('newsletter/confirmacion/{id}', 'App\Http\Controllers\NewsletterController@NewsletterConfirmacion');
Route::post('newsletter/gracias', ['as'=>'leads.gracias','uses'=>'App\Http\Controllers\NewsletterController@NewsletterGracias']);

Route::get('leads/ubigeo/{id}','App\Http\Controllers\EEstudianteController@getDepartamentos');
Route::get('leads/{aa}/ubigeo/{id}', 'App\Http\Controllers\EEstudianteController@getDepartamentos');
Route::get('leads/ubigeo2/{id}','App\Http\Controllers\EEstudianteController@getDistritos');
Route::get('leads/{aa}/ubigeo2/{id}','App\Http\Controllers\EEstudianteController@getDistritosEdit');
Route::get('leads/vdni/{id}/{eventos_id}','App\Http\Controllers\EEstudianteController@getDNI');
Route::get('buscar_usuarios/{pais}/{dato?}', 'App\Http\Controllers\EEstudianteController@buscar_leads');

// export 
//Route::get('leads_export', ['as' => 'leads.export', 'uses' => 'App\Http\Controllers\EEstudianteController@EstudianteExport']);quitado 2023
Route::post('leads_import', ['as' => 'leads.import', 'uses' => 'App\Http\Controllers\EEstudianteController@EstudianteImport']);
Route::post('leads_importsave', ['as' => 'leads.importsave', 'uses' => 'App\Http\Controllers\EEstudianteController@EstudianteImportSave']);
Route::get('leads_importresults', ['as' => 'leads.importresults', 'uses' => 'App\Http\Controllers\EEstudianteController@EstudianteImportResults']);
Route::post('leads_enviarI', ['as' => 'leads.enviarI', 'uses' => 'App\Http\Controllers\EEstudianteController@enviarInvitacionE']);


// Tercer MOD BDatos 
Route::get('bd/importaemails', ['as'=>'bd.importa-emails','uses'=>'App\Http\Controllers\bdController@importaCorregidos']);
Route::get('bd', ['as'=>'bd.index','uses'=>'App\Http\Controllers\bdController@index']);
Route::post('eliminar_bd', ['as'=>'bd.eliminarVarios','uses'=>'App\Http\Controllers\bdController@eliminarVarios']);
Route::get('bd/{id}/edit', ['as'=>'bd.edit','uses'=>'App\Http\Controllers\bdController@edit']);
Route::put('bd/{id}', ['as'=>'bd.update','uses'=>'App\Http\Controllers\bdController@update']);

// HISTORIAL
Route::post('bd/historial', ['as'=>'bd.historial','uses'=>'App\Http\Controllers\bdController@historial']);
Route::get('historial/{id}', ['as' => 'estudiantes.historial', 'uses' => 'App\Http\Controllers\bdController@Historial']);

// export 
Route::post('bd_import', ['as' => 'bd.import', 'uses' => 'App\Http\Controllers\bdController@EstudianteImport']);
Route::post('bd_importsave', ['as' => 'bd.importsave', 'uses' => 'App\Http\Controllers\bdController@EstudianteImportSave']);
Route::get('bd_importresults', ['as' => 'bd.importresults', 'uses' => 'App\Http\Controllers\bdController@EstudianteImportResults']);



// Cuarto MOD ACT. ACADEMICAS - PENDIENTE 
Route::get('academico', ['as'=>'academico.index','uses'=>'App\Http\Controllers\ModAcademicoController@index']);
Route::get('academico/create', ['as'=>'academico.create','uses'=>'App\Http\Controllers\ModAcademicoController@create']);
Route::post('academico', ['as'=>'academico.store','uses'=>'App\Http\Controllers\ModAcademicoController@store']);
Route::get('academico/{id}', ['as'=>'academico.show','uses'=>'App\Http\Controllers\ModAcademicoController@show']);
Route::get('academico/{id}/edit', ['as'=>'academico.edit','uses'=>'App\Http\Controllers\ModAcademicoController@edit']);
Route::put('academico/{id}', ['as'=>'academico.update','uses'=>'App\Http\Controllers\ModAcademicoController@update']);
Route::delete('academico/{id}', ['as'=>'academico.destroy','uses'=>'App\Http\Controllers\ModAcademicoController@destroy']);
Route::post('eliminar_academico', ['as'=>'academico.eliminarVarios','uses'=>'App\Http\Controllers\ModAcademicoController@eliminarVarios']);
// Load Aulas
Route::get('loadAulas', ['as'=>'academico.loadAulas','uses'=>'App\Http\Controllers\ModAcademicoController@loadAulas']);

// Import cursos y programas
Route::get('academico_export', ['as' => 'academico.export', 'uses' => 'App\Http\Controllers\ModAcademicoController@CursoExport']);
Route::post('academico_import', ['as' => 'academico.import', 'uses' => 'App\Http\Controllers\ModAcademicoController@CursoImport']);
Route::post('academico_importsave', ['as' => 'academico.importsave', 'uses' => 'App\Http\Controllers\ModAcademicoController@CursoImportSave']);
Route::get('academico_importresults', ['as' => 'academico.importresults', 'uses' => 'App\Http\Controllers\ModAcademicoController@CursoImportResults']);

// DOCENTES
Route::resource('docentes','App\Http\Controllers\DocentesController');
Route::post('eliminar_docentes', ['as'=>'docentes.eliminarVarios','uses'=>'App\Http\Controllers\DocentesController@eliminarVarios']);
Route::get('addDocentes/{evento_id}/{dia}/{actividad_id}/{num}', ['as'=>'academico.addDocentes','uses'=>'App\Http\Controllers\DocentesController@addDocentes']);

Route::get('autocomplete/findDoc', 'App\Http\Controllers\DocentesController@findDocentes');

// ESTUDIANTES DE LOS CURSOS
Route::get('est', ['as'=>'est.index','uses'=>'App\Http\Controllers\Est_AcademicoController@index']);
Route::get('est/create', ['as'=>'est.create','uses'=>'App\Http\Controllers\Est_AcademicoController@create'])->middleware('VerificarToken');
Route::post('est', ['as'=>'est.store','uses'=>'App\Http\Controllers\Est_AcademicoController@store'])->middleware('VerificarToken');
Route::get('est/{id}', ['as'=>'est.show','uses'=>'App\Http\Controllers\Est_AcademicoController@show'])->middleware('VerificarToken');
Route::get('est/{id}/edit', ['as'=>'est.edit','uses'=>'App\Http\Controllers\Est_AcademicoController@edit'])->middleware('VerificarToken');
Route::put('est/{id}', ['as'=>'est.update','uses'=>'App\Http\Controllers\Est_AcademicoController@update'])->middleware('VerificarToken');
Route::delete('est/{id}', ['as'=>'est.destroy','uses'=>'App\Http\Controllers\Est_AcademicoController@destroy']);
Route::post('eliminar_est', ['as'=>'est.eliminarVarios','uses'=>'App\Http\Controllers\Est_AcademicoController@eliminarVarios']);
Route::get('est/{id}/s/{dni}/{evento}/{tipo}', ['as'=>'est.solicitud', 'uses'=>'App\Http\Controllers\Est_AcademicoController@solicitud']);

Route::get('est/{aa}/ubigeo/{id}', 'App\Http\Controllers\EEstudianteController@getDepartamentos');

Route::get('est_export', ['as' => 'est.export', 'uses' => 'App\Http\Controllers\Est_AcademicoController@EstudianteExport']);
Route::post('est_import', ['as' => 'est.import', 'uses' => 'App\Http\Controllers\Est_AcademicoController@EstudianteImport']);
Route::post('est_importsave', ['as' => 'est.importsave', 'uses' => 'App\Http\Controllers\Est_AcademicoController@EstudianteImportSave']);
Route::get('est_importresults', ['as' => 'est.importresults', 'uses' => 'App\Http\Controllers\Est_AcademicoController@EstudianteImportResults']);
Route::post('est_enviarI', ['as' => 'est.enviarI', 'uses' => 'App\Http\Controllers\Est_AcademicoController@enviarInvitacionE']);

// END MOD ACT. ACADEMICAS

// MOD. AUDITORIA EST
Route::get('auditoriae', ['as'=>'auditoriae.index','uses'=>'App\Http\Controllers\AuditoriasEstuController@index']);
Route::get('auditoriae/{id}', ['as'=>'auditoriae.show','uses'=>'App\Http\Controllers\AuditoriasEstuController@show'])->middleware('VerificarToken');// ruta ver
Route::post('eliminar_auditoriae', ['as'=>'auditoriae.eliminarVarios','uses'=>'App\Http\Controllers\AuditoriasEstuController@eliminarVarios']);

// MOD. AUDITORIA PROG
Route::get('auditoriap', ['as'=>'auditoriap.index','uses'=>'App\Http\Controllers\AuditoriasProgController@index']);
Route::get('auditoriap/{id}', ['as'=>'auditoriap.show','uses'=>'App\Http\Controllers\AuditoriasProgController@show'])->middleware('VerificarToken');
Route::post('eliminar_auditoriap', ['as'=>'auditoriap.eliminarVarios','uses'=>'App\Http\Controllers\AuditoriasProgController@eliminarVarios']);

// BACKUP
Route::get('backup', ['as'=>'backup' , 'uses'=>'App\Http\Controllers\BackupController@Backup']);

// API USER
Route::get('applogin/{dni}',['as'=>'api.login','uses'=>'App\Http\Controllers\AppController@login']);

Route::get('app/user/{dni}', 'App\Http\Controllers\AppController@show');
Route::get('app/actividad/{dni}', 'App\Http\Controllers\AppController@actividad');
Route::get('app/actividad_all', 'App\Http\Controllers\AppController@act_all');


Route::get('au/enc/{dni}/{id}/{idplant}', ['as'=>'autori', 'uses'=>'App\Http\Controllers\AutorizacionController@index']);
Route::get('au/enc/email', ['as'=>'autorifin', 'uses'=>'App\Http\Controllers\AutorizacionController@create']);

// MOD.MAESTRIA - FORM ANTIGUO
Route::resource('maestria','App\Http\Controllers\MaestriasController');
Route::post('eliminar_maestria', ['as'=>'maestria.eliminarVarios','uses'=>'App\Http\Controllers\MaestriasController@eliminarVarios']);

// NUEVOS MODULOS: Maestria - Estudios e Investigaciones

Route::resource('grupo-maestria','App\Http\Controllers\grupoMaestriaController');
Route::get('grupo-maestria/form/create', ['as'=>'grupo-maestria_form.create','uses'=>'App\Http\Controllers\grupoMaestriaController@createForm']);
Route::post('grupo-maestria/form', ['as'=>'grupo-maestria_form.store','uses'=>'App\Http\Controllers\grupoMaestriaController@storeForm']);
Route::get('grupo-maestria/form/{id}/edit', ['as'=>'grupo-maestria_form.edit','uses'=>'App\Http\Controllers\grupoMaestriaController@editForm']);
Route::put('grupo-maestria/form/{id}', ['as'=>'grupo-maestria_form.update','uses'=>'App\Http\Controllers\grupoMaestriaController@updateForm']);

Route::resource('grupo-estudio-investigacion','App\Http\Controllers\grupoEstudiosInvestController');
Route::get('grupo-ei/form/create', ['as'=>'grupo-estudio-investigacion_form.create','uses'=>'App\Http\Controllers\grupoEstudiosInvestController@createForm']);
Route::post('grupo-ei/form', ['as'=>'grupo-estudio-investigacion_form.store','uses'=>'App\Http\Controllers\grupoEstudiosInvestController@storeForm']);
Route::get('grupo-ei/form/{id}/edit', ['as'=>'grupo-estudio-investigacion_form.edit','uses'=>'App\Http\Controllers\grupoEstudiosInvestController@editForm']);
Route::put('grupo-ei/form/{id}', ['as'=>'grupo-estudio-investigacion_form.update','uses'=>'App\Http\Controllers\grupoEstudiosInvestController@updateForm']);

// FORM ESPECIAL - PREGUNTAS
// EVENTOS ESPECIALES
Route::resource('eventos-es','App\Http\Controllers\grupoEspecialController');
Route::get('eventos-es/form/create', ['as'=>'eventos-es_form.create','uses'=>'App\Http\Controllers\grupoEspecialController@createForm']);
Route::post('eventos-es/form', ['as'=>'eventos-es_form.store','uses'=>'App\Http\Controllers\grupoEspecialController@storeForm']);
Route::get('eventos-es/form/{id}/edit', ['as'=>'eventos-es_form.edit','uses'=>'App\Http\Controllers\grupoEspecialController@editForm']);
Route::put('eventos-es/form/{id}', ['as'=>'eventos-es_form.update','uses'=>'App\Http\Controllers\grupoEspecialController@updateForm']);

// LINKS DE REGISTROS MAESTRIA
Route::get('maestria-inscripcion', ['as'=>'maestria_link.create','uses'=>'App\Http\Controllers\maestriaLinkRegistroController@create']);
Route::post('maestria-inscripcion', ['as'=>'maestria_link.store','uses'=>'App\Http\Controllers\maestriaLinkRegistroController@store']);
Route::get('maestria-inscripcion/{id}','App\Http\Controllers\maestriaLinkRegistroController@getDepartamentos');
Route::get('maestria-inscripcion/vdni/{id}/{evento}/','App\Http\Controllers\maestriaLinkRegistroController@getDNI');
Route::get('maestriainscripcion/gracias', ['as' => 'maestria_link.gracias', 'uses' => 'App\Http\Controllers\maestriaLinkRegistroController@gracias']);

// LINKS DE REGISTROS ESTUDIO DE INV.
Route::get('form-registro', ['as'=>'forme_link.create','uses'=>'App\Http\Controllers\formEstudiosInvestController@create']);
Route::post('form-registro', ['as'=>'forme_link.store','uses'=>'App\Http\Controllers\formEstudiosInvestController@store']);
Route::get('form-registro/show/{id}/{eventos_id}', ['as'=>'forme_link.show','uses'=>'App\Http\Controllers\formEstudiosInvestController@show']);
Route::get('form-registro/vdni/{id}/{evento}/','App\Http\Controllers\formEstudiosInvestController@getDNI');
Route::get('form-registro/gracias', ['as' => 'forme_link.gracias', 'uses' => 'App\Http\Controllers\formEstudiosInvestController@gracias']);

// LINKS DE REGISTROS FORM ESPECIAL - PREGUNTAS
Route::get('form-cgr', ['as'=>'form_link.create','uses'=>'App\Http\Controllers\formLinkRegistroController@create']);
Route::post('form-cgr', ['as'=>'form_link.store','uses'=>'App\Http\Controllers\formLinkRegistroController@store']);
Route::get('form-cgr/{id}','App\Http\Controllers\formLinkRegistroController@getDepartamentos');
Route::get('form-cgr/vdni/{id}/{evento}/','App\Http\Controllers\formLinkRegistroController@getDNI');
Route::get('form-cgr/gracias', ['as' => 'form_link.gracias', 'uses' => 'App\Http\Controllers\formLinkRegistroController@gracias']);

// MOD.CAMPAÑAS
Route::get('campanias/reporte/{campania}',['as'=>'campanias.reportes','uses'=>'App\Http\Controllers\CampaniasController@reporte']);
Route::post('campanias/reporte',['as'=>'campanias.person','uses'=>'App\Http\Controllers\CampaniasController@saveper']);
Route::post('campanias/verifica',['as'=>'campanias.dni','uses'=>'App\Http\Controllers\CampaniasController@verificadni']);
Route::post('campanias/actualiza',['as'=>'campanias.actualiza','uses'=>'App\Http\Controllers\CampaniasController@actualiza']);

Route::post('campanias/participantes',['as'=>'campanias.participantes','uses'=>'App\Http\Controllers\CampaniasController@participantes']);
Route::post('campanias/queue/{id}',['as'=>'campanias.queue','uses'=>'App\Http\Controllers\CampaniasController@runQueue'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
	
Route::match(['GET','POST','DELETE'],'campanias/noemails',['as'=>'campanias.noemails','uses'=>'App\Http\Controllers\CampaniasController@noemails']);
Route::get('emails_errores/{id}', ['as'=>'campanias.emails_errores','uses'=>'App\Http\Controllers\CampaniasController@emails_errores']);

Route::resource('/campanias','App\Http\Controllers\CampaniasController');
//Route::get('camp/reportes/{id}', ['as'=>'campanias.reportes','uses'=>'App\Http\Controllers\CampaniasController@reportes']);
Route::post('eliminar_campania', ['as'=>'campanias.eliminarVarios','uses'=>'App\Http\Controllers\CampaniasController@eliminarVarios']);
Route::get('envios', ['as'=>'campanias.envio','uses'=>'App\Http\Controllers\CampaniasController@envio_email']);


// UBIGEO
Route::get('ubigeo/fetch', 'App\Http\Controllers\ubigeo@fetch')->name('ubigeo.fetch'); // para FORM
Route::get('typemodal', 'App\Http\Controllers\ubigeo@modal')->name('type.modal'); // para FORM

// CORREOS ENC
Route::get('correosenc', ['as'=>'correos.index','uses'=>'App\Http\Controllers\CorreosController@index']);
Route::get('correosenc/create', ['as'=>'correos.create','uses'=>'App\Http\Controllers\CorreosController@create'])->middleware('VerificarToken');
Route::post('correosenc', ['as'=>'correos.store','uses'=>'App\Http\Controllers\CorreosController@store'])->middleware('VerificarToken');
Route::get('correosenc/{id}', ['as'=>'correos.show','uses'=>'App\Http\Controllers\CorreosController@show'])->middleware('VerificarToken');
Route::get('correosenc/{id}/edit/{idcorreo}', ['as'=>'correos.edit','uses'=>'App\Http\Controllers\CorreosController@edit'])->middleware('VerificarToken');
Route::put('correosenc/{id}/{idcorreo}', ['as'=>'correos.update','uses'=>'App\Http\Controllers\CorreosController@update'])->middleware('VerificarToken');
Route::delete('correosenc/{id}', ['as'=>'correos.destroy','uses'=>'App\Http\Controllers\CorreosController@destroy']);
Route::post('eliminar_correosenc', ['as'=>'correos.eliminarVarios','uses'=>'App\Http\Controllers\CorreosController@eliminarVarios']);
Route::get('correosenc/{id}/s/{dni}/{evento}/{tipo}', ['as'=>'correos.solicitud', 'uses'=>'App\Http\Controllers\CorreosController@solicitud']);
Route::post('correosenc/genera', ['as'=>'correos.genera','uses'=>'App\Http\Controllers\CorreosController@genera']);

// CORREOS ENC import export 
Route::get('correosenc_export', ['as' => 'correos.export', 'uses' => 'App\Http\Controllers\CorreosController@EstudianteExport']);
Route::post('correosenc_import', ['as' => 'correos.import', 'uses' => 'App\Http\Controllers\CorreosController@EstudianteImport']);
Route::post('correosenc_importsave', ['as' => 'correos.importsave', 'uses' => 'App\Http\Controllers\CorreosController@EstudianteImportSave']);
Route::get('correosenc_importresults', ['as' => 'correos.importresults', 'uses' => 'App\Http\Controllers\CorreosController@EstudianteImportResults']);
Route::post('correosenc_enviarI', ['as' => 'correos.enviarI', 'uses' => 'App\Http\Controllers\CorreosController@enviarInvitacionE']);

// MOD. LANDING PAGE
Route::resource('landingpage','App\Http\Controllers\LandingpageController');
Route::get('landingpage/pag/create', ['as'=>'landing_form.create','uses'=>'App\Http\Controllers\LandingpageController@createForm']);
Route::post('landingpage/pag', ['as'=>'landing_form.store','uses'=>'App\Http\Controllers\LandingpageController@storeForm']);

Route::get('landingpage/pag/{id}/edit', ['as'=>'landing_form.edit','uses'=>'App\Http\Controllers\LandingpageController@editForm']);
Route::put('landingpage/pag/{id}', ['as'=>'landing_form.update','uses'=>'App\Http\Controllers\LandingpageController@updateForm']);

Route::post('eliminar_land', ['as'=>'landingpage.eliminarVarios','uses'=>'App\Http\Controllers\LandingpageController@eliminarVarios']);

/* NUEVO MODULOS: 2021 - DDJJ */
Route::resource('grupo-dj','App\Http\Controllers\gDDJJController');
Route::get('grupo-dj/form/create', ['as'=>'grupo-dj_form.create','uses'=>'App\Http\Controllers\gDDJJController@createForm']);
Route::post('grupo-dj/form', ['as'=>'grupo-dj_form.store','uses'=>'App\Http\Controllers\gDDJJController@storeForm']);
Route::get('grupo-dj/form/{id}/edit', ['as'=>'grupo-dj_form.edit','uses'=>'App\Http\Controllers\gDDJJController@editForm']);
Route::put('grupo-dj/form/{id}', ['as'=>'grupo-dj_form.update','uses'=>'App\Http\Controllers\gDDJJController@updateForm']);
Route::get('dj/year', ['as'=>'ddjj_year','uses'=>'App\Http\Controllers\gDDJJController@ddjj_year']);

	// LINKS DE REGISTROS DDJJ
	Route::get('ddjj-inscripcion', ['as'=>'dj_link.create','uses'=>'App\Http\Controllers\ddjjLinkRegistroController@create']);
	Route::get('ddjj-inscripcion/{id}/{t?}/{d?}/{de?}', ['as'=>'dj_link2.create','uses'=>'App\Http\Controllers\ddjjLinkRegistroController@create']);
	Route::post('ddjj-inscripcion', ['as'=>'dj_link.store','uses'=>'App\Http\Controllers\ddjjLinkRegistroController@store']);
	//Route::get('ddjj-inscripcion/{id}','App\Http\Controllers\ddjjLinkRegistroController@getDepartamentos');
	Route::get('ddjj-inscripcion/gracias', ['as' => 'dj_link.gracias', 'uses' => 'App\Http\Controllers\ddjjLinkRegistroController@gracias']);
	Route::get('ddjj-migracion','App\Http\Controllers\ddjjLinkRegistroController@migracion');
	Route::get('ddjj-pdf/{id_evento}/{dni}/{detalle_id?}/', ['as'=>'dj_form.generate-pdf','uses'=>'App\Http\Controllers\ddjjLinkRegistroController@generaPDF']);
	Route::get('ddjj-comprimido-zip/{id?}', ['as'=>'ddjj-comprimido.zip','uses'=>'App\Http\Controllers\ddjjLinkRegistroController@comprimido_zip']);
	Route::get('getCurso/{cod_curso}/{evento_id}/', 'App\Http\Controllers\ddjjLinkRegistroController@getCurso');
	Route::get('getTipoInscripcion', 'App\Http\Controllers\ddjjLinkRegistroController@getTipoInscripcion')->name('tipo.inscripcion');

/* NUEVO MODULOS: 2021 - FORM DOCENTES */
Route::resource('grupo-doc','App\Http\Controllers\formDocentesController');
Route::get('grupo-doc/form/create', ['as'=>'grupo-doc_form.create','uses'=>'App\Http\Controllers\formDocentesController@createForm']);
Route::post('grupo-doc/form', ['as'=>'grupo-doc_form.store','uses'=>'App\Http\Controllers\formDocentesController@storeForm']);
Route::get('grupo-doc/form/{id}/edit', ['as'=>'grupo-doc_form.edit','uses'=>'App\Http\Controllers\formDocentesController@editForm']);
Route::put('grupo-doc/form/{id}', ['as'=>'grupo-doc_form.update','uses'=>'App\Http\Controllers\formDocentesController@updateForm']);

	// LINKS DE REGISTROS FICHA - Docentes
	Route::get('ficha-inscripcion', ['as'=>'ficha_link.create','uses'=>'App\Http\Controllers\fichaLinkRegController@create']);
	Route::post('ficha-inscripcion', ['as'=>'ficha_link.store','uses'=>'App\Http\Controllers\fichaLinkRegController@store']);
	Route::get('ficha-inscripcion/{id}','App\Http\Controllers\fichaLinkRegController@getDepartamentos');
	Route::get('ficha-inscripcion/gracias', ['as' => 'ficha_link.gracias', 'uses' => 'App\Http\Controllers\fichaLinkRegController@gracias']);

	// Visualizar participantes y excel
	Route::get('leads-ficha/{id}/edit', ['as'=>'leads.ficha','uses'=>'App\Http\Controllers\fichaViewController@ficha']);
	Route::get('leads-ficha/{id}/xls', ['as'=>'leads.fichaexcel','uses'=>'App\Http\Controllers\fichaViewController@exportaXLS']);
	Route::get('leads-ficha-migracion', ['as'=>'leads.migracion','uses'=>'App\Http\Controllers\fichaViewController@migracion']);

/* END 2021 */

Route::get('test/{id}/xls',['as'=>'test.xls','uses'=>'App\Http\Controllers\TestController@exportaXLS']);
Route::get('phpinfo',['as'=>'php.info','uses'=>'App\Http\Controllers\TestController@info']);
// clear cache
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});

/* NUEVO MODULOS: 2022 - DDJJ y Cartas de Compromiso CGR */
Route::resource('grupo-djcgr','App\Http\Controllers\gDDJJCGRController');
Route::get('grupo-djcgr/form/create', ['as'=>'grupo-djcgr_form.create','uses'=>'App\Http\Controllers\gDDJJCGRController@createForm']);
Route::post('grupo-djcgr/form', ['as'=>'grupo-djcgr_form.store','uses'=>'App\Http\Controllers\gDDJJCGRController@storeForm']);
Route::get('grupo-djcgr/form/{id}/edit', ['as'=>'grupo-djcgr_form.edit','uses'=>'App\Http\Controllers\gDDJJCGRController@editForm']);
Route::put('grupo-djcgr/form/{id}', ['as'=>'grupo-djcgr_form.update','uses'=>'App\Http\Controllers\gDDJJCGRController@updateForm']);
Route::get('dj/year', ['as'=>'ddjj_year','uses'=>'App\Http\Controllers\gDDJJCGRController@ddjj_year']);

	// LINKS DE REGISTROS DDJJ Cartas CGR
	#Route::get('djcgr-inscripcion', ['as'=>'djcgr_link.create','uses'=>'App\Http\Controllers\ddjjCartasLinkRegistroController@create']);
	Route::get('djcgr-inscripcion/{id}/{t?}/{d?}/{de?}', ['as'=>'djcgr_link.create','uses'=>'App\Http\Controllers\ddjjCartasLinkRegistroController@create']);
	Route::post('djcgr-inscripcion', ['as'=>'djcgr_link.store','uses'=>'App\Http\Controllers\ddjjCartasLinkRegistroController@store']);
	Route::get('djcgr-inscripcion/gracias', ['as' => 'djcgr_link.gracias', 'uses' => 'App\Http\Controllers\ddjjCartasLinkRegistroController@gracias']);
	Route::get('djcgr-migracion','App\Http\Controllers\ddjjCartasLinkRegistroController@migracion');
	Route::get('djcgr-pdf/{id_evento}/{dni}/{detalle_id?}/{tipo?}', ['as'=>'djcgr_form.generate-pdf','uses'=>'App\Http\Controllers\ddjjCartasLinkRegistroController@generaPDF']);	
	Route::get('getCurso/{cod_curso}/{evento_id}/', 'App\Http\Controllers\ddjjCartasLinkRegistroController@getCurso');
	Route::get('getCodigo/{id}/{evento}/','App\Http\Controllers\ddjjCartasLinkRegistroController@getCodigo');

// NUEVO MODULO FORM

Route::get('modulos', ['as' => 'modulos.index', 'uses' => 'App\Http\Controllers\ModuloController@index']);
Route::get('modulos/inputs/{id?}', ['as' => 'modulos.inputs', 'uses' => 'App\Http\Controllers\ModuloController@inputs']);

Route::post('modulos_eliminar', ['as' => 'modulos.eliminar', 'uses' => 'App\Http\Controllers\ModuloController@eliminarVarios']);
Route::resource('/modulos', 'App\Http\Controllers\ModuloController');
Route::post('modulos/plantilla', ['as' => 'modulos.plantilla.save', 'uses' => 'App\Http\Controllers\ModuloController@storeTemplate']);
Route::get('modulos/{modulo}/plantilla', ['as' => 'modulos.plantilla.form', 'uses' => 'App\Http\Controllers\ModuloController@plantilla']);
Route::delete('modulos/{id}', ['as'=>'modulo.destroy','uses'=>'App\Http\Controllers\ModuloController@destroy']);


#categoria
Route::delete('m/{m_category_id}/{m_product_id?}', ['as' => 'mcat.delete', 'uses' => 'App\Http\Controllers\ModuloCatController@destroy']);
Route::get('m/{m_category_id}/edit/{m_product_id?}', ['as' => 'mcat.edit', 'uses' => 'App\Http\Controllers\ModuloCatController@edit']);
Route::get('m/{m_category_id}/create', ['as' => 'mcat.create', 'uses' => 'App\Http\Controllers\ModuloCatController@create']);
Route::post('m/{m_category_id}/store', ['as' => 'mcat.store', 'uses' => 'App\Http\Controllers\ModuloCatController@store']);
Route::get('m/{m_category_id}', ['as' => 'mcat.index', 'uses' => 'App\Http\Controllers\ModuloCatController@index']);
Route::post('ml/{m_category_id}/{m_product_id}/store', ['as' => 'mlead.store', 'uses' => 'App\Http\Controllers\ModuloLeadController@store']);

#pagina leads
Route::get('ml/{m_category_id}/{m_product_id}/eregistrados', ['as' => 'mlead.reportes.e_registrados', 'uses' => 'App\Http\Controllers\ModuloLeadController@eRegistrados']);

Route::get('ml/{m_category_id}/{m_product_id}/create', ['as' => 'mlead.create', 'uses' => 'App\Http\Controllers\ModuloLeadController@create']);
Route::get('ml/{m_category_id}/{m_product_id}/{id}/edit', ['as' => 'mlead.edit', 'uses' => 'App\Http\Controllers\ModuloLeadController@edit']);
Route::get('ml/{m_category_id}/{m_product_id}/{id}/', ['as' => 'mlead.show', 'uses' => 'App\Http\Controllers\ModuloLeadController@edit']);
Route::get('ml/{m_category_id}/{m_product_id}', ['as' => 'mlead.index', 'uses' => 'App\Http\Controllers\ModuloLeadController@index']);
Route::post('ml/{m_category_id}/{m_product_id}/eliminar_leads', ['as'=>'mlead.eliminarVarios','uses'=>'App\Http\Controllers\ModuloLeadController@eliminarVarios']);
#paginas de plantilla
#paginas de plantilla --update --ml
Route::get('evconfirm/{m_category_id}/{m_product_id}/confirm', ['as' => 'mlead.confirm', 'uses' => 'App\Http\Controllers\ModuloLeadController@confirm']);
Route::get('ml/{m_category_id}/{m_product_id}/cerrado', ['as' => 'mlead.cerrado', 'uses' => 'App\Http\Controllers\ModuloLeadController@cerrado']);
Route::get('ml/{m_category_id}/{m_product_id}/registrados', ['as' => 'mlead.registrados', 'uses' => 'App\Http\Controllers\ModuloLeadController@registrados']);
Route::post('document/{m_product_id}/get/', 'App\Http\Controllers\ModuloLeadController@postDNI')->name('modulos.postdni');
#pagina leads

# data JSON
Route::get('m/getDNI/{id}/{evento}/', 'App\Http\Controllers\ModuloLeadController@getDNI')->name('modulos.getdni');
Route::get('m/template/{id}', 'App\Http\Controllers\ModuloLeadController@getPlantillaEmail')->name('modulos.template');

// FIN RUTAS MODULO FORM
// export new Excel
Route::get('importExportView', [MyController::class, 'importExportView']);
Route::get('export_excel', [MyController::class, 'exportView'])->name('export');
Route::post('import_excel', [MyController::class, 'import'])->name('import');

// Form AGREGAR CURSOS A DJ
Route::match(['GET','POST','DELETE'],'agregar_cursos',['as'=>'agregar.cursos','uses'=>'App\Http\Controllers\CursosController@agregar_cursos']);
#Importar Cursos DJ
Route::get('importar_cursos_a', ['as'=>'importar_cursos','uses'=>'App\Http\Controllers\CursosController@importar_cursos']);
Route::post('importar_cursos', ['as'=>'importar_cursos_store','uses'=>'App\Http\Controllers\CursosController@importar_cursos_store']);
Route::post('cursos_import', ['as' => 'cursos.import', 'uses' => 'App\Http\Controllers\CursosController@CursoImport']);
Route::post('cursos_importsave', ['as' => 'cursos.importsave', 'uses' => 'App\Http\Controllers\CursosController@CursoImportSave']);
Route::get('cursos_importresults', ['as' => 'cursos.importresults', 'uses' => 'App\Http\Controllers\CursosController@CursoImportResults']);

// Form AGREGAR PERSONAL CGR
Route::match(['GET','POST','DELETE'],'agregar_colaboradores',['as'=>'agregar.colaboradores','uses'=>'App\Http\Controllers\ColaboradoresController@agregar_colaboradores']); 
#Importar Personal CGR
Route::get('importar_colaboradores_a', ['as'=>'importar_colaboradores','uses'=>'App\Http\Controllers\ColaboradoresController@importar_colaboradores']);
Route::post('importar_colaboradores', ['as'=>'importar_colaboradores_store','uses'=>'App\Http\Controllers\ColaboradoresController@importar_colaboradores_store']);
Route::post('colaboradores_import', ['as' => 'colaboradores.import', 'uses' => 'App\Http\Controllers\ColaboradoresController@ColaboradoresImport']);
Route::post('colaboradores_importsave', ['as' => 'colaboradores.importsave', 'uses' => 'App\Http\Controllers\ColaboradoresController@ColaboradoresImportSave']);
Route::get('colaboradores_importresults', ['as' => 'colaboradores.importresults', 'uses' => 'App\Http\Controllers\ColaboradoresController@ColaboradoresImportResults']); 


###Route::post('import_cursos', [MyController::class, 'import_cursos'])->name('import_cursos');
###Route::match(['GET','POST','DELETE'],'campanias/noemails',['as'=>'campanias.noemails','uses'=>'App\Http\Controllers\CampaniasController@noemails']);

// Listado de colaboradores CGR - cartasdecompromiso
#sale error 
Route::get('colaboradores', ['as' => 'cgr.listado', 'uses' => 'App\Http\Controllers\ColaboradoresController@index']);

#para actualizar:
Route::get('reportes/update_caii',['as'=>'reportes.update_caii','uses'=>'App\Http\Controllers\CaiiReporteController@update_caii']);

#Mod. importar listado sin DNI
Route::get('igenerate/{id}', ['as' => 'invimport.generate', 'uses' => 'App\Http\Controllers\InvImportacionesController@generate']);

Route::resource('/invimport','App\Http\Controllers\InvImportacionesController');
#Route::resource('/invimport',['as' => 'importarlista.index', 'uses' => 'App\Http\Controllers\InvImportacionesController']);
Route::post('/invimport_inv',['as'=>'invimport.imp','uses'=>'App\Http\Controllers\InvImportacionesController@import']);
Route::post('eliminar_invimport', ['as'=>'invimport.eliminarVarios','uses'=>'App\Http\Controllers\InvImportacionesController@eliminarVarios']);
Route::get('invimport/results', ['as' => 'invimport.importresults', 'uses' => 'App\Http\Controllers\InvImportacionesController@EstudianteImportResults']);
Route::post('invimport_save', ['as' => 'invimport.importsave', 'uses' => 'App\Http\Controllers\InvImportacionesController@EstudianteImportSave']);