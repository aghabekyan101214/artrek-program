@extends('layouts.app')

@section('content')
   <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">{{$title}}</h3>
                {{--table--}}
                <div class="table-responsive">
{{--                    <table id="datatable" class="display table table-hover table-striped nowrap" cellspacing="0"--}}
{{--                           width="100%">--}}
{{--                        <thead>--}}
{{--                            <tr>--}}
{{--                                <th>Գումար</th>--}}
{{--                                <th>Ամսաթիվ</th>--}}
{{--                                <th>Մեկնաբանություն</th>--}}
{{--                                <th>Կարգավորումներ</th>--}}
{{--                            </tr>--}}
{{--                        </thead>--}}

{{--                        <tbody>--}}
{{--                        @foreach($driver->paidSalary as $key => $val)--}}
{{--                            <tr data-toggle="collapse" data-target="#accordion" class="clickable">--}}
{{--                                <td>{{ $val->price * -1 }}</td>--}}
{{--                                <td>{{ $val->created_at }}</td>--}}
{{--                                <td>{{ $val->comment }}</td>--}}
{{--                                <td>--}}
{{--                                    <form style="display: inline-block" action="{{ "/cashdesk/".$val->id }}"--}}
{{--                                          method="post" id="work-for-form">--}}
{{--                                        @csrf--}}
{{--                                        @method("DELETE")--}}
{{--                                        <input type="hidden" name="back_route" value="{{ $route . "/" . $driver->id }}">--}}
{{--                                        <a href="javascript:void(0);" data-text="գումարը" class="delForm" data-id ="{{ $val->id }}">--}}
{{--                                            <button data-toggle="tooltip"--}}
{{--                                                    data-placement="top" title="Հեռացնել"--}}
{{--                                                    class="btn btn-danger btn-circle tooltip-danger"><i--}}
{{--                                                    class="fas fa-trash"></i></button>--}}
{{--                                        </a>--}}
{{--                                    </form>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Ամիս</td>
                            <td>Վճարված Աշխատավարձ</td>
                        </tr>
                        @foreach($months as $bin => $month)
                            <tr onclick="openMonth('month-{{ $bin }}')" style="cursor: pointer">
                                <td>{{ $month['name'] }}</td>
                                <td><span style="font-size: 16px;" class="badge badge-success ">{{ $driver->paidSalary->where('month', $month['index'])->sum('price') * - 1 }}</span></td>
                            </tr>
                            @foreach($driver->paidSalary->where('month', $month['index']) as $s)
                                <tr class="month-{{ $bin }}" style="display: none">
                                    <td colspan="2" style="background: white; padding: 0 0 0 20px">
                                        <table style="width: 50%; background: white">
                                            <tr style="background: white">
                                                <td style="background: white; width: 50%"><b>{{ $s->price * -1 }}</b>{{ " - " . $s->created_at }}</td>
                                                <td>
                                                    <form style="display: inline-block" action="{{ $route . '/' . $s->id . '/deleteSalary' }}"
                                                          method="post" id="work-for-form">
                                                        @csrf
                                                        @method("DELETE")
                                                        <input type="hidden" name="back_route" value="{{ $route . "/" . $driver->id }}">
                                                        <a href="javascript:void(0);" data-text="գումարը" class="delForm" data-id ="{{ $s->id }}">
                                                            <button data-toggle="tooltip"
                                                                    data-placement="top" title="Հեռացնել"
                                                                    class="btn btn-danger btn-circle tooltip-danger btn-sm"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </a>
                                                    </form>
                                                    <button data-toggle="modal" data-target="#exampleModal"
                                                            data-placement="top" class="btn btn-primary btn-circle btn-sm tooltip-primary open-modal" price="{{ $s->price }}" month="{{ $s->month }}" onclick="openModal('{{ url($route."/".$s->id."/updateGivenSalary") }}', '{{ $s->price * -1 }}', '{{ $s->month }}')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
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
                   <h5 class="modal-title" id="exampleModalLabel">Աշխատավարձի վճարում</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <form action="" class="pay-form" method="post">
                   <div class="modal-body">
                       @csrf
                       <div class="form-group">
                           <label for="sum">Գումարի Չափ</label>
                           <input type="number" min="1" step="any" id="sum" name="price" required class="form-control">
                       </div>
                       <div class="form-group">
                           <label for="sum">Ամսաթիվ</label>
                           <select name="month" class="form-control months" id="">
                               @foreach($months as $month)
                                   <option value="{{ $month['index'] }}">{{ $month['name'] }}</option>
                               @endforeach
                           </select>
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
        openModal = (url, price, month) => {
            $(".pay-form").attr("action", url);
            $("#sum").val(price);
            $('.months').val(month)
            console.log(price)
        }
        openMonth = e => $("." + e).toggle();
    </script>
@endpush



