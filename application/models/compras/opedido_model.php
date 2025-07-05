<?php
class Pedido_model extends Model{
    var $somevar;
	function __construct()
        {
            parent::__construct();
            $this->load->database();
            $this->load->helper('date');
            $this->load->model('mantenimiento_model');
            $this->somevar ['compania'] = $this->session->userdata('compania');
            $this->somevar ['usuario']    = $this->session->userdata('user');
            $this->somevar['hoy']              = mdate("%Y-%m-%d %h:%i:%s",time());
	}
	function listar_pedidos($number_items='',$offset='')
        {
            $compania = $this->somevar['compania'];
            $where = array("COMPP_Codigo"=>$compania,"PEDIC_FlagEstado"=>"1");
            $query = $this->db->order_by('PEDIC_Numero','desc')->where($where)->get('cji_pedido',$number_items,$offset);
            if($query->num_rows>0){
                foreach($query->result() as $fila){
                    $data[] = $fila;
                }
                return $data;
            }
	}
    function obtener_pedido($pedido){
        $query = $this->db->where('PEDIP_Codigo',$pedido)->get('cji_pedido');
		if($query->num_rows>0){
			foreach($query->result() as $fila){
				$data[] = $fila;
			}
			return $data;
		}
    }
    function obtener_detalle_pedido($pedido){
         $where = array("PEDIP_Codigo"  => $pedido,"PEDIDETC_FlagEstado" => "1");
        $query = $this->db->where($where)->get('cji_pedido');
		if($query->num_rows>0){
			foreach($query->result() as $fila){
				$data[] = $fila;
			}
			return $data;
		}
    }
    function insertar_pedido(){

    }
    function insertar_detalle_pedido(){

    }
    function modificar_pedido(){

    }
    function eliminar_pedido($pedido){
		$data      = array("PEDIC_FlagEstado"=>'0');
		$where = array("PEDIP_Codigo"=>$pedido);
		$this->db->where($where);
		$this->db->update('cji_pedido',$data);
		$data      = array("PEDIDETC_FlagEstado"=>'0');
		$where = array("PEDIP_Codigo"=>$pedido);
		$this->db->where($where);
		$this->db->update('cji_pedidodetalle',$data);
    }
    function eliminar_producto_pedido($detalle_pedido){
		$data      = array("PEDIDETC_FlagEstado"=>'0');
		$where = array("PEDIDETP_Codigo"=>$detalle_pedido);
		$this->db->where($where);
		$this->db->update('cji_pedidodetalle',$data);
    }
}
?>