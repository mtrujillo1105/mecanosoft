<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once "Spreadsheet/Excel/Writer.php";  
class Tareo extends CI_Controller {
    var $entidad;
    var $login;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");        
        $this->load->model(ventas.'ot_model');
        $this->load->model(personal.'reloj_model');
        $this->load->model(produccion.'tareo_model');
        $this->load->model(maestros.'area_model');
        $this->load->model(maestros.'ttorre_model');
        $this->load->model(maestros.'tipoot_model');
        $this->load->model(ventas.'cliente_model');
        $this->load->model(maestros.'estadoot_model');
        $this->load->model(maestros.'tipoproducto_model');
        $this->load->model(maestros.'tipoproducto_old_model');
        $this->load->model(maestros.'periodoot_model');
        $this->load->model(maestros.'tc_model');
        $this->load->model(siddex.'orden_model');   
        $this->load->model(siddex.'parte_model');   
        $this->load->model(siddex.'proceso_model');   
        $this->load->model(siddex.'clase_model');   
        $this->load->model(siddex.'listamat_model');   
        $this->load->model(siddex.'clientesidd_model');  
        $this->load->helper('date');
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');
    }
    
    public function index(){
        redirect('produccion/tareo/tareoot');
    }
    
    public function listar(){
        
    }
    
    public function obtener(){
        
    }
    
    public function grabar(){
        $codres          = $this->input->get_post('codres');
        $fecha           = $this->input->get_post('fecha');
        $dni             = $this->input->get_post('dni');
        $arrcodot        = $this->input->get_post('codot');
        $arrarea         = $this->input->get_post('area');
        $arrarea_old     = $this->input->get_post('area_old');
        $arrhora         = $this->input->get_post('hora');
        $arrcantidad     = $this->input->get_post('cantidad');
        $arrhora_old     = $this->input->get_post('hora_old');
        $arrdescripcion  = $this->input->get_post('descripcion');
        $arraccion       = $this->input->get_post('accion');
        $resultado       = array();
        for($k=0;$k<count($arrcodot);$k++){
            $codot1 = $arrcodot[$k];
            $filter = new stdClass();
            $filter->codot = $codot1;
            $ots          = $this->ot_model->obtenerg($filter);
            $hh           = $ots->Hhombre;
            $hh_avance    = $ots->Hhombre_avance;
            $nroot1       = $ots->NroOt;
            $hora1        = $arrhora[$k];
            $area1        = $arrarea[$k];
            $area_old1    = $arrarea_old[$k];
            $hora_old1    = $arrhora_old[$k];
            $cantidad1    = $arrcantidad[$k]; 
            $descripcion1 = $arrdescripcion[$k];
            $accion1   = $arraccion[$k]; 
            $hora1_acumulada = $hh_avance + $hora1 - $hora_old1;
            $valida    = true;
            $resultado[trim($nroot1)] = 1;
            if($hh != 0 && $hh < $hh_avance + $hora1){
                $valida = false;
                $resultado[trim($nroot1)] = 0;
                $hora1_acumulada = $hora1;
            }
            if($valida){
                if($codot1!=''){
                    if($accion1=='N'){
                            $filter2 = new stdClass();
                            $filter2->item   = 1;
                            $filter2->codres = $codres;
                            $filter2->codent = $this->entidad;
                            $filter2->dni    = $dni;
                            $filter2->fecha  = $fecha;
                            $filter2->areaproduccion = trim($area1);
                            $filter2->cantidad       = trim($cantidad1);
                            $filter2->horas          = $hora1;
                            $filter2->descripcion    = $descripcion1;
                            $filter2->codot          = $codot1;
                            $this->tareo_model->insertar($filter2);      
                    }
                    elseif($accion1=='M'){
                        $filter = new stdClass();
                        $filter->cantidad       = trim($cantidad1);
                        $filter->horas          = $hora1;
                        $filter->descripcion    = $descripcion1;
                        $filter->areaproduccion = trim($area1);
                        $this->tareo_model->modificar($codres,$dni,$fecha,$codot1,$area_old1,$filter);
                    }  
                }
                $where = new stdClass();
                $where->codot = $codot1;
                $filter3 = new stdClass();
                $filter3->Hhombre_avance = $hora1_acumulada;
                $this->ot_model->modificar($where,$filter3);                
            }
        }
        echo json_encode($resultado);
    }
    
    public function eliminar(){
        $codres  = $this->input->get_post('codres');
        $dni     = $this->input->get_post('dni');
        $fecha   = $this->input->get_post('fecha');
        $codot   = $this->input->get_post('codot');
        $hora_old = $this->input->get_post('hora_old');
        $aproduccion = $this->input->get_post('aproduccion');
        $where = array("item"=>1,"codres"=>$codres,"codent"=>$this->entidad,"dni"=>$dni,"fecha"=>$fecha,"areaproduccion"=>$aproduccion,"codot"=>$codot);
        $this->tareo_model->eliminar($where);
        /*Se puede evitar ESTO se envian las horas totales de avance y la validacion estaria en una linea.*/
        $filter = new stdClass();
        $filter->codot = $codot;
        $ots          = $this->ot_model->obtenerg($filter);
        $hh_avance    = $ots->Hhombre_avance;
        /*Actualiza*/
//        $where = new stdClass();
//        $where->codot = $codot;
//        $filter3 = new stdClass();
//        $filter3->Hhombre_avance = $hh_avance - $hora_old;
//        $this->ot_model->modificar($where,$filter3);  
    }

    public function rpt_tareoot(){
        $tipoexport    = $this->input->get_post('tipoexport');
        $fecha  = $this->input->get_post('fecha');
        $fechafin  = $this->input->get_post('fechafin');
        $area   = $this->input->get_post('area');
        $cargo   = $this->input->get_post('cargo');
        $codres = $this->input->get_post('codres');
        $codot  = $this->input->get_post('codot');
        $ot     = $this->input->get_post('ot');
        $tipot  = $this->input->get_post('tipot');
        $moneda = $this->input->get_post('moneda');
        if($moneda=='') $moneda='S';
        if($fecha=='') $fecha = date("01/m/Y",time());
        if($fechafin=='') $fechafin = date("d/m/Y",time());
        $filtroarea     = form_dropdown("area",$this->area_model->seleccionar("::Seleccione:::","000"),$area,"id='area' class='comboMedio'  onClick='limpiarText();' ");
        $filtrocargo     = form_dropdown("cargo",$this->area_model->seleccionar_c("::Seleccione:::","000"),$cargo,"id='cargo' class='comboMedio'  onClick='limpiarText();' ");
        $filtronombre   = form_dropdown("codres",$this->reloj_model->seleccionarNombresOT("::Todos:::","00000",$codot),$codres,"id='codres' class='comboGrande' onClick='limpiarText();'");
        $tareos  = $this->tareo_model->listar2($fecha,$fechafin,$codres,$area,$codot,$cargo);
        $arr_columns = array();
        $arr_export_detalle = array();
        $arr_columns[]['DATE'] = 'FECHA';
        $arr_columns[]['STRING'] = 'NOMBRES';
        $arr_columns[]['STRING'] = 'AREA';
        $arr_columns[]['STRING'] = 'CARGO';
        $arr_columns[]['STRING'] = 'DESCRIPCION';
        $arr_columns[]['STRING'] = 'CANT';
        $arr_columns[]['STRING'] = 'OT';
        $arr_columns[]['NUMERIC'] = 'Hrs';
        $moneda=='D'?$arr_columns[]['NUMERIC'] = '$/.':$arr_columns[]['NUMERIC'] = 'S/.';
        
        
        $fila    = "";
        $totalhoras = 0;
        $totalmonto = 0;
        if(count($tareos)>0){
            foreach($tareos as $indice => $value){
                $arr_data = array();
                $codres2 =    $value->dni;
                $fecha2      = $value->fecha2;
                $nomper      = $value->nomper;
                $area        = $value->areapro;
                $cargo       = $value->cargos;
                $descripcion = $value->descripcion;
                $cantidad    = $value->cantidad;
                $nroOt       = $value->NroOt;
                $horas       = $value->horas;
                $monto       = $value->monto;
                $codot2      = $value->codot;
                $tipot2      = $value->tipot;
                $tipo2       = $this->tipoot_model->obtener($tipot2);
                $totalhoras  = $totalhoras + $horas;
                $totalmonto  = $totalmonto + $monto;
                if($moneda=='D'){
                    $tc=$this->tc_model->obtener($fecha2)->Valor_2;
                    if($tc=='') $tc=1;
                     $monto=$monto/$tc;
                }
                
                
                $fila .= "<tr>";
                $fila .= "<td align='center'>".$fecha2."</td>";
                $arr_data[] = $fecha2;
                $fila .= "<td  align='left'>".$nomper."(".$codres2.")</td>";
                $arr_data[] = utf8_encode($nomper);
                $fila .= "<td  align='left'>".$area."</td>";
                $arr_data[] = $area;
                $fila .= "<td  align='left'>".$cargo."</td>";
                $arr_data[] = $cargo;
                $fila .= "<td  align='left'>".$descripcion."</td>";
                $arr_data[] = utf8_encode($descripcion);
                $fila .= "<td  align='right'>".(trim($cantidad)==''?'&nbsp;':$cantidad)."</td>";
                $arr_data[] = $cantidad;
                $fila .= "<td  align='center'>".$tipo2->Des_Corta."-".$nroOt."</td>";
                $arr_data[] = $nroOt;
                $fila .= "<td style='width:8%;' align='right'>".number_format($horas,2)."</td>";
                $arr_data[] = $horas;
                $fila .= "<td align='right'>".number_format($monto,2)."</td>";
                $arr_data[] = $monto;
                $fila .= "</tr>";
                
                
                array_push($arr_export_detalle,$arr_data);
            }
            //$arr_data = array('','','','','','','',$totalhoras);
            //array_push($arr_export_detalle,$arr_data);
            $var_export = array('columns' => $arr_columns, 'rows' => $arr_export_detalle);
            $this->session->set_userdata('data_rpt_tareoot', $var_export);
            
          /*  $f_fila = "<td colspan='7' align='center'>&nbsp;</td>";
            $f_fila .= "<td style='width:8%;' align='right'>".number_format($totalhoras,2)."</td>";
            $f_fila .= "<td align='right'>".number_format($totalmonto,2)."</td>";*/
            
            
        }
                
        else{
            
          $fila  = "<td colspan='8' align='center'>NO EXISTEN REGISTROS</>";   
        }

       if($tipoexport=="excel"){
            if($this->session->userdata('data_rpt_tareoot')){
                $result = $this->session->userdata('data_rpt_tareoot');
                $this->reports_model->rpt_general('data_rpt_tareoot', 'REPORTE DE TAREO POR OT', $result["columns"], $result["rows"]);
            }else{
                echo "<div style='color:rgb(150,150,150);padding:10px;width:560px;height:160px;border:1px solid rgb(210,210,210);'>
                    No hay datos para exportar
                    </div>";
            }
        }        
        else{
            $tipoexport  = "";   
        }
        $data['fila_detalle']  = $fila; 
        $data['tipot']         = $tipot; 
        $data['codot']         = $codot; 
        $data['ot']            = $ot; 
        $data['fecha']         = $fecha;
        $data['fechafin']         = $fechafin;
        $data['nrofilas']      = 0;
        $data['filtroarea']    = $filtroarea;
        $data['filtrocargo']    = $filtrocargo;
        $data['filtronombre']  = $filtronombre;
        $data['tipoexport']    = $tipoexport;
        $data['moneda']          =$moneda;
        $data['TotHoras']   =number_format($totalhoras,2);
        $data['TotMontos']   =number_format($totalmonto,2);
        
        $this->load->view(produccion."rpt_tareoot",$data);
    }
    
    public function tareoot()
    {
        $tipoexport    = $this->input->get_post('tipoexport');
        $fecha  = $this->input->get_post('fecha');
        if($fecha=='') $fecha = date("d/m/Y",time());
        $j = 1;
        $fila_cabecera = "";
        $fila_detalle  = "<td colspan='8' align='center'>NO EXISTEN REGISTROS</>"; 
        $arrfecha  = explode("/",$fecha);
        $fechacomp    = mktime( 0, 0, 0,$arrfecha[1]+1-1,$arrfecha[0]+1-1,$arrfecha[2]+1-1); 
        $condi='';
        $tareo_cabecera = $this->reloj_model->listar3($fecha);
        if(count($tareo_cabecera)>0){
            foreach($tareo_cabecera as $indice=>$value){
                $dni      = $value->Dni;
                $codres   = $value->codres;
                $nomper   = $value->nomper;
                $cond     = $value->flgtreg;
                $hora     = $value->Hora;
                $salida   = $value->Salida;
                $codot    = $value->codot;
                $dirot    = $value->DirOt;
                $estado   = $value->Estado;
                $horast   = $value->Htrabajadas;
                $horase   = $value->Hextra;
                $ots      = $this->tareo_model->obtener_ot($codres,$dni,$fecha);
                $cadOts   = "";
//                $ingreso_h = substr($hora, 0, 2);
//                $ingreso_m = substr($hora, 3, 2);
//                $ingreso_s = substr($hora, 6, 2);
//                $salida_h = substr($salida, 0, 2);
//                $salida_m = substr($salida, 3, 2);
//                $salida_s = substr($salida, 6, 2);
//                $total_horas_i = (int)$ingreso_h * 3600 + (int)$ingreso_m * 60 + (int)$ingreso_s;
//                $total_horas_s = (int)$salida_h * 3600 + (int)$salida_m * 60 + (int)$salida_s;
//                $total_horas_i = (float)($total_horas_i / 3600);
//                $total_horas_s = (float)($total_horas_s / 3600);
                if(count($ots)>0){
                    $indice2 = 0;
                    foreach($ots as $indice2=>$value2){
                        $cadOts = trim($cadOts).($cadOts!=''?",":"").trim($value2->nroOt);
                    }
                    if($indice2==0 && strlen($cadOts)!=0){
                        $cadOts = $cadOts;
                    }
                    else{
                       $cadOts   = substr ($cadOts, 0, strlen($cadOts));   
                    }
                }
                if($cond=='2') $condi='PLANILLA OTROS';
                else if($cond=='3') $condi='PLANILLA FORMAL';
                else if($cond=='4') $condi='RECIBO HONORARIOS';
                $color = $estado=='C'?'#FF0000':'#000000';
                $fila_cabecera .= "<tr class='cabecera_class' id='".$codres."' id2='".$nomper."' id3='".$dni."' id4='".$estado."'>";
                $fila_cabecera .= "<td style='width:3%;' align='center'><font color='".$color."'>".$j."</font></td>";
                $fila_cabecera .= "<td style='width:25%;' align='left'><font color='".$color."'>".$nomper."</font></td>";
                $fila_cabecera .= "<td style='width:11%;' align='left'><font color='".$color."'>".$condi."</font></td>";
                $fila_cabecera .= "<td style='width:7%;' align='center'><font color='".$color."'>".$hora."</font></td>";
                $fila_cabecera .= "<td style='width:7%;' align='center'><font color='".$color."'>".$salida."</font></td>";
                $fila_cabecera .= "<td style='width:7%;' align='center'><font color='".$color."'>".number_format($horast + $horase, 2)."</font></td>";
                $fila_cabecera .= "<td style='width:27%;' align='left'><font color='".$color."'>".$dirot."</font></td>";
                $fila_cabecera .= "<td style='width:19%;' align='left'><font color='".$color."'>".$cadOts."</font></td>";
                $fila_cabecera .= "</tr>";
                $j++;
            }
        }

        if($tipoexport=="excel"){
            $arr_columns[0]['NUMERIC'] = 'Nro';
            $arr_columns[1]['STRING'] = 'Datos del Personal';
            $arr_columns[2]['STRING'] = 'Condición';
            $arr_columns[3]['STRING'] = 'Entrada';
            $arr_columns[4]['DATE'] = 'Salida';
            $arr_columns[5]['STRING'] = 'Centro de Labores';
            $arr_columns[6]['STRING'] = 'Nro. de OT';
            $arr_data    = array();
            $var_prd_n = 0;
            $var_row = 7;
            $var_reg = 1;
            if(count($tareo_cabecera)>0){
                foreach($tareo_cabecera as $indice=>$value){
                    $dni      = $value->Dni;
                    $codres   = $value->codres;
                    $nomper   = $value->nomper;
                    $cond     = $value->flgtreg;
                    $hora     = (string)$value->Hora;
                    $salida   = $value->Salida;
                    $codot    = $value->codot;
                    $dirot    = $value->DirOt;
                    $estado   = $value->Estado; 
                    $ots      = $this->tareo_model->obtener_ot($codres,$dni,$fecha);

                    $cadOts   = "";
                    if(count($ots)>0){
                        $indice2 = 0;
                        foreach($ots as $indice2=>$value2){
                            $cadOts = trim($cadOts).($cadOts!=''?",":"").trim($value2->nroOt);
                        }
                        if($indice2==0 && strlen($cadOts)!=0){
                            $cadOts = $cadOts;
                        }
                        else{
                           $cadOts   = substr ($cadOts, 0, strlen($cadOts));   
                        }
                    }

                    if($cond=='2') $condi='PLANILLA OTROS';
                    ELSE if($cond=='3') $condi='PLANILLA FORMAL';
                    ELSE if($cond=='4') $condi='RECIBO HONORARIOS';

                    $arr_data[$var_prd_n] = array(
                    $var_reg,
                    utf8_encode(trim($nomper)),
                    utf8_encode(trim($condi)),
                    $hora,
                    $salida,
                    utf8_encode(trim($dirot)),
                    $cadOts
                );
               $var_prd_n++; 
               $var_row++;
               $var_reg++;

                }
            }
            $arr_grouping_header = array();
            $arr_grouping_header['A5:C5'] = utf8_encode('Descripción');
            $this->reports_model->rpt_general('rpt_tareo_por_OT','Reporte de Tareo por OT : '.$fecha,$arr_columns,$arr_data ,$arr_grouping_header);
        }        
        $data['fila_detalle']  = $fila_detalle; 
        $data['fecha']         = $fecha;
        $data['fechacomp']     = $fechacomp;
        $data['fila_cabecera'] = $fila_cabecera;
        $data['nrofilas']      = 0;
        $data['dni']           = "";
        $data['codres']        = "";
        $data['tipoexport']    = "";
        $data['estado']        = "C";
        $this->load->view(produccion."tareoot",$data);
    }
    
   public function tareoot_cabecera(){
        $tipoexport = $this->input->get_post('tipoexport');
        $codsel     = $this->input->get_post('codsel');
        $fecha  = $this->input->get_post('fecha');
        $j = 1;
        $fila_cabecera = "";
        $tareo_cabecera = $this->reloj_model->listar3($fecha);
        if(count($tareo_cabecera)>0){
            foreach($tareo_cabecera as $indice=>$value){
                $dni      = $value->Dni;
                $codres   = $value->codres;
                $nomper   = $value->nomper;
                $hora     = $value->Hora;
                $salida   = $value->Salida;
                $codot    = $value->codot;
                $dirot    = $value->DirOt;
                $estado   = $value->Estado; 
                $ots      = $this->tareo_model->obtener_ot($codres,$dni,$fecha);
                $background = $codres==$codsel?"#66ff33":"#ffffff";
                $cadOts   = "";
                if(count($ots)>0){
                    $indice2 = 0;
                    foreach($ots as $indice2=>$value2){
                        $cadOts = trim($cadOts).($cadOts!=''?",":"").trim($value2->nroOt);
                    }
                    if($indice2==0 && strlen($cadOts)!=0){
                        $cadOts = $cadOts;
                    }
                    else{
                       $cadOts   = substr ($cadOts, 0, strlen($cadOts));   
                    }
                }
                $color = $estado=='C'?'#FF0000':'#000000';
                $fila_cabecera .= "<tr bgcolor='".$background."' class='cabecera_class' id='".$codres."' id2='".$nomper."' id3='".$dni."' id4='".$estado."'>";
                $fila_cabecera .= "<td style='width:3%;' align='center'><font color='".$color."'>".$j."</font></td>";
                $fila_cabecera .= "<td style='width:37%;' align='left'><font color='".$color."'>".$nomper."</font></td>";
                $fila_cabecera .= "<td style='width:7%;' align='center'><font color='".$color."'>".$hora."</font></td>";
                $fila_cabecera .= "<td style='width:7%;' align='center'><font color='".$color."'>".$salida."</font></td>";
                $fila_cabecera .= "<td style='width:27%;' align='left'><font color='".$color."'>".$dirot."</font></td>";
                $fila_cabecera .= "<td style='width:19%;' align='left'><font color='".$color."'>".$cadOts."</font></td>";
                $fila_cabecera .= "</tr>";
                $j++;
            }
        }
        $data['fecha']         = $fecha;
        $data['fila_cabecera'] = $fila_cabecera;
        $data['nrofilas']      = 0;
        $data['dni']           = "";
        $data['codres']        = "";
        $data['tipoexport']    = "";
        $data['estado']        = "C";
        $this->load->view(produccion."tareoot_cabecera",$data);
   }
    
    function tareoot_detalle(){
        $codres = $this->input->get_post('codres');
        $fecha  = $this->input->get_post('fecha');
        $dni    = $this->input->get_post('dni');
        $modo   = $this->input->get_post('modo');
        $estado = $this->input->get_post('estado');
        $j      = 0;
        if(trim($codres)!=''){
            $fila_detalle = "";
            $tareo_detalle = $this->tareo_model->listar($codres,$dni,$fecha);
            if(count($tareo_detalle)>0){
                foreach($tareo_detalle as $indice=>$value){
                    $areaproduccion = $value->areaproduccion;
                    $cantidad       = $value->cantidad;
                    $horas          = $value->horas;
                    $descripcion    = $value->descripcion;
                    $nroot          = $value->nroOt;
                    $dirOt          = $value->dirOt;
                    $codot          = $value->codot;
                    $readonly       = $estado=='C'?"readonly='readonly'":"";
                    $display        = $estado=='C'?"style='display:none;'":"";
                    $disabled       = $estado=='C'?"disabled='disabled'":"";
                    $filtroarea     = form_dropdown("area[$j]",$this->area_model->seleccionar("::Seleccione:::","000"),$areaproduccion,"id='area[$j]' onclick='javascript:valid_ot(this,$j)' class='comboMedio'  ".$disabled."");  
                    $fila_detalle  .= "<tr>";
                    $fila_detalle  .= "<td style='width:3%;' align='center' >".($j+1)."</td>";
                    $fila_detalle  .= "<td  style='width:10%;' align='left'><input type='hidden' class='otclass' name='codot[".$j."]' id='codot[".$j."]' value='".$codot."'><input readonly type='text' name='ot[".$j."]' id='ot[".$j."]' value='".$nroot."' style='width:70px;'></td>";
                    $fila_detalle  .= "<td  style='width:23%;' align='left'><input type='text' readonly name='site[".$j."]' id='site[".$j."]' style='width:210px;' value='".$dirOt."'></td>";
                    $fila_detalle  .= "<td style='width:13%;' align='center'><input type='hidden' name='area_old[".$j."]' id='area_old[".$j."]' style='width:25px;' value='".$areaproduccion."'>".$filtroarea."</td>";
                    $fila_detalle  .= "<td style='width:7%;' align='center'><span class='filatareo'>";
                    $fila_detalle  .= "<input type='text' maxlength='5' onkeypress='return numbersonly(this,event,\".\");' name='hora[".$j."]' id='hora[".$j."]' value='".trim($horas)."' style='width:50px;' ".$readonly.">";
                    $fila_detalle  .= "<input type='hidden' maxlength='5' name='hora_old[".$j."]' id='hora_old[".$j."]' value='".trim($horas)."' style='width:50px;'>";                                        
                    $fila_detalle  .= "</span></td>";
                    $fila_detalle  .= "<td style='width:7%;' align='center'><input type='text' maxlength='5' onkeypress='return numbersonly(this,event,\".\");' name='cantidad[".$j."]' id='cantidad[".$j."]' value='".trim($cantidad)."' style='width:50px;' ".$readonly."></td>";                    
                    $fila_detalle  .= "<td style='width:23%;' align='left'><input type='text' name='descripcion[".$j."]' id='descripcion[".$j."]' value='".$descripcion."' style='width:250px;' ".$readonly."></td>";
                    $fila_detalle  .= "<td style='width:7%;' align='center'><a href='javascript:;' onclick='borrar_detalle(".$j.");'><image src='".  base_url()."img/del.gif' border='0' ".$display."></a><input type='hidden' name='accion[".$j."]' id='accion[".$j."]' value='M' style='width:20px;'></td>";
                    $fila_detalle  .= "</tr>";
                    $j++;
                }
            }
            else{
                    $fila_detalle = "<td colspan='8' align='center'>NO EXISTEN REGISTROS</>";                
            }
        }
        else{
            $fila_detalle = "<td colspan='8' align='center'>NO EXISTEN REGISTROS</>";
        }
        $data['codres']       = $codres;
        $data['fecha']        = $fecha;
        $data['dni']          = $dni;
        $data['nrofilas']     = $j;
        $data['estado']       = $estado;
        $data['fila_detalle'] = $fila_detalle;
        $this->load->view(produccion."tareoot_detalle",$data);
    }
    
    
    
    
   public function tareoot_excel(){
         $tipo    = $this->input->get_post('tipo');
    }
    

  public function hhporot(){
        $tipoexport   = $this->input->get_post('tipoexport');   
        $ttorre       = $this->input->get_post('ttorre');
        $tipoot       = $this->input->get_post('tipoot');
        $estado       = $this->input->get_post('estado');
        $codcliente   = $this->input->get_post('codcliente');
        $tipoproducto = $this->input->get_post('tipoproducto');
        $tipo_reporte = $this->input->get_post('tipo_reporte');
        $fInicio      = $this->input->get_post('fecha_ini');
        $fFin         = $this->input->get_post('fecha_fin');
        if($tipoot=='')       $tipoot = 18;
        if($tipo_reporte=='') $tipo_reporte='C';  
        if($fInicio=='')      $fInicio     ="01/01/".date("Y",time());  
        if($fFin=='')         $fFin        =date("d/m/Y",time());  
        $arrestado            = array(""=>":::Seleccione:::","A"=>"Abierta","C"=>"Cerrada");
        $selecttorre          = form_dropdown('ttorre',$this->clase_model->seleccionar(new stdClass(),new stdClass(),"::Seleccione:::","000"),$ttorre," size='1' id='ttorre' class='comboGrande'");               
        $selecttipoot         = form_dropdown('tipoot',$this->periodoot_model->seleccionar("::Seleccione:::","000"),$tipoot," size='1' id='tipoot' class='comboMedio' onchange=\"$('#tipoexport').val('');submit();\" ");               
        $selecestado          = form_dropdown('estado',$arrestado,$estado," size='1' id='estado' class='comboMedio'");               
        $seleccliente         = form_dropdown('codcliente',$this->clientesidd_model->seleccionar(new stdClass(),"::Seleccione:::","000"),$codcliente," size='1' id='codcliente' class='comboMedio'");               
        $nomenclatura         = $this->listamat_model->listar_totales(new stdclass());
        $fila                 = "";
        $valestado            = ""; 
        $filter               = new stdClass();
        $filter->numeroi      = 140000;
        if($ttorre!="000")     $filter->clase = $ttorre;
        if($codcliente!="000") $filter->codcli = $codcliente;
        if($estado!="")        $filter->situacion = $arrestado[$estado];       
        $ordenes1   = $this->orden_model->listar($filter);
        unset($filter->numeroi);
        $filter->numerof      = 99999;
        $ordenes2   = $this->orden_model->listar($filter);
        $ordenes    = count($ordenes2)>0?array_merge($ordenes1,$ordenes2):$ordenes1;
        $registros  = count($ordenes);
        if(count($ordenes)>0){
            $arr_export_detalle = array();
            foreach($ordenes as $indice => $value)
            {
                $peso        = 0;
                $altura      = 0;                
                $horas       = 0;
                $soles       = 0;
                $arrhora     = array();
                $arrpago     = array();                
                $nroot       = $value->Numero;
                $site        = $value->Descripcion;
                $codcli      = $value->CodigoCliente;
                $clase       = $value->ClaseOferta;
                $fecha       = $value->fecot;
                $fechatermino= $value->fteot;
                $estadoot    = $value->Situacion;
                //$color = $estadoot=='Cerrada'?" color='#FF0000'":"";
                $color = "";
                /*Tipo de torre*/
                $filter2 = new stdClass();
                $filter2->codigo = $clase;
                $clases  = $this->clase_model->listar($filter2);
                $tipo_t  = isset($clases->Descripcion)?str_replace('.','',$clases->Descripcion):'<::SELECCIONE::>';
                /*Obtengo cliente*/
                $filter3 = new stdClass();
                $filter3->codigo = (int)$codcli;
                $clientes = $this->clientesidd_model->listar($filter3);
                $razon_social = isset($clientes->RazonSocial)?$clientes->RazonSocial:'<::SELECCIONE::>';
                /*Peso ingenieria*/
                foreach($nomenclatura as $val){
                    if(trim($val->numeroorden) == $nroot){
                        $peso = is_null($val->peso)?0:number_format($val->peso/1000,2);
                        break;
                    }
                }
                /*Obtener HH por proceso*/
//                $filtro2 = new stdclass();
//                $filtro2->numero = $nroot;
//                if($fInicio!="") $filtro2->fechai = $fInicio;
//                if($fFin!="")    $filtro2->fechaf = $fFin;
//                $columnasVinc  = $this->parte_model->listar_totales($filtro2);
                
                $filtro2    = new stdclass();
                $filtro2not = new stdclass();
                $filtro2not->proceso = "";
                $filtro2->numero = $nroot;
                $filtro2->group_by = array("substring(p.codigoproceso,1,1)","p.numeroorden");
                if($fInicio!="") $filtro2->fechai = $fInicio;
                if($fFin!="")    $filtro2->fechaf = $fFin;
                $columnasVinc  = $this->parte_model->listar_totales2($filtro2,$filtro2not);                
                foreach($columnasVinc as $indice2 => $value2)
                {
                    $area = isset($value2->computed)?$value2->computed:'';
                    $arrhora[$area] = isset($value2->Horas)?$value2->Horas:'';
                    $arrpago[$area] = isset($value2->Monto)?$value2->Monto:'';
                    $horas0 = isset($value2->Horas)?$value2->Horas:'';
                    $pago0 = isset($value2->Monto)?$value2->Monto:'';
                    $horas = $horas0+$horas;
                    $soles = $pago0+$soles;
                }
                $soles_horas = $horas==0?"":$soles/$horas;
                $horas_peso  = $peso==0?0:$horas/$peso;
                $soles_peso  = $peso==0?0:$soles/$peso;
                $fila .= "<tr>";
                $fila .= "<td  valign='middle' style='width:5%;'><font size='1' ".$color.">".$nroot."</font></td>";
                $fila .= "<td  valign='middle' style='width:5%;'><font size='1' ".$color.">".$fecha."</font></td>";    
                $fila .= "<td  valign='middle' style='width:5%;'><font size='1' ".$color.">".$fechatermino."</font></td>";  
                $fila .= "<td  valign='middle' style='width:15%;'><font size='1' ".$color.">".$site."</font></td>";
                $fila .= "<td  valign='middle' style='width:15%;'><font size='1' ".$color.">".$razon_social."</font></td>";
                $fila .= "<td  valign='middle' style='width:5%;'><font size='1' ".$color.">".$peso."</font></td>";
                //$fila .= "<td   valign='middle' style='width:2.12%;'><p class='listadoot'><font size='1' ".$color.">".$altura."</font></p></td>";
                $fila .= "<td   valign='middle' style='width:10%;'><font size='1' ".$color.">".$tipo_t."</font></td>";
                $fila .= "<td   valign='middle' style='width:5%;'>";
                $fila .= "<ul>";
                $fila .= "<li><font size='1' ".$color.">HORAS</font></li>";
                $fila .= "<li><font size='1' ".$color.">S/.</font></li>";
                $fila .= "<li><font size='1' ".$color.">S/. / HORAS</font></li>";
                $fila .= "<li><font size='1' ".$color.">HORAS/TON</font></li>";
                $fila .= "<li><font size='1' ".$color.">S/./TON</font></li>";
                $fila .= "</ul>";
                $fila .= "</td>";
                $procesos = array("1","2","3","4","5","6","7");
                if($tipo_reporte=='D'){
                    foreach($procesos as $item2 => $value2){
                        $indicador = $value2;
                        $fila .= "<td   valign='middle' style='width:5%;'>";
                        $fila .= "<ul>";
                        $fila .= "<li><font size='1' ".$color.">".(isset($arrhora[$indicador])?number_format($arrhora[$indicador],2):'&nbsp;')."</li>";
                        $fila .= "<li><font size='1' ".$color.">".(isset($arrpago[$indicador])?number_format($arrpago[$indicador],2):'&nbsp;')."</li>";
                        $fila .= "<li><font size='1' ".$color.">".(isset($arrhora[$indicador]) && isset($arrpago[$indicador])?number_format($arrpago[$indicador]/$arrhora[$indicador],2):'&nbsp;')."</li>";
                        $fila .= "<li><font size='1' ".$color.">".((isset($arrhora[$indicador]) && $peso!=0)?number_format($arrhora[$indicador]/$peso,2):(isset($arrhora[$indicador])?'---':'&nbsp;'))."</li>";
                        $fila .= "<li><font size='1' ".$color.">".((isset($arrpago[$indicador]) && $peso!=0)?number_format($arrpago[$indicador]/$peso,2):(isset($arrhora[$indicador])?'---':'&nbsp;'))."</li>";
                        $fila .= "</ul>";
                        $fila .= "</td>"; 
                    }     
                }
                /*Columna Totales*/
                $fila .= "<td   valign='middle' style='width:5%;'><p class='listadoot'>";
                $fila .= "<ul>";
                $fila .= "<li><font size='1' ".$color.">".($horas==0?"&nbsp;":number_format($horas,2))."</li>";
                $fila .= "<li><font size='1' ".$color.">".($horas==0?"&nbsp;":number_format($soles,2))."</li>";
                $fila .= "<li><font size='1' ".$color.">".($horas!=0?number_format($soles/$horas,2):'&nbsp;')."</li>";
                $fila .= "<li><font size='1' ".$color.">".(($peso!=0 && $horas!=0)?number_format($horas/$peso,2):($horas!=0?'---':'&nbsp;'))."</li>";
                $fila .= "<li><font size='1' ".$color.">".(($peso!=0 && $soles!=0)?number_format($soles/$peso,2):($horas!=0?'---':'&nbsp;'))."</li>";
                $fila .= "</ul>";
                $fila .= "</p></td>";
                $fila .= "</tr>";
                for($k=0;$k<5;$k++){
                    $datafila   = array();
                    $datafila[] = $nroot;
                    $datafila[] = $fecha;
                    $datafila[] = $fechatermino;
                    $datafila[] = utf8_encode($site);
                    $datafila[] = utf8_encode($razon_social);
                    $datafila[] = $peso;
                    $datafila[] = utf8_encode($tipo_t);
                    foreach($procesos as $item2 => $value2){
                        $indicador = $value2;                    
                        if($k==0) $datafila[] = (isset($arrhora[$indicador])?$arrhora[$indicador]:0);
                        if($k==1) $datafila[] = (isset($arrpago[$indicador])?$arrpago[$indicador]:0);
                        if($k==2) $datafila[] = (isset($arrhora[$indicador]) && isset($arrpago[$indicador])?$arrpago[$indicador]/$arrhora[$indicador]:0);
                        if($k==3) $datafila[] = ((isset($arrhora[$indicador]) && $peso!=0)?$arrhora[$indicador]/$peso:(isset($arrhora[$indicador])?0:0));
                        if($k==4) $datafila[] = ((isset($arrpago[$indicador]) && $peso!=0)?$arrpago[$indicador]/$peso:(isset($arrhora[$indicador])?0:0));
                    }
                    if($k==0) $datafila[] = ($horas==0?0:$horas);
                    if($k==1) $datafila[] = ($horas==0?0:$soles);
                    if($k==2) $datafila[] = ($horas!=0?$soles/$horas:0);
                    if($k==3) $datafila[] = (($peso!=0 && $horas!=0)?($horas/$peso):($horas!=0?0:0));
                    if($k==4) $datafila[] = (($peso!=0 && $soles!=0)?($soles/$peso):($horas!=0?0:0));
                    array_push($arr_export_detalle,$datafila);
                }
            }    
            $var_export = array('rows' => $arr_export_detalle);
            $this->session->set_userdata('data_hhporot', $var_export);            
       }  
       if(count($ordenes)<0){$fila .= "<h2 align='center'>Seleccione Datos</h2>";}   
       $data['selecttorre']  = $selecttorre;
       $data['selecttipoot'] = $selecttipoot;
       $data['selecestado']  = $selecestado;
       $data['seleccliente'] = $seleccliente;
       $data['registros']    = $registros;
       $data['tipo_reporte'] = $tipo_reporte;
       $data['fila']         = $fila;
       $data['fInicio']      = $fInicio;
       $data['fFin']         = $fFin;
       $this->load->view(produccion."hhporot",$data);
    }    
    
    function seleccionar_areapro(){
        $resultado = $this->area_model->seleccionar("::Seleccione::","000");
        echo json_encode($resultado);
    }
    
     public function export_excel($type) {
        if($this->session->userdata('data_'.$type)){
            $result = $this->session->userdata('data_'.$type);
            $arr_columns = array();            
            switch ($type) {
                case 'hhporot':
                    $arr_export_detalle = array();
//                    print_r("<pre>");
//                    print_r($result);
//                    print_r("</pre>");
                    $arr_columns[]['STRING']  = 'NRO.OT';
                    $arr_columns[]['DATE']    = 'F.INICIO';
                    $arr_columns[]['DATE']    = 'F.TERMINO';
                    $arr_columns[]['STRING']  = 'SITE';
                    $arr_columns[]['STRING']  = 'CLIENTE';
                    $arr_columns[]['NUMERIC'] = 'PESO(TN)';
                    $arr_columns[]['STRING']  = 'TIPO TORRE';
                    $arr_columns[]['NUMERIC'] = 'H.MANUAL';
                    $arr_columns[]['NUMERIC'] = 'H.AUTOMATICO';
                    $arr_columns[]['NUMERIC'] = 'ESTRUCTURADO';
                    $arr_columns[]['NUMERIC'] = 'GALVANIZADO';
                    $arr_columns[]['NUMERIC'] = 'PINTURA';
                    $arr_columns[]['NUMERIC'] = 'DESPACHO';
                    $arr_columns[]['NUMERIC'] = 'CONTROL';
                    $arr_columns[]['NUMERIC'] = 'TOTAL';
                    $arr_group = array();
                    $this->reports_model->rpt_general('rpt_'.$type,'Horas hombre por OT',$arr_columns,$result["rows"],$arr_group); 
                    break;
                case '':
                    break;
            }
        }else{
            echo "<div style='color:rgb(150,150,150);padding:10px;width:560px;height:160px;border:1px solid rgb(210,210,210);'>
                No hay datos para exportar
                </div>";
        }
    }    
}
?>
