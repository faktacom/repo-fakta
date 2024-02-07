@extends('layouts.admin')

@section('title', $detail_privacy_policy[0]->privacy_policy_id)


@push('css')
{{--
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'> --}}
@endpush


@push('js')
<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#preview')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

</script>

{{-- <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script> --}}

<script>
    $(function () {
        $(".large").select2({
            placeholder: "Select a category",
            allowClear: true
        });
        $('.js-example-basic-multiple').select2({
            placeholder: "Select a tags",
            allowClear: true,
        });
    });
</script>

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

@section('content')
<div class="row page-titles">
    <div class="col-md-12 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.footer.index')}}">Footer</a></li>
            <li class="breadcrumb-item"><a
                    href="{{route('admin.privacyPolicy.index')}}">History Privacy Policy</a></li>
            <li class="breadcrumb-item active">{{$detail_privacy_policy[0]->privacy_policy_id}}</li>
        </ol>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@yield('title')</h4>
            <table class="table table-bordered">
                <tr>
                    <th>Content</th>
                    <td>{!! $detail_privacy_policy[0]->content !!}</td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td><span class="badge badge-info">{{$detail_privacy_policy[0]->created_date}}</span></td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection