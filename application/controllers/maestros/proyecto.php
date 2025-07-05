<?php header("Content-type: text/html; charset=utf-8"); 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proyecto extends CI_Controller{
    public function __construct(){
        parent::__construct(); 
        $this->load->helper('form');
        $this->load->helper('date');
        $this->load->model('maestros/proyecto_model');
        $this->load->model('maestros/directivo_model');
        $this->load->model('maestros/compania_model');
        $this->load->model('maestros/persona_model');
        $this->load->library('html');
        $this->load->library('pagination');
        $this->load->library('layout','layout');
        $this->somevar ['compania'] = $this->session->userdata('compania');
        $this->somevar['hoy']       = mdate("%Y-%m-%d %h:%i:%s",time());
    }
    public function index()
    {
       $this->layout->view('seguridad/inicio');	
    }
    public function proyectos($j=0){
        $data['nombres']       = "";
        $data['descripcion']    = "";
        $data['encargado']  = "";
        $data['titulo_tabla']  = "RELACIÓN DE PROYECTOS";
        $data['registros'] =  count($this->proyecto_model->listar_proyectos());
        $data['action'] = base_url()."index.php/maestros/proyecto/buscar_proyectos";
        $conf['base_url'] = site_url('maestros/proyecto/buscar_proyectos/');
        $conf['total_rows'] = $data['registros'];
        $conf['per_page'] = 50;
        $conf['num_links']  = 3;
        $conf['next_link'] = "&gt;";
        $conf['prev_link'] = "&lt;";
        $conf['first_link'] = "&lt;&lt;";
        $conf['last_link']  = "&gt;&gt;";
        $conf['uri_segment'] = 4;
        $this->pagination->initialize($conf);
        $data['paginacion'] = $this->pagination->create_links();
        $listado_proyectos = $this->proyecto_model->listar_proyectos($conf['per_page'],$j);
        $item        = $j+1;
        $lista           = array();
                    if(count($listado_proyectos)>0){
                            foreach($listado_proyectos as $indice=>$valor){
                                    $codigo        = $valor->PROYP_Codigo;
                                    $nombre        = $valor->PROYC_Nombre;
                                    $descripcion   = $valor->PROYC_Descripcion;
                                    $directivo     = $valor->DIREP_Codigo;
                                    if($directivo!=0){
                                    $temp          = $this->directivo_model-> obtener_directivo($directivo);
                                    $persona       = $temp[0]->PERSP_Codigo;
                                    $temp2         = $this->persona_model->obtener_datosPersona($persona);
                                    $encargado     = $temp2[0]->PERSC_Nombre;
                                     }else{
                                         $encargado="";

                                         }
                                    
                                    $editar         = "<a href='javascript:;' onclick='editar_proyecto(".$codigo.")'><img src='".base_url()."images/modificar.png' width='16' height='16' border='0' title='Modificar'></a>";
                                    $ver            = "<a href='javascript:;' onclick='ver_proyecto(".$codigo.")'><img src='".base_url()."images/ver.png' width='16' height='16' border='0' title='Modificar'></a>";
                                    $eliminar       = "<a href='javascript:;' onclick='eliminar_proyecto(".$codigo.")'><img src='".base_url()."images/eliminar.png' width='16' height='16' border='0' title='Modificar'></a>";
                                    $lista[]        = array($item,$nombre,$descripcion,$encargado,$editar,$ver,$eliminar);
                                    $item++;
                            }
                    }
        $data['lista'] = $lista;
        print_r($lista);
        $this->layout->view("maestros/proyecto_index",$data);
    }
   public function nuevo_proyecto(){
                $compania=$this->somevar ['compania'];
                $temp =$this->compania_model->obtener_compania($compania);
                $empresa=$temp[0]->EMPRP_Codigo;
                $lista_directivos= $this->directivo_model->listar_directivo($empresa);
                $data['nombres']	      = "";
		$data['descripcion']	      = "";
                $data['encargado'] = "<select id='encargado' name='encargado' class='fuente8''>".$this->OPTION_generador($lista_directivos,'DIREP_Codigo',array('PERSC_Nombre','PERSC_ApellidoPaterno','PERSC_ApellidoMaterno')).'</select>';
                $data['id']	      = "";
                $data['fechai']        = form_input(array("name"=>"fechai","id"=>"fechai","class"=>"cajaPequena","readonly"=>"readonly","maxlength"=>"10","value"=>""));
                $data['fechaf']        = form_input(array("name"=>"fechaf","id"=>"fechaf","class"=>"cajaPequena","readonly"=>"readonly","maxlength"=>"10","value"=>""));    
		
                $data['modo']		      = "insertar";
		$objeto = new stdClass();
                $objeto->id     = "";
		$objeto->nombres     = "";
                $objeto->descripcion    = "";
                $objeto->fechai     = form_input(array("name"=>"fechai","id"=>"fechai","class"=>"cajaPequena","readonly"=>"readonly","maxlength"=>"10","value"=>""));
                $objeto->fechaf     = form_input(array("name"=>"fechaf","id"=>"fechaf","class"=>"cajaPequena","readonly"=>"readonly","maxlength"=>"10","value"=>""));
		$data['datos'] = $objeto;
		$data['titulo'] = "REGISTRAR PROYECTO";
		$data['listado_proyectos']  = array();

		$this->load->view("maestros/proyecto_nuevo",$data);
	}
 
 public function insertar_proyecto(){

                $nombres           = $this->input->post('nombres');
                $descripcion       = $this->input->post('descripcion');
                $encargado         = $this->input->post('encargado');
                $fechai            = $this->input->post('fechai');
                $compania          = $this->somevar['compania'];
                $fechaf            = $this->input->post('fechaf');
                $this->proyecto_model->insertar_datosProyecto($nombres,$descripcion,$encargado,$fechai,$fechaf,$compania);
           }
 public function editar_proyecto($proyecto){
                       $compania=$this->somevar ['compania'];
                       $temp =$this->compania_model->obtener_compania($compania);
                       $empresa=$temp[0]->EMPRP_Codigo;
                       $lista_directivos= $this->directivo_model->listar_directivo($empresa);

                       $data['modo']	  = "modificar";
                       $data['id']	  = $this->input->post('id');
		       $datos_proyecto   = $this->proyecto_model->obtener_datosProyecto($proyecto);
		       $nombres          = $datos_proyecto[0]->PROYC_Nombre;
                       $descripcion      = $datos_proyecto[0]->PROYC_Descripcion;
                       $encargado        = $datos_proyecto[0]->DIREP_Codigo;
                       $fechai           = $datos_proyecto[0]->PROYC_FechaInicio;
                       $fechaf           = $datos_proyecto[0]->PROYC_FechaFin;

                        $data['nombres']        = $nombres;
			$data['descripcion']    = $descripcion;
			$data['encargado']      = "<select id='encargado' name='encargado' class='fuente8''>".$this->OPTION_generador($lista_directivos,'DIREP_Codigo',array('PERSC_Nombre','PERSC_ApellidoPaterno','PERSC_ApellidoMaterno'),$encargado).'</select>';
			$data['fechai']         = form_input(array("name"=>"fechai","id"=>"fechai","class"=>"cajaPequena","readonly"=>"readonly","maxlength"=>"10","value"=>$fechai));
			$data['fechaf']         = form_input(array("name"=>"fechaf","id"=>"fechaf","class"=>"cajaPequena","readonly"=>"readonly","maxlength"=>"10","value"=>$fechaf));
                        $oculto     = form_hidden(array('accion'=>"",'codigo'=>$proyecto,'modo'=>"modificar",'base_url'=>base_url()));
		        $data['oculto']     = $oculto;

                       
                        $objeto                 = new stdClass();
                        $objeto->id             = $datos_proyecto[0]->PROYP_Codigo;
                        $objeto->nombres        = $datos_proyecto[0]->PROYC_Nombre;
                        $objeto->descripcion    = $datos_proyecto[0]->PROYC_Descripcion;
                        $objeto->encargado      = $datos_proyecto[0]->DIREP_Codigo;
                        $objeto->fechai         = $datos_proyecto[0]->PROYC_FechaInicio;
                        $objeto->fechaf         = $datos_proyecto[0]->PROYC_FechaFin;
                        $data['datos']    = $objeto;
                        $data['titulo']  = "EDITAR PROYECTO ::: ";
	                $this->load->view("maestros/proyecto_nuevo",$data);
	        }

  public function modificar_proyecto(){
		
                $codigo            = $this->input->post('proyecto');
		$nombres            = $this->input->post('nombres');
                $descripcion        = $this->input->post('descripcion');
		$encargado          = $this->input->post('encargado');
		$fechai             = $this->input->post('fechai');
		$fechaf             = $this->input->post('fechaf');	
		$this->proyecto_model->modificar_datosProyecto($codigo,$nombres,$descripcion,$encargado,$fechai,$fechaf);
  }

    public function ver_proyecto($proyecto)
    {
     
        $datos   = $this->proyecto_model->obtener_datosProyecto($proyecto);
        $data['nombres']             = $datos[0]->PROYC_Nombre;
        $data['descripcion']         = $datos[0]->PROYC_Descripcion;       
        $data['encargado']           = $datos[0]->DIREP_Codigo;
        $data['datos']  = $datos;
        $data['titulo'] = "VER PROYECTO";
        $this->load->view('maestros/proyecto_ver',$data);
    }

   public function buscar_proyectos($j='0'){
        $filter = new stdClass();
        $filter->PROYC_Nombre = $this->input->post('nombres');
        $data['nombres']      = $filter->PROYC_Nombre;
        $data['titulo_tabla']    = "RESULTADO DE BÚSQUEDA DE PROYECTOS";
        $data['registros']  = count($this->proyecto_model->buscar_proyectos($filter));
        $data['action'] = base_url()."index.php/maestros/proyecto/buscar_proyectos";
        $conf['base_url'] = site_url('maestros/proyecto/buscar_proyectos/');
        $conf['total_rows'] = $data['registros'];
        $conf['per_page']   = 10;
        $conf['num_links']  = 3;
        $conf['next_link'] = "&gt;";
        $conf['prev_link'] = "&lt;";
        $conf['first_link'] = "&lt;&lt;";
        $conf['last_link']  = "&gt;&gt;";
        $conf['uri_segment'] = 4;
        $this->pagination->initialize($conf);
        $data['paginacion'] = $this->pagination->create_links();
        $listado_proyectos = $this->proyecto_model->buscar_proyectos($filter, $conf['per_page'],$j);
        $item            = $j+1;
        $lista           = array();
                    if(count($listado_proyectos)>0){
                            foreach($listado_proyectos as $indice=>$valor){
                                    $proyecto       = $valor->PROYP_Codigo;
                                    $nombres        = $valor->PROYC_Nombre;
                                    $descripcion    = $valor->PROYC_Descripcion;
                                    $encargado      = $valor->DIREP_Codigo;
                                    $editar         = "<a href='#' onclick='editar_proyecto(".$proyecto.")'><img src='".base_url()."images/modificar.png' width='16' height='16' border='0' title='Modificar'></a>";
                                    $ver            = "<a href='#' onclick='ver_proyecto(".$proyecto.")'><img src='".base_url()."images/ver.png' width='16' height='16' border='0' title='Modificar'></a>";
                                    $eliminar       = "<a href='#' onclick='eliminar_proyecto(".$proyecto.")'><img src='".base_url()."images/eliminar.png' width='16' height='16' border='0' title='Modificar'></a>";
                                    $lista[]        = array($item,$nombres,$descripcion,$encargado,$editar,$ver,$eliminar);
                                    $item++;
                            }
                    }
        $data['lista'] = $lista;
        $this->layout->view("maestros/proyecto_index",$data);

    }

     public function eliminar_proyecto(){
		$proyecto = $this->input->post('proyecto');
		$this->proyecto_model->eliminar_proyecto($proyecto);
	}
}       
?>