<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Image Upload in Laravel using Dropzone</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
</head>

<body>
    <div class="container-fluid">
        <br />
        <h3 align="center">Crear un nuevo album</h3>
        <br />
      
        <h4>1. Sube una foto de portada, luego, da clic en subir foto, para guardar los cambios</h4>
       
       

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Foto de portada</h3>
            </div>
            <div class="panel-body">
                <form id="portada" class="dropzone" action="{{ route('dropzone.portada') }}">
                    @csrf
                </form>
               
            </div>
        </div>



        <h4>2. Sube las fotos de tu album. Cantidad recomendada: 60 fotos </h4>


        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Fotos del album</h3>
            </div>
            <div class="panel-body">
             
                <form id="dropzoneForm" class="dropzone" action="{{ route('dropzone.upload') }}">
                    @csrf
                </form>
                <div align="center">
                    <button type="button" class="btn btn-info" id="guardar">Guardar album</button>
                </div>
            </div>
        </div>
        <br />
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Imágenes subidas</h3>
            </div>
            <div class="panel-body" id="uploaded_image">

            </div>
        </div>
    </div>
</body>

</html>

<script type="text/javascript">
    
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
                } else{
                    myDropzone.processQueue();

                }

                
            });
            load_images();

        }

    };



    function load_images() {
        $.ajax({
            url: "{{ route('dropzone.fetch') }}",
            success: function (data) {
                $('#uploaded_image').html(data);
            }
        })
    }

    $(document).on('click', '.remove_image', function () {
        var name = $(this).attr('id');
        $.ajax({
            url: "{{ route('dropzone.delete') }}",
            data: {
                name: name
            },
            success: function (data) {
                load_images();
            }
        })
    });

    $(document).on('click', '#guardar', function () {
        $.ajax({
            url: "{{ route('dropzone.guardar') }}",
            
            success: function (data) {
                
               window.location.replace(data);

            }
        })
    });


</script>
