@extends('layouts.admin')

@section('title', $detail_survey_respond[0]->survey_name)


@section('content')
<div class="row page-titles">
    <div class="col-md-12 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.survey.index')}}">Survey</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.survey.allanswer', $detail_survey_respond[0]->survey_id)}}">All Answer</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.survey.respond', $detail_survey_respond[0]->survey_id)}}">{{$detail_survey_respond[0]->survey_name}}</a></li>
            <li class="breadcrumb-item active">{{$detail_survey_respond[0]->survey_name}} Answer</li>
        </ol>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@yield('title') Answer</h4>
            <table class="table table-bordered">
                <tr>
                    <th>Created Date</th>
                    <td>{{ date("d M Y | H:i", strtotime($detail_survey_respond[0]->created_date))}}</td>
                </tr>
                <tr>
                    <th>Responden</th>
                    <td>{{$detail_survey_respond[0]->name}} {{empty($detail_survey_respond[0]->user_id)?" - (Visitor)":""}}</td>
                </tr> 
                <tr>
                    <th>Email</th>
                    <td>{{$detail_survey_respond[0]->email}}</td>
                </tr> 
                <tr>
                    <th>Phone</th>
                    <td>{{$detail_survey_respond[0]->phone}}</td>
                </tr> 
                <tr>
                    <th>Province</th>
                    <td>{{$detail_survey_respond[0]->province_name}}</td>
                </tr> 
                <tr>
                    <th>City</th>
                    <td>{{$detail_survey_respond[0]->city_name}}</td>
                </tr> 
            </table>
        </div>
    </div>
</div>

<div class="card-group">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List Answer</h4>
            <table class="table table-striped table-bordered" id="editable-datatable">
                <thead>
                    <tr>
                        <th style="width: 50%;">Question</th>
                        <th style="width: 50%;">Answer</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($list_survey_answer))
                        @foreach ($list_survey_answer as $item)
                        <tr id="1" class="gradeX">
                            <td>{{$item->question}}</td>
                            @if (!empty($item->answer_list))
                                @if (count($item->answer_list) > 1)
                                <td>
                                    <ul> 
                                        @foreach ($item->answer_list as $answer )                                     
                                            <li>{{$answer->answer}}</li>
                                        @endforeach  
                                    </ul>
                                    </td>
                                @else
                                    @foreach ($item->answer_list as $answer )
                                        <td>{{$answer->answer}}</td>  
                                    @endforeach
                                @endif     
                            @else
                                <td> - </td>
                            @endif
                        </tr>
                        @endforeach 
                    @else
                        <tr>
                            <td colspan="2">Answer not found</td>
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
