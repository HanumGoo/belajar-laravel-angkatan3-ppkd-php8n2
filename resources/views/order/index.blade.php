@extends('app')
@section('content')
    Hello haha
    click here
    <a href="{{ url('order/create') }}" class="btn btn-primary">to order create</a>

    {{-- <div align="right" class="mb-3">
        <a href="{{ route('peserta-create') }}" class="btn btn-primary">Add Peserta</a>
    </div> --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Order Code</th>
                <th>Order Amount</th>
                <th>Order Change</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $index => $value)
                <tr>
                    <td>{{ $index += 1 }}</td>
                    <td>{{ $value->order_code }}</td>
                    <td>Rp {{ number_format($value->order_amount, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($value->order_change, 0, ',', '.') }}</td>
                    <td><span
                            class="badge text-bg-{{ $value->status == 2 ? 'success' : ($value->status == 1 ? 'warning' : 'danger') }}">
                            {{ $value->status == 2 ? 'paid' : ($value->status == 1 ? 'pending' : 'denied') }}
                        </span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
