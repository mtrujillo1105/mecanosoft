<?php header("Content-type: text/html; charset=utf-8"); 
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Constancia extends CI_Controller {
    public function __construct(){
        parent::__construct();  
        $this->load->model(produccion.'constancia_model');
        $this->load->model(produccion.'tipomaterial_model');
        $this->load->model(ventas.'cliente_model');
        $this->load->model(ventas.'orden_model');  
        $this->load->model(seguridad.'permiso_model');  
        $this->load->model(almacen.'producto_model'); 
        $this->load->model(maestros.'persona_model');        
        $this->load->model(maestros.'moneda_model');         
        $this->configuracion = $this->config->item('conf_pagina');
    }
    
    public function index(){
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
                $lista[$indice]           = new stdClass();
                $lista[$indice]->codigo   = $value->ORDENP_Codigo;
                $lista[$indice]->nombres  = $value->PERSC_Nombre;
                $lista[$indice]->paterno  = $value->PERSC_ApellidoPaterno;
                $lista[$indice]->materno  = $value->PERSC_ApellidoMaterno;
                $lista[$indice]->curso    = $value->PROD_Nombre;
                $lista[$indice]->estado   = $value->ORDENC_FlagEstado;
                $lista[$indice]->fechareg = $value->ORDENC_FechaRegistro;
                $lista[$indice]->fecha    = $value->ORDENC_Fecot;
            }
        }
        $configuracion = $this->configuracion;
        $configuracion['base_url']    = base_url()."index.php/maestros/persona/listar";
        $configuracion['total_rows']  = $registros;
        $this->pagination->initialize($configuracion);
        /*Enviamos los datos a la vista*/
        $data['lista']        = $lista;
        $data['menu']         = $menu;
        $data['form_open']    = form_open('',array("name"=>"frmPersona","id"=>"frmPersona","onsubmit"=>"return valida_guiain();"));     
        $data['form_close']   = form_close();         
        $data['j']            = $j;
        $data['registros']    = $registros;
        $data['paginacion']   = $this->pagination->create_links();
        $this->load->view("ventas/orden_index",$data);
    }

    public function editar($accion='n',$codigo=""){
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
            $lista->fecha      = date('d/m/Y',time());
            $lista->alumno     = "";
            $lista->usercurso  = ""; 
            $lista->clavecurso = "";  
            $lista->tiempo     = "";
            $lista->moneda     = 1;
        } 
        $selcurso          = form_dropdown('curso',$this->producto_model->seleccionar(''),$lista->curso,"id='curso' class='comboMedio'");         
        $seltipomaterial   = form_dropdown('tipomaterial',$this->tipomaterial_model->seleccionar(''),$lista->curso,"id='tipomaterial' class='comboPequeno'");         
        $data['titulo']       = "CONSTANCIA DE RECEPCION"; 
        $data['menu']         = $menu_padre;
        $data['form_open']    = form_open('',array("name"=>"frmPersona","id"=>"frmPersona","onsubmit"=>"return valida_guiain();"));     
        $data['form_close']   = form_close();         
        $data['lista']	      = $lista;  
        $data['selcurso']     = $selcurso;  
        $data['seltipomat']   = $seltipomaterial;  
        $data['selmoneda']    = form_dropdown('moneda',$this->moneda_model->seleccionar(),$lista->moneda,"id='moneda' class='comboMedio'");
        $data['oculto']       = form_hidden(array("accion"=>$accion,"codigo"=>$codigo));
	$this->load->view("produccion/constancia_nuevo",$data);
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
}
?>
