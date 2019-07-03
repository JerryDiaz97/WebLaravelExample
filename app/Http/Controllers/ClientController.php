<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $find = $request->find;
        $criterion = $request->criterion;

        if($find == ''){
            $clients = Client::orderBy('id','desc')->paginate(3);
        }
        else{
            $clients = Client::where($criterion, 'like', '%'. $find .'%')->orderBy('id','desc')->paginate(3);
        }
        
        return [
            'pagination' => [
                'total'         => $clients->total(), 
                'current_page'  => $clients->currentPage(),
                'per_page'      => $clients->perPage(),
                'last_page'     => $clients->lastPage(),
                'from'          => $clients->firstItem(),
                'to'            => $clients->lastItem()
            ],
            'clients' => $clients

        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $client = new Client();
        $client->namec = $request->namec;
        $client->type_doc = $request->type_doc;
        $client->doc_num = $request->doc_num;
        $client->address = $request->address;
        $client->phone_num = $request->phone_num;
        $client->email = $request->email;
        $client->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $client = Client::findOrFail($request->id);
        $client->namec = $request->namec;
        $client->type_doc = $request->type_doc;
        $client->doc_num = $request->doc_num;
        $client->address = $request->address;
        $client->phone_num = $request->phone_num;
        $client->email = $request->email;
        $client->save();    
    }

}
