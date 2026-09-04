@extends('app')
@section('content')
    <div class="table table-responsive">
        <div align="right" class="mb-3">
            <a href="{{ route('product.create') }}" class="btn btn-primary">Add Product</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Category Name</th>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $value)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $value->category->name }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->qty }}</td>
                        <td>Rp. {{ number_format($value->price, 2, ',', '.') }}</td>
                        <td>{{ $value->description }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img class="rounded" width="60" src="{{ url(Storage::url($value->photo)) }}" alt=""
                                    style="object-fit: cover">
                                <div class="">
                                    {{ $value->name }}
                                </div>
                            </div>


                        </td>
                        <td class="d-flex gap-3">
                            <a href="{{ route('product.edit', $value->id) }}" class="btn btn-success">Edit</a>
                            <form action="{{ route('product.destroy', $value->id) }}" method="post">
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
