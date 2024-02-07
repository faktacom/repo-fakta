@extends('layouts.front', ['listAds' => $listAds])
@section('title', 'Survey')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('survey') }}" style="color: var(--color-red)">Survey</a></li>
    <li>{{$detailSurvey[0]->survey_name}}</li>
</ul>
<div class="fkt-survey-container">
    @if (empty($detailSurvey[0]))
        <h1>Survey/Polling Not Found</h1>
    @elseif ($detailSurvey[0]->survey_start_date >= $currentDate )
        <h1>Survey/Polling belum dapat diisi.</h1>
    @elseif ($detailSurvey[0]->survey_end_date <= $currentDate )
        <h1>Survey/Polling sudah tidak dapat diisi lagi.</h1>
     @elseif ($detailSurvey[0]->is_anonymous == 0 && empty(auth()->user()->user_id))
        <h1>Harap login terlebih dahulu untuk mengisi Polling ini.</h1>
    @else
        <h1> {{$detailSurvey[0]->survey_name}}</h1>
        <p>
            {{$detailSurvey[0]->survey_description}}
        </p>
        <div class="fkt-survey-date-container">
            <div class="fkt-survey-date-item">
                <h5>Start</h5>
                <p>{{date('d/m/Y H:i', strtotime($detailSurvey[0]->survey_start_date))}}</p>
            </div>
            <div class="fkt-survey-date-item">
                <h5>End</h5>
                <p>{{date('d/m/Y H:i', strtotime($detailSurvey[0]->survey_end_date))}}</p>
            </div>
            <div class="fkt-survey-date-item">
                @if ($detailSurvey[0]->is_anonymous == 1)
                    <h5>Survey</h5>
                @else
                    <h5>Polling</h5>
                @endif
            </div>
        </div>
        <form action="{{route('survey.answer')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="survey_id" value="{{$detailSurvey[0]->survey_id}}">
            <input type="hidden" name="survey_code" value="{{$detailSurvey[0]->survey_code}}">
            <input type="hidden" name="survey_end_date" value="{{$detailSurvey[0]->survey_end_date}}">
            <input type="hidden" name="survey_start_date" value="{{$detailSurvey[0]->survey_start_date}}">
            <input type="hidden" name="is_anonymous" value="{{$detailSurvey[0]->is_anonymous}}">
            <input type="hidden" name="is_duplicate_email" value="{{$detailSurvey[0]->is_duplicate_email}}">
            <div class="fkt-survey-content-container">
                @if (!empty($detailSurvey[0]->question_list))
                    <div class="fkt-survey-biodata-container">
                        <div class="fkt-survey-content-item">
                            <div class="fkt-survey-question">
                                <h6>Nama</h6>
                            </div>
                            <input type="text" name="name" class="fkt-survey-answer-essay" placeholder="Nama anda....">
                        </div>
                        <div class="fkt-survey-content-item">
                            <div class="fkt-survey-question">
                                <h6>Email</h6>
                            </div>
                            <input type="email" name="email" class="fkt-survey-answer-essay" placeholder="Email anda...." value="{{ isset($userEmail) ? $userEmail[0]->email : '' }}">
                        </div>
                        <div class="fkt-survey-content-item">
                            <div class="fkt-survey-question">
                                <h6>No. Telepon</h6>
                            </div>
                            <input type="text" name="phone" class="fkt-survey-answer-essay" placeholder="Nomor telepon anda....">
                        </div>
                        <div class="fkt-survey-content-item">
                            <div class="fkt-survey-question">
                                <h6>Pekerjaan</h6>
                            </div>
                            <input type="text" name="job" class="fkt-survey-answer-essay" placeholder="Pekerjaan anda....">
                        </div>
                        <div class="fkt-survey-content-item">
                            <div class="fkt-survey-question">
                                <h6>Jenis Kelamin</h6>
                            </div>
                            <select name="gender"  class="fkt-survey-answer-essay">
                                <option value="Pria">Pria</option>
                                <option value="Wanita">Wanita</option>
                            </select>
                        </div>
                        <div class="fkt-survey-content-item">
                            <div class="fkt-survey-question">
                                <h6>Tanggal Lahir</h6>
                            </div>
                            <input type="date" name="birth_date" class="fkt-survey-answer-essay" placeholder="Tanggal lahir anda....">
                        </div>
                        <div class="fkt-survey-content-item">
                            <div class="fkt-survey-question">
                                <h6>Provinsi</h6>
                            </div>
                            @if (!empty($listProvince))
                                <select name="province_id"  class="fkt-survey-answer-essay" id="provinceId" onchange="getCity(this.value)">
                                    @foreach ($listProvince as $item)
                                        <option value="{{$item->province_id}}">{{$item->province_name}}</option>
                                    @endforeach
                                </select>
                            @else
                                <h6>Data province not found.</h6>
                            @endif
                        </div>
                        <div class="fkt-survey-content-item">
                            <div class="fkt-survey-question">
                                <h6>Kota</h6>
                            </div>
                            <select name="city_id"  class="fkt-survey-answer-essay" id="cityId" disabled>
                                <option>--Pilih Provinsi--</option>
                            </select>
                        </div>
                    </div>     
                    @foreach ($detailSurvey[0]->question_list as $question)
                        @if ($question->question_type_id == 2 || $question->question_type_id == 3)
                            <div class="fkt-survey-content-item">
                                <input type="hidden" name="question_id[{{$loop->index}}]" value="{{$question->question_id}}">
                                <div class="fkt-survey-question">
                                    <h5>{{$loop->iteration}}.</h5>
                                    <p>{{$question->question}}</p>
                                </div>
                                <div class="fkt-survey-answer-multiple-container">
                                    @if ($question->question_type_id == 2)
                                        @foreach ($question->option_list as $option)
                                            <div>
                                                <input type="radio" name="answer[{{$loop->parent->index}}]" id="answer{{$loop->iteration}}" value="{{$option->option_name}}">
                                                <label for="answer{{$loop->iteration}}">{{$option->option_name}}</label>
                                            </div>
                                        @endforeach
                                    @else
                                        @foreach ($question->option_list as $option)
                                            <div>
                                                <input type="checkbox" name="answer[{{$loop->parent->index}}][]" id="answer{{$loop->iteration}}" value="{{$option->option_name}}">
                                                <label for="answer{{$loop->iteration}}">{{$option->option_name}}</label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="fkt-survey-content-item">
                                <input type="hidden" name="question_id[{{$loop->index}}]" value="{{$question->question_id}}">
                                <div class="fkt-survey-question">
                                    <h5>{{$loop->iteration}}.</h5>
                                    <p>{{$question->question}}</p>
                                </div>
                                <input type="text" name="answer[{{$loop->index}}]" class="fkt-survey-answer-essay" placeholder="Jawaban anda....">
                            </div>                        
                        @endif
                    @endforeach
                    <button class="fkt-survey-button">Submit</button>
                @else
                    <h1>Question not found</h1>
                @endif
            </div>
        </form>   
    @endif
</div>
@endsection
@push('js')
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
    function getCity(provinceId){
        var cityDropdown = document.getElementById("cityId");
        $.ajax({
            url:"{{route('survey.city')}}",
            method: "POST",
            dataType: "json",
            data:{
                province_id:provinceId,
                ajax:true,
                _token: "{{csrf_token()}}"
            },
            success:function(result){
                var valid = result.bool;
                var cityData = result.data;
                if(valid && cityData != ""){
                    cityDropdown.disabled = false;
                    cityDropdown.innerHTML = "";
                    for(let i = 0; i < cityData.length;i++){
                        let cityOption = document.createElement("option");
                        cityOption.value = cityData[i].city_id;
                        cityOption.text = cityData[i].city_name;
                        cityDropdown.add(cityOption);
                    }
                }else{
                    console.log("City not found")
                }
            },
            error: function(error){
                console.log(error);
            }

        })
    }
</script>
    
@endpush
