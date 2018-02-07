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
    <entitylist
        :originals="{{collect($entitys->items())}}"
        route_image="{{url(\App\Models\Entity::routeImages)}}"
    >
    </entitylist>

    {{$entitys->links()}}
@endsection
