<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EmployeeController extends Controller
{
    //
    public function index()
    {
        # code...
        $data = DB::table('tb_employee')
                ->join('tb_jabatan','tb_jabatan.nik','=','tb_employee.nik')
                ->select('tb_employee.*','tb_jabatan.jabatan',DB::raw('DATE_FORMAT(tb_jabatan.efective_date,"%d/%m/%Y") as efective_date'))->get();
        return view('welcome')->with('data',$data);
    }

    public function form()
    {
        # code...
        return view('form');
    }

    public function formedit($nik)
    {
        # code...
        return view('formedit')->with('nik',$nik);
    }

    public function detail($nik)
    {
        # code...
        
        $data = DB::table('tb_employee')
        ->join('tb_jabatan','tb_jabatan.nik','=','tb_employee.nik')
        ->select('tb_employee.*','tb_jabatan.jabatan',DB::raw('DATE_FORMAT(tb_jabatan.efective_date,"%d/%m/%Y") as efective_date'),'tb_jabatan.efective_date as efect_date');
        $data = $data->where('tb_employee.nik','=',$nik);
        $data = $data->first();

        return view('detail')->with('data',$data);
    }

    public function delete(Request $r)
    {
        # code...
        DB::beginTransaction();
        try{
            DB::table('tb_employee')->where('nik',$r->nik)->delete();
            DB::table('tb_jabatan')->where('nik',$r->nik)->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil delete'
            ],200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 400,
                'message' => 'Gagal Delete'
            ],400);
        }
    }

    public function getemployee(Request $r)
    {
        # code...
        $data = DB::table('tb_employee')
                ->join('tb_jabatan','tb_jabatan.nik','=','tb_employee.nik')
                ->select('tb_employee.*','tb_jabatan.jabatan',DB::raw('DATE_FORMAT(tb_jabatan.efective_date,"%d/%m/%Y") as efective_date'),'tb_jabatan.efective_date as efect_date');
        $nik = $r->nik;

        if($nik != ''){
            $data = $data->where('tb_employee.nik','=',$nik);
            $data = $data->first();
        }else{
            $data = $data->get();
        }

        if(!is_null($data)){
            return response()->json([
                'status' => 200,
                'employee' => $data
            ],200);
        }else{
            $nik = 'NIK '.$nik;
            return response()->json([
                'status' => 404,
                'employee' => trim($nik.' Not Found')
            ],404);
        }
    }

    public function addemployee(Request $r)
    {
        # code...
        $validated = \Validator::make($r->all(),[
            'employee_name' => ['required','max:50'],
            'birth_date' => ['required','date'],
            'email' => ['required','email'],
            'phone' => ['required'],
            'photo' => ['mimes:jpg','max:10000'],
            'jabatan' => ['required','max:100'],
            'efective_date' => ['required']            
        ]); 

        if($validated->fails()){
            return response()->json([
                'status' => 400,
                'message' => $validated->errors()->getMessages()
            ],400);
        }

        $img = ''; $fileName = '';
        if($r->hasFile('photo')){
            $img = $r->file('photo');
    		$fileName = $img->getClientOriginalName();
            $pathFile = public_path() . '/storage/'.$fileName;    		
            $pathphoto = '/storage/'.$fileName;
    		$img->move($pathFile,$fileName);             
            $img = $pathphoto;
        }

        $nik = $this->getNIK();
 
        if(preg_match("/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/",$r->efective_date)){
            $efectdate = Carbon::createFromFormat('d/m/Y',$r->efective_date)->format('Y-m-d');
        }else{
            $efectdate = $r->efective_date;
        }

        DB::beginTransaction();
        try{
            DB::table('tb_employee')->insert([
                'nik' => $nik,
                'employee_name' => $r->employee_name,
                'birth_date' => $r->birth_date,
                'email' => $r->email,
                'phone' => $r->phone,
                'photo' => $img,
                'nama_file' => $fileName             
            ]);
          
            DB::table('tb_jabatan')->insert([
                'nik' => $nik,
                'jabatan' => $r->jabatan,
                'efective_date' => $efectdate
            ]); 
            DB::commit(); 
            
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil tambah employee'
            ],200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 404,
                'message' => $e->getMessage()
            ],404);            
        }
    }

    public function saveemployee(Request $r)
    {
        # code...
        $nik = $r->input('nik');
        $validated = \Validator::make($r->all(),[
            'employee_name' => ['required','max:50'],
            'birth_date' => ['required','date'],
            'email' => ['required','email'],
            'phone' => ['required'],
            'photo' => ['mimes:jpg','max:10000'],
            'jabatan' => ['required','max:100'],
            'efective_date' => ['required']            
        ]); 

        if($validated->fails()){
            return response()->json([
                'status' => 400,
                'message' => $validated->errors()->getMessages()
            ],400);
        }

        $img = ''; $fileName = '';
        $employe = DB::table('tb_employee')->where('nik',$nik)->first();
        if($r->hasFile('photo')){            
            $img = $r->file('photo');
    		$fileName = $img->getClientOriginalName();
            $pathFile = public_path() . '/storage/'.$fileName;    		
    		$pathphoto = '/storage/'.$fileName;
            $img->move($pathFile,$fileName);             
            $img = $pathphoto;
        }else{
            $img = $employe->photo;
            $fileName = $employe->nama_file;
        }
        
        if(preg_match("/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/",$r->efective_date)){
            $efectdate = Carbon::createFromFormat('d/m/Y',$r->efective_date)->format('Y-m-d');
        }else{
            $efectdate = $r->efective_date;
        }

        DB::beginTransaction();
        try{
            DB::table('tb_employee')->where('nik',$nik)->update([                
                'employee_name' => $r->employee_name,
                'birth_date' => $r->birth_date,
                'email' => $r->email,
                'phone' => $r->phone,
                'photo' => $img,
                'nama_file' => $fileName                
            ]);
            
            DB::table('tb_jabatan')->where('nik',$nik)->update([                
                'jabatan' => $r->jabatan,
                'efective_date' => $efectdate
            ]); 
            DB::commit(); 
            
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil edit employee'
            ],200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ],400);            
        }
    }

    public function exportexcel()
    {
        # code...

        $list = DB::table('tb_employee')
                ->join('tb_jabatan','tb_jabatan.nik','=','tb_employee.nik')
                ->select('tb_employee.*','tb_jabatan.jabatan',DB::raw('DATE_FORMAT(tb_jabatan.efective_date,"%d/%m/%Y") as efective_date'))->get();
        
        $objPHPExcel = new Spreadsheet();
        
        $objPHPExcel->getProperties()->setCreator("Farhan")
                                     ->setLastModifiedBy("Farhan")
                                     ->setTitle("Test Harta")
                                     ->setSubject("Office 2007 XLSX Test Harta")
                                     ->setDescription("Office 2007 XLSX Test Harta");
        
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', "NIK");
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', "EMPLOYEE NAME");
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'BIRTH DATE');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'EMAIL');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'PHONE');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'JABATAN');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'EFECTIVE DATE');
        $row=0;
        foreach ($list as $key => $value) {
            $row++;
            //echo $value->policy_no.'<p>';
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.($row+1), $value->nik);            			
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.($row+1), $value->employee_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.($row+1), $value->birth_date);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.($row+1), $value->email);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.($row+1), $value->phone);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.($row+1), $value->jabatan);
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.($row+1), $value->efective_date);
        }

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Sheet1');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Asuransi Harta.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
    }
}
