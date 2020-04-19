@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                Դուք Մուտք գործեցիք <br/>
                {{Auth::user()->name}}
            </div>
        </div>
    </div>
@endsection
