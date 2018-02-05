@extends('layouts.app')

@section('content')
    <entity
        route_image="{{url(\App\Models\Entity::routeImages)}}"
        route_back="{{route('entity.front.index')}}"
        :entity="{{$entity}}"
    ></entity>
@endsection