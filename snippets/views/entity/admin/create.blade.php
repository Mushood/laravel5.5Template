@extends('admin.layout')

@section('header')
    <section class="content-header">
      @if($entity == null)
        <h1>Write a entity</h1>
      @else
        <h1>Update a entity</h1>
      @endif
    </section>
@endsection

@section('content')

  @if($entity == null)
    <entitystudio
            route_image="{{url('/images/entitys')}}"
            route_image_create="{{route('entity.image.upload')}}"
            route_entity_submit="{{route('entity.store')}}"
    ></entitystudio>
  @else
    <entitystudio
            edit="{{$entity->id}}"
            route_image="{{url('/images/entitys')}}"
            route_image_create="{{route('entity.image.upload')}}"
            route_entity_submit="{{route('entity.store')}}"
    ></entitystudio>
  @endif

@endsection
