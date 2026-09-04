@extends('app')
@section('content')
    <div align="right" class="mb-3">
        <a href="{{ route('peserta-create') }}" class="btn btn-primary">Add Peserta</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>umur</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pesertas as $index => $value)
                <tr>
                    <td>{{ $index += 1 }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->age }}</td>
                    <td class="d-flex gap-3">
                        <a href="{{ route('peserta-edit', $value->id) }}" class="btn btn-success">Edit</a>
                        <form action="{{ route('peserta-delete', $value->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('for real?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
