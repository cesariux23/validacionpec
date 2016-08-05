<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Coordinaciones;
use App\Validacion;
use App\BaseValidacion;
use App\Fechas;
use Auth;

class EmisionController extends Controller
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
        if(Auth::user()->rol<2){
          $request->flash();
          $coordinaciones= Coordinaciones::lists('cNombreCZ','iCveCZ');
          $fechas= Fechas::lists('fConclusion','fConclusion');
          $coordinaciones->prepend('--Todas las CZ --', '');
          //$fechas=array_shift($fechas);
          $fechas->prepend('--Todas --', '');
          $base=BaseValidacion::emitido($request->input('emitido'))
            ->rfe($request->input('rfe'))
            ->cz($request->input('nombrecz'))
            ->nivel($request->input('nivel'))
            ->fechaconclusion($request->input('fechaconclusion'))
            ->fechaemision($request->input('fechaemision'))
            ->paterno($request->input('paterno'))
            ->materno($request->input('materno'))
            ->nombre($request->input('nombre'))
            ->paginate(20);
            //->tosql();
            //dd($base);
          return view('certificado/index')->with(array('base'=>$base, 'coordinaciones'=>$coordinaciones,'fechas'=>$fechas));
        }
        else{
          return view('errors/503');
        }
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $val=Validacion::find($id);
          if($request->has('emision')){
            $val->emisioncertificado=$request->get('emision');
            if($request->get('emision')=='1'){
              $val->fechaemision=date('Y-m-d');
            }
            $val->save();
          }
        return redirect()->back();
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
        $titulo='Certificados_';
        if($request->input('emitido')!==null){
          switch ($request->input('emitido')) {
            case '0':
              $titulo .='_pendientes';
              break;
            case '1':
              $titulo .='_emitidos';
              break;
            case '2':
              $titulo .='_cancelados';
              break;
            default:
              $titulo .='_todos_los_registros';
              break;
          }
        }
          if($request->input('nombrecz')!=null){
            $titulo .='_CZ'.$request->input('nombrecz');
          }else{
            $titulo .='_todasCZ';
          }
          //fecha
          $titulo.=date('_dmyHis').'.csv';


        $csv->insertOne(\Schema::getColumnListing('baseValidacion'));
          $r=$base=BaseValidacion::emitido($request->input('emitido'))
            ->rfe($request->input('rfe'))
            ->cz($request->input('nombrecz'))
            ->nivel($request->input('nivel'))
            ->fechaconclusion($request->input('fechaconclusion'))
            ->fechaemision($request->input('fechaemision'))
            ->paterno($request->input('paterno'))
            ->materno($request->input('materno'))
            ->nombre($request->input('nombre'))
            ->get();
          $r->each(function($person) use($csv) {
            $csv->insertOne($person->toArray());
          });
          $csv->output($titulo);
    }
}
