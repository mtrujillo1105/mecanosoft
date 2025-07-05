<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Directivo_model extends CI_Model{
    var $compania;
    var $table; 
    public function __construct(){
	parent::__construct();
        $this->compania = $this->session->userdata('compania');
        $this->table   = "directivo";
    }

   public function listar_directivo($empresa){
        $where = array("cji_directivo.DIREC_FlagEstado"=>1,'cji_directivo.EMPRP_Codigo'=>$empresa);
        $query = $this->db->order_by('`cji_directivo`.PERSP_Codigo')
                 ->join('cji_persona', 'cji_persona.PERSP_Codigo = cji_directivo.PERSP_Codigo', 'left')
                          ->where_not_in('DIREP_Codigo','0')->where($where)
                          ->select('cji_directivo.DIREP_Codigo,cji_persona.PERSC_Nombre,cji_persona.PERSC_ApellidoPaterno,cji_persona.PERSC_ApellidoMaterno')
                          ->from('cji_directivo')
                          ->get();
        if($query->num_rows>0){
            foreach($query->result() as $fila){
                $data[] = $fila;
            }
            return $data;
        }
    }



        function obtener_directivo($directivo){
		$where = array('DIREP_Codigo'=>$directivo);
		$query = $this->db->where($where)->get('cji_directivo');
		if($query->num_rows>0){
			foreach($query->result() as $fila){
				$data[] = $fila;
			}
			return $data;
		}
	}
	function buscar_directivo($empresa,$persona){
		$where = array('EMPRP_Codigo'=>$empresa,'PERSP_Codigo'=>$persona);
		$query = $this->db->where($where)->get('cji_directivo');
		if($query->num_rows>0){
			foreach($query->result() as $fila){
				$data[] = $fila;
			}
			return $data;		
		}			
	}
}
?>