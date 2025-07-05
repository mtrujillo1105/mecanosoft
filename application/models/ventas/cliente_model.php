<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cliente_model extends CI_Model{
    var $compania;
    var $table;
    
    public function __construct(){
        parent::__construct();
        $this->compania = $this->session->userdata('compania');
        $this->table    = "cliente";
    }
	
    public function seleccionar($default="",$value=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar() as $indice=>$valor)
        {
            $indice1   = $valor->codcli;
            $valor1    = $valor->razcli;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }

    public function listar($filter,$filter_not='',$number_items='',$offset=''){     
        $where = array("c.COMPP_Codigo"=>$this->compania);
        $this->db->select('*');
        $this->db->from($this->table." as c");
        $this->db->join('empresa as p','p.EMPRP_Codigo=c.EMPRP_Codigo','inner');  
        $this->db->where($where);
        $query = $this->db->get();
        $resultado = new stdClass();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado; 
    }    

	
    public function obtener($filter,$filter_not){
        $where = array("CodEnt"=>$this->entidad,"tipcli"=>"02","estcli"=>'2');
        if(isset($filter->codcliente) && $filter->codcliente!='')  $where = array_merge($where,array("CodCli"=>$filter->codcliente));
        if(isset($filter->ruccliente) && $filter->ruccliente!='')  $where = array_merge($where,array("RucCli"=>$filter->ruccliente));
        
        if(isset($filter->tipcliente ) && $filter->tipcliente!=''){
            $where = array_merge($where,array("tipcli"=>$filter->tipcliente));
        }else{
            $where = array_merge($where,array("tipcli"=>"02"));
        }
        
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
	
    public function modificar($id,$filter){
        $this->db->where("CodCli",$id);
        $this->db->update($this->table,(array)$filter);
    }
	
    public function eliminar($id){
        $this->db->delete($this->table,array('CodCli' => $id));
    }
	
    public function buscar($filter,$number_items='',$offset=''){
        $this->db->select('*');
        $this->db->from($this->table,$number_items='',$offset='');
        $this->db->where('CodEnt',$this->entidad);
        $this->db->where_not_in('Estado','A');	
        if(isset($filter->CodEnt) && $filter->CodEnt!="")
            $this->db->like('CodEnt',$filter->CodEnt);
        if(isset($filter->Estado) && $filter->Estado!="")
            $this->db->like('Estado',$filter->Estado);
        $query = $this->db->get();
        if($query->num_rows>0){
            foreach($query->result() as $fila){
                    $data[] = $fila;
            }
            return $data;
        }
    }
}
?>