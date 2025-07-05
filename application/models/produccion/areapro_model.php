<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Areapro_model extends CI_Model{
    var $table;
    var $entidad;
    public function __construct(){
        parent::__construct();
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "tabla_m_detalle";
    }

    public function seleccionar($default="",$value=''){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar() as $indice=>$valor)
        {
            $indice1   = $valor->cod_argumento;
            $valor1    = $valor->des_larga;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }      
    
    public function listar($number_items='',$offset='')
    {
        $where = array('CodEnt'=>$this->entidad,'Cod_Tabla'=>'APRO');        
        $this->db->select('cod_tabla,cod_argumento,valor_2,des_larga,Des_Corta');
        $this->db->from($this->table,$number_items,$offset);
        $this->db->where($where);
        $this->db->where_not_in('cod_argumento','000');
        $this->db->order_by('des_larga');
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
	
    public function obtener($codigo)
    {
        $where = array("cod_argumento"=>$codigo,"cod_tabla"=>'TEOT',"CodEnt"=>$this->entidad);
        $query = $this->db->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        return $resultado;
    }
	
    public function insertar(stdClass $filter = null){
        $this->db->insert($this->table,(array)$filter);
    }
	
    public function modificar($codigo,$filter)
    {
        $where = array("cod_argumento"=>$codigo,"cod_tabla"=>'TEOT',"CodEnt"=>$this->entidad);
        $this->db->where($where);
        $this->db->update($this->table,(array)$filter);
    }
	
    public function eliminar($codigo)
    {
        $where = array("cod_argumento"=>$codigo,"cod_tabla"=>'TEOT',"CodEnt"=>$this->entidad);        
        $this->db->delete($this->table,array('CodEnt' => $id));
    }
}
?>