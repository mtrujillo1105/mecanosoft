<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once "Spreadsheet/Excel/Writer.php";  
class Personal extends CI_Controller {
    var $entidad;
    var $login;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");        
        $this->load->model(personal.'responsable_model');
        $this->load->model(ventas.'ot_model');
        $this->load->model(personal.'reloj_model');
        $this->load->model(personal.'persona_model');
        $this->load->model(maestros.'area_model');
        $this->load->helper('date');
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');
    }
    
    public function index(){
        
    }
    
    public function listar(){
        
    }
    
    public function obtener(){
        
    }
    
    public function grabar(){
        $codres = $this->input->get_post('codres');
        $fecha  = $this->input->get_post('fecha');
        $dni    = $this->input->get_post('dni');
        $arrcodot    = $this->input->get_post('codot');
        $arrarea     = $this->input->get_post('area');
        $arrarea_old = $this->input->get_post('area_old');
        $arrhora     = $this->input->get_post('hora');
        
        $arrcantidad = $this->input->get_post('cantidad');
        $arrdescripcion = $this->input->get_post('descripcion');
        $arraccion      = $this->input->get_post('accion');
        for($k=0;$k<count($arrcodot);$k++){
            $codot1 = $arrcodot[$k];
            $area1  = $arrarea[$k];
            $area_old1  = $arrarea_old[$k];
            $hora1  = $arrhora[$k];
            $cantidad1    = $arrcantidad[$k]; 
            $descripcion1 = $arrdescripcion[$k];
            $accion1 = $arraccion[$k];
            if($accion1=='N'){
                if($codot1!=''){
                    $fliter = new stdClass();
                    $filter->item   = 1;
                    $filter->codres = $codres;
                    $filter->codent = $this->entidad;
                    $filter->dni    = $dni;
                    $filter->fecha  = $fecha;
                    $filter->areaproduccion = trim($area1);
                    $filter->cantidad       = trim($cantidad1);
                    $filter->horas          = $hora1;
                    $filter->descripcion    = $descripcion1;
                    $filter->codot          = $codot1;
                    $this->tareo_model->insertar($filter);                    
                }
            }
            elseif($accion1=='M'){
                $filter = new stdClass();
                $filter->cantidad       = trim($cantidad1);
                $filter->horas          = $hora1;
                $filter->descripcion    = $descripcion1;
                $filter->areaproduccion = trim($area1);
                $this->tareo_model->modificar($codres,$dni,$fecha,$codot1,$area_old1,$filter);
                //$sql = "update tareo set areaproduccion='".$area1."',cantidad='".trim($cantidad1)."',horas='".$hora1."',descripcion='".$descripcion1."' where item='1' and codres='".$codres."' and codent='".$codent."' and dni='".$dni."' and fecha='".$fecha."' and codot='".$codot1."'";
            }
        }
    }
    
    public function eliminar(){
        $codres  = $this->input->get_post('codres');
        $dni     = $this->input->get_post('dni');
        $fecha   = $this->input->get_post('fecha');
        $codot   = $this->input->get_post('codot');
        $aproduccion = $this->input->get_post('aproduccion');
        $where = array("item"=>1,"codres"=>$codres,"codent"=>$this->entidad,"dni"=>$dni,"fecha"=>$fecha,"areaproduccion"=>$aproduccion,"codot"=>$codot);
        $this->tareo_model->eliminar($where);
//        $cadena = "delete from tareo where item='1' and codres='".$codres."' and codent='".$codent."' and dni='".$dni."' and fecha='".$fecha."' and areaproduccion='".$aproduccion."' and codot='".$codot."'";
//        mssql_query($cadena,$cnx);
    }
      
    public function tareoot_cabecera()
    {

        $tipoexport    = $this->input->get_post('tipoexport');
     
        $fecha  = $this->input->get_post('fecha');
        if($fecha=='') $fecha = date("d/m/Y",time());
        $j = 1;
        $fila_cabecera = "";
        $fila_detalle  = "<td colspan='8' align='center'>NO EXISTEN REGISTROS</>"; 
        
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
                $fila_cabecera .= "<tr class='cabecera_class' id='".$codres."' id2='".$nomper."' id3='".$dni."' id4='".$estado."'>";
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
        
        
        
        
        
        
             
       if($tipoexport=="excel"){
  
       
              $xls = new Spreadsheet_Excel_Writer();
              $xls->send("Tareo_Por_OT.xls");
              $sheet  =$xls->addWorksheet('HojaPersonalOT');
              
  
              $sheet->setColumn(0,0,5); //COLUMNA A1
              $sheet->setColumn(1,1,49); //COLUMNA B2
              $sheet->setColumn(2,2,11.5); //COLUMNA C3
              $sheet->setColumn(3,3,10); //COLUMNA D4
              $sheet->setColumn(4,4,35); //COLUMNA E5
              $sheet->setColumn(5,5,25); //COLUMNA F6
       

              $sheet->setRow(0,50);
              $sheet->setRow(1,42);
             
              
              $format_bold=$xls->addFormat();
              $format_bold->setBold();
              $format_bold->setvAlign('vcenter');
              $format_bold->sethAlign('left');
              $format_bold->setBorder(1);
              $format_bold->setTextWrap();
             
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
              
              
              $sheet->mergeCells(0,0,0,5);  
              $sheet->write(0,1,"REPORTE DE TAREO POR OT",$format_titulo); $sheet->write(0,4,"Hasta el $fecha",$format_titulo);
              
              $sheet->write(1,0,"No.",$format_titulo2);  $sheet->write(1,1,"DATOS DEL PERSONAL",$format_titulo2);  $sheet->write(1,2,"ENTRADA",$format_titulo2);  $sheet->write(1,3,"SALIDA",$format_titulo2);   $sheet->write(1,4,"CENTRO DE LABORES",$format_titulo2);      $sheet->write(1,5,"No. DE OTS",$format_titulo2);  
              
            $x=1;
            $z=2;
         
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
                $cadOts   = "";
                if(count($ots)>0){
                    foreach($ots as $indice2=>$value2){
                        $cadOts = trim($cadOts).($cadOts!=''?",":"").trim($value2->nroOt);
                    }
                    $cadOts   = substr ($cadOts, 0, strlen($cadOts) - 1);
                }

                               $sheet->write($z,0,$x,$format_bold);
                               $sheet->write($z,1,$nomper,$format_bold);
                               $sheet->write($z,2,$hora,$format_bold);
                               $sheet->write($z,3,$salida,$format_bold);
                               $sheet->write($z,4,$dirot,$format_bold);
                               $sheet->write($z,5,$cadOts,$format_bold);
                                   
                                $x++;
                $z++;
            }
        } 


                                  $xls->close();
                                }        
        

        
        
        
        
        $data['fila_detalle']  = $fila_detalle; 
        $data['fecha']         = $fecha;
        $data['fila_cabecera'] = $fila_cabecera;
        $data['nrofilas']      = 0;
        $data['dni']           = "";
        $data['codres']        = "";
        $data['tipoexport']    = "";
        $data['estado']        = "C";
        $this->load->view(produccion."tareoot",$data);
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
                    $filtroarea     = form_dropdown("area[$j]",$this->area_model->seleccionar("::Seleccione:::","000"),$areaproduccion,"id='area[$j]' class='comboMedio' ".$disabled."");  
                    $fila_detalle  .= "<tr>";
                    $fila_detalle  .= "<td style='width:3%;' align='center' >".($j+1)."</td>";
                    $fila_detalle  .= "<td  style='width:10%;' align='left'><input type='hidden' class='otclass' name='codot[".$j."]' id='codot[".$j."]' value='".$codot."'><input readonly type='text' name='ot[".$j."]' id='ot[".$j."]' value='".$nroot."' style='width:70px;'></td>";
                    $fila_detalle  .= "<td  style='width:23%;' align='left'><input type='text' readonly name='site[".$j."]' id='site[".$j."]' style='width:210px;' value='".$dirOt."'></td>";
                    $fila_detalle  .= "<td style='width:13%;' align='center'><input type='hidden' name='area_old[".$j."]' id='area_old[".$j."]' style='width:25px;' value='".$areaproduccion."'>".$filtroarea."</td>";
                    $fila_detalle  .= "<td style='width:7%;' align='center'><span class='filatareo'><input type='text' maxlength='2' onkeypress='return numbersonly(this,event,\".\");' name='hora[".$j."]' id='hora[".$j."]' value='".trim($horas)."' style='width:50px;' ".$readonly."></span></td>";
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
    
    
    function seleccionar_areapro(){
        $resultado = $this->area_model->seleccionar("::Seleccione::","000");
        echo json_encode($resultado);
    }
}
?>
