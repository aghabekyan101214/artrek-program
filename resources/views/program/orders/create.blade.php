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

                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label for="price">Ընդհանուր գումար</label>
                                    @error('price')
                                    <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                    @enderror
                                    <input type="number" step="any" class="form-control" id="price" name="price" required value="{{ $order->price ?? old('price') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="price">Վճարվել է</label>
                                @error('paid')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="number" step="any" class="form-control" id="paid" name="paid" required value="{{ isset($order) ? ($order->paidList->last()->price ?? 0) : old('paid') ?? 0 }}">
                            </div>
                            <div class="form-group">
                                <label for="transfer">
                                    Փոխանցում
                                    <input type="checkbox" style="width: 39px;" name="transfer_type" @if(isset($order) && isset($order->paidList->last()->type) && $order->paidList->last()->type == 1) checked @endif value="1" id="transfer" class="form-control">
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="due_date">Հանձնման Ժամկետ</label>
                                @error('due_date')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <input type="date" placeholder="YYYY-MM-DD" step="any" class="form-control" id="due_date" name="due_date" required value="{{ $order->due_date ?? old('due_date') }}">
                            </div>

                            <div class="form-group">
                                <label for="comment">Մեկնաբանություն</label>
                                @error('comment')
                                <p class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></p>
                                @enderror
                                <textarea id="comment" class="form-control" name="comment" rows="5">{{ $order->comment ?? '' }}</textarea>
                            </div>

                            <hr>

                            <span class="here">

                            </span>

                            <div class="form-group">
                                <button onclick="add()" type="button" class="btn form-control btn-primary" style="color: white">Ավելացնել Ապրանք <i class="fa fa-plus"></i></button>
                            </div>
                            <p class="calculated-price">Մոտավոր ընդհանուր ինքնարժեք ՝ <span>0</span> դրամ</p>
                            <p class="calculated-laser-price">Մոտավոր լազերի ինքնարժեք ՝ <span>0</span> դրամ</p>
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
        <script src="{{asset('assets/plugins/swal/sweetalert.min.js')}}"></script>
        <script>
            let json = '<?php echo json_encode($materials); ?>';
            let jsonedLaserTypes = '<?php echo json_encode($laserTypes); ?>';
            let materials = JSON.parse(json);
            let laserTypes = JSON.parse(jsonedLaserTypes);
            let count = 0;

            const engravingPrice = Number('{{ $engravingPrice }}');
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
                let className = "group-" + count;
                let html = "<div class='groups "+className+"'>";

                html += "<div class='form-group'>";
                html +=
                    '<label>Ապրանք</label>' +
                    `<select onchange="countPrice()" name="data[${count}][material_id]" class='mat form-control ${id}'>` +
                    '<option value="">Ընտրել Ապրանք</option>'

                materials.forEach(e => {
                    let selected = (e.id == data.material_id) ? "selected" : "";
                    html += `<option ${selected} price="${e.self_price.self_price || 0}" value="${e.id}">${e.name}</option>`
                });
                html += "</select></div>";

                html += "<div class='form-group'>";
                html += '<label>Ապրանքի Օգտագործում</label>' +
                    `<select onchange="disableInputs(), countPrice()" name="data[${count}][order_type]" required class='form-control order_type'>`;
                html += `<option ${order_type == null ? "selected" : ""} value="0">Սովորական</option>`;
                html += `<option ${order_type != null ? "selected" : ""} value="1">Լազեր</option>`;
                html += "</select></div>";

                html += "<div class='form-group laser'>";
                html += '<label>Տեսակ</label>' +
                    `<select name="data[${count}][type]" onchange="countPrice(), disableInputs()" required class='form-control laser-inp laser_type'>`;
                laserTypes.forEach((e, i) => {
                    html += `<option ${data.type == i ? "selected" : ""} value="${i}">${e}</option>`
                });
                html += "</select></div>";

                html += "<div class='form-group laser'>" +
                    '<label>Հաստություն / Րոպե</label>';
                html += `<input type="number" step="any" class="form-control laser-inp thickness" oninput="countPrice()" value="${data.thickness || ''}" id="thickness" name="data[${count}][thickness]" required>`;
                html += "</div>";

                html += "<div class='form-group laser'>" +
                    '<label>Գծամետր</label>';
                html += `<input type="number" step="any" class="form-control laser-inp line_meter" oninput="countPrice()" value="${data.line_meter || 0}" id="line_meter" name="data[${count}][line_meter]" required>`;
                html += "</div>";

                html += "<div class='form-group'>" +
                    '<label><span class="q">Օգտագործված Ապրանքի Քանակ</span></label>';
                html += `<input type="number" step="any" class="form-control quantity-input" oninput="countPrice()" id="price" value="${data.quantity || 0}" name="data[${count}][quantity]" required>`
                if(count) {
                    html += "<div class='form-group text-right' style='margin-top: 20px;'>";
                    html += "<button type='button' class='btn btn-danger' onclick=deleteRow('"+className+"')>-</button>";
                    html += "</div>";
                }
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
                        if($(this).parentsUntil(".here").find(".laser_type").val() != 0) {
                            $(".line_meter").attr("disabled", true);
                        } else {
                            $(".line_meter").attr("disabled", false);
                        }
                    }
                });
            }

            function countPrice() {
                let emptyMaterial = false;
                let calculatedPrice = 0;
                let calculatedLaserPrice = 0;

                $(document).find(".here .groups").each(function () {
                    if($(this).find(".order_type").val() == 0) {
                        // if the order is ordinary

                    } else {
                        if($(this).find(".laser_type").val() == 0) {
                            // Cutting
                            let thickness = $(this).find(".thickness").val();
                            let quantity = $(this).find(".line_meter").val();
                            let price = calculateCuttingPrice(thickness);
                            calculatedLaserPrice += quantity * price;

                        } else if($(this).find(".laser_type").val() == 1) {
                            // Gravirovka
                            let minutes = $(this).find(".thickness").val();
                            calculatedLaserPrice += (minutes * engravingPrice);
                        }
                    }
                    if(!$(this).find(".mat").val()) emptyMaterial = true;
                    let price = $(this).find(".mat option:selected").attr("price");
                    let quantity = $(this).find(".quantity-input").val();
                    if(price !== undefined) {
                        calculatedPrice += (price * quantity);
                    }
                });
                // if(emptyMaterial) calculatedPrice = 0;
                let wholePrice = calculatedPrice + calculatedLaserPrice;
                $(".calculated-price span").html(wholePrice);
                $(".calculated-laser-price span").html(calculatedLaserPrice);
            }

            function calculateCuttingPrice(thickness) {
                let price = 0;
                if(thickness == 0) price = 70;
                else if(thickness > 0 && thickness <= 3) price = 200;
                else if(thickness > 3 && thickness <= 4 ) price = 280;
                else if(thickness > 4 && thickness <= 5 ) price = 350;
                else if(thickness > 5 && thickness <= 6 ) price = 400;
                else if(thickness > 6 && thickness <= 8 ) price = 500;
                else if(thickness > 8 && thickness <= 10 ) price = 700;
                else if(thickness > 10 && thickness <= 15 ) price = 1200;
                else if(thickness > 15) price = 2000;
                return price;
            }

            deleteRow = className => {
                swal({
                    title: "Դուք ցանկանու՞մ եք հեռացնել տվյալ դաշտը։",
                    icon: "warning",
                    dangerMode: true,
                    buttons: ['Համոզված չեմ', 'Այո'],
                }).then((willDelete) => {
                    if (willDelete) {
                        $("." + className).remove();
                    } else {
                        swal.close();
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
                    });
                    countPrice();
                });

            </script>
        @endif
    @endpush
@endsection
