@extends('layouts.admin')
@section('title', 'User Performance')
@php
    $labels = [];
    $series = [];
    $high_day = 0;
    $total_days = cal_days_in_month(0, $month, $year);
    for ($i = 0; $i <= $total_days; $i++) {
        array_push($labels, $i);
        foreach ($listNewsPerMonth as $item) {
            $date = explode("-", $item->date);
            $day = (int) $date[2];
            
            if ($day == $i) {
                array_push($series,$item->total);
            }
        }
        if(empty($series[$i])){
            $series[$i] = 0;
        }
    }
    $high_day = max($series) + 10;
@endphp
@push('js')
<script src="{{asset('assets/js/chartist.min.js')}}"></script>
<script src="{{asset('assets/js/chartist-plugin-tooltip.js')}}"></script>
<script src="{{asset('assets/node_modules/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script>
    $(document).ready(function () {
        $('#editable-datatable').DataTable();
    });

    let totalDays = {{ $total_days }};
    var data2 = {
        labels: @json($labels),
        series: [@json($series)]
    }
    var options2 = {
        high: {{ $high_day }},
        low: 0,
        fullWidth: true,
        axisY: {
            onlyInteger: true,
            offset: 20
        },
        plugins: [
            Chartist.plugins.tooltip()
        ]
    }

    new Chartist.Line("#performanceUsers", data2, options2);

    function updateDate() {
        var month = document.querySelector("#monthInput").value;
        var year = document.querySelector("#yearInput").value;
        $.ajax({
            url: '{{route("admin.user.perform")}}',
            method : 'GET',
            dataType: "json",
            data: {
                month: month,
                year: year,
                _token: "{{csrf_token()}}"
            },
            success: function(res) {
                if (res.bool) {
                    let newData = res.data.listNewsPerMonth;
                    let totalDays = daysInMonth(month,year);
                    let newLabels = [];
                    let newSeries = [];
                    for (let index = 0; index <= totalDays; index++) {
                        newLabels.push(index);
                        newData.forEach(item => {
                            var newDate = item.date.split("-");
                            var newDay = parseInt(newDate[2]);
                            if (newDay == index) {
                                newSeries.push(item.total)
                            }
                        })
                        if (newSeries[index] == null) {
                            newSeries[index] = 0;
                        }
                    }
                    let newData2 = {
                        labels: newLabels,
                        series: [newSeries]
                    }
                new Chartist.Line("#performanceUsers", newData2, options2)
               }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function daysInMonth (month, year) {
        return new Date(year, month, 0).getDate();
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
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <div class="krp-card-table">
                <h5 class="card-title">Performance Stats</h5>
                <div class="krp-card-table-filter">
                    <select name="month" id="monthInput" onchange="updateDate()">
                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            if ($i == $month) {
                                echo "<option value='$i' selected>" . date("F", strtotime("2020-$i-01")) . "</option>";
                            } else {
                                echo "<option value='$i'>" . date("F", strtotime("2020-$i-01")) . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <select name="year" id="yearInput" onchange="updateDate()">
                        <?php
                        for ($i = 2021; $i <= date("Y"); $i++) {
                            if ($i == $year) {
                                echo "<option value='$i' selected>$i</option>";
                            } else {
                                echo "<option value='$i'>$i</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div id="performanceUsers" height="100"></div>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="editable-datatable">
                    <thead>
                        <tr style="text-align:center;">
                            <th>No</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Total Article</th>
                            <th>Total Views</th>
                            <th style="width:180px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listUser as $item)
                        <tr id="1" class="gradeX" style="text-align:center;">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->role_name}}</td>
                            <td>{{$item->total_news}}</td>
                            <td>{{$item->total_views}}</td>
                            <td>
                                <a href="{{route('admin.user.performDetail', $item->user_id)}}" class="btn btn-info btn-sm"><i
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