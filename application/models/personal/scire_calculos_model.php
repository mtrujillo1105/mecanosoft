<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Scire_calculos_model extends CI_Model{
    var $entidad;
    var $table;

    public function __construct(){
       parent::__construct();
       $this->entidad = $this->session->userdata('entidad');
    }
    
    public function getDetallePlanilla($filter,$filter_not){
        $planilla_id = "";
        $periodo_id  = "";
        $personal_id = "";
        $concepto_id = "";
        $proceso_id  = "";
        $ccostoo_id  = "";
        $ttrabajador_id = "";
        $nro_cta = "";
        if(isset($filter->planilla) && $filter->planilla!='')         $planilla_id = $filter->planilla;
        if(isset($filter->periodo) && $filter->periodo!='')           $periodo_id  = trim($filter->periodo);
        if(isset($filter->persona) && $filter->persona!='')           $personal_id = $filter->persona;
        if(isset($filter->concepto) && $filter->concepto!='')         $concepto_id = $filter->concepto;
        if(isset($filter->proceso) && $filter->proceso!='')           $proceso_id  = $filter->proceso;
        if(isset($filter->ccosto) && $filter->ccosto!='')             $ccostoo_id  = $filter->ccosto;
        if(isset($filter->ttrabajador) && $filter->ttrabajador!='')   $ttrabajador_id  = $filter->ttrabajador;
        if(isset($filter->ttrabajador) && $filter->ttrabajador!='')   $nro_cta     = $filter->ttrabajador;
        $sql = "Sp_sel_Planilla_Semana '".$periodo_id."','".$ttrabajador_id."','".$ccostoo_id."','','".$this->entidad."'";
        $query = $this->db->query($sql);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }   
        return $resultado; 
    }
    
    
}
?>
