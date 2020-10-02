<?php

namespace App\Http\Controllers;

use App\Album;
use Illuminate\Http\Request;
use App\Subida;
use Illuminate\Support\Facades\Auth;
use Zip;
use Illuminate\Support\Facades\File;

class DropZone extends Controller
{
    function index()
    {


        return view('panel.album.subir');
    }
    public function zip($directorio)
    {

        $zip = Zip::create(uniqid() . '.zip');
        $images = \File::allFiles(public_path($directorio."/nuevas"));

        foreach ($images as $image) {





            $zip->add($image->getPath());
            break;
        }



        var_dump($zip);

        $zip->close();
    }

    public function redimensionar()
    {


        $images = \File::allFiles(public_path('images'));
        $contador = 0;
        foreach ($images as $image) {

            $src = imagecreatefromstring(file_get_contents($image->getRealPath()));
            if (imagesx($src) > imagesy($src)) { // horizontal o vertical
                $w = 1711;
                $h = 1140;
            } else {
                $w = 1140;
                $h = 1711;
            }

            $newpic = imagecreatetruecolor($w, $h);
            imagecopyresized($newpic, $src, 0, 0, 0, 0, $w, $h, imagesx($src), imagesy($src));

            $rand = 'images/nuevas/' . explode('.', $image->getFilename())[0];

            $hola = imagejpeg($newpic, $rand . '.jpg');
        }
    }


    function upload(Request $request)
    {
        $image = $request->file('file');
        $aux = Auth::user()->albums->count() + 1;
        $directorio = "images/" . Auth::user()->id . "/" . $aux;
        $imageName = "";
        if (!File::exists($directorio)) { //Crear directorio para el album
            $image->move(public_path($directorio), "1." . $image->extension());
            $imageName = "1.".$image->extension();
        } else {



            $contador = count(File::allFiles(public_path($directorio)));

            $imageName =   $contador + 1 . '.' . $image->extension();

            $image->move(public_path($directorio), $imageName);
        }

        if (!File::exists($directorio . "/nuevas")) {
            File::makeDirectory($directorio . "/nuevas");
        }

        return response()->json(['success' => $imageName]);
    }


    function portada(Request $request)
    {
        $image = $request->file('file');
        $aux = Auth::user()->albums->count() + 1;
        $directorio = "images/" . Auth::user()->id . "/" . $aux;




        $imageName =   "portada" . '.' . $image->extension();

        $image->move(public_path($directorio), $imageName);


        return response()->json(['success' => $imageName]);
    }



    function fetch()
    {
        $aux = Auth::user()->albums->count() + 1;
        $directorio = "images/" . Auth::user()->id . "/" . $aux;

        $images = \File::allFiles(public_path($directorio));
        $output = '<div class="row">';
        foreach ($images as $image) {
            $output .= '
      <div class="col-md-2" style="margin-bottom:16px;" align="center">
                <img src="' . asset($directorio . '/' . $image->getFilename()) . '" class="img-thumbnail" width="175" height="175" style="height:175px;" />
                <button type="button" class="btn btn-link remove_image" id="' . $image->getFilename() . '">Remove</button>
            </div>
      ';
        }
        $output .= '</div>';
        echo $output;
    }

    function delete(Request $request)
    {
        if ($request->get('name')) {
            $aux = Auth::user()->albums->count() + 1;
            $directorio = "images/" . Auth::user()->id . "/" . $aux;
            \File::delete(public_path($directorio . "/" . $request->get('name')));
        }
    }
}
