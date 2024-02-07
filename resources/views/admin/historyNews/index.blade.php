@extends('layouts.admin')

@section('title', 'History Update')




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

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">@yield('title')</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.news.index')}}">News</a></li>
                <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered" id="editable-datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>View</th>
                        <th>Update at</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list_history as $item)
                    <tr id="1" class="gradeX">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->title}}</td>
                        <td>{{$item->count_view}}</td>
                        <td>{{$item->updated_date}}</td>
                        <td style="text-align:center;">
                            <a href="{{route('admin.historyNews.show', $item->news_id)}}" class="btn btn-info btn-sm"><i
                                    class="icon-magnifier"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection