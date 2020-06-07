@extends('layouts.app')

@section('content')
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
                                <th>#</th>
                                <th>Հաճախորդ</th>
                                <th>Վարորդ</th>
                                <th>Ընդհանուր Գումար</th>
                                <th>Վճարվել է</th>
                                <th>Վարորդի Մոտ</th>
                                <th>Կարգավորումներ</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($data as $key=>$val)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$val->client->name}}</td>
                                <td>{{$val->driver->name}}</td>
                                <td>{{ intval($val->price) }}</td>
                                <td>
                                    <p>Ընդ․ ՝ {{ $val->paidList->sum("price") }}</p>
                                    <ul>
                                        @foreach($val->paidList as $list)
                                            <li style="display: flex;justify-content: space-between; padding: 5px 0;"><small>{{ intval($list->price) . " - " . $list->created_at->format('Y-m-d'). " " . ($list->at_driver == 1 ? "(Վարորդի Մոտ)" : "") }}</small>
                                                @if($list->at_driver)
                                                    <form action="{{$route."/take-from-driver/$list->id"}}" method="post">
                                                        @csrf
                                                        <button style="float: right" data-toggle="tooltip" data-placement="top" title="Գումարն արդեն ինձ մոտ է" class="btn btn-success btn-circle tooltip-success"><i class="fa fa-money-bill-alt"></i></button>
                                                    </form>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>

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
                                        <a href="javascript:void(0);" data-text="հաճախորդին" class="delForm" data-id ="{{$val->id}}">
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



