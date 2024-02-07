@extends('layouts.admin')

@section('title', "Survey Respond")

@section('content')
<div class="row page-titles">
    <div class="col-md-12 align-self-center">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.survey.index')}}">Survey</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.survey.allanswer', $survey_id)}}">All Answer</a></li>
                <li class="breadcrumb-item active">Survey Responden</li>
            </ol>

            @if (isset($list_survey_respond) && count($list_survey_respond) > 0)
                <a href="{{route('admin.survey.respond.print', $list_survey_respond[0]->survey_id)}}" class="btn btn-success d-inline-block m-l-15">
                    <i class="fa fa-arrow-circle-down"></i> Download Excel
                </a>
            @endif
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
                            <th style="width: 20%;">Created Date</th>
                            <th style="width: 20%;">Responden</th>
                            <th style="width: 20%;">Email</th>
                            <th style="width: 20%;">Phone</th>
                            <th style="width: 20%;">Gender</th>
                            <th style="width: 20%;">Job</th>
                            <th style="width: 20%;">Birth Date</th>
                            <th style="width: 20%;">Province</th>
                            <th style="width: 20%;">City</th>
                            <th style="width:200px;text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list_survey_respond as $item)
                        <tr id="1" class="gradeX">
                            <td>{{$loop->iteration}}</td>
                            <td>{{date('Y-m-d, H:i', strtotime($item->created_date))}}</td>
                            <td>{{$item->name}} {{empty($item->user_id)?" - (Visitor)":""}}</td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->phone}}</td>
                            <td>{{$item->gender}}</td>
                            <td>{{$item->job}}</td>
                            <td>{{date('Y-m-d, H:i', strtotime($item->birth_date))}}</td>
                            <td>{{$item->province_name}}</td>
                            <td>{{$item->city_name}}</td>
                            <td style="text-align:center;">
                                <a href="{{route('admin.survey.answer', $item->user_survey_id)}}"
                                    class="btn btn-info btn-sm" title="View Answer" ><i class="icon-eye"></i></a>
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