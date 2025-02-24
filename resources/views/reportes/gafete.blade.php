<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Demo tabla</title>
</head>
<body>


{{-- <table class="table">
  <thead class="thead-inverse">
    <tr>
      <th>#</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Username</th>
    </tr>
  </thead>
  <tbody>

    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>Larry</td>
      <td>the Bird</td>
      <td>@twitter</td>
    </tr>
  </tbody>
</table> --}}


<table id="order-listing" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                          <thead>
                            <tr role="row">
                              <th class="sorting" style="width: 20%;">Ap_Paterno</th>
                              <th class="sorting" style="width: 20%;">Ap_Materno</th>
                              <th class="sorting" style="width: 20%;">Nombres</th>
                              <th class="sorting" style="width: 5%;">DNI</th>
                              <th class="sorting" style="width: 10%;">Fecha</th>
                              <th class="sorting" style="width: 10%;">Hora</th>
                              <th class="sorting" style="width: 10%;">Usuario</th>
                            </tr>
                          </thead>
                          <tbody>
{{-- foreach($asistencia as $index => $data) {
                    $sheet->row($index+2, [
                        $data->ap_paterno, $data->ap_materno, $data->nombres,$data->dni_doc, $data->fecha, $data->hora
                    ]); 
                }*/ --}}


                            @foreach ($asistencia as $datos)
                            <tr role="row" class="odd">
                                <td>{{ $datos->ap_paterno or '' }}</td>
                                <td>{{ $datos->ap_materno or '' }}</td>
                                <td>{{ $datos->nombres or 'nombre' }}</td>
                                <td>{{ $datos->dni_doc }}</td>
                                <td>{{ $datos->fecha }}</td>
                                <td>{{ $datos->hora }}</td>
                                
                            </tr>
                            @endforeach
                          </tbody>
                        </table>



</body>
</html>