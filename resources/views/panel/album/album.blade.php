@extends('panel.index')
@section('titulocontenido')

Viendo Album con de {{$album->user->name}} ({{$album->id}})
@endsection
@section('subtitulo')
En esta seccion se muestran las imagenes del Album
@endsection
@section('contenido')
<div class="row text-center text-lg-left">
    @foreach ($images as $image)
    <div class="col-lg-3 col-md-4 col-6">
       
        <img class="img-fluid img-thumbnail" src="{{asset($directorio . '/' . $image->getFilename()) }}" alt="">
        
      </div>
  
    @endforeach
   

@endsection