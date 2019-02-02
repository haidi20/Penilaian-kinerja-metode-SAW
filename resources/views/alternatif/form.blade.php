@extends('_layouts.default')

@section('konten')
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <h1>Form Alternatif</h1>
      </div>
      <div class="col-md-6 text-right">
        <a href="{{route('alternatif.index')}}" class="btn btn-success btn-md buat">Kembali</a>
      </div>
    </div>
    <hr class="dashed mb20 mt20">
    <br>
    <div class="row">
      <div class="col-md-4 m">
        <form action="{{$action}}" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="_method" value="{{$method}}">
          <div class="row">
            <div class="col-md">
              <div class="form-group">
                <label for="nik">Nik</label>
                <input type="text" name="nik" id="nik" class="form-control" value="{{old('nik')}}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md">
              <div class="form-group">
                <label for="nama">Nama Warga</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{old('nama')}}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1 col-md-offset-9">
              <button type="submit" class="btn btn-md btn-success">Oke</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
