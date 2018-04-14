@extends('layouts.app')

@section('content')
    <div  class="container">
        <a class="btn btn-success" href="{{ route('forms.create') }}">Add new form</a>
        <table class="table">
            <thead>
            <th>Form name</th>
            <th>Form files</th>
            <th>Form form elements</th>
            <th>Actions</th>
            </thead>
            @foreach($forms as $form)
                <tr>
                    <td>{{ $form->name }}</td>
                    @if($form->files)
                    <td>{{ count($form->files) }}</td>
                    @endif
                    @if($form->fields)
                    <td>{{ count($form->fields) }}</td>
                    @endif
                    <td><a  href="{{ route('forms.edit', $form->id) }}" class="btn btn-danger">Edit</a></td>
                </tr>
            @endforeach
        </table>
    </div>


@endsection
