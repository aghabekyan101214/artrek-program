@extends('layouts.app')

@section('content')
   <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">{{$title}}</h3>
                <a data-route="{{ app('router')->getRoutes()->match(app('request')->create($route."/create"))->getName() }}" href="{{$route."/create"}}" class="btn btn-success m-b-30"><i class="fas fa-plus"></i> Ավելացնել {{$title}}</a>

                {{--table--}}
                <div class="table-responsive">
                    <table id="datatable" class="display table table-hover table-striped nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Անուն</th>
                                <th>Ծախսեր</th>
                                <th>Կարգավորումներ</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <select name="" class="category form-control select2" id="">
                                        <option value="">Ընտրել</option>
                                        @foreach($categoriesAll as $c)
                                            <option @if($request->category == $c->id) selected @endif value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" autocomplete="off" name="datefilter1" class="form-control date datefilter1" value="{{ !is_null($request->registered_from) ? ($request->registered_from . " - " . $request->registered_to) : '' }}"/>
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
                        @foreach($data as $key=>$val)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$val->name}}</td>
                                <td>
                                    <ul>
                                        @foreach($val->spendings as $spending)
                                            <li>{{ $spending->price . " || " . $spending->created_at }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <a data-route="{{ app('router')->getRoutes()->match(app('request')->create($route."/".$val->id."/edit"))->getName() }}" href="{{$route."/".$val->id."/edit"}}" data-toggle="tooltip"
                                       data-placement="top" title="Փոփոխել" class="btn btn-info btn-circle tooltip-info">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form data-route="spendings.destroy" style="display: inline-block" action="{{ $route."/".$val->id }}"
                                          method="post" id="work-for-form">
                                        @csrf
                                        @method("DELETE")
                                        <a href="javascript:void(0);" data-text="կատեգորիան" class="delForm" data-id ="{{$val->id}}">
                                            <button data-toggle="tooltip"
                                                    data-placement="top" title="Հեռացնել"
                                                    class="btn btn-danger btn-circle tooltip-danger"><i
                                                    class="fas fa-trash"></i></button>
                                        </a>
                                    </form>

                                    <button data-toggle="modal" data-target="#exampleModal"
                                            data-route="spendings.pay"
                                       data-placement="top" title="Ավելացնել Ծախս" class="btn btn-success btn-circle tooltip-success open-modal" onclick="openModal('{{url($route."/".$val->id."/pay")}}')">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </button>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

   <!-- Modal -->
   <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="exampleModalLabel">Ավելացնել Ծախս</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <form action="" class="pay-form" method="post">
                   <div class="modal-body">
                       @csrf
                       <div class="form-group">
                           <label for="sum">Գումարի Չափ</label>
                           <input type="number" required step="any" id="sum" name="price" class="form-control">
                       </div>
                       <div class="form-group">
                           <label for="transfer">
                               Փոխանցում
                               <input type="checkbox" style="width: 39px;" name="transfer_type" @if(isset($craneOrder->paidList[0]->type) && $craneOrder->paidList[0]->type == 1) checked @endif value="1" id="transfer" class="form-control">
                           </label>
                       </div>
                       <div class="form-group">
                           <label for="comment">Մեկնաբանություն</label>
                           <textarea name="comment" id="comment" class="form-control" cols="30" rows="5"></textarea>
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
            'ordering': false
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
            let category = $(".category").val();
            var urlParams = new URLSearchParams(window.location.search);

            if(!urlParams.has("registered_from")) {
                urlParams.append('registered_from', reg_search[0] || '');
                urlParams.append('registered_to', reg_search[1] || '');
            } else {
                urlParams.set('registered_from', reg_search[0] || '');
                urlParams.set('registered_to', reg_search[1] || '');
            }

            if(category) {
                if(!urlParams.has("category")) {
                    urlParams.append('category', category || '');
                } else {
                    urlParams.set('category', category || '');
                }
            } else {
                urlParams.delete('category');
            }

            let params = urlParams.toString();
            location.href = url + "?" + params;
        }
    </script>
@endpush



