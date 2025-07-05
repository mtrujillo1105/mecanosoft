<?php
class Nsalida extends controller
{
    private $_hoy;
    public function __construct()
    {
        parent::Controller();
        $this->load->model('almacen/guiasa_model');
        $this->load->model('almacen/guiasadetalle_model');
        $this->load->model('almacen/almacen_model');
        $this->load->model('almacen/producto_model');
        $this->load->model('almacen/unidadmedida_model');
        $this->load->model('almacen/tipomovimiento_model');
        $this->load->model('ventas/cliente_model');
        $this->load->model('seguridad/usuario_model');
        $this->load->model('maestros/configuracion_model');
        $this->load->helper('form','url');
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->somevar['user'] = $this->session->userdata('user');
        $this->somevar['compania'] = $this->session->userdata('compania');
        date_default_timezone_set('America/Los_Angeles');          
        $this->_hoy                = mdate("%Y-%m-%d ",time());
    }
    public function listar($j=0)
    {
        $this->load->library('layout', 'layout');
        $data['registros']  = count($this->guiasa_model->listar());
        $conf['base_url']   = site_url('almacen/guiasa/listar/');
        $conf['per_page']   = 30;
        $conf['num_links']  = 3;
        $conf['first_link'] = "&lt;&lt;";
        $conf['last_link']  = "&gt;&gt;";
        $conf['next_link']  = "&gt;";
        $conf['prev_link']  = "&lt;";
        $conf['uri_segment']= 4;
        $conf['total_rows'] = $data['registros'];
        $offset             = (int)$this->uri->segment(3);
        $listado            = $this->guiasa_model->listar($conf['per_page'],$offset);
        $item               = $j+1;
        $lista              = array();
        if(count($listado)>0){
             foreach($listado as $indice=>$valor){
                 $codigo         = $valor->GUIASAP_Codigo;
                 $almacen        = $valor->ALMAP_Codigo;
                 $datos_almacen  = $this->almacen_model->obtener($almacen);
                 $nombre_almacen = $datos_almacen[0]->ALMAC_Descripcion;
                 $fecha          = explode(" ",$valor->GUIASAC_Fecha);
                 $rsocial        = $this->cliente_model->obtener($valor->CLIP_Codigo);
                 $rsocial_nombre = $rsocial->nombre;
                 $editar         = "<a href='#' onclick='editar_guiasa(".$codigo.")'><img src='".base_url()."images/modificar.png' width='16' height='16' border='0' title='Modificar'></a>";
                 $ver            = "<a href='#' onclick='ver_guiasa_pdf(".$codigo.")'><img src='".base_url()."images/ver.png' width='16' height='16' border='0' title='Modificar'></a>";
                 $eliminar       = "<a href='#' onclick='eliminar_guiasa(".$codigo.")'><img src='".base_url()."images/eliminar.png' width='16' height='16' border='0' title='Modificar'></a>";
                 $lista[]        = array($item++,$fecha[0],$valor->GUIASAC_Numero,$nombre_almacen,$rsocial_nombre,$editar,$ver,$eliminar);
             }
        }
        $data['lista']           = $lista;
        $data['titulo_busqueda'] = "BUSCAR COMPROBANTE DE SALIDA";
        $data['titulo_tabla']    = "Relaci&oacute;n de COMPROBANTE DE SALIDA";
        $this->pagination->initialize($conf);
        $data['paginacion']      = $this->pagination->create_links();
        $this->layout->view('almacen/guiasa_index',$data);
    }
    public function nueva()
    {
        $this->load->library('layout', 'layout');
        $usuario                  = $this->somevar['user'];
        $datos_usuario            = $this->usuario_model->obtener($usuario);
        $nombre_usuario           = $datos_usuario->PERSC_Nombre." ".$datos_usuario->PERSC_ApellidoPaterno;
        $data['titulo']           = "NUEVO COMPROBANTE DE SALIDA";
        $data['form_open']        = form_open(base_url().'index.php/almacen/guiasa/grabar',array("name"=>"frmGuiasa","id"=>"frmGuiasa","onsubmit"=>"return valida_guiasa();"));
        $data['oculto']           = form_hidden(array("base_url"=>base_url(),"guiasa_id"=>'',"centro_costo"=>1,"accion"=>"n","GenInd"=>""));
        $data['numero']  	  = form_input(array("name"=>"numero","id"=>"numero","class"=>"cajaPequena2","readonly"=>"readonly","maxlength"=>"10"));
        $data['fecha']  	  = form_input(array("name"=>"fecha","id"=>"fecha","class"=>"cajaPequena","readonly"=>"readonly","maxlength"=>"10","value"=>$this->_hoy));
        $data['nombre_usuario']   = form_input(array("name"=>"nombre_usuario","id"=>"nombre_usuario","class"=>"cajaMedia","readonly"=>"readonly","maxlength"=>"30","value"=>$nombre_usuario));
        $data['cliente']          = form_input(array("name"=>"cliente","id"=>"cliente","class"=>"cajaPequena2","readonly"=>"readonly","maxlength"=>"30","value"=>"","type"=>"hidden"));
        $data['nombre_cliente']   = form_input(array("name"=>"nombre_cliente","id"=>"nombre_cliente","class"=>"cajaMedia","readonly"=>"readonly","maxlength"=>"50","value"=>""));
        $data['ruc']              = form_input(array("name"=>"ruc","id"=>"ruc","class"=>"cajaPequena2","readonly"=>"readonly","maxlength"=>"11","value"=>"","onblur"=>"obtener_cliente();","onkeypress","return numbersonly(this,event,'.');","type"=>"hidden"));
        $atributos                = array('width'=>600,'height'=>400,'scrollbars'=>'yes','status'=>'yes','resizable'=>'yes','screenx'=>'0','screeny'=>'0');
        $contenido                = "<img height='16' width='16' src='".base_url()."images/ver.png' title='Buscar' border='0'>";
        $data['vercliente']       = anchor_popup('ventas/cliente/ventana_busqueda_cliente',$contenido,$atributos);
        $data['verproducto']      = "<a href='#' onclick='busqueda_producto_x_almacen();'>".$contenido."</a>";
        $data['hidden']		  = "";
        $data['detalle']          = array();
        $filterin                 = new stdClass();
        $filterin->TIPOMOVC_Tipo  = 2;
        $data['cboAlmacen']       = form_dropdown("almacen",$this->almacen_model->seleccionar(),""," class='comboMedio' id='almacen'");
        $data['cboTipoMov']       = form_dropdown("tipo_movimiento",$this->tipomovimiento_model->seleccionar($filterin),"1"," class='comboMedio' id='tipo_movimiento'");
        $data['form_close']       = form_close();
        $data['observacion']      = form_textarea(array("name"=>"observacion","id"=>"observacion","class"=>"fuente8","cols"=>"108","rows"=>"3"));
        $this->layout->view('almacen/guiasa_nueva',$data);
    }
    public function editar($codigo)
    {
        $this->load->library('layout', 'layout');
        $modo            = "modificar";
        $datos_guiasa    = $this->guiasa_model->obtener($codigo);
        $tipo_movimiento = $datos_guiasa->TIPOMOVP_Codigo;
        $almacen         = $datos_guiasa->ALMAP_Codigo;
        $usuario         = $datos_guiasa->USUA_Codigo;
        $cliente         = $datos_guiasa->CLIP_Codigo;
        $numero 	 = $datos_guiasa->GUIASAC_Numero;
        $observacion     = $datos_guiasa->GUIASAC_Observacion;
        $arrfecha        = explode(" ",$datos_guiasa->GUIASAC_FechaRegistro);
        $fecha           = $arrfecha[0];
        $datos_cliente   = $this->cliente_model->obtener($cliente);
        $nombre_cliente  = $datos_cliente->nombre;
        $ruc             = $datos_cliente->ruc;
        $datos_usuario   = $this->usuario_model->obtener($usuario);
        $nombre_usuario  = $datos_usuario->PERSC_Nombre." ".$datos_usuario->PERSC_ApellidoPaterno;
        $data['titulo']           = "EDITAR COMPROBANTE DE SALIDA";
        $data['form_open']        = form_open(base_url().'index.php/almacen/guiasa/grabar',array("name"=>"frmGuiasa","id"=>"frmGuiasa","onsubmit"=>"return valida_guiasa();"));
        $data['oculto']           = form_hidden(array('accion'=>"m",'guiasa_id'=>$codigo,'modo'=>$modo,'base_url'=>base_url(),"GenInd"=>""));
        $data['numero']  	  = form_input(array("name"=>"numero","id"=>"numero","class"=>"cajaPequena2","readonly"=>"readonly","maxlength"=>"10","value"=>$numero));
        $data['fecha']  	  = form_input(array("name"=>"fecha","id"=>"fecha","class"=>"cajaPequena","readonly"=>"readonly","maxlength"=>"10","value"=>$fecha));
        $data['nombre_usuario']   = form_input(array("name"=>"nombre_usuario","id"=>"nombre_usuario","class"=>"cajaMedia","readonly"=>"readonly","maxlength"=>"30","value"=>$nombre_usuario));
        $data['cliente']          = form_input(array("name"=>"cliente","id"=>"cliente","class"=>"cajaPequena2","readonly"=>"readonly","maxlength"=>"30","value"=>"","type"=>"hidden","value"=>$cliente));
        $data['nombre_cliente']   = form_input(array("name"=>"nombre_cliente","id"=>"nombre_cliente","class"=>"cajaMedia","readonly"=>"readonly","maxlength"=>"50","value"=>$nombre_cliente));
        $data['ruc']              = form_input(array("name"=>"ruc","id"=>"ruc","class"=>"cajaPequena2","readonly"=>"readonly","maxlength"=>"11","value"=>"","onblur"=>"obtener_cliente();","onkeypress","return numbersonly(this,event);","type"=>"hidden","value"=>$ruc));
        $atributos                = array('width'=>600,'height'=>400,'scrollbars'=>'yes','status'=>'yes','resizable'=>'yes','screenx'=>'0','screeny'=>'0');
        $contenido                = "<img height='16' width='16' src='".base_url()."images/ver.png' title='Buscar' border='0'>";
        $data['vercliente']       = anchor_popup('ventas/cliente/ventana_busqueda_cliente',$contenido,$atributos);
        $data['verproducto']      = "<a href='#' onclick='busqueda_producto_x_almacen();'>".$contenido."</a>";
        $data['hidden']		  = "";
        $filterin                 = new stdClass();
        $filterin->TIPOMOVC_Tipo  = 2;
        $data['cboAlmacen']       = form_dropdown("almacen",$this->almacen_model->seleccionar(),$almacen," class='comboMedio' id='almacen'");
        $data['cboTipoMov']       = form_dropdown("tipo_movimiento",$this->tipomovimiento_model->seleccionar($filterin),$tipo_movimiento," class='comboMedio' id='tipo_movimiento'");
        $data['observacion']      = form_textarea(array("name"=>"observacion","id"=>"observacion","class"=>"fuente8","cols"=>"108","rows"=>"3","value"=>$observacion));
        $data['form_close']       = form_close();
        /*Detalle*/
        $detalle               = $this->guiasadetalle_model->obtener2($codigo);
        $detalle_guiasa         = array();
        if(count($detalle)>0){
             foreach($detalle as $indice=>$valor)
             {
                $detguiasa   = $valor->GUIASADETP_Codigo;
                $producto    = $valor->PRODCTOP_Codigo;
                $unidad      = $valor->UNDMED_Codigo;
                $cantidad    = $valor->GUIASADETC_Cantidad;
                $costo       = $valor->GUIASADETC_Costo;
                $GenInd      = $valor->GUIASADETC_GenInd;
                $descri      = $valor->GUIASADETC_Descripcion;
                $datos_producto  = $this->producto_model->obtener_producto($producto);
                $datos_unidad    = $this->unidadmedida_model->obtener($unidad);
                $nombre_producto = $datos_producto[0]->PROD_Nombre;
                $codigo_interno  = $datos_producto[0]->PROD_CodigoInterno;
                $nombre_unidad   = $datos_unidad[0]->UNDMED_Simbolo;
                if($GenInd=="I"){
                    $filter2 = new stdClass();
                    $filter2->SERIC_Guiasa = $codigo;
                    $arrserie = $this->serie_model->obtener($producto,$filter2);
                    $data2     = array();
                    if(count($arrserie)>0){
                        foreach($arrserie as $value){
                            $data2[] = $value->SERIP_Codigo;
                        }
                    }
                    $_SESSION['serie'][$producto] = $data2;
                }
                $objeto          =   new stdClass();
                $objeto->GUIASADETP_Codigo    = $detguiasa;
                $objeto->PRODCTOP_Codigo      = $producto;
                $objeto->PROD_CodigoInterno   = $codigo_interno;
                $objeto->GUIASADETC_Cantidad  = $cantidad;
                $objeto->GUIASADETC_Costo     = $costo;
                $objeto->UNDMED_Codigo        = $unidad;
                $objeto->PROD_Nombre          = $nombre_producto;
                $objeto->UNDMED_Simbolo       = $nombre_unidad;
                $objeto->GenInd              = $GenInd;
                $objeto->GUIASADETC_Descripcion              = $descri;
                $detalle_guiasa[]             = $objeto;
            }
        }
        $data['detalle']                     = $detalle_guiasa;
        $this->layout->view('almacen/guiasa_nueva',$data);
    }
    public function grabar()
    {
        $this->form_validation->set_rules('nombre_usuario','usuario','required');
        $this->form_validation->set_rules('nombre_cliente','cliente','required');
        $this->form_validation->set_rules('almacen','almacen','required');
        $this->form_validation->set_rules('tipo_movimiento','motivo de movimiento','required');
        $this->form_validation->set_rules('prodcodigo','detalle de producto','required');
        if($this->form_validation->run() == FALSE){
            $this->nueva();
        }
        else{
            $guiasa_id            = $this->input->post("guiasa_id");
            $almacen              = $this->input->post("almacen");
            $fecha                = $this->input->post("fecha");
            $cliente              = $this->input->post("cliente");
            $numero_ref           = $this->input->post("numero_ref");
            $tipo_movimiento      = $this->input->post("tipo_movimiento");
            $observacion          = $this->input->post("observacion");
            $accion               = $this->input->post("accion");
            $prodcodigo           = $this->input->post('prodcodigo');
            $produnidad           = $this->input->post('produnidad');
            $prodcantidad         = $this->input->post('prodcantidad');
            $prodcosto            = $this->input->post('prodcosto');
            $prodventa            = $this->input->post('prodventa');
            $detaccion            = $this->input->post('detaccion');
            $detguiasa            = $this->input->post('detguiasa');
            $proddescri           = $this->input->post('proddescri');
            $flagGenInd           = $this->input->post('flagGenIndDet');
            $detobserv            = "oob";
            $filter = new stdClass();
            $filter->TIPOMOVP_Codigo = $tipo_movimiento;
            $filter->ALMAP_Codigo    = $almacen;
            $filter->USUA_Codigo     = $this->somevar['user'];
            $filter->CLIP_Codigo     = $cliente;
            $filter->GUIASAC_Observacion   = $observacion;
            $filter->GUIASAC_Fecha         = $fecha;
            $filter->GUIASAC_FechaRegistro = $this->_hoy;
            if($accion=="m"){
                $this->guiasadetalle_model->eliminar2($guiasa_id);
            }
            if(isset($guiasa_id) && $guiasa_id>0){
              unset($filter->GUIASAC_FechaRegistro);
              $this->guiasa_model->modificar($guiasa_id,$filter);
            }
            else{
               $guiasa_id = $this->guiasa_model->insertar($filter);
            }
            if(count($prodcodigo)>0){
               foreach($prodcodigo as $indice=>$valor){
                 $producto = $prodcodigo[$indice];
                 $unidad   = $produnidad[$indice];
                 $cantidad = $prodcantidad[$indice];
                 $costo    = $prodcosto[$indice];
                 $accion   = $detaccion[$indice];
                 $detg     = $detguiasa[$indice];
                 $detflag  = $flagGenInd[$indice];
                 $descri   = $proddescri[$indice];
                 $observ   = "Insertar";
                 $filter2  = new stdClass();
                 $filter2->GUIASAP_Codigo      = $guiasa_id;
                 $filter2->PRODCTOP_Codigo     = $producto;
                 $filter2->UNDMED_Codigo       = $unidad;
                 $filter2->GUIASADETC_Cantidad = $cantidad;
                 $filter2->GUIASADETC_Costo    = $costo;
                 $filter2->GUIASADETC_GenInd   = $detflag;
                 $filter2->GUIASADETC_Descripcion   = $descri;
                 $this->guiasadetalle_model->insertar($filter2);
               }
            }
            unset($_SESSION['serie']);//Elimina la serie
            redirect('almacen/guiasa/listar');
        }
    }

    public function ver(){
        $numero         = $this->input->get_post('numero');
        $filter         = new stdClass();
        $filter_not     = new stdClass();
        $filter->numero = $numero;
        
        /*$filter,$filter_not*/
        $voucher     = $this->nsalida_model->obtener();
        $fecemi      = $voucher->FecEmi;
        $fecpago     = $voucher->FecPago;
        $nrocheque   = $voucher->NroCheque;
        $nrocta      = $voucher->NroCta;
        $tipSolPago  = $voucher->TipSolPago;
        $codsolicita = $voucher->CodSolicita;
        $codbco      = $voucher->CodBco;
        $mtoPago     = $voucher->MtoPago;
        $fentrega    = $voucher->fEntrega;
        $Mo          = $voucher->MO;
        $observacion = $voucher->d_descripcion;
        $moneda      = $Mo==2?"SOLES":"DOLARES";     
        $this->load->library("fpdf/pdf");
        $CI = & get_instance();
        $CI->pdf->FPDF('P');
        $CI->pdf->AliasNbPages();
        $CI->pdf->AddPage();
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFillColor(255,255,255);
        /*Cabecera*/
        $CI->pdf->SetFont('Arial','',7);
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFillColor(216,216,216);
        //$CI->pdf->Image('images/anadir.jpg',11,4,30);
        $CI->pdf->Cell(0,8, "VALE DE SALIDA No ".$numero,0,1,"C",0);
        $CI->pdf->Cell(120,5, "" ,0,1,"L",0);
        $CI->pdf->Cell(60,5, "FECHA: ".$codbco ,0,0,"L",0);
        $CI->pdf->Cell(60,5, "" ,0,0,"L",0);
        $CI->pdf->Cell(60,5,  "",0,1,"L",0);
        $CI->pdf->Cell(90,5, "RESPONSABLE : ".$nrocheque ,0,0,"L",0);
        $CI->pdf->Cell(60,5,  "SOLICITANTE :".$codsolicita ,0,0,"L",0);
        $CI->pdf->Cell(60,5, "" ,0,1,"L",0);
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFillColor(255,255,255);
        $CI->pdf->Cell(120,5, "No REQUI. : ".$numero ,0,0,"L",1);
        $CI->pdf->Cell(120,5, "" ,0,1,"L",0);
        $CI->pdf->SetTextColor(255,255,255);
        $CI->pdf->SetFillColor(192,192,192);
        /*Detalle*/
        $CI->pdf->Cell(0,5, "" ,0,1,"R",0);
        $CI->pdf->SetFillColor(0,0,128);
        $CI->pdf->Cell( 12,5,"ITEM",0,0,"C",1);
        $CI->pdf->Cell( 20,5,"CODIGO",0,0,"C",1);
        $CI->pdf->Cell(15,5,"UNIDAD",0,0,"C",1);
        $CI->pdf->Cell( 90,5,"DESCRIPCION",0,0,"C",1);
        $CI->pdf->Cell( 20,5,"CANTIDAD",0,0,"C",1);
        $CI->pdf->Cell( 30,5,"UBICACION",0,0,"C",1);
        $CI->pdf->Cell( 0,5,"",0,0,"C",1);
        $CI->pdf->Cell( 0,5,"",0,1,"C",1);
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFont('Arial','',6);
        $filter2     = new stdClass();
        $filter2_not = new stdClass();
        $filter2->numero = $numero;
        $voucher_det = $this->vale_salida_model->listar_detalle($filter2,$filter2_not);
        $i = 1;
        foreach($voucher_det as $indice=>$value){
            $importe     = $value->ImpPdet;
            $descripcion = $value->DesPago;
            $tipodoc     = $value->TipoDocRef;
            $numerodoc   = $value->NroDocRef;
            $seriedoc    = $value->SerieDocRef;
            $codot       = $value->codot;
            $tipPago     = $value->TipPago;
            $igv         = $value->Igv;
            /*Obtenemos OT*/
           $ots         = $this->ot_model->obtener($codot);
            $nroot       = $ots->NroOt;
            $dirot       = $ots->DirOt;
            $CI->pdf->Cell(5,6,$i,0,0,"C",0);
            $CI->pdf->Cell(20,6,$tipPago,0,0,"C",0);
            $CI->pdf->Cell(15,6,$nroot,0,0,"R",0);
            $CI->pdf->Cell(40,6,$dirot,0,0,"L",0);
            $CI->pdf->Cell(70,6,$descripcion,0,0,"L",0);    
            $CI->pdf->Cell(15,6,$tipodoc."-".$seriedoc."-".$numerodoc,0,0,"C",0);    
            $CI->pdf->Cell(15,6,number_format($importe,2),0,0,"R",0);    
            $CI->pdf->Cell(9,6,$igv,0,1,"R",0);   
            $i++;
        }
        $CI->pdf->Output();
    }
    public function ver_pdf($codigo){
        $this->load->library('cezpdf');
        $this->load->helper('pdf_helper');
        prep_pdf();
        /*Datos principales*/
        $datos_guiasa         = $this->guiasa_model->obtener($codigo);
        $datos_detalle_guiasa = $this->guiasadetalle_model->obtener2($codigo);
        $tipo_movimiento = $datos_guiasa->TIPOMOVP_Codigo;
        $almacen         = $datos_guiasa->ALMAP_Codigo;
        $usuario         = $datos_guiasa->USUA_Codigo;
        $cliente         = $datos_guiasa->CLIP_Codigo;
        $numero          = $datos_guiasa->GUIASAC_Numero;
        $observacion     = $datos_guiasa->GUIASAC_Observacion;
        $arrfecha             = explode(" ",$datos_guiasa->GUIASAC_FechaRegistro);
        $datos_almacen        = $this->almacen_model->obtener($almacen);
        $nombre_almacen       = $datos_almacen[0]->ALMAC_Descripcion;
        $fecha                = $arrfecha[0];
        $datos_cliente        = $this->cliente_model->obtener($cliente);
        $razon_social         = $datos_cliente->nombre;
        $ruc                  = $datos_cliente->ruc;
        $telefono             = $datos_cliente->telefono;
        $direccion            = $datos_cliente->direccion;
        $fax                  = $datos_cliente->fax;
        $datos_tipomov        = $this->tipomovimiento_model->obtener($tipo_movimiento);
        $nombre_tipomov       = $datos_tipomov[0]->TIPOMOVC_Descripcion;
        /*Cabecera*/
        $delta=20;
        $this->cezpdf->ezText('','',array("leading"=>120-$delta));
		$this->cezpdf->ezText('<b>COMPROBANTE DE SALIDA No '.$numero.'</b>','13',array("leading"=>10,"left"=>150));
		$this->cezpdf->ezText('<b>FECHA:                  '.$fecha.'</b>','10',array("leading"=>40-$delta,'left'=>350));
		$this->cezpdf->ezText('','',array("leading"=>10));
        $data_cabecera = array(
            array('c1'=>'Senor(es):','c2'=>$razon_social,'c3'=>'Telefono:','c4'=>$telefono),
            array('c1'=>'RUC:','c2'=>$ruc,'c3'=>'Fax:','c4'=>$fax),
            array('c1'=>'Direccion:','c2'=>$direccion,'c3'=>'Mot. Mov.','c4'=>$nombre_tipomov)
            );
        $this->cezpdf->ezTable($data_cabecera,"","",array(
            'width'=>555,
            'showLines'=>0,
            'shaded'=>0,
            'showHeadings'=>0,
            'xPos'=>'center',
            'cols'=>array(
                'c1'=>array('width'=>70,'justification'=>'left'),
                'c2'=>array('width'=>355,'justification'=>'left'),
                'c3'=>array('width'=>60,'justification'=>'left'),
                'c4'=>array('width'=>70,'justification'=>'right')
                )
        ));
        $this->cezpdf->ezText('','',array("leading"=>10));
        /*Detalle*/
        if(count($datos_detalle_guiasa)>0){
             foreach($datos_detalle_guiasa as $indice=>$valor){
                $producto       = $valor->PRODCTOP_Codigo;
                $unidad         = $valor->UNDMED_Codigo;
                $costo          = $valor->GUIASADETC_Costo;
                $datos_producto = $this->producto_model->obtener_producto($producto);
                $datos_unidad   = $this->unidadmedida_model->obtener($unidad);
                $prod_nombre    = $datos_producto[0]->PROD_Nombre;
                $prod_codigo    = $datos_producto[0]->PROD_CodigoInterno;
                $prod_unidad    = $datos_unidad[0]->UNDMED_Simbolo;
                $prod_cantidad  = $valor->GUIASADETC_Cantidad;
                $db_data[] = array(
                    'col1'=>$indice+1,
                    'col2'=>$prod_codigo,
                    'col3'=>$prod_nombre,
                    'col4' =>number_format($prod_cantidad,2),
                    'col5'=>$prod_unidad
                    );
             }
        }
        $col_names = array(
            'col1' => 'Itm',
            'col2' => 'Codigo',
            'col3' => 'Descripcion',
            'col4' => 'Cant',
            'col5' => 'Und'
        );
        $this->cezpdf->ezTable($db_data,$col_names, '', array(
            'width'=>550,
            'showLines'=>1,
            'shaded'=>0,
            'showHeadings'=>1,
            'xPos'=>'center',
            'cols'=>array(
                'col1'=>array('width'=>25,'justification'=>'center'),
                'col2'=>array('width'=>70,'justification'=>'center'),
                'col3'=>array('width'=>355,'justification'=>'left'),
                'col4'=>array('width'=>60,'justification'=>'right'),
                'col5'=>array('width'=>40,'justification'=>'left')
                )
         ));
        /**Pie de pagina**/
        $this->cezpdf->ezSetY(105+$delta);
        $this->cezpdf->ezText('<b>Observacion(es) :</b>'.$observacion,'10',array('leading'=>18,'left'=>0));
        $this->cezpdf->ezStream();
    }
    public function eliminar()
    {
        $codigo = $this->input->post('codigo');
        $this->guiasadetalle_model->eliminar2($codigo);
        $this->guiasa_model->eliminar($codigo);
        echo true;
    }
    public function cancelar()
    {
        unset($_SESSION['serie']);//Elimina la serie
        redirect('almacen/guiasa/listar');
    }
}
?>
