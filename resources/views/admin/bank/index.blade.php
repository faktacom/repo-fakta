@extends('layouts.admin')

@section('title', 'Bank Image')

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">@yield('title')</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
            <a href="{{route('admin.bank.create')}}" class="btn btn-info d-inline-block m-l-15"><i
                    class="fa fa-plus-circle"></i> Upload Image </a>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="editable-datatable">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 20%;">Image</th>
                            <th style="width: 20%;">Title</th>
                            <th style="width: 20%;">Caption</th>
                            <th style="width:200px;text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list_bank_image as $item)
                        <tr id="1" class="gradeX">
                            <td>{{$loop->iteration}}</td>
                            <td><img src="{{asset('assets/images/bank_image/'. $item->image_path)}}" class="img-fluid" width="80">
                            </td>
                            <td>{{$item->image_title}}</td>
                            <td>{{$item->image_caption}}</td>
                            <td style="text-align:center;">
                                {{-- <button class="btn btn-info btn-sm" title="Copy Image" style="background-color:#0396D7; border-color:#0396D7;" onclick="copyImage(`{{asset('assets/images/bank_image/'. $item->image_path)}}`)">
                                    <i class="icon-folder"></i>
                                </button> --}}
                                <a href="{{route('admin.bank.detail', $item->image_id)}}"
                                    class="btn btn-info btn-sm" title="View Detail" ><i class="icon-eye"></i></a>
                                <a href="{{route('admin.bank.edit', $item->image_id)}}" title="Edit" class="btn btn-warning btn-sm"><i
                                        class="icon-pencil"></i></a>
                            </td>
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
@endif

<script>
     function copyImage(imageAddress){
        console.log(imageAddress);
        var imgAddress = imageAddress;

        navigator.clipboard.writeText(imgAddress);
    }
</script>

@endpush