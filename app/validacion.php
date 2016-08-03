<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Validacion extends Model
{
    //Modelo de la base de datos SIGA
    protected $table='validacion';

    public function scopeRfe($query,$rfe)
    {
      if(isset($rfe)){
        return $query->where('rfe', 'like', $rfe.'%');
      }
    }
    public function scopeCz($query,$cz)
    {
      if(isset($cz) && strlen($cz)>0){
        return $query->where('cz', $cz);
      }
    }
    public function scopeNivel($query,$nivel)
    {
      if(isset($nivel) && strlen($nivel)>0){
        return $query->where('nivel', $nivel);
      }
    }

    public function scopeEmitido($query,$emitido)
    {
      if(isset($emitido) && $emitido!=null){
        $query->where('emisioncertificado', $emitido);
        if($emitido==0)
          $query->orWhereNull('emisioncertificado');
        return $query;
      }
    }
    public function validaTodo()
    {
      $this->datospersonales=1;
      $this->curp=1;
      $this->certificado=1;
      $this->foto=1;
      $this->autoevaluacion=1;
      $this->terceros=1;
      $this->aprendizaje=1;
    }


}
