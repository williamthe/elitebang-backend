<?php

namespace App\Http\Controllers\Litbang;

use App\Helpers\MessageConstant;
use App\Http\Controllers\APIController;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Auth;
use App\Repositories\MainRepository;

class KelitbanganController extends APIController
{
    private $KelitabanganRepository;
    private $PelaksanaKelitbanganRepository;
    private $AttachmentRepository;
    //private $PenggunaRepository;

    public function initialize()
    {
        $this->KelitbanganRepository = \App::make('\App\Repositories\Contracts\Litbang\KelitbanganInterface');
        $this->PelaksanaKelitbanganRepository = \App::make('\App\Repositories\Contracts\Litbang\PelaksanaKelitbanganInterface');
        $this->AttachmentRepository = \App::make('\App\Repositories\Contracts\Litbang\AttachmentInterface');
       // $this->PenggunaRepository = \App::make('\App\Repositories\Contracts\Pengguna\AkunInterface');
    }

    public function list(Request $request)
    {
        $result = $this->KelitbanganRepository->with([
            'lingkup_data',
            'pelaksana',
            'attachment',
        ])->get();
        return $this->respond($result);
        $relations = [

        ];
        return $datatable = datatables()->of($this->KelitabanganRepository
            ->relation($relations)
            ->get())
            ->editColumn('tanggal', function ($list) {
                return '<span class="label  label-success label-inline " style="display: none"> '.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->timestamp.' </span>'.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d M Y');
                // return Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d/m/Y');
            })
            ->addColumn('action', function ($data) {
                $btn_edit   = "add_content_tab('pembelian_faktur_pembelian','edit_data_".$data['id']."','pembelian/faktur-pembelian/edit/".$data['id']."', 'Edit Data', '".$data['nomor']."')";
                $btn_delete = "destroy(".$data['id'].", '".$data['nomor']."','pembelian/faktur-pembelian','tbl_pembelian_faktur_pembelian')";
                return '
                      <div class="dropdown dropdown-inline">
                          <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">
                              <i class="flaticon2-layers-1 text-muted"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                              <ul class="navi flex-column navi-hover py-2">
                                  <li class="navi-item" onclick="'.$btn_edit.'">
                                          <a href="#" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="'.$btn_delete.'">
                                          <a href="#" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-trash"></i></span>
                                                  <span class="navi-text">Hapus</span>
                                          </a>
                                  </li>
                          </ul>
                          </div>
                      </div>
                    ';

            })
            ->toJson();
        $result = $this->KelitbanganRepository->all();
        return $this->respond($result);
    }

    public function listWithDatatable(Request $request)
    {
        $relations = [
            'lingkup_data',
            'documents',
            'attachment'
        ];
//        return $this->KelitbanganRepository->relation($relations)
//            ->get();
        return $datatable = datatables()->of($this->KelitbanganRepository
            ->relation($relations)
            ->get())
//            ->editColumn('tanggal', function ($list) {
//                return '<span class="label  label-success label-inline " style="display: none"> '.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->timestamp.' </span>'.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d M Y');
//                // return Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d/m/Y');
//            })
            ->editColumn('lingkup', function ($list) {
                return $list['lingkup_data'] == null ? 'Instansi Tidak Ditemukan' : $list['lingkup_data']['nama'];
            })
            ->addColumn('dokumen', function ($list) {
                //return $list['documents'];
                $docs = '';
                foreach ($list['documents'] as $dc => $doc){
                    $docs .= '<a href="/download-regulasi/'.$doc['nama'].'" style="color:inherit;">'.$doc['nama'].'</a>';
                }
                return $docs;
            })
            ->addColumn('action', function ($data) {
                $btn_edit   =  '#';
                    //"add_content_tab('pembelian_faktur_pembelian','edit_data_".$data['id']."','pembelian/faktur-pembelian/edit/".$data['id']."', 'Edit Data', '".$data['nomor']."')";
                $btn_delete = '#';
                    //"destroy(".$data['id'].", '".$data['nomor']."','pembelian/faktur-pembelian','tbl_pembelian_faktur_pembelian')";

                return '
                      <div class="dropdown dropdown-inline">
                          <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">
                              <i class="flaticon2-layers-1 text-muted"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                              <ul class="navi flex-column navi-hover py-2">
                                  <li class="navi-item" onclick="'.$btn_edit.'">
                                          <a href="/kelitbangan-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteKelitbangan('.$data['id'].')">
                                          <a href="#" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-trash"></i></span>
                                                  <span class="navi-text">Hapus</span>
                                          </a>
                                  </li>
                          </ul>
                          </div>
                      </div>
                    ';

            })
            ->rawColumns(['dokumen','action'])
            ->toJson();
    }

    public function listWithDatatableByTanggal(Request $request)
    {
        $relations = [
            'lingkup_data',
            'documents',
            'attachment'
        ];
//        return $this->KelitbanganRepository->relation($relations)
//            ->get();
        //return 'Tanggal Awal ='.$request->tanggal_awal.' Tanggal Akhir ='.$request->tanggal_akhir;
        return $datatable = datatables()->of($this->KelitbanganRepository
            ->relation($relations)
            ->where('tanggal','>=',$request->tanggal_awal)
            ->where('tanggal','<=',$request->tanggal_akhir)
            ->get())
//            ->editColumn('tanggal', function ($list) {
//                return '<span class="label  label-success label-inline " style="display: none"> '.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->timestamp.' </span>'.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d M Y');
//                // return Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d/m/Y');
//            })
            ->editColumn('lingkup', function ($list) {
                return $list['lingkup_data'] == null ? 'Instansi Tidak Ditemukan' : $list['lingkup_data']['nama'];
            })
            ->addColumn('dokumen', function ($list) {
                //return $list['documents'];
                $docs = '';
                foreach ($list['documents'] as $dc => $doc){
                    $docs .= '<a href="/download-regulasi/'.$doc['nama'].'" style="color:inherit;">'.$doc['nama'].'</a>';
                }
                return $docs;
            })
            ->addColumn('action', function ($data) {
                $btn_edit   =  '#';
                //"add_content_tab('pembelian_faktur_pembelian','edit_data_".$data['id']."','pembelian/faktur-pembelian/edit/".$data['id']."', 'Edit Data', '".$data['nomor']."')";
                $btn_delete = '#';
                //"destroy(".$data['id'].", '".$data['nomor']."','pembelian/faktur-pembelian','tbl_pembelian_faktur_pembelian')";

                return '
                      <div class="dropdown dropdown-inline">
                          <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">
                              <i class="flaticon2-layers-1 text-muted"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                              <ul class="navi flex-column navi-hover py-2">
                                  <li class="navi-item" onclick="'.$btn_edit.'">
                                          <a href="/kelitbangan-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteKelitbangan('.$data['id'].')">
                                          <a href="#" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-trash"></i></span>
                                                  <span class="navi-text">Hapus</span>
                                          </a>
                                  </li>
                          </ul>
                          </div>
                      </div>
                    ';

            })
            ->rawColumns(['dokumen','action'])
            ->toJson();
    }

    public function listWithDatatableByBidang(Request $request)
    {
        $relations = [
            'lingkup_data',
            'documents',
            'attachment'
        ];
//        return $this->KelitbanganRepository->relation($relations)
//            ->get();
        //return 'Tanggal Awal ='.$request->tanggal_awal.' Tanggal Akhir ='.$request->tanggal_akhir;
        return $datatable = datatables()->of($this->KelitbanganRepository
            ->relation($relations)
            ->where('lingkup',$request->bidang)
            ->get())
//            ->editColumn('tanggal', function ($list) {
//                return '<span class="label  label-success label-inline " style="display: none"> '.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->timestamp.' </span>'.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d M Y');
//                // return Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d/m/Y');
//            })
            ->editColumn('lingkup', function ($list) {
                return $list['lingkup_data'] == null ? 'Instansi Tidak Ditemukan' : $list['lingkup_data']['nama'];
            })
            ->addColumn('dokumen', function ($list) {
                //return $list['documents'];
                $docs = '';
                foreach ($list['documents'] as $dc => $doc){
                    $docs .= '<a href="/files-attachment/laporan-kelitbangan/'.$doc['nama'].'" style="color:inherit;" target="_blank">'.$doc['nama'].'</a>';
                }
                return $docs;
            })
            ->editColumn('rangkuman', function ($list) {
                //return $list['documents'];

                $docs = '<a href="/files-attachment/rangkuman-kelitbangan/'.$list['rangkuman'].'" style="color:inherit;" target="_blank">'.$list['rangkuman'].'</a>';
                return $docs;
            })
            ->addColumn('action', function ($data) {
                $btn_edit   =  '#';
                //"add_content_tab('pembelian_faktur_pembelian','edit_data_".$data['id']."','pembelian/faktur-pembelian/edit/".$data['id']."', 'Edit Data', '".$data['nomor']."')";
                $btn_delete = '#';
                //"destroy(".$data['id'].", '".$data['nomor']."','pembelian/faktur-pembelian','tbl_pembelian_faktur_pembelian')";

                return '
                      <div class="dropdown dropdown-inline">
                          <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">
                              <i class="flaticon2-layers-1 text-muted"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                              <ul class="navi flex-column navi-hover py-2">
                                  <li class="navi-item" onclick="'.$btn_edit.'">
                                          <a href="/kelitbangan-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteKelitbangan('.$data['id'].')">
                                          <a href="#" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-trash"></i></span>
                                                  <span class="navi-text">Hapus</span>
                                          </a>
                                  </li>
                          </ul>
                          </div>
                      </div>
                    ';

            })
            ->rawColumns(['dokumen','rangkuman','action'])
            ->toJson();
    }

    public function listByBidang(Request $request)
    {
        $relations = [
            'lingkup_data',
            'documents',
            'attachment'
        ];
//        return $this->KelitbanganRepository->relation($relations)
//            ->get();
        //return 'Tanggal Awal ='.$request->tanggal_awal.' Tanggal Akhir ='.$request->tanggal_akhir;
        return $this->KelitbanganRepository
            ->relation($relations)
            ->where('lingkup',$request->bidang)
            ->get();

    }

    public function listByBidangWithLimit(Request $request)
    {
        $relations = [
            'lingkup_data',
            'documents',
            'attachment','pelaksana'
        ];
//        return $this->KelitbanganRepository->relation($relations)
//            ->get();
        //return 'Tanggal Awal ='.$request->tanggal_awal.' Tanggal Akhir ='.$request->tanggal_akhir;
        $result = $this->KelitbanganRepository
            ->relation($relations)
            ->where('lingkup',$request->bidang)
            ->skip(intval($request->page) * 20)->take(20)
            ->get();
        return $this->respond($result);

    }

    public function listByBidangWithLimitJudul(Request $request)
    {
        $relations = [
            'lingkup_data',
            'documents',
            'attachment',
            'pelaksana'
        ];
//        return $this->KelitbanganRepository->relation($relations)
//            ->get();
        //return 'Tanggal Awal ='.$request->tanggal_awal.' Tanggal Akhir ='.$request->tanggal_akhir;
        $result = $this->KelitbanganRepository
            ->relation($relations)
            ->where('lingkup',$request->bidang)
            ->where('judul','like','%'.$request->judul.'%')

            ->skip(intval($request->page) * 20)->take(20)
            ->get();
        return $this->respond($result);

    }

    public function getById(Request $request)
    {
        $result = $this->KelitbanganRepository->with(['pelaksana','attachment'])->find($request->id);
        if ($result) {
            return $this->respond($result);
        } else {
            return $this->respondNotFound(MessageConstant::KELITBANGAN_GET_FAILED_MSG);
        }
    }

    public function getActivity(Request $request)
    {
        $log_detail = [];
        $result['log_detail'] = [];
        $result = $this->DepartemenRepository->find($request->id);
        $result['log'] = Activity::where('log_name','Departemen')
            ->where('subject_id',$request->id)->orderBy('id','desc')->get();

        $properti_baru = [];
        $new_detail = [];
        $log_detail_baru = [];
        // return $result;
        foreach ($result['log'] as $key => $value) {
            $result['log'][$key]['oleh'] = $this->PenggunaRepository
                ->find($result['log'][$key]['causer_id'])->full_name;
            $properti_baru = [];
            if ($value['description'] == 'updated') {
                // Old Attributes
                $properti_baru = [];

                if (isset($result['log'][$key]['properties']['old']['kode'])) {
                    $properti_baru['Kode'] = $result['log'][$key]['properties']['old']['kode'];
                }
                if (isset($result['log'][$key]['properties']['old']['keterangan'])) {
                    $properti_baru['Keterangan']   = $result['log'][$key]['properties']['old']['keterangan'];
                }

                $result['log'][$key]['old'] = $properti_baru;
                // End Old

                // New Attributes

                if (isset($result['log'][$key]['properties']['attributes']['kode'])) {
                    $properti_baru['Kode'] = $result['log'][$key]['properties']['attributes']['kode'];
                }
                if (isset($result['log'][$key]['properties']['attributes']['keterangan'])) {
                    $properti_baru['Keterangan']   = $result['log'][$key]['properties']['attributes']['keterangan'];
                }

                $result['log'][$key]['new'] = $properti_baru;
                // End New

            }else {

                // New Attributes
                $properti_baru = [];

                $properti_baru['Kode'] = $result['log'][$key]['properties']['attributes']['kode'];
                $properti_baru['Keterangan']   = $result['log'][$key]['properties']['attributes']['keterangan'];

                $result['log'][$key]['new'] = $properti_baru;
                // End New
            }
        }

        $result['show_properties'] = ['Harga Jasa','kuantitas','Harga','Kode Pajak'];

        return $this->respond($result);
    }

    public function create(Request $request)
    {

        $validator = $this->KelitbanganRepository->validate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();
            $result = $this->KelitbanganRepository->create(
                [
                    'nomor' =>  $request->nomor,
                    'tanggal' => $request->tanggal,
                    'judul'   => $request->judul,
                    'lingkup' =>  $request->lingkup,
                    'abstrak' => $request->abstrak,
                    'tindak_lanjut'   => $request->tindak_lanjut,
                    'tipe' => $request->tipe,
                    'rangkuman' => $request->rangkuman,
                ]
            );
            if ($result->count()) {
                if(isset($request->pelaksana)){
                    if ( is_string($request->pelaksana) ){
                        $pelaksana = json_decode($request->pelaksana);
                    }else{
                        $pelaksana = $request->pelaksana;
                    }
                    foreach ($pelaksana as $item => $nama) {
                        if ($nama == null or $nama == ''){
                            return $this->respondInternalError($rr= null,'Nama Pelaksana Dibutuhkan!');
                        }
                        $this->PelaksanaKelitbanganRepository->create([
                            'kelitbangan_id' => $result->id,
                            'nama'       => $nama,
                        ]);
                    }

                }else{
                    return $this->respondInternalError($rr= null,'Pelaksana Dibutuhkan!');
                }
                $attachment = null;
                if (isset($request->attachment)){
                    if (is_string($request->attachment)){
                        $attachment = json_decode($request->attachment,true);
                    }else{
                        $attachment = $request->attachment;
                    }
                    if (count($attachment) > 0){
                        foreach ($attachment as $item => $att) {
                            $this->AttachmentRepository->create([
                                'kelitbangan_id' => $result->id,
                                'nama'       => $att['nama'],
                                'url'        => $att['url'],
                                'tipe'       => $att['tipe']
                            ]);
                        }
                    }
                }

                DB::commit();
                return $this->respondCreated($result, MessageConstant::KELITBANGAN_CREATE_SUCCESS_MSG);
            } else {
                DB::rollBack();
                return $this->respondConflict();
            }
        }
    }

    public function update(Request $request)
    {
        //return $request->all();
        $validator = $this->KelitbanganRepository->validateUpdate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();
            $deletePelaksana = $this->PelaksanaKelitbanganRepository
                ->where('kelitbangan_id',$request->id)
                ->delete();
            $deleteAttch = $this->AttachmentRepository
                ->where('kelitbangan_id',$request->id)
                ->delete();
            $result = $this->KelitbanganRepository
                ->where('id',$request->id)
                ->update(
                    [
                        'nomor' =>  $request->nomor,
                        'tanggal' => $request->tanggal,
                        'judul'   => $request->judul,
                        'lingkup' =>  $request->lingkup,
                        'abstrak' => $request->abstrak,
                        'tindak_lanjut'   => $request->tindak_lanjut,
                        'tipe' => $request->tipe,
                        'rangkuman' => $request->rangkuman,
                    ]
                );
            if ($result) {
                if(isset($request->pelaksana)){

                    if ( is_string($request->pelaksana) ){
                        $pelaksana = json_decode($request->pelaksana);
                    }else{
                        $pelaksana = $request->pelaksana;
                    }
                   // return $this->respondInternalError($rr= null,$pelaksana);
                    foreach ($pelaksana as $item => $nama) {
                        if ($nama == null or $nama == ''){
                            return $this->respondInternalError($rr= null,'Nama Pelaksana Dibutuhkan!');
                        }
                        $this->PelaksanaKelitbanganRepository->create([
                            'kelitbangan_id' => $request->id,
                            'nama'       => $nama,
                        ]);
                    }

                }else{
                    return $this->respondInternalError($rr= null,'Pelaksana Dibutuhkan!');
                }
                $attachment = null;
                if (isset($request->attachment)){
                    if (is_string($request->attachment)){
                        $attachment = json_decode($request->attachment,true);
                    }else{
                        $attachment = $request->attachment;
                    }
                    if (count($attachment) > 0){
                        foreach ($attachment as $item => $att) {
                            $this->AttachmentRepository->create([
                                'kelitbangan_id' => $request->id,
                                'nama'       => $att['nama'],
                                'url'        => $att['url'],
                                'tipe'       => $att['tipe']
                            ]);
                        }
                    }
                }

                DB::commit();
                return $this->respondCreated($result, MessageConstant::KELITBANGAN_UPDATE_SUCCESS_MSG);
            } else {
                DB::rollBack();
                return $this->respondNotFound();
            }
        }
    }

    public function delete(Request $request)
    {

        $result = $this->KelitbanganRepository->delete($request->id);
        if ($result) {
            $this->AttachmentRepository->where('kelitbangan_id',$request->id)->delete();
            $this->PelaksanaKelitbanganRepository->where('kelitbangan_id',$request->id)->delete();
            return $this->respondOk(MessageConstant::KELITBANGAN_DELETE_SUCCESS_MSG);
        } else {
            return $this->respondNotFound(MessageConstant::KELITBANGAN_DELETE_FAILED_MSG);
        }
    }

    public function terkini(){
        $result = $this->KelitbanganRepository
            ->limit(4)
            ->orderBy('id','desc')
            ->get();
        return $this->respond($result);
    }

    public function getAutoNomor(){
        $data = $this->KelitbanganRepository->withTrashed()->get();
        return MainRepository::generateCode($data,'KEL-');
    }
}
