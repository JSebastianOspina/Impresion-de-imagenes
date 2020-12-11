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
    public function index(Request $request)
    {

        $albumes = Auth::user()->hasRole('admin') ? Album::where('url', 'Listo para descargar')->get() : Auth::user()->albums;
        if ($request->todos == "true") {
            $albumes = Auth::user()->hasRole('admin') ? Album::all() : Auth::user()->albums;
        }
        return view('panel.album.clientes', compact('albumes'));
    }
    public function faq()
    {
        $todos = Album::where('url', 'no')->get();
        foreach ($todos as $album) {
            $album->url = "Listo para descargar";
            $album->save();
        }
        return view('panel.facs');
    }

    public function verAlbum($id)
    {
        $aux = str_replace('-', '/', $id);
        $directorio = "images/" . $aux;
        $album = Album::where('identificador', $id)->first();
        $images = explode(',', $album->orden);
        //$images = \File::Files($directorio);
        //natsort($images);
        return view('panel.album.album', compact('images', 'directorio', 'album'));
    }

    function guardarAlbum(Request $request)
    {
        $aux = $request->get('album');
        $directorio = "images/" .   $request->get('user') . "/" . $aux;

        if (Storage::exists($directorio)) {
            $album = Album::where('identificador', $request->get('user') . '-' . $aux)->first();
            if ($album != null) {
                $album->url = "Listo para descargar";
                $album->orden = $request->orden;
                $album->cantidad =  count(\File::allFiles($directorio));

                $album->save();
                return response(route('album.ver', ['id' => Auth::user()->id . "-" . $aux]), 200);
            }
            $album = new Album();
            $album->portada = $directorio . "/portada.jpeg";
            $album->cantidad =  count(\File::allFiles($directorio));
            $album->user_id = Auth::user()->id;
            $album->identificador = Auth::user()->id . "-" . $aux;
            $album->url = "Listo para descargar";
            $album->orden = $request->orden;
            $album->save();
            return response(route('album.ver', ['id' => Auth::user()->id . "-" . $aux]), 200);
        } else {
            return response('', 403);
        }
    }
}
