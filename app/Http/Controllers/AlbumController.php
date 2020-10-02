<?php

namespace App\Http\Controllers;

use App\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $albumes = Album::all();

        return view('panel.album.clientes', compact('albumes'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function verAlbum($id)
    {
        $directorio = "images/" . Auth::user()->id . "/" . $id;
        $album = Album::find($id);
        $images = \File::allFiles(public_path($directorio));
        return view('panel.album.album',compact('images','directorio','album'));
    }

    function guardarAlbum(Request $request)
    {

        $aux = Auth::user()->albums->count() + 1;
        $directorio = "images/" . Auth::user()->id . "/" . $aux;
        if (File::exists($directorio)) {
            $album = new Album();
            $album->portada = $directorio."/.jpeg";
            $album->cantidad =  count(\File::allFiles(public_path($directorio)));
            $album->user_id = Auth::user()->id;
            $album->save();
            return response(route('album.ver',['id'=>$aux]),200);
        } else {
            return response('',403);
        }
    }
}
