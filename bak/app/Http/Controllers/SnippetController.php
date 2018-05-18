<?php

namespace App\Http\Controllers;

use App\Snippet;
use Illuminate\Http\Request;

class SnippetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $snippets = Snippet::orderBy('id', 'desc')->paginate(10);
        return view('snippet.index')->withSnippets($snippets);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('snippet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $snippet              = new Snippet($request->all());
        $snippet->user_id     = $request->user()->id;
        $snippet->category_id = 0; //TODO:: set category for snippets ..
        $snippet->save();

        $this->make_image($request, $snippet);
        $snippet->save();
        return redirect()->to('/snippet');
    }

    public function make_image($request, $snippet)
    {
        if ($request->image) {
            $img = \ImageMaker::make($request->image->path());
            $img->resize(100, 100);
            $snippet_image_folder = public_path('/storage/snippet');
            if (!is_dir($snippet_image_folder)) {
                mkdir($snippet_image_folder, 0777, 1);
            }
            $filename   = $snippet->id . '.jpg';
            $image_path = '/storage/snippet/' . $filename;
            $img->save(public_path($image_path));
            $snippet->image = $image_path;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $snippet       = Snippet::findOrFail($id);
        $snippet->body = parse_video($snippet->body);
        return view('snippet.show')->withSnippet($snippet);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $snippet = Snippet::findOrFail($id);
        return view('snippet.edit')->withSnippet($snippet);
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
        $snippet = Snippet::findOrFail($id);
        $snippet->update($request->all());
        $snippet->user_id     = $request->user()->id;
        $snippet->category_id = 0; //TODO:: set category for snippets ..

        $this->make_image($request, $snippet);
        $snippet->save();
        return redirect()->to('/snippet');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $snippet = Snippet::findOrFail($id);
        $snippet->delete();
        return redirect()->to('/snippet');
    }
}
