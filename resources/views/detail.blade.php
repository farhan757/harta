
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    
</div>
<div class="modal-body">
    <table>
        <td style="text-align: center;"><img src="{{ url('') }}{{ $data->photo }}/{{ $data->nama_file }}" style="height: 80%; width:80%;" alt=""></td>
    </table>
    </br>
    <dl class="dl-horizontal">
        <dt class="col-4" style="text-align: left;">NIK</dt>
        <dd class="col-8" style="text-align: left;" id="name">{{ $data->nik }}</dd>
        <dt class="col-4" style="text-align: left;">Employee Name</dt>
        <dd class="col-8" style="text-align: left;" id="account">{{ $data->employee_name }}</dd>
        <dt class="col-4" style="text-align: left;">Birth Date</dt>
        <dd class="col-8" style="text-align: left;" id="spaj">{{ $data->birth_date }}</dd>
        <dt class="col-4" style="text-align: left;">Email</dt>
        <dd class="col-8" style="text-align: left;" id="to">{{ $data->email }}</dd>
        <dt class="col-4" style="text-align: left;">Phone</dt>
        <dd class="col-8" style="text-align: left;" id="sent_at">{{ $data->phone }}</dd>
        <dt class="col-4" style="text-align: left;">Jabatan</dt>
        <dd class="col-8" style="text-align: left;" id="by">{{ $data->jabatan }}</dd>
        <dt class="col-4" style="text-align: left;">Efective Date</dt>
        <dd class="col-8" style="text-align: left;" id="app_at">{{ $data->efective_date }}</dd>
    </dl>        
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
