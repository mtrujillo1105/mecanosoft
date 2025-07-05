<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo titulo;?></title>
    <META HTTP-EQUIV="Refresh" content="300"> 
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <meta http-equiv="Content-Language" content="es"> 
    <!-- Calendario -->
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
    <!-- Calendario -->	    
    <script type="text/javascript" src="<?php echo js;?>JSCookMenu.js"></script>	
    <script type="text/javascript" src="<?php echo js;?>theme.js"></script>	
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>	
    <script type="text/javascript" src="<?php echo js;?>superlink.js"></script>	
    <script type="text/javascript" src="<?php echo js;?>inicio.js"></script>	
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">   
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo js;?>calendario/aqua/theme.css" title="Aqua" />
<script type="text/javascript">

var oldLink = null;
// code to change the active stylesheet
function setActiveStyleSheet(link, title) {
  var i, a, main;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
      a.disabled = true;
      if(a.getAttribute("title") == title) a.disabled = false;
    }
  }
  if (oldLink) oldLink.style.fontWeight = 'normal';
  oldLink = link;
  link.style.fontWeight = 'bold';
  return false;
}

// This function gets called when the end-user clicks on some date.
function selected(cal, date) {
  cal.sel.value = date; // just update the date in the input field.
  if (cal.dateClicked && (cal.sel.id == "sel1" || cal.sel.id == "sel3"))
    // if we add this call we close the calendar on single-click.
    // just to exemplify both cases, we are using this only for the 1st
    // and the 3rd field, while 2nd and 4th will still require double-click.
    cal.callCloseHandler();
}

// And this gets called when the end-user clicks on the _selected_ date,
// or clicks on the "Close" button.  It just hides the calendar without
// destroying it.
function closeHandler(cal) {
  cal.hide();                        // hide the calendar
//  cal.destroy();
  _dynarch_popupCalendar = null;
}

// This function shows the calendar under the element having the given id.
// It takes care of catching "mousedown" signals on document and hiding the
// calendar if the click was outside.
function showCalendar(id, format, showsTime, showsOtherMonths) {
  var el = document.getElementById(id);
  if (_dynarch_popupCalendar != null) {
    // we already have some calendar created
    _dynarch_popupCalendar.hide();                 // so we hide it first.
  } else {
    // first-time call, create the calendar.
    var cal = new Calendar(1, null, selected, closeHandler);
    // uncomment the following line to hide the week numbers
    // cal.weekNumbers = false;
    if (typeof showsTime == "string") {
      cal.showsTime = true;
      cal.time24 = (showsTime == "24");
    }
    if (showsOtherMonths) {
      cal.showsOtherMonths = true;
    }
    _dynarch_popupCalendar = cal;                  // remember it in the global var
    cal.setRange(1900, 2070);        // min/max year allowed.
    cal.create();
  }
  _dynarch_popupCalendar.setDateFormat(format);    // set the specified date format
  _dynarch_popupCalendar.parseDate(el.value);      // try to parse the text in field
  _dynarch_popupCalendar.sel = el;                 // inform it what input field we use

  // the reference element that we pass to showAtElement is the button that
  // triggers the calendar.  In this example we align the calendar bottom-right
  // to the button.
  _dynarch_popupCalendar.showAtElement(el.nextSibling, "Br");        // show the calendar

  return false;
}

var MINUTE = 60 * 1000;
var HOUR = 60 * MINUTE;
var DAY = 24 * HOUR;
var WEEK = 7 * DAY;

// If this handler returns true then the "date" given as
// parameter will be disabled.  In this example we enable
// only days within a range of 10 days from the current
// date.
// You can use the functions date.getFullYear() -- returns the year
// as 4 digit number, date.getMonth() -- returns the month as 0..11,
// and date.getDate() -- returns the date of the month as 1..31, to
// make heavy calculations here.  However, beware that this function
// should be very fast, as it is called for each day in a month when
// the calendar is (re)constructed.
function isDisabled(date) {
  var today = new Date();
  return (date.getTime() > today.getTime());
}

function flatSelected(cal, date) {
  var el = document.getElementById("preview");
  el.innerHTML = date;
  url = base_url+"index.php/inicio/agenda/"+date;
  $.post(url,"",function(data){
    $("#divcentro").html(data);
  });
}

function showFlatCalendar() {
  var parent = document.getElementById("display");

  // construct a calendar giving only the "selected" handler.
  var cal = new Calendar(0, null, flatSelected);

  // hide week numbers
  cal.weekNumbers = false;

  // We want some dates to be disabled; see function isDisabled above
  cal.setDisabledHandler(isDisabled);
  //cal.setDateFormat("%A, %e de %B del %Y ");
  cal.setDateFormat("%e%m%Y ");
  // this call must be the last as it might use data initialized above; if
  // we specify a parent, as opposite to the "showCalendar" function above,
  // then we create a flat calendar -- not popup.  Hidden, though, but...
  cal.create(parent);

  // ... we can show it here.
  cal.show();
}
</script>    
</head>
<body onload="showFlatCalendar()">
    <div class="container">
        <div style="float:left;border:0px solid #000;width:20%">
            <div>
                <form id="frmPrincipal" method="post">
                    <table border="0" cellpadding="0" width="100%" height="100%" style="border:4px groove;" class="bodycabecera">
                      <tr>
                        <td class="titulocabecera" valign='top'>
                            <a style='text-decoration:none;font:bold 13px verdana;color:#f0f0ff' href='#' target='_blank'>METALES INGENIERIA Y CONSTRUCCION S.A.C.</a>
                        </td>
                      </tr>
                      <tr>
                        <td align='right' valign='bottom' class="textocabecera">Usuario: <a title="Click aqui para cambiar su password" class="textocabecera"><?=$nombreusuario;?></a></td>
                      </tr> 
                      <tr> 
                        <td align='right' valign='bottom' class="textocabecera">cod: <a  class="textocabecera"><?=$codres;?></a></td>
                      </tr>
                      <tr>
                      </tr>
                      <tr>
                        <td align='right' valign='bottom' class="textocabecera"><input type='button' value='Salir' class='botones' onclick='location.href="<?php echo base_url();?>index.php/inicio/salir"' title='Desconectarse del sistema'></td>
                      </tr>
                      <?php echo $oculto;?>
                    </table>
                </form>
            </div>
            <div style="height:925px;border: 0px solid #000;background: #ccc;"><?php echo $filamenu;?></div>
        </div>
        <div id="divcentro" style="float:left;border:1px solid #A4A4A4;width:60%">
            <div>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height='45' class="cabeceradia">
                    <tr>
                        <td valign='top' style='font:bold 11px verdana;color: #990000;margin: 5px 10px 10px 10px;'><?php echo $nombreentidad;?></td>
                    </tr>
                    <tr>
                        <td align='center' valign='top' style="font: bold 22px verdana;color:#ffffff;"><?php echo $fecha;?></td>
                    </tr>
                </table>
            </div>
            <div>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tabladetalledia">
                    <?php echo $varr;?>
                </table>
            </div>
        </div>
       <div id="divderecho" style="position: absolute; top: -10px; right:0px; border:0px solid #A4A4A4;width:19%;background: #ccc;height:1045px;">
           <div style=" left:0px; background:#fff;">
           <!-----------  MUESTRA CALENDARIO  ---------------->
            <TBODY align=center border="0" cellpadding="0" style="border-collapse: collapse;width: 100%;">
                <tr align="center">
                    <td align="center"><div id="display" style="float: left; clear: both;"><p align="center"></div></td>
                </tr>
                <tr>
                    <td><div id="preview" style="display: none;"><p align="center"></div></td>
                </tr>
            </TBODY>
      <!-----------  MUESTRA CALENDARIO  ---------------->   
           </div>
           <div style=" left:0px; background:#fff;">
                <br>
                <TBODY  style="top: 300px;"  border="0" width="100%" cellpadding="2" cellspacing="2">

                <tr>
                    <td class='resmenutitle' align='left'>
                        <table border="0" cellpadding="0" cellspacing="0" width='100%'>
                            <tr>

                                <td align='center'>
                <!--                    -CAMARAS-
                                    <br>
                                   <select class='textos' style='width:150px;' name='webcam' onchange="document.a.submit();">< ?=$varwebcam;?></select>-->

                <!--                <br> <br>   -->
                                <img src='<?php echo img;?>mimco.jpg' width='165' height='140'>
                                </td>
                            </tr>
                        </table>
                    </td>


                </tr>
                </TBODY>
           </div>
           <div style=" left:0px; background:#fff;">
                <div>
                <table border="0" width="100%" cellpadding="2" cellspacing="2">
                    <tr>
                        <td align='center' colspan='3' style='font:bold 9px verdana;border:1px solid #a0a0a0;padding:2 2 2 2;background:#000000;color:#ffffff;'>
                            <input type="button" style="font:bold 10px verdana;color:#ffffff;background:#000000;" id="btn" name="btn" value="MARCACION DE RELOJ">
                        </td>
                    </tr>
                    <tr>
                        <td style='font:bold 9px verdana;border:1px solid #a0a0a0;padding:1 1 1 1;background:#000000;color:#ffffff;'>PERSONA</td>
                        <td style='font:bold 9px verdana;border:1px solid #a0a0a0;padding:1 1 1 1;background:#000000;color:#ffffff;'>HORA</td>
                        <td style='font:bold 9px verdana;border:1px solid #a0a0a0;padding:1 1 1 1;background:#000000;color:#ffffff;'>TIPO</td>
                    </tr>
                </table>
                </div>
                <div style="height:400px;overflow:auto;">
                <table style="font:6px verdana;border:1px solid;" width="100%" border="0" cellpadding="0" cellspacing="0">
                    <?php echo $rpta;?>
                </table>
                </div>                   
           </div>
        </div>
    </div>
</body>
</html>