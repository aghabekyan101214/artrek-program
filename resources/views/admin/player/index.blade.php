@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">{{$title}}</h3>
                <a href="{{$route."/create"}}" class="btn btn-success m-b-30"><i class="fas fa-plus"></i> Add Player</a>

                {{--table--}}
                <div class="table-responsive">
                    <table id="datatable" class="display table table-hover table-striped nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Email</th>
                            <th>Full Name</th>
                            <th>Age</th>
                            <th>Image</th>
                            <th>Date Of Birth</th>
                            <th>Phone Number</th>
                            <th>Team</th>
                            <th>Emergency Name</th>
                            <th>Emergency Phone</th>
                            <th>Gender</th>
                            <th>Height</th>
                            <th>Options</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($player as $key=>$val)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$val->email}}</td>
                                <td>{{$val->full_name}}</td>
                                <td>{{\Carbon\Carbon::parse($val->dob)->age}}</td>
                                <td>
                                    <img src="{{asset("uploads/player/$val->image")}}" alt=""
                                         style="width: 150px; height: 150px;" class="container-fluid">
                                </td>
                                <td>{{$val->dob}}</td>
                                <td>{{$val->phone_number}}</td>
                                <td>
                                    @foreach($val->teamPlayers as $bin)
                                        {{$bin->name}}
                                    @endforeach
                                </td>
                                <td>{{$val->emergency_name}}</td>
                                <td>{{$val->emergency_phone}}</td>
                                <td>
                                    @if($val->gender == 1)
                                        Male
                                    @else
                                        Female
                                    @endif
                                </td>
                                <td>{{$val->height}}</td>
                                <td>
                                    <a href="{{$route."/".$val->id}}" data-toggle="tooltip"
                                       data-placement="top" title="Show"
                                       class="btn btn-warning btn-circle tooltip-warning">
                                        <i class="fas fas fa-eye"></i>
                                    </a>

                                    <a href="{{$route."/".$val->id."/edit"}}" data-toggle="tooltip"
                                       data-placement="top" title="Edit" class="btn btn-info btn-circle tooltip-info">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form style="display: inline-block" action="{{ $route."/".$val->id }}"
                                          method="post" id="work-for-form">
                                        @csrf
                                        @method("DELETE")
                                        <a href="javascript:void(0);" class="delForm" data-id ="{{$val->id}}">
                                            <button data-toggle="tooltip"
                                                    data-placement="top" title="Delete"
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

@push('custom-style')
    <style>
        .swal-modal {
            width: 660px !important;
        }
    </style>
@endpush

@push('custom-script')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $('.delForm').on('click', function (event) {
            event.preventDefault();
            var id = $(this).data('id');
            var text = $('.text_'+id).html();

            swal({
                title: "Are you sure you want to delete the player?",
                text: text,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $("#work-for-form").submit();
                } else {
                    swal.close();
                }
            });
        })
    </script>
@endpush

@push('custom-datatable')
    <script>
        $('#datatable').DataTable({
            "ordering": false,
            initComplete: function () {
                this.api().columns([6]).every(function () {
                    var column = this;
                    var select = $('<select style="margin-left: 5px;"><option value="">All</option></select>')
                        .appendTo($(column.header()))
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }
        });
    </script>
@endpush


