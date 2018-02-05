@extends('admin.layout')

@section('header')
    <section class="content-header">
      <div class="row">
        <div class="col-md-8">
          <h1>
            Our Entity
          </h1>
        </div>
        <div class="col-md-4">
          <a href="{{route('entity.create')}}" class="btn btn-primary btn-block">New Entity</a>
        </div>
      </div>

    </section>
@endsection


@section ('content')
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entitys as $key => $entity)
                <tr is="entitylist"
                    index="{{$key+1}}"
                    :entity="{{$entity}}"
                    is_admin="{{(Auth::user() != null) && Auth::user()->hasRole('admin')}}"
                    route_show="{{route('entity.show', ['entity' => $entity->id])}}"
                    route_edit="{{route('entity.edit', ['entity' => $entity->id])}}"
                    route_publish="{{route('entity.publish', ['entity' => $entity->id])}}"
                    route_unpublish="{{route('entity.unpublish', ['entity' => $entity->id])}}"
                    route_delete="{{route('entity.destroy', ['entity' => $entity->id])}}"
                    route_image="{{url(\App\Models\Entity::routeImages)}}"
                >
                </tr>
            @endforeach
        </tbody>
    </table>
    {{$entitys->links()}}
</div>
@endsection
