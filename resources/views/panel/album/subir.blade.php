@extends('panel.index')
@section('titulocontenido')
Creación de nuevo album
@endsection
@section('subtitulo')

@endsection
@section('contenido')
 
  
    <h3 class="font-light mt-3" >1. Sube una foto de portada, esta se guardará automáticamente</h3>
   
   

    <div class="panel panel-default">
       
        <div class="panel-body">
            <form id="portada" class="dropzone" action="{{ route('dropzone.portada') }}">
                @csrf
            </form>
           
        </div>
    </div>



    <h3 class="mt-3 font-light">2. Sube las fotos de tu album. Cantidad recomendada: 60 fotos</h3>


    
    <div class="panel panel-default">
        
        <div class="panel-body">
         
            <form id="dropzoneForm" class="dropzone" action="{{ route('dropzone.upload') }}">
                @csrf
            </form>
            <div align="center">
                <button type="button" class="btn btn-info mt-3" id="guardar">Guardar album</button>
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



   

@endsection