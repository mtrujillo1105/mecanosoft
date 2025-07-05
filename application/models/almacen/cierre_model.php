<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cierre_model extends CI_Model{
    var $entidad;
    var $table;

    public function __construct(){
        parent::__construct();
        $this->entidad   = $this->session->userdata('entidad');
        $this->table     = "Cierre";
        $this->table_det = "Cierre_Almacen";
    }
    
     public function seleccionar($default="",$value=''){
        $nombre_x_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_x_defecto);
        foreach($this->listar_TipoOt() as $indice=>$valor)
        {
            $indice1   = $valor->Cod_Argumento;
            $valor1    = $valor->Des_Larga;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
     } 
     
     public function listar($filter,$filter_not,$order_by='',$number_items='',$offset=''){
         if($this->entidad=='01'){
             $filtrofecha  = "";
             $filtronumero = "";
             if(isset($filter->fecha) && $filter->fecha!='')   $filtrofecha  = "AND Feccierre=CTOD('".date_dbf($filter->fecha)."')";
             if(isset($filter->numero) && $filter->numero!='') $filtronumero  = "AND Nro='".$filter->numero."'";
             $cadena = "
                     select 
                     *
                     from ".$this->table."
                     where codent = '01'
                     ".$filtrofecha."
                     ".$filtronumero."
             ";
            $query     = $this->dbase->query($cadena);
            $resultado = $query->result();             
         }
         elseif($this->entidad=='02'){
            $arrWhere  = array('c.codent'=>$this->entidad);
            $this->db->select("*");
            $this->db->from($this->table." as c");
            $this->db->where($arrWhere);   
            if(isset($filter->codalmacen) && $filter->codalmacen!='') $this->db->where('c.CodAlm',$filter->codalmacen);
            if(isset($filter->fecha) && $filter->fecha!='')           $this->db->where('c.Feccierre',$filter->fecha);
            $query = $this->db->get();
            $resultado = array();
            if($query->num_rows>1) exit('Existe mas de 1 resultado');    
            if($query->num_rows>0){
                $resultado = $query->row();
            } 
         }
         return $resultado;  
    } 

     public function listar_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
         if($this->entidad=='01'){
             $filtrofecha  = "";
             $filtronumero = "";
             $filtrocodpro = "";
             if(isset($filter->fecha) && $filter->fecha!='')   $filtrofecha  = "AND Feccierre=CTOD('".date_dbf($filter->fecha)."')";  
             if(isset($filter->numero) && $filter->numero!='') $filtronumero = "AND Nro='".$filter->numero."'";  
             if(isset($filter->codproducto) && $filter->codproducto!='') $filtrocodpro = "AND Codpro='".$filter->codproducto."'";  
             $cadena = "
                     select 
                     *
                     from ".$this->table_det."
                     where codent = '01'
                     ".$filtrofecha."
                     ".$filtrocodpro."
             ";
            $query     = $this->dbase->query($cadena);
            $resultado = $query->result();             
         }
         elseif($this->entidad=='02'){
            $arrWhere  = array('d.codent'=>$this->entidad);
            $this->db->select("codalm,codpro,stkanterior,ingresos,salidas,stkactual,precio,precprom as preprom,mo as moneda");
            $this->db->from($this->table_det." as d");
            $this->db->where($arrWhere);   
            if(isset($filter->codalmacen) && $filter->codalmacen!='')   $this->db->where('d.CodAlm',$filter->codalmacen);
            if(isset($filter->fecha) && $filter->fecha!='')             $this->db->where('d.Feccierre',$filter->fecha);
            if(isset($filter->numero) && $filter->numero!='')           $this->db->where('d.Nro',$filter->numero);
            if(isset($filter->codproducto) && $filter->codproducto!='') $this->db->where('d.CodPro',$filter->codproducto);
            $query = $this->db->get();
            $resultado = array();   
            if($query->num_rows>0){
                $resultado = $query->result();
            } 
         }
         return $resultado;  
    }     
    
    public function ultimo($filter,$filter_not){
       if($this->entidad=='01'){
           $filtrofecha  = "";
           if(isset($filter->fecha) && $filter->fecha!='')   $filtrofecha  = "AND feccierre=CTOD('".date_dbf($filter->fecha)."')";
           $cadena = "
                    select
                    feccierre as fecha,
                    *
                    from ".$this->table."
                    where codent = '01'
                    ".$filtrofecha."
                    order by feccierre,nro
                    ";
            $query     = $this->dbase->query($cadena);
            $resultado = $query->last_row();            
         }
         elseif($this->entidad=='02'){
            $arrWhere  = array('c.codent'=>$this->entidad);
            $this->db->select("convert(varchar,c.FecCierre,103) as fecha,c.nro,*");
            $this->db->from($this->table." as c");
            $this->db->where($arrWhere);   
            $this->db->order_by("c.FecCierre,c.nro");   
            $query = $this->db->get();
            $resultado = array();  
            if($query->num_rows>0){
                $resultado = $query->last_row();
            } 
         }
         return $resultado;  
    }
    
    public function primero(){
        
    }
    
    public function obtener($filter,$filter_not){
        $cadena = "  
        select 
        ot.NroOt,
        ot.DirOt,
        ot.CodCli,
        ot.CodOt,
        clientes.razcli,
        ot.Tipo,
        c.Valor_3,
        c.Des_Larga,
        ot.peso,
        OtDetalleVta.Altura,
        ot.Torre,
        view_tipTorre.Des_Larga as descri,
        convert(varchar,ot.FecOt,103) as fecha
        from ot 
        left join OtDetalleVta on OtDetalleVta.CodOt=ot.codot and OtDetalleVta.codent=ot.codent
        inner join view_tipTorre on view_tipTorre.Cod_Argumento=ot.Torre
        inner join clientes on clientes.codent=ot.codent and clientes.codcli=ot.codcli 
        inner join view_tipProducto_Old as c on c.Cod_Argumento = ot.Tipo
        where ot.codent='".$this->entidad."'
        ".$filtrotipoot."
        ".$filtroestado."
        ".$filtrotipotorre."
        ".$filtrocliente."
        and c.Valor_3='02'    
        ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }
}
?>
