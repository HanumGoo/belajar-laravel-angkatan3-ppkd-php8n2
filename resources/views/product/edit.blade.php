@extends('app')
@section('content')
    <form action="{{ route('product.update', $product->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="" class="form-label">Category Name</label>
            <select name="category_id" id="" class="form-control" required>
                <option value="" hidden>Select One</option>
                @foreach ($categories as $index => $value)
                    <option {{ $product->category_id == $value->id ? 'selected' : '' }} value="{{ $value->id }}">
                        {{ $value->name }}
                    </option>
                @endforeach

            </select>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="{{ $product->name }}" required>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Quantity</label>
            <input type="number" class="form-control" name="qty" value="{{ $product->qty }}">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Price</label>
            <input type="number" class="form-control" name="price" step="any" value="{{ $product->price }}" required>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Photo (leave it blank if you don't want to change)</label>
            <input type="file" class="form-control" name="photo">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Description</label>
            <textarea class="form-control" name="description">{{ $product->description }}</textarea>
        </div>

        <div class="mb-3">
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
@endsection
