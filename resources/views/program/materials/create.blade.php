@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">{{$title}}</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form method="post" action="{{ $route}}@if(isset($material)){{"/".$material->id }}@endif" enctype="multipart/form-data">
                            @csrf
                            @if(isset($material))
                                @method("PUT")
                            @endif

                            <div class="form-group">
                                <label for="name">Նյութի Անուն</label>
                                @error('name')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="name" name="name" value="{{ $material->name ?? old('name')}}">
                            </div>

                            <div class="form-group">
                                <label for="phone">Չափման Միավոր</label>
                                @error('unit')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <select name="unit" class="form-control" id="unit">
                                    @foreach($units as $bin => $unit)
                                        <option @if(isset($material->unit) && $material->unit == $bin) selected @elseif(old("unit") == $bin) selected @endif value="{{ $bin }}">{{ $unit }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Պահպանել</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
