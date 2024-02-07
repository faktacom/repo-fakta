@extends('layouts.admin')

@section('title', $detail_survey[0]->survey_name)


@section('content')
<div class="row page-titles">
    <div class="col-md-12 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.survey.index')}}">Survey</a></li>
            <li class="breadcrumb-item active">{{$detail_survey[0]->survey_name}}</li>
        </ol>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@yield('title')</h4>
            <table class="table table-bordered">
                <tr>
                    <th>Title</th>
                    <td>{{$detail_survey[0]->survey_name}}</td>
                    <th>Created Date</th>
                    <td>{{ date("d M Y | H:i", strtotime($detail_survey[0]->created_date))}}</td>
                </tr>
                <tr>
                    <th>Start Date</th>
                    <td>{{ date("d M Y | H:i", strtotime($detail_survey[0]->survey_start_date))}}</td>
                    <th>Created Admin</th>
                    <td>{{$detail_survey[0]->created_admin}}</td>
                </tr>
                <tr>
                    <th>End Date</th>
                    <td>{{ date("d M Y | H:i", strtotime($detail_survey[0]->survey_end_date))}}</td>
                    @if (!empty($detail_survey[0]->updated_admin_id))
                        <th>Updated Date</th>
                        <td>{{ date("d M Y | H:i", strtotime($detail_survey[0]->updated_date))}}</td>
                    @endif
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{$detail_survey[0]->survey_description}}</td>
                    @if (!empty($detail_survey[0]->updated_admin_id))
                        <th>Updated Admin</th>
                        <td>{{$detail_survey[0]->updated_admin}}</td>
                        @endif
                </tr>
                <tr>
                    <th>Mode</th>
                    @if ($detail_survey[0]->is_anonymous == 0 && $detail_survey[0]->is_duplicate_email == 0)
                    <td>Survey</td>
                    @else
                    <td>Polling</td>
                    @endif  
                </tr>   
            </table>
        </div>
    </div>
</div>
<a href="{{route('admin.question.create', $detail_survey[0]->survey_id)}}" class="btn btn-info d-inline-block mb-3"><i
    class="fa fa-plus-circle"></i> Add Question </a>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List Question</h4>
            <table class="table table-striped table-bordered" id="editable-datatable">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 50%;">Question</th>
                        <th style="width: 20%;">Question Type</th>
                        <th style="width: 50%;">Option</th>
                        <th style="width:200px;text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($list_question))
                        @foreach ($list_question as $item)
                        <tr id="1" class="gradeX">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->question}}</td>
                            <td>{{$item->question_type_name}}</td>
                            @if (!empty($item->option_list))
                                <td>
                                    <ol>
                                    @foreach ($item->option_list as $option )
                                        <li>{{$option->option_name}}</li>
                                    @endforeach    
                                    </ol>
                                </td>
                            @else
                                <td> - </td>
                            @endif
                            <td style="text-align:center;">
                                <a href="{{route('admin.question.detail', $item->question_id)}}" class="btn btn-info btn-sm" title="View Detail" >
                                    <i class="icon-eye"></i>
                                </a>
                                {{-- <a href="{{ route('admin.question.edit', ['id' => $item->question_id, 'survey_id' => $item->survey_id]) }}" title="Edit" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                </a> --}}
                                <button  class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{$item->question_id}}">
                                    <i class="icon-pencil"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{$item->question_id}}">
                                    <i class="icon-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <div id="deleteModal{{$item->question_id}}" class="modal" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">Delete Question "{{$item->question}}"</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">×</button>
                                    </div>
                                    <form action="{{route('admin.question.destroy', ['id' => $item->question_id, 'survey_id' => $item->survey_id])}}" method="post">
                                        @csrf
                                        <div class="modal-body text-center">
                                            <h6>WARNING!! If you Delete this question, you will also delete all the answers that respond to this question. Are You Sure?</h6>
                                            <button type="button" class="btn btn-success waves-effect"
                                                data-dismiss="modal">No</button>
                                            <button type="submit" class="btn btn-danger waves-effect">Yes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div id="editModal{{$item->question_id}}" class="modal" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">Edit Question "{{$item->question}}"</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">×</button>
                                    </div>
                                    <form action="{{ route('admin.question.edit', ['id' => $item->question_id, 'survey_id' => $item->survey_id]) }}" method="get">
                                        @csrf
                                        <div class="modal-body text-center">
                                            <h6>Attention!! If you Edit this question, you will also delete all the answers that respond to this question. Are You Sure?</h6>
                                            <button type="button" class="btn btn-success waves-effect"
                                                data-dismiss="modal">No</button>
                                            <button type="submit" class="btn btn-danger waves-effect">Yes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach 
                    @else
                        <tr>
                            <td colspan="5">Question not found</td>
                        </tr> 
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
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
