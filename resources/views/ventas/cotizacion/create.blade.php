@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Nueva Cotizacion</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif
		</div>
	</div>
			{!!Form::open(array('url'=>'ventas/cotizacion','method'=>'POST','autocomplete'=>'off'))!!}
            {{Form::token()}}
    <div class="row">
    	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    		<div class="form-group">
            	<label for="cliente">Cliente</label>
            	<select name="idcliente" id="idcliente" class="form-control selectpicker"  data-live-search="true">
                    <option value="" selected>Seleccione un cliente</option>
                    @foreach($personas as $persona)
                    <option value="{{$persona->idpersona}}">{{$persona->nombre}}</option>
                    @endforeach
                </select>
            </div>
    	</div>
        
    	<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
    		<div class="form-group">
    			<label>Tipo Comprobante</label>
    			<select name="tipo_comprobante" class="form-control">
    				<option value="Cotizacion">Cotizacion</option>
    			</select>
    		</div>
    	</div>
    </div>
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                    <div class="form-group">
                        <label>Articulo</label>
                        <select name="pidarticulo" class="form-control selectpicker" id="pidarticulo" data-live-search="true">
                            <option value="" selected>Seleccione un articulo</option>
                            @foreach($articulos as $articulo)
                            <option value="{{$articulo->idarticulo}}_{{$articulo->stock}}_{{number_format($articulo->precio_promedio,2, '.', ',')}}">{{$articulo->articulo}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" name="pcantidad" class="form-control" id="pcantidad" placeholder="cantidad" onkeypress="return validarentero(event,this.value)">
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" disabled name="pstock" class="form-control" id="pstock" placeholder="Stock">
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="precio_venta">Precio Venta</label>
                        <input type="" name="pprecio_venta" class="form-control" id="pprecio_venta" placeholder="p. Venta" onkeypress="return validardecimal(event,this.value)">
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="descuento">Descuento x articulo</label>
                        <input type="" name="pdescuento" class="form-control" id="pdescuento" placeholder="Descuento" value="0" onkeypress="return validardecimal(event,this.value)">
                    </div>
                </div>
                
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <button type="button" id="bt_add" class="btn btn-primary">Agregar</button>
                    </div>
                </div>
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <div class="form-group">
                        <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                            <thead style="background-color:#A9D0F5">
                                <th>Opciones</th>
                                <th>Articulo</th>
                                <th>Cantidad</th>
                                <th>Precio Venta</th>
                                <th>Descuento</th>
                                <th>Subtotal</th>
                            </thead>
                            <tfoot>
                                <th>TOTAL</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><h4 id="total">Q. 0.00</h4><input type="hidden" name="total_cotizacion" id="total_cotizacion"></th>
                            </tfoot>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12" id="guardar">
    		 <div class="form-group">
                <input name="_token" value="{{ csrf_token() }}" type="hidden" ></input>
            	<button class="btn btn-primary" type="submit">Guardar</button>
            	<button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
    	</div>
    </div>
            
           
           

{!!Form::close()!!}		
            
@push ('scripts')
    <script>
        $(document).ready(function(){
            $('#bt_add').click(function(){
                agregar();
            });
        });

        var cont=0;
        total=0;
        subtotal=[];
        $("#guardar").hide();
        $("#pidarticulo").change(mostrarValores);

        function mostrarValores()
        {
            datosArticulo=document.getElementById('pidarticulo').value.split('_');
            $("#pprecio_venta").val(datosArticulo[2]);
            $("#pstock").val(datosArticulo[1]);
        }

        function agregar()
        {
            datosArticulo=document.getElementById('pidarticulo').value.split('_');

            idarticulo=datosArticulo[0];
            articulo=$("#pidarticulo option:selected").text();
            cantidad=$("#pcantidad").val();

            descuento=$("#pdescuento").val();
            precio_venta=$("#pprecio_venta").val();
            stock=$("#pstock").val();

            if (idarticulo!="" && cantidad!="" && cantidad>0 && descuento!="" && precio_venta!="")
            {
                
                    subtotal[cont]=(cantidad*precio_venta-descuento);
                    total=total+subtotal[cont];

                    var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td><td><input type="number" readonly name="cantidad[]" value="'+cantidad+'"></td><td><input type="number" readonly name="precio_venta[]" value="'+precio_venta+'"></td><td><input type="number" readonly name="descuento[]" value="'+descuento*cantidad+'"></td><td align="right">'+subtotal[cont].toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')+'</td></tr>'; 
                    cont++;
                    limpiar();
                    $("#total").html("Q. " + total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
                    $("#total_cotizacion").val(total);
                    evaluar();
                    $('#detalles').append(fila);
                

                
            }
            else 
            {
                alert("Error al ingresar el detalle de la Cotizacion, revise los datos del articulo");
            }
        }

        function limpiar()
        {
            $("#pcantidad").val("0");
            $("#pdescuento").val("0");
            $("#pprecio_venta").val("");
        }

        function evaluar()
        {
            if (total>0)
            {
                $("#guardar").show();
            }
            else
            {
                $("#guardar").hide();
            }
        }

        function eliminar(index)
        {
            total=total-subtotal[index];
            $("#total").html("Q. " + total);
            $("#total_cotizacion").val(total);
            $("#fila" + index).remove();
            evaluar();
        }

        function validardecimal(e,txt) 
        {
            tecla = (document.all) ? e.keyCode : e.which;
            if (tecla==8) return true;
            if (tecla==46 && txt.indexOf('.') != -1) return false;
            patron = /[\d\.]/;
            te = String.fromCharCode(tecla);
            return patron.test(te); 
        }  

        function validarentero(e,txt) 
        {
            tecla = (document.all) ? e.keyCode : e.which;

            //Tecla de retroceso para borrar, siempre la permite
            if (tecla==8)
            {
                return true;
            }
        
        // Patron de entrada, en este caso solo acepta numeros
        patron =/[0-9]/;
        tecla_final = String.fromCharCode(tecla);
        return patron.test(tecla_final); 
        }
    </script>
@endpush
@endsection