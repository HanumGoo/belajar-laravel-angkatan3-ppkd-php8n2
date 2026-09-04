@extends('app')
@section('content')
    <form action="{{ route('peserta-update', $peserta->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="" class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama" value="{{ $peserta->name }}">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ $peserta->email }}">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Umur</label>
            <input type="number" class="form-control" name="umur" value="{{ $peserta->age }}">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Address</label>
            <input type="text" class="form-control" name="address" value="{{ $peserta->address }}">
        </div>
        <div class="mb-3">
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
@endsection
