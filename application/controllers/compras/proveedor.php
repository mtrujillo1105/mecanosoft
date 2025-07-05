<?php
class Proveedor extends Controller{
    public function __construct()
    {
            parent::__construct();
            $this->load->model('maestros/empresa_model');
            $this->load->model('maestros/persona_model');
            $this->load->model('maestros/directivo_model');
            $this->load->model('maestros/cargo_model');
            $this->load->model('maestros/area_model');
            $this->load->model('maestros/tipoestablecimiento_model');
            $this->load->model('maestros/nacionalidad_model');
            $this->load->model('maestros/tipodocumento_model');
            $this->load->model('maestros/tipocodigo_model');
            $this->load->model('maestros/estadocivil_model');
            $this->load->model('maestros/ubigeo_model');
            $this->load->model('compras/proveedor_model');
            $this->load->helper('json');
            $this->load->library('html');
            $this->load->library('table');
            $this->load->library('layout','layout');
            $this->load->library('pagination');	
	}
	public function index()
	{
		$this->layout->view('seguridad/inicio');
	}	
	public function proveedores($j=0){
            $data['numdoc']       = "";
            $data['nombre']    = "";
            $data['telefono']  = "";
            $data['tipo']      = "";
            $data['titulo_tabla']    = "RELACIÓN DE PROVEEDORES";                
            $data['registros']  = count($this->proveedor_model->listar_proveedor());
            $data['action']          = base_url()."index.php/compras/proveedor/buscar_proveedores";
            $conf['base_url']   = site_url('compras/proveedor/proveedores/');
            $conf['total_rows'] = $data['registros'];
            $conf['per_page']   = 50;
            $conf['num_links']  = 3;
            $conf['next_link'] = "&gt;";
            $conf['prev_link'] = "&lt;";
            $conf['first_link'] = "&lt;&lt;";
            $conf['last_link']  = "&gt;&gt;";
            $conf['uri_segment'] = 4;
            $this->pagination->initialize($conf);
            $data['paginacion'] = $this->pagination->create_links();
            $listado_proveedores = $this->proveedor_model->listar_proveedor($conf['per_page'],$j);
            $item            = $j+1;
            $lista           = array();
			if(count($listado_proveedores)>0){
				foreach($listado_proveedores as $indice=>$valor){
					$codigo         = $valor->PROVP_Codigo;
					$ruc            = $valor->ruc;
                                        $dni            = $valor->dni;
					$razon_social   = $valor->nombre;
					$tipo_proveedor = $valor->PROVC_TipoPersona==1?"P.JURIDICA":"P.NATURAL";
					$telefono       = $valor->telefono;
					$movil          = $valor->movil;
					$editar         = "<a href='#' onclick='editar_proveedor(".$codigo.")'><img src='".base_url()."images/modificar.png' width='16' height='16' border='0' title='Modificar'></a>";
					$ver            = "<a href='#' onclick='ver_proveedor(".$codigo.")'><img src='".base_url()."images/ver.png' width='16' height='16' border='0' title='Modificar'></a>";
					$eliminar       = "<a href='#' onclick='eliminar_proveedor(".$codigo.")'><img src='".base_url()."images/eliminar.png' width='16' height='16' border='0' title='Modificar'></a>";
					$lista[]        = array($item,$ruc,$dni,$razon_social,$tipo_proveedor,$telefono,$movil,$editar,$ver,$eliminar);
					$item++;
				}
			}
            $data['lista'] = $lista;
            $this->layout->view("compras/proveedor_index",$data);
	}
	public function nuevo_proveedor(){
		$data['cbo_dpto']         = $this->seleccionar_departamento('15');	
		$data['cbo_prov']         = $this->seleccionar_provincia('15','01');
		$data['cbo_dist']         = $this->seleccionar_distritos('15','01');
		$data['cbo_estadoCivil']  = $this->seleccionar_estadoCivil('');
		$data['cbo_nacionalidad'] = $this->seleccionar_nacionalidad('193');
		$data['cbo_nacimiento']   = $this->seleccionar_distritos('15','01','01');
                $data['tipocodigo']           = $this->seleccionar_tipocodigo('1');
		$data['display']              = "";
		$data['display_datosEmpresa'] = "";
		$data['display_datosPersona'] = "display:none;";
		$data['nombres']			  = "";
		$data['paterno']			  = "";
		$data['materno']			  = "";
		$data['numero_documento']     = "";
		$data['ruc']			      = "";
		$data['sexo']			      = "";
		$data['tipo_documento']	      = $this->seleccionar_tipodocumento('1');
		$data['tipo_persona']	      = "1";
		$data['id']			  = "";
		$data['modo']				  = "insertar";
		$objeto = new stdClass();
		$objeto->id      = "";
                $objeto->tipo      = "";
                $objeto->ruc      = "";
		$objeto->nombre   = "";
		$objeto->telefono = "";
		$objeto->movil    = "";
		$objeto->fax      = "";
		$objeto->web      = "";
		$objeto->email    = "";
		$objeto->direccion="";
		$data['datos'] = $objeto; 
		$data['titulo'] = "REGISTRAR PROVEEDOR";
		$data['listado_empresaSucursal']  = array();
		$data['listado_empresaContactos'] = array();
		$data['cboNacimiento'] = "000000";
		$data['cboNacimientovalue'] = "";
		$this->load->view("compras/proveedor_nuevo",$data);
	}
  public function insertar_proveedor(){
		$nombre_sucursal = array();
		$nombre_contacto = array();
                $empresa_persona = $this->input->post('empresa_persona');
		$tipo_persona    = $this->input->post('tipo_persona');
		$tipocodigo      = $this->input->post('cboTipoCodigo');
                $ruc             = $this->input->post('ruc');
		$razon_social    = $this->input->post('razon_social');	
		$telefono        = $this->input->post('telefono');
		$movil           = $this->input->post('movil');
		$fax             = $this->input->post('fax');
		$email           = $this->input->post('email');
		$web             = $this->input->post('web');
		$direccion       = $this->input->post('direccion');
		$departamento    = $this->input->post('cboDepartamento');
		$provincia       = $this->input->post('cboProvincia');
		$distrito        = $this->input->post('cboDistrito');	
		$nombres         = $this->input->post('nombres');	
		$paterno         = $this->input->post('paterno');	
		$materno         = $this->input->post('materno');	
		$tipo_documento    = $this->input->post('tipo_documento');	
		$numero_documento  = $this->input->post('numero_documento');
		$nacimiento        = $this->input->post('cboNacimiento');
		$ubigeo_nacimiento = $this->input->post('cboNacimiento')==''?'000000':$this->input->post('cboNacimiento');
		$sexo             = $this->input->post('cboSexo');
		$estado_civil     = $this->input->post('cboEstadoCivil');
		$nacionalidad     = $this->input->post('cboNacionalidad');
		$ruc_persona      = $this->input->post('ruc_persona');
		$ubigeo_domicilio = $departamento.$provincia.$distrito;
		
		/*Array de variables*/
		$nombre_sucursal      = $this->input->post('nombreSucursal');
		$direccion_sucursal   = $this->input->post('direccionSucursal');
		$tipo_establecimiento = $this->input->post('tipoEstablecimiento');		
		$arrayDpto            = $this->input->post('dptoSucursal');
		$arrayProv            = $this->input->post('provSucursal');
		$arrayDist            = $this->input->post('distSucursal');
		$persona_contacto     = $this->input->post('contactoPersona');
		$nombre_contacto      = $this->input->post('contactoNombre');
		$area_contacto        = $this->input->post('contactoArea');
		$cargo_contacto       = $this->input->post('cargo_encargado');
		$telefono_contacto    = $this->input->post('contactoTelefono');
		$email_contacto       = $this->input->post('contactoEmail');
		$tipo_contacto        = $this->input->post('contactoTipo');
		$nombre_area          = $this->input->post('nombre_area');
		$web				  = $this->input->post('web');
		if($arrayDpto!='' && $arrayProv!='' && $arrayDist!=''){
			$ubigeo_sucursal  = $this->html->array_ubigeo($arrayDpto,$arrayProv,$arrayDist);
		}
		if($tipo_persona==1){//Empresa
			$persona = 0;
                        if($empresa_persona!='' && $empresa_persona!='0'){
                            $empresa=$empresa_persona;
                            $this->empresa_model->modificar_datosEmpresa($empresa,$tipocodigo, $ruc,$razon_social,$telefono,$movil,$fax,$web,$email);                           
                        }
                        else
                            $empresa = $this->empresa_model->insertar_datosEmpresa($tipocodigo, $ruc,$razon_social,$telefono,$fax,$web,$movil,$email);
                        
			$this->empresa_model->insertar_sucursalEmpresaPrincipal('1',$empresa,$ubigeo_domicilio,'PRINCIPAL',$direccion);//Direccion Principal
			$this->proveedor_model->insertar_datosProveedor($empresa,$persona,$tipo_persona);
			//Insertar Establecimientos
    		if($nombre_sucursal!=''){
				foreach($nombre_sucursal as $indice=>$valor){
					if($nombre_sucursal[$indice]!='' && $direccion_sucursal!='' && $tipo_establecimiento[$indice]!=''){
						$ubigeo_s = strlen($ubigeo_sucursal[$indice])<6?"000000":$ubigeo_sucursal[$indice];
						$this->empresa_model->insertar_sucursalEmpresa($tipo_establecimiento[$indice],$empresa,$ubigeo_s,$nombre_sucursal[$indice],$direccion_sucursal[$indice]);
					}
				}
			} 
			//Insertar contactos empresa
			if($nombre_contacto!=''){
				foreach($nombre_contacto as $indice=>$valor){
					if($nombre_contacto[$indice]!=''){
						$pers_contacto = $persona_contacto[$indice];
						$nom_contacto  = $nombre_contacto[$indice];
						$car_contacto  = $cargo_contacto[$indice];
						$ar_contacto   = $area_contacto[$indice];
						$arrTelConctacto = explode("/",$telefono_contacto[$indice]);
						switch(count($arrTelConctacto)){
							case 2:
								$tel_contacto  = $arrTelConctacto[0];
								$mov_contacto  = $arrTelConctacto[1];	
								break;
							case 1:
								$tel_contacto  = $arrTelConctacto[0];
								$mov_contacto  = "";	
								break;
							case 0:
								$tel_contacto  = "";
								$mov_contacto  = "";	
								break;							
						}	
						$e_contacto    = $email_contacto[$indice];
						if($pers_contacto==''){$pers_contacto = $this->persona_model->insertar_datosPersona('000000','000000','1','193',$nom_contacto,'','','','1');}//Inserto persona
						$directivo = $this->empresa_model->insertar_directivoEmpresa($empresa,$pers_contacto,$car_contacto);
						$this->empresa_model->insertar_areaEmpresa($ar_contacto,$empresa,$directivo,'::OBSERVACION::');
						$this->empresa_model->insertar_contactoEmpresa($empresa,'::OBSERVACION:',$tel_contacto,$mov_contacto,$e_contacto,$pers_contacto);			
					}
				}
			}
		}
		elseif($tipo_persona==0){//Persona
			$empresa = 0;                       
			if($empresa_persona!='' && $empresa_persona!='0'){
                            $persona=$empresa_persona;
                            $this->persona_model->modificar_datosPersona($persona,$ubigeo_nacimiento,$ubigeo_domicilio,$estado_civil,$nacionalidad,$nombres,$paterno,$materno,$ruc_persona,$tipo_documento,$numero_documento,$direccion,$telefono,$movil,$email,$domicilio,$sexo,$fax,$web);
                        }                            
                        else
                            $persona = $this->persona_model->insertar_datosPersona($ubigeo_nacimiento,$ubigeo_domicilio,$estado_civil,$nacionalidad,$nombres,$paterno,$materno,$ruc_persona,$tipo_documento,$numero_documento,$direccion,$telefono,$movil,$email,$direccion,$sexo,$web);
			
                        $this->proveedor_model->insertar_datosProveedor($empresa,$persona,$tipo_persona);
		}
	}
	public function editar_proveedor($id){
		$datos           = $this->proveedor_model->obtener_datosProveedor($id);
		$tipo_persona    = $datos[0]->PROVC_TipoPersona;
		$persona         = $datos[0]->PERSP_Codigo;
		$empresa         = $datos[0]->EMPRP_Codigo;
		$data['display'] = "style='display:none;'";	
		$data['modo']	 = "modificar";
		$data['tipo_persona'] = $tipo_persona;
		$data['id']		= $id;			
		if($tipo_persona==0){//Persona
			$datos_persona             = $this->persona_model->obtener_datosPersona($persona);		
                        $ubigeo_domicilio          = $datos_persona[0]->UBIGP_Domicilio;
			$ubigeo_nacimiento         = $datos_persona[0]->UBIGP_LugarNacimiento;
			$nacionalidad			   = $datos_persona[0]->NACP_Nacionalidad;
			$estado_civil			   = $datos_persona[0]->ESTCP_EstadoCivil;
			$dpto_domicilio            = substr($ubigeo_domicilio,0,2);
			$prov_domicilio            = substr($ubigeo_domicilio,2,2);
			$dist_domicilio            = substr($ubigeo_domicilio,4,2);	
			$dpto_nacimiento           = substr($ubigeo_nacimiento,0,2);
			$prov_nacimiento           = substr($ubigeo_nacimiento,2,2);
			$dist_nacimiento           = substr($ubigeo_nacimiento,4,2);		
                        $data['nombres']           = $datos_persona[0]->PERSC_Nombre;
			$data['paterno']           = $datos_persona[0]->PERSC_ApellidoPaterno;
			$data['materno']           = $datos_persona[0]->PERSC_ApellidoMaterno;
			$data['tipo_documento']    = $this->seleccionar_tipodocumento($datos_persona[0]->PERSC_TipoDocIdentidad);
			$data['numero_documento']  = $datos_persona[0]->PERSC_NumeroDocIdentidad;		
                                             
			$data['ruc']               = $datos_persona[0]->PERSC_Ruc;
			$data['sexo']		   = $datos_persona[0]->PERSC_Sexo;
			$data['cbo_estadoCivil']   = $this->seleccionar_estadoCivil($estado_civil);
			$data['cbo_nacionalidad']  = $this->seleccionar_nacionalidad($nacionalidad);
			$data['cboNacimiento']     = $ubigeo_nacimiento;
			$nombre_persona            = $datos_persona[0]->PERSC_ApellidoPaterno." ".$datos_persona[0]->PERSC_ApellidoMaterno." ".$datos_persona[0]->PERSC_Nombre;
			$datos_nacimiento          = $this->ubigeo_model->obtener_ubigeo($ubigeo_nacimiento);
			$data['cboNacimientovalue'] = $ubigeo_nacimiento=='000000'?'':$datos_nacimiento[0]->UBIGC_Descripcion;
			$data['cbo_dpto']         = $this->seleccionar_departamento($dpto_domicilio);	
			$data['cbo_prov']         = $this->seleccionar_provincia($dpto_domicilio,$prov_domicilio);
			$data['cbo_dist']         = $this->seleccionar_distritos($dpto_domicilio,$prov_domicilio,$dist_domicilio);	
			$data['direccion']	  = $datos_persona[0]->PERSC_Direccion;
                        /*Mejorar esto*/
                        $objeto            = new stdClass();
                        $objeto->id        = $datos_persona[0]->PERSP_Codigo;
                        $objeto->persona   = $datos_persona[0]->PERSP_Codigo;
                        $objeto->empresa   = 0;
                        $objeto->nombre    = $datos_persona[0]->PERSC_ApellidoPaterno." ".$datos_persona[0]->PERSC_ApellidoMaterno." ".$datos_persona[0]->PERSC_Nombre;
                        $objeto->ruc       = $datos_persona[0]->PERSC_Ruc;
                        $objeto->telefono  = $datos_persona[0]->PERSC_Telefono;
                        $objeto->fax       = $datos_persona[0]->PERSC_Fax;
                        $objeto->movil     = $datos_persona[0]->PERSC_Movil;
                        $objeto->web       = $datos_persona[0]->PERSC_Web;
                        $objeto->direccion = $datos_persona[0]->PERSC_Direccion;
                        $objeto->email     = $datos_persona[0]->PERSC_Email;
                        $objeto->dni       = $datos_persona[0]->PERSC_NumeroDocIdentidad;
                        $objeto->tipo      = "0";
                        $data['datos']    = $objeto;
                        /**/
			$data['display_datosEmpresa'] = "display:none;";
			$data['display_datosPersona'] = "";	
			$data['titulo']  = "EDITAR PROVEEDOR ::: ".$nombre_persona;			
		}
		elseif($tipo_persona==1){
			$datos_empresa           = $this->empresa_model->obtener_datosEmpresa($empresa);		
			$razon_social            = $datos_empresa[0]->EMPRC_RazonSocial;
			$datos       = $this->empresa_model->obtener_datosEmpresa($empresa);
                        /**/
                        $datos_empresaSucursal = $this->empresa_model->obtener_establecimientoEmpresa($empresa,'1');
                        if(count($datos_empresaSucursal)>0){
                            $direccion = $datos_empresaSucursal[0]->EESTAC_Direccion;
                        }
                        else{
                            $direccion = "";
                        }
                        $id=$datos_empresa[0]->EMPRP_Codigo;
                        $tipocodigo=$datos_empresa[0]->TIPCOD_Codigo;
                        
                        $objeto                = new stdClass();
                        $objeto->id            = $datos[0]->EMPRP_Codigo;
                        $objeto->persona       = 0;
                        $objeto->empresa       = $datos[0]->EMPRP_Codigo;
                        $objeto->nombre        = $datos[0]->EMPRC_RazonSocial;
                        $objeto->ruc           = $datos[0]->EMPRC_Ruc;
                        $objeto->telefono      = $datos[0]->EMPRC_Telefono;
                        $objeto->fax           = $datos[0]->EMPRC_Fax;
                        $objeto->movil         = $datos[0]->EMPRC_Movil;
                        $objeto->web           = $datos[0]->EMPRC_Web;
                        $objeto->direccion     = $direccion;
                        $objeto->email         = $datos[0]->EMPRC_Email;
                        $objeto->tipo          = "1";
                        $objeto->dni           = "";
                        $data['datos']    = $objeto;
                        /*Mejorar esto*/
			$datos_empresaSucursal	      = $this->empresa_model->obtener_establecimientoEmpresa($empresa,'1');
			$listado_empresaSucursal      = $this->listar_sucursalesEmpresa($empresa);
			$listado_empresaContactos     = $this->listar_contactosEmpresa($empresa);
			if(count($datos_empresaSucursal)>0){
				$ubigeo_domicilio         = $datos_empresaSucursal[0]->UBIGP_Codigo;
				$dpto_domicilio           = substr($ubigeo_domicilio,0,2);
				$prov_domicilio           = substr($ubigeo_domicilio,2,2);
				$dist_domicilio           = substr($ubigeo_domicilio,4,2);	
				
			}
			else{
				$dpto_domicilio           = "15";
				$prov_domicilio           = "01";
				$dist_domicilio           = "";				
			}
			$data['listado_empresaContactos'] = $listado_empresaContactos;
			$data['listado_empresaSucursal']  = $listado_empresaSucursal;
			$data['cbo_dpto']                 = $this->seleccionar_departamento($dpto_domicilio);	
			$data['cbo_prov']                 = $this->seleccionar_provincia($dpto_domicilio,$prov_domicilio);
			$data['cbo_dist']                 = $this->seleccionar_distritos($dpto_domicilio,$prov_domicilio,$dist_domicilio);				
			//$data['direccion']			  = $direccion_domicilio;
			$data['display_datosEmpresa']     = "";
			$data['display_datosPersona']     = "display:none;";	
			$data['nombres']		  = "";
			$data['paterno']		  = "";
			$data['materno']		  = "";	
                        $data['tipocodigo']               = $this->seleccionar_tipocodigo($tipocodigo);
			$data['ruc']			  = "";
			$data['numero_documento']	  = "";	
			$data['sexo']			  = "0";
			$data['tipo_documento']	          = $this->seleccionar_tipodocumento('1');
			$data['cbo_nacionalidad']         = $this->seleccionar_nacionalidad('193');		
			$data['titulo']                   = "EDITAR PROVEEDOR ::: ".$razon_social;						
		}
		$this->load->view("compras/proveedor_nuevo",$data);
	}
	public function modificar_proveedor(){
		$id         = $this->input->post('id');	
		$datos   = $this->proveedor_model->obtener_datosProveedor($id);
		$empresa           = $datos[0]->EMPRP_Codigo;
		$persona           = $datos[0]->PERSP_Codigo;
		$tipo_persona      = $datos[0]->PROVC_TipoPersona;
		$tipocodigo       = $this->input->post('cboTipoCodigo');
                $ruc               = $this->input->post('ruc');	
		$razon_social      = $this->input->post('razon_social');	
		$telefono          = $this->input->post('telefono');	
		$movil             = $this->input->post('movil');	
		$fax               = $this->input->post('fax');	
		$email             = $this->input->post('email');	
		$web               = $this->input->post('web');
		$ubigeo_nacimiento = $this->input->post('cboNacimiento');
		$ubigeo_domicilio  = $this->input->post('cboDepartamento').$this->input->post('cboProvincia').$this->input->post('cboDistrito');;
		$domicilio         = $this->input->post('direccion');	
		$estado_civil      = $this->input->post('cboEstadoCivil');	
		$nacionalidad      = $this->input->post('cboNacionalidad');	
		$nombres           = $this->input->post('nombres');	
		$paterno           = $this->input->post('paterno');	
		$materno           = $this->input->post('materno');	
		$ruc_persona       = $this->input->post('ruc_persona');	
		$tipo_documento    = $this->input->post('tipo_documento');	
		$numero_documento  = $this->input->post('numero_documento');	
		$direccion         = $this->input->post('direccion');	
		$sexo              = $this->input->post('cboSexo');	
		if($tipo_persona==0){
			$this->persona_model->modificar_datosPersona($persona,$ubigeo_nacimiento,$ubigeo_domicilio,$estado_civil,$nacionalidad,$nombres,$paterno,$materno,$ruc_persona,$tipo_documento,$numero_documento,$direccion,$telefono,$movil,$email,$domicilio,$sexo,$fax,$web);		
		}
		elseif($tipo_persona==1){
			$this->empresa_model->modificar_datosEmpresa($empresa,$tipocodigo, $ruc,$razon_social,$telefono,$movil,$fax,$web,$email);
			$this->empresa_model->modificar_sucursalEmpresaPrincipal($empresa,'1',$ubigeo_domicilio,'PRINCIPAL',$direccion);
			//Modificar contactows empresa
		}
	}
	public function eliminar_proveedor(){
		$proveedor = $this->input->post('proveedor');	
		/*$datos_proveedor = $this->proveedor_model->obtener_datosProveedor($proveedor);
		$tipo_proveedor  = $datos_proveedor[0]->PROVC_TipoPersona;
		$persona         = $datos_proveedor[0]->PERSP_Codigo;
		$empresa         = $datos_proveedor[0]->EMPRP_Codigo;
		if($tipo_proveedor=='0'){//Persona
			$this->persona_model->eliminar_persona($persona);
		}
		elseif($tipo_proveedor=='1'){
			$this->empresa_model->eliminar_empresa_total($empresa);
		}*/
		$this->proveedor_model->eliminar_proveedor($proveedor);
	}
	public function buscar_proveedores($j='0'){
		$numdoc        = $this->input->post('txtNumDoc');
                $nombre        = $this->input->post('txtNombre');
                $telefono      = $this->input->post('txtTelefono');
                $tipo          = $this->input->post('cboTipoProveedor');
                
                $filter = new stdClass();
                $filter->numdoc   = $numdoc;
                $filter->nombre   = $nombre;
                $filter->telefono = $telefono;
                $filter->tipo     = $tipo;
                
                $data['numdoc']    = $numdoc;
                $data['nombre']    = $nombre;
                $data['telefono']  = $telefono;
                $data['tipo']      = $tipo;
                $data['titulo_tabla']    = "RESULTADO DE BÚSQUEDA DE PROVEEDORES";
    
                $data['registros']  = count($this->proveedor_model->buscar_proveedor($filter));
                $conf['base_url']   = site_url('compras/proveedor/buscar_proveedores/');
                $data['action']     = base_url()."index.php/compras/proveedor/buscar_proveedores";
                $conf['total_rows'] = $data['registros'];
                $conf['per_page']   = 50;
                $conf['num_links']  = 3;
                $conf['next_link'] = "&gt;";
                $conf['prev_link'] = "&lt;";
                $conf['first_link'] = "&lt;&lt;";
                $conf['last_link']  = "&gt;&gt;";
                $conf['uri_segment'] = 4;
                $this->pagination->initialize($conf);
                $data['paginacion'] = $this->pagination->create_links();
                $listado_proveedores = $this->proveedor_model->buscar_proveedor($filter, $conf['per_page'],$j);
                $item            = $j+1;
                $lista           = array();
                            if(count($listado_proveedores)>0){
                                    foreach($listado_proveedores as $indice=>$valor){
                                            $codigo         = $valor->PROVP_Codigo;
                                            $ruc            = $valor->ruc;
                                            $dni            = $valor->dni;
                                            $razon_social   = $valor->nombre;
                                            $tipo_proveedor = $valor->PROVC_TipoPersona==1?"P.JURIDICA":"P.NATURAL";
                                            $telefono       = $valor->telefono;
                                            $movil          = $valor->movil;
                                            $editar         = "<a href='#' onclick='editar_proveedor(".$codigo.")'><img src='".base_url()."images/modificar.png' width='16' height='16' border='0' title='Modificar'></a>";
                                            $ver            = "<a href='#' onclick='ver_proveedor(".$codigo.")'><img src='".base_url()."images/ver.png' width='16' height='16' border='0' title='Modificar'></a>";
                                            $eliminar       = "<a href='#' onclick='eliminar_proveedor(".$codigo.")'><img src='".base_url()."images/eliminar.png' width='16' height='16' border='0' title='Modificar'></a>";
                                            $lista[]        = array($item,$ruc,$dni,$razon_social,$tipo_proveedor,$telefono,$movil,$editar,$ver,$eliminar);
                                            $item++;
                                    }
                            }
                $data['lista'] = $lista;
                $this->layout->view("compras/proveedor_index",$data);
	
	}

    public function ventana_busqueda_proveedor($j=0){
        $data['registros']  = count($this->proveedor_model->listar_proveedor());
        $conf['base_url']   = site_url('compras/proveedor/ventana_busqueda_proveedor/');
        $conf['total_rows'] = $data['registros'];
        $conf['per_page']   = 50;
        $conf['num_links']  = 3;
        $conf['next_link'] = "&gt;";
        $conf['prev_link'] = "&lt;";
        $conf['first_link'] = "&lt;&lt;";
        $conf['last_link']  = "&gt;&gt;";
        $conf['uri_segment'] = 4;
        $this->pagination->initialize($conf);
        $data['paginacion'] = $this->pagination->create_links();
        $listado_proveedores = $this->proveedor_model->listar_proveedor($conf['per_page'],$j);
        $item            = $j+1;
        $lista           = array();
        foreach($listado_proveedores as $indice=>$valor){
                $codigo         = $valor->PROVP_Codigo;
                $ruc            = $valor->PROVC_TipoPersona==1?$valor->ruc:$valor->ruc;
                $razon_social   = $valor->nombre;
                $tipo_proveedor = $valor->PROVC_TipoPersona==1?"P.JURIDICA":"P.NATURAL";
                $telefono       = $valor->telefono;
                $movil             = $valor->movil;
                $seleccionar  = "<a href='#' onclick='editar_proveedor(".$codigo.")' target='_parent'><img src='".base_url()."images/convertir.png' width='16' height='16' border='0' title='Modificar'></a>";
                $lista[]        = array($item,$ruc,$razon_social,$tipo_proveedor,$seleccionar,$codigo);
                $item++;
        }
        $data['lista'] = $lista;
        $this->load->view('compras/proveedor_ventana_buqueda',$data);
    }
    public function obtener_nombre_proveedor($numdoc)
    {
        $datos_empresa  = $this->empresa_model->obtener_datosEmpresa2($numdoc);
        $datos_persona  = $this->persona_model->obtener_datosPersona2($numdoc);	
        $resultado = '[{"PROVP_Codigo":"0","EMPRC_Ruc":"","EMPRC_RazonSocial":""}]';
        if(count($datos_empresa)>0){
            $empresa        = $datos_empresa[0]->EMPRP_Codigo;
            $razon_social   = $datos_empresa[0]->EMPRC_RazonSocial;
            $datosProveedor = $this->proveedor_model->obtener_datosProveedor2($empresa);
	    if(count($datosProveedor)>0){
		$proveedor      = $datosProveedor[0]->PROVP_Codigo;
		$resultado      = '[{"PROVP_Codigo":"'.$proveedor.'","EMPRC_Ruc":"'.$numdoc.'","EMPRC_RazonSocial":"'.$razon_social.'"}]';
	    }           
        }
        elseif(count($datos_persona)>0){
            $persona        = $datos_persona[0]->PERSP_Codigo;
            $nombres        = $datos_persona[0]->PERSC_Nombre." ".$datos_persona[0]->PERSC_ApellidoPaterno." ".$datos_persona[0]->PERSC_ApellidoMaterno;
            $datosProveedor = $this->proveedor_model->obtener_datosProveedor3($persona);
            if(count($datosProveedor)>0){
	        $proveedor      = $datosProveedor[0]->PROVP_Codigo;
                $resultado      = '[{"PROVP_Codigo":"'.$proveedor.'","EMPRC_Ruc":"'.$numdoc.'","EMPRC_RazonSocial":"'.$nombres.'"}]';
	    }
        }
        echo $resultado;
    }
    public function obtener_proveedor($proveedor)
    {
        $datos_proveedor = $this->proveedor_model->obtener_datosProveedor($proveedor);
        $tipo_proveedor  = $datos_proveedor[0]->PROVC_TipoPersona;
        $persona         = $datos_proveedor[0]->PERSP_Codigo;
        $empresa         = $datos_proveedor[0]->EMPRP_Codigo;
        $resultado       = array();
        if($tipo_proveedor==1){
            $datos_empresa         = $this->empresa_model->obtener_datosEmpresa($empresa);
            $datos_empresaSucursal = $this->empresa_model->obtener_establecimientoEmpresa($empresa,'1');
            if(count($datos_empresaSucursal)>0)
                $direccion = $datos_empresaSucursal[0]->EESTAC_Direccion;
            else
                $direccion = "";
            $resultado['proveedor']     = $proveedor;
            $resultado['nombre']        = $datos_empresa[0]->EMPRC_RazonSocial;
            $resultado['ruc']           = $datos_empresa[0]->EMPRC_Ruc;
            $resultado['direccion']     = $direccion;
            $resultado['distrito']      = "";
        }
        else if($tipo_proveedor==0){
            $datos_persona        = $this->persona_model->obtener_datosPersona($persona);
            $resultado['proveedor']     = $proveedor;
            $resultado['nombre']        = $datos_persona[0]->PERSC_Nombre." ".$datos_persona[0]->PERSC_ApellidoPaterno." ".$datos_persona[0]->PERSC_ApellidoMaterno;
            $resultado['ruc']           = $datos_persona[0]->PERSC_Ruc;
            $resultado['direccion']	= $datos_persona[0]->PERSC_Direccion;
            $resultado['distrito']      = "";
        }
        echo json_encode($resultado);
    }
    public function ver_proveedor($proveedor)
    {
        $datos_proveedor = $this->proveedor_model->obtener_datosProveedor($proveedor);
        $persona         = $datos_proveedor[0]->PERSP_Codigo;
        $empresa         = $datos_proveedor[0]->EMPRP_Codigo;
        $tipo_proveedor  = $datos_proveedor[0]->PROVC_TipoPersona;
        if($tipo_proveedor==0){
            $datos                = $this->persona_model->obtener_datosPersona($persona);
            $tipo_doc             = $datos[0]->PERSC_TipoDocIdentidad;
            $estado_civil         = $datos[0]->ESTCP_EstadoCivil;
            $nacionalidad         = $datos[0]->NACP_Nacionalidad;
            $nacimiento           = $datos[0]->UBIGP_LugarNacimiento;
            $sexo                 = $datos[0]->PERSC_Sexo;
            $ubigeo_domicilio     = $datos[0]->UBIGP_Domicilio;
            $datos_nacionalidad   = $this->nacionalidad_model->obtener_nacionalidad($nacionalidad);
            $datos_nacimiento     = $this->ubigeo_model->obtener_ubigeo($nacimiento);
            $datos_ubigeoDom_dpto = $this->ubigeo_model->obtener_ubigeo_dpto($ubigeo_domicilio);
            $datos_ubigeoDom_prov = $this->ubigeo_model->obtener_ubigeo_prov($ubigeo_domicilio);
            $datos_ubigeoDom_dist = $this->ubigeo_model->obtener_ubigeo($ubigeo_domicilio);
            $datos_doc            = $this->tipodocumento_model->obtener_tipoDocumento($tipo_doc);
            $datos_estado_civil   = $this->estadocivil_model->obtener_estadoCivil($estado_civil);
            $data['nacionalidad'] = $datos_nacionalidad[0]->NACC_Descripcion;
            $data['nacimiento']   = $datos_nacimiento[0]->UBIGC_Descripcion;
            $data['tipo_doc']     = $datos_doc[0]->TIPOCC_Inciales;
            $data['estado_civil'] = $datos_estado_civil[0]->ESTCC_Descripcion;
            $data['sexo']         = $sexo==0?'MASCULINO':'FEMENINO';
            $data['telefono']     = $datos[0]->PERSC_Telefono;
            $data['movil']        = $datos[0]->PERSC_Movil;
            $data['fax']          = $datos[0]->PERSC_Fax;
            $data['email']        = $datos[0]->PERSC_Email;
            $data['web']          = $datos[0]->PERSC_Web;
            $data['direccion']    = $datos[0]->PERSC_Direccion;
            $data['dpto']         = $datos_ubigeoDom_dpto[0]->UBIGC_Descripcion;
            $data['prov']         = $datos_ubigeoDom_prov[0]->UBIGC_Descripcion;
            $data['dist']         = $datos_ubigeoDom_dist[0]->UBIGC_Descripcion;
        }
        elseif($tipo_proveedor==1){
            $datos                = $this->empresa_model->obtener_datosEmpresa($empresa);
            $datos_sucurPrincipal = $this->empresa_model->obtener_establecimientosEmpresa_principal($empresa);
            $ubigeo_domicilio     = $datos_sucurPrincipal[0]->UBIGP_Codigo;
            $datos_ubigeoDom_dpto = $this->ubigeo_model->obtener_ubigeo_dpto($ubigeo_domicilio);
            $data['dpto']         = $datos_ubigeoDom_dpto[0]->UBIGC_Descripcion;
            $data['prov']         = $datos_ubigeoDom_dpto[0]->UBIGC_Descripcion;
            $data['dist']         = $datos_ubigeoDom_dpto[0]->UBIGC_Descripcion;
            $data['direccion']    = $datos_sucurPrincipal[0]->EESTAC_Direccion;
            $data['telefono']     = $datos[0]->EMPRC_Telefono;
            $data['movil']        = $datos[0]->EMPRC_Movil;
            $data['fax']          = $datos[0]->EMPRC_Fax;
            $data['email']        = $datos[0]->EMPRC_Email;
            $data['web']          = $datos[0]->EMPRC_Web;
        }
        $data['datos']  = $datos;
        $data['titulo'] = "VER PROVEEDOR";
        $data['tipo']   = $tipo_proveedor;
        $this->load->view('compras/proveedor_ver',$data);
    }
    public function obtener_datosEmpresa_array($datos_empresa){
         $resultado = array();
         foreach($datos_empresa as $indice=>$valor){
              $objeto                = new stdClass();
             $empresa               = $datos_empresa[$indice]->EMPRP_Codigo;
             $datos_empresaSucursal = $this->empresa_model->obtener_establecimientoEmpresa($empresa,'1');
             if(count($datos_empresaSucursal)>0){
                 $direccion = $datos_empresaSucursal[0]->EESTAC_Direccion;
             }
             else{
                 $direccion = "";
             }
             $objeto->id                = $datos_empresa[$indice]->EMPRP_Codigo;
             $objeto->persona    = 0;
             $objeto->empresa   = $datos_empresa[$indice]->EMPRP_Codigo;
             $objeto->nombre     = $datos_empresa[$indice]->EMPRC_RazonSocial;
             $objeto->ruc               = $datos_empresa[$indice]->EMPRC_Ruc;
             $objeto->telefono   = $datos_empresa[$indice]->EMPRC_Telefono;
             $objeto->fax               = $datos_empresa[$indice]->EMPRC_Fax;
             $objeto->movil          = $datos_empresa[$indice]->EMPRC_Movil;
             $objeto->web             = $datos_empresa[$indice]->EMPRC_Web;
             $objeto->direccion   = $direccion;
             $objeto->email          = $datos_empresa[$indice]->EMPRC_Email;
             $objeto->tipo              = "1";
             $objeto->dni                = "";
             $resultado[$indice]  = $objeto;
         }
         return $resultado;
    }
	public function listar_sucursalesEmpresa($empresa){
		$listado_sucursalesEmpresa = $this->empresa_model->listar_sucursalesEmpresa($empresa);
		$resultado = array();
		if(count($listado_sucursalesEmpresa)>0){
			foreach($listado_sucursalesEmpresa as $indice=>$valor){
				$tipo            = $valor->TESTP_Codigo;
				$ubigeo          = $valor->UBIGP_Codigo;
				$datos_tipoEstab = $this->tipoestablecimiento_model->obtener_tipoEstablecimiento($tipo);
				$nombre_tipo     = $datos_tipoEstab[0]->TESTC_Descripcion;
				$datos_ubigeo    = $this->ubigeo_model->obtener_ubigeo($ubigeo);
				$nombre_ubigeo   = $datos_ubigeo[0]->UBIGC_Descripcion;
				$objeto = new stdClass();
				$objeto->tipo        = $valor->TESTP_Codigo;
				$objeto->nombre_tipo = $nombre_tipo;
				$objeto->empresa     = $valor->EMPRP_Codigo;
				$objeto->ubigeo      = $valor->UBIGP_Codigo;
				$objeto->des_ubigeo  = $nombre_ubigeo;
				$objeto->descripcion = $valor->EESTABC_Descripcion==''?'&nbsp;':$valor->EESTABC_Descripcion;
				$objeto->direccion   = $valor->EESTAC_Direccion==''?"&nbsp;":$valor->EESTAC_Direccion;
				$objeto->estado      = $valor->EESTABC_FlagEstado;
				$objeto->sucursal    = $valor->EESTABP_Codigo;
				$resultado[]         = $objeto;
			}
		}
		return $resultado;
	}
	public function listar_contactosEmpresa($empresa){
		$listado_contactosEmpresa = $this->empresa_model->listar_contactosEmpresa($empresa);
		$resultado = array();
		if(count($listado_contactosEmpresa)>0){
			foreach($listado_contactosEmpresa as $indice => $valor){
				$persona         = $valor->ECONC_Persona;
				$datos_persona   = $this->persona_model->obtener_datosPersona($persona);
				$nombres_persona = $datos_persona[0]->PERSC_Nombre." ".$datos_persona[0]->PERSC_ApellidoPaterno." ".$datos_persona[0]->PERSC_ApellidoMaterno." ";
				$datos_directivo = $this->directivo_model->buscar_directivo($empresa,$persona);
				$directivo       = $datos_directivo[0]->DIREP_Codigo;
				$cargo           = $datos_directivo[0]->CARGP_Codigo;
				$datos_areaEmpresa = $this->empresa_model->obtener_areaEmpresa($empresa,$directivo);
				$datos_cargo     = $this->cargo_model->obtener_cargo($cargo);
				$nombre_cargo    = $datos_cargo[0]->CARGC_Descripcion;
				$area            = $datos_areaEmpresa[0]->AREAP_Codigo;
				$datos_area      = $this->area_model->obtener_area($area);
				$nombre_area     = $datos_area[0]->AREAC_Descripcion; 				
				$objeto = new stdClass();
				$objeto->area            = $area;
				$objeto->nombre_area     = $nombre_area;
				$objeto->empresa         = $valor->EMPRP_Codigo;
				$objeto->personacontacto = $valor->PERSP_Contacto;
				$objeto->descripcion     = $valor->ECONC_Descripcion;
				$objeto->telefono        = $valor->ECONC_Telefono==''?'&nbsp;':$valor->ECONC_Telefono;
				$objeto->movil           = $valor->ECONC_Movil;
				$objeto->fax             = $valor->ECONC_Fax;
				$objeto->email           = $valor->ECONC_Email==''?'&nbsp;':$valor->ECONC_Email;
				$objeto->persona         = $valor->ECONC_Persona;
				$objeto->nombre_persona  = $nombres_persona;
				$objeto->tipo_contacto   = $valor->ECONC_TipoContacto;
				$objeto->nombre_cargo    = $nombre_cargo;
				$resultado[]             = $objeto;
			}
		}
		return $resultado;
	}
	public function listar_contactosProveedor($proveedor){
            $datos_proveedor = $this->proveedor_model->obtener_datosProveedor($proveedor);
            $tipo_proveedor  = $datos_proveedor[0]->PROVC_TipoPersona;
            $resultado = array();
            if($tipo_proveedor==1){
                $empresa = $datos_proveedor[0]->EMPRP_Codigo;
		$listado_contactosEmpresa = $this->empresa_model->listar_contactosEmpresa($empresa);
		if(count($listado_contactosEmpresa)>0){
			foreach($listado_contactosEmpresa as $indice => $valor){
                            $persona         = $valor->ECONC_Persona;
                            $datos_persona   = $this->persona_model->obtener_datosPersona($persona);
                            $nombres_persona = $datos_persona[0]->PERSC_Nombre." ".$datos_persona[0]->PERSC_ApellidoPaterno." ".$datos_persona[0]->PERSC_ApellidoMaterno." ";
                            $datos_directivo = $this->directivo_model->buscar_directivo($empresa,$persona);
                            $directivo       = $datos_directivo[0]->DIREP_Codigo;
                            $cargo           = $datos_directivo[0]->CARGP_Codigo;
                            $datos_areaEmpresa = $this->empresa_model->obtener_areaEmpresa($empresa,$directivo);
                            $datos_cargo     = $this->cargo_model->obtener_cargo($cargo);
                            $nombre_cargo    = $datos_cargo[0]->CARGC_Descripcion;
                            $area            = $datos_areaEmpresa[0]->AREAP_Codigo;
                            $datos_area      = $this->area_model->obtener_area($area);
                            $nombre_area     = $datos_area[0]->AREAC_Descripcion;
                            $objeto = new stdClass();
                            $objeto->area            = $area;
                            $objeto->nombre_area     = $nombre_area;
                            $objeto->empresa         = $valor->EMPRP_Codigo;
                            $objeto->personacontacto = $valor->PERSP_Contacto;
                            $objeto->descripcion     = $valor->ECONC_Descripcion;
                            $objeto->telefono        = $valor->ECONC_Telefono==''?'&nbsp;':$valor->ECONC_Telefono;
                            $objeto->movil           = $valor->ECONC_Movil;
                            $objeto->fax             = $valor->ECONC_Fax;
                            $objeto->email           = $valor->ECONC_Email==''?'&nbsp;':$valor->ECONC_Email;
                            $objeto->persona         = $valor->ECONC_Persona;
                            $objeto->nombre_persona  = $nombres_persona;
                            $objeto->tipo_contacto   = $valor->ECONC_TipoContacto;
                            $objeto->nombre_cargo    = $nombre_cargo;
                            $resultado[]             = $objeto;
			}
		}
            }
            echo json_encode($resultado);
	}
	public function seleccionar_estadoCivil($indSel){
		$array_dist = $this->estadocivil_model->listar_estadoCivil();
		$arreglo = array();
		foreach($array_dist as $indice=>$valor){
			$indice1   = $valor->ESTCP_Codigo;
			$valor1    = $valor->ESTCC_Descripcion;
			$arreglo[$indice1] = $valor1;
		}
		$resultado = $this->html->optionHTML($arreglo,$indSel,array('0','::Seleccione::'));
		return $resultado;
	}	
	public function seleccionar_nacionalidad($indSel=''){
		$array_dist = $this->nacionalidad_model->listar_nacionalidad();
		$arreglo = array();
		foreach($array_dist as $indice=>$valor){
			$indice1   = $valor->NACP_Codigo;
			$valor1    = $valor->NACC_Descripcion;
			$arreglo[$indice1] = $valor1;
		}
		$resultado = $this->html->optionHTML($arreglo,$indSel,array('','::Seleccione::'));
		return $resultado;
	}		
	public function insertar_areaEmpresa($nombre_area){
		$this->empresa_model->insertar_areaEmpresa($area,$empresa,$descripcion);
		
	}
	public function seleccionar_departamento($indDefault=''){
		$array_dpto = $this->ubigeo_model->listar_departamentos();
		$arreglo = array();
		if(count($array_dpto)>0){
			foreach($array_dpto as $indice=>$valor){
				$indice1   = $valor->UBIGC_CodDpto;
				$valor1    = $valor->UBIGC_Descripcion;
				$arreglo[$indice1] = $valor1;
			}
		}
		$resultado = $this->html->optionHTML($arreglo,$indDefault,array('00','::Seleccione::'));
		return $resultado;
	}	
	public function seleccionar_provincia($departamento,$indDefault=''){
		$array_prov = $this->ubigeo_model->listar_provincias($departamento);
		$arreglo = array();
		if(count($array_prov)>0){
			foreach($array_prov as $indice=>$valor){
				$indice1   = $valor->UBIGC_CodProv;
				$valor1    = $valor->UBIGC_Descripcion;
				$arreglo[$indice1] = $valor1;
			}
		}
		$resultado = $this->html->optionHTML($arreglo,$indDefault,array('00','::Seleccione::'));
		return $resultado;
	}		
	public function seleccionar_distritos($departamento,$provincia,$indDefault=''){
		$array_dist = $this->ubigeo_model->listar_distritos($departamento,$provincia);
		$arreglo = array();
		if(count($array_dist)>0){
			foreach($array_dist as $indice=>$valor){
				$indice1   = $valor->UBIGC_CodDist;
				$valor1    = $valor->UBIGC_Descripcion;
				$arreglo[$indice1] = $valor1;
			}
		}
		$resultado = $this->html->optionHTML($arreglo,$indDefault,array('00','::Seleccione::'));
		return $resultado;
	}
        public function seleccionar_tipocodigo($indDefault=''){
		$array_dist = $this->tipocodigo_model->listar_tipo_codigo();
		$arreglo = array();
		if(count($array_dist)>0){
			foreach($array_dist as $indice=>$valor){
				$indice1   = $valor->TIPCOD_Codigo;
				$valor1    = $valor->TIPCOD_Inciales;
				$arreglo[$indice1] = $valor1;
			}
		}
		$resultado = $this->html->optionHTML($arreglo,$indDefault,array('0','::Seleccione::'));
		return $resultado;
	}
        public function seleccionar_tipodocumento($indDefault=''){
		$array_dist = $this->tipodocumento_model->listar_tipo_documento();
		$arreglo = array();
		if(count($array_dist)>0){
			foreach($array_dist as $indice=>$valor){
				$indice1   = $valor->TIPDOCP_Codigo;
				$valor1    = $valor->TIPOCC_Inciales;
				$arreglo[$indice1] = $valor1;
			}
		}
		$resultado = $this->html->optionHTML($arreglo,$indDefault,array('0','::Seleccione::'));
		return $resultado;
	}

        
}
?>