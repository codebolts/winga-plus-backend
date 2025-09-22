@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">{{ __('Dashboard') }}</h1>
        <div class="card">
            <div class="card-body">
                <p class="card-text">{{ __("You're logged in!") }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
