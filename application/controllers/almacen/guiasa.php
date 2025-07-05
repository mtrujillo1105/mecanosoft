<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once "Spreadsheet/Excel/Writer.php";
class Guiasa extends CI_Controller {
    var $entidad;
    var $login;
    var $codres;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");   
        $this->load->model(finanzas.'voucher_model');
        $this->load->model(ventas.'ot_model');
        $this->load->model(almacen.'guiasa_model');
        $this->load->model(almacen.'producto_model');
        $this->load->model(personal.'responsable_model');
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');
    }
 
    
    public function listar($j=0){
        $offset             = (int)$this->uri->segment(3);
        $conf['base_url']   = site_url('almacen/nsalida/listar/');
        $conf['per_page']   = 30;
        $conf['num_links']  = 3;
        $conf['first_link'] = "&lt;&lt;";
        $conf['last_link']  = "&gt;&gt;";
        $conf['next_link']  = "&gt;";
        $conf['prev_link']  = "&lt;";
        $conf['uri_segment']= 4;
        $conf['total_rows'] = 100;
        $filter             = new stdClass();
        $filter_not         = new stdClass();
        $filter->fechai     = "01/01/2012";
        $listado            = $this->nsalida_model->listar($filter,$filter_not,"",$conf['per_page'],$offset);
        $item               = $j+1;
        $fila               = "";
        if(count($listado)>0){
             foreach($listado as $indice=>$valor){
                 $fecha  = $valor->fecha;
                 $codot  = $valor->codot;
                 $serie  = $valor->serie;
                 $numero = $valor->numero;
                 $numoc  = $valor->numoc;
                 $numcom = $valor->numcom;
                 $fila  .= "<tr class='".($indice%2==0?'itemParTabla':'itemParTabla')."'>";
                 $fila  .= "<td align='center'>".$item++."</td>";
                 $fila  .= "<td align='center'>".date_sql($fecha)."</td>";
                 $fila  .= "<td align='center'>".$codot."</td>";
                 $fila  .= "<td align='center'>".$serie."</td>";
                 $fila  .= "<td align='center'>".$numero."</td>";
                 $fila  .= "<td align='center'>".$numoc."</td>";
                 $fila  .= "<td align='center'>".$numcom."</td>";
                 $fila  .= "<td align='center'><a href='#' onclick='editar(".$numcom.")'><img src='".base_url()."img/modificar.png' width='16' height='16' border='0' title='Modificar'></a></td>";
                 $fila  .= "<td align='center'><a href='#' onclick='ver(".$numcom.")'><img src='".base_url()."img/ver.png' width='16' height='16' border='0' title='Modificar'></a></td>";
                 $fila  .= "<td align='center'><a href='#' onclick='eliminar(".$numcom.")'><img src='".base_url()."img/eliminar.png' width='16' height='16' border='0' title='Modificar'></a></td>";                 
                 $fila  .= "</tr>";
             }
        }
        $data['fila']           = $fila;
        $data['titulo_busqueda'] = "Buscar Vales de Salida";
        $data['titulo_tabla']    = "Relaci&oacute;n de Vale de Salida";
        $this->pagination->initialize($conf);
        $data['paginacion']      = $this->pagination->create_links();
        $this->load->view(almacen."nsalida_listar",$data);
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
        $hora_Actual=date("H:i:s");
        $numeros        = $this->input->get_post('numero');
        $filter         = new stdClass();
        $filter_not     = new stdClass();
        $filter->numero = $numeros;
        $nsalida_det    = $this->nsalida_model->listar_detalle($filter,$filter_not);
        $codot          = $nsalida_det[0]->codot;   
        $num            = $nsalida_det[0]->codres;   
        $ot             = $nsalida_det[0]->ot;
        $ruccli         = $nsalida_det[0]->ruccli;   
        $ns_numero      = $nsalida_det[0]->numero;
        $fecha          = $nsalida_det[0]->fecha;
        $numreq         = $nsalida_det[0]->numreq;
        $codres         = $nsalida_det[0]->codres;      
        $filter->codresponsable = $num;
        $responsable    = $this->responsable_model->obtener($filter,$filter_not);
        $filter->codresponsable = $ruccli;
        $responsable2   = $this->responsable_model->obtener($filter,$filter_not);    
        $filter->codresponsable = $this->session->userdata('codres');
        $responsable3    = $this->responsable_model->obtener($filter,$filter_not);
        /*Listado de productos*/
        $productos      = $this->producto_model->listar(new stdClass(),new stdClass());
        foreach($productos as $indice => $value){
            $codpro = $value->codpro;
            $arrproducto[$codpro] = $value;
        }
        $filter2         = new stdClass();
        $filter2_not     = new stdClass();
        $filter2->codot  = $codot;
        $oOt             = $this->ot_model->obtenerg($filter2,$filter2_not);
        $res             = isset($responsable2->nomper)?$responsable2->nomper:"";
        $sol             = isset($responsable->nomper)?$responsable->nomper:"";
        $user            = isset($responsable3->nomper)?$responsable3->nomper:"";
        $Dot             = $oOt->DirOt;   
        $this->load->library("fpdf/pdf");
        $CI = & get_instance();
        $CI->pdf->FPDF('P');
        $CI->pdf->AliasNbPages();
        $CI->pdf->AddPage();
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFillColor(255,255,255);
        /*Cabecera*/
        $CI->pdf->SetFont('Arial','B',11);
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFillColor(216,216,216);
        $CI->pdf->Image('img/mimco_ruc.jpg',10,8,55);
        $CI->pdf->Cell(0,8, "VALE DE SALIDA No 001 - ".$numeros,0,1,"C",0);
        $CI->pdf->Cell(60,8, "",0,1,"L",0);
        $CI->pdf->SetFont('Arial','',7);
        $CI->pdf->Cell(120,5, "" ,0,1,"L",0);
        $CI->pdf->Cell(60,5, "FECHA                :  ".date_sql($fecha). '    '.$hora_Actual,0,0,"L",0);
        $CI->pdf->Cell(60,5, "" ,0,0,"L",0);
        $CI->pdf->Cell(60,5,  "",0,1,"L",0);
        $CI->pdf->Cell(90,5, "RESPONSABLE :  ".$ruccli. " -    " . $res ,0,0,"L",0);
        $CI->pdf->Cell(60,5,  "SOLICITANTE :  ".$codres."- " . $sol ,0,0,"L",0);
        $CI->pdf->Cell(60,5, "" ,0,1,"L",0);
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFillColor(255,255,255);
        $CI->pdf->Cell(120,5, "No REQUI.          :  001 - ".$numreq. "                           OT:  ".$ot ."   -   ". $Dot,0,0,"L",1);
        $CI->pdf->Cell(120,5, "" ,0,1,"L",0);
        $CI->pdf->SetTextColor(255,255,255);
        $CI->pdf->SetFillColor(192,192,192);
        /*Detalle*/
        $CI->pdf->Cell(0,5, "" ,0,1,"R",0);
        $CI->pdf->SetFillColor(0,0,128);
        $CI->pdf->Cell( 12,5,"ITEM",0,0,"C",1);
        $CI->pdf->Cell( 20,5,"CODIGO",0,0,"C",1);
        $CI->pdf->Cell(12,5,"UNIDAD",0,0,"C",1);
        $CI->pdf->Cell( 125,5,"DESCRIPCION",0,0,"C",1);
        $CI->pdf->Cell( 10,5,"CANT.",0,0,"C",1);
        $CI->pdf->Cell( 15,5,"UBICA.",0,1,"C",1);
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFont('Arial','',6);
        $filter2     = new stdClass();
        $filter2_not = new stdClass();
        $filter2->numero = $numeros;
        $nsalida_det = $this->nsalida_model->listar_detalle($filter2,$filter2_not);
        $i = 1;
        foreach($nsalida_det as $indice=>$value){
            $codigo   = $value->codigo;
            $cantidad = $value->cantidad;
            $ubica    = $value->ubica;
            $unidad   = isset($arrproducto[$codigo]->unimed)?$arrproducto[$codigo]->unimed:"";
            $descri   = isset($arrproducto[$codigo]->despro)?$arrproducto[$codigo]->despro:":::BORRADO:::";
            $CI->pdf->Cell(12,6,$i,1,0,"C",0);
            $CI->pdf->Cell(20,6,$codigo,1,0,"C",0);
            $CI->pdf->Cell(12,6,$unidad,1,0,"C",0);    
            $CI->pdf->Cell(125,6,$descri,1,0,"L",0);    
            $CI->pdf->Cell(10,6, number_format($cantidad,2),1,0,"R",0);    
            $CI->pdf->Cell(15,6,$ubica,1,1,"C",0);   
            $i++;
        }
        $CI->pdf->SetFillColor(255,255,255);
        $CI->pdf->Cell(65,20,"",0,0,"C",0);
        $CI->pdf->Cell( 65,20,"",0,0,"C",0);
        $CI->pdf->Cell( 65,20,"",0,1,"C",0);
        $CI->pdf->Cell(65,5, "---------------------------------------",0,0,"C",0);
        $CI->pdf->Cell( 65,5,"---------------------------------------",0,0,"C",0);
        $CI->pdf->Cell( 65,5,"---------------------------------------",0,1,"C",0);
        $CI->pdf->Cell( 65,5,"SOLICITANTE",0,0,"C",1);
        $CI->pdf->Cell( 65,5,"AUTORIZADO POR",0,0,"C",1);
        $CI->pdf->Cell( 65,5,"ALMACEN",0,1,"C",1);
        $CI->pdf->Cell(65,5,"",0,0,"C",1);
        $CI->pdf->Cell( 65,5,"",0,0,"C",1);
        $CI->pdf->Cell( 65,5,"",0,1,"C",1);
        $CI->pdf->Cell(65,5,"Usuario: ".$user,0,0,"C",1);
        $CI->pdf->Cell( 65,5,"",0,0,"C",1);
        $CI->pdf->Cell( 65,5,"Peso Estimado: 0.000",0,1,"C",1);
        $CI->pdf->Output();
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
