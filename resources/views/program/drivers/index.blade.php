@extends('layouts.app')

@section('content')
   <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">{{$title}}</h3>
                <a data-route="{{ app('router')->getRoutes()->match(app('request')->create($route."/create"))->getName() }}" href="{{$route."/create"}}" class="btn btn-success m-b-30"><i class="fas fa-plus"></i> Ավելացնել {{$title}}</a>

                {{--table--}}
                <div class="table-responsive">
                    <table id="datatable" class="display table table-hover table-striped nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Անուն</th>
                                <th>Կցված Ավտոաշտարակ</th>
                                <th>Հեռախոսահամար</th>
                                <th>Կուտակված Աշխատավարձ</th>
                                <th>Ստեղծող</th>
                                <th>Կարգավորումներ</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($data as $key=>$val)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$val->name}}</td>
                                <td>{{$val->car->name}}</td>
                                <td>{{$val->phone}}</td>
                                <td>{{$val->salary->sum("price") + $val->paidSalary->where('driver_salary_type', \App\Model\PaidOrder::DRIVER_SALARY_COLLECTED)->sum("price") }}</td>
                                <td>{{ isset($val->creator) ? $val->creator->name : 'Բաբկեն Սնապյան'  }}</td>
                                <td>
                                    <a data-route="{{ app('router')->getRoutes()->match(app('request')->create($route."/".$val->id."/edit"))->getName() }}" href="{{$route."/".$val->id."/edit"}}" data-toggle="tooltip"
                                       data-placement="top" title="Փոփոխել" class="btn btn-info btn-circle tooltip-info">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form data-route="drivers.destroy" style="display: inline-block" action="{{ $route."/".$val->id }}"
                                          method="post" id="work-for-form">
                                        @csrf
                                        @method("DELETE")
                                        <a href="javascript:void(0);" data-text="վարորդին" class="delForm" data-id ="{{ $val->id }}">
                                            <button data-toggle="tooltip"
                                                    data-placement="top" title="Հեռացնել"
                                                    class="btn btn-danger btn-circle tooltip-danger"><i
                                                    class="fas fa-trash"></i></button>
                                        </a>
                                    </form>

                                    <button data-toggle="modal" data-target="#exampleModal"
                                            data-route="drivers.pay_salary"
                                       data-placement="top" title="Վճարել Աշխատավարձ" class="btn btn-success btn-circle tooltip-success open-modal" onclick="openModal('{{url($route."/".$val->id."/pay")}}')">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </button>

                                    <a href="{{ $route . "/" . $val->id . "?year=" . Carbon\Carbon::now()->year }}">
                                        <button data-placement="top" title="Տեսնել վճարված աշխատավարձերը" data-toggle="tooltip" class="btn btn-primary btn-circle tooltip-primary"><i class="fa fa-eye"></i></button>
                                    </a>

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
                   <h5 class="modal-title" id="exampleModalLabel">Աշխատավարձի Վճարում</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <form action="" class="pay-form" method="post">
                   <div class="modal-body">
                       @csrf
                       <div class="form-group">
                           <label for="sum">Գումարի Չափ <strong>Կանխիկ</strong></label>
                           <input type="number" step="any" id="sum" name="price_cash" class="form-control">
                       </div>
                       <div class="form-group">
                           <label for="sum">Գումարի Չափ <strong>Փոխանցում</strong></label>
                           <input type="number" step="any" id="sum" name="price_transfer" class="form-control">
                       </div>
                       <div class="form-group">
                           <label for="sum">Ամսաթիվ</label>
                           <select name="month" class="form-control" id="">
                               @foreach($months as $month)
                                   <option @if(Carbon\Carbon::now()->subMonth()->month == $month['index']) selected @endif value="{{ $month['index'] }}">{{ $month['name'] }}</option>
                               @endforeach
                           </select>
                       </div>
                       <div class="form-group">
                           <label for="year">Տարի</label>
                           <select name="year" class="form-control" id="year">
                               <option value="{{ Carbon\Carbon::now()->year - 1 }}">{{ Carbon\Carbon::now()->year - 1 }}</option>
                               <option selected value="{{ Carbon\Carbon::now()->year }}">{{ Carbon\Carbon::now()->year }}</option>
                               <option value="{{ Carbon\Carbon::now()->year + 1 }}">{{ Carbon\Carbon::now()->year + 1 }}</option>
                           </select>
                       </div>
                       <div class="form-group">
                           <label for="driver_salary_fixed">
                               Ֆիքսված աշխատավարձ
                               <input type="checkbox" style="width: 39px;" name="driver_salary_fixed" value="1" id="driver_salary_fixed" class="form-control">
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
        $('#datatable').DataTable();
        openModal = e => $(".pay-form").attr("action", e);

    </script>
@endpush



