@extends('layouts.app')

@section('content')
    <entity
        route_image="{{url('/images/entity/')}}",
        route_back="{{route('entity.index')}}"
    ></entity>
@endsection