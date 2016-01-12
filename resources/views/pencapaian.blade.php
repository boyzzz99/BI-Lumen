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
                <h2 class="sub-header">Rekap Pencapaian</h2>
                <div class="row">
                    <div class="col-md-2">
                        <div class="progress-label">Target</div>                        
                    </div>
                    <div class="col-md-10">
                        <div class="progress-label" id="target">Rp 0</div><br>             
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="progress-label">Pendapatan</div>                        
                    </div>
                    <div class="col-md-10">
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger" style="width: 0%" id="income_green"></div>
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
                    url: '{{ url('ajax-pendapatan') }}/' + id_pegawai + '/' + id_waktu,
                    type: 'GET',
                    cache: false,
                    success : function(data){
                        console.log(data);
                        $('#id_pegawai').text(data.id_pegawai);
                        $('#nama').text(data.nama_pegawai);
                        $('#jabatan').text(data.nama_jabatan);
                        $('#cabang').text(data.nama_cabang);
                        
                        $('#target').text('Rp ' + data.target);
                        
                        var persen_income_green = Math.round(data.nominal / data.target * 100);
                        var persen_income_green_style = persen_income_green;
                        $('#income_green').attr('class', 'progress-bar progress-bar-danger');
                        if(persen_income_green > 100){
                            persen_income_green_style = 100;
                            $('#income_green').attr('class', 'progress-bar progress-bar-success');
                        }
                        $('#income_green').css('width', persen_income_green_style + '%').text('Rp ' + data.nominal + ' dari total target Rp ' + data.target + ' (' + persen_income_green + '%)');

                        // $('#loadingmessage').hide();
                    }
                });
            }
        });
    </script>
@endsection