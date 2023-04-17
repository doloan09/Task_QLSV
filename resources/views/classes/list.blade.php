@extends('layouts.master')
@section('title', 'Quản lý lớp học phần')

<style>
    #classes-table_filter input{
        padding: 6px 12px;
        border-radius: 4px;
        border: 1px solid #ccc;
        margin-bottom: 10px;
    }

    #classes-table_filter input:focus-visible{
        outline: none;
    }

    #classes-table_length select{
        padding: 6px 12px;
        border-radius: 4px;
        border: 1px solid #ccc;
        background-color: white;
    }

    #classes-table_length select:focus-visible{
        outline: none;
    }

    #classes-table_paginate {
        margin-top: 20px;
    }

</style>

@section('content')
    <div>
        <p style="color: #707070; font-size: 25px;">Quản lý lớp học phần</p>
        <div style="margin-top: 20px; margin-bottom: 20px; display: flex; justify-content: right;">
            <div class="dropdown" style="margin-right: 10px;">
                <button class="dropbtn">Bộ lọc</button>
                <div class="dropdown-content" style="padding: 30px 20px; margin-left: -50px;">
                    <div>
                        <div style="margin-right: 10px;">Kỳ học:</div>
                        <select id="filter_semester" style="border: 1px #ccc solid; border-radius: 5px; background-color: white; margin-top: 10px; padding: 5px;">

                        </select>
                    </div>
                    <div>
                        <div style="margin-right: 10px; margin-top: 20px;">Môn học:</div>
                        <select id="filter_subject" style="border: 1px #ccc solid; border-radius: 5px; background-color: white; margin-top: 10px; padding: 5px;">

                        </select>
                    </div>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Thao tác</button>
                <div class="dropdown-content" style="min-width: 120px;">
                    <p data-toggle="modal" data-target="#createClass">Thêm mới</p>
                </div>
            </div>
        </div>
        <table class="table table-bordered" id="classes-table">
            <thead>
            <tr>
                <th style="width: 20px;">Id</th>
                <th>Môn học</th>
                <th>Tên lớp học phần</th>
                <th>Mã lớp học phần</th>
                <th>Kỳ học</th>
                <th style="width: 10%;">Action</th>
            </tr>
            </thead>
        </table>
    </div>

    <!-- Modal create -->
    <div class="modal fade" id="createClass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="display: flex; justify-content: center">
                    <h3 class="modal-title" id="exampleModalLabel">Thông tin lớp học phần</h3>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="create-class" enctype="multipart/form-data" style="margin: 0px 20px;">
                        @csrf
                        <div class="row">
                            <input id="id_class" name="id_class" class="form-control" type="text" style="display: none;">
                            <div class="">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Tên lớp học phần</label>
                                    <input id="name_class" name="name_class" class="form-control" type="text" placeholder="Nhập vào tên lớp học phần..." required>
                                    <div style="margin-top: 5px; " id="div_err_name_class">

                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Mã lớp học phần</label>
                                    <input id="code_class" name="code_class" class="form-control" type="text" placeholder="Nhập vào mã lớp học phần ... " required>
                                    <div style="margin-top: 5px; " id="div_err_code_class">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="padding-left: 0;">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label" style="width: 100%;">Môn học</label>
                                    <select style="width: 100%; padding: 6px;" id="id_subject">

                                    </select>
                                    <div style="margin-top: 5px; " id="div_err_id_subject">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="padding-right: 0;">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label" style="width: 100%;">Kỳ học</label>
                                    <select style="width: 100%; padding: 6px;" id="id_semester">

                                    </select>
                                    <div style="margin-top: 5px; " id="div_err_id_semester">

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
@endsection

@push('scripts')
    <script>

        getSemester();
        getSubject();

        $('#btn').on('click', function (){
            getSubject();
            getSemester();
        });

        $('#filter_semester').on('change', function() {
            let semester = document.getElementById('filter_semester').value;
            getData(semester);
        });

        $('#filter_subject').on('change', function() {
            let subject = document.getElementById('filter_subject').value;
            getDataBySubject(subject);
        });

        function setValue(id, name, code, id_subject, id_semester){
            $('#id_class').val(id);
            $('#name_class').val(name);
            $('#code_class').val(code);
            $('#id_subject').val(id_subject);
            $('#id_semester').val(id_semester);
            $("#create-btn").hide();
            $("#update-btn").show();
        };

        function getSubject(){
            $.ajax({
                url: '{{ route('v1.subjects.index') }}',
                processData: false,
                contentType: false,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("token"),
                },
                method: "GET",
                success: function (data) {
                    let list = data.data;
                    let str = '';
                    let str2 = '<option value="">--Tất cả--</option>';
                    for (let item in list){
                        str += '<option value="' + list[item]['id'] + '">' + list[item]['name_subject'] + '</option>'
                    }

                    str2 += str;
                    $('#id_subject').html(str);
                    $('#filter_subject').html(str2);
                },
                error: function (err) {
                    toastr.error(err.statusText);
                    console.log(err);
                },
            });
        }

        function getSemester(){
            $.ajax({
                url: '{{ route('v1.semesters.index') }}',
                processData: false,
                contentType: false,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("token"),
                },
                method: "GET",
                success: function (data) {
                    let list = data.data;
                    let str = '';
                    let str2 = '<option value="">- Tất cả -</option>';
                    for (let item in list){
                        str += '<option value="' + list[item]['id'] + '">' + list[item]['name_semester'] + '_' + list[item]['year_semester'] + '</option>'
                    }

                    str2 += str;
                    $('#id_semester').html(str);
                    $('#filter_semester').html(str2);
                },
                error: function (err) {
                    toastr.error(err.statusText);
                    console.log(err);
                },
            });
        }

        var tableClass = $('#classes-table').DataTable({
            processing: true,
            serverSide: true,
            "bInfo" : false,
            language: {
                paginate: {
                    next: '>',
                    previous: '<'
                }
            },
            ajax: {
                url: '{{ env('URL_API') }}' + 'classes?id_semester=',
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("token"),
                },
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name_subject', name: 'id_subject'},
                {data: 'name_class', name: 'name_class'},
                {data: 'code_class', name: 'code_class'},
                {data: 'name_semester', name: 'id_semester'},
                {data: 'action', name: '', orderable: false, searchable: false},
            ]
        });

        function getData(semester = '') {
            tableClass
                .columns([4])
                .search(semester)
                .draw();
        }

        function getDataBySubject(subject = '') {
            tableClass
                .columns([1])
                .search(subject)
                .draw();
        }

        $("#create-class").submit(function (e) {
            e.preventDefault();

            var formData = new FormData();

            formData.append('name_class', $("#name_class").val());
            formData.append('code_class', $("#code_class").val());
            formData.append('id_subject', $("#id_subject").val());
            formData.append('id_semester', $("#id_semester").val());

            let url = '{{ route('v1.classes.store') }}';
            let noti = 'Thêm mới lớp học phần thành công!';
            if ($('#id_class').val()){
                url = '{{ env('URL_API') }}' + 'classes/update/' + $('#id_class').val();
                noti = 'Cập nhật thông tin lớp học phần thành công!';
            }

            $.ajax({
                url: url,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                    "Authorization": "Bearer " + localStorage.getItem("token"),
                },
                method: "POST",
                data: formData,
                success: function (data) {
                    if (data.response.code === 200) {
                        toastr.success(noti, 'Success');
                        window.location = "{{ route('classes.list') }}";
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

        function deleteClass(id){
            if (confirm('Ban co muon xoa khong?') === true) {
                $.ajax({
                    url: '{{ env('URL_API') }}' + `classes/` + id,
                    headers: {
                        'X-CSRF-TOKEN': '{{ @csrf_token() }}',
                        "Authorization": "Bearer " + localStorage.getItem("token"),
                    },
                    method: "DELETE",
                    success: function (data) {
                        if (data.response.code === 500){
                            toastr.error('Bạn không thể xóa môn học này!', 'Error');
                        }else {
                            window.location = "{{ route('classes.list') }}";
                        }
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
