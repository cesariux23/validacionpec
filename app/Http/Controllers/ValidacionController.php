<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Validacion;
use App\BaseValidacion;
use Auth;

class ValidacionController extends Controller
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
    public function index()
    {
        //
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
      //$validacion=BaseValidacion::find($id);
      $validacion=Validacion::find($id);
      $base=BaseValidacion::select('cCURP','cArchivoFoto')->where('idvalidacion',$id)->first();

      return view('validacion/view')->with(['validacion'=>$validacion, 'base'=>$base]);
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
        $request->flash();
        $validacion=Validacion::find($id);
        if($request->has('curp'))
          $validacion->curp=$request->get('curp');
        if($request->has('datospersonales'))
          $validacion->datospersonales=$request->get('datospersonales');
        if($request->has('certificado'))
          $validacion->certificado=$request->get('certificado');
        if($request->has('terceros'))
          $validacion->terceros=$request->get('terceros');
        if($request->has('aprendizaje'))
          $validacion->aprendizaje=$request->get('aprendizaje');
        if($request->has('autoevaluacion'))
          $validacion->autoevaluacion=$request->get('autoevaluacion');
        if($request->has('foto'))
          $validacion->foto=$request->get('foto');
        if($request->has('observaciones'))
          $validacion->observaciones=$request->get('observaciones');
        if($request->has('valido')){
          $validacion->valido=$request->get('valido');
          if($request->get('valido')=='1'){
            $validacion->verificado=0;
          }
          if($request->get('valido')>='1'){
            $validacion->validadopor=Auth::user()->id;
            $validacion->fechavalidacion=date("Y-m-d");
          }
        }
        if($request->has('verificado')){
          $validacion->verificado=$request->get('verificado');
          if($request->get('verificado')==0 && $validacion->valido==3){
            $validacion->valido=1;
          }
          $validacion->verificadopor=  Auth::user()->id;
          $validacion->fechaverificacion=date("Y-m-d");
        }
        if($request->has('todos'))
            $validacion->validaTodo();
        $validacion->save();
        if ($request->ajax())
        {
          $valido=($validacion->datospersonales==1 && $validacion->curp==1 && $validacion->certificado==1 && $validacion->foto==1 && $validacion->curp==1 && $validacion->foto==1 && $validacion->autoevaluacion==1 && ($validacion->terceros==1 || $validacion->aprendizaje==1))? true: false;
          //Regresa la validación del Object
          return response()->json([
              'valido' => $valido
            ]);
        }
        else{
          return redirect()->back();
        }

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
}
