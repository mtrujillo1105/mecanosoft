<?php
require_once "../database/BD.sqlserver.class.php";
class Tc_model
{
    private $cnx;
    private $oBD;
    
    public function __construct(){
        $this->oBD = new BD();
        $this->cnx = $this->oBD->cnx;	
    }
    
   public function listar(){
        
        
    }    
    
    public function seleccionar(){

    }
    
    public function obtener($entidad,$fecha){
	$arrfFin = explode("/",$fecha);
	$mkfFin  = mktime( 0, 0, 0, $arrfFin[1],$arrfFin[0],$arrfFin[2]); 
	$dia_hoy = date("d",$mkfFin);
	$mes_hoy = date("my",$mkfFin);        
        $cadena = "
                select 
                valor_2 
                from tabla_m_detalle 
                where cod_argumento='".$dia_hoy."' 
                and cod_tabla='".$mes_hoy."' 
                and codent='".$entidad."'
                ";
        $resultado  = $this->oBD->seleccionar($cadena);
        return $resultado;
        
    }      
    
    public function insertar(){
        
    }
    
    public function eliminar(){
        
        
    }
    
}
?>
