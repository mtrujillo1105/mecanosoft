<?php header("Content-type: text/html; charset=utf-8"); 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once "Spreadsheet/Excel/Writer.php";
class Productoatributodetalle extends CI_Controller {
    var $configuracion;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");        
        $this->load->model(almacen.'producto_model');
        $this->load->model(almacen.'productoatributo_model');
        $this->load->model(almacen.'productoatributodetalle_model');
        $this->load->model(almacen.'unidadmedida_model');
        $this->load->model(seguridad.'permiso_model');  
        $this->configuracion = $this->config->item('conf_pagina');
        $this->login   = $this->session->userdata('login');
    }

    public function index(){
        $this->load->view('seguridad/inicio');
    }
    
    public function listar($j=0){
        $filter     = new stdClass();
        $filter_not = new stdClass(); 
        $filter->order_by    = array("PROD_Nombre"=>"asc","d.PRODATRIB_Descripcion"=>"asc","c.PRODATRIBDET_Descripcion"=>"asc");
        $registros = count($this->productoatributodetalle_model->listar($filter,$filter_not));
        $productoatribdet = $this->productoatributodetalle_model->listar($filter,$filter_not,$this->configuracion['per_page'],$j);
        $item      = 1;
        $lista     = array();
        if(count($productoatribdet)>0){
            foreach($productoatribdet as $indice=>$valor){  
                $lista[$indice]                 = new stdClass();
                $lista[$indice]->codigo         = $valor->PRODATRIBDET_Codigo;
                $lista[$indice]->producto       = $valor->PROD_Nombre;
                $lista[$indice]->atributo       = $valor->PRODATRIB_Descripcion;
                $lista[$indice]->descripcion    = $valor->PRODATRIBDET_Descripcion;
                $lista[$indice]->descriampliada = $valor->PRODATRIBDET_DescripcionAmpliada;
                $lista[$indice]->cantidad       = $valor->PRODATRIBDET_Numero;
                $lista[$indice]->archivo        = $valor->PRODATRIBDET_Caracteristica1;
                
            }
        }
        $configuracion = $this->configuracion;
        $configuracion['base_url']    = base_url()."index.php/almacen/productoatributodetalle/listar";
        $configuracion['total_rows']  = $registros;
        $this->pagination->initialize($configuracion);        
        /*Datos para la vista*/
        $data['titulo_tabla']    = "RELACI&Oacute;N de PRODUCTOS ATRIBUTOS";
        $data['titulo_busqueda'] = "BUSCAR PRODUCTO ATRIBUTOS";        
        $data['form_open']  = form_open('',array("name"=>"frmProducto","id"=>"frmProducto","onsubmit"=>"return valida_producto();","method"=>"post","enctype"=>"multipart/form-data"));     
        $data['form_close'] = form_close(); 
        $data['lista']      = $lista;
        $data['codigo']     = "";
        $data['nombre']     = "";
        $data['familia']    = "";
        $data['registros']  = $registros;
        $data['paginacion'] = $this->pagination->create_links();        
        $this->load->view('almacen/productoatributodetalle_index',$data);
    }

    public function editar($accion,$codigo=""){
        $lista = new stdClass();
        if($accion == "e"){
            $titulo                   = "EDITAR ATRIBUTO DETALLE";      
            $filter                   = new stdClass();
            $filter->productoatributodetalle = $codigo;
            $productoatributodet      = $this->productoatributodetalle_model->obtener($filter);               
            $lista->descripcion       = $productoatributodet->PRODATRIBDET_Descripcion;
            $lista->descriampliada    = $productoatributodet->PRODATRIBDET_DescripcionAmpliada;
            $lista->cantidad          = $productoatributodet->PRODATRIBDET_Numero;
            $lista->caracteristica1   = $productoatributodet->PRODATRIBDET_Caracteristica1;
            $lista->caracteristica2   = $productoatributodet->PRODATRIBDET_Caracteristica2;
            $lista->caracteristica3   = $productoatributodet->PRODATRIBDET_Caracteristica3;
            $lista->caracteristica4   = $productoatributodet->PRODATRIBDET_Caracteristica4;
            $lista->caracteristica5   = $productoatributodet->PRODATRIBDET_Caracteristica5;
            $lista->nombre            = $productoatributodet->PROD_Nombre;
            $lista->atributo          = $productoatributodet->PRODATRIB_Codigo;
            $lista->producto          = $productoatributodet->PROD_Codigo;
        }
        elseif($accion == "n"){
            $titulo                 = "NUEVO ATRIBUTO DETALLE";
            $lista->descripcion     = "";
            $lista->descriampliada  = "";
            $lista->cantidad        = "";
            $lista->caracteristica1 = "";
            $lista->caracteristica2 = "";
            $lista->caracteristica3 = "";
            $lista->caracteristica4 = "";
            $lista->caracteristica5 = "";
            $lista->nombre          = "";
            $lista->atributo        = "";
            $lista->producto        = "";
        }
        $data['titulo']      = $titulo;        
        $data['form_open']   = form_open('',array("name"=>"form1","id"=>"form1","onsubmit"=>"","method"=>"post","enctype"=>"multipart/form-data"));
        $data['form_close']  = form_close();
        $data['lista']	     = $lista;
        $filter              = new stdClass();
        $filter->order_by    = array("p.PROD_Nombre"=>"asc");
        $data['selproducto'] = form_dropdown('producto',$this->producto_model->seleccionar('0',$filter),$lista->producto,"id='producto' class='comboGrande'");
        $filter              = new stdClass();
        $filter->order_by    = array("c.PRODATRIB_Descripcion"=>"asc");
        $data['selatributo'] = form_dropdown('atributo',$this->productoatributo_model->seleccionar('0',$filter),$lista->atributo,"id='atributo' class='comboGrande'");
        $data['oculto']      = form_hidden(array('accion'=>$accion,'codigo'=>$codigo));
        $this->load->view('almacen/productoatributodetalle_nuevo',$data);
    }  
    
    public function grabar(){
        $accion = $this->input->get_post('accion');
        $codigo = $this->input->get_post('codigo');
        $data   = array(
                        "PRODATRIBDET_Descripcion"         => strtoupper($this->input->post('descripcion')),
                        "PRODATRIBDET_DescripcionAmpliada" => strtoupper($this->input->post('descriampliada')),
                        "PRODATRIBDET_Numero"              => strtoupper($this->input->post('cantidad')),
                        "PRODATRIBDET_Caracteristica1"     => $this->input->post('caracteristica1'),
                        "PRODATRIBDET_Caracteristica2"     => $this->input->post('caracteristica2'),
                        "PRODATRIBDET_Caracteristica3"     => $this->input->post('caracteristica3'),
                        "PRODATRIBDET_Caracteristica4"     => $this->input->post('caracteristica4'),
                        "PRODATRIBDET_Caracteristica5"     => $this->input->post('caracteristica5'),
                        "PRODATRIB_Codigo"                 => $this->input->post('atributo')
                       );
        if($accion == "n"){
            $this->productoatributodetalle_model->insertar($data);            
        }
        elseif($accion == "e"){
            $this->productoatributodetalle_model->modificar($codigo,$data);            
        }
    }   
    
    public function eliminar(){
        $codigo = $this->input->post('codigo');
        $this->productoatributodetalle_model->eliminar($codigo);
    } 
}
?>