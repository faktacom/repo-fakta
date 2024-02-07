@extends('layouts.admin')

@section('title', 'Add Question')

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
                <li class="breadcrumb-item"><a href="{{route('admin.survey.index')}}">Survey</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.survey.detail', $survey_id)}}">Survey Detail</a></li>
                <li class="breadcrumb-item active">Add Question</li>
            </ol>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <form action="{{route('admin.question.store', $survey_id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <div class="form-group">
                            <label for="">Question</label>
                            <input type="text" name="question" class="form-control form-control-sm"
                                placeholder="Enter question" value="{{old('question')}}">
                        </div>
                        <div class="form-group">
                            <label>Question Type</label>
                            <select class="form-control form-control-sm" style="width:100%" name="question_type_id" id="typeOption" onchange="addOption(this.value)">
                                <option value="">-- select --</option>
                                @foreach ($list_question_type as $item)
                                    @if (old('question_type_id') == $item->question_type_id)
                                        <option value="{{$item->question_type_id}}" selected>{{$item->question_type_name}}</option>
                                    @else    
                                        <option value="{{$item->question_type_id}}">{{$item->question_type_name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div id="inputOption" hidden>
                            <div class="form-group">
                                <div class="btn btn-primary" onclick='addOptionRow()'>Add Option</div>
                                <table class="table" id="rowOption">
                                    <tr>
                                        <td>Option Name</td>
                                        <td>Order</td>
                                    </tr>
                                </table>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm d-block ml-auto ">Add Question</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/select2.full.js') }}"></script>
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
    var input_count = 0;

    function addOption(optionTypeId){
        inputOption = document.getElementById("inputOption");
        if(optionTypeId == 2 || optionTypeId == 3){
            inputOption.removeAttribute("hidden");
        }else{
            inputOption.setAttribute("hidden", "");

        }

    }
    function addOptionRow() {
        var d = new Date();
        var randomNumber = Math.floor(Math.random() * 100);
        var rowId = "" + randomNumber + d.getHours() + d.getMinutes() + d.getSeconds();
        var html_row = "";
        html_row += "<tr id='" + rowId + "'>";
        html_row += "<input type='hidden' name='option_row_id[]' value='" + rowId + "'>";
        html_row += "<td><input type='text' name='option_name[]' placeholder='Option Name..' value=''></td>";
        html_row += "<td><input type='number' name='order[]' placeholder='Order..' value=''></td>";
        html_row += "<td><div class='btn btn-danger' onclick='removeOptionRow(" + rowId + ")'>Remove</div></td>";
        html_row += "</tr>";
        $("#rowOption").append(html_row);
        input_count++;
    }
    function removeOptionRow(rowId) {
        $("#" + rowId).remove();
        input_count--;
    }
</script>
@endpush