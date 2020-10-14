<?php

namespace App\Http\Controllers;

use App\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $albumes = Auth::user()->hasRole('admin') ? Album::all() : Auth::user()->albums;

        return view('panel.album.clientes', compact('albumes'));
    }

    public function verAlbum($id)
    {
        $aux = str_replace('-', '/', $id);
        $directorio = "images/" . $aux;
        $album = Album::where('identificador', $id)->first();
        $images = \File::Files($directorio);
        return view('panel.album.album', compact('images', 'directorio', 'album'));
    }

    function guardarAlbum(Request $request)
    {

        $aux = Auth::user()->albums->count() + 1;
        $directorio = "images/" . Auth::user()->id . "/" . $aux;
        if (Storage::exists($directorio)) {
            $album = new Album();
            $album->portada = $directorio . "/portada.jpeg";
            $album->cantidad =  count(\File::allFiles($directorio));
            $album->user_id = Auth::user()->id;
            $album->identificador = Auth::user()->id . "-" . $aux;

            $album->save();
            return response(route('album.ver', ['id' => Auth::user()->id . "-" . $aux]), 200);
        } else {
            return response('', 403);
        }
    }
}
