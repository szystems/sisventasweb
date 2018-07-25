<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table='cotizacion';

    protected $primaryKey='idcotizacion';

    public $timestamps=false;


    protected $fillable =[
    	'idcliente',
    	'tipo_comprobante',
    	'fecha_hora',
    	'impuesto',
    	'total_cotizacion',
    	'estado',
        'id_empresa'
    ];

    protected $guarded =[

    ];
}
