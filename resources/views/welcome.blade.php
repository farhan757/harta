@extends('layout.layout')

@section('content')
<div class="row" style="clear: both;margin-top: 18px;">
    <div class="col-12 text-right">
        <a href="{{ route('exportexcel') }}" class="btn btn-warning mb-3">Export Excel</a>
        <a href="{{ route('newform') }}" class="btn btn-success mb-3">Add Employee</a><br><br>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table id="laravel_crud" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Employee Name</th>
                    <th>Birth Date</th>
                    <th>Jabatan</th>
                    <th>Efective Date</th>
                    <th colspan="3" style="text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $val)
                <tr id="row_{{$val->nik}}">
                    <td>{{ $val->nik  }}</td>
                    <td>{{ $val->employee_name }}</td>
                    <td>{{ $val->birth_date }}</td>
                    <td>{{ $val->jabatan }}</td>
                    <td>{{ $val->efective_date }}</td>
                    <td style="text-align: center;"><a href="{{ route('detail',$val->nik) }}" data-id="{{ $val->nik }}" data-toggle="modal" data-target="#modal-default-detail{{$val->nik}}" class="btn btn-info">Detail</a></td>
                    <td style="text-align: center;"><a href="{{ route('formedit',$val->nik) }}" data-id="{{ $val->nik }}" class="btn btn-info">Edit</a></td>
                    <td style="text-align: center;">
                        <a href="#" onclick="deleteEmpl('{{ $val->nik }}')" data-id="{{ $val->nik }}" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

    @foreach($data as $value)
    <div class="modal fade" id="modal-default-detail{{ $value->nik }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="title-detail"><span class="fa fa-edit"></span> Detail </h4>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    @endforeach
@stop

@section('js')
<script>
    function deleteEmpl(nik) {
        Swal.fire({
            title: 'Form CRUD',
            text: 'Yakin Mau Delete ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Lanjut !',
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                $.getJSON("{{ route('delete') }}", {
                    nik: nik
                }, function(json) {
                    if (json.status == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: json.message,
                            onClose: () => {
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: json.message,
                            onClose: () => {
                                window.location.reload();
                            }
                        });
                    }
                });
            }
        });
    }
</script>
@stop