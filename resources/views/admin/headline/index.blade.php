@extends('layouts.admin')

@section('title', 'Headline')
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
                <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
        </div>
    </div>
    <div id="myModal" class="modal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Add @yield('title')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form action="{{route('admin.headline.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="category_id" id="categoryId" value="all">
                    <div class="modal-body">
                        @for ($index = 1; $index <= 5; $index++)
                            <div class="form-group">
                                <label>Headline {{$index}}</label>
                                <select class="form-control form-control-sm news js-select2" id="newsId{{$index}}" style="width:100%" name="news_id[]">
                                    @foreach ($list_news as $item)
                                        <option value="{{$item->news_id}}.{{$item->news_type_id}}" data-news-type-id="{{$item->news_type_id}}" data-category-id="{{$item->category_id}}">{{$item->title}}</option>
                                    @endforeach     
                                </select>
                                <input type="hidden" name="order[]" value="{{$index}}">
                            </div>          
                        @endfor
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success waves-effect">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div id="myModal2" class="modal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Edit @yield('title')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form action="{{route('admin.headline.update')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="category_id" id="categoryIdEdit" value="all">
                    <div class="modal-body">
                        @php
                            $index = 1;
                        @endphp
                        @for ($i = 0; $i < 5; $i++)
                            @if (isset($list_headline[$i]))
                                <div class="form-group">
                                    <label>Headline {{$index}}</label>
                                    <select class="form-control form-control-sm newsedit js-select2" id="newsIdEdit{{$index}}" style="width:100%" name="news_id[]">
                                        @foreach ($list_news as $item)
                                            @if ($list_headline[$i]->news_id == $item->news_id)
                                                <option value="{{$item->news_id}}.{{$item->news_type_id}}" data-news-type-id="{{$item->news_type_id}}" data-category-id="{{$item->category_id}}" selected>{{$item->title}}</option>
                                            @else
                                                <option value="{{$item->news_id}}.{{$item->news_type_id}}" data-news-type-id="{{$item->news_type_id}}" data-category-id="{{$item->category_id}}">{{$item->title}}</option>
                                            @endif
                                        @endforeach     
                                    </select>
                                    <input type="hidden" name="rel_id[]" id="relId{{$index}}" value="{{$list_headline[$i]->rel_id}}">
                                    <input type="hidden" name="order[]" value="{{$index}}">
                                </div>
                            @else
                                <div class="form-group">
                                    <label>Headline {{$index}} </label>
                                    <select class="form-control form-control-sm newsedit js-select2" id="newsIdEdit{{$index}}" style="width:100%" name="news_id[]">
                                        @foreach ($list_news as $item)
                                            <option value="{{$item->news_id}}.{{$item->news_type_id}}" data-news-type-id="{{$item->news_type_id}}" data-category-id="{{$item->category_id}}">{{$item->title}}</option>
                                        @endforeach     
                                    </select>
                                    <input type="hidden" name="order[]" value="{{$index}}">
                                </div>
                            @endif
                            @php
                                $index++
                            @endphp
                        @endfor
                           
                           
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success waves-effect">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="form-group">
                        <label>Kompartemen</label>
                        <select class="form-control form-control-sm category js-select2" style="width:100%" onchange="refreshHeadlineData(this.value)">
                            <option value="all" selected> Homepage</option>
                            <option value="video_page">Video Page</option>
                            @foreach ($list_category as $item)
                                <option value="{{$item->category_id}}">{{$item->title}}</option>
                            @endforeach     
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex justify-content-end align-items-center">
                        @php
                            $display_btn_add = "";
                            $display_btn_edit = "";
                            if(count($list_headline) == 0){
                                $display_btn_add = "block !important";
                                $display_btn_edit = "none !important";
                            }else{
                                $display_btn_add = "none !important";
                                $display_btn_edit = "block !important";
                            }
                        @endphp
                        <button type="button" class="btn btn-info d-inline-block m-l-15 btn-add-headline" data-toggle="modal" id="btnAddHeadline" data-target="#myModal" style="display:{{$display_btn_add}}">
                            <i class="fa fa-plus-circle"></i>
                            Add @yield('title')
                        </button>
            
                        <button type="button" class="btn btn-info d-inline-block m-l-15 btn-edit-headline" data-toggle="modal" id="btnEditHeadline" data-target="#myModal2" style="display:{{$display_btn_edit}}">
                            <i class="fa fa-plus-circle"></i> 
                            Edit @yield('title')
                        </button>
                                
            
                        {{-- <a href="{{route('admin.news.create')}}" class="btn btn-info d-inline-block m-l-15"><i
                                class="fa fa-plus-circle"></i> Create
                            @yield('title')</a> --}}
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered" >
                    <thead>
                        <tr>
                            <th style="width: 5%;">Order</th>
                            <th style="width: 65%;">Title</th>
                            <th style="width: 15%;">Views</th>
                            <th style="width: 15%;">News Type</th>
                            <th style="width: 15%;">Kompartemen</th>
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="headlineContainer">
                        @foreach ($list_headline as $item)
                        <tr id="1" class="gradeX">
                            <td>{{$item->order}}</td>
                            <td>{{$item->news_title}}</td>
                            <td>{{$item->count_view}}</td>
                            <td>{{$item->news_type_name}}</td>
                            @if (!empty($item->category_name))
                                <td>{{$item->category_name}}</td>
                            @else
                                <td>Homepage</td>
                            @endif
                            <td style="text-align:center;">
                                <a href="{{route('admin.headline.show', $item->news_id)}}" class="btn btn-info btn-sm"><i
                                    class="icon-magnifier"></i></a>
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
<script src="{{ asset('assets/js/select2.full.js') }}"></script>

<script>
    $(function () {
        $('#myModal').on('shown.bs.modal', function () {
            $(".news").select2({
                placeholder: "Select a news",
                allowClear: true
            });
        });
        $('#myModal2').on('shown.bs.modal', function () {
            $(".newsedit").select2({
                placeholder: "Select a news",
                allowClear: true
            });
        });
        $(".category").select2({
            placeholder: "Select a category",
            allowClear: true
        });
    });
</script>

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
    function refreshHeadlineData(categoryId){
        $("#categoryId").val(categoryId);
        $("#categoryIdEdit").val(categoryId);
        $.ajax({
            url: "{{route('admin.headline.refresh')}}",
            method : "GET",
            dataType: "json",
            data:{
                category_id:categoryId,
                ajax: true,
                _token: "{{csrf_token()}}"
            },
            success: function(result) {
                var newListHeadline = result.new_list_headline;
                var newOptionHeadline = result.new_option_headline;
                refreshHeadlineList(newListHeadline);
                refreshHeadlineOption(newOptionHeadline);
                refreshHeadlineOptionEdit(newListHeadline, newOptionHeadline);
            },
            error: function(error){
                console.log(error);
            }
        })
    }

    function refreshHeadlineList(newListHeadline){
        var table = document.getElementById("headlineContainer");
        table.innerHTML = "";
        var html = ""

        for (let i = 0; i < newListHeadline.length; i++) {
            let newsId = newListHeadline[i].news_id;
            html+="<tr>";
            html+="<td>"+newListHeadline[i].order+"</td>";
            html+="<td>"+newListHeadline[i].news_title+"</td>";
            html+="<td>"+newListHeadline[i].count_view+"</td>";
            html+="<td>"+newListHeadline[i].news_type_name+"</td>";
            if(newListHeadline[i].category_id != null){
                html+="<td>"+newListHeadline[i].category_name+"</td>";
            }else{
                html+="<td>Homepage</td>";
            }
            html += "<td style='text-align:center;'><a href='{{ route('admin.headline.show', '') }}/"+newsId+"' class='btn btn-info btn-sm'><i class='icon-magnifier'></i></a></td>";
            html+="</tr>";
        }
        $("#headlineContainer").append(html);
    }
    function refreshHeadlineOption(newOptionHeadline){
        for (let i = 1; i <= 5; i++) {
            let dropdown = document.getElementById("newsId" + i);                
            while (dropdown.options.length > 0) {
                dropdown.remove(0);
            }
            for (let j = 0; j < newOptionHeadline.length; j++) {
                let option = document.createElement("option");
                option.value = newOptionHeadline[j].news_id+"."+newOptionHeadline[j].news_type_id;
                option.text = newOptionHeadline[j].title;
                dropdown.add(option);
            }
            dropdown.id = "newsId" + i;
        }
    }
    function refreshHeadlineOptionEdit(newListHeadline, newOptionHeadline){
        var btnAdd = document.getElementById("btnAddHeadline");
        var btnEdit = document.getElementById("btnEditHeadline");
        if (newListHeadline.length > 0) {
            btnEdit.style.setProperty("display", "block", "important");
            btnAdd.style.setProperty("display", "none", "important");
        } else {
            btnEdit.style.setProperty("display", "none", "important");
            btnAdd.style.setProperty("display", "block", "important");
        }

        let $row = 1;
        for(let i = 0; i < 5; i++){
            let dropdown = document.getElementById("newsIdEdit" + $row);
            dropdown.innerHTML = "";
            var option = "";
            if(newListHeadline.hasOwnProperty(i)){
                for (let j = 0; j < newOptionHeadline.length; j++) {
                    if(newListHeadline[i].news_id == newOptionHeadline[j].news_id ){
                        option += "<option value='"+newOptionHeadline[j].news_id+"."+newOptionHeadline[j].news_type_id+"' selected>"+newOptionHeadline[j].title+"</option>"
                    }else{
                        option += "<option value='"+newOptionHeadline[j].news_id+"."+newOptionHeadline[j].news_type_id+"'>"+newOptionHeadline[j].title+"</option>"
                    }
                    $("#relId"+$row).val(newListHeadline[i].rel_id);
                }
                $("#newsIdEdit" + $row).append(option);
            } else{
                for (let j = 0; j < newOptionHeadline.length; j++) {
                    option += "<option value='"+newOptionHeadline[j].news_id+"."+newOptionHeadline[j].news_type_id+"'>"+newOptionHeadline[j].title+"</option>"
                    $("#relId"+$row).val(0);
                }
                $("#newsIdEdit" + $row).append(option);
            }
            $row++;
        }                         
    }
</script>

@endpush