<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Sale;
use App\SalesDetail;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $find = $request->find;
        $criterion = $request->criterion;

        if($find == ''){
            $sales = Sale::join('clients','sales.id_client','=','clients.id')
            ->join('users', 'sales.id_user','=', 'users.id')        
            ->select('sales.id','sales.type_voucherS','sales.voucher_seriesS',
            'sales.voucher_numS','sales.date_hourS','sales.taxesS','sales.totalS',
            'sales.status','clients.namec','users.user_name')
            ->orderBy('sales.id','desc')->paginate(3);
        }
        else{
            $sales = Sale::join('clients','sales.id_client','=','clients.id')
            ->join('users', 'sales.id_user','=', 'users.id')        
            ->select('sales.id','sales.type_voucherS','sales.voucher_seriesS',
            'sales.voucher_numS','sales.date_hourS','sales.taxesS','sales.totalS',
            'sales.status','clients.namec','users.user_name')
            ->where('sales.'.$criterion, 'like', '%'. $find .'%')
            ->orderBy('sales.id','desc')->paginate(3);
        }
        
        return [
            'pagination' => [
                'total'         => $sales->total(), 
                'current_page'  => $sales->currentPage(),
                'per_page'      => $sales->perPage(),
                'last_page'     => $sales->lastPage(),
                'from'          => $sales->firstItem(),
                'to'            => $sales->lastItem()
            ],
            'sales' => $sales

        ];
    }

    public function getHeader(Request $request){

        if (!$request->ajax()) return redirect('/');
        $id = $request->id;

        $sale = Sale::join('clients','sales.id_client','=','clients.id')
        ->join('users', 'sales.id_user','=', 'users.id')        
        ->select('sales.id','sales.type_voucherS','sales.voucher_seriesS',
        'sales.voucher_numS','sales.date_hourS','sales.taxesS','sales.totalS',
        'sales.status','clients.namec','users.user_name')
        ->where('sales.id','=',$id)
        ->orderBy('sales.id','desc')->take(1)->get();
        
        return ['sale' => $sale];
    }

    public function getDetails(Request $request){

        if (!$request->ajax()) return redirect('/');
        $id = $request->id;

        $details = SalesDetail::join('products','sales_details.id_product','=','products.id')       
        ->select('sales_details.amount','sales_details.price','sales_details.discount',
        'products.nameProd as product')->where('sales_details.id_venta','=',$id)
        ->orderBy('sales_details.id','desc')->get();
        
        return ['details' => $details];
    }

    public function pdf(Request $request, $id){
        $sale = Sale::join('clients','sales.id_client','=','clients.id')
        ->join('users', 'sales.id_user','=', 'users.id')        
        ->select('sales.id','sales.type_voucherS','sales.voucher_seriesS',
        'sales.voucher_numS','sales.created_at','sales.taxesS','sales.totalS',
        'sales.status','clients.namec','clients.type_doc','clients.doc_num',
        'clients.address','clients.email','clients.phone_num','users.user_name')
        ->where('sales.id', '=', $id)
        ->orderBy('sales.id','desc')->take(1)->get();

        $details = SalesDetail::join('products','sales_details.id_product','=','products.id')
        ->select('sales_details.amount','sales_details.price','sales_details.discount',
        'products.nameProd as product')->where('sales_details.id_venta','=',$id)
        ->orderBy('sales_details.id','desc')->get();

        $salenum = Sale::select('voucher_numS')->where('id',$id)->get();

        $pdf = \PDF::loadView('pdf.sale', ['sale'=>$sale,'details'=>$details]);
        return $pdf->download('sale-'.$salenum[0]->voucher_numS.'.pdf');

    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            $mytime = Carbon::now('America/Mexico_City');

            $sale = new Sale();
            $sale->id_client = $request->id_client;
            $sale->id_user = \Auth::user()->id;
            $sale->type_voucherS = $request->type_voucherS;
            $sale->voucher_seriesS = $request->voucher_seriesS;
            $sale->voucher_numS = $request->type_voucherS;
            $sale->date_hourS = $mytime->toDateString();
            $sale->taxesS = $request->taxesS;
            $sale->totalS = $request->totalS;
            $sale->status = 'Registrado';
            $sale->save();

            $details = $request->data; // Array Details

            foreach($details as $ep=>$det)
            {
                $detail = new SalesDetail();
                $detail->id_venta = $sale->id;
                $detail->id_product = $det['id_product'];
                $detail->amount = $det['amount'];
                $detail->price = $det['price'];
                $detail->discount = $det['discount'];
                $detail->save();
            }

            DB::commit();

            return[
                'id' => $sale->id
            ];

        } catch(Exception $e){
            DB::rollBack();
        }

    }

    public function deactivate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $sale = Sale::findOrFail($request->id);
        $sale->status = 'Anulado';
        $sale->save(); 
    }
}
