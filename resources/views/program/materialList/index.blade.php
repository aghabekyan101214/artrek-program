@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">{{$title}}</h3>
                <a data-route="{{ app('router')->getRoutes()->match(app('request')->create($route."/create"))->getName() }}" href="{{$route."/create"}}" class="btn btn-success m-b-30"><i class="fas fa-plus"></i> {{$title}}</a>

                {{--table--}}
                <div class="table-responsive">
                    <table id="datatable" class="display table table-hover table-striped nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Նյութի Անուն</th>
                            <th>Չափման Միավոր</th>
                            <th>Մնացորդ</th>
                            <th>Ինքնարժեք</th>
                            <th>Ստեղծող</th>
                            <th>Կարգավորումներ</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($data as $key => $val)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $val->material->name }}</td>
                                <td>{{ $units[$val->material->unit] }}</td>
{{--                                <td>{{ $val->quantity->sum("quantity") - $val->used->sum("quantity") }}</td>--}}
                                <td>{{ $val->quantity }}</td>
                                <td>{{ $val->self_price }}</td>
                                <td>{{ isset($val->creator) ? $val->creator->name : 'Բաբկեն Սնապյան'  }}</td>
                                <td>
                                    <a data-route="{{ app('router')->getRoutes()->match(app('request')->create($route."/".$val->id."/edit"))->getName() }}" href="{{$route."/".$val->id."/edit"}}" data-toggle="tooltip"
                                       data-placement="top" title="Փոփոխել" class="btn btn-info btn-circle tooltip-info">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form data-route="material-list.destroy" style="display: inline-block" action="{{ $route."/".$val->id }}"
                                          method="post" id="work-for-form">
                                        @csrf
                                        @method("DELETE")
                                        <a href="javascript:void(0);" data-text="մուտքը" class="delForm" data-id ="{{$val->id}}">
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
        $('#datatable').DataTable();
    </script>
@endpush



