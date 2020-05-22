@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">{{$title}}</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form method="post" action="{{ $route."/".$client->id }}" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")

                            <div class="form-group">
                                <label for="client_id">Հաճախորդ</label>
                                @error('client_id')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <select name="client_id" class="form-control select2" id="" required>
                                    <option value="">Ընտրել Հաճախորդ</option>
                                    @foreach($clients as $client)
                                        <option @if(old("client_id") == $client->id) selected @endif value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="price">Գումար</label>
                                @error('price')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="number" step="any" class="form-control" id="price" name="price" required value="{{old('price')}}">
                            </div>

                            <div class="form-group">
                                <label for="price">Վճարվել է</label>
                                @error('paid')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="number" step="any" class="form-control" id="paid" name="paid" required value="{{old('paid') ?? 0}}">
                            </div>
                            <div class="form-group">
                                <label for="transfer">
                                    Փոխանցում
                                    <input type="checkbox" style="width: 39px;" name="transfer_type" value="1" id="transfer" class="form-control">
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="due_date">Հանձնման Ժամկետ</label>
                                @error('due_date')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="date" placeholder="YYYY-MM-DD" step="any" class="form-control" id="due_date" name="due_date" required value="{{old('due_date')}}">
                            </div>

                            <hr>

                            <span class="here">

                            </span>

                            <div class="form-group">
                                <button onclick="add()" type="button" class="btn form-control btn-primary" style="color: white">Ավելացնել Ապրանք <i class="fa fa-plus"></i></button>
                            </div>

                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Պահպանել</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
