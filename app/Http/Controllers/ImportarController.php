<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\BaseValidacion;
use App\Validacion;
use Auth;


class ImportarController extends Controller
{
    public function index(Request $request)
    {
      $resultado=null;
      if($request->isMethod('post')){
        $resultado=new \stdClass();
        if($request->file('archivo') != null && $request->file('archivo')->isValid()){
          $file = $request->file('archivo');
          $resultado->mensaje="Abriendo archivo ...<br>";
          $error=' <i class="fa fa-times"></i>';
          $ok=' <i class="fa fa-check"></i>';
          if(($fichero = fopen($file, "r")) !== FALSE) {
              $c=1;
              while (($datos = fgetcsv($fichero, 1000)) !== FALSE) {
                  // Procesar los datos.
                  // En $datos[0] está el valor del primer campo,
                  // en $datos[1] está el valor del segundo campo, etc...
                  $rfe=$datos[0];
                  $nivel=null;
                  if(array_key_exists(1,$datos)){
                    $nivel=$datos[1];
                  }
                  if(array_key_exists(2,$datos)){
                    $observaciones=$datos[2];
                  }
                  $resultado->mensaje.=$c.' -- ';
                  $resultado->mensaje.=$rfe.',';
                  if($nivel==Null){
                    $resultado->mensaje.=' Sin nivel especificado ';
                    $resultado->mensaje.=$error;
                  }else{
                    //se busca en la base de datos
                    $r=BaseValidacion::where('cRFE',$rfe)
                    ->where('cNivel',$nivel)
                    ->get();
                    if($r->count()==1){
                      $registro=$r->first();
                      if($registro->cEstatusCertificado=="Emitido" || $registro->cEstatusCertificado=="Cancelado")
                        $resultado->mensaje.='certificado '.$registro->cEstatusCertificado.$error;
                      else{
                        $v=$registro->getValidacion();
                        if($v->emisioncertificado==null){
                          $v->validatodo();
                          $v->valido=$request->get('valido');
                          $resultado->mensaje.='OK! '.$ok;
                          if(isset($observaciones) && $observaciones!=null){
                            $v->observaciones=$observaciones;
                          }
                          $v->validadopor=  Auth::user()->username;
                          $v->save();
                        }else{
                          $resultado->mensaje.="Ya procesado para emisión ".$error;
                        }


                      }
                    }
                    else{
                      $resultado->mensaje.='Tiene mas de una oportunidad en el nivel '.$error;
                    }
                  }

                  $resultado->mensaje.='<br>';
                  $c=$c+1;
              }
              fclose($fichero);
              $resultado->tipo="success";
          }
        }
        else{
          $resultado->mensaje="Sin archivo adjunto";
          $resultado->tipo="danger";
        }

      }
      return view('importar.index')->with(['resultado'=>$resultado]);
    }
}
