<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Investimento;


class InvestimentoController extends Controller
{
    public function investir(Request $request){


        try{


            $validator = Validator::make($request->all(), [
                'data_envestimento' => 'required',
                'valor_envestimento' => 'required',
           ]);
        //    $user = Auth::user();
            //  return response()->json(['sucssess' => $request->user_id], 200);
            // dd($request);
           $investir = Investimento::create([

               'user_id' => $request->user_id,
               'nome_investidor' => $request->nome_investidor,
               'data_investimento' => $request->data_investimento,
               'valor_investimento' => $request->valor_investimento
           ]);

           if(!$investir){
               return response()->json(['error' => 'Não foi possível registrar os colaborador]'], 400);
             }else{

                   return response()->json(['sucssess' =>  $investir], 200);

             }
             }catch (\Illuminate\Database\QueryException $e) {
                 return response()->json(['error' => $e->errorInfo], 500);
             }
    }

    public function getInvestiments(){

      try{
        $investimentos = Investimento::orderBy('nome_investidor',"DESC")->get();

        if(!$investimentos){
            return response()->json(['error' => 'Não foi possível listar os iinvestimentos]'], 400);
          }else{
          return response()->json(['investimentos' =>  $investimentos], 200);
          }
      }catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['error' => $e->errorInfo], 500);
    }
    }

    public  function calcula () {

        $investimentos = Investimento::get();


    }
}
