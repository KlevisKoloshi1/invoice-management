@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Invoice #{{ $invoice->id }}</h1>
    <div class="mb-3">
        <strong>Client:</strong> {{ $invoice->client->name ?? 'N/A' }}<br>
        <strong>Total:</strong> {{ $invoice->total }}<br>
        <strong>Status:</strong> {{ $invoice->status }}<br>
        <strong>Fiscalized:</strong> {{ $invoice->fiscalized ? 'Yes' : 'No' }}<br>
        <strong>Created At:</strong> {{ $invoice->created_at }}<br>
    </div>
    <h3>Items</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back to Invoices</a>
</div>
@endsection 