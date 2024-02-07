@extends('layouts.admin')

@section('title', $detail_bank_image[0]->image_title)


@section('content')
<div class="row page-titles">
    <div class="col-md-12 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.bank.index')}}">Bank Image</a></li>
            <li class="breadcrumb-item active">{{$detail_bank_image[0]->image_title}}</li>
        </ol>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@yield('title')</h4>
            <table class="table table-bordered">
                <tr>
                    <th>Title</th>
                    <td>{{$detail_bank_image[0]->image_title}}</td>
                </tr>
                <tr>
                    <th>Caption</th>
                    <td>{{$detail_bank_image[0]->image_caption}}</td>
                </tr>
                <tr>
                    <th>Created Date</th>
                    <td>{{ date("d M Y | H:i", strtotime($detail_bank_image[0]->created_date))}}</td>
                </tr>
                @if (!empty($detail_bank_image[0]->updated_date))
                    <tr>
                        <th>Updated Date</th>
                        <td>{{ date("d M Y | H:i", strtotime($detail_bank_image[0]->updated_date))}}</td>
                    </tr>  
                @endif
                <tr>
                    <th>Image</th>
                    <td><img src="{{asset('assets/images/bank_image/' . $detail_bank_image[0]->image_path)}}" class="img-fluid"
                        width="100%"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection