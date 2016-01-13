<?php

namespace App\Http\Controllers;

use DB;
use DateTime;
use Illuminate\Http\Request;

class MainController extends Controller
{
    
    public function getIndex()
    {
        $data = [];
        $target = [];
        $pencapaian = [];
        foreach(DB::table('dim_waktu')->get() as $waktu){
            $select = DB::table('fact_income')->groupBy('id_waktu')
                                              ->select(DB::raw('SUM(nominal) as pencapaian, SUM(target) as target'))
                                              ->having('id_waktu', '=', $waktu->id_waktu)
                                              ->first();
            $target[] = $select->target / 1000;
            $pencapaian[] = $select->pencapaian / 1000;
        }
        $data[] = [
            'name' => 'Target',
            'data' => $target
        ];
        $data[] = [
            'name' => 'Pencapaian',
            'data' => $pencapaian
        ];
        
        $months = [];
        $tmp_times = DB::table('dim_waktu')->get();
        foreach($tmp_times as $time){
            $date = DateTime::createFromFormat('d-n-Y', '01-'.$time->bulan.'-'.$time->tahun);
            $months[] = $date->format('M Y');
        }
        
        return view('index')->with(compact(['data', 'months']));
    }
    
    public function getAbsensi()
    {
        $employees = [];
        $tmp_employees = DB::table('fact_absensi')->select('id_pegawai')->distinct()->orderBy('id_pegawai')->get();
        foreach($tmp_employees as $employee){
            $emp = DB::table('dim_pegawai')->select('nama_pegawai')->where('id_pegawai', $employee->id_pegawai)->first();
            $employees[] = [
                'id_pegawai' => $employee->id_pegawai,
                'nama_pegawai' => $emp->nama_pegawai,
            ];
        }
        $employees = array_values(array_sort($employees, function ($value) {
            return $value['nama_pegawai'];
        }));
        $employees = json_decode(json_encode($employees));
        
        $months = [];
        $tmp_times = DB::table('dim_waktu')->get();
        foreach($tmp_times as $time){
            $date = DateTime::createFromFormat('d-n-Y', '01-'.$time->bulan.'-'.$time->tahun);
            $months[] = [
                'id_waktu' => $time->id_waktu,
                'bulan' => $date->format('F Y'),
            ];
        }
        $months = json_decode(json_encode($months));
        
        return view('absensi')->with(compact(['employees', 'months']));
    }
    
    public function getPendapatan()
    {
        $employees = [];
        $tmp_employees = DB::table('fact_absensi')->select('id_pegawai')->distinct()->orderBy('id_pegawai')->get();
        foreach($tmp_employees as $employee){
            $emp = DB::table('dim_pegawai')->select('nama_pegawai')->where('id_pegawai', $employee->id_pegawai)->first();
            $employees[] = [
                'id_pegawai' => $employee->id_pegawai,
                'nama_pegawai' => $emp->nama_pegawai,
            ];
        }
        $employees = array_values(array_sort($employees, function ($value) {
            return $value['nama_pegawai'];
        }));
        $employees = json_decode(json_encode($employees));
        
        $months = [];
        $tmp_times = DB::table('dim_waktu')->get();
        foreach($tmp_times as $time){
            $date = DateTime::createFromFormat('d-n-Y', '01-'.$time->bulan.'-'.$time->tahun);
            $months[] = [
                'id_waktu' => $time->id_waktu,
                'bulan' => $date->format('F Y'),
            ];
        }
        $months = json_decode(json_encode($months));
        
        return view('pencapaian')->with(compact(['employees', 'months']));
    }
    
    public function getAjaxAbsensi(Request $request, $id, $bulan)
    {
        if($request->ajax()){
            $absensi = DB::table('fact_absensi')
                         ->join('dim_pegawai', 'fact_absensi.id_pegawai', '=', 'dim_pegawai.id_pegawai')
                         ->join('dim_jabatan', 'dim_pegawai.id_jabatan', '=', 'dim_jabatan.id_jabatan')
                         ->join('dim_cabang', 'dim_pegawai.id_cabang', '=', 'dim_cabang.id_cabang')
                         ->select('fact_absensi.*', 'dim_pegawai.nama_pegawai', 'dim_jabatan.nama_jabatan', 'dim_cabang.nama_cabang')
                         ->where('fact_absensi.id_pegawai', $id)
                         ->where('fact_absensi.id_waktu', $bulan)
                         ->first();
            $waktu = DB::table('dim_waktu')->where('id_waktu', $bulan)->first();
            if($absensi && $waktu){
                $jml_hari = cal_days_in_month(CAL_GREGORIAN, $waktu->bulan, $waktu->tahun);
                $data = [
                    'id_pegawai' => $absensi->id_pegawai,
                    'nama_pegawai' => $absensi->nama_pegawai,
                    'nama_jabatan' => $absensi->nama_jabatan,
                    'nama_cabang' => $absensi->nama_cabang,
                    'jumlah_hari' => $jml_hari,
                    'jumlah_masuk' => $absensi->jumlah_masuk,
                    'jumlah_telat' => $absensi->jumlah_telat,
                    'jumlah_pulang_awal' => $absensi->jumlah_pulang_awal,
                ];
                return response()->json($data);
            }
        }
    }
    
    public function getAjaxPendapatan(Request $request, $id, $bulan)
    {
        // if($request->ajax()){
            $income = DB::table('fact_income')
                        ->join('dim_pegawai', 'fact_income.id_pegawai', '=', 'dim_pegawai.id_pegawai')
                        ->join('dim_jabatan', 'dim_pegawai.id_jabatan', '=', 'dim_jabatan.id_jabatan')
                        ->join('dim_cabang', 'dim_pegawai.id_cabang', '=', 'dim_cabang.id_cabang')
                        ->select('fact_income.*', 'dim_pegawai.nama_pegawai', 'dim_jabatan.nama_jabatan', 'dim_cabang.nama_cabang')
                        ->where('fact_income.id_pegawai', $id)
                        ->where('fact_income.id_waktu', $bulan)
                        ->first();
            $waktu = DB::table('dim_waktu')->where('id_waktu', $bulan)->first();
            if($income && $waktu){
                $jml_hari = cal_days_in_month(CAL_GREGORIAN, $waktu->bulan, $waktu->tahun);
                $data = [
                    'id_pegawai' => $income->id_pegawai,
                    'nama_pegawai' => $income->nama_pegawai,
                    'nama_jabatan' => $income->nama_jabatan,
                    'nama_cabang' => $income->nama_cabang,
                    'target' => $income->target,
                    'nominal' => $income->nominal,
                ];
                return response()->json($data);
            }
        // }
    }
    
}