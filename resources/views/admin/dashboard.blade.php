@extends('admin.layout')

@section('header')
    <section class="content-header">
      <h1>
        Dashboard<small>template</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#">Project name</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="box-title">Title</div>
                </div>

                <div class="box-body">Body</div>
            </div>
        </div>
    </div>
@endsection
