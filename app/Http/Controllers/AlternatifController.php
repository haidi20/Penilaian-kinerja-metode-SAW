<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Alternatif;

class AlternatifController extends Controller
{
    public function index(){
      $alternatif = Alternatif::all();

      session()->put('aktif','alternatif');
      session()->put('aktiff','dasar');
      return view('alternatif.index', compact('alternatif'));
    }

    public function create(){
      return $this->form();
    }

    public function edit($id){
      return $this->form($id);
    }

    public function form($id = null){
      $alternatifFind = Alternatif::find($id);

      if ($alternatifFind){
          session()->flashInput($alternatifFind->toArray());
          $action   = route('alternatif.update', $id);
          $method   = 'PUT';
      }else{
          $action   = route('alternatif.store');
          $method   = 'POST';
      }

      return view('alternatif.form', compact('action', 'method'));
    }

    public function store(){
      return $this->save();
    }

    public function update($id){
      return $this->save($id);
    }

    public function save($id = null){
      if ($id) {
        $alternatif = Alternatif::find($id);
      }else{
        $alternatif = new Alternatif; 
      }

      $alternatif->nama = request('nama');
      $alternatif->nik  = request('nik');
      $alternatif->save();

      return redirect()->route('alternatif.index');
    }

    public function destroy($id){
      $alternatif = Alternatif::find($id);
      $alternatif->delete();

      return redirect()->back();
    }
}
