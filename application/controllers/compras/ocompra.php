<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once "Spreadsheet/Excel/Writer.php";
class Ocompra extends CI_Controller {
    var $entidad;
    var $login;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");        
        $this->load->model(compras.'ocompra_model');
        $this->load->model(compras.'requis_model');
        $this->load->model(compras.'proveedor_model');
        $this->load->model(almacen.'producto_model');
        $this->load->model(almacen.'ningreso_model');
        $this->load->model(ventas.'ot_model');
        $this->load->model(produccion.'tareo_model');
        $this->load->model(maestros.'tipoproducto_model');
        $this->load->model(maestros.'ttorre_model');
        $this->load->model(maestros.'proyecto_model');
        $this->load->model(maestros.'periodoot_model');
        $this->load->model(ventas.'cliente_model');
        
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');
    }

    public function index()
    {
        redirect(compras."ocompra/listar");
    }

    public function listar($j=0){
        $offset             = (int)$this->uri->segment(3);
        $conf['base_url']   = site_url('compras/ocompra/listar/');
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
        $listado            = $this->ocompra_model->listar($filter,$filter_not,"",$conf['per_page'],$offset);
        $item               = $j+1;
        $fila               = "";
        if(count($listado)>0){
             foreach($listado as $indice=>$valor){
                 $serie  = $valor->seriedoc;
                 $numero = $valor->nrodoc;
                 $ruccli = $valor->ruccli;
                 $fecrep = $valor->fecrep;
                 $mo     = $valor->mo;
                 $entrega = $valor->gentrega;
                 $fpago  = $valor->forpago;
                 $tpago  = $valor->tipopago;
                 $tc     = $valor->tc;
                 $afecto = $valor->afecto;
                 $faprob = $valor->fec_apro;
                 $faprob = $valor->fec_apro;
                 $faprob = $valor->fec_apro;
                 $faprob = $valor->fec_apro;
                 $faprob = $valor->fec_apro;
                 $fila  .= "<tr class='".($indice%2==0?'itemParTabla':'itemParTabla')."'>";
                 $fila  .= "<td align='center'>".$item++."</td>";
                 $fila  .= "<td align='center'>".date_sql($fecrep)."</td>";
                 $fila  .= "<td align='center'>".$serie."-".$numero."</td>";
                 $fila  .= "<td align='center'>".$ruccli."</td>";
                 $fila  .= "<td align='center'>".$mo."</td>";
                 $fila  .= "<td align='center'>".date_sql($entrega)."</td>";
                 $fila  .= "<td align='center'>".$fpago."</td>";
                 $fila  .= "<td align='center'>".$tpago."</td>";
                 $fila  .= "<td align='center'>".$tc."</td>";
                 $fila  .= "<td align='center'>".$afecto."</td>";
                 $fila  .= "<td align='center'>".$faprob."</td>";
                 $fila  .= "<td align='center'><a href='#' onclick='editar(".$numero.")'><img src='".base_url()."img/modificar.png' width='16' height='16' border='0' title='Modificar'></a></td>";
                 $fila  .= "<td align='center'><a href='#' onclick='ver(".$numero.")'><img src='".base_url()."img/ver.png' width='16' height='16' border='0' title='Modificar'></a></td>";
                 $fila  .= "<td align='center'><a href='#' onclick='eliminar(".$numero.")'><img src='".base_url()."img/eliminar.png' width='16' height='16' border='0' title='Modificar'></a></td>";                 
                 $fila  .= "</tr>";
             }
        }
        $data['fila']           = $fila;
        $data['titulo_busqueda'] = "Buscar Orden de compra";
        $data['titulo_tabla']    = "Relaci&oacute;n de Ordenes de Compra";
        $this->pagination->initialize($conf);
        $data['paginacion']      = $this->pagination->create_links();
        $this->load->view(compras."ocompra_listar",$data);
    }

    public function nuevo(){     
        $this->load->view(ventas."ot_nuevo");
    }
	
    public function editar(){

    }

    public function grabar(){

        
    }
	
    public function eliminar(){

    }

    public function ver(){
        $serie          = $this->input->get_post('serie');
        $numero         = $this->input->get_post('numero');
     //   print_R($serie.'-'.$numero);die();
        $filter         = new stdClass();
        $filter_not     = new stdClass();
        $filter->serie  = $serie;
        $filter->numero = $numero;
        $ocompras       = $this->ocompra_model->obtener($filter,$filter_not);
        $productos      = $this->producto_model->listar(new stdClass(),new stdClass());
        foreach($productos as $indice => $value){
            $codpro = $value->codpro;
            $arrproducto[$codpro] = $value;
        }
        $ruc         = $ocompras->ruccli;
        $fecemi      = $ocompras->fecemi;
        $monedadoc   = $ocompras->mo;
        $fecentrega  = $ocompras->gentrega;
        $formapag    = $ocompras->forpago;
        $tipopago    = $ocompras->tipopago;
        $tcambio     = $ocompras->tc;
        $fecha_apro  = $ocompras->fec_apro;
        $gsersol     = $ocompras->gsersol;
        $gnumsol     = $ocompras->gnumsol;
        $dia         = $ocompras->dias;
        $this->load->library("fpdf/pdf");
        $CI = & get_instance();
        $CI->pdf->FPDF('P');
        $CI->pdf->AliasNbPages();
        $CI->pdf->AddPage();
        /*Cabecera*/
        $CI->pdf->SetFont('Arial','',7);
        $filter     = new stdClass();
        $filter_not = new stdClass();
        $filter->ruccliente = $ruc; 
        $clientes   = $this->proveedor_model->obtener($filter,$filter_not);
        $razon      = $clientes->RazCli;
        $direc      = $clientes->DirCli;
        $TelCli_1   = $clientes->TelCli_1;
        $TelCli_2   = $clientes->TelCli_2;
        $FaxCli     = $clientes->FaxCli;
        $contacto   = $clientes->contacto;
        $tpago      = $this->proyecto_model->obtener_forma_pago_dbf($formapag);
        $tippag     = $tpago->p_descrip;
        $CI->pdf->SetFont('Arial','B',11);
        $CI->pdf->Image('img/recuadro.jpg',6,45,200);
        $CI->pdf->Image('img/mimco_ruc.jpg',10,8,55);
      //  $CI->pdf->Image('img/recuadro.jpg',6,244,200);
        $CI->pdf->Cell(0,8, "ORDEN COMPRA No ".$serie."-".$numero,0,1,"C",0);
        $CI->pdf->Cell(120,15, "" ,0,1,"L",0);
        $CI->pdf->SetFont('Arial','B',7);
        $CI->pdf->Cell(60,5, "FACTURAR A : METALES INGENIERIA Y CONSTRUCCION: " ,0,1,"L",0);
        $CI->pdf->Cell(60,5, "R.U.C. : 20300166611",0,1,"L",0);
        $moneda      = $monedadoc=='S'?"NUEVOS SOLES":"DOLARES AMERICANOS";
        $CI->pdf->Cell(120,5, "" ,0,1,"L",0);
        $CI->pdf->Cell(5,15, "" ,0,0,"L",0);
        $CI->pdf->Cell(90,5, "PROVEEDOR : ".$ruc ."   -   ".$razon,0,1,"L",0);
        $CI->pdf->Cell(5,15, "" ,0,0,"L",0);
        $CI->pdf->Cell(90,5, "DIRECCION: ".$direc ,0,0,"L",0);
        $CI->pdf->Cell(90,5, "TEL./FAX : ".$TelCli_1." // ".$TelCli_2." // ". $FaxCli ,0,1,"L",0);
        $CI->pdf->Cell(5,15, "" ,0,0,"L",0);
        $CI->pdf->Cell(90,5, "ATENCION : ".$contacto ,0,1,"L",0);
        $CI->pdf->Cell(5,15, "" ,0,0,"L",0);
        $CI->pdf->Cell(90,5, "FORMA PAGO : ".$tippag ,0,0,"L",0);
        $CI->pdf->Cell(90,5, "MONEDA : ".$moneda ,0,1,"L",0);
        $CI->pdf->Cell(5,15, "" ,0,0,"L",0);
        $CI->pdf->Cell(90,5, "LUGAR ENTREGA: JR. PACIFICO 680 CALLAO",0,0,"L",0);
        $CI->pdf->Cell(90,5, "TIEMPO ENTREGA : ".$dia." DIA(S) CALENDARIO(S)",0,1,"L",0);
        $CI->pdf->Cell(5,15, "" ,0,0,"L",0);
        $CI->pdf->Cell(60,5,  "FECHA EMISION :  ".$fecemi,0,0,"L",0);
        $CI->pdf->Cell(60,5, "FECHA ENTREGA : ".$fecentrega ,0,0,"L",0);
        $CI->pdf->Cell(60,5, "No SOLICITUD : ".$gsersol."  -  ".$gnumsol ,0,1,"L",0);
        $CI->pdf->Cell(5,8, "" ,0,1,"L",0);
        $CI->pdf->Cell(15,5,  "No REQ.",1,0,"C",0);
        $CI->pdf->Cell(18,5, "OT/CC/OI" ,1,0,"C",0);
        $CI->pdf->Cell(13,5, "CANT." ,1,0,"C",0);
        $CI->pdf->Cell(12,5,  "UND.",1,0,"C",0);
        $CI->pdf->Cell(103,5, "DESCRIPCION" ,1,0,"C",0);
        $CI->pdf->Cell(16,5, "P. UNITARIO" ,1,0,"C",0);
        $CI->pdf->Cell(16,5, "TOTAL" ,1,1,"C",0);
        $filter2     = new stdClass();
        $filter2_not = new stdClass();
        $filter2->serie  = $serie;
        $filter2->numero = $numero;
        $ocompra_det = $this->ocompra_model->listar_detalle($filter2,$filter2_not); 
        $CI->pdf->Cell(5,2, "" ,0,1,"L",0);
        $subtotal    = 0;
        foreach($ocompra_det as $indicex=>$valors){
            $req     = $valors->gnumreq;
            $ot      = $valors->got;
            $can     = $valors->gcantidad;
            $pre     = $valors->gprecio;
            $codpro2 = $valors->gcodpro;
            $precio_real = $pre*$can;
            $subtotal    = $precio_real+$subtotal;
            $uni         = isset($arrproducto[$codpro2]->unimed)?$arrproducto[$codpro2]->unimed:"";
            $des         = isset($arrproducto[$codpro2]->despro)?$arrproducto[$codpro2]->despro:"";
            //$uni     = $valors->unidad;
            //$des     = $valors->producto;
            $CI->pdf->Cell(15,7,$req,1,0,"C",0);
            $CI->pdf->Cell(18,7,$ot,1,0,"R",0);
            $CI->pdf->Cell(13,7,number_format($can,2),1,0,"R",0);
            $CI->pdf->Cell(12,7,$uni,1,0,"C",0);
            $CI->pdf->Cell(103,7,$des,1,0,"L",0);
            $CI->pdf->Cell(16,7,number_format($pre,4),1,0,"C",0);
            $CI->pdf->Cell(16,7,number_format($pre*$can,4),1,1,"C",0);
        }
        $igv=0.18*$subtotal;
        $total=$subtotal+$igv; 
        $CI->pdf->Cell(150,5, "" ,0,1,"C",0);
        $CI->pdf->Cell(160,5, "" ,0,0,"C",0);
        $CI->pdf->Cell(20,5, "SUBTOTAL" ,0,0,"L",0);
        $CI->pdf->Cell(13,5, number_format($subtotal,4) ,0,1,"R",0);
        $CI->pdf->Cell(160,5, "DESCUENTO  0%            0.00 " ,0,0,"L",0);
        $CI->pdf->Cell(20,5, "IGV   18% " ,0,0,"L",0);
        $CI->pdf->Cell(13,5,number_format($igv,4) ,0,1,"R",0);
        $CI->pdf->Cell(160,5, "PERCEPC.      2%            0.00 " ,0,0,"L",0);
        $CI->pdf->Cell(20,5, "TOTAL" ,0,0,"L",0);
        $CI->pdf->Cell(13,5, number_format($total,4) ,0,1,"R",0);
        $CI->pdf->Cell(5,5, "" ,0,1,"L",0);
        $CI->pdf->Cell(90,5, "Toda entrega de material debe estar acompanada de una guia de remision y copia de la orden de compra, caso contrario la mercaderia no sera recepcionada." ,0,1,"L",0);
        $CI->pdf->Cell(5,5, "" ,0,1,"L",0);
        $CI->pdf->Cell(90,5, "Nota: si el bien no cumpliera con las especificaciones de la presente orden de compra sera devuelta al proveedor, el cual debera devolver el importe correspondiente." ,0,1,"L",0);
        $CI->pdf->Cell(90,5, "El monto facturado debe coincidir con con la orden de compra." ,0,1,"L",0);
        $CI->pdf->Cell(5,12, "" ,0,1,"L",0);
        $CI->pdf->Cell(30,5, "-------------------------------",0,0,"C",0);
        $CI->pdf->Cell( 30,5,"-------------------------------",0,1,"C",0);
        $CI->pdf->Cell( 30,5,"DPTO. LOGISTICA",0,0,"C",0);
        $CI->pdf->Cell( 30,5,"GERENTE GENERAL",0,1,"C",0);
        $CI->pdf->Cell(5,15, "" ,0,0,"L",0);
      /*  $CI->pdf->Cell(120,5, "OBSERVACION : " ,0,0,"L",1);*/
        $CI->pdf->Output();
    }

    public function buscar(){

    }

    public function indicador_requis_atendidas(){
        
                  
       if(isset($_POST["anio"])){
       $anio    = $_POST["anio"];
       }
        else{
        $i = date("Y",time());
        $anio = ($i-1); 
        }
        //echo $anio;
        $fila = "";
        
             $tipo    = $this->input->get_post('tipo');
             $tiporequi    = $this->input->get_post('tiporequi');
        
             
             if($tipo=="html"){

                    for($i=0;$i<12;$i++){
                        $mes   = str_pad($i+1,2,"0",STR_PAD_LEFT);
                        $dia1  = "01";
                        $dia2  = date('t', mktime (0,0,0, $i+1, 1, $anio)); 
                        $fecha_inicial = $dia1."/".$mes."/".$anio;
                        $fecha_final   = $dia2."/".$mes."/".$anio;
                        $resultado = $this->requis_model->indicador_requis_atendidas_dbf($fecha_inicial,$fecha_final);
                        $j  = 0;
                        $c  = 0;
                        $c_total = 0;
                        $j_total = 0;
                        $menos24 = 0;
                        $entre24_48 =0;
                        $mas48 = 0;
                        $menos24_total = 0;
                        $entre24_48_total =0;
                        $mas48_total = 0;

                        foreach($resultado as $indice=>$value){
                            $cantidad 	= $value->gnumguia; 
                            $fecha   	= $value->fecemi; 
                            $resultado2 = $this->requis_model->obtener_vale_xfecha($fecha_inicial,$fecha_final);

                            $k_cant     = 0;
                            if(count($resultado2)>0){
                                foreach($resultado2 as $indice2=>$value2){
                                    $cant   = $value2->gnumguia;
                                    $fecha2 = $value2->f_estado;
                                    $k_cant = $k_cant + $cant;
                                }
                               if($cantidad==$k_cant){	
                                    $j++;
                                    $arrFecha  = explode("-",$fecha);
                                    $arrFecha2 = explode("-",$fecha2);
                                    $fec1 = mktime(0,0,0, $arrFecha[1], $arrFecha[2], $arrFecha[0]);
                                    $fec2 = mktime(0,0,0, $arrFecha2[1], $arrFecha2[2], $arrFecha2[0]);
                                    //echo "$fecha $fecha2" ; echo date("d",($fec2-$fec1))."<br>";
                                    $dias_aten = date("d",($fec2-$fec1));
                                    if($dias_aten <=1) $menos24++;
                                    if($dias_aten >1 && $dias_aten<2) $entre24_48++;
                                    if($dias_aten >= 2) $mas48++;
                                    
                                }                        
                            }
                            $c++; 
                        }
                        if($c!=0){
                            $valor = ($j/$c)*100;	
                            $valor = number_format($valor, 2, '.', '');
                        }
                        else{
                            $valor = "--";
                        }
                        $c_array[$i] = $c;
                        $j_array[$i] = $j;
                        $valor_array[$i] = $valor;
                        $menos24_array[$i] = $menos24;
                        $entre24_48_array[$i] = $entre24_48;
                        $mas48_array[$i] = $mas48;
                        if($i==1) break;//////////
                    }
                    /*Primera fila*/
                   $fila.="<tr>";
                   $fila.="<td>REQUISICIONES EFECTUADAS</td>";
                   for($z=0;$z<12;$z++){
                      if(isset($c_array[$z])){
                        $fila.="<td>".$c_array[$z]."</td>";
                        $c_total +=$c_array[$z];
                      }
                   }
                   $fila.="<td>".$c_total."</td>";
                   $fila.="</tr>";
                   /*Segunda fila*/
                   $fila.="<tr>";
                   $fila.="<td>REQUISICIONES ATENDIDAS</td>";
                   for($z=0;$z<12;$z++){
                       if(isset($j_array[$z])){
                           $fila.="<td>".$j_array[$z]."</td>";
                           $j_total +=$j_array[$z];
                       }
                   }
                   $fila.="<td>".$j_total."</td>";
                   $fila.="</tr>";
                   /*Tercera fila*/
                   $fila.="<tr>";
                   $fila.="<td>INDICE DE ATENCION (%)</td>";
                   for($z=0;$z<12;$z++){
                     if(isset($valor_array[$z])){
                       $fila.="<td>".$valor_array[$z]."</td>";
                     }
                   }
                   $fila.="<td>".number_format(($j_total*100/$c_total),2)."</td>";
                   $fila.="</tr>"; 
                   /*Cuarta fila*/


                   $fila.="<tr>";
                   $fila.="<td>Atendidas en 24h o menos</td>";
                   for($z=0;$z<12;$z++){
                      if(isset($menos24_array[$z])){
                        $fila.="<td>".$menos24_array[$z]."</td>";
                        $menos24_total +=$menos24_array[$z];
                      }
                   }
                   $fila.="<td>".$menos24_total."</td>";
                   $fila.="</tr>";       
                   /*Quinta fila*/



                   $fila.="<tr>";
                   $fila.="<td>Atendidas entre 24 y 48h</td>";
                   for($z=0;$z<12;$z++){
                      if(isset($entre24_48_array[$z])){
                        $fila.="<td>".$entre24_48_array[$z]."</td>";
                        $entre24_48_total +=$entre24_48_array[$z];
                      }
                   }
                   $fila.="<td>".$entre24_48_total."</td>";
                   $fila.="</tr>";       
                   /*Sexta fila*/



                   $fila.="<tr>";
                   $fila.="<td>Atendidas en más de 48h</td>";
                   for($z=0;$z<12;$z++){
                      if(isset($mas48_array[$z])){
                        $fila.="<td>".$mas48_array[$z]."</td>";
                        $mas48_total +=$mas48_array[$z];
                      }
                   }
                   $fila.="<td>".$mas48_total."</td>";
                   $fila.="</tr>";       
                   /*Septima fila*/   

               }
       
               
               
               
               
                elseif($tipo=="pdf"){
                    
                    
                    for($i=0;$i<12;$i++){
                        $mes   = str_pad($i+1,2,"0",STR_PAD_LEFT);
                        $dia1  = "01";
                        $dia2  = date('t', mktime (0,0,0, $i+1, 1, $anio)); 
                        $fecha_inicial = $dia1."/".$mes."/".$anio;
                        $fecha_final   = $dia2."/".$mes."/".$anio;
                        $resultado = $this->requis_model->indicador_requis_atendidas_dbf($fecha_inicial,$fecha_final);
                        $j  = 0;
                        $c  = 0;
                        $c_total = 0;
                        $j_total = 0;
                        $menos24 = 0;
                        $entre24_48 =0;
                        $mas48 = 0;
                        $menos24_total = 0;
                        $entre24_48_total =0;
                        $mas48_total = 0;

                        foreach($resultado as $indice=>$value){
                            $cantidad 	= $value->sum_gcantidad; 
                            $serie 	= $value->gserguia; 
                            $numero 	= $value->gnumguia; 
                            $fecha   	= $value->fecemi; 
                            $resultado2 = $this->requis_model->obtener_vale_xfecha($fecha_inicial,$fecha_final,$serie,$numero);

                            $k_cant     = 0;
                            if(count($resultado2)>0){
                                foreach($resultado2 as $indice2=>$value2){
                                    $cant   = $value2->cantidad;
                                    $fecha2 = $value2->fecha;
                                    $k_cant = $k_cant + $cant;
                                }
                               if($cantidad==$k_cant){	
                                    $j++;
                                    $arrFecha  = explode("-",$fecha);
                                    $arrFecha2 = explode("-",$fecha2);
                                    $fec1 = mktime(0,0,0, $arrFecha[1], $arrFecha[2], $arrFecha[0]);
                                    $fec2 = mktime(0,0,0, $arrFecha2[1], $arrFecha2[2], $arrFecha2[0]);
                                    //echo "$fecha $fecha2" ; echo date("d",($fec2-$fec1))."<br>";
                                    $dias_aten = date("d",($fec2-$fec1));
                                    if($dias_aten <=1) $menos24++;
                                    if($dias_aten >1 && $dias_aten<2) $entre24_48++;
                                    if($dias_aten >= 2) $mas48++;
                                    //echo $entre24_48;///////
                                }                        
                            }
                            $c++; 
                        }
                        if($c!=0){
                            $valor = ($j/$c)*100;	
                            $valor = number_format($valor, 2, '.', '');
                        }
                        else{
                            $valor = "--";
                        }
                        $c_array[$i] = $c;
                        $j_array[$i] = $j;
                        $valor_array[$i] = $valor;
                        $menos24_array[$i] = $menos24;
                        $entre24_48_array[$i] = $entre24_48;
                        $mas48_array[$i] = $mas48;
                        if($i==11) break;//////////
                    }
                    
                    
                    
                    
                $this->load->library("fpdf/pdf");

                $CI = & get_instance();
                $CI->pdf->FPDF('L','mm','A4');
                $CI->pdf->AliasNbPages();
                
                $CI->pdf->AddPage();
                $CI->pdf->SetTextColor(0,0,0);
                $CI->pdf->SetFillColor(255,255,255);


                $CI->pdf->SetFont('Arial','B',13);
                $CI->pdf->SetY(5);
                $CI->pdf->SetX(6); 
                $CI->pdf->Cell(284,20,"Reporte de Gestion de los Pedidos en Logistica Integral",1,1,"C",0);
                $CI->pdf->SetX(6); 
                $CI->pdf->Cell(284,8,"Requisiciones Atendidas durante el $anio",1,1,"L",0);

                $CI->pdf->SetFont('Arial','',7);
                $CI->pdf->SetY(35);
                $CI->pdf->SetX(6); 
                $CI->pdf->Cell(50,5,"CONCEPTO  /  MES",1,0,"C",0);
                $CI->pdf->Cell(18,5,"ENERO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"FEBRERO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"MARZO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"ABRIL",1,0,"C",0);
                $CI->pdf->Cell(18,5,"MAYO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"JUNIO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"JULIO",1,0,"C",0); //
                $CI->pdf->Cell(18,5,"AGOSTO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"SETIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"OCTUBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"NOVIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"DICIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"TOTAL",1,0,"C",0);
                


                    /*Primera fila*/
                   
                   $CI->pdf->SetFont('Arial','',8);
                   $CI->pdf->SetY(40);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Requisiciones Efectuadas",1,0,"C",0);
                   for($z=0;$z<12;$z++){
                      if(isset($c_array[$z])){
                   $CI->pdf->Cell(18,4,$c_array[$z],1,0,"C",0);
                        $c_total +=$c_array[$z];
                      }
                   }
                   $CI->pdf->Cell(18,4,$c_total,1,0,"C",0);
                 

                   /*Segunda fila*/
                   $CI->pdf->SetY(44);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Requisiciones Atendidas",1,0,"C",0);

                   for($z=0;$z<12;$z++){
                       if(isset($j_array[$z])){
                   $CI->pdf->Cell(18,4,$j_array[$z],1,0,"C",0);
                           $j_total +=$j_array[$z];
                       }
                   }
                   $CI->pdf->Cell(18,4,$j_total,1,0,"C",0);

                   
                   
                   /*Tercera fila*/
                   $CI->pdf->SetY(48);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Indice de Atencion (%)",1,0,"C",0);                   
                   for($z=0;$z<12;$z++){
                     if(isset($valor_array[$z])){
                   $CI->pdf->Cell(18,4,$valor_array[$z],1,0,"C",0);
                     }
                   }
                   $CI->pdf->Cell(18,4,number_format(($j_total*100/$c_total),2),1,0,"C",0);

             
                   /*Cuarta fila*/
                   $CI->pdf->SetY(52);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas en 24h o Menos",1,0,"C",0);   
                   for($z=0;$z<12;$z++){
                      if(isset($menos24_array[$z])){
                   $CI->pdf->Cell(18,4,$menos24_array[$z],1,0,"C",0);
                        $menos24_total +=$menos24_array[$z];
                      }
                   }
                   $CI->pdf->Cell(18,4,$menos24_total,1,0,"C",0);
               
                   
                   /*Quinta fila*/
                   $CI->pdf->SetY(56);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas entre 24 y 48h",1,0,"C",0); 
                   for($z=0;$z<12;$z++){
                      if(isset($entre24_48_array[$z])){
                   $CI->pdf->Cell(18,4,$entre24_48_array[$z],1,0,"C",0);
                        $entre24_48_total +=$entre24_48_array[$z];
                      }
                   }
                   $CI->pdf->Cell(18,4,$entre24_48_total,1,0,"C",0);

                   
                   
                   
                   
                   /*Sexta fila*/
                   $CI->pdf->SetY(60);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas en mas de 48h",1,0,"C",0); 
                   for($z=0;$z<12;$z++){
                      if(isset($mas48_array[$z])){
                   $CI->pdf->Cell(18,4,$mas48_array[$z],1,0,"C",0);
                        $mas48_total +=$mas48_array[$z];
                      }
                   }
                   $CI->pdf->Cell(18,4,$mas48_total,1,0,"C",0);       
                   /*Septima fila*/   

                $CI->pdf->Output();
            }          
       
        
            elseif($tipo=="excel"){
                
                    for($i=0;$i<12;$i++){
                        $mes   = str_pad($i+1,2,"0",STR_PAD_LEFT);
                        $dia1  = "01";
                        $dia2  = date('t', mktime (0,0,0, $i+1, 1, $anio)); 
                        $fecha_inicial = $dia1."/".$mes."/".$anio;
                        $fecha_final   = $dia2."/".$mes."/".$anio;
                        $resultado = $this->requis_model->indicador_requis_atendidas_dbf($fecha_inicial,$fecha_final);
                        $j  = 0;
                        $c  = 0;
                        $c_total = 0;
                        $j_total = 0;
                        $menos24 = 0;
                        $entre24_48 =0;
                        $mas48 = 0;
                        $menos24_total = 0;
                        $entre24_48_total =0;
                        $mas48_total = 0;

                        foreach($resultado as $indice=>$value){
                            $cantidad 	= $value->sum_gcantidad; 
                            $serie 	= $value->gserguia; 
                            $numero 	= $value->gnumguia; 
                            $fecha   	= $value->fecemi; 
                            $resultado2 = $this->requis_model->obtener_vale_xfecha($fecha_inicial,$fecha_final,$serie,$numero);

                            $k_cant     = 0;
                            if(count($resultado2)>0){
                                foreach($resultado2 as $indice2=>$value2){
                                    $cant   = $value2->cantidad;
                                    $fecha2 = $value2->fecha;
                                    $k_cant = $k_cant + $cant;
                                }
                               if($cantidad==$k_cant){	
                                    $j++;
                                    $arrFecha  = explode("-",$fecha);
                                    $arrFecha2 = explode("-",$fecha2);
                                    $fec1 = mktime(0,0,0, $arrFecha[1], $arrFecha[2], $arrFecha[0]);
                                    $fec2 = mktime(0,0,0, $arrFecha2[1], $arrFecha2[2], $arrFecha2[0]);
                                    //echo "$fecha $fecha2" ; echo date("d",($fec2-$fec1))."<br>";
                                    $dias_aten = date("d",($fec2-$fec1));
                                    if($dias_aten <=1) $menos24++;
                                    if($dias_aten >1 && $dias_aten<2) $entre24_48++;
                                    if($dias_aten >= 2) $mas48++;
                                    //echo $entre24_48;///////
                                }                        
                            }
                            $c++; 
                        }
                        if($c!=0){
                            $valor = ($j/$c)*100;	
                            $valor = number_format($valor, 2, '.', '');
                        }
                        else{
                            $valor = "--";
                        }
                        $c_array[$i] = $c;
                        $j_array[$i] = $j;
                        $valor_array[$i] = $valor;
                        $menos24_array[$i] = $menos24;
                        $entre24_48_array[$i] = $entre24_48;
                        $mas48_array[$i] = $mas48;
                        if($i==11) break;//////////
                    }
                                    
              $xls = new Spreadsheet_Excel_Writer();
              $xls->send("IndicadoresRequis.xls");
              $sheet  =$xls->addWorksheet('Indic.Requi.');
              
              //$sheet->setColumn(3,3,575);
              
              $sheet->setColumn(0,0,35); //COLUMNA A1
              $sheet->setColumn(1,1,15); //COLUMNA B2
              $sheet->setColumn(2,2,15); //COLUMNA C3
              $sheet->setColumn(3,3,15); //COLUMNA D4
              $sheet->setColumn(4,4,15); //COLUMNA E5
              $sheet->setColumn(5,5,15); //COLUMNA F6
              $sheet->setColumn(6,6,15); //COLUMNA G7
              $sheet->setColumn(7,7,15); //COLUMNA H8
              $sheet->setColumn(8,8,15); //COLUMNA I9
              $sheet->setColumn(9,9,15); //COLUMNA J10
              $sheet->setColumn(10,10,15); //COLUMNA K11
              $sheet->setColumn(11,11,15); //COLUMNA L12
              $sheet->setColumn(12,12,15); //COLUMNA M13
              $sheet->setColumn(13,13,15); //COLUMNA N14
              
              
              $sheet->setRow(0, 51);
              $sheet->setRow(1,42);
             
    
              
              $format_bold=$xls->addFormat();
              $format_bold->setBold();
              $format_bold->setvAlign('vcenter');
              $format_bold->sethAlign('center');
              $format_bold->setBorder(1);
              $format_bold->setTextWrap();
             
              $format_titulo=$xls->addFormat();
              $format_titulo->setBold();
              $format_titulo->setSize(10);
              $format_titulo->setvAlign('vcenter');
              $format_titulo->sethAlign('center');
              $format_titulo->setBorder(1);
              $format_titulo->setTextWrap();

  
              $sheet->mergeCells(0,0,0,13);  
              $sheet->write(0,3,"REPORTE",$format_titulo); $sheet->write(0,4,"DE",$format_titulo);  $sheet->write(0,5,"INDICADORES",$format_titulo);  $sheet->write(0,6,"DE",$format_titulo);  $sheet->write(0,7,"GESTION DE (MATERIALES)",$format_titulo);
              //$sheet->write(0,1,"",$format_bold);$sheet->write(0,2,"",$format_bold);$sheet->write(0,3,"",$format_bold);$sheet->write(0,4,"",$format_bold);$sheet->write(0,5,"SSSSS",$format_bold);
              $sheet->write(0,0,"REPORTE DEL $anio",$format_bold); 
              
              $sheet->write(1,0,"CONCEPTO   /   MES",$format_bold);  $sheet->write(1,1,"ENERO",$format_bold);  $sheet->write(1,2,"FEBRERO",$format_bold);  $sheet->write(1,3,"MARZO",$format_bold);   $sheet->write(1,4,"ABRIL",$format_bold);      $sheet->write(1,5,"MAYO",$format_bold);   $sheet->write(1,6,"JUNIO",$format_bold);    $sheet->write(1,7,"JULIO",$format_bold);      $sheet->write(1,8,"AGOSTO",$format_bold);      $sheet->write(1,9,"SETIEMBRE",$format_bold);     $sheet->write(1,10,"OCTUBRE",$format_bold);       $sheet->write(1,11,"NOVIEMBRE",$format_bold);    $sheet->write(1,12,"DICIEMBRE",$format_bold);     $sheet->write(1,13,"TOTAL",$format_bold);

              
              
              
                    /*Primera fila*/
                   
                   $CI->pdf->SetFont('Arial','',8);
                   $CI->pdf->SetY(40);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Requisiciones Efectuadas",1,0,"C",0);
                   for($z=0;$z<12;$z++){
                      if(isset($c_array[$z])){
                   $CI->pdf->Cell(18,4,$c_array[$z],1,0,"C",0);
                        $c_total +=$c_array[$z];
                      }
                   }
                   $CI->pdf->Cell(18,4,$c_total,1,0,"C",0);
                 

                   
                   
            
             // $sheet->write(2,0,"$fInicio",$format_bold); 
              
//              
//                    $i=2;
//                    $resultado = $this->ocompra_model->rpt_control_compras($fInicio,$fFin);
//
//                    foreach($resultado as $indice=>$value)
//                    {
//
//                                       $serie_requerimiento = $value->serie_requerimiento;
//                                       $numero_requerimiento = $value->numero_requerimiento;
//                                       $fecha_requerimiento = $value->fecha_requerimiento;
//                                       $serie_sc = $value->serie_sc;
//                                       $numero_sc = $value->numero_sc;
//                                       $fecha_sc = $value->fecha_sc;
//                                       $serie_oc = $value->serie_oc;       
//                                       $numero_oc = $value->numero_oc;
//                                       $fecha_oc = $value->fecha_oc;
//                                       $fecha_aproboc = $value->fecha_aproboc;
//                                       $fregistro_oc = $value->fregistro_oc;
//                                       $serie_ne = $value->serie_ne;
//                                       $numero_ne = $value->numero_ne;
//                                       $fecha_ne = $value->fecha_ne;
//                                       $fecha_guiacli = $value->fecha_guiacli;
//                                       $fregistro_ne = $value->fregistro_ne;
//                                       $codpro = $value->codpro;
//                                       $producto = $value->producto;
//
//
//                                   $sheet->write($i,0,"$serie_requerimiento",$format_bold);
//                                   $sheet->write($i,1,$numero_requerimiento,$format_bold);
//                                   $sheet->write($i,2,$fecha_requerimiento,$format_bold);
//                                   $sheet->write($i,3,$serie_sc,$format_bold);
//                                   $sheet->write($i,4,$numero_sc,$format_bold);
//                                   $sheet->write($i,5,$fecha_sc,$format_bold);
//                                   $sheet->write($i,6,$serie_oc,$format_bold);
//                                   $sheet->write($i,7,$numero_oc,$format_bold);
//                                   $sheet->write($i,8,$fecha_oc,$format_bold);
//                                   $sheet->write($i,9,$fecha_aproboc,$format_bold);
//                                   $sheet->write($i,10,$fregistro_oc,$format_bold);
//                                   $sheet->write($i,11,$serie_ne,$format_bold);
//                                   $sheet->write($i,12,$numero_ne,$format_bold);
//                                   $sheet->write($i,13,$fecha_ne,$format_bold);
//                                   $sheet->write($i,14,$fecha_guiacli,$format_bold);
//                                   $sheet->write($i,15,$fregistro_ne,$format_bold);
//                                   $sheet->write($i,16,$codpro,$format_bold);
//                                   $sheet->write($i,17,$producto,$format_bold);
//
//
//                    $i++;
//
//                    }


                                  $xls->close();
                                }        
        
            
       
       $data['anio_ini'] = $anio;
       $data['fila'] = $fila;
       $this->load->view(compras."rpt_requisiciones_atendidas.php",$data);
   
    
    
    
}   

        public function indicador_requis_materiales_estatico(){
        $tipo    = $this->input->get_post('tipo');    
        if($tipo=="excel"){
            
              $xls = new Spreadsheet_Excel_Writer();
              $xls->send("IndicadoresMateriales.xls");
              $sheet  =$xls->addWorksheet('Ind.Materiales');
              $sheet->setInputEncoding('ISO-8859-7');
 
              //$sheet->setInputEncoding('ISO-8859-1');
              //$sheet->setInputEncoding('utf-8');
              
              $sheet->setColumn(0,0,16); //COLUMNA A1
              $sheet->setColumn(1,1,12); //COLUMNA B2
              $sheet->setColumn(2,2,8); //COLUMNA C3
              $sheet->setColumn(3,3,8); //COLUMNA D4
              $sheet->setColumn(4,4,8); //COLUMNA E5
              $sheet->setColumn(5,5,8); //COLUMNA F6
              $sheet->setColumn(6,6,8); //COLUMNA G7
              $sheet->setColumn(7,7,8); //COLUMNA H8
              $sheet->setColumn(8,8,8); //COLUMNA I9
              $sheet->setColumn(9,9,10); //COLUMNA J10
              $sheet->setColumn(10,10,8); //COLUMNA K11
              $sheet->setColumn(11,11,10); //COLUMNA L12
              $sheet->setColumn(12,12,10); //COLUMNA M13
              $sheet->setColumn(13,13,8); //COLUMNA N14
              $sheet->setColumn(14,14,8); //COLUMNA O15
              $sheet->setColumn(15,15,8); //COLUMNA P16
              $sheet->setColumn(16,16,8); //COLUMNA Q17
              $sheet->setColumn(17,17,8); //COLUMNA R18

              $sheet->setRow(0, 40);
              $sheet->setRow(1,39);
              $sheet->setRow(2,44);
              $sheet->setRow(3,44);
              $sheet->setRow(4,44);
              $sheet->setRow(5,5);
              $sheet->setRow(6,15);
              $sheet->setRow(7,44);
              $sheet->setRow(8,44);
              $sheet->setRow(9,44);
              
              
              $format_bold=$xls->addFormat();
              $format_bold->setBold();
              $format_bold->setSize(9);
              $format_bold->setvAlign('vcenter');
              $format_bold->sethAlign('center');
              $format_bold->setBorder(1);
              $format_bold->setTextWrap();
             
              $format_titulo=$xls->addFormat();
              $format_titulo->setBold();
              $format_titulo->setSize(7);
              $format_titulo->setvAlign('vcenter');
              $format_titulo->sethAlign('center');
              $format_titulo->setBorder(1);
              $format_titulo->setTextWrap();
              
              $sheet->mergeCells(0,0,0,13);  
              $sheet->write(0,1,"INDICADORES DE MATERIALES",$format_titulo);

              $sheet->write(0,0,"REPORTE DEL 2011",$format_bold); 
              
                $sheet->write(1,0,"CONCEPTO   /   MES",$format_bold);       $sheet->write(1,1,"ENERO",$format_bold);  $sheet->write(1,2,"FEBRERO",$format_bold);  $sheet->write(1,3,"MARZO",$format_bold);   $sheet->write(1,4,"ABRIL",$format_bold);      $sheet->write(1,5,"MAYO",$format_bold);   $sheet->write(1,6,"JUNIO",$format_bold);    $sheet->write(1,7,"JULIO",$format_bold);   $sheet->write(1,8,"AGOSTO",$format_bold);   $sheet->write(1,9,"SETIEMBRE",$format_bold);    $sheet->write(1,10,"OCTUBRE",$format_bold);    $sheet->write(1,11,"NOVIEMBRE",$format_bold);   $sheet->write(1,12,"DICIEMBRE",$format_bold);   $sheet->write(1,13,"TOTAL",$format_bold);
                $sheet->write(2,0,"Requisiciones Efectuadas",$format_bold); $sheet->write(2,1,"188",$format_bold);    $sheet->write(2,2,"341",$format_bold);      $sheet->write(2,3,"347",$format_bold);     $sheet->write(2,4,"468",$format_bold);        $sheet->write(2,5,"406",$format_bold);    $sheet->write(2,6,"375",$format_bold);      $sheet->write(2,7,"337",$format_bold);     $sheet->write(2,8,"564",$format_bold);      $sheet->write(2,9,"585",$format_bold);          $sheet->write(2,10,"488",$format_bold);        $sheet->write(2,11,"670",$format_bold);         $sheet->write(2,12,"740",$format_bold);         $sheet->write(2,13,"5509",$format_bold);
                $sheet->write(3,0,"Requisiciones Atendidas",$format_bold);  $sheet->write(3,1,"89",$format_bold);     $sheet->write(3,2,"151",$format_bold);      $sheet->write(3,3,"166",$format_bold);     $sheet->write(3,4,"202",$format_bold);        $sheet->write(3,5,"180",$format_bold);    $sheet->write(3,6,"141",$format_bold);      $sheet->write(3,7,"139",$format_bold);     $sheet->write(3,8,"228",$format_bold);      $sheet->write(3,9,"192",$format_bold);          $sheet->write(3,10,"210",$format_bold);        $sheet->write(3,11,"213",$format_bold);         $sheet->write(3,12,"291",$format_bold);         $sheet->write(3,13,"2202",$format_bold);
                $sheet->write(4,0,"Indice de atencion en %",$format_bold);  $sheet->write(4,1,"47.34",$format_bold);  $sheet->write(4,2,"44.28",$format_bold);    $sheet->write(4,3,"47.84",$format_bold);   $sheet->write(4,4,"43.16",$format_bold);      $sheet->write(4,5,"44.33",$format_bold);  $sheet->write(4,6,"37.60",$format_bold);    $sheet->write(4,7,"41.25",$format_bold);   $sheet->write(4,8,"40.30",$format_bold);    $sheet->write(4,9,"32.82",$format_bold);        $sheet->write(4,10,"43.03",$format_bold);      $sheet->write(4,11,"31.79",$format_bold);       $sheet->write(4,12,"39.32",$format_bold);       $sheet->write(4,13,"41.09",$format_bold);
                
                $sheet->mergeCells(5,0,5,13);
                $sheet->mergeCells(6,0,5,13);
                
                $sheet->write(6,0,"Indices por periodo");
                $sheet->write(7,0,"Atendidas en 24 hrs o menos",$format_bold);  $sheet->write(7,1,"18",$format_bold);    $sheet->write(7,2,"16",$format_bold);     $sheet->write(7,3,"29",$format_bold);    $sheet->write(7,4,"25",$format_bold);       $sheet->write(7,5,"34",$format_bold);   $sheet->write(7,6,"30",$format_bold);     $sheet->write(7,7,"17",$format_bold);    $sheet->write(7,8,"21",$format_bold);     $sheet->write(7,9,"27",$format_bold);         $sheet->write(7,10,"30",$format_bold);       $sheet->write(7,11,"25",$format_bold);        $sheet->write(7,12,"57",$format_bold);        $sheet->write(7,13,"329",$format_bold);
                $sheet->write(8,0,"Atendidas entre 24 y 48 hrs",$format_bold);  $sheet->write(8,1,"0",$format_bold);     $sheet->write(8,2,"0",$format_bold);      $sheet->write(8,3,"0",$format_bold);     $sheet->write(8,4,"0",$format_bold);        $sheet->write(8,5,"0",$format_bold);    $sheet->write(8,6,"0",$format_bold);      $sheet->write(8,7,"0",$format_bold);     $sheet->write(8,8,"0",$format_bold);      $sheet->write(8,9,"0",$format_bold);          $sheet->write(8,10,"0",$format_bold);        $sheet->write(8,11,"0",$format_bold);         $sheet->write(8,12,"0",$format_bold);         $sheet->write(8,13,"0",$format_bold);
                $sheet->write(9,0,"Atendidas en mas de 48 hrs",$format_bold);   $sheet->write(9,1,"71",$format_bold);    $sheet->write(9,2,"135",$format_bold);    $sheet->write(9,3,"137",$format_bold);   $sheet->write(9,4,"177",$format_bold);      $sheet->write(9,5,"146",$format_bold);  $sheet->write(9,6,"111",$format_bold);    $sheet->write(9,7,"122",$format_bold);   $sheet->write(9,8,"207",$format_bold);    $sheet->write(9,9,"165",$format_bold);        $sheet->write(9,10,"180",$format_bold);      $sheet->write(9,11,"188",$format_bold);       $sheet->write(9,12,"234",$format_bold);       $sheet->write(9,13,"1873",$format_bold);

                $xls->close();
        }
        
        
        elseif($tipo=="pdf"){     
                $this->load->library("fpdf/pdf");
                $CI = & get_instance();
                $CI->pdf->FPDF('L','mm','A4');
                $CI->pdf->AliasNbPages();
                
                $CI->pdf->AddPage();
                $CI->pdf->SetTextColor(0,0,0);
                $CI->pdf->SetFillColor(255,255,255);


                $CI->pdf->SetFont('Arial','B',13);
                $CI->pdf->SetY(5);
                $CI->pdf->SetX(6); 
                $CI->pdf->Cell(284,20,"Indicadores de Requisiciones de Materiales",1,1,"C",0);
                $CI->pdf->SetX(6); 
                $CI->pdf->Cell(284,8,"Requisiciones durante el 2011",1,1,"L",0);

                $CI->pdf->SetFont('Arial','',7);
                $CI->pdf->SetY(35);
                $CI->pdf->SetX(6); 
                $CI->pdf->Cell(50,5,"CONCEPTO  /  MES",1,0,"C",0);
                $CI->pdf->Cell(18,5,"ENERO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"FEBRERO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"MARZO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"ABRIL",1,0,"C",0);
                $CI->pdf->Cell(18,5,"MAYO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"JUNIO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"JULIO",1,0,"C",0); //
                $CI->pdf->Cell(18,5,"AGOSTO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"SETIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"OCTUBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"NOVIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"DICIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"TOTAL",1,0,"C",0);
                

                   $CI->pdf->SetFont('Arial','',8);
                   $CI->pdf->SetY(40);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Requisiciones Efectuadas",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"188",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"341",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"347",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"468",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"406",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"375",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"337",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"564",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"585",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"488",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"670",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"740",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"5509",1,0,"C",0);
                   
                   $CI->pdf->SetY(44);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Requisiciones Atendidas",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"89",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"151",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"166",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"202",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"180",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"141",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"139",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"228",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"192",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"210",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"213",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"291",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"2202",1,0,"C",0);

                   $CI->pdf->SetY(48);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Indice de Atencion (%)",1,0,"C",0);                   
                   $CI->pdf->Cell(18,4,"47.34",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"44.28",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"47.84",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"43.16",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"44.33",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"37.60",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"41.25",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"40.30",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"32.82",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"43.03",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"31.79",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"39.32",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"41.09",1,0,"C",0);

                   $CI->pdf->SetY(52);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas en 24h o Menos",1,0,"C",0);   
                   $CI->pdf->Cell(18,4,"18",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"16",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"29",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"25",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"34",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"30",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"17",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"21",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"27",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"30",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"25",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"57",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"329",1,0,"C",0);
               

                   $CI->pdf->SetY(56);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas entre 24 y 48h",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   
                  
                   $CI->pdf->SetY(60);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas en mas de 48h",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"71",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"135",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"137",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"177",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"146",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"111",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"122",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"207",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"165",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"180",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"188",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"234",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"1873",1,0,"C",0);
                   
                   $CI->pdf->Output();
             }    

        
        $this->load->view(compras."materiales.html");   
        } 
        
        
        
        
        
        
        
        
        
        
        
        
        public function indicador_requis_transporte_estatico(){
            
       $tipo    = $this->input->get_post('tipo');    
    
        
        
        if($tipo=="excel"){
            
              $xls = new Spreadsheet_Excel_Writer();
              $xls->send("IndicadoresServ.Transporte.xls");
              $sheet  =$xls->addWorksheet('Ind.Serv.Trans Nacional');
              $sheet->setInputEncoding('ISO-8859-7');
 
              //$sheet->setInputEncoding('ISO-8859-1');
              //$sheet->setInputEncoding('utf-8');
              
              $sheet->setColumn(0,0,16); //COLUMNA A1
              $sheet->setColumn(1,1,12); //COLUMNA B2
              $sheet->setColumn(2,2,8); //COLUMNA C3
              $sheet->setColumn(3,3,8); //COLUMNA D4
              $sheet->setColumn(4,4,8); //COLUMNA E5
              $sheet->setColumn(5,5,8); //COLUMNA F6
              $sheet->setColumn(6,6,8); //COLUMNA G7
              $sheet->setColumn(7,7,8); //COLUMNA H8
              $sheet->setColumn(8,8,8); //COLUMNA I9
              $sheet->setColumn(9,9,10); //COLUMNA J10
              $sheet->setColumn(10,10,8); //COLUMNA K11
              $sheet->setColumn(11,11,10); //COLUMNA L12
              $sheet->setColumn(12,12,10); //COLUMNA M13
              $sheet->setColumn(13,13,8); //COLUMNA N14
              $sheet->setColumn(14,14,8); //COLUMNA O15
              $sheet->setColumn(15,15,8); //COLUMNA P16
              $sheet->setColumn(16,16,8); //COLUMNA Q17
              $sheet->setColumn(17,17,8); //COLUMNA R18

              $sheet->setRow(0, 40);
              $sheet->setRow(1,39);
              $sheet->setRow(2,44);
              $sheet->setRow(3,44);
              $sheet->setRow(4,44);
              $sheet->setRow(5,5);
              $sheet->setRow(6,15);
              $sheet->setRow(7,44);
              $sheet->setRow(8,44);
              $sheet->setRow(9,44);
              
              
              $format_bold=$xls->addFormat();
              $format_bold->setBold();
              $format_bold->setSize(9);
              $format_bold->setvAlign('vcenter');
              $format_bold->sethAlign('center');
              $format_bold->setBorder(1);
              $format_bold->setTextWrap();
             
              $format_titulo=$xls->addFormat();
              $format_titulo->setBold();
              $format_titulo->setSize(7);
              $format_titulo->setvAlign('vcenter');
              $format_titulo->sethAlign('center');
              $format_titulo->setBorder(1);
              $format_titulo->setTextWrap();
              
              $sheet->mergeCells(0,0,0,13);  
              $sheet->write(0,1,"INDICADORES DE TRANSPORTE",$format_titulo);    $sheet->write(0,2,"TIPO: Trans. Nacional",$format_titulo);

              $sheet->write(0,0,"REPORTE DEL 2011",$format_bold); 
              
                $sheet->write(1,0,"CONCEPTO   /   MES",$format_bold);       $sheet->write(1,1,"ENERO",$format_bold);  $sheet->write(1,2,"FEBRERO",$format_bold);   $sheet->write(1,3,"MARZO",$format_bold);    $sheet->write(1,4,"ABRIL",$format_bold);   $sheet->write(1,5,"MAYO",$format_bold);    $sheet->write(1,6,"JUNIO",$format_bold);    $sheet->write(1,7,"JULIO",$format_bold);    $sheet->write(1,8,"AGOSTO",$format_bold);   $sheet->write(1,9,"SETIEMBRE",$format_bold);     $sheet->write(1,10,"OCTUBRE",$format_bold);     $sheet->write(1,11,"NOVIEMBRE",$format_bold);    $sheet->write(1,12,"DICIEMBRE",$format_bold);   $sheet->write(1,13,"TOTAL",$format_bold);
                $sheet->write(2,0,"Requisiciones Efectuadas",$format_bold); $sheet->write(2,1,"59",$format_bold);     $sheet->write(2,2,"27",$format_bold);        $sheet->write(2,3,"36",$format_bold);       $sheet->write(2,4,"22",$format_bold);       $sheet->write(2,5,"21",$format_bold);      $sheet->write(2,6,"18",$format_bold);       $sheet->write(2,7,"28",$format_bold);       $sheet->write(2,8,"29",$format_bold);       $sheet->write(2,9,"37",$format_bold);            $sheet->write(2,10,"25",$format_bold);          $sheet->write(2,11,"37",$format_bold);           $sheet->write(2,12,"55",$format_bold);           $sheet->write(2,13,"394",$format_bold);
                $sheet->write(3,0,"Requisiciones Atendidas",$format_bold);  $sheet->write(3,1,"12",$format_bold);     $sheet->write(3,2,"5",$format_bold);        $sheet->write(3,3,"22",$format_bold);       $sheet->write(3,4,"0",$format_bold);       $sheet->write(3,5,"13",$format_bold);      $sheet->write(3,6,"3",$format_bold);       $sheet->write(3,7,"19",$format_bold);       $sheet->write(3,8,"14",$format_bold);       $sheet->write(3,9,"26",$format_bold);            $sheet->write(3,10,"17",$format_bold);          $sheet->write(3,11,"18",$format_bold);           $sheet->write(3,12,"4",$format_bold);           $sheet->write(3,13,"153",$format_bold);
                $sheet->write(4,0,"Indice de atencion en %",$format_bold);  $sheet->write(4,1,"20.34",$format_bold);  $sheet->write(4,2,"18.52",$format_bold);    $sheet->write(4,3,"61.11",$format_bold);   $sheet->write(4,4,"0.00",$format_bold);       $sheet->write(4,5,"61.90",$format_bold);  $sheet->write(4,6,"16.67",$format_bold);   $sheet->write(4,7,"67.86",$format_bold);   $sheet->write(4,8,"48.28",$format_bold);    $sheet->write(4,9,"70.27",$format_bold);        $sheet->write(4,10,"68.00",$format_bold);      $sheet->write(4,11,"48.65",$format_bold);       $sheet->write(4,12,"7.27",$format_bold);      $sheet->write(4,13,"40.74",$format_bold);
                
                $sheet->mergeCells(5,0,5,13);
                $sheet->mergeCells(6,0,5,13);
                
                $sheet->write(6,0,"Indices por periodo");
                $sheet->write(7,0,"Atendidas en 24 hrs o menos",$format_bold);  $sheet->write(7,1,"11",$format_bold);    $sheet->write(7,2,"4",$format_bold);    $sheet->write(7,3,"8",$format_bold);   $sheet->write(7,4,"0",$format_bold);      $sheet->write(7,5,"13",$format_bold);  $sheet->write(7,6,"3",$format_bold);    $sheet->write(7,7,"19",$format_bold);   $sheet->write(7,8,"13",$format_bold);    $sheet->write(7,9,"26",$format_bold);        $sheet->write(7,10,"17",$format_bold);      $sheet->write(7,11,"18",$format_bold);       $sheet->write(7,12,"4",$format_bold);       $sheet->write(7,13,"136",$format_bold);
                $sheet->write(8,0,"Atendidas entre 24 y 48 hrs",$format_bold);  $sheet->write(8,1,"0",$format_bold);    $sheet->write(8,2,"0",$format_bold);    $sheet->write(8,3,"0",$format_bold);   $sheet->write(8,4,"0",$format_bold);      $sheet->write(8,5,"0",$format_bold);  $sheet->write(8,6,"0",$format_bold);    $sheet->write(8,7,"0",$format_bold);   $sheet->write(8,8,"0",$format_bold);    $sheet->write(8,9,"0",$format_bold);        $sheet->write(8,10,"0",$format_bold);      $sheet->write(8,11,"0",$format_bold);       $sheet->write(8,12,"0",$format_bold);       $sheet->write(8,13,"0",$format_bold);
                $sheet->write(9,0,"Atendidas en mas de 48 hrs",$format_bold);   $sheet->write(9,1,"1",$format_bold);    $sheet->write(9,2,"1",$format_bold);    $sheet->write(9,3,"14",$format_bold);   $sheet->write(9,4,"0",$format_bold);      $sheet->write(9,5,"0",$format_bold);  $sheet->write(9,6,"0",$format_bold);    $sheet->write(9,7,"0",$format_bold);   $sheet->write(9,8,"1",$format_bold);    $sheet->write(9,9,"0",$format_bold);        $sheet->write(9,10,"0",$format_bold);      $sheet->write(9,11,"0",$format_bold);       $sheet->write(9,12,"0",$format_bold);       $sheet->write(9,13,"17",$format_bold);

                
                
              $sheet  =$xls->addWorksheet('Ind.Serv.Trans Acarreo Mat.');
              $sheet->setInputEncoding('ISO-8859-7');
 
              //$sheet->setInputEncoding('ISO-8859-1');
              //$sheet->setInputEncoding('utf-8');
              
              $sheet->setColumn(0,0,16); //COLUMNA A1
              $sheet->setColumn(1,1,12); //COLUMNA B2
              $sheet->setColumn(2,2,8); //COLUMNA C3
              $sheet->setColumn(3,3,8); //COLUMNA D4
              $sheet->setColumn(4,4,8); //COLUMNA E5
              $sheet->setColumn(5,5,8); //COLUMNA F6
              $sheet->setColumn(6,6,8); //COLUMNA G7
              $sheet->setColumn(7,7,8); //COLUMNA H8
              $sheet->setColumn(8,8,8); //COLUMNA I9
              $sheet->setColumn(9,9,10); //COLUMNA J10
              $sheet->setColumn(10,10,8); //COLUMNA K11
              $sheet->setColumn(11,11,10); //COLUMNA L12
              $sheet->setColumn(12,12,10); //COLUMNA M13
              $sheet->setColumn(13,13,8); //COLUMNA N14
              $sheet->setColumn(14,14,8); //COLUMNA O15
              $sheet->setColumn(15,15,8); //COLUMNA P16
              $sheet->setColumn(16,16,8); //COLUMNA Q17
              $sheet->setColumn(17,17,8); //COLUMNA R18

              $sheet->setRow(0, 40);
              $sheet->setRow(1,39);
              $sheet->setRow(2,44);
              $sheet->setRow(3,44);
              $sheet->setRow(4,44);
              $sheet->setRow(5,5);
              $sheet->setRow(6,15);
              $sheet->setRow(7,44);
              $sheet->setRow(8,44);
              $sheet->setRow(9,44);
              
              
              $format_bold=$xls->addFormat();
              $format_bold->setBold();
              $format_bold->setSize(9);
              $format_bold->setvAlign('vcenter');
              $format_bold->sethAlign('center');
              $format_bold->setBorder(1);
              $format_bold->setTextWrap();
             
              $format_titulo=$xls->addFormat();
              $format_titulo->setBold();
              $format_titulo->setSize(7);
              $format_titulo->setvAlign('vcenter');
              $format_titulo->sethAlign('center');
              $format_titulo->setBorder(1);
              $format_titulo->setTextWrap();
              
              $sheet->mergeCells(0,0,0,13);  
              $sheet->write(0,1,"INDICADORES DE TRANSPORTE",$format_titulo);    $sheet->write(0,2,"TIPO: Trans. Acarreo Material",$format_titulo);

              $sheet->write(0,0,"REPORTE DEL 2011",$format_bold); 
              
                $sheet->write(1,0,"CONCEPTO   /   MES",$format_bold);       $sheet->write(1,1,"ENERO",$format_bold);  $sheet->write(1,2,"FEBRERO",$format_bold);  $sheet->write(1,3,"MARZO",$format_bold);   $sheet->write(1,4,"ABRIL",$format_bold);      $sheet->write(1,5,"MAYO",$format_bold);   $sheet->write(1,6,"JUNIO",$format_bold);    $sheet->write(1,7,"JULIO",$format_bold);   $sheet->write(1,8,"AGOSTO",$format_bold);   $sheet->write(1,9,"SETIEMBRE",$format_bold);    $sheet->write(1,10,"OCTUBRE",$format_bold);    $sheet->write(1,11,"NOVIEMBRE",$format_bold);   $sheet->write(1,12,"DICIEMBRE",$format_bold);   $sheet->write(1,13,"TOTAL",$format_bold);
                $sheet->write(2,0,"Requisiciones Efectuadas",$format_bold); $sheet->write(2,1,"0",$format_bold);      $sheet->write(2,2,"0",$format_bold);        $sheet->write(2,3,"0",$format_bold);       $sheet->write(2,4,"0",$format_bold);          $sheet->write(2,5,"0",$format_bold);      $sheet->write(2,6,"0",$format_bold);        $sheet->write(2,7,"0",$format_bold);       $sheet->write(2,8,"0",$format_bold);        $sheet->write(2,9,"0",$format_bold);            $sheet->write(2,10,"0",$format_bold);          $sheet->write(2,11,"0",$format_bold);           $sheet->write(2,12,"0",$format_bold);           $sheet->write(2,13,"0",$format_bold);
                $sheet->write(3,0,"Requisiciones Atendidas",$format_bold);  $sheet->write(3,1,"0",$format_bold);      $sheet->write(3,2,"0",$format_bold);        $sheet->write(3,3,"0",$format_bold);       $sheet->write(3,4,"0",$format_bold);          $sheet->write(3,5,"0",$format_bold);      $sheet->write(3,6,"0",$format_bold);        $sheet->write(3,7,"0",$format_bold);       $sheet->write(3,8,"0",$format_bold);        $sheet->write(3,9,"0",$format_bold);            $sheet->write(3,10,"0",$format_bold);          $sheet->write(3,11,"0",$format_bold);           $sheet->write(3,12,"0",$format_bold);           $sheet->write(3,13,"0",$format_bold);
                $sheet->write(4,0,"Indice de atencion en %",$format_bold);  $sheet->write(4,1,"0.0",$format_bold);    $sheet->write(4,2,"0.0",$format_bold);      $sheet->write(4,3,"0.0",$format_bold);     $sheet->write(4,4,"0.0",$format_bold);        $sheet->write(4,5,"0.0",$format_bold);    $sheet->write(4,6,"0.0",$format_bold);      $sheet->write(4,7,"0.0",$format_bold);     $sheet->write(4,8,"0.0",$format_bold);      $sheet->write(4,9,"0.0",$format_bold);          $sheet->write(4,10,"0.0",$format_bold);        $sheet->write(4,11,"0.0",$format_bold);         $sheet->write(4,12,"0.0",$format_bold);         $sheet->write(4,13,"0.0",$format_bold);
                
                $sheet->mergeCells(5,0,5,13);
                $sheet->mergeCells(6,0,5,13);
                
                $sheet->write(6,0,"Indices por periodo");
                $sheet->write(7,0,"Atendidas en 24 hrs o menos",$format_bold);  $sheet->write(7,1,"0",$format_bold);     $sheet->write(7,2,"0",$format_bold);      $sheet->write(7,3,"0",$format_bold);     $sheet->write(7,4,"0",$format_bold);        $sheet->write(7,5,"0",$format_bold);    $sheet->write(7,6,"0",$format_bold);      $sheet->write(7,7,"0",$format_bold);     $sheet->write(7,8,"0",$format_bold);      $sheet->write(7,9,"0",$format_bold);          $sheet->write(7,10,"0",$format_bold);        $sheet->write(7,11,"0",$format_bold);          $sheet->write(7,12,"0",$format_bold);         $sheet->write(7,13,"0",$format_bold);
                $sheet->write(8,0,"Atendidas entre 24 y 48 hrs",$format_bold);  $sheet->write(8,1,"0",$format_bold);     $sheet->write(8,2,"0",$format_bold);      $sheet->write(8,3,"0",$format_bold);     $sheet->write(8,4,"0",$format_bold);        $sheet->write(8,5,"0",$format_bold);    $sheet->write(8,6,"0",$format_bold);      $sheet->write(8,7,"0",$format_bold);     $sheet->write(8,8,"0",$format_bold);      $sheet->write(8,9,"0",$format_bold);          $sheet->write(8,10,"0",$format_bold);        $sheet->write(8,11,"0",$format_bold);          $sheet->write(8,12,"0",$format_bold);         $sheet->write(8,13,"0",$format_bold);
                $sheet->write(9,0,"Atendidas en mas de 48 hrs",$format_bold);   $sheet->write(9,1,"0",$format_bold);     $sheet->write(9,2,"0",$format_bold);      $sheet->write(9,3,"0",$format_bold);     $sheet->write(9,4,"0",$format_bold);        $sheet->write(9,5,"0",$format_bold);    $sheet->write(9,6,"0",$format_bold);      $sheet->write(9,7,"0",$format_bold);     $sheet->write(9,8,"0",$format_bold);      $sheet->write(9,9,"0",$format_bold);          $sheet->write(9,10,"0",$format_bold);        $sheet->write(9,11,"0",$format_bold);          $sheet->write(9,12,"0",$format_bold);         $sheet->write(9,13,"0",$format_bold);
                

                
                
                
                
              $sheet  =$xls->addWorksheet('Ind.Serv.Trans Internacional');
              $sheet->setInputEncoding('ISO-8859-7');
 
              //$sheet->setInputEncoding('ISO-8859-1');
              //$sheet->setInputEncoding('utf-8');
              
              $sheet->setColumn(0,0,16); //COLUMNA A1
              $sheet->setColumn(1,1,12); //COLUMNA B2
              $sheet->setColumn(2,2,8); //COLUMNA C3
              $sheet->setColumn(3,3,8); //COLUMNA D4
              $sheet->setColumn(4,4,8); //COLUMNA E5
              $sheet->setColumn(5,5,8); //COLUMNA F6
              $sheet->setColumn(6,6,8); //COLUMNA G7
              $sheet->setColumn(7,7,8); //COLUMNA H8
              $sheet->setColumn(8,8,8); //COLUMNA I9
              $sheet->setColumn(9,9,10); //COLUMNA J10
              $sheet->setColumn(10,10,8); //COLUMNA K11
              $sheet->setColumn(11,11,10); //COLUMNA L12
              $sheet->setColumn(12,12,10); //COLUMNA M13
              $sheet->setColumn(13,13,8); //COLUMNA N14
              $sheet->setColumn(14,14,8); //COLUMNA O15
              $sheet->setColumn(15,15,8); //COLUMNA P16
              $sheet->setColumn(16,16,8); //COLUMNA Q17
              $sheet->setColumn(17,17,8); //COLUMNA R18

              $sheet->setRow(0, 40);
              $sheet->setRow(1,39);
              $sheet->setRow(2,44);
              $sheet->setRow(3,44);
              $sheet->setRow(4,44);
              $sheet->setRow(5,5);
              $sheet->setRow(6,15);
              $sheet->setRow(7,44);
              $sheet->setRow(8,44);
              $sheet->setRow(9,44);
              
              
              $format_bold=$xls->addFormat();
              $format_bold->setBold();
              $format_bold->setSize(9);
              $format_bold->setvAlign('vcenter');
              $format_bold->sethAlign('center');
              $format_bold->setBorder(1);
              $format_bold->setTextWrap();
             
              $format_titulo=$xls->addFormat();
              $format_titulo->setBold();
              $format_titulo->setSize(7);
              $format_titulo->setvAlign('vcenter');
              $format_titulo->sethAlign('center');
              $format_titulo->setBorder(1);
              $format_titulo->setTextWrap();
              
              $sheet->mergeCells(0,0,0,13);  
              $sheet->write(0,1,"INDICADORES DE TRANSPORTE",$format_titulo);    $sheet->write(0,2,"TIPO: Trans. Internacional",$format_titulo);

              $sheet->write(0,0,"REPORTE DEL 2011",$format_bold); 
              
                $sheet->write(1,0,"CONCEPTO   /   MES",$format_bold);       $sheet->write(1,1,"ENERO",$format_bold);  $sheet->write(1,2,"FEBRERO",$format_bold);  $sheet->write(1,3,"MARZO",$format_bold);   $sheet->write(1,4,"ABRIL",$format_bold);      $sheet->write(1,5,"MAYO",$format_bold);   $sheet->write(1,6,"JUNIO",$format_bold);    $sheet->write(1,7,"JULIO",$format_bold);   $sheet->write(1,8,"AGOSTO",$format_bold);   $sheet->write(1,9,"SETIEMBRE",$format_bold);    $sheet->write(1,10,"OCTUBRE",$format_bold);    $sheet->write(1,11,"NOVIEMBRE",$format_bold);   $sheet->write(1,12,"DICIEMBRE",$format_bold);   $sheet->write(1,13,"TOTAL",$format_bold);
                $sheet->write(2,0,"Requisiciones Efectuadas",$format_bold); $sheet->write(2,1,"0",$format_bold);      $sheet->write(2,2,"0",$format_bold);        $sheet->write(2,3,"0",$format_bold);       $sheet->write(2,4,"0",$format_bold);          $sheet->write(2,5,"0",$format_bold);      $sheet->write(2,6,"3",$format_bold);        $sheet->write(2,7,"2",$format_bold);       $sheet->write(2,8,"2",$format_bold);        $sheet->write(2,9,"0",$format_bold);            $sheet->write(2,10,"1",$format_bold);          $sheet->write(2,11,"0",$format_bold);           $sheet->write(2,12,"1",$format_bold);           $sheet->write(2,13,"9",$format_bold);
                $sheet->write(3,0,"Requisiciones Atendidas",$format_bold);  $sheet->write(3,1,"0",$format_bold);      $sheet->write(3,2,"0",$format_bold);        $sheet->write(3,3,"0",$format_bold);       $sheet->write(3,4,"0",$format_bold);          $sheet->write(3,5,"0",$format_bold);      $sheet->write(3,6,"0",$format_bold);        $sheet->write(3,7,"0",$format_bold);       $sheet->write(3,8,"0",$format_bold);        $sheet->write(3,9,"0",$format_bold);            $sheet->write(3,10,"1",$format_bold);          $sheet->write(3,11,"0",$format_bold);           $sheet->write(3,12,"0",$format_bold);           $sheet->write(3,13,"1",$format_bold);
                $sheet->write(4,0,"Indice de atencion en %",$format_bold);  $sheet->write(4,1,"0.0",$format_bold);    $sheet->write(4,2,"0.0",$format_bold);      $sheet->write(4,3,"0.0",$format_bold);     $sheet->write(4,4,"0.0",$format_bold);        $sheet->write(4,5,"0.0",$format_bold);    $sheet->write(4,6,"0.0",$format_bold);      $sheet->write(4,7,"0.0",$format_bold);     $sheet->write(4,8,"0.0",$format_bold);      $sheet->write(4,9,"0.0",$format_bold);        $sheet->write(4,10,"100.0",$format_bold);      $sheet->write(4,11,"0.0",$format_bold);         $sheet->write(4,12,"0.0",$format_bold);         $sheet->write(4,13,"8.33",$format_bold);
                
                $sheet->mergeCells(5,0,5,13);
                $sheet->mergeCells(6,0,5,13);
                
                $sheet->write(6,0,"Indices por periodo");
                $sheet->write(7,0,"Atendidas en 24 hrs o menos",$format_bold);  $sheet->write(7,1,"0",$format_bold);    $sheet->write(7,2,"0",$format_bold);    $sheet->write(7,3,"0",$format_bold);   $sheet->write(7,4,"0",$format_bold);      $sheet->write(7,5,"0",$format_bold);  $sheet->write(7,6,"0",$format_bold);    $sheet->write(7,7,"0",$format_bold);   $sheet->write(7,8,"0",$format_bold);    $sheet->write(7,9,"0",$format_bold);        $sheet->write(7,10,"1",$format_bold);      $sheet->write(7,11,"0",$format_bold);       $sheet->write(7,12,"0",$format_bold);        $sheet->write(7,13,"1",$format_bold);
                $sheet->write(8,0,"Atendidas entre 24 y 48 hrs",$format_bold);  $sheet->write(8,1,"0",$format_bold);    $sheet->write(8,2,"0",$format_bold);    $sheet->write(8,3,"0",$format_bold);   $sheet->write(8,4,"0",$format_bold);      $sheet->write(8,5,"0",$format_bold);  $sheet->write(8,6,"0",$format_bold);    $sheet->write(8,7,"0",$format_bold);   $sheet->write(8,8,"0",$format_bold);    $sheet->write(8,9,"0",$format_bold);        $sheet->write(8,10,"0",$format_bold);      $sheet->write(8,11,"0",$format_bold);       $sheet->write(8,12,"0",$format_bold);         $sheet->write(8,13,"0",$format_bold);
                $sheet->write(9,0,"Atendidas en mas de 48 hrs",$format_bold);   $sheet->write(9,1,"0",$format_bold);    $sheet->write(9,2,"0",$format_bold);    $sheet->write(9,3,"0",$format_bold);   $sheet->write(9,4,"0",$format_bold);      $sheet->write(9,5,"0",$format_bold);  $sheet->write(9,6,"0",$format_bold);    $sheet->write(9,7,"0",$format_bold);   $sheet->write(9,8,"0",$format_bold);    $sheet->write(9,9,"0",$format_bold);        $sheet->write(9,10,"0",$format_bold);      $sheet->write(9,11,"0",$format_bold);       $sheet->write(9,12,"0",$format_bold);       $sheet->write(9,13,"0",$format_bold);
                
                
                
                $xls->close();
        }
        
        
        elseif($tipo=="pdf"){     
                $this->load->library("fpdf/pdf");
                $CI = & get_instance();
                $CI->pdf->FPDF('L','mm','A4');
                $CI->pdf->AliasNbPages();
                
                $CI->pdf->AddPage();
                $CI->pdf->SetTextColor(0,0,0);
                $CI->pdf->SetFillColor(255,255,255);


                $CI->pdf->SetFont('Arial','B',13);
                $CI->pdf->SetY(5);
                $CI->pdf->SetX(6); 
                $CI->pdf->Cell(284,20,"Indicadores de Requisiciones de Servicios de Transporte",1,1,"C",0);
                $CI->pdf->SetX(6); 
                $CI->pdf->Cell(284,8,"Requisiciones durante el 2011 (Trans. Nacional)",1,1,"L",0);

                $CI->pdf->SetFont('Arial','',7);
                $CI->pdf->SetY(35);
                $CI->pdf->SetX(6); 
                $CI->pdf->Cell(50,5,"CONCEPTO  /  MES",1,0,"C",0);
                $CI->pdf->Cell(18,5,"ENERO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"FEBRERO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"MARZO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"ABRIL",1,0,"C",0);
                $CI->pdf->Cell(18,5,"MAYO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"JUNIO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"JULIO",1,0,"C",0); //
                $CI->pdf->Cell(18,5,"AGOSTO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"SETIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"OCTUBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"NOVIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"DICIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"TOTAL",1,0,"C",0);
                

                   $CI->pdf->SetFont('Arial','',8);
                   $CI->pdf->SetY(40);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Requisiciones Efectuadas",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"59",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"27",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"36",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"22",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"21",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"18",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"28",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"29",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"37",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"25",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"37",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"55",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"394",1,0,"C",0);
                   
                   $CI->pdf->SetY(44);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Requisiciones Atendidas",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"12",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"5",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"22",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"13",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"3",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"19",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"14",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"26",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"17",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"18",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"4",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"153",1,0,"C",0);

                   $CI->pdf->SetY(48);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Indice de Atencion (%)",1,0,"C",0);                   
                   $CI->pdf->Cell(18,4,"20.34",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"18.52",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"61.11",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"61.90",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"16.67",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"67.86",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"48.28",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"70.27",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"68.00",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"48.65",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"7.27",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"40.74",1,0,"C",0);

                   $CI->pdf->SetY(52);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas en 24h o Menos",1,0,"C",0);   
                   $CI->pdf->Cell(18,4,"11",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"4",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"8",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"13",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"3",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"19",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"13",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"26",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"17",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"18",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"4",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"136",1,0,"C",0);
               

                   $CI->pdf->SetY(56);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas entre 24 y 48h",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   
                  
                   $CI->pdf->SetY(60);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas en mas de 48h",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"1",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"1",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"14",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"1",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"17",1,0,"C",0);
                   
                   
                $CI->pdf->SetY(76);   
                $CI->pdf->SetX(6);
                $CI->pdf->SetFont('Arial','B',13);
                $CI->pdf->Cell(284,8,"Requisiciones durante el 2011 (Trans. Acarreo Material)",1,1,"L",0);
                
                $CI->pdf->SetFont('Arial','',7);
                $CI->pdf->SetY(85);
                $CI->pdf->SetX(6); 
                $CI->pdf->Cell(50,5,"CONCEPTO  /  MES",1,0,"C",0);
                $CI->pdf->Cell(18,5,"ENERO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"FEBRERO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"MARZO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"ABRIL",1,0,"C",0);
                $CI->pdf->Cell(18,5,"MAYO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"JUNIO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"JULIO",1,0,"C",0); //
                $CI->pdf->Cell(18,5,"AGOSTO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"SETIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"OCTUBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"NOVIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"DICIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"TOTAL",1,0,"C",0);                   
                 

                   $CI->pdf->SetFont('Arial','',8);
                   $CI->pdf->SetY(90);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Requisiciones Efectuadas",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   
                   $CI->pdf->SetY(94);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Requisiciones Atendidas",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);

                   $CI->pdf->SetY(98);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Indice de Atencion (%)",1,0,"C",0);                   
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);

                   $CI->pdf->SetY(102);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas en 24h o Menos",1,0,"C",0);   
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
               

                   $CI->pdf->SetY(106);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas entre 24 y 48h",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   
                  
                   $CI->pdf->SetY(110);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas en mas de 48h",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);                
                
                   
                   
                   
                $CI->pdf->SetY(126);   
                $CI->pdf->SetX(6);
                $CI->pdf->SetFont('Arial','B',13);
                $CI->pdf->Cell(284,8,"Requisiciones durante el 2011 (Trans. Internacional)",1,1,"L",0);
                     
                
                $CI->pdf->SetFont('Arial','',7);
                $CI->pdf->SetY(134);
                $CI->pdf->SetX(6); 
                $CI->pdf->Cell(50,5,"CONCEPTO  /  MES",1,0,"C",0);
                $CI->pdf->Cell(18,5,"ENERO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"FEBRERO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"MARZO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"ABRIL",1,0,"C",0);
                $CI->pdf->Cell(18,5,"MAYO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"JUNIO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"JULIO",1,0,"C",0); //
                $CI->pdf->Cell(18,5,"AGOSTO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"SETIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"OCTUBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"NOVIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"DICIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"TOTAL",1,0,"C",0);                   
                 

                   $CI->pdf->SetFont('Arial','',8);
                   $CI->pdf->SetY(139);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Requisiciones Efectuadas",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"3",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"2",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"2",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"1",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"1",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"9",1,0,"C",0);
                   
                   $CI->pdf->SetY(143);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Requisiciones Atendidas",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"1",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"1",1,0,"C",0);

                   $CI->pdf->SetY(147);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Indice de Atencion (%)",1,0,"C",0);                   
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.00",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.00",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"100.00",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0.0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"8.33",1,0,"C",0);

                   $CI->pdf->SetY(151);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas en 24h o Menos",1,0,"C",0);   
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"1",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"1",1,0,"C",0);
               

                   $CI->pdf->SetY(155);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas entre 24 y 48h",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   
                  
                   $CI->pdf->SetY(159);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas en mas de 48h",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);                
                
                
                
                   $CI->pdf->Output();
             }    

            
        $this->load->view(compras."transporte.html");   
        } 
        
        
        
        
        
        
        
        
        
        
        
        public function indicador_requis_otros_estatico(){
        
                $tipo    = $this->input->get_post('tipo');    
    
        
        
        if($tipo=="excel"){
            
              $xls = new Spreadsheet_Excel_Writer();
              $xls->send("IndicadoresOtrosServ.xls");
              $sheet  =$xls->addWorksheet('Ind.OtrosServ.');
              $sheet->setInputEncoding('ISO-8859-7');
 
              //$sheet->setInputEncoding('ISO-8859-1');
              //$sheet->setInputEncoding('utf-8');
              
              $sheet->setColumn(0,0,16); //COLUMNA A1
              $sheet->setColumn(1,1,12); //COLUMNA B2
              $sheet->setColumn(2,2,8); //COLUMNA C3
              $sheet->setColumn(3,3,8); //COLUMNA D4
              $sheet->setColumn(4,4,8); //COLUMNA E5
              $sheet->setColumn(5,5,8); //COLUMNA F6
              $sheet->setColumn(6,6,8); //COLUMNA G7
              $sheet->setColumn(7,7,8); //COLUMNA H8
              $sheet->setColumn(8,8,8); //COLUMNA I9
              $sheet->setColumn(9,9,10); //COLUMNA J10
              $sheet->setColumn(10,10,8); //COLUMNA K11
              $sheet->setColumn(11,11,10); //COLUMNA L12
              $sheet->setColumn(12,12,10); //COLUMNA M13
              $sheet->setColumn(13,13,8); //COLUMNA N14
              $sheet->setColumn(14,14,8); //COLUMNA O15
              $sheet->setColumn(15,15,8); //COLUMNA P16
              $sheet->setColumn(16,16,8); //COLUMNA Q17
              $sheet->setColumn(17,17,8); //COLUMNA R18

              $sheet->setRow(0, 40);
              $sheet->setRow(1,39);
              $sheet->setRow(2,44);
              $sheet->setRow(3,44);
              $sheet->setRow(4,44);
              $sheet->setRow(5,5);
              $sheet->setRow(6,15);
              $sheet->setRow(7,44);
              $sheet->setRow(8,44);
              $sheet->setRow(9,44);
              
              
              $format_bold=$xls->addFormat();
              $format_bold->setBold();
              $format_bold->setSize(9);
              $format_bold->setvAlign('vcenter');
              $format_bold->sethAlign('center');
              $format_bold->setBorder(1);
              $format_bold->setTextWrap();
             
              $format_titulo=$xls->addFormat();
              $format_titulo->setBold();
              $format_titulo->setSize(7);
              $format_titulo->setvAlign('vcenter');
              $format_titulo->sethAlign('center');
              $format_titulo->setBorder(1);
              $format_titulo->setTextWrap();
              
              $sheet->mergeCells(0,0,0,13);  
              $sheet->write(0,1,"INDICADORES DE OTROS Serv.",$format_titulo);

              $sheet->write(0,0,"REPORTE DEL 2011",$format_bold); 
              
                $sheet->write(1,0,"CONCEPTO   /   MES",$format_bold);       $sheet->write(1,1,"ENERO",$format_bold);  $sheet->write(1,2,"FEBRERO",$format_bold);  $sheet->write(1,3,"MARZO",$format_bold);    $sheet->write(1,4,"ABRIL",$format_bold);       $sheet->write(1,5,"MAYO",$format_bold);   $sheet->write(1,6,"JUNIO",$format_bold);    $sheet->write(1,7,"JULIO",$format_bold);   $sheet->write(1,8,"AGOSTO",$format_bold);    $sheet->write(1,9,"SETIEMBRE",$format_bold);    $sheet->write(1,10,"OCTUBRE",$format_bold);     $sheet->write(1,11,"NOVIEMBRE",$format_bold);   $sheet->write(1,12,"DICIEMBRE",$format_bold);   $sheet->write(1,13,"TOTAL",$format_bold);
                $sheet->write(2,0,"Requisiciones Efectuadas",$format_bold); $sheet->write(2,1,"74",$format_bold);     $sheet->write(2,2,"83",$format_bold);       $sheet->write(2,3,"101",$format_bold);       $sheet->write(2,4,"77",$format_bold);          $sheet->write(2,5,"132",$format_bold);     $sheet->write(2,6,"129",$format_bold);       $sheet->write(2,7,"108",$format_bold);      $sheet->write(2,8,"137",$format_bold);        $sheet->write(2,9,"142",$format_bold);     $sheet->write(2,10,"148",$format_bold);         $sheet->write(2,11,"133",$format_bold);          $sheet->write(2,12,"150",$format_bold);         $sheet->write(2,13,"1414",$format_bold);
                $sheet->write(3,0,"Requisiciones Atendidas",$format_bold);  $sheet->write(3,1,"30",$format_bold);     $sheet->write(3,2,"40",$format_bold);       $sheet->write(3,3,"50",$format_bold);       $sheet->write(3,4,"29",$format_bold);          $sheet->write(3,5,"63",$format_bold);     $sheet->write(3,6,"69",$format_bold);       $sheet->write(3,7,"69",$format_bold);      $sheet->write(3,8,"83",$format_bold);        $sheet->write(3,9,"96",$format_bold);          $sheet->write(3,10,"94",$format_bold);         $sheet->write(3,11,"53",$format_bold);          $sheet->write(3,12,"77",$format_bold);         $sheet->write(3,13,"753",$format_bold);
                $sheet->write(4,0,"Indice de atencion en %",$format_bold);  $sheet->write(4,1,"40.54",$format_bold);  $sheet->write(4,2,"48.19",$format_bold);    $sheet->write(4,3,"49.50",$format_bold);   $sheet->write(4,4,"37.66",$format_bold);      $sheet->write(4,5,"47.73",$format_bold);  $sheet->write(4,6,"53.49",$format_bold);    $sheet->write(4,7,"63.89",$format_bold);   $sheet->write(4,8,"60.58",$format_bold);    $sheet->write(4,9,"67.61",$format_bold);        $sheet->write(4,10,"63.51",$format_bold);      $sheet->write(4,11,"39.85",$format_bold);      $sheet->write(4,12,"51.33",$format_bold);       $sheet->write(4,13,"51.99",$format_bold);
                
                $sheet->mergeCells(5,0,5,13);
                $sheet->mergeCells(6,0,5,13);
                
                $sheet->write(6,0,"Indices por periodo");
                $sheet->write(7,0,"Atendidas en 24 hrs o menos",$format_bold);  $sheet->write(7,1,"18",$format_bold);    $sheet->write(7,2,"28",$format_bold);    $sheet->write(7,3,"13",$format_bold);   $sheet->write(7,4,"14",$format_bold);      $sheet->write(7,5,"38",$format_bold);    $sheet->write(7,6,"29",$format_bold);     $sheet->write(7,7,"40",$format_bold);       $sheet->write(7,8,"47",$format_bold);       $sheet->write(7,9,"62",$format_bold);        $sheet->write(7,10,"52",$format_bold);       $sheet->write(7,11,"32",$format_bold);       $sheet->write(7,12,"41",$format_bold);       $sheet->write(7,13,"414",$format_bold);
                $sheet->write(8,0,"Atendidas entre 24 y 48 hrs",$format_bold);  $sheet->write(8,1,"0",$format_bold);    $sheet->write(8,2,"0",$format_bold);    $sheet->write(8,3,"0",$format_bold);   $sheet->write(8,4,"0",$format_bold);          $sheet->write(8,5,"0",$format_bold);     $sheet->write(8,6,"0",$format_bold);      $sheet->write(8,7,"0",$format_bold);        $sheet->write(8,8,"0",$format_bold);        $sheet->write(8,9,"0",$format_bold);         $sheet->write(8,10,"0",$format_bold);        $sheet->write(8,11,"0",$format_bold);       $sheet->write(8,12,"0",$format_bold);       $sheet->write(8,13,"0",$format_bold);
                $sheet->write(9,0,"Atendidas en mas de 48 hrs",$format_bold);   $sheet->write(9,1,"12",$format_bold);    $sheet->write(9,2,"12",$format_bold);    $sheet->write(9,3,"37",$format_bold);   $sheet->write(9,4,"15",$format_bold);      $sheet->write(9,5,"25",$format_bold);    $sheet->write(9,6,"40",$format_bold);     $sheet->write(9,7,"29",$format_bold);       $sheet->write(9,8,"36",$format_bold);       $sheet->write(9,9,"34",$format_bold);        $sheet->write(9,10,"42",$format_bold);       $sheet->write(9,11,"21",$format_bold);       $sheet->write(9,12,"36",$format_bold);       $sheet->write(9,13,"339",$format_bold);

                $xls->close();
        }
        
        
        elseif($tipo=="pdf"){     
                $this->load->library("fpdf/pdf");
                $CI = & get_instance();
                $CI->pdf->FPDF('L','mm','A4');
                $CI->pdf->AliasNbPages();
                
                $CI->pdf->AddPage();
                $CI->pdf->SetTextColor(0,0,0);
                $CI->pdf->SetFillColor(255,255,255);


                $CI->pdf->SetFont('Arial','B',13);
                $CI->pdf->SetY(5);
                $CI->pdf->SetX(6); 
                $CI->pdf->Cell(284,20,"Indicadores de Requisiciones de Otros Servicios",1,1,"C",0);
                $CI->pdf->SetX(6); 
                $CI->pdf->Cell(284,8,"Requisiciones durante el 2011",1,1,"L",0);

                $CI->pdf->SetFont('Arial','',7);
                $CI->pdf->SetY(35);
                $CI->pdf->SetX(6); 
                $CI->pdf->Cell(50,5,"CONCEPTO  /  MES",1,0,"C",0);
                $CI->pdf->Cell(18,5,"ENERO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"FEBRERO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"MARZO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"ABRIL",1,0,"C",0);
                $CI->pdf->Cell(18,5,"MAYO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"JUNIO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"JULIO",1,0,"C",0); //
                $CI->pdf->Cell(18,5,"AGOSTO",1,0,"C",0);
                $CI->pdf->Cell(18,5,"SETIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"OCTUBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"NOVIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"DICIEMBRE",1,0,"C",0);
                $CI->pdf->Cell(18,5,"TOTAL",1,0,"C",0);
                

                   $CI->pdf->SetFont('Arial','',8);
                   $CI->pdf->SetY(40);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Requisiciones Efectuadas",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"74",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"83",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"101",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"77",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"132",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"129",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"108",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"137",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"142",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"148",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"133",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"150",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"1414",1,0,"C",0);
                   
                   $CI->pdf->SetY(44);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Requisiciones Atendidas",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"30",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"40",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"50",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"29",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"63",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"69",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"69",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"83",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"96",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"94",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"53",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"77",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"753",1,0,"C",0);

                   $CI->pdf->SetY(48);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Indice de Atencion (%)",1,0,"C",0);                   
                   $CI->pdf->Cell(18,4,"40.54",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"48.19",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"49.50",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"37.66",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"47.73",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"53.49",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"63.89",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"60.58",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"67.61",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"63.51",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"39.85",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"51.33",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"51.99",1,0,"C",0);

                   $CI->pdf->SetY(52);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas en 24h o Menos",1,0,"C",0);   
                   $CI->pdf->Cell(18,4,"18",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"28",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"13",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"14",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"38",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"29",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"40",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"47",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"62",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"52",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"32",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"41",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"414",1,0,"C",0);
               

                   $CI->pdf->SetY(56);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas entre 24 y 48h",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"0",1,0,"C",0);
                   
                  
                   $CI->pdf->SetY(60);
                   $CI->pdf->SetX(6);
                   $CI->pdf->Cell(50,4,"Atendidas en mas de 48h",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"12",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"12",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"37",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"15",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"25",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"40",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"29",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"36",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"34",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"42",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"21",1,0,"C",0); 
                   $CI->pdf->Cell(18,4,"36",1,0,"C",0);
                   $CI->pdf->Cell(18,4,"339",1,0,"C",0);
                   
                   $CI->pdf->Output();
             }    

   
            
            $this->load->view(compras."otros.html");   
        } 
        
        
        
        
     public function indicador_requis_transporte(){
         
                if(isset($_POST["anio"])){
       $anio    = $_POST["anio"];
       }
        else{
        $i = date("Y",time());
        $anio = ($i-1); 
        }
        //echo $anio;
        $fila = "";
        
             $tipo    = $this->input->get_post('tipo');
             $tiporequi    = $this->input->get_post('tiporequi');
        
             
             if($tipo=="html"){

                    for($i=0;$i<12;$i++){
                        $mes   = str_pad($i+1,2,"0",STR_PAD_LEFT);
                        $dia1  = "01";
                        $dia2  = date('t', mktime (0,0,0, $i+1, 1, $anio)); 
                        $fecha_inicial = $dia1."/".$mes."/".$anio;
                        $fecha_final   = $dia2."/".$mes."/".$anio;
                        
                        
                        
                        
                        
                        $resultado = $this->requis_model->indicador_requis_transporte($fecha_inicial,$fecha_final);
                        $j  = 0;
                        $c  = 0;
                        $c_total = 0;
                        $j_total = 0;
                        $menos24 = 0;
                        $entre24_48 =0;
                        $mas48 = 0;
                        $menos24_total = 0;
                        $entre24_48_total =0;
                        $mas48_total = 0;

                        foreach($resultado as $indice=>$value){
                          $cantidad 	= $value->Cnt_gestado; 
                            //$serie 	= $value->gserguia; 
                            //$numero 	= $value->gnumguia; 
                           // $fecha   	= $value->fecemi; 
                          $cantidad = $c;
                            $c++; 
                        }
                        
                        $resultado2 = $this->requis_model->indicador_requis_transporte_atendidas($fecha_inicial,$fecha_final);
                    
                        
                        foreach($resultado2 as $indice=>$value2){
                          $cantidad2	= $value2->Cnt_gestado; 
                            //$serie 	= $value->gserguia; 
                            //$numero 	= $value->gnumguia; 
                           // $fecha   	= $value->fecemi; 
                          $cantidad2 = $j;
                            $j++; 
                        }
                        
                        
                        if($c!=0){
                            $valor = ($j/$c)*100;	
                            $valor = number_format($valor, 2, '.', '');
                        }
                        else{
                            $valor = "--";
                        }
                        
                        $c_array[$i] = $c;
                        $j_array[$i] = $j;
                        $valor_array[$i] = $valor;
                        $menos24_array[$i] = $menos24;
                        $entre24_48_array[$i] = $entre24_48;
                        $mas48_array[$i] = $mas48;
                        if($i==3) break;//////////
                    }
                    
                    
                    
                    
                    
                    
                    /*Primera fila*/
                   $fila.="<tr>";
                   $fila.="<td>REQUISICIONES EFECTUADAS</td>";
                   for($z=0;$z<12;$z++){
                      if(isset($c_array[$z])){
                        $fila.="<td>".$c_array[$z]."</td>";
                        $c_total +=$c_array[$z];
                      }
                   }
                   $fila.="<td>".$c_total."</td>";
                   $fila.="</tr>";
                   /*Segunda fila*/
                   $fila.="<tr>";
                   $fila.="<td>REQUISICIONES ATENDIDAS</td>";
                   for($z=0;$z<12;$z++){
                       if(isset($j_array[$z])){
                           $fila.="<td>".$j_array[$z]."</td>";
                           $j_total +=$j_array[$z];
                       }
                   }
                   $fila.="<td>".$j_total."</td>";
                   $fila.="</tr>";
                   /*Tercera fila*/
                   $fila.="<tr>";
                   $fila.="<td>INDICE DE ATENCION (%)</td>";
                   for($z=0;$z<12;$z++){
                     if(isset($valor_array[$z])){
                       $fila.="<td>".$valor_array[$z]."</td>";
                     }
                   }
                   $fila.="<td>".number_format(($j_total*100/$c_total),2)."</td>";
                   $fila.="</tr>"; 
                   /*Cuarta fila*/


                   $fila.="<tr>";
                   $fila.="<td>Atendidas en 24h o menos</td>";
                   for($z=0;$z<12;$z++){
                      if(isset($menos24_array[$z])){
                        $fila.="<td>".$menos24_array[$z]."</td>";
                        $menos24_total +=$menos24_array[$z];
                      }
                   }
                   $fila.="<td>".$menos24_total."</td>";
                   $fila.="</tr>";       
                   /*Quinta fila*/



                   $fila.="<tr>";
                   $fila.="<td>Atendidas entre 24 y 48h</td>";
                   for($z=0;$z<12;$z++){
                      if(isset($entre24_48_array[$z])){
                        $fila.="<td>".$entre24_48_array[$z]."</td>";
                        $entre24_48_total +=$entre24_48_array[$z];
                      }
                   }
                   $fila.="<td>".$entre24_48_total."</td>";
                   $fila.="</tr>";       
                   /*Sexta fila*/



                   $fila.="<tr>";
                   $fila.="<td>Atendidas en más de 48h</td>";
                   for($z=0;$z<12;$z++){
                      if(isset($mas48_array[$z])){
                        $fila.="<td>".$mas48_array[$z]."</td>";
                        $mas48_total +=$mas48_array[$z];
                      }
                   }
                   $fila.="<td>".$mas48_total."</td>";
                   $fila.="</tr>";       
                   /*Septima fila*/   

               }
         
            $data['anio_ini'] = $anio;
            $data['fila'] = $fila;
            $this->load->view(compras."rpt_requisiciones_transporte.php",$data);
     }
        
    public function rpt_control_compras(){
        if(isset($_POST["fInicio"]) && $_POST["fFin"]!=""){
        $this->form_validation->set_rules('fInicio','Fecha','required');
        $this->form_validation->set_rules('fFin','Fecha','required');
        }
        else{
              $fInicio   = date("01/m/Y",time());
              $fFin      = date("d/m/Y",time());
        }
        $fila      = "";
        $fila2     = "";
        $registros = "";
        if($this->form_validation->run() == TRUE)
        {
            $fFin    = $this->input->get_post('fFin');
            $fInicio = $this->input->get_post('fInicio');   
            $tipo    = $this->input->get_post('tipo');
            if($tipo=="html"){
                $resultado = $this->ocompra_model->rpt_control_compras($fInicio,$fFin);
                $registros = count($resultado);
                foreach($resultado as $indice=>$value){
                    $codpro     = $value->codpro;
                    $serie_req  = $value->serie_requerimiento;
                    $numero_req = $value->numero_requerimiento;
                    $fecha_req  = $value->fecha_requerimiento;
                    $serie_sc   = $value->serie_sc;
                    $numero_sc  = $value->numero_sc;
                    $fecha_sc   = $value->fecha_sc;
                    $serie_oc   = $value->serie_oc;
                    $numero_oc  = $value->numero_oc;
                    $fecha_oc   = $value->fecha_oc;
                    $fecha_apro = $value->fecha_aproboc;
                    $fecha_reg  = $value->fregistro_oc;
                    $RucCli     = isset($value->RucCli)?$value->RucCli:"&nbsp;";
                    $productos = $this->producto_model->obtener($codpro);
                    $despro    = !isset($productos->DesPro)?'':$productos->DesPro;
                    /*Obtener cliente*/
                    $filter     = new stdClass();
                    $filter_not =  new stdClass();
                    $filter->ruccliente = trim($RucCli);
                    $filter->tipcliente = '03';
                    $oCliente  = $this->cliente_model->obtener($filter,$filter_not);
                    $razcli    = "&nbsp;";
                    if(isset($oCliente->RazCli))  $razcli    = $oCliente->RazCli;
                    //Listar NEAs
                    $neas      = $this->ningreso_model->listar_detalle2($serie_oc,$numero_oc,$codpro);
                    if(count($neas)>0){
                        foreach($neas as $indice2=>$value2){
                            $serie_nea  = $value2->SerieDoc;
                            $numero_nea = $value2->NroDoc;
                            $fecha_nea  = $value2->Fec_Doc;
                            $fecha_guia = $value2->FecMov;
                            $fecha_reg  = $value2->Fec_Reg;
                            $fila.="<tr>";
                            $fila.="<td>".$serie_req."</td>";
                            $fila.="<td>".$numero_req."</td>";
                            $fila.="<td>".$fecha_req."</td>";
                            $fila.="<td>".$serie_sc."</td>";
                            $fila.="<td>".$numero_sc."</td>";
                            $fila.="<td>".$fecha_sc."</td>";
                            $fila.="<td>".$serie_oc."</td>";
                            $fila.="<td>".$numero_oc."</td>";
                            $fila.="<td>".$fecha_oc."</td>";
                            $fila.="<td>".$fecha_apro."</td>";
                            $fila.="<td>".$fecha_reg."</td>";
                            $fila.="<td>".$serie_nea."</td>";
                            $fila.="<td>".$numero_nea."</td>";
                            $fila.="<td>".$fecha_nea."</td>";
                            $fila.="<td>".$fecha_guia."</td>";
                            $fila.="<td>".$fecha_reg."</td>";
                            $fila.="<td>".$codpro."</td>";
                            $fila.="<td align='left'>".$despro."</td>";
                            $fila.="<td>".$RucCli."</td>";
                            $fila.="<td>".$razcli."</td>";
                            $fila.="</tr>";                        
                        }
                    }
                    else{
                        $fila.="<tr>";
                        $fila.="<td>".$serie_req."</td>";
                        $fila.="<td>".$numero_req."</td>";
                        $fila.="<td>".$fecha_req."</td>";
                        $fila.="<td>".$serie_sc."</td>";
                        $fila.="<td>".$numero_sc."</td>";
                        $fila.="<td>".$fecha_sc."</td>";
                        $fila.="<td>".$serie_oc."</td>";
                        $fila.="<td>".$numero_oc."</td>";
                        $fila.="<td>".$fecha_oc."</td>";
                        $fila.="<td>".$fecha_apro."</td>";
                        $fila.="<td>".$fecha_reg."</td>";
                        $fila.="<td>&nbsp;</td>";
                        $fila.="<td>&nbsp;</td>";
                        $fila.="<td>&nbsp;</td>";
                        $fila.="<td>&nbsp;</td>";
                        $fila.="<td>&nbsp;</td>";
                        $fila.="<td>&nbsp;</td>";
                        $fila.="<td>&nbsp;</td>";
                        $fila.="</tr>";     
                    }

                }   
                foreach($resultado as $indice=>$value){
                    $fila2.="<tr>";
                    $fila2.="<th>".$value->fecha_aproboc."</th>";                        
                    $fila2.="<td align='left'>".$codpro."</td>";
                    $fila2.="</tr>";
                }                          
            }
            elseif($tipo=="excel"){
              $xls = new Spreadsheet_Excel_Writer();
              $xls->send("Compras.xls");
              $sheet  =$xls->addWorksheet('HojaCompras');
              //$sheet->setColumn(3,3,575);
              $sheet->setColumn(0,0,16); //COLUMNA A1
              $sheet->setColumn(1,1,16); //COLUMNA B2
              $sheet->setColumn(2,2,16); //COLUMNA C3
              $sheet->setColumn(3,3,22); //COLUMNA D4
              $sheet->setColumn(4,4,22); //COLUMNA E5
              $sheet->setColumn(5,5,22); //COLUMNA F6
              $sheet->setColumn(6,6,22); //COLUMNA G7
              $sheet->setColumn(7,7,22); //COLUMNA H8
              $sheet->setColumn(8,8,22); //COLUMNA I9
              $sheet->setColumn(9,9,22); //COLUMNA J10
              $sheet->setColumn(10,10,22); //COLUMNA K11
              $sheet->setColumn(11,11,22); //COLUMNA L12
              $sheet->setColumn(12,12,22); //COLUMNA M13
              $sheet->setColumn(13,13,22); //COLUMNA N14
              $sheet->setColumn(14,14,22); //COLUMNA O15
              $sheet->setColumn(15,15,22); //COLUMNA P16
              $sheet->setColumn(16,16,22); //COLUMNA Q17
              $sheet->setColumn(17,17,60); //COLUMNA R18
              $sheet->setColumn(18,18,15); //COLUMNA R18
              $sheet->setColumn(19,19,30); //COLUMNA R18
              $sheet->setRow(0, 51);
              $sheet->setRow(1,42);
              $format_bold=$xls->addFormat();
              $format_bold->setBold();
              $format_bold->setvAlign('vcenter');
              $format_bold->sethAlign('center');
              $format_bold->setBorder(1);
              $format_bold->setTextWrap();
              $format_titulo=$xls->addFormat();
              $format_titulo->setBold();
              $format_titulo->setSize(21);
              $format_titulo->setvAlign('vcenter');
              $format_titulo->sethAlign('center');
              $format_titulo->setBorder(1);
              $format_titulo->setTextWrap();
              $format_bold3=$xls->addFormat(array(
                                              'Size' => 15,
                                              'Color' => 'black',
                                              'FgColor' => 'white',
                                              'BorderColor' => 'black',
                                              'Bold',
                                              'vAlign'  => 'vcenter',
                                              'hAlign'  => 'center',
                                              'Border'  => 2,
                                              'TextWrap'
                                              ));
              $sheet->mergeCells(0,0,0,17);  
              $sheet->write(0,3,"REPORTE",$format_titulo); $sheet->write(0,4,"DE",$format_titulo);  $sheet->write(0,5,"CONTROL",$format_titulo);  $sheet->write(0,6,"DE",$format_titulo);  $sheet->write(0,7,"COMPRAS",$format_titulo);
              //$sheet->write(0,1,"",$format_bold);$sheet->write(0,2,"",$format_bold);$sheet->write(0,3,"",$format_bold);$sheet->write(0,4,"",$format_bold);$sheet->write(0,5,"SSSSS",$format_bold);
              $sheet->write(0,0,"REPORTE DEL $fInicio AL $fFin",$format_bold); 
              
              $sheet->write(1,0,"SERIE REQUERIMIENTO",$format_bold);  $sheet->write(1,1,"NUMERO REQUERIMIENTO",$format_bold);  $sheet->write(1,2,"FECHA REQUERIMIENTO",$format_bold);  $sheet->write(1,3,"SERIE SOLICITUD COMPRA",$format_bold);   $sheet->write(1,4,"NUMERO SOLICITUD COMPRA",$format_bold);      $sheet->write(1,5,"FECHA SOLICITUD COMPRA",$format_bold);   $sheet->write(1,6,"SERIE ORDEN COMPRA",$format_bold);    $sheet->write(1,7,"NUMERO ORDEN COMPRA",$format_bold);      $sheet->write(1,8,"FECHA ORDEN COMPRA",$format_bold);      $sheet->write(1,9,"FECHA APROBACION",$format_bold);     $sheet->write(1,10,"FECHA REGISTRO ORDEN COMPRA",$format_bold);       $sheet->write(1,11,"SERIE NOTA ENTRADA",$format_bold);    $sheet->write(1,12,"NUMERO NOTA ENTRADA",$format_bold);     $sheet->write(1,13,"FECHA NOTA ENTRADA",$format_bold);     $sheet->write(1,14,"FECHA GUIA CLIENTE",$format_bold);   $sheet->write(1,15,"FECHA REGISTRO NOTA ENTRADA",$format_bold);       $sheet->write(1,16,"CODIGO PRODUCTO",$format_bold);      $sheet->write(1,17,"PRODUCTO",$format_bold);        $sheet->write(1,18,"CLIENTE RUC",$format_bold);     $sheet->write(1,19,"CLIENTE RAZON SOCIAL",$format_bold);    

            
             // $sheet->write(2,0,"$fInicio",$format_bold); 
              
              
                    $i=2;
                    
                    
                $resultado3 = $this->ocompra_model->rpt_control_compras($fInicio,$fFin);
                foreach($resultado3 as $indice3=>$value3){
                    $codpro     = $value3->codpro;
                    $serie_req  = $value3->serie_requerimiento;
                    $numero_req = $value3->numero_requerimiento;
                    $fecha_req  = $value3->fecha_requerimiento;
                    $serie_sc   = $value3->serie_sc;
                    $numero_sc  = $value3->numero_sc;
                    $fecha_sc   = $value3->fecha_sc;
                    $serie_oc   = $value3->serie_oc;
                    $numero_oc  = $value3->numero_oc;
                    $fecha_oc   = $value3->fecha_oc;
                    $fecha_apro = $value3->fecha_aproboc;
                    $fecha_reg  = $value3->fregistro_oc;
                    $RucCli     = $value3->RucCli;
                    $productos = $this->producto_model->obtener($codpro);
                    $despro    = !isset($productos->DesPro)?'':$productos->DesPro;
                    /*Obtener cliente*/
                    $filter     = new stdClass();
                    $filter_not =  new stdClass();
                    $filter->ruccliente = $RucCli;
                    $filter->tipcliente = '03';
                    $oCliente  = $this->cliente_model->obtener($filter,$filter_not);
                    $razcli    = "";
                    if(isset($oCliente->RazCli))  $razcli    = $oCliente->RazCli;
                    //Listar NEAs
                    $neas      = $this->ningreso_model->listar_detalle2($serie_oc,$numero_oc,$codpro);
                    if(count($neas)>0){
                        foreach($neas as $indice2=>$value2){
                            $serie_nea  = $value2->SerieDoc;
                            $numero_nea = $value2->NroDoc;
                            $fecha_nea  = $value2->Fec_Doc;
                            $fecha_guia = $value2->FecMov;
                            $fecha_reg  = $value2->Fec_Reg;
                            
                                   $sheet->write($i,0,$serie_req,$format_bold);
                                   $sheet->write($i,1,$numero_req,$format_bold);
                                   $sheet->write($i,2,$fecha_req,$format_bold);
                                   $sheet->write($i,3,$serie_sc,$format_bold);
                                   $sheet->write($i,4,$numero_sc,$format_bold);
                                   $sheet->write($i,5,$fecha_sc,$format_bold);
                                   $sheet->write($i,6,$serie_oc,$format_bold);
                                   $sheet->write($i,7,$numero_oc,$format_bold);
                                   $sheet->write($i,8,$fecha_oc,$format_bold);
                                   $sheet->write($i,9,$fecha_apro,$format_bold);
                                   $sheet->write($i,10,$fecha_reg,$format_bold);
                                   $sheet->write($i,11,$serie_nea,$format_bold);
                                   $sheet->write($i,12,$numero_nea,$format_bold);
                                   $sheet->write($i,13,$fecha_nea,$format_bold);
                                   $sheet->write($i,14,$fecha_guia,$format_bold);
                                   $sheet->write($i,15,$fecha_reg,$format_bold);
                                   $sheet->write($i,16,$codpro,$format_bold);
                                   $sheet->write($i,17,$despro,$format_bold);
                                   $sheet->write($i,18,$RucCli,$format_bold);
                                   $sheet->write($i,19,$razcli,$format_bold);
          
                                   $i++;
                        }
                    }
                    else{
                        
                                   $sheet->write($i,0,$serie_req,$format_bold);
                                   $sheet->write($i,1,$numero_req,$format_bold);
                                   $sheet->write($i,2,$fecha_req,$format_bold);
                                   $sheet->write($i,3,$serie_sc,$format_bold);
                                   $sheet->write($i,4,$numero_sc,$format_bold);
                                   $sheet->write($i,5,$fecha_sc,$format_bold);
                                   $sheet->write($i,6,$serie_oc,$format_bold);
                                   $sheet->write($i,7,$numero_oc,$format_bold);
                                   $sheet->write($i,8,$fecha_oc,$format_bold);
                                   $sheet->write($i,9,$fecha_apro,$format_bold);
                                   $sheet->write($i,10,$fecha_reg,$format_bold);
                                   $sheet->write($i,11,"",$format_bold);
                                   $sheet->write($i,12,"",$format_bold);
                                   $sheet->write($i,13,"",$format_bold);
                                   $sheet->write($i,14,"",$format_bold);
                                   $sheet->write($i,15,"",$format_bold);
                                   $sheet->write($i,16,"",$format_bold);
                                   $sheet->write($i,17,"",$format_bold);
    
                                   $i++;
                    }

                    
                    
                    
                }  

                                  $xls->close();
                                }        
                    elseif($tipo=="pdf"){
                    $this->load->library("fpdf/pdf");
          
                   // $CI = new FPDF('L','mm','A4');
                    $CI = & get_instance();

                    $CI->pdf->FPDF('L','mm','A3');
                            
                    $CI->pdf->AliasNbPages();
                    $CI->pdf->AddPage();
                    $CI->pdf->SetTextColor(0,0,0);
                    $CI->pdf->SetFillColor(255,255,255);
                    
                    
                    $CI->pdf->SetFont('Arial','B',13);
                    $CI->pdf->SetY(5);
                    $CI->pdf->SetX(5); 
                    $CI->pdf->Cell(410,20,"REPORTE DE CONTROL DE COMPRAS",1,1,"C",0);
                    $CI->pdf->SetX(5); 
                    $CI->pdf->Cell(410,8,"REPORTE DEL $fInicio AL $fFin",1,1,"L",0);
                    
                  
                    
                    $CI->pdf->SetFont('Arial','',6);
                    $CI->pdf->SetY(35);
                    $CI->pdf->SetX(5); 
                    $CI->pdf->Cell(15,3,"Serie Req.",1,0,"C",0);
                    $CI->pdf->Cell(13,3,"Num Req",1,0,"C",0);
                    $CI->pdf->Cell(20,3,"FechaReq",1,0,"C",0);
                    $CI->pdf->Cell(15,3,"CodSolComp",1,0,"C",0);
                    $CI->pdf->Cell(15,3,"NSolComp",1,0,"C",0);
                    $CI->pdf->Cell(20,3,"FecSolComp",1,0,"C",0);
                    $CI->pdf->Cell(15,3,"CodOrdComp",1,0,"C",0);
                    $CI->pdf->Cell(14,3,"NOrdComp",1,0,"C",0); //
                    $CI->pdf->Cell(18,3,"FecOrdCom",1,0,"C",0);
                    $CI->pdf->Cell(18,3,"FecAprobacion",1,0,"C",0);
                    $CI->pdf->Cell(18,3,"FecRgOrdComp",1,0,"C",0);
                    $CI->pdf->Cell(15,3,"SerieNotaEnt",1,0,"C",0);
                    $CI->pdf->Cell(15,3,"NumNotaEnt",1,0,"C",0);
                    $CI->pdf->Cell(20,3,"FecNota Ent",1,0,"C",0);
                    $CI->pdf->Cell(25,3,"FecGuiaClie",1,0,"C",0);
                    $CI->pdf->Cell(25,3,"FecRegNEntr",1,0,"C",0);
                    $CI->pdf->Cell(25,3,"Cod Producto",1,0,"C",0);
                    $CI->pdf->Cell(103,3,"Producto",1,0,"C",0);

                    $CI->pdf->Ln(2);$CI->pdf->Ln(2);
                    
                    $j=1;
                    $resultado = $this->ocompra_model->rpt_control_compras($fInicio,$fFin);

                    foreach($resultado as $indice=>$value)
                    {

                                       $serie_requerimiento = $value->serie_requerimiento;
                                       $numero_requerimiento = $value->numero_requerimiento;
                                       $fecha_requerimiento = $value->fecha_requerimiento;
                                       $serie_sc = $value->serie_sc;
                                       $numero_sc = $value->numero_sc;
                                       $fecha_sc = $value->fecha_sc;
                                       $serie_oc = $value->serie_oc;       
                                       $numero_oc = $value->numero_oc;
                                       $fecha_oc = $value->fecha_oc;
                                       $fecha_aproboc = $value->fecha_aproboc;
                                       $fregistro_oc = $value->fregistro_oc;
                                       $serie_ne = $value->serie_ne;
                                       $numero_ne = $value->numero_ne;
                                       $fecha_ne = $value->fecha_ne;
                                       $fecha_guiacli = $value->fecha_guiacli;
                                       $fregistro_ne = $value->fregistro_ne;
                                       $codpro = $value->codpro;
                                       $producto = $value->producto;
                                       
                                        $CI->pdf->SetFont('Arial','',7);
                                        $CI->pdf->SetX(5);
                                        $CI->pdf->Cell(15,3,$serie_requerimiento,1,0,"C",0);
                                        $CI->pdf->Cell(13,3,$numero_requerimiento,1,0,"C",0);
                                        $CI->pdf->Cell(20,3,$fecha_requerimiento,1,0,"C",0);
                                        $CI->pdf->Cell(15,3,$serie_sc,1,0,"C",0);
                                        $CI->pdf->Cell(15,3,$numero_sc,1,0,"C",0);
                                        $CI->pdf->Cell(20,3,$fecha_sc,1,0,"C",0);
                                        $CI->pdf->Cell(15,3,$serie_oc,1,0,"C",0);
                                        $CI->pdf->Cell(14,3,$numero_oc,1,0,"C",0);
                                        $CI->pdf->Cell(18,3,$fecha_oc,1,0,"C",0);
                                        $CI->pdf->Cell(18,3,$fecha_aproboc,1,0,"C",0);
                                        $CI->pdf->Cell(18,3,$fregistro_oc,1,0,"C",0);                        
                                        $CI->pdf->Cell(15,3,$serie_ne,1,0,"C",0);
                                        $CI->pdf->Cell(15,3,$numero_ne,1,0,"C",0); 
                                        $CI->pdf->Cell(20,3,$fecha_ne,1,0,"C",0);
                                        $CI->pdf->Cell(25,3,$fecha_guiacli,1,0,"C",0);                        
                                        $CI->pdf->Cell(25,3,$fregistro_ne,1,0,"C",0);
                                        $CI->pdf->Cell(25,3,$codpro,1,0,"C",0);                        
                                        $CI->pdf->Cell(103,3,$producto,1,1,"J",0);                                
                                   

                    $j++;

                    }                  
                    $CI->pdf->Output();
                }                                
                                                        
  
        }
        $data['fFin_ini']    = $fFin;
        $data['fInicio_ini'] = $fInicio;  
        $data['fila']        = $fila;
        $data['fila2']        = $fila2;
        $data['registros']        = $registros;
        $this->load->view(compras."rpt_control_compras",$data);
    }
    
    
    
    
    
    
}