@extends('layouts.master')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="container-fluid py-4" style="margin-top: 70px;">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <p class="text-uppercase text-sm" style="font-size: 20px;">Thông tin người dùng</p>
                    <form method="POST" action="{{ route('v1.users.store') }}" id="create-user" enctype="multipart/form-data" style="padding-left: 20px; padding-top: 20px;">
                        @csrf
                        <div class="row">
                            <div class="">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Chức vụ người dùng</label>
                                    <br>
                                    <div style="display: flex; margin-top: 5px;">
                                        <div>
                                            <input type="radio" id="admin" name="role" value="admin" checked>
                                            <label for="html">Admin</label><br>
                                        </div>
                                        <div style="margin: 0 20px;">
                                            <input type="radio" id="teacher" name="role" value="teacher">
                                            <label for="css">Giáo viên</label><br>
                                        </div>
                                        <div>
                                            <input type="radio" id="student" name="role" value="student">
                                            <label for="css">Sinh viên</label><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Họ tên <span style="color: red; ">*</span></label>
                                    <input id="name" name="name" class="form-control" type="text" placeholder="Nhập vào họ tên người dùng..." required>
                                    <div style="margin-top: 5px; " id="div_err_name">
{{--                                        <span style="font-size: smaller; color: red;">* Họ tên không được bỏ trống</span>--}}
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Email <span style="color: red; ">*</span></label>
                                    <input id="email" name="email" class="form-control" type="email" placeholder="Nhập vào email ..." required>
                                    <div style="margin-top: 5px; " id="div_err_email">
{{--                                        <span style="font-size: smaller; color: red;">* Email không được bỏ trống</span>--}}
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Mã nhân viên/Mã sinh viên <span style="color: red; ">*</span></label>
                                    <input id="code_user" name="code_user" class="form-control" type="text" placeholder="Mã sinh viên hoặc mã nhân viên" required>
                                    <div style="margin-top: 5px; " id="div_err_code_user">
{{--                                        <span style="font-size: smaller; color: red;">* Mã nhân viên/Mã sinh viên không được bỏ trống</span>--}}
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Ngày sinh <span style="color: red; ">*</span></label>
                                    <input id="date_of_birth" name="date_of_birth" class="form-control" type="date" value="" required>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Địa chỉ</label>
                                    <input id="address" name="address" class="form-control" type="text" placeholder="Thông tin địa chỉ người dùng ...">
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Số điện thoại</label>
                                    <input id="phone" name="phone" class="form-control" type="text" placeholder="số điện thoại">
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Giới tính</label>
                                    <br>
                                    <div style="display: flex; margin-top: 5px;">
                                        <div style="margin-right: 20px;">
                                            <input type="radio" id="nam" name="sex" value="nam" checked>
                                            <label for="html">Nam</label><br>
                                        </div>
                                        <div>
                                            <input type="radio" id="nu" name="sex" value="nữ">
                                            <label for="css">Nữ</label><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="">
                                    <label for="example-text-input">Ảnh thẻ </label>
                                    <input id="avatar" name="avatar" type="file">
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: 40px; margin-bottom: 20px; margin-left: -10px;">
                            <button type="submit" class="btn btn-xs btn-warning" style="padding: 5px;">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script type="module">
        $("#create-user").submit(function (e) {
            e.preventDefault();

            var formData = new FormData();

            formData.append('role', $('input[name="role"]:checked').val());
            formData.append('name', $("#name").val());
            formData.append('email', $("#email").val());
            formData.append('code_user', $("#code_user").val());
            formData.append('date_of_birth', $("#date_of_birth").val());
            formData.append('sex', $('input[name="sex"]:checked').val());
            formData.append('address', $("#address").val());
            formData.append('phone', $("#phone").val());
            var file =   $("#avatar")[0].files[0];
            if (file) formData.append('avatar', $("#avatar")[0].files[0] );

            $.ajax({
                url: '{{ route('v1.users.store') }}',
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                method: "POST",
                data: formData,
                success: function (data) {
                    if (data.response.code === 200) {
                        toastr.success('Thêm mới tài khoản thành công!', 'Success');
                        setTimeout(function () {
                            window.location = "{{ route('users.list') }}";
                        }, 1000);
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

    </script>
@endpush
