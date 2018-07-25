<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;

use sisVentas\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentas\Http\Requests\CotizacionFormRequest;

use sisVentas\Cotizacion;
use sisVentas\DetalleCotizacion;
use DB;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

use Auth;
use sisVentas\User;

class CotizacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request)
        {
            $idempresa = Auth::user()->id_empresa;
            $query=trim($request->get('searchText'));
            $cotizaciones=DB::table('cotizacion as cot')
            ->join('persona as p','cot.idcliente','=','p.idpersona')
            ->join('detalle_cotizacion as dc','cot.idcotizacion','=','dc.idcotizacion')
            ->select('cot.idcotizacion','cot.fecha_hora','p.nombre','cot.tipo_comprobante','cot.impuesto','cot.estado','cot.total_cotizacion')
            ->where('cot.idcotizacion','LIKE','%'.$query.'%')
            ->where('cot.id_empresa','=',$idempresa)
            ->orderBy('cot.idcotizacion','desc')
            ->groupBy('cot.idcotizacion','cot.fecha_hora','p.nombre','cot.tipo_comprobante','cot.impuesto','cot.estado')
            ->paginate(7);
            return view('ventas.cotizacion.index',["cotizaciones"=>$cotizaciones,"searchText"=>$query]);

        }
    }

    public function create()
    {
        $idempresa = Auth::user()->id_empresa;
    	$personas=DB::table('persona')
        ->where('tipo_persona','=','Cliente')
        ->where('id_empresa','=',$idempresa)
        ->get();
    	$articulos=DB::table('articulo as art')
    	->join('detalle_ingreso as di','art.idarticulo','=','di.idarticulo')
    	->select(DB::raw('CONCAT(art.codigo, " ",art.nombre) AS articulo'),'art.idarticulo','art.stock',DB::raw('avg(di.precio_venta) as precio_promedio'))
    	->where('art.estado','=','Activo')
    	->where('art.stock','>=','0')
        ->where('art.id_empresa','=',$idempresa)
    	->groupBy('articulo','art.idarticulo','art.stock')
    	->get();
    	return view("ventas.cotizacion.create",["personas"=>$personas,"articulos"=>$articulos]);
    }

    public function store (cotizacionFormRequest $request)
    {
    	try
    	{ 
            $idempresa = Auth::user()->id_empresa;

    		DB::beginTransaction();

    		$cotizacion=new cotizacion;
    		$cotizacion->idcliente=$request->get('idcliente');
    		$cotizacion->tipo_comprobante=$request->get('tipo_comprobante');
    		$cotizacion->total_cotizacion=$request->get('total_cotizacion');

    		$mytime = Carbon::now('America/Guatemala');
    		$cotizacion->fecha_hora=$mytime->toDateTimeString();
    		$cotizacion->impuesto='0';
    		$cotizacion->estado='A';
            $cotizacion->id_empresa=$idempresa;
    		$cotizacion->save();


    		$idarticulo = $request->get('idarticulo');
    		$cantidad = $request->get('cantidad');
    		$descuento = $request->get('descuento');
    		$precio_venta = $request->get('precio_venta');

    		$cont = 0;

    		while ($cont < count($idarticulo)) 
    		{
    			$detalle = new DetalleCotizacion();
    			$detalle->idcotizacion=$cotizacion->idcotizacion;
    			$detalle->idarticulo=$idarticulo[$cont];
    			$detalle->cantidad=$cantidad[$cont];
    			$detalle->descuento=$descuento[$cont];
    			$detalle->precio_venta=$precio_venta[$cont];
    			$detalle->save();

    			$cont=$cont+1;	
    		}

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

    	return Redirect::to('ventas/cotizacion');
    }

    public function show($id)
    {
        $idempresa = Auth::user()->id_empresa;
        
    	$cotizacion=DB::table('cotizacion as cot')
            ->join('persona as p','cot.idcliente','=','p.idpersona')
            ->join('detalle_cotizacion as dc','cot.idcotizacion','=','dc.idcotizacion')
            ->select('cot.idcotizacion','cot.fecha_hora','p.nombre','cot.tipo_comprobante','cot.impuesto','cot.estado','cot.total_cotizacion')
            ->where('cot.idcotizacion','=',$id)
            ->where('cot.id_empresa','=',$idempresa)
            ->first();

        $detalles=DB::table('detalle_cotizacion as d')
        	->join('articulo as a','d.idarticulo','=','a.idarticulo')
        	->select('a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta')
        	->where('d.idcotizacion','=',$id)
        	->get();

        return view("ventas.cotizacion.show",["cotizacion"=>$cotizacion,"detalles"=>$detalles]);
    }

    public function destroy($id)
    {
    	$cotizacion=Cotizacion::findOrFail($id);
    	$cotizacion->Estado='C';
    	$cotizacion->update();
    	return Redirect::to('ventas/cotizacion');
    }
}
