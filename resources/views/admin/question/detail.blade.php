@extends('layouts.admin')

@section('title', $detail_question[0]->question)


@section('content')
<div class="row page-titles">
    <div class="col-md-12 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.survey.index')}}">Survey</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.survey.detail', $detail_question[0]->survey_id)}}">Survey Detail</a></li>
            <li class="breadcrumb-item active">{{$detail_question[0]->question}}</li>
        </ol>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@yield('title')</h4>
            <table class="table table-bordered">
                <tr>
                    <th>Question</th>
                    <td>{{$detail_question[0]->question}}</td>
                    <th>Created Date</th>
                    <td>{{ date("d M Y | H:i", strtotime($detail_question[0]->created_date))}}</td>
                </tr>
                <tr>
                    <th>Question Type</th>
                    <td>{{$detail_question[0]->question_type_name}}</td>
                    <th>Created Admin</th>
                    <td>{{$detail_question[0]->created_admin}}</td>
                </tr>
                <tr>
                    <th>Option List</th>
                    @if (!empty($detail_question[0]->option_list))
                        <td>
                            <ol>
                                @foreach ($detail_question[0]->option_list as $item )
                                    <li>{{$item->option_name}}</li>
                                @endforeach
                            </ol>
                        </td>
                    @else
                        <td>-</td>   
                    @endif
                    @if (!empty($detail_question[0]->updated_admin_id))
                        <th>Updated Date</th>
                        <td>{{ date("d M Y | H:i", strtotime($detail_question[0]->updated_date))}}</td>
                    @endif
                </tr>
                @if (!empty($detail_question[0]->updated_admin_id))
                    <tr>
                        <td></td>
                        <td></td>
                        <th>Updated Admin</th>
                        <td>{{$detail_question[0]->updated_admin}}</td>
                    </tr>  
                @endif
            </table>
        </div>
    </div>
</div>
@endsection