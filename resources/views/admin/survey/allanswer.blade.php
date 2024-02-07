@extends('layouts.admin')

@section('title', "All Answer")

@section('content')
<div class="row page-titles">
    <div class="col-md-12 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.survey.index')}}">Survey</a></li>
            <li class="breadcrumb-item active">All Answer</li>
        </ol>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-lg-4">
                    <a href="{{route('admin.survey.respond', $survey_id)}}" class="btn btn-info d-inline-block">
                        <i class="icon-eye"></i> View Responden
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="editable-datatable">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 20%;">Question</th>
                            <th style="width: 20%;">Question Type</th>
                            <th style="width: 20%;">Answer</th>
                        </tr>
                    </thead>
                    <tbody id="questionAnswerContainer">
                        @foreach ($list_question_answer as $item)
                        <tr id="1" class="gradeX">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->question}}</td>
                            <td>{{$item->question_type_name}}</td>
                            @if (!empty($item->answer_list))
                            <td>
                                <ol>
                                @foreach ($item->answer_list as $answer )
                                    <li>{{$answer->answer}} ({{$answer->count_answer}} answer)</li>
                                @endforeach    
                                </ol>
                            </td>
                            @else
                                <td> - </td>
                            @endif
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
<script>
    $(function () {
        $(".category").select2({
            placeholder: "Select a question",
            allowClear: true
        });
    });
</script>
@endif



@endpush