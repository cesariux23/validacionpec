<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Validacion;

class BaseValidacion extends Model
{
    //Modelo de la base de datos SIGA
    protected $table='baseValidacion';

     protected $dates = ['created_at', 'updated_at', 'fechaemision'];

    public function scopeRfe($query,$rfe)
    {
      if(isset($rfe) && strlen($rfe)>0){
        return $query->where('cRFE', 'like', $rfe.'%');
      }
    }
    public function validacion()
    {
      return $this->hasOne('App\Validacion', 'id', 'idValidacion');
    }

    public function scopePaterno($query,$paterno)
    {
      if(isset($paterno) && strlen($paterno)>0){
        return $query->where('cPaterno', 'like', $paterno.'%');
      }
    }
    public function scopeMaterno($query,$materno)
    {
      if(isset($materno) && strlen($materno)>0){
        return $query->where('cMaterno', 'like', $materno.'%');
      }
    }
    public function scopeNombre($query,$nombre)
    {
      if(isset($nombre) && strlen($nombre)>0){
        return $query->where('cNombre', 'like', $nombre.'%');
      }
    }
    public function scopeCz($query,$cz)
    {
      if(isset($cz) && strlen($cz)>0){
        return $query->where('iCveCZ', $cz);
      }
    }
    public function scopeFechaconclusion($query,$fecha)
    {
      if(isset($fecha) && strlen($fecha)>0){
        return $query->where('fConclusion', $fecha);
      }
    }
    public function scopeFechaemision($query,$fecha)
    {
      if(isset($fecha) && strlen($fecha)>0){
        return $query->where('fEmisionCertificado', $fecha);
        return $query->orWhere('fechaemision', $fecha);
      }
    }
    public function scopeNivel($query,$nivel)
    {
      if(isset($nivel) && strlen($nivel)>0){
        return $query->where('cNivel', $nivel);
      }
    }
    public function scopeVerificado($query,$verificado)
    {
      if(isset($verificado)){
        if($verificado=='0'){
          return $query->orWhere(function($q){
            $q->where('verificado', 0);
            $q->orWhereNull('verificado');
          });
        }
        else{
          return $query->where('verificado', $verificado);
        }
      }
    }
    public function scopeValido($query,$valido)
    {
      if(isset($valido) && $valido){
        //validados
        $query->where('valido', $valido);

        if($valido=='1'){

          $query->orWhere(function($q)
          {
            $q->whereNull('valido');
            $q->where(function($q0){
              //requeridos en PB
              $q0->where('cCURP','!=','');
              $q0->where('cArchivoFoto','!=','');
              $q0->where('cArchivoAuto','!=','');

              $q0->orWhere(function($q1){
                $q1->where('cArchivoTerc','!=','');
                $q1->where('cArchivoCapacitacion','!=','');
              });
            });
          });
        }
        //incompletos
        elseif($valido==2){
          $query->orwhere('valido', '0');
          //$query->where('valido', $valido);
          $query->orWhere(function($q)
          {
            //$q->where('verificado','!=','1');
            //requeridos
            $q->where(function($q0){
              $q0->where('verificado','!=','1');
              $q0->orWhereNull('verificado');
            });
              $q->where('cCURP','=','');
            $q->orWhere('cArchivoFoto','=','');
            $q->orWhere('cArchivoAuto','=','');
            $q->orWhere(function($q1){
              $q1->where('cArchivoTerc','=','');
              $q1->where('cArchivoCapacitacion','=','');
            });
          });
        }else {
          $query->where('valido', $valido);
        }
        return $query;
      }else {
        //$query->where('bTerminoProceso', 'FALSO');
        $query->whereNull('valido');
        $query->orWhere('valido', 0);

        return $query;
      }
    }


    public function getValidacion()
    {

      //busca en la base de acuerdo al indice compuesto
      if(isset($this->idValidacion)){
        $val=Validacion::find($this->idValidacion);
      }
      if(!isset($val)){
        //se crea un nuevo registro de Validacion
        $val=new Validacion();
        $val->nombre=$this->cNombre;
        $val->paterno=$this->cPaterno;
        $val->materno=$this->cMaterno;
        $val->cz=$this->iCveCZ;
        $val->nombrecz=$this->cNombreCZ;
        $val->nivel=$this->cNivel;
        $val->oportunidad=$this->cOportunidad;
        $val->calificacion=$this->dCalFinal;
        $val->acredito=0;
        $val->emisioncertificado=0;
        $val->datospersonales=1;
        if($this->dCalFinal >= 6){
          $val->acredito=1;
        }
        if($this->cCURP!='' && strlen(trim($this->cCURP))==18){
          $val->curp=1;
        }else{
          $val->curp=0;
        }

        if($this->cArchivoFoto!=''){
          $val->foto=1;
        }else{
          $val->foto=0;
        }
        if($this->cArchivoAuto!=''){
          $val->autoevaluacion=1;
        }else{
          $val->autoevaluacion=0;
        }

        if($this->cArchivoTerc!=''){
          $val->terceros=1;
        }
        if($this->cArchivoCapacitacion!=''){
          $val->aprendizaje=1;
        }

        //if(strpos(strtolower($val->nivel),'primaria')!== false)
        $val->certificado=1;
        //$val->valido=0;
        $val->rfe=$this->cRFE;

        //Se termina de validar el Proceso
        if(($val->datospersonales==1 && $val->curp==1 && $val->certificado==1 && $val->foto==1 && $val->curp==1 && $val->foto==1 && $val->autoevaluacion==1 && ($val->terceros==1 || $val->aprendizaje==1))){
          $val->valido=1;
          $val->validadopor=3030;
          $val->fechavalidacion=date("Y-m-d");
        }
        else{
          $val->valido=2;
          $val->validadopor=3030;
          $val->fechavalidacion=date("Y-m-d");
        }
        $val->save();
      };
      return $val;
    }

    public function getEstado()
    {
      # code...
      if($this->idValidacion){
        return true;
      }
      else{
        return false;
      }
    }

    public function scopeEmitido($query,$emitido)
    {
        if ($emitido!==null) {
          switch ($emitido) {
            case '0':
              # code...
              $query->where('verificado', 1);
              $query->where('cEstatusCertificado','');
              $query->Where('emisioncertificado',0);

              $query->orWhere(function($q)
                      {
                          $q->where('dCalFinal','>=',6)->where('cEstatusCertificado','')->where('bTerminoProceso','1');
                      });
              # code...
              break;
              //emitidos
              case '1':
                $query->orWhere('emisioncertificado', $emitido);
                $query->orWhere('cEstatusCertificado','like','emitido%');
                break;
              case '2':
                $query->orwhere('emisioncertificado', $emitido);
                $query->orWhere('cEstatusCertificado','like','cancelado%');
                break;
                case '3':
                  $query->orWhere('cEstatusCertificado','like','entregado%');
                  break;
            default:
              # solo los aprobados
              $query->where('dCalFinal','>=',6);
              break;
          }
            return $query;
        }

    }

}
