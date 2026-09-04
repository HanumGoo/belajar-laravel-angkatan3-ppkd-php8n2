@extends('app')
@section('content')
    <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="" class="form-label">Category Name</label>
            <select name="category_id" id="" class="form-control" required>
                <option value="" hidden>Select One</option>
                @foreach ($categories as $index => $value)
                    @if ($value->is_active == 1)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                    @endif
                @endforeach

            </select>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Quantity</label>
            <input type="number" class="form-control" name="qty">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Price</label>
            <input type="number" class="form-control" name="price" step="any" required>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Photo</label>
            <input type="file" class="form-control" name="photo">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Description</label>
            <textarea class="form-control" name="description"></textarea>
        </div>

        <div class="mb-3">
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
@endsection
