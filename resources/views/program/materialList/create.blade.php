@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">{{$title}}</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form method="post" action="{{ $route}}@if(isset($service)){{"/".$service->id }}@endif" enctype="multipart/form-data">
                            @csrf
                            @if(isset($service))
                                @method("PUT")
                            @endif

                            <div class="form-group">
                                <label for="name">Ապրանքի Անվանում</label>
                                @error('material_id')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <select name="material_id" id="" class="form-control select2" required>
                                    @foreach($materials as $material)
                                        <option @if(old("material_id") == $material->id) @endif value="{{ $material->id }}">{{ $material->name . " ( " .$units[$material->unit] . " ) " }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="name">Ապրանքի Քանակ</label>
                                @error('quantity')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="number" step="any" class="form-control" name="quantity" value="{{ old("quantity") }}" required>
                            </div>

                            <div class="form-group">
                                <label for="name">Ապրանքի Ինքնարժեք ( Միավոր Ապրանքի Համար )</label>
                                @error('self_price')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="number" step="any" class="form-control" value="{{ old("self_price") }}" name="self_price" required>
                            </div>

                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Պահպանել</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('head')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2/dist/css/select2.css") }}">
@endpush
@push('foot')
    <script src="{{ asset("assets/plugins/select2/dist/js/select2.js") }}"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2();
        })
    </script>
@endpush
