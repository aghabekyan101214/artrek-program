@extends('layouts.app')

@section('content')
    <style>
        hr{
            border-color: #0e6185;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">{{$title}}</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form method="post" action="{{ $route .(isset($paidOrder->id) ? "/$paidOrder->id" : "") }}" enctype="multipart/form-data">
                            @csrf
                            @if(isset($paidOrder->id))
                                @method("PUT")
                            @endif

                            <div class="form-group">
                                <label for="type">Տեսակ</label>
                                @error('type')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <select name="type" id="type" required class="form-control">
                                    <option @if(isset($paidOrder->price) && $paidOrder->price < 0) selected @endif value="-1">Ելք</option>
                                    <option @if(isset($paidOrder->price) && $paidOrder->price > 0) selected @endif value="1">Մուտք</option>
                                </select>
                            </div>

                            @if(!isset($paidOrder))

                                <div class="form-group">
                                    <label for="price_cash">Գումարի Չափ <strong>ԿԱՆԽԻԿ</strong></label>
                                    @error('price_cash')
                                    <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                    @enderror
                                    <input type="number" step="any" min="0" class="form-control" id="price_cash" name="price_cash" required value="{{ isset($paidOrder->price) ? abs($paidOrder->price) : (old('price') ?? 0) }}">
                                </div>

                                <div class="form-group">
                                    <label for="price_transfer">Գումարի Չափ <strong>ՓՈԽԱՆՑՈՒՄ</strong></label>
                                    @error('price_transfer')
                                    <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                    @enderror
                                    <input type="number" step="any" min="0" class="form-control" id="price_transfer" name="price_transfer" required value="{{ isset($paidOrder->price_transfer) ? abs($paidOrder->price_transfer) : (old('price_transfer') ?? 0) }}">
                                </div>

                            @else

                                <div class="form-group">
                                    <label for="price">Գումարի Չափ <strong> @if($paidOrder->transfer_type) ՓՈԽԱՆՑՈՒՄ @else ԿԱՆԽԻԿ @endif</strong></label>
                                    @error('price')
                                    <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                    @enderror
                                    <input type="number" step="any" min="0" class="form-control" id="price" name="price" required value="{{ isset($paidOrder->price) ? abs($paidOrder->price) : (old('price') ?? 0) }}">
                                </div>

                            @endif

                            <div class="form-group">
                                <label for="comment">Comment</label>
                                @error('comment')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <textarea name="comment" cols="30" class="form-control" rows="10">{{ $paidOrder->comment ?? old("comment") }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Պահպանել</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('head')
        <link rel="stylesheet" href="{{ asset("assets/plugins/select2/dist/css/select2.css") }}">
        <link rel="stylesheet" href="{{ asset("assets/plugins/datepicker/bootstrap-datepicker.min.css") }}">
    @endpush
    @push('foot')
        <script src="{{ asset("assets/plugins/select2/dist/js/select2.js") }}"></script>
        <script src="{{ asset("assets/plugins/datepicker/bootstrap-datepicker.min.js") }}"></script>
        <script>

            $(document).ready(function () {
                $(".select2").select2();

            });

        </script>
    @endpush
@endsection
