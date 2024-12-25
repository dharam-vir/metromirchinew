@extends('adminlte::page')

@section('title', 'Listing')

@section('content_header')
    <h1>Listing</h1>
@stop

@section('content')
    <p>Welcome to this beautiful Listing Page.</p>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop