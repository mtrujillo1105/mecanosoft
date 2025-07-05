<?php header("Content-type: text/html; charset=utf-8"); 
class Tipocodigo_model extends CI_Model{
    var $compania;
    var $table; 
    public function __construct(){
	parent::__construct();
        $this->compania = $this->session->userdata('compania');
        $this->table   = "tipocodigo";
    }
        
	function listar_tipo_codigo(){
		$query = $this->db->order_by('TIPCOD_Inciales')->where('TIPCOD_FlagEstado','1')->get('cji_tipocodigo');
		if($query->num_rows>0){
			foreach($query->result() as $fila){
				$data[] = $fila;
			}
			return $data;		
		}
	}
	function obtener_tipoDocumento($tipo){
		$query = $this->db->where('TIPCOD_Codigo',$tipo)->get('cji_tipocodigo');
		if($query->num_rows>0){
			foreach($query->result() as $fila){
				$data[] = $fila;
			}
			return $data;		
		}		
	}
}
?>