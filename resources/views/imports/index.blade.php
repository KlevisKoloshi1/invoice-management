@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Imports</h1>
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <a href="{{ route('imports.create') }}" class="btn btn-primary mb-3">Upload Import</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>File</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($imports as $import)
            <tr>
                <td>{{ $import->id }}</td>
                <td>{{ $import->file_path }}</td>
                <td>{{ $import->status }}</td>
                <td>{{ $import->user->name ?? 'N/A' }}</td>
                <td>{{ $import->created_at }}</td>
                <td>
                    <a href="{{ route('imports.edit', $import) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('imports.destroy', $import) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this import?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $imports->links() }}
</div>
@endsection 