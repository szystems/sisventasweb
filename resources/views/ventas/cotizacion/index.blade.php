@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Cotizaciones <a href="cotizacion/create"><button class="btn btn-success">Nuevo</button></a></h3>
		@include('ventas.cotizacion.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Fecha</th>
					<th>Cliente</th>
					<th>Comprobante</th>
					<th>Impuesto</th>
					<th>Total</th>
					<th>Estado</th>
					<th>Opciones</th>
				</thead>
               @foreach ($cotizaciones as $cot)
				<tr>
					<td align="left">{{ $cot->fecha_hora}}</td>
					<td align="left">{{ $cot->nombre}}</td>
					<td align="left">{{ $cot->tipo_comprobante}}</td>
					<td align="right">{{ number_format($cot->impuesto,2, '.', ',')}}</td>
					<td align="right">{{ number_format($cot->total_cotizacion,2, '.', ',')}}</td>
					<td align="center">{{ $cot->estado}}</td>
					<td align="left">
						<a href="{{URL::action('CotizacionController@show',$cot->idcotizacion)}}"><button class="btn btn-primary">Detalles</button></a>
                         <a href="" data-target="#modal-delete-{{$cot->idcotizacion}}" data-toggle="modal"><button class="btn btn-danger">Anular</button></a>
					</td>
				</tr>
				@include('ventas.cotizacion.modal')
				@endforeach
			</table>
		</div>
		{{$cotizaciones->render()}}
	</div>
</div>

@endsection