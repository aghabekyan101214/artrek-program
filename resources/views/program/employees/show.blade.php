@extends('layouts.app')

@section('content')
   <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">{{$title . " " . Request::get("year")}}</h3>
                <div class="row" style="margin: 10px 0;">
                    <div class="year-select-box col-md-4" style="padding: 0">
                        <label for="filter_year">Ընտրել Տարեթիվը</label>
                        <select name="filter_year" onchange="filter_year($(this).val())" class="form-control" id="filter_year">
                            @for($i = 2020; $i <= Carbon\Carbon::now()->year + 1; $i++)
                                <option @if(\Illuminate\Support\Facades\Request::get("year") == $i) selected @endif value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                {{--table--}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Ամիս</td>
                            <td>Վճարված Աշխատավարձ</td>
                        </tr>
                        @foreach($months as $bin => $month)
                            <tr onclick="openMonth('month-{{ $bin }}')" style="cursor: pointer">
                                <td>{{ $month['name'] }}</td>
                                <td><span style="font-size: 16px;" class="badge badge-success ">{{ $employee->salaries->where('month', $month['index'])->where('year', Request::get("year"))->sum('price') * - 1 }}</span></td>
                            </tr>
                            @foreach($employee->salaries->where('month', $month['index'])->where('year', Request::get("year")) as $s)
                                <tr class="month-{{ $bin }}" style="display: none">
                                    <td colspan="2" style="background: white; padding: 0 0 0 20px">
                                        <table style="width: 50%; background: white">
                                            <tr style="background: white">
                                                <td style="background: white; width: 50%"><b>{{ $s->price * -1 }}</b>{{ " - " . $s->created_at . "(" . ($s->paidSalaries->type ? 'Փոխանցում' : 'Կանխիկ') .")" }}</td>
                                                <td>
                                                    <form style="display: inline-block" action="{{ $route . '/' . $s->id . '/deleteSalary' }}"
                                                          method="post" id="work-for-form">
                                                        @csrf
                                                        @method("DELETE")
                                                        <input type="hidden" name="back_route" value="{{ $route . "/" . $employee->id }}">
                                                        <a href="javascript:void(0);" data-text="գումարը" class="delForm" data-id ="{{ $s->id }}">
                                                            <button data-toggle="tooltip"
                                                                    data-placement="top" title="Հեռացնել"
                                                                    class="btn btn-danger btn-circle tooltip-danger btn-sm"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </a>
                                                    </form>
                                                    <button data-toggle="modal" data-target="#exampleModal"
                                                            data-placement="top" class="btn btn-primary btn-circle btn-sm tooltip-primary open-modal" price="{{ $s->price }}" month="{{ $s->month }}" onclick="openModal('{{ url($route."/".$s->id."/updateGivenSalary") }}', '{{ $s->price * -1 }}', '{{ $s->month }}', {{ $s->year }}, '{{ $s->paidSalaries->type }}')">
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
                       <div class="form-group">
                           <label for="year">Տարի</label>
                           <select name="year" class="form-control years" id="year">
                               <option value="{{ Carbon\Carbon::now()->year - 1 }}">{{ Carbon\Carbon::now()->year - 1 }}</option>
                               <option selected value="{{ Carbon\Carbon::now()->year }}">{{ Carbon\Carbon::now()->year }}</option>
                               <option value="{{ Carbon\Carbon::now()->year + 1 }}">{{ Carbon\Carbon::now()->year + 1 }}</option>
                           </select>
                       </div>
                       <div class="form-group">
                           <label for="transfer">
                               Փոխանցում
                               <input type="checkbox" style="width: 39px;" name="transfer_type" id="transfer" class="form-control">
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
        openModal = (url, price, month, year, transfer_type) => {
            $(".pay-form").attr("action", url);
            $("#sum").val(price);
            $('.months').val(month);
            $('.years').val(year);
            if(transfer_type != 0) $("#transfer").prop('checked', true);
            else $("#transfer").prop('checked', false);
        }

        openMonth = e => $("." + e).toggle();
        filter_year = year => {
            var urlParams = new URLSearchParams(window.location.search);
            if(urlParams.has("year")) urlParams.set('year', year);
            let url = location.href.split("?")[0];
            let params = urlParams.toString();
            location.href = url + "?" + params;
        }
    </script>
@endpush



