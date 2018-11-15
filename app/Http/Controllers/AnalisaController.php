<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Hasil;
use App\Models\Kreteria;
use App\Models\Alternatif;

use App\Supports\Logika;

class AnalisaController extends Controller
{
    public function __construct(Logika $logika){
      $this->logika = $logika;
    }

    public function index(){
      $kreteria   = Kreteria::berdasarkan()->get();
      $warga      = Hasil::berdasarkanAlternatif()->get();
      $nilai      = $this->logika->warga();

      session()->put('aktif','analisa');
      session()->put('aktiff','');

      return view('analisa.index',compact('nilai','kreteria','warga'));
    }
}
