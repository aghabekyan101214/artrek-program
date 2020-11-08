@extends('layouts.app')

@section('content')
   <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">{{$title}}</h3>
                <a href="javascript:void(0)" data-toggle="modal" data-target="#spendingOrder" onclick="openModal('{{ $route . "/" . $order->id . "/addSpending" }}')" class="btn btn-success m-b-30"><i class="fas fa-plus"></i> Ավելացնել {{$title}}</a>

                {{--table--}}
                <div class="table-responsive">
                    <table id="datatable" class="display table table-hover table-striped nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                            <tr>
                                <td>Անվանում</td>
                                <td>Ընդհանուր գումար</td>
                                <td>Վճարվել է</td>
                                <td>Պարտք</td>
                                <td>Գործիքներ</td>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($spendings as $s)
                                <tr>
                                    <td>{{ $s->title }}</td>
                                    <td>{{ $s->price }}</td>
                                    <td>
                                        Ընդ․ ՝ {{ -$s->paidList->sum('price') }}
                                        <ul>
                                            @foreach($s->paidList as $list)
                                                <li>
                                                    <small>{{ $list->price . " - " . $list->created_at }}</small>
                                                    <form style="display: inline-block" action="{{ $route."/destroyPayment/".$list->paidOrder->id }}"
                                                          method="post" id="work-for-form">
                                                        @csrf
                                                        @method("DELETE")
                                                        <a href="javascript:void(0);" data-text="պատվերը" class="delForm" data-id ="{{$list->paidOrder->id}}">
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
                                    <td>
                                        {{ $s->price + $s->paidList->sum('price')}}
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#spendingOrder" onclick="openModal('{{ $route."/$s->id/editSpending/" }}', '{{ $s->title }}', '{{ $s->price }}', 'Փոփոխել')" class="btn btn-info btn-circle tooltip-info">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form style="display: inline-block" action="{{ $route."/$s->id/deleteSpending" }}"
                                              method="post" id="work-for-form">
                                            @csrf
                                            @method("DELETE")
                                            <a href="javascript:void(0);" data-text="պատվերը" class="delForm" data-id ="{{ $s->id }}">
                                                <button data-toggle="tooltip"
                                                        data-placement="top" title="Հեռացնել"
                                                        class="btn btn-danger btn-circle tooltip-danger"><i
                                                        class="fas fa-trash"></i></button>
                                            </a>
                                        </form>
                                        <button data-toggle="modal" onclick="openPayModal('{{ $route . "/$s->id/paySpending" }}')" data-target="#addPrice" data-placement="top" class="btn btn-success btn-circle tooltip-success open-modal"><i class="fas fa-money-bill-alt"></i></button>
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
   <div class="modal fade" id="spendingOrder" tabindex="-1" role="dialog" aria-labelledby="spendingOrderLabel" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="spendingOrderLabel"><b>Այլ Ծախս <span class="button"></span></b></h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <form action="" class="spending-pay-form" method="post">
                   <div class="modal-body">
                       @csrf
                       <div class="form-group">
                           <label for="title">Այլ ծախսի անվանում</label>
                           <input type="text" name="title" id="title" class="form-control" required>
                       </div>

                       <div class="form-group">
                           <label for="price">Ընդհանուր Գումար</label>
                           <input type="number" step="any" id="price" name="price" required class="form-control">
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button class="btn btn-primary button"></button>
                   </div>
               </form>
           </div>
       </div>
   </div>


   <!-- Modal -->
   <div class="modal fade" id="addPrice" tabindex="-1" role="dialog" aria-labelledby="addPriceLabel" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="addPriceLabel"><b>Այլ Ծախս</b></h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <form action="" class="add-pay-form" method="post">
                   <div class="modal-body">
                       @csrf
                       <div class="form-group">
                           <label for="price">Գումար</label>
                           <input type="number" step="any" id="price" name="price" required class="form-control">
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button class="btn btn-primary button">Ավելացնել</button>
                   </div>
               </form>
           </div>
       </div>
   </div>
@endsection

@push('head')
    <!--This is a datatable style -->
    <link href="{{asset('assets/plugins/datatables/media/css/dataTables.bootstrap.css')}}" rel="stylesheet"
          type="text/css"/>

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
    <script>
        $('#datatable').DataTable({
            "order": false
        });
        openModal = (url, title = null, price = null, button="Ավելացնել") => {
            $(".spending-pay-form").attr("action", url);
            $("#title").val(title);
            $("#price").val(price);
            $(".button").html(button)
        }

        openPayModal = url => {
            $(".add-pay-form").attr("action", url)
        }
    </script>
@endpush



