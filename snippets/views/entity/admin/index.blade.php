@extends('admin.layout')

@section('header')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>
                    Our Entity
                </h1>
            </div>
            <div class="col-md-3">
                <a href="{{route('entity.export')}}" class="btn btn-primary btn-block" target="_blank">Export to CSV</a>
            </div>
            <div class="col-md-3">
                <a href="{{route('entity.create')}}" class="btn btn-success btn-block">New Entity</a>
            </div>
        </div>
        <div class="row">
            <searchbar
                route_search="{{route('entity.search')}}"
            >
            </searchbar>
        </div>
    </section>
@endsection


@section ('content')
    <entitylist
        :originals="{{collect($entitys->items())}}"
        route_image="{{url(\App\Models\Entity::routeImages)}}"
        route_bulk_action="{{route('entity.bulk.action')}}"
    >
    </entitylist>

    {{$entitys->links()}}
@endsection
