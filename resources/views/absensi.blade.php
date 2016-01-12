@extends('layout')

@section('content')
                <h1 class="page-header" style="display:none;"></h1>
                <div class="page-header">
                    <div class="form-inline">
                        <div class="form-group">
                            <label for="select-nama">Nama</label>
                            <select class="form-control" id="select-nama">
                                @foreach($employees as $employee)
                                <option value="{{ $employee->id_pegawai }}">{{ $employee->id_pegawai.' - '.$employee->nama_pegawai }}</option>
                                @endforeach
                            </select>
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <label for="select-bulan">Bulan</label>
                            <select class="form-control" id="select-bulan">
                                @foreach($months as $month)
                                <option value="{{ $month->id_waktu }}">{{ $month->bulan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-success" id="btn-check">Check</button>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xs-6 col-sm-3 text-center placeholder">
                        <img src="./img/user/user.png" width="200" height="200" class="img-responsive" alt="User Photo">
                    </div>
                    <div class="col-xs-6 col-sm-4">
                        <span class="text-muted">ID Pegawai</span>
                        <h4 id="id_pegawai">&nbsp;</h4>
                        <span class="text-muted">Nama</span>
                        <h4 id="nama">&nbsp;</h4>
                        <span class="text-muted">Jabatan</span>
                        <h4 id="jabatan">&nbsp;</h4>
                        <span class="text-muted">Cabang</span>
                        <h4 id="cabang">&nbsp;</h4>
                    </div>
                </div>
                
                <style>
                    .progress{height:30px;}
                    .progress .progress-bar{padding-top:5px;}
                    .progress-label{padding-top:5px;}
                </style>
                <h2 class="sub-header">Rekap Absensi</h2>
                <div class="row">
                    <div class="col-md-2">
                        <div class="progress-label">Kehadiran</div>                        
                    </div>
                    <div class="col-md-10">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" style="width: 0%" id="hadir_green"></div>
                            <div class="progress-bar progress-bar-danger" style="width: 0%" id="hadir_red"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="progress-label">Ketepatan Waktu</div>                        
                    </div>
                    <div class="col-md-10">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" style="width: 0%" id="tepat_green"></div>
                            <div class="progress-bar progress-bar-danger" style="width: 0%" id="tepat_red"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="progress-label">Kedisiplinan Pulang</div>                       
                    </div>
                    <div class="col-md-10">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" style="width: 0%" id="disiplin_green"></div>
                            <div class="progress-bar progress-bar-danger" style="width: 0%" id="disiplin_red"></div>
                        </div>
                    </div>
                </div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){
            // $('#loadingmessage').show();
            
            $('#btn-check').click(function(){
                var id_pegawai = $('#select-nama').val();
                var id_waktu = $('#select-bulan').val();
                getData(id_pegawai, id_waktu);
            });
            
            function getData(id_pegawai, id_waktu){
                $.ajax({
                    url: '{{ url('ajax-absensi') }}/' + id_pegawai + '/' + id_waktu,
                    type: 'GET',
                    cache: false,
                    success : function(data){
                        // console.log(data);
                        $('#id_pegawai').text(data.id_pegawai);
                        $('#nama').text(data.nama_pegawai);
                        $('#jabatan').text(data.nama_jabatan);
                        $('#cabang').text(data.nama_cabang);
                        
                        var persen_hadir_green = Math.round(data.jumlah_masuk / data.jumlah_hari * 100);
                        $('#hadir_green').css('width', persen_hadir_green + '%').text(data.jumlah_masuk + ' kali hadir (' + persen_hadir_green + '%)');
                        var persen_hadir_red = Math.round((data.jumlah_hari - data.jumlah_masuk) / data.jumlah_hari * 100);
                        $('#hadir_red').css('width', persen_hadir_red + '%').text(data.jumlah_masuk + ' kali tidak hadir (' + persen_hadir_red + '%)');
                        
                        var persen_tepat_green = Math.round((data.jumlah_masuk - data.jumlah_telat) / data.jumlah_masuk * 100);
                        $('#tepat_green').css('width', persen_tepat_green + '%').text((data.jumlah_masuk - data.jumlah_telat) + ' kali tepat waktu (' + persen_tepat_green + '%)');
                        var persen_tepat_red = Math.round(data.jumlah_telat / data.jumlah_masuk * 100);
                        $('#tepat_red').css('width', persen_tepat_red + '%').text(data.jumlah_telat + ' kali telat (' + persen_tepat_red + '%)');
                        
                        var persen_disiplin_green = Math.round((data.jumlah_masuk - data.jumlah_pulang_awal) / data.jumlah_masuk * 100);
                        $('#disiplin_green').css('width', persen_disiplin_green + '%').text((data.jumlah_masuk - data.jumlah_pulang_awal) + ' kali disiplin pulang (' + persen_disiplin_green + '%)');
                        var persen_disiplin_red = Math.round(data.jumlah_pulang_awal / data.jumlah_masuk * 100);
                        $('#disiplin_red').css('width', persen_disiplin_red + '%').text(data.jumlah_pulang_awal + ' kali pulang lebih awal (' + persen_disiplin_red + '%)');
                        
                        // $('#loadingmessage').hide();
                    }
                });
            }
        });
    </script>
@endsection