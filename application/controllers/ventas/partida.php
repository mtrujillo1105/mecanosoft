<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once "Spreadsheet/Excel/Writer.php";  
class Partida extends CI_Controller {
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");                
        $this->load->model(ventas.'partida_model');
        $this->load->model(ventas.'ctrlobras_model');
        $this->load->model(ventas.'ot_model');
        $this->load->model(maestros.'tipoproducto_old_model');
        $this->load->model(finanzas.'voucher_model');
        $this->load->model(finanzas.'caja_model');
        $this->load->model(almacen.'nsalida_model');
        $this->load->model(compras.'requiser_model');
        $this->load->helper('date');
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');

    }
    
    public function index()
    {
        $this->load->view(ventas."partida_nuevo");
    }

    public function listar(){
        $this->load->view(ventas."partida");
    }
    
    public function listar_partidaot(){
        $codot        = $this->input->get_post('codot'); 
        $moneda       = $this->input->get_post('moneda'); 
        $codpartida2  = $this->input->get_post('codpartida2'); 
        $verencabezado = $this->input->get_post('verencabezado'); 
        $tipoexport    = $this->input->get_post('tipoexport'); 
        if($moneda=="")       $moneda = 'S';
        /*Nombre de OT*/
        $filter        = new stdClass();
        $filter_not    = new stdClass();
        $filter->codot = $codot;    
        $oOt       = $this->ot_model->obtenerg($filter,$filter_not);
        $nroOt     = $oOt->NroOt;
        $dirOt     = $oOt->DirOt;
        $tipoOld    = $oOt->Tipo;        
        
        /*Obtengo las partidas por tipo de productoold*/
        $producto_old = $this->tipoproducto_old_model->obtener($tipoOld);
        $tipProd      = $producto_old->Valor_3;
        
        /*Obtengo las partidas para este tipo de producto*/
        $filter       = new stdClass();
        $filter_not   = new stdClass();
        $filter->codtipoproducto = $tipProd;
        $order_by     = array("pt.orden"=>"asc");
        $oPartidatipoprod = $this->partida_model->listar_partidatipoproducto($filter,$filter_not,$order_by);
        $total_ejecutado_soles = 0;
        $total_ejecutado_dolar = 0;
        $total_margen_soles    = 0;
        $total_margen_dolar    = 0;
        $total_monto_soles     = 0;
        $total_monto_dolar     = 0;
        $total_ampliado_soles  = 0;
        $total_ampliado_dolar  = 0;
        $fila = "";
        $item    = 1;
        
        /*Cabecera de la exportacion a excel*/
        if($tipoexport=='excel'){
            $xls = new Spreadsheet_Excel_Writer();
            $xls->send("Rpt_costomateriales.xls");
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
            $sheet->mergeCells(0,0,0,9);   
            //$sheet->write(0,1,"Reporte de Costos por OT del ".$fecha_ini." al ".$fecha_fin." ",$format_titulo);  
            //$sheet->write(0,4,"OT: ".$fecha_ini."   ".$fecha_fin." ",$format_titulo); 
            //$sheet->write(1,0,"CODIGO",$format_titulo2);  $sheet->write(1,1,"LINEA",$format_titulo2);  $sheet->write(1,2,"DESCRIPCION",$format_titulo2);  $sheet->write(1,3,"FECHA",$format_titulo2);   $sheet->write(1,4,"CANTIDAD",$format_titulo2);   if($monedadoc=='S'){    $sheet->write(1,5,"PRECIO S/.",$format_titulo2);   $sheet->write(1,6,"TOTAL S/.",$format_titulo2);     $sheet->write(1,7,"NUMERO DOCUMENTO",$format_titulo2);  }else{    $sheet->write(1,5,"PRECIO $.",$format_titulo2);   $sheet->write(1,6,"TOTAL $.",$format_titulo2);     $sheet->write(1,7,"NUMERO DOCUMENTO",$format_titulo2);   }        
            $z=2;
            $y=2; 
        }
        foreach($oPartidatipoprod as $indice => $value){
            $codpartida = $value->CodPartida;
            $nompartida = $value->Des_Larga;
            
            /*Obtenemos lo presupuestado*/
            $filter2     = new stdClass();
            $filter2_not = new stdClass();
            $filter2->codot      = $codot;
            $filter2->codpartida = $codpartida;
            $filter2->estado     = 'P';
            $oCtrlObras = $this->ctrlobras_model->obtener($filter2,$filter2_not);
            $nrodoc         = "";
            $monto_soles    = 0;
            $monto_dolar    = 0;
            $ampliado_soles = 0;
            $ampliado_dolar = 0;
            if(count((array)$oCtrlObras)>0){
                $nrodoc      = $oCtrlObras->NroDoc;
                $mo          = $oCtrlObras->Mo;
                $igv         = $oCtrlObras->IGV;
                $monto       = $oCtrlObras->MtoDoc2;
                $ampliado    = $oCtrlObras->Mtomod2;
                $tc          = $oCtrlObras->tcambio;
                if($tc!=''){
                    $monto_soles = $mo==2?$monto:$monto*$tc;
                    $monto_dolar = $mo==3?$monto:$monto/$tc;
                    $ampliado_soles = $mo==2?$ampliado:$ampliado*$tc;
                    $ampliado_dolar = $mo==3?$ampliado:$ampliado/$tc;   
                }
                else{
                    $monto_soles = 0;
                    $monto_dolar = 0;
                    $ampliado_soles = 0;
                    $ampliado_dolar = 0;    
                }
                $total_monto_soles = $total_monto_soles + $monto_soles;
                $total_monto_dolar = $total_monto_dolar + $monto_dolar;
                $total_ampliado_soles = $total_ampliado_soles + $ampliado_soles;
                $total_ampliado_dolar = $total_ampliado_dolar + $ampliado_dolar;
            }
            
            /*Obtengo lo ejecutado*/
            $filter3     = new stdClass();
            $filter3_not = new stdClass();
            $filter3->codot      = $codot;
            $filter3->codpartida = $codpartida;
            $oVoucher = $this->voucher_model->listar_totales($filter3,$filter3_not);
            $ejecutado_soles = 0;
            $ejecutado_dolar = 0;
            $margen_soles    = 0;
            $margen_dolar    = 0;
            if($codpartida=='05'){//Materiales cargados a la OT.
                $filter5 = new stdClass();
                $filter5->codot  = $codot;
                $Omateria_prima  = $this->nsalida_model->listar_totales($filter5,new stdClass());
                foreach($Omateria_prima as $indice3=>$value3){
                    $ejecutado_dolar  = $value3->sum_exp_2;
                    $ejecutado_soles   = $value3->sum_exp_3;
                }
            }
            else{
                if(is_array($oVoucher) && count($oVoucher)==1){
                    $ejecutado_soles = $oVoucher[0]->ImpSoles;
                    $ejecutado_dolar = $oVoucher[0]->ImpDolares;
                }                
            }
            
            /*Adiciono el pago de transportes a la partida 08 TRASNPORTE*/
            if($codpartida=='08'){
                $costo_servicios  = 0;
                $costo_serviciosD = 0;
                $filter6    = new stdClass();
                $filternot6 = new stdClass();
                $filter6->codot = $codot;
                $oServicios = $this->requiser_model->listar_totales($filter6,$filternot6,"");
                foreach($oServicios as $indice6 => $value6){
                    $costo_servicios     = $value6->sum_exp_4;
                    $costo_serviciosD    = $value6->sum_exp_6;
                }
                $ejecutado_soles = $ejecutado_soles + $costo_servicios;
                $ejecutado_dolar = $ejecutado_dolar + $costo_serviciosD; 
            }
            
            /*Adiciono el pago por caja chica,transportes y servicios a la partida 10 CONTINGENCIA*/
            if($codpartida=='10'){
                $caja_chica  = 0;
                $caja_chicaD = 0;
                $filter4   = new stdClass();
                $filter4->codot   = $codot;
                $filter4->group_by = array("det.codot");
                $oCaja = $this->caja_model->listar_totales($filter4,new stdClass());
                if(count($oCaja)>0){
                    if($moneda=='S'){
                        $caja_chica = $oCaja[0]->subSoles;
                    }
                    elseif($moneda=='D'){
                        $caja_chicaD = $oCaja[0]->subDolar;
                    } 
                }
                $ejecutado_soles = $ejecutado_soles + $caja_chica;
                $ejecutado_dolar = $ejecutado_dolar + $caja_chicaD;
            }
            $total_ejecutado_soles = $total_ejecutado_soles + $ejecutado_soles;
            $total_ejecutado_dolar = $total_ejecutado_dolar + $ejecutado_dolar;
            
            /*Obtengo el margen*/
            $margen_soles = $monto_soles - $ejecutado_soles;
            $margen_dolar = $monto_dolar - $ejecutado_dolar;
            $total_margen_soles = $total_margen_soles + $margen_soles;
            $total_margen_dolar = $total_margen_dolar + $margen_dolar;            
            if($tipoexport==""){
                $fila       .= "<tr id='".$item."' id2='".$codpartida."'>";
                $fila       .= "<td align='center'><div style='width:50px;'>";
                $fila       .= "<a href='#' class='ver_subpartida' onclick='ver_subpartida(this);'><img src='".img."/mas.gif' border='0' height='8' width='8'></a>";
                $fila       .= "<a href='#' class='ocultar_subpartida' onclick='ocultar_subpartida(this);' style='display:none;'><img src='".img."/menos.png' border='0' height='8' width='8'></a>";
                $fila       .= "&nbsp;".$item."</div></td>";
                $fila       .= "<td align='left'><div style='width:250px;'>".$codpartida."-".$nompartida."</td>";
                //$fila       .= "<td align='center'><div style='width:50px;'><a href='#'>".$nrodoc."</a></td>";
                if($moneda=='S'){
                    $fila   .= "<td align='right'><div style='width:80px;'>".number_format($monto_soles,2)."</td>";
                    $fila   .= "<td align='right'><div style='width:80px;'>".number_format($ampliado_soles,2)."</td>";  
                    $fila   .= "<td align='right'><div style='width:80px;'><a href='#' onclick='rpt_ejecutado(this);'>".number_format($ejecutado_soles,2)."</a></td>";        
                    $fila   .= "<td align='right'><div style='width:80px;'>".number_format($margen_soles,2)."</td>";
                }
                elseif($moneda=='D'){
                    $fila  .= "<td align='right'><div style='width:80px;'>".number_format($monto_dolar,2)."</td>";
                    $fila  .= "<td align='right'><div style='width:80px;'>".number_format($ampliado_dolar,2)."</td>"; 
                    $fila  .= "<td align='right'><div style='width:80px;'><a href='#'>".number_format($ejecutado_dolar,2)."</a></td>";
                    $fila  .= "<td align='right'><div style='width:80px;'>".number_format($margen_dolar,2)."</td>";
                }
                $fila      .= "</tr>";  
            }
            elseif($tipoexport=="excel"){
               $sheet->write($z,0,$item,$format_bold);
               $sheet->write($z,1,$codpartida."-".$nompartida,$format_bold);
               if($moneda=='S'){
                  $sheet->write($z,2,number_format($monto_soles,2),$format_bold);
                  $sheet->write($z,3,number_format($ampliado_soles,2),$format_bold);
                  $sheet->write($z,4,number_format($ejecutado_soles,2),$format_bold);                   
                  $sheet->write($z,5,number_format($margen_soles,2),$format_bold);   
               }
               elseif($moneda=='D'){
                  $sheet->write($z,2,number_format($monto_dolar,2),$format_bold);
                  $sheet->write($z,3,number_format($ampliado_dolar,2),$format_bold);
                  $sheet->write($z,4,number_format($ejecutado_dolar,2),$format_bold);                   
                  $sheet->write($z,5,number_format($margen_dolar,2),$format_bold);   
               }
               $z++; 
            }
            
            /*Obtenemos las subpartidas*/
            $filter7     = new stdClass();
            $filter7_not = new stdClass();
            $filter7->codpartida = $codpartida;
            $oSubpartida = $this->partida_model->listar_detalle($filter7,$filter7_not);
            if(count($oSubpartida)>0){
                $jj   = $item+0.1;
                foreach($oSubpartida as $indice7=>$value7){
                    $codsubpartida = $value7->cod_argumento;
                    $nomsubpartida = $value7->des_larga;
                    /*Obtengo cargo, abono, cargo de las subpartdias*/
                    $filter8       = new stdClass();
                    $filter8_not   = new stdClass();
                    $filter8->codpartida = $codpartida;
                    $filter8->codsubpartida = $codsubpartida;
                    $filter8->numero     = $nrodoc;
                    $filter8->estado     = 'P';
                    $filter8->codot      = $codot;
                    $oCtrlDet      = $this->ctrlobras_model->listar_detalle($filter8,$filter8_not);
                    $cargo2    = 0;
                    $abono2    = 0;
                    $saldo2    = 0;                      
                    if(is_array($oCtrlDet) && count($oCtrlDet)>0){                      
                        foreach($oCtrlDet as $indice8=>$value8){
                            $cargo2    = $cargo2 + $value8->Cargo2;
                            $abono2    = $abono2 + $value8->Abono;
                            //$saldo2    = $saldo2 + $value8->Saldo2;
                        }
                        $saldo2 = $cargo2 - $abono2;
                        $fila    .= "<tr class='tbSubpartida".$item."' id='tbSubpartida".$item."' id2='".$codsubpartida."' style='display:none;'>";
                        $fila    .= "<td align='center'><div style='width:80px;'><font size='1' color='#4682B4'>".$jj."</font></td>";
                        $fila    .= "<td align='left'><div style='margin-left:10px;'><font size='1' color='#4682B4'><li>".$codsubpartida."-".$nomsubpartida."</font></li></td>";
                        //$fila    .= "<td align='right'><div style='width:80px;'>&nbsp;</td>";
                        $fila    .= "<td align='right'><div style='width:80px;'><font size='1' color='#4682B4'>".number_format($cargo2,2)."</font></td>";
                        //$fila    .= "<td align='right'><div style='width:80px;'>&nbsp;</td>";
                        $fila    .= "<td align='right'><div style='width:80px;'><a href='#'><font size='1' color='#4682B4'>".number_format($abono2,2)."</font></a></td>";
                        $fila    .= "<td align='right'><div style='width:80px;'><font size='1' color='#4682B4'>".number_format($saldo2,2)."</font></td>";
                        $fila    .= "</tr>"; 
                        $jj       = $jj + 0.1;
                    }                      
                }
            }
            $item++;
        }
        if($tipoexport=='excel'){
           $xls->close();  
        }
        $data['nroOt'] = $nroOt;
        $data['dirOt'] = $dirOt;
        $data['fila']  = $fila;
        $data['codot'] = $codot;
        $data['verencabezado'] = $verencabezado;
        $data['total_ejecutado_soles']  = $total_ejecutado_soles;
        $data['total_ejecutado_dolar']  = $total_ejecutado_dolar;
        $data['monto_soles']            = $monto_soles;
        $data['monto_dolar']            = $monto_dolar;
        $data['total_margen_soles']     = $total_margen_soles;
        $data['total_margen_dolar']     = $total_margen_dolar;
        $data['total_monto_soles']      = $total_monto_soles;
        $data['total_monto_dolar']      = $total_monto_dolar;
        $data['total_ampliado_soles']   = $total_ampliado_soles;
        $data['total_ampliado_dolar']   = $total_ampliado_dolar;       
        $data['monedadoc']              = $moneda;
        $this->load->view(ventas."partidaot_listar",$data);
    } 

    public function nuevo(){
        $this->load->view(ventas."partida_nuevo");
    }

    public function editar(){

    }
    
}
?>
