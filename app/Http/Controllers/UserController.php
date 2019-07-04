<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Client;


class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $find = $request->find;
        $criterion = $request->criterion;

        if($find == ''){
            $clients = User::join('clients','users.id','=','clients.id')
            ->join('roles', 'users.id_role','=', 'roles.id')        
            ->select('clients.id','clients.namec','clients.type_doc','clients.doc_num',
            'clients.address','clients.phone_num','clients.email','users.user_name',
            'users.password','users.condition','users.id_role','roles.namer')
            ->orderBy('clients.id','desc')->paginate(3);
        }
        else{
            $clients = User::join('clients','users.id','=','clients.id')
            ->join('roles', 'users.id_role','=', 'roles.id')        
            ->select('clients.id','clients.namec','clients.type_doc','clients.doc_num',
            'clients.address','clients.phone_num','clients.email','users.user_name',
            'users.password','users.condition','users.id_role','roles.namer')
            ->where('clients.'.$criterion, 'like', '%'. $find .'%')
            ->orderBy('id','desc')->paginate(3);
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

            $user = new User();
            $user->user_name = $request->user_name;
            $user->password = bcrypt($request->password);
            $user->condition = '1';
            $user->id_role = $request->id_role;
            $user->id = $client->id;
            $user->save();

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
            $user = User::findOrFail($request->id);

            $client = Client::findOrFail($user->id);

            $client->namec = $request->namec;
            $client->type_doc = $request->type_doc;
            $client->doc_num = $request->doc_num;
            $client->address = $request->address;
            $client->phone_num = $request->phone_num;
            $client->email = $request->email;
            $client->save();

            $user->user_name = $request->user_name;
            $user->password = bcrypt($request->password);
            $user->condition = '1';
            $user->id_role = $request->id_role;
            $user->save();

            DB::commit();

        } catch(Exception $e){
            DB::rollBack();
        }  
    }

    public function activate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condition = '1';
        $user->save(); 
    }

    
    public function deactivate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condition = '0';
        $user->save(); 
    }
}
