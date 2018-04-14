@extends('layouts.app')

@section('content')
    <div class="container">

        <form action="/api/forms" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            Form name:
            <br/>
            <input type="text" name="form_name"/>
            Form description:
            <br/>
            <textarea id="description" name="description"></textarea>
            <br/><br/>
            Form image
            <br/>
            <input type="file" name="form_file"/>
            <br/><br/>

            Form icon
            <br/>
            <input type="file" name="icon"/>
            <br/><br/>
            <input type="submit" value="Upload"/>
        </form>
    </div>

@endsection
