<?php

namespace App\Http\Controllers;

use App\Album;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zip;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DropZone extends Controller
{
    function index()
    {
        $user = \Route::current()->parameters()['user'];
        $album = \Route::current()->parameters()['album'];
        session(['album' => $album]);

        $resultado = Album::where('identificador', $user . '-' . $album)->first();
        if ($resultado != null) {
            $resultado->url = "Editando";
            $resultado->save();
        }
        return view('panel.album.subir');
    }
    public function zip($directorio)
    {
        $aux = uniqid() . '.zip';
        $nombre = "../laravel/storage/app/images/" . $aux;
        $zip = Zip::create($nombre);
        $images = File::allFiles($directorio . "/nuevas");
        foreach ($images as $image) {

            $zip->add($image->getPath());
            break;
        }
        // Storage::putFileAs('images',$zip,$nombre);
        $zip->close();
        return $aux;
    }

    public function redimensionar($id)
    {

        $aux = str_replace('-', '/', $id);
        $directorio = "images/" . $aux;
        $images = File::allFiles($directorio);
        natsort($images);

        $imagesaux = Album::where('identificador', $id)->first();
        $imagesorden = explode(',', $imagesaux->orden);
        $auxiliar = '';
        $indice = 1;
        foreach ($imagesorden as $image) {

            $src = imagecreatefromstring(file_get_contents(asset($directorio . '/' . $image)));
            if (imagesx($src) > imagesy($src)) { // horizontal o vertical
                $w = 1280;
                $h = 720;
            } else {
                $w = 720;
                $h = 1280;
            }
            $multiplicador = $h / imagesy($src);
            if (imagesx($src) * $multiplicador <= $w && $multiplicador > 1) {
                $newpic = imagecreatetruecolor($w * $multiplicador, $h * $multiplicador);
                imagecopyresized($newpic, $src, 0, 0, 0, 0, $w * $multiplicador, $h * $multiplicador, imagesx($src), imagesy($src));
            } else {
                $newpic = $src;
            }


            if (explode('.', $image)[0] == 'portada') {
                $rand = $directorio . "/nuevas/" . $image;
                imagejpeg($newpic, $rand);
            } else {
                $rand = $directorio . "/nuevas/" . $indice . '.' . explode('.', $image)[1];
                $indice = $indice + 1;
                imagejpeg($newpic, $rand);
            }
        }

        $zip = $this->zip($directorio);
        $album = Album::where('identificador', $id)->first();
        $album->url = 'Descargado';
        $album->save();
        return response()->download(storage_path() . "/app/images/" . $zip);
    }

    function upload(Request $request)
    {
        $image = $request->file('file');
        $aux = session('album');
        $directorio = "images/" . Auth::user()->id . "/" . $aux;
        $imageName = "";
        if (!Storage::exists($directorio)) { //Crear directorio para el album
            $image->storeAs($directorio, "1." . $image->extension());
            session(['nombre' => '2']);
        } else {

            // $contador = count(Storage::allFiles($directorio));
            $nombre = session('nombre');

            $imageName = $nombre . '.' . $image->extension();
            $image->storeAs($directorio, $imageName);
            session(['nombre' => $nombre + 1]);
        }

        if (!Storage::exists($directorio . "/nuevas")) {
            Storage::makeDirectory($directorio . "/nuevas");
        }

        return response()->json(['success' => $imageName]);
    }


    function portada(Request $request)
    {
        $image = $request->file('file');
        $aux = Auth::user()->albums->count() + 1;
        $directorio = "images/" . Auth::user()->id . "/" . $aux;
        $imageName =   "portada" . '.' . $image->extension();
        $image->storeAs($directorio, $imageName);
        session(['nombre' => '1']);

        return response()->json(['success' => $imageName]);
    }

    function fetch(Request $request)
    {
        $directorio = "images/" . $request->get('user') . "/" . $request->get('album');
        $images = File::allFiles($directorio);
        natsort($images);
        $output = '';
        foreach ($images as $image) {
            $output .= '
      <div class="col-8 offset-2 offset-md-0 
      col-md-2 imagenes" id="' . $image->getFilename() . '" style="margin-bottom:16px;" align="center">
                <img src="' . asset($directorio . '/' . $image->getFilename()) . '" class="img-thumbnail" width="175" height="175" style="height:175px;" />
                <button type="button" class="btn btn-link remove_image" id="' . $image->getFilename() . '">Borrar imagen</button>
                </div>
      ';
        }
        $output .= '</div>';
        echo $output;
    }

    function fetchOrden(Request $request)
    {
        $directorio = "images/" . $request->get('user') . "/" . $request->get('album');
        $imagesaux = Album::where('identificador', $request->user . '-' . $request->album)->first();
        $images = explode(',', $imagesaux->orden);
        $output = '';
        foreach ($images as $image) {

            $output .= '
      <div class="col-8 offset-2 offset-md-0 
      col-md-2 imagenes" id="' . $image . '" style="margin-bottom:16px;" align="center">
                <img src="' . asset($directorio . '/' . $image) . '" class="img-thumbnail" width="175" height="175" style="height:175px;" />
                <button type="button" class="btn btn-link remove_image" id="' . $image . '">Borrar imagen</button>
                </div>
      ';
        }
        $output .= '</div>';
        echo $output;
    }

    public function orden(Request $request)
    {
        $output = $request->user;
        echo $output;
    }
    function delete(Request $request)
    {
        if ($request->get('name')) {
            $directorio = "app/images/" . $request->get('user') . "/" . $request->get('album');
            File::delete(storage_path($directorio . "/" . $request->get('name')));
        }
    }
}
