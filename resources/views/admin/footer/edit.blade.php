@extends('layouts.admin')

@section('title', 'Update Footer')


@push('css')
{{--
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'> --}}
@endpush


@push('js')
<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script>
    var input_count = 0;
    categoryNews = {!! json_encode($category_news)!!};
    detailFooter = {!! json_encode($detail_footer)!!};
    $(document).ready(function(){
        showDiv(typeOption);
        redaksiList = {!! json_encode($redaksi_list) !!};
        aboutusValueList = {!! json_encode($about_us_value_list) !!};
        aboutusTeamsList = {!! json_encode($about_us_teams_list) !!};
        

        if (redaksiList) {
            for (let index = 0; index < redaksiList.length; index++) {
                var presetValue = {
                    redaksiId: redaksiList[index].redaksi_id,
                    position: redaksiList[index].position,
                    name: redaksiList[index].name,
                    order: redaksiList[index].order
                };
                addPositionRow(presetValue);
            }
        }

        if (aboutusTeamsList) {
            for (let index = 0; index < aboutusValueList.length; index++) {
                var presetValue = {
                    about_us_value_id: aboutusValueList[index].about_us_value_id,
                    title_value: aboutusValueList[index].title_value,
                    description_value: aboutusValueList[index].description_value,
                    image_value: aboutusValueList[index].image_value,
                    order_value: aboutusValueList[index].order_value
                };
                addValueRow(presetValue);
            }
        }
        if (aboutusValueList) {
            for (let index = 0; index < aboutusTeamsList.length; index++) {
                var presetValue = {
                    about_us_Teams_id: aboutusTeamsList[index].about_us_teams_id,
                    name_teams: aboutusTeamsList[index].name_teams,
                    position_teams: aboutusTeamsList[index].position_teams,
                    order_teams: aboutusTeamsList[index].order_teams
                };
                addTeamsRow(presetValue);
            }
        }
    });

    function showDiv(element) {
        var redaksi = "";
        var link = "";
        var content = "";
        var aboutus = "";
        var category = "";
        
        if(element.value == 7){
            category += "<div class='form-group'>";
            category += "<label for=''>Category</label>";
            category += "<div class='card-body'>"
            category += "<select class='form-control large js-select2' name='category_id'>";
            for (let i = 0; i < this.categoryNews.length; i++) {
                if(detailFooter.category_id == categoryNews[i].category_id){
                    category += "<option value='" + categoryNews[i].category_id + "."+categoryNews[i].slug + "'selected>" + categoryNews[i].title + "</option>";

                }else{
                    category += "<option value='" + categoryNews[i].category_id + "."+categoryNews[i].slug +"'>" + categoryNews[i].title + "</option>";
                }
            }
            category += "</select>";
            category += "</div>"
            category += "</div>";
            $("#inputOption").html(category);
        }else if (element.value == 4) {
            aboutus += "<div class='form-group'>";
            aboutus += "<label for=''>Content</label>";
            aboutus += "<textarea class='ckeditor' name='content'>{!!old('content', request()->content)!!}</textarea>";
            aboutus += "</div>";
            aboutus += "<hr>";
            aboutus += "<div class='form-group'>";
            aboutus += "<label for=''>Value Description</label>";
            aboutus += "<textarea class='form-control form-control-sm' name='value_description' rows='6'>{{$detail_footer->value_description}}}</textarea>";
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
            CKEDITOR.instances["content"].setData({!! json_encode($detail_footer->content) !!});
            CKEDITOR.instances["team_about_us"].setData({!! json_encode($detail_footer->team_about_us) !!});
        }else if (element.value == 3) {
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
        } else if (element.value == 2 || element.value == 5 || element.value == 6) {
            content += "<div class='form-group'>";
            content += "<label for=''>Content</label>";
            content += "<textarea class='ckeditor' name='content'>{!!old('content', request()->content)!!}</textarea>";
            content += "</div>";
            $("#inputOption").html(content);

            CKEDITOR.replace('content', {
                filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });
            CKEDITOR.instances["content"].setData({!! json_encode($detail_footer->content) !!});
        } else if (element.value == 1) {
            link += "<div class='form-group'>";
            link += "<label for=''>Link</label>";
            link += "<input type='text' name='link' class='form-control' placeholder='Enter link..' value='{{old('link') ? request()->link : $detail_footer->link}}'>";
            link += "</div>";
            $("#inputOption").html(link);
        } else {
            $("#inputOption").html("");
        }
    }

    function addPositionRow(presetValue = false) {
        if (presetValue === false) {
            presetValue = {
                redaksiId: "",
                position: "",
                name: "",
                order: 0
            };
        }
        var d = new Date();
        var randomNumber = Math.floor(Math.random() * 100);
        var rowId = "" + randomNumber+ d.getHours() + d.getMinutes() + d.getSeconds();
        var html_row = "";
        html_row += "<tr id='" + rowId + "'>";
        html_row += "<input type='hidden' name='redaksi_id[]' value='" + presetValue.redaksiId + "'>";
        html_row += "<td><input type='text' name='position[]' placeholder='Position..' value='" + presetValue.position +"'></td>";
        html_row += "<td><input type='text' name='name[]' placeholder='Name..' value='" + presetValue.name +"'></td>";
        html_row += "<td><input type='number' name='order_name[]' placeholder='Order..' value='" + presetValue.order +"'></td>";
        html_row += "<td><div class='btn btn-danger' onclick='removePositionRow(" + rowId + ")'>Remove</div></td>";
        html_row += "</tr>";
        $("#rowPosition").append(html_row);
        input_count++;
    }

    function addValueRow(presetValue = false) {
        if (presetValue === false) {
            presetValue = {
                about_us_value_id: "",
                title_value: "",
                description_value: "",
                order_value: 0
            };
        }
        var d = new Date();
        var randomNumber = Math.floor(Math.random() * 100);
        var rowId = "" + randomNumber+ d.getHours() + d.getMinutes() + d.getSeconds();
        var html_row = "";
        html_row += "<tr id='" + rowId + "'>";
        html_row += "<input type='hidden' name='about_us_value_id[]' value='" + presetValue.about_us_value_id + "'>";
        html_row += "<td><input type='text' name='title_value[]' placeholder='Title..' value='" + presetValue.title_value + "'></td>";
        html_row += "<td><input type='text' name='description_value[]' placeholder='Description..' value='" + presetValue.description_value + "'></td>";
        html_row += "<td><input type='number' name='order_value[]' placeholder='Order..' value='" + presetValue.order_value + "'></td>";
        html_row += "<td><div class='btn btn-danger' onclick='removePositionRow(" + rowId + ")'>Remove</div></td>";
        html_row += "</tr>";
        $("#valueRowPosition").append(html_row);
        input_count++;
    }

    function addTeamsRow(presetValue = false) {
        if (presetValue === false) {
            presetValue = {
                about_us_Teams_id: "",
                name_teams: "",
                position_teams: "",
                order_teams: 0
            };
        }
        var d = new Date();
        var randomNumber = Math.floor(Math.random() * 100);
        var rowId = "" +randomNumber+ d.getHours() + d.getMinutes() + d.getSeconds();
        var html_row = "";
        html_row += "<tr id='" + rowId + "'>";
        html_row += "<input type='hidden' name='about_us_teams_id[]' value='" + presetValue.about_us_teams_id + "'>";
        html_row += "<td><input type='text' name='name_teams[]' placeholder='Name..' value='" + presetValue.name_teams + "'></td>";
        html_row += "<td><input type='text' name='position_teams[]' placeholder='Position..' value='" + presetValue.position_teams + "'></td>";
        html_row += "<td><input type='number' name='order_teams[]' placeholder='Order..' value='" + presetValue.order_teams + "'></td>";
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

            reader.onload = function (e) {
                $('#preview')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
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

            reader.onload = function (e) {
                $('#previewValue'+rowId)
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
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
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">@yield('title')</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.footer.index')}}">Footer</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <form action="{{route('admin.footer.update', $detail_footer->link_id)}}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <div class="form-group">
                            <label for="">Label</label>
                            <input type="text" name="title" class="form-control" placeholder="Enter label.."
                                value="{{old('title') ? request()->title : $detail_footer->title}}">
                        </div>
                        <div class="form-group">
                            <label for="">Link Type</label>
                            <select class="form-control js-select2" style="width:100%" name="link_type_id"
                                id="typeOption">
                                @foreach ($list_link_type as $item)
                                <option value="{{$item->link_type_id}}" {{$item->link_type_id ==
                                    $detail_footer->link_type_id ? 'selected' : ''}} >{{$item->link_type}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <label for="">Order</label>
                                <input type="number" name="order_footer" class="form-control" placeholder="Enter order.."
                                    value="{{old('order') ? request()->order_footer : $detail_footer->order_footer}}">
                            </div>
                            <div class="col-6">
                                <label for="">Column</label>
                                <select class="form-control" style="width:100%" name="column_footer">
                                    <option value="">--Select--</option>
                                    <option value="1" {{$detail_footer->column_footer == 1 ? 'selected' : ''}} >1</option>
                                    <option value="2" {{$detail_footer->column_footer == 2 ? 'selected' : ''}} >2</option>
                                </select>
                            </div>
                        </div>
                        <div id="inputOption"></div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary d-block ml-auto ">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection