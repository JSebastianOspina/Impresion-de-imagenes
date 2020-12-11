@extends('panel.index') 
@section('titulocontenido') 
Creación de nuevo album 
@endsection 
@section('subtitulo')
@endsection 
@section('contenido') <h3 class="font-light mt-3">1. Sube una foto de portada,
    esta se guardará automáticamente</h3>
<div class="panel panel-default">
    <div class="panel-body">
        <form id="portada" class="dropzone" action="{{ route('dropzone.portada') }}">@csrf </form>
    </div>
</div>
<h3 class="mt-3 font-light">2. Sube las fotos de tu album. Cantidad recomendada: 60 fotos. Cuando hayas terminado,
    da clic a <strong>Guardar album</strong></h3>
<div class="panel panel-default">
    <div class="panel-body">
        <form id="dropzoneForm" class="dropzone" action="{{ route('dropzone.upload') }}">@csrf </form>
        <div align="center"><button type="button" class="btn mt-3" style="background-color: #ffdb2c"
                id="guardar">Guardar album</button></div>
    </div>
</div><br />
<div style="text-align:justify">
    <h3>Las imágenes están organizadas de arriba a abajo. Esto quiere decir que la primer imagen será la primera en
        parecer en el álbum, las demás irán en orden después de esta. </h3>
    <br>
    <h3>
        La ultima imagen corresponde a la foto de portada.
    </h3>
</div>
<br>
<br>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Imágenes subidas</h3>
    </div>
    <div class="panel-body" id="uploaded_image">
        <div class="row" id="reordenar"></div>
    </div>
</div>@endsection @section('scripts') <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js">
</script>
<script>
    Dropzone.options.dropzoneForm = {
        parallelUploads: 1,
        maxFiles: 60,
        autoProcessQueue: true,
        acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg",

        init: function () {

            myDropzone = this;

            this.on("complete", function () {
                    if (this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {

                        var _this = this;
                        _this.removeAllFiles();
                        load_images();

                    } else {
                        myDropzone.processQueue();

                    }
                }

            );
            load_images_orden();


        }
    }

    ;

    function load_images() {
        $.ajax({

                url: "{{ route('dropzone.fetch') }}",
                data: {
                    user: "{{(Route::current()->parameters()['user'])}}",
                    album: "{{(Route::current()->parameters()['album'])}}"

                }

                ,
                success: function (data) {
                    $('#reordenar').html(data);
                }
            }

        )
    }

    function load_images_orden() {
        $.ajax({

                url: "{{ route('dropzone.fetchOrden') }}",
                data: {
                    user: "{{(Route::current()->parameters()['user'])}}",
                    album: "{{(Route::current()->parameters()['album'])}}"

                }

                ,
                success: function (data) {
                    $('#reordenar').html(data);
                }
            }

        )
    }

    $(document).on('click', '.remove_image', function () {
            var name = $(this).attr('id');

            $.ajax({

                    url: "{{ route('dropzone.delete') }}",
                    data: {
                        name: name,
                        user: "{{(Route::current()->parameters()['user'])}}",
                        album: "{{(Route::current()->parameters()['album'])}}"

                    }

                    ,
                    success: function (data) {
                        console.log(data);
                        load_images();
                    }
                }

            )
        }

    );

    $(document).on('click', '#guardar', function () {
            let test = document.getElementsByClassName('imagenes');
            let orden = '';
            for (let i = 0; i < test.length; i++) {
                if (i == test.length - 1) {
                    orden = orden + test[i].id;

                } else {
                    orden = orden + test[i].id + ',';
                }
            }
            $.ajax({

                    url: "{{ route('dropzone.guardar') }}",
                    data: {
                        user: "{{(Route::current()->parameters()['user'])}}",
                        album: "{{(Route::current()->parameters()['album'])}}",
                        orden: orden,

                    },

                    success: function (data) {
                        window.location.href = data;
                    }
                }

            )
        }

    );

</script>
<script>
    var sortable = new Sortable(reordenar, {
        animation: 150,
        onEnd: function ( /**Event*/ evt) {
            var itemEl = evt.item; // dragged HTMLElement
            let test = document.getElementsByClassName('imagenes');
            let orden = '';
            for (let i = 0; i < test.length; i++) {
                if (i == test.length - 1) {
                    orden = orden + test[i].id;

                } else {
                    orden = orden + test[i].id + ',';
                }
            }
            console.log(orden);
        },
    });

</script>@endsection
