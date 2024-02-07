@extends('layouts.admin')

@section('title', 'Ads')

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
            <button type="button" class="btn btn-info d-inline-block m-l-15" data-toggle="modal"
                data-target="#myModal"><i class="fa fa-plus-circle"></i> Create
                @yield('title')</button>
        </div>
    </div>
    <div id="myModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Create @yield('title')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form action="{{route('admin.ads.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Ads URL</label>
                            <textarea name="ads_url" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Ads Slot</label>
                            <select class="form-control large js-select2" style="width:100%" name="ads_slot_id" onchange="toggleImageMobile(this.value)">
                                @foreach ($list_ads_slot as $slot)
                                <option value="{{$slot->ads_slot_id}}" {{$slot->ads_slot_id == old('ads_slot_id',
                                    request()->ads_slot_id) ? 'selected' : ''}}>{{$slot->ads_slot_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Image</label>
                            <input type="file" name="ads_image_path" class="form-control form-control-sm">
                        </div>
                        <div class="form-group" id="imageMobile" style="display: none">
                            <label for="">Image Mobile</label>
                            <input type="file" name="ads_image_path_mobile" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="">Published Date</label>
                            <input type="date" name="published_date" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="">End Date</label>
                            <input type="date" name="end_date" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success waves-effect">Save</button>
                    </div>
                </form>
            </div>
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
                        <th>URL</th>
                        <th>Ads Slot</th>
                        <th>Picture</th>
                        <th>Clicks</th>
                        <th>Published Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list_ads as $item)
                    <tr id="1" class="gradeX">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->ads_url}}</td>
                        <td>{{$item->ads_slot_name}}</td>
                        @if ($item->ads_image_path == NULL)
                        <td><img src="{{asset('assets/images/profile/no-profile.jpg')}}" class="img-fluid" width="80">
                        </td>
                        @else
                        <td><img src="{{asset('assets/images/ads/'. $item->ads_image_path)}}" class="img-fluid"
                                width="80"></td>
                        @endif
                        <td>{{$item->total_clicks}}</td>
                        <td>{{$item->published_date}}</td>
                        <td>{{$item->end_date}}</td>
                        @if ($current_date < $item->end_date)
                        <td>Active</td>
                        @else
                        <td>No active</td>
                        @endif
                        <td style="text-align:center;">
                            <button class="btn btn-info btn-sm" data-toggle="modal"
                                data-target="#editModal{{$item->ads_id}}"><i class="icon-pencil"></i></button>
                            <button class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteModal{{$item->ads_id}}"><i class="icon-trash"></i></button>
                        </td>
                    </tr>

                    <div id="deleteModal{{$item->ads_id}}" class="modal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Delete @yield('title')</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">×</button>
                                </div>
                                <form action="{{route('admin.ads.destroy', $item->ads_id)}}" method="post">
                                    @csrf
                                    <div class="modal-body text-center">
                                        <h6>Are you sure delete?</h6>
                                        <button type="button" class="btn btn-success waves-effect"
                                            data-dismiss="modal">No</button>
                                        <button type="submit" class="btn btn-danger waves-effect">Yes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div id="editModal{{$item->ads_id}}" class="modal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Update @yield('title')</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">×</button>
                                </div>
                                <form action="{{route('admin.ads.update', $item->ads_id)}}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="">Ads URL</label>
                                            <textarea name="ads_url" class="form-control"
                                                rows="4">{{$item->ads_url}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Ads Slot</label>
                                            <select class="form-control large js-select2" style="width:100%"
                                                name="ads_slot_id" onchange="toggleImageMobile(this.value)">
                                                @foreach ($list_ads_slot as $slot)
                                                <option value="{{$slot->ads_slot_id}}" {{$slot->ads_slot_id ==
                                                    $item->ads_slot_id ? 'selected' : ''}}>{{$slot->ads_slot_name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Image</label>
                                            <input type="file" name="ads_image_path"
                                                class="form-control form-control-sm">

                                            <div class="mt-3">
                                                <img src="{{asset('assets/images/ads/' . $item->ads_image_path)}}"
                                                    width="100" class="img-fluid">
                                            </div>
                                        </div>
                                        @php
                                            $display = "none";
                                            if($item->ads_slot_id == 1 || $item->ads_slot_id == 4 || $item->ads_slot_id == 5 || $item->ads_slot_id == 8){
                                                $display = "block";
                                            }
                                        @endphp
                                        <div class="form-group" id="imageMobile" >
                                            <label for="">Image Mobile</label>
                                            <input type="file" name="ads_image_path_mobile"
                                                class="form-control form-control-sm">

                                            <div class="mt-3">
                                                <img src="{{asset('assets/images/ads/' . $item->ads_image_path_mobile)}}"
                                                    width="100" class="img-fluid">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Published Date</label>
                                            <input type="date" name="published_date"
                                                class="form-control form-control-sm" value="{{$item->published_date}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">End Date</label>
                                            <input type="date" name="end_date" class="form-control form-control-sm"
                                                value="{{$item->end_date}}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-info waves-effect"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success waves-effect">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
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

    function toggleImageMobile(adsSlotId){
        imageMobileInput = document.getElementById("imageMobile");
        if(adsSlotId == 1 || adsSlotId == 4 || adsSlotId == 5 || adsSlotId == 8){
            imageMobileInput.style.display = "block";
        }else{
            imageMobileInput.style.display = "none";
        }
    }

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