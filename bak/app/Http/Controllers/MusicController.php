<?php

namespace App\Http\Controllers;

use App\Music;
use Auth;
use Illuminate\Http\Request;

class MusicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $musics = Music::orderBy('id', 'desc')->paginate(10);
        return view('music.index')
            ->withMusics($musics);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('music.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $music = new Music($request->all());

        $this->save_music($music, $request);

        return redirect()->to('/home');
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
        $music = Music::findOrFail($id);
        return view('music.edit')
            ->withMusic($music);
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
        $music = Music::findOrFail($id);
        $music->update($request->all());
    
        return redirect()->to('/music');
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

    public function save_music($music,$request)
    {
        if ($request->music->getClientOriginalExtension() == "mp3") {
            $file    = $request->music;
            $mp3_dir = public_path('/storage/mp3/');
            if (!is_dir($mp3_dir)) {
                mkdir($mp3_dir, 0777, 1);
            }
            $filename = Auth::id() . time() . '.mp3';
            $file->move($mp3_dir, $filename);
            $path        = '/storage/mp3/' . $filename;
            $music->path = $path;
            $music->save();
        } else {
            dd('上传的文件格式不符合要求或没有上传文件');
        }
    }
}
