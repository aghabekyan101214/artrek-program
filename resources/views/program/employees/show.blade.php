@extends('layouts.app')

@section('content')
   <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">{{$title}}</h3>
                {{--table--}}
                <div class="table-responsive">
                    <table id="datatable" class="display table table-hover table-striped nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                            <tr>
                                <th>Գումար</th>
                                <th>Ամսաթիվ</th>
                                <th>Մեկնաբանություն</th>
                                <th>Կարգավորումներ</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($employee->salaries as $key => $val)
                            <tr>
                                <td>{{ $val->price * -1 }}</td>
                                <td>{{ $val->created_at }}</td>
                                <td>{{ $val->comment }}</td>
                                <td>
                                    <form style="display: inline-block" action="{{ $route . '/' . $val->id . '/deleteSalary' }}"
                                          method="post" id="work-for-form">
                                        @csrf
                                        @method("DELETE")
                                        <input type="hidden" name="back_route" value="{{ $route . "/" . $employee->id }}">
                                        <a href="javascript:void(0);" data-text="գումարը" class="delForm" data-id ="{{ $val->id }}">
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
        openModal = e => $(".pay-form").attr("action", e);

    </script>
@endpush



