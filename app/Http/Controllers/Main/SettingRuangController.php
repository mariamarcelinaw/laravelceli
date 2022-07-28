<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\MasterRuang;
use App\Models\MasterRuangApprover;
use App\Models\SysUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SettingRuangController extends Controller //Inheritance
{
    private string $module = self::class;
    private string $url = '/main/ruangan'; //alamat url
    private string $view = 'main.setting_ruang'; //template blade.php untuk tampilan setting ruang

    public function index()
    {
        $dataRuang = MasterRuang::get(); //ambil dari models MasterRuang dimana di ambil dari data tabel master_ruang 
        $parser    = ['datas' => $dataRuang, 'url' => $this->url];
        return view("$this->view.index")->with($parser);

    }


    public function create(Request $request)
    {
        $listKepalaLab = SysUser::where('user_jab_id',1)->get();//ambil dari models SysUser dimana di ambil dari data tabel sys_user
        $parser        = ['module' => $this->module, 'url' => $this->url, 'dataKepalaLab' => $listKepalaLab];
        return view("$this->view.create")->with($parser);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruang_nama'    => 'required', //list field yang harus diisi
            'ruang_lokasi'  => 'required',
            'list_approver' => 'required'
        ]);

        try {
            DB::beginTransaction(); //memulai transaksi data ke dalam database
            $dataRuang = MasterRuang::create([ //menambahkan isi database dalam tabel master_ruang
                'ruang_nama'       => $request['ruang_nama'],
                'ruang_lokasi'     => $request['ruang_lokasi'],
                'ruang_created_at' => Carbon::now(),
                'ruang_updated_at' => Carbon::now(),
            ]);

            foreach ($request['list_approver'] as $userId) {
                MasterRuangApprover::create([//menambahkan isi database dalam tabel master_ruang_approver
                    'approver_ruang_id'   => $dataRuang->ruang_id,
                    'approver_user_id'    => $userId,
                    'approver_created_at' => Carbon::now(),
                    'approver_updated_at' => Carbon::now(),
                ]);
            }

            DB::commit(); //Perubahan data di database dengan perintah commit dilakukan.

            return redirect(url($this->url))->with('message', 'Tambah Ruang Berhasil'); //notifikasi jika sudah berhasil
        } catch (\Exception $e) {
            DB::rollBack(); //Pembatalanpenulisan data di database dilakukan dengan rollback
            return redirect()->back()->withErrors(['message' => $e->getMessage()]); //notifikasi jika terjadi error
        }
    }

    public function edit(Request $request)
    {
        try {
            DB::beginTransaction(); //memulai transaksi data ke dalam database
            $dataRuang = MasterRuang::with('master_ruang_approvers')->find($request['ruang_id']); //mencari id ruang
            if (empty($dataRuang)) throw new \Exception('Data ruang tidak ditemukan');

            $listKepalaLab = SysUser::where('user_jab_id', 1)
                ->leftJoin('master_ruang_approver', function ($join) { //join tabel 
                    $join->on('user_id', '=', 'approver_user_id');
                    $join->on('approver_ruang_id', '=', DB::raw(1));
                })
                ->get();

            $parser = ['module' => $this->module, 'url' => $this->url, 'dataKepalaLab' => $listKepalaLab, 'ruang' => $dataRuang];
            return view("$this->view.edit")->with($parser);
        } catch (\Exception $e) {
            DB::rollBack();//Pembatalanpenulisan data di database dilakukan dengan rollback
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);//notifikasi jika terjadi error
        }

    }

    public function update(Request $request)
    {
        $request->validate([
            'ruang_id'      => 'required',
            'ruang_nama'    => 'required',
            'ruang_lokasi'  => 'required',
            'list_approver' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $dataRuang = MasterRuang::find($request['ruang_id']);
            if (empty($dataRuang)) throw new \Exception('Data ruang tidak ditemukan');

            // Update Data ruang
            $dataRuang->ruang_nama   = $request['ruang_nama'];
            $dataRuang->ruang_lokasi = $request['ruang_lokasi'];
            $dataRuang->save();

            // Update Approver
            // 1. Delete all data
            MasterRuangApprover::where('approver_ruang_id', $dataRuang->ruang_id)->delete();
            // 2. Reinsert data
            foreach ($request['list_approver'] as $userId) {
                MasterRuangApprover::create([
                    'approver_ruang_id'   => $dataRuang->ruang_id,
                    'approver_user_id'    => $userId,
                    'approver_created_at' => Carbon::now(),
                    'approver_updated_at' => Carbon::now(),
                ]);
            }

            DB::commit();

            return redirect(url($this->url))->with('message', 'Update Ruang Berhasil');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }
    
    public function destroy(Request $request)
    {
        MasterRuang::find($request['ruang_id'])->delete();
        return redirect(url($this->url))->with('message', 'Delete Ruang Berhasil');
    }
}
