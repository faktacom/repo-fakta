@extends('layouts.admin')

@section('title', 'Add Survey')

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
                <li class="breadcrumb-item active">Create Survey</li>
            </ol>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <form action="{{route('admin.survey.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="survey_name" class="form-control form-control-sm"
                                placeholder="Enter title" value="{{old('survey_name')}}">
                        </div>
                        <div class="form-group">
                            <label for="">Description</label>
                            <textarea  class="form-control form-control-sm" name="survey_description" rows="5">{{old('survey_description')}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Start Date</label>
                            <input type="datetime-local" name="survey_start_date" class="form-control form-control-sm" value="{{old('survey_start_date')}}">
                        </div>
                        <div class="form-group">
                            <label for="">End Date</label>
                            <input type="datetime-local" name="survey_end_date" class="form-control form-control-sm" value="{{old('survey_end_date')}}">
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control form-control-sm" style="width:100%" name="mode_id">
                                <option value="0" selected>Survey</option>
                                <option value="1">Polling</option>
                            </select>
                        </div>
                        {{-- <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_anonymous">
                            <span class="form-check-label">Is Anonymous</span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_duplicate_email">
                            <span class="form-check-label">Is Duplicate Email</span>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm d-block ml-auto ">Create Survey</button>
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