@extends('layouts.app')

@section('content')
   <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">{{$title}}</h3>
                <a href="{{$route."/create"}}" class="btn btn-success m-b-30"><i class="fas fa-plus"></i> Գումարի Կառավարում</a>

                {{--table--}}
                <div class="table-responsive">
                    <table id="datatable" class="display table table-hover table-striped nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Գումար</th>
                                <th>Վճարման Եղանակ</th>
                                <th>Ամսաթիվ</th>
                                <th>Մեկնաբանություն</th>
                                <th>Կարգավորումներ</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <select class="form-control sum">
                                        <option value="">Ամբողջը</option>
                                        <option @if($request->sum == -1) selected @endif value="-1"> < 0</option>
                                        <option @if($request->sum == 1) selected @endif value="1"> > 0</option>
                                    </select>
                                </td>
                                <td></td>
                                <td>
                                    <input type="text" autocomplete="off" name="datefilter1" class="form-control date datefilter1" value="{{ !is_null($request->registered_from) ? ($request->registered_from . " - " . $request->registered_to) : '' }}"/>
                                </td>
                                <td>
                                    <select class="form-control type">
                                        <option value="">Ամբողջը</option>
                                        <option @if($request->type == 1) selected @endif value="1"> Միայն Գովազդի Պատվերները</option>
                                        <option @if($request->type == 2) selected @endif value="2"> Միայն Ավտոաշտարակի Պատվերները</option>
                                    </select>
                                </td>
                                <th>
                                    <button class="btn btn-deafult" onclick="search()" style="margin-left: 10px;"><i class="fa fa-search"></i></button>
                                    <a href="{{ $route }}">
                                        <button class="btn btn-success"><i class="fa fa-recycle"></i></button>
                                    </a>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($data as $key => $val)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td><b style="color: @if($val->price > 0) green @else red @endif">{{ $val->price }}</b></td>
                                <td>{{ $val->type == 0 ? "Կանխիկ" : "Փոխանցում" }}</td>
                                <td>{{ $val->created_at }}</td>
                                <td>{{ $val->comment }}</td>
                                <td>
                                    <a href="{{$route."/".$val->id."/edit"}}" data-toggle="tooltip"
                                       data-placement="top" title="Փոփոխել" class="btn btn-info btn-circle tooltip-info">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form style="display: inline-block" action="{{ $route."/".$val->id }}"
                                          method="post" id="work-for-form">
                                        @csrf
                                        @method("DELETE")
                                        <a href="javascript:void(0);" data-text="տողը" class="delForm" data-id ="{{$val->id}}">
                                            <button data-toggle="tooltip"
                                                    data-placement="top" title="Հեռացնել"
                                                    class="btn btn-danger btn-circle tooltip-danger"><i
                                                    class="fas fa-trash"></i></button>
                                        </a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="alert alert-success">Կանխիկ: {{ $cash }}</div>
            <div class="alert alert-success">Փոխանցում: {{ $transfer }}</div>
            <div class="alert alert-success">Ընդհանուր Գումար: {{ $transfer + $cash }}</div>
        </div>
    </div>
@endsection

@push('head')
    <!--This is a datatable style -->
    <link href="{{asset('assets/plugins/datatables/media/css/dataTables.bootstrap.css')}}" rel="stylesheet" type="text/css"/>
    <!-- DateRangePicker css -->
    <link href="{{ asset("assets/plugins/daterangepicker/daterangepicker.css") }}" rel="stylesheet">
    <style>
        .swal-modal {
            width: 660px !important;
        }
    </style>
@endpush

@push('foot')
    <!--Datatable js-->
    <script src="{{asset('assets/plugins/datatables/datatables.min.js')}}"></script>

    <script src="{{asset('assets/plugins/swal/sweetalert.min.js')}}"></script>
    <!-- Plugin JavaScript -->
    <script src="{{ asset("assets/plugins/moment/moment.min.js") }}"></script>
    <!--DateRAngePicker Js-->
    <script src="{{ asset("assets/plugins/daterangepicker/daterangepicker.js") }}"></script>
    <script>
        $('#datatable').DataTable({
            'ordering': false
        });
        $(document).ready(function () {
            $(function() {
                $('input[name="datefilter1"]').daterangepicker({
                    opens: 'right',
                    timePicker: true,
                    autoUpdateInput: false,

                    locale: {
                        format: 'Y-MM-D H:m:s'
                    }
                });


                $('input[name="datefilter1"]').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('Y-MM-D H:m:s') + ' - ' + picker.endDate.format('Y-MM-D H:m:s'));
                });

                $('input[name="datefilter1"]').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });


            });
        });

        function search() {
            let query = "";
            let reg_search = $(".datefilter1").val().split(" - ");
            let url = location.href.split("?")[0];
            var urlParams = new URLSearchParams(window.location.search);
            let sum = $('.sum').val();
            let type = $('.type').val();

            if(!urlParams.has("registered_from")) {
                urlParams.append('registered_from', reg_search[0] || '');
                urlParams.append('registered_to', reg_search[1] || '');
            } else {
                urlParams.set('registered_from', reg_search[0] || '');
                urlParams.set('registered_to', reg_search[1] || '');
            }

            if(!urlParams.has("sum")) {
                urlParams.append('sum', sum || '');
            } else {
                urlParams.set('sum', sum || '');
            }

            if(!urlParams.has("type")) {
                urlParams.append('type', type || '');
            } else {
                urlParams.set('type', type || '');
            }

            let params = urlParams.toString();
            location.href = url + "?" + params;
        }
    </script>
@endpush



