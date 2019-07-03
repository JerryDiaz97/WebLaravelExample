<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facedes\DB;
use App\Client;
use App\Provider;


class ProviderController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $find = $request->find;
        $criterion = $request->criterion;

        if($find == ''){
            $providers = Provider::join('clients','providers.id','=','clients.id')
            ->select('clients.id','clients.namec','clients.type_doc','clients.doc_num',
            'clients.address','clients.phone_num','clients.email','providers.contact',
            'providers.contact_phone')->orderBy('clients.id','desc')->paginate(3);
        }
        else{
            $providers = Provider::join('clients','providers.id','=','clients.id')
            ->select('clients.id','clients.namec','clients.type_doc','clients.doc_num',
            'clients.address','clients.phone_num','clients.email','providers.contact',
            'providers.contact_phone')->where('clients.'.$criterion, 'like', '%'. $find .'%')
            ->orderBy('id','desc')->paginate(3);
        }
        
        return [
            'pagination' => [
                'total'         => $providers->total(), 
                'current_page'  => $providers->currentPage(),
                'per_page'      => $providers->perPage(),
                'last_page'     => $providers->lastPage(),
                'from'          => $providers->firstItem(),
                'to'            => $providers->lastItem()
            ],
            'providers' => $providers

        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            $client = new Client();
            $client->namec = $request->namec;
            $client->type_doc = $request->type_doc;
            $client->doc_num = $request->doc_num;
            $client->address = $request->address;
            $client->phone_num = $request->phone_num;
            $client->email = $request->email;
            $client->save();

            $provider = new Provider();
            $provider->contact = $request->contact;
            $provider->contact_phone = $request->contact_phone;
            $provider->id = $client->id;
            $provider->save();

            DB::commit();

        } catch(Exception $e){
            DB::rollBack();
        }

    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();
            //First we search if the provider exist
            $provider = Provider::findOrFail($request->id);
            $client = Client::findOrFail($provider->id);

            $client->namec = $request->namec;
            $client->type_doc = $request->type_doc;
            $client->doc_num = $request->doc_num;
            $client->address = $request->address;
            $client->phone_num = $request->phone_num;
            $client->email = $request->email;
            $client->save();

            $provider->contact = $request->contact;
            $provider->contact_phone = $request->contact_phone;
            $provider->save();

            DB::commit();

        } catch(Exception $e){
            DB::rollBack();
        }  
    }
}
