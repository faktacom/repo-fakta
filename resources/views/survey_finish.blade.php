@extends('layouts.front', ['listAds' => $listAds])
@section('title', 'Survey')

@section('content')
<div class="fkt-survey-container finish">
    <div class="fkt-survey-finish">
        <h1> Terima Kasih Telah Mengisi Survey</h1>
        <p style="text-align: center;">Kembali Ke <a href="{{route('survey')}}">List Survey</a></p>

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