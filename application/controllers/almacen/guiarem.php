<?php header("Content-type: text/html; charset=utf-8"); if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guiarem extends CI_Controller {
    var $entidad;
    var $login;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");        
        $this->load->model(almacen.'producto_model');
        $this->load->model(almacen.'almacen_model');
        $this->load->model(almacen.'tipomovimiento_model');
        $this->load->model(almacen.'guiarem_model');
        $this->load->model(almacen.'guiaremdetalle_model');
        $this->load->model(almacen.'almacen_model');
        $this->load->model(almacen.'guiarem_model');
        $this->load->model(maestros.'ubigeo_model');
        $this->load->model(maestros.'documento_model');
        $this->load->model(personal.'responsable_model');
    }

    public function index(){
        $this->load->view('seguridad/inicio');
    }
    
    public function listar($j=0){
        $offset             = (int)$this->uri->segment(3);
        $conf['base_url']   = site_url('almacen/guiarem/listar/');
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
        $listado            = $this->guiarem_model->listar($filter,$filter_not,"",$conf['per_page'],$offset);
        $item               = $j+1;
        $fila               = "";
        if(count($listado)>0){
             foreach($listado as $indice=>$valor){
                 $serie   = $valor->serie;
                 $numero  = $valor->numero;
                 $fecha   = $valor->fecha;
                 $codpartida  = $valor->codpartida;
                 $codllegada  = $valor->codllegada;
                 $observacion = $valor->observacion;
                 $tipoguia    = $valor->tipoguia;
                 $numvale     = $valor->vale;
                 $codres      = $valor->codres;
                 $rucli       = $valor->ruccli;
                 $codcli      = $valor->codcli;
                 $seriereq    = $valor->seriereq;
                 $numreq      = $valor->numreq;
                 $codtransportista  = $valor->codtransportista;
                 $fila   .= "<tr class='".($indice%2==0?'itemParTabla':'itemParTabla')."'>";
                 $fila   .= "<td align='center'>".$item++."</td>";
                 $fila   .= "<td align='center'>".$fecha."</td>";
                 $fila   .= "<td align='center'>".$serie."-".$numero."</td>";
                 $fila   .= "<td align='center'>".$codllegada."</td>";
                 $fila   .= "<td align='center'>".$codres."</td>";
                 $fila   .= "<td align='center'>".$rucli."</td>";
                 $fila   .= "<td align='center'>".$observacion."</td>";
                 $fila   .= "<td align='center'>&nbsp;</td>";
                 $fila   .= "<td align='center'><a href='#' onclick='editar(".$numero.")'><img src='".base_url()."img/modificar.png' width='16' height='16' border='0' title='Modificar'></a></td>";
                 $fila   .= "<td align='center'><a href='#' onclick='ver(".$numero.")'><img src='".base_url()."img/ver.png' width='16' height='16' border='0' title='Modificar'></a></td>";
                 $fila   .= "<td align='center'><a href='#' onclick='eliminar(".$numero.")'><img src='".base_url()."img/eliminar.png' width='16' height='16' border='0' title='Modificar'></a></td>";                 
                 $fila   .= "</tr>";
             }
        }
        $data['fila']           = $fila;
        $data['titulo_busqueda'] = "Buscar Guia de remision";
        $data['titulo_tabla']    = "Relaci&oacute;n de Guias de Remision";
        $this->pagination->initialize($conf);
        $data['paginacion']      = $this->pagination->create_links();
        $this->load->view(almacen."guiarem_listar",$data);
    }
   
      public function editar($accion="e",$codigo="8"){
        $lista = new stdClass();
        if($accion == "e"){
            $titulo               = "EDITAR GUIA DE REMISION";
            $filter               = new stdClass();
            $filter->guiarem      = $codigo;
            $lista->detalle       = $this->guiaremdetalle_model->listar($filter);            
            $filter               = new stdClass();
            $filter->guiarem      = $codigo;
            $guiarem              = $this->guiarem_model->obtener($filter);
            $lista->fecha         = $guiarem->GUIAREMC_Fecha;
            $lista->numeroref     = $guiarem->GUIAREMC_NumeroRef;
            $lista->numero        = $guiarem->GUIAREMC_Numero;
            $lista->fechatraslado = $guiarem->GUIAREMC_FechaTraslado;  
            $lista->direntrega    = $guiarem->GUIAREMC_DirEntrega;  
            $lista->observacion   = $guiarem->GUIAREMC_Observacion;  
            $lista->marcaplaca    = $guiarem->GUIAREMC_MarcaPlaca;  
            $lista->certificado   = $guiarem->GUIAREMC_Certificado;  
            $lista->licencia      = $guiarem->GUIAREMC_Licencia;  
            $lista->ructransportista    = $guiarem->GUIAREMC_RucTransportista;  
            $lista->nombretransportista = $guiarem->GUIAREMC_NombreTransportista;  
            $lista->documento     = $guiarem->DOCUP_Codigo;  
            $lista->tipomov       = $guiarem->TIPOMOVP_Codigo;  
            $lista->usuario       = $guiarem->USUA_Codigo;  
            $lista->estado        = $guiarem->GUIAREMC_FlagEstado;  
            $lista->almacen       = $guiarem->ALMAP_Codigo;  
            $lista->cliente       = $guiarem->CLIP_Codigo;  
            $lista->ruccliente    = $guiarem->EMPRC_Ruc;  
            $lista->razon_social  = $guiarem->EMPRC_RazonSocial;  
            $lista->nomusuario    = $guiarem->PERSC_ApellidoPaterno." ".$guiarem->PERSC_ApellidoMaterno.", ".$guiarem->PERSC_Nombre; 
        }
        elseif($accion == "n"){
            $titulo               = "NUEVA GUIA DE REMISION";
            $lista->detalle       = array();
            $lista->fecha         = "";
            $lista->numeroref     = "";
            $lista->numero        = "";
            $lista->fechatraslado = "";  
            $lista->direntrega    = "";  
            $lista->observacion   = "";  
            $lista->marcaplaca    = "";  
            $lista->certificado   = "";  
            $lista->licencia      = "";  
            $lista->ructransportista    = "";  
            $lista->nombretransportista = "";  
            $lista->estado        = "";  
            $lista->almacen       = "";  
            $lista->cliente       = "";  
            $lista->ruccliente    = "";  
            $lista->razon_social  = "";  
            $lista->documento     = "";  
            $lista->tipomov       = "";  
            $lista->nomusuario    = $this->session->userdata('compania');
        }
        $arrEstado          = array("0"=>"::Seleccione::","1"=>"ACTIVO","2"=>"INACTIVO");
        $data['titulo']     = $titulo;
        $data['form_open']  = form_open('',array("name"=>"form1","id"=>"form1","onsubmit"=>"return valida_producto();","method"=>"post","enctype"=>"multipart/form-data"));     
        $data['form_close'] = form_close();    
        $data['lista']	    = $lista;
        $data['selestado']  = form_dropdown('estado',$arrEstado,$lista->estado,"id='estado' class='comboMedio'");
        $data['selalmacen'] = form_dropdown('almacen',$this->almacen_model->seleccionar(),$lista->almacen,"id='almacen' class='comboMedio'");        
        $data['seldocumento'] = form_dropdown('documento',$this->documento_model->seleccionar(),$lista->documento,"id='documento' class='comboMedio'");        
        $data['seltipomov']   = form_dropdown('tipomov',$this->tipomovimiento_model->seleccionar(),$lista->tipomov,"id='tipomov' class='comboMedio'");        
        $data['oculto']       = form_hidden(array('accion'=>$accion,'codigo'=>$codigo));
        $data['links']        = array("urlprod"=>base_url()."index.php/almacen/producto/editar/".$accion."/".$codigo,"urlatrib"=>base_url()."index.php/almacen/productoatributo/listar/".$accion."/".$codigo,"urlcomp"=>"");
        $this->load->view('almacen/guiarem_nueva',$data);
    }  
 
    public function listar_detalle(){

        $tipoexport      = $this->input->get_post('tipoexport');
        $fecha_ini       = $this->input->get_post('fecha_ini');
        $fecha_fin       = $this->input->get_post('fecha_fin');
        $tipomaterial    = $this->input->get_post('tipomaterial');
        $tipomovimiento  = $this->input->get_post('tipomovimiento'); 
        $moneda          = $this->input->get_post('moneda'); 
        $codtip          = $this->input->get_post('codtip');
        $codot           = $this->input->get_post('codot');
        $ots             = $this->input->get_post('ot');
        $tipot           = $this->input->get_post('tipot');
        $codproducto     = $this->input->get_post('codpro');

        $fila='';
   
       
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
      
     
        if($fecha_ini=="")    $fecha_ini    = date("01/m/Y",time());
        if($fecha_fin=="")    $fecha_fin    = date("d/m/Y",time());  
        
        $filter3     = new stdClass();
        $filter3_not = new stdClass();
        
        
        $filter3->fechai      = date_dbf($fecha_ini);
        $filter3->fechaf      = date_dbf($fecha_fin);
        
        if($tipomaterial!='00') $filter3->codtipomaterial = $tipomaterial;
        if($tipomovimiento!='00') $filter3->codtipomovimiento = $tipomovimiento;
        if($codproducto!="000000000000")    $filter3->codproducto = $codproducto;
       
      
        if($codot!='') 
        {$filter3->codot = $codot;
        $filter3->ot = $ots;}
        else {$codot='';}
    
        
        
        $guia = $this->guiarem_model->listar_detalle($filter3,$filter3_not);
              
        if($tipoexport==""){
           
              
           //   print_r($guia);
        foreach($guia as $indice => $value){
            $gserguia       = $value->gserguia;
            $gnumguia       = $value->gnumguia;
          //  print_r($gnumguia);
            $fecemi         = $value->fecemi;
            $gserreq        = $value->gserreq;
            $gnumreq        = $value->gnumreq;
            $gpersonal      = $value->gpersonal;
            $gruccli        = $value->gruccli;
            $gpartida       = $value->gpartida;
            $gllegada       = $value->gllegada;
            $gcodpro        = $value->gcodpro;
            $producto     = $value->p_descri;
            $gcantidad      = $value->gcantidad;
            $refe           = $value->refe;
            $fectra         = $value->fectra;
            $obs            = $value->obs;
            $dirpar         = $value->dirpar;
            $dire           = $value->dire;
            $clie_prov      = $value->clie_prov;
            $tiposalida     = $value->tiposalida;
            $gtrans         = $value->gtrans;
            $tipoguia       = $value->tipoguia;
            
            $gnumvale       = $value->gnumvale;
            $gdescri        = $value->gdescri;
            $codres         = $value->codres;
            $got            = $value->got;
          //  $codot1          = $value->codot;
          
            if($clie_prov=='1')$cp='Cliente';else if ($clie_prov=='2')$cp='Proveedor';else $cp='';       
            $filter3     = new stdClass();
            $filter3_not = new stdClass();
            $filter3->ruccliente = $gruccli;
           
            $provs = $this->proveedor_model->obtener2($filter3,$filter3_not);
            //print_r($provs);die;
            if(isset($provs->RazCli))
            $razcli      = $provs->RazCli; 
            else         $razcli      ='';   
            
            
            $razon_social = !isset($razcli)?'':$razcli;
                
            if ($razon_social ==''){$razon_social =$cp.' Inactivo';}
            else {$razon_social =$razcli;}
            /*Cliente - Proveedor*/
          
            if($tipoguia=='01')$cg='V.S.';else if ($tipoguia=='02')$cg='R.S.';else if ($tipoguia=='03')$cg='OTROS';      
                
            
            
            $filter     = new stdClass();
            $filter_not = new stdClass();
            $filter->ubica = $gpartida;
            $lugar = $this->ubigeo_model->obtener_ubicacion($filter,$filter_not);
            
            if(isset($lugar->Descrip))
            $direcp      = $lugar->Descrip; 
            else  $direcp="";
         
            $filter1     = new stdClass();
            $filter1_not = new stdClass();
            
       
            $filter1->ubicas = $gllegada;
            $lug = $this->ubigeo_model->obtener_ubicacion1($filter1,$filter1_not);
            
            if(isset($lug->des))  $direcc=$lug->des;
            else  $direcc="";
           
            //print_r($gruccli);die;
                     
            $filter2     = new stdClass();
            $filter2_not = new stdClass();
            $filter2->codresponsable = $codres;
            $filter2->estado    = 2;
            $filter2->situacion = 2;
            $responsable    = $this->responsable_model->obtener($filter2,$filter2_not);  
            $nomresponsable = !isset($responsable->nomper)?'':$responsable->nomper;
                
            if ($nomresponsable==''){$nomresponsable='Usuario Inactivo';}
            
            
            $fila        .= "<tr>";
            $fila        .= "<td align='right'>".$cg."</td>";
            $fila        .= "<td align='center'>".$gserguia.'-'.$gnumguia."</td>";
            $fila        .= "<td align='center'>".date_sql($fecemi)."</td>";
            $fila        .= "<td align='center'>".$gserreq.'-'.$gnumreq."</td>";
                       
            $fila        .= "<td align='center'>".$gruccli."</td>";
            $fila        .= "<td align='left'><b>".$cp.':</B> '.$razon_social."</td>";
            
            $fila        .= "<td align='left'>".$direcp."</td>";
            $fila        .= "<td align='left'>".$dirpar."</td>";
            $fila        .= "<td align='left'>".$direcc."</td>";
            $fila        .= "<td align='left'>".$dire."</td>"; 
            $fila        .= "<td align='left'>".$gcodpro."</td>";
            $fila        .= "<td align='left'>".$producto."</td>";
            $fila        .= "<td align='center'>".$gcantidad."</td>";
            $fila        .= "<td align='center'>".$refe."</td>";
            $fila        .= "<td align='center'>".date_sql($fectra)."</td>";
         /*   $fila        .= "<td align='right'>".$obs."</td>";
            $fila        .= "<td align='right'>".$tiposalida."</td>";*/
          //  $fila        .= "<td align='center'>".$gtrans."</td>";
            

            $fila        .= "<td align='right'>".$gnumvale."</td>";
        //    $fila        .= "<td align='center'>".$gdescri."</td>"; 
          //  $fila        .= "<td align='right'>".$codres."</td>";
            $fila        .= "<td align='right'>".$got."</td>";
          
             
         $fila        .= "</tr>";
            
             }
           
          }
              else if($tipoexport=="excel"){
         
              $xls = new Spreadsheet_Excel_Writer();
            $xls->send("Guia_Remision.xls");
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
            $sheet->write(0,1,"Reporte de Guias de Remision",$format_titulo);  
            /*if($nroOt!="") $sheet->write(0,4,"OT: ".$nroOt,$format_titulo); */
            
                       
            
            $sheet->write(1,0,"TIPO",$format_titulo2); 
            $sheet->write(1,1,"Nº GUIA",$format_titulo2); 
            $sheet->write(1,2,"FEC. EMISION",$format_titulo2); 
            $sheet->write(1,3,"REQUIS",$format_titulo2);  
            $sheet->write(1,4,"RUC",$format_titulo2);  
            $sheet->write(1,5,"RAZ. SOCIAL",$format_titulo2); 
            $sheet->write(1,6,"PARTIDA",$format_titulo2); 
            $sheet->write(1,7,"DIR. PARTIDA",$format_titulo2);   
         /*   $sheet->write(1,8,"MO",$format_titulo2); 
            $sheet->write(1,9,"T.C.",$format_titulo2);  */
            $sheet->write(1,8,"LLEGADA.",$format_titulo2); 
            $sheet->write(1,9,"DIR. LLEGADA",$format_titulo2); 
            $sheet->write(1,10,"CODIGO",$format_titulo2);  
            $sheet->write(1,11,"PRODUCTO",$format_titulo2);  
           // $sheet->write(1,11,"DOC. REF.",$format_titulo2); 
            $sheet->write(1,12,"CANT.",$format_titulo2); 
            $sheet->write(1,13,"REF.",$format_titulo2);   
            $sheet->write(1,14,"FEC. TRASLADO",$format_titulo2); 
            $sheet->write(1,15,"VALE SALIDA",$format_titulo2); 
            $sheet->write(1,16,"OT",$format_titulo2);  
           
            $z=2;
            $y=2;
            
            
            
            
            
           foreach($guia as $indice => $value){
        
           
      
              $gserguia       = $value->gserguia;
            $gnumguia       = $value->gnumguia;
          //  print_r($gnumguia);
            $fecemi         = $value->fecemi;
            $gserreq        = $value->gserreq;
            $gnumreq        = $value->gnumreq;
            $gpersonal      = $value->gpersonal;
            $gruccli        = $value->gruccli;
            $gpartida       = $value->gpartida;
            $gllegada       = $value->gllegada;
            $gcodpro        = $value->gcodpro;
            $producto     = $value->p_descri;
            $gcantidad      = $value->gcantidad;
            $refe           = $value->refe;
            $fectra         = $value->fectra;
            $obs            = $value->obs;
            $dirpar         = $value->dirpar;
            $dire           = $value->dire;
            $clie_prov      = $value->clie_prov;
            $tiposalida     = $value->tiposalida;
            $gtrans         = $value->gtrans;
            $tipoguia       = $value->tipoguia;
            
            $gnumvale       = $value->gnumvale;
            $gdescri        = $value->gdescri;
            $codres         = $value->codres;
            $got            = $value->got;
          //  $codot1          = $value->codot;
          
            if($clie_prov=='1')$cp='Cliente';else if ($clie_prov=='2')$cp='Proveedor';else $cp='';       
            $filter3     = new stdClass();
            $filter3_not = new stdClass();
            $filter3->ruccliente = $gruccli;
           
            $provs = $this->proveedor_model->obtener2($filter3,$filter3_not);
            //print_r($provs);die;
            if(isset($provs->RazCli))
            $razcli      = $provs->RazCli; 
            else         $razcli      ='';   
            
            
            $razon_social = !isset($razcli)?'':$razcli;
                
            if ($razon_social ==''){$razon_social =$cp.' Inactivo';}
            else {$razon_social =$razcli;}
            /*Cliente - Proveedor*/
          
            if($tipoguia=='01')$cg='V.S.';else if ($tipoguia=='02')$cg='R.S.';else if ($tipoguia=='03')$cg='OTROS';      
                
            
            
            $filter     = new stdClass();
            $filter_not = new stdClass();
            $filter->ubica = $gpartida;
            $lugar = $this->ubigeo_model->obtener_ubicacion($filter,$filter_not);
            $direcp      = $lugar->descrip; 
         
            $filter1     = new stdClass();
            $filter1_not = new stdClass();
            
       
            $filter1->ubicas = $gllegada;
            $lug = $this->ubigeo_model->obtener_ubicacion1($filter1,$filter1_not);
            
            if(isset($lug->des))  $direcc=$lug->des;
            else  $direcc="";
           
            //print_r($gruccli);die;
                     
            $filter2     = new stdClass();
            $filter2_not = new stdClass();
            $filter2->codresponsable = $codres;
            $filter2->estado    = 2;
            $filter2->situacion = 2;
            $responsable    = $this->responsable_model->obtener($filter2,$filter2_not);  
            $nomresponsable = !isset($responsable->nomper)?'':$responsable->nomper;
                
            if ($nomresponsable==''){$nomresponsable='Usuario Inactivo';}
            
            

            
            
                $sheet->write($z,0,$cg,$format_bold);
                $sheet->write($z,1,$gserguia.'-'.$gnumguia,$format_bold);
                $sheet->write($z,2,date_sql($fecemi),$format_bold);
                $sheet->write($z,3,$gserreq.'-'.$gnumreq,$format_bold);
                $sheet->write($z,4,$gruccli,$format_bold);
                $sheet->write($z,5,$razon_social,$format_bold);
                $sheet->write($z,6,$direcp,$format_bold);
                $sheet->write($z,7,$dirpar,$format_bold);
             /* $sheet->write($z,8,$moneda,$format_bold);
                $sheet->write($z,9, $tcambio,$format_bold);*/
                $sheet->write($z,8,$direcc,$format_bold);
                $sheet->write($z,9,$dire,$format_bold);
                $sheet->write($z,10,$gcodpro,$format_bold);
                $sheet->write($z,11,$producto,$format_bold);
           //   $sheet->write($z,11,$serief.'-'.$numdocf,$format_bold);
                $sheet->write($z,12,$gcantidad,$format_bold);
                $sheet->write($z,13,$refe,$format_bold);
                $sheet->write($z,14,date_sql($fectra),$format_bold);
             // $sheet->write($z,15, $fechafac,$format_bold);
                $sheet->write($z,15,$gnumvale,$format_bold);
                $sheet->write($z,16,$got,$format_bold);
                $z++;     
      }
           $xls->close();    
      
}   
            
      /*$data['codigo']      = $codigo;
        $data['descripcion'] = $descripcion;
        $data['stock']       = $stock;*/
        $data['tipoexport']      = $tipoexport;
        $data['fecha_ini']       = $fecha_ini;
        $data['fecha_fin']       = $fecha_fin;
      /*$data['selmoneda']       = $selmoneda;
        $data['tipomaterial']    = $cbotipomaterial;
        $data['tipomovimiento']  = $cbotipomovimiento;*/
        $data['filtroproducto']  = $filtroproducto;
        $data['tipot']           = $tipot;
        $data['codot']           = $codot;
        $data['ot']              = $ots;
        $data['fila']            = $fila;
        $this->load->view(almacen."guia_remision.php",$data);  
   }

 
    

}
?>