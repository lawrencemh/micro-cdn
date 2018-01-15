@extends('layouts.basic')

@section('title', 'Welcome')

@push('assets')
<link rel="stylesheet" href="{{ url('build/css/app.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div id="app"></div>
    </div>

    <script src="{{ url('build/js/app.js') }}"></script>
@endsection
