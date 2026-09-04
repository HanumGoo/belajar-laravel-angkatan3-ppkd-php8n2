@extends('app')
@section('content')
    <div class="table table-responsive">
        <div align="right" class="mb-3">
            <a href="{{ route('role.create') }}" class="btn btn-primary">Add Menu</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($menus as $index => $value)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->is_active ? 'Enabled' : 'Disabled' }}</td>
                        <td class="d-flex gap-3">
                            <a href="{{ route('menu.edit', $value->id) }}" class="btn btn-success">Edit</a>
                            <form action="{{ route('menu.destroy', $value->id) }}" method="post">
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
    </div>
@endsection
