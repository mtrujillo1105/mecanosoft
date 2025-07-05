<?php
class Lote_Model extends Model
{
    protected $_name = "cji_lote";
    private $_hoy;
    public function  __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->somevar['compania'] = $this->session->userdata('compania');
        $this->_hoy                = mdate("%Y-%m-%d %h:%i:%s",time());
    }
    public function seleccionar($default="")
    {
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array(''=>$nombre_defecto);
        foreach($this->listar() as $indice=>$valor)
        {
            $indice1   = $valor->LOTP_Codigo;
            $valor1    = substr($valor->LOTC_FechaRegistro, 0,10);
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    public function listar($number_items='',$offset='')
    {
        $query = $this->db->order_by('LOTC_FechaRegistro')->where($where)->get('cji_lote');
        if($query->num_rows>0){
           return $query->result();
        }
    }
    public function obtener($id)
    {
        $where = array("LOTP_Codigo"=>$id);
        $query = $this->db->where($where)->get('cji_lote');
        if($query->num_rows>0){
          return $query->row();
        }
    }
    public function insertar(stdClass $filter = null)
    {
        $this->db->insert("cji_lote",(array)$filter);
        return $this->db->insert_id();
    }
    public function modificar($id,$filter)
    {
        $this->db->where("LOTP_Codigo",$id);
        $this->db->update("cji_lote",(array)$filter);
    }
    public function eliminar($id)
    {
        $this->db->delete('cji_lote',array('LOTP_Codigo' => $id));
    }
    public function buscar($filter,$number_items='',$offset='')
    {

    }
    public function aumentar($lote_id,$cantidad)
    {
        $datos_lote = $this->obtener($lote_id);
        $cantidad_inicial = $datos_lote->LOTC_Cantidad;
        $filter3 = new stdClass();
        $filter3->LOTC_Cantidad = $cantidad_inicial+$cantidad;
        $filter3->LOTC_FechaModificacion = $this->_hoy;
        $this->lote_model->modificar($lote_id,$filter3);
    }
    public function disminuir($lote_id,$cantidad)
    {
        $datos_lote = $this->obtener($lote_id);
        $cantidad_inicial = $datos_lote->LOTC_Cantidad;
        $filter3 = new stdClass();
        $filter3->LOTC_Cantidad = $cantidad_inicial-$cantidad;
        $filter3->LOTC_FechaModificacion = $this->_hoy;
        $this->lote_model->modificar($lote_id,$filter3);
    }
}
?>