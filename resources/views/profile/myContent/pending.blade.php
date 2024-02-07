@push('css')
<style>
    .action {
        margin-top: 5px;
        font-size: 12px;
    }

    .action a {
        text-decoration: none;
    }

    .action .hapus {
        color: red;
    }

</style>
@endpush

@foreach ($pending as $news)
<div class="updates-card" data-newsid="{{$news->id}}">
    <div class="row">
        <div class="col-lg-4 col-4">
            @php
                if(strpos($news->image, "assets/images/bank_image/") === false){
                    $news->image = "assets/news/images/".$news->image;
                }
            @endphp
            <div class="image-updates">
                <img src="{{asset($news->_image)}}" class="img-fluid">
            </div>
        </div>
        <div class="col-lg-8 col-8">
            <div class="description-updates">
                <div class="category">
                    <a href="" style="text-decoration:none;">
                        <span>{{$news->category->title}}</span>
                    </a>
                </div>
                <div class="content-description">
                    <a href="{{route('news.detail', ['category_slug' => $news->category->slug, 'slug' => $news->slug])}}"
                        style="text-decoration: none;">
                        @php
                            if(strpos($news->title, '<i>') !== false){
                                if(strpos($news->title, '</i>') !== true){
                                    $news->title = strip_tags($news->title);
                                    $news->title = html_entity_decode($news->title);
                                }
                            }
                        @endphp
                        <h6> <b>{!!html_entity_decode($news->title)!!}</b></h6>
                    </a>
                    <p>{!!\Str::limit($news->description, 150)!!}</p>
                    <div class="action">
                        <a href="{{route('profile.editNews' , $news->slug)}}" class="edit">Edit</a> |
                        <a href="#" type="button" data-toggle="modal" data-target="#deleteModal{{$news->id}}"
                            class="hapus">Hapus</a>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteModal{{$news->id}}" tabindex="-1" role="dialog"
                aria-labelledby="deleteModal{{$news->id}}Title" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Konfirmasi</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h6>Data yang sudah di hapus tidak dapat dikembalikan lagi, Anda yakin ingin
                                menghapus?
                            </h6>
                            <div class="row justify-content-center mt-3">
                                <div class="col-lg-7">
                                    <div class="row">
                                        <div class="col-6">
                                            <button type="button" class="btn btn-secondary btn-block btn-sm"
                                                data-dismiss="modal">Tidak</button>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" data-pointid="{{$news->id}}"
                                                class="btn btn-danger btn-block btn-sm btn-hapus">Yakin</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
{{$pending->withPath('/profile/my-content?tab=pending')->links('pagination::bootstrap-4')}}

@push('js')
<script>
    function loadMoreData(page) {
        $.ajax({
            url: '?tab=pending&page=' + page,
            type: 'get',
            timeout: 5000
            beforeSend: function () {
                $(".ajax-load").show();
            }
        }).done(function (data) {
            if (data.html == "") {
                $('.ajax-load').html("Data tidak ditemukan");
                return;
            }
            $('.ajax-load').hide();
            $('#pendingData').append(data.html);
            // console.log(data);
        }).fail(function (jqHXR, ajaxOptions, thrownError) {
            alert('Server not responding...');
        });
    }

    var page = 1;
    $(window).scroll(function () {
        if (window.innerWidth > 992) {
            if ($(window).scrollTop() + $(window).height() + 1 >= $(document).height()) {
                page++;
                loadMoreData(page);
            }
        } else {
            if ($(window).scrollTop() + $(window).height() + 70 >= $(document).height()) {
                page++;
                loadMoreData(page);
            }
        }
    });

    $(".btn-hapus").click(function () {
        let pointid = $(this).attr("data-pointid");
        $.ajax({
            type: 'DELETE',
            url: '{{route('profile.deleteNews')}}',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: pointid,
                "_token": "{{csrf_token()}}",
            },

            beforeSend: function() {
                $('#deleteModal'+ pointid).modal('hide');
            },

            success: function (data) {

                if (data.bool == true) {
                    $("div[data-newsid="+pointid+"]").remove();
                    $.toast({
                        heading: 'Data Berhasil dihapus',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 5000,
                        stack: 6
                    });
                }
            },
        });
    });

</script>
@endpush
