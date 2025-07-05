jQuery(document).ready(function(){
    //Default Action
    $(".tab_content").hide(); //Hide all content
    $("ul.tabs li:first").addClass("active").show(); //Activate first tab
    $(".tab_content:first").show(); //Show first tab content
    $("#alcanceSuministro").show();
    //On Click Event
    $("ul.tabs li").click(function() {
        $("ul.tabs li").removeClass("active"); //Remove any "active" class
        $(this).addClass("active"); //Add "active" class to selected tab
        $(".tab_content").hide(); //Hide all tab content
        var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
        $(activeTab).fadeIn(); //Fade in the active content
        return false;
    });    
    
    $("#nuevo.ocompra_listar").click(function(){
	location.href = base_url+"index.php/compras/ocompra/nuevo";
    });	
    $("#editar").click(function(){
	location.href = base_url+"index.php/compras/ocompra/editar";
    });	
    $("#buscar").click(function(){
	$("#frmBusqueda").submit();
    });	
    $("#limpiar").click(function(){
        url = base_url+"index.php/compras/ocompra/listar";
        location.href=url;
    });
    $("#grabar").click(function(){
        url = base_url+"index.php/compras/ocompra/grabar";
        location.href = url;
    });
    $("#cancelar").click(function(){
        url = base_url+"index.php/compras/ocompra/listar";
        location.href = url;
    });    	    
    $("#salir.ocompra_listar").click(function(){
        window.close();
    }); 
    
    
    
    
    
    /*Mostrar capas*/
    $("#ttab1").click(function(){
        codigo = $("#codigo").val();
        if(codigo!=''){
            $('#divMateriales').show();
            $('#divRrhh').hide();
            $('#divServicios').hide();  
            url = base_url+"index.php/ventas/ot/listar_materiales_x_ot/"+codigo;
            $('#VentanaTransparente').css("display","block");
            $.post(url,"",function(data){
                $("#VentanaTransparente").css("display","none");
                $("#divMateriales tbody").html(data);
            });
        }
        else{
            alert("Por favor seleccione una \n Orden de trabajo");
        }
    });
    $("#ttab2").click(function(){
        $('#divMateriales').hide();
        $('#divRrhh').hide();
        $('#divServicios').show();
    });
    $("#ttab3").click(function(){
        $('#divMateriales').hide();
        $('#divRrhh').show();
        $('#divServicios').hide();
    });        
    
    /* Reporte por control Compras */
    $("#html.control").click(function(){
        $("#tipo").val('html');
        $("#frmControl").attr("target","_parent");
        $("#frmControl").submit();
    });	 
    $("#excel.control").click(function(){
        $("#tipo").val('excel');
        $("#frmControl").attr("target","_blank");        
        $("#frmControl").submit();
    });	     
    $("#pdf.control").click(function(){
        $("#tipo").val('pdf');
        $("#frmControl").attr("target","_blank");
        $("#frmControl").submit();
    });	
    $("#grafica.control").click(function(){
        $("#tipo").val('grafica');
    });	    
    $("#salir.control").click(function(){
        window.close();
    });	    
    
    
    
    
    
    
  /* Reporte por Requisiciones Atendidas */
    $("#html.atendidas").click(function(){
        $("#tipo").val('html');
        $("#frmAtendidas").attr("target","_parent");
        $("#frmAtendidas").submit();
    });	 
    $("#excel.atendidas").click(function(){
        $("#tipo").val('excel');
        $("#frmAtendidas").attr("target","_blank");        
        $("#frmAtendidas").submit();
    });	     
    $("#pdf.atendidas").click(function(){
        $("#tipo").val('pdf');
        $("#frmAtendidas").attr("target","_blank");
        $("#frmAtendidas").submit();
    });	
    $("#grafica.atendidas").click(function(){
        $("#tipo").val('grafica');
    });	    
    $("#salir.atendidas").click(function(){
        
        window.close();
    });	        
    

    
     /* Reporte por Requisiciones Transporte */
    $("#html.transporte").click(function(){
        $("#tipo").val('html');
        $("#frmTransporte").attr("target","_parent");
        $("#frmTransporte").submit();
    });	 
    $("#excel.transporte").click(function(){
        $("#tipo").val('excel');
        $("#frmTransporte").attr("target","_blank");        
        $("#frmTransporte").submit();
    });	     
    $("#pdf.transporte").click(function(){
        $("#tipo").val('pdf');
        $("#frmTransporte").attr("target","_blank");
        $("#frmTransporte").submit();
    });	
    $("#grafica.transporte").click(function(){
        $("#tipo").val('grafica');
    });	    
    $("#salir.transporte").click(function(){
       
        window.close();
    });	        
     
    
    
    
    
    
    
    
    $("#nuevoProducto").click(function(){
        $(".prod_sup").show();
        $(".prod_inf").show();
        $("#cancelarProducto").show();
        $("#grabarProducto").show();
        $("#nuevoProducto").hide();
    });
    $("#editarProducto").click(function(){
        $(".prod_sup").show();
        $(".prod_inf").show();
        $("#cancelarProducto").show();
        $("#grabarProducto").show();
        $("#nuevoProducto").hide();
    });    
    $("#cancelarProducto").click(function(){
        $(".prod_sup").hide();
        $(".prod_inf").hide();
        $("#cancelarProducto").hide();
        $("#grabarProducto").hide();
        $("#nuevoProducto").show();
    });
    $("#grabarProducto").click(function(){
        $(".prod_sup").hide();
        $(".prod_inf").hide();
        $("#cancelarProducto").hide();
        $("#grabarProducto").hide();
        $("#nuevoProducto").show();
    });
    $("input[name=chkProducto]").click(function(){
        valor = $("input[name=chkProducto]:checked").val();
        $("#divTipProducto").html('<div class="lbl1_block"><strong>TIPO PRODUCTO</strong></div><div class="lbl1_block"><input type="radio" name="chkTipPro" id="chkTipPro" value="b1">Montaje</div>');
        $("#divModelo").html('<div class="lbl1_block"><strong>TIPO PRODUCTO</strong></div><div class="lbl1_block"><input type="radio" name="chkTipPro" id="chkTipPro" value="b1">Montaje</div>');
    }); 
    $("#nuevoPartida").click(function(){
        
    });
    $(".editarPartida").click(function(){
        ident = $(this).parent().parent().parent().attr("id");
        $("#lblPresup"+ident).hide();
        $("#txtPresup"+ident).show();
        $("#lblEjec"+ident).hide();
        $("#txtEjec"+ident).show();
        $("#lblSaldo"+ident).hide();
        $("#txtSaldo"+ident).show();    
        $("#btnGrabar"+ident).show(); 
        $("#btnEliminar"+ident).hide(); 
    });    
    $(".grabarPartida").click(function(){
        ident = $(this).parent().parent().parent().attr("id");
        $("#lblPresup"+ident).show();
        $("#txtPresup"+ident).hide();
        $("#lblEjec"+ident).show();
        $("#txtEjec"+ident).hide();
        $("#lblSaldo"+ident).show();
        $("#txtSaldo"+ident).hide();    
        $("#btnGrabar"+ident).hide(); 
        $("#btnEliminar"+ident).show(); 
    });  
    $(".eliminarPartida").click(function(){
        componen = $(this).parent().parent().parent().attr("id");
        /*Eliminar con jquery*/
        $(this).parent().parent().parent().remove();        
    });      
    $("#cancelarProducto").click(function(){
        $(".prod_sup").hide();
        $(".prod_inf").hide();
        $("#cancelarProducto").hide();
        $("#grabarProducto").hide();
        $("#nuevoProducto").show();
    });
    $("#grabarProducto").click(function(){
        $(".prod_sup").hide();
        $(".prod_inf").hide();
        $("#cancelarProducto").hide();
        $("#grabarProducto").hide();
        $("#nuevoProducto").show();
    });
     $(".verDetPartida").click(function(){
         id = $(this).attr("id");
         /*Obtengo el detalle para esta ot*/
         contenido = "\n\
        \n\<table width='98%' border='1' align='center' cellpadding='0' cellspacing='0'>\n\
        \n\<thead>\n\
        \n\<tr bgcolor='#CCCCCC'>\n\
        \n\<th scope='col' width='52%'><div align='center'>Partida</div></th>\n\
        \n\<th scope='col' width='12%'><div align='center'>Presupuestado</div></th>\n\
        \n\<th scope='col' width='12%'><div align='center'>Ejecutado</div></th>\n\
        \n\<th scope='col' width='12%'><div align='center'>Saldo</div></th>\n\
        \n\<th scope='col' width='12%'><div align='center'>Acciones</div></th>\n\
        \n\</tr>\n\
        \n\</thead>\n\
        \n\<tbody>\n\
        \n\<tr bgcolor='#FFFFFF' id='1'>\n\
        \n\<td>Obra Civil</td>\n\
        \n\<td align='right'>\n\
        \n\<span id='lblPresup1'>1200.00</span>\n\
        \n\<span id='txtPresup1' style='display: none;'><input type='text' class='cajaPequena' value='1200.00'></span>\n\
        \n\</td>\n\
        \n\<td align='right'>\n\
        \n\<span id='lblEjec1'>0.00</span>\n\
        \n\<span id='txtEjec1' style='display: none;'><input type='text' class='cajaPequena' value='00.00'></span>\n\
        \n\</td>\n\
        \n\<td align='right'>\n\
        \n\<span id='lblSaldo1'>1200.00</span>\n\
        \n\<span id='txtSaldo1' style='display: none;'><input type='text' class='cajaPequena' value='1200.00'></span>\n\
        \n\</td>\n\
        \n\\n\
        \n\
        \n\
        \n\
        \n\
        \n\
        \n\
        \n\
        \n\
        \n\
        \n\
        \n\
        \n\
        \n\
        \n\
        \n\
        \n\
        \n\
        \n\
";
         $("#divPartida").html(contenido);
    });   
   
    $('#prodGeneral').click(function(){        
        $('#general').show();
        $('#datosPrecios').hide();
        $('#datosProveedores').hide();
        $("#nuevoRegistroProv").hide();
        $('#datosOcompras').hide();
        $('#divBotones').show();
    });
    $('#prodPrecios').click(function(){
        $('#general').hide();
        $('#datosPrecios').show();
        $('#datosProveedores').hide();
        $("#nuevoRegistroProv").hide();
        $('#datosOcompras').hide();
        $('#divBotones').show();
    });
    $('#prodProveedores').click(function(){
        $('#general').hide();
        $('#datosPrecios').hide();
        $('#datosProveedores').show();
        $("#nuevoRegistroProv").show();
        $('#datosOcompras').hide();
        $('#divBotones').show();
    });
    $('#prodCompras').click(function(){
        producto = $("#producto").val();
        //alert('Corregir cuando sale esto');
        if(producto!=''){
            $('#general').hide();
            $('#datosPrecios').hide();
            $('#datosProveedores').hide();
            $("#nuevoRegistroProv").hide();
            $('#datosOcompras').show();
            $('#divBotones').hide();
            url = base_url+"index.php/almacen/producto/listar_ocompras_x_producto/"+producto;
            $.post(url,'',function(data){
                $('#datosOcompras').html(data);
            });
        }
    });
    $("#nuevoRegistroProv").click(function(){
        $("#msgRegistros").hide();
        n = document.getElementById('tblProveedor').rows.length;
        fila  = "<tr>";
        fila += "<td align='center'>"+n+"</td>";
        fila += "<td align='left'><input type='text' name='ruc["+n+"]' id='ruc["+n+"]' class='cajaPequena' readonly='readonly'></td>";
        fila += "<td align='left'>";
        fila += "<input type='hidden' name='proveedor["+n+"]' id='proveedor["+n+"]'>";        
        fila += "<input type='text' name='nombre_proveedor["+n+"]' id='nombre_proveedor["+n+"]' class='cajaGrande'>";
        fila += "<a href='#' onclick='buscar_proveedor("+n+");'>&nbsp;<img height='16' width='16' border='0' title='Agregar Proveedor' src='"+base_url+"images/ver.png'></a>";
        fila += "</td>";
        fila += "<td align='left'><input type='text' name='direccion["+n+"]' id='direccion["+n+"]' class='cajaGrande' readonly='readonly'></td>";
        fila += "<td align='left'><input type='text' name='distrito["+n+"]' id='distrito["+n+"]' class='cajaMedia' readonly='readonly'></td>";        
	fila += "<td align='center'><a href='#' onclick='eliminar_productoproveedor("+n+");'><img src='"+base_url+"images/delete.gif' border='0'></a></td>";
        $("#tblProveedor").append(fila);
    });
     $("a.limpiarPrecios").click(function(){
          $(this).parents("tr").find("input").val('');

     });
});