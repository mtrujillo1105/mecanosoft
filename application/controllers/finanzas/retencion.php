<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once "Spreadsheet/Excel/Writer.php";
class Retencion extends CI_Controller {
    var $entidad;
    var $login;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");   
        $this->load->model(finanzas.'retencion_model');
        $this->load->model(ventas.'ot_model');
        $this->load->model(personal.'responsable_model');
        $this->load->model(maestros.'banco_model');
        $this->load->model(personal.'responsable_model');
        $this->load->model(compras.'proveedor_model');
        $this->load->model(maestros.'tipomov_model');
        $this->dbase   = $this->load->database('dsn',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');
    }
    
    public function index()
    {
        $this->load->view(ventas."ot_listar");
    }
    
    public function listar(){
        $offset             = (int)$this->uri->segment(3);
        $conf['base_url']   = site_url('finanzas/voucher/listar/');
        $conf['per_page']   = 30;
        $conf['num_links']  = 3;
        $conf['first_link'] = "&lt;&lt;";
        $conf['last_link']  = "&gt;&gt;";
        $conf['next_link']  = "&gt;";
        $conf['prev_link']  = "&lt;";
        $conf['uri_segment']= 4;
        $conf['total_rows'] = 100;
        $j = 0;
        $filter             = new stdClass();
        $filter_not         = new stdClass();
        $filter->order_by   = array('fecrep'=>'desc');
        //$filter->ruc        = "20111821781";
        //$filter->fecha      = "23/08/2013";
        $listado            = $this->retencion_model->listar($filter,$filter_not,$conf['per_page'],$offset);
        $item               = $j+1;
        $fila               = "";
        if(count($listado)>0){
             foreach($listado as $indice=>$valor){
                 $fecha    = $valor->fecrep;
                 $tipdoc   = $valor->tipdoc;
                 $ruccli   = $valor->ruccli;
                 $razcli   = $valor->razcli;
                 $numero   = $valor->nrodoc;
                 /*Obtenemos los totales*/
                 $filter2 = new stdClass();
                 $filter2_not = new stdClass();
                 $filter2->numero = $numero;
                 
                 $listado2 = $this->retencion_model->listarTotales($filter2,$filter2_not);
                 $precio   = $listado2->monto;
                 $cantidad = $listado2->retencion;
                 $neto     = $precio + $cantidad;
                 $fila  .= "<tr class='".($indice%2==0?'itemParTabla':'itemParTabla')."'>";
                 $fila  .= "<td align='center'>".$item++."</td>";
                 $fila  .= "<td align='center'>".$fecha."</td>";
                 $fila  .= "<td align='center'>".$tipdoc."</td>";
                 $fila  .= "<td align='center'>".$ruccli."</td>";
                 $fila  .= "<td align='center'>".$razcli."</td>";
                 $fila  .= "<td align='rigth'>".number_format($precio,2)."</td>";
                 $fila  .= "<td align='rigth'>".number_format($cantidad,2)."</td>";
                 $fila  .= "<td align='rigth'>".number_format($neto,2)."</td>";
                 $fila  .= "<td align='center'><a href='#' onclick='editar(".$numero.")'><img src='".base_url()."img/modificar.png' width='16' height='16' border='0' title='Editar'></a></td>";
                 $fila  .= "<td align='center'><a href='#' onclick='ver(".$numero.")'><img src='".base_url()."img/ver.png' width='16' height='16' border='0' title='Ver'></a></td>";
                 $fila  .= "<td align='center'><a href='#' onclick='eliminar(".$numero.")'><img src='".base_url()."img/eliminar.png' width='16' height='16' border='0' title='Eliminar'></a></td>";                 
                 $fila  .= "</tr>";
             }
        }
        $data['fila']           = $fila;
        $data['titulo_busqueda'] = "Buscar Retencion";
        $data['titulo_tabla']    = "Relacion de Retencion";
        $this->pagination->initialize($conf);
        $data['paginacion']      = $this->pagination->create_links();
        $this->load->view(finanzas."retencion_listar",$data);
    }

    public function nuevo(){     
        $this->load->view(ventas."ot_nuevo");
    }
	
    public function editar(){

    }
    
    public function grabar(){

        
    }
	
  
      public function excel_retencion(){
        

        
        $tipoalmacen  = $this->input->get_post('tipoalmacen');
        $tipomaterial = $this->input->get_post('tipomaterial');
        $linea        = $this->input->get_post('familia');
        $tipoexport   = $this->input->get_post('tipoexport');
        $chknegativo  = $this->input->get_post('chknegativo');
        $chkprecio    = $this->input->get_post('chkprecio');
        $moneda_doc   = $this->input->get_post('moneda');
        
        $checked            = (($chknegativo=='1' || !isset($_REQUEST['tipoalmacen']))?true:false);
       
        
        
        $checkedprecio      = (($chkprecio=='1')?true:false);

        
        
        
        if($moneda_doc==''){
            $moneda_doc = "S";
        }
        
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
        $tcambio      = $tipocambio2->Valor_3."<br>";  
        
        if($tipoexport==""){
            
            $negativos = 0;
            $negativos_stock  = 0;
            $tmaterial_ant    = 0;
            $total_precioprod = 0;
            $total_preprom    = 0;
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
                
                IF ($this->codot!='0001934'){$total_precioprod = $total_precioprod + ($precioprod*$cantidad);} else{ ECHO '';}
                      
                
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
        
        $data['tbl_headers']       = $tbl_headers;
        
       
        $data['arr_data_products']       = json_encode($productos);   
        
        $data['total_precioprod'] = $total_precioprod;
        $data['total_preprom']    = $total_preprom;
        $data['oculto']           = form_hidden(array('serie'=>'','numero'=>'','cadenaot'=>'','codot'=>'','tipoexport'=>'','codpro'=>''));
        $this->load->view(almacen."stock_productos.php",$data);
        
    }
    
    
    
    
    public function eliminar(){

    }

    public function ver(){
        $numero         = $this->input->get_post('numero');
        $filter         = new stdClass();
        $filter_not     = new stdClass();
        $filter->numero = $numero;
        $voucher     = $this->voucher_model->obtener($filter,$filter_not);
        $fecemi      = $voucher->FecEmi;
        $fecpago     = $voucher->FecPago;
        $nrocheque   = $voucher->NroCheque;
        $nrocta      = $voucher->NroCta;
        $tipSolPago  = $voucher->TipSolPago;
        $codsolicita = $voucher->CodSolicita;
        $Est_vuelto  =$voucher->Est_Vuelto;
        $mtoPago     = $voucher->MtoPago;
        $fentrega    = $voucher->fEntrega;
        $Mo          = $voucher->MO;
        $observacion = $voucher->d_descripcion;
        $cambio = $voucher->Tc;
        $moneda      = $Mo==2?"NUEVOS SOLES":"DOLARES AMERICANOS";
        
        
       $codbco      = $voucher->CodBco;
       $filter         = new stdClass();
        $filter_not     = new stdClass();
        $filter->bancos = $codbco;    
        $ind   = $this->banco_model->obtener($filter,$filter_not);
      /*  PRINT_r($ind);*/
     $banco= $ind->b_banco; 
    /*    $codsolicita = $voucher->CodSolicita;
        $filter         = new stdClass();
        $filter_not     = new stdClass();
        $filter->codresponsable = $codsolicita;    
        $indice   = $this->responsable_model->obtener($filter,$filter_not);
        echo 
        $alaorden           = $indice->NomPer;*/
        
        
      $gpersonal   = $voucher->CodSolicita;
       /*  print_r($gruc);die;*/
       
        
        
        
       
       if ($Est_vuelto=='P')
        {
        $filter->ruccliente = $gpersonal; 
        
        $responsable   = $this->proveedor_model->obtener1($filter,$filter_not);
      //  print(count($responsable));die;
        if(isset($responsable->RazCli)){
        $personal    = $responsable->RazCli;
        $gdni    = $responsable->CodCli;}
        
         else
            $Est_vuelto='C';
         
       //  print_r($Est_vuelto);die;
          
        }
          
        if ($Est_vuelto=='C')
        {
        $filter->codresponsable = $gpersonal; 
        $responsable   = $this->responsable_model->obtener($filter,$filter_not);
        $personal    = $responsable->nomper;
        $gdni    = $responsable->dniper;
      
        
        
        }
        
        
        else
        { $filter->ruccliente = $gpersonal; 
        $responsable   = $this->proveedor_model->obtener1($filter,$filter_not);
        $personal    = $responsable->RazCli;
        $gdni    = $responsable->CodCli;}
        
        
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
        //$CI->pdf->Image('images/anadir.jpg',11,4,30);
        
         $CI->pdf->Image('img/mimco_ruc.jpg',10,8,55);
        $CI->pdf->Cell(0,8, "VOUCHER No ".$numero,0,1,"C",0);
         $CI->pdf->SetFont('Arial','B',7);
        $CI->pdf->Cell(120,5, "" ,0,1,"L",0);
        $CI->pdf->Cell(60,8,  "",0,1,"L",0);
        $CI->pdf->Cell(60,5, "BANCO : ".$banco,0,0,"L",0);
        $CI->pdf->Cell(60,5, "NRO CTA CTE : ".$nrocta ,0,0,"L",0);
        $CI->pdf->Cell(60,5,  "FECHA EMISION :  ".$fecemi,0,1,"L",0);
        $CI->pdf->Cell(60,5, "NRO CHEQUE : ".$nrocheque ,0,0,"L",0);
        $CI->pdf->Cell(60,5, "MONEDA : ".$moneda ,0,0,"L",0);
       
        $CI->pdf->Cell(60,5, "FECHA PAGO : ".$fecpago ,0,1,"L",0);
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFillColor(255,255,255);
        $CI->pdf->Cell(120,5,  "A LA ORDEN :   ". $gdni."   -   " . $personal,0,0,"L",0);
       
        $CI->pdf->Cell(60,5,  "T.C. :  ".$cambio ,0,1,"L",0);
        $CI->pdf->Cell(120,5, "OBSERVACION : ".$observacion ,0,0,"L",1);
        $CI->pdf->Cell(120,5, "" ,0,1,"L",1);
     
        $CI->pdf->SetTextColor(255,255,255);
        $CI->pdf->SetFillColor(192,192,192);
        /*Detalle*/
        $CI->pdf->Cell(0,5, "" ,0,1,"R",0);
        $CI->pdf->SetFillColor(0,0,128);
       /* $CI->pdf->Cell( 5,5,"Item",0,0,"C",1);*/
        $CI->pdf->Cell( 45,5,"TIPO MOV.",0,0,"C",1);
        $CI->pdf->Cell(13,5,"OT/CC",0,0,"C",1);
        $CI->pdf->Cell( 40,5,"SITE",0,0,"C",1);
        $CI->pdf->Cell( 60,5,"DESCRIPCION",0,0,"C",1);
        $CI->pdf->Cell( 21,5,"Nro. Doc.",0,0,"C",1);
        $CI->pdf->Cell( 10,5,"PAGO",0,0,"C",1);
        $CI->pdf->Cell( 7,5,"IGV",0,1,"C",1);
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFont('Arial','',6);
        $filter2     = new stdClass();
        $filter2_not = new stdClass();
        $filter2->numero = $numero;
        $voucher_det = $this->voucher_model->listar_detalle2($filter2,$filter2_not);
        $i = 1;
        $import=0;
        foreach($voucher_det as $indice=>$value){
            $importe     = $value->ImpPdet;
            $descripcion = $value->DesPago;
            $tipodoc     = $value->TipoDocRef;
            $numerodoc   = $value->NroDocRef;
            $seriedoc    = $value->SerieDocRef;
            $codot       = $value->codot;
            $tipPago     = $value->TipPago;
            $igv         = $value->Igv;
            
             $igv      = $igv=='C'?"Inc.":"Sin.";
            
            
            
        
        $filter->tipomov = $tipPago; 
        $valor   = $this->tipomov_model->obtener($filter,$filter_not);
        $tipomovi    = $valor->Des_Larga;
    
             
             
             
            /*Obtenemos OT*/
            $ots         = $this->ot_model->obtener($codot);
            $nroot       = $ots->NroOt;
            $dirot       = $ots->DirOt;
         /*   $CI->pdf->Cell(5,6,$i,0,0,"C",0);*/
            $CI->pdf->Cell(45,6,$tipomovi,1,0,"L",0);
            $CI->pdf->Cell(13,6,$nroot,1,0,"R",0);
            $CI->pdf->Cell(40,6,$dirot,1,0,"L",0);
            $CI->pdf->Cell(60,6,$descripcion,1,0,"L",0);    
            $CI->pdf->Cell(21,6,$tipodoc."-".$seriedoc."-".$numerodoc,1,0,"C",0);    
            $CI->pdf->Cell(10,6,number_format($importe,2),1,0,"R",0);    
            $CI->pdf->Cell(7,6,$igv,1,1,"c",0);    
            $i++;
            $import=$importe+$import;
        }
        $CI->pdf->Cell( 161,5,'',0,1,"C",0);
        $CI->pdf->Cell( 161,5,'',0,0,"C",0);
        $CI->pdf->Cell( 15,5,"TOTAL",1,0,"C",0);
        $CI->pdf->Cell( 15,5,number_format($import,2),1,1,"C",0);
        
        
        
        $CI->pdf->Output();
    }

    public function buscar(){

    }
}
?>
