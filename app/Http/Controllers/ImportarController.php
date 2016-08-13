<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\BaseValidacion;
use App\Validacion;
use App\User;
use Auth;


class ImportarController extends Controller
{
    public function base(Request $request)
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
              while (($datos = fgetcsv($fichero)) !== FALSE) {
                  // Procesar los datos.
                  // En $datos[0] está el valor del primer campo,
                  // en $datos[1] está el valor del segundo campo, etc...
                  $rfe=$datos[0];
                  $nivel=null;
                  if(array_key_exists(1,$datos)){
                    $nivel=$datos[1];
                  }
                  $resultado->mensaje.=$c.' -- ';
                  $resultado->mensaje.=$rfe.',';
                  if($nivel==Null){
                    $resultado->mensaje.=' Sin nivel especificado ';
                    $resultado->mensaje.=$error;
                  }else{
                    //se busca en la base de datos
                    $registro=BaseValidacion::where('cRFE',$rfe)
                    ->where('cNivel',$nivel)
                    ->first();
                    if($registro!=null){
                      if($registro->cEstatusCertificado=="Emitido" || $registro->cEstatusCertificado=="Cancelado"){
                        $resultado->mensaje.='certificado '.$registro->cEstatusCertificado.$error;
                      }
                      elseif($registro->dCalFinal<6){
                        $resultado->mensaje.="El registro no cuenta con calificación aprobatoria ".$error;
                      }
                      else{
                        $v=$registro->getValidacion();
                        if($v->emisioncertificado==null || $v->emisioncertificado=='0'){
                          if($request->get('valido')==1){
                            $v->fechaverificacion=date('Y-m-d');
                            //verificación
                            $v->verificado=1;
                            $v->verificadopor=Auth::user()->id;
                            $v->fechaverificacion=date('Y-m-d');
                          }
                          $v->valido=$request->get('valido');
                          $resultado->mensaje.='OK! '.$ok;
                          if(isset($observaciones) && $observaciones!=null){
                            $v->observaciones=$observaciones;
                          }
                          if($request->get('validadopor')){
                            $v->validadopor=$request->get('validadopor');
                          }
                          else {
                            $v->validadopor=Auth::user()->id;
                          }
                          $v->fechavalidacion=date('Y-m-d');
                          $v->save();
                        }else{
                          $resultado->mensaje.="Registro ya procesado ".$error;
                        }


                      }
                    }
                    else{
                      $resultado->mensaje.='No se encuentra en la base de Power BI '.$error;
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
      $usuarios= User::lists('name','id');
      return view('importar.base')->with(['resultado'=>$resultado, 'usuarios'=>$usuarios]);
    }

    public function certificados(Request $request)
    {
      $resultado=null;
      if($request->isMethod('post')){
        $resultado=new \stdClass();
        $emision=$request->get('emisioncertificado');
        if($emision!=null){

          switch ($emision) {
            case '1':
              $estado='Emitido';
              break;
            case '2':
              $estado='Cancelado';
              break;
            case '3':
              $estado='Entregado';
              break;

            default:
              # code...
              break;
          }
        }
        if($request->file('archivo') != null && $request->file('archivo')->isValid() && isset($estado)){
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
                    $folio=$datos[2];
                  }
                  $resultado->mensaje.=$c.' -- ';
                  $resultado->mensaje.=$rfe.',';
                  if($nivel==Null){
                    $resultado->mensaje.=' Sin nivel especificado ';
                    $resultado->mensaje.=$error;
                  }else{
                    //se busca en la base de datos
                    $registro=BaseValidacion::where('cRFE',$rfe)
                    ->where('cNivel',$nivel)
                    ->first();
                    if($registro!=null){
                      if($registro->cEstatusCertificado=="Emitido" || $registro->cEstatusCertificado=="Cancelado"){
                        $resultado->mensaje.='certificado ya procesado -- '.$registro->cEstatusCertificado.$error;
                      }
                      elseif($registro->dCalFinal<6){
                        $resultado->mensaje.="El registro no cuenta con calificación aprobatoria ".$error;
                      }
                      else{
                        $v=$registro->getValidacion();
                        if($v->valido=='1'){
                          $v->emisioncertificado=$emision;
                          $registro->cEstatusCertificado=$estado;
                          if(isset($folio)){
                            $registro->cFolioCertificado=$folio;
                            $v->folio=$folio;
                          }
                          $v->fechaemision=date('Y-m-d');
                          $resultado->mensaje.='OK! '.$ok;
                          $v->save();
                        }
                        else{
                          $resultado->mensaje.="El registro aún no es validado ".$error;
                        }
                      }
                    }
                    else{
                      $resultado->mensaje.='No se encuentra en la base de Power BI '.$error;
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
      return view('importar.certificados')->with(['resultado'=>$resultado]);
    }
}
