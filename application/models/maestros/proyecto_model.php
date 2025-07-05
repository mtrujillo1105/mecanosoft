<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proyecto_model extends CI_Model{
    var $table;
    var $entidad;
    public function __construct(){
        parent::__construct();
        $this->dbase   = $this->load->database('dsn',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "tabla_m_detalle";
    }
	
    public function seleccionar($default="",$value=''){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar() as $indice=>$valor)
        {
            $indice1   = $valor->cod_argumento;
            $valor1    = utf8_encode($valor->des_larga);
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }       
    
    public function listar($number_items='',$offset='')
    {
        $where = array('CodEnt'=>$this->entidad,'Cod_Tabla'=>'MPRO');
        $this->db->select('cod_tabla,cod_argumento,valor_2,des_larga');
        $this->db->from($this->table,$number_items,$offset);
        $this->db->where($where);
        $this->db->where_not_in('cod_argumento','001');
        $this->db->order_by('des_larga');        
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
	
    public function obtener($codigo){
        $where = array("cod_argumento"=>$codigo,"cod_tabla"=>'MPRO',"CodEnt"=>$this->entidad);
        $query = $this->db->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        return $resultado;
    }
    
      public function obtener_sitio($codigo){
        $where = array("cod_argumento"=>$codigo,"cod_tabla"=>'MSIT',"CodEnt"=>$this->entidad);
        $query = $this->db->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        return $resultado;
    }
    
     public function obtener_forma_pago($codigo){
         
        
        $where = array("cod_argumento"=>$codigo,"cod_tabla"=>'TFOR',"CodEnt"=>$this->entidad);
        $query = $this->db->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        return $resultado;
    }
    
    public function obtener_forma_pago_dbf($codigo){
         
        $cadena="select * from payfor where P_codigo='$codigo'";
       
        
          $query = $this->dbase->query($cadena);
            $resultado = $query->row();
        
      
        return $resultado;
    }
    
	
    public function insertar(stdClass $filter = null){
        $this->db->insert($this->table,(array)$filter);
    }
	
    public function modificar($id,$filter){
        $this->db->where("CodEnt",$id);
        $this->db->update($this->table,(array)$filter);
    }
	
    public function eliminar($id){
        $this->db->delete($this->table,array('CodEnt' => $id));
    }
}
?>