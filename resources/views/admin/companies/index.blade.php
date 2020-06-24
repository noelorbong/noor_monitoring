@extends('layouts.coreuiadmin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Companies</div>

                    <div class="card-body table-responsive">

                        <router-view name="companiesIndex"></router-view>
                        <router-view></router-view>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
