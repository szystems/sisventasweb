<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class DetalleCotizacion extends Model
{
    protected $table='detalle_cotizacion';

    protected $primaryKey='iddetalle_cotizacion';

    public $timestamps=false;


    protected $fillable =[
    	'idcotizacion',
    	'idarticulo',
    	'cantidad',
    	'precio_venta',
    	'descuento'
    ];

    protected $guarded =[

    ];
}
