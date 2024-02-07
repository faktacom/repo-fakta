@extends('layouts.admin')

@section('title', 'Edit UAC')

@push('js')
<script src="{{asset('assets/node_modules/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script>
    $(document).ready(function () {
        $('#editable-datatable').DataTable();
    });

</script>
<script>
    $('.btn-grant').on('click', function() {
        var id = $(this).attr("data-pointid");
        $.ajax({
            url: '{{route('admin.uac.update')}}',
            method : 'POST',
            dataType: "json",
            data: {
                roleId: "{{$role_id}}",
                actionId: id,
                status: "grant",
                _token: "{{csrf_token()}}"
            },

            success: function(res) {
                if(res.bool == true) {
                    location.reload();
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    $('.btn-revoke').on('click', function() {
        var id = $(this).attr("data-pointid");
        $.ajax({
            url: '{{route('admin.uac.update')}}',
            method : 'POST',
            dataType: "json",
            data: {
                roleId: "{{$role_id}}",
                actionId: id,
                status: "revoke",
                _token: "{{csrf_token()}}"
            },

            success: function(res) {
                if(res.bool == true) {
                    location.reload();
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
    
</script>

@if (Session::has('error'))
<script>
    $(function () {
        $.toast({
            heading: '{{session('error')}}',
            position: 'top-right',
            loaderBg: '#ff6849',
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
                <li class="breadcrumb-item"><a href="{{route('admin.user.index')}}">User</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.uac')}}">User Access Control</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
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
                            <th>Code</th>
                            <th>Action</th>
                            <th>Authorized Date</th>
                            <th>Authorized By</th>
                            <th style="width:180px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail_role_action as $item)
                        <tr id="1" class="gradeX">
                            <td>{{$item->action_code}}</td>
                            <td>{{$item->action_name}}</td>
                            <td>
                                @if ($item->access_id)
                                {{$item->authorized_date}}
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                @if ($item->access_id)
                                {{$item->authorizer_name}}
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                @if ($item->access_id)
                                <button class="btn btn-danger btn-revoke" data-pointid="{{$item->action_id}}">
                                    Revoke Access
                                </button>
                                @else
                                <button class="btn btn-success btn-grant" data-pointid="{{$item->action_id}}">
                                    Grant Access
                                </button>
                                @endif
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