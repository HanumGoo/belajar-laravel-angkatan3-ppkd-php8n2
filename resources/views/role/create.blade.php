@extends('app')
@section('content')
    <form action="{{ route('role.store') }}" method="post">
        @csrf
        <div class="mb-3">
            <label for="" class="form-label">Role Name</label>
            <input type="text" class="form-control" name="name">
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" checked>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
        </div>

        <div class="mb-3">
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
@endsection
