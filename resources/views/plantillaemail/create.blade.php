@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        <div class="content-wrapper p-0 mt-3">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Crear Plantilla HTML</h4>
                  <p class="card-description">
                  Formulario para creación de Plantillas HTML                   
                  </p>
                  

                  @if(session()->has('info'))
                    <div class="alert alert-success" role="alert">
                      {{ session('info') }}
                    </div>
                    
                    <a href="{{ route('plantillaemail.index') }}" class="btn btn-success">Volver al listado</a>

                  @else
                  <form class="forms-sample" id="estudiantesForm" action="{{ route('plantillaemail.store') }}" method="post">
                    {!! csrf_field() !!}
                    @include ('plantillaemail.form')

                  </form>
                  @endif
                </div>
              </div>
            </div>
          </div>
          
          
        </div>
        

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

@endsection
@section('scripts')

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<script>
  $("#flujo_ejecucion").on('change', function(){
    var tipo = $("#flujo_ejecucion").val();
    console.log('click flujo_ejecucion :');

    if(tipo == "LEY-27419"){
      $(".auto_conf_div").removeClass('d-none');
    }else{
      $(".auto_conf_div").addClass('d-none');
    }
  });
</script> 

<script>

//$('#summernote').summernote();
$('#summernote').summernote({
    placeholder: 'BLOQUE PARA CANCELAR SUSCRIPCIÓN',
    tabsize: 2,
    height: 180,
    toolbar: [
      /*['style', ['style']],
      ['font', ['bold', 'underline', 'clear']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['table', ['table']],
      ['insert', ['link', 'picture', 'video']],*/
      ['view', ['fullscreen', 'codeview']]//, 'help'
    ]
});

$('#plantillahtml').summernote({
    placeholder: 'HTML...',
    tabsize: 2,
    height: 400,
    toolbar: [
      ['view', ['fullscreen', 'codeview']]//, 'help'
    ]
});
</script>
@endsection

@push('js')
    {{-- <script src="{{asset('js_a/tinymce/tinymce.min.js')}}"></script>
    <script>
        tinymce.init({
            selector:'#summernote2',
            height : "500px",
            language: 'es',
            plugins: [
                'print preview fullpage paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons spellchecker mediaembed pageembed linkchecker powerpaste formatpainter casechange'],
            menubar: 'file edit view insert format tools table help',
            toolbar: 'casechange undo redo  bold italic underline strikethrough  fontselect fontsizeselect formatselect alignleft aligncenter alignright alignjustify outdent indent numlist bullist  forecolor backcolor removeformat pagebreak charmap emoticons fullscreen preview save print insertfile image media template link anchor codesample fullpage ltr rtl styleselect pageembed formatpainter'
        });
    </script> --}}
@endpush