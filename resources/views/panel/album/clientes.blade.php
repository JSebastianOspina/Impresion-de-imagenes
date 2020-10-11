@extends('panel.index')
@section('titulocontenido')
Clientes - Albumes
@endsection
@section('subtitulo')
En esta seccion se muestran todos los albumes de los clientes
@endsection
@section('contenido')
<div class=" d-flex justify-content-end">
    <button class="btn btn-success text-white mb-2 " data-toggle="modal" data-target="#responsive-modal">
        <i class="fa fa-plus-square"></i>
        Crear nuevo negocio
    </button>
</div>
@if(session('mensaje') != null)
    <div class="alert alert-success">{{ session('mensaje') }}</div>
@endif

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Cliente</th>
                <th scope="col">Id album</th>
                <th scope="col">Foto de portada</th>
                <th scope="col">Ver album</th>
                <th scope="col">Descargar</th>

            </tr>
        </thead>
        <tbody>
            @foreach($albumes as $album)
                <tr>

                    <td>{{ $album->user->name }}</td>
                    <td>{{ $album->identificador }} </td>
                    <td>
                        <a href="{{ '/storage/'.$album->imagen }}"
                            target="_blank">Clic para ver la imagen</a>
                    </td>
                    <td>
                        <a class="btn btn-success"
                            href="{{ route('album.ver',['id'=>$album->identificador]) }}">Ver Album</a>
                       
                    </td>
                   
                    <td>
                        <a class="btn btn-success"
                            href="{{ route('album.descargar',['id'=>$album->id]) }}"> Descargar album</a>
                       
                    </td>


                </tr>
            @endforeach


        </tbody>
    </table>
</div>
@endsection
