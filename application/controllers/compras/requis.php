<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Content-type: text/html; charset=utf-8");
require_once "Spreadsheet/Excel/Writer.php";
class Requis extends CI_Controller {
    var $entidad;
    var $login;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");  
        $this->load->model(almacen.'producto_model');
        $this->load->model(almacen.'servicio_model');
        $this->load->model(almacen.'producto_model');
        $this->load->model(almacen.'familia_model');
        $this->load->model(almacen.'ningreso_model');
        $this->load->model(almacen.'nsalida_model');
        $this->load->model(ventas.'ot_model');
        $this->load->model(compras.'ocompra_model');
        $this->load->model(compras.'proveedor_model');
        $this->load->model(compras.'soliocompra_model');
        $this->load->model(compras.'requis_model');
        $this->load->model(compras.'facturac_model');
        $this->load->model(personal.'persona_model');
        $this->load->model(personal.'responsable_model');
        $this->load->model(maestros.'tipo_dcto_model');
        $this->load->model(maestros.'formapago_model');
        $this->load->model(maestros.'ubigeo_model');
        $this->load->model(maestros.'tc_model');
        $this->load->model(maestros.'ttorre_model');
        $this->dbase   = $this->load->database('dsn',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');
    }
    
    public function index(){
        
    }
    
    public function listar(){
        
    }
    
    /*Reporte de requisiciones largo ::: Seguimiento*/
    public function listar_x_ot(){
        $fecha_ini  = $this->input->get_post('fecha_ini');
        $fecha_fin  = $this->input->get_post('fecha_fin'); 
        $tipoexport = $this->input->get_post('tipoexport');
        $numero     = $this->input->get_post('numero');
        $fecha      = $this->input->get_post('fecha');
        $area       = $this->input->get_post('area');
        $codres     = $this->input->get_post('codres');
        $codtip     = $this->input->get_post('codtip');
        $codot      = $this->input->get_post('codot');
        $ot         = $this->input->get_post('ot');
        $codproducto = $this->input->get_post('pro_codigo');
        $pro_descripcion = $this->input->get_post('pro_descripcion');
          
        $opcion      = $this->input->get_post('opcion');
        $tipot       = $this->input->get_post('tipot');
        
        $arr_export_detalle = array();
        $hora_actual = date("H:i:s",time()-3600);
        if($fecha_ini=="")    $fecha_ini    = date("01/m/Y",time());
        if($fecha_fin=="")    $fecha_fin    = date("d/m/Y",time());
        if($codproducto=="")  $codproducto       = "000000000000";
        /*Productos*/
        $filter = new stdClass();
        $filter->estado    = 2;
        $filter->situacion = 2;
        $productos      = $this->producto_model->listar(new stdClass(),new stdClass(),array("P_descri"));
        $familias               = $this->familia_model->listar(new stdclass());
        $arrproducto2   = array("000000000000"=>"::: TODOS :::");
        foreach($productos as $indice => $value){
            $codpro = trim($value->codpro);
            $arrproducto[$codpro]  = $value;
            $arrproducto2[$codpro] = $value->codpro." - ".$value->despro;
        } 
        $filter2    = new stdClass();
        $filter2->estado    = 2;
        $filter2->situacion = 2;
        $filter3 = new stdClass();
        $filter3->estado = 1;
        $filtroresponsable  = form_dropdown("codres",$this->responsable_model->seleccionar($filter2,new stdClass(),array('nomper'=>'asc'),":::TODOS:::","000000"),$codres,"id='codres' class='comboGrande' onClick='limpiarText();' ");
        $filtroproducto     = form_dropdown("codpro",$arrproducto2,$codproducto,"id='codpro' class='comboMedio' onClick='limpiarText();' ");
        $filtrotipo         = form_dropdown("codtip",$this->requis_model->seleccionar_requis_tipo($filter3,new stdClass(),array('codtip'=>'asc'),":::TODOS:::",""),$codtip,"id='codtip' class='comboMedio' onClick='limpiarText();' onchange=\"$('#frmBusqueda').attr('target','_self');$('#frmBusqueda').attr('action','');$('#tipoexport').val('');$('#numero').val('');\"");
        $fila      = "";
        $registros = "";
        if($opcion == "C"){
            /*Solicitud de compra*/
            $filter      = new stdClass();
            $filter_not  = new stdClass();
            $filter->fechai     = $fecha_ini;   
            $filter->estado     = "P";   
            $soli_ocompra  = $this->soliocompra_model->listar_detalle($filter,$filter_not);
            /*Orden de compra*/
            $filter      = new stdClass();
            $filter_not  = new stdClass();
            $filter->fechai     = $fecha_ini;    
            $orden_compras = $this->ocompra_model->listar_detalle($filter,$filter_not);
            /*Nota de ingreso*/
            $filter      = new stdClass();
            $filter_not  = new stdClass();
            $filter->fechai     = $fecha_ini;        
            $notas_ingreso = $this->ningreso_model->listar_detalle($filter,$filter_not);
            /*Proveedores*/
            $proveedores = $this->proveedor_model->listar(new stdClass());
            /*Vale de salida*/
            $filter         = new stdClass();
            $filter_not     = new stdClass();
            $filter->fechai = $fecha_ini;   
            if($codot!="")  $filter->codot      = $codot;   
            $nsalidas   = $this->nsalida_model->listar_detalle($filter,$filter_not);            
            /*Requisiciones*/
            $filter             = new stdClass();
            $filter_not         = new stdClass();        
            if($ot!="" ) $filter->codot      = $codot;
            $filter->fechai     = $fecha_ini;
            $filter->fechaf     = $fecha_fin;   
            $filter->codres     = $codres;
            if($codproducto!="000000000000" )    $filter->codpro = $codproducto;
            if($codtip!="")    $filter->tipo = $codtip;
            $requisiciones      = $this->requis_model->listar_detalle($filter,$filter_not,'Fecemi desc');
            $registros  = count($requisiciones);
            if($tipoexport==""){
            if($registros>0){
                foreach($requisiciones as $indice=>$value){
                    $arr_data = array();
                    $serie        = $value->seriedoc;
                    $numguia      = $value->nrodoc;
                    $codigo       = $value->gcodpro;
                    $cantidad     = $value->gcantidad;
                    $precio       = $value->gprecio;
                    $cantidad_S    = $value->gcantidad;
                    $departamento = $value->gdepa;
                    $tipoot       = $value->tipot;
                    $nroot        = $value->got;
                    $codresot     = $value->gsolicita;
                   
                    $tot_soli = $cantidad_S*$precio;
                    
                    while (strlen($codresot)<6) {
                        $codresot= "0".$codresot;
                    }
                    $useraprob    = $value->useraprob;
                    $fecemi       = $value->fecemi;
                    $peso         = $value->peso;
                    $observacion  = $value->gobs;
                    $tiporequis   = $value->tipo;
                    $fecapro      = $value->fec_apro;
                    $arrfecapro   = isset($fecapro)?explode(" ",trim($value->fec_apro)):"";
                    $fechapro     = isset($fecapro)?date_sql(trim($arrfecapro[0])):"";
                    $horapro      = isset($fecapro)?trim($arrfecapro[1]):"";
                    $linea        = "";
                    $descripcion  = "";
                    $unidad       = "";
                    $stock        = "";
                    $transito     = "";
                    $comprometido = "";
                    $disponible   = "";
                    $pesoprod     = "";
                    $familia      = "";
                    /*Obtengo producto*/
                    foreach($arrproducto as $cod => $val){
                        if(trim($cod)==trim($codigo)){
                            $descripcion  = $val->despro;
                            $unidad       = $val->unimed;
                            $almacen      = $this->entidad=="01"?$val->codalm:substr($codigo,0,2);
                            $linea        = $val->tipo;
                            $stock        = $val->stk_actual;
                            $transito     = $val->stk_trans;
                            $comprometido = $val->stk_comp;
                            $disponible   = $val->stk_actual + $val->stk_trans - $val->stk_comp;
                            $pesoprod     = $val->peso;
                            $codfamilia      = $this->entidad=="01"?substr($codigo,0,4):substr($codigo,0,5);
                            $familia      = "";
                             foreach($familias as $ind => $val){
                                    if($codfamilia==$val->cod_argumento){
                                $familia = $val->des_larga;
                                     break;
                                        }else{$familia=  $codfamilia;   }
                                        }
                            
                        }
                    }
                    
                    /*Solicitud de compra*/
                    $modo      = "";
                    $numoc     = "";
                    $numsc     = "";
                    $varnumsc  = "";
                    $cant_solicompra = 0;
                    $fecha_solioc = "";      
                    $nombre_modo  = "";
                    $soli_total=0;
                    $arrnumoc     = array();
                    foreach($soli_ocompra as $indice2 => $value2){
                        if(trim($value2->gnumreq)==trim($numguia) && trim($value2->gcodpro)==trim($codigo)){
                            $fecha_solioc  = ($value2->fecemi!=''?date_sql($value2->fecemi):'')."\r\n".$fecha_solioc;                           
                            $modo  = $value2->modo;
                            $numoc = $value2->numord;
                            $arrnumoc[] = $value2->numord;
                            $numsc   = $numsc."<div id='".$value2->gnumero."' ><a href='#' onclick='ver_nea(this);'>".$value2->gserie."-".$value2->gnumero."</a></div>";
                            $varnumsc =trim($value2->gserie)."-".trim($value2->gnumero)."|".$varnumsc;                              
                            $cant_solicompra  = $cant_solicompra + $value2->gcantidad;
                           // $soli_pre = $cant_solicompra* $value2->gprecioreal;
                            
                            if($modo=='O' or $modo=='03') $nombre_modo = "O.COMPRA<br>".$nombre_modo;
                            if($modo=='S' or $modo=='01') $nombre_modo = "LETRA S<br>".$nombre_modo;
                            if($modo=='E' or $modo=='02') $nombre_modo = "EFECT.<br>".$nombre_modo;
                           
                        }
                    }
                    
                    /*Ordenes de compra*/
                    $fechaoc = "";
                    $numocompra = "";
                    $var_numoc  = "";
                    $numruc     = "";
                    $var_ruc    = "";
                    $razcli     = "";
                    $var_razcli = "";
                    $cant_oc    = 0;
                    $var_precio_oc = 0;
                    $fechaocapro = "";
                    if(count($arrnumoc)>0){
                        foreach($arrnumoc as $numoc){
                            foreach($orden_compras as $indice2 => $value2){
                               if(trim($value2->nrodoc)==trim($numoc) && trim($value2->gnumreq)==trim($numguia) && trim($value2->gcodpro)==trim($codigo)){
                                   $fechaoc    = ($value2->fecrep!=''?date_sql($value2->fecrep):'')."\r\n".$fechaoc;  
                                   $fechaocapro = ($value2->fec_apro!=''?date_sql(substr($value2->fec_apro,0,10)):'')."\r\n".$fechaocapro; 
                                   $numocompra = $numocompra."<div id='".$value2->nrodoc."' id2='001' id3='OC'><a href='#' onclick='ver_ocos(this);'>".$value2->seriedoc."-".$value2->nrodoc."</a></div>";
                                   $var_numoc  = trim($value2->nrodoc)."|".$var_numoc; 
                                   $cant_oc    = $cant_oc + $value2->gcantidad;
                                   $numruc     = trim($value2->ruccli)."<br>".$numruc; 
                                   $var_ruc    = trim($value2->ruccli)."|".$var_ruc; 
                                   //$precio     = $value2->gprecio;
                                   $var_precio_oc = $var_precio_oc + ($value2->gmoneda == 'D')?($value2->gprecio)*($value2->gcantidad)*($value2->tc):($value2->gprecio)*($value2->gcantidad);
                                   foreach($proveedores as $indice3 => $value3){
                                       if(trim($value3->ruc) == trim($value2->ruccli)){
                                         $razcli =  trim($value3->rsocial);
                                         break; 
                                       }
                                   }
                                   $var_razcli = $razcli."|".$var_razcli;
                                  /* $aproboc = $value2->flgAprobado;
                                   if($aproboc!='X')  {$numoc = "";$fechaoc="";} */
                               }
                            }
                        }  
                    }
                    /*Nota de entrada*/
                    $fechnea = "";
                    $fechneareg = "";
                    $numnea  = "";
                    $cantnea  = 0;
                    $var_numnea = "";
                    if(count($arrnumoc)>0){
                        foreach($arrnumoc as $numoc){
                            foreach($notas_ingreso as $indice2 => $value2){
                                if($this->entidad=='01'){
                                    if(trim($value2->numoc)==trim($numoc) && trim($value2->numreq)==trim($numguia) && trim($value2->codigo)==trim($codigo)){
                                        $fechnea  = ($value2->fecha!=''?date_sql($value2->fecha):'')."\r\n".$fechnea;  
                                        $fechneareg  = ($value2->fechareg!=''?date_sql($value2->fechareg):'')."\r\n".$fechneareg;
                                        $numnea   = $numnea."<div id='".$value2->numcom."' ><a href='#' onclick='ver_nea(this);'>".$value2->sercom."-".$value2->numcom."</a></div>";
                                        $var_numnea =$value2->numcom."|".$var_numnea;  
                                        if ($value2->tip_movmto=='I' and $value2->documento!='DV'){
                                          $cantnea  = $cantnea + $value2->cantidad;
                                        }                                
                                    }                            
                                }
                                elseif($this->entidad=='02'){
                                    if(trim($value2->numoc)==trim($numoc) && trim($value2->codigo)==trim($codigo)){//Falta la requis
                                        $fechnea  = ($value2->fecha!=''?date_sql($value2->fecha):'')."\r\n".$fechnea;   
                                        $fechneareg  = ($value2->fechareg!=''?date_sql($value2->fechareg):'')."\r\n".$fechneareg;
                                        $numnea   = $numnea."<div id='".$value2->numcom."' ><a href='#' onclick='ver_nea(this);'>".$value2->sercom."-".$value2->numcom."</a></div>";
                                        $var_numnea =trim($value2->sercom)."-".trim($value2->numcom)."|".$var_numnea;                                
                                        if ($value2->tip_movmto=='I' and $value2->documento!='DV'){
                                          $cantnea  = $cantnea + $value2->cantidad;
                                        }     
                                    }
                                }
                            } 
                        }
                    }

                    /*Vales de salida*/
                    $fechvale = "";
                    $numvale  = "";
                    $var_numvale  = "";                    
                    $cantvale = 0;
                    $var_precio_vale = 0;
                    foreach($nsalidas as $indice2 => $value2){
                        if(trim($value2->numreq)==trim($numguia) && trim($value2->codigo)==trim($codigo)){
                           $fechvale  = ($value2->fecha!=''?date_sql($value2->fecha):'')."\r\n".$fechvale; 
                           $numvale   = $numvale."<div id='".$value2->numero."'><a href='#' onclick='ver_vale_salida(this);'>".$value2->serie."-".$value2->numero."</a></div>";
                           $var_numvale =trim($value2->serie)."-".trim($value2->numero)."|".$var_numvale;
                           if ($value2->tip_movmto=='S' and $value2->documento!='DV'){
                             $cantvale  = $cantvale + $value2->cantidad;
                             $var_precio_vale = $var_precio_vale + ($value2->preprom)*($value2->cantidad);
                           }
                        }
                    }

                    $documento='G';
                    /*Responsable*/
                    $filter2     = new stdClass();
                    $filter2_not = new stdClass();
                    $filter2->codresponsable = $codresot;
                    $filter2->estado    = 2;
                    $filter2->situacion = 2;
                    $responsable    = $this->responsable_model->obtener($filter2,$filter2_not);  
                    $nomresponsable = !isset($responsable->nomper)?'':$responsable->nomper;
                    if ($nomresponsable==''){$nomresponsable='Usuario Inactivo';}
                    $fila   .= "<tr>";
                    $fila   .= "<td align='center' style='width:1.7%;'><div>".$tiporequis."</div></td>";
                    $arr_data[] = $tiporequis;
                    $fila   .= "<td align='center' style='width:3%;'><div id='".trim($numguia)."' ><a href='#' onclick='ver_requis(this);'>".$serie."-".$numguia."</a></div></td>";
                    $arr_data[] = $numguia;
                    $fila   .= "<td align='center' style='width:2.7%;'><div>".$nroot."</div></td>";
                    $arr_data[] = $nroot;
                    $fila   .= "<td align='left'   style='width:6.5%;'><div>".utf8_encode($nomresponsable)."</div></td>";
                    $arr_data[] = utf8_encode($nomresponsable);
                    $fila   .= "<td align='center' style='width:3.5%;'><div>".utf8_encode($departamento)."</div></td>";
                    $arr_data[] = utf8_encode($departamento);
                    $fila   .= "<td align='center' style='width:3.2%;'><div>".$codigo."</div></td>";
                    $arr_data[] = $codigo;
                    $fila   .= "<td align='center' style='width:3.2%;'><div>".$almacen."</div></td>";
                    $arr_data[] = $almacen;
                    $fila   .= "<td align='center' style='width:3.2%;'><div>".$linea."</div></td>";
                    $arr_data[] = $linea;
                     $fila   .= "<td align='center' style='width:3.2%;'><div>".utf8_encode($familia)."</div></td>";
                    $arr_data[] = utf8_encode($familia);
                    $fila   .= "<td align='left' style='width:8.6%;'><div>".utf8_encode($descripcion)."</div></td>";
                    $arr_data[] = utf8_encode($descripcion);
                    $fila   .= "<td align='center' style='width:2%;'><div>".$unidad."</div></td>";
                    $arr_data[] = $unidad;
                    $fila   .= "<td align='right' style='width:2.8%;'><div>".$cantidad."</div></td>";// SOLICITADO
                    $arr_data[] = $cantidad;
                    $fila   .= "<td align='right' style='width:2.8%;'><div>".$cant_oc."</div></td>";
                     $arr_data[] = $cant_oc;                      
                    $fila   .= "<td align='right' style='width:2.8%;'><div>".$cantnea."</div></td>";
                     $arr_data[] = $cantnea;                    
                    $fila   .= "<td align='right' style='width:2.8%;'><div>".$cantvale."</div></td>";
                    $arr_data[] = $cantvale;
                    $fila   .= "<td align='right' style='width:2.7%;'><div>".$stock."</div></td>";
                    $arr_data[] = $stock;
                    $fila   .= "<td align='right' style='width:2.7%;'><div>".$transito."</div></td>";
                    $arr_data[] = $transito;   
                    $fila   .= "<td align='right' style='width:2.7%;'><div>".$comprometido."</div></td>";
                    $arr_data[] = $comprometido;   
                    $fila   .= "<td align='right' style='width:2.7%;'><div>".$disponible."</div></td>";
                    $arr_data[] = $disponible;                       
                    $fila   .= "<td align='right' style='width:2.7%;'><div>".$peso."</div></td>";
                     $arr_data[] = $peso;
                    $fila   .= "<td align='right' style='width:2.7%;'><div>".$pesoprod*$cantvale."</div></td>";
                     $arr_data[] = $pesoprod*$cantvale;
                    $fila   .= "<td align='right' style='width:2.7%;'><div>".number_format($tot_soli,2)."</div></td>";
                    $arr_data[] = $tot_soli; 
                    $fila   .= "<td align='right' style='width:2.7%;'><div>".number_format($var_precio_oc,2)."</div></td>";
                    $arr_data[] = $var_precio_oc;                     
                    $fila   .= "<td align='right' style='width:2.7%;'><div>".number_format($var_precio_vale,2)."</div></td>";
                    $arr_data[] = $var_precio_vale;
                    $fila   .= "<td align='center' style='width:4%;'><div>".date_sql($fecemi)."</div></td>";
                     $arr_data[] = date_sql($fecemi);
                    $fila   .= "<td align='center' style='width:4%;'><div>".(trim($fechapro)=='30/12/1899'?'':$fechapro)."</div></td>";
                    $arr_data[] = (trim($fechapro)=='30/12/1899'?'':$fechapro);
                    $fila   .= "<td align='center' style='width:3.5%;'><div>".$fecha_solioc."</div></td>";
                    $arr_data[] = $fecha_solioc!=""?substr($fecha_solioc,0,strlen($fecha_solioc)-2):"";
                    $fila   .= "<td align='center' style='width:3.5%;'><div>".$fechaoc."</div></td>";
                    $arr_data[] = $fechaoc!=""?substr($fechaoc,0,strlen($fechaoc)-2):"";
                     $fila   .= "<td align='center' style='width:3.5%;'><div>".(trim($fechaocapro)=='30/12/1899'?'':$fechaocapro)."</div></td>";
                    $arr_data[] = ($fechaocapro=="")?"":substr($fechaocapro,0,strlen($fechaocapro)-2);
                    $fila   .= "<td align='center' style='width:3.5%;'><div>".$fechnea."</div></td>";
                    $arr_data[] = $fechnea!=""?substr($fechnea,0,strlen($fechnea)-2):"";
                    $fila   .= "<td align='center' style='width:3.5%;'><div>".(trim($fechneareg)=='30/12/1899'?'':$fechneareg)."</div></td>";
                    $arr_data[] = ($fechneareg!="")?substr($fechneareg,0,strlen($fechneareg)-2):"";                    
                    $fila   .= "<td align='center' style='width:3.5%;'><div>".$fechvale."</div></td>";
                    $arr_data[] = $fechvale!=""?substr($fechvale,0,strlen($fechvale)-2):"";
                    $fila   .= "<td align='center' style='width:3.5%;'><div>".$nombre_modo."</div></td>";
                    $nombre_modo2 = str_replace("<br>","|",$nombre_modo);
                    $arr_data[] = $nombre_modo2!=""?substr($nombre_modo2,0,strlen($nombre_modo2)-1):"";
                    $fila   .= "<td align='center' style='width:3.5%;'><div>".$numsc."</div></td>";
                    $arr_data[] = $varnumsc!=""?substr($varnumsc,0,strlen($varnumsc)-1):"";
                    $fila   .= "<td align='center' style='width:3.5%;'><div>".$numocompra."</div></td>";
                    $arr_data[] = $var_numoc!=""?substr($var_numoc,0,strlen($var_numoc)-1):"";
                    $fila   .= "<td align='center' style='width:3.5%;'><div>".$numnea."</div></td>";
                    $arr_data[] = $var_numnea!=""?substr($var_numnea,0,strlen($var_numnea)-1):"";
                    $fila   .= "<td align='center' style='width:3.5%;'><div>".$numvale."</div></td>";
                    $arr_data[] = trim($var_numvale);
                    $fila   .= "<td align='center' style='width:7.7%;'><div>".utf8_encode($observacion)."</div></td>";
                    $arr_data[] = utf8_encode($observacion)  ;
                    $fila   .= "<td align='center' style='width:7.7%;'><div>".$numruc."</div></td>";
                    $arr_data[] = $var_ruc!=""?substr($var_ruc,0,strlen($var_ruc)-1):"";        
                    $fila   .= "<td align='center' style='width:7.7%;'><div>".($var_razcli!=""?str_replace('|','<br>',$var_razcli):'')."</div></td>";
                    $arr_data[] = utf8_encode($var_razcli);  
                    $fila   .= "</tr>";
                    array_push($arr_export_detalle,$arr_data);
                }
            }
            $var_export = array('rows' => $arr_export_detalle);
            $this->session->set_userdata('data_listar_requisiciones_ot', $var_export);
            }             
        }
        $data['fila'] = $fila;
        $data['filtroresponsable'] = $filtroresponsable;
        $data['filtroproducto']    = $filtroproducto;
        $data['filtrotipo']    = $filtrotipo;
        $data['tipoexport']    = $tipoexport;
        $data['tipot']         = $tipot;
        $data['codot']         = $codot;
        $data['ot']            = $ot;
        $data['fecha_ini']     = $fecha_ini;
        $data['fecha_fin']     = $fecha_fin;
        $data['fecha'] = "";
        $data['registros']     = $registros;
        $data['pro_descripcion']=$pro_descripcion;
        $data['pro_codigo']     =$codproducto;
        $this->load->view(compras."requis_listar_x_ot_total",$data);
    }
    
    /*Reporte de requisiciones corto*/
    public function rpt_requis(){
        $fecha_ini     = $this->input->get_post('fecha_ini');
        $fecha_fin     = $this->input->get_post('fecha_fin'); 
        $tipoexport    = $this->input->get_post('tipoexport');
        $numero        = $this->input->get_post('numero');
        $fecha         = $this->input->get_post('fecha');
        $area          = $this->input->get_post('area');
        $codtip        = $this->input->get_post('codtip');
        $codot         = $this->input->get_post('codot');
        $cadenaot      = $this->input->get_post('cadenaot'); 
        $codproducto   = $this->input->get_post('codpro');
        $tipot         = $this->input->get_post('tipot');
        $monedadoc     = $this->input->get_post('moneda');
        $flg_exclusiones    = $this->input->get_post('exclusiones');
        $arr_export_detalle = array();
        $hora_actual   = date("H:i:s",time()-3600);
        /*Son todas las familias de productos que no se considerarán en el reporte de control de pesos*/
        $exclusiones = $this->config->item('exclusiones');            
        $arrCodOT      = $codot!=''?array($codot):explode(",",$cadenaot);
        /*Nro OT*/
        $nroOt         = "";
        $dirOt         = "";
        if(count($arrCodOT)==1){
            $filter        = new stdClass();
            $filter_not    = new stdClass();
            $filter->codot = $arrCodOT[0];   
            $oOt       = $this->ot_model->obtenerg($filter,$filter_not);
            $nroOt     = $oOt->NroOt;
            $dirOt     = $oOt->DirOt;      
        }        
        if($fecha_ini=="")    $fecha_ini    = '01/01/2012';
        if($fecha_fin=="")    $fecha_fin    = date("d/m/Y",time());
        if($codproducto=="")  $codproducto       = "000000000000";
        $productos      = $this->producto_model->listar(new stdClass(),new stdClass(),array("P_descri"));
        $familias       = $this->familia_model->listar(new stdclass());
        $arrfamila      = array();
        foreach($familias as $item => $value){
            $arrfamila[trim($value->cod_argumento)] = $value->des_larga;
        }
//        print_r("<pre>");
//        print_r($familias);
//        print_r("</pre>");
        foreach($productos as $indice => $value){
            $codpro = trim($value->codpro);
            $arrproducto[$codpro]  = $value;
        } 
        $filter              = new stdClass();
        $filter_not          = new stdClass();
        $filter->codot       = $arrCodOT;
        $filter->fechaproi   = $fecha_ini;
        $filter->fechaprof   = $fecha_fin;
        $filter->flgAprobado = 1; 
        if($codproducto!="000000000000")    $filter->codpro = $codproducto;
        if($codtip!="0")     $filter->tipo = $codtip;
        if($flg_exclusiones=='S')$filter_not->linea  = $exclusiones;
        $requisiciones      = $this->requis_model->listar_detalle($filter,$filter_not,'Fecemi desc');
        $fila               = "";
        $tot_peso_solic     = 0;
        $tot_cant_solic     = 0;
        $codot_user  = $this->session->userdata('codot');
        $ver_precios = ($codot_user!="0003739" && $codot_user!="0003722" && $codot_user!="0003723")?true:false;        
        if($tipoexport==""){
        if(count($requisiciones)>0 && count($arrCodOT)>0){
            foreach($requisiciones as $indice=>$value){
                $arr_data     = array();
                $codigoot     = $value->codot;
                $serie        = $value->seriedoc;
                $numguia      = $value->nrodoc;
                $codigo       = $value->gcodpro;
                $cantidad     = $value->gcantidad;                         
                $departamento = $value->gdepa;
                $codresot     = $value->gsolicita;
                while (strlen($codresot)<6) {
                    $codresot= "0".$codresot;
                }
                $useraprob    = $value->useraprob;
                $fecemi       = $value->fecemi;//FEcha emision de la requis
                $peso         = $value->peso;
                $observacion  = $value->gobs;
                $tiporequis   = $value->tipo;
                $precio       = $value->gprecio;
                $arrfecapro   = explode(" ",trim($value->fec_apro));//Fecha de aprobacion
                $fechapro     = date_sql(trim($arrfecapro[0]));
                $horapro      = trim($arrfecapro[1]);
                $tcambio      = $this->tc_model->obtener($fechapro);                    
                $tc           = $tcambio->Valor_2;     
                /*Obtengo producto*/
                $descripcion  = ":: Producto eliminado ::";
                $unidad       = "--";
                $linea        = 0;
                $stock        = 0;
                $transito     = 0;
                $comprometido = 0;
                $disponible   = 0;
                $pesoprod     = 0;
                $precioprod   = 0;
                foreach($arrproducto as $cod => $val){
                    if($cod==$codigo){
                        $descripcion  = $val->despro;
                        $unidad       = $val->unimed;
                        $linea        = $val->tipo;
                        $stock        = $val->stk_actual;
                        $transito     = $val->stk_trans;
                        $comprometido = $val->stk_comp;
                        $disponible   = $val->stk_actual + $val->stk_trans - $val->stk_comp;
                        $pesoprod     = $val->peso;
                        $precioprod   = $val->precprom;//Precio en soles
                        break;
                    }
                }
                /*Responsable*/
                $filter2     = new stdClass();
                $filter2_not = new stdClass();
                $filter2->codresponsable = $codresot;
                $filter2->estado    = 2;
                $filter2->situacion = 2;
                $responsable    = $this->responsable_model->obtener($filter2,$filter2_not);  
                $nomresponsable = !isset($responsable->nomper)?'':$responsable->nomper;
                if ($nomresponsable==''){$nomresponsable='Usuario Inactivo';}
                $tot_peso_solic     = $tot_peso_solic + $pesoprod*$cantidad;   
                $total_soles        = 0;
                $total_dolares      = 0;
                /*Obtengo OT*/
                $filter3        = new stdClass();
                $filter3->codot = $codigoot;   
                $ots       = $this->ot_model->obtenerg($filter3);
                $numeroot  = $ots->NroOt;
                $torres      = $this->ttorre_model->obtener($ots->Torre);
                $fila   .= "<tr>";
                $fila   .= "<td align='center' style='width:3.2%;'><div>".$numeroot."</div></td>";
                $arr_data[] = $numeroot;  
                $fila   .= "<td align='center' style='width:3.2%;'><div>".$torres->Des_Larga."</div></td>";
                $arr_data[] = utf8_encode($torres->Des_Larga);  
                $fila   .= "<td align='center' style='width:3%;'><div id='".trim($numguia)."' ><a href='#' onclick='ver_requis(this);'>".$serie."-".$numguia."</a></div></td>";
                $arr_data[] = $serie."-".$numguia;  
                $fila   .= "<td align='center' style='width:4%;'><div>".(trim($fecemi)=='30/12/1899'?'':$fecemi)."</div></td>";
                $arr_data[] = (trim($fecemi)=='30/12/1899'?'':$fecemi);  
                $fila   .= "<td align='center' style='width:3.5%;'><div>".$departamento."</div></td>";
                $arr_data[] = utf8_encode($departamento);  
                $fila   .= "<td align='center' style='width:3.2%;'><div>".$codigo."</div></td>";
                $arr_data[] = $codigo; 
                $fila   .= "<td align='left' style='width:8.6%;'><div>".$arrfamila[substr($codigo,0,4)]."</div></td>";
                $arr_data[] = utf8_encode($arrfamila[substr($codigo,0,4)]); 
                $fila   .= "<td align='left' style='width:8.6%;'><div>".$descripcion."</div></td>";
                $arr_data[] = utf8_encode($descripcion);  
                $fila   .= "<td align='center' style='width:2%;'><div>".$unidad."</div></td>";
                $arr_data[] = $unidad;  
                $fila   .= "<td align='right' style='width:2.8%;'><div>".$cantidad."</div></td>";
                $arr_data[] = $cantidad;  
                if($ver_precios){
                    if($monedadoc=='S'){
                        $tot_cant_solic = $tot_cant_solic + $precio*$cantidad;   
                        $fila    .= "<td style='width:11%;height:auto;text-align:right;'><div>".number_format($precio,4)."</div></td>";        
                        $arr_data[] = $precio;  
                        $fila    .= "<td style='width:10%;height:auto;text-align:right;'><div>".number_format($precio*$cantidad,4)."</div></td>";
                        $arr_data[] = $precio*$cantidad;  
                    }    
                    elseif($monedadoc=='D'){
                        $tot_cant_solic = $tot_cant_solic + $precio*$cantidad/$tc;   
                        $fila    .= "<td style='width:11%;height:auto;text-align:right;'><div>".number_format($precio/$tc,4)."</div></td>";        
                        $arr_data[] = $precio/$tc;  
                        $fila    .= "<td style='width:10%;height:auto;text-align:right;'><div>".number_format($precio*$cantidad/$tc,4)."</div></td>";
                        $arr_data[] = $precio*$cantidad/$tc;  
                    }                         
                }           
                $fila   .= "<td align='right' style='width:2.7%;'><div>".number_format($pesoprod*$cantidad,2)."</div></td>";
                $arr_data[] = $pesoprod*$cantidad;  
                $fila   .= "<td align='left'   style='width:6.5%;'><div>".$nomresponsable."</div></td>";     
                $arr_data[] = utf8_encode($nomresponsable);  
                $fila   .= "<td align='center' style='width:7.7%;'><div>".$observacion."</div></td>";
                $arr_data[] = utf8_encode($observacion);  
                $fila   .= "</tr>";
                array_push($arr_export_detalle,$arr_data);
            }   
            $fila   .= "<tr>";
            $fila   .= "<td colspan='".($ver_precios?11:8)."'><div>&nbsp;</div></td>";
            //$fila   .= "<td align='right' style='width:7.7%;'><div>".number_format($tot_cant_solic,4)."</div></td>";
            $fila   .= "<td align='right' style='width:7.7%;'><div></div></td>";
            $fila   .= "<td align='right' style='width:7.7%;'><div>".number_format($tot_peso_solic,2)."</div></td>";
            $fila   .= "</tr>";
        }
        $var_export = array('rows' => $arr_export_detalle);
        $this->session->set_userdata('data_listar_req_ot_corto', $var_export);
         }
        $data['fila']        = $fila;
        $data['tipoexport']  = $tipoexport;
        $data['monedadoc']   = $monedadoc;
        $data['tipot']       = $tipot;
        $data['codot']       = $codot;
        $data['nroOt']       = $nroOt;
        $data['dirOt']       = $dirOt;        
        $data['fecha_ini']   = $fecha_ini;
        $data['fecha_fin']   = $fecha_fin;
        $data['ver_precios'] = $ver_precios;
        $data['fecha']       = "";
        $this->load->view(compras."requis_listar_x_ot",$data);
    }
    
    public function obtener(){
        
        $this->load->view(ventas."ot_listar");
    }    
    
    public function ver(){
       $hora_Actual=date("H:i:s");
       /* $serie          = $this->input->get_post('serie');*/
        $numero         = $this->input->get_post('numero');
        //  $coot         = $this->input->get_post('codot');
        $filter         = new stdClass();
        $filter_not     = new stdClass();
        /*$filter->serie  = $serie;*/
        $filter->numero = $numero;
        $facturas       = $this->facturac_model->obtener_rq($filter,$filter_not);
     /*   if(isset($facturas->NroDoc)){*/
            $gserguia    = $facturas->gserguia;
            $gnumguia     = $facturas->gnumguia;
            $gentrega    = $facturas->gentrega;
            $codot    = $facturas->codot;
            $gdepa=$facturas->gdepa;
            $gsolicita= $facturas->gsolicita;
            $gnumguia     = $facturas->gnumguia;
            $fecemi     = $facturas->fecemi;
            $got = $facturas->got;
             $gobs = $facturas->  gobs; 
            //$nlote   = $facturas->nlote;
            $filter1     = new stdClass();
            $filter1_not = new stdClass();
            $filter1->solicita = $gsolicita;
            $solicitaper = $this->persona_model->obtener_sol($filter1,$filter1_not);
            if(isset($solicitaper->nombre))
            {
            $soliper      = $solicitaper->nombre;       }
            else{
                $soliper='';
            }
            $filter1     = new stdClass();
            $filter1_not = new stdClass();
            $filter1->codot = $codot;
            $ot1 = $this->ot_model->obtenerg($filter1,$filter1_not);
           $NroOt      = $ot1->NroOt;     
          /*  $nrod        = $facturas->nrod;
            $gfentrega  = $facturas->gfentrega;
            $gpeso = $facturas->gpeso;
            $ghora = $facturas->ghora;$tipod   = $facturas->tipod;
            $seried   = $facturas->seried;
            $detalle   = $facturas->gobserva;$fdespacho  = $facturas->fdespacho;
            $fpago = $facturas->fpago;
            $igv = $facturas->igv;
            $subtotal   = $facturas->subtotal;
            $gdetrac   = $facturas->gdetrac;
            $gp_detrac        = $facturas->gp_detrac;
            $mo        = $facturas->moneda;
            $cambio       = $facturas->cambio;
            $dot     = $facturas->got;*/
         /*   $filter2     = new stdClass();
            $filter2_not = new stdClass();
            //$filter2->numero = $dot;
            $filter2->codot = $coot;
            //print_r($filter2);
            $oOt         = $this->ot_model->obtenerg($filter2,$filter2_not);
             $nroOt       = $oOt->NroOt;
             $dirOt       = $oOt->DirOt;*/
      /*       $nrod        = $facturas->tipod;echo $nrod;*/
           // $docu        = $numd->docdescri;
         /*   $gruc   = $facturas->gruc;
             if($gruc==0)
             $gruc       ='00000000000';
            $filter         = new stdClass();
            $filter_not     = new stdClass();
            $filter->ruccliente = $gruc;
            $pro            = $this->proveedor_model->obtener($filter,$filter_not);
            $razonsocial           = $pro->RazCli;  */
        //    $gcontacto        = $facturas->gcontacto;  
      /*      $filter         = new stdClass();
            $filter_not     = new stdClass();
            $filter->ruccliente = $gcontacto;
         /*   $pro            = $this->proveedor_model->obtener($filter,$filter_not);*/
         //   $contactox           = isset($pro->RazCli)?$pro->RazCli:"";  
       //     $gcodser = $facturas->gcodser;
        /*    $filter         = new stdClass();
            $filter_not     = new stdClass();
            $filter->codservicio = $gcodser;    
            $service   = $this->servicio_model->obtener($filter,$filter_not);
            /*print_r($service);*/
        //    $servdetalle           = $service->DesPro; 
        /*    $gdestino   = $facturas->gdestino;
            $filter         = new stdClass();
            $filter_not     = new stdClass();
            $filter->ubica = $gdestino;    
            $ubic   = $this->ubigeo_model->obtener_ubigeo1($filter,$filter_not);
            $unionx           = $ubic->union; */
       /* echo $service->despro;die;*/
      /* $gpersonal  = $facturas->gpersonal;
        $filter->codresponsable = $gpersonal; 
        $responsable   = $this->responsable_model->obtener($filter,$filter_not);
              $sol     = $responsable->nomper;
            */
         //   $moneda      = $mo=='S'?"NUEVOS SOLES":"DOLARES AMERICANOS";
           /* $filter3     = new stdClass();
            $filter3_not = new stdClass();
            $filter3->ruccliente = $ruccli;
            $proveedores = $this->proveedor_model->obtener($filter3,$filter3_not);
            $razcli      = $proveedores->RazCli;*/
            /*Cabecera*/
            $this->load->library("fpdf/pdf");
            $CI = & get_instance();
            $CI->pdf->FPDF('P');
            $CI->pdf->AliasNbPages();
            $CI->pdf->AddPage();
            $CI->pdf->SetTextColor(0,0,0);
            $CI->pdf->SetFillColor(255,255,255);
            $CI->pdf->SetFont('Arial','B',11);
            $CI->pdf->SetTextColor(0,0,0);
            $CI->pdf->SetFillColor(216,216,216);
            //$CI->pdf->Image('images/anadir.jpg',11,4,30);
            $CI->pdf->Image('img/mimco_ruc.jpg',10,8,55);
            $CI->pdf->Cell(120,8, "No REQUISICION:  003-".$numero,0,0,"R",0);
            $CI->pdf->SetFont('Arial','B',7);
            $CI->pdf->Cell(50,8,"Hora:".$hora_Actual,0,1,"R",0);
            $CI->pdf->Cell(150,15, "" ,0,1,"L",0);
            //$CI->pdf->Cell(60,5, "NRO OT : ".$nroOt ,0,0,"L",0);
            //  $CI->pdf->Cell(60,5, "REQ.SERVICIO : ".$seroc."-".$nrooc ,0,0,"L",0);
            $CI->pdf->Cell( 25,5,"SOLICITANTE" ,0,0,"L",0);
            $CI->pdf->Cell(25,5,":  ". $gsolicita.'  -  '. $soliper,0,1,"L",0);
            $CI->pdf->Cell(25,5,"DEPARTAMENTO" ,0,0,"L",0);
            $CI->pdf->Cell(25,5,":  ".  $gdepa,0,0,"L",0);
            $CI->pdf->Cell( 25,5,"        T. CAMBIO:",0,0,"L",0);
            $CI->pdf->Cell( 25,5,"",0,1,"L",0);
            $CI->pdf->Cell( 25,5,"FECHA",0,0,"L",0);
            $CI->pdf->Cell( 25,5,":   ".date_sql($fecemi),0,0,"L",0);
            $CI->pdf->Cell( 25,5,"       OT.",0,0,"L",0);
            $CI->pdf->Cell( 25,5,  $NroOt,0,0,"L",0);
            $CI->pdf->Cell( 25,5,"FECHA REQUERIDA",0,0,"L",0);
            $CI->pdf->Cell( 25,5,":   ".date_sql($gentrega),0,1,"L",0);
           /* $CI->pdf->Cell(60,5, "TIPO DOC. REF. : ".$tipdocref ,0,0,"L",0);
            $CI->pdf->Cell(60,5,  "NRO DOC. REF. :".$serieref."-".$numeroref ,0,0,"L",0);
            $CI->pdf->Cell(60,5, "MONEDA : ".$moneda ,0,1,"L",0);
            $CI->pdf->Cell(120,5, "PROVEEDOR : ".$ruccli." ".$razcli ,0,0,"L",1);
            $CI->pdf->Cell(120,5, "FEHCA VENC. : ".$fecvcto ,0,1,"L",0);
            $CI->pdf->SetTextColor(255,255,255);
            $CI->pdf->SetFillColor(192,192,192);*/
            /*Detalle*/
            //$CI->pdf->Cell( 25,5,":   ".$gpersonal." -    ". $sol. "      No Lote:  ". $nlote,0,1,"L",0);
            $CI->pdf->Cell( 25,5,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"L",0);
           /* $CI->pdf->SetFillColor(0,0,128);*/
            $CI->pdf->Cell(  25,5,"ITEM",0,0,"C",0);
        //     $CI->pdf->Cell( 25,5,":   ".$got. " -    ". $dirOt,0,1,"L",0);
            $CI->pdf->Cell( 25,5,"CODIGO",0,0,"C",0);
     //        $CI->pdf->Cell( 25,5,":   ".$servdetalle,0,1,"L",0);
            $CI->pdf->Cell( 10,5,"MONEDA",0,0,"C",0);
           //  $CI->pdf->Cell( 25,5,":   ".$unionx,0,1,"L",0);
            $CI->pdf->Cell( 70,5,"DESCRIPCION",0,0,"C",0);
       //      $CI->pdf->Cell( 25,5,":   ".$gcontacto."   -    ".$contactox,0,1,"L",0);
            $CI->pdf->Cell( 20,5,"CANTIDAD",0,0,"C",0);
         //    $CI->pdf->Cell( 25,5,":   ".date_sql($gfentrega)."                  PESO:  ".$gpeso."                   HORA ENTREGA:  ".$ghora,0,1,"L",0);
            $CI->pdf->Cell( 20,5,"P. UNIT.",0,0,"C",0);
       //      $CI->pdf->Cell( 25,5,":   ".$gruc."   -    ".$razonsocial,0,1,"L",0);
            $CI->pdf->Cell( 20,5,"MONTO",0,1,"C",0);
    //         $CI->pdf->Cell( 25,5,":   ".$tipod. " -    ".$docu."          SERIE / NRO. DCTO  :  ".$seried." - ".$nrod,0,1,"L",0);
              $CI->pdf->Cell( 25,5,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"L",0);
        $lista_rq       = $this->facturac_model->listar_rq($filter,$filter_not);
        $z=1;
        foreach($lista_rq as $indice => $value){
            $gnumguia  = $value->gnumguia;
            $gserguia  = $value->gserguia;
            $gsolicita = $value->gsolicita;
            $gcodpro   = $value->gcodpro;
            $gprecio   = $value->gprecio;
            $gcantidad = $value->gcantidad;
            //$producto1 = $this->producto_model->obtener1($gcodpro);
            $filter3   = new stdClass();
            $filter3->codproducto = $gcodpro;
            $producto1 = $this->producto_model->obtenerg($filter3);
            if(isset($producto1->Despro)){       
             $producto  = $producto1->Despro;  
             //$mo        = $value->Mo;
            }
            else{
              $producto = $producto1->despro;;  ////  $subtotal     = $value->codo;
              //$mo       = $value->mo;
            }
            $gtotal=$gprecio*$gcantidad;
            //$moneda       = $mo=='2'?"S/":"$";
            $moneda = "";
            /*Cliente*/
           /* $filter2     = new stdClass();
            $filter2_not = new stdClass();
            if($tper=='02'){
                if(strlen(trim($codcliente))>6) $codcliente = substr(trim($codcliente),2,6);
                $filter2->codcliente = $codcliente;
                $oCliente  = $this->proveedor_model->obtener($filter2,$filter2_not);
                $razcli    = $oCliente->RazCli; 
            }
            elseif($tper=='03'){
                $filter2->codresponsable = substr(trim($codcliente),2,6);
                $oCliente  = $this->responsable_model->obtener($filter2,$filter2_not);
                $razcli    = isset($oCliente->nomper)?$oCliente->nomper:""; 
            }    */
            /*Tipo rodumento referencia*/
          /*  $tipodoc = $this->tipodocumento_caja_model->obtener($tipdocref);
            $tipodocref_nombre = $tipodoc->Des_Larga;*/
            $CI->pdf->Cell(25,5,$z,1,0,"C",0);
            $CI->pdf->Cell(25,5,$gcodpro,1,0,"C",0);
            $CI->pdf->Cell(10,5,$moneda,1,0,"C",0);
            $CI->pdf->Cell(70,5,$producto,1,0,"l",0);
            $CI->pdf->Cell(20,5,$gcantidad,1,0,"C",0);
            $CI->pdf->Cell(20,5,$gprecio,1,0,"C",0);
            $CI->pdf->Cell(20,5,$gtotal,1,1,"C",0);
           /* $CI->pdf->Cell(60,5,substr($razcli,0,46),1,0,"L",0);
            $CI->pdf->Cell(10,5,substr($tipodocref_nombre,0,2),1,0,"C",0);    
            $CI->pdf->Cell(20,5,$seriedocref."-".$nrodocref,1,0,"C",0);    
            $CI->pdf->Cell(20,5,trim($fecemision),1,0,"C",0);    
            $CI->pdf->Cell(12,5,$moneda." ".number_format($importe,2),1,1,"R",0); 
            $importe_total = $importe_total + $importe;*/
            $z=$z+1;
        }
        /*    $CI->pdf->Cell( 25,5,"I.G.V.",0,0,"L",0);
         //    $CI->pdf->Cell( 25,5,":   ".Number_format($igv,2),0,0,"L",0);
              $CI->pdf->Cell( 20,5,"SUBTOTAL",0,0,"L",0);
          //   $CI->pdf->Cell( 25,5,":   ".Number_format($subtotal,2),0,0,"L",0);
             $CI->pdf->Cell( 15,5,"TOTAL",0,0,"L",0);
          //   $CI->pdf->Cell( 25,5,":   ".Number_format($subtotal+$igv,2),0,1,"L",0);
            $CI->pdf->Cell( 25,5,"DETRACCION",0,0,"L",0);
         //    $CI->pdf->Cell( 25,5,":   ".Number_format($gdetrac,2)."   %   ".Number_format($gp_detrac,2),0,1,"L",0);
            
            $CI->pdf->Cell( 25,5,"MONEDA",0,0,"L",0);
        //     $CI->pdf->Cell( 25,5,":   ".$moneda."        TC.:  ".$cambio,0,1,"L",0);*/
            $CI->pdf->SetFont('Arial','',8);
            $CI->pdf->SetTextColor(0,0,0);
            $filter4     = new stdClass();
            $filter4_not = new stdClass();        
          /*  $filter4->serie  = $serie;
            $filter4->numero = $numero;
            $filter4->codot  = $codot;*/
           /* $facturas_det    = $this->facturac_model->listar_detalle($filter4,$filter4_not);
            foreach($facturas_det as $indice => $value){
                $cantidad  = $value->CantSolRep;
                $codigo    = $value->CodPro;
                $punitario = $value->PrecUnit;
                $codot2    = $value->CodOt;*/
                /*Nombre OT*/
                $filter6        = new stdClass();
                $filter6_not    = new stdClass();
            /*    $filter6->codot = trim($codot2); */
             /*   $oOt2          = $this->ot_model->obtenerg($filter6,$filter6_not);
                $nroOt2        = $oOt2->NroOt;  
                $filter5     = new stdClass();
                $filter5_not = new stdClass();
                $filter5->codot = $codot2;
                $ots2           = $this->ot_model->obtenerg($filter5,$filter5_not);
                $nroOt          = $ots2->NroOt;
                $productos      = $this->producto_model->obtener($codigo);
                $descripcion    = $productos->DesPro;
                $codunidad      = $productos->UniMed;
                $CI->pdf->Cell(5,5,$indice+1,1,0,"C",0);
                $CI->pdf->Cell(10,5,$cantidad,1,0,"C",0);
                $CI->pdf->Cell(30,5,$codigo,1,0,"C",0);
                $CI->pdf->Cell(20,5,$codunidad,1,0,"L",0);
                $CI->pdf->Cell(69,5,$descripcion,1,0,"L",0);
                $CI->pdf->Cell(20,5,number_format($punitario,2),1,0,"R",0);    
                $CI->pdf->Cell(20,5,number_format($punitario*$cantidad,2),1,0,"R",0);      
                $CI->pdf->Cell(15,5,$nroOt2,1,1,"L",0); 
            }*/$CI->pdf->Cell( 25,5,"",0,1,"L",0);
            $CI->pdf->Cell( 25,5,"",0,1,"L",0);
             $CI->pdf->SetFont('Arial','B',8);
             $CI->pdf->Cell( 25,5,"OBSERVACIONES",0,1,"L",0);
            $CI->pdf->Cell( 25,5,"$gobs",0,1,"L",0);
             $CI->pdf->Cell( 25,5,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"L",0);
               $CI->pdf->Cell(65,20,"",0,0,"C",0);
        $CI->pdf->Cell( 65,20,"",0,0,"C",0);
        $CI->pdf->Cell( 65,20,"",0,1,"C",0);
           $CI->pdf->Cell(92,20,"",0,0,"C",0);
        $CI->pdf->Cell( 65,20,"",0,0,"C",0);
        $CI->pdf->Cell( 65,20,"",0,1,"C",0);
        $CI->pdf->Cell(60,5, "--------------------------------",0,0,"C",0);
        $CI->pdf->Cell(60,5, "--------------------------------",0,0,"C",0);
        $CI->pdf->Cell( 60,5,"--------------------------------",0,1,"C",0);
        $CI->pdf->Cell( 60,5,"SOLICITANTE",0,0,"C",0);
        $CI->pdf->Cell( 60,5,"VoBo JEFE DE AREA",0,0,"C",0);
     //       $CI->pdf->Cell(92,5,$sol,0,0,"C",0);
        $CI->pdf->Cell( 60,5,"VoBo GERENTE Y/O VoBo LOGISTICA",0,1,"C",0);
     //       $CI->pdf->Cell(92,5,$sol,0,0,"C",0);
        $CI->pdf->Cell( 60,5,$soliper,0,0,"C",0);
        $CI->pdf->Cell( 60,5,"",0,1,"C",0);
          $CI->pdf->Cell( 92,25,"NOTA: EL PRECIO Y EL PESO TOTAL SON ESTIMADOS, LOS PRECIOS SON REFERENCIALES Y NO INCLUYEN IGV",0,1,"l",0);   
             $CI->pdf->Output();
        }

        public function export_excel($type){
            if($this->session->userdata('data_'.$type)){
                $result = $this->session->userdata('data_'.$type);
                switch ($type) {
                    case 'listar_requisiciones_ot':
                        $arr_columns = array();
                        $arr_export_detalle = array();
                        $arr_columns[]['STRING'] = 'TIPO';
                        $arr_columns[]['STRING'] = 'NUM. REQ.';
                        $arr_columns[]['STRING'] = 'OT.';
                        $arr_columns[]['STRING'] = 'RESPONSABLE';
                        $arr_columns[]['STRING'] = 'DPTO';
                        $arr_columns[]['STRING'] = 'CODIGO';
                        $arr_columns[]['STRING'] =  'ALMACEN';
                        $arr_columns[]['STRING'] =  'MATERIAL';
                        $arr_columns[]['STRING'] =  'FAMILIA';
                        $arr_columns[]['STRING'] = 'PRODUCTO';
                        $arr_columns[]['STRING'] = 'UNIDAD';
                        $arr_columns[]['NUMERIC'] = 'SOLICITADO';
                        $arr_columns[]['NUMERIC'] = 'COMPRADO';
                        $arr_columns[]['NUMERIC'] = 'ALMACENADO';
                        $arr_columns[]['NUMERIC'] = 'ATENDIDO';
                        $arr_columns[]['NUMERIC'] = 'STOCK';
                        $arr_columns[]['NUMERIC'] = 'STOCK TRANS';
                        $arr_columns[]['NUMERIC'] = 'STOCK COMPROM';
                        $arr_columns[]['NUMERIC'] = 'STOCK DISP';
                        $arr_columns[]['NUMERIC'] = 'PESO SOLIC.';
                        $arr_columns[]['NUMERIC'] = 'PESO ATENDIDO';
                        $arr_columns[]['NUMERIC'] = 'PRECIO TOTAL SOLI S/.';
                        $arr_columns[]['NUMERIC'] = 'PRECIO TOTAL COMP S/.';
                        $arr_columns[]['NUMERIC'] = 'PRECIO TOTAL ATEND S/.';
                        $arr_columns[]['DATE'] = 'FECHA EMISION REQ.';
                        $arr_columns[]['DATE'] = 'FECHA APROBACION REQ.';
                        $arr_columns[]['DATE'] = 'FECHA SOLI OC.';
                        $arr_columns[]['DATE'] = 'FECHA OC.';
                        $arr_columns[]['DATE'] = 'FECHA APROBACION ORDENC.'; 
                        $arr_columns[]['DATE'] = 'FECHA ING.ALMACEN.';  
                        $arr_columns[]['DATE'] = 'FECHA REGISTRO.ALMACEN.'; 
                        $arr_columns[]['DATE'] = 'FECHA VALE'; 
                        $arr_columns[]['STRING'] = 'PROCESO DE COMPRA';
                        $arr_columns[]['STRING'] = 'SC.';
                        $arr_columns[]['STRING'] = 'OC.';
                        $arr_columns[]['STRING'] = 'NEA';
                        $arr_columns[]['STRING'] = 'VALE SALIDA';
                        $arr_columns[]['STRING'] = 'OBSERVACION';
                        $arr_columns[]['STRING'] = 'RUC';
                        $arr_columns[]['STRING'] = 'RAZON SOCIAL';
                        $arr_group = array('A5:L5'=>'GENERALES');
                        $this->reports_model->rpt_general('rpt_'.$type,'Requisiciones por OT', $arr_columns, $result["rows"],$arr_group);
                        break;         
                    case 'listar_req_ot_corto':
                        $arr_columns = array();
                        $arr_export_detalle = array();
                        $simbolo = "S/.";
                        $arr_columns[]['STRING']  = 'O.T.';
                        $arr_columns[]['STRING']  = 'T.PRODUCTO';
                        $arr_columns[]['STRING']  = 'Numero Req';
                        $arr_columns[]['DATE']    = 'Fecha Req';
                        $arr_columns[]['STRING']  = 'Dpto';
                        $arr_columns[]['STRING']  = 'Codigo';
                        $arr_columns[]['STRING']  = 'Familia';
                        $arr_columns[]['STRING']  = 'Producto';
                        $arr_columns[]['STRING']  = 'Unidad';
                        $arr_columns[]['NUMERIC'] = 'Cantidad Solic.';
                        $arr_columns[]['NUMERIC'] = 'Precio '.".$simbolo.";
                        $arr_columns[]['NUMERIC'] = 'Total '. ".$simbolo.";
                        $arr_columns[]['NUMERIC'] = 'Peso(Kg)';
                        $arr_columns[]['STRING'] = 'Responsable';
                        $arr_columns[]['STRING'] = 'Observacion';
                        $arr_group = array();
                        $this->reports_model->rpt_general('rpt_'.$type,'Requisiciones por OT corto',$arr_columns,$result["rows"],$arr_group);                          
                        break;
                }
            }
            else{
                echo "<div style='color:rgb(150,150,150);padding:10px;width:560px;height:160px;border:1px solid rgb(210,210,210);'>
                No hay datos para exportar
                </div>";
            }
        }
        
        
}
?>
