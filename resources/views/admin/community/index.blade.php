@extends('layouts.admin')

@section('title', 'Community')

@push('js')
<script src="{{asset('assets/node_modules/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script>
    $(document).ready(function () {
        $('#editable-datatable').DataTable();
    });

</script>

<script>
    $('.btn-terima').on('click', function() {
        var id = $(this).attr("data-pointid");
        var _status = 3;
        $.ajax({
            url: '{{route('admin.news.updateStatus')}}',
            method : 'POST',
            dataType: "json",
            data: {
                id: id,
                status: _status,
                _token: "{{csrf_token()}}"
            },

            beforeSend: function() {
                $("button[data-idbutton="+id+"]").remove();
                $("#statusModal"+id).modal('hide');
            },

            success: function(res) {
                if(res.bool == true) {
                    $("td[id-status="+ id +"]").html("Tayang")
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
    
</script>

@if (Session::has('success'))
<script>
    $(function () {
        $.toast({
            heading: '{{session('success')}}',
            position: 'top-right',
            loaderBg: '#ff6849',
            icon: 'success',
            hideAfter: 3500,
            stack: 6
        });
    });
</script>
@endif

@if (Session::has('invalid'))
<script>
    $(function () {
        $.toast({
            heading: '{{session('invalid')}}',
            position: 'top-right',
            loaderBg: '#de4a58',
            icon: 'error',
            hideAfter: 3500,
            stack: 6
        });
    });
</script>
@endif

@if ($errors->any())
@foreach ($errors->all() as $error)
<script>
    $(function () {
        $.toast({
            heading: '{{$error}}',
            position: 'top-right',
            loaderBg: '#de4a58',
            icon: 'error',
            hideAfter: 3500,
            stack: 6
        });
    });
</script>
@endforeach
@endif

@endpush

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">@yield('title')</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
            <a href="{{route('admin.community.create')}}" class="btn btn-info d-inline-block m-l-15"><i
                    class="fa fa-plus-circle"></i> Create
                @yield('title')</a>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="editable-datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th style="width:180px; text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listCommunity as $item)
                        <tr id="1" class="gradeX">
                            <td>{{$loop->iteration}}</td>
                            <td><img src="{{asset('assets/images/community/'. $item->featured_image)}}" class="img-fluid" width="80">
                            <td>{{$item->community_name}}</td>
                            <td>{{date("d-m-Y",strtotime($item->created_date))}}</td>
                            <td>{{$item->status_type_name}}</td>
                            <td style="text-align:center;">
                                <a href="{{ route('admin.community.edit', $item->community_id) }}" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                </a>
                                @if ($item->status_type_name == "Draft" || $item->status_type_name == "Pending")
                                <button class="btn btn-success btn-sm" data-idbutton="{{$item->community_id}}"data-toggle="modal" data-target="#statusModal{{$item->community_id}}">
                                    <i class="icon-check"></i>
                                </button>
                                @endif
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{$item->community_id}}">
                                    <i class="icon-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <div id="deleteModal{{$item->community_id}}" class="modal" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">Delete @yield('title')</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">×</button>
                                    </div>
                                    <form action="{{route('admin.community.destroy', $item->community_id)}}" method="post">
                                        @csrf
                                        <div class="modal-body text-center">
                                            <h6>Are you sure delete community "{{$item->community_name}}"?</h6>
                                            <button type="button" class="btn btn-success waves-effect"
                                                data-dismiss="modal">No</button>
                                            <button type="submit" class="btn btn-danger waves-effect">Yes</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection