<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Kinerja;
use App\Models\Pembantu;
use App\Models\Peringkat;
use App\Models\Normalisasi;

use App\Supports\Logika;
use App\Supports\Topsis;

use Auth;

class DataController extends Controller
{
  public function __construct(Logika $logika,Topsis $topsis){
    $this->logika = $logika;
    $this->topsis = $topsis;
  }

  public function index(){
    return $this->inputNormalisasi();
    $this->inputKinerja();

    // $this->inputPeringkat();
    // return $this->jalur();
  }

  // NORMALISASI UNTUK SAW DAN TOPSIS
  public function inputNormalisasi(){
    $normalisasiProses = $this->logika->normalisasiProses();

    // return $normalisasiProses;

    foreach ($normalisasiProses as $index => $item) {
      $attribute  = $item->attribute;
      $kreteria_id= $item->kreteria;

      foreach ($item->hasilAkhir as $key => $value) {
        $nilai      = $value->nilai;
        $alternatif_id = $value->alternatif;

        $normalisasi = Normalisasi::FirstOrCreate(compact('kreteria_id', 'alternatif_id'));
        $normalisasi->nilai = $nilai;
        $normalisasi->save();
      }
    }

    return $normalisasi;
  }

// menampilkan data sekolah secara realtime..
  public function dataSekolah(){
    $id = request('alter');
    $nilai  = $this->logika->inputan($id,'ajax');

    return $nilai ;
  }

// start function supports

// function jalur
  public function jalur(){
    if (session()->get('controller') == 'sekolah') {
      return redirect()->route('sekolah.index');
    }
    else if (session()->get('controller') == 'kreteria') {
      return redirect()->route('kreteria.index');
    }
  }

// function JENIS
  public function jenis($jenis){
    // nama metode yang di pakai
    $nama = Auth::user()->nama;
    if ($jenis == 'nama') {
      return $nama ;
    }elseif($jenis == 'status'){
      // function inutKinerja
      if ($nama == 'saw') {
        return 'kinerja';
      }else{
        return 'terbobot';
      }
    }
  }

// end function supports

// input data KINERJA untuk SAW dan TERBOBOT untuk TOPSIS
  public function inputKinerja(){
    $kinerjaProses  = $this->logika->kinerjaProses($this->jenis('nama'));
    $kinerja        = [];

    foreach ($kinerjaProses as $key => $value) {
      foreach ($value as $index => $item) {
        $nilai          = $item['nilai'];
        $kreteria_id    = $item['kreteria'];
        $alternatif_id  = $item['alternatif'];
        $jenis          = $this->jenis('status');

        $kinerja        = Kinerja::firstOrCreate(compact('alternatif_id','kreteria_id','jenis'));
        $kinerja->nilai = $nilai;
        $kinerja->save();
      }
    }
  }

  public function inputPeringkat(){
    $jenis = $this->jenis('nama') ;
    if ($jenis == 'saw') {
      $peringkatProses = $this->logika->peringkatProses();
    }elseif($jenis == 'topsis'){
      $peringkatProses = $this->topsis->peringkatProses();
    }

    foreach ($peringkatProses as $key => $value) {
      $nilai      = $value['nilai'];
      $rengking   = $value['rengking'];
      $alternatif_id = $value['alternatif'];

      $peringkat = Peringkat::firstOrCreate([
        'jenis' => $jenis,
        'alternatif_id' => $alternatif_id
      ]);
      $peringkat->nilai = $nilai;
      $peringkat->peringkat = $rengking;
      $peringkat->save();
    }
  }

// start SUPPORTS pembantu
  public function inputPembantu($data,$format,$jenis){
    // alpha = kreteria_id dan delta = alternatif_id
    if ($format == 'alpha') {
      $atribut = 'kreteria_id';
    }else{
      $atribut = 'alternatif_id';
    }
    foreach ($data as $index => $item) {
      $pembantu = Pembantu::firstOrCreate([
        $atribut      => $index,
        'jenis'       => $jenis,
        'format'      => $format
      ]);
      $pembantu->nilai = $item;
      $pembantu->save();
    }
  }
//end SUPPORTS pembantu alpha dan delta

  public function inputAlphaPositif(){
    $data = $this->topsis->alphaProses('maksimal');

    $this->inputPembantu($data,'alpha','positif');
  }

  public function inputAlphaNegatif(){
    $data   = $this->topsis->alphaProses('minimal');

    $this->inputPembantu($data,'alpha','negatif');
  }

  public function inputDeltaPositif(){
    $jenis  = 'positif';

    $data   = $this->topsis->deltaProses($jenis);
    $this->inputPembantu($data,'delta',$jenis);
  }

  public function inputDeltaNegatif(){
    $jenis  = 'negatif';

    $data   = $this->topsis->deltaProses($jenis);
    $this->inputPembantu($data,'delta',$jenis);
  }
}
