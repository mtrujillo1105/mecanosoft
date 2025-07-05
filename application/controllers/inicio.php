<?php header("Content-type: text/html; charset=utf-8"); 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {
    var $entidad;
    public function __construct(){
        parent::__construct(); 
        $this->load->model(compras.'proveedor_model');
        $this->load->model(maestros.'entidad_model');
        $this->load->model(seguridad.'usuario_model');
        $this->load->model(personal.'persona_model');    
        $this->load->model(seguridad.'permiso_model');  
        $this->load->model(seguridad.'menu_model');
        $this->entidad = $this->session->userdata('entidad');
        $this->codres  = $this->session->userdata('codres');
        $this->codot   = $this->session->userdata('codot');
    }

    public function index()
    {
        $data['titulo']     = "<strong>INGRESO AL SISTEMA</strong>";
        $data['form_open']  = form_open(base_url().'index.php/inicio/ingresar',array("name"=>"frmInicio","id"=>"frmInicio"));
        $data['form_close'] = form_close(); 
        $data['txtUsuario'] = form_input(array('name'=>'txtUsuario','id'=>'txtUsuario','value' =>'','maxlength' => '100','class' => 'cajaMedia2','onkeypress' => 'capLock(event)'));
        $data['txtClave']   = form_password(array('name'=>'txtClave','id'=>'txtClave','value'=>'','maxlength'=>'32','class' =>'cajaMedia2','onKeyPress'=>'return submitenter(this,event);return capLock(event)'));
		$data['cboEntidad'] = form_dropdown('compania',$this->entidad_model->seleccionar(),'',"id='compania' class='comboGrande'");        
        $data['onload']     = "onload=\"$('#txtUsuario').focus();\"";   
        $this->load->view("inicio",$data);
    }
    
    public function ingresar(){
        $this->form_validation->set_rules('txtUsuario','Nombre Usuario','required|max_length[20]');
        $this->form_validation->set_rules('txtClave','Clave de Usuario','required|max_length[15]');
        $this->form_validation->set_rules('compania','Compania','required');  
        if($this->form_validation->run() == FALSE){
            redirect('inicio/index');
        }
        else{
            $txtUsuario = $this->input->post('txtUsuario');
            $txtClave   = $this->input->post('txtClave');
            $compania   = $this->input->post('compania');
            $usuarios   = $this->usuario_model->ingresar(trim($txtUsuario),md5(trim($txtClave)),$compania);
			if(count((array)$usuarios)>0){
                $data = array(
                            'nomper'   => $usuarios->PERSC_Nombre." ".$usuarios->PERSC_ApellidoPaterno,
                            'login'    => $usuarios->USUA_usuario,
                            'compania' => $usuarios->COMPP_Codigo,
                            'codusu'   => $usuarios->USUA_Codigo,
                            'rolusu'   => $usuarios->ROL_Codigo
                             );
                $this->session->set_userdata($data);
                redirect("inicio/principal");                
            }
            else{
                $msgError = "<br><div align='center' class='error'>Usuario y/o contrasena no valido para esta empresa.</div>";
                echo $msgError;
                $this->index();
            }
        }
    }
    
    public function principal(){
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");                
        $arrmes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes    = date("m",time());
        $ano    = date("Y",time());
        $dia    = date("d",time());
        $ver    = "";
        $fecha  = $dia." DE ".strtoupper($arrmes[$mes-1])." DE ".$ano;
        $fecha_std = $dia."/".$mes."/".$ano;
        $fecha_red = $dia.$mes.$ano;
        $mes    = str_pad($mes,2,"0",STR_PAD_LEFT);
        $dia    = str_pad($dia,2,"0",STR_PAD_LEFT);
        $tiempo = mktime(1,1,1,$mes,$dia,$ano);
        $diasemana     = date("w",$tiempo);
        $aniv          = "";
        $anivot        = "";
        $varcalendario = "";
        $varpersona    = "";
        $varcalendario.= "<tr>";
        $nombreusuario = $this->session->userdata('nomper');
        $codusu        = $this->session->userdata('codusu');
        $compania      = $this->session->userdata('compania');
        $rolusu        = $this->session->userdata('rolusu');
        $entidades     = $this->entidad_model->obtener($compania);
        $nombreentidad = $entidades->EMPRC_RazonSocial;
        //Menu
        $filamenu = "<ul class='glossymenu' id='menu'>";
        $filter           = new stdClass();
        $filter->codigo   = 1; 
        $filter->rol      = $rolusu; 
        $filter->order_by = array("m.MENU_Orden"=>"asc","p.MENU_Codigo"=>"asc");
        $menu_padre = $this->permiso_model->listar($filter);
        foreach($menu_padre as $indice=>$value){
            $filamenu.="<li class='glossymenutitle'><a target=_blank href=".base_url().$value->MENU_Url.">" .$value->MENU_Descripcion. "</a>";
            $filamenu.="<ul>";
            $filter = new stdClass();
            $filter->codigo   = $value->MENU_Codigo;
            $filter->rol      = $rolusu; 
            $filter->order_by = array("m.MENU_Orden"=>"asc");
            $menu_hijo = $this->permiso_model->listar($filter);
            if(count($menu_hijo)>0){
                foreach($menu_hijo as $indice2=>$value2){
                    $filamenu.="<li><a target=_blank href=" .base_url().$value2->MENU_Url. ">" .$value2->MENU_Descripcion. "</a></li>";
                } 
            }
            $filamenu.="</ul>";
            $filamenu.="</li>";
        }
        $filamenu.="<li class='glossymenutitle'><a href='".base_url()."index.php/inicio/salir'>Salir</a></li>";
        $filamenu.="</ul>";
        
        
        $varr = $this->ver_agenda($fecha_red);
        //Reloj de asistencia
        $rpta  = "";
        $total = 0;
//        $reloj = $this->reloj_model->listar2($ano.$mes.$dia);
//        if(count($reloj)>0 && ($this->codot=='0001931' || $this->codot=='0001932' || $this->codot=='0001936')){
////            echo "<pre>";
////            print_r($reloj);
////            echo "</pre>";
////            exit;
//            foreach($reloj as $indice => $value){
//                if(isset($value->hora) && trim($value->hora)!=""){
//                    $arrhora = explode(":",$value->hora);
//                    $minutos = $arrhora[0] * 60 + $arrhora[1];
//                    $color = (($minutos<=495 && substr($value->tipo,0,1)=="I") ? "#f0f0f0" : ($minutos>1110 ? "#f0f0f0" : "#f0d0d0" ) );
//                    $rpta.="<tr style='border:1px solid #a0a0a0;padding:1 1 1 1;background:".$color."'>";
//                    $rpta.="<td align='left' style='font:10px verdana;'>";
//                    $rpta.=$value->nomper;
//                    $rpta.="</td>";
//                    $rpta.="<td style='align:center;font:11px verdana;'>".$value->hora."</td>";
//                    $rpta.="<td style='align:center;font:11px verdana;'>".substr($value->tipo,0,1)."</td>";
//                    $rpta.="</tr>"; 
//                }
//            }            
//        }
        $data['fecha'] = $fecha;
        $data['varr']  = $varr;
        $data['rpta']  = $rpta;
        $data['varcalendario'] = $varcalendario;
        $data['nombreusuario'] = $nombreusuario;
        $data['codres']        = $codusu;
        $data['nombreentidad'] = $nombreentidad;
        $data['filamenu']      = $filamenu;
        $data['menu']          = $menu_padre;
        $data['registros']     = count($menu_padre);
        $data['oculto']        = form_hidden(array("serie"=>"","numero"=>"","codot"=>""));
        $this->load->view("seguridad/principal",$data);
    }
    
    public function salir(){
        session_destroy();
        redirect('inicio/index');
    }
    
    public function agenda($fecha_red){
        $fecha_red = str_pad($fecha_red,8,"0",STR_PAD_LEFT);
        $dia  = substr($fecha_red,0,2);
        $mes  = substr($fecha_red,2,2);
        $ano  = substr($fecha_red,4,4);
        $arrmes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha  = $dia." DE ".strtoupper($arrmes[$mes-1])." DE ".$ano;  
        $ver    = "";      
        $entidad       = $this->session->userdata('entidad');
        $entidades     = $this->entidad_model->obtener($entidad);
        $nombreentidad = $entidades->RazEnt;        
        $varr          = $this->ver_agenda($fecha_red);
        $data['fecha'] = $fecha;
        $data['varr']  = $varr;
        $data['nombreentidad'] = $nombreentidad;
        $this->load->view("menu/agenda",$data);
    }
    
    public function ver_agenda($fecha_red){
        $dia  = substr($fecha_red,0,2);
        $mes  = substr($fecha_red,2,2);
        $ano  = substr($fecha_red,4,4);
        $varr = "";
        $filter      = new stdClass();
        $filter->fechanac = $fecha_red;
        $aniversario = $this->persona_model->listar($filter);
        //$ots         = $this->ot_model->obtener2($ano.$mes.$dia);
//        $filter      = new stdClass();
//        $filter_not  = new stdClass();
//        $filter->fecha       = $dia."/".$mes."/".$ano;
//        $filter->flgAprobado = 1;
//        $ocompras    = $this->ocompra_model->listar($filter,$filter_not);
        for($k=7;$k<24;$k++)
        {
            $fechaeval = date("Y-m-d",mktime(0,0,0,$mes,$dia,$ano));
            $desdeeval = str_pad($k,2,"0",STR_PAD_LEFT);            
            $numero    = 0;
            $buffer    = "";
            $varr.="<tr><td  title='Anadir un evento (doble click)' style='cursor:pointer' height='30' width='80' align='center' class='titulocabeceradia' ondblclick='accion(".chr(34)."a".chr(34).",".chr(34).$fechaeval.chr(34).",".chr(34).$desdeeval.chr(34).",".chr(34).chr(34).")'>".str_pad($k,2,0,STR_PAD_LEFT).":00</td>";
            $varr.="<td valign='top' class='detalledia' >";
            // ANALISIS DE ANIVERSARIOS
            if(is_array($aniversario)){
                for($p=0;$p<count($aniversario);$p++) 
                {
                    if ($desdeeval=="07")
                    {
                        if (strpos($varr,"<ANIVPERSONA>")<=0) $varr.="<ANIVPERSONA>";
                        $buffer.="<option value='a/".$aniversario[$p]->PERSC_Ruc."'>Cumpleanos de ".$aniversario[$p]->PERSC_Nombre." ".$aniversario[$p]->PERSC_ApellidoPaterno." ".$aniversario[$p]->PERSC_ApellidoMaterno."</option>";
                        $numero++;
                    };
                };
            }
            // ANALISIS DE OTS
//            if(is_array($ots)){
//                for($p=0;$p<count($ots);$p++) 
//                {
//                    if (strpos($varr,"<ANIVPERSONA>")<=0) $varr.="<ANIVPERSONA>";
//                    if ($ots[$p]->hora == $desdeeval){
//                        $tipodocumento = trim($this->entidad)=='01'?"OT":"OS";
//                        $buffer.="<option value='OT-".$ots[$p]->codot."'>".$tipodocumento." ".$ots[$p]->nroot." - ".$ots[$p]->desot."</option>";
//                    }  
//                    $numero++;
//                };
//            }    
            // ANALISIS DE ORDEN DE COMPRA
//            if(is_array($ocompras) && count($ocompras)>0){
//                for($p=0;$p<count($ocompras);$p++){
//                    if (strpos($varr,"<ANIVPERSONA>")<=0) $varr.="<ANIVPERSONA>";
//                    $fecha_oc = $ocompras[$p]->fec_apro;
//                    $arrfecha_oc  = explode(" ",trim($fecha_oc));
//                    $hora_oc   = substr($arrfecha_oc[1],0,2);
//                    if ($hora_oc == $desdeeval){
//                        $filter2      = new stdClass();
//                        $filter2_not  = new stdClass();
//                        $filter2->ruccliente = $ocompras[$p]->ruccli;
//                        $proveedores = $this->proveedor_model->obtener($filter2,$filter2_not);
//                        $buffer.="<option value='OC-".$ocompras[$p]->nrodoc."'>OC: ".$ocompras[$p]->nrodoc."-".(isset($proveedores->RazCli)?$proveedores->RazCli:'')."</option>";
//                    }  
//                    $numero++;
//                }
//            }
            $varr.="<select  style='border:0px;font:10px verdana;width:100%;' title='".$numero." eventos' size='3'>".$buffer."</select></td></tr>";
        }//ondblclick='activar(this)'
        return $varr;
    }
}