<?php header("Content-type: text/html; charset=utf-8"); 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orden extends CI_Controller {
    public function __construct(){
        parent::__construct(); 
        $this->load->model(ventas.'orden_model');
        $this->load->model(ventas.'tipoorden_model');
        $this->load->model(maestros.'persona_model');        
        $this->load->model(maestros.'moneda_model');     
        $this->load->model(maestros.'ubigeo_model');
        $this->load->model(seguridad.'permiso_model');
        $this->load->model(seguridad.'usuario_model');         
        $this->load->model(almacen.'producto_model');   
        $this->configuracion = $this->config->item('conf_pagina');
    }

    public function index()
    {
        $this->load->view('seguridad/inicio');	
    }

    public function listar($j=0){
        $filter           = new stdClass();
        $filter->codigo   = 1; 
        $filter->rol      = 4; 
        $filter->order_by = array("p.MENU_Codigo"=>"asc");
        $menu       = $this->permiso_model->listar($filter);            
        $filter     = new stdClass();
        $filter_not = new stdClass(); 
        $registros = count($this->orden_model->listar($filter,$filter_not));
        $ordenes   = $this->orden_model->listar($filter,$filter_not,$this->configuracion['per_page'],$j);
        $item      = 1;
        $lista     = array();
        if(count($ordenes)>0){
            foreach($ordenes as $indice => $value){
                $lista[$indice]               = new stdClass();
                $lista[$indice]->codigo       = $value->ORDENP_Codigo;
                $lista[$indice]->rsocial      = $value->EMPRC_RazonSocial;
                $lista[$indice]->numero       = $value->ORDENC_Numero;
                $lista[$indice]->descripcion  = $value->ORDENC_Descripcion;
                $lista[$indice]->importe      = $value->ORDENC_Importe;
                $lista[$indice]->peso         = $value->ORDENC_Peso;
                $lista[$indice]->estado       = $value->ORDENC_FlagEstado;
                $lista[$indice]->fechareg     = $value->ORDENC_FechaRegistro;
                $lista[$indice]->fecha        = $value->ORDENC_Fecot;
                $lista[$indice]->fechaentrega = $value->ORDENC_Fteot;
            }
        }
        $configuracion = $this->configuracion;
        $configuracion['base_url']    = base_url()."index.php/ventas/orden/listar";
        $configuracion['total_rows']  = $registros;
        $this->pagination->initialize($configuracion);
        /*Enviamos los datos a la vista*/
        $arrCampos            = array("ORDENC_Numero"=>"O.T.","ORDENC_Descripcion"=>"Descripcion","CLIP_Codigo"=>"Razon Social","ORDENC_Fecot"=>"Fecha");
        $data['lista']        = $lista;
        $data['menu']         = $menu;
        $data['form_open']    = form_open('',array("name"=>"frmPersona","id"=>"frmPersona","onsubmit"=>"return valida_guiain();"));     
        $data['form_close']   = form_close();  
        $data['selcampos']    = form_dropdown('campos',$arrCampos,0,"id='campos' class='comboPequeno'");         
        $data['j']            = $j;
        $data['registros']    = $registros;
        $data['paginacion']   = $this->pagination->create_links();
        $this->load->view("ventas/orden_index",$data);
    }

    public function editar($accion='n',$codigo=""){
            $departamento      = "";
            $provincia         = "";
            $distrito          = "";         
       $filter           = new stdClass();
        $filter->codigo   = 1; 
        $filter->rol      = 4; 
        $filter->order_by = array("p.MENU_Codigo"=>"asc");
        $menu_padre = $this->permiso_model->listar($filter); 
        $lista = new stdClass();
        if($accion == "e"){
            $filter            = new stdClass();
            $filter->persona   = $codigo;
            $orden             = $this->orden_model->obtener($filter);
            $lista->paterno    = $orden->PERSC_ApellidoPaterno;  
            $lista->materno    = $orden->PERSC_ApellidoMaterno;  
            $lista->nombres    = $orden->PERSC_Nombre;  
            $lista->curso      = $orden->PROD_Codigo;  
            $lista->fecha      = $orden->ORDENC_Fecot;  
            $lista->alumno     = $orden->PERSP_Codigo; 
            $lista->usercurso  = $orden->ORDENC_Usuario; 
            $lista->clavecurso = $orden->ORDENC_Password; 
            $lista->tiempo     = $orden->ORDENC_Peso;
            $lista->moneda     = 1;
        }
        elseif($accion == "n"){ 
            $lista->paterno    = "";  
            $lista->materno    = ""; 
            $lista->nombres    = "";  
            $lista->curso      = "";  
            $lista->fecha      = "";
            $lista->alumno     = "";
            $lista->usercurso  = ""; 
            $lista->clavecurso = "";  
            $lista->tiempo     = "";
            $lista->moneda     = 1;
        }
        $selusuario        = form_dropdown('usuario',$this->usuario_model->seleccionar(''),$lista->curso,"id='usuario' class='comboMedio'");         
        $selcurso          = form_dropdown('curso',$this->producto_model->seleccionar(''),$lista->curso,"id='curso' class='comboMedio'");         
        $seltipoorden      = form_dropdown('tipoorden',$this->tipoorden_model->seleccionar(''),$lista->curso,"id='tipoorden' class='comboMedio'");         
        $filter            = new stdClass();
        $filter->provincia = "00";
        $filter->distrito  = "00";
        $seldpto           = form_dropdown('departamento',$this->ubigeo_model->seleccionar('',$filter),$departamento."0000","id='departamento' class='comboMedio'"); 
        $filter            = new stdClass();
        $filter->departamento = $departamento;
        $filter->distrito  = "00";
        $selprov           = form_dropdown('provincia',$this->ubigeo_model->seleccionar('',$filter),$departamento.$provincia."00","id='provincia' class='comboMedio'"); 
        $filter            = new stdClass();
        $filter->departamento = $departamento;
        $filter->provincia = $provincia;
        $seldist              = form_dropdown('distrito',$this->ubigeo_model->seleccionar('',$filter),$departamento.$provincia.$distrito,"id='distrito' class='comboMedio'");         
        $data['titulo']       = "ORDEN DE TRABAJO"; 
        $data['menu']         = $menu_padre;
        $data['form_open']    = form_open('',array("name"=>"frmPersona","id"=>"frmPersona","onsubmit"=>"return valida_guiain();"));     
        $data['form_close']   = form_close();         
        $data['lista']	      = $lista; 
        $data['seldpto']      = $seldpto;
        $data['selprov']      = $selprov;        
        $data['seldist']      = $seldist;           
        $data['selcurso']     = $selcurso;  
        $data['seltipoorden'] = $seltipoorden;  
        $data['selusuario']   = $selusuario;  
        $data['selmoneda']    = form_dropdown('moneda',$this->moneda_model->seleccionar(),$lista->moneda,"id='moneda' class='comboMedio'");
        $data['oculto']       = form_hidden(array("accion"=>$accion,"codigo"=>$codigo));
	$this->load->view("ventas/orden_nuevo",$data);
    }

    public function grabar(){
        $nuevaOt = new stdClass();
        $nuevaOt->NroOt  = '12-000001';
        $nuevaOt->CodEnt = '01';
        $nuevaOt->CodCli = '003874';
        $nuevaOt->CodRes = '000514';
        $nuevaOt->CodOt = '0006513';
        //$ots = $this->ot_model->insertar($nuevaOt);
        
    }
	
    public function eliminar(){

    }

    public function ver(){
        $codot         = $this->input->get_post('codot');
        $filter        = new stdClass();
        $filter_not    = new stdClass();
        $filter->codot = $codot;
        
        $ots           = $this->ot_model->obtenerg($filter,$filter_not);
        
        $numero        = $ots->NroOt;
        $presupuesto   = $ots->PreOt;
        $codcliente    = $ots->CodCli;
        $site          = $ots->DirOt;
        $OrdOt         = $ots->OrdOt;
        $CodRes        = $ots->CodRes;
        $CodOt        = $ots->CodOt;
        
        
        $ocompra       = "";
        $ubigeo        = $ots->UbiOt;
        $modelo        = $ots->Modelo;
        $proyecto      = $ots->Proyecto;
        $fecOt         = $ots->FecOt;
        $fecini        = $ots->FinOt;
        $fecfin        = $ots->FteOt;
        $descripcion   = $ots->DesOt;
        $observacion   = $ots->ObsOt;
        $forma_pago    = $ots->Pago;
        $peso    = $ots->PESO;
        
        
        $filter     = new stdClass();
        $filter_not = new stdClass();
        $filter->codcliente = $codcliente; 
        $clientes   = $this->cliente_model->obtener($filter,$filter_not);
        $personal   = isset($clientes->RazCli)?$clientes->RazCli:"";
        $gdni       = isset($clientes->RucCli)?$clientes->RucCli:"";
        $filter     = new stdClass();
        $filter_not = new stdClass();
        $filter->ubica = $ubigeo; 
        $ubi  = $this->ubigeo_model->obtener_ubigeo1($filter,$filter_not);
        $ubicacion    = isset($ubi->union)?html_entity_decode($ubi->union):"";

        
        $filter        = new stdClass();
        $filter_not    = new stdClass();
        $filter->codresponsable = $CodRes; 
        $resp  = $this->responsable_model->obtener($filter,$filter_not);
        $responsablex    = isset($resp->nomper)?$resp->nomper:"";

        $proy  = $this->proyecto_model->obtener($proyecto);
        $proyectos    = $proy->Des_Larga;
        
        

       
     
        $filter        = new stdClass();
        $filter_not    = new stdClass();
        $filter->codresponsable = $CodRes; 
        $resp  = $this->responsable_model->obtener($filter,$filter_not);
        $responsablex    = $resp->nomper;



        
        $sit  = $this->proyecto_model->obtener_sitio($modelo);
        $sitx   = $sit->Des_Larga;
   
        
        
        
        
        
        
        $this->load->library("fpdf/pdf");
        $CI = & get_instance();
        $CI->pdf->FPDF('P');
        $CI->pdf->AliasNbPages();
        $CI->pdf->AddPage();
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFillColor(255,255,255);
        /*Cabecera*/
       
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFillColor(216,216,216);
        //$CI->pdf->Image('images/anadir.jpg',11,4,30);
        $CI->pdf->SetFont('Arial','B',11);
         
        
        $CI->pdf->Image('img/mimco_ruc.jpg',10,8,55);
        $CI->pdf->Cell(0,13, $CodOt."- ORDEN DE TRABAJO - 2012 ",0,1,"C",0);
         $CI->pdf->SetFont('Arial','B',7);
        $CI->pdf->Cell(120,10, "" ,0,1,"L",0);
        
        $CI->pdf->Cell(90,5, "O/T No : ".$numero ,1,0,"L",0);
        $CI->pdf->Cell(1,1, "" ,0,0,"L",0);
        $CI->pdf->Cell(90,5, "PRESUPUESTO No : ".$presupuesto ,1,1,"L",0);
        $CI->pdf->Cell(90,1, "" ,0,1,"L",0);
        
        $CI->pdf->Cell(181,5, "CLIENTE :  ".$gdni."   -   ".$personal ,1,1,"L",0);
         $CI->pdf->Cell(90,1, "" ,0,1,"L",0);
        $CI->pdf->Cell(90,5, "SITE : ".$site ,1,0,"L",0);
        $CI->pdf->Cell(1,1, "" ,0,0,"L",0);
        $CI->pdf->Cell(90,5, "O/C CLIENTE : " .$OrdOt,1,1,"L",0);
         $CI->pdf->Cell(90,1, "" ,0,1,"L",0);
        $CI->pdf->Cell(181,5, "ENTREGA : ".$ubicacion ,1,1,"L",0);
         $CI->pdf->Cell(90,1, "" ,0,1,"L",0);
        $CI->pdf->Cell(90,5, "Mo. SITIO : ".$sitx,1,0,"L",0);
        $CI->pdf->Cell(1,1, "" ,0,0,"L",0);
        $CI->pdf->Cell(90,5, "PROYECTO : ".$proyectos ,1,1,"L",0);
         $CI->pdf->Cell(90,1, "" ,0,1,"L",0);
        $CI->pdf->Cell(90,5, "Apert. de O/T : ".$fecOt ,1,0,"L",0);
        $CI->pdf->Cell(1,1, "" ,0,0,"L",0);
        $CI->pdf->Cell(90,5, "INICIO : ".$fecini ,1,1,"L",0);
         $CI->pdf->Cell(90,1, "" ,0,1,"L",0);
         
        $CI->pdf->Cell(90,5, "TERMINO OFRECIDO : ".$fecfin ,1,0,"L",0);
        $CI->pdf->Cell(1,1, "" ,0,0,"L",0);
        $CI->pdf->Cell(90,5, "TERMINO REAL : ".$fecfin ,1,1,"L",0);   
         $CI->pdf->Cell(90,1, "" ,0,1,"L",0);
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFillColor(255,255,255);
        $CI->pdf->Cell(90,5, "RESPONSABLE : ".$responsablex ,1,0,"L",0);
        $CI->pdf->Cell(1,1, "" ,0,0,"L",0);
        $CI->pdf->Cell(90,5, "PESO ESTIMADO : ".$peso ,1,1,"L",0);  
         $CI->pdf->Cell(90,1, "" ,0,1,"L",0);
        $CI->pdf->Cell(181,5, "DESCRIPCION : " ,0,1,"L",1);
        $CI->pdf->Cell(181,5, SUBSTR($descripcion,0,124)."-" ,1,1,"L",1);
        $CI->pdf->Cell(181,5, SUBSTR($descripcion,124,250)."-" ,1,1,"L",1);
        $CI->pdf->Cell(181,5, SUBSTR($descripcion,250,374)."-" ,1,1,"L",1);
 /*       $CI->pdf->Cell(181,5, SUBSTR($descripcion,374,498)."-" ,1,1,"L",1);*/
         $CI->pdf->Cell(90,1, "" ,0,1,"L",0);
         
         
        $CI->pdf->Cell(181,5, "OBSERVACIONES: " ,0,1,"L",1);
        $CI->pdf->Cell(181,5, SUBSTR($observacion,0,124)."-" ,1,1,"L",1);
        $CI->pdf->Cell(181,5, SUBSTR($observacion,124,250)."-" ,1,1,"L",1);
        $CI->pdf->Cell(181,5, SUBSTR($observacion,250,374)."-" ,1,1,"L",1);
  /*      $CI->pdf->Cell(181,5, SUBSTR($descripcion,374,498)."-" ,1,1,"L",1);*/
        
         $CI->pdf->Cell(90,1, "" ,0,1,"L",0);
         $CI->pdf->Cell(181,5, "FORMA PAGO: " ,0,1,"L",1);
        $CI->pdf->Cell(181,5, SUBSTR($forma_pago,0,124)."-" ,1,1,"L",1);
         $CI->pdf->Cell(181,5, SUBSTR($forma_pago,124,250)."-" ,1,1,"L",1);
        

        
         $CI->pdf->Cell(90,13, "" ,0,1,"L",0);
         
         $CI->pdf->Cell(26,5, "-----------------------",0,0,"C",0);
         $CI->pdf->Cell(26,5, "-----------------------",0,0,"C",0);
         $CI->pdf->Cell(26,5, "-----------------------",0,0,"C",0);
         $CI->pdf->Cell(26,5, "-----------------------",0,0,"C",0);
         $CI->pdf->Cell(26,5, "-----------------------",0,0,"C",0);
         $CI->pdf->Cell(26,5, "-----------------------",0,0,"C",0);
         $CI->pdf->Cell( 26,5,"-----------------------",0,1,"C",0);
         $CI->pdf->Cell( 26,5,"G. General",0,0,"C",0);       
         $CI->pdf->Cell( 26,5,"G. Comercial",0,0,"C",0);
         $CI->pdf->Cell( 26,5,"G. Infraestructura",0,0,"C",0);
         $CI->pdf->Cell( 26,5,"G. Estr. Livianas",0,0,"C",0);
         $CI->pdf->Cell( 26,5,"G. Administrativa",0,0,"C",0);
         $CI->pdf->Cell( 26,5,"G. Estr. Pesadas",0,0,"C",0);
         $CI->pdf->Cell( 26,5,"G. Galvanizado",0,0,"C",0);
         
         $CI->pdf->Cell(92,15,"",0,1,"C",0);
         $CI->pdf->SetFont('Arial','B',10);
         $CI->pdf->Cell( 26,5,"FORMA DE PAGO",0,1,"l",0);
         
        $ot_det = $this->otdetalle_model->listar($CodOt);
      // print_r($ot_det);
        
        $i = 1;
            $CI->pdf->SetFont('Arial','B',7);
            $CI->pdf->Cell(20,3,"",0,1,"L",0);
           
            $CI->pdf->Cell(20,6,"Porcentaje",1,0,"C",0);
            $CI->pdf->Cell(60,6,"Forma Pago",1,0,"C",0);
           
            $CI->pdf->Cell(20,6,"Importe",1,0,"C",0);
            $CI->pdf->Cell(15,6,"No Factura",1,0,"C",0);
            $CI->pdf->Cell(30,6,"F. Factura",1,0,"C",0);
            $CI->pdf->Cell(30,6,"F. Vence",1,1,"C",0);
          
          
          
          
        foreach($ot_det as $inc=>$valu){
            $PorOt    = $valu->PorOt;
            $ImpOt = $valu->Impot;
            $fTeot    = $valu->fTeot;
            $dctos   = $valu->dctos;
            $fecfacturar = $valu->fecfacturar;
            $cod_tabla   = $valu->cod_tabla;
            $cod_arg   = $valu->fpaOt;
            
            $ot_det = $this->otdetalle_model->listar($cod_arg);
            
        
            $ot_for = $this->proyecto_model->obtener_forma_pago($cod_arg);
            
            $forma_pago=$ot_for->Des_Larga; 
            $CI->pdf->Cell(20,6,number_format($PorOt,2),1,0,"L",0);
            $CI->pdf->Cell(60,6,$forma_pago,1,0,"L",0);
           
            $CI->pdf->Cell(20,6,$ImpOt,1,0,"R",0);
            $CI->pdf->Cell(15,6,$dctos,1,0,"L",0);
            $CI->pdf->Cell(30,6,$fTeot,1,0,"L",0);
            $CI->pdf->Cell(30,6,$fecfacturar,1,1,"R",0);
            }
         $CI->pdf->SetFont('Arial','B',10);
         $CI->pdf->Cell(20,5,"",0,1,"L",0);
  /*       $CI->pdf->Cell( 26,5,"ESTADO DE CUENTA DE LA OBRA",0,1,"l",0);*/
         $CI->pdf->SetFont('Arial','B',7);
     
        $CI->pdf->SetTextColor(255,255,255);
        $CI->pdf->SetFillColor(192,192,192);
        $CI->pdf->Output();
    }
      
    public function obtener($tipoOt){
        $this->load->model(maestros.'tipoot_model');
        $tipoOt = $this->tipoot_model->obtener($tipoOt);
        echo json_encode($tipoOt);
    }
    
    public function rpt_por_facturar_cliente_detalle()
    {
        $opcion     = $this->input->get_post('opcion');   
        $tipo       = $this->input->get_post('tipo');   
        $fInicio    = $this->input->get_post('fInicio');   
        $fFin       = $this->input->get_post('fFin');   
        $tipOt      = $this->input->get_post('codperiodo');
        $codcliente = $this->input->get_post('codcliente2');
        $this->form_validation->set_rules('codcliente','Cliente','required');
        $this->form_validation->set_rules('codperiodo','Periodo','required');
        $this->form_validation->set_rules('fFin','Fecha','required');   
        $this->form_validation->set_rules('tipo','Tipo de reporte','required'); 
        $arrfInicio = explode("/",$fInicio);
        $arrfFin    = explode("/",$fFin);
        $f1         = mktime( 0, 0, 0,$arrfInicio[1]+1-1,$arrfInicio[0]+1-1,$arrfInicio[2]+1-1); 
        $f2         = mktime( 0, 0, 0,$arrfFin[1]+1-1,$arrfFin[0]+1-1,$arrfFin[2]+1-1); 
        $cboCli     = $this->cliente_model->seleccionar("Seleccionar todos","000000");  
        $periodoOt  = form_dropdown('codperiodo',$this->periodoot_model->seleccionar('',''),$tipOt,"id='codperiodo' class='comboMedio'");   
        $filter     = new stdClass();
        $filter_not = new stdClass();
        $filter->codcliente = $codcliente;
        $arrCliente = $this->cliente_model->obtener($filter,$filter_not);  
        if($f2 < $f1){
            redirect(ventas."ot/rpt_por_facturar_cliente_detalle");
        }
        $arrTc      = $this->tc_model->obtener($fFin);
        $tc         = $arrTc->Valor_2;
        $resultado  = $this->ot_model->rpt_por_facturar_cliente_detalle($tipOt,$fInicio, $fFin, $codcliente);
        if($tipo=="html"){
            $anio   = "";
            $mes    = "";
            $codcli = "";
            $cliente     = "";
            $sumadolares = "";
            $sumasoles   = "";
            $factDolares = ""; 
            $factSoles   = "";
            $factDol     = "";
            $factSol     = "";                
            $anio_ant    = "";
            $item        = 1;
            $fila        = "";
            $acumulado_dolares = 0;
            $acumulado_soles   = 0;
            $acumuado_factDolares = 0;
            $acumuado_factSoles   = 0;
            $acumulado_saldosoles_ant   = 0;
            $acumulado_saldodolares_ant = 0;
            $acumuado_saldoDolares = 0;
            $acumuado_saldoSoles   = 0;
            $acumuado_saldoDolares_total = 0;
            foreach($resultado as $indice => $value){
                $saldodolares_ant      = 0;
                $saldosoles_ant        = 0;	
                $inicialdolares = $value->inicialdolares;
                $inicialsoles   = $value->inicialsoles;
                $sumadolares    = $value->sumadolares;
                $sumasoles      = $value->sumasoles;
                $factDolares    = $value->factDolares;
                $totalDolares   = $value->totalDolares;
                $factSoles      = $value->factSoles;
                $numero         = $value->numero;
                $site           = $value->site;
                $codot          = $value->codot;
                $fila.="<tr ondblClick='rpt_detalle2(\"".$codot."\");' id='".$codot."'>";
                $fila.="<td align='center'>".$item."</td>";
                $chkDia = 1;
                $fila.=($chkDia=='1'?"<td><a href='javascript:;'>".(trim($numero)=='11-000000'?"SALDO INICIAL":$numero)." (".$site.")</a></td>":"");
                $fila.="<td align='right'>".number_format($sumasoles,2,",",".")."</td>";
                $fila.="<td align='right'>".number_format($sumadolares,2,",",".")."</td>";
                $fila.="<td align='right'>".number_format($factSoles,2,",",".")."</td>";
                $fila.="<td align='right'>".number_format($factDolares,2,",",".")."</td>";
                $fila.="<td align='right' style='background-color: #DDECFE; opacity:0.8' >".number_format(($inicialsoles+$sumasoles-$factSoles),2,",",".")."</td>";				
                $fila.="<td align='right' style='background-color: #FFFFCC; opacity:0.8' >".number_format(($inicialdolares+$sumadolares-$factDolares),2,",",".")."</td>";
                $fila.="<td align='right' style='background-color: #CCFFCC; opacity:0.8' >".number_format((($inicialsoles+$sumasoles-$factSoles)/$tc)+($inicialdolares+$sumadolares-$factDolares),2,",",".")."</td>";
                $fila.="</tr>";
                //$codigo_ant  = $codigo;
                $factDol_ant = $factDol;
                $factSol_ant = $factSol;
                $anio_ant    = $anio;
                $mes_ant     = $mes;
                $acumulado_dolares     = $acumulado_dolares + $sumadolares;
                $acumulado_soles       = $acumulado_soles + $sumasoles;
                $acumuado_factDolares  = $acumuado_factDolares + $factDolares;
                $acumuado_factSoles    = $acumuado_factSoles + $factSoles;
                $acumulado_saldosoles_ant   = $acumulado_saldosoles_ant + $inicialsoles;
                $acumulado_saldodolares_ant = $acumulado_saldodolares_ant + $inicialdolares; 
                $acumuado_saldoDolares = $acumuado_saldoDolares + ($inicialdolares+$sumadolares-$factDolares);
                $acumuado_saldoSoles   = $acumuado_saldoSoles + ($inicialsoles+$sumasoles-$factSoles);
                $acumuado_saldoDolares_total = $acumuado_saldoDolares_total + (($inicialsoles+$sumasoles-$factSoles)/$tc)+($inicialdolares+$sumadolares-$factDolares);  
                $item++;
            }
            $cantidad = count($resultado);              
        } 
        elseif($tipo=="excel"){
            $xls = new Spreadsheet_Excel_Writer();
            $xls->send("ReportexFacturarCliente.xls");
            $sheet  =$xls->addWorksheet('xFacturarCli.');
            $sheet->setInputEncoding('ISO-8859-7');
            $format =$xls->addFormat();
            $format->setBold();
              $sheet->setRow(0, 40);
              $sheet->setRow(1,10);
              $sheet->setRow(2,45);
              $sheet->setRow(3,15);
              $sheet->setRow(4,15);
              $sheet->setRow(5,15);
              $sheet->setRow(6,15);
              $sheet->setRow(7,15);
              $sheet->setRow(8,15);
              $sheet->setRow(9,15);
              $sheet->setRow(10,15);
              $sheet->setRow(11,15);
             
              
              $format_bold=$xls->addFormat();
              $format_bold->setBold();
              $format_bold->setSize(9);
              $format_bold->setvAlign('vcenter');
              $format_bold->sethAlign('center');
              $format_bold->setBorder(1);
              $format_bold->setTextWrap();
             
              $format_titulo=$xls->addFormat();
              $format_titulo->setBold();
              $format_titulo->setSize(9);
              $format_titulo->setvAlign('vcenter');
              $format_titulo->sethAlign('center');
              $format_titulo->setBorder(1);
              $format_titulo->setTextWrap();
              
              $format_bold2=$xls->addFormat();
              $format_bold2->setSize(9);
              $format_bold2->setvAlign('vcenter');
              $format_bold2->sethAlign('center');
              $format_bold2->setBorder(1);
              $format_bold2->setTextWrap();
 
              $sheet->setColumn(0,0,11); //COLUMNA A1
              $sheet->setColumn(1,1,61); //COLUMNA B2
              $sheet->setColumn(2,2,12); //COLUMNA C3
              $sheet->setColumn(3,3,12); //COLUMNA D4
              $sheet->setColumn(4,4,12); //COLUMNA E5
              $sheet->setColumn(5,5,12); //COLUMNA F6
              $sheet->setColumn(6,6,12); //COLUMNA G7
              $sheet->setColumn(7,7,12); //COLUMNA H8
              $sheet->setColumn(8,8,12); //COLUMNA I9

                  
              $sheet->mergeCells(0,0,0,8);  
              $sheet->write(0,1,"REPORTE POR FACTURAR POR CLIENTE (INCLUYE NUEVAS VENTAS)",$format_titulo); $sheet->write(0,2,"",$format_bold); $sheet->write(0,3,"",$format_bold); $sheet->write(0,4,"",$format_bold); $sheet->write(0,5,"",$format_bold); $sheet->write(0,6,"",$format_bold);   $sheet->write(0,7,"",$format_bold);   $sheet->write(0,8,"",$format_bold);  
              $sheet->write(0,0,"REPORTE DEL 2012",$format_bold);
              
              $sheet->write(2,0,"No",$format_bold);       $sheet->write(2,1,"CLIENTE",$format_bold);  $sheet->write(2,2,"VALOR DE VENTA S/.",$format_bold);  $sheet->write(2,3,"VALOR DE VENTA $",$format_bold);   $sheet->write(2,4,"MONTO FACTURADO S/.",$format_bold);      $sheet->write(2,5,"MONTO FACTURADO $",$format_bold);   $sheet->write(2,6,"SALDO POR FACTURAR S/.",$format_bold);    $sheet->write(2,7,"SALDO POR FACTURAR $",$format_bold);   $sheet->write(2,8,"SALDO TOTAL POR FACTURAR $",$format_bold);  
              
              
                  $anio   = "";
                  $mes    = "";
                  $codcli = "";
                  $cliente     = "";
                  $sumadolares = "";
                  $sumasoles   = "";
                  $factDolares = "";
                  $factSoles   = "";
                  $anio_ant    = "";
                  $item        = 1;
                  $acumulado_dolares     = 0;
                  $acumulado_soles       = 0;
                  $acumuado_factDolares  = 0;
                  $acumuado_factSoles    = 0;
                  $acumuado_saldoSoles   = 0;
                  $acumuado_saldoDolares = 0;
                  $acumuado_saldoDolares_total = 0;
                  $acumulado_saldosoles_ant   = 0;
                  $acumulado_saldodolares_ant = 0;   
                  $fila = 3;
                  $codigo  = "";
                  $factDol = 0;
                  $factSol = 0;
                  foreach($resultado as $indice => $value){
                    $saldodolares_ant      = 0;
                    $saldosoles_ant        = 0;	
                    $cliente        = $value->cliente;
                    $sumadolares    = $value->sumadolares;
                    $sumasoles      = $value->sumasoles;
                    $factDolares    = $value->factDolares;
                    $factSoles      = $value->factSoles;
                    $mes            = ($chkMes=='1'?$value->mes:"");
                    $dia            = ($chkDia=='1'?$value->dia:"");
                    $anio           = ($chkAnio=='1'?$value->anio:"");
                    $numero         = ($chkAnio=='1'?$value->numero:"");
                    $site           = $value->site;
                    $codcli         = $value->codcli;
                    
                    $sheet->write($fila,0,$item,$format_bold2);
                    if($chkAnio=="1") $sheet->write($fila,1,$anio,$format_bold2);
                    if($chkMes=="1")  $sheet->write($fila,2,$mes,$format_bold2);
                    if($chkDia=="1")  $sheet->write($fila,3,$dia,$format_bold2);
                    if($chkDia=="1")  $sheet->write($fila,4,(trim($numero)=='11-000000'?"SALDO INICIAL":$numero),$format_bold2);
                    if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1")  $sheet->write($fila,5,$cliente,$format_bold2);else $sheet->write($fila,1,$cliente,$format_bold2); 
                    if($opcion=='4' && isset($_REQUEST['fInicio'])){
                       
                    }
                    if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1") $sheet->write($fila,8,$sumasoles,$format_bold2);else $sheet->write($fila,2,$sumasoles,$format_bold2);
                    if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1") $sheet->write($fila,9,$sumadolares,$format_bold2);else $sheet->write($fila,3,$sumadolares,$format_bold2);
                    if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1") $sheet->write($fila,10,$factSoles,$format_bold2);else $sheet->write($fila,4,$factSoles,$format_bold2);
                    if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1") $sheet->write($fila,11,$factDolares,$format_bold2);else $sheet->write($fila,5,$factDolares,$format_bold2);
                    if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1") $sheet->write($fila,12,($sumasoles-$factSoles),$format_bold2);else $sheet->write($fila,6,($sumasoles-$factSoles),$format_bold2);
                    if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1") $sheet->write($fila,13,($sumadolares-$factDolares),$format_bold2);else $sheet->write($fila,7,($sumadolares-$factDolares),$format_bold2);
                    if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1") $sheet->write($fila,14,(($sumasoles-$factSoles)/$tc)+($sumadolares-$factDolares),$format_bold2);else $sheet->write($fila,8,(($sumasoles-$factSoles)/$tc)+($sumadolares-$factDolares),$format_bold2);         
                    $codigo_ant = $codigo;
                    $factDol_ant = $factDol;
                    $factSol_ant = $factSol;
                    $anio_ant    = $anio;
                    $mes_ant     = $mes;
                    $acumulado_dolares     = $acumulado_dolares + $sumadolares;
                    $acumulado_soles       = $acumulado_soles + $sumasoles;
                    $acumuado_factDolares  = $acumuado_factDolares + $factDolares;
                    $acumuado_factSoles    = $acumuado_factSoles + $factSoles;

                    $acumuado_saldoDolares = $acumuado_saldoDolares + ($sumadolares-$factDolares);
                    $acumuado_saldoSoles   = $acumuado_saldoSoles + ($sumasoles-$factSoles);
                    $acumuado_saldoDolares_total = $acumuado_saldoDolares_total + (($sumasoles-$factSoles)/$tc)+($sumadolares-$factDolares);
                    $item++;
                    $fila++;
                  }

                  if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1") $sheet->write($fila,8,$acumulado_soles,$format_bold2);else $sheet->write($fila,2,$acumulado_soles,$format_bold2);
                  if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1") $sheet->write($fila,9,$acumulado_dolares,$format_bold2);else $sheet->write($fila,3,$acumulado_dolares,$format_bold2);
                  if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1") $sheet->write($fila,10,$acumuado_factSoles,$format_bold2);else $sheet->write($fila,4,$acumuado_factSoles,$format_bold2);
                  if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1") $sheet->write($fila,11,$acumuado_factDolares,$format_bold2);else $sheet->write($fila,5,$acumuado_factDolares,$format_bold2);
                  if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1") $sheet->write($fila,12,$acumuado_saldoSoles,$format_bold2);else $sheet->write($fila,6,$acumuado_saldoSoles,$format_bold2);
                  if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1") $sheet->write($fila,13,$acumuado_saldoDolares,$format_bold2);else $sheet->write($fila,7,$acumuado_saldoDolares,$format_bold2);
                  if($chkAnio=="1" && $chkMes=="1" && $chkDia=="1") $sheet->write($fila,14,$acumuado_saldoDolares_total,$format_bold2);else $sheet->write($fila,8,$acumuado_saldoDolares_total,$format_bold2);                       
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
                    $CI->pdf->SetX(10); 
                    $CI->pdf->Cell(284,20,"REPORTE POR FACTURAR POR CLIENTE - (DETALLE1)",1,1,"C",0);
                    $CI->pdf->SetX(10); 
                    $CI->pdf->Cell(284,8,"Reporte durante el 2012",1,1,"L",0);
                    $CI->pdf->SetFont('Arial','',7);
                    $CI->pdf->SetY(35);
                    $CI->pdf->SetX(10); 
                    $CI->pdf->Cell(10,5,"No",1,0,"C",0);
                    $CI->pdf->Cell(90,5,"CLIENTE",1,0,"C",0);
                    $CI->pdf->Cell(25,5,"VALORdeVENTA(S/)",1,0,"C",0);
                    $CI->pdf->Cell(25,5,"VALORdeVENTA $",1,0,"C",0);
                    $CI->pdf->Cell(27,5,"FACTURADO(S/)",1,0,"C",0);
                    $CI->pdf->Cell(27,5,"FACTURADO $",1,0,"C",0);
                    $CI->pdf->Cell(26,5,"SALDOxFACTURAR(S/)",1,0,"C",0);
                    $CI->pdf->Cell(26,5,"SALDOxFACTURAR $",1,0,"C",0); //
                    $CI->pdf->Cell(28,5,"TOTALxFACTURAR $",1,0,"C",0);
                    $CI->pdf->SetFont('Arial','',8);
                    $CI->pdf->SetY(40);
                    $CI->pdf->SetX(10);
                    $anio   = "";
                    $mes    = "";
                    $codcli = "";
                    $cliente     = "";
                    $sumadolares = "";
                    $sumasoles   = "";
                    $factDolares = "";
                    $factSoles   = "";
                    $anio_ant    = "";
                    $item        = 1;
                    $acumulado_dolares     = 0;
                    $acumulado_soles       = 0;
                    $acumuado_factDolares  = 0;
                    $acumuado_factSoles    = 0;
                    $acumuado_saldoSoles   = 0;
                    $acumuado_saldoDolares = 0;
                    $acumuado_saldoDolares_total = 0;
                    $acumulado_saldosoles_ant   = 0;
                    $acumulado_saldodolares_ant = 0;  
                    $fila = 1;
                    $codigo  = "";
                    $factDol = 0;
                    $factSol = 0;
                    foreach($resultado as $indice => $value){
                        $saldodolares_ant = 0;
                        $saldosoles_ant   = 0;	
                        $cliente        = $value->cliente;
                        $inicialdolares = $value->inicialdolares;
                        $inicialsoles   = $value->inicialsoles;
                        $sumadolares    = $value->sumadolares;
                        $sumasoles      = $value->sumasoles;
                        $factDolares    = $value->factDolares;
                        $factSoles      = $value->factSoles;
                        $mes            = ($chkMes=='1'?$value->mes:"");
                        $dia            = ($chkDia=='1'?$value->dia:"");
                        $anio           = ($chkAnio=='1'?$value->anio:"");
                        $numero         = ($chkAnio=='1'?$value->numero:"");
                        $codcli         = $value->codcli;
                        $CI->pdf->Cell(10,5,$item,1,0,"C",0);
                        $CI->pdf->Cell(90,5,$cliente,1,0,"L",0);
                        $CI->pdf->Cell(25,5,$sumasoles,1,0,"R",0);
                        $CI->pdf->Cell(25,5,$sumadolares,1,0,"R",0);
                        $CI->pdf->Cell(27,5,$factSoles,1,0,"R",0);
                        $CI->pdf->Cell(27,5,$factDolares,1,0,"R",0);
                        $CI->pdf->Cell(26,5,($sumasoles-$factSoles),1,0,"R",0);
                        $CI->pdf->Cell(26,5,($sumadolares-$factDolares),1,0,"R",0);
                        $CI->pdf->Cell(28,5,(($sumasoles-$factSoles)/$tc)+($sumadolares-$factDolares),1,1,"R",0); 
                        $codigo_ant = $codigo;
                        $factDol_ant = $factDol;
                        $factSol_ant = $factSol;
                        $anio_ant    = $anio;
                        $mes_ant     = $mes;
                        $acumulado_dolares     = $acumulado_dolares + $sumadolares;
                        $acumulado_soles       = $acumulado_soles + $sumasoles;
                        $acumuado_factDolares  = $acumuado_factDolares + $factDolares;
                        $acumuado_factSoles    = $acumuado_factSoles + $factSoles;
                        $acumuado_saldoDolares = $acumuado_saldoDolares + ($sumadolares-$factDolares);
                        $acumuado_saldoSoles   = $acumuado_saldoSoles + ($sumasoles-$factSoles);
                        $acumuado_saldoDolares_total = $acumuado_saldoDolares_total + (($sumasoles-$factSoles)/$tc)+($sumadolares-$factDolares);
                        $item++;
                        $fila++; 
                    }
                    $CI->pdf->Cell(10,5,"",1,0,"C",0);
                    $CI->pdf->Cell(90,5,"",1,0,"L",0);
                    $CI->pdf->Cell(25,5,$acumulado_soles,1,0,"R",0);
                    $CI->pdf->Cell(25,5,$acumulado_dolares,1,0,"R",0);
                    $CI->pdf->Cell(27,5,$acumuado_factSoles,1,0,"R",0);
                    $CI->pdf->Cell(27,5,$acumuado_factDolares,1,0,"R",0);
                    $CI->pdf->Cell(26,5,$acumuado_saldoSoles,1,0,"R",0);
                    $CI->pdf->Cell(26,5,$acumuado_saldoDolares,1,0,"R",0);
                    $CI->pdf->Cell(28,5,$acumuado_saldoDolares_total,1,1,"R",0); 
                    $CI->pdf->Output();
                }

        $data2['acumulado_soles']             = $acumulado_soles;
        $data2['acumulado_dolares']           = $acumulado_dolares;
        $data2['acumuado_factSoles']          = $acumuado_factSoles;
        $data2['acumuado_factDolares']        = $acumuado_factDolares;
        $data2['acumuado_saldoSoles']         = $acumuado_saldoSoles;
        $data2['acumuado_saldoDolares']       = $acumuado_saldoDolares;
        $data2['acumuado_saldoDolares_total'] = $acumuado_saldoDolares_total;  
        $data2['fFin']       = $fFin;
        $data2['fInicio']    = $fInicio;
        $data2['codcliente'] = $codcliente;    
        $data2['tc']         = $tc;  
        $data2['fila']       = $fila; 
        $data2['tipo']       = $tipo; 
        $data2['razon_social'] = $arrCliente->RazCli;
        $data2['periodoOt']  = $periodoOt;
       $this->load->view(ventas."ots_x_facturar_detalle",$data2);  
    } 
    
    public function rpt_gestion_ot(){
        $codperiodo  = $this->input->get_post('codperiodo');
        $codcliente  = $this->input->get_post('codcliente');
        $codestado   = $this->input->get_post('codestado');
        $codproyecto = $this->input->get_post('codproyecto');
        $fInicio     = $this->input->get_post('fInicio');//A partir de aqui es valido.
        $fFin        = $this->input->get_post('fFin');
        $monedadoc   = $this->input->get_post('monedadoc');
        if($fInicio=="")      $fInicio    = "31/07/2011";
        if($fFin=="")         $fFin       = date("d/m/Y",time()); 
        if($codperiodo == "") $codperiodo = "18";
        if($monedadoc == "")  $monedadoc  = "S";
        $cboCliente  = form_dropdown('codcliente',$this->cliente_model->seleccionar2('','000000'),$codcliente,"id='codcliente' class='comboMedio'"); 
        $cboProy     = form_dropdown('codproyecto',$this->proyecto_model->seleccionar('','000'),$codproyecto,"id='codproyecto' class='comboMedio'");
        $cboEstado   = form_dropdown('codestado',$this->estadoot_model->seleccionar('','00'),$codestado,"id='codestado' class='comboMedio'");    
        $periodoOt   = form_dropdown('codperiodo',$this->periodoot_model->seleccionar('','00'),$codperiodo,"id='codperiodo' class='comboMedio'");   
        $oculto      = form_hidden(array('codot'=> '',"moneda"=>$monedadoc));
        $estado      = 0;
        $proyecto    = 0;
        $fila        = "";        
        if(TRUE){
            /*Se extrae las Materias primas por OT y se ingresan en un array*/
            $filter = new stdClass();
            $filter->tipoot   = $codperiodo;
            $filter->fechai   = $fInicio;
            $filter->fechaf   = $fFin;
            $filter->moneda   = $monedadoc;
            $filter->group_by = array("k.Codot");            
            $oMateriales      = costomateriales($filter);
            /*Se extraen las requisiciones de servicio y se ingresan en un array*/
            $filter    = new stdClass();
            $filternot = new stdClass();
            $filter->fechai = $fInicio;
            $filter->fechaf = $fFin;     
            $filter->moneda = $monedadoc;
            $filternot->codservicio = array('000000000010','000000000002','000000000074','000000000057','000000000001','000000000086','000000000084','000000000048','000000000049','000000000047','000000000046','000000000071');
            $oService = costoservicios($filter,$filternot);
            /*Se extraen las requisiciones de servicio de TRASNPORTE y se ingresan en un array(DBF)*/
            $filter    = new stdClass();
            $filternot = new stdClass();
            $filter->fechai = $fInicio;
            $filter->fechaf = $fFin;     
            $filter->moneda = $monedadoc;
            $filter->codservicio = array('000000000010','000000000002','000000000074','000000000057','000000000001','000000000086','000000000084','000000000048','000000000049','000000000047','000000000046','000000000071');
            $oTransport = costoservicios($filter,$filternot);               
            /*Matriz mano de obra*/
            $filter         = new stdClass();
            $filter->fechai = $fInicio;
            $filter->fechaf = $fFin;
            $filter->moneda = $monedadoc;  
            $filter->group_by = (substr($fFin,6,4)<= 2013)?array("c.nroot"):array('p.numeroorden');
            $oManoObra        = costomanoobra($filter,new stdClass()); 
            /*Matriz caja chica*/
            $filter           = new stdClass();
            $filter->fechai   = $fInicio;
            $filter->fechaf   = $fFin;
            $filter->moneda   = $monedadoc;  
            $filter->group_by = array("det.codot");
            $oCaja            = costocaja($filter,new stdClass());    
            /*Matriz de Tesoreria*/
            $filter = new stdClass();
            $filter_not = new stdClass();
            $filter->fechai   = $fInicio;
            $filter->fechaf   = $fFin;
            $filter->moneda   = $monedadoc;
            $filter->group_by = array("det.codot");
            $filter_not->codtipomov = array('03','19','02','08');
            $oTesoreria        = costotesoreria($filter,$filter_not); 
            /*Listado de OTs*/
            $filter = new stdClass();
            if($codproyecto!='000')    $filter->codproyecto = $codproyecto;
            //$filter->estado      = $codestado;
            $filter->tipoot      = $codperiodo;
            $filter->fechai      = $fInicio;
            $filter->fechaf      = $fFin;
            $filter2 = new stdClass();
            $filter2->Valor_3    = '02';
            $producto_old = $this->tipoproducto_old_model->listar($filter2);
            foreach($producto_old as $idice=>$value){
                $arrTipo[] = $value->cod_argumento;
            }
            $filter->tipo   = $arrTipo;
            $ots = $this->ot_model->listarg($filter,array('ot.nroOt'=>'desc')); 
            foreach($ots as $indice=>$value){
                $razcli         = $value->razcli;
                $site           = $value->DirOt;
                $numero         = $value->NroOt;
                $fIni           = $value->FinOt;
                $fFinal         = $value->FteOt;
                $moneda         = $value->EstOt;
                $codot          = $value->CodOt;
                $fecha          = $value->fecha;
                $fFabricacion   = $value->FfabOt;
                $peso           = $value->peso_teorico_sp;
                $peso_fab       = $value->peso;
                $idproyecto     = $value->Proyecto;
                $avance         = $value->avance;
                $tipoTorre      = $value->Torre;
                $ubigeo         = $value->UbiOt;
                $proyectos      = $this->proyecto_model->obtener($idproyecto);
                $nomproyecto    = $proyectos->Des_Larga;
                $torres         = $this->ttorre_model->obtener($tipoTorre);
                $descripcion    = $torres->Des_Larga;  
                $departamento   = $this->ubigeo_model->obtener_dpto($ubigeo);
                $provincia      = $this->ubigeo_model->obtener_prov($ubigeo);
                $distrito       = $this->ubigeo_model->obtener_dist($ubigeo);
                $departamento   = isset($departamento->nombre)?$departamento->nombre:"";
                $provincia      = isset($provincia->nombre)?$provincia->nombre:"";
                $distrito       = isset($distrito->nombre)?$distrito->nombre:"";
                $costos         = @$oMateriales[$codot]->costo + @$oService[$codot]->costo + @$oTransport[$codot]->costo + @$oManoObra[$codot]->costo + @$oTesoreria[$codot]->costo + @$oCaja[$codot]->costo;
                $fila.="<tr class='selectot' id='".$codot."'>";
                $fila.="<td align='center'>".$numero."</td>";
                $fila.="<td align='center'>".$fecha."</td>";
                $fila.="<td align='center'>Si</td>";
                $fila.="<td align='left'>".$nomproyecto."</td>";
                $fila.="<td align='left'>".$razcli."</td>";
                $fila.="<td align='left'>".$site."</td>";
    //            $fila.="<td align='left'>".$departamento."-".$provincia."-".$distrito."</td>";
    //            $fila.="<td align='left'></td>";
                $fila.="<td align='left'>".$descripcion."</td>";
                $fila.="<td align='right'>".number_format($peso_fab,2)."</td>";
    //            $fila.="<td align='center'>".$avance."</td>";
                $fila.="<td align='center'>".$fIni."</td>";
                $fila.="<td align='center'>".$fFinal."</td>";            
                $fila.="<td align='center'>".(trim($fFabricacion)=='01/01/1900'?'':trim($fFabricacion))."</td>";
                $fila.="<td>11/05/2012</td>";
                $fila.="<td align='right'>".number_format($costos,2)."</td>";  
                $fila.="</tr>";
            }            
        }
        $data['tipOt']   = $codperiodo;
        $data['fInicio'] = $fInicio;
        $data['fFin']    = $fFin;
        $data['fila']    = $fila;
        $data['cboProyecto'] = $cboProy;
        $data['cboEstado']   = $cboEstado;
        $data['cboCliente']  = $cboCliente;
        $data['periodoOt']   = $periodoOt;
        $data['oculto']      = $oculto;
        $this->load->view(ventas."ot_gestion",$data);
    }
    
    public function rpt_productos_ot(){
        $fecha_ini  = $this->input->get_post('fecha_ini');
        $fecha_fin  = $this->input->get_post('fecha_fin'); 
        $tipoexport = $this->input->get_post('tipoexport');
        $cod_torre  = $this->input->get_post('cod_torre');
        $codot      = $this->input->get_post('codot');
        $ot         = $this->input->get_post('ot');
        $familia    = $this->input->get_post('familia');
        $opcion     = $this->input->get_post('opcion');          
        $opcion     = 'C'; 
        $arr_export_detalle = array();
        $hora_actual = date("H:i:s",time()-3600);
        if($fecha_ini=="")    $fecha_ini    = date("01/m/Y",time());
        if($fecha_fin=="")    $fecha_fin    = date("d/m/Y",time());
        if($cod_torre=="")     $cod_torre     = "000";
        /**/
        $filter = new stdClass();
        $filter->fechai = $fecha_ini;
        $filter->fechaf = $fecha_fin;
        $filter->group_by = array("r.codot","r.GCODPRO");

        /*Productos*/
        $filter = new stdClass();
        $filter->estado    = 2;
        $filter->situacion = 2;
        $filter3 = new stdClass();
        $productos      = $this->producto_model->listar(new stdClass(),new stdClass(),array("P_descri"));
        $arrproducto2   = array("000000000000"=>"::: TODOS :::");
        foreach($productos as $indice => $value){
            $codpro = trim($value->codpro);
            $arrproducto[$codpro]  = $value;
            $arrproducto2[$codpro] = $value->codpro." - ".$value->despro;
        } 
        /* Familia */
        $familias       = $this->familia_model->listar(new stdclass());
        $arrfamilias2   = array("0000"=>"::: TODOS :::");
        foreach($familias as $indice => $value){
            $codfamilia = trim($value->cod_argumento);
            $arrfamilias2[$codfamilia] = $codfamilia." - ".$value->des_larga;
            $arrfamilias[$codfamilia] = $value->des_larga;
        } 
       
        /* tipo de torre */
        $filtert = new stdClass();
        $torres      = $this->ttorre_model->listar();
        $arrtorres2   = array("000"=>"::: TODOS :::");       
        foreach($torres as $indice => $value){
            $codtorre = trim($value->cod_argumento);
            $arrtorres2[$codtorre] = $codtorre." - ".$value->des_larga;
            $arrtorres[$codtorre] = $value->des_larga;
        } 
        
        
        /* crear select*/
        $filtrotorre     = form_dropdown("codtorre",$arrtorres2,$cod_torre,"id='cod_torre' class='comboMedio' onClick='limpiarText();' ");
        $filtrofamilia         = form_dropdown("codfam",$arrfamilias2,$familia,"id='familia' class='comboMedio' onClick='limpiarText();' ");
       
        /*requisiciones */
        $filterr=new stdClass();
        $filterr->group_by   =array('r.codot','p.p_codigo');
        $requisiciones  = $this->requis_model->listar_totales($filterr);
        
        /* Cargar Datos */
        $ot_pro= array();
        $fila      = "";
        $registros = 0;
        
        if($opcion=='C'){
            /* ingresos */
            $filter      = new stdClass();
            $filter_not  = new stdClass();
            $filter->fechai     = $fecha_ini;   
            $filter->fechaf     = $fecha_fin; 
            $filter->codot      =$codot; 
            
            $filter->group_by   =array('k.codot','codigo');
            $ingresos  = $this->ningreso_model->listar_ingresos($filter,$filter_not);
            echo "ingresos :: ".count($ingresos)."<br>";
                foreach ($ingresos as $key =>$val){
                    $ot_pro[trim("".$val->codot).trim("".$val->codigo)]       ="1";
                   // echo "*".trim($val->codot).trim($val->codigo)."<br>";
                    $ot_ing[trim($val->codot).trim($val->codigo)]       =array($val->cantidad,$val->soles,$val->dolares);
                }
            
            /* salidas */
            $filter1      = new stdClass();
            $filter_not1  = new stdClass();
            $filter1->fechai     = $fecha_ini;   
            $filter1->fechaf     = $fecha_fin; 
            $filter1->codot      =$codot; 
            $filter1->group_by   =array('k.codot','codigo');
           
            $salidas   = $this->nsalida_model->listar_salidas($filter1,$filter_not1);
             echo "salidas :: ".count($salidas)."<br>";
                foreach ($salidas as $key1 =>$val1){
                    $ot_pro[trim($val1->codot).trim($val1->codigo)]      ="3";
                  //  echo "*".trim($val1->codot).trim($val1->codigo)."<br>";
                    $ot_sal[trim($val1->codot).trim($val1->codigo)]      =array($val1->cantidad,$val1->soles,$val1->dolares);
                }
            
            krsort($ot_pro);    
            count($ot_pro);
            
            foreach ($ot_pro as $indice =>$value){
                $_codot= substr($indice,0,7);
                $cod_pro=substr($indice,7,strlen($indice));
                //  echo "OT ->". substr($indice,0,7);
                $rsot         = $this->ot_model->obtener(substr($indice,0,7));
                // $rsot         = $this->ot_model->obtener($value->codot);
             
                $nroot        =isset($rsot->NroOt)?$rsot->NroOt:'-';
                
                $torre='';
                $familia=substr($cod_pro,0,4);
                $tipotorre    =isset($rsot->NroOt)?$rsot->Torre:''; 
                if(trim($tipotorre)!='') { if(isset($arrtorres[$tipotorre])) $torre=$arrtorres[$tipotorre];}
                if(trim($familia)!='') { if(isset($arrfamilias[substr($cod_pro,0,4)])) $familia=$arrfamilias[substr($cod_pro,0,4)];}

                $despro  = isset($arrproducto[$cod_pro]->despro)?$arrproducto[$cod_pro]->despro:'No encontrado';
                /*calculo ingresos*/
                if(isset($ot_ing[$indice])){
                    $ingreso= $ot_ing[$indice][0];
                    $s_ingreso= $ot_ing[$indice][1];
                    $d_ingreso= $ot_ing[$indice][2];
                }else{
                    $ingreso= 0;
                    $s_ingreso= 0;
                    $d_ingreso= 0;
                }
                /*calculo salidas*/
                if(isset($ot_sal[$indice])){
                    $salida= $ot_sal[$indice][0];
                    $s_salida= $ot_sal[$indice][1];
                    $d_salida= $ot_sal[$indice][2];
                }else{
                    $salida= 0;
                    $s_salida= 0;
                    $d_salida= 0;
                }
                
                $saldo=$ingreso-$salida;
                $s_monto=$s_ingreso-$s_salida;
                $d_monto=$d_ingreso-$d_salida;

                $arr_data = array();
                $fila   .= "<tr>";
                      $fila   .= "<td align='center' style='width:1.7%;'><div>".$nroot."</div></td>";
                      $arr_data[] = $nroot;
                      $fila   .= "<td align='center' style='width:1.7%;'><div>".utf8_encode($torre)."</div></td>";
                      $arr_data[] = utf8_encode($torre);
                      $fila   .= "<td align='center' style='width:1.7%;'><div>".$cod_pro."</div></td>";
                      $arr_data[] = $cod_pro;
                      $fila   .= "<td align='center' style='width:1.7%;'><div>".utf8_encode($familia)." </div></td>";
                      $arr_data[] = utf8_encode($familia);
                      $fila   .= "<td align='center' style='width:1.7%;'><div>".utf8_encode($despro)."</div></td>";
                      $arr_data[] = utf8_encode($despro);
                      
                      $fila   .= "<td align='center' style='width:1.7%;'><div></div></td>";
                      
                      $fila   .= "<td align='center' style='width:1.7%;'><div> ".$ingreso."</div></td>";
                      $arr_data[] = $ingreso;
                      $fila   .= "<td align='center' style='width:1.7%;'><div> ".$salida."</div></td>";
                      $arr_data[] = $salida;
                      $fila   .= "<td align='center' style='width:1.7%;'><div> ".$saldo."</div></td>";
                      $arr_data[] = $saldo;
                      
                      $fila   .= "<td align='center' style='width:1.7%;'><div></div></td>";
                      
                     $fila   .= "<td align='center' style='width:1.7%;'><div> ".$s_ingreso."</div></td>";
                      $arr_data[] = $s_ingreso;
                      $fila   .= "<td align='center' style='width:1.7%;'><div> ".$s_salida."</div></td>";
                      $arr_data[] = $s_salida;
                      $fila   .= "<td align='center' style='width:1.7%;'><div> ".$s_monto."</div></td>";
                      $arr_data[] = $s_monto;



                $fila   .= "</tr>";
                $registros++;
                array_push($arr_export_detalle,$arr_data);
              
            }
           $var_export = array('rows' => $arr_export_detalle);
           $this->session->set_userdata('data_productos_x_ot', $var_export);
        }
       
        
        $data['fila'] = $fila;
        $data['filtrotorre']    =  $filtrotorre;
        $data['filtrofamilia']    =  $filtrofamilia;
        $data['tipoexport']    = $tipoexport;
        $data['codot']         = $codot;
        $data['ot']            = $ot;
        $data['fecha_ini']     = $fecha_ini;
        $data['fecha_fin']     = $fecha_fin;
        $data['cod_torre'] =    $codtorre;
        $data['familia'] =    $familia;
        $data['registros']     = $registros;
        $this->load->view(ventas."ots_productos_ot",$data);
    }

     public function export_excel($type) {
        if($this->session->userdata('data_'.$type)){
            $result = $this->session->userdata('data_'.$type);
            $arr_columns = array();            
            switch ($type) {
                case 'listar_requisiciones_ot':
                    $this->reports_model->rpt_general('rpt_'.$type, 'REQUISICIONES POR OT', $result["columns"], $result["rows"],$result["group"]);
                    break;
                case 'listar_control_pesos1':
                case 'listar_control_pesos2':
                case 'listar_control_pesos3':
                case 'listar_control_pesos4':
                case 'listar_control_pesos5':
                case 'listar_control_pesos':
                    $arr_export_detalle = array();
                    $arr_columns[]['STRING']  = 'NRO.OT';
                    $arr_columns[]['STRING']  = 'NOMBRE';
                    $arr_columns[]['STRING']  = 'PROYECTO';
                    $arr_columns[]['STRING']  = 'TIPO PRODUCTO';
                    $arr_columns[]['DATE']    = 'F.INICIO';
                    $arr_columns[]['DATE']    = 'F.TERMINO';
                    $arr_columns[]['NUMERIC'] = 'W.REQUISICION';
                    $arr_columns[]['NUMERIC'] = 'W.PPTO.';
                    //$arr_columns[]['NUMERIC'] = 'W.METRADO';
                    $arr_columns[]['NUMERIC'] = 'W.O.TECNICA';
                    $arr_columns[]['NUMERIC'] = 'W.GALVANIZADO';
                    $arr_columns[]['NUMERIC'] = 'W.PRODUCCION';
                    $arr_columns[]['NUMERIC'] = 'W.ALMACEN';
                    $arr_group = array();
                    $this->reports_model->rpt_general('rpt_'.$type,'Control de pesos',$arr_columns,$result["rows"],$arr_group); 
                    break;
                case'productos_x_ot':
                    $arr_export_detalle = array();
                    $arr_columns[]['STRING']  = 'NRO.OT';
                    $arr_columns[]['STRING']  = 'T.TORRE';
                    $arr_columns[]['STRING']  = 'CODIGO';
                    $arr_columns[]['STRING']  = 'FAMILIA';
                    $arr_columns[]['STRING']  = 'DESCRIPCION';
                    $arr_columns[]['NUMERIC'] = 'INGRESO';
                    $arr_columns[]['NUMERIC'] = 'SALIDA';
                    $arr_columns[]['NUMERIC'] = 'SALDO';
                    $arr_columns[]['NUMERIC'] = 'INGRESO';
                    $arr_columns[]['NUMERIC'] = 'SALIDA';
                    $arr_columns[]['NUMERIC'] = 'SALDO';
                    $arr_group = array('E5:G5'=>'CANTIDAD','H5:K5'=>'MONTO');
                    $arr_group = array();
                    $this->reports_model->rpt_general('rpt_'.$type,'pRODUCTOS POR OT',$arr_columns,$result["rows"],$arr_group); 
                    break;
            }
        }else{
            echo "<div style='color:rgb(150,150,150);padding:10px;width:560px;height:160px;border:1px solid rgb(210,210,210);'>
                No hay datos para exportar
                </div>";
        }
    }
}