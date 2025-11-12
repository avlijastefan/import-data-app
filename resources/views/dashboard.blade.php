@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')
<div class="row">
    @can('user-management')
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ \App\Models\User::count() }}</h3>
                <p>Users</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ \App\Models\Import::count() }}</h3>
                <p>Imports</p>
            </div>
            <div class="icon"><i class="fas fa-file-import"></i></div>
        </div>
    </div>
    @else
      <div class="col-12 text-center my-5">
            <h3>Welcome to Import App</h3>
            <p>This application allows you to import and manage your data efficiently.</p>
        </div>
    @endcan
</div>
@endsection