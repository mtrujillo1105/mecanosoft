<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tipomov_model extends CI_Model{
    var $entidad;
    var $table;
    
    public function __construct(){
       parent::__construct();
       //$this->dbase   = $this->load->database('dsn',TRUE);
       $this->entidad = $this->session->userdata('entidad');
      /* $this->table   = "banco"; */ 
    
      
    }
    
      public function index(){
        
    }

    
        
      public function obtener($filter,$filter_not){
           
          
          $cadena="SELECT * FROM Tabla_M_Detalle as x where x.cod_tabla='TVOU' and x.codent='01' and Cod_Argumento='$filter->tipomov'";
                
          $query = $this->db->query($cadena);
          $resultado     = new stdClass();
          $resultado = $query->row();
          
                 
          return $resultado;
         
          
          
       /*   
          $where = array("b_codigos"=>$this->bancos); 
          $query = $this->db->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        return $resultado;*/
          
          
          
          
          
          
          
          
          
          
          
          
        }
}
?>