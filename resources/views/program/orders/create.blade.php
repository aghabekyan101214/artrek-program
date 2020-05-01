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
                                <label for="due_date">Հանձնման Ժամկետ</label>
                                @error('due_date')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="date" placeholder="YYYY-MM-DD" step="any" class="form-control" id="due_date" name="due_date" required value="{{old('due_date')}}">
                            </div>

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

    @push('head')
        <link rel="stylesheet" href="{{ asset("assets/plugins/select2/dist/css/select2.css") }}">
        <link rel="stylesheet" href="{{ asset("assets/plugins/datepicker/bootstrap-datepicker.min.css") }}">
    @endpush
    @push('foot')
        <script src="{{ asset("assets/plugins/select2/dist/js/select2.js") }}"></script>
        <script src="{{ asset("assets/plugins/datepicker/bootstrap-datepicker.min.js") }}"></script>
        <script>
            let json = '<?php echo json_encode($materials); ?>';
            let materials = JSON.parse(json);
            let count = 0;
            $(document).ready(function () {
                $(".select2").select2();
                add();
                let a = $('#due_date').datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                });
            });

            function add() {
                let id = `num${count}`;
                let html = "<div class='form-group'>" +
                    '<label>Ապրանք</label>' +
                    `<select name="data[${count}][material_id]" required class='form-control ${id}'>` +
                    '<option value="">Ընտրել Ապրանք</option>'

                materials.forEach(e => {
                    html += `<option value="${e.id}">${e.name}</option>`
                });

                html += "</select></div>";

                html += "<div class='form-group'>" +
                    '<label>Քանակ</label>';
                html += `<input type="number" step="any" class="form-control" id="price" name="data[${count}][quantity]" required>`
                html += "</div>";
                $(".here").append(html);
                $(`.${id}`).select2();
                count ++;
            }
        </script>
    @endpush
@endsection
