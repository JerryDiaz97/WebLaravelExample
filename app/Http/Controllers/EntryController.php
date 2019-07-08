<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Entry;
use APP\IncomeDetail;



class EntryController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $find = $request->find;
        $criterion = $request->criterion;

        if($find == ''){
            $entries = Entry::join('clients','entries.id_provider','=','clients.id')
            ->join('users', 'entries.id_user','=', 'users.id')        
            ->select('entries.id','entries.type_voucher','entries.voucher_series',
            'entries.voucher_num','entries.date_hour','entries.taxes','entries.total',
            'entries.status','clients.namec','users.user_name')
            ->orderBy('entries.id','desc')->paginate(3);
        }
        else{
            $entries = Entry::join('clients','entries.id_provider','=','clients.id')
            ->join('users', 'entries.id_user','=', 'users.id')        
            ->select('entries.id','entries.type_voucher','entries.voucher_series',
            'entries.voucher_num','entries.date_hour','entries.taxes','entries.total',
            'entries.status','clients.namec','users.user_name')
            ->where('entries.'.$criterion, 'like', '%'. $find .'%')
            ->orderBy('id','desc')->paginate(3);
        }
        
        return [
            'pagination' => [
                'total'         => $entries->total(), 
                'current_page'  => $entries->currentPage(),
                'per_page'      => $entries->perPage(),
                'last_page'     => $entries->lastPage(),
                'from'          => $entries->firstItem(),
                'to'            => $entries->lastItem()
            ],
            'entries' => $entries

        ];
    }

    public function getHeader(Request $request){

        if (!$request->ajax()) return redirect('/');
        $id = $request->id;

        $entry = Entry::join('clients','entries.id_provider','=','clients.id')
        ->join('users', 'entries.id_user','=', 'users.id')        
        ->select('entries.id','entries.type_voucher','entries.voucher_series',
        'entries.voucher_num','entries.date_hour','entries.taxes','entries.total',
        'entries.status','clients.namec','users.user_name')
        ->where('entries.id','=',$id)
        ->orderBy('entries.id','desc')->take(1)->get();
        
        return ['entry' => $entry];
    }

    public function getDetails(Request $request){

        if (!$request->ajax()) return redirect('/');
        $id = $request->id;

        $details = IncomeDetail::join('products','income_details.id_product','=','products.id')       
        ->select('income_details.amount','income_details.price','products.nameProd as product')
        ->where('income_details.id_income','=',$id)
        ->orderBy('income_details.id','desc')->get();
        
        return ['details' => $details];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            $mytime = Carbon::now('America/Mexico_City');

            $entry = new Entry();
            $entry->id_provider = $request->id_provider;
            $entry->id_user = \Auth::user()->id;
            $entry->type_voucher = $request->type_voucher;
            $entry->voucher_series = $request->voucher_series;
            $entry->voucher_num = $request->type_voucher;
            $entry->date_hour = $mytime->toDateString();
            $entry->taxes = $request->taxes;
            $entry->total = $request->total;
            $entry->status = 'Registrado';
            $entry->save();

            $details = $request->data; // Array Details

            foreach($details as $ep=>$det)
            {
                $detail = new IncomeDetail();
                $detail->id_entry = $entry->id;
                $detail->id_product = $det['id_product'];
                $detail->amount = $det['amount'];
                $detail->price= $det['price'];
                $detail->save();
            }

            DB::commit();

        } catch(Exception $e){
            DB::rollBack();
        }

    }

    public function deactivate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $entry = Entry::findOrFail($request->id);
        $entry->status = 'Anulado';
        $entry->save(); 
    }
}
