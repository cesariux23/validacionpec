<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\BaseValidacion;
use App\Coordinaciones;
use App\Validacion;

class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $titulo="Registro pendientes de validación";
        $ruta=$request->route()->getName();
        switch ($ruta) {
          case 'base.pendientes.index':
          $titulo="Registro pendientes";
          $valido=0;
            break;
          case 'base.incompletos.index':
            $titulo="<span class='text-danger'>Registro con expediente incompleto</span>";
            $valido=2;
            break;
          case 'base.validos.index':
            $titulo="<span class='text-success'><i class='fa fa-check-circle-o'></i></span> Validación finalizada";
            $valido=1;
            break;
          case 'base.ensasa.index':
            $titulo="<span class='text-warning'><i class='fa fa-copy'></i></span> Registros ya existentes en SASA";
            $valido=3;
            break;
          default:
            $titulo="Registro pendientes de validación";
            $valido=0;
            break;
        }

        $request->flash();
        $coordinaciones= Coordinaciones::lists('cNombreCZ','iCveCZ');
        $coordinaciones->prepend('--Todas las CZ --', '');
        $base=BaseValidacion::where('cEstatusCertificado','')
          ->rfe($request->input('cRFE'))
          ->paterno($request->input('cPaterno'))
          ->materno($request->input('cMaterno'))
          ->nombre($request->input('cNombre'))
          ->cz($request->input('iCveCZ'))
          ->nivel($request->input('cNivel'))
          ->where('dCalFinal','>=',6)
          ->valido($valido)
          ->paginate(20);
        return view('base/index')->with(array('base'=>$base, 'coordinaciones'=>$coordinaciones, 'titulo'=>$titulo, 'valido'=>$valido,'ruta'=>$ruta));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $registro=baseValidacion::find($id);
        $validacion=$registro->getValidacion();

        return redirect()->route('validacion.edit', [$validacion]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        //busca en la base de acuerdo al indice compuesto
        $registro=BaseValidacion::find($id);
        $val=$registro->getValidacion();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function export(Request $request)
    {
        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
        $titulo='Registros';
        if($request->input('valido')!==null){
          switch ($request->input('valido')) {
            case 0:
              $titulo .='_pendientes';
              break;
            case 1:
              $titulo .='_validados';
              break;
            case 2:
              $titulo .='_incompletos';
              break;
            case 3:
              $titulo .='_enSASA';
              break;
          }
          if($request->input('iCveCZ')!=null){
            $titulo .='_CZ'.$request->input('iCveCZ');
          }else{
            $titulo .='_todasCZ';
          }
          //fecha
          $titulo.=date('_dmyHis').'.csv';

        }
        $csv->insertOne(\Schema::getColumnListing('baseValidacion'));
          $r=BaseValidacion::where('cEstatusCertificado','')
            ->rfe($request->input('cRFE'))
            ->paterno($request->input('cPaterno'))
            ->materno($request->input('cMaterno'))
            ->nombre($request->input('cNombre'))
            ->cz($request->input('iCveCZ'))
            ->nivel($request->input('cNivel'))
            ->where('dCalFinal','>=',6)
            ->valido($request->input('valido'))
            ->get();
          $r->each(function($person) use($csv) {
            $csv->insertOne($person->toArray());
          });
          $csv->output($titulo);
    }
}
