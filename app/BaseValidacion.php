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
    public function scopeValido($query,$valido)
    {
      if(isset($valido) && $valido){
        return $query->where('valido', $valido);
      }else {
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
        if($this->dCalFinal >= 6){
          $val->acredito=1;
        }

        if(strpos(strtolower($val->nivel),'primaria')!== false)
          $val->certificado=1;
        //$val->valido=0;
        $val->rfe=$this->cRFE;
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
              $query->where('valido', 1);
              $query->Where('emisioncertificado',0);
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
