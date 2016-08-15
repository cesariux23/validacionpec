<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;

class UserController extends Controller
{
    //
    public function cambiarPassword(Request $request)
    {
      $ok=0;
      if($request->isMethod('post')){
        $this->validate($request, [
          'password' => 'required|min:5|confirmed',
        ]);

        //Se actualiza la contraseña
        $u=Auth::user();
        $u->password=bcrypt($request->get('password'));
        $u->save();
        $ok=1;
      }
      return view('user.password')->with(['ok'=>$ok]);
    }
}
