@extends('admin.layout')

@section('header')
    <section class="content-header">
        <div class="row">
            <div class="col-md-8">
                @if($entity == null)
                    <h1>Write a entity</h1>
                @else
                    <h1>Update a entity</h1>
                @endif
            </div>
            <div class="col-md-4">
                <a class="btn btn-warning btn-block" href="{{route('entity.index')}}"> Back</a>
            </div>
        </div>

    </section>
@endsection

@section('content')

  @if($entity == null)
    <entitystudio
            route_image="{{url(\App\Models\Entity::routeImagePath())}}"
            route_image_create="{{route('entity.image.upload')}}"
            route_entity_submit="{{route('entity.store')}}"
    ></entitystudio>
  @else
    <entitystudio
            edit="{{$entity->id}}"
            :original="{{$entity}}"
            route_image="{{url(\App\Models\Entity::routeImagePath())}}"
            route_image_create="{{route('entity.image.upload')}}"
            route_entity_submit="{{route('entity.store')}}"
    ></entitystudio>
  @endif

@endsection
