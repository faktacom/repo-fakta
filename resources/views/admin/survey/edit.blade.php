@extends('layouts.admin')

@section('title', 'Edit Survey')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endpush

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">@yield('title')</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.survey.index')}}">Survey</a></li>
                <li class="breadcrumb-item active">Edit Survey</li>
            </ol>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <form action="{{route('admin.survey.update', $detail_survey[0]->survey_id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <input type="hidden" name="survey_code" value="{{$detail_survey[0]->survey_code}}">
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="survey_name" class="form-control form-control-sm"
                                placeholder="Enter title" value="{{$detail_survey[0]->survey_name}}">
                        </div>
                        <div class="form-group">
                            <label for="">Description</label>
                            <textarea class="form-control form-control-sm" name="survey_description" rows="5">{{$detail_survey[0]->survey_description}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Start Date</label>
                            <input type="datetime-local" name="survey_start_date" class="form-control form-control-sm" value="{{$detail_survey[0]->survey_start_date}}">
                        </div>
                        <div class="form-group">
                            <label for="">End Date</label>
                            <input type="datetime-local" name="survey_end_date" class="form-control form-control-sm" value="{{$detail_survey[0]->survey_end_date}}">
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control form-control-sm" style="width:100%" name="mode_id" >
                                @if($detail_survey[0]->is_anonymous == 1)
                                    <option value="0" selected>Survey</option>
                                    <option value="1">Polling</option>
                                    @else
                                    <option value="0" >Survey</option>
                                    <option value="1" selected>Polling</option>
                                @endif
                                
                            </select>
                        </div>
                        {{-- <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_anonymous" {{$detail_survey[0]->is_anonymous || old('is_anonymous') ? 'checked' : ''}}>
                            <span class="form-check-label">Is Anonymous</span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_duplicate_email" {{$detail_survey[0]->is_duplicate_email || old('is_duplicate_email') ? 'checked' : ''}}>
                            <span class="form-check-label">Is Duplicate Email</span>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm d-block ml-auto ">Edit Survey</button>
            </div>
        </form>
    </div>
</div>
@endsection


@push('js')
<script src="{{ asset('assets/js/select2.full.js') }}"></script>

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