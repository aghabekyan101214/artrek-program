@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">{{$title}}</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form method="post" action="{{ $route }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="name">Անուն</label>
                                @error('name')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}">
                            </div>

                            <div class="form-group">
                                <label for="username">Օգտանուն</label>
                                @error('username')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="username" name="username"
                                       value="{{old('username')}}">
                            </div>

                            <div class="form-group">
                                <label for="password">Գաղտնաբառ</label>
                                @error('password')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="text" class="form-control" id="password" name="password"
                                       value="{{old('password')}}">
                                <button type="button" class="pass btn btn-primary m-t-5">Գեներացնել Գաղտնաբառ</button>
                            </div>

                            <div class="form-group">
                                <label for="allowed_routes">Թույլատրելի հղումներ</label>
                                @error('allowed_routes')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <select style="height: 300px" multiple name="whitelist_routes[]" class="form-control allowed_roles" id="">
                                    @foreach($routes as $r)
                                        <option value="{{ $r['uri'] }}">{{ $r['name'] }}</option>
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

@push('foot')
    <script !src="">
        $(document).ready(function () {
            $('.pass').click(function () {
                var pass = generatePassword();
                $('#password').val(pass);
            });

            function generatePassword() {
                var length = 8,
                    charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
                    retVal = "";
                for (var i = 0, n = charset.length; i < length; ++i) {
                    retVal += charset.charAt(Math.floor(Math.random() * n));
                }
                return retVal;
            }

            $(".allowed_roles").bootstrapDualListbox();
        })
    </script>
@endpush
