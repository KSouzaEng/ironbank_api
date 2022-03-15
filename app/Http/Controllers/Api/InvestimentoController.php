<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Investimento;
use Carbon\Carbon;


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

    public function getInvestiments($id){

      try{
        $investimentos = Investimento::where('user_id',$id)->orderBy('nome_investidor',"DESC")->get();

        if(!$investimentos){
            return response()->json(['error' => 'Não foi possível listar os iinvestimentos]'], 400);
          }else{
          return response()->json(['investimentos' =>  $investimentos], 200);
          }
      }catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['error' => $e->errorInfo], 500);
    }
    }

    public  function calcula ($id) {


        try{
            $investimento = Investimento::where('id',$id)->get();

            if(!$investimento){
                return response()->json(['error' => 'Não foi possível listar os iinvestimento]'], 400);
              }else{
               date_default_timezone_set('America/Manaus');
               $d = Carbon::parse($investimento[0]['data_investimento']);
            //    return response()->json(['investimento' => date('d')], 200);
               if(date('d') ==  $d->format('d')){
                  $adicao = 0.52/100;
                  $id = $investimento[0]['id'];
                  $adicao = $adicao+1;
                  $valor_investimento_ganho = $investimento[0]['valor_investimento'] * $adicao;
                  $ganho_investimento = $valor_investimento_ganho - $investimento[0]['valor_investimento'];
                  $atualiza = Investimento::where('id',$id)->update(['valor_investimento_ganho'=> $valor_investimento_ganho,'ganho_investimento' =>   $ganho_investimento ]);
                  return response()->json(['investimento' =>   $valor_investimento_ganho], 200);
               }else{
                return response()->json(['investimento' =>  $investimento[0]['valor_investimento_ganho']], 200);
               }

               //$date->format('d')
               //$investimento[0]['valor_investimento']

              }
          }catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['error' => $e->errorInfo], 500);
        }

    }
    public function  retirada(Request $request){

        try{
            $investimentos = Investimento::where('user_id',$request->id)->get();


            if(!$investimentos){
                return response()->json(['error' => 'Não foi possível listar os iinvestimento]'], 400);
              }else{
                date_default_timezone_set('America/Manaus');
               foreach ($investimentos as $key => $i) {

                 $d = Carbon::parse($i->data_investimento);

                 if(date('d') ==  $d->format('d')){

                    $Date = strtotime($i->data_investimento);
                     $um_ano = date('Y-m-d', strtotime('+1 year'));
                     $dois_anos = date('Y-m-d', strtotime('+2 year'));
                    if($Date < strtotime($um_ano)){
                        // return response()->json(['investimento' =>'tes'], 200);
                        $imposto = $i->ganho_investimento * 0.225;
                        $r =  $i->valor_investimento_ganho - $imposto;
                        $retirada = $r - $request->valor_retirada;
                          $atualiza = Investimento::where('data_investimento',$i->data_investimento)->update(['valor_investimento_ganho_retirada'=> $retirada,'valor_retirada' => $request->valor_retirada ]);

                       }else if($Date >= strtotime($um_ano) && $Date <= strtotime($dois_anos)){

                        $imposto = $i->ganho_investimento * 0.185;
                        $r =  $i->valor_investimento_ganho - $imposto;
                        $retirada = $r - $request->valor_retirada;
                          $atualiza = Investimento::where('data_investimento',$i->data_investimento)->update(['valor_investimento_ganho_retirada'=> $retirada,'valor_retirada' => $request->valor_retirada ]);
                        return response()->json(['investimento' => $retirada], 200);
                       }else if($Date > strtotime($dois_ano)){

                        $imposto = $i->ganho_investimento* 0.15    ;
                        $r =  $i->valor_investimento_ganho - $imposto;
                        $retirada = $r - $request->valor_retirada;
                          $atualiza = Investimento::where('data_investimento',$i->data_investimento)->update(['valor_investimento_ganho_retirada'=> $retirada,'valor_retirada' => $request->valor_retirada ]);
                        return response()->json(['investimento' => $retirada], 200);

                    }
                   }

                    return response()->json(['investimento' =>  $i->valor_investimento_ganho], 200);


               }
            }
          }catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['error' => $e->errorInfo], 500);
        }
    }
}
