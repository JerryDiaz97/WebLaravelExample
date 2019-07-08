<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $anio=date('Y');
        $entries=DB::table('entries as i')
        ->select(DB::raw('MONTH(i.date_hour) as mes'),
        DB::raw('YEAR(i.date_hour) as anio'),
        DB::raw('SUM(i.total) as total'))
        ->whereYear('i.date_hour',$anio)
        ->groupBy(DB::raw('MONTH(i.date_hour)'),DB::raw('YEAR(i.date_hour)'))
        ->get();

        $sales=DB::table('sales as v')
        ->select(DB::raw('MONTH(v.date_hourS) as mes'),
        DB::raw('YEAR(v.date_hourS) as anio'),
        DB::raw('SUM(v.total) as total'))
        ->whereYear('v.date_hourS',$anio)
        ->groupBy(DB::raw('MONTH(v.date_hourS)'),DB::raw('YEAR(v.date_hourS)'))
        ->get();

        return ['entries'=>$entries,'sales'=>$sales,'anio'=>$anio];      

    }
}

