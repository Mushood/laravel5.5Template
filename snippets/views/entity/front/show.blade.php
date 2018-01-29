@extends('layouts.app')

@section('content')
    <entity
        route_image="{{url('/images/entitys')}}"
        route_back="{{route('entity.front.index')}}"
        :entity="{{$entity}}"
    ></entity>
@endsection