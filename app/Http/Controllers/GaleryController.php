<?php

namespace App\Http\Controllers;

use App\Models\galery;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class GaleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $galeries = Galery::orderBy('id','DESC')->where('user_id',Session::get('user_id'))->get();
       return view('index',compact('galeries'));
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
        $val=$request->validate([
            'judul'=>"required",
            'deskripsi'=>"required",
            'photo'=>"required",
        ]);
        if ($request->hasFile('photo'))
        {
            $filePath = Storage::disk('public')->put('images/posts/', request()->file('photo'));
            $val['photo']= $filePath;
        }
        $create = Galery::create([
            'judul'=> $val['judul'],
            'deskripsi'=> $val['deskripsi'],
            'photo'=> $val['photo'],
            'user_id'=> Session::get('user_id'),
        ]);
        if ($create)
        {
            session()->flash('success','galery berhasil dibuat');
            return redirect('/galery');
        }
        return abort(500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\galery  $galery
     * @return \Illuminate\Http\Response
     */
    public function show(galery $galery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\galery  $galery
     * @return \Illuminate\Http\Response
     */
    public function edit(galery $galery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\galery  $galery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, galery $galery)
        {
            if ($request->hasFile('photo'))
            {
                $filePath = Storage::disk('public')->put('images/posts/', request()->file('photo'));
                $galery->judul=$request->judul;
                $galery->deskripsi=$request->deskripsi;
                $galery->photo=$filePath;
                $galery->user_id=Session::get('user_id');
                $galery->save();
            }
            else{
                $galery->judul=$request->judul;
                $galery->deskripsi=$request->deskripsi;
                $galery->photo=$galery->photo;
                $galery->user_id=Session::get('user_id');
                $galery->save();
            }
            return redirect('/galery');
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\galery  $galery
     * @return \Illuminate\Http\Response
     */
    public function destroy(galery $galery)
    {
        $galery->delete();
        return redirect('/galery');
    }
}
