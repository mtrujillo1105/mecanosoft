<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ccosto_model extends CI_Model {
    
    public $entidad;
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->scire = $this->load->database('scire',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table = "Ccosto";
    }
     
    public function select($filter,$default="",$value=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array(''=>$nombre_defecto);
        $ccostos = $this->get($filter);
        if(count($ccostos)>1){
            foreach($ccostos as $indice=>$valor)
            {
                $indice1   = $valor->ccosto_id;
                $valor1    = utf8_encode($valor->Descripcion);
                $arreglo[$indice1] = $valor1;
            }            
        }
        elseif(count($ccostos)==1){
            $indice1   = $ccostos->ccosto_id;
            $valor1    = utf8_encode($ccostos->Descripcion);
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    public function get($filter,$number_items='',$offset=''){ 
        $where = array("Categoria2_Id" => $this->entidad);
        if(isset($filter->entidad)){
            if($filter->entidad == "")
                $where = array();
            else
                $where = array_merge($where,array("Categoria2_Id"=>$filter->entidad));
        }
        if(isset($filter->estado) && $filter->estado!='')              $where = array_merge($where,array("Estado_id"=>$filter->estado));
        if(isset($filter->ccosto) && $filter->ccosto!='')              $where = array_merge($where,array("ccosto_id"=>$filter->ccosto));
        if(isset($filter->ccosto_conta) && $filter->ccosto_conta!='')  $where = array_merge($where,array("Codigo_Auxiliar2"=>trim($filter->ccosto_conta)));
        $this->scire->select('*');
        $this->scire->from($this->table,$number_items,$offset);
        if(is_array($where) && isset($where))   $this->scire->where($where);
        $this->scire->where_not_in("ccosto_id","000000000000000");
        $this->scire->order_by('Codigo_Auxiliar');        
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>1)   $resultado = $query->result();
        if($query->num_rows==1)  $resultado = $query->row();
        return $resultado;
    }    
     
}
?>
