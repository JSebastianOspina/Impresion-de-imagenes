<?php

namespace App\Http\Controllers;

use App\Album;
use Illuminate\Http\Request;
use App\Subida;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Auth;
use Zip;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DropZone extends Controller
{
    function index()
    {


        return view('panel.album.subir');
    }
    public function zip($directorio)
    {
        $aux = uniqid() . '.zip';
        $nombre = "../laravel/storage/app/images/".$aux;
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

            $rand = $directorio . "/nuevas/" . explode('.', $image->getFilename())[0];

            $hola = imagejpeg($newpic, $rand . '.jpg');
        }
        $zip = $this->zip($directorio);
        
        return response()->download(storage_path()."/app/images/".$zip);
    }


    function upload(Request $request)
    {
        $image = $request->file('file');
        $aux = (Auth::user()->albums->count() + 1);
        $directorio = "images/" . Auth::user()->id . "/" . $aux;
        $imageName = "";
        if (!Storage::exists($directorio)) { //Crear directorio para el album
            $image->storeAs($directorio,"1." . $image->extension());
//            $image->move(public_path($directorio), "1." . $image->extension());
           
        } else {

            $contador = count(Storage::allFiles($directorio));

            $imageName =   $contador + 1 . '.' . $image->extension();
            $image->storeAs($directorio,$imageName);
            //$image->move(public_path($directorio), $imageName);
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


        return response()->json(['success' => $imageName]);
    }



    function fetch()
    {
        $aux = Auth::user()->albums->count() + 1;
        $directorio = "images/" . Auth::user()->id . "/" . $aux;

        $images = File::allFiles($directorio);
        $output = '<div class="row">';
        foreach ($images as $image) {
            $output .= '
      <div class="col-md-2" style="margin-bottom:16px;" align="center">
                <img src="' . asset($directorio . '/' . $image->getFilename()) . '" class="img-thumbnail" width="175" height="175" style="height:175px;" />
            </div>
      ';
        }
        //<button type="button" class="btn btn-link remove_image" id="' . $image->getFilename() . '">Remove</button>

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
