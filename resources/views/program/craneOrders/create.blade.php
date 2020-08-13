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
                        <form method="post" action="{{ $route .(isset($craneOrder->id) ? "/$craneOrder->id" : "") }}" enctype="multipart/form-data">
                            @csrf
                            @if(isset($craneOrder->id))
                                @method("PUT")
                            @endif
                            <div class="form-group">
                                <label for="client_id">Հաճախորդ</label>
                                @error('client_id')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <select name="client_id" class="form-control select2" id="" required>
                                    <option value="">Ընտրել Հաճախորդ</option>
                                    @foreach($clients as $client)
                                        <option @if(old("client_id") == $client->id || (isset($craneOrder->id) && $craneOrder->client_id == $client->id) ) selected @endif value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="driver_id">Վարորդ</label>
                                @error('driver_id')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <select name="driver_id" class="form-control select2" id="" required>
                                    <option value="">Ընտրել Վարորդ</option>
                                    @foreach($drivers as $driver)
                                        <option @if(old("driver_id") == $driver->id || (isset($craneOrder->id) && $craneOrder->driver_id == $driver->id) ) selected @endif value="{{ $driver->id }}">{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="price">Պատվերի Գումար</label>
                                @error('price')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="number" step="any" class="form-control" id="price" name="price" required value="{{ $craneOrder->price ?? old('price') }}">
                            </div>

                            <div class="form-group">
                                <label for="price">Վճարվել է</label>
                                @error('paid')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="number" step="any" class="form-control" id="paid" name="paid" required value="{{ $craneOrder->paidList[0]->price ?? old('paid') ?? 0 }}">
                            </div>
                            <div class="form-group">
                                <label for="transfer">
                                    Փոխանցում
                                    <input type="checkbox" style="width: 39px;" name="transfer_type" @if(isset($craneOrder->paidList[0]->type) && $craneOrder->paidList[0]->type == 1) checked @endif value="1" id="transfer" class="form-control">
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="at_driver">
                                    Գումարը Վարորդի Մոտ է
                                    <input type="checkbox" style="width: 39px;" name="at_driver" @if(isset($craneOrder->paidList[0]->at_driver) && $craneOrder->paidList[0]->at_driver == 1) checked @endif value="1" id="at_driver" class="form-control">
                                </label>
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
