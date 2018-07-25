<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;

use sisVentas\Http\Requests;

use sisVentas\User;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Http\Requests\UsuarioFormRequest;
use sisVentas\Http\Requests\UsuarioEditFormRequest;
use DB;
//use sisventas\Http\Controllers\Auth;
use Auth;



class UsuarioController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index (Request $request)
	{
		if ($request)
		{
            $idempresa = Auth::user()->id_empresa;
			$query=trim($request->get('searchText'));
			$usuarios=DB::table('users')
            ->where('name','LIKE','%'.$query.'%')
            ->where('id_empresa','=',$idempresa)
			->orderBy('id','desc')
			->paginate(7);
			return view('seguridad.usuario.index',["usuarios"=>$usuarios,"searchText"=>$query]);
		}
	}

	public function create()
	{
		return view("seguridad.usuario.create");
	}
    
    public function store(UsuarioFormRequest $request)
    {
        
        $usuario=new User;
    	$usuario->name=$request->get('name');
    	$usuario->email=$request->get('email');
    	$usuario->password=bcrypt($request->get('password'));
        $usuario->empresa=$request->get('empresa');
        $usuario->id_empresa=$request->get('id_empresa');
    	$usuario->save();



    	return Redirect::to('seguridad/usuario');
    }

    public function edit($id)
    {
    	return view("seguridad.usuario.edit",["usuario"=>User::findOrFail($id)]);
    }

    public function update(UsuarioEditFormRequest $request,$id)
    {
        

        $empresa = Auth::user()->empresa;
        $email = Auth::user()->email;

    	$usuario=User::findOrFail($id);
    	$usuario->name=$request->get('name');
        $usuario->email=$request->get('email');
    	$usuario->password=bcrypt($request->get('password'));

        if ($request->get('empresa') != $empresa)
        {
            DB::table('users')
            ->where('empresa', $empresa)
            ->update(['empresa' => $request->get('empresa')]);
       
        }

        //$usuario->empresa=$request->get('empresa');
    	$usuario->update();
    	return Redirect::to('seguridad/usuario');


    }

    public function destroy($id)
    {
    	$usuario = DB::table('users')->where('id', '=', $id)->delete();
    	return Redirect::to('seguridad/usuario');
    }
}
