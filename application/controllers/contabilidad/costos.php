<?php header("Content-type: text/html; charset=utf-8"); if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Content-type: text/html; charset=utf-8");
require_once "Spreadsheet/Excel/Writer.php";
class Costos extends CI_Controller {
    var $entidad;
   
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");       
        $this->load->model(maestros.'periodoot_model');
        $this->load->model(maestros.'tipoproducto_model');
        $this->load->model(maestros.'tipoproducto_old_model');
        $this->load->model(maestros.'tipodocumento_caja_model');
        $this->load->model(maestros.'estadoot_model');
        $this->load->model(maestros.'proyecto_model');
        $this->load->model(maestros.'tvoucher_model');
        $this->load->model(maestros.'centrocosto_model'); 
        $this->load->model(maestros.'ttorre_model');
        $this->load->model(balanza.'constancia_model');
        $this->load->model(almacen.'nsalida_model');
        $this->load->model(almacen.'servicio_model');
        $this->load->model(almacen.'producto_model');
        $this->load->model(ventas.'ot_model');
        $this->load->model(contabilidad.'vale_salida_model');
        $this->load->model(compras.'requiser_model');
        $this->load->model(compras.'proveedor_model');
        $this->load->model(compras.'facturac_model');
        $this->load->model(personal.'responsable_model');
        $this->load->model(produccion.'tareo_model');
        $this->load->model(finanzas.'voucher_model');
        $this->load->model(finanzas.'caja_model');
        $this->load->model(ventas.'cliente_model');
        $this->load->model(ventas.'ctrlobras_model');
        $this->load->model(ventas.'partida_model');
        $this->load->model(siddex.'parte_model');  
        $this->load->model(siddex.'listamat_model');  
        $this->load->helper('costos');
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');
    }
    
    public function index()
    {
        redirect("contabilidad/costos/rpt_costoot");    
    }

    public function rpt_costoot(){
        $botonExcel   = $this->input->get_post('botonExcel');
        $botonPdf     = $this->input->get_post('botonPdf');
        $tipoexport   = $this->input->get_post('tipoexport');
        $tipOt        = $this->input->get_post('tipot'); 
        $codproyecto  = $this->input->get_post('codproyecto'); 
        $tiproducto   = $this->input->get_post('tiproducto'); 
        $estado       = $this->input->get_post('estado'); 
        $moneda       = $this->input->get_post('moneda'); 
        $ot           = $this->input->get_post('ot'); 
        $tipo_reporte = '';
        if($tiproducto!=''){
            $var_ot_letra = $this->tipoproducto_model->getLetra($tiproducto);    
        }
        else{
            $var_ot_letra = 'X';
        }
        //$this->input->get_post('tipo_reporte'); 
        $fecha_ini    = $this->input->get_post('fecha_ini'); 
        $fecha_fin    = $this->input->get_post('fecha_fin'); 
        $hora_actual  = date("H:i:s",time()-3600);
        $fecha_actual = date("d/m/Y",time());
        if($tipOt=="")        $tipOt        = 18;
        if($estado=="")       $estado       = '000';
        if($moneda=="")       $moneda       = 'S';
        if($tipo_reporte=="") $tipo_reporte = 'G';
        if($fecha_ini=="")    $fecha_ini    = date("d/m/Y", strtotime('-1 month'));
        if($fecha_fin=="")    $fecha_fin    = date("d/m/Y",time());    
        $fila     = "";
        $cadenaot = "";
        $tipoex   = "";
        $j        = 0;
        $arrfini  = explode("/",$fecha_ini);
        $arrffin  = explode("/",$fecha_fin);
        $fecha_ini_dbf = $arrfini[1]."/".$arrfini[0]."/".$arrfini[2];
        $fecha_fin_dbf = $arrffin[1]."/".$arrffin[0]."/".$arrffin[2];
        $cfecha_ini    = str_replace("/","",$fecha_ini);
        $cfecha_fin    = str_replace("/","",$fecha_fin);
        $selecttipoot  = form_dropdown('tipot',$this->periodoot_model->seleccionar("::Seleccione:::",""),$tipOt," size='1' id='tipot' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#frmBusqueda').attr('action','');$('#tipoexport').val('');$('#numero').val('');\" ");               
        $selectipoproducto = form_dropdown('tiproducto',$this->tipoproducto_model->seleccionar2(new stdClass(),"valor_2","::Seleccione:::",""),$tiproducto," size='1' id='tiproducto' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#frmBusqueda').attr('action','');$('#tipoexport').val('');$('#numero').val('');\" ");               
        $selproyecto       = form_dropdown('codproyecto',$this->proyecto_model->seleccionar("::Seleccione:::","000"),$codproyecto," size='1' id='codproyecto' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#frmBusqueda').attr('action','');$('#tipoexport').val('');$('#numero').val('');\" ");               
        $selecestado       = form_dropdown('estado',$this->estadoot_model->seleccionar("::Seleccione:::","000"),$estado," size='1' id='estado' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#frmBusqueda').attr('action','');$('#tipoexport').val('');$('#numero').val('');\" ");               
        $selmoneda         = form_dropdown('moneda',array(""=>"::Seleccione:::","S"=>"SOLES","D"=>"DOLARES"),$moneda," size='1' id='moneda' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#frmBusqueda').attr('action','');$('#tipoexport').val('');$('#numero').val('');\" ");               
        $this->form_validation->set_rules('tipot','Tipo OT','required');
        if($this->form_validation->run() == TRUE){
            $arrMateriales  = array();
            $arrMaterialesD = array();
            $arrServicio    = array();
            $arrMO   = array();
            $arrCaja = array();
            $arrSub  = array();
            $arrTrans = array();
            $arrOtros = array();
            $arrGalv  = array();
            /*Se extrae las Materias primas por OT y se ingresan en un array*/
            $filter3 = new stdClass();
            $filter3->tipoot   = $tipOt;
            $filter3->fechai   = $fecha_ini;
            $filter3->fechaf   = $fecha_fin;
            $filter3->moneda   = $moneda;
            $filter3->group_by = array("k.Codot");
            $oMateriales = costomateriales($filter3);
            /*Se extraen las requisiciones de servicio y se ingresan en un array*/
            $filter6    = new stdClass();
            $filternot6 = new stdClass();
            $filter6->fechai = $fecha_ini;
            $filter6->fechaf = $fecha_fin;   
            $filter6->moneda = $moneda;   
            $filternot6->codservicio = array('000000000010','000000000002','000000000074','000000000057','000000000001','000000000086','000000000084','000000000048','000000000049','000000000047','000000000046','000000000071');
            $oService = costoservicios($filter6,$filternot6);
            /*Se extraen las requisiciones de servicio de TRASNPORTE y se ingresan en un array(DBF)*/
            $filter7    = new stdClass();
            $filternot7 = new stdClass();
            $filter7->fechai = $fecha_ini;
            $filter7->fechaf = $fecha_fin;     
            $filter7->moneda = $moneda;  
            $filter7->codservicio = array('000000000010','000000000002','000000000074','000000000057','000000000001','000000000086','000000000084','000000000048','000000000049','000000000047','000000000046','000000000071');
            $oTransport = costoservicios($filter7,$filternot7);   
            /*Matriz mano de obra*/
            $filter8         = new stdClass();
            $filter8->fechai = $fecha_ini;
            $filter8->fechaf = $fecha_fin;
            $filter8->moneda = $moneda;  
            $filter8->group_by = (substr($fecha_fin,6,4)<= 2013)?array("c.nroot"):array('p.numeroorden');
            $oManoObra       = costomanoobra($filter8,new stdClass());    
            /*Matriz caja chica*/
            $filter8           = new stdClass();
            $filter8->fechai   = $fecha_ini;
            $filter8->fechaf   = $fecha_fin;
            $filter8->moneda   = $moneda;  
            $filter8->group_by = array("det.codot");
            $oCaja   = costocaja($filter8,new stdClass()); 
            /*Matriz de tesoreria*/
            $filter8 = new stdClass();
            $filter8_not = new stdClass();
            $filter8->fechai   = $fecha_ini;
            $filter8->fechaf   = $fecha_fin;
            $filter8->moneda   = $moneda;
            $filter8->group_by = array("det.codot");
            $filter8_not->codtipomov = array('03','19','02','08');
            $oTesoreria        = costotesoreria($filter8,$filter8_not); 
            /*Matriz de subcontratos*/
            $filter8 = new stdClass();
            $filter8_not = new stdClass();
            $filter8->fechai  = $fecha_ini;
            $filter8->fechaf  = $fecha_fin;
            $filter8->moneda  = $moneda;
            $filter8->codpartida = '03';
            $filter8->group_by = array("det.codot");
            $oSubcontrato        = costotesoreria($filter8,$filter8_not); 
            /*Matriz de costo residente*/    
            $filter8 = new stdClass();
            $filter8_not = new stdClass();
            $filter8->fechai  = $fecha_ini;
            $filter8->fechaf  = $fecha_fin;
            $filter8->moneda  = $moneda;
            $filter8->codpartida = '06';
            $filter8->group_by = array("det.codot");
            $oResidente  = costotesoreria($filter8,$filter8_not);   
            /*Matriz de estudios y proyectos*/
            $filter8 = new stdClass();
            $filter8_not = new stdClass();
            $filter8->fechai  = $fecha_ini;
            $filter8->fechaf  = $fecha_fin;
            $filter8->moneda  = $moneda;
            $filter8->codpartida = '07';
            $filter8->group_by = array("det.codot");
            $oEstudios = costotesoreria($filter8,$filter8_not);  
            /*Matriz Administracion directa*/
            $filter8 = new stdClass();
            $filter8_not = new stdClass();
            $filter8->fechai  = $fecha_ini;
            $filter8->fechaf  = $fecha_fin;
            $filter8->moneda  = $moneda;
            $filter8->codpartida = '04';
            $filter8->group_by = array("det.codot");
            $oAdministracion = costotesoreria($filter8,$filter8_not);  
            /*Contingencia*/
            $filter8 = new stdClass();
            $filter8_not = new stdClass();
            $filter8->fechai  = $fecha_ini;
            $filter8->fechaf  = $fecha_fin;
            $filter8->moneda  = $moneda;
            $filter8->codpartida = '10';
            $filter8->group_by = array("det.codot");
            $oContingencia = costotesoreria($filter8,$filter8_not);  
            /*Matriz de Otros Costos Directos*/    
            $filter8 = new stdClass();
            $filter8_not = new stdClass();
            $filter8->fechai  = $fecha_ini;
            $filter8->fechaf  = $fecha_fin;
            $filter8->moneda  = $moneda;
            $filter8->codpartida = '09';
            $filter8->group_by = array("det.codot");
            $oCostosDirectos = costotesoreria($filter8,$filter8_not);  
            /*Matriz Acarreo*/
            $filter8 = new stdClass();
            $filter8_not = new stdClass();
            $filter8->fechai  = $fecha_ini;
            $filter8->fechaf  = $fecha_fin;
            $filter8->moneda  = $moneda;
            $filter8->codpartida = '08';
            $filter8->group_by = array("det.codot");
            $oAcarreo = costotesoreria($filter8,$filter8_not);  
                
            /*Mostrar los resultados*/
            $g_materiales_totales = 0;
            $g_manoobra           = 0;
            $g_subcontrato        = 0;
            $g_costo_residente    = 0;
            $g_estudiosyproyectos = 0;
            $g_admin_directa      = 0;
            $g_otros_costos_directos = 0;
            $g_acarreo            = 0;
            $g_contingencia       = 0;
            $g_servicios          = 0;
            $g_transportes        = 0;
            $g_gastos_tesoreria   = 0;
            $g_caja_chica         = 0;
            $g_total              = 0;
            $g_presupuestado      = 0;
            $g_delta              = 0;
            $g_valor_venta        = 0;
            $g_galvanizado        = 0;
            /*Listado de OTs*/
            $filter = new stdClass();
            $filter->estado = $estado;
            $filter->fechai = "01/01/2012";
            $filter->fechaf = $fecha_fin;
            if($ot=='')       $filter->letra  = $var_ot_letra; 
            if($codproyecto!='000') $filter->codproyecto = $codproyecto;
            if($tipOt!='')          $filter->tipoot      = $tipOt;
            if($ot!='')             $filter->nroot       = $ot;            
            $ots = $this->ot_model->listarg($filter,array('ot.nroOt'=>'desc')); //USE MODEL : OT
            $arr_export_detalle = array();
            foreach($ots as $indice2 => $value2){
                $arr_data = array();
                $codot    = $value2->CodOt;
                $nroOt    = $value2->NroOt;
                $dirOt    = $value2->DirOt;
                $proyecto = $value2->Proyecto;
                $fecOt    = $value2->fecha;
                $finot    = $value2->FinOt;
                $mtoPre   = $value2->MtoPre;
                $tcOt     = $value2->tcOt;
                $impOt    = $value2->ImpOt;
                $monedaOt = $value2->EstOt;
                $fteOt    = $value2->FteOt;
                $peso     = $value2->peso;
                $impOt_soles   = $monedaOt==2?$impOt:($impOt*$tcOt);
                if ($tcOt ==0)  {
                    $tcOt = 1;
                }                    
                $impOt_dolares = $monedaOt==3?$impOt:($impOt/$tcOt);
                if($moneda=='S'){
                    $presupuestado = $mtoPre;
                    $valor_venta = $impOt_soles;
                }
                elseif($moneda=='D'){
                    $presupuestado = $mtoPre/$tcOt;
                    $valor_venta = $impOt_dolares;
                }
                $proyectos = $this->proyecto_model->obtener($proyecto); //USE MODEL : MAESTROS
                $nomproyecto = isset($proyectos->Des_Larga)?$proyectos->Des_Larga:'';

                /*MATERIALES PRIMA*/
                $materiales = @$oMateriales[trim($codot)]->costo;
                
                /*MANO DE OBRA*/
                $cod = str_replace("-","",trim($nroOt));
                $manoobra_real= @$oManoObra[$cod]->costo;
              
                /*SERVICIOS DIRECTOS*/
                $servicios= @$oService[trim($codot)]->costo;
                
                /*TRANSPORTE*/                
                $transportes= @$oTransport[trim($codot)]->costo;
                
                /*CAJA CHICA*/                
                $caja_chica= @$oCaja[trim($codot)]->costo;
                
                /*SUBCONTRATOS*/
                $subcontrato= @$oSubcontrato[$codot]->costo;
                
                /*COSTOS DE RESIDENTE*/
                $costo_residente = @$oResidente[$codot]->costo;
                
                /*ESTUDIOS Y PROYECTOS*/
                $estudiosyproyectos = @$oEstudios[$codot]->costo;
                
                /*ADMINISTRACION DIRECTA*/
                $admin_directa = @$oAdministracion[$codot]->costo;
                
                /*OTROS COSTOS DIRECTOS*/
                $otros_costos_directos = @$oCostosDirectos[$codot]->costo;
                
                /*ACARREO Y TRASNPORTE*/
                $acarreo = @$oAcarreo[$codot]->costo;
                if($tiproducto!='02')  $acarreo = $acarreo + $transportes;
                if($tiproducto!='02')  $transportes = 0;
                
                /*CONTINGENCIA*/
                $contingencia = @$oContingencia[$codot]->costo;
                
                /*GASTOS DE TESORERIA*/
                $gastos_tesoreria = @$oTesoreria[$codot]->costo;
                
                /*GASTOS POR GALVANIZADO POR OT*/
                $galvanizado     = "";
                $arrnumero       = explode("-",trim($nroOt));
                $filter2         = new stdClass();
                $filter2_not     = new stdClass();
                $filter2_not->estado = "A";
                $filter2->codot  = $codot;
                //$filter2->nroot  = $arrnumero[0]."-".(strlen($arrnumero[1])==5?substr($arrnumero[1],0,4)."-".substr($arrnumero[1],4,1):substr($arrnumero[1],0,4)); 
                $filter2->fechai = $fecha_ini;
                $filter2->fechaf = $fecha_fin;                
                $constancias     = $this->constancia_model->listar_totales($filter2,$filter2_not);
                if(count($constancias)>0){
                    if($moneda=='S'){
                        $galvanizado    = isset($constancias->imp_soles)?$constancias->imp_soles:0;
                    }
                    elseif($moneda=='D'){
                        $galvanizado    = isset($constancias->imp_dolares)?$constancias->imp_dolares:0;
                    }
                }                
                
                /*TOTAL*/
                $total = $materiales + $manoobra_real + $subcontrato + $costo_residente + $estudiosyproyectos + $admin_directa + $otros_costos_directos + $acarreo + $contingencia + $servicios + $transportes + $caja_chica + $gastos_tesoreria + $galvanizado;
                if($total!=0)  $cadenaot.= $codot.",";  
                $delta = $valor_venta-$total;
                $color = $delta<0?"color='#FF0000'":"";
                
                if ($total>0){
                    $fila .= "<tbody>";
                    $fila .= "<tr class='rpt_costodetalle' id='".$codot."'>";
                    $arr_data[] =$codot;
                    $fila .= "<td><div id='".trim($codot)."'><a href='#' onclick='ver_ot(this);'>".$nroOt."</a></div></td>";
                    $arr_data[] = $nroOt;
                    $fila .= "<td align='left'>".utf8_encode($dirOt)."</td>";
                    $arr_data[] = utf8_encode($dirOt);
                    $fila .= "<td align='left'>".utf8_encode($nomproyecto)."</td>";
                    $arr_data[] = utf8_encode($nomproyecto);
                    $fila .= "<td>".$finot."</td>";
                    $arr_data[] = $finot;
                    $fila .= "<td>".$fteOt."</td>";
                    $arr_data[] = $fteOt;
                    $fila .= "<td class='pa01' id='01' id2='1'  style='display:none;' align='right'>".($materiales==''?'-':"<a href='#' onclick='rpt_materiales(this);'>".number_format($materiales,2))."</a></td>";                    
                    if(substr($fteOt,6,4)<= 2013){
                        $fila .= "<td class='pa02' id='02' style='display:none;' align='right'>".($manoobra_real==0?'-':"<a href='#' onclick='rpt_manoobra(this);'>".number_format($manoobra_real,2))."</a></td>";   
                    }
                    else{
                        $fila .= "<td class='pa02' id='02' style='display:none;' align='right'>".($manoobra_real==0?'-':"<a href='#' onclick='rpt_manoobrasiddex(this);'>".number_format($manoobra_real,2))."</a></td>";
                    }                    
                    $fila .= "<td class='pa03' id='03' style='display:none;' align='right'>".($subcontrato==''?'-':"<a href='#' onclick='rpt_tesoreria(this);'>".number_format($subcontrato,2))."</a></td>";
                    $fila .= "<td class='pa06' id='06' style='display:none;' align='right'>".($costo_residente==''?'-':"<a href='#' onclick='rpt_tesoreria(this);'>".number_format($costo_residente,2))."</a></td>";
                    $fila .= "<td class='pa07' id='07' style='display:none;' align='right'>".($estudiosyproyectos==''?'-':"<a href='#' onclick='rpt_tesoreria(this);'>".number_format($estudiosyproyectos,2))."</a></td>";
                    $fila .= "<td class='pa04' id='04' style='display:none;' align='right'>".($admin_directa==''?'-':"<a href='#' onclick='rpt_tesoreria(this);'>".number_format($admin_directa,2))."</a></td>";
                    $fila .= "<td class='pa09' id='09' style='display:none;' align='right'>".($otros_costos_directos==0?'&nbsp;':"<a href='#' onclick='rpt_tesoreria(this);'>".number_format($otros_costos_directos,2))."</a></td>";
                    $fila .= "<td class='pa08' id='08' style='display:none;' align='right'>".($acarreo==0?'-':"<a href='#' onclick='rpt_tesoreria(this);'>".number_format($acarreo,2))."</a></td>";
                    $fila .= "<td class='pa10' id='10' style='display:none;' align='right'>".($contingencia==0?'-':"<a href='#' onclick='rpt_tesoreria(this);'>".number_format($contingencia,2))."</a></td>";
                    $fila .= "<td class='pa11' id='11' style='display:none;' align='right'>".($servicios==''?'-':"<a href='#' onclick='rpt_servicios(this);'>".number_format($servicios,2))."</a></td>";
                    $fila .= "<td class='pa12' id='12' style='display:none;' align='right'>".($transportes==''?'-':"<a href='#' onclick='rpt_transportes(this);'>".number_format($transportes,2))."</a></td>";                        
                    $fila .= "<td class='pa15' id='15' style='display:none;' align='right'>".($galvanizado==''?'-':"<a href='#' onclick='rpt_galva(this);'>".number_format($galvanizado,2))."</a></td>";   
                    $fila .= "<td class='pa13' id='13' style='display:none;' align='right'>".($gastos_tesoreria==''?'-':"<a href='#' onclick='rpt_tesoreria(this);'>".number_format($gastos_tesoreria,2))."</a></td>";
                    $fila .= "<td class='pa14' id='14' style='display:none;' align='right'>".($caja_chica==0?'-':"<a href='#' onclick='rpt_caja(this);'>".number_format($caja_chica,2))."</a></td>";
                    $fila .= "<td align='right'><font ".$color.">".number_format($valor_venta,2)."</font></td>";
                    $arr_data[] = number_format($valor_venta,2);
                    $fila .= "<td align='right'><font ".$color.">".number_format($total,2)."</font></td>";
                    $arr_data[] = number_format($total,2);
                    $fila .= "<td align='right'><font ".$color.">".number_format($delta,2)."</font></td>";
                    $arr_data[] = number_format($total,2);
                    $fila .= "</tr>";
                    $fila .= "</tbody>";
                    array_push($arr_export_detalle,$arr_data);
                }
                //$var_export = array('rows' => $arr_export_detalle);
                //$this->session->set_userdata('data_costos_x_ot', $var_export);
                $j++;
                $g_materiales_totales = $g_materiales_totales + $materiales;
                $g_manoobra           = $g_manoobra + $manoobra_real;
                $g_subcontrato        = $g_subcontrato + $subcontrato;
                $g_costo_residente    = $g_costo_residente + $costo_residente;
                $g_estudiosyproyectos = $g_estudiosyproyectos + $estudiosyproyectos;
                $g_admin_directa      = $g_admin_directa + $admin_directa;
                $g_otros_costos_directos = $g_otros_costos_directos + $otros_costos_directos;
                $g_acarreo               = $g_acarreo + $acarreo;
                $g_contingencia          = $g_contingencia + $contingencia;
                $g_servicios             = $g_servicios + $servicios;
                $g_transportes           = $g_transportes + $transportes;
                $g_gastos_tesoreria      = $g_gastos_tesoreria + $gastos_tesoreria;
                $g_caja_chica            = $g_caja_chica + $caja_chica;
                $g_presupuestado         = $g_presupuestado + $presupuestado;
                $g_total                 = $g_total + $total;
                $g_delta                 = $g_delta + $delta;
                $g_valor_venta           = $g_valor_venta + $valor_venta;
                $g_galvanizado           = $g_galvanizado + $galvanizado;
                //echo $nroOt."-".$galvanizado."<br>";
            }
            
           /* $cur_moindirecta = $this->tareo_model->getMonto($fecha_ini,$fecha_fin);
            $str_cc = "";
            foreach($cur_moindirecta as $ind => $val_cc){
                $codigo = $val_cc->codot;
                $nombre = $val_cc->nroot;
                $dir = utf8_encode($val_cc->dirot);
                $monto = $val_cc->monto;
                $str_cc .= "<tr><td>".$codigo."</td>";
                $str_cc .= "<td>".$nombre."</td>";
                $str_cc .= "<td>".$dir."</td>";
                $str_cc .= "<td>".$monto."</td></tr>";
            }*/

            $cadenaot = substr($cadenaot,0,strlen($cadenaot)-1);
            $fila .= "<tfoot><tr>";
            $fila .= "<td colspan='5'>&nbsp;</td>";
            $fila .= "<td align='right' class='pa01' id='01' style='display:none;'><a href='#' onclick='rpt_materialest(this);'>".number_format($g_materiales_totales,2)."</a></td>";
            $fila .= "<td align='right' class='pa02' id='02' style='display:none;'><a href='#' onclick='rpt_manoobrat(this);'>".number_format($g_manoobra,2)."</a></td>";
            $fila .= "<td align='right' class='pa03' id='03' style='display:none;'><a href='#' onclick='rpt_tesoreria(this);'>".number_format($g_subcontrato,2)."</a></td>";
            $fila .= "<td align='right' class='pa06' id='06' style='display:none;'><a href='#' onclick='rpt_tesoreria(this);'>".number_format($g_costo_residente,2)."</a></td>";
            $fila .= "<td align='right' class='pa07' id='07' style='display:none;'><a href='#' onclick='rpt_tesoreria(this);'>".number_format($g_estudiosyproyectos,2)."</a></td>";
            $fila .= "<td align='right' class='pa04' id='04' style='display:none;'><a href='#' onclick='rpt_tesoreria(this);'>".number_format($g_admin_directa,2)."</a></td>";
            $fila .= "<td align='right' class='pa09' id='09' style='display:none;'><a href='#' onclick='rpt_tesoreria(this);'>".number_format($g_otros_costos_directos,2)."</a></td>";
            $fila .= "<td align='right' class='pa08' id='08' style='display:none;'><a href='#' onclick='rpt_tesoreria(this);'>".number_format($g_acarreo,2)."</a></td>";    
            $fila .= "<td align='right' class='pa10' id='10' style='display:none;'><a href='#' onclick='rpt_tesoreria(this);'>".number_format($g_contingencia,2)."</a></td>";    
            $fila .= "<td align='right' class='pa11' id='11' style='display:none;'><a href='#' onclick='rpt_servicios(this);'>".number_format($g_servicios,2)."</a></td>";
            $fila .= "<td align='right' class='pa12' id='12' style='display:none;'><a href='#' onclick='rpt_transportest(this);'>".number_format($g_transportes,2)."</a></td>";                    
            $fila .= "<td align='right' class='pa15' id='15' style='display:none;'><a href='#' onclick='rpt_galvat(this);'>".number_format($g_galvanizado,2)."</a></td>"; 
            $fila .= "<td align='right' class='pa13' id='13' style='display:none;'><a href='#' onclick='rpt_tesoreria(this);'>".number_format($g_gastos_tesoreria,2)."</a></td>";
            $fila .= "<td align='right' class='pa14' id='14' style='display:none;'><a href='#' onclick='rpt_cajat(this);'>".number_format($g_caja_chica,2)."</a></td>";
            $arr_data[] ='';
            $arr_data[] ='';
            $arr_data[] ='';
            $arr_data[] ='';
            $arr_data[] ='';
            $arr_data[] ='TOTAL';
            $fila .= "<td align='right'><font>".number_format($g_valor_venta,2)."</font></td>";
            $arr_data[] = number_format($g_valor_venta,2);
            //$fila .= "<td align='right'><font>".number_format($g_presupuestado,2)."</font></td>";
            $fila .= "<td align='right'><font>".number_format($g_total,2)."</font></td>";
            $arr_data[] = number_format($g_total,2);
            $fila .= "<td align='right'><font>".number_format($g_delta,2)."</font></td>";
            $arr_data[] = number_format($g_delta,2);
            $fila .= "</tr></tfoot>";
            array_push($arr_export_detalle,$arr_data);
            $var_export = array('rows' => $arr_export_detalle);
            $this->session->set_userdata('data_costos_x_ot', $var_export);
        }
        
        /*
         * export to Excel
         */ 
        
        if($tipoexport == "excel0"){
            $arr_columns[0]['STRING'] = 'Nro';     
            $arr_columns[1]['STRING'] = 'Nombre';        
            $arr_columns[2]['STRING'] = 'Proyecto';        
            $arr_columns[3]['DATE'] = 'F. Inicio';        
            $arr_columns[4]['DATE'] = 'F. Termino';        
            $arr_columns[5]['NUMERIC'] = 'Materiales';        
            $arr_columns[6]['NUMERIC'] = 'M.O. Directa';
            if($tiproducto!='02'){
                $arr_columns[7]['NUMERIC'] = 'Subcontratos';
                $arr_columns[8]['NUMERIC'] = 'Costo Residente';
                $arr_columns[9]['NUMERIC'] = 'Estudios y Proyectos';
                $arr_columns[10]['NUMERIC'] = 'Adm. Directa';
                $arr_columns[11]['NUMERIC'] = 'Otros Costos Directos';
                $arr_columns[12]['NUMERIC'] = 'Transporte';
                $arr_columns[13]['NUMERIC'] = 'Contingencia';
                //$arr_columns[14]['NUMERIC'] = 'Galvanizado';
                $arr_columns[14]['NUMERIC'] = 'Gasto Tesoreria';
                $arr_columns[15]['NUMERIC'] = 'Caja Chica';
                $arr_columns[16]['NUMERIC'] = 'Valor Venta';
                $arr_columns[17]['NUMERIC'] = 'Costo Total';
                $arr_columns[18]['NUMERIC'] = 'Delta';
            }else{  
                $arr_columns[7]['NUMERIC'] = 'Serv. Directo';
                $arr_columns[8]['NUMERIC'] = 'Transporte';
                $arr_columns[9]['NUMERIC'] = 'Galvanizado';
                $arr_columns[10]['NUMERIC'] = 'Gasto Tesoreria';
                $arr_columns[11]['NUMERIC'] = 'Caja Chica';
                $arr_columns[12]['NUMERIC'] = 'Valor Venta';
                $arr_columns[13]['NUMERIC'] = 'Costo Total';
                $arr_columns[14]['NUMERIC'] = 'Delta'; 
          }        

        $arr_data    = array();
        $var_prd_n = 0;
        $var_row = 7;
        
             foreach($ots as $indice2 => $value2){
                $codot = $value2->CodOt;
                $nroOt = $value2->NroOt;
                $dirOt = $value2->DirOt;
                $proyecto = $value2->Proyecto;
                $fecOt    = $value2->fecha;
                $mtoPre   = $value2->MtoPre;
                $tcOt     = $value2->tcOt;
                $impOt    = $value2->ImpOt;
                $monedaOt = $value2->EstOt;
                $fteOt    = $value2->FteOt;
                $peso     = $value2->peso;

                $impOt_soles   = $monedaOt==2?$impOt:($impOt*$tcOt);
                $impOt_dolares = $monedaOt==3?$impOt:($impOt/$tcOt);
                if($moneda=='S'){
                    $presupuestado = $mtoPre;
                    $valor_venta = $impOt_soles;
                }
                elseif($moneda=='D'){
                    $presupuestado = $mtoPre/$tcOt;
                    $valor_venta = $impOt_dolares;
                }
                $proyectos = $this->proyecto_model->obtener($proyecto);
                $nomproyecto = $proyectos->Des_Larga;

                /*Extraemos los TOTAL MATERIALES primas del array*/
                $materiales  = "";
                $materialesD = "";
                foreach($arrMateriales as $id=>$value){
                    if(trim($id)==$codot) {$materiales=$value;break;}
                }
                foreach($arrMaterialesD as $id=>$value){
                    if(trim($id)==$codot) {$materialesD=$value;break;}
                }
                /*Materiales auxiliares*/
                $materiales_auxiliares  = "";
                $materiales_auxiliaresD = "";
                foreach($arrMaterialesAuxiliares as $id=>$value){
                    if(trim($id)==trim($codot)) {$materiales_auxiliares=$value;break;}
                }
                foreach($arrMaterialesAuxiliaresD as $id=>$value){
                    if(trim($id)==trim($codot)) {$materiales_auxiliaresD=$value;break;}
                }
                /*Suministros*/
                $suministros  = "";
                $suministrosD = "";
                foreach($arrSuministros as $id=>$value){
                    if(trim($id)==$codot) {$suministros=$value;break;}
                }
                foreach($arrSuministrosD as $id=>$value){
                    if(trim($id)==$codot) {$suministrosD=$value;break;}
                }
                /*Total de materiales*/
                if($moneda=='S'){
                    $materiales_totales = $materiales + $materiales_auxiliares + $suministros;
                }
                elseif($moneda=='D'){
                    $materiales_totales = $materialesD + $materiales_auxiliaresD + $suministrosD;
                }
                
                /*MANO DE OBRA*/
                $manoobra        = 0;
                $manoobra_real   = 0;
                $manoobraD       = 0;
                $manoobra_realD  = 0;
                $filter8         = new stdClass();
                $filter8->codot  = $codot;
                $filter8->fechai = $fecha_ini;
                $filter8->fechaf = $fecha_fin;
                $filter8->flagtareado = 1;
                $filter8->group_by    = array("a.codot");
                $oManoObra       = $this->tareo_model->listar_totales($filter8,new stdClass());
                if(count($oManoObra)>0){
                    if($moneda=='S'){
                        $manoobra       = $oManoObra[0]->simple;
                        $manoobra_real  = $oManoObra[0]->real;
                    }
                    elseif($moneda=='D'){
                        $manoobra      = $oManoObra[0]->simpleD;
                        $manoobra_real = $oManoObra[0]->realD;
                    }
                }

              /*SERVICIOS DIRECTOS*/
                $servicios= "";
                if(count($arrServicio)>0){
                    foreach($arrServicio as $id=>$value)
                    {
                        if($id==$codot)
                        {
                            $servicios=$value;
                            break;
                        }
                    }                    
                }
                $servicios1 = 0;
                $servicios2 = 0;
                $servicios3 = 0;
                foreach($arrServicio as $id=>$value){
                    if($id==$codot) {
                        foreach($value as $id2=>$value2){
                            if($id2=='01') $servicios1 = $value2;
                            if($id2=='02') $servicios2 = $value2;
                            if($id2=='03') $servicios3 = $value2;
                        }
                    }
                }
                $servicios = $servicios1 + $servicios2 + $servicios3;
                
                /*TRANSPORTE*/
                $transportes = "";
                
                if(isset($arrServicioTrans))
                if(count($arrServicioTrans)>0){
                    foreach($arrServicioTrans as $id=>$value){
                        if($id==$codot) {
                            $transportes = 0;
                            foreach($value as $id2=>$value2){
                                $transportes = $transportes + $value2;
                            }
                        }
                    }
                }   
                
                else
                {
                    $transportes = 0;
                }
                
                /*CAJA CHICA*/
                $caja_chica = 0;
                $filter16   = new stdClass();
                $filter16->codot   = $codot;
                $filter16->fechai  = $fecha_ini;
                $filter16->fechaf  = $fecha_fin;
                $filter16->group_by = array("det.codot");
                $oCaja = $this->caja_model->listar_totales($filter16,new stdClass());
                if(count($oCaja)>0){
                    if($moneda=='S'){
                        $caja_chica = $oCaja[0]->subSoles;
                    }
                    elseif($moneda=='D'){
                        $caja_chica = $oCaja[0]->subDolar;
                    } 
                }                
                
                /*SUBCONTRATOS*/
                $subcontrato = "";
                $filter9 = new stdClass();
                $filter9->codot   = $codot;
                $filter9->fechai  = $fecha_ini;
                $filter9->fechaf  = $fecha_fin;
                $filter9->codpartida = '03';
                $oSubcontrato = $this->voucher_model->listar_totales($filter9,new stdClass());
                if(count($oSubcontrato)>0){
                    if($moneda=='S'){
                        $subcontrato = $oSubcontrato[0]->ImpSoles;
                    }
                    elseif($moneda=='D'){
                        $subcontrato = $oSubcontrato[0]->ImpDolares;
                    }
                }

                /*COSTOS DE RESIDENTE*/
                $costo_residente = "";
                $filter10 = new stdClass();
                $filter10->codot   = $codot;
                $filter10->fechai  = $fecha_ini;
                $filter10->fechaf  = $fecha_fin;
                $filter10->codpartida = '06';
                $oResidente = $this->voucher_model->listar_totales($filter10,new stdClass());
                if(count($oResidente)>0){
                    if($moneda=='S'){
                        $costo_residente = $oResidente[0]->ImpSoles;
                    }
                    elseif($moneda=='D'){
                        $costo_residente = $oResidente[0]->ImpDolares;
                    }
                }

                /*ESTUDIOS Y PROYECTOS*/
                $estudiosyproyectos = "";
                $filter11 = new stdClass();
                $filter11->codot   = $codot;
                $filter11->fechai  = $fecha_ini;
                $filter11->fechaf  = $fecha_fin;
                $filter11->codpartida = '07';
                $oEstudios = $this->voucher_model->listar_totales($filter11,new stdClass());
                if(count($oEstudios)>0){
                    if($moneda=='S'){
                        $estudiosyproyectos = $oEstudios[0]->ImpSoles;
                    }
                    elseif($moneda=='D'){
                        $estudiosyproyectos = $oEstudios[0]->ImpDolares;
                    }
                }

                /*ADMINISTRACION DIRECTA*/
                $admin_directa = "";
                $filter12 = new stdClass();
                $filter12->codot   = $codot;
                $filter12->fechai  = $fecha_ini;
                $filter12->fechaf  = $fecha_fin;
                $filter12->codpartida = '04';
                $oAdministracion = $this->voucher_model->listar_totales($filter12,new stdClass());
                if(count($oAdministracion)>0){
                    if($moneda=='S'){
                        $admin_directa = $oAdministracion[0]->ImpSoles;
                    }
                    elseif($moneda=='D'){
                        $admin_directa = $oAdministracion[0]->ImpDolares;
                    }
                }

                /*OTROS COSTOS DIRECTOS*/
                $otros_costos_directos = "";
                $filter13 = new stdClass();
                $filter13->codot   = $codot;
                $filter13->fechai  = $fecha_ini;
                $filter13->fechaf  = $fecha_fin;
                $filter13->codpartida = '09';
                $oCostosDirectos = $this->voucher_model->listar_totales($filter13,new stdClass());
                if(count($oCostosDirectos)>0){
                    if($moneda=='S'){
                        $otros_costos_directos = $oCostosDirectos[0]->ImpSoles;
                    }
                    elseif($moneda=='D'){
                        $otros_costos_directos = $oCostosDirectos[0]->ImpDolares;
                    }
                }

                /*ACARREO Y TRASNPORTE*/
                $acarreo = "";
                $filter14 = new stdClass();
                $filter14->codot   = $codot;
                $filter14->fechai  = $fecha_ini;
                $filter14->fechaf  = $fecha_fin;
                $filter14->codpartida = '08';
                $oAcarreo = $this->voucher_model->listar_totales($filter14,new stdClass());
                if(count($oAcarreo)>0){
                    if($moneda=='S'){
                        $acarreo = $oAcarreo[0]->ImpSoles;
                    }
                    elseif($moneda=='D'){
                        $acarreo = $oAcarreo[0]->ImpDolares;
                    }
                }
                if($tiproducto!='02')  $acarreo = $acarreo + $transportes;
                if($tiproducto!='02')  $transportes = 0;
                
                /*CONTINGENCIA*/
                $contingencia = "";
                $filter17 = new stdClass();
                $filter17->codot   = $codot;
                $filter17->fechai  = $fecha_ini;
                $filter17->fechaf  = $fecha_fin;
                $filter17->codpartida = '10';
                $oContingencia = $this->voucher_model->listar_totales($filter17,new stdClass());
                if(count($oContingencia)>0){
                    if($moneda=='S'){
                        $contingencia = $oContingencia[0]->ImpSoles;
                    }
                    elseif($moneda=='D'){
                        $contingencia = $oContingencia[0]->ImpDolares;
                    }
                }   
                if($tiproducto!='02')  $contingencia = $contingencia + $servicios + $caja_chica;
                if($tiproducto!='02')  {$servicios    = 0;$caja_chica=0;}
                
                /*GASTOS DE TESORERIA*/
                $gastos_tesoreria = "";
                $filter15 = new stdClass();
                $filter15_not = new stdClass();
                $filter15->codot   = $codot;
                $filter15->fechai  = $fecha_ini;
                $filter15->fechaf  = $fecha_fin;
                $filter15_not->codpartida = array('03','06','07','04','09','08','10');
                $oOtrosTeso = $this->voucher_model->listar_totales($filter15,$filter15_not);
                if(count($oOtrosTeso)>0){
                    if($moneda=='S'){
                        $gastos_tesoreria = $oOtrosTeso[0]->ImpSoles;
                    }
                    elseif($moneda=='D'){
                        $gastos_tesoreria = $oOtrosTeso[0]->ImpDolares;
                    }
                }
                
                /*GASTOS POR GALVANIZADO*/
                $galvanizado = "";
                $arrnumero      = explode("-",trim($nroOt));
                $filter2        = new stdClass();
                $filter2_not    = new stdClass();
                $filter2_not->estado = "A";
                $filter2->nroot = $arrnumero[0]."-".(strlen($arrnumero[1])==5?substr($arrnumero[1],0,4)."-".substr($arrnumero[1],4,1):substr($arrnumero[1],0,4)); 
                $constancias    = $this->constancia_model->listar_totales($filter2,$filter2_not);
                if(count($constancias)>0){
                    if($moneda=='S'){
                        $galvanizado    = isset($constancias->imp_soles)?$constancias->imp_soles:0;
                    }
                    elseif($moneda=='D'){
                        $galvanizado    = isset($constancias->imp_dolares)?$constancias->imp_dolares:0;
                    }
                }                
                
                /*TOTAL*/
                $total = $materiales_totales + $manoobra_real + $subcontrato + $costo_residente + $estudiosyproyectos + $admin_directa + $otros_costos_directos + $acarreo + $contingencia + $servicios + $transportes + $caja_chica + $galvanizado;
                if($total!=0)  $cadenaot.= $codot.",";  
                $delta = $valor_venta-$total;
                $color = $delta<0?"color='#FF0000'":"";
                
                
                $total = $materiales_totales + $manoobra_real + $subcontrato + $costo_residente + $estudiosyproyectos + $admin_directa + $otros_costos_directos + $acarreo + $contingencia + $servicios + $transportes + $caja_chica + (($tiproducto=='02')?$galvanizado:0);
                
            $delta = $valor_venta-$total;
            $materiales_totalesok = ($materiales_totales==''?'':number_format($materiales_totales,2, '.', ''));
            $manoobra_realok = ($manoobra_real==0?'':number_format($manoobra_real,2, '.', ''));            
            $subcontratook = ($subcontrato==''?'':number_format($subcontrato,2, '.', ''));
            $costo_residenteok = ($costo_residente==''?'':number_format($costo_residente,2, '.', ''));
            $estudiosyproyectosok = ($estudiosyproyectos==''?'':number_format($estudiosyproyectos,2, '.', ''));
            $admin_directaok = ($admin_directa==''?'':number_format($admin_directa,2, '.', ''));
            $otros_costos_directos = ($otros_costos_directos==0?'':number_format($otros_costos_directos,2, '.', ''));
            $acarreook = ($acarreo==0?'':number_format($acarreo,2, '.', ''));
            $contingenciaok = ($contingencia==0?'':number_format($contingencia,2, '.', ''));
            /**/ $serviciosok = ($servicios==''?'':number_format($servicios,2, '.', ''));
            /**/ $transportesok = ($transportes==''?'':number_format($transportes,2, '.', ''));
            $galvanizadook = ($galvanizado==''?'':number_format($galvanizado,2, '.', ''));
            $gastos_tesoreriaok = ($gastos_tesoreria==''?'':number_format($gastos_tesoreria,2, '.', ''));
            $caja_chicaok = ($caja_chica==0?'':number_format($caja_chica,2, '.', ''));
            $valor_ventaok = number_format($valor_venta, 2, '.', '' );
            $totalok = number_format($total, 2, '.', '' );
            $deltaok = number_format($delta, 2, '.', '' );  
             
            
            if ($total>0){
            if($tiproducto!='02'){
               $arr_data[$var_prd_n] = array(
                    0 => $nroOt,
                    1 => utf8_encode(trim($dirOt)),
                    2 => utf8_encode(trim($nomproyecto)),
                    3 => trim($fecOt),
                    4 => trim($fteOt),
                    5 => $materiales_totalesok,
                    6 => $manoobra_realok,
                   7 => $subcontratook,
                   8 => $costo_residenteok,
                   9 => $estudiosyproyectosok,
                   10 => $admin_directaok,
                   11 => $otros_costos_directos,
                   12 => $acarreook,
                   13 => $contingenciaok,
                   //14 => $galvanizadook,
                   $gastos_tesoreriaok,
                   $caja_chicaok,
                   $valor_ventaok,
                   $totalok,
                   $deltaok
                   
               );

            }else{
                $arr_data[$var_prd_n] = array(
                    0 => $nroOt,
                    1 => utf8_encode(trim($dirOt)),
                    2 => utf8_encode(trim($nomproyecto)),
                    3 => trim($fecOt),
                    4 => trim($fteOt),
                    5 => $materiales_totalesok,
                    6 => $manoobra_realok,
                   7 => $serviciosok,
                   8 => $transportesok,
                    $galvanizadook,
                   $gastos_tesoreriaok,
                   $caja_chicaok,
                   $valor_ventaok,
                   $totalok,
                   $deltaok
               );
                
            }
            
            
            
            $var_prd_n++; 
            $var_row++;
            }   
                
                
            }
            $arr_grouping_header = array();
            $arr_grouping_header['A5:E5'] = 'Descripción';
            $this->reports_model->rpt_general('rpt_costs',"Costos por OT del ".trim($fecha_ini).' al '.trim($fecha_fin),$arr_columns,$arr_data ,$arr_grouping_header);
            //$this->reports_model->rpt_general('rpt_costs',"Costos por OT del ".addslashes($fecha_ini)." al ".addslashes($fecha_fin)." ",$arr_columns,$arr_data ,$arr_grouping_header);
            //$xls->close();
            $tipoexport  = "";
        }else{
            $tipoexport  = "";   
        }
	$data['tipoexport']   = $tipoexport;
        $data['seltipot']     = $selecttipoot;
        $data['selproducto']  = $selectipoproducto;
        $data['selestado']    = $selecestado;
        $data['selproyecto']  = $selproyecto;
        $data['selmoneda']    = $selmoneda;
        $data['tipo_reporte'] = $tipo_reporte;
        $data['tipoex']       = $tipoex;
        $data['fecha_ini']    = $fecha_ini;
        $data['fecha_fin']    = $fecha_fin;
        $data['fila']         = $fila;  /*DATA CARGADA DEL MODELO PARA IMPRIMIR EN LA VISTA*/
        $data['j']            = $j;
        $data['tiproducto']   = $tiproducto;
        $data['hora_actual']  = $hora_actual;
        $data['numeroot']     = $ot;
        $data['oculto']       = form_hidden(array('serie'=>'','numero'=>'','cadenaot'=>$cadenaot,'codot'=>'','tipoexport'=>'','codpartida'=>''));        
        $this->load->view(contabilidad."rpt_costoot",$data);
    }
   
    public function rpt_costocc(){
        $tipoexport   = $this->input->get_post('tipoexport');
        $codproyecto  = $this->input->get_post('codproyecto'); 
        $tiproducto   = $this->input->get_post('tiproducto'); 
        $estado       = $this->input->get_post('estado'); 
        $moneda       = $this->input->get_post('moneda'); 
        $codigo_cc    = $this->input->get_post('codigo_cc'); 
        $fecha_ini    = $this->input->get_post('fecha_ini'); 
        $fecha_fin    = $this->input->get_post('fecha_fin'); 
        $tipo_reporte = "";
        $hora_actual  = date("H:i:s",time()-3600);
        if($estado=="")       $estado       = 'P';
        if($moneda=="")       $moneda       = 'S';
        if($tipo_reporte=="") $tipo_reporte = 'G';
        if($fecha_ini=="")    $fecha_ini    = '01/01/'.date("Y",time());
        if($fecha_fin=="")    $fecha_fin    = date("d/m/Y",time());     
        if($codigo_cc=="")    $codigo_cc    = "0000000"; 
        $fila          = "";
        $cadenaot      = "";
        $tipoex        = "";
        $j             = 0;
        $arrfini       = explode("/",$fecha_ini);
        $arrffin       = explode("/",$fecha_fin);
        $fecha_ini_dbf = $arrfini[1]."/".$arrfini[0]."/".$arrfini[2];
        $fecha_fin_dbf = $arrffin[1]."/".$arrffin[0]."/".$arrffin[2];
        $cfecha_ini    = str_replace("/","",$fecha_ini);
        $cfecha_fin    = str_replace("/","",$fecha_fin);
        $selecttipoot  = form_dropdown('codigo_cc',$this->centrocosto_model->seleccionar("::TODOS:::","0000000"),$codigo_cc," size='1' id='tipot' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#frmBusqueda').attr('action','');$('#tipoexport').val('');$('#numero').val('');submit();\" ");               
        $selecestado   = form_dropdown('estado',$this->estadoot_model->seleccionar("::Seleccione:::","000"),$estado," size='1' id='estado' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#tipoexport').val('');submit();\" ");               
        $selmoneda     = form_dropdown('moneda',array(""=>"::Seleccione:::","S"=>"SOLES","D"=>"DOLARES"),$moneda," size='1' id='moneda' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#tipoexport').val('');submit();\" ");               
        $this->form_validation->set_rules('tipot','Tipo OT','required');
        $this->form_validation->set_rules('tiproducto','Tipo Producto','required');
        /*Matriz materiales*/
        $arrMateriales   = array();
        $arrMaterialesD  = array();
        $arrServicio     = array();
        $arrServicioTrans = array();
//        $filter3         = new stdClass();
//        $filter3->tipoot = '04';
//        $filter3->fechai = $fecha_ini;
//        $filter3->fechaf = $fecha_fin;
//        $mater  = $this->nsalida_model->listar_totales($filter3,new stdClass());
        
            $filter = new stdClass();
            $filter->tipoot   = '04';
            $filter->fechai   = $fecha_ini;
            $filter->fechaf   = $fecha_fin;
            $filter->moneda   = $moneda;
            $filter->group_by = array("k.Codot");            
            $oMateriales      = costomateriales($filter);
        
//        foreach($mater as $indice3=>$value3){
//            $codigo       = $value3->codot;
//            $montoD       = $value3->sum_exp_2;
//            $monto        = $value3->sum_exp_3;
//            $arrMateriales[$codigo]  = $monto;
//            $arrMaterialesD[$codigo] = $montoD;
//        }
        /*Matriz Servicios*/
        $filter6    = new stdClass();
        $filternot6 = new stdClass();
        $filter6->fechai = $fecha_ini;
        $filter6->fechaf = $fecha_fin;
        $filternot6->codservicio = array('000000000010','000000000002','000000000074','000000000057','000000000001','000000000086','000000000084','000000000048','000000000049','000000000047','000000000046','000000000071');
        $oServicios = $this->requiser_model->listar_totales($filter6,$filternot6,"");
        foreach($oServicios as $indice6 => $value6){
            $codigo       = $value6->codot;
            $tipser       = $value6->tipser;
            $total        = $value6->soles;
            $subtotal     = $value6->subtotalsoles;
            $totalD       = $value6->dolares;
            $subtotalD    = $value6->subtotaldolares;
            $arrServicio[$codigo][$tipser] = $moneda=='S'?$subtotal:$subtotalD;
        }
        /*Matriz Transporte*/
        $filter7    = new stdClass();
        $filternot7 = new stdClass();
        $filter7->fechai = $fecha_ini;
        $filter7->fechaf = $fecha_fin;
        $filter7->codservicio = array('000000000010','000000000002','000000000074','000000000057','000000000001','000000000086','000000000084','000000000048','000000000049','000000000047','000000000046','000000000071');
        $oTransportes = $this->requiser_model->listar_totales($filter7,$filternot7,"");
        foreach($oTransportes as $indice7 => $value7){
            $codigo       = $value7->codot;
            $tipser       = $value7->tipser;
            $total        = $value7->soles;
            $subtotal     = $value7->subtotalsoles;
            $totalD       = $value7->dolares;
            $subtotalD    = $value7->subtotaldolares;
            $arrServicioTrans[$codigo][$tipser] = $moneda=='S'?$subtotal:$subtotalD;
        }
        /*Obtengo los centros de costos*/
        $q_tot_materiales_totales = 0;
        $q_tot_manoobra    = 0;
        $q_tot_servicios   = 0;
        $q_tot_servicios   = 0;
        $q_tot_transportes = 0;
        $q_tot_gastos_tesoreria = 0;
        $q_tot_caja_chica  = 0;
        $q_tot_total       = 0;        
        $fila       = "";
        $filter     = new stdClass();
        $filter_not = new stdClass();
        $filter->estado = 'P';
        if($codigo_cc!='0000000')   $filter->codot = $codigo_cc;
        $ccostos    = $this->centrocosto_model->listar($filter,$filter_not,array('ot.NroOt'=>'asc'),array('ot.NroOt'=>array('00-00'=>'before')));
        foreach($ccostos as $indice => $value){
            $tot_materiales_totales = 0;
            $tot_manoobra    = 0;
            $tot_servicios   = 0;
            $tot_servicios   = 0;
            $tot_transportes = 0;
            $tot_gastos_tesoreria = 0;
            $tot_caja_chica  = 0;
            $tot_total       = 0;
            $numcc = trim(substr($value->NroOt,0,5));
            $fila.= "<tr bgcolor='#FFCC99'>";
            $fila.= "<td>".$value->NroOt."</td>";
            $fila.= "<td align='left'>".$value->DirOt."</td>";
            $fila.= "<td colspan='8'>&nbsp;</td>";
            $fila.= "</tr>";
            $ccostos_det = array();
            $filter = new stdClass();
            $filter->estado = 'P';
            $ccostos_det = $this->centrocosto_model->listar($filter,new stdClass(),array('ot.NroOt'=>'asc'),array('ot.NroOt'=>array($numcc=>'after')));
            foreach($ccostos_det as $indice2 => $value2){
                /*Materiales*/
                $codot2      = $value2->codot2;
                $nroot2      = $value2->NroOt;
                $dirot2      = $value2->DirOt;
                $materiales  = 0;
                $materialesD = 0;
                $materiales_totales = 0;
//                foreach($arrMateriales as $id=>$value){
//                    if(trim($id)==$codot2) {$materiales=$value;break;}
//                }
//                foreach($arrMaterialesD as $id=>$value){
//                    if(trim($id)==$codot2) {$materialesD=$value;break;}
//                }
                $materiales = $materialesD;
                $materiales = @$oMateriales[$codot2]->costo;
                /*Mano de Obra*/
                $manoobra        = 0;
                $manoobra_real   = 0;
                $manoobraD       = 0;
                $manoobra_realD  = 0;
                $filter8         = new stdClass();
                $filter8->codot  = $codot2;
                $filter8->fechai = $fecha_ini;
                $filter8->fechaf = $fecha_fin;
                $filter8->group_by = array("a.codot");
                $oManoObra       = $this->tareo_model->listar_totales($filter8,new stdClass());
                /*Servicios*/
                $servicios= 0;
                foreach($arrServicio as $id=>$value){
                    if($id==$codot2) {
                        foreach($value as $id2=>$valuex){
                            $servicios = $servicios + $valuex;
                        }
                    }
                }
                /*Transporte*/
                $transportes = 0;
                if(count($arrServicioTrans)>0){
                    foreach($arrServicioTrans as $id=>$value){
                        if($id==$codot2) {
                            $transportes = 0;
                            foreach($value as $id2=>$valuex){
                                $transportes = $transportes + $valuex;
                            }
                        }
                    }
                }        
                /*Tesoreria*/
                $gastos_tesoreria = 0;
                $filter15 = new stdClass();
                $filter15->codot   = $codot2;
                $filter15->fechai  = $fecha_ini;
                $filter15->fechaf  = $fecha_fin;
                $oOtrosTeso = $this->voucher_model->listar_totales($filter15,new stdClass());  
                /*Caja chica*/
                $caja_chica = 0;
                $filter16   = new stdClass();
                $filter16->codot   = $codot2;
                $filter16->fechai  = $fecha_ini;
                $filter16->fechaf  = $fecha_fin;
                $filter16->group_by = array("det.codot");
                $oCaja = $this->caja_model->listar_totales($filter16,new stdClass());                 
                if($moneda=='S'){
                    $materiales_totales = $materiales;
                    $manoobra           = isset($oManoObra->simple)?$oManoObra->simple:"";
                    $manoobra_real      = isset($oManoObra->real)?$oManoObra->real:"";     
                    $gastos_tesoreria   = isset($oOtrosTeso[0]->ImpSoles)?$oOtrosTeso[0]->ImpSoles:"";
                    $caja_chica         = isset($oCaja[0]->subSoles)?$oCaja[0]->subSoles:"";
                }
                elseif($moneda=='D'){
                    $materiales_totales = $materialesD;
                    $manoobra           = isset($oManoObra->simpleD)?$oManoObra->simpleD:"";
                    $manoobra_real      = isset($oManoObra->realD)?$oManoObra->realD:""; 
                    $gastos_tesoreria   = isset($oOtrosTeso[0]->ImpDolares)?$oOtrosTeso[0]->ImpDolares:"";
                    $caja_chica         = isset($oCaja[0]->subDolar)?$oCaja[0]->subDolar:"";
                }
                $total = $materiales_totales + $manoobra_real + $servicios + $transportes + $caja_chica;
                $fila.= "<tr id='".$codot2."'>";
                $fila.= "<td>".$nroot2."</td>";
                $fila.= "<td align='left'>".$dirot2."</td>";
                $fila.= "<td align='left'>".$value2->nomper."</td>";
                $fila.= "<td align='right' id='01'><a href='#' onclick='rpt_materiales(this);'>".number_format($materiales_totales,2)."</a></td>";
                $fila.= "<td align='right' id='02'><a href='#' onclick='rpt_manoobra(this);'>".number_format($manoobra,2)."</a></td>";
                $fila.= "<td align='right' id='11'><a href='#' onclick='rpt_servicios(this);'>".number_format($servicios,2)."</a></td>";
                $fila.= "<td align='right' id='12'><a href='#' onclick='rpt_transportes(this);'>".number_format($transportes,2)."</a></td>";
                $fila.= "<td align='right' id='13'><a href='#' onclick='rpt_tesoreria(this);'>".number_format($gastos_tesoreria,2)."</a></td>";
                $fila.= "<td align='right' id='14'><a href='#' onclick='rpt_caja(this);'>".number_format($caja_chica,2)."</a></td>";
                $fila.= "<td align='right'>".number_format($total,2)."</td>";
                $fila.= "</tr>";   
                $tot_materiales_totales = $tot_materiales_totales + $materiales_totales;
                $tot_manoobra    = $tot_manoobra + $manoobra;
                $tot_servicios   = $tot_servicios + $servicios;
                $tot_transportes = $tot_transportes + $transportes;
                $tot_gastos_tesoreria = $tot_gastos_tesoreria + $gastos_tesoreria;
                $tot_caja_chica  = $tot_caja_chica + $caja_chica;
                $tot_total       = $tot_total + $total;  
                /*Calculo de monto Total*/
                $q_tot_materiales_totales = $q_tot_materiales_totales + $materiales_totales;
                $q_tot_manoobra    = $q_tot_manoobra + $manoobra;
                $q_tot_servicios   = $q_tot_servicios + $servicios;
                $q_tot_transportes = $q_tot_transportes + $transportes;
                $q_tot_gastos_tesoreria = $q_tot_gastos_tesoreria + $gastos_tesoreria;
                $q_tot_caja_chica  = $q_tot_caja_chica + $caja_chica;
                $q_tot_total       = $q_tot_total + $total;                  
            }
            $fila.= "<tr>";
            $fila.= "<td colspan='3' align='right'><i>Total</i></td>";
            $fila.= "<td align='right'><i>".number_format($tot_materiales_totales,2)."</i></td>";
            $fila.= "<td align='right'><i>".number_format($tot_manoobra,2)."</i></td>";
            $fila.= "<td align='right'><i>".number_format($tot_servicios,2)."</i></td>";
            $fila.= "<td align='right'><i>".number_format($tot_transportes,2)."</i></td>";
            $fila.= "<td align='right'><i>".number_format($tot_gastos_tesoreria,2)."</i></td>";
            $fila.= "<td align='right'><i>".number_format($tot_caja_chica,2)."</i></td>";
            $fila.= "<td align='right'><i>".number_format($tot_total,2)."</i></td>";            
            $fila.= "</tr>"; 
        }
        if($codigo_cc=='0000000'){
            $fila.= "<tr>";
            $fila.= "<td colspan='3' align='right'><i>Monto Total</i></td>";
            $fila.= "<td align='right'><i>".number_format($q_tot_materiales_totales,2)."</i></td>";
            $fila.= "<td align='right'><i>".number_format($q_tot_manoobra,2)."</i></td>";
            $fila.= "<td align='right'><i>".number_format($q_tot_servicios,2)."</i></td>";
            $fila.= "<td align='right'><i>".number_format($q_tot_transportes,2)."</i></td>";
            $fila.= "<td align='right'><i>".number_format($q_tot_gastos_tesoreria,2)."</i></td>";
            $fila.= "<td align='right'><i>".number_format($q_tot_caja_chica,2)."</i></td>";
            $fila.= "<td align='right'><i>".number_format($q_tot_total,2)."</i></td>";            
            $fila.= "</tr>";               
        }
	$data['tipoexport']   = $tipoexport;
        $data['seltipot']     = $selecttipoot;
        $data['selestado']    = $selecestado;
        $data['selmoneda']    = $selmoneda;
        $data['tipo_reporte'] = $tipo_reporte;
        $data['tipoex'] = $tipoex;
        $data['fecha_ini']    = $fecha_ini;
        $data['fecha_fin']    = $fecha_fin;
        $data['fila']         = $fila; 
        $data['hora_actual']  = $hora_actual;
        $data['oculto']       = form_hidden(array('serie'=>'','numero'=>'','cadenaot'=>$cadenaot,'codot'=>'','tipoexport'=>'','codpartida'=>'','tipot'=>'04'));        
        $this->load->view(contabilidad."rpt_costocc",$data);        
    }
    
    public function rpt_costomateriales(){
        $tipoexport    = $this->input->get_post('tipoexport');
        $codot         = $this->input->get_post('codot');
        $cadenaot      = $this->input->get_post('cadenaot');
        $monedadoc     = $this->input->get_post('moneda');
        $fecha_ini     = $this->input->get_post('fecha_ini');
        $fecha_fin     = $this->input->get_post('fecha_fin');
        $verencabezado = $this->input->get_post('verencabezado');
        $flg_exclusiones  = $this->input->get_post('exclusiones');
        /*Son todas las familias de productos que no se considerarán en el reporte de control de pesos*/
        $exclusiones = $this->config->item('exclusiones');        
        $arr_export_detalle = array();
        $arrCodOT      = $codot!=''?array($codot):explode(",",$cadenaot);
        $nroOt         = "";
        $dirOt         = "";
        $fila          = "";
        foreach($arrCodOT as $item => $value){
            $filter        = new stdClass();
            $filter->codot = $value; 
            $oOt         = $this->ot_model->obtenerg($filter);
            $arrnumero[] = $oOt->NroOt;
            $arrsite[]   = $oOt->DirOt;
            $torres      = $this->ttorre_model->obtener($oOt->Torre);
            $arrtorre[trim($oOt->NroOt)]  = $torres->Des_Larga;
        }          
        /*Listado de productos*/
        $productos      = $this->producto_model->listar(new stdClass(),new stdClass());
        foreach($productos as $indice => $value){
            $codpro = $value->codpro;
            $arrproducto[$codpro] = $value;
        }
        $filter     = new stdClass();
        $filter_not = new stdClass();
        $filter->codot  = $arrCodOT;
        if($fecha_ini!='')         $filter->fechai = $fecha_ini;
        if($fecha_fin!='')         $filter->fechaf = $fecha_fin;
        if($flg_exclusiones=="S")  $filter_not->linea  = $exclusiones;
        $order_by       = array('k.Fecha desc');
        $oMateriales = $this->nsalida_model->listar_salidas_detalle($filter,$filter_not,$order_by);
        $codot_user  = $this->session->userdata('codot');
        $ver_precios = ($codot_user!="0003739" && $codot_user!="0003722" && $codot_user!="0003723")?true:false;
        if($tipoexport==""){
            if(count($oMateriales)>0){
                $total_total  = 0;
                $total_total_dolar = 0;
                $fila         = "";
                $tot_pesoaten = 0;
                foreach($oMateriales as $indice=>$value){
                    $arr_data     = array();
                    $codigo       = $value->codot;
                    $moneda       = $value->moneda;
                    $precio       = $value->preprom;//Precio promedio en soles
                    $cantidad     = $value->cantidad;
                    $tcambio      = $value->tcambio;
                    $codpro       = $value->codigo;
                    $fecha        = date_sql($value->fecha);
                    $tip_movmto   = $value->tip_movmto;
                    $documento    = $value->documento;
                    $serie        = trim($value->serie);
                    $numero       = trim($value->numero);  
                    $serreq       = trim($value->serreq);
                    $numreq       = trim($value->numreq);
                    if(substr($numero,4,strlen($numero)-1)==substr($numreq,1,strlen($numreq)-1)){
                        $serreq   = "--";
                        $numreq   = "--";
                    }
                  //  $pesos       = $value->peso_total; 
                    $codlinea     = isset($arrproducto[$codpro]->tipo)?$arrproducto[$codpro]->tipo:"";
                    $descri       = isset($arrproducto[$codpro]->despro)?$arrproducto[$codpro]->despro:":PRODUCTO BORRADO::";
                    $linea        = $codlinea=='02'?'MATERIA PRIMA':($codlinea=='03'?'MATERIAS AUXILIARES':'SUMINISTROS');
                    $peso         = isset($arrproducto[$codpro]->peso)?$arrproducto[$codpro]->peso:"";                    
                    /*Numero de ot2*/
                    $filter3        = new stdClass();
                    $filter3_not    = new stdClass();
                    $filter3->codot = trim($codigo); 
                    $oOt2          = $this->ot_model->obtenerg($filter3,$filter3_not);
                    $nroOt2        = $oOt2->NroOt;    
                    $total_soles   = $cantidad*$precio;
                    $total_dolares = $cantidad*$precio/$tcambio;    
                    $codlinea      = "";
                    if(trim($documento)=="G"){
                        $total_total  = $total_total + $total_soles;
                        $total_total_dolar  = $total_total_dolar + $total_dolares;    
                    }
                    elseif(trim($documento)=="DV"){
                        $total_total  = $total_total - $total_soles;
                        $total_total_dolar  = $total_total_dolar - $total_dolares;    
                    }
                    /*Se inicializa el peso si pertenece a otra linea*/
//                    foreach($exclusiones as $ind => $val){
//                        if(substr($codpro,0,4)==$val) {$peso = 0;break;}
//                    }
                    if($documento=="DV")  $peso = -1*$peso;
                    $tot_pesoaten = $tot_pesoaten + $peso*$cantidad;
                    $fila        .= "<tr bgcolor='".($codlinea=='01'?'#5BEAA7':($codlinea=='02'?'#5B9EEA':'#ffffff'))."'>";
                    $fila        .= "<td style='width:8%;height:auto;text-align:center;'><div id='".trim($codigo)."'>".$nroOt2."</div></td>";
                    $arr_data[]   = $nroOt2; 
                    $fila        .= "<td style='width:8%;height:auto;text-align:center;'><div>".$arrtorre[trim($nroOt2)]."</div></td>";
                    $arr_data[]   = utf8_encode($arrtorre[trim($nroOt2)]);  
                    $fila        .= "<td style='width:8%;height:auto;text-align:center;'><div id='".$numreq."'><a href='#' ".($numreq=='--'?'':'onclick="ver_requis(this);"').">".$serreq."-".$numreq."</a></div></td>";                                    
                    $arr_data[]   = $serreq."-".$numreq; 
                    $fila        .= "<td style='width:9%;height:auto;text-align:center;'><div>".$codpro."</div></td>";
                    $arr_data[]   = $codpro; 
                    $fila        .= "<td style='width:10%;height:auto;text-align:center;'><div>".$linea."</div></td>";
                    $arr_data[]   = $linea; 
                    $fila        .= "<td style='width:16%;height:auto;text-align:left;'><div>".$descri."</div></td>";
                    $arr_data[]   = utf8_encode($descri); 
                    $fila        .= "<td style='width:8%;height:auto;text-align:center;'><div>".$fecha."</div></td>";
                    $arr_data[]   = $fecha; 
                    $fila        .= "<td style='width:8%;height:auto;text-align:right;'><div>".$cantidad."</div></td>";
                    $arr_data[]   = $cantidad; 
                    if($ver_precios){
                        if($monedadoc=='S'){
                            $fila       .= "<td style='width:11%;height:auto;text-align:right;'><div>".number_format($precio,4)."</div></td>";        
                            $arr_data[] = $precio; 
                            $fila       .= "<td style='width:10%;height:auto;text-align:right;'><div>".($documento=='DV'?'-':'').number_format($total_soles,4)."</div></td>";
                            $arr_data[] = $total_soles; 
                        }    
                        elseif($monedadoc=='D'){
                            $fila    .= "<td style='width:11%;height:auto;text-align:right;'><div>".number_format($precio/$tcambio,4)."</div></td>";        
                            $arr_data[] = $precio/$tcambio; 
                            $fila    .= "<td style='width:10%;height:auto;text-align:right;'><div>".($documento=='DV'?'-':'').number_format($total_dolares,4)."</div></td>";
                            $arr_data[] = $total_dolares; 
                        }  
                    }
                    $fila      .= "<td style='width:10%;height:auto;text-align:right;'>".number_format($peso*$cantidad,2)."</td>";
                    $arr_data[] = $peso*$cantidad; 
                    $fila      .= "<td style='width:10%;height:auto;text-align:center;'><div id='".$numero."'><a href='#' onclick='".(trim($documento)=='G'?'ver_vale_salida(this);':'ver_devolucion(this);')."'>".(trim($documento)=='G'?'VS':$documento)."  ".trim($serie)."-".trim($numero)."</a></div></td>";
                    $arr_data[] = (trim($documento)=='G'?'VS':$documento)."  ".trim($serie)."-".trim($numero); 
                    $fila      .= "</tr>";
                    array_push($arr_export_detalle,$arr_data);
                }
                $fila        .= "<tr>";
                $fila        .= "<td colspan='".($ver_precios?9:8)."'>&nbsp;</td>";
                if($ver_precios){
                    if($monedadoc=='S'){
                        $fila    .= "<td style='width:8%;height:auto;text-align:right;'><div>".number_format($total_total,2)."</div></td>";
                    }
                    elseif($monedadoc=='D'){
                        $fila    .= "<td style='width:8%;height:auto;text-align:right;'><div>".number_format($total_total_dolar,2)."</div></td>";
                    }                    
                }
                $fila    .= "<td align='right'>".number_format($tot_pesoaten,2)."</td>";
                $fila    .= "</tr>";
            }
            $var_export = array('rows' => $arr_export_detalle);
            $this->session->set_userdata('data_listar_costomateriales', $var_export);
        }
        $data['codot']     = $codot;
        $data['fila']      = $fila;
        $data['arrnumero'] = $arrnumero;
        $data['arrsite']   = $arrsite;
        $data['verencabezado'] = $verencabezado;
        $data['monedadoc']     = $monedadoc;
        $data['ver_precios']   = $ver_precios;
        $this->load->view(contabilidad."rpt_costomateriales",$data);
    }
    
    public function rpt_costomanoobra(){
        $tipoexport = $this->input->get_post('tipoexport');
        $codot      = $this->input->get_post('codot');
        $monedadoc  = $this->input->get_post('moneda');
        $fecha_ini  = $this->input->get_post('fecha_ini');
        $fecha_fin  = $this->input->get_post('fecha_fin');
        $cadenaot   = $this->input->get_post('cadenaot');
        $arrCodOT   = $codot!=''?array($codot):explode(",",$cadenaot);
        $nroOt      = "";
        $dirOt      = ""; 
        $fila       = "";
        $peso       = "";
        if(count($arrCodOT)==1){
            $filter2        = new stdClass();
            $filter2_not    = new stdClass();
            $filter2->codot = $arrCodOT[0];            
            $oOt       = $this->ot_model->obtenerg($filter2,$filter2_not);
            $nroOt     = $oOt->NroOt;
            $dirOt     = $oOt->DirOt;
            $peso      = ($oOt->PESO)/1000;          
        }        
        $filter         = new stdClass();
        $filter->codot  = $arrCodOT;
        $filter->flagtareado  = 1;
        if($fecha_ini!="") $filter->fechai = $fecha_ini;
        if($fecha_fin!="") $filter->fechaf = $fecha_fin;    
        $order_by       = array('tareo.Fecha'=>'asc','responsable.nomper'=>'asc');
        $oTareo         = $this->tareo_model->listarg($filter,$order_by);
        $fila   = "";
        $total_simple = 0;
        $total_real   = 0;
        $total_simpleD = 0;
        $total_realD   = 0;
        $total_horas   = 0;  
        $arr_export_detalle = array();
        if($tipoexport==""){
            if(count($oTareo)>0){
                foreach($oTareo as $indice => $value){
                    $arr_data    = array();
                    $tc          = $value->tcambio; 
                    $fecha       = $value->fecha2; 
                    $nomper      = utf8_encode($value->nomper); 
                    $areapro     = $value->areapro; 
                    $descripcion = $value->descripcion; 
                    $horas       = $value->horas; 
                    $simple      = $value->simple; 
                    $real        = $value->real; 
                    $flgPlanilla = $value->flgPlanilla; 
                    $codres      = $value->codres; 
                    $codigo      = $value->codot; 
                    /*Numero de ot2*/
                    $filter3        = new stdClass();
                    $filter3_not    = new stdClass();
                    $filter3->codot = trim($codigo); 
                    $oOt2          = $this->ot_model->obtenerg($filter3,$filter3_not);
                    $nroOt2        = $oOt2->NroOt;    
                    /**/                
                    $total_simple = $total_simple + $simple;
                    $total_real   = $total_real   + $real;
                    if($tc==0)
                    {
                        $total_simpleD = $total_simpleD + ($simple/1);
                    $total_realD   = $total_realD   + ($real/1);
                    
                    }
                    else
                    {
                        $total_simpleD = $total_simpleD + ($simple/$tc);
                    $total_realD   = $total_realD   + ($real/$tc);
                    
                    }
                    $total_horas   = $total_horas + $horas;
                    $fila     .= "<tr>";
                    $fila     .= "<td style='width:5%;' align='center'>".$nroOt2."</td>";
                    $arr_data[] =$nroOt2;
                    $fila     .= "<td style='width:5%;' align='center'>".$codres."</td>";
                    $arr_data[] =$codres;
                    $fila     .= "<td style='width:35%;' align='left'>".utf8_encode($nomper)."</td>";
                    $arr_data[] =utf8_encode($nomper);
                    $fila     .= "<td style='width:15%;' align='left'>".utf8_encode($areapro)."</td>";
                    $arr_data[] =utf8_encode($areapro);
                    $fila     .= "<td style='width:20%;' align='left'>".utf8_encode($descripcion)."</td>";
                    $arr_data[] =utf8_encode($descripcion);
                    $fila     .= "<td style='width:5%;'>".$fecha."</td>";
                    $arr_data[] =$fecha;
                    $fila     .= "<td style='width:5%;' align='center'>".$horas."</td>";
                    $arr_data[] =$horas;
                    if($monedadoc=='S'){  
                        $fila     .= "<td style='width:5%;' align='right'>".number_format($real,2)."</td>";        
                        $arr_data[] =$real;
                    }
                    elseif($monedadoc=='D'){  
                        $fila     .= "<td style='width:5%;' align='right'>".number_format($real/$tc,2)."</td>";            
                        $arr_data[] =$real/$tc;
                    }
                    $fila     .= "<td style='width:5%;' align='center'>".$flgPlanilla."</td>";  
                    $fila     .= "</tr>";
                    array_push($arr_export_detalle,$arr_data);
                }
                $fila     .= "<tr>";
                $fila     .= "<td colspan='6' align='right'>&nbsp;</td>";
                $fila     .= "<td align='center'>".number_format($total_horas,2)."</td>";
                if($monedadoc=='S'){
                    $fila     .= "<td align='right'>".number_format($total_real,2)."</td>";
                }
                elseif($monedadoc=='D'){
                    $fila     .= "<td align='right'>".number_format($total_realD,2)."</td>";  
                }
                $fila     .= "</tr>";
                $var_export = array('rows' => $arr_export_detalle);
                $this->session->set_userdata('data_listar_costomanoobra', $var_export);                
            }
            
            $cur_moindirecta = $this->tareo_model->getMonto($fecha_ini,$fecha_fin);
            $str_cc = "";
            $monto_c = 0;
            foreach($cur_moindirecta as $ind => $val_cc){
                $codigo = $val_cc->codot;
                $nombre = $val_cc->nroot;
                $dir = utf8_encode($val_cc->dirot);
                $monto = $val_cc->monto;
                $str_cc .= "<tr><td>".$codigo."</td>";
                $str_cc .= "<td>".$nombre."</td>";
                $str_cc .= "<td>".$dir."</td>";
                $str_cc .= "<td>".number_format($monto,2)."</td></tr>";
                $monto_c= $monto_c +$monto ;
            }
        }
        $data['monto_c'] = number_format($monto_c,2);
        $data['str_cc'] = $str_cc;
        $data['codot'] = $codot;
        $data['nroOt'] = $nroOt;
        $data['dirOt'] = $dirOt;
        $data['fila']  = $fila;
        $data['monedadoc']   = $monedadoc;
        $data['total_horas'] = $total_horas;
        $data['total_real']  = $total_real;
        $data['peso']        = $peso;
        $this->load->view(contabilidad."rpt_costomanoobra",$data);
    }
    
    function rpt_costomanoobrasiddex(){
        $tipoexport = $this->input->get_post('tipoexport');
        $codot      = $this->input->get_post('codot');
        $monedadoc  = $this->input->get_post('moneda');
        $fecha_ini  = $this->input->get_post('fecha_ini');
        $fecha_fin  = $this->input->get_post('fecha_fin');
        $cadenaot   = $this->input->get_post('cadenaot');
        $arrCodOT   = $codot!=''?array($codot):explode(",",$cadenaot);
        $nroOt      = "";
        $dirOt      = ""; 
        $fila       = "";
        $peso       = "";
        $t_horas    = 0; 
        $t_monto    = 0; 
        $t_peso     = 0;      
        foreach($arrCodOT as $item => $value){
            $filter        = new stdClass();
            $filter->codot = $value; 
            $oOt         = $this->ot_model->obtenerg($filter);
            $arrnumero[] = str_replace("-","",trim($oOt->NroOt));
            $arrsite[]   = $oOt->DirOt;
            $torres      = $this->ttorre_model->obtener($oOt->Torre);
            $arrtorre[trim($oOt->NroOt)]  = $torres->Des_Larga;
        }  
        /*Obtengo pesos*/
        $filter  = new stdClass();
        $filter->numero = $arrnumero;
        $nomenclatura   = $this->listamat_model->listar_totales($filter);
        foreach($nomenclatura as $val){
            $t_peso = $t_peso + @$val->peso/1000;
        }    
        /*Obtengo detalle*/
        $filter = new stdClass();
        $filter->numero = $arrnumero;
        if($fecha_ini!="") $filter->fechai = $fecha_ini;
        if($fecha_fin!="") $filter->fechaf = $fecha_fin;
        $partes = $this->parte_model->listar($filter);
        $arr_export_detalle = array();
        if(count($partes)>0){
            foreach($partes as $item => $value){
                $arr_data  = array();
                $fila     .= "<tr>";
                $fila     .= "<td style='width:5%;' align='center'>".$value->NumeroOrden."</td>";
                $arr_data[] =$value->NumeroOrden;
                $fila     .= "<td style='width:5%;' align='center'>".$value->NumeroTarjeta."</td>";
                $arr_data[] =$value->NumeroTarjeta;
                $fila     .= "<td style='width:35%;' align='left'>".utf8_encode($value->Nombre)."</td>";
                $arr_data[] =utf8_encode($value->Nombre);
                $fila     .= "<td style='width:15%;' align='left'>".utf8_encode($value->Proceso)."</td>";
                $arr_data[] =utf8_encode($value->Proceso);
                $fila     .= "<td style='width:20%;' align='left'>".$value->CodigoProceso."</td>";
                $arr_data[] =$value->CodigoProceso;
                $fila     .= "<td style='width:5%;'  align='center'>".$value->FechaParte."</td>";
                $arr_data[] =$value->FechaParte;
                $fila     .= "<td style='width:5%;' align='right'>".$value->TiempoEjecucion."</td>";
                $arr_data[] =$value->TiempoEjecucion;
                if($monedadoc=='S'){
                    $monto = $value->TiempoEjecucion*$value->Tarifa;
                }
                elseif($monedadoc=='D'){
                    $monto = ($value->TiempoEjecucion*$value->Tarifa)/$tc;
                }
                $fila     .= "<td style='width:5%;' align='right'>".number_format($monto,2)."</td>";  
                $arr_data[] =$monto;
                $fila     .= "</tr>";
                array_push($arr_export_detalle,$arr_data);
                $t_horas   = $t_horas + $value->TiempoEjecucion;
                $t_monto   = $t_monto + $monto;
            }
            $fila     .= "<tr>";
            $fila     .= "<td colspan='6'>&nbsp;</td>";
            $fila     .= "<td align='right'>".$t_horas."</td>";
            $fila     .= "<td align='right'>".$t_monto."</td>";
            $fila     .= "</tr>";
            $var_export = array('rows' => $arr_export_detalle);
            $this->session->set_userdata('data_listar_costomanoobra', $var_export);
        }
        $data['codot']       = $codot;
        $data['arrnumero']   = $arrnumero;
        $data['arrsite']     = $arrsite;
        $data['fila']        = $fila;
        $data['monedadoc']   = $monedadoc;
        $data['total_horas'] = $t_horas;
        $data['total_real']  = $t_monto;
        $data['peso']        = $t_peso;
        $this->load->view(contabilidad."rpt_costomanoobrasiddex",$data);
    }
    
    public function rpt_servicios($tipser=''){
        $codot      = $this->input->get_post('codot');
        $tipoexport = $this->input->get_post('tipoexport');
        $monedadoc  = $this->input->get_post('moneda');
        $fecha_ini  = $this->input->get_post('fecha_ini');
        $fecha_fin  = $this->input->get_post('fecha_fin');
        $cadenaot   = $this->input->get_post('cadenaot');
        $arrCodOT   = $codot!=''?array($codot):explode(",",$cadenaot);
        $nroOt      = "";
        $dirOt      = ""; 
        $fila       = "";
        $peso       = "";
        if(count($arrCodOT)==1){
            $filter2        = new stdClass();
            $filter2_not    = new stdClass();
            $filter2->codot = $arrCodOT[0];            
            $oOt       = $this->ot_model->obtenerg($filter2,$filter2_not);
            $nroOt     = $oOt->NroOt;
            $dirOt     = $oOt->DirOt;      
        }        
        $filter      = new stdClass();
        $filter_not  = new stdClass();
        if($tipser==''){
            $filter_not->codservicio = array('000000000010','000000000002','000000000074','000000000057','000000000001','000000000086','000000000084','000000000048','000000000049','000000000047','000000000046','000000000071');    
        }
        elseif($tipser=='T'){
            $filter->codservicio     = array('000000000010','000000000002','000000000074','000000000057','000000000001','000000000086','000000000084','000000000048','000000000049','000000000047','000000000046','000000000071');                
        }
        $filter->codot       = $arrCodOT;
        $filter->fechai      = $fecha_ini;
        $filter->fechaf      = $fecha_fin;  
//        $filter->frealizadoi = $fecha_ini;
//        $filter->frealizadof = $fecha_fin;  
        $order_by            = array('r.Fecemi desc');        
        $oServicio      = $this->requiser_model->listarg($filter,$filter_not,$order_by);
        $fila   = "";
        $total_servicios = 0;
        $total_servicios_dolar = 0;
        $total_facturado = 0;
        $total_facturado_dolar = 0;
        if($tipoexport==""){
            if(count($oServicio)>0){
                foreach($oServicio as $indice => $value){
                    $codigo    = $value->codot;
                    $tipser2   = $value->tipser;
                    $total     = $value->costo;
                    $codser    = $value->gcodser;
                    $fentrega  = $value->gfentrega;
                    $peso      = $value->gpeso;
                    $ruc       = $value->gruc;
                    if($ruc==0)
                    $ruc       ='00000000000';
                    $moneda    = $value->moneda;
                    $tipdoc    = $value->tipod;
                    $seriedoc  = $value->seried;
                    $nrodoc    = $value->nrod;
                    $tc        = $value->cambio;
                    $ser_guia  = $value->gserguia;
                    $num_guia  = $value->gnumguia;
                    $estado    = $value->gestado;
                    $observ    = $value->gobserva;
                    $fecemi    = $value->fecemi;
                    $frealiza  = $value->fdespacho;
                    $subtotal_a= $value->subtotal;
                    $igv_a     = $value->igv;
                    $total_a   = $value->costo;
                    $subtotal  = $moneda=='S'?$subtotal_a:($subtotal_a*$tc);
                    $igv       = $moneda=='S'?$igv_a:($igv_a*$tc);
                    $total     = $moneda=='S'?$total_a:($total_a*$tc);
                    $subtotal_dolar = $moneda=='D'?$subtotal_a:($subtotal_a/$tc);
                    $igv_dolar      = $moneda=='D'?$igv_a:($igv_a/$tc);
                    $total_dolar    = $moneda=='D'?$total_a:($total_a/$tc);    
                    $total_servicios = $total_servicios + $subtotal;
                    $total_servicios_dolar = $total_servicios_dolar + $subtotal_dolar;
                    $tipo_documento        = $tipdoc=='01'?"FV":"OTRO";
                    $filter11              = new stdClass();
                    $filter11_not          = new stdClass();
                    $filter11->ruccli      = trim($ruc);
                    $filter11->numguia     = trim($num_guia);
                    $factura_detalle       = $this->facturac_model->obtener_factura($filter11,$filter11_not);
                    $nrofac                = $factura_detalle->NroDoc; 
                    $nroser                = $factura_detalle->SerieDoc; 
                    $nrotip                = $factura_detalle->TipDoc; 
                    $nroruc                = $factura_detalle->RucCli; 
                    if($nrotip=='FV' && $nroser=='0000' && $nroser=='000000')
                    {
                        $valor=$num_guia;
                    }
                    else
                    {
                        $valor=$nrofac;
                    }
                    
                    //Obtengo el monto total de la factura de compra
                    $filter2               = new stdClass();
                    $filter2_not           = new stdClass();
                    $filter2->codot        = $codot;
                    $filter2->numero       = $nrodoc;
                    $filter2->seroc        = $ser_guia;
                    $filter2->nrooc        = $num_guia;
                    //$facturac              = $this->facturac_model->listar_totales($filter2,$filter2_not);//VOLVER A ACTIVAR
                    $montofacturaS         = 0;
                    $montofacturaD         = 0;
//                    if(isset($facturac->montoS)){
//                        $montofacturaS         = $facturac->montoS;//VOLVER A ACTIVAR
//                        $montofacturaD         = $facturac->montoD;    //VOLVER A ACTIVAR
//                    }
                    $total_facturado = $total_facturado + $montofacturaS;
                    $total_facturado_dolar = $total_facturado_dolar + $montofacturaD;
                    /*Obtengo el numero de voucher*/
                    $filter8               = new stdClass();
                    $filter8_not           = new stdClass();
                    $filter8->codot        = $codot;
                    $filter8->nrodocref    = $valor;
                    $nroVoucher            = "";                 
                    $oVoucher              = $this->voucher_model->listar_detalle2($filter8,$filter8_not);
                    if(is_array($oVoucher) && count($oVoucher)>0){
                        foreach($oVoucher as $indice2 => $value2){
                            $nroVoucher = $value2->NroVoucher;
                            $codot2     = $value2->codot;
                            break;
                            
                        }
                    }

                    // Servicios
                    $descripcion = "";  
                    if($codser!=''){
                        $filter3     = new stdClass();
                        $filter3_not = new stdClass();
                        $filter3->codservicio = $codser;
                        $objServicio   = $this->servicio_model->obtener($filter3,$filter3_not);
                        $descripcion   = $objServicio->DesPro;
                    }
                    // Nombre de ot
                    $filter7        = new stdClass();
                    $filter7_not    = new stdClass();
                    $filter7->codot = trim($codigo); 
                    $oOt2          = $this->ot_model->obtenerg($filter7,$filter7_not);
                    $nroOt2        = $oOt2->NroOt;   
                    /*Nombre del proveedor*/
                    $filter4     = new stdClass();
                    $filter4_not = new stdClass();
                    $filter4->ruccliente = $ruc;
                    $oProveedor  = $this->proveedor_model->obtener($filter4,$filter4_not);
                    $razcli      = $oProveedor->RazCli;
                    
                    //smi: RQ - VOUCHER ANIDADO 
                    $reqx='RS';
                    $filter5     = new stdClass();
                    $filter5_not = new stdClass();
                    $filter5->requis_voucher = $num_guia;
                  
                    $rvx  = $this->voucher_model->ReqVou($filter5,$filter5_not);
                   
                    if(isset($rvx-> NroVoucher))
                    $RVoucher      = $rvx-> NroVoucher;
                    
                    else
                    $RVoucher ='';  
                                 
                    $fila       .= "<tr>";
                    $fila       .= "<td align='center'><div id='".trim($codigo)."' style='width:50px;'>".$nroOt2."</div></td>";/*id2='".trim($ser_guia)."'*/
                    $fila       .= "<td align='center'><div id='".trim($num_guia)."' id2='".trim($codot)."'  style='width:100px;height:auto;'><a href='#' onclick='ver_requis_ser(this);'>".$ser_guia."-".$num_guia."</a></div></td>";
                    $fila       .= "<td align='center'><div style='width:80px;'>".date_sql($fecemi)."</a></td>";
                    $fila       .= "<td align='center'><div style='width:80px;'>".(date_sql($frealiza)=='30/12/1899'?'':date_sql($frealiza))."</a></td>";
                    $fila       .= "<td align='left'><div style='width:120px;'>".$codser."-".$descripcion."</a></td>";
                    //$fila       .= "<td align='left'><div style='width:80px;'>".$observ."</a></td>";
                    $fila       .= "<td align='right'><div style='width:80px;'>".number_format($peso,2)."</a></td>";
                    $fila       .= "<td align='left'><div title='".$ruc."' style='width:100px;'>".$razcli." ".$ruc."</a></td>";
                    $fila       .= "<td align='center'><div style='width:70px;'>".($estado==1?"SI":"NO")."</a></td>";
                    if($monedadoc=='S'){
                        $fila       .= "<td align='right'><div style='width:80px;'>".number_format($subtotal,2)."</a></td>";
                        $fila       .= "<td align='right'><div style='width:80px;'>".number_format($montofacturaS,2)."</a></td>";        
                    }
                    elseif($monedadoc=='D'){
                        $fila       .= "<td align='right'><div style='width:80px;'>".number_format($subtotal_dolar,2)."</a></td>";
                        $fila       .= "<td align='right'><div style='width:80px;'>".number_format($montofacturaD,2)."</a></td>";        
                    }
                    
                    
                 //numero de factura
                  //$fila       .= "<td align='center'>".($nrotip=='FV' && $nroser=='0000' && $nroser=='000000'?'-':"<div id='".trim($nrofac)."' id2='".trim($nroser)."' id3='".trim($nrotip)."' id4='".trim($nroruc)."' id5='".trim($codot)."' style='width:100px;'><a href='#' onclick='ver_facturac(this);'>".$nrotip."-".$nroser."-".$nrofac."</a><div>")."</td>";             
                    $fila       .= "<td align='center'>".($nrotip=='FV' && $nroser=='0000' && $nroser=='000000'?'-':"<div id='".trim($nrofac)."' id2='".trim($nroser)."' id3='".trim($codot)."' id4='".trim($nroruc)."' id5='".trim($codot)."' style='width:100px;'><a href='#' onclick='ver_facturac(this);'>".$nrotip."-".$nroser."-".$nrofac."</a><div>")."</td>";             
                    $fila       .= "<td align='center'><div id='".$nroVoucher."' style='width:100px;'><a href='#' onclick='ver_voucher(this);'>".$nroVoucher."</a></td>";    
                    $fila       .= "</tr>";

                }
                $fila       .= "<tr>";
                if($monedadoc=='S'){
                    $fila       .= "<td colspan='9' align='right'>".number_format($total_servicios,2)."</td>";
                    $fila       .= "<td align='right'>".number_format($total_facturado,2)."</td>";  
                }elseif($monedadoc=='D'){
                    $fila       .= "<td colspan='9' align='right'>".number_format($total_servicios_dolar,2)."</td>";
                    $fila       .= "<td align='right'>".number_format($total_facturado_dolar,2)."</td>";
                }
                $fila       .= "</tr>";
            }
            else{
                $fila       .= "<tr><td colspan='12' align='center'>::NO EXISTEN REGISTROS::</td></tr>";
            }
        }
        elseif($tipoexport=="excel"){
            if(count($oServicio)>0){
                $arr_data    = array();
                $var_prd_n = 0;
                $var_row = 7;
                foreach($oServicio as $indice => $value){
                    $codigo    = $value->codot;
                    $tipser2   = $value->tipser;
                    $total     = $value->costo;
                    $codser    = $value->gcodser;
                    $fentrega  = $value->gfentrega;
                    $peso      = $value->gpeso;
                    $ruc       = $value->gruc;
                    if($ruc==0) $ruc ='00000000000';
                    $moneda    = $value->moneda;
                    $tipdoc    = $value->tipod;
                    $seriedoc  = $value->seried;
                    $nrodoc    = $value->nrod;
                    $tc        = $value->cambio;
                    $ser_guia  = $value->gserguia;
                    $num_guia  = $value->gnumguia;
                    $estado    = $value->gestado;
                    $observ    = $value->gobserva;
                    $fecemi    = $value->fecemi;
                    $frealiza  = date_sql($value->fdespacho);
                    $subtotal_a= $value->subtotal;
                    $igv_a     = $value->igv;
                    $total_a   = $value->costo;
                    $subtotal  = $moneda=='S'?$subtotal_a:($subtotal_a*$tc);
                    $igv       = $moneda=='S'?$igv_a:($igv_a*$tc);
                    $total     = $moneda=='S'?$total_a:($total_a*$tc);
                    $subtotal_dolar = $moneda=='D'?$subtotal_a:($subtotal_a/$tc);
                    $igv_dolar      = $moneda=='D'?$igv_a:($igv_a/$tc);
                    $total_dolar    = $moneda=='D'?$total_a:($total_a/$tc);    
                    $total_servicios = $total_servicios + $subtotal;
                    $total_servicios_dolar = $total_servicios_dolar + $subtotal_dolar;
                    $tipo_documento        = $tipdoc=='01'?"FV":"OTRO";
                    $filter11              = new stdClass();
                    $filter11_not          = new stdClass();
                    $filter11->ruccli      = trim($ruc);
                    $filter11->numguia     = trim($num_guia);
                    $factura_detalle       = $this->facturac_model->obtener_factura($filter11,$filter11_not);
                    $nrofac=$factura_detalle->NroDoc; 
                    $nroser=$factura_detalle->SerieDoc; 
                    $nrotip=$factura_detalle->TipDoc; 
                    $nroruc=$factura_detalle->RucCli; 
                    if($nrotip=='FV' && $nroser=='0000' && $nroser=='000000')
                    {
                        $valor=$num_guia;
                    }
                    else
                    {
                        $valor=$nrofac;
                    }
                    
                    //Obtengo el monto total de la factura de compra
                    $filter2               = new stdClass();
                    $filter2_not           = new stdClass();
                    $filter2->codot        = $codot;
                    $filter2->numero       = $nrodoc;
                    $filter2->seroc        = $ser_guia;
                    $filter2->nrooc        = $num_guia;
                    //$facturac              = $this->facturac_model->listar_totales($filter2,$filter2_not);/VOLVER A ACTIVAR
                    $montofacturaS         = 0;
                    $montofacturaD         = 0;
//                    if(isset($facturac->montoS)){
//                        $montofacturaS         = $facturac->montoS;//VOLVER A ACTIVAR
//                        $montofacturaD         = $facturac->montoD;//VOLVER A ACTIVAR    
//                    }
                    $total_facturado = $total_facturado + $montofacturaS;
                    $total_facturado_dolar = $total_facturado_dolar + $montofacturaD;
                    /*Obtengo el numero de voucher*/
                    $filter8               = new stdClass();
                    $filter8_not           = new stdClass();
                    $filter8->codot        = $codot;
                    $filter8->nrodocref    = $valor;
                    $nroVoucher            = "";                 
                    $oVoucher              = $this->voucher_model->listar_detalle2($filter8,$filter8_not);
                    if(is_array($oVoucher) && count($oVoucher)>0){
                        foreach($oVoucher as $indice2 => $value2){
                            $nroVoucher = $value2->NroVoucher;
                            $codot2     = $value2->codot;
                            break;
                            
                        }
                    }

                    // Servicios
                    $descripcion = "";  
                    if($codser!=''){
                        $filter3     = new stdClass();
                        $filter3_not = new stdClass();
                        $filter3->codservicio = $codser;
                        $objServicio   = $this->servicio_model->obtener($filter3,$filter3_not);
                        $descripcion   = $objServicio->DesPro;
                    }
                    // Nombre de ot
                    $filter7        = new stdClass();
                    $filter7_not    = new stdClass();
                    $filter7->codot = trim($codigo); 
                    $oOt2          = $this->ot_model->obtenerg($filter7,$filter7_not);
                    $nroOt2        = $oOt2->NroOt;   
                    /*Nombre del proveedor*/
                    $filter4     = new stdClass();
                    $filter4_not = new stdClass();
                    $filter4->ruccliente = $ruc;
                    $oProveedor  = $this->proveedor_model->obtener($filter4,$filter4_not);
                    $razcli      = $oProveedor->RazCli;
                    
                    $reqx='RS';
                    $filter5     = new stdClass();
                    $filter5_not = new stdClass();
                    $filter5->requis_voucher = $num_guia;
                  
                    $rvx  = $this->voucher_model->ReqVou($filter5,$filter5_not);
                   
                    if(isset($rvx-> NroVoucher))
                    $RVoucher      = $rvx-> NroVoucher;
                    
                    else
                    $RVoucher ='';  

                    if($monedadoc=='S'){
                        $var_costo   = $subtotal;
                        $var_factura = $montofacturaS;        
                    }
                    elseif($monedadoc=='D'){
                        $var_costo   = $subtotal_dolar;
                        $var_factura = $montofacturaD;        
                    }
                    
                
                    $arr_data[$var_prd_n] = array(
                        $nroOt2,
                        $ser_guia."-".$num_guia,
                        $fecemi,
                        ($frealiza=='30/12/1899'?'':$frealiza),
                        $codser."-".trim(utf8_encode($descripcion)),
                        $peso,
                        $ruc,
                        trim(utf8_encode($razcli)),
                        ($estado==1?"SI":"NO"),
                        $var_costo,
                        $var_factura,
                        $nrotip."-".$nroser."-".$nrofac,
                        $nroVoucher
                    );

                   $var_prd_n++; 
                   $var_row++;
                   
                    
                }
                
                $arr_columns = array();  
                $arr_columns[0]['STRING'] = 'Nro. OT';
                $arr_columns[1]['STRING'] = 'Req. Serv';
                $arr_columns[2]['DATE'] = 'Fec. Emision';
                $arr_columns[3]['DATE'] = 'Fec. Serv. Realizado';
                $arr_columns[4]['STRING'] = 'Tipo de Servicio';
                $arr_columns[5]['NUMERIC'] = 'Peso (KG)';
                $arr_columns[6]['STRING'] = 'RUC';
                $arr_columns[7]['STRING'] = 'Proveedor';
                $arr_columns[8]['STRING'] = 'Realizado';
                $arr_columns[9]['NUMERIC'] = 'Costo (S/.)';
                $arr_columns[10]['NUMERIC'] = 'Fact. (S/.)';
                $arr_columns[11]['STRING'] = 'Comprobante Pago';
                $arr_columns[12]['STRING'] = 'Voucher';
                $arr_grouping_header = array();
                $arr_grouping_header['A5:C5'] = 'Descripción';
                $this->reports_model->rpt_general('rpt_transporte','Transporte',$arr_columns,$arr_data ,$arr_grouping_header);
            }
        }
        $data['tipser'] = $tipser;
        $data['codot']  = $codot;
        $data['nroOt']  = $nroOt;
        $data['dirOt']  = $dirOt;
        $data['fila']   = $fila;
        $data['monedadoc']   = $monedadoc;
        $this->load->view(contabilidad."rpt_servicios",$data);
    }
    
    public function rpt_caja(){
        $codot      = $this->input->get_post('codot');
        $tipoexport = $this->input->get_post('tipoexport');
        $monedadoc  = $this->input->get_post('moneda');
        $fecha_ini  = $this->input->get_post('fecha_ini');
        $fecha_fin  = $this->input->get_post('fecha_fin');
        $cadenaot   = $this->input->get_post('cadenaot');
        $arrCodOT   = $codot!=''?array($codot):explode(",",$cadenaot);        
        $nroOt         = "";
        $dirOt         = "";
        $fila          = "";
        if(count($arrCodOT)==1){
            $filter2        = new stdClass();
            $filter2_not    = new stdClass();
            $filter2->codot = $arrCodOT[0];  
            $oOt       = $this->ot_model->obtenerg($filter2,$filter2_not);
            $nroOt     = $oOt->NroOt;
            $dirOt     = $oOt->DirOt;         
        }        
        /*Obtengo detalle de la caja chica*/
        $filter          = new stdClass();
        $filter_not      = new stdClass();
        $filter->codot   = $arrCodOT;
        $filter->fechai  = $fecha_ini;
        $filter->fechaf  = $fecha_fin;
        $order_by        = array('det.FecEmision'=>'desc');
        $oCaja           = $this->caja_model->listar_detalle($filter,$filter_not,$order_by);
        $fila            = "";
        $total_importe   = 0;
        $total_subtotal  = 0;
        $total_importeD  = 0;
        $total_subtotalD = 0;
        if(count($oCaja)>0 && isset($oCaja) && is_array($oCaja)){
            if($tipoexport==""){
              foreach($oCaja as $indice => $value){
                    $numero    = $value->NroCaja;    
                    $operacion = $value->NroOperacion;    
                    $codcli    = $value->Codcli;      
                    $fecemi    = $value->fecha2;      
                    $desope    = $value->DesOperacion;    
                    $moneda_doc= $value->Mo;    
                    $subtotal_a = $value->Subtotal;    
                    $igv_a      = $value->igv;     
                    $importe_a  = $value->ImpOperacion;    
                    $tc        = $value->Tc;    
                    $tipoodc   = $value->TipDocRef;     
                    $seriedoc  = $value->SerieDocRef;    
                    $nrodoc    = $value->NroDocRef;    
                    $codgas    = $value->CodGas;     
                    $motivo    = $value->Motivo;    
                    $dirOt2    = $value->dirOt;    
                    $nroOt2    = $value->NroOt; 
                    $tipcli    = $value->tPer; 
                    $subtotal  = $moneda_doc==2?$subtotal_a:($subtotal_a*$tc);      
                    $igv       = $moneda_doc==2?$igv_a:($igv_a*$tc);      
                    $importe   = $moneda_doc==2?$importe_a:($importe_a*$tc); 
                    $subtotalD  = $moneda_doc==3?$subtotal_a:($subtotal_a/$tc);      
                    $igvD       = $moneda_doc==3?$igv_a:($igv_a/$tc);      
                    $importeD   = $moneda_doc==3?$importe_a:($importe_a/$tc);     
                    $total_subtotal = $total_subtotal + $subtotal;
                    $total_importe = $total_importe + $importe;
                    $total_subtotalD = $total_subtotalD + $subtotalD;
                    $total_importeD = $total_importeD + $importeD; 
                    /*Tipo rodumento referencia*/
                    $tipodocref = $this->tipodocumento_caja_model->obtener($tipoodc);
                    $tipodocref_nombre = $tipodocref->Des_Larga;
                    /*Cliente*/
                    $filter2     = new stdClass();
                    $filter2_not = new stdClass();
                    if($tipcli=='02'){ 
                        $filter2->codcliente = $codcli;
                        $oCliente  = $this->proveedor_model->obtener($filter2,$filter2_not);
                        $razcli    = isset($oCliente->RazCli)?$oCliente->RazCli:""; 
                    }
                    elseif($tipcli=='03'){/*Trabajdor de MIMCO*/
                        $filter2->codresponsable = substr(trim($codcli),2,6);
                        $oCliente  = $this->responsable_model->obtener($filter2,$filter2_not);
                        
                        if(isset($oCliente->nomper)){
                            $razcli    = $oCliente->nomper; 
                        }else{
                            $razcli    ="";
                        }
                    }                                   
                    $fila     .= "<tr>";
                    $fila     .= "<td style='width:5%;'>".$nroOt2."</td>";
                    $fila     .= "<td style='width:5%;'><div id='".$numero."' style='width:80px;height:auto;'><a href='#' onclick='ver_cajas(this);'>".$numero."</a></div></td>";
                    $fila     .= "<td style='width:5%;'>".$operacion."</td>";
                    $fila     .= "<td style='width:10%;' align='left'>".utf8_encode(trim($dirOt2))."</td>";
                    $fila     .= "<td style='width:20%;' align='left'>".utf8_encode(trim($razcli))."</td>";
                    $fila     .= "<td style='width:10%;' align='center'>".utf8_encode(trim($tipodocref_nombre))."</td>";
                    $fila     .= "<td style='width:10%;' align='center'>".$seriedoc."-".$nrodoc."</td>";    
                    $fila     .= "<td style='width:10%;' align='center'>".$fecemi."</td>";
                    $fila     .= "<td style='width:20%;' align='left'>".utf8_encode(trim($desope))."</td>";
                    if($monedadoc=='S'){
                        $fila     .= "<td style='width:5%;' align='right'>".number_format($subtotal,2)."</td>";
                    }
                    elseif($monedadoc=='D'){
                        $fila     .= "<td style='width:5%;' align='right'>".number_format($subtotalD,2)."</td>";
                    }
                    $fila     .= "</tr>";
                }
                $fila     .= "<tr>";
                $fila     .= "<td colspan='9' align='right'>&nbsp;</td>";
                if($monedadoc=='S'){
                    $fila .= "<td align='right'>".number_format($total_subtotal,2)."</td>";    
                }
                elseif($monedadoc=='D'){
                    $fila     .= "<td align='right'>".number_format($total_subtotalD,2)."</td>";
                }
                $fila     .= "</tr>";                
            }
            elseif($tipoexport=="excel"){
                $arr_data    = array();
                $var_prd_n = 0;
                $var_row = 7;
                foreach($oCaja as $indice => $value){
                    $numero    = $value->NroCaja;    
                    $operacion = $value->NroOperacion;    
                    $codcli    = $value->Codcli;      
                    $fecemi    = $value->fecha2;      
                    $desope    = $value->DesOperacion;    
                    $moneda_doc= $value->Mo;    
                    $subtotal_a = $value->Subtotal;    
                    $igv_a      = $value->igv;     
                    $importe_a  = $value->ImpOperacion;    
                    $tc        = $value->Tc;    
                    $tipoodc   = $value->TipDocRef;     
                    $seriedoc  = $value->SerieDocRef;    
                    $nrodoc    = $value->NroDocRef;    
                    $codgas    = $value->CodGas;     
                    $motivo    = $value->Motivo;    
                    $dirOt2    = $value->dirOt;    
                    $nroOt2    = $value->NroOt; 
                    $tipcli    = $value->tPer; 
                    $subtotal  = $moneda_doc==2?$subtotal_a:($subtotal_a*$tc);      
                    $igv       = $moneda_doc==2?$igv_a:($igv_a*$tc);      
                    $importe   = $moneda_doc==2?$importe_a:($importe_a*$tc); 
                    $subtotalD  = $moneda_doc==3?$subtotal_a:($subtotal_a/$tc);      
                    $igvD       = $moneda_doc==3?$igv_a:($igv_a/$tc);      
                    $importeD   = $moneda_doc==3?$importe_a:($importe_a/$tc);     
                    $total_subtotal  = $total_subtotal + $subtotal;
                    $total_importe   = $total_importe + $importe;
                    $total_subtotalD = $total_subtotalD + $subtotalD;
                    $total_importeD  = $total_importeD + $importeD; 
                    $tipodocref         = $this->tipodocumento_caja_model->obtener($tipoodc);
                    $tipodocref_nombre  = $tipodocref->Des_Larga;
                    $filter2     = new stdClass();
                    $filter2_not = new stdClass();
                    if($tipcli=='02'){ 
                        $filter2->codcliente = $codcli;
                        $oCliente  = $this->proveedor_model->obtener($filter2,$filter2_not);
                        $razcli    = isset($oCliente->RazCli)?$oCliente->RazCli:""; 
                    }
                    elseif($tipcli=='03'){
                        $filter2->codresponsable = substr(trim($codcli),2,6);
                        $oCliente  = $this->responsable_model->obtener($filter2,$filter2_not);
                        
                        if(isset($oCliente->nomper)){
                            $razcli    = $oCliente->nomper; 
                        }else{
                            $razcli    ="";
                        }
                    }                                   
                    if($monedadoc=='S'){
                        $var_subtotal = $subtotal;
                    }
                    elseif($monedadoc=='D'){
                        $var_subtotal = $subtotalD;
                    }
                    
                    
                    $arr_data[$var_prd_n] = array(
                        $nroOt2,
                        $numero,
                        $operacion,
                        utf8_encode(trim($dirOt2)),
                        utf8_encode(trim($razcli)),
                        utf8_encode(trim($tipodocref_nombre)),
                        $seriedoc."-".$nrodoc,
                        trim($fecemi),
                        utf8_encode(trim($desope)),
                        $var_subtotal
                    );

                   $var_prd_n++; 
                   $var_row++;

                }
  
                $arr_columns = array();  
                $arr_columns[0]['STRING'] = 'Nro. OT';
                $arr_columns[1]['STRING'] = 'Nro. Caja';
                $arr_columns[2]['STRING'] = 'Item';
                $arr_columns[3]['STRING'] = 'Referencia';
                $arr_columns[4]['STRING'] = 'Proveedor';
                $arr_columns[5]['STRING'] = 'Tip. Doc';
                $arr_columns[6]['STRING'] = 'Nro. Documento';
                $arr_columns[7]['DATE'] = 'Fecha Documento';
                $arr_columns[8]['STRING'] = 'Descripción';
                $arr_columns[9]['NUMERIC'] = 'Subtotal (S/.)';


                $arr_grouping_header = array();
                $arr_grouping_header['A5:E5'] = 'Descripción';
                $this->reports_model->rpt_general('rpt_caja_chica','Caja Chica',$arr_columns,$arr_data ,$arr_grouping_header);
            
            }
        }
        else{
            $fila .= "<tr><td colspan='10' align='center'>::NO EXISTEN REGISTROS:::</td></tr>";
        }
        $data['nroOt'] = $nroOt;
        $data['codot'] = $codot;
        $data['dirOt'] = $dirOt;
        $data['fila']  = $fila;
        $data['monedadoc']   = $monedadoc;
        $this->load->view(contabilidad."rpt_caja",$data);
    }
    
    public function rpt_tesoreria(){
        $codot         = $this->input->get_post('codot');
        $tipoexport    = $this->input->get_post('tipoexport');
        $monedadoc     = $this->input->get_post('moneda');
        $fecha_ini     = $this->input->get_post('fecha_ini');
        $fecha_fin     = $this->input->get_post('fecha_fin');
        $cadenaot      = $this->input->get_post('cadenaot');
        $codpartida    = $this->input->get_post('codpartida');
        $var_codpartida    = $this->input->get_post('codpartida');
        $verencabezado = $this->input->get_post('verencabezado');
        /*Nombre de la partida*/
        $n_partida     = "";
        if(trim($codpartida)!=''){
            $filter        = new stdClass();
            $filter_not    = new stdClass();            
            $filter->codpartida = $codpartida;
            $partidas      = $this->partida_model->obtener($filter,$filter_not);
            $n_partida     = $partidas->des_larga;  
        }
        $arrCodOT      = $codot!=''?array($codot):explode(",",$cadenaot);
        $nroOt         = "";
        $dirOt         = "";
        $fila          = "";
        if(count($arrCodOT)==1){
            $filter2        = new stdClass();
            $filter2_not    = new stdClass();
            $filter2->codot = $arrCodOT[0];            
            $oOt       = $this->ot_model->obtenerg($filter2,$filter2_not);
            $nroOt     = $oOt->NroOt;
            $dirOt     = $oOt->DirOt;         
        }
        
        /*Detalle de partida*/
        $pres_soles  = 0;
        $pres_dolar  = 0;
        $mod_soles   = 0;
        $mod_dolar   = 0;
        if(count($arrCodOT)==1){
            $filter       = new stdClass();
            $filter_not   = new stdClass();
            $filter->codot       = $arrCodOT[0];
            $filter->estado      = "P";
            if($codpartida!="")  $filter->codpartida = $codpartida;
            $oCtrlObras  = $this->ctrlobras_model->listar($filter,$filter_not);
            foreach($oCtrlObras as $indice => $value){
                $tipodoc      = $value->TipDoc;
                $nrodoc       = $value->NroDoc;
                $fecdoc       = $value->fecha2;
                $mtodoc       = $value->MtoDoc2;
                $mtomod       = $value->Mtomod2;
                $mondoc       = $value->Mo;
                $tipsol       = $value->TipSol;
                $observacion  = $value->Obs;
                $tc_pres      = $value->tcambio;
                if($mondoc==2){
                    $pres_soles   = $pres_soles + $mtodoc;
                    $pres_dolar   = $pres_dolar + ($mtodoc/$tc_pres);
                    $mod_soles    = $mod_soles + $mtomod;
                    $mod_dolar    = $mod_dolar + ($mtomod/$tc_pres);                    
                }
                elseif($mondoc==3){
                    $pres_soles   = $pres_soles + ($mtodoc*$tc_pres);
                    $pres_dolar   = $pres_dolar + $mtodoc;
                    $mod_soles    = $mod_soles + ($mtomod*$tc_pres);
                    $mod_dolar    = $mod_dolar + $mtomod; 
                }
            }
        }
        
        /*Listado de vouchers para la OT*/
        $fila   = "";
        $total_movilidad = 0;
        $total_otros_costos = 0;
        $total_otros_costos_dolar = 0;
        $j = 1;        
        $filter2      = new stdClass();
        $filter2_not  = new stdClass();
        $filter2->codot      = $arrCodOT;
        if($codpartida=='13') $filter2_not->codtipomov = array('03','19','02','08');
        if($fecha_ini!='')    $filter2->fechai = $fecha_ini;
        if($fecha_fin!='')    $filter2->fechaf = $fecha_fin;
        if($codpartida!="")   $filter2->codpartida = $codpartida;   
        $monto_totals=0;
        $order_by = array('p.FecPago'=>'desc');
        $oVoucher = $this->voucher_model->listar_detalle2($filter2,$filter2_not,$order_by);
        if(is_array($oVoucher) && count($oVoucher)>0){
            if($tipoexport==""){
              foreach($oVoucher as $indice => $value){
                    $importe     = $value->ImpPdet;
                    $descripcion = $value->DesPago;
                    $codvoucher  = $value->TipPago;
                    $codpartida  = $value->codpartida;
                    $codCtrl     = $value->CodCtrl;
                    $nro_voucer  = $value->NroVoucher;
                    $tipodoc     = $value->TipoDocRef;
                    $seriedoc    = $value->SerieDocRef;
                    $numdoc      = $value->NroDocRef;
                    $nro_cheque  = $value->NroCheque;
                    $moneda_doc  = $value->MO;
                    $cod_tipsol  = $value->TipSolPago;
                    $cod_solicita = $value->CodSolicita;
                    $tc           = $value->Tc;
                    $flgIgv       = $value->Igv;
                    $fec_pago     = $value->fecha2;
                    $codot2       = $value->codot;
                    /*Numero de ot2*/
                    $filter4        = new stdClass();
                    $filter4_not    = new stdClass();
                    $filter4->codot = $codot2; 
                    $oOt2          = $this->ot_model->obtenerg($filter4,$filter4_not);
                    $nroOt2        = $oOt2->NroOt;    
                    $seroc  = "";
                    $nrooc  = "";
                    $tipooc = "";
                    if($tipodoc=="FV"){
                        $filter5         = new stdClass();
                        $filter5_not     = new stdClass();   
                        $filter5->serie  = $seriedoc;
                        $filter5->numero = $numdoc;
                        $filter5->codot  = $codot2;
                        /*Esto debe obtener ahora la seroc o nrooc del detalle de la tabla reposicion*/
                        $facturasc       = $this->facturac_model->obtener($filter5,$filter5_not);
                        $seroc           = isset($facturasc->SerOC)?trim($facturasc->SerOC):"";
                        $nrooc           = isset($facturasc->NroOC)?$facturasc->NroOC:"";
                        if($seroc=='007')  $tipooc = 'RS'; elseif($seroc=='003') $tipoc = 'OC';
                    }
                    elseif($tipodoc=="RS"){
                        $seroc = $seriedoc;
                        $nrooc = $numdoc;
                        $seriedoc = "";
                        $numdoc   = "";
                        $tipodoc  = "";
                        $tipooc   = "RS";
                    }
                    /*Obtenemos el nombre del tipo de voucher*/
                    $importe      = $flgIgv=='C'?($importe/1.18):$importe;
                    $importe_soles   = $moneda_doc==2?$importe:($importe*$tc);
                    $importe_dolares = $moneda_doc==3?$importe:($importe/$tc);
                    $total_otros_costos = $total_otros_costos + $importe_soles;
                    $total_otros_costos_dolar = $total_otros_costos_dolar + $importe_dolares;                    
                    $filter3      = new stdClass();
                    $filter3_not  = new stdClass();
                    $filter3->codvoucher = $codvoucher;
                    $oTipoVoucher  = $this->tvoucher_model->obtener($filter3,$filter3_not);
                    $tipo_pago     = isset($oTipoVoucher->Des_Larga)?$oTipoVoucher->Des_Larga:"";                
                    $fila .= "<tr>";
                    $fila .= "<td align='center'><div id='".trim($codot2)."' style='width:80px;height:auto;'>".$nroOt2."</div></td>";
                    $fila .= "<td align='center'><div id='".$nro_voucer."' style='width:80px;height:auto;'><a href='#' onclick='ver_voucher(this);'>".$nro_voucer."</a></div></td>";
                    $fila .= "<td align='center'><div style='width:80px;height:auto;'>".$fec_pago."</div></td>";
                    $fila .= "<td align='left'><div style='width:80px;height:auto;'>".$codvoucher."-".$tipo_pago."</div></td>";    
                    $fila .= "<td align='left'><div style='width:80px;height:auto;'>".$codpartida."</div></td>";    
                    $fila .= "<td align='left'><div style='width:150px;height:auto;'>".$descripcion."</div></td>";    
                    $fila .= "<td align='center'><div style='width:80px;height:auto;'>".$nro_cheque."</div></td>";
                    $fila .= "<td align='center'><div style='width:80px;height:auto;'>".$cod_solicita."</div></td>";
                    ///$fila .= "<td align='center'>".$tipodoc."-".$seriedoc."-".$numdoc."</a></div></td>";
                    $fila .= "<td align='center'><div id='".trim($numdoc)."' id2='".trim($seriedoc)."' id3='".trim($codot)."' id4='".trim($cod_solicita)."'  style='width:100px;height:auto;'>".($nrooc==''?'-':"<a href='#' onclick='ver_facturac(this);'>".$tipodoc."-".$seriedoc."-".$numdoc)."</a></td>";
                    $fila .= "<td align='center'><div id='".trim($nrooc)."' id2='".trim($seroc)."' id3='".trim($tipooc)."'  style='width:100px;height:auto;'>".($nrooc==''?'-':"<a href='#' onclick='ver_ocos(this);'>".trim($tipooc)."-".trim($seroc)."-".trim($nrooc))."</a></td>";
                    //$fila .= "".($xi!='- -' )?$tipooc."-".$seroc."-".$nrooc:''."</a></div></td>";
                    if($monedadoc=='S'){
                        $fila .= "<td align='right'><div style='width:50px;height:auto;'>".number_format($importe_soles,2)."</div></td>";//$importe_soles
                    }
                    elseif($monedadoc=='D'){
                        $fila .= "<td align='right'><div style='width:50px;height:auto;'>".number_format($importe_dolares,2)."</div></td>";    
                    }
                    $fila .= "</tr>";
                    $j++;
                }
                $fila .= "<tr>";
                $fila .= "<td colspan='10' align='right'>&nbsp;</td>";
                if($monedadoc=='S'){
                    $fila .= "<td align='right'>".number_format($total_otros_costos,2)."</td>";    
                }
                elseif($monedadoc=='D'){
                    $fila .= "<td align='right'>".number_format($total_otros_costos_dolar,2)."</td>";
                }
                $fila .= "</tr>";     
            }
            elseif($tipoexport=='excel'){
                
                $arr_data    = array();
                $var_prd_n = 0;
                $var_row = 7;

                
                 foreach($oVoucher as $indice => $value){
                    $importe     = $value->ImpPdet;
                    $descripcion = $value->DesPago;
                    $codvoucher  = $value->TipPago;
                    $codCtrl     = $value->CodCtrl;
                    $nro_voucer  = $value->NroVoucher;
                    $tipodoc     = $value->TipoDocRef;
                    $seriedoc    = $value->SerieDocRef;
                    $numdoc      = $value->NroDocRef;
                    $nro_cheque  = $value->NroCheque;
                    $moneda_doc  = $value->MO;
                    $cod_tipsol  = $value->TipSolPago;
                    $cod_solicita = $value->CodSolicita;
                    $tc           = $value->Tc;
                    $flgIgv       = $value->Igv;
                    $fec_pago     = $value->fecha2;
                    $codot2       = $value->codot;
                    /*Numero de ot2*/
                    $filter4        = new stdClass();
                    $filter4_not    = new stdClass();
                    $filter4->codot = $codot2; 
                    $oOt2          = $this->ot_model->obtenerg($filter4,$filter4_not);
                    $nroOt2        = $oOt2->NroOt;    
                    /**/
                    $importe      = $flgIgv=='C'?($importe/1.18):$importe;
                    $importe_soles   = $moneda_doc==2?$importe:($importe*$tc);
                    $importe_dolares = $moneda_doc==3?$importe:($importe/$tc);
                    $total_otros_costos = $total_otros_costos + $importe_soles;
                    $total_otros_costos_dolar = $total_otros_costos_dolar + $importe_dolares;
                    /*Obtengo la orden de compra/servicio*/
                    $seroc  = "";
                    $nrooc  = "";
                    $tipooc = "";
                    if($tipodoc=="FV"){
                        $filter5         = new stdClass();
                        $filter5_not     = new stdClass();   
                        $filter5->serie  = $seriedoc;
                        $filter5->numero = $numdoc;
                        $filter5->codot  = $codot2;
                        $facturasc       = $this->facturac_model->obtener($filter5,$filter5_not);
                        $seroc           = isset($facturasc->SerOC)?trim($facturasc->SerOC):"";
                        $nrooc           = isset($facturasc->NroOC)?$facturasc->NroOC:"";
                        if($seroc=='007')  $tipooc = 'RS'; elseif($seroc=='003') $tipoc = 'OC';
                    }
                    /*Obtenemos el nombre del tipo de voucher*/
                    $filter3      = new stdClass();
                    $filter3_not  = new stdClass();
                    $filter3->codvoucher = $codvoucher;
                    $oTipoVoucher  = $this->tvoucher_model->obtener($filter3,$filter3_not);
                    $tipo_pago     = isset($oTipoVoucher->Des_Larga)?$oTipoVoucher->Des_Larga:"";                  
                    if($monedadoc=='S'){
                        $var_total = $importe_soles;
                    }
                    elseif($monedadoc=='D'){
                        $var_total = $importe_dolares; 
                    }
                    $arr_data[$var_prd_n] = array(
                        $nroOt2,
                        $nro_voucer,
                        $fec_pago,
                        $codvoucher."-".$tipo_pago,
                        utf8_encode(trim($descripcion)),
                        $nro_cheque,
                        $cod_solicita,
                        trim($tipodoc)."-".trim($seriedoc)."-".trim($numdoc),
                        trim($tipooc)."-".trim($seroc)."-".trim($nrooc),
                        $var_total
                    );
                     
                    
                
                    
                    $var_prd_n++; 
                    $var_row++;
                 }
                
                $arr_columns[0]['STRING'] = 'OT';     
                $arr_columns[1]['STRING'] = 'Voucher';        
                $arr_columns[2]['DATE'] = 'Fecha';        
                $arr_columns[3]['STRING'] = 'Tip. Movimiento';        
                $arr_columns[4]['STRING'] = 'Descripción';        
                $arr_columns[5]['STRING'] = 'Cheque';        
                $arr_columns[6]['STRING'] = 'A la Orden';
                $arr_columns[7]['STRING'] = 'Numero Doc.';
                $arr_columns[8]['STRING'] = 'OC / OS';
                $arr_columns[9]['NUMERIC'] = 'Importe';
                $arr_grouping_header = array();
                $arr_grouping_header['A5:D5'] = 'Descripción';
                $this->reports_model->rpt_general('rpt_tesoreria','TESORERIA DEL : '.$fecha_ini." al ".$fecha_fin,$arr_columns,$arr_data ,$arr_grouping_header); 
            }
        }
        /*INCLUYO LA CAJA CHICA EN PARTIDA CONTINGENCIA(10)*/
        $fila2  = "";
        if($codpartida=='10'){
            $filter3         = new stdClass();
            $filter3_not     = new stdClass();
            $filter3->codot  = $arrCodOT;
            $filter3->fechai = $fecha_ini;
            $filter3->fechaf = $fecha_fin;
            $order_by3       = array('det.FecEmision'=>'desc');
            $oCaja           = $this->caja_model->listar_detalle($filter3,$filter3_not,$order_by3);  
            $fila2           = "";
            $total_importe  = 0;
            $total_subtotal = 0;
            $total_importeD  = 0;
            $total_subtotalD = 0;
            if(count($oCaja)>0 && isset($oCaja) && is_array($oCaja)){
                if($tipoexport==""){
                 foreach($oCaja as $indice => $value2){
                        $numero    = $value2->NroCaja;    
                        $operacion = $value2->NroOperacion;    
                        $codcli    = $value2->Codcli;      
                        $fecemi    = $value2->fecha2;      
                        $desope    = $value2->DesOperacion;    
                        $moneda_doc2 = $value2->Mo;    
                        $subtotal_a = $value2->Subtotal;    
                        $igv_a      = $value2->igv;     
                        $importe_a  = $value2->ImpOperacion;    
                        $tc2        = $value2->Tc;    
                        $tipoodc   = $value2->TipDocRef;     
                        $seriedoc  = $value2->SerieDocRef;    
                        $nrodoc    = $value2->NroDocRef;    
                        $codgas    = $value2->CodGas;     
                        $motivo    = $value2->Motivo;    
                        $dirOt2    = $value2->dirOt;   
                        $nroOt2    = $value2->NroOt; 
                        $tipcli    = $value2->tPer; 
                        $subtotal  = $moneda_doc2==2?$subtotal_a:($subtotal_a*$tc2);      
                        $igv       = $moneda_doc2==2?$igv_a:($igv_a*$tc2);      
                        $importe   = $moneda_doc2==2?$importe_a:($importe_a*$tc2); 
                        $subtotalD  = $moneda_doc2==3?$subtotal_a:($subtotal_a/$tc2);      
                        $igvD       = $moneda_doc2==3?$igv_a:($igv_a/$tc2);      
                        $importeD   = $moneda_doc2==3?$importe_a:($importe_a/$tc2);     
                        $total_subtotal = $total_subtotal + $subtotal;
                        $total_importe = $total_importe + $importe;
                        $total_subtotalD = $total_subtotalD + $subtotalD;
                        $total_importeD = $total_importeD + $importeD; 
                        /*Tipo rodumento referencia*/
                        $tipodocref = $this->tipodocumento_caja_model->obtener($tipoodc);
                        $tipodocref_nombre = $tipodocref->Des_Larga;                        
                        /*Cliente*/
                        $razcli    = "";
                        $filter2     = new stdClass();
                        $filter2_not = new stdClass();
                        if($tipcli=='02'){ 
                            $filter2->codcliente = $codcli;
                            $oCliente  = $this->proveedor_model->obtener($filter2,$filter2_not);
                            $razcli    = isset($oCliente->RazCli)?$oCliente->RazCli:""; 
                        }
                        elseif($tipcli=='03'){/*Trabajdor de MIMCO*/
                            $filter2->codresponsable = substr(trim($codcli),2,6);
                            $oCliente  = $this->responsable_model->obtener($filter2,$filter2_not);

                            if(isset($oCliente->nomper))
                            {
                            $razcli    = $oCliente->nomper; 
                            }
                            else
                            {
                                $razcli    ="";
                            }
                        }     
                        $fila2     .= "<tr>";
                        $fila2     .= "<td style='width:5%;'>".$nroOt2."</td>";
                        $fila2     .= "<td style='width:5%;'><div id='".$numero."' style='width:80px;height:auto;'><a href='#' onclick='ver_cajas(this);'>".$numero."</a></div></td>";
                        $fila2     .= "<td style='width:5%;'>".$operacion."</td>";
                        $fila2     .= "<td style='width:10%;' align='left'>".$dirOt2."</td>";
                        $fila2     .= "<td style='width:20%;' align='left'>".$razcli."</td>";
                        $fila2     .= "<td style='width:10%;' align='center'>".$tipoodc."</td>";
                        $fila2     .= "<td style='width:10%;' align='center'>".$seriedoc."-".$nrodoc."</td>";    
                        $fila2     .= "<td style='width:10%;' align='center'>".$fecemi."</td>";
                        $fila2     .= "<td style='width:20%;' align='left'>".$desope."</td>";
                        if($monedadoc=='S'){
                            $fila2     .= "<td style='width:5%;' align='right'>".number_format($subtotal,2)."</td>";
                        }
                        elseif($monedadoc=='D'){
                            $fila2     .= "<td style='width:5%;' align='right'>".number_format($subtotalD,2)."</td>";
                        }
                        $fila2     .= "</tr>";
                    }
                    $fila2     .= "<tr>";
                    $fila2     .= "<td colspan='9' align='right'>&nbsp;</td>";
                    if($monedadoc=='S'){
                        $fila2 .= "<td align='right'>".number_format($total_subtotal,2)."</td>";    
                    }
                    elseif($monedadoc=='D'){
                        $fila2     .= "<td align='right'>".number_format($total_subtotalD,2)."</td>";
                    }
                    $fila2     .= "</tr>";   
                    $total_otros_costos = $total_otros_costos + $total_subtotal;
                    $total_otros_costos_dolar = $total_otros_costos_dolar + $total_subtotalD;
                }
//                elseif($tipoexport=="excel"){
//                    $xls = new Spreadsheet_Excel_Writer();
//                    $xls->send("Rpt_codpartida10.xls");
//                    $sheet  =$xls->addWorksheet('Reporte');
//                    $sheet->setColumn(0,0,9); //COLUMNA A1
//                    $sheet->setColumn(1,1,41); //COLUMNA B2
//                    $sheet->setColumn(2,2,29); //COLUMNA C3
//                    $sheet->setColumn(3,3,12); //COLUMNA D4
//                    $sheet->setColumn(4,4,15); //COLUMNA E5
//                    $sheet->setColumn(5,5,18); //COLUMNA F6
//                    $sheet->setColumn(6,6,18); //COLUMNA G7
//                    $sheet->setRow(0,50);
//                    $sheet->setRow(1,42);
//                    $format_bold=$xls->addFormat();
//                    $format_bold->setBold();
//                    $format_bold->setvAlign('vcenter');
//                    $format_bold->sethAlign('left');
//                    $format_bold->setBorder(1);
//                    $format_bold->setTextWrap();
//                    $format_bold2=$xls->addFormat();
//                    $format_bold2->setBold();
//                    $format_bold2->setvAlign('vcenter');
//                    $format_bold2->sethAlign('center');
//                    $format_bold2->setBorder(1);
//                    $format_bold2->setTextWrap();
//                    $format_titulo=$xls->addFormat();
//                    $format_titulo->setBold();
//                    $format_titulo->setSize(16);
//                    $format_titulo->setvAlign('vcenter');
//                    $format_titulo->sethAlign('center');
//                    $format_titulo->setBorder(1);
//                    $format_titulo->setTextWrap();
//                    $format_titulo2=$xls->addFormat();
//                    $format_titulo2->setBold();
//                    $format_titulo2->setSize(12);
//                    $format_titulo2->setvAlign('vcenter');
//                    $format_titulo2->sethAlign('center');
//                    $format_titulo2->setBorder(1);
//                    $format_titulo2->setTextWrap();
//                    $sheet->mergeCells(0,0,0,9);   
//                //  $sheet->write(0,1,"Reporte de Costos por OT del ".$fecha_ini." al ".$fecha_fin." ",$format_titulo);  
//                 // $sheet->write(0,4,"OT: ".$fecha_ini."   ".$fecha_fin." ",$format_titulo); 
//                    $sheet->write(1,0,"CODIGO",$format_titulo2);  $sheet->write(1,1,"LINEA",$format_titulo2);  $sheet->write(1,2,"DESCRIPCION",$format_titulo2);  $sheet->write(1,3,"FECHA",$format_titulo2);   $sheet->write(1,4,"CANTIDAD",$format_titulo2);   if($monedadoc=='S'){    $sheet->write(1,5,"PRECIO S/.",$format_titulo2);   $sheet->write(1,6,"TOTAL S/.",$format_titulo2);     $sheet->write(1,7,"NUMERO DOCUMENTO",$format_titulo2);  }else{    $sheet->write(1,5,"PRECIO $.",$format_titulo2);   $sheet->write(1,6,"TOTAL $.",$format_titulo2);     $sheet->write(1,7,"NUMERO DOCUMENTO",$format_titulo2);   }        
//                    $z=2;
//                    $y=2;
//                    echo "bbb";
//                }
            } 
        }
        /*INCLUYO COSTO DE SERVICIOS EN LA PARTIDA CONTINGENCIA (10)*/
        $fila4 = "";
        if($codpartida=='10'){  
            $filter5      = new stdClass();
            $filter5_not  = new stdClass();
            $filter5_not->codservicio     = array('000000000010','000000000002','000000000074','000000000057','000000000001','000000000086','000000000084','000000000048','000000000049','000000000047','000000000046','000000000071');                
            $filter5->codot  = $arrCodOT;
            $filter5->fechai = $fecha_ini;
            $filter5->fechaf = $fecha_fin;  
            //$filter5->frealizadoi = $fecha_ini;
            //$filter5->frealizadof = $fecha_fin;   
            $order_by            = array('r.Fdespacho desc');
            $oServicio   = $this->requiser_model->listarg($filter5,$filter5_not,$order_by);
            $total_servicios       = 0;
            $total_servicios_dolar = 0;
            $total_facturado       = 0;
            $total_facturado_dolar = 0;  
            if(count($oServicio)>0){
                if($tipoexport==""){
                     foreach($oServicio as $indice => $value4){
                       $codigo     = $value4->codot;
                        $tipser    = $value4->tipser;
                        $total     = $value4->costo;
                        $codser    = $value4->gcodser;
                        $fentrega  = $value4->gfentrega;
                        $peso      = $value4->gpeso;
                        $ruc       = $value4->gruc;
                        $moneda    = $value4->moneda;
                        $tipdoc    = $value4->tipod;
                        $seriedoc  = $value4->seried;
                        $nrodoc    = $value4->nrod;
                        $tc        = $value4->cambio;
                        $ser_guia  = $value4->gserguia;
                        $num_guia  = $value4->gnumguia;
                        $estado    = $value4->gestado;
                        $observ    = $value4->gobserva;
                        $fecemi    = $value4->fecemi;
                        $frealiza  = $value4->fdespacho;
                        $subtotal_a= $value4->subtotal;
                        $igv_a     = $value4->igv;
                        $total_a   = $value4->costo;
                        /*Numero de ot2*/
                        $filter8        = new stdClass();
                        $filter8_not    = new stdClass();
                        $filter8->codot = $codigo; 
                        $oOt2           = $this->ot_model->obtenerg($filter8,$filter8_not);
                        $nroOt2         = $oOt2->NroOt;                      
                        $subtotal       = $moneda=='S'?$subtotal_a:($subtotal_a*$tc);
                        $igv            = $moneda=='S'?$igv_a:($igv_a*$tc);
                        $total          = $moneda=='S'?$total_a:($total_a*$tc);
                        $subtotal_dolar = $moneda=='D'?$subtotal_a:($subtotal_a/$tc);
                        $igv_dolar      = $moneda=='D'?$igv_a:($igv_a/$tc);
                        $total_dolar    = $moneda=='D'?$total_a:($total_a/$tc);    
                        $total_servicios = $total_servicios + $subtotal;
                        $total_servicios_dolar = $total_servicios_dolar + $subtotal_dolar;
                        $tipo_documento        = $tipdoc=='01'?"FV":"OTRO";
                        /*Obtengo el monto total de la factura de compra*/
                        $filter2               = new stdClass();
                        $filter2_not           = new stdClass();
                        $filter2->codot        = $codot;
                        $filter2->serie        = $seriedoc;
                        $filter2->numero       = $nrodoc;
                        $facturac              = $this->facturac_model->listar_totales($filter2,$filter2_not);
                        $montofacturaS         = 0;
                        $montofacturaD         = 0;
                        if(isset($facturac->montoS)){
                            $montofacturaS         = $facturac->montoS;
                            $montofacturaD         = $facturac->montoD;    
                        }
                        $total_facturado = $total_facturado + $montofacturaS;
                        $total_facturado_dolar = $total_facturado_dolar + $montofacturaD;
                        /*Obtengo el numero de voucher*/
                        $filter8               = new stdClass();
                        $filter8_not           = new stdClass();
                        $filter8->codot        = $codot;
                        $filter8->tipdocref    = $tipo_documento;
                        //$filter8->seriedocref  = $seriedoc;
                        $filter8->nrodocref    = $nrodoc;
                        $nroVoucher            = "";                 
                        $oVoucher              = $this->voucher_model->listar_detalle2($filter8,$filter8_not);
                        if(is_array($oVoucher) && count($oVoucher)>0){
                            foreach($oVoucher as $indice2 => $value2){
                                $nroVoucher = $value2->NroVoucher;
                                $codot2     = $value2->codot;
                                break;
                            }
                        }
                        /*Servicios*/
                        $descripcion = "";  
                        if($codser!=''){
                            $filter6     = new stdClass();
                            $filter6_not = new stdClass();
                            $filter6->codservicio = $codser;
                            $oServicio   = $this->servicio_model->obtener($filter6,$filter6_not);
                            $descripcion = $oServicio->DesPro;
                        }
                        /*Nombre del proveedor*/
                        $filter7     = new stdClass();
                        $filter7_not = new stdClass();
                        $filter7->ruccliente = $ruc;
                        $oProveedor  = $this->proveedor_model->obtener($filter7,$filter7_not);
                        $razcli      = isset($oProveedor->RazCli)?$oProveedor->RazCli:'';
                        $fila4       .= "<tr>";
                        $fila4       .= "<td align='center'><div style='width:80px;'>".$nroOt2."</a></td>";       
                        $fila4       .= "<td align='center'><div style='width:80px;' id='".trim($num_guia)."' id2='".trim($codot)."'><a href='#' onclick='ver_requis_ser(this);'>".$ser_guia."-".$num_guia."</a></td>";
                        $fila4       .= "<td align='center'><div style='width:80px;'>".date_sql($frealiza)."</a></td>";
                        $fila4       .= "<td align='left'><div style='width:120px;'>".$codser."-".$descripcion."</a></td>";
                        $fila4       .= "<td align='right'><div style='width:80px;'>".number_format($peso,2)."</a></td>";
                        $fila4       .= "<td align='center'><div title='".$ruc."' style='width:100px;'>".$razcli."</a></td>";
                        $fila4       .= "<td align='center'><div style='width:100px;'>".($estado==1?"SI":"NO")."</a></td>";
                        if($monedadoc=='S'){
                            $fila4       .= "<td align='right'><div style='width:80px;'>".number_format($subtotal,2)."</a></td>";
                            $fila4       .= "<td align='right'><div style='width:80px;'>".number_format($montofacturaS,2)."</a></td>";        
                        }
                        elseif($monedadoc=='D'){
                            $fila4       .= "<td align='right'><div style='width:80px;'>".number_format($subtotal_dolar,2)."</a></td>";
                            $fila4       .= "<td align='right'><div style='width:80px;'>".number_format($montofacturaD,2)."</a></td>";        
                        }
                        if($nroVoucher!=''){
                            $fila4       .= "<td align='center'><div style='width:100px;'><a href='factura.php?tipo=".$tipdoc."&serie=".$seriedoc."&numero=".$nrodoc."' target='_blank'>".$tipo_documento."-".$seriedoc."-".$nrodoc."</a></td>";             
                        }
                        else{
                            $fila4       .= "<td align='center'><div style='width:100px;'>&nbsp;</td>";     
                        }
                        $fila4       .= "<td align='center'><div  style='width:100px;'><a href='voucher.php?numero=".$nroVoucher."' target='_blank'>".$nroVoucher."</a></td>";   
                        $fila4       .= "</tr>";
                        $total_otros_costos = $total_otros_costos + $subtotal;
                        $total_otros_costos_dolar = $total_otros_costos_dolar + $subtotal_dolar;
                    }
                    $fila4       .= "<tr>";
                    if($monedadoc=='S'){
                        $fila4       .= "<td colspan='8' align='right'>".number_format($total_servicios,2)."</td>";
                        $fila4       .= "<td align='right'>".number_format($total_facturado,2)."</td>";  
                    }
                    elseif($monedadoc=='D'){
                        $fila4       .= "<td colspan='8' align='right'>".number_format($total_servicios_dolar,2)."</td>";
                        $fila4       .= "<td align='right'>".number_format($total_facturado_dolar,2)."</td>";
                    }
                    $fila4       .= "</tr>";  
                }
//                elseif($tipoexport=='excel'){
//                    
//                }
            }
        }
        /*INCLUYO EN LOS COSTOS DE TRANSPORTE EN LA PARTIDA TRANSPORTE (08)*/
        $fila3 = "";
        if($codpartida=='08'){         
            $filter4      = new stdClass();
            $filter4_not  = new stdClass();
            $filter4->codservicio     = array('000000000010','000000000002','000000000074','000000000057','000000000001','000000000086','000000000084','000000000048','000000000049','000000000047','000000000046','000000000071');                
            $filter4->codot  = $arrCodOT;
            $filter4->fechai = $fecha_ini;
            $filter4->fechaf = $fecha_fin;  
            //$filter4->frealizadoi = $fecha_ini;
            //$filter4->frealizadof = $fecha_fin;  
            $order_by            = array('r.Fecemi desc');
            $oServicio   = $this->requiser_model->listarg($filter4,$filter4_not,$order_by);
            $total_servicios       = 0;
            $total_servicios_dolar = 0;
            $total_facturado       = 0;
            $total_facturado_dolar = 0;  
            if(count($oServicio)>0){
                if($tipoexport==""){
                    foreach($oServicio as $indice => $value3){
                       $codigo    = $value3->codot;
                        $tipser    = $value3->tipser;
                        $total     = $value3->costo;
                        $codser    = $value3->gcodser;
                        $fentrega  = $value3->gfentrega;
                        $peso      = $value3->gpeso;
                        $ruc       = $value3->gruc;
                        $moneda    = $value3->moneda;
                        $tipdoc    = $value3->tipod;
                        $seriedoc  = $value3->seried;
                        $nrodoc    = $value3->nrod;
                        $tc        = $value3->cambio;
                        $ser_guia  = $value3->gserguia;
                        $num_guia  = $value3->gnumguia;
                        $estado    = $value3->gestado;
                        $observ    = $value3->gobserva;
                        $fecemi    = $value3->fecemi;
                        $frealiza  = $value3->fdespacho;
                        $subtotal_a= $value3->subtotal;
                        $igv_a     = $value3->igv;
                        $total_a   = $value3->costo;
                        /*Numero de ot2*/
                        $filter8        = new stdClass();
                        $filter8_not    = new stdClass();
                        $filter8->codot = $codigo; 
                        $oOt2          = $this->ot_model->obtenerg($filter8,$filter8_not);
                        $nroOt2        = $oOt2->NroOt;    
                        /**/                    
                        $subtotal  = $moneda=='S'?$subtotal_a:($subtotal_a*$tc);
                        $igv       = $moneda=='S'?$igv_a:($igv_a*$tc);
                        $total     = $moneda=='S'?$total_a:($total_a*$tc);
                        $subtotal_dolar = $moneda=='D'?$subtotal_a:($subtotal_a/$tc);
                        $igv_dolar      = $moneda=='D'?$igv_a:($igv_a/$tc);
                        $total_dolar    = $moneda=='D'?$total_a:($total_a/$tc);    
                        $total_servicios = $total_servicios + $subtotal;
                        $total_servicios_dolar = $total_servicios_dolar + $subtotal_dolar;
                        $tipo_documento        = $tipdoc=='01'?"FV":"OTRO";
                        /*Obtengo el monto total de la factura de compra*/
                        $filter2               = new stdClass();
                        $filter2_not           = new stdClass();
                        $filter2->codot        = $codot;
                        $filter2->serie        = $seriedoc;
                        $filter2->numero       = $nrodoc;
                        $facturac              = $this->facturac_model->listar_totales($filter2,$filter2_not);
                        $montofacturaS         = 0;
                        $montofacturaD         = 0;
                        if(isset($facturac->montoS)){
                            $montofacturaS         = $facturac->montoS;
                            $montofacturaD         = $facturac->montoD;    
                        }
                        $total_facturado = $total_facturado + $montofacturaS;
                        $total_facturado_dolar = $total_facturado_dolar + $montofacturaD;
                        /*Obtengo el numero de voucher*/
                        $filter8               = new stdClass();
                        $filter8_not           = new stdClass();
                        $filter8->codot        = $codot;
                        $filter8->tipdocref    = $tipo_documento;
                        //$filter8->seriedocref  = $seriedoc;
                        $filter8->nrodocref    = $nrodoc;
                        $nroVoucher            = "";                 
                        $oVoucher              = $this->voucher_model->listar_detalle2($filter8,$filter8_not);
                        if(is_array($oVoucher) && count($oVoucher)>0){
                            foreach($oVoucher as $indice2 => $value2){
                                $nroVoucher = $value2->NroVoucher;
                                $codot2     = $value2->codot;
                                break;
                            }
                        }
                        /*Servicios*/
                        $descripcion = "";  
                        if($codser!=''){
                            $filter6     = new stdClass();
                            $filter6_not = new stdClass();
                            $filter6->codservicio = $codser;
                            $oServicio2   = $this->servicio_model->obtener($filter6,$filter6_not);
                            $descripcion = $oServicio2->DesPro;
                        }
                        /*Nombre del proveedor*/
                        $filter7     = new stdClass();
                        $filter7_not = new stdClass();
                        $filter7->ruccliente = $ruc;
                        $oProveedor  = $this->proveedor_model->obtener($filter7,$filter7_not);
                        $razcli      = isset($oProveedor->RazCli)?$oProveedor->RazCli:"";
                        $fila3       .= "<tr>";
                        $fila3       .= "<td align='center'><div style='width:80px;'>".$nroOt2."</a></td>";       
                        $fila3       .= "<td align='center'><div style='width:80px;' id='".trim($num_guia)."' id2='".trim($codot)."'><a href='#' onclick='ver_requis_ser(this);'>".$ser_guia."-".$num_guia."</a></td>";
                        $fila3       .= "<td align='center'><div style='width:80px;'>".date_sql($fecemi)."</a></td>";
                        $fila3       .= "<td align='center'><div style='width:80px;'>".(date_sql($frealiza)=='30/12/1899'?'':date_sql($frealiza))."</a></td>";
                        //$fila3       .= "<td align='center'><div style='width:80px;'>".$fecemi."</a></td>";
                        $fila3       .= "<td align='left'><div style='width:120px;'>".$codser."-".$descripcion."</a></td>";
                        //$fila3       .= "<td align='left'><div style='width:80px;'>".$observ."</a></td>";
                        $fila3       .= "<td align='right'><div style='width:80px;'>".number_format($peso,2)."</a></td>";
                        $fila3       .= "<td align='center'><div title='".$ruc."' style='width:100px;'>".$razcli."</a></td>";
                        $fila3       .= "<td align='center'><div style='width:100px;'>".($estado==1?"SI":"NO")."</a></td>";
                        if($monedadoc=='S'){
                            $fila3       .= "<td align='right'><div style='width:80px;'>".number_format($subtotal,2)."</a></td>";
                            $fila3       .= "<td align='right'><div style='width:80px;'>".number_format($montofacturaS,2)."</a></td>";        
                        }
                        elseif($monedadoc=='D'){
                            $fila3       .= "<td align='right'><div style='width:80px;'>".number_format($subtotal_dolar,2)."</a></td>";
                            $fila3       .= "<td align='right'><div style='width:80px;'>".number_format($montofacturaD,2)."</a></td>";        
                        }
                        if($nroVoucher!=''){
                            $fila3       .= "<td align='center'><div style='width:100px;'><a href='factura.php?tipo=".$tipdoc."&serie=".$seriedoc."&numero=".$nrodoc."' target='_blank'>".$tipo_documento."-".$seriedoc."-".$nrodoc."</a></td>";             
                        }
                        else{
                            $fila3       .= "<td align='center'><div style='width:100px;'>&nbsp;</td>";     
                        }
                        $fila3       .= "<td align='center'><div  style='width:100px;'><a href='voucher.php?numero=".$nroVoucher."' target='_blank'>".$nroVoucher."</a></td>";   
                        $fila3       .= "</tr>";
                        $total_otros_costos = $total_otros_costos + $subtotal;
                        $total_otros_costos_dolar = $total_otros_costos_dolar + $subtotal_dolar;
                    }
                    $fila3       .= "<tr>";
                    if($monedadoc=='S'){
                        $fila3       .= "<td colspan='9' align='right'>".number_format($total_servicios,2)."</td>";
                        $fila3       .= "<td align='right'>".number_format($total_facturado,2)."</td>";  
                    }
                    elseif($monedadoc=='D'){
                        $fila3       .= "<td colspan='9' align='right'>".number_format($total_servicios_dolar,2)."</td>";
                        $fila3       .= "<td align='right'>".number_format($total_facturado_dolar,2)."</td>";
                    }
                    $fila3       .= "</tr>";
                }
            }
        }
        $data['nroOt'] = $nroOt;
        $data['codot'] = $codot;
        $data['codpartida'] = $codpartida;
        $data['var_codpartida'] = $var_codpartida;
        $data['dirOt'] = $dirOt;
        $data['fila']  = $fila;
        $data['fila2'] = $fila2;
        $data['fila3'] = $fila3;
        $data['fila4'] = $fila4;
        $data['monedadoc']   = $monedadoc;
        $data['pres_soles']  = $pres_soles;
        $data['mod_soles']   = $mod_soles;
        $data['verencabezado'] = $verencabezado;
        $data['total_otros_costos']   = $total_otros_costos;
        $this->load->view(contabilidad."rpt_tesoreria",$data);
    }
    
    public function rpt_otros(){
        $codot  = $this->input->get_post('codot');
        $monedadoc = $this->input->get_post('moneda');
        $fecha_ini   = $this->input->get_post('fini');
        $fecha_fin   = $this->input->get_post('ffin');
        $tipot       = 14;
        $oOt         = $this->ot_model->obtener($codot,$tipot);
        $nroOt       = $oOt->NroOt;
        $dirOt       = $oOt->DirOt;
        /*Recupero todos los voucher*/
        $filter      = new stdClass();
        $filter_not  = new stdClass();
        $filter->codot      = $codot;
        $filter->fechai     = $fecha_ini;
        $filter->fechaf     = $fecha_fin;
        $filter->codpartida = $codpartida;        
        $oVoucher    = $this->voucher_model->listar_detalle($filter,$filter_not);
        if(is_array($oVoucher) && count($oVoucher)>0){
            foreach($oVoucher as $indice => $value){
                $importe     = $value->ImpPdet;
                $descripcion = $value->DesPago;
                $tipPago     = $value->TipPago;
                $codCtrl     = $value->CodCtrl;
                $nro_voucer  = $value->NroVoucher;
                $tipodoc     = $value->TipoDocRef;
                $seriedoc    = $value->SerieDocRef;
                $numdoc      = $value->NroDocRef;
                $nro_cheque  = $value->NroCheque;
                $moneda_voucher  = $value->MO;
                $cod_tipsol  = $value->TipSolPago;
                $cod_solicita = $value->CodSolicita;
                $tc          = $value->Tc;
                $flgIgv      = $value->Igv;
                $fec_pago    = $value->fecha2;
                $fec_emi     = $value->fecemi;
                /*Obtenemos el nombre del tipo de voucher*/
                
                /*Obtenemos la orden de compra*/
                
  
                /*Obtenemos nombre proveedor*/
                
                


            }
        }
    }

    public function export_excel($type){
        if($this->session->userdata('data_'.$type)){
            $result = $this->session->userdata('data_'.$type);
            switch ($type) {
                case 'listar_costomateriales':
                    $simbolo = "S/.";
                    $arr_columns = array();
                    $arr_export_detalle = array();
                    $arr_columns[]['STRING']  = 'OT';
                    $arr_columns[]['STRING']  = 'T.PRODUCTO';
                    $arr_columns[]['STRING']  = 'Requerimiento';
                    $arr_columns[]['STRING']  = 'Código';
                    $arr_columns[]['STRING']  = 'Linea';
                    $arr_columns[]['STRING']  = 'Descripción';
                    $arr_columns[]['DATE']    = 'Fecha';
                    $arr_columns[]['NUMERIC'] = 'Cantidad';
                    $arr_columns[]['NUMERIC'] = 'Precio '.".$simbolo.";
                    $arr_columns[]['NUMERIC'] = 'Total '. ".$simbolo.";
                    $arr_columns[]['NUMERIC'] = 'Pesos Totales';
                    $arr_columns[]['STRING']  = 'Documento';
                    $arr_group = array();
                    $this->reports_model->rpt_general('rpt_'.$type,'Costo de Materiales ',$arr_columns,$result["rows"] ,$arr_group);                                        
                    break;    
                case 'listar_costomanoobra':
                    $simbolo = "S/.";
                    $arr_columns = array();                  
                    $arr_columns[]['STRING'] = 'OT';
                    $arr_columns[]['STRING'] = 'Código';
                    $arr_columns[]['STRING'] = 'Apellidos y Nombres';
                    $arr_columns[]['STRING'] = 'Área';
                    $arr_columns[]['STRING'] = 'Descripción';
                    $arr_columns[]['DATE'] = 'Fecha';
                    $arr_columns[]['NUMERIC'] = 'Horas';
                    $arr_columns[]['NUMERIC'] = 'Monto '.".$simbolo.";
                    $arr_group = array();  
                    $this->reports_model->rpt_general('rpt_'.$type,'Costo de Mano de Obra ',$arr_columns,$result["rows"] ,$arr_group);   
                    break;
                case 'costos_x_ot':
                    $simbolo = "S/.";
                    $arr_columns = array();                  
                    $arr_columns[]['STRING'] = 'OT';
                    $arr_columns[]['STRING'] = 'Nro Ot';
                    $arr_columns[]['STRING'] = 'Site';
                    $arr_columns[]['STRING'] = 'Proyecto';
                    $arr_columns[]['DATE'] = 'Fecha Inicio';
                    $arr_columns[]['STRING'] = 'Fecha Termino';
                    $arr_columns[]['STRING'] = 'Valor Venta';
                    $arr_columns[]['STRING'] = 'Costo Toal';
                    $arr_columns[]['STRING'] = 'Delta';
                    $arr_group = array();  
                    $this->reports_model->rpt_general('rpt_'.$type,'Reporte Costo de Ot',$arr_columns,$result["rows"] ,$arr_group);   
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