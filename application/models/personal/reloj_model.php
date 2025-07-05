<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reloj_model extends CI_Model{
    var $entidad;
    var $table;
    public function __construct()
    {
        parent::__construct();
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "reloj";
    }
	
    public function seleccionarNombres($default="",$value='',$fecha){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar0($fecha) as $indice=>$valor)
        {
            $indice1   = $valor->codres;
            $valor1    = $valor->nomper;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }  
    
    public function ubicarInReloj($dni, $fecha) {
        $where = array("Dni" => $dni, "Fecha" => $fecha);
        $this->db->select('count(*) as cantidad');
        $this->db->from($this->table);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row();
    }
    
    public function get($filter,$filter_not,$order_by=""){ 
        $where = array();
        if(isset($filter->dni) && $filter->dni!='')         $where = array_merge($where,array("Dni"=>$filter->dni));
        if(isset($filter->fecha) && $filter->fecha!='')     $where = array_merge($where,array("Fecha"=>$filter->fecha));
        if(isset($filter->codres) && $filter->codres!='')   $where = array_merge($where,array("Codres"=>$filter->codres));
        $this->scire->select('*');
        $this->scire->from($this->table,$number_items,$offset);
        if(is_array($where) && isset($where))   $this->scire->where($where);
        if(isset($order_by) && count($order_by)>0 && $order_by!=""){
            foreach($order_by as $indice=>$value){
                $this->scire->order_by($indice,$value);
            }
        }        
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>1)   $resultado = $query->result();
        if($query->num_rows==1)  $resultado = $query->row();
        return $resultado;
    }  
    
    public function getTotal($filter,$filter_not){ 
        $where    = array();
        $group_by = array("Dni");
        if(isset($filter->dni) && $filter->dni!='')         $where = array_merge($where,array("Dni"=>$filter->dni));
        if(isset($filter->fecha) && $filter->fecha!='')     $where = array_merge($where,array("Fecha"=>$filter->fecha));
        $this->scire->select('Dni,sum(tardanza),sum(Htrabajadas),sum(Hextra)');
        $this->scire->from($this->table,$number_items,$offset);
        if(is_array($where) && isset($where))   $this->scire->where($where);
        $this->scire->group_by($group_by);
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>1)   $resultado = $query->result();
        if($query->num_rows==1)  $resultado = $query->row();
        return $resultado;
    }      
                    
    public function listar0($fecha){
        $cadena = "
            select
            b.codres,
            b.nomper
            from tareo as a
            inner join responsable as b on (b.codent=a.codent and b.codres=a.codres)
            where a.fecha='".$fecha."'
            and a.codent='".$this->entidad."'
            and item='1'
            order by b.nomper
        ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }    

    public function seleccionarNombresOT($default="",$value='',$codot){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar000($codot) as $indice=>$valor)
        {
            $indice1   = $valor->codres;
            $valor1    = $valor->nomper;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }  
                
    public function listar000($codot){
        $cadena = "
            select
            b.codres,
            b.nomper
            from tareo as a
            left join responsable as b on (b.codent=a.codent and b.codres=a.codres)
            where a.codot='".$codot."'
            and a.codent='".$this->entidad."'
            and item='1'
            order by b.nomper
        ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }    

    public function seleccionar($default="")
    {
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array(''=>$nombre_defecto);
        foreach($this->listar() as $indice=>$valor)
        {
            $indice1   = $valor->CodOt;
            $valor1    = $valor->NroOt;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
	
    public function listar($fecha,$number_items='',$offset='')
    {
        $where = array("flgronda"=>"0","CONVERT(VARCHAR,fecha,112)"=>$fecha);
        $this->db->select('*');
        $this->db->from($this->table,$number_items,$offset);
        $this->db->join("responsable","responsable.codent=$this->table.codent and responsable.codres=$this->table.codres","left");
        $this->db->where($where);	
        $this->db->order_by('responsable.nomper');
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
    
    public function listar2($fecha){
        $cadena = "
                select * from 
                (
                  select 
                  r.dni as dni,
                  res.nomper as nomper,
                  r.hora as hora,
                  r.tipo as tipo 
                  from reloj as r
                  left join responsable as res on (res.codent=r.codent and res.codres=r.codres)
                  where CONVERT(VARCHAR,fecha,112)='".$fecha."'
                  and r.tipo='IT'
                  and flgronda='0'
                  UNION ALL
                  select 
                  r.dni as dni,
                  res.nomper as nomper,
                  r.salida as hora,
                  r.tip2 as tipo
                  from reloj as r
                  left join responsable as res on (res.codent=r.codent and res.codres=r.codres)
                  where CONVERT(VARCHAR,fecha,112)='".$fecha."'
                  and r.tip2='ST'
                  and flgronda='0'
                ) 
                as temp order by temp.hora desc            
            ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }
    
    public function listar3($fecha){//Para tareos de obreros
        $cadena = "
                    select 
                    r.Dni,
                    (select top 1 codres from responsable where codent=r.codent and dniper=r.Dni order by codres desc) as codres,
                    res.nomper,
                    res.flgtreg,
                    r.Hora,
                    r.Salida,
                    r.Htrabajadas,
                    r.Hextra,
                    res.codot,
                    ot.DirOt,
                    r.Estado
                    from reloj as r
                    inner join responsable as res on (res.codent=r.codent and res.codres=r.codres)
                    inner join ot on (ot.CodEnt=res.codent and ot.CodOt=res.codot)
                    where r.codent='".$this->entidad."' 
                    and r.fecha='".$fecha."' 
                    and r.item='1' 
                    and res.TipTrab in('02','04')
                    and r.hora!=''
                    and r.categoria!='00005'
                    and res.codot='0003722'
                    --and res.codres not in ('000991','000037','001256','001264')
                    order by res.nomper
                  ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }
    
    public function obtener($id)
    {
        $where = array("codOt"=>$id);
        $query = $this->db->order_by('nroOt')->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        return $resultado;
    }
    
    public function insertar(stdClass $filter = null)
    {
        $this->db->insert($this->table,(array)$filter);
    }
    
    public function update($dni, $fecha, $filter) {
        $this->db->where(array("Dni" => $dni, "Fecha" => $fecha));
        $this->db->update($this->table,(array)$filter);
    }
    
    public function modificar($id,$filter)
    {
        $this->db->where("CodOt",$id);
        $this->db->update($this->table,(array)$filter);
    }
	
    public function eliminar($id)
    {
        $this->db->delete($this->table,array('codot' => $id));
    }
	
    public function buscar($filter,$number_items='',$offset='')
    {
        $this->db->select('*');
        $this->db->from($this->table,$number_items='',$offset='');
        $this->db->where('CodEnt',$this->entidad);
        $this->db->where_not_in('Estado','A');	
        if(isset($filter->CodEnt) && $filter->CodEnt!="")
            $this->db->like('CodEnt',$filter->CodEnt);
        if(isset($filter->Estado) && $filter->Estado!="")
            $this->db->like('Estado',$filter->Estado);
        $query = $this->db->get();
        if($query->num_rows>0){
            foreach($query->result() as $fila){
                    $data[] = $fila;
            }
            return $data;
        }
    }
}
?>