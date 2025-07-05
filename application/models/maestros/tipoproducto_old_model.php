<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*Modelo Tipo de Producto Old, este se reemplazo por Tipoproducto
 * 001                    FABRICACION Y MONTAJE
 * 002                    FABRICACION
 * 003                    FABRICACION, TRANSPORTE Y MONTAJE
 * 004                    SUMINISTRO DE MATERIALES
 * 005                    DESMONTAJE
 * ......
 */
class Tipoproducto_old_model extends CI_Model{
    var $table;
    var $entidad;
    public function __construct(){
        parent::__construct();
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "view_tipProducto_Old";
    }
    
    /*Combo para hacer un select*/
    public function seleccionar($filter,$default="",$value=''){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar($filter) as $indice=>$valor)
        {
            $indice1   = $valor->cod_argumento;
            $valor1    = $valor->des_larga;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }      
    
    /*Listado general de tipos de producto.*/
    public function listar($filter,$number_items='',$offset='')
    {      
        $where = array("codent"=>$this->entidad);        
        if(isset($filter->Valor_3)) $where = array_merge ($where,array("Valor_3"=>$filter->Valor_3));
        $this->db->select('cod_tabla,cod_argumento,valor_2,des_larga,Des_Corta');
        $this->db->from($this->table,$number_items,$offset);
        $this->db->where($where);
        $this->db->where_not_in('cod_argumento','01');
        $this->db->order_by('des_larga');
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
    
    
         public function listar2($tipOt,$tiproducto,$filtroproyecto,$filtroestado){
        $cadena = "
            select 
            ot.CodOt,
            ot.NroOt,
            ot.DirOt,
            ot.Proyecto,
            ot.MtoPre,
            convert(char,FecOt,103) as fecha,
            ot.ImpOt,
            ot.EstOt,
            dbo.getTipoCambioV(ot.FecOt,'".$codent."') as tcOt,
            convert(char,ot.FteOt,103) as FteOt,
            ot.PESO as peso
            from ot 
            where codent='".$this->entidad."' 
            and tipOt='".$tipOt."' 
            and FecOt>='23/03/2012' 
            and Tipo in (
                select Cod_Argumento from view_tipProducto_Old where codent='".$this->entidad."' and Valor_3='".$tiproducto."'
            )
            ".$filtroproyecto."
            ".$filtroestado."
            order by nroOt
               ";
        $query     = $this->dbase->query($cadena);
        $resultado = $query->result();
        return $resultado;  
    }     
    
    /*Obtiene un tipo de producto, el resultado es único*/
    public function obtener($codigo)
    {
        $where = array("cod_argumento"=>$codigo,"CodEnt"=>$this->entidad);
        $query = $this->db->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        return $resultado;
    }
	
    public function insertar(stdClass $filter = null){
        $this->db->insert($this->table,(array)$filter);
    }
	
    public function modificar($codigo,$filter)
    {
        $where = array("cod_argumento"=>$codigo,"cod_tabla"=>'TORD',"CodEnt"=>$this->entidad);
        $this->db->where($where);
        $this->db->update($this->table,(array)$filter);
    }
	
    public function eliminar($codigo)
    {
        $where = array("cod_argumento"=>$codigo,"cod_tabla"=>'TORD',"CodEnt"=>$this->entidad);        
        $this->db->delete($this->table,array('CodEnt' => $id));
    }
}
?>