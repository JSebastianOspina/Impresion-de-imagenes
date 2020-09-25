<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subida;

class DropZone extends Controller
{
    function index()
    {
     return view('dropzone');
    }

    function upload(Request $request)
    {
     $image = $request->file('file');
     $contador = Subida::latest()->first();
     /*
     foreach($request->file('file') as $foto){
        $foto->move(public_path('images'), $contador.'.'.$foto->extension());
        $contador++;
     }
     */
    
    $imageName =   $contador->actual.'.'.$image->extension();

    $image->move(public_path('images'), $imageName);
    $contador->actual =  $contador->actual+1;
    $contador->save();

     return response()->json(['success' => $imageName]);
    }

    function fetch()
    {
     $images = \File::allFiles(public_path('images'));
     $output = '<div class="row">';
     foreach($images as $image)
     {
      $output .= '
      <div class="col-md-2" style="margin-bottom:16px;" align="center">
                <img src="'.asset('images/' . $image->getFilename()).'" class="img-thumbnail" width="175" height="175" style="height:175px;" />
                <button type="button" class="btn btn-link remove_image" id="'.$image->getFilename().'">Remove</button>
            </div>
      ';
     }
     $output .= '</div>';
     echo $output;
    }

    function delete(Request $request)
    {
     if($request->get('name'))
     {
      \File::delete(public_path('images/' . $request->get('name')));
     }
    }

}
