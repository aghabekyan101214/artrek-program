@extends('layouts.app')

@section('content')
    <style>
        .badge-danger{
            font-size: 16px;
        }
    </style>
   <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">{{$title}}</h3>
                <a href="{{$route."/create"}}" class="btn btn-success m-b-30"><i class="fas fa-plus"></i> Ավելացնել {{ $title }}</a>

                {{--table--}}
                <div class="table-responsive">
                    <table id="datatable" class="display table table-hover table-striped nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                            <tr>
                                <th>Գրանցման Ամսաթիվ</th>
                                <th>Հաճախորդ</th>
                                <th>Վարորդ</th>
                                <th>Ընդհանուր Գումար</th>
                                <th>Վճարվել է</th>
                                <th>Պարտք</th>
                                <th>Վարորդի Մոտ</th>
                                <th>Կարգավորումներ</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" autocomplete="off" name="datefilter1" class="form-control date datefilter1" value="{{ !is_null($request->registered_from) ? ($request->registered_from . " - " . $request->registered_to) : '' }}"/>
                                </td>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>
                                    <button class="btn btn-deafult" onclick="search()" style="margin-left: 10px;"><i class="fa fa-search"></i></button>
                                    <a href="{{ $route }}">
                                        <button class="btn btn-success"><i class="fa fa-recycle"></i></button>
                                    </a>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                        @php
                            $wholeDoubt = 0;
                            $wholeSum = 0;
                        @endphp
                        @foreach($data as $key=>$val)
                            @php
                                $wholeDoubt += ($val->paidList->sum("price") - intval($val->price)) * -1;
                                $wholeSum += intval($val->price);
                            @endphp
                            <tr>
                                <td>{{ $val->created_at }}</td>
                                <td>{{ $val->client->name }}</td>
                                <td>{{ $val->driver->name }}</td>
                                <td>{{  intval($val->price) }}</td>
                                <td>
                                    <p>Ընդ․ ՝ {{ $val->paidList->sum("price") }}</p>
                                    <ul>
                                        @foreach($val->paidList as $list)
                                            <li><small>{{ intval($list->price) . " - " . $list->created_at->format('Y-m-d'). " " . ($list->at_driver == 1 ? "(Վարորդի Մոտ)" : "") }}</small>
                                                @if($list->at_driver)
                                                    <form style="display: inline-block; margin-left: 5px;" action="{{$route."/take-from-driver/$list->id"}}" method="post">
                                                        @csrf
                                                        <button data-toggle="tooltip" data-placement="top" title="Գումարն արդեն ինձ մոտ է" class="btn btn-success btn-circle tooltip-success"><i class="fa fa-money-bill-alt"></i></button>
                                                    </form>
                                                @endif
                                                <form style="display: inline-block; margin-left: 5px;" action="{{ $route."/destroyPayment/".$list->id }}" method="post" id="work-for-form">
                                                    @csrf
                                                    @method("DELETE")
                                                    <a href="javascript:void(0);" data-text="տողը" class="delForm" data-id="{{$list->id}}">
                                                        <button data-toggle="tooltip"
                                                                data-placement="top" title="Հեռացնել"
                                                                class="btn btn-danger btn-circle tooltip-danger"><i
                                                                class="fas fa-trash"></i></button>
                                                    </a>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>

                                </td>
                                @php
                                  $doubt = ($val->paidList->sum("price") - intval($val->price)) * -1;
                                @endphp
                                <td>
                                    @if($doubt > 0)
                                        <span class="badge badge-danger">{{ $doubt }}</span>
                                    @else
                                        <span class="badge badge-success">{{ 0 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <?php $sum = 0; ?>
                                    @foreach($val->paidList as $p)
                                        <?php if($p->at_driver == 1) $sum += $p->price; ?>
                                    @endforeach
                                    {{ $sum }}
                                </td>
                                <td>
                                    <a href="{{$route."/".$val->id."/edit"}}" data-toggle="tooltip"
                                       data-placement="top" title="Փոփոխել" class="btn btn-info btn-circle tooltip-info">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form style="display: inline-block" action="{{ $route."/".$val->id }}"
                                          method="post" id="work-for-form">
                                        @csrf
                                        @method("DELETE")
                                        <a href="javascript:void(0);" data-text="պատվերը" class="delForm" data-id ="{{$val->id}}">
                                            <button data-toggle="tooltip"
                                                    data-placement="top" title="Հեռացնել"
                                                    class="btn btn-danger btn-circle tooltip-danger"><i
                                                    class="fas fa-trash"></i></button>
                                        </a>
                                    </form>

                                    <button data-toggle="modal" data-target="#exampleModal"
                                            data-placement="top" class="btn btn-success btn-circle tooltip-success open-modal" onclick="openModal('{{url($route."/".$val->id."/pay")}}')">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </button>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="alert alert-success">Պատվերների Համագումար: {{ $wholeSum }} Որից Վճարված է։ {{ $wholeSum - $wholeDoubt }}</div>
            <div class="alert alert-danger">Պարտք: {{ $wholeDoubt }}</div>
        </div>
    </div>

   <!-- Modal -->
   <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="exampleModalLabel">Ավտոաշտարակի Մնացորդ Գումարի Վճարում</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <form action="" class="pay-form" method="post">
                   <div class="modal-body">
                       @csrf
                       <div class="form-group">
                           <label for="sum">Գումարի Չափ</label>
                           <input type="number" step="any" id="sum" name="price" class="form-control">
                       </div>
                       <div class="form-group">
                           <label for="at_driver">
                               Գումարը Վարորդի Մոտ է
                               <input type="checkbox" style="width: 39px;" name="at_driver" @if(isset($craneOrder->paidList[0]->at_driver) && $craneOrder->paidList[0]->at_driver == 1) checked @endif value="1" id="at_driver" class="form-control">
                           </label>
                       </div>
                       <div class="form-group">
                           <label for="transfer">
                               Փոխանցում
                               <input type="checkbox" style="width: 39px;" name="transfer_type" @if(isset($craneOrder->paidList[0]->type) && $craneOrder->paidList[0]->type == 1) checked @endif value="1" id="transfer" class="form-control">
                           </label>
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button class="btn btn-primary">Վճարել</button>
                   </div>
               </form>
           </div>
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
            "ordering": false
        });
        openModal = e => $(".pay-form").attr("action", e);
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

            if(!urlParams.has("registered_from")) {
                urlParams.append('registered_from', reg_search[0] || '');
                urlParams.append('registered_to', reg_search[1] || '');
            } else {
                urlParams.set('registered_from', reg_search[0] || '');
                urlParams.set('registered_to', reg_search[1] || '');
            }

            let params = urlParams.toString();
            location.href = url + "?" + params;
        }
    </script>
@endpush



