@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-12">
        <form name="formemployee" id="formemployee" class="form-horizontal" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="nik" id="nik">
            <div class="form-group">
                <label for="name" class="col-sm-2">Nama</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="employee_name" name="employee_name" placeholder="employee_name" required>
                    <span id="employee_nameError" class="alert-message"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2">Birth Date</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" id="birth_date" date-format="Y-m-d" name="birth_date" placeholder="birth_date" required>
                    <span id="employee_nameError" class="alert-message"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2">Email</label>
                <div class="col-sm-4">
                    <input type="email" class="form-control" id="email" name="email" placeholder="email" required>
                    <span id="emailError" class="alert-message"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2">Phone</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="phone" required>
                    <span id="phoneError" class="alert-message"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2">Photo</label>
                <div class="col-sm-4">
                    <input type="file" class="form-control" id="photo" name="photo">
                    <span id="photoError" class="alert-message"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2">Jabatan</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="jabatan" name="jabatan">
                    <span id="jabatanError" class="alert-message"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2">Efective Date</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control efective_date" id="efective_date" name="efective_date">
                    <span id="efective_dateError" class="alert-message"></span>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Submit</button>
                <a href="{{ route('home') }}" class="btn btn-default">Kembali</a>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')

<script>
    var today = new Date();
    var dd = today.getDate() - 1;
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }

    today = yyyy + '-' + mm + '-' + dd;
    document.getElementById("birth_date").setAttribute("max", today);

    $(function() {
        $('#formemployee').submit(function(e) {
            e.preventDefault();
            var url = "{{ route('addemployee') }}";
            var formData = new FormData($('#formemployee')[0]);
            //showLoad();

            Swal.fire({
                title: 'Form CRUD',
                text: 'Yakin Mau Lanjut Upload ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjut !',
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            //alert(JSON.stringify(response));
                            //hideLoad(); 
                            if (response.status == 200) {
                                Swal.fire({
                                        icon: 'success',
                                        title: response.message,
                                        onClose: () => {
                                            window.location.reload();
                                        }
                                    });
                            } else {
                                if (response.status == 400) {
                                    var errors = '';
                                    for (var i = 0, l = response.error.length; i < l; i++) {
                                        errors += "<p>" + response.error[i] + "</p>";
                                    }
                                    Swal.fire({
                                        icon: 'error',
                                        title: response.message + " ?? " + response.error,
                                        text: errors
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: response.message,
                                        onClose: () => {
                                            window.location.reload();
                                        }
                                    });
                                }
                            }
                        },
                        error: function(response) {
                            //hideLoad();
                            Swal.fire({
                                icon: 'error',
                                title: response.message,
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@stop