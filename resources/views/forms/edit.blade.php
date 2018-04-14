@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>Edit your form</h1>
                <hr>
                <div id="form-canva"
                     data-file-id="{{ $files[0]->id }}"
                     style="background:url(/{{$files[0]->filename}});
                             width: {{$files[0]->file_width}}px;
                             height: {{$files[0]->file_height}}px;
                             background-repeat: no-repeat;"
                >

                @foreach($files[0]->fields as $field)
                            <div class='input-drag'
                                 data-fieldid="{{ $field->id }}"
                                 id="form-field-{{ $field->id }}"
                                 style="position: relative; left: {{$field->field_pos_x }}px; top: {{$field->field_pos_y }}px;"
                            >

                                <div class='input-resize {{$field->field_type }}'
                                style="width: {{ $field->width }}px"
                                >
                                    @if($field->field_type == 'radio')
                                        <input type="{{$field->field_type }}" name="{{$field->group }}">

                                    @elseif($field->field_type == 'text')
                                        <input maxlength="{{ $field->length }}" type="{{$field->field_type }}"  name="{{$field->name }}">

                                    @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4">
                <h3>This is my form elements</h3>
                <hr>
                <div id="new-radio" class="btn btn-default">Add Radio element</div>
                <div id="new-text" class="btn btn-default">Add Text element</div>
                <hr>
                <form id="element-params">
                <h3>Editing element <span id="element-id"></span></h3>

                    <div class="form-group text">
                        <label>Length: </label>
                        <input type="text" id="length" name="length">
                    </div>
                    <div class="form-group text">
                        <label>Width: </label>
                        <input type="text" name="width">
                    </div>

                    <div class="form-group radio">
                        <label>Group: </label>
                        <input type="text" id="group" name="group">
                    </div>

                    <div class="select-info form-group select">
                        <label>Options: </label>
                        <input type="text" name="group">
                        <button class="btn btn-default" id="add-option">Add</button>
                    </div>
                    <button type="submit" class="btn btn-success" id="save-params">Save</button>

                </form>
            </div>
        </div>
    </div>

@endsection
