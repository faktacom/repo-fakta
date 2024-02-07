@extends('layouts.admin')

@section('title', 'Create Footer')


@push('css')
{{--
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'> --}}
@endpush

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">@yield('title')</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.footer.index')}}">Footer</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <form action="{{route('admin.footer.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <div class="form-group">
                            <label for="">Label</label>
                            <input type="text" name="title" class="form-control" placeholder="Enter label.." value="{{old('title', request()->title)}}">
                        </div>
                        <div class="form-group">
                            <label for="">Link Type</label>
                            <select class="form-control" style="width:100%" name="link_type_id" id="typeOption" onchange="showDiv(this)">
                                <option value="">--Select--</option>
                                @foreach ($list_link_type as $type)
                                @if (old('link_type_id' == $type->link_type_id))
                                    <option value="{{$type->link_type_id}}" selected>{{$type->link_type}}</option>
                                @else
                                    <option value="{{$type->link_type_id}}">{{$type->link_type}}</option>    
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <label for="">Order</label>
                                <input type="number" name="order_footer" class="form-control" placeholder="Enter order.." value="{{old('order', request()->order)}}">
                            </div>
                            <div class="col-6">
                                <label for="">Column</label>
                                <select class="form-control" style="width:100%" name="column_footer">
                                    <option value="">--Select--</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                        </div>
                        <div id="inputOption"></div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm d-block ml-auto">Create</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script>
    var input_count = 0;
    termsOfService = {!! json_encode($terms_of_service) !!};
    privacyPolicy = {!!  json_encode($privacy_policy)!!};
    categoryNews = {!!  json_encode($category_news)!!};

    function showDiv(element) {
        var redaksi = "";
        var link = "";
        var content = "";
        var aboutus = "";
        var category = "";
        if(element.value == 7) {
            category += "<div class='form-group'>";
            category += "<label for=''>Category</label>";
            category += "<div class='card-body'>"
            category += "<select class='form-control large js-select2' name='category_id'>";
            for (let i = 0; i < this.categoryNews.length; i++) {
                category += "<option value='" + categoryNews[i].category_id + "."+categoryNews[i].slug +"'>" + categoryNews[i].title + "</option>";
            }
            category += "</select>";
            category += "</div>"
            category += "</div>";
            $("#inputOption").html(category);
        } else if (element.value == 6){
            content += "<div class='form-group'>";
            content += "<label for=''>Content</label>";
            content += "<textarea class='ckeditor' name='content'>{!!old('content', request()->content)!!}</textarea>";
            content += "</div>";
            $("#inputOption").html(content);

            CKEDITOR.replace('content', {
                filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });
            if(privacyPolicy){
                CKEDITOR.instances["content"].setData(privacyPolicy.content);
            }
        } else if (element.value == 5) {
            content += "<div class='form-group'>";
            content += "<label for=''>Content</label>";
            content += "<textarea class='ckeditor' name='content'>{!!old('content', request()->content)!!}</textarea>";
            content += "</div>";
            $("#inputOption").html(content);

            CKEDITOR.replace('content', {
                filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });
            if(termsOfService){
                CKEDITOR.instances["content"].setData(termsOfService.content);
            }
        } else if (element.value == 4) {
            aboutus += "<div class='form-group'>";
            aboutus += "<label for=''>Content</label>";
            aboutus += "<textarea class='ckeditor' name='content'>{!!old('content', request()->content)!!}</textarea>";
            aboutus += "</div>";
            aboutus += "<hr>";
            aboutus += "<div class='form-group'>";
            aboutus += "<label for=''>Value Description</label>";
            aboutus += "<textarea class='form-control form-control-sm' name='value_description' rows='6'>{!!old('value_description', request()->value_description)!!}</textarea>";
            aboutus += "</div>";
            aboutus += "<div class='form-group'>";
            aboutus += "<div class='btn btn-primary' onclick='addValueRow()'>Add Value</div>";
            aboutus += "<table class='table' id='valueRowPosition'>";
            aboutus += "<tr>";
            aboutus += "<td>Title</td>";
            aboutus += "<td>Description</td>";
            aboutus += "<td>Order</td>";
            aboutus += "<td>Action</td>";
            aboutus += "</tr>";
            aboutus += "</table>";
            aboutus += "</div>";
            aboutus += "</div>";
            aboutus += "<hr>";
            aboutus += "<div class='form-group'>";
            aboutus += "<label for=''>Teams</label>";
            aboutus += "<textarea class='ckeditor' name='team_about_us'>{!!old('team_about_us', request()->team_about_us)!!}</textarea>";
            aboutus += "</div>";
            $("#inputOption").html(aboutus);
            CKEDITOR.replace('content', {
                filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });
            CKEDITOR.replace('team_about_us', {
                filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });
        } else if (element.value == 3) {
            redaksi += "<hr>";
            redaksi += "<div class='form-group'>";
            redaksi += "<div class='btn btn-primary' onclick='addPositionRow()'>Add Position</div>";
            redaksi += "<table class='table' id='rowPosition'>";
            redaksi += "<tr>";
            redaksi += "<td>Position</td>";
            redaksi += "<td>Name</td>";
            redaksi += "<td>Order</td>";
            redaksi += "<td>Action</td>";
            redaksi += "</tr>";
            redaksi += "</table>";
            redaksi += "</div>";
            redaksi += "</div>";
            $("#inputOption").html(redaksi);
        } else if (element.value == 2) {
            content += "<div class='form-group'>";
            content += "<label for=''>Content</label>";
            content += "<textarea class='ckeditor' name='content'>{!!old('content', request()->content)!!}</textarea>";
            content += "</div>";
            $("#inputOption").html(content);

            CKEDITOR.replace('content', {
                filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });
        } else if (element.value == 1) {
            link += "<div class='form-group'>";
            link += "<label for=''>Link</label>";
            link += "<input type='text' name='link' class='form-control' placeholder='Enter link..' value='{{old('link', request()->link)}}'>";
            link += "</div>";
            $("#inputOption").html(link);
        } else {
            $("#inputOption").html("");
        }
    }

    function addPositionRow() {
        var d = new Date();
        var randomNumber = Math.floor(Math.random() * 100);
        var rowId = "" + randomNumber + d.getHours() + d.getMinutes() + d.getSeconds();
        var html_row = "";
        html_row += "<tr id='" + rowId + "'>";
        html_row += "<td><input type='text' name='position[]' placeholder='Position..' value=''></td>";
        html_row += "<td><input type='text' name='name[]' placeholder='Name..' value=''></td>";
        html_row += "<td><input type='number' name='order_name[]' placeholder='Order..' value=''></td>";
        html_row += "<td><div class='btn btn-danger' onclick='removePositionRow(" + rowId + ")'>Remove</div></td>";
        html_row += "</tr>";
        $("#rowPosition").append(html_row);
        input_count++;
    }

    function addValueRow() {
        var d = new Date();
        var randomNumber = Math.floor(Math.random() * 100);
        var rowId = "" + randomNumber + d.getHours() + d.getMinutes() + d.getSeconds();
        var html_row = "";
        html_row += "<tr id='" + rowId + "'>";
        html_row += "<td><input type='text' name='title_value[]' placeholder='Title..' value=''></td>";
        html_row += "<td><input type='text' name='description_value[]' placeholder='Description..' value=''></td>";
        html_row += "<img src='#' id='previewValue" + rowId + "' class='img-fluid' alt='' style='max-width:100px; max-height:100px;'></td>";
        html_row += "<td><input type='number' name='order_value[]' placeholder='Order..' value=''></td>";
        html_row += "<td><div class='btn btn-danger' onclick='removePositionRow(" + rowId + ")'>Remove</div></td>";
        html_row += "</tr>";
        $("#valueRowPosition").append(html_row);
        input_count++;
    }

    function addTeamsRow() {
        var d = new Date();
        var randomNumber = Math.floor(Math.random() * 100);
        var rowId = "" + randomNumber + d.getHours() + d.getMinutes() + d.getSeconds();
        var html_row = "";
        html_row += "<tr id='" + rowId + "'>";
        html_row += "<td><input type='text' name='name_teams[]' placeholder='Name..' value=''></td>";
        html_row += "<td><input type='text' name='position_teams[]' placeholder='Position..' value=''></td>";
        html_row += "<td><input type='number' name='order_teams[]' placeholder='Order..' value=''></td>";
        html_row += "<td><div class='btn btn-danger' onclick='removePositionRow(" + rowId + ")'>Remove</div></td>";
        html_row += "</tr>";
        $("#teamsRowPosition").append(html_row);
        input_count++;
    }

    function removePositionRow(target) {
        $("#" + target).remove();
        input_count--;
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#preview')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#preview2')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL3(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#preview3')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL4(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#preview4')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readURLValue(input, rowId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#previewValue' + rowId)
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readURLTeams(input, rowId) {
        console.log(rowId);
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#previewTeams' + rowId)
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@if (Session::has('invalid'))
<script>
    $(function() {
        $.toast({
            heading: '{{session('
            invalid ')}}',
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
    $(function() {
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