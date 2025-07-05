<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Requiser extends CI_Controller {
    var $entidad;
    var $login;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");  
        $this->load->model(almacen.'producto_model');
         $this->load->model(almacen.'servicio_model');
        $this->load->model(ventas.'ot_model');
        $this->load->model(compras.'proveedor_model');
        $this->load->model(compras.'facturac_model');
        $this->load->model(compras.'requiser_model');
        $this->load->model(personal.'responsable_model');
        $this->load->model(maestros.'tipo_dcto_model');
        $this->load->model(maestros.'formapago_model');
        $this->load->model(maestros.'ubigeo_model');
        $this->load->model(finanzas.'voucher_model');
        $this->dbase   = $this->load->database('dsn',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');
    }
    /* esta correcto */
    public function index(){
        
    }
    
    public function listar(){
        $this->load->view(ventas."ot_listar");
    }
    
    public function listar_x_ot(){
        $codot      = $this->input->get_post('codot');
        $ot         = $this->input->get_post('ot');
        $tipoexport = $this->input->get_post('tipoexport');
        $monedadoc  = $this->input->get_post('monedadoc');
        $fecha_ini  = $this->input->get_post('fecha_ini');
        $fecha_fin  = $this->input->get_post('fecha_fin');
        $cadenaot   = $this->input->get_post('cadenaot');
        $codprov    = $this->input->get_post('codprov');
        $codtiposer = $this->input->get_post('codtiposer');
        $codres     = $this->input->get_post('codres');
        $opcion     = $this->input->get_post('opcion');
        $arr_export_detalle = array();
        if($fecha_ini=="")    $fecha_ini  = date("01/m/Y",time());
        if($fecha_fin=="")    $fecha_fin  = date("d/m/Y",time());        
        if($monedadoc=="")    $monedadoc    = "2";
        $filter            = new stdClass();
        $filter->estado    = 2;
        $filter->situacion = 2;
        $filter2           = new stdClass();
        $filter2->order_by = array("ltrim(RazCli)"=>"asc");
        $filter3           = new stdClass();
        $filter3->order_by = array("ltrim(DesPro)"=>"asc");
        $filtrosolicitante = form_dropdown("codres",$this->responsable_model->seleccionar($filter,new stdClass(),array('nomper'=>'asc'),":::TODOS:::","000000"),$codres,"id='codres' class='comboGrande' onClick='limpiarText();' ");
        $filtroproveedor   = form_dropdown("codprov",$this->proveedor_model->seleccionar($filter2),$codprov,"id='codprov' class='comboMedio' onClick='limpiarText();' ");
        $filtrotiposer     = form_dropdown("codtiposer",$this->servicio_model->seleccionar($filter3),$codtiposer,"id='codtiposer' class='comboMedio' onClick='limpiarText();' onchange=\"$('#frmBusqueda').attr('target','_self');$('#frmBusqueda').attr('action','');$('#tipoexport').val('');$('#numero').val('');\"");
        $filtromoneda      = form_dropdown('monedadoc',array(""=>"::Seleccione:::","2"=>"SOLES","3"=>"DOLARES"),$monedadoc," size='1' id='monedadoc' class='comboPequeno' onchange=\"$('#frmBusqueda').attr('target','_self');$('#frmBusqueda').attr('action','');$('#tipoexport').val('');$('#numero').val('');\" ");               
        $fila   = "";
        $total_servicios       = 0;
        $total_servicios_dolar = 0;
        $t_peso                = 0;
        $peso                  = 0;
        $registros             = 0;
        if($opcion=="C"){
            $filter         = new stdClass();
            $filter_not     = new stdClass();
            $filter->fechai = $fecha_ini;
            $filter->fechaf = $fecha_fin;  
            if($codtiposer!="")   $filter->codservicio = $codtiposer;
            if($codprov!="")      $filter->ruc    = $codprov;
            if($codot!="")        $filter->codot  = $codot;
            if($codres!="000000") $filter->codres = $codres;
            $order_by       = array('r.Fecemi desc','r.Gcodser desc');
            $oServicio      = $this->requiser_model->listarg($filter,$filter_not,$order_by);
            $registros      = count($oServicio);
            if($registros>0){
                foreach($oServicio as $indice => $value){
                    $arr_data = array();
                    $codigo    = $value->codot;
                    $tipser2   = $value->tipser;
                    $total     = $value->costo;
                    $codser    = $value->gcodser;
                    $codsoli   = $value->gpersonal;
                    $fentrega  = $value->gfentrega;
                    $peso      = $value->gpeso;
                    $ruc       = ($value->gruc==0)?"":$value->gruc;
                    $moneda    = $value->moneda; //ok
                    $tipdoc    = $value->tipod;
                    $seriedoc  = $value->seried;
                    $nrodoc    = $value->nrod;
                    $tc        = $value->cambio;
                    $ser_guia  = $value->gserguia;  //ok
                    $num_guia  = $value->gnumguia; //ok
                    $estado    = $value->gestado;
                    $observ    = $value->gobserva;
                    $fecemi    = $value->fecemi;  //ok
                    $frealiza  = $value->fdespacho;
                    $subtotal_a= $value->subtotal;
                    $igv_a     = $value->igv;
                    $total_a   = $value->costo;
                     
                    
                    if($moneda=='2'||$moneda=='S'){
                        $subtotal   = $subtotal_a;
                        $igv        = $igv_a;
                        $total      = $total_a;
                    } else {
                            $subtotal   =($subtotal_a*$tc);
                            $igv        =($igv_a*$tc);
                            $total      =($total_a*$tc);
                     }
                   
                    if($moneda=='3'||$moneda=='D'){
                        $subtotal_dolar = $subtotal_a;
                        $igv_dolar      = $igv_a;
                        $total_dolar    = $total_a; 
                    } else{
                            $subtotal_dolar = ($subtotal_a/$tc);
                            $igv_dolar      = ($igv_a/$tc);
                            $total_dolar    = ($total_a/$tc); 
                     }
                   
                    
                    
                    $total_servicios = $total_servicios + $total;
                    $t_peso         = $t_peso + $peso;
                    $total_servicios_dolar = $total_servicios_dolar + $subtotal_dolar;
                    
                   
                    
                    //Descripcion del Servicio
                    $descripcion = "";  
                    if($codser!=''){
                        $filter3     = new stdClass();
                        $filter3_not = new stdClass();
                        $filter3->codservicio = $codser;
                        $objServicio   = $this->servicio_model->obtener($filter3,$filter3_not);
                        $descripcion   = $objServicio->DesPro;
                    }
                    //Nombre de ot
                    $filter7        = new stdClass();
                    $filter7_not    = new stdClass();
                    $filter7->codot = trim($codigo); 
                    $oOt2           = $this->ot_model->obtenerg($filter7,$filter7_not);
                    $nroOt2         = $oOt2->NroOt;

                    $nombretorre = "";                     
                if(substr($nroOt2,0,2)!='CC' && substr($nroOt2,0,2)!='PT' && substr($nroOt2,0,2)!='' )  {
                        $filtertorre = new stdClass();
                        $filtertorre->torre = $oOt2->Torre;
                    /*    echo $oOt2->Torre . $oOt2->NroOt ."<br>" ;*/
                    
                        $torre = $this->ot_model->obtenerTorre($filtertorre);                    
                        $nombretorre = $torre->Des_Larga;                        
                    }
                            
                    /*Nombre del proveedor*/
                    if($ruc!=''){
                    $filter4     = new stdClass();
                    $filter4_not = new stdClass();
                    $filter4->ruccliente = $ruc;
                    $oProveedor  = $this->proveedor_model->obtener($filter4,$filter4_not);
                    $razcli      = $oProveedor->RazCli;}
                    else{ $razcli=''; }
                    /*Nombre del solicitante*/
                    $filter5     = new stdClass();
                    $filter5_not = new stdClass();
                    $filter5->codresponsable = $codsoli;
                    $filter5->estado    = 2;
                    $filter5->situacion = 2;
                    $solicitante    = $this->responsable_model->obtener($filter5,$filter5_not);  
                    $nomsolicitante = !isset($solicitante->nomper)?'':$solicitante->nomper;                    
                    $fila      .= "<tr>";
                    $fila      .= "<td align='center'><div id='".trim($codigo)."' style='width:60px;'>".$nroOt2."</div></td>";/*id2='".trim($ser_guia)."'*/
                    $arr_data[] = $nroOt2;
                    $fila      .= "<td align='center'><div id='".trim($num_guia)."' id2='".trim($codot)."'  style='width:80px;height:auto;'><a href='#' onclick='ver_requis_ser(this);'>".$ser_guia."-".$num_guia."</a></div></td>";
                    $arr_data[] = $ser_guia."-".$num_guia;                    
                    $fila       .= "<td align='left'><div style='width:80px;'>".$nomsolicitante."</a></td>";                    
                    $arr_data[] = utf8_encode($nomsolicitante);
                    
                     $fila     .= "<td align='left'><div style='width:120px;'>".$codser."</a></td>";
                    $arr_data[] = utf8_encode($codser);
                    
                    $fila     .= "<td align='left'><div style='width:120px;'>".$descripcion."</a></td>";
                    $arr_data[] = utf8_encode($descripcion);
                    
                    
                    
                    $fila     .= "<td align='left'><div title='".$ruc."' style='width:100px;'>".$razcli." ".$ruc."</a></td>";
                    $arr_data[]= utf8_encode($ruc);
                    $arr_data[] = utf8_encode($razcli);
                    $fila     .= "<td align='center'><div style='width:70px;'>".($estado==1?"SI":"NO")."</a></td>";
                    $arr_data[] = ($estado==1?"SI":"NO");
                    if($monedadoc=='2'){
                        $fila .= "<td align='right'><div style='width:80px;'>".number_format($total,2)."</a></td>";  
                        $arr_data[] = $total;
                    }
                    elseif($monedadoc=='3'){
                        $fila .= "<td align='right'><div style='width:80px;'>".number_format($total_dolar,2)."</a></td>"; 
                        $arr_data[] = $total_dolar;
                    }
                    $fila       .= "<td align='right'><div style='width:80px;'>".number_format($peso,2)."</a></td>";  
                    $arr_data[] = $peso;
                    $fila     .= "<td align='center'><div style='width:80px;'>".date_sql($fecemi)."</a></td>";
                    $arr_data[] = date_sql($fecemi);
                    
                    if(date_sql($frealiza)=='01 00:00:00/01/1900'||date_sql($frealiza)=='30/12/1899'){$fecha_realiza='';}
                    else{$fecha_realiza=date_sql($frealiza);}
                    
                   // $fila     .= "<td align='center'><div style='width:80px;'>".(date_sql($frealiza)=='01 00:00:00/01/1900'?'':date_sql($frealiza))."</div></td>";
                   // $arr_data[] = (date_sql($frealiza)=='01 00:00:00/01/1900'?'':date_sql($frealiza));//01 00:00:00/01/1900
                    $fila     .= "<td align='center'><div style='width:80px;'>".$fecha_realiza."</div></td>";
                    $arr_data[] = $fecha_realiza;//01 00:00:00/01/1900
                    
                    
                    $fila     .= "<td align='center'><div style='width:80px;'>".date_sql($fentrega)."</a></td>";
                    $arr_data[] = (date_sql($fentrega)=='30/12/1899'?'':date_sql($fentrega));
                    $fila       .= "<td align='left'><div style='width:80px;'>".$observ."</a></td>";
                    $arr_data[] = utf8_encode($observ);

                    $fila     .= "<td align='center'><div style='width:100px;'>".$nombretorre."</a></td>";
                    $arr_data[] = utf8_encode($nombretorre);

                    
                    $fila       .= "</tr>";
                    array_push($arr_export_detalle,$arr_data);
                }
                $fila       .= "<tr>";
                if($monedadoc=='2'){
                    $fila       .= "<td colspan='8' align='right'>".number_format($total_servicios,2)."</td>";
                }elseif($monedadoc=='3'){
                    $fila       .= "<td colspan='8' align='right'>".number_format($total_servicios_dolar,2)."</td>";
                }
                $fila       .= "<td align='right'><div>".number_format($t_peso,2)."</div></td>";
                $fila       .= "</tr>";
            }
            $var_export = array('rows' => $arr_export_detalle);
            $this->session->set_userdata('data_listar_requiser_x_ot', $var_export);
        }

        $data['codot']  = $codot;
        $data['ot']     = $ot;
        $data['fila']   = $fila;
        $data['monedadoc']   = $monedadoc;
        $data['registros']   = $registros;
        $data['tipoexport']  = "";
        $data['fecha_ini']   = $fecha_ini;
        $data['fecha_fin']   = $fecha_fin;
        $data['filtrosolicitante'] = $filtrosolicitante;
        $data['filtroproveedor']   = $filtroproveedor;
        $data['filtrotiposer']     = $filtrotiposer;        
        $data['filtromoneda']      = $filtromoneda;  
        $this->load->view(compras."requiser_listar_ot",$data);
    }
    
    public function obtener(){
        
        $this->load->view(ventas."ot_listar");
    }    
    
    public function ver(){
       $hora_Actual=date("H:i:s");
       /* $serie          = $this->input->get_post('serie');*/
        $numero         = $this->input->get_post('numero');
        $coot         = $this->input->get_post('codot');
        $filter         = new stdClass();
        $filter_not     = new stdClass();
        /*$filter->serie  = $serie;*/
        $filter->numero = $numero;
        $facturas       = $this->facturac_model->obtener_rs($filter,$filter_not);
     /*   if(isset($facturas->NroDoc)){*/
            $gserguia    = $facturas->gserguia;
            $gnumguia     = $facturas->gnumguia;
            $fecemi     = $facturas->fecemi;
            $got = $facturas->got;$nlote   = $facturas->nlote;
          
            $nrod        = $facturas->nrod;
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
            $dot     = $facturas->got;
            $filter2     = new stdClass();
            $filter2_not = new stdClass();
            //$filter2->numero = $dot;
            $filter2->codot = $coot;
            //print_r($filter2);
            $oOt         = $this->ot_model->obtenerg($filter2,$filter2_not);
             $nroOt       = $oOt->NroOt;
             $dirOt       = $oOt->DirOt;
      /*       $nrod        = $facturas->tipod;echo $nrod;*/
            $numd        = $this->tipo_dcto_model->obtener($tipod);
            $docu        = $numd->docdescri;
            $tp            = $this->formapago_model->obtener($fpago);
            $fpdes         = $tp->Des_Larga;    
            $gruc   = $facturas->gruc;
             if($gruc==0)
             $gruc       ='00000000000';
            $filter         = new stdClass();
            $filter_not     = new stdClass();
            $filter->ruccliente = $gruc;
            $pro            = $this->proveedor_model->obtener($filter,$filter_not);
            $razonsocial           = $pro->RazCli;  
            $gcontacto        = $facturas->gcontacto;  
            $filter         = new stdClass();
            $filter_not     = new stdClass();
            $filter->ruccliente = $gcontacto;
            $pro            = $this->proveedor_model->obtener($filter,$filter_not);
            $contactox           = isset($pro->RazCli)?$pro->RazCli:"";  
            $gcodser = $facturas->gcodser;
            $filter         = new stdClass();
            $filter_not     = new stdClass();
            $filter->codservicio = $gcodser;    
            $service   = $this->servicio_model->obtener($filter,$filter_not);
            /*print_r($service);*/
            $servdetalle           = $service->DesPro; 
            $gdestino   = $facturas->gdestino;
            $filter         = new stdClass();
            $filter_not     = new stdClass();
            $filter->ubica = $gdestino;    
            $ubic   = $this->ubigeo_model->obtener_ubigeo1($filter,$filter_not);
            $unionx           = $ubic->union; 
       $gpersonal  = $facturas->gpersonal;
        $filter->codresponsable = $gpersonal; 
        $responsable   = $this->responsable_model->obtener($filter,$filter_not);
              $sol     = $responsable->nomper;
            $moneda      = $mo=='S'?"NUEVOS SOLES":"DOLARES AMERICANOS";
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
            $CI->pdf->Cell(120,8, "No SERVICIO:  ".$gserguia."-".$gnumguia,0,0,"R",0);
            $CI->pdf->SetFont('Arial','B',7);
            $CI->pdf->Cell(50,8,"Hora:".$hora_Actual,0,1,"R",0);
           /* $CI->pdf->Cell(120,5, "" ,0,1,"L",0);
            $CI->pdf->Cell(60,5, "NRO OT : ".$nroOt ,0,0,"L",0);
            $CI->pdf->Cell(60,5, "REQ.SERVICIO : ".$seroc."-".$nrooc ,0,0,"L",0);*/
            $CI->pdf->Cell(60,15,  "",0,1,"L",0);
            $CI->pdf->Cell( 25,5,"FECHA",0,0,"L",0);
            $CI->pdf->Cell( 25,5,":   ".date_sql($fecemi),0,1,"L",0);
           /* $CI->pdf->Cell(60,5, "TIPO DOC. REF. : ".$tipdocref ,0,0,"L",0);
            $CI->pdf->Cell(60,5,  "NRO DOC. REF. :".$serieref."-".$numeroref ,0,0,"L",0);
            $CI->pdf->Cell(60,5, "MONEDA : ".$moneda ,0,1,"L",0);
            $CI->pdf->Cell(120,5, "PROVEEDOR : ".$ruccli." ".$razcli ,0,0,"L",1);
            $CI->pdf->Cell(120,5, "FEHCA VENC. : ".$fecvcto ,0,1,"L",0);
            $CI->pdf->SetTextColor(255,255,255);
            $CI->pdf->SetFillColor(192,192,192);*/
            /*Detalle*/
            $CI->pdf->Cell( 25,5,"SOLICITANTE" ,0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$gpersonal." -    ". $sol. "      No Lote:  ". $nlote,0,1,"L",0);
             
           /* $CI->pdf->SetFillColor(0,0,128);*/
            $CI->pdf->Cell(  25,5,"OT/PT/CC",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$got. " -    ". $dirOt,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"T. SERVICIO",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$servdetalle,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"PTO. LLEGADA",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$unionx,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"CONTACTO",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$gcontacto."   -    ".$contactox,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"F. ENTREGA",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".date_sql($gfentrega)."                  PESO:  ".$gpeso."                   HORA ENTREGA:  ".$ghora,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"PROVEEDOR",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$gruc."   -    ".$razonsocial,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"T. DCTO",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$tipod. " -    ".$docu."          SERIE / NRO. DCTO  :  ".$seried." - ".$nrod,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"F. REALIZADO",0,0,"L",0);
            $CI->pdf->Cell( 25,5,($fdespacho!=''?":  ".date_sql($fdespacho):'//'),0,1,"L",0);
             
           
            $CI->pdf->Cell( 25,5,"FORMA PAGO",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$fpago. "   -    ".$fpdes,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"I.G.V.",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".Number_format($igv,2),0,0,"L",0);
             $CI->pdf->Cell( 20,5,"SUBTOTAL",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".Number_format($subtotal,2),0,0,"L",0);
             $CI->pdf->Cell( 15,5,"TOTAL",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".Number_format($subtotal+$igv,2),0,1,"L",0);

            $CI->pdf->Cell( 25,5,"DETRACCION",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".Number_format($gdetrac,2)."   %   ".Number_format($gp_detrac,2),0,1,"L",0);
            
            $CI->pdf->Cell( 25,5,"MONEDA",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$moneda."        TC.:  ".$cambio,0,1,"L",0);

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
              $CI->pdf->Cell( 0,5,"R E Q U I S I C I O N      D E     S E R V I C I O S",0,1,"C",0);
             $CI->pdf->Cell( 25,5,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"L",0);
             $CI->pdf->Cell( 10,5,"ITEM",1,0,"C",1);
             $CI->pdf->Cell( 10,5,"CANT.",1,0,"C",1);
             $CI->pdf->Cell( 20,5,"CODIGO",1,0,"C",1);
             $CI->pdf->Cell( 10,5,"UND",1,0,"C",1);
             $CI->pdf->Cell( 95,5,"DESCRIPCION",1,0,"C",1);
             $CI->pdf->Cell( 15,5,"OT",1,0,"C",1);
             $CI->pdf->Cell( 15,5,"P.UNIT.",1,0,"C",1);        
             $CI->pdf->Cell( 15,5,"P.VENT.",1,1,"C",1);
             $CI->pdf->Cell( 25,5,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"L",0);
             $CI->pdf->Cell( 25,5,"OBSERVACIONES",0,1,"L",0);
             $CI->pdf->Cell( 25,5,"$detalle",0,1,"L",0);
             $CI->pdf->Cell( 25,5,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"L",0);
             $CI->pdf->Cell(65,20,"",0,0,"C",0);
            $CI->pdf->Cell( 65,20,"",0,0,"C",0);
            $CI->pdf->Cell( 65,20,"",0,1,"C",0);
            $CI->pdf->Cell(92,20,"",0,0,"C",0);
            $CI->pdf->Cell( 65,20,"",0,0,"C",0);
            $CI->pdf->Cell( 65,20,"",0,1,"C",0);
            $CI->pdf->Cell(92,5, "------------------------------------------------------------",0,0,"C",0);
            $CI->pdf->Cell( 92,5,"------------------------------------------------------------",0,1,"C",0);
            $CI->pdf->Cell( 92,5,"REQUERIDO POR",0,0,"C",0);
            $CI->pdf->Cell( 92,5,"VoBo GERENTE Y/O VoBo LOGISTICA",0,1,"C",0);
            $CI->pdf->Cell(92,5,$sol,0,0,"C",0);
            $CI->pdf->Cell( 92,5,"JORGE VILLAVICENCIO Y/O MARTIN GALINDO",0,1,"C",0);
            $CI->pdf->Output();
        }
        
       public function export_excel($type){
            if($this->session->userdata('data_'.$type)){
                $result = $this->session->userdata('data_'.$type);
                switch ($type) {
                    case 'listar_requiser_x_ot':
                        $arr_export_detalle = array();
                        $arr_columns = array();  
                        $arr_columns[0]['STRING']  = 'Nro. OT';
                        $arr_columns[1]['STRING']  = 'Req. Serv';
                        $arr_columns[2]['STRING']  = 'Solicitante';
                        $arr_columns[3]['STRING'] = 'COD Serv';
                        $arr_columns[4]['STRING']  = 'Servicio';
                        $arr_columns[5]['STRING']  = 'Ruc Prov.';
                        $arr_columns[6]['STRING']  = 'Proveedor';
                        $arr_columns[7]['STRING']  = 'Realizado';
                        $arr_columns[8]['NUMERIC'] = 'Costo (S/.)';
                        $arr_columns[9]['NUMERIC'] = 'Peso (KG)';
                        $arr_columns[10]['DATE']    = 'Fec. Emision';
                        $arr_columns[11]['DATE']    = 'Fec. Serv. Realizado';
                        $arr_columns[12]['DATE']    = 'Fec. Entrega';
                        $arr_columns[13]['STRING']  = 'Observacion';
                        $this->reports_model->rpt_general('rpt_'.$type, 'Requisiciones de servicio POR OT', $arr_columns, $result["rows"]);
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
