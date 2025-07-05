<!DOCTYPE html>
<html>
<head>
    <!-- Calendario -->
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	
    <!-- Calendario -->	
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>ventas/ot.js"></script>
    
    
    
    <script>
        
    function listadoot(obj)
    {
        codot = $(obj).attr('id');
        tipo  = $(obj).attr('id2');
        fteot = $(obj).attr('id3');
        factual = $(obj).attr('id4');
 
        
if(fteot < factual)
{
   alert("Ya fue asignada la Fecha Termino, \n Favor de seleccionar otra.");
}
else
{  
       
       
       
       
         <?php   if($n!='')
                 {     ?>
                 window.opener.cargar_ot(<?php echo $n;?>,codot,tipo);
         <?php   }
                 else
                 {     ?>
                 window.opener.cargar_ot2(codot,tipo);
         <?php   }     ?>
             
         window.close();
    }
}  


    function cerrar()
    {
        <?php   if($n!='')
                {     ?>
                window.close();
        <?php   }   
                else
                {     ?>
                window.opener.cargar_ot2('','');
                
        <?php   }     ?>
            
            
    }
    
    

    
    
    </script>   
    
    
</head>
<body  style='font-size:62.5%;' onload="$('#ot').focus();">
    <div style="align:left;border:0px solid #000;height:30px;margin-top: 8px;">
        <form id='frmBusqueda' method='post'>
            <span>TIPO A BUSCAR: <?php echo $tipoot;?></span>
            <span>NRO OT: <input type='text' name='ot' id='ot' value="<?php echo $ot;?>" style='width:75px;'></input></span>
            <span>RAZON SOCIAL:<input type='text' name='rsocial' id='rsocial' style='width:150px;' value="<?php echo $rsocial;?>"></span>
            <span><input type="submit" value="Buscar"></span>
        </form>
    </div>
    <div style = "display: table; width: 100%;border:1px solid #000;">
        <div style = "float: left; height:50px; width: 99%;border:1px solid #000;">
            <table border='1' style='width:100%;'>
                <tr align='center' style="height:50px;">
                    <td style='width:10%;'><div>NRO OT</div></td>
                    <td style='width:35%;'><div>SITE</div></td>                
                    <td style='width:55%;'><div>RAZON SOCIAL</div></td>
                </tr>
            </table>
        </div>
        <div style = "float: left; height: 300px;overflow: auto; width: 100%;border:1px solid #000;">
            <table border='1' style='width:100%;'><?php echo $fila;?></table>        
        </div>
    </div>
    
     <?php
     //if($n!=''){
       echo "<div><input type='button' value='Salir' onclick='cerrar();'></div>";  
     //}
     //else{echo "";}
     ?>

</body>
</html>