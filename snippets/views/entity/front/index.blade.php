@extends('layouts.app')

@section ('content')
<div class="container">
    @foreach($entitys as $entity)
        <div class="row">
            <entitylist :entity="{{$entity}}"
                        is_admin="{{(Auth::user() != null) && Auth::user()->hasRole('admin')}}"
                        route_show="{{route('entity.front.show', ['entity' => $entity->slug])}}"
                        route_edit="{{route('entity.edit', ['entity' => $entity->id])}}"
                        route_publish="{{route('entity.publish', ['entity' => $entity->id])}}"
                        route_unpublish="{{route('entity.unpublish', ['entity' => $entity->id])}}"
                        route_delete="{{route('entity.destroy', ['entity' => $entity->id])}}"
                        route_image="{{url(\App\Models\Entity::routeImages)}}"
            >
            </entitylist>
        </div>
        <hr />
    @endforeach
    {{$entitys->links()}}
</div>
@endsection
