<?php header("Content-type: text/html; charset=utf-8"); 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once "Spreadsheet/Excel/Writer.php";
class Productoatributo extends CI_Controller {
    var $configuracion;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");        
        $this->load->model(almacen.'producto_model');
        $this->load->model(almacen.'productoatributo_model');
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
        $filter->order_by    = array("d.PROD_Nombre"=>"asc","c.PRODATRIB_Descripcion"=>"asc");
        $registros = count($this->productoatributo_model->listar($filter,$filter_not));
        $productoatrib = $this->productoatributo_model->listar($filter,$filter_not,$this->configuracion['per_page'],$j);
        $item      = 1;
        $lista     = array();
        if(count($productoatrib)>0){
            foreach($productoatrib as $indice=>$valor){  
                $lista[$indice]                 = new stdClass();
                $lista[$indice]->codigo         = $valor->PRODATRIB_Codigo;
                $lista[$indice]->producto         = $valor->PROD_Nombre;
                $lista[$indice]->descripcion    = $valor->PRODATRIB_Descripcion;
                $lista[$indice]->descriampliada = $valor->PRODATRIB_DescripcionAmpliada;
                $lista[$indice]->cantidad       = $valor->PRODATRIB_Cantidad;
                $lista[$indice]->archivo        = $valor->PRODATRIB_Archivo;
            }
        }
        $configuracion = $this->configuracion;
        $configuracion['base_url']    = base_url()."index.php/almacen/productoatributo/listar";
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
        $this->load->view('almacen/productoatributo_index',$data);
    }

    public function editar($accion,$codigo=""){
        $lista = new stdClass();
        if($accion == "e"){
            $titulo                   = "EDITAR ATRIBUTO";      
            $filter                   = new stdClass();
            $filter->productoatributo = $codigo;
            $productoatributo         = $this->productoatributo_model->obtener($filter);
            $filter                   = new stdClass();
            $filter->producto         = $productoatributo->PROD_Codigo;
            $productos                = $this->producto_model->obtener($filter);                  
            $lista->descripcion       = $productoatributo->PRODATRIB_Descripcion;
            $lista->descriampliada    = $productoatributo->PRODATRIB_DescripcionAmpliada;
            $lista->cantidad          = $productoatributo->PRODATRIB_Cantidad;
            $lista->archivo           = $productoatributo->PRODATRIB_Archivo;
            $lista->nombre            = $productos->PROD_Nombre;
            $lista->producto          = $productoatributo->PROD_Codigo;
        }
        elseif($accion == "n"){
            $titulo                = "NUEVO ATRIBUTO";
            $lista->descripcion    = "";
            $lista->descriampliada = "";
            $lista->cantidad       = "";
            $lista->archivo        = "";
            $lista->nombre         = "";
            $lista->producto       = "";
        }
        $data['titulo']      = $titulo.":: ".$lista->descripcion;        
        $data['form_open']   = form_open('',array("name"=>"form1","id"=>"form1","onsubmit"=>"","method"=>"post","enctype"=>"multipart/form-data"));
        $data['form_close']  = form_close();
        $data['lista']	     = $lista;
        $filter              = new stdClass();
        $filter->order_by    = array("p.PROD_Nombre"=>"asc");
        $data['selproducto'] = form_dropdown('producto',$this->producto_model->seleccionar('0',$filter),$lista->producto,"id='producto' class='comboGrande'");
        $data['oculto']      = form_hidden(array('accion'=>$accion,'codigo'=>$codigo));
        $this->load->view('almacen/productoatributo_nuevo',$data);
    }  
    
    public function grabar(){
        $accion = $this->input->get_post('accion');
        $codigo = $this->input->get_post('codigo');
        $data   = array(
                        "PRODATRIB_Descripcion"         => strtoupper($this->input->post('descripcion')),
                        "PRODATRIB_DescripcionAmpliada" => strtoupper($this->input->post('descriampliada')),
                        "PRODATRIB_Cantidad"            => strtoupper($this->input->post('cantidad')),
                        "PRODATRIB_Archivo"             => "",
                        "PROD_Codigo"                   => $this->input->post('producto')
                       );
        if($accion == "n"){
            $this->productoatributo_model->insertar($data);            
        }
        elseif($accion == "e"){
            $this->productoatributo_model->modificar($codigo,$data);            
        }
    }   
    
    public function eliminar(){
        $codigo = $this->input->post('codigo');
        $this->productoatributo_model->eliminar($codigo);
    } 
}
?>