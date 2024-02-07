@extends('layouts.admin')
@section('title', 'Dashboard')
@push('css')
<style>
        .fkt-loader {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
        grid-column-start: span 3;
        align-self: center;
        justify-self: center;
    }
    .fkt-loader div {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 64px;
        height: 64px;
        margin: 8px;
        border: 8px solid #b81f24;
        border-radius: 50%;
        animation: fkt-loader 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: #b81f24 transparent transparent transparent;
    }
    .fkt-loader div:nth-child(1) {
        animation-delay: -0.45s;
    }
    .fkt-loader div:nth-child(2) {
        animation-delay: -0.3s;
    }
    .fkt-loader div:nth-child(3) {
        animation-delay: -0.15s;
    }
    @keyframes fkt-loader {
        0% {
        transform: rotate(0deg);
        }
        100% {
        transform: rotate(360deg);
        }
    }
</style>
    
@endpush
@php
    $labels = [];
    $series = [];
    $series_2 = [];
    $series_3 = [];
    $high_day = 0;
    $total_days = cal_days_in_month(0, $month, $year);
    for ($i = 0; $i <= $total_days; $i++) {
        array_push($labels, $i);
        foreach ($activeUsers as $item) {
            $date = explode("-", $item->date);
            $day = (int) $date[2];

            if ($day == $i) {
                array_push($series,$item->total);
            }
        }
        if(empty($series[$i])){
            $series[$i] = 0;
        }
        foreach ($registerUsers as $item) {
            $date = explode("-", $item->date);
            $day = (int) $date[2];

            if ($day == $i) {
                array_push($series_2,$item->total);
            }
        }
        if(empty($series_2[$i])){
            $series_2[$i] = 0;
        }
        foreach ($unregisterUsers as $item) {
            $date = explode("-", $item->date);
            $day = (int) $date[2];

            if ($day == $i) {
                array_push($series_3,$item->total);
            }
        }
        if(empty($series_3[$i])){
            $series_3[$i] = 0;
        }
    }
    if(max($series) > max($series_2) && max($series) > max($series_3)){
        $high_day = max($series) + 10;
    }elseif (max($series_2) > max($series) && max($series_2) > max($series_3)) {
        $high_day = max($series_2) + 10;
    }elseif (max($series_3) > max($series) && max($series_3) > max($series_2)) {
        $high_day = max($series_3) + 10;
    }
@endphp


@section('content')

<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
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

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="krp-card-table">
                    <h5 class="card-title">Active Users, Registered User & Unregistered User</h5>
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
                <div id="chartUsers" height="100"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @if (session()->get('role_id') == 1 || session()->get('role_id') == 2 || session()->get('role_id') == 3 || session()->get('role_id') == 5 || session()->get('role_id') == 6 )
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-10 align-items-center no-block">
                    <h5 class="card-title">Create</h5>
                </div>
                @if (session()->get('role_id') == 1 || session()->get('role_id') == 2 || session()->get('role_id') == 3 || session()->get('role_id') == 5 || session()->get('role_id') == 6 )
                    <a href="{{ route('admin.news.create') }}" class="btn btn-info btn-block m-b-10">
                        <i class="fa fa-plus-circle"></i> Create News
                    </a>
                @endif
                @if (session()->get('role_id') == 1 || session()->get('role_id') == 2 || session()->get('role_id') == 5 || session()->get('role_id') == 6 )
                    <a href="#" class="btn btn-info btn-block m-b-10" data-toggle="modal" data-target="#myModal">
                        <i class="fa fa-plus-circle"></i> Create Tag
                    </a>
                @endif
                @if (session()->get('role_id') == 1 || session()->get('role_id') == 2 || session()->get('role_id') == 5 || session()->get('role_id') == 6 )
                    <div id="myModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if (session()->get('role_id') == 1 || session()->get('role_id') == 2 || session()->get('role_id') == 3 || session()->get('role_id') == 5 || session()->get('role_id') == 6 || session()->get('role_id') == 8)
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex m-b-10 align-items-center no-block">
                        <h5 class="card-title">Bank Image</h5>
                    </div>
                    <a href="{{ route('admin.bank.create') }}" class="btn btn-info btn-block m-b-10">
                        <i class="fa fa-plus-circle"></i> Upload Image
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if (session()->get('role_id') == 1 || session()->get('role_id') == 6 )
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex m-b-10 align-items-center no-block">
                        <h5 class="card-title">Webinar</h5>
                    </div>
                    <a href="{{ route('admin.webinar.create') }}" class="btn btn-info btn-block m-b-10">
                        <i class="fa fa-plus-circle"></i> Create Webinar
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row">
    <div class="col-lg-4 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-10 align-items-center no-block">
                    <h5 class="card-title">Top Platform</h5>
                </div>
                <div id="chartPlatform" height="300"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-10 align-items-center no-block">
                    <h5 class="card-title ">Currently Online</h5>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Last Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($onlineList) && $onlineList->count() > 0)
                            @foreach ($onlineList as $item)
                                @php
                                    $to_time = strtotime(date("Y-m-d H:i:s"));
                                    $from_time = strtotime($item->last_online);
                                    $last_active = round(abs($to_time - $from_time) / 60);
                                    if ($last_active <= 0) {
                                        $last_active = "Online";
                                    } else {
                                        $last_active .= "m Ago";
                                    }
                                @endphp
                            <tr id="1" class="gradeX">
                                <td>{{$item->name}}</td>
                                <td>{{$last_active}}</td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="4">Data tidak ditemukan</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-10 align-items-center no-block">
                    <h5 class="card-title ">Creator Statistic</h5>
                </div>
                <div class="d-flex m-b-10 align-items-center no-block">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" id="startDateCreator" class="form-control form-control-sm" value="{{ date("Y-m-d", strtotime('-3 days', time())) }}" onchange="updateCreatorList()">
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" id="endDateCreator" class="form-control form-control-sm" value="{{ date("Y-m-d") }}" onchange="updateCreatorList()">
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>News Created</th>
                        </tr>
                    </thead>
                    <tbody id="creatorListContainer">
                        @if (isset($creatorList) && $creatorList->count() > 0)
                            @foreach ($creatorList as $item)
                            <tr id="1" class="gradeX">
                                <td>{{$item->name}}</td>
                                <td>{{$item->count_news}}</td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="4">Data tidak ditemukan</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-10 align-items-center no-block">
                    <h5 class="card-title ">Berita Terpopuler</h5>
                </div>
                <div class="row justify-content-sm-start" style="gap: 10px; padding:0 10px;">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" id="startDatePopuler" class="form-control form-control-sm" value="{{ date("Y-m-d", strtotime('-7 days', time())) }}" onchange="updatePopulerList()">
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" id="endDatePopuler" class="form-control form-control-sm" value="{{ date("Y-m-d") }}" onchange="updatePopulerList()">
                    </div>
                </div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Link</th>
                            <th>Visit</th>
                        </tr>
                    </thead>
                    <tbody id="topListContainer">
                        @if (isset($topList) && $topList->count() > 0)
                        @foreach ($topList as $item)
                        <tr id="1" class="gradeX">
                            <td>{{$loop->iteration}}</td>
                            @php
                            if(strpos($item->title, '<i>') !== false){
                                if(strpos($item->title, '</i>') === false){
                                    $item->title = strip_tags($item->title);
                                    $item->title = html_entity_decode($item->title);
                                }
                            }
                             @endphp
                            <td>{!!html_entity_decode($item->title)!!}</td>
                            <td><a href="{{url('news/'.$item->category_slug.'/'.$item->slug)}}" target="_blank">{{url('news/'.$item->category_slug.'/'.$item->slug)}}</a></td>
                            <td>{{$item->total_views}}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4">Data tidak ditemukan</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>

<!-- ============================================================== -->
<!-- End Page Content -->
@endsection

@push('js')
<script src="{{asset('assets/js/chartist.min.js')}}"></script>
<script src="{{asset('assets/js/chartist-plugin-tooltip.js')}}"></script>
<script src="{{asset('assets/node_modules/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script>
    $(document).ready(function () {
        $('#editable-datatable').DataTable();
    });

</script>
<script>
    let totalDays = {{ $total_days }};
    var data1 = {
        labels: @json($labels),
        series: [{meta:'Total Active User :', value:@json($series)}
            , {meta:'Registered User :', value:@json($series_2)}
            , {meta:'Unregistered User :', value:@json($series_3)}
        ]
    }
    
    var options1 = {
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

    $(function () {
        $.toast({
            heading: 'Welcome to Fakta',
            position: 'top-right',
            loaderBg: '#000000',
            icon: 'info',
            hideAfter: 3500,
            stack: 6
        });
    });

    var responsiveOptions = [
        ['screen and (min-width: 640px)', {
            chartPadding: 30,
            labelOffset: 100,
            labelDirection: 'explode',
            labelInterpolationFnc: function(value) {
            return value;
            }
        }],
        ['screen and (min-width: 1024px)', {
            labelOffset: 80,
            chartPadding: 20
        }]
    ];

    new Chartist.Pie('#chartPlatform', {
        labels: [
            'Windows',
            'MacOS',
            'Linux',
            'Android',
            'Iphone'
        ],
        series: [{meta:'windows', value:{!! $windows !!}},
        {meta:'macos', value:{!! $macos !!}},
        {meta:'linux', value:{!! $linux !!}},
        {meta:'android', value:{!! $android !!}},
        {meta:'iphone', value:{!! $iphone !!}}],
    }, {
        width: '300px',
        height: '300px',
        plugins: [
            Chartist.plugins.tooltip()
        ],
        labelInterpolationFnc: function(value) {
            return value[0]
        }
    }, responsiveOptions);

    new Chartist.Line("#chartUsers", data1, options1);

    function updateDate() {
        var month = document.querySelector("#monthInput").value;
        var year = document.querySelector("#yearInput").value;
        startDate = document.getElementById("startDatePopuler").value;
        endDate = document.getElementById("endDatePopuler").value;
        $.ajax({
            url: '{{route("admin.home")}}',
            method : 'GET',
            dataType: "json",
            data: {
                month: month,
                year: year,
                startDate: startDate,
                endDate: endDate,
                _token: "{{csrf_token()}}"
            },
            success: function(res) {
                if (res.bool) {
                    $(function () {
                        $.toast({
                            heading: 'Grafik Berhasil Diperbarui',
                            position: 'top-right',
                            loaderBg: '#de4a58',
                            icon: 'success',
                            hideAfter: 3500,
                            stack: 6
                        });
                    });
                    let newData = res.data.activeUsers;
                    let newData2 = res.data.registerUsers;
                    let newData3 = res.data.unregisterUsers;
                    let totalDays = daysInMonth(month,year);
                    let newLabels = [];
                    let newSeries = [];
                    let newSeries2 = [];
                    let newSeries3 = [];
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
                        newData2.forEach(item => {
                            var newDate = item.date.split("-");
                            var newDay = parseInt(newDate[2]);
                            if (newDay == index) {
                                newSeries2.push(item.total)
                            }
                        })
                        if (newSeries2[index] == null) {
                            newSeries2[index] = 0;
                        }
                        newData3.forEach(item => {
                            var newDate = item.date.split("-");
                            var newDay = parseInt(newDate[2]);
                            if (newDay == index) {
                                newSeries3.push(item.total)
                            }
                        })
                        if (newSeries3[index] == null) {
                            newSeries3[index] = 0;
                        }
                    }
                    let newData1 = {
                        labels: newLabels,
                        series: [{meta:'Total Active User :', value:newSeries}
                            , {meta:'Registered User :', value:newSeries2}
                            , {meta:'Unregistered User :', value:newSeries3}
                        ]
                    }
                new Chartist.Line("#chartUsers", newData1, options1)
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

    function updatePopulerList(){
        baseUrl = window.location.origin;
        table = document.getElementById("topListContainer");
        var month = document.querySelector("#monthInput").value;
        var year = document.querySelector("#yearInput").value;
        startDate = document.getElementById("startDatePopuler").value;
        endDate = document.getElementById("endDatePopuler").value;


        if(endDate < startDate){
            return  $(function () {
                $.toast({
                    heading: 'Periode Tanggal Tidak Valid',
                    position: 'top-right',
                    loaderBg: '#de4a58',
                    icon: 'error',
                    hideAfter: 3500,
                    stack: 6
                });
            });
        }

        $.ajax({
            url: '{{route("admin.home")}}',
            method : 'GET',
            dataType: "json",
            data: {
                month: month,
                year: year,
                startDate: startDate,
                endDate: endDate,
                _token: "{{csrf_token()}}"
            },
            beforeSend:function(){
                table.innerHTML = "";
                html = "";
                html += `<div class="fkt-loader"><div></div><div></div><div></div><div>`;
                table.innerHTML = html;
             },
            success: function(res) {
                $(function () {
                $.toast({
                        heading: 'Berita Terpopuler Behasil Diperbarui',
                        position: 'top-right',
                        loaderBg: '#de4a58',
                        icon: 'success',
                        hideAfter: 3500,
                        stack: 6
                    });
                });
                table.innerHTML = "";
                html = "";
                if(res.bool){
                    updateTerpopulerList = res.data.topList;
                    if(updateTerpopulerList.length > 0){
                        for(let i = 0; i < updateTerpopulerList.length ;i++){
                            html += "<tr>";
                            html += "<td>"+(i+1)+"</td>";
                            html += `<td>`+updateTerpopulerList[i].title+`</td>`;
                            html += "<td>";
                            html += "<a href='"+baseUrl+"/news/"+updateTerpopulerList[i].category_slug+"/"+updateTerpopulerList[i].slug+"' target='_blank'>";
                            html += baseUrl+"/news/"+updateTerpopulerList[i].category_slug+"/"+updateTerpopulerList[i].slug;
                            html += "</a>";
                            html += "</td>";
                            html += "<td>"+updateTerpopulerList[i].total_views+"</td>";
                            html += "</tr>";
                        }
                    }else{
                        html += "<tr>";
                        html += "<td colspan='4'>Data tidak ditemukan pada periode ini<td>";
                        html += "</tr>";
                    }
                    
                }else{
                    html += "<tr>";
                    html += "<td colspan='4'>Data tidak ditemukan<td>";
                    html += "</tr>";
                }
                table.innerHTML = html;
            },
            error: function(error) {
                console.log(error);
                $(function () {
                $.toast({
                        heading: 'Terjadi Kesalahan',
                        position: 'top-right',
                        loaderBg: '#de4a58',
                        icon: 'error',
                        hideAfter: 3500,
                        stack: 6
                    });
                });
                table = document.getElementById("topListContainer");
                table.innerHTML = "";
                html = "";
                html += "<tr>";
                html += "<td colspan='4'>Terjadi Kesalahan<td>";
                html += "</tr>";
            }
        });

        
    }

    function updateCreatorList(){
        baseUrl = window.location.origin;
        table = document.getElementById("creatorListContainer");
        var month = document.querySelector("#monthInput").value;
        var year = document.querySelector("#yearInput").value;
        startDate = document.getElementById("startDateCreator").value;
        endDate = document.getElementById("endDateCreator").value;


        if(endDate < startDate){
            return  $(function () {
                $.toast({
                    heading: 'Periode Tanggal Tidak Valid',
                    position: 'top-right',
                    loaderBg: '#de4a58',
                    icon: 'error',
                    hideAfter: 3500,
                    stack: 6
                });
            });
        }

        $.ajax({
            url: '{{route("admin.home")}}',
            method : 'GET',
            dataType: "json",
            data: {
                month: month,
                year: year,
                startDate: startDate,
                endDate: endDate,
                _token: "{{csrf_token()}}"
            },
            beforeSend:function(){
                table.innerHTML = "";
                html = "";
                html += `<div class="fkt-loader"><div></div><div></div><div></div><div>`;
                table.innerHTML = html;
            },
            success: function(res) {
                $(function () {
                $.toast({
                        heading: 'Statistik Kreator Behasil Diperbarui',
                        position: 'top-right',
                        loaderBg: '#de4a58',
                        icon: 'success',
                        hideAfter: 3500,
                        stack: 6
                    });
                });
                table.innerHTML = "";
                html = "";
                if(res.bool){
                    newCreatorList = res.data.creatorList;
                    if(newCreatorList.length > 0){
                        for(let i = 0; i < newCreatorList.length ;i++){
                            html += "<tr>";
                            html += `<td>`+newCreatorList[i].name+`</td>`;
                            html += `<td>`+newCreatorList[i].count_news+`</td>`;
                            html += "</tr>";
                        }
                    }else{
                        html += "<tr>";
                        html += "<td colspan='4'>Data tidak ditemukan pada periode ini<td>";
                        html += "</tr>";
                    }
                }else{
                    html += "<tr>";
                    html += "<td colspan='4'>Data tidak ditemukan <td>";
                    html += "</tr>";
                }
                table.innerHTML = html;
            },
            error: function(error) {
                console.log(error);
                $(function () {
                $.toast({
                        heading: 'Terjadi Kesalahan',
                        position: 'top-right',
                        loaderBg: '#de4a58',
                        icon: 'error',
                        hideAfter: 3500,
                        stack: 6
                    });
                });
                table = document.getElementById("creatorListContainer");
                table.innerHTML = "";
                html = "";
                html += "<tr>";
                html += "<td colspan='4'>Terjadi Kesalahan<td>";
                html += "</tr>";
            }
        });    
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
@endpush