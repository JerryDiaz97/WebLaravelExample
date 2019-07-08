<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $find = $request->find;
        $criterion = $request->criterion;

        if($find == ''){
            $products = Product::join('categories','products.idcategory','=','categories.id')
            ->select('products.id','products.idcategory','products.code','products.nameProd','categories.name',
            'products.sale_price','products.stock','products.descriptionProd','products.conditionProd')            
            ->orderBy('products.id','desc')->paginate(3);
        }
        else{

            $products = Product::join('categories','products.idcategory','=','categories.id')
            ->select('products.id','products.idcategory','products.code','products.nameProd','categories.name',
            'products.sale_price','products.stock','products.descriptionProd','products.conditionProd')
            ->where('products.'.$criterion, 'like', '%'. $find .'%')            
            ->orderBy('products.id','desc')->paginate(3);
            
        }
        
        return [
            'pagination' => [
                'total'         => $products->total(), 
                'current_page'  => $products->currentPage(),
                'per_page'      => $products->perPage(),
                'last_page'     => $products->lastPage(),
                'from'          => $products->firstItem(),
                'to'            => $products->lastItem()
            ],
            'products' => $products

        ];
    }

    public function listProduct(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $find = $request->find;
        $criterion = $request->criterion;

        if($find == ''){
            $products = Product::join('categories','products.idcategory','=','categories.id')
            ->select('products.id','products.idcategory','products.code','products.nameProd','categories.name',
            'products.sale_price','products.stock','products.descriptionProd','products.conditionProd')            
            ->orderBy('products.id','desc')->paginate(10);
        }
        else{

            $products = Product::join('categories','products.idcategory','=','categories.id')
            ->select('products.id','products.idcategory','products.code','products.nameProd','categories.name',
            'products.sale_price','products.stock','products.descriptionProd','products.conditionProd')
            ->where('products.'.$criterion, 'like', '%'. $find .'%')            
            ->orderBy('products.id','desc')->paginate(10);
            
        }
        
        return ['products' => $products];
    }

    public function listProductSale(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $find = $request->find;
        $criterion = $request->criterion;

        if($find == ''){
            $products = Product::join('categories','products.idcategory','=','categories.id')
            ->select('products.id','products.idcategory','products.code','products.nameProd','categories.name',
            'products.sale_price','products.stock','products.descriptionProd','products.conditionProd')
            ->where('products.stock','>','0')            
            ->orderBy('products.id','desc')->paginate(10);
        }
        else{

            $products = Product::join('categories','products.idcategory','=','categories.id')
            ->select('products.id','products.idcategory','products.code','products.nameProd','categories.name',
            'products.sale_price','products.stock','products.descriptionProd','products.conditionProd')
            ->where('products.stock','>','0') 
            ->where('products.'.$criterion, 'like', '%'. $find .'%')            
            ->orderBy('products.id','desc')->paginate(10);
            
        }
        
        return ['products' => $products];
    }

    public function listPdf(){

        $products = Product::join('categories','products.idcategory','=','categories.id')
            ->select('products.id','products.idcategory','products.code','products.nameProd','categories.name',
            'products.sale_price','products.stock','products.descriptionProd','products.conditionProd')            
            ->orderBy('products.nameProd','desc')->get();

        $cont = Product::count();
        $pdf = \PDF::loadView('pdf.productspdf',['products'=>$products,'cont'=>$cont]);
        return $pdf->download('products.pdf');

    }    

    public function findProduct(Request $request){
        
        if (!$request->ajax()) return redirect('/');

        $filter = $request->filter;
        $products = Product::where('code','=',$filter)
        ->select('id','nameProd')->orderBy('nameProd','asc')->take(1)->get();

        return['products' => $products];

    }

    public function findProductSale(Request $request){
        
        if (!$request->ajax()) return redirect('/');

        $filter = $request->filter;
        $products = Product::where('code','=',$filter)
        ->select('id','nameProd','stock','sale_price')
        ->where('stock','>','0')->orderBy('nameProd',
        'asc')->take(1)->get();

        return['products' => $products];

    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $product = new Product();
        $product->idcategory = $request->idcategory;
        $product->code = $request->code;
        $product->nameProd = $request->nameProd;
        $product->sale_price = $request->sale_price;
        $product->stock = $request->stock;
        $product->descriptionProd = $request->descriptionProd;
        $product->conditionProd = '1';
        $product->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $product = Product::findOrFail($request->id);
        $product->idcategory = $request->idcategory;
        $product->code = $request->code;
        $product->nameProd = $request->nameProd;
        $product->sale_price = $request->sale_price;
        $product->stock = $request->stock;
        $product->descriptionProd = $request->descriptionProd;
        $product->conditionProd = '1';
        $product->save();    
    }

    
    public function activate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $product = Product::findOrFail($request->id);
        $product->conditionProd = '1';
        $product->save(); 
    }

    
    public function deactivate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $product = Product::findOrFail($request->id);
        $product->conditionProd = '0';
        $product->save(); 
    }
    
}
