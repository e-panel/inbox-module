<?php

namespace Modules\Inbox\Http\Controllers;

use Modules\Core\Http\Controllers\CoreController as Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon, Avatar;

use Modules\Inbox\Entities\Feedback;

class InboxController extends Controller
{
    protected $title;

    /**
     * Siapkan konstruktor controller
     * 
     * @param Model $data
     */
    public function __construct(Feedback $data) 
    {
        $this->title = __('inbox::general.title');
        $this->data = $data;

        $this->toIndex = route('epanel.inbox.index');
        $this->prefix = 'epanel.inbox';
        $this->view = 'inbox::inbox';

        $this->tDelete = __('inbox::general.deleted', ['title' => $this->title]);

        view()->share([
            'title' => $this->title, 
            'view' => $this->view, 
            'prefix' => $this->prefix
        ]);
    }

    /**
     * Tampilkan halaman utama modul yang dipilih
     * 
     * @param Request $request
     * @return Response|View
     */
    public function index(Request $request) 
    {
        $data = $this->data->latest()->get();

        if($request->has('datatable')):
            return $this->datatable($data);
        endif;

        return view("$this->view.index", compact('data'));
    }

    /**
     * Tampilkan halaman untuk menambah data
     * 
     * @return Response|View
     */
    public function create() 
    {
        return abort(404);
    }

    /**
     * Lakukan penyimpanan data ke database
     * 
     * @param Request $request
     * @return Response|View
     */
    public function store(Request $request) 
    {
        return abort(404);
    }

    /**
     * Menampilkan detail lengkap
     * 
     * @param Int $id
     * @return Response|View
     */
    public function show($id)
    {
        # GET data by UUID
        $data = $this->data->uuid($id)->firstOrFail();

        # Tampilkan View
        return view("$this->view.ajax", compact('data'));
    }

    /**
     * Tampilkan halaman perubahan data
     * 
     * @param Int $id
     * @return Response|View
     */
    public function edit($id)
    {
        return abort(404);
    }

    /**
     * Lakukan perubahan data sesuai dengan data yang diedit
     * 
     * @param Request $request
     * @param Int $id
     * @return Response|View
     */
    public function update(Request $request, $id)
    {
        return abort(404);
    }

    /**
     * Lakukan penghapusan data yang tidak diinginkan
     * 
     * @param Request $request
     * @param Int $id
     * @return Response|String
     */
    public function destroy(Request $request, $id)
    {
        if($request->has('pilihan')):
            foreach($request->pilihan as $temp):
                $each = $this->data->whereUuid($temp)->firstOrFail();
                $each->delete();
            endforeach;
            notify()->flash($this->tDelete, 'success');
            return redirect()->back();
        endif;
    }


    /**
     * Datatable API
     * 
     * @param  $data
     * @return Datatable
     */
    public function datatable($data) 
    {
        return datatables()->of($data)
            ->addColumn('pilihan', function($data) {
                $return  = '<span>';
                $return .= '    <div class="checkbox checkbox-only">';
                $return .= '        <input type="checkbox" id="pilihan['.$data->id.']" name="pilihan[]" value="'.$data->uuid.'">';
                $return .= '        <label for="pilihan['.$data->id.']"></label>';
                $return .= '    </div>';
                $return .= '</span>';
                return $return;
            })
            ->addColumn('nama', function($data) {
                $return  = $data->nama;
                return $return;
            })
            ->addColumn('telepon', function($data) {
                return '<code>'.$data->telepon.'</code>';                
            })
            ->addColumn('email', function($data) {
                return '<a href="mailto:'.$data->email.'" target="_blank"><i class="fa fa-envelope-o"></i> '.strtolower($data->email).'</a>';
            })
            ->addColumn('tanggal', function($data) {
                Carbon::setLocale('id');
                $return  = '<small>' . date('Y-m-d h:i:s', strtotime($data->created_at)) . '</small><br/>';
                $return .= str_replace('yang ', '', $data->created_at->diffForHumans());
                return $return;
            })
            ->addColumn('pesan', function($data) {
                $return  = '<a href="'.route("$this->prefix.show", $data->uuid).'" class="btn btn-default-outline" data-lity>';
                $return .= '    <i class="fa fa-search"></i> ' . strtoupper(__('inbox::table.rows.read'));
                $return .= '</a>';
                return $return;
            })
            ->rawColumns(['pilihan', 'nama', 'telepon', 'email', 'tanggal', 'pesan'])->toJson();
    }
}
