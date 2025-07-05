<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ctecorriente_model extends CI_Model {
    
    public $Ctas_ctes = 'Ctas_ctes';
    public $Ctacte_Operacion = 'Ctacte_Operacion';
    public $Ctacte_Motivo = 'Ctacte_Motivo';
    public $Cuotas = 'Cuotas';
    
    public function __construct() {
        parent::__construct();
        $this->entidad = $this->session->userdata('entidad');
    }
    
    public function getDetalleCteCorriente($filter, $filternot) {
        $sql = "cta.Cta_cte_Id,cta.Personal_Id,per.Apellido_Paterno + ' ' + per.Apellido_Materno + ' ' + per.Nombres as Nombres,";
        $sql .= "cta.Fecha_Sistema as FecMov,cta.Monto as Prestamo,cta.Nro_cuotas,cuo.Cuota_desc,cuo.Interes,cuo.Monto,";
        $sql .= "son.Descripcion as Periodo,est.Descripcion as Estado,mot.Descripcion as Motivo,ope.Descripcion as Operacion";
        $this->db->select($sql);
        $this->db->from('SCIRERH.dbo.Cuotas as cuo');
        $this->db->join('SCIRERH.dbo.Ctas_ctes as cta','cuo.Cta_cte_Id = cta.Cta_cte_Id','left');
        $this->db->join('SCIRERH.dbo.Personal as per','per.Personal_Id = cta.Personal_Id','left');
        $this->db->join('SCIRERH.dbo.Periodo as son','son.Periodo_Id = cuo.Periodo_Id','left');
        $this->db->join('SCIRERH.dbo.Cuotas_Estado_Pago as est','est.Cuotas_Estado_Pago_Id = cuo.Estado_pago_Id','left');
        $this->db->join('SCIRERH.dbo.Ctacte_Motivo as mot','mot.Motivo_CtaCte_Id = cta.Motivo_Id','left');
        $this->db->join('SCIRERH.dbo.Ctacte_Operacion as ope','cta.Operacion_Id = ope.Ctacte_Operacion_id','left');
        $this->db->order_by("son.Descripcion","asc");
        $this->db->order_by("3","asc");
        $this->db->where(array("per.Categoria2_id" => $this->entidad));
        $this->db->where_in('cuo.Periodo_Id', $filter->periodo);
        $query = $this->db->get();
        return $query->result();
    }
    
}

?>
