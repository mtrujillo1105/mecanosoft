<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*Periodo de creación de las OT
 * 10        OT año 2010
 * 12        OT año 2011 
 * 14        OT año 2012
 * Hay qye eliminarlo
 */
class Periodoot_model extends CI_Model{
    var $table;
    var $entidad;
    public function __construct(){
        parent::__construct();
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "tabla_m_detalle";
    }
    
    /*Obtiene el periodo ot en combos*/
   public function seleccionar($default="",$value=''){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar() as $indice=>$valor)
        {
            $indice1   = $valor->cod_argumento;
            $valor1    = $valor->Des_larga;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }       
    
    /*Lista el periodo ot general*/
    public function listar($number_items='',$offset='')
    {
        $where = array('CodEnt'=>$this->entidad,'Cod_Tabla'=>'TORD','Des_corta'=>'OT','cod_argumento>= '=>'14');
        $this->db->select('cod_tabla,cod_argumento,valor_2,Des_larga,Des_corta');
        $this->db->from($this->table,$number_items,$offset);
        $this->db->where($where);
        $this->db->order_by('Des_larga');        
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
    
 }
?>