@extends('layouts.admin')

@section('title', 'All News')

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
    <table>
        <thead>
            <tr>
                <th scope="col"><a href="{{route('admin.news.index')}}" class="text-themecolor">News Updates &nbsp; &nbsp; &nbsp; &nbsp;|&nbsp; &nbsp; &nbsp; &nbsp;</a></th>
                <th scope="col"><a href="{{route('admin.news.all')}}" class="text-themecolor">All News</a></th>
                </tr>
        </thead>
    </table>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
            <a href="{{route('admin.news.create')}}" class="btn btn-info d-inline-block m-l-15"><i
                    class="fa fa-plus-circle"></i> Create
                News</a>
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
                            <th style="width: 15%;">Title</th>
                            <th style="width: 15%;">Show Date</th>
                            <th style="width: 5%;">Views</th>
                            <th style="width: 10%;">News Type</th>
                            <th style="width: 10%;">User</th>
                            <th style="width: 10%;">Kompartemen</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width:200px;text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list_news as $item)
                        <tr id="1" class="gradeX">
                            <td>{{$loop->iteration}}</td>
                            <td>{!! html_entity_decode($item->title)!!}</td>
                            @if (isset($item->show_date))
                            <td>{{ date('Y-m-d, H:i', strtotime($item->show_date))}}</td>  
                            @else
                            <td> - </td>       
                            @endif
                            <td>{{$item->count_view}}</td>
                            <td>{{$item->news_type_name}}</td>
                            <td>{{$item->user_name}}</td>
                            <td>{{$item->category_name}}</td>
                            <td id="statusTd" id-status={{$item->news_id}}>{{$item->news_status_name}}</td>
                            <td style="text-align:center;">
                                <a href="{{route('news.preview', ['user' => $item->username, 'slug' => $item->slug])}}" target="_blank" class="btn btn-info btn-sm" title="Preview News" style="background-color:#0396D7; border-color:#0396D7;">
                                    <i class="icon-eye"></i>
                                </a>
                                <a href="{{route('admin.historyNews.index', $item->timestamp)}}"
                                    class="btn btn-info btn-sm"><i class="icon-clock"></i></a>
                                @if (session()->get('role_id') == 1 || session()->get('role_id') == 2 ||( session()->get('role_id') == 3 && $item->user_id == auth()->user()->user_id))
                                <a href="{{route('admin.news.edit', $item->news_id)}}" class="btn btn-warning btn-sm"><i
                                        class="icon-pencil"></i></a>
                                @endif
                                @if (session()->get('role_id') == 1 || session()->get('role_id') == 2)
                                    @if ($item->news_status_name == "Draft" || $item->news_status_name == "Pending")
                                    <button class="btn btn-success btn-sm" data-idbutton="{{$item->news_id}}"
                                        data-toggle="modal" data-target="#statusModal{{$item->news_id}}"><i
                                            class="icon-check"></i></button>
                                    @endif
                                @endif
                                @if (session()->get('role_id') == 1)
                                    <button class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#deleteModal{{$item->news_id}}"><i class="icon-trash"></i></button>
                                @endif
                            </td>
                        </tr>

                        <div id="deleteModal{{$item->news_id}}" class="modal" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">Delete @yield('title')</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">×</button>
                                    </div>
                                    <form action="{{route('admin.news.destroy', $item->news_id)}}" method="post">
                                        @csrf
                                        <div class="modal-body text-center">
                                            <h6>Are you sure delete {{$item->title}} news?</h6>
                                            <button type="button" class="btn btn-success waves-effect"
                                                data-dismiss="modal">No</button>
                                            <button type="submit" class="btn btn-danger waves-effect">Yes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        @if ($item->news_status_name == "Draft" || $item->news_status_name == "Pending")
                        <div id="statusModal{{$item->news_id}}" class="modal" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">Terima Artikel?</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">×</button>
                                    </div>
                                    <div class="form-group"
                                        style="display: block;margin-left: auto;margin-right: auto;margin-top: 25px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                id="status{{$item->news_id}}">
                                            <label class="form-check-label" for="status{{$item->news_id}}">
                                                Terima Artikel
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="date" name="show_date" id="showDate{{$item->news_id}}" class="form-control form-control-sm" value="{{ date('Y-m-d', strtotime($item->show_date))}}">    
                                    </div>
                                    <div class="form-group">
                                        <input type="time" name="show_time" id="showTime{{$item->news_id}}" class="form-control form-control-sm" value="{{ date('H:i', strtotime($item->show_date))}}">    
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary btn-sm btn-terima"
                                            data-pointid="{{$item->news_id}}">
                                            Update
                                        </button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <label>* News Updates Menampilkan News 2 Hari Sebelumnya</label>
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

<script>
    $('.btn-terima').on('click', function() {
        var id = $(this).attr("data-pointid");
        var _status = 3;
        var  _show_date = $("#showDate"+id).val();
        var  _show_time = $("#showTime"+id).val();
        $.ajax({
            url: '{{route('admin.news.updateStatus')}}',
            method : 'POST',
            dataType: "json",
            data: {
                id: id,
                status: _status,
                show_date: _show_date,
                show_time: _show_time,
                _token: "{{csrf_token()}}"
            },

            beforeSend: function() {
                $("button[data-idbutton="+id+"]").remove();
                $("#statusModal"+id).modal('hide');
            },

            success: function(res) {
                if(res.bool == true) {
                    $("td[id-status="+ id +"]").html("Tayang")
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
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