<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Proveedor_model extends CI_Model{
    var $entidad;
    var $table;
    
    public function __construct(){
       parent::__construct();
       //$this->dbase   = $this->load->database('dsn',TRUE);
       $this->entidad = $this->session->userdata('entidad');
       $this->table   = "clientes";        
    }
    
    public function seleccionar($filter,$default="",$value=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar($filter) as $indice=>$valor)
        {
            $indice1   = $valor->ruc;
            $valor1    = $valor->ruc." ::: ".$valor->rsocial;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    public function listar($filter,$filter_not="",$number_items='',$offset=''){
        $where = array("CodEnt"=>$this->entidad,"tipcli"=>"03");
        if(isset($filter->ruccliente) && $filter->ruccliente!='')  $where = array_merge($where,array("RucCli"=>$filter->ruccliente));
        if(isset($filter->estado) && $filter->estado!='')          $where = array_merge($where,array("EstCli"=>$filter->estado));
        $this->db->select('CodCli as codcli,RucCli as ruc,RazCli as rsocial');                                                                                                                                                     
        $this->db->from($this->table,$number_items,$offset);
        $this->db->where($where);
        if(isset($filter->order_by) && count($filter->order_by)>0){
            foreach($filter->order_by as $indice=>$value){
                $this->db->order_by($indice,$value);
            }
        }        
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
           $resultado = $query->result();
        }
        return $resultado;
    }
    
    public function obtener($filter,$filter_not){
        $where = array("CodEnt"=>$this->entidad,"tipcli"=>"03","estcli"=>'2');
        if(isset($filter->codcliente) && $filter->codcliente!='')  $where = array_merge($where,array("CodCli"=>$filter->codcliente));
        if(isset($filter->ruccliente) && $filter->ruccliente!='')  $where = array_merge($where,array("RucCli"=>$filter->ruccliente));
        $query = $this->db->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit("Existe mas de 1 resultado en la tabla ".$this->table."");
        if($query->num_rows==1){
           $resultado = $query->row();
        }
        return $resultado;
    }
    
    
     public function obtener1($filter,$filter_not){
        $where = array("CodEnt"=>$this->entidad);
    /*    if(isset($filter->codcliente) && $filter->codcliente!='')  $where = array_merge($where,array("CodCli"=>$filter->codcliente));*/
        if(isset($filter->ruccliente) && $filter->ruccliente!='')  $where = array_merge($where,array("CodCli"=>$filter->ruccliente));
        $query = $this->db->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultadofff');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        if($query->num_rows==0){
           
            $resultado='';
        }
        return $resultado;
    }
    
       public function obtener2($filter,$filter_not){
            $query = $this->db->select("top 1 RucCli,RazCli"); 
         $where = array("CodEnt"=>$this->entidad);
    /*    if(isset($filter->codcliente) && $filter->codcliente!='')  $where = array_merge($where,array("CodCli"=>$filter->codcliente));*/
        if(isset($filter->ruccliente) && $filter->ruccliente!='')  $where = array_merge($where,array("RucCli"=>$filter->ruccliente));
        $query = $this->db->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultadofff');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        if($query->num_rows==0){
           
            $resultado='';
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
    
    public function buscar($filter, $number_items='',$offset=''){   
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
    
    
    public function get_Supplier($sup_ruc, $sup_codent){
        $sql = "Sp_sel_Clientes '','".$sup_ruc."','".$sup_codent."','03',''";
        
        $query = $this->db->query($sql);
        $result = $query->result();
        
       
        foreach ($result as $key => $value) {
            return $value->RazCli;
            
        }
    }
}
?>