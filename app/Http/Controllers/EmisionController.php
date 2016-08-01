<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Coordinaciones;
use App\Validacion;
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
          $coordinaciones->prepend('--Todas las CZ --', '');
          $base=Validacion::where('valido',1)
            ->rfe($request->input('rfe'))
            ->cz($request->input('nombrecz'))
            ->nivel($request->input('nivel'))
            ->emitido($request->input('emitido'))
            ->paginate(20);
          return view('certificado/index')->with(array('base'=>$base, 'coordinaciones'=>$coordinaciones));
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
}
