@extends('layouts.master')

@section('content')
    <div>
        <h3>Quản lý thông báo</h3>
        <div style="margin-top: 20px; margin-bottom: 20px;">
            <button type="button" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#createNoti" style="padding: 5px">
                Create
            </button>
        </div>
        <table class="table table-bordered" id="notifications-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Content</th>
                <th style="width: 15%;">Action</th>
            </tr>
            </thead>
        </table>
    </div>

    <!-- Modal create -->
    <div class="modal fade" id="createNoti" tabindex="-1" role="dialog" data-target=".bd-example-modal-" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="display: flex; justify-content: center">
                    <h3 class="modal-title" id="exampleModalLabel">Nội dung thông báo</h3>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="create-notification" enctype="multipart/form-data" style="margin: 0px 20px;">
                        @csrf
                        <div class="row">
                            <input id="id_noti" name="id_noti" class="form-control" type="text" style="display: none;">
                            <div class="">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Tên môn học</label>
                                    <input id="title" name="title" class="form-control" type="text" placeholder="Nhập vào tiêu đề thông báo ..." required>
                                    <div style="margin-top: 5px; " id="div_err_title">

                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Mã môn học</label>
                                    <textarea rows="25" id="content" name="content" class="form-control" type="text" placeholder="Nhập vào nội dung thông báo ... " required></textarea>
                                    <div style="margin-top: 5px; " id="div_err_content">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: 20px; margin-bottom: 20px; display: flex; justify-content: right; font-size: small;">
                            <button type="submit" class="btn btn-xs btn-warning" style="padding: 8px;" id="create-btn">Create</button>
                            <button type="submit" class="btn btn-xs btn-warning" style="padding: 8px; display: none;" id="update-btn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script>

        function setValue(id, title, content){
            $('#id_noti').val(id);
            $('#content').val(content);
            $('#title').val(title);
            $("#create-btn").hide();
            $("#update-btn").show();
        };

        $(function() {
            $('#notifications-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('v1.notifications.index') !!}',
                    headers: {
                        "Authorization": "Bearer " + localStorage.getItem("token"),
                    },
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'title', name: 'title' },
                    { data: 'content', name: 'content' },
                    // {
                    //     data: 'content', render: function (content){
                    //         return '<p style="display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; overflow: hidden; width: 500px;">' + content +'</p>'
                    //     }
                    // },
                    { data: 'action', name: '', orderable: false, searchable: false},
                ]
            });
        });

        $("#create-notification").submit(function (e) {
            e.preventDefault();

            var formData = new FormData();

            formData.append('title', $("#title").val());
            formData.append('content', $("#content").val());

            let url = '{{ route('v1.notifications.store') }}';
            let noti = 'Thêm mới thông báo thành công!';
            if ($('#id_noti').val()){
                url = '{{ env('URL_API') }}' + 'notifications/update/' + $('#id_noti').val();
                noti = 'Cập nhật thông báo thành công!';
            }

            $.ajax({
                url: url,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                method: "POST",
                data: formData,
                success: function (data) {
                    if (data.response.code === 200) {
                        toastr.success(noti, 'Success');
                        window.location = "{{ route('notifications.list') }}";
                    }
                },
                error: function (err) {
                    if (err.status === 422) {
                        let errList = err.responseJSON.Err_Message;

                        for (let key in errList) {
                            $("#div_err_" + key).html(`<p style="color: red; font-size: small;">* ` + errList[key] + `</p>`);
                        }

                        console.log(err);
                    }else if (err.status === 500){
                        toastr.error(err.statusText);
                        console.log(err);
                    }
                },
            });
            return true;
        });

        function deleteNoti(id){
            if (confirm('Ban co muon xoa khong?') === true) {
                $.ajax({
                    url: '{{ env('URL_API') }}' + `notifications/` + id,
                    headers: {
                        'X-CSRF-TOKEN': '{{ @csrf_token() }}',
                        "Authorization": "Bearer " + localStorage.getItem("token"),
                    },
                    method: "DELETE",
                    success: function (data) {
                        window.location = "{{ route('notifications.list') }}";
                    },
                    error: function (err) {
                        alert('error');
                        console.log(err);
                    }
                });
            }
        }

    </script>
@endpush
