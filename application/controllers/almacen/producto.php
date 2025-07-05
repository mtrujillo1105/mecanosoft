<?php header("Content-type: text/html; charset=utf-8"); 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once "Spreadsheet/Excel/Writer.php";

class Producto extends CI_Controller {
    var $compania;
    var $configuracion;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");        
        $this->load->model(almacen.'producto_model');
        $this->load->model(almacen.'almacen_model');
        $this->load->model(almacen.'familia_model');
        $this->load->model(almacen.'fabricante_model');
        $this->load->model(almacen.'tipoproducto_model');
        $this->load->model(almacen.'cierre_model');      
        $this->load->model(almacen.'nsalida_model');
        $this->load->model(almacen.'ningreso_model'); 
        $this->load->model(almacen.'tipmaterial_conta_model');
        $this->load->model(almacen.'kardex_model');
        $this->load->model(almacen.'plantilla_model');
        $this->load->model(almacen.'productoproveedor_model');
        $this->load->model(almacen.'unidadmedida_model');
        $this->load->model(almacen.'linea_model');
        $this->load->model(almacen.'marca_model');  
        $this->load->model(compras.'requis_model');
        $this->load->model(compras.'ocompra_model');
        $this->load->model(compras.'proveedor_model');
        $this->load->model(maestros.'tc_model');
        $this->load->model(maestros.'empresa_model');
        $this->load->model(maestros.'moneda_model');
        $this->load->model(maestros.'persona_model');
        $this->load->model(seguridad.'permiso_model');  
        $this->configuracion = $this->config->item('conf_pagina');
        $this->login   = $this->session->userdata('login');
    }

    public function index(){
        $this->load->view('seguridad/inicio');
    }
    
    public function listar($j=0){
        $filter     = new stdClass();
        $filter_not = new stdClass(); 
        $filter->order_by    = array("p.PROD_Nombre"=>"asc");
        $registros = count($this->producto_model->listar($filter,$filter_not));
        $productos = $this->producto_model->listar($filter,$filter_not,$this->configuracion['per_page'],$j);
        $item      = 1;
        $lista     = array();
        if(count($productos)>0){
            foreach($productos as $indice=>$valor){
                $filter       = new stdClass();
                $filter->tipo = $valor->TIPPROD_Codigo;
                $tipo       = $this->tipoproducto_model->obtener($filter);
                $familia    = $this->familia_model->obtener($valor->FAMI_Codigo);
                $fabricante = $this->fabricante_model->obtener($valor->FABRIP_Codigo);                
                $lista[$indice]             = new stdClass();
                $lista[$indice]->codigo     = $valor->PROD_Codigo;
                $lista[$indice]->interno    = $valor->PROD_CodigoInterno;
                $lista[$indice]->nombre     = $valor->PROD_Nombre;
                $lista[$indice]->estado     = $valor->PROD_FlagEstado;
                $lista[$indice]->activo     = $valor->PROD_FlagActivo;
                $lista[$indice]->tipo       = $tipo->TIPPROD_Descripcion;
                $lista[$indice]->familia    = $familia->FAMI_Descripcion;
                $lista[$indice]->fabricante = $fabricante->FABRIC_Descripcion;
            }
        }
        $configuracion = $this->configuracion;
        $configuracion['base_url']    = base_url()."index.php/almacen/producto/listar";
        $configuracion['total_rows']  = $registros;
        $this->pagination->initialize($configuracion);
        /*Enviamos los datos a la vista*/
        $data['titulo_tabla']    = "RELACI&Oacute;N de PRODUCTOS";
        $data['titulo_busqueda'] = "BUSCAR PRODUCTO";
        $data['form_open']  = form_open('',array("name"=>"form1","id"=>"form1","method"=>"post"));     
        $data['form_close'] = form_close();          
        $data['lista']      = $lista;
        $data['codigo']     = "";
        $data['nombre']     = "";
        $data['familia']    = "";
        $data['registros']  = $registros;
        $data['paginacion'] = $this->pagination->create_links();
        $this->load->view('almacen/producto_index',$data);
    }

    public function editar($accion,$codigo=""){
        $lista = new stdClass();
        if($accion == "e"){
            $filter              = new stdClass();
            $filter->producto    = $codigo;
            $productos           = $this->producto_model->obtener($filter);
            $lista->familia      = $productos->FAMI_Codigo;
            $lista->tipo         = $productos->TIPPROD_Codigo;
            $lista->moneda       = $productos->MONED_Codigo;
            $lista->fabricante   = $productos->FABRIP_Codigo;  
            $lista->linea        = $productos->LINP_Codigo;  
            $lista->marca        = $productos->MARCP_Codigo;  
            $lista->nombre       = $productos->PROD_Nombre;  
            $lista->descripcion  = $productos->PROD_DescripcionBreve;  
            $lista->imagen       = $productos->PROD_Imagen;  
            $lista->especificacion = $productos->PROD_EspecificacionPDF;  
            $lista->modelo       = $productos->PROD_Modelo;  
            $lista->presentacion = $productos->PROD_Presentacion;  
            $lista->generico     = $productos->PROD_GenericoIndividual;  
            $lista->comentario   = $productos->PROD_Comentario;  
            $lista->stock        = $productos->PROD_Stock;  
            $lista->flagactivo   = $productos->PROD_FlagActivo;  
            $lista->interno      = $productos->PROD_CodigoInterno;
            $lista->unidad       = $productos->UNDMED_Codigo;
            $lista->estado       = $productos->PROD_FlagEstado;
            $familias            = $this->familia_model->obtener($productos->FAMI_Codigo);
            $lista->familia_interno = $familias->FAMI_CodigoInterno.'.';  
            $lista->familia_nombre  = $familias->FAMI_Descripcion;             
        }
        elseif($accion == "n"){
            $lista->familia      = 0;
            $lista->tipo         = 0;
            $lista->moneda       = 1;
            $lista->fabricante   = 0;  
            $lista->linea        = 0;  
            $lista->marca        = 0;  
            $lista->nombre       = "";  
            $lista->descripcion  = "";  
            $lista->imagen       = "";  
            $lista->especificacion = "";  
            $lista->modelo       = "";  
            $lista->presentacion = "";  
            $lista->generico     = "";  
            $lista->comentario   = "";  
            $lista->stock        = 0;  
            $lista->flagactivo   = "";  
            $lista->interno      = "";
            $lista->unidad       = 0; 
            $lista->estado       = 0; 
            $lista->familia_interno = "";
            $lista->familia_nombre  = "";
        }
        $arrEstado          = array("0"=>"::Seleccione::","1"=>"ACTIVO","2"=>"INACTIVO");
        $data['titulo']     = "EDITAR PRODUCTO :: ".$lista->nombre;
        $data['form_open']  = form_open('',array("name"=>"form1","id"=>"form1","onsubmit"=>"return valida_producto();","method"=>"post","enctype"=>"multipart/form-data"));     
        $data['form_close'] = form_close();    
        $data['lista']	    = $lista;
        $data['selestado']  = form_dropdown('estado',$arrEstado,$lista->estado,"id='estado' class='comboMedio'");
        $data['selmoneda']  = form_dropdown('moneda',$this->moneda_model->seleccionar(),$lista->moneda,"id='moneda' class='comboMedio'");
        $data['selfabricante'] = form_dropdown('fabricante',$this->fabricante_model->seleccionar(),$lista->fabricante,"id='fabricante' class='comboMedio'");
        $data['sellinea']   = form_dropdown('linea',$this->linea_model->seleccionar(),$lista->linea,"id='linea' class='comboMedio'");
        $data['selmarca']   = form_dropdown('marca',$this->marca_model->seleccionar(),$lista->marca,"id='marca' class='comboMedio'");
        $data['selunidad']  = form_dropdown('unidadmedida',$this->unidadmedida_model->seleccionar(),$lista->unidad,"id='unidadmedida' class='comboMedio'");        
        $data['seltipo']    = form_dropdown('tipoprod',$this->tipoproducto_model->seleccionar(),$lista->tipo,"id='tipoprod' class='comboMedio'");        
        $data['oculto']     = form_hidden(array('accion'=>$accion,'codigo'=>$codigo));
        $data['links']     = array("urlprod"=>base_url()."index.php/almacen/producto/editar/".$accion."/".$codigo,"urlatrib"=>base_url()."index.php/almacen/productoatributo/listar/".$accion."/".$codigo,"urlcomp"=>"");
        $this->load->view('almacen/producto_nuevo',$data);
    }  
    
    public function grabar(){
        $accion = $this->input->get_post('accion');
        $codigo = $this->input->get_post('codigo');
        $data   = array(
                        "PROD_Nombre"             => strtoupper($this->input->post('nombre')),
                        "PROD_DescripcionBreve"   => strtoupper($this->input->post('descripcion')),
                        "PROD_EspecificacionPDF"  => strtoupper($this->input->post('pdf')),
                        "PROD_Comentario"         => strtoupper($this->input->post('comentario')),
                        "PROD_CodigoInterno"      => strtoupper($this->input->post('interno')),
                        "PROD_Imagen"             => strtoupper($this->input->post('imagen')),
                        "PROD_Modelo"             => strtoupper($this->input->post('modelo')),
                        "PROD_Presentacion"       => strtoupper($this->input->post('presentacion')),
                        "PROD_GenericoIndividual" => strtoupper($this->input->post('generico')),
                        "PROD_FlagEstado"         => strtoupper($this->input->post('estado')),
                        "FABRIP_Codigo"           => strtoupper($this->input->post('fabricante')),
                        "MARCP_Codigo"            => strtoupper($this->input->post('marca')),
                        "UNDMED_Codigo"           => strtoupper($this->input->post('unidadmedida')),
                        "MONED_Codigo"            => strtoupper($this->input->post('moneda')),
                        "LINP_Codigo"             => strtoupper($this->input->post('linea')),
                        "TIPPROD_Codigo"          => strtoupper($this->input->post('tipoprod')),
                        "PROD_FlagEstado"         => strtoupper($this->input->post('estado'))
                       );
        if($accion == "n"){
            $this->producto_model->insertar($data);            
        }
        elseif($accion == "e"){
            $this->producto_model->modificar($codigo,$data);            
        }
    }   
    
    public function eliminar(){
        $codigo = $this->input->post('codigo');
        $this->producto_model->eliminar($codigo);
    } 

 // funcion para buscar   
    public function buscar($n=""){
         
       // $tipo    = $this->input->get_post('tipo');
       // $ot      = $this->input->get_post('ot');
       $descripcion = $this->input->get_post('descripcion');
       $cod_producto = $this->input->get_post('cod_producto');
        
       // $filter  = new stdClass();
       // $filter->anio = date('Y',time());
       
        $fila   = "";
        $filter = new stdClass();
        
        if($descripcion!='')      $filter->descripcion      = $descripcion;//echo 'el nombre es : '.  $filter->descripcion; 
        if($cod_producto!='')      $filter->codpro      = $cod_producto;//echo 'el codigo dio : '.  $filter->codpro; 
        
        
        $productos = $this->producto_model->listar($filter,'','');         
       
        if(count($productos)>0){
            foreach($productos as $indice=>$value){
                $codpro  = $value->codpro;
                $despro   = $value->despro;
                
                $fila .= "<tr  title='codigo: ".$codpro."'     id='".$codpro."' id2='".$despro."'  id3='".$despro."' onclick='pasar_producto(this);'>";
                $fila .= "<td style='width:30%;' align='center'><p class='listadoot'>".$codpro."</p></td>";
                $fila .= "<td style='width:70%;' align='left'><p class='listadoot'>".$despro."</p></td>";
                
                $fila .= "</tr>";
            }
        }  
        else{
            $fila.="<tr>";
            $fila.="<td colspan='3'>NO EXISTEN RESULTADOS</td>";
            $fila.="</tr>";
        }
        $data['cod_producto']   = $cod_producto;
        $data['descripcion'] =$descripcion;
        $data['n']    = $n;
        $data['fila'] = $fila;
       // $data['tipoot']  = $tipoOt;
       // $data['rsocial'] = $rsocial;
        $this->load->view(almacen."producto_buscar",$data);  
    }   

    
    public function stock_productos_val(){
        $tipoalmacen  = $this->input->get_post('tipoalmacen');
        $tipomaterial = $this->input->get_post('tipomaterial');
        $linea        = $this->input->get_post('familia');
        $tipoexport   = $this->input->get_post('tipoexport');
        $fecha        = date("Y-m-d",time());
        /*Obtenemos las matriz ocompra*/
//        $fecha_prueba     = date_add($fecha,-30);
//        $filter           = new stdClass();
//        $filter_not       = new stdClass();
//        $filter->fechai   = date_sql($fecha_prueba);
//        $filter->flgAprobado = 1;
//        $order_by         = array('o.Fecemi','o.Gnumguia','o.Gcodpro');
//        $arrOrdenc        = array();
//        $arrFecha         = array();
//        $codoc_ant        = "";
//        $codpro_ant       = "";
//        $cantidad_ant     = "";
//        $ocompras         = $this->ocompra_model->listar_detalle($filter,$filter_not,$order_by);
//        foreach($ocompras as $indice => $value){
//            $codoc        = $value->nrodoc;
//            $codpro       = $value->gcodpro;
//            $cantidad     = $value->gcantidad;
//            $fecdoc       = $value->fecha;
//            if($codoc_ant==$codoc && $codpro_ant==$codpro){
//                $cantidad_ant = $cantidad + $cantidad_ant;    
//            }
//            else{
//                $cantidad_ant = $cantidad;   
//            }
//            $arrOrdenc[$codoc][$codpro] = $cantidad_ant;
//            $arrFecha[$codoc]  = $fecdoc;
//            $codoc_ant    = $codoc;
//            $codpro_ant   = $codpro;
//        }
        /*Obtengo la matriz de NEAs*/
//        $filter2          = new stdClass();
//        $filter2_not      = new stdClass();
//        $filter2->fechai  = date_sql($fecha_prueba);
//        $order_by         = array('k.fecha','k.numoc','k.codigo');
//        $arrNeas          = array();
//        $codoc_ant2       = "";
//        $codpro_ant2      = "";
//        $cantidad_ant2    = "";        
//        $neas             = $this->ningreso_model->listar_detalle($filter2,$filter2_not,$order_by);
//        foreach($neas as $indice2 => $value2){
//            $codoc2  = $value2->numoc;
//            $codpro2 = $value2->codigo;
//            $cantidad2 = $value2->cantidad;
//            if($codoc_ant2==$codoc2 && $codpro_ant2==$codpro2){
//                $cantidad_ant2 = $cantidad2 + $cantidad_ant2;    
//            }
//            else{
//                $cantidad_ant2 = $cantidad2;   
//            }
//            $arrNeas[$codoc2][$codpro2] = $cantidad_ant2;
//            $codoc_ant2    = $codoc2;
//            $codpro_ant2  = $codpro2; 
//        }
        /*Comparamos compras versus NEAS*/
        $mensaje = "";
//        foreach($arrOrdenc as $ocompra => $value3){
//            $delta = 0;
//            foreach($value3 as $codpro => $cantidad){
//                $cantidad_nea = isset($arrNeas[$ocompra][$codpro])?$arrNeas[$ocompra][$codpro]:0;
//                $delta = $delta + ($cantidad - $cantidad_nea);
//            }
//            if($delta!=0)  $mensaje .= "<p align='left'><code>".date_sql($arrFecha[$ocompra])."     $ocompra    $delta</code></p>";
//        }
        /*Obtengo la ultima fecha de cierre*/
        $ultimo           = $this->cierre_model->ultimo(new stdClass(),new stdClass());
        $fecha_ant        = ($this->entidad=='02')?$ultimo->fecha:(date_sql($ultimo->fecha));
        $nro_cierre       = $ultimo->nro;
        $fila             = "";
        $j=0;
        $almacenes        = $this->almacen_model->seleccionar(new stdClass(),"","000");
        $tipomateriales   = $this->tipmaterial_conta_model->seleccionar(new stdClass(),"","00");
        $lineas           = $this->familia_model->seleccionar(new stdClass(),"","0000");
        $cboTipoalmacen   = form_dropdown('tipoalmacen',$almacenes,$tipoalmacen,"id='tipoalmacen' class='comboMedio' onchange=\"$('#tipoexport').val('');$('#frmFact').submit();\" ");   
        $cboTipoamaterial = form_dropdown('tipomaterial',$tipomateriales,$tipomaterial,"id='tipomaterial' class='comboMedio' onchange=\"$('#tipoexport').val('');$('#frmFact').submit();\" ");   
        $cboFamilia       = form_dropdown('familia',$lineas,$linea,"id='familia' class='comboMedio' onchange=\"$('#tipoexport').val('');$('#frmFact').submit();\" ");   
        $filter3         = new stdClass();
        $filter3_not     = new stdClass();
        $filter3->fecha  = $fecha_ant;
        $filter3->numero = $nro_cierre;
        $cierres_det     = $this->cierre_model->listar_detalle($filter3,$filter3_not);
        if(count($cierres_det)>0){
            foreach($cierres_det as $indice2=>$value2){
                $codalmacen  = $value2->codalm;
                $codpro      = $value2->codpro;
                $stkanterior = $value2->stkanterior;
                $ingresos    = $value2->ingresos;
                $salidas     = $value2->salidas;
                $arrstock[$codpro]  = $value2->stkactual;
                $arrprecio[$codpro] = $value2->precio;
                $arrpprom[$codpro]  = $value2->preprom;
                $arrmoneda[$codpro] = $value2->moneda;
            }
        }
        /*Obtengo listado de productos*/
        $filter           = new stdClass();
        $filter_not       = new stdClass();
        if($tipoalmacen!='000')    $filter->codalmacen = $tipoalmacen;
        if($tipomaterial!='00')    $filter->codtipomaterial = $tipomaterial;
        if($linea!='0000')         $filter->codlinea = $linea;
        $productos              = $this->producto_model->listar($filter,$filter_not); 
        if($tipoexport==""){
            $zz = 0;
            foreach($productos as $indice => $value){
                $codigo       = $value->codpro;
                $descripcion  = $value->despro;
                $cantidad     = $value->stk_actual;
                $minimo       = $value->stk_min;
                $maximo       = $value->stk_max;
                $comprometido = $value->stk_comp;
                $transito     = $value->stk_trans;
                $codalm       = $value->codalm;
                $precio       = $value->precio;
                $preprom      = $value->precprom;
                $moneda       = $value->mo;
                $tcambio      = $value->t_cambio;
                $precioprod   = $moneda=='D'?($tcambio*$precio):$precio;
                $disponible   = $cantidad-$comprometido+$transito;
                $stockcierre  = (isset($arrstock[$codigo])?$arrstock[$codigo]:'&nbsp;');
                $precio2      = (isset($arrprecio[$codigo])?$arrprecio[$codigo]:'&nbsp;');
                $preprom2     = (isset($arrpprom[$codigo])?$arrpprom[$codigo]:'&nbsp;');
                $moneda2      = (isset($arrmoneda[$codigo])?$arrmoneda[$codigo]:'&nbsp;');
                $precioprod2  = $moneda2=='D'?($tcambio*$precio2):$precio2;
                $delta        = $cantidad-$stockcierre;
                if($delta!=0) $zz++;
                $fila        .= "<tr ".($delta!=0?"bgcolor='#FFD700'":'')." id='".$codigo."'>";
                $fila        .= "<td align='center'><a href='#' onclick='ver_kardex(this);'>".$codigo."</a></td>";
                $fila        .= "<td align='center'>".$codalm."</td>";
                $fila        .= "<td align='left'>".$descripcion."</td>";
//                $fila        .= "<td align='right'>".$minimo."</td>";
//                $fila        .= "<td align='right'>".$maximo."</td>";
                $fila        .= "<td align='right'>".$cantidad."</td>";	
                $fila        .= "<td align='right'>".$stockcierre."</td>";
                $fila        .= "<td align='right'><a href='#' onclick='ver_stockcomprometido(this);'>".$comprometido."</a></td>";
                $fila        .= "<td align='right'><a href='#' onclick='ver_stocktransito(this);'>".$transito."</a></td>";
                $fila        .= "<td align='right'>".$disponible."</td>";
                $fila        .= "<td align='right'>".$precioprod."</td>";
                $fila        .= "<td align='right'>".$preprom."</td>";
                $fila        .= "<td align='right'>".$precioprod2."</td>";
                $fila        .= "<td align='right'>".$preprom2."</td>";
                $fila        .= "</tr>";
                $j++;
            }    
        }
        elseif($tipoexport=='excel'){
            $xls = new Spreadsheet_Excel_Writer();
            $xls->send("Rpt_stockproducto.xls");
            $sheet  =$xls->addWorksheet('Reporte');
            $sheet->setColumn(0,0,9); //COLUMNA A1
            $sheet->setColumn(1,1,41); //COLUMNA B2
            $sheet->setColumn(2,2,29); //COLUMNA C3
            $sheet->setColumn(3,3,12); //COLUMNA D4
            $sheet->setColumn(4,4,15); //COLUMNA E5
            $sheet->setColumn(5,5,18); //COLUMNA F6
            $sheet->setColumn(6,6,18); //COLUMNA G7
            $sheet->setRow(0,50);
            $sheet->setRow(1,42);
            $format_bold=$xls->addFormat();
            $format_bold->setBold();
            $format_bold->setvAlign('vcenter');
            $format_bold->sethAlign('left');
            $format_bold->setBorder(1);
            $format_bold->setTextWrap();
            $format_bold2=$xls->addFormat();
            $format_bold2->setBold();
            $format_bold2->setvAlign('vcenter');
            $format_bold2->sethAlign('center');
            $format_bold2->setBorder(1);
            $format_bold2->setTextWrap();
            $format_titulo=$xls->addFormat();
            $format_titulo->setBold();
            $format_titulo->setSize(16);
            $format_titulo->setvAlign('vcenter');
            $format_titulo->sethAlign('center');
            $format_titulo->setBorder(1);
            $format_titulo->setTextWrap();
            $format_titulo2=$xls->addFormat();
            $format_titulo2->setBold();
            $format_titulo2->setSize(12);
            $format_titulo2->setvAlign('vcenter');
            $format_titulo2->sethAlign('center');
            $format_titulo2->setBorder(1);
            $format_titulo2->setTextWrap();
            $sheet->mergeCells(0,0,0,15);  
            //$sheet->write(1,0,"NRO",$format_titulo2);  $sheet->write(1,1,"NOMBRE",$format_titulo2);  $sheet->write(1,2,"PROYECTO",$format_titulo2);  $sheet->write(1,3,"F. INICIO",$format_titulo2);   $sheet->write(1,4,"F. TERMINO",$format_titulo2);   $sheet->write(1,5,"MATERIALES",$format_titulo2);   $sheet->write(1,6,"M.O. DIRECTA",$format_titulo2);        if($tiproducto!='02'){   $sheet->write(1,7,"SUBCONTRATOS",$format_titulo2);    $sheet->write(1,8,"COSTO DEL RESIDENTE",$format_titulo2);    $sheet->write(1,9,"ESTUDIOS Y PROYECTOS",$format_titulo2);     $sheet->write(1,10,"ADM. DIRECTA",$format_titulo2);     $sheet->write(1,11,"OTROS COSTOS DIRECTOS",$format_titulo2);      $sheet->write(1,12,"TRANSPORTE",$format_titulo2);     $sheet->write(1,13,"CONTINGENCIA",$format_titulo2);      $sheet->write(1,14,"SERVICIOS DIRECTOS",$format_titulo2);     $sheet->write(1,15,"TRANSPORTE",$format_titulo2);    $sheet->write(1,16,"CAJA CHICA",$format_titulo2);      $sheet->write(1,17,"VALOR VENTA",$format_titulo2);      $sheet->write(1,18,"COSTO TOTAL",$format_titulo2);      $sheet->write(1,19,"DELTA",$format_titulo2);    }else{   $sheet->write(1,7,"SERV. DIRECTOS",$format_titulo2);    $sheet->write(1,8,"TRANSPORTE",$format_titulo2);   $sheet->write(1,9,"CAJA CHICA",$format_titulo2);     $sheet->write(1,10,"VALOR VENTA",$format_titulo2);      $sheet->write(1,11,"COSTO TOTAL",$format_titulo2);     $sheet->write(1,12,"DELTA",$format_titulo2); }        
            $z=2;
            $y=2;
            foreach($productos as $indice => $value){
                $codigo       = $value->codpro;
                $descripcion  = $value->despro;
                $cantidad     = $value->stk_actual;
                $minimo       = $value->stk_min;
                $maximo       = $value->stk_max;
                $comprometido = $value->stk_comp;
                $transito     = $value->stk_trans;
                $codalm       = $value->codalm;
                $disponible   = $cantidad-$comprometido+$transito;
                $sheet->write($z,0,$codigo,$format_bold);
                $sheet->write($z,1,$codalm,$format_bold);
                $sheet->write($z,2,$descripcion,$format_bold);
                $sheet->write($z,3,$minimo,$format_bold);
                $sheet->write($z,4,$maximo,$format_bold);
                $sheet->write($z,5,$cantidad,$format_bold);
                $sheet->write($z,6,$comprometido,$format_bold);
                $sheet->write($z,6,$transito,$format_bold);
                $sheet->write($z,6,$disponible,$format_bold);
                $z++;
            } 
            $xls->close(); 
        }
        $data['cboTipoalmacen']   = $cboTipoalmacen;
        $data['cboTipoamaterial'] = $cboTipoamaterial;
        $data['cboFamilia']       = $cboFamilia;
        $data['fila']             = $fila;
        $data['zz']               = $zz;
        $data['feccierre']        = $fecha_ant;
        $data['mensaje']          = $mensaje;
        $data['oculto']       = form_hidden(array('serie'=>'','numero'=>'','cadenaot'=>'','codot'=>'','tipoexport'=>'','codpro'=>''));
        $this->load->view(almacen."stock_productos_val.php",$data);
    }    
    
    public function     stock_productos(){
        $tipoalmacen  = $this->input->get_post('tipoalmacen');
        $tipomaterial = $this->input->get_post('tipomaterial');
        $linea        = $this->input->get_post('familia');
        $tipoexport   = $this->input->get_post('tipoexport');
        $chknegativo  = $this->input->get_post('chknegativo');
        $chkprecio    = $this->input->get_post('chkprecio');
        $moneda_doc   = $this->input->get_post('moneda');
        $checked      = (($chknegativo=='1' || !isset($_REQUEST['tipoalmacen']))?true:false);
        $checkedprecio      = (($chkprecio=='1')?true:false);
        if($moneda_doc==''){$moneda_doc = "S";}
        $tbl_headers = '<thead>';
        $tbl_headers .= '<th>Código</th>';
        $tbl_headers .= '<th>Alm.</th>';
        $tbl_headers .= '<th>Material</th>';
        $tbl_headers .= '<th>Producto</th>';
        $tbl_headers .= '<th>Unidad</th>';
        $tbl_headers .= '<th>Actual</th>';
        $tbl_headers .= '<th>Comprom</th>';
        $tbl_headers .= '<th>Tránsito</th>';
        $tbl_headers .= '<th>Disponible</th>';
        if($checkedprecio){ 
            $tbl_headers .= '<th>ULT. PREC. '.($moneda_doc=='S')?'S/.':'$'.'</th>';
            $tbl_headers .= '<th>PREC. PROM. '.($moneda_doc=='S')?'S/.':'$'.'</th>';
            $tbl_headers .= '<th>TOT. ULT. PREC. '.($moneda_doc=='S')?'S/.':'$'.'</th>';
            $tbl_headers .= '<th>PREC. PROM. '.($moneda_doc=='S')?'S/.':'$'.'</th>';
        }         
        $fecha        = date("Y-m-d",time());
        $hora_actual  = date("H:i:s",time()-3600);  
        $fila         = "";
        /*Obtengo listado de productos*/
        $filter           = new stdClass();
        $filter_not       = new stdClass();
        if($tipoalmacen!='000') $filter->codalmacen = $tipoalmacen;
        if($tipomaterial!='00') $filter->codtipomaterial = $tipomaterial;
        if($linea!='0000')      $filter->codlinea = $linea;
        $filter_not->codalmacen = array('000','EQU');
        //$filter_not->p_activo   ='A';
        $filter->estado ='2' ;       
        $productos      = $this->producto_model->listar($filter,$filter_not,array('P_descri'));
        $familias       = $this->familia_model->listar(new stdclass());
        $almacenes      = $this->almacen_model->seleccionar(new stdClass(),"::Todos::","000");
        $registros      = count($productos);
        $tipocambio2    = $this->tc_model->obtener(date_sql($fecha));
        //$tcambio      = $tipocambio2->Valor_3."<br>";  
        $fila      = "";
        $arr_export_detalle = array();
        if($tipoexport==""){
            $negativos = 0;
            $negativos_stock  = 0;
            $tmaterial_ant    = 0;
            $total_precioprod = 0;
            $total_preprom    = 0;
            $zz = 0;
            foreach($productos as $indice => $value){
                $arr_data = array();
                $tcambio      = $value->t_cambio;
                $codigo       = trim($value->codpro);
                $descripcion  = $value->despro;
                $cantidad     = $value->stk_actual;
                $minimo       = $value->stk_min;
                $maximo       = $value->stk_max;
                $comprometido = $value->stk_comp;
                $transito     = $value->stk_trans;
                $codalm       = $value->codalm;
                $precio       = $value->precio;
                $preprom      = $value->precprom;
                $moneda       = $value->mo;
                $material     = $value->tipo;
                $unidad       = $value->unimed;
                $peso         = $value->peso;
                /*  calcular familia  */
                $codfamilia   = $this->entidad=="01"?substr($codigo,0,4):substr($codigo,0,5);
                $familia      = "";
                foreach($familias as $ind => $val){
                    if($codfamilia==$val->cod_argumento){
                        $familia = $val->des_larga;
                        break;
                    }else{$familia=  $codfamilia;   }
                }
                /*Quitar cuando valide su data logistica*/
                $precioprod   = "oo";
                $tcambio      = $tcambio=='0'?1:$tcambio;
                /**********************/
                if($moneda_doc=='S'){
                    $precioprod   = $moneda=='D'?($tcambio*$precio):$precio;
                    $preprom      = $preprom;
                }
                elseif($moneda_doc=='D'){
                    $precioprod   = $moneda=='S'?($precio/$tcambio):$precio;
                    $preprom      = $preprom/$tcambio;
                }
                $disponible   = $cantidad-$comprometido+$transito;
                if($cantidad<0) $negativos_stock++;
                if($comprometido<0 || $transito<0) $negativos++;
                if(!$checked){
                    if($cantidad>0){
                        $fila        .= "<tr ".(($comprometido<0 || $transito<0)?"bgcolor='#FFD700'":'')." ".($cantidad<0?"bgcolor='#FF0000'":'')." id='".$codigo."'>";
                        $fila        .= "<td align='center'><a href='#' onclick='ver_kardex(this);'>".$codigo."</a></td>";
                        $arr_data[] =$codigo;
                        $fila        .= "<td style='text-align:center'>".$codalm."</td>";
                        $arr_data[] =$codalm;
                        $fila        .= "<td align='center'>".$material."</td>";
                        $arr_data[] = $material;
                        $fila        .= "<td align='center'>".utf8_encode($familia)."</td>";
                        $arr_data[] =utf8_encode($familia);
                        $fila        .= "<td align='left'>".utf8_encode($descripcion)."</td>";
                        $arr_data[] =utf8_encode($descripcion);
                        $fila        .= "<td align='right'>".number_format($peso,2)."</td>";
                        $arr_data[] =number_format($peso,2);
                        $fila        .= "<td align='center'>".$unidad."</td>";
                        $arr_data[] =$unidad;
                        $fila        .= "<td align='right'>".$cantidad."</td>";
                        $arr_data[] =$cantidad;
                        $fila        .= "<td align='right'><a href='#' onclick='ver_stockcomprometido(this);'>".$comprometido."</a></td>";
                        $arr_data[] =$comprometido;
                        $fila        .= "<td align='right'><a href='#' onclick='ver_stocktransito(this);'>".$transito."</a></td>";
                        $arr_data[] =$transito;
                        $fila        .= "<td align='right'>".$disponible."</td>";
                        $arr_data[] =$disponible;
                        if($checkedprecio){
                            $fila        .= "<td align='right'>".number_format($precioprod,6)."</td>";
                            $fila        .= "<td align='right'>".number_format($preprom,6)."</td>";
                            $fila        .= "<td align='right'>".number_format($precioprod*$cantidad,6)."</td>";
                            $fila        .= "<td align='right'>".number_format($preprom*$cantidad,6)."</td>";                                                      
                        }
                        $fila        .= "</tr>"; 
                        array_push($arr_export_detalle,$arr_data);
                    }
                }
                else{
                    $fila        .= "<tr ".(($comprometido<0 || $transito<0)?"bgcolor='#FFD700'":'')." ".($cantidad<0?"bgcolor='#FF0000'":'')." id='".$codigo."'>";
                    $fila        .= "<td style='text-align:center'><a href='#' onclick='ver_kardex(this);'>".$codigo."</a></td>";
                     $arr_data[]  =$codigo;
                    $fila        .= "<td style='text-align:center'>".$codalm."</td>";
                     $arr_data[]  =$codalm;
                    $fila        .= "<td style='text-align:center'>".$material."</td>";
                     $arr_data[]  =$material;
                    $fila        .= "<td style='text-align:center'>".utf8_encode($familia)."</td>";
                     $arr_data[]  =utf8_encode($familia);
                    $fila        .= "<td style='text-align:left'>".utf8_encode($descripcion)."</td>";
                     $arr_data[]  =utf8_encode($descripcion);
                    $fila        .= "<td align='right'>".number_format($peso,2)."</td>";
                     $arr_data[]  =number_format($peso,2);
                    $fila        .= "<td style='text-align:center'>".$unidad."</td>";
                     $arr_data[]  =$unidad;
                    $fila        .= "<td style='text-align:center'>".$cantidad."</td>";
                     $arr_data[]  =$cantidad;
                    $fila        .= "<td style='text-align:center'><a href='#' onclick='ver_stockcomprometido(this);'>".$comprometido."</a></td>";
                     $arr_data[]  =$comprometido;
                    $fila        .= "<td style='text-align:center'><a href='#' onclick='ver_stocktransito(this);'>".$transito."</a></td>";
                     $arr_data[]  =$transito;
                    $fila        .= "<td style='text-align:center'>".$disponible."</td>";
                     $arr_data[]  =$disponible;
                    if($checkedprecio){
                        IF ($this->codot!='0001934'){$fila .= "<td align='right'>".number_format($precioprod,6)."</td>";} else{echo '';}
                        IF ($this->codot!='0001934'){$fila        .= "<td colspan='1' align='right'>".number_format($preprom,6)."</td>";} else{$fila .= "<td colspan='2' align='right'>".number_format($preprom,6)."</td>";}
                        IF ($this->codot!='0001934'){$fila .="<td align='right'>".number_format($precioprod*$cantidad,6)."</td>";} else{echo '';}
                        IF ($this->codot!='0001934'){$fila        .= "<td colspan='1' align='right'>".number_format($preprom*$cantidad,6)."</td>";} else{$fila .= "<td colspan='2' align='right'>".number_format($preprom*$cantidad,6)."</td>";}
                        }
                    $fila        .= "</tr>";
                    array_push($arr_export_detalle,$arr_data);
                }
                if($this->codot!='0001934'){$total_precioprod = $total_precioprod + ($precioprod*$cantidad);} else{ ECHO '';}
                $total_preprom    = $total_preprom + ($preprom*$cantidad);   
                $zz++;
            }          
            $tbl_headers .= '</thead>';
            $var_export = array('rows' => $arr_export_detalle);
            $this->session->set_userdata('data_stock_productos', $var_export);
        }

        /*Carga de combos*/    
     //se carga antes   $almacenes        = $this->almacen_model->seleccionar(new stdClass(),"::Todos::","000");
        $filter           = new stdClass();
        $tipomateriales   = $this->tipmaterial_conta_model->seleccionar($filter,"::Todos::","00");
        $filter           = new stdClass();
        $filter->order_by = array("des_larga"=>"asc");
        $lineas           = $this->familia_model->seleccionar($filter,"","0000");
        $cboTipoalmacen   = form_dropdown('tipoalmacen',$almacenes,$tipoalmacen,"id='tipoalmacen' class='comboMedio' onchange=\"$('#tipoexport').val('');\" ");   
        $cboTipoamaterial = form_dropdown('tipomaterial',$tipomateriales,$tipomaterial,"id='tipomaterial' class='comboMedio' onchange=\"$('#tipoexport').val('');\" ");   
        $cboFamilia       = form_dropdown('familia',$lineas,$linea,"id='familia' class='comboMedio' onchange=\"$('#tipoexport').val('');\" ");
        $chknegativo      = form_checkbox('chknegativo',1, $checked,"onclick=\"this.value=(this.checked)?'1':'0';\"");
        $chkprecio        = form_checkbox('chkprecio',1, $checkedprecio,"onclick=\"$('#divMoneda').attr('style',(this.value=(this.checked)?'visibility:block;float:left;width:60%;':'visibility:hidden;float:left;width:60%;'));this.value=(this.checked)?'1':'0';\"");
        $cboMoneda        = form_dropdown('moneda',array(""=>"::Seleccione:::","S"=>"SOLES","D"=>"DOLARES"),$moneda_doc," size='1' id='moneda' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#tipoexport').val('');\" ");               
        $data['cboTipoalmacen']   = $cboTipoalmacen;
        $data['cboTipoamaterial'] = $cboTipoamaterial;
        $data['cboFamilia']       = $cboFamilia;
        $data['cboMoneda']        = $checkedprecio?$cboMoneda:'&nbsp;';
        $data['fila']             = $fila;
        $data['registros']        = $registros;
        $data['hora_actual']      = $hora_actual;
        $data['negativos']        = $negativos;
        $data['negativos_stock']  = $negativos_stock;
        $data['chknegativo']      = $chknegativo;
        $data['chkprecio']        = $chkprecio;
        $data['checkedprecio']    = $checkedprecio;
        $data['moneda_doc']       = $moneda_doc;
        $data['tbl_headers']       = $tbl_headers;
        $data['arr_data_products'] = json_encode($productos);   
        $data['total_precioprod'] = $total_precioprod;
        $data['total_preprom']    = $total_preprom;
        $data['oculto']           = form_hidden(array('serie'=>'','numero'=>'','cadenaot'=>'','codot'=>'','tipoexport'=>'','codpro'=>''));
        $this->load->view(almacen."stock_productos.php",$data);
    }
    
    public function stock_productos_cierre(){
        $tipoalmacen  = $this->input->get_post('tipoalmacen');
        $tipomaterial = $this->input->get_post('tipomaterial');
        $linea        = $this->input->get_post('familia');
        $tipoexport   = $this->input->get_post('tipoexport');
        $chknegativo  = $this->input->get_post('chknegativo');
        $chkprecio    = $this->input->get_post('chkprecio');
        $moneda_doc   = $this->input->get_post('moneda');
        $fecha_ini    = $this->input->get_post('fecha_ini');
        $fecha_fin    = $this->input->get_post('fecha_fin'); 
        $checked      = (($chknegativo=='1' || !isset($_REQUEST['tipoalmacen']))?true:false);
        $checkedprecio   = (($chkprecio=='1')?true:false);
        $fecha        = date("Y-m-d",time());
        $hora_actual  = date("H:i:s",time()-3600);
        $fila         = "";
        if($fecha_ini=="")    $fecha_ini    = '07/11/2013';
        if($fecha_fin=="")    $fecha_fin    = date("d/m/Y",time());
        $tipocambio2  = $this->tc_model->obtener(date_sql($fecha_fin));
        /*Matriz de productos*/
        $prod      = $this->producto_model->listar(new stdClass(),new stdClass());
        foreach($prod as $indice => $value){
            $codpro = $value->codpro;
            $arrproducto[$codpro] = $value;
        }
        /*Ultimo cierre*/
        $filter           = new stdClass();
        $filter_not       = new stdClass();
        $filter->fecha    = $fecha_ini;
        $ultimo           = $this->cierre_model->ultimo($filter,$filter_not);
        $fecha_ant        = ($this->entidad=='02')?$ultimo->fecha:(date_sql($ultimo->fecha));
        $nro_cierre       = $ultimo->nro;
        /*Listado de productos*/
        $filter2          = new stdClass();
        $filter2_not      = new stdClass();
        $filter2->fecha   = $fecha_ini;
        $filter2->numero  = $nro_cierre;
        $productos        = $this->cierre_model->listar_detalle($filter2,$filter2_not);
        $registros        = count($productos);
        if($tipoexport==""){
            foreach($productos as $indice => $value){
                $codigo       = $value->codpro;
                $descripcion  = "";
                $anterior     = $value->stk_actual;
                $ingresos     = $value->stk_min;
                $salidas      = $value->stk_max;
                $actual       = $value->stk_comp;
                $transito     = $value->stk_trans;
                $codalm       = $value->codalm;
                $precio       = $value->precio;
                $preprom      = $value->precprom;
                $moneda       = $value->mo;
            //    $tcambio      = $value->t_cambio;
                $material     = $value->tipo;
                $unidad       = $value->unimed;
                /*Quitar cuando valide su data logistica*/
                $precioprod   = "oo";
                $tcambio      = $tcambio=='0'?1:$tcambio;
            }
         }

        /*Carga de combos*/
        $almacenes        = $this->almacen_model->seleccionar(new stdClass(),"::Todos::","000");
        $filter           = new stdClass();
        //$filter->cod_argumento = $rra2;
        $tipomateriales   = $this->tipmaterial_conta_model->seleccionar($filter,"::Todos::","00");
        $lineas           = $this->familia_model->seleccionar(new stdClass(),"","0000");
        $cboTipoalmacen   = form_dropdown('tipoalmacen',$almacenes,$tipoalmacen,"id='tipoalmacen' class='comboMedio' onchange=\"$('#tipoexport').val('');$('#frmBusqueda').submit();\" ");
        $cboTipoamaterial = form_dropdown('tipomaterial',$tipomateriales,$tipomaterial,"id='tipomaterial' class='comboMedio' onchange=\"$('#tipoexport').val('');$('#frmBusqueda').submit();\" ");
        $cboFamilia       = form_dropdown('familia',$lineas,$linea,"id='familia' class='comboMedio' onchange=\"$('#tipoexport').val('');$('#frmBusqueda').submit();\" ");
        $chknegativo      = form_checkbox('chknegativo',1, $checked,"onclick=\"this.value=(this.checked)?'1':'0';$('#frmBusqueda').submit();\"");
        $chkprecio        = form_checkbox('chkprecio',1, $checkedprecio,"onclick=\"$('#divMoneda').attr('style',(this.value=(this.checked)?'visibility:block;float:left;width:60%;':'visibility:hidden;float:left;width:60%;'));this.value=(this.checked)?'1':'0';$('#frmBusqueda').submit();\"");
        $cboMoneda        = form_dropdown('moneda',array(""=>"::Seleccione:::","S"=>"SOLES","D"=>"DOLARES"),$moneda_doc," size='1' id='moneda' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#tipoexport').val('');submit();\" ");
        $data['cboTipoalmacen']   = $cboTipoalmacen;
        $data['cboTipoamaterial'] = $cboTipoamaterial;
        $data['cboFamilia']       = $cboFamilia;
        $data['cboMoneda']        = $checkedprecio?$cboMoneda:'&nbsp;';
        $data['fila']             = $fila;
        $data['registros']        = $registros;
        $data['hora_actual']      = $hora_actual;
        $data['negativos']        = $negativos;
        $data['negativos_stock']  = $negativos_stock;
        $data['chknegativo']      = $chknegativo;
        $data['chkprecio']        = $chkprecio;
        $data['checkedprecio']    = $checkedprecio;
        $data['moneda_doc']       = $moneda_doc;
        $data['total_precioprod'] = $total_precioprod;
        $data['total_preprom']    = $total_preprom;
        $data['fecha_fin']        = "";
        $data['fecha_ini']        = "";
        $data['oculto']           = form_hidden(array('serie'=>'','numero'=>'','cadenaot'=>'','codot'=>'','tipoexport'=>'','codpro'=>''));
        $this->load->view(almacen."stock_productos_cierre.php",$data);
    }

    public function stock_comprometido(){
        $tipoalmacen  = $this->input->get_post('tipoalmacen');
        $tipomaterial = $this->input->get_post('tipomaterial');
        $linea        = $this->input->get_post('familia');
        $tipoexport   = $this->input->get_post('tipoexport');
        $codpro       = $this->input->get_post('codpro');
        $fecha        = date("Y-m-d",time());
        $date = date("Y-m-d", strtotime('-4 month'));
        //$fecha_prueba = date_add($fecha,-60);  
        $fecha_prueba=$date;
        $filter       = new stdClass();
        $filter_not   = new stdClass();
        $j    = 0;
        $k    = 0;
        $fila = "";
        $total_cantidad  = 0;
        $total_cantidads = 0;
        $total_valcant   = 0;
        $total_comprom   = 0;
        $valcantidad2    = 0;
        $u=0;
        /*Nombre del producto*/
        $filter2     = new stdClass();
        $filter2_not = new stdClass();
        $filter2->codproducto = $codpro;
        $productos = $this->producto_model->obtenerg($filter2,$filter2_not);
        if(isset($productos->despro))
        { $nombre_producto = $productos->despro; }
        else    { $nombre_producto=''; }  
     //////
        /*Listado de productos que van a salir del comprometido*/
        /*Total por requisicioes*/
        $filter->tipo        = "R";
        $filter->fechai      = date_sql($fecha_prueba);
        $filter->flgAprobado = 1;        
        $filter->codpro      = $codpro;
        $filter_not->codot   = "0001999";
        $order_by            = "Gnumguia";
        $requisiciones = $this->requis_model->listar_detalle($filter,$filter_not,$order_by);
        $valcant2      = 0;
   //// Para excel   /////
        $arr_data    = array();
        $var_prd_n = 0;
        $var_row = 9;
    //////////////////*********/////  
        $arr_export_detalle = array();
        if(count($requisiciones)>0){
            
          foreach($requisiciones as $indice => $value){
             
            $serie     = $value->seriedoc;
            $numguia   = $value->nrodoc;
            $codigo    = $value->gcodpro;
            $cantidad  = $value->gcantidad;
            $guser     = $value->user;
            $cantidads = $value->gcantidads;//Este dato no esta verificado
            $departamento = $value->gcoddpto;	
            $tipoot       = $value->tipot;	
            $codot        = $value->codot;
            $nroot        = $value->got;
            $codresot     = $value->codresot;
            $useraprob    = $value->useraprob;
            $fecemi       = $value->fecemi;
            /*Obtenemos los valores del kardex*/
            $valfecha  = "";
            $valserie  = "";
            $valnumero = "";
            $documento = "";
            $codmov    = "";
            $valuser   = "";
            $valot     = "";            
            $filter3      = new stdClass();
            $filter3_not  = new stdClass();
            $filter3->codproducto = $codigo;
            $filter3->serreq      = $serie;
            $filter3->numreq      = $numguia;
            $nsalidas             = $this->nsalida_model->listar_detalle($filter3,$filter3_not);
            $total_cantidad       = $total_cantidad + $cantidad;
            $valcantidad          = 0;    
            $valcant2      = 0;
            $registros            = count($nsalidas);
            
            if(count($nsalidas)>0){  
                
                foreach($nsalidas as $indice2=>$value2){
                     $arr_data = array();
                    $valcantidad  = $value2->cantidad;
                    $valfecha     = $value2->fecha;
                    $numreq       = $value2->numreq;
                    $valserie     = $value2->serie;
                    $valnumero    = $value2->numero;
                    $documento    = $value2->documento;
                    $codmov       = $value2->codmov;
                    $valuser      = $value2->user;
                    $valot        = $value2->ot;
                    $estmov       = $value2->estmov;	
                    $total_valcant   = $total_valcant + $valcantidad;
                    $valcant2     = $valcant2 + $valcantidad;
                    $comprom2     = ($indice2 == $registros-1)?($cantidad - $valcant2):0;
                    $total_comprom = $total_comprom + $comprom2;
                    
               if($tipoexport==""){  
                    $arr_data = array();
                    $fila        .= "<tr>";
                    $fila        .= "<td>".($indice2>0?'&nbsp;':$serie.'-'.$numguia)."</td>";
                    $arr_data[]     = ($indice2>0?'':$serie.'-'.$numguia);
                    $fila        .= "<td>".($indice2>0?'&nbsp;':date_sql($fecemi))."</td>";
                    $arr_data[]     = ($indice2>0?'&nbsp;':date_sql($fecemi));
                    $fila        .= "<td>".($indice2>0?'&nbsp;':$nroot)."</td>";
                    $arr_data[]     = ($indice2>0?'&nbsp;':$nroot);
                    $fila        .= "<td>".($indice2>0?'&nbsp;':$codresot)."</td>";
                    $arr_data[]     = ($indice2>0?'&nbsp;':$codresot);
                    $fila        .= "<td>".($indice2>0?'&nbsp;':$guser)."</td>";
                    $arr_data[]     = ($indice2>0?'&nbsp;':$guser);
                    $fila        .= "<td>".($indice2>0?'&nbsp;':$useraprob)."</td>";
                    $arr_data[]     = ($indice2>0?'&nbsp;':$useraprob);
                    $fila        .= "<td align='right'>".($indice2>0?'&nbsp;':$cantidad)."</td>";
                    $arr_data[]     = ($indice2>0?'&nbsp;':$cantidad);
                    $fila        .= "<td align='right'>".($valcantidad!=''?$valcantidad:0)."</td>";
                    $arr_data[]     = ($valcantidad!=''?$valcantidad:0);
                    $fila        .= "<td align='right'>".$comprom2."</td>";
                    $arr_data[]     = $comprom2+"";
                    $fila        .= "<td>".date_sql($valfecha)."</td>";
                    $arr_data[]     =  date_sql($valfecha);
                    $fila        .= "<td  align='center'><div style='width:110px;height:auto;' id='".trim($valnumero)."'><a href='#' onclick='".(trim($documento)=='G'?'ver_vale_salida(this);':'ver_devolucion(this);')."'>".trim($valserie).'-'.trim($valnumero)."</a></div></td>";
                    $arr_data[]     = trim($valserie).'-'.trim($valnumero);
                    $fila        .= "<td>".$valuser."</td>";
                    $arr_data[]     = $valuser;
                  $fila        .= "</tr>";
                  array_push($arr_export_detalle,$arr_data);
                  
               }//datos para html
               
              /* elseif ($tipoexport=='excel.det'){
                   
                   $num_req=            $indice2>0?'':$serie.'-'.$numguia;
                   $fec_emi=            $indice2>0?'':date_sql($fecemi);
                   $nro_ot=             $indice2>0?'':$nroot;
                   $cod_responsable=    $indice2>0?'':$codresot;
                   $aprob=              $indice2>0?'':$guser;
                   $aprob_user =        $indice2>0?'':$useraprob;
                   $cant=               $indice2>0?'':$cantidad;
                   $vs_cant=            $valcantidad!=''?$valcantidad:0;
                   $cant_compro=        $comprom2;
                   $vs_fecha=           date_sql($valfecha);
                   $vs_numero=          trim($valnumero);
                   $vs_user=            $valuser;
                   
                   $arr_data[$var_prd_n] = array(
                    $num_req,
                    $fec_emi,
                    $nro_ot,
                    $cod_responsable,
                    $aprob,
                    $aprob_user,
                   $cant,
                   $vs_cant,
                   $cant_compro,
                   $vs_fecha,
                   $vs_numero,
                   $vs_user                    
                );
               $var_prd_n++; 
               $var_row++; 
              
               } *///datos para exel (quitar)
                  
                }//recorrido de las NEA
                
            } // si salidas mayores que 0
            
            else{
                $total_comprom = $total_comprom + $cantidad;
                if($tipoexport==""){
                $arr_data = array();
                $fila        .= "<tr>";
                $fila        .= "<td>".$serie.'-'.$numguia."</td>";
                $arr_data[]     = $serie.'-'.$numguia;
                $fila        .= "<td>".date_sql($fecemi)."</td>";
                $arr_data[]     = date_sql($fecemi);
                $fila        .= "<td>".$nroot."</td>";
                $arr_data[]     = $nroot;
                $fila        .= "<td>".$codresot."</td>";
                $arr_data[]     = $codresot;
                $fila        .= "<td>".$guser."</td>";
                $arr_data[]     = $guser;
                $fila        .= "<td>".$useraprob."</td>";
                $arr_data[]     = $useraprob;
                $fila        .= "<td align='right'>".$cantidad."</td>";
                $arr_data[]     =  $cantidad;
                $fila        .= "<td align='right'></td>";
                $arr_data[]     ='';
                $fila        .= "<td align='right'>".$cantidad."</td>";
                $arr_data[]    = $cantidad+"";
                $fila        .= "<td></td>";
                $arr_data[]     ='';
                $fila        .= "<td></td>";
                $arr_data[]     ='';
                $fila        .= "<td></td>";		
                $arr_data[]     ='';
                $fila        .= "</tr>";
                array_push($arr_export_detalle,$arr_data);
                 } // para html
                 
            /*  elseif($tipoexport=="excel.det"){
                    $num_req=           $serie.'-'.$numguia;
                   $fec_emi=            date_sql($fecemi);
                   $nro_ot=             $nroot;
                   $cod_responsable=    $codresot;
                   $aprob=              $guser;
                   $aprob_user =        $useraprob;
                   $cant=               $cantidad;
                   $vs_cant=            '';
                   $cant_compro=        $cantidad;
                   $vs_fecha=           '';
                   $vs_numero=          '';
                   $vs_user=            '';
                  // echo "reqi :".$num_req."<br>".$fec_emi;
                   $arr_data[$var_prd_n] = array(
                    $num_req,
                    $fec_emi,
                    $nro_ot,
                    $cod_responsable,
                    $aprob,
                    $aprob_user,
                   $cant,
                   $vs_cant,
                   $cant_compro,
                   $vs_fecha,
                   $vs_numero,
                   $vs_user                    
                );
               $var_prd_n++; 
               $var_row++; 
             // echo "<br>Nro de reqgistro : ".$var_prd_n;   
              }   *///para excel  (quitar)
                 
            }// si salidas menores que 0
            
          }  
        }
        
        $var_export = array('rows' => $arr_export_detalle);
        $this->session->set_userdata('data_stock_comprometido', $var_export);
         
        $data['fila']            = $fila;
        $data['codigo']          = $codpro;
        $data['producto']        = $nombre_producto;
        $data['total_cantidad']  = $total_cantidad;
        $data['total_cantidads'] = $total_cantidads;
        $data['total_valcant']   = $total_valcant;
        $data['total_comprom']   = $total_comprom;
        $this->load->view(almacen."stock_comprometido.php",$data);  
        
       
        
     /*   elseif ($tipoexport=='excel.det'){
            
             $arr_columns[0]['STRING']='NUM.REQ';
            $arr_columns[1]['STRING']='FECHA';
            $arr_columns[2]['STRING']='NROOT';		
            $arr_columns[3]['STRING']='CODRES';
            $arr_columns[4]['STRING']='APROBADO';
            $arr_columns[5]['STRING']='USERAPROB';
            $arr_columns[6]['STRING']='CANTIDAD';
            $arr_columns[7]['STRING']='CANTIDAD V.S.</td>';
            $arr_columns[8]['STRING']='STOCK COMPROM.';
            $arr_columns[9]['STRING']='FECHA V.S.';
            $arr_columns[10]['STRING']='NUMERO V.S.';
            $arr_columns[11]['STRING']='VALUSER';
                           
                        
            $arr_grouping_header = array();
            $arr_grouping_header['A5:L5'] = 'Detalle de Stock Comprometido';
            $this->reports_model->rpt_general('Stock_Compro_Cod_'.$codpro,'"'.$codpro.' - '.$nombre_producto.'"',$arr_columns,$arr_data ,$arr_grouping_header);
        }  */  //para excel (quitar)
        
        
        
        
        
    }/// fin de stock comprometido
    
    public function stock_transito(){
        $tipoalmacen  = $this->input->get_post('tipoalmacen');
        $tipomaterial = $this->input->get_post('tipomaterial');
        $linea        = $this->input->get_post('familia');
        $tipoexport   = $this->input->get_post('tipoexport');
        $codpro       = $this->input->get_post('codpro');
        $fecha        = date("Y-m-d",time());
        $fecha_prueba = date_add($fecha,-60);   
        //$fecha_prueba = date("Y-m-d", strtotime('-2 month'));
        $fila         = "";
        //// Para excel   /////
        $arr_data    = array();
        $var_prd_n = 0;
        $var_row = 9;
    //////////*********/////   
        /*Nombre del producto*/
        $filter2     = new stdClass();
        $filter2_not = new stdClass();
        $filter2->codproducto = $codpro;
        $productos = $this->producto_model->obtenerg($filter2,$filter2_not);
        if(isset($productos->despro))
        {
        $nombre_producto = $productos->despro;
        }
        else
        {
            $nombre_producto='';
        }   
        /*Ordenes de compra para ese producto*/
        $totaloc      = 0;
        $totalnea     = 0;
        $totaltrans   = 0;    
        $filter     = new stdClass();
        $filter_not = new stdClass();
        $filter->codproducto = $codpro;
        $filter->flgAprobado = 1;
        $filter->fechai      = date_sql($fecha_prueba);
        $ordenc = $this->ocompra_model->listar($filter,$filter_not);
        $arr_export_detalle = array();
        if(count($ordenc)>0){
            foreach($ordenc as $indice => $value){
                $serieoc      = $value->seriedoc;
                $numoc        = $value->nrodoc;
                $cantidadoc   = $value->cantidad;
                $fecemi       = $value->fecrep;
                $totaloc      = $totaloc + $cantidadoc;  
                /*Obtengo las NEAS*/
                $filter2      = new stdClass();
                $filter2_not  = new stdClass();
                $filter2->codproducto = $codpro; 
                $filter2->numoc       = $numoc;  
                $filter2->group_by    = array('k.Codigo','k.Numoc','k.Serie','k.Numero','k.Fecha','k.Moneda','k.Tcambio','k.Tip_movmto','k.Documento','k.numcom');                
                $ningreso             = $this->ningreso_model->listar_ingresos($filter2,$filter2_not);
                $zz           = 0;
                $cantidadnea  = 0;
                $valornea      = 0;
                $valornea_ant  = 0;
                $numnea_ant    = 0;
                $serienea_ant  = 0;
                $cantidadtrans = 0; 
                $tcantidadnea  = 0;
                $registros     = count($ningreso);
                if(count($ningreso)>0){
                    foreach($ningreso as $indice2 => $value2){
                        $cantidadnea  = $value2->cantidad;
                        $serienea     = $value2->serie;
                        $numeronea    = $value2->numero;
                        $numerocom    = $value2->numcom;
                        $documento    = $value2->documento;
                        $tip_movmto   = $value2->tip_movmto;
                        $fechanea     = $value2->fecha;
                        $tcantidadnea = $tcantidadnea + $cantidadnea;  
                        $totalnea     = $totalnea + $cantidadnea;
                        if($indice2 == $registros - 1){
                            $cantidadtrans = $cantidadoc - $tcantidadnea;
                            $tcantidadnea = 0;
                        }
                     if($tipoexport==""){
                        $arr_data = array();
                        $fila        .= "<tr>";
                        $fila        .= "<td>".($indice2>0?'&nbsp;':date_sql($fecemi))."</td>";
                        $arr_data[]   =($indice2>0?'&nbsp;':date_sql($fecemi));
                        $fila        .= "<td align='center'><div id='".trim($numoc)."' id2='".trim($serieoc)."' id3='OC' style='width:100px;height:auto;'><a href='#' onclick='ver_ocos(this);'>".($indice2>0?'&nbsp;':trim($serieoc).'-'.trim($numoc))."</a></div></td>";
                        $arr_data[]   =($indice2>0?'&nbsp;':trim($serieoc).'-'.trim($numoc));
                        $fila        .= "<td>".($indice2>0?'&nbsp;':$cantidadoc)."</td>";
                        $arr_data[]   =($indice2>0?'&nbsp;':$cantidadoc);
                        $fila        .= "<td>".($numeronea==""?"&nbsp;":date_sql($fechanea))."</td>";
                        $arr_data[]   =($numeronea==""?"&nbsp;":date_sql($fechanea));
                        $fila        .= "<td align='center'><div style='width:110px;height:auto;' id='".$numeronea."'><a href='#' onclick='ver_nota_ingreso(this);'>".($numerocom==""?"&nbsp;":trim($serienea).'-'.trim($numerocom))."</a></div></td>";
                        $arr_data[]   =($numerocom==""?"&nbsp;":trim($serienea).'-'.trim($numerocom));
                        $fila        .= "<td>".$cantidadnea."</td>";
                        $arr_data[]   =$cantidadnea;
                        $fila        .= "<td>".$cantidadtrans."</td>";
                        $arr_data[]   =$cantidadtrans;
                        $fila        .= "</tr>";
                         array_push($arr_export_detalle,$arr_data);
                        }  //// mostrar en HTML
                        
                        //(QUITAR)
                       /* elseif($tipoexport=="excel.det"){
                            $orden_fecha    =$indice2>0?'':date_sql($fecemi);
                            $orden_num      =$indice2>0?'':trim($serieoc).'-'.trim($numoc);
                            $orden_cant     =$indice2>0?'':$cantidadoc;
                            $nea_fecha     =$numeronea==""?"":date_sql($fechanea);
                            $nea_numero     =$numerocom==""?"":trim($serienea).'-'.trim($numerocom);
                            $nea_cant       =$cantidadnea;
                            $trans_cant     =$cantidadtrans;
                            
                             $arr_data[$var_prd_n] = array(
                                $orden_fecha,
                                $orden_num,
                                $orden_cant,
                                $nea_fecha,
                                $nea_numero,
                                $nea_cant,
                                $trans_cant,      
                                 );
                                 $var_prd_n++; 
                                 $var_row++;  
                             } */ // mostrar en excel
                    }
                }
                else{
                    $cantidadtrans = $cantidadoc;
                     if($tipoexport==""){  
                          $arr_data = array();
                    $fila .= "<tr>";
                    $fila .= "<td>".date_sql($fecemi)."</td>";
                    $arr_data[]   =date_sql($fecemi);
                    $fila .= "<td align='center'><div id='".trim($numoc)."' id2='".trim($serieoc)."' id3='OC' style='width:100px;height:auto;'><a href='#' onclick='ver_ocos(this);'>".trim($serieoc).'-'.trim($numoc)."</a></div></td>";
                    $arr_data[]   =trim($serieoc).'-'.trim($numoc);
                    $fila .= "<td>".$cantidadoc."</td>";
                    $arr_data[]   =$cantidadoc;
                    $fila .= "<td colspan='3'>&nbsp;</td>";
                    $arr_data[]   ="";
                    $fila .= "<td>".$cantidadtrans."</td>";
                    $arr_data[]   =$cantidadtrans;
                     $fila .= "</tr>";     } /// mostrar HTML
                   array_push($arr_export_detalle,$arr_data);
                     
                 // (QUITAR)    
               /*     elseif($tipoexport=="excel.det"){
                                                        
                             $arr_data[$var_prd_n] = array(
                                date_sql($fecemi),
                                trim($serieoc).'-'.trim($numoc),
                                $cantidadoc,
                                '',
                                '',
                                '',
                                $cantidadtrans,      
                                 );
                                 $var_prd_n++; 
                                 $var_row++;  
                             }  */ // mostrar en EXCEL 
                     
                }
                
                $totaltrans	= $totaltrans + $cantidadtrans;	
                  
        }
        $fila        .= "<tr>";
        $fila        .= "<td colspan='2' align='lert'></td>";
        $fila        .= "<td>".$totaloc."</td>";
        $fila        .= "<td colspan='2' align='lert'></td>";		
        $fila        .= "<td>".$totalnea."</td>";
        $fila        .= "<td>".$totaltrans."</td>";
        $fila        .= "</tr>";  
        
        }
           
        if($tipoexport==""){
        
        $data['fila']            = $fila;
        $data['codigo']          = $codpro;
        $data['producto']        = $nombre_producto;
        $this->load->view(almacen."stock_transito.php",$data); } //fin para HTML
        $var_export = array('rows' => $arr_export_detalle);
        $this->session->set_userdata('data_stock_transito', $var_export);
        // ( Quitar )
     /*   elseif($tipoexport=="excel.det"){
            $arr_columns[0]['STRING']='FECHA OC';
            $arr_columns[1]['STRING']='NUM OC';
            $arr_columns[2]['STRING']='CANT. OC';		
            $arr_columns[3]['STRING']='FECHA NEA';
            $arr_columns[4]['STRING']='NUM. NEA';
            $arr_columns[5]['STRING']='CANT. NEA';
            $arr_columns[6]['STRING']='STOCK TRANS.';
                         
                        
            $arr_grouping_header = array();
            $arr_grouping_header['A5:G5'] = 'Detalle de Stock Transito';
            $this->reports_model->rpt_general('Stock_Transito_Cod_'.$codpro,'"'.$codpro.' - '.$nombre_producto.'"',$arr_columns,$arr_data ,$arr_grouping_header);
            
        } */  ///
        
    }/// fin de stock entransito
    
    public function stock_productos_ot(){
        $tipoalmacen  = $this->input->get_post('tipoalmacen');
        $tipomaterial = $this->input->get_post('tipomaterial');
        $linea        = $this->input->get_post('familia');
        $tipoexport   = $this->input->get_post('tipoexport');
        $chknegativo  = $this->input->get_post('chknegativo');
        $chkprecio    = $this->input->get_post('chkprecio');
        $moneda_doc   = $this->input->get_post('moneda');
        $checked      = (($chknegativo=='1' || !isset($_REQUEST['tipoalmacen']))?true:false);
        $checkedprecio      = (($chkprecio=='1')?true:false);
        if($moneda_doc==''){$moneda_doc = "S";}
        $tbl_headers = '<thead>';
        $tbl_headers .= '<th>Código</th>';
        $tbl_headers .= '<th>Alm.</th>';
        $tbl_headers .= '<th>Material</th>';
        $tbl_headers .= '<th>Producto</th>';
        $tbl_headers .= '<th>Unidad</th>';
        $tbl_headers .= '<th>Actual</th>';
        $tbl_headers .= '<th>Comprom</th>';
        $tbl_headers .= '<th>Tránsito</th>';
        $tbl_headers .= '<th>Disponible</th>';
        if($checkedprecio){ 
            $tbl_headers .= '<th>ULT. PREC. '.($moneda_doc=='S')?'S/.':'$'.'</th>';
            $tbl_headers .= '<th>PREC. PROM. '.($moneda_doc=='S')?'S/.':'$'.'</th>';
            $tbl_headers .= '<th>TOT. ULT. PREC. '.($moneda_doc=='S')?'S/.':'$'.'</th>';
            $tbl_headers .= '<th>PREC. PROM. '.($moneda_doc=='S')?'S/.':'$'.'</th>';
        }         
        $fecha        = date("Y-m-d",time());
        $hora_actual  = date("H:i:s",time()-3600);  
        $fila         = "";
        /*Obtengo listado de productos*/
        $filter           = new stdClass();
        $filter_not       = new stdClass();
        if($tipoalmacen!='000') $filter->codalmacen = $tipoalmacen;
        if($tipomaterial!='00') $filter->codtipomaterial = $tipomaterial;
        if($linea!='0000')      $filter->codlinea = $linea;
        $filter_not->codalmacen = array('000','EQU');
        $productos              = $this->producto_model->listar($filter,$filter_not,array('P_descri'));
        $registros              = count($productos);
        $tipocambio2  = $this->tc_model->obtener(date_sql($fecha));
        //$tcambio      = $tipocambio2->Valor_3."<br>";  
        if($tipoexport==""){
            $negativos = 0;
            $negativos_stock  = 0;
            $tmaterial_ant    = 0;
            $total_precioprod = 0;
            $total_preprom    = 0;
            foreach($productos as $indice => $value){
                $tcambio      = $value->t_cambio;
                $codigo       = $value->codpro;
                $descripcion  = $value->despro;
                $cantidad     = $value->stk_actual;
                $minimo       = $value->stk_min;
                $maximo       = $value->stk_max;
                $comprometido = $value->stk_comp;
                $transito     = $value->stk_trans;
                $codalm       = $value->codalm;
                $precio       = $value->precio;
                $preprom      = $value->precprom;
                $moneda       = $value->mo;
                $material     = $value->tipo;
                $unidad       = $value->unimed;
                /*Quitar cuando valide su data logistica*/
                $precioprod   = "oo";
                $tcambio      = $tcambio=='0'?1:$tcambio;
                /**********************/
                if($moneda_doc=='S'){
                    $precioprod   = $moneda=='D'?($tcambio*$precio):$precio;
                    $preprom      = $preprom;
                }
                elseif($moneda_doc=='D'){
                    $precioprod   = $moneda=='S'?($precio/$tcambio):$precio;
                    $preprom      = $preprom/$tcambio;
                }
                $disponible   = $cantidad-$comprometido+$transito;
                if($cantidad<0) $negativos_stock++;
                if($comprometido<0 || $transito<0) $negativos++;
                if(!$checked){
                    if($cantidad>0){
                        $fila        .= "<tr ".(($comprometido<0 || $transito<0)?"bgcolor='#FFD700'":'')." ".($cantidad<0?"bgcolor='#FF0000'":'')." id='".$codigo."'>";
                        $fila        .= "<td align='center'><a href='#' onclick='ver_kardex(this);'>".$codigo."</a></td>";
                        $fila        .= "<td style='text-align:center'>".$codalm."</td>";
                        $fila        .= "<td align='center'>".$material."</td>";
                        $fila        .= "<td align='left'>".utf8_encode($descripcion)."</td>";
                        $fila        .= "<td align='center'>".$unidad."</td>";
                        $fila        .= "<td align='right'>".$cantidad."</td>";
                        $fila        .= "<td align='right'><a href='#' onclick='ver_stockcomprometido(this);'>".$comprometido."</a></td>";
                        $fila        .= "<td align='right'><a href='#' onclick='ver_stocktransito(this);'>".$transito."</a></td>";
                        $fila        .= "<td align='right'>".$disponible."</td>";
                        if($checkedprecio){
                            $fila        .= "<td align='right'>".number_format($precioprod,6)."</td>";
                            $fila        .= "<td align='right'>".number_format($preprom,6)."</td>";
                            $fila        .= "<td align='right'>".number_format($precioprod*$cantidad,6)."</td>";
                            $fila        .= "<td align='right'>".number_format($preprom*$cantidad,6)."</td>";                            
                            $fila        .= "</tr>";                            
                        }
                    }
                }
                else{
                    $fila        .= "<tr ".(($comprometido<0 || $transito<0)?"bgcolor='#FFD700'":'')." ".($cantidad<0?"bgcolor='#FF0000'":'')." id='".$codigo."'>";
                    $fila        .= "<td style='text-align:center'><a href='#' onclick='ver_kardex(this);'>".$codigo."</a></td>";
                    $fila        .= "<td style='text-align:center'>".$codalm."</td>";
                    $fila        .= "<td style='text-align:center'>".$material."</td>";
                    $fila        .= "<td style='text-align:left'>".utf8_encode($descripcion)."</td>";
                    $fila        .= "<td style='text-align:center'>".$unidad."</td>";
                    $fila        .= "<td style='text-align:center'>".$cantidad."</td>";
                    $fila        .= "<td style='text-align:center'><a href='#' onclick='ver_stockcomprometido(this);'>".$comprometido."</a></td>";
                    $fila        .= "<td style='text-align:center'><a href='#' onclick='ver_stocktransito(this);'>".$transito."</a></td>";
                    $fila        .= "<td style='text-align:center'>".$disponible."</td>";
                    if($checkedprecio){
                    //die('testsasd');
                        IF ($this->codot!='0001934'){$fila .= "<td align='right'>".number_format($precioprod,6)."</td>";} else{echo '';}
                        IF ($this->codot!='0001934'){$fila        .= "<td colspan='1' align='right'>".number_format($preprom,6)."</td>";} else{$fila        .= "<td colspan='2' align='right'>".number_format($preprom,6)."</td>";}
                        IF ($this->codot!='0001934'){$fila .="<td align='right'>".number_format($precioprod*$cantidad,6)."</td>";} else{echo '';}
                        IF ($this->codot!='0001934'){$fila        .= "<td colspan='1' align='right'>".number_format($preprom*$cantidad,6)."</td>";} else{$fila        .= "<td colspan='2' align='right'>".number_format($preprom*$cantidad,6)."</td>";}
                        }
                    $fila        .= "</tr>";
                }
                if($this->codot!='0001934'){$total_precioprod = $total_precioprod + ($precioprod*$cantidad);} else{ ECHO '';}
                $total_preprom    = $total_preprom + ($preprom*$cantidad);                  
            }          
            $tbl_headers .= '</thead>';
        }
        elseif($tipoexport=='excel'){
            $arr_columns[0]['STRING'] = 'Código';
            $arr_columns[1]['STRING'] = 'Almacén';
            $arr_columns[2]['STRING'] = 'Producto';
            $arr_columns[3]['STRING'] = 'UND';
            $arr_columns[4]['NUMERIC'] = 'Stock Mínimo';
            $arr_columns[5]['NUMERIC'] = 'Stock Actual';
            $arr_columns[6]['NUMERIC'] = 'Stock Comprometido';
            $arr_columns[7]['NUMERIC'] = 'Stock Tránsito';
            $arr_columns[8]['FORMULA'] = 'Stock Disponible';
            $arr_data    = array();
            $var_prd_n = 0;
            $var_row = 7;
            foreach($productos as $prd_key => $prd_value){
                $arr_data[$var_prd_n] = array(
                    $prd_value->codpro,
                    $prd_value->codalm,
                    utf8_encode(trim($prd_value->despro)),
                    $prd_value->unimed,
                    $prd_value->stk_min,
                    $prd_value->stk_actual,
                    $prd_value->stk_comp,
                    $prd_value->stk_trans,
                    '=F'.$var_row.'-G'.$var_row.'+H'.$var_row.''
                );
               $var_prd_n++; 
               $var_row++;
            }
            $arr_grouping_header = array();
            $arr_grouping_header['A5:D5'] = 'Descripción';
            $this->reports_model->rpt_general('rpt_stock_products','sTOCK de Productos',$arr_columns,$arr_data ,$arr_grouping_header);
        }   
        /*Carga de combos*/    
        $almacenes        = $this->almacen_model->seleccionar(new stdClass(),"::Todos::","000");
        $filter           = new stdClass();
        //$filter->cod_argumento = $rra2;
        $tipomateriales   = $this->tipmaterial_conta_model->seleccionar($filter,"::Todos::","00");
        $lineas           = $this->familia_model->seleccionar(new stdClass(),"","0000");
        $cboTipoalmacen   = form_dropdown('tipoalmacen',$almacenes,$tipoalmacen,"id='tipoalmacen' class='comboMedio' onchange=\"$('#tipoexport').val('');\" ");   
        $cboTipoamaterial = form_dropdown('tipomaterial',$tipomateriales,$tipomaterial,"id='tipomaterial' class='comboMedio' onchange=\"$('#tipoexport').val('');\" ");   
        $cboFamilia       = form_dropdown('familia',$lineas,$linea,"id='familia' class='comboMedio' onchange=\"$('#tipoexport').val('');\" ");
        $chknegativo      = form_checkbox('chknegativo',1, $checked,"onclick=\"this.value=(this.checked)?'1':'0';\"");
        $chkprecio        = form_checkbox('chkprecio',1, $checkedprecio,"onclick=\"$('#divMoneda').attr('style',(this.value=(this.checked)?'visibility:block;float:left;width:60%;':'visibility:hidden;float:left;width:60%;'));this.value=(this.checked)?'1':'0';\"");
        $cboMoneda        = form_dropdown('moneda',array(""=>"::Seleccione:::","S"=>"SOLES","D"=>"DOLARES"),$moneda_doc," size='1' id='moneda' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#tipoexport').val('');\" ");               
        $data['cboTipoalmacen']   = $cboTipoalmacen;
        $data['cboTipoamaterial'] = $cboTipoamaterial;
        $data['cboFamilia']       = $cboFamilia;
        $data['cboMoneda']        = $checkedprecio?$cboMoneda:'&nbsp;';
        $data['fila']             = $fila;
        $data['registros']        = $registros;
        $data['hora_actual']      = $hora_actual;
        $data['negativos']        = $negativos;
        $data['negativos_stock']  = $negativos_stock;
        $data['chknegativo']      = $chknegativo;
        $data['chkprecio']        = $chkprecio;
        $data['checkedprecio']    = $checkedprecio;
        $data['moneda_doc']       = $moneda_doc;
        $data['tbl_headers']       = $tbl_headers;
        $data['arr_data_products'] = json_encode($productos);   
        $data['total_precioprod'] = $total_precioprod;
        $data['total_preprom']    = $total_preprom;
        $data['oculto']           = form_hidden(array('codot'=>'','tipoexport'=>'','codpro'=>'','tipot'=>'','opcion'=>'','ot'=>''));
        $data['fecha']  = "";
        $this->load->view(almacen."stock_productos_ot.php",$data);
    }
    
    
    public function kardex(){

       /* $tipoalmacen  = $this->input->get_post('tipoalmacen');
        
        $linea        = $this->input->get_post('familia');
       
        $codpro       = $this->input->get_post('codpro');*/
        
      
        $tipoexport      = $this->input->get_post('tipoexport');
        $fecha_ini       = $this->input->get_post('fecha_ini');
        $fecha_fin       = $this->input->get_post('fecha_fin');
        $tipomaterial    = $this->input->get_post('tipomaterial');
        $tipomovimiento  = $this->input->get_post('tipomovimiento'); 
        $moneda          = $this->input->get_post('moneda'); 
        $codtip          = $this->input->get_post('codtip');
        $codot           = $this->input->get_post('codot');
        $ots              = $this->input->get_post('ot');
        $tipot      = $this->input->get_post('tipot');
        $codproducto     = $this->input->get_post('codpro');
     //  print_r($ot.'-');
        
        $total_cantidad=0;
        $total_cantoc=0;
        $total_precio=0;
        $total=0;
     /*   $mes= date ("m");
        $ano= date ("y");
        $fechax1=date_format(date_create($fecha_ini), 'U');
        $fechax2=date_format(date_create($fecha_fin), 'B');
       
        
        
        $mes1=date("m",$fechax1);
        $mes2=date("m",$fechax2);
        
        $ano1=date("Y",$fechax1);
        $ano2=date("Y",$fechax2);
        
        if($mes1==$mes2 and $ano1==$ano2)
        {*/
         if($moneda=="")       $moneda       = 'S';
         $productos      = $this->producto_model->listar(new stdClass(),new stdClass(),array("P_descri"));
        $arrproducto2   = array("000000000000"=>"::: TODOS :::");
        foreach($productos as $indice => $value){
            $codpro = trim($value->codpro);
            $arrproducto[$codpro]  = $value;
            $arrproducto2[$codpro] = $value->codpro." - ".$value->despro;
        } 
        
        
        $filter           = new stdClass();
        $filter2           = new stdClass();
        //$filter->cod_argumento = $rra2;
        $tipomateriales   = $this->tipmaterial_conta_model->seleccionar($filter,"::Todos::","00");
      
        
        $cbotipomaterial = form_dropdown('tipomaterial',$tipomateriales,$tipomaterial,"id='tipomaterial' class='comboMedio' onchange=\"$('#tipoexport').val('');$('#frmBusqueda').submit();\" ");   
        
        $tipomovimientos  = $this->tipmaterial_conta_model->seleccionar_movi($filter2,"::Todos::","00");
      
        $cbotipomovimiento = form_dropdown('tipomovimiento',$tipomovimientos,$tipomovimiento,"id='tipomovimiento' class='combopeque' onchange=\"$('#tipoexport').val('');$('#frmBusqueda').submit();\" ");   

        
        $filtroproducto     = form_dropdown("codpro",$arrproducto2,$codproducto,"id='codpro' class='comboMedio' onClick='limpiarText();' onchange=\"$('#frmBusqueda').attr('target','_self');$('#frmBusqueda').attr('action','');$('#tipoexport').val('');$('#numero').val('');submit();\" ");
        $selmoneda          = form_dropdown('moneda',array(""=>"::Seleccione:::","S"=>"SOLES","D"=>"DOLARES"),$moneda," size='1' id='moneda' class='combopeque' onchange=\"$('#frmBusqueda').attr('target','_self');$('#frmBusqueda').attr('action','');$('#tipoexport').val('');submit();\" ");               
      

        
        /*Nombre del producto*/
     /*   $filter     = new stdClass();
        $filter_not = new stdClass();
        $filter->codproducto = $codpro;
        $productos = $this->producto_model->obtenerg($filter,$filter_not);
        
        if(isset($productos->despro))
        {
        $descripcion = $productos->despro;
        $stock       = $productos->stk_actual;
        $precioi     = $productos->precio;
        $monedai     = $productos->mo;
        }
        else
        {
             $descripcion = '';
        $stock       = '';
        $precioi     = '';
        $monedai     = '';
        }*/
        /*Obtengo el último inventario*/
       /* $filter2     = new stdClass();
        $filter2_not = new stdClass();
        $filter2->codproducto = $codpro;
        $filter2->tipomov     = 'K';        
        $ultimo = $this->kardex_model->ultimo_detalle($filter2,$filter2_not);
          if(isset($ultimo->fecha))
        {
        $fecha_kardex = $ultimo->fecha;
        }
        else{
            $fecha_kardex = '';}*/
        /*Obtengo datos del kardex*/
     // $fecha_kardex = '2012-11-01';
     
        if($fecha_ini=="")    $fecha_ini    = date("01/m/Y",time());
        if($fecha_fin=="")    $fecha_fin    = date("d/m/Y",time());  
        
        $filter3     = new stdClass();
        $filter3_not = new stdClass();
    //    $filter3->codproducto = $codpro;
        
        if($fecha_ini!='')$filter3->fechai      = date_dbf($fecha_ini);
        else $filter3->fechai      = '01/01/2005';
                //$fecha_ini;
        
        if($fecha_fin!='')$filter3->fechaf      = date_dbf($fecha_fin);
        else $filter3->fechaf      = '01/01/2005';
                //$fecha_fin;
        
        if($tipomaterial!='00') $filter3->codtipomaterial = $tipomaterial;
        if($tipomovimiento!='00') $filter3->codtipomovimiento = $tipomovimiento;
        if($codproducto!="000000000000")    $filter3->codproducto = $codproducto;
        if($ots>0)$filter3->codot  = $ots;
        else {$codot='';$ots='';}
        
        
        $kardex = $this->kardex_model->listar_detalle_kardex($filter3,$filter3_not);
        
      //  print_r($kardex);
        
        $fila      = "";
        $ingreso_total = 0;
        $salida_total  = 0;
        $ingreso_ant  = 0;
        $salida_ant   = 0;
        $saldo_ant = 0;
        $saldo     = 0;
        
        
        
        $is='';
        $arrdocumento = array('G'=>'VS','TR'=>'','01'=>'NE','0'=>'AA','DV'=>'DV',''=>'IV','TF'=>'TF','10'=>'10','AJ'=>'AJ','1'=>'1','09'=>'09');
        $arrjs        = array('G'=>'ver_vale_salida(this);','TR'=>'javascript:;','01'=>'ver_nota_ingreso(this);','0'=>'javascript:;','DV'=>'ver_devolucion(this);',''=>'javascript:;','TF'=>'javascript:;','10'=>'10','AJ'=>'AJ','1'=>'javascript:;','09'=>'javascript:;');
        
        if($tipoexport==""){
        
            
            
            
          /*  $filter0           = new stdClass();
            $filter0_not       = new stdClass();
            
            $filter0->numreq  = $numreq;
            $filter0->numcom  = $numcom;
            $filter0->codigo  = $codigo;
           
            $ordcom   = $this->ocompra_model->obtener_cantidad($filter0,$filter0_not);
           
            
            
            
            $cantoc     =       $ordcom->gcantidad;
            */
            
           
            
            
        foreach($kardex as $indice => $value){
            $fecha        = $value->fecha;
            $codalm       = $value->codalm;
            $material       = $value->material;
            $codigo       = $value->codigo;
            $producto     = $value->p_descri;
            $tipmov       = $value->tip_movmto;
            $documento    = $value->documento;
            $serie        = $value->serie;
            $numero       = $value->numero;
            $monedad       = $value->moneda;
            $preciox       = $value->preprom;
            $cantidad     = $value->cantidad;
            $preprom      = $value->preprom;
            $serief       = $value->serief;
            $numdocf       = $value->numdocf;
            $serreq       = $value->serreq;
            $numreq       = $value->numreq;
            $sercom       = $value->sercom;
          
            $numcom       = $value->numcom;
            $seroc       = $value->seroc;
            $numoc       = $value->numoc;
            $cantoc       = $value->gcantidad;
          
        //  PRINT_R($cantoc);
            
            
            $fechafac     = $value->fechafac;
            $tcambio     = $value->tcambio;
            $ot           = $value->ot;
            $codot        = $value->codot;
            /*$ingreso      = (($tipmov=='I' || $tipmov=='K')?$cantidad:0); 
            $salida       = (($tipmov=='S')?$cantidad:0);
            $saldo        =  $saldo + $ingreso - $salida;*/
            
            
            
            if($moneda=='S')
            {   /*if *($monedad =='S')*/ $precio=$preciox;
               /* else  $precio=$preciox * $tcambio;*/
            }   
            else if ($moneda=='D')
            {
              /*  if ($monedad =='S')  
                    
                    if ($tcambio==0 or $tcambio=='')
                    $precio=$preciox/1;   
                    else*/
                    $precio=$preciox/$tcambio;
                
              //  else $precio=$preciox; 
            }
            
            
            
            
                    
            if ($tipmov=='I')
            {
              $is='1';   
            }
            else 
            {
              $is='2';
            }
             
             if(TRIM($serreq)!='003' AND TRIM($serreq)!='SR' AND TRIM($numreq)!='')
            {
           $serreq='003';
                 
            }
         
            
            $total_cantidad=$cantidad +$total_cantidad;
            $total_cantoc=$cantoc +$total_cantoc;
            $total_precio=$precio +$total_precio;
            $total=$precio*$cantidad + $total;
            
            
            
            $fila        .= "<tr>";
            $fila        .= "<td align='center'>".date_sql($fecha)."</td>";
         //   $fila        .= "<td align='center'><div style='width:120px;height:auto;' id='".trim($numero)."'><a href='#' onclick='".$arrjs[trim($documento)]."'>".$arrdocumento[trim($documento)].'-'.$serie.(trim($serie)==''?'':'-').$numero."</a></div></td>";
          //  $fila        .= "<td align='center'>".($tipmov=='K'?'INV':$tipmov)."</td>";
            $fila        .= "<td align='right'>".$codalm."</td>";
            $fila        .= "<td align='center'>".$material."</td>";
            $fila        .= "<td align='right'>".$codigo."</td>";
            $fila        .= "<td align='left'>".$producto."</td>";
            $fila        .= "<td align='center'>".$tipmov."</td>";
            $fila        .= "<td align='center'>".$documento."</td>";
            $fila        .= "<td align='center'>".$serie."-".$numero."</td>";
            
          /*  $fila        .= "<td align='right'>".$monedad."</td>";
            $fila        .= "<td align='right'>".number_format($tcambio,2)."</td>";*/
        
           
           
          /*  IF ($documento=='DV'){
            $fila        .= "<td align='center'>".number_format($precio*-1,4)."123</td>";    
            $fila        .= "<td align='right'>".number_format($cantidad,2)."</td>";
            $fila        .= "<td align='center'>".number_format($precio*$cantidad*-1,4)."</td>";
            
            
            $total_cantidad=$cantidad*-1 +$total_cantidad*-1;
            $total_precio=($precio*-1 +$total_precio);
            $total=$precio*$cantidad*-1 + $total;
            
            
            }ELSE{*/
            $fila        .= "<td align='center'>".number_format($precio,4)."</td>"; 
            $fila        .= "<td align='right'>".number_format($cantoc,4)."</td>";
            $fila        .= "<td align='right'>".number_format($cantidad,2)."</td>";
           
           
            $fila        .= "<td align='center'>".number_format($precio*$cantidad,4)."</td>";
           
            
          /*
            
            }*/
            
          //  $fila        .= "<td align='center'>".$serief."-".$numdocf."</td>";
            
            if(TRIM($serreq)!='003' AND TRIM($serreq)!='SR')
            {
             $fila        .= "<td align='center'>-</td>";
            }
            else{
            $fila        .= "<td align='center'><div id='".trim($numreq)."' ><a href='#' onclick='ver_requis(this);'>".TRIM($serreq)."-".trim($numreq)."</a></div></td>";
            } 
            
            IF(TRIM($tipmov)=='I')
            $fila        .= "<td align='center'><div id='".trim($numcom)."'><a href='#' onclick='ver_nea(this);'>".$sercom."-".$numcom."</a></div></td>";
            ELSE IF(TRIM($tipmov)=='S')
            $fila        .= "<td align='center'><div id='".trim($numcom)."'><a href='#' onclick='ver_vale(this);'>".$sercom."-".$numcom."</a></div></td>";
            else
            $fila        .= "<td align='center'>".$sercom."-".$numcom."</td>";
               
            
            
            
         
            
             
             
             
             
             
             
             if(TRIM($seroc)!='001' AND TRIM($seroc)!='000')
            {
             $fila        .= "<td align='center'>-</td>";
            }
            else{
             $fila        .= "<td align='center'><div id2='".trim($seroc)."' id='".trim($numoc)."' id3='OC'><a href='#' onclick='ver_ocos(this);'>".$seroc."-".$numoc."</a></div></td>";
            }
            
            
            
            
         //   $fila        .= "<td align='center'>".$fechafac."</td>";
           
            $fila        .= "<td align='left'>".$ot."</td>";
            
        
            
            
            $fila        .= "</tr>";
            
             }
            $fila .="<tr><td colspan=7></td><td bgcolor=red align=right><b><font color=white>TOTALES:</font><b></td><td bgcolor=pink>".$total_precio."</td><td bgcolor=pink>".$total_cantoc."</td><td bgcolor=pink>".$total_cantidad."</td><td bgcolor=pink>".$total."</td></tr>";
        }
              else if($tipoexport=="excel"){
         
              $xls = new Spreadsheet_Excel_Writer();
            $xls->send("Kardex.xls");
            $sheet  =$xls->addWorksheet('Reporte');
            $sheet->setColumn(0,0,9); //COLUMNA A1
            $sheet->setColumn(1,1,20); //COLUMNA B2
            $sheet->setColumn(2,2,20); //COLUMNA C3
            $sheet->setColumn(3,3,30); //COLUMNA D4
            $sheet->setColumn(4,4,13); //COLUMNA E5
            $sheet->setColumn(5,5,25); //COLUMNA F6
            $sheet->setColumn(6,6,15); //COLUMNA G7
            $sheet->setColumn(7,7,20); //COLUMNA G7
            $sheet->setColumn(8,8,20); //COLUMNA G7
            $sheet->setColumn(9,9,20); //COLUMNA G7
            $sheet->setColumn(10,10,20); //COLUMNA G7
            $sheet->setRow(0,50);
            $sheet->setRow(1,42);
            $format_bold=$xls->addFormat();
            $format_bold->setBold();
            $format_bold->setvAlign('vcenter');
            $format_bold->sethAlign('left');
            $format_bold->setBorder(1);
            $format_bold->setTextWrap();
            $format_bold2=$xls->addFormat();
            $format_bold2->setBold();
            $format_bold2->setvAlign('vcenter');
            $format_bold2->sethAlign('center');
            $format_bold2->setBorder(1);
            $format_bold2->setTextWrap();
            $format_titulo=$xls->addFormat();
            $format_titulo->setBold();
            $format_titulo->setSize(19);
            $format_titulo->setvAlign('vcenter');
            $format_titulo->sethAlign('center');
            $format_titulo->setBorder(1);
            $format_titulo->setTextWrap();
            $format_titulo2=$xls->addFormat();
            $format_titulo2->setBold();
            $format_titulo2->setSize(12);
            $format_titulo2->setvAlign('vcenter');
            $format_titulo2->sethAlign('center');
            $format_titulo2->setBorder(1);
            $format_titulo2->setTextWrap();
            $sheet->mergeCells(0,0,0,10);   
         //   $nom_tipser = $tipser=="T"?"Transportes":"Servicios";
            $sheet->write(0,1,"Reporte de Kardex del ".$fecha_ini." al ".$fecha_fin,$format_titulo);  
            /*if($nroOt!="") $sheet->write(0,4,"OT: ".$nroOt,$format_titulo); */
            
           
            
            
            
            
            
            $sheet->write(1,0,"FECHA",$format_titulo2); 
            $sheet->write(1,1,"COD. ALM.",$format_titulo2); 
            $sheet->write(1,2,"COD. MAT.",$format_titulo2); 
            $sheet->write(1,3,"CODIGO",$format_titulo2);  
            $sheet->write(1,4,"PRODUCTO",$format_titulo2);  
            $sheet->write(1,5,"TIP. MOV.",$format_titulo2); 
            $sheet->write(1,6,"DOC. REF.",$format_titulo2); 
            $sheet->write(1,7,"NUM. REF.",$format_titulo2);   
         /*   $sheet->write(1,8,"MO",$format_titulo2); 
            $sheet->write(1,9,"T.C.",$format_titulo2);  */
            $sheet->write(1,8,"PREC.",$format_titulo2); 
            $sheet->write(1,9,"CANT.",$format_titulo2); 
            $sheet->write(1,10,"CANT.",$format_titulo2);  
            $sheet->write(1,11,"PREC. TOTAL",$format_titulo2);  
           // $sheet->write(1,11,"DOC. REF.",$format_titulo2); 
            $sheet->write(1,12,"REQUIS.",$format_titulo2); 
            $sheet->write(1,13,"NUMERO",$format_titulo2);   
            $sheet->write(1,14,"O/C",$format_titulo2); 
          //$sheet->write(1,15,"FEC. FACT.",$format_titulo2); 
            $sheet->write(1,15,"OT",$format_titulo2);  
           
            $z=2;
            $y=2;
            
            
            
            
            
           foreach($kardex as $indice => $value){
            $fecha        = $value->fecha;
            $codalm       = $value->codalm;
            $material       = $value->material;
            $codigo       = $value->codigo;
            $producto     = $value->p_descri;
            $tipmov       = $value->tip_movmto;
            $documento    = $value->documento;
            $serie        = $value->serie;
            $numero       = $value->numero;
            $monedad       = $value->moneda;
            $preciox       = $value->precio;
            $cantidad     = $value->cantidad;
            $cantoc     = $value->gcantidad;
            $preprom      = $value->preprom;
            $serief       = $value->serief;
            $numdocf       = $value->numdocf;
            $serreq       = $value->serreq;
            $numreq       = $value->numreq;
            $sercom       = $value->sercom;
            $numcom       = $value->numcom;
            $seroc       = $value->seroc;
            $numoc       = $value->numoc;
            $fechafac     = $value->fechafac;
            $tcambio     = $value->tcambio;
            $ot           = $value->ot;
            $codot        = $value->codot;
           
            
         /*     if($moneda=='S')
            {   if ($monedad =='S') $precio=$preciox;
                else  $precio=$preciox * $tcambio;
            }   
            else if ($moneda=='D')
            {
                if ($monedad =='S')  
                    
                    if ($tcambio==0 or $tcambio=='')
                    $precio=$preciox/1;   
                    else
                    $precio=$preciox/$tcambio;
                
                
                
                
                else $precio=$preciox; 
            }*/
            
            
             if($moneda=='S')
            {   /*if *($monedad =='S')*/ $precio=$preciox;
               /* else  $precio=$preciox * $tcambio;*/
            }   
            else if ($moneda=='D')
            {
              /*  if ($monedad =='S')  
                    
                    if ($tcambio==0 or $tcambio=='')
                    $precio=$preciox/1;   
                    else*/
                    $precio=$preciox/$tcambio;
                
              //  else $precio=$preciox; 
            }
            
            
             if(TRIM($serreq)!='003' AND TRIM($serreq)!='SR' AND TRIM($numreq)!='')
            {
           $serreq='003';
                 
            }
            
            
            
                $sheet->write($z,0,$fecha,$format_bold);
                $sheet->write($z,1,$codalm,$format_bold);
                $sheet->write($z,2,$material,$format_bold);
                $sheet->write($z,3,$codigo,$format_bold);
                $sheet->write($z,4,$producto,$format_bold);
                $sheet->write($z,5,$tipmov,$format_bold);
                $sheet->write($z,6,$documento,$format_bold);
                $sheet->write($z,7,$serie.'-'.$numero,$format_bold);
             /*   $sheet->write($z,8,$moneda,$format_bold);
                $sheet->write($z,9, $tcambio,$format_bold);*/
                $sheet->write($z,8,$precio,$format_bold);
                $sheet->write($z,9,$cantoc,$format_bold);
                $sheet->write($z,10,$cantidad,$format_bold);
                $sheet->write($z,11,$precio*$cantidad,$format_bold);
           //     $sheet->write($z,11,$serief.'-'.$numdocf,$format_bold);
                $sheet->write($z,12,$serreq.'-'.$numreq,$format_bold);
                $sheet->write($z,13,$sercom.'-'.$numcom,$format_bold);
                $sheet->write($z,14,$seroc.'-'.$numoc ,$format_bold);
             //   $sheet->write($z,15, $fechafac,$format_bold);
                $sheet->write($z,15,$ot,$format_bold);
               
                $z++;     
      }
           $xls->close();    
      
}   
            
     /* $data['codigo']      = $codigo;
        $data['descripcion'] = $descripcion;
        $data['stock']       = $stock;*/
       
        $data['tipoexport']      = $tipoexport;
        $data['fecha_ini']       = $fecha_ini;
        $data['fecha_fin']       = $fecha_fin;
        $data['selmoneda']       = $selmoneda;
        $data['tipomaterial']    = $cbotipomaterial;
        $data['tipomovimiento']  = $cbotipomovimiento;
        $data['filtroproducto']  = $filtroproducto;
        $data['tipot']           = $tipot;
        $data['codot']           = $codot;
        $data['ot']              = $ots;
        $data['fila']            = $fila;
        $this->load->view(almacen."kardex.php",$data);  
  /* }

else
        {
        echo "<script>alert('El rango debe ser del mismo mes y mismo año')</script>";}*/
    
//    public function stock_productosp(){
//        $tipoalmacen  = $this->input->get_post('tipoalmacen');
//        $tipomaterial = $this->input->get_post('tipomaterial');
//        $linea        = $this->input->get_post('familia');
//        $tipoexport   = $this->input->get_post('tipoexport');
//        $chknegativo  = $this->input->get_post('chknegativo');
//        $chkprecio    = $this->input->get_post('chkprecio');
//        $moneda_doc   = $this->input->get_post('moneda');
//        $checked      = (($chknegativo=='1' || !isset($_REQUEST['tipoalmacen']))?true:false);
//        $checkedprecio   = (($chkprecio=='1')?true:false);
//        $fecha        = date("Y-m-d",time());
//        $hora_actual  = date("H:i:s",time()-3600);        
//        $fila         = "";  
//        /*Obtengo listado de productos*/
//        $filter           = new stdClass();
//        $filter_not       = new stdClass();
//        if($tipoalmacen!='000') $filter->codalmacen = $tipoalmacen;
//        if($tipomaterial!='00') $filter->codtipomaterial = $tipomaterial;
//        if($linea!='0000')      $filter->codlinea = $linea;
//        if($moneda_doc=='')     $moneda_doc = "S";
//        $filter_not->codalmacen = array('000','EQU');
//        $filter_not->codtipomaterial = array(' ');
//        $productos              = $this->producto_model->listar($filter,$filter_not,array('P_descri'));
//        $registros              = count($productos);
//        if($tipoexport==""){
//            $negativos = 0;
//            $negativos_stock  = 0;
//            $tmaterial_ant    = 0;
//            $total_precioprod = 0;
//            $total_preprom    = 0;
//            
//         
//        $actual=date("d-m-Y");
//        $filter        = new stdClass();
//        $filter_not    = new stdClass();
//        $filter->tcactual=$actual;
//        $tipocambio = $this->producto_model->cambio_dia($filter,$filter_not);     
//        $tcambio=$tipocambio->tipcam;   
//            
//            
//            
//            foreach($productos as $indice => $value){
//                $codigo       = $value->codpro;
//                $descripcion  = $value->despro;
//                $cantidad     = $value->stk_actual;
//                $minimo       = $value->stk_min;
//                $maximo       = $value->stk_max;
//                $comprometido = $value->stk_comp;
//                $transito     = $value->stk_trans;
//                $codalm       = $value->codalm;
//                $precio       = $value->precio;
//                $preprom      = $value->precprom;
//                $moneda       = $value->mo;
//                
//                
//            //    $tcambio      = $value->t_cambio;
//                
//                
//                $material     = $value->tipo;
//                $unidad       = $value->unimed;
//                /*Quitar cuando valide su data logistica*/
//                $precioprod   = "oo";
//                $tcambio      = $tcambio=='0'?1:$tcambio;
//                /**********************/
//                if($moneda_doc=='S'){
//                    $precioprod   = $moneda=='D'?($tcambio*$precio):$precio;
//                    $preprom      = $preprom;
//                }
//                elseif($moneda_doc=='D'){
//                    $precioprod   = $moneda=='S'?($precio/$tcambio):$precio;
//                    $preprom      = $preprom/$tcambio;
//                }
//                $disponible   = $cantidad-$comprometido+$transito;
//                if($cantidad<0) $negativos_stock++;
//                if($comprometido<0 || $transito<0) $negativos++;
//                if(!$checked){
//                    if($cantidad>0){
//                        $fila        .= "<tr ".(($comprometido<0 || $transito<0)?"bgcolor='#FFD700'":'')." ".($cantidad<0?"bgcolor='#FF0000'":'')." id='".$codigo."'>";
//                        $fila        .= "<td align='center'><a href='#' onclick='ver_kardex(this);'>".$codigo."</a></td>";
//                        $fila        .= "<td align='center'>".$codalm."</td>";
//                        $fila        .= "<td align='center'>".$material."</td>";
//                        $fila        .= "<td align='left'>".$descripcion."</td>";
//                        $fila        .= "<td align='center'>".$unidad."</td>";
//                        $fila        .= "<td align='right'>".$cantidad."</td>";
//                        $fila        .= "<td align='right'><a href='#' onclick='ver_stockcomprometido(this);'>".$comprometido."</a></td>";
//                        $fila        .= "<td align='right'><a href='#' onclick='ver_stocktransito(this);'>".$transito."</a></td>";
//                        $fila        .= "<td align='right'>".$disponible."</td>";
//                        if($checkedprecio){
//                            $fila        .= "<td align='right'>".number_format($precioprod,6)."</td>";
//                            $fila        .= "<td align='right'>".number_format($preprom,6)."</td>";
//                            $fila        .= "<td align='right'>".number_format($precioprod*$cantidad,6)."</td>";
//                            $fila        .= "<td align='right'>".number_format($preprom*$cantidad,6)."</td>";                            
//                            $fila        .= "</tr>";                            
//                        }
//                    }
//                }
//                else{
//                    $fila        .= "<tr ".(($comprometido<0 || $transito<0)?"bgcolor='#FFD700'":'')." ".($cantidad<0?"bgcolor='#FF0000'":'')." id='".$codigo."'>";
//                    $fila        .= "<td align='center'><a href='#' onclick='ver_kardex(this);'>".$codigo."</a></td>";
//                    $fila        .= "<td align='center'>".$codalm."</td>";
//                    $fila        .= "<td align='center'>".$material."</td>";
//                    $fila        .= "<td align='left'>".$descripcion."</td>";
//                    $fila        .= "<td align='left'>".$unidad."</td>";
//                    $fila        .= "<td align='right'>".$cantidad."</td>";
//                    $fila        .= "<td align='right'><a href='#' onclick='ver_stockcomprometido(this);'>".$comprometido."</a></td>";
//                    $fila        .= "<td align='right'><a href='#' onclick='ver_stocktransito(this);'>".$transito."</a></td>";
//                    $fila        .= "<td align='right'>".$disponible."</td>";
//                    if($checkedprecio){
//                        $fila        .= "<td align='right'>".number_format($precioprod,6)."</td>";
//                        $fila        .= "<td align='right'>".number_format($preprom,6)."</td>";     
//                        $fila        .= "<td align='right'>".number_format($precioprod*$cantidad,6)."</td>";
//                        $fila        .= "<td align='right'>".number_format($preprom*$cantidad,6)."</td>";                            
//                    }
//                    $fila        .= "</tr>";
//                }
//                $total_precioprod = $total_precioprod + ($precioprod*$cantidad);
//                $total_preprom    = $total_preprom + ($preprom*$cantidad);                  
//            }          
////            $rra2 = array();
////            $rra = $this->producto_model->obtener_materiales($tipoalmacen); 
////            foreach($rra as $indice2 => $value2){
////                $rra2[] = $value2->material;
////            }
//        }
//        elseif($tipoexport=='excel'){
//            $xls = new Spreadsheet_Excel_Writer();
//            $xls->send("Rpt_stockproducto.xls");
//            $sheet  =$xls->addWorksheet('Reporte');
//            $sheet->setColumn(0,0,13); //COLUMNA A1
//            $sheet->setColumn(1,1,12); //COLUMNA B2
//            $sheet->setColumn(2,2,45); //COLUMNA C3
//            $sheet->setColumn(3,3,12); //COLUMNA D4
//            $sheet->setColumn(4,4,15); //COLUMNA E5
//            $sheet->setColumn(5,5,18); //COLUMNA F6
//            $sheet->setColumn(6,6,18); //COLUMNA G7
//            $sheet->setColumn(7,7,18); //COLUMNA G7
//            $sheet->setRow(0,50);
//            $sheet->setRow(1,42);
//            $format_bold=$xls->addFormat();
//            $format_bold->setBold();
//            $format_bold->setvAlign('vcenter');
//            $format_bold->sethAlign('left');
//            $format_bold->setBorder(1);
//            $format_bold->setTextWrap();
//            $format_bold2=$xls->addFormat();
//            $format_bold2->setBold();
//            $format_bold2->setvAlign('vcenter');
//            $format_bold2->sethAlign('center');
//            $format_bold2->setBorder(1);
//            $format_bold2->setTextWrap();
//            $format_titulo=$xls->addFormat();
//            $format_titulo->setBold();
//            $format_titulo->setSize(16);
//            $format_titulo->setvAlign('vcenter');
//            $format_titulo->sethAlign('center');
//            $format_titulo->setBorder(1);
//            $format_titulo->setTextWrap();
//            $format_titulo2=$xls->addFormat();
//            $format_titulo2->setBold();
//            $format_titulo2->setSize(12);
//            $format_titulo2->setvAlign('vcenter');
//            $format_titulo2->sethAlign('center');
//            $format_titulo2->setBorder(1);
//            $format_titulo2->setTextWrap();
//            $sheet->mergeCells(0,0,0,7);  
//            $sheet->write(0,1,"STOCK DE PRODUCTOS (INCLUYE TRANSITO Y COMPROMETIDO)",$format_titulo); 
//            $sheet->write(1,0,"CODIGO",$format_titulo2);  $sheet->write(1,1,"T.ALMACEN",$format_titulo2);  $sheet->write(1,2,"PRODUCTO",$format_titulo2);  $sheet->write(1,3,"UND.",$format_titulo2);$sheet->write(1,4,"STOCK MINIMO",$format_titulo2);   $sheet->write(1,5,"STOCK ACTUAL",$format_titulo2);   $sheet->write(1,6,"STOCK COMPROMETIDO",$format_titulo2);   $sheet->write(1,7,"STOCK TRANSITO",$format_titulo2);  $sheet->write(1,8,"STOCK DISPONIBLE",$format_titulo2);     
//             if($checkedprecio){  
//            $sheet->write(1,9,"ULTIMO PRECIO",$format_titulo2);$sheet->write(1,10,"PRECIO PROMEDIO",$format_titulo2);$sheet->write(1,11,"TOTAL ULTIMO PRECIO",$format_titulo2);$sheet->write(1,12,"TOTAL PRECIO PROM.",$format_titulo2);
//             }
//            $z=2;
//            $y=2;
//            
//            
//        $actual=date("d-m-Y");
//        $filter        = new stdClass();
//        $filter_not    = new stdClass();
//        $filter->tcactual=$actual;
//        $tipocambio = $this->producto_model->cambio_dia($filter,$filter_not);     
//        $tcambio=$tipocambio->tipcam;   
//            foreach($productos as $indice => $value){
//                $codigo       = $value->codpro;
//                $descripcion  = $value->despro;
//                $cantidad     = $value->stk_actual;
//                $minimo       = $value->stk_min;
//                $maximo       = $value->stk_max;
//                $comprometido = $value->stk_comp;
//                $transito     = $value->stk_trans;
//                $codalm       = $value->codalm;
//                $precio       = $value->precio;
//                $preprom      = $value->precprom;
//                $moneda       = $value->mo;
//             //   $tcambio      = $value->t_cambio;
//                $unidad       = $value->unimed;
//                
//                if($moneda_doc=='S'){
//                    $precioprod   = $moneda=='D'?($tcambio*$precio):$precio;
//                    $preprom      = $preprom;
//                }
//                elseif($moneda_doc=='D'){
//                    $precioprod   = $moneda=='S'?($precio/$tcambio):$precio;
//                    $preprom      = $preprom/$tcambio;
//                }
//                
//                $disponible   = $cantidad-$comprometido+$transito;
//                $sheet->write($z,0,$codigo,$format_bold);
//                $sheet->write($z,1,$codalm,$format_bold);
//                $sheet->write($z,2,$descripcion,$format_bold);
//                $sheet->write($z,3,$unidad,$format_bold);
//                $sheet->write($z,4,$minimo,$format_bold);
//                $sheet->write($z,5,$cantidad,$format_bold);
//                $sheet->write($z,6,$comprometido,$format_bold);
//                $sheet->write($z,7,$transito,$format_bold);
//                $sheet->write($z,8,$disponible,$format_bold);
//                
//                if($checkedprecio){  
//                
//                $sheet->write($z,9,number_format($precioprod,6),$format_bold);
//                $sheet->write($z,10,number_format($preprom,6),$format_bold);
//                $sheet->write($z,11,number_format($precioprod*$cantidad,6),$format_bold);
//                $sheet->write($z,12,number_format($preprom*$cantidad,6),$format_bold);
//                   }
//                
//                
//                
//                $z++;
//            } 
//            $xls->close(); 
//        }   
//        /*Carga de combos*/    
//        $almacenes        = $this->almacen_model->seleccionar(new stdClass(),"::Todos::","000");
//        $filter           = new stdClass();
//        //$filter->cod_argumento = $rra2;
//        $tipomateriales   = $this->tipmaterial_conta_model->seleccionar($filter,"::Todos::","00");
//        $lineas           = $this->familia_model->seleccionar(new stdClass(),"","0000");
//        $cboTipoalmacen   = form_dropdown('tipoalmacen',$almacenes,$tipoalmacen,"id='tipoalmacen' class='comboMedio' onchange=\"$('#tipoexport').val('');$('#frmBusqueda').submit();\" ");   
//        $cboTipoamaterial = form_dropdown('tipomaterial',$tipomateriales,$tipomaterial,"id='tipomaterial' class='comboMedio' onchange=\"$('#tipoexport').val('');$('#frmBusqueda').submit();\" ");   
//        $cboFamilia       = form_dropdown('familia',$lineas,$linea,"id='familia' class='comboMedio' onchange=\"$('#tipoexport').val('');$('#frmBusqueda').submit();\" ");
//        $chknegativo      = form_checkbox('chknegativo',1, $checked,"onclick=\"this.value=(this.checked)?'1':'0';$('#frmBusqueda').submit();\"");
//        $chkprecio        = form_checkbox('chkprecio',1, $checkedprecio,"onclick=\"$('#divMoneda').attr('style',(this.value=(this.checked)?'visibility:block;float:left;width:60%;':'visibility:hidden;float:left;width:60%;'));this.value=(this.checked)?'1':'0';$('#frmBusqueda').submit();\"");
//        $cboMoneda        = form_dropdown('moneda',array(""=>"::Seleccione:::","S"=>"SOLES","D"=>"DOLARES"),$moneda_doc," size='1' id='moneda' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#tipoexport').val('');submit();\" ");               
//        $data['cboTipoalmacen']   = $cboTipoalmacen;
//        $data['cboTipoamaterial'] = $cboTipoamaterial;
//        $data['cboFamilia']       = $cboFamilia;
//        $data['cboMoneda']        = $checkedprecio?$cboMoneda:'&nbsp;';
//        $data['fila']             = $fila;
//        $data['registros']        = $registros;
//        $data['hora_actual']      = $hora_actual;
//        $data['negativos']        = $negativos;
//        $data['negativos_stock']  = $negativos_stock;
//        $data['chknegativo']      = $chknegativo;
//        $data['chkprecio']        = $chkprecio;
//        $data['checkedprecio']    = $checkedprecio;
//        $data['moneda_doc']       = $moneda_doc;
//        $data['total_precioprod'] = $total_precioprod;
//        $data['total_preprom']    = $total_preprom;
//        $data['oculto']           = form_hidden(array('serie'=>'','numero'=>'','cadenaot'=>'','codot'=>'','tipoexport'=>'','codpro'=>''));
//        $this->load->view(almacen."stock_productosp.php",$data);
//    }

    }

 public function kardex1(){
    
        $tipoalmacen  = $this->input->get_post('tipoalmacen');
        $tipomaterial = $this->input->get_post('tipomaterial');
        $linea        = $this->input->get_post('familia');
        $tipoexport   = $this->input->get_post('tipoexport');
        $codpro       = $this->input->get_post('codpro');
        /*Nombre del producto*/
        $filter     = new stdClass();
        $filter_not = new stdClass();
        $filter->codproducto = $codpro;
        $productos = $this->producto_model->obtenerg($filter,$filter_not);
        if(isset($productos->despro)){
            $descripcion = $productos->despro;
            $stock       = $productos->stk_actual;
            $precioi     = $productos->precio;
            $monedai     = $productos->mo;
        }
        else
        {
             $descripcion = '';
            $stock       = '';
            $precioi     = '';
            $monedai     = '';
        }
        /*Obtengo el último inventario*/
        $filter2     = new stdClass();
        $filter2_not = new stdClass();
        $filter2->codproducto = $codpro;
        $filter2->tipomov     = 'K';        
        $ultimo = $this->kardex_model->ultimo_detalle($filter2,$filter2_not);
        if(isset($ultimo->fecha)){
            $fecha_kardex = $ultimo->fecha;
        }
        else{
            $fecha_kardex = '';
        }
        /*Obtengo datos del kardex*/
        $filter3     = new stdClass();
        $filter3_not = new stdClass();
        $filter3->codproducto = $codpro;
        if($fecha_kardex!=''){
            $filter3->fechai      = date_sql($fecha_kardex);    
        }
        else{
            $filter3->fechai      = $fecha_kardex;    
        }
        $kardex = $this->kardex_model->listar_detalle($filter3,$filter3_not);
         
       
        
        if($tipoexport==""){
            $arr_export_detalle = array();//para excel
            $fila      = "";
            $ingreso_total = 0;
            $salida_total  = 0;
            $ingreso_ant  = 0;
            $salida_ant   = 0;
            $saldo_ant = 0;
            $saldo     = 0;
            $arrdocumento = array('G'=>'VS','TR'=>'','01'=>'NE','0'=>'AA','DV'=>'DV',''=>'IV','TF'=>'TF','10'=>'10','AJ'=>'AJ','1'=>'1','09'=>'09');
            $arrjs        = array('G'=>'ver_vale_salida(this);','TR'=>'javascript:;','01'=>'ver_nota_ingreso(this);','0'=>'javascript:;',
                                  'DV'=>'ver_devolucion(this);',''=>'javascript:;','TF'=>'javascript:;','10'=>'10','AJ'=>'AJ',
                                  '1'=>'javascript:;','09'=>'javascript:;','03'=>'javascript:;','22'=>'javascript:;',
                                  'VS'=>'ver_vale_salida(this)','NE'=>'ver_vale_salida(this)');
            $numerodoc    = "";
            foreach($kardex as $indice => $value){
                $arr_data = array(); // para excel
                $fecha        = $value->fecha;
                $codalm       = $value->codalm;
                $codigo       = $value->codigo;
                $tipmov       = $value->tip_movmto;
                $moneda       = $value->moneda;
                $precio       = $value->precio;
                $cantidad     = $value->cantidad;
                $preprom      = $value->preprom;
                $documento    = $value->documento;
                $serie        = $value->serie;
                $numero       = $value->numero;
                $serreq       = $value->serreq;
                $numreq       = $value->numreq;
                $codot        = $value->codot;
                $sercom       = $value->sercom;
                $numcom       = $value->numcom;
                $ot           = $value->ot;
                $ingreso      = (($tipmov=='I' || $tipmov=='K')?$cantidad:0); 
                $salida       = (($tipmov=='S')?$cantidad:0);
                $saldo        =  $saldo + $ingreso - $salida;
                $numerodoc    = ($tipmov=='I')?$numcom:(($tipmov=='S')?$numero:"----");
                
                $fila        .= "<tr>";
                $fila        .= "<td align='center'>".$fecha."</td>";
                $arr_data[]   =date_sql($fecha);
                $fila        .= "<td align='center'>".($tipmov=='K'?'INV':$tipmov)."</td>";
                $arr_data[]   =($tipmov=='K'?'INV':$tipmov);
                $fila        .= "<td align='center'>".$documento."</td>";
                $arr_data[]   =$documento;
                $fila        .= "<td align='center'><div style='width:120px;height:auto;' id='".trim($numerodoc)."'><a href='#' onclick='".$arrjs[trim($documento)]."'>".$serie.(trim($serie)==''?'':'-').$numerodoc."</a></div></td>";
                $arr_data[]   =$serie.(trim($serie)==''?'':'-').$numerodoc;
                $fila        .= "<td align='right'>".$ingreso."</td>";
                $arr_data[]   =$ingreso;
                $fila        .= "<td align='right'>".$salida."</td>";
                $arr_data[]   =$salida;
                $fila        .= "<td align='right'>".$saldo."</td>";
                $arr_data[]   =$saldo;
                $fila        .= "<td align='center'>".$ot."</td>";
                $arr_data[]   =$ot;
                $fila        .= "<td align='center'>".$serreq."-".$numreq."</td>";
                $arr_data[]   =$serreq."-".$numreq;
                $fila        .= "</tr>";
                array_push($arr_export_detalle,$arr_data);
            }  
            
            $var_export = array('rows' => $arr_export_detalle);
            $this->session->set_userdata('data_detalle_stock', $var_export);
            
            $data['codigo']      = $codigo;
            $data['descripcion'] = $descripcion;
            $data['stock']       = $stock;
            $data['fila']        = $fila;
            $data['saldo']       = $saldo;
            $this->load->view(almacen."kardex1.php",$data);  
        } 
        
       /* 
        elseif ($tipoexport=='excel.det'){
            
            
            $arr_columns[0]['STRING'] = 'Fecha';
            $arr_columns[1]['STRING'] = 'Tipo_Mov';
            $arr_columns[2]['STRING'] = 'Documento';
            $arr_columns[3]['STRING'] = 'Numero';
            $arr_columns[4]['STRING'] = 'Ingreso';
            $arr_columns[5]['STRING'] = 'Salida';
            $arr_columns[6]['STRING'] = 'Saldo';
            $arr_columns[7]['STRING'] = 'OT';
            $arr_columns[8]['STRING'] = 'REQUIRIMIENTO';
            $arr_data    = array();
            $var_prd_n = 0;
            $var_row = 9;
            $saldo     = 0;
            foreach($kardex as $indice => $value){
                $numero       = $value->numero;
                $cantidad     = $value->cantidad;
                $tipmov= $value->tip_movmto;
                
                $ingreso      = (($tipmov=='I' || $tipmov=='K')?$cantidad:0); 
                $salida       = (($tipmov=='S')?$cantidad:0);
                $saldo        =  $saldo + $ingreso - $salida;
                $numerodoc    = ($tipmov=='I')?$numcom:(($tipmov=='S')?$numero:"----");
                
                $numcom       = $value->numcom;
           // echo "elemento $var_prd_n <br>ingreso: $ingreso<br>Salida: $salida<br>Salddo: $saldo<br><br>";
                
                $arr_data[$var_prd_n] = array(
                    $value->fecha,
                    $value->tip_movmto,
                    $value->documento,
                    $numerodoc,
                    $ingreso,
                   $salida,
                    $saldo,
                    $value->ot,
                    $value->numreq,
                    
                );
               $var_prd_n++; 
               $var_row++;
            }
            
            $arr_grouping_header = array();
            $arr_grouping_header['A5:I5'] = 'KARDEX';
            $this->reports_model->rpt_general('Stock_Producto_Cod_'.$codpro,'"'.$codpro.' - '.$descripcion.'"',$arr_columns,$arr_data ,$arr_grouping_header);
        }
        */
    }
 
   

    public function req_pending(){
        
        if($this->input->get_post('chk_products')){
            $c = $this->input->get_post('chk_products');
            if($this->entidad=='01'){
                foreach ($c as $key => $value) {
                    $c[$key] = (int)$value;
                }
            }
            $this->requis_model->del_Requis($c);
        }
           
        $arr_req = $this->requis_model->get_Requis();
        $list_req = array();
        
        foreach($arr_req as $req_code => $req_data){
            $list_req[$req_data->req_number][$req_data->req_code] = $req_data ; 
        }
        

        if($this->input->get_post('export_type')=='excel'){
           
            $arr_columns[0]['STRING'] = 'Requisición';
            $arr_columns[1]['DATE'] = 'Fecha';
            $arr_columns[2]['STRING'] = 'Departamento';
            $arr_columns[3]['STRING'] = 'OT';
            $arr_columns[4]['STRING'] = 'Código';
            $arr_columns[5]['STRING'] = 'Producto';
            $arr_columns[6]['NUMERIC'] = 'Solicitado';
            $arr_columns[7]['NUMERIC'] = 'Atendido';
            $arr_columns[8]['FORMULA'] = 'Pendiente';
            $arr_columns[9]['NUMERIC'] = 'OC';

            $arr_data    = array();
            $var_prd_n = 0;
            $var_row = 7;
            foreach($list_req as $req_code => $req_data){
                foreach ($req_data as $key => $value) {
                    $arr_data[$var_prd_n] = array(
                     
                        $value->req_serie.'-'.$value->req_number,
                        date("d/m/Y",strtotime($value->req_date)),
                        trim($value->req_department),
                        trim($value->req_ot_number),
                        $value->req_prd_code,
                        utf8_encode(trim($value->req_prd_description)),
                        $value->req_qty,
                        $value->req_qty_s,
                        '=G'.$var_row.'-H'.$var_row.'',
			$value->qty_oc
                    );
                    $var_prd_n++; 
                    $var_row++;
                }
            }

            $arr_grouping_header = array();
            $arr_grouping_header['A5:F5'] = 'Descripción';
            
          
            $this->reports_model->rpt_general('rpt_pending_requeriments','Requisiciones Pendientes',$arr_columns,$arr_data ,$arr_grouping_header);

        }
        
            
            
        $data['arr_req'] = $list_req;
        $this->load->view(almacen."v_req_pending",$data);
    }

    


    public function ord_pending(){

        if($this->input->get_post('chk_products')){
            $ord_codes = $this->input->get_post('chk_products');
            if($this->entidad=='01'){
                foreach ($ord_codes as $key => $value) {
                    $ord_codes[$key] = (int)$value;
                }
            }
            $this->ocompra_model->del_Orders($ord_codes);
        }
           
        $arr_ord = $this->ocompra_model->get_Orders();
        $list_ord = array();
        
        foreach($arr_ord as $ord_code => $ord_data){
            $list_ord[$ord_data->ord_number][$ord_data->ord_code] = $ord_data ; 
        }
        
        
        if($this->input->get_post('export_type')=='excel'){
           
            $arr_columns[0]['STRING'] = 'Orden';
            $arr_columns[1]['STRING'] = 'Fecha';
            $arr_columns[2]['STRING'] = 'RUC';
            $arr_columns[3]['STRING'] = 'Proveedor';
            $arr_columns[4]['STRING'] = 'Código';
            $arr_columns[5]['STRING'] = 'Producto';
            $arr_columns[6]['NUMERIC'] = 'Solicitado';
            $arr_columns[7]['NUMERIC'] = 'Atendido';
            $arr_columns[8]['FORMULA'] = 'Pendiente';


            $arr_data    = array();
            $var_prd_n = 0;
            $var_row = 7;
            foreach($list_ord as $ord_code => $ord_data){
                foreach ($ord_data as $key => $value) {
                    $arr_data[$var_prd_n] = array(
                     
                        $value->ord_serie.'-'.$value->ord_number,
                        date("d/m/Y",strtotime($value->ord_date)),
                        $value->ord_sup_ruc,
                        trim($value->ord_sup_name),
                        $value->ord_prd_code,
                        utf8_encode($value->ord_prd_description),
                        $value->ord_qty,
                        $value->ord_qty_s,
                        '=G'.$var_row.'-H'.$var_row.''
                    );
                    $var_prd_n++; 
                    $var_row++;
                }
            }

            $arr_grouping_header = array();
            $arr_grouping_header['A5:F5'] = 'Descripción';
            
          
            $this->reports_model->rpt_general('rpt_pending_orders','Ordenes Pendientes',$arr_columns,$arr_data ,$arr_grouping_header);

        }
        
        $data['arr_ord'] = $list_ord;
        $this->load->view(almacen."v_ord_pending",$data);
 
    }
    
    public function upd_Comprometido(){
        //$this->requis_model->upd_Comprometido();
        //echo "test";
    }
    public function expo(){
        
        $productos = json_decode($this->input->get_post('arr'));

            $arr_columns[0]['STRING'] = 'Código';
            $arr_columns[1]['STRING'] = 'Almacén';
            $arr_columns[2]['STRING'] = 'Producto';
            $arr_columns[3]['STRING'] = 'UND';
            $arr_columns[4]['NUMERIC'] = 'Stock Mínimo';
            $arr_columns[5]['NUMERIC'] = 'Stock Actual';
            $arr_columns[6]['NUMERIC'] = 'Stock Comprometido';
            $arr_columns[7]['NUMERIC'] = 'Stock Tránsito';
            $arr_columns[8]['FORMULA'] = 'Stock Disponible';


            $arr_data    = array();
            $var_prd_n = 0;
            $var_row = 7;

            foreach($productos as $prd_key => $prd_value){
                $arr_data[$var_prd_n] = array(
                    $prd_value->codpro,
                    $prd_value->codalm,
                    utf8_encode(trim($prd_value->despro)),
                    $prd_value->unimed,
                    $prd_value->stk_min,
                    $prd_value->stk_actual,
                    $prd_value->stk_comp,
                    $prd_value->stk_trans,
                    '=F'.$var_row.'-G'.$var_row.'+H'.$var_row.''
                );
               $var_prd_n++; 
               $var_row++;
            }
            
            $arr_grouping_header = array();
            $arr_grouping_header['A5:D5'] = 'Descripción';
            $this->reports_model->rpt_general('rpt_stock_products','sTOCK de Productos',$arr_columns,$arr_data ,$arr_grouping_header);
            
    }

    public function export_excel($type){
            if($this->session->userdata('data_'.$type)){
                $result = $this->session->userdata('data_'.$type);
                switch ($type) {
                    case 'stock_productos':
                        $arr_columns = array();
                        $arr_export_detalle = array();
                        $arr_columns[]['STRING'] = 'CODIGO';
                        $arr_columns[]['STRING'] = 'ALMACEN';
                        $arr_columns[]['STRING'] = 'MATERIAL';
                        $arr_columns[]['STRING'] = 'LINEA';
                        $arr_columns[]['STRING'] = 'PRODUCTO';
                        $arr_columns[]['NUMERIC'] = 'PESO';
                        $arr_columns[]['STRING'] = 'UNIDAD';
                        $arr_columns[]['NUMERIC'] = 'STOCK ACTUAL';
                        $arr_columns[]['NUMERIC'] = 'STOCK COMPROM';
                        $arr_columns[]['NUMERIC'] = 'STOCK TRANS';
                        $arr_columns[]['NUMERIC'] = 'STOCK DISPONIBLE';
                        
                        $arr_group = array('H5:K5'=>'STOCK');
                        $this->reports_model->rpt_general('rpt_'.$type,'Stock_Productos', $arr_columns, $result["rows"],$arr_group);
                        break;         
                    case 'detalle_stock':
                        $arr_columns = array();
                        $arr_export_detalle = array();
                        $arr_columns[]['STRING'] = 'Fecha';
                        $arr_columns[]['STRING'] = 'Tipo_Mov';
                        $arr_columns[]['STRING'] = 'Documento';
                        $arr_columns[]['STRING'] = 'Numero';
                        $arr_columns[]['NUMERIC'] = 'Ingreso';
                        $arr_columns[]['NUMERIC'] = 'Salida';
                        $arr_columns[]['NUMERIC'] = 'Saldo';
                        $arr_columns[]['STRING'] = 'OT';
                        $arr_columns[]['STRING'] = 'REQUIRIMIENTO';
                        $arr_group = array();
                        $this->reports_model->rpt_general('rpt_'.$type,'Detalle de Producto',$arr_columns,$result["rows"],$arr_group);                          
                        break;
                    
                    case 'stock_comprometido':
                        
                        $arr_columns = array();
                        $arr_export_detalle = array();
                        $arr_columns[]['STRING']='NUM.REQ';
                        $arr_columns[]['STRING']='FECHA';
                        $arr_columns[]['STRING']='NROOT';		
                        $arr_columns[]['STRING']='CODRES';
                        $arr_columns[]['STRING']='APROBADO';
                        $arr_columns[]['STRING']='USERAPROB';
                        $arr_columns[]['NUMERIC']='CANTIDAD';
                        $arr_columns[]['NUMERIC']='CANTIDAD V.S.</td>';
                        $arr_columns[]['NUMERIC']='STOCK COMPROM.';
                        $arr_columns[]['STRING']='FECHA V.S.';
                        $arr_columns[]['STRING']='NUMERO V.S.';
                        $arr_columns[]['STRING']='VALUSER';
                        $arr_group = array();
                        $this->reports_model->rpt_general('rpt_'.$type,'Detalle de Stock Comprometido',$arr_columns,$result["rows"],$arr_group);                          
                        break;
                    
                    case 'stock_transito':
                        $arr_columns[]['STRING']='FECHA OC';
                        $arr_columns[]['STRING']='NUM OC';
                        $arr_columns[]['NUMERIC']='CANT. OC';		
                        $arr_columns[]['STRING']='FECHA NEA';
                        $arr_columns[]['STRING']='NUM. NEA';
                        $arr_columns[]['NUMERIC']='CANT. NEA';
                        $arr_columns[]['NUMERIC']='STOCK TRANS.';
                        $arr_group = array();
                        $this->reports_model->rpt_general('rpt_'.$type,'Detalle de Stock Transito',$arr_columns,$result["rows"],$arr_group);                          
                        break;
                }
            }
            else{
                echo "<div style='color:rgb(150,150,150);padding:10px;width:560px;height:160px;border:1px solid rgb(210,210,210);'>
                No hay datos para exportar
                </div>";
            }
        }
     
	public function seleccionar_unidad_medida($indDefault=''){
		$array_undMedida = $this->umedida_model->listar();
		$arreglo = array();
		if(count($array_undMedida)>0){
			foreach($array_undMedida as $indice=>$valor){
				$indice1   = $valor->UNDMED_Codigo;
				$valor1    = $valor->UNDMED_Descripcion;
				$arreglo[$indice1] = $valor1;
			}
		}
		$resultado = $this->html->optionHTML($arreglo,$indDefault,array('','::Seleccione::'));
		return $resultado;
	}       
        
}
?>