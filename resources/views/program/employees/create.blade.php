@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">{{ $title }}</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form method="post" action="{{ $route }}@if(isset($employee)){{"/".$employee->id }}@endif" enctype="multipart/form-data">
                            @if(isset($employee))
                                @method("PUT")
                            @endif
                            @csrf

                            <div class="form-group">
                                <label for="name">Անուն</label>
                                @error('name')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $employee->name ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label for="phone">Հեռախոսահամար</label>
                                @error('phone')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') ?? $employee->phone ?? '' }}">
                            </div>

                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Պահպանել</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
