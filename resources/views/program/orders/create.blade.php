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
                        <form method="post" action="{{ $route .(isset($order->id) ? "/$order->id" : "") }}" enctype="multipart/form-data">
                            @csrf
                            @if(isset($order->id))
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
                                        <option @if(old("client_id") == $client->id || (isset($order->id) && $order->client_id == $client->id) ) selected @endif value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="price">Գումար</label>
                                @error('price')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="number" step="any" class="form-control" id="price" name="price" required value="{{ $order->price ?? old('price') }}">
                            </div>

                            <div class="form-group">
                                <label for="price">Վճարվել է</label>
                                @error('paid')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="number" step="any" class="form-control" id="paid" name="paid" required value="{{ $order->paidList[0]->price ?? old('paid') ?? 0 }}">
                            </div>
                            <div class="form-group">
                                <label for="transfer">
                                    Փոխանցում
                                    <input type="checkbox" style="width: 39px;" name="transfer_type" @if(isset($order->paidList[0]->type) && $order->paidList[0]->type == 1) checked @endif value="1" id="transfer" class="form-control">
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="due_date">Հանձնման Ժամկետ</label>
                                @error('due_date')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="date" placeholder="YYYY-MM-DD" step="any" class="form-control" id="due_date" name="due_date" required value="{{ $order->due_date ?? old('due_date') }}">
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

    @push('head')
        <link rel="stylesheet" href="{{ asset("assets/plugins/select2/dist/css/select2.css") }}">
        <link rel="stylesheet" href="{{ asset("assets/plugins/datepicker/bootstrap-datepicker.min.css") }}">
    @endpush
    @push('foot')
        <script src="{{ asset("assets/plugins/select2/dist/js/select2.js") }}"></script>
        <script src="{{ asset("assets/plugins/datepicker/bootstrap-datepicker.min.js") }}"></script>
        <script>
            let json = '<?php echo json_encode($materials); ?>';
            let jsonedLaserTypes = '<?php echo json_encode($laserTypes); ?>';
            let materials = JSON.parse(json);
            let laserTypes = JSON.parse(jsonedLaserTypes);
            let count = 0;
            $(document).ready(function () {
                $(".select2").select2();
                @if(!isset($order))
                add();
                @endif
                let a = $('#due_date').datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                });
            });

            function add(data = {}) {
                let id = `num${count}`;
                let order_type = data.type >= 0 ? data.type : null;
                console.log(order_type)
                let html = "<div>";

                html += "<div class='form-group'>";
                html +=
                    '<label>Ապրանք</label>' +
                    `<select name="data[${count}][material_id]" required class='form-control ${id}'>` +
                    '<option value="">Ընտրել Ապրանք</option>'

                materials.forEach(e => {
                    let selected = (e.id == data.material_id) ? "selected" : "";
                    html += `<option ${selected} value="${e.id}">${e.name}</option>`
                });

                html += "</select></div>";

                html += "<div class='form-group'>";
                html += '<label>Ապրանքի Օգտագործում</label>' +
                    `<select onchange="disableInputs()" name="data[${count}][order_type]" required class='form-control order_type'>`;
                html += `<option ${order_type == null ? "selected" : ""} value="0">Սովորական</option>`;
                html += `<option ${order_type != null ? "selected" : ""} value="1">Լազեր</option>`;
                html += "</select></div>";

                html += "<div class='form-group laser'>";
                html += '<label>Տեսակ</label>' +
                    `<select name="data[${count}][type]" required class='form-control laser-inp laser_type'>`;
                laserTypes.forEach((e, i) => {
                    html += `<option ${data.type == i ? "selected" : ""} value="${i}">${e}</option>`
                });
                html += "</select></div>";

                html += "<div class='form-group laser'>" +
                    '<label>Հաստություն / Րոպե</label>';
                html += `<input type="number" step="any" class="form-control laser-inp" value="${data.thickness}" id="thickness" name="data[${count}][thickness]" required>`;
                html += "</div>";
                html += "<div class='form-group'>" +
                    '<label><span class="q">Քանակ</span></label>';
                html += `<input type="number" step="any" class="form-control" id="price" value="${data.quantity}" name="data[${count}][quantity]" required>`
                html += "</div><hr>";
                html += "</div>";

                $(".here").append(html);
                $(`.${id}`).select2();
                count ++;
                disableInputs();
            }

            let disableInputs = () => {
                $(document).find(".order_type").each(function(e){
                    if($(this).val() == 0) {
                        $(this).parentsUntil(".here").find(".laser-inp").attr("disabled", true);
                    } else {
                        $(this).parentsUntil(".here").find(".laser-inp").attr("disabled", false);
                    }
                });
            }

        </script>
        @if(isset($order))
            <script>
                $(document).ready(function(){
                    let laserList = JSON.parse('<?php echo json_encode($order->laserList); ?>');
                    let orderList = JSON.parse('<?php echo json_encode($order->orderList); ?>');
                    orderList.forEach(e => {
                        add(e)
                    });
                    laserList.forEach(e => {
                        add(e)
                    })
                })

            </script>
        @endif
    @endpush
@endsection
