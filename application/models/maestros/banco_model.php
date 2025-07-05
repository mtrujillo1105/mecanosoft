<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Banco_model extends CI_Model{
    var $entidad;
    var $table;
    public function __construct(){
       parent::__construct();
       $this->entidad = $this->session->userdata('entidad');
       $this->table   = "banco";  
    }

    public function obtener($filter,$filter_not){
          $cadena="SELECT x.b_codigos, x.b_banco FROM banco as x WHERE x.b_codigos='$filter->bancos'";
          $query = $this->dbase->query($cadena);
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