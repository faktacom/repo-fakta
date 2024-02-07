@extends('layouts.admin')

@section('title', 'Survey')

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
            <a href="{{route('admin.survey.create')}}" class="btn btn-info d-inline-block m-l-15"><i
                    class="fa fa-plus-circle"></i> Create Survey </a>
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
                            <th style="width: 5%;">No</th>
                            <th style="width: 20%;">Survey Name</th>
                            <th style="width: 20%;">Type</th>
                            <th style="width: 20%;">Start Date</th>
                            <th style="width: 20%;">End Date</th>
                            {{-- <th style="width: 20%;">Anonymous</th>
                            <th style="width: 20%;">Duplicate Email</th> --}}
                            <th style="width:200px;text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list_survey as $item)
                        <tr id="1" class="gradeX">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->survey_name}}</td>
                            @if($item->is_anonymous == 1)
                                <td>Survey</td>
                            @else
                                <td>Polling</td>
                            @endif
                            <td>{{date('Y-m-d, H:i', strtotime($item->survey_start_date))}}</td>
                            <td>{{date('Y-m-d, H:i', strtotime($item->survey_end_date))}}</td>
                            {{-- @if ($item->is_anonymous == 1)
                                <td> Yes </td>
                            @else
                                <td> No </td>    
                            @endif
                            @if ($item->is_duplicate_email == 1)
                                <td> Yes </td>
                            @else
                                <td> No </td>    
                            @endif --}}
                            <td style="text-align:center;">
                                <a href="{{route('admin.survey.detail', $item->survey_id)}}"
                                    class="btn btn-info btn-sm" title="View Detail" ><i class="icon-eye"></i></a>
                                <a href="{{route('admin.survey.edit', $item->survey_id)}}" title="Edit" class="btn btn-warning btn-sm"><i
                                        class="icon-pencil"></i></a>
                                <a href="{{route('admin.survey.allanswer', $item->survey_id)}}" title="View Answer" class="btn btn-warning btn-sm" style="background-color:#0396D7; border-color:#0396D7;"><i
                                        class="icon-bubble"></i></a>
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

@push('js')
<script src="{{asset('assets/node_modules/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script>
    $(document).ready(function () {
        $('#editable-datatable').DataTable();
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