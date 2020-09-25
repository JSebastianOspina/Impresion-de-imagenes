<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subida;
use Zip;
use Illuminate\Support\Facades\File;

class DropZone extends Controller
{
    function index()
    {


        return view('dropzone');
    }
    public function zip()
    {

        $zip = Zip::create('file.zip');
        $images = \File::allFiles(public_path('images/nuevas'));
        
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
        $contador =0;
        foreach ($images as $image) {
            $w = 1711;
            $h = 1140;
            $src = imagecreatefromstring(file_get_contents($image->getRealPath()));
            $newpic = imagecreatetruecolor($w, $h);
            imagecopyresized($newpic, $src, 0, 0, 0, 0, $w, $h, imagesx($src), imagesy($src));

            $rand = 'images/nuevas/' . explode('.',$image->getFilename())[0];

            $hola = imagepng($newpic, $rand.'.png');

        }



              
    }


    function upload(Request $request)
    {
        $image = $request->file('file');
        $contador = Subida::latest()->first();
        
        $imageName =   $contador->actual . '.' . $image->extension();

        $image->move(public_path('images'), $imageName);

        $contador->actual =  $contador->actual + 1;
        $contador->save();

        if (! File::exists("images/nuevas")) {
            File::makeDirectory("images/nuevas");
        }

        return response()->json(['success' => $imageName]);
    }

    function fetch()
    {
        $images = \File::allFiles(public_path('images'));
        $output = '<div class="row">';
        foreach ($images as $image) {
            $output .= '
      <div class="col-md-2" style="margin-bottom:16px;" align="center">
                <img src="' . asset('images/' . $image->getFilename()) . '" class="img-thumbnail" width="175" height="175" style="height:175px;" />
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
            \File::delete(public_path('images/' . $request->get('name')));
        }
    }
}
