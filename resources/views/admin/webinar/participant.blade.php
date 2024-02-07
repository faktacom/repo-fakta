@extends('layouts.admin')

@section('title', "$detail_webinar->title" )

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
                <li class="breadcrumb-item"><a href="{{route('admin.webinar.index')}}">Webinar</a></li>
                <li class="breadcrumb-item active">{{$detail_webinar->title}} Participant</li>
            </ol>
            @if (isset($listWebinarParticipant) && $listWebinarParticipant->count() > 0)
                <button type="button" class="btn btn-info d-inline-block m-l-15" data-toggle="modal"
                data-target="#myModal"><i class="fa fa-arrow-circle-down"></i> Download</button>
            @endif
        </div>
    </div>
    <div id="myModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <table class="table table-striped table-bordered" id="editable-datatable">
                    @if (isset($listWebinarParticipant) && $listWebinarParticipant->count() > 0)
                        <tr>
                            <th colspan="2">
                                {{$detail_webinar->title}} Participant
                            </th>
                        </tr>
                    @endif
                    <tr>
                        <td>PDF</td>
                        <td>@if (isset($listWebinarParticipant) && $listWebinarParticipant->count() > 0)
                            <a href="{{route('admin.webinar.download', $listWebinarParticipant[0]->webinar_id)}}" class="btn btn-info"><i
                                class="fa fa-arrow-circle-down"></i> Download Pdf</a>
                            @endif</td>
                    </tr>
                    <tr>
                        <td>Excel</td>
                        <td>@if (isset($listWebinarParticipant) && $listWebinarParticipant->count() > 0)
                            <a href="{{route('admin.webinar.export', $listWebinarParticipant[0]->webinar_id)}}" class="btn btn-success"><i
                                class="fa fa-arrow-circle-down"></i> Download Excel</a>
                            @endif</td>
                    </tr>
                </table>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="editable-datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Job</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Province</th>
                            <th>City</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($listWebinarParticipant) && $listWebinarParticipant->count() > 0)
                            @foreach ($listWebinarParticipant as $item)
                            <tr id="1" class="gradeX">
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{!empty($item->job) ? $item->job : " - "}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->phone}}</td>
                                <td>{{!empty($item->province_name) ? $item->province_name : " - "}}</td>
                                <td>{{!empty($item->city_name) ? $item->city_name : " - "}}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" style="text-align: center"> Participant not found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection