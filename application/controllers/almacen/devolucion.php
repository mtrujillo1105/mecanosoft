<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once "Spreadsheet/Excel/Writer.php";
class Devolucion extends CI_Controller {
    var $entidad;
    var $login;
    public function __construct(){
        parent::__construct();
        date_default_timezone_set('America/Los_Angeles');       
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");    
        $this->load->model(compras.'requis_model');
        $this->load->model(compras.'proveedor_model');
        $this->load->model(almacen.'producto_model');
        $this->load->model(almacen.'devolucion_model');
        $this->load->model(personal.'responsable_model');
        $this->load->model(ventas.'ot_model');
        $this->load->helper('form','url');
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');
    }
    
    public function index(){
        $this->load->view(almacen."devolucion_listar",$data);
    }
    
    public function listar($j=0){
        $offset             = (int)$this->uri->segment(3);
        $conf['base_url']   = site_url('almacen/devolucion/listar/');
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
        $listado            = $this->devolucion_model->listar($filter,$filter_not,"",$conf['per_page'],$offset);
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
                 $filter2 = new stdClass();
                 $filter2->codot = $codot;
                 $ots    = $this->ot_model->obtenerg($filter2,new stdClass());
                 $nroot  = isset($ots->NroOt)?$ots->NroOt:"";
                 $fila  .= "<tr class='".($indice%2==0?'itemParTabla':'itemParTabla')."'>";
                 $fila  .= "<td align='center'>".$item++."</td>";
                 $fila  .= "<td align='center'>".date_sql($fecha)."</td>";
                 $fila  .= "<td align='center'>".$nroot."</td>";
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
        $data['titulo_busqueda'] = "Buscar Devolucion";
        $data['titulo_tabla']    = "Relaci&oacute;n de Devoluciones";
        $this->pagination->initialize($conf);
        $data['paginacion']      = $this->pagination->create_links();
        $this->load->view(almacen."devolucion_listar",$data);
			
    }
    public function nueva()
    {
        $this->load->library('layout', 'layout');
        $usuario                = $this->somevar['user'];
        $datos_usuario          = $this->usuario_model->obtener($usuario);
        $nombre_usuario         = $datos_usuario->PERSC_Nombre." ".$datos_usuario->PERSC_ApellidoPaterno;
        $fecha                  = explode(" ",$this->_hoy);
        $data['titulo']         = "Nuevo Comprobante de Ingreso";
        $data['form_open']      = form_open(base_url().'index.php/almacen/guiain/grabar',array("name"=>"frmGuiain","id"=>"frmGuiain","onsubmit"=>"return valida_guiain();"));
        $data['oculto']         = form_hidden(array("base_url"=>base_url(),"guiain_id"=>'',"centro_costo"=>1,"accion"=>"n","GenInd"=>""));
        $data['numero']  	= form_input(array("name"=>"numero","id"=>"numero","class"=>"cajaPequena2","readonly"=>"readonly","maxlength"=>"10"));
        $data['fecha']  	= form_input(array("name"=>"fecha","id"=>"fecha","class"=>"cajaPequena","readonly"=>"readonly","maxlength"=>"10","value"=>$fecha[0]));
        $data['nombre_usuario'] = form_input(array("name"=>"nombre_usuario","id"=>"nombre_usuario","class"=>"cajaMedia","readonly"=>"readonly","maxlength"=>"30","value"=>$nombre_usuario));
        $data['proveedor']        = form_input(array("name"=>"proveedor","id"=>"proveedor","class"=>"cajaPequena2","readonly"=>"readonly","maxlength"=>"30","value"=>"","type"=>"hidden"));
        $data['nombre_proveedor'] = form_input(array("name"=>"nombre_proveedor","id"=>"nombre_proveedor","class"=>"cajaMedia","readonly"=>"readonly","maxlength"=>"50","value"=>""));
        $data['ruc']              = form_input(array("name"=>"ruc","id"=>"ruc","class"=>"cajaPequena2","readonly"=>"readonly","maxlength"=>"11","value"=>"","onblur"=>"obtener_proveedor();","onkeypress","return numbersonly(this,event,'.');","type"=>"hidden"));
        $data['verproveedor']     = "";
        $data['verproducto']      = "";
        $data['hidden']		  = "style='display:none;'";
        $data['detalle']          = array();
        $filterin                 = new stdClass();
        $filterin->TIPOMOVC_Tipo  = 1;
        $data['cboAlmacen']       = form_dropdown("almacen",$this->almacen_model->seleccionar(),""," class='comboMedio' id='almacen'");
        $data['cboDocumento']     = form_dropdown("referencia",$this->documento_model->seleccionar(),"10"," class='comboPequeno' id='referencia'");
        $data['cboTipoMov']       = form_dropdown("tipo_movimiento",$this->tipomovimiento_model->seleccionar($filterin),"3"," class='comboMedio' id='tipo_movimiento'");
        $data['cboOcompra']       = form_dropdown("orden_compra",$this->ocompra_model->seleccionar2(),""," class='comboMedio' id='orden_compra' onchange='obtener_detalle_ocompra();'");
        $data['form_close']       = form_close();
        $data['numero_ref']       = form_input(array("name"=>"numero_ref","id"=>"numero_ref","class"=>"cajaPequena","maxlength"=>"20","onkeypress"=>"return numbersonly(this,event,true);"));
        $data['fecha_emision']    = form_input(array("name"=>"fecha_emision","id"=>"fecha_emision","class"=>"cajaPequena","maxlength"=>"10"));
        $data['nombre_transportista'] = form_input(array("name"=>"nombre_transportista","id"=>"nombre_transportista","class"=>"cajaPequena","maxlength"=>"10"));
        $data['ruc_transportista']    = form_input(array("name"=>"ruc_transportista","id"=>"ruc_transportista","class"=>"cajaPequena","maxlength"=>"11","onkeypress"=>"return numbersonly(this,event);"));
        $data['marca_placa']      = form_input(array("name"=>"marca_placa","id"=>"marca_placa","class"=>"cajaPequena","maxlength"=>"10"));
        $data['certificado']      = form_input(array("name"=>"certificado","id"=>"certificado","class"=>"cajaPequena","maxlength"=>"10"));
        $data['licencia']         = form_input(array("name"=>"licencia","id"=>"licencia","class"=>"cajaPequena","maxlength"=>"10"));
        $data['observacion']      = form_textarea(array("name"=>"observacion","id"=>"observacion","class"=>"fuente8","cols"=>"108","rows"=>"3"));
        $this->layout->view('almacen/guiain_nueva',$data);
    }
    public function editar($codigo)
    {
        $this->load->library('layout', 'layout');
        $modo            = "modificar";
        $datos_guiain    = $this->guiain_model->obtener($codigo);
        $tipo_movimiento = $datos_guiain[0]->TIPOMOVP_Codigo;
        $almacen         = $datos_guiain[0]->ALMAP_Codigo;
        $usuario         = $datos_guiain[0]->USUA_Codigo;
        $proveedor       = $datos_guiain[0]->PROVP_Codigo;
        $ocompra         = $datos_guiain[0]->OCOMP_Codigo;
        $referencia	 = $datos_guiain[0]->DOCUP_Codigo;
        $numero_ref      = $datos_guiain[0]->GUIAINC_NumeroRef;
        $numero          = $datos_guiain[0]->GUIAINC_Numero;
        $fecha           = $datos_guiain[0]->GUIAINC_Fecha;
        $fecha_emision   = explode(" ",$datos_guiain[0]->GUIAINC_FechaEmision);
        if($fecha_emision[0]=="0000-00-00") $fecha_emision[0]="";
        $observacion     = $datos_guiain[0]->GUIAINC_Observacion;
        $marca_placa     = $datos_guiain[0]->GUIAINC_MarcaPlaca;
        $certificado     = $datos_guiain[0]->GUIAINC_Certificado;
        $licencia        = $datos_guiain[0]->GUIAINC_Licencia;
        $ruc_transportista    = $datos_guiain[0]->GUIAINC_RucTransportista;
        $nombre_transportista = $datos_guiain[0]->GUIAINC_NombreTransportista;
        $datos_proveedor = $this->proveedor_model->obtener($proveedor);
        $nombre_proveedor= $datos_proveedor->nombre;
        $ruc             = $datos_proveedor->ruc;
        $datos_usuario   = $this->usuario_model->obtener($usuario);
        $nombre_usuario  = $datos_usuario->PERSC_Nombre." ".$datos_usuario->PERSC_ApellidoPaterno;
        $data['titulo']           = "EDITAR COMPROBANTE DE INGRESO";
        $data['form_open']        = form_open(base_url().'index.php/almacen/guiain/grabar',array("name"=>"frmGuiain","id"=>"frmGuiain"));
        $data['oculto']           = form_hidden(array('accion'=>"m",'guiain_id'=>$codigo,'modo'=>$modo,'base_url'=>base_url(),"GenInd"=>'G'));
        $data['numero']  	  = form_input(array("name"=>"numero","id"=>"numero","class"=>"cajaPequena2","readonly"=>"readonly","maxlength"=>"10","value"=>$numero));
        $data['fecha']  	  = form_input(array("name"=>"fecha","id"=>"fecha","class"=>"cajaPequena","readonly"=>"readonly","maxlength"=>"10","value"=>$fecha));
        $data['nombre_usuario']   = form_input(array("name"=>"nombre_usuario","id"=>"nombre_usuario","class"=>"cajaMedia","readonly"=>"readonly","maxlength"=>"30","value"=>$nombre_usuario));
        $data['proveedor']        = form_input(array("name"=>"proveedor","id"=>"proveedor","class"=>"cajaPequena2","readonly"=>"readonly","maxlength"=>"30","value"=>"","type"=>"hidden","value"=>$proveedor));
        $data['nombre_proveedor'] = form_input(array("name"=>"nombre_proveedor","id"=>"nombre_proveedor","class"=>"cajaMedia","readonly"=>"readonly","maxlength"=>"50","value"=>$nombre_proveedor));
        $data['ruc']              = form_input(array("name"=>"ruc","id"=>"ruc","class"=>"cajaPequena2","readonly"=>"readonly","maxlength"=>"11","value"=>"","onblur"=>"obtener_proveedor();","onkeypress","return numbersonly(this,event,'.');","type"=>"hidden","value"=>$ruc));
        $data['verproveedor']     = "";
        $data['verproducto']      = "";
        $data['hidden']		  = "style='display:none;'";
        $filterin                 = new stdClass();
        $filterin->TIPOMOVC_Tipo  = 1;
        $data['cboAlmacen']       = form_dropdown("almacen",$this->almacen_model->seleccionar(),$almacen," class='comboMedio' id='almacen'");
        $data['cboDocumento']     = form_dropdown("referencia",$this->documento_model->seleccionar(),$referencia," class='comboPequeno' id='referencia'");
        $data['cboTipoMov']       = form_dropdown("tipo_movimiento",$this->tipomovimiento_model->seleccionar($filterin),$tipo_movimiento," class='comboMedio' id='tipo_movimiento'");
        $data['cboOcompra']       = form_dropdown("orden_compra",$this->ocompra_model->seleccionar(),$ocompra," class='comboMedio' id='orden_compra' onchange='obtener_detalle_ocompra();'");
        $data['numero_ref']       = form_input(array("name"=>"numero_ref","id"=>"numero_ref","class"=>"cajaPequena","maxlength"=>"20","value"=>$numero_ref,"onkeypress"=>"return numbersonly(this,event,true);"));
        $data['fecha_emision']    = form_input(array("name"=>"fecha_emision","id"=>"fecha_emision","class"=>"cajaPequena","maxlength"=>"10","value"=>$fecha_emision[0]));
        $data['nombre_transportista'] = form_input(array("name"=>"nombre_transportista","id"=>"nombre_transportista","class"=>"cajaPequena","maxlength"=>"10","value"=>$nombre_transportista));
        $data['ruc_transportista']    = form_input(array("name"=>"ruc_transportista","id"=>"ruc_transportista","class"=>"cajaPequena","maxlength"=>"11","value"=>$ruc_transportista,"onkeypress"=>"return numbersonly(this,event);"));
        $data['marca_placa']      = form_input(array("name"=>"marca_placa","id"=>"marca_placa","class"=>"cajaPequena","maxlength"=>"10","value"=>$marca_placa));
        $data['certificado']      = form_input(array("name"=>"certificado","id"=>"certificado","class"=>"cajaPequena","maxlength"=>"10","value"=>$certificado));
        $data['licencia']         = form_input(array("name"=>"licencia","id"=>"licencia","class"=>"cajaPequena","maxlength"=>"10","value"=>$licencia));
        $data['observacion']      = form_textarea(array("name"=>"observacion","id"=>"observacion","class"=>"fuente8","cols"=>"108","rows"=>"3","value"=>$observacion));
        $data['form_close']       = form_close();
        /*Detalle*/
        $detalle               = $this->guiaindetalle_model->obtener2($codigo);
        $detalle_guiain        = array();
        if(count($detalle)>0){
             foreach($detalle as $indice=>$valor)
             {
                $detguiain   = $valor->GUIAINDETP_Codigo;
                $producto    = $valor->PRODCTOP_Codigo;
                $unidad      = $valor->UNDMED_Codigo;
                $cantidad    = $valor->GUIAINDETC_Cantidad;
                $costo        = $valor->GUIAINDETC_Costo;
                $GenInd       = $valor->GUIIAINDETC_GenInd;
                $datos_producto  = $this->producto_model->obtener_producto($producto);
                $datos_unidad    = $this->unidadmedida_model->obtener($unidad);
                $nombre_producto = $datos_producto[0]->PROD_Nombre;
                $codigo_interno  = $datos_producto[0]->PROD_CodigoInterno;
                $nombre_unidad   = $datos_unidad[0]->UNDMED_Simbolo;
                if($GenInd=="I"){
                    $filter2 = new stdClass();
                    $filter2->SERIC_Guiain = $codigo;
                    $arrserie = $this->serie_model->obtener($producto,$filter2);
                    $data2     = array();
                    if(count($arrserie)>0){
                        foreach($arrserie as $value){
                            $data2[] = $value->SERIC_Numero;
                        }
                    }
                    $_SESSION['serie'][$producto] = $data2;
                }
                $objeto          =   new stdClass();
                $objeto->GUIAINDETP_Codigo   = $detguiain;
                $objeto->PROD_Codigo         = $producto;
                $objeto->PROD_CodigoInterno  = $codigo_interno;
                $objeto->GUIAINDETC_Cantidad = $cantidad;
                $objeto->GUIAINDETC_Costo    = $costo;
                $objeto->UNDMED_Codigo       = $unidad;
                $objeto->PROD_Nombre         = $nombre_producto;
                $objeto->UNDMED_Simbolo      = $nombre_unidad;
                $objeto->GenInd              = $GenInd;
                $detalle_guiain[]            = $objeto;
            }
        }
        $data['detalle']                     = $detalle_guiain;
        $this->layout->view('almacen/guiain_nueva',$data);
    }
    public function grabar()
    {
        $this->form_validation->set_rules('nombre_usuario','usuario','required');
        $this->form_validation->set_rules('nombre_proveedor','proveedor','required');
        $this->form_validation->set_rules('almacen','almacen','required');
        $this->form_validation->set_rules('orden_compra','orden de compra','required');
        $this->form_validation->set_rules('tipo_movimiento','motivo de movimiento','required');
        $this->form_validation->set_rules('referencia','documento de referencia','required');
        $this->form_validation->set_rules('GenInd','numeros de serie','required');
        if($this->form_validation->run() == FALSE){
            $this->nueva();
        }
        else{
            $guiain_id            = $this->input->post("guiain_id");
            $almacen              = $this->input->post("almacen");
            $orden_compra         = $this->input->post("orden_compra");
            $proveedor            = $this->input->post("proveedor");
            $referencia           = $this->input->post("referencia");
            $numero_ref           = $this->input->post("numero_ref");
            $tipo_movimiento      = $this->input->post("tipo_movimiento");
            $fecha                = $this->input->post("fecha");
            $fecha_emision        = $this->input->post("fecha_emision");
            $nombre_transportista = $this->input->post("nombre_transportista");
            $ruc_transportista    = $this->input->post("ruc_transportista");
            $marca_placa          = $this->input->post("marca_placa");
            $certificado          = $this->input->post("certificado");
            $licencia             = $this->input->post("licencia");
            $observacion          = $this->input->post("observacion");
            $accion               = $this->input->post("accion");
            $prodcodigo           = $this->input->post('prodcodigo');
            $produnidad           = $this->input->post('produnidad');
            $prodcantidad         = $this->input->post('prodcantidad');
            $prodpu               = $this->input->post('prodpu');
            $prodimporte          = $this->input->post('prodimporte');
            $detaccion            = $this->input->post('detaccion');
            $detguiain            = $this->input->post('detguiain');
            $flagGenInd           = $this->input->post('flagGenInd');
            $detobserv            = "oob";
            $filter = new stdClass();
            $filter->TIPOMOVP_Codigo             = $tipo_movimiento;
            $filter->ALMAP_Codigo                = $almacen;
            $filter->PROVP_Codigo                = $proveedor;
            $filter->OCOMP_Codigo                = $orden_compra;
            $filter->DOCUP_Codigo                = $referencia;
            $filter->GUIAINC_NumeroRef           = $numero_ref;
            $filter->GUIAINC_Fecha               = $fecha;
            $filter->GUIAINC_FechaEmision        = $fecha_emision;
            $filter->GUIAINC_FechaModificacion   = $this->_hoy;
            $filter->GUIAINC_Observacion         = $observacion;
            $filter->GUIAINC_MarcaPlaca          = $marca_placa;
            $filter->GUIAINC_Certificado         = $certificado;
            $filter->GUIAINC_Licencia            = $licencia;
            $filter->GUIAINC_RucTransportista    = $ruc_transportista;
            $filter->GUIAINC_NombreTransportista = $nombre_transportista;
            $filter->USUA_Codigo                 = $this->somevar['user'];
            if($accion=="m"){
                $this->guiaindetalle_model->eliminar2($guiain_id);
                $this->ocompra_model->modificar_detocompra_flagsIngresos($orden_compra);
            }
            if(isset($guiain_id) && $guiain_id>0){
              unset($filter->GUIAINC_Numero);
              $this->guiain_model->modificar($guiain_id,$filter);
            }
            else{
               unset($filter->GUIAINC_FechaModificacion);
               $guiain_id = $this->guiain_model->insertar($filter);
            }
            if(count($prodcodigo)>0){
               foreach($prodcodigo as $indice=>$valor){
                 $producto = $prodcodigo[$indice];
                 $unidad   = $produnidad[$indice];
                 $cantidad = $prodcantidad[$indice];
                 $pu       = $prodpu[$indice];
                 $importe  = $prodimporte[$indice];
                 $accion   = $detaccion[$indice];
                 $detg     = $detguiain[$indice];
                 $detflag  = $flagGenInd[$indice];
                 $observ   = "Insertar";
                 $filter2  = new stdClass();
                 $filter2->GUIAINP_Codigo      = $guiain_id;
                 $filter2->PRODCTOP_Codigo     = $producto;
                 $filter2->UNDMED_Codigo       = $unidad;
                 $filter2->GUIAINDETC_Cantidad = $cantidad;
                 $filter2->GUIAINDETC_Costo    = $pu;
                 $filter2->GUIIAINDETC_GenInd  = $detflag;
                 $filter2->OCOMP_Codigo        = $orden_compra;
                 $this->guiaindetalle_model->insertar($filter2);
                 $this->ocompra_model->modificar_detocompra_flagIngreso($orden_compra,$producto);
               }
               $this->ocompra_model->modificar_ocompra_flagIngreso($orden_compra);
            }
            unset($_SESSION['serie']);//Elimina la serie
            redirect('almacen/guiain/listar');
        }
    }
    public function ver(){
        $hora_Actual=date("H:i:s");
        $numeros        = $this->input->get_post('numero');
        $filter         = new stdClass();
        $filter_not     = new stdClass();
        $filter->numero = $numeros;
        
        
        
        $nsalida_det    = $this->devolucion_model->listar_detalle($filter,$filter_not);
        
        
       // print_r($nsalida_det);die;
        
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
        $CI->pdf->Cell(0,8, "DEVOLUCION No 001 - ".$numeros,0,1,"C",0);
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
        $nsalida_det = $this->devolucion_model->listar_detalle($filter2,$filter2_not);
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
        $CI->pdf->Cell(90,5, "---------------------------------------",0,0,"C",0);
        
        $CI->pdf->Cell( 90,5,"---------------------------------------",0,1,"C",0);
        $CI->pdf->Cell( 90,5,"ALMACEN",0,0,"C",1);
        
        $CI->pdf->Cell( 90,5,"RESPONSABLE",0,1,"C",1);
        $CI->pdf->Cell(65,5,"",0,0,"C",1);
        $CI->pdf->Cell( 65,5,"",0,0,"C",1);
        $CI->pdf->Cell( 65,5,"",0,1,"C",1);
        $CI->pdf->Cell(65,5,"Usuario: ".$user,0,0,"C",1);
        $CI->pdf->Cell( 65,5,"",0,0,"C",1);
        $CI->pdf->Cell( 65,5,"Peso Estimado: 0.000",0,1,"C",1);
        $CI->pdf->Output();
    }
    
    public function eliminar(){
        $this->load->model("almacen/guiaindetalle_model");
        $id = $this->input->post('codigo');
        $datos_guiain = $this->guiain_model->obtener($id);
        $orden_compra = $datos_guiain[0]->OCOMP_Codigo;
        $this->guiaindetalle_model->eliminar2($id);
        $this->ocompra_model->modificar_detocompra_flagsIngresos($orden_compra);
        $this->ocompra_model->modificar_ocompra_flagIngreso($orden_compra);
        $this->guiain_model->eliminar($id);
        echo true;
    }
    
    public function cancelar(){
        unset($_SESSION['serie']);//Elimina la serie
        redirect('almacen/guiain/listar');
    }
}
?>
