@extends('layouts.app')

@section('content')
    <entity
        route_image="{{url(\App\Models\Entity::routeImagePath())}}"
        route_back="{{route('entity.front.index')}}"
        :entity="{{$entity}}"
    ></entity>
@endsection