@extends('layouts.front', ['listAds' => $listAds])
@section('title', 'Survey')

@section('content')
<div class="fkt-survey-container" style="grid-column-start: span 12">
    <h1><b>List Survey</b></h1>
    @if (isset($listSurvey) && $listSurvey->count() > 0)
        @foreach ($listSurvey as $item)
            {{-- <a href="{{route('survey.detail', $item->survey_code)}}"> --}}
                <div class="fkt-survey-list-item">
                    <div class="fkt-survey-list-item-header">
                        <div>
                            <h3>{{$item->survey_name}}</h3>
                            <div class="fkt-survey-list-item-date">
                                <small>{{date('d/m/Y H:i', strtotime($item->survey_start_date))}}</small>
                                <small>{{date('d/m/Y H:i', strtotime($item->survey_end_date))}}</small> 
                                @If($item->is_anonymous == 1)
                                    <small>Survey</small>
                                @else
                                    <small>Polling</small>
                                @endif                      
                            </div>
                        </div>
                        <a href="{{route('survey.detail', $item->survey_code)}}" class="fkt-survey-list-item-button">Mulai Survey</a>
                    </div>
                    <p>{{$item->survey_description}}</p>
                </div>
            {{-- </a> --}}
        @endforeach
        {{$listSurvey->links('custom-pagination')}}
    @else
    <div class="slider-not-found">
        <h6>Data not found</h6>
    </div>
    @endif
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