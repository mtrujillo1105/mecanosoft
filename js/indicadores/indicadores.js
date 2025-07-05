jQuery(document).ready(function() {
    
    function checkController(vmin, vmax, vreal, period, fecreg, feccre) {
        var parametros = {
            "valorMin" : vmin,
            "valorMax" : vmax,
            "valorReal" : vreal,
            "periodo" : period,
            "fecregistro" : fecreg,
            "feccreacion" : feccre
        }
        
        $.ajax({
            data:  parametros,
            url:   'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
            type:  'post'
        });
    }
    
    //Default Action
    $(".tab_content").hide(); //Hide all content
    $("ul.tabs li:first").addClass("active").show(); //Activate first tab
    $(".tab_content:first").show(); //Show first tab content
    //On Click Event
    $("ul.tabs li").click(function() {
        $("ul.tabs li").removeClass("active"); //Remove any "active" class
        $(this).addClass("active"); //Add "active" class to selected tab
        $(".tab_content").hide(); //Hide all tab content
        var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
        //$(this).find("a").remove(); //Find the rel attribute value to identify the active tab + content
        $(activeTab).fadeIn(); //Fade in the active content
    });
    
    $('#menu li a').click(function(event){
                var elem = $(this).next();
                if(elem.is('ul')){
                        event.preventDefault();
                        $('#menu ul:visible').not(elem).slideUp();
                        elem.slideToggle();
                }
    });
    
    $("#dialog-btn1").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio1").val(),
                    "ffin": $("#ffin1").val(),
                    "valorReal": $("#vreal1").val(),
                    "kpiCode": $("#kpicode1").val(),
                };
 
                if ($("#finicio1").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin1").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn2").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio2").val(),
                    "ffin": $("#ffin2").val(),
                    "valorReal": $("#vreal2").val(),
                    "kpiCode": $("#kpicode2").val(),
                };
 
                if ($("#finicio2").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin2").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn3").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio3").val(),
                    "ffin": $("#ffin3").val(),
                    "valorReal": $("#vreal3").val(),
                    "kpiCode": $("#kpicode3").val(),
                };
 
                if ($("#finicio3").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin3").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn4").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio4").val(),
                    "ffin": $("#ffin4").val(),
                    "valorReal": $("#vreal4").val(),
                    "kpiCode": $("#kpicode4").val(),
                };
 
                if ($("#finicio4").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin4").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn5").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio5").val(),
                    "ffin": $("#ffin5").val(),
                    "valorReal": $("#vreal5").val(),
                    "kpiCode": $("#kpicode5").val(),
                };
 
                if ($("#finicio5").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin5").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn6").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio6").val(),
                    "ffin": $("#ffin6").val(),
                    "valorReal": $("#vreal6").val(),
                    "kpiCode": $("#kpicode6").val(),
                };
 
                if ($("#finicio6").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin6").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn7").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio7").val(),
                    "ffin": $("#ffin7").val(),
                    "valorReal": $("#vreal7").val(),
                    "kpiCode": $("#kpicode7").val(),
                };
 
                if ($("#finicio7").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin7").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn8").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var finicio = $("#finicio8").val();
                var ffin = $("#ffin8").val();
                
                var newfinicio = finicio.split('/');
                var newffin = ffin.split('/');
                
                finicio = newfinicio[1] + "-" + newfinicio[0] + "-" + newfinicio[2];
                ffin = newffin[1] + "-" + newffin[0] + "-" + newffin[2];
 
                if ($("#finicio8").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin8").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
                
                location.href = "http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador/08/" + finicio + "/" + ffin;
                
//                location.href = "http://nazca/mimco_dev_3/index.php/indicadores/indicadores/kpi08/" + finicio + "/" + ffin;

            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn9").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var finicio = $("#finicio9").val();
                var ffin = $("#ffin9").val();
                
                var newfinicio = finicio.split('/');
                var newffin = ffin.split('/');
                
                finicio = newfinicio[1] + "-" + newfinicio[0] + "-" + newfinicio[2];
                ffin = newffin[1] + "-" + newffin[0] + "-" + newffin[2];
 
                if ($("#finicio9").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin9").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
                
                location.href = "http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador/08/" + finicio + "/" + ffin;
                
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn10").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio10").val(),
                    "ffin": $("#ffin10").val(),
                    "valorReal": $("#vreal10").val(),
                    "kpiCode": $("#kpicode10").val(),
                };
 
                if ($("#finicio10").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin10").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn11").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio11").val(),
                    "ffin": $("#ffin11").val(),
                    "valorReal": $("#vreal11").val(),
                    "kpiCode": $("#kpicode11").val(),
                };
 
                if ($("#finicio11").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin11").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn12").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio12").val(),
                    "ffin": $("#ffin12").val(),
                    "valorReal": $("#vreal12").val(),
                    "kpiCode": $("#kpicode12").val(),
                };
 
                if ($("#finicio12").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin12").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn13").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio13").val(),
                    "ffin": $("#ffin13").val(),
                    "valorReal": $("#vreal13").val(),
                    "kpiCode": $("#kpicode13").val(),
                };
 
                if ($("#finicio13").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin13").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn14").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio14").val(),
                    "ffin": $("#ffin14").val(),
                    "valorReal": $("#vreal14").val(),
                    "kpiCode": $("#kpicode4").val(),
                };
 
                if ($("#finicio14").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin14").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn15").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio15").val(),
                    "ffin": $("#ffin15").val(),
                    "valorReal": $("#vreal15").val(),
                    "kpiCode": $("#kpicode15").val(),
                };
 
                if ($("#finicio15").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin15").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn16").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio16").val(),
                    "ffin": $("#ffin16").val(),
                    "valorReal": $("#vreal16").val(),
                    "kpiCode": $("#kpicode16").val(),
                };
 
                if ($("#finicio16").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin16").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn17").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio17").val(),
                    "ffin": $("#ffin17").val(),
                    "valorReal": $("#vreal17").val(),
                    "kpiCode": $("#kpicode17").val(),
                };
 
                if ($("#finicio17").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin17").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn18").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio18").val(),
                    "ffin": $("#ffin18").val(),
                    "valorReal": $("#vreal18").val(),
                    "kpiCode": $("#kpicode18").val(),
                };
 
                if ($("#finicio18").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin18").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn19").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio19").val(),
                    "ffin": $("#ffin19").val(),
                    "valorReal": $("#vreal19").val(),
                    "kpiCode": $("#kpicode19").val(),
                };
 
                if ($("#finicio19").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin19").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn20").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio20").val(),
                    "ffin": $("#ffin20").val(),
                    "valorReal": $("#vreal20").val(),
                    "kpiCode": $("#kpicode20").val(),
                };
 
                if ($("#finicio20").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin20").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn21").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio21").val(),
                    "ffin": $("#ffin21").val(),
                    "valorReal": $("#vreal21").val(),
                    "kpiCode": $("#kpicode21").val(),
                };
 
                if ($("#finicio21").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin21").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn22").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio22").val(),
                    "ffin": $("#ffin22").val(),
                    "valorReal": $("#vreal22").val(),
                    "kpiCode": $("#kpicode22").val(),
                };
 
                if ($("#finicio22").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin22").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn23").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio23").val(),
                    "ffin": $("#ffin23").val(),
                    "valorReal": $("#vreal23").val(),
                    "kpiCode": $("#kpicode23").val(),
                };
 
                if ($("#finicio23").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin23").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn24").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio24").val(),
                    "ffin": $("#ffin24").val(),
                    "valorReal": $("#vreal24").val(),
                    "kpiCode": $("#kpicode24").val(),
                };
 
                if ($("#finicio24").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin24").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn25").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio25").val(),
                    "ffin": $("#ffin25").val(),
                    "valorReal": $("#vreal25").val(),
                    "kpiCode": $("#kpicode25").val(),
                };
 
                if ($("#finicio25").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin25").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn26").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio26").val(),
                    "ffin": $("#ffin26").val(),
                    "valorReal": $("#vreal26").val(),
                    "kpiCode": $("#kpicode26").val(),
                };
 
                if ($("#finicio26").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin26").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn27").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio27").val(),
                    "ffin": $("#ffin27").val(),
                    "valorReal": $("#vreal27").val(),
                    "kpiCode": $("#kpicode27").val(),
                };
 
                if ($("#finicio27").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin27").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn28").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio28").val(),
                    "ffin": $("#ffin28").val(),
                    "valorReal": $("#vreal28").val(),
                    "kpiCode": $("#kpicode28").val(),
                };
 
                if ($("#finicio28").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin28").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn29").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio29").val(),
                    "ffin": $("#ffin29").val(),
                    "valorReal": $("#vreal29").val(),
                    "kpiCode": $("#kpicode29").val(),
                };
 
                if ($("#finicio29").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin29").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn30").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio30").val(),
                    "ffin": $("#ffin30").val(),
                    "valorReal": $("#vreal30").val(),
                    "kpiCode": $("#kpicode30").val(),
                };
 
                if ($("#finicio30").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin30").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn31").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio31").val(),
                    "ffin": $("#ffin31").val(),
                    "valorReal": $("#vreal31").val(),
                    "kpiCode": $("#kpicode31").val(),
                };
 
                if ($("#finicio31").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin31").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn32").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio32").val(),
                    "ffin": $("#ffin32").val(),
                    "valorReal": $("#vreal32").val(),
                    "kpiCode": $("#kpicode32").val(),
                };
 
                if ($("#finicio32").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin32").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn33").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio33").val(),
                    "ffin": $("#ffin33").val(),
                    "valorReal": $("#vreal33").val(),
                    "kpiCode": $("#kpicode33").val(),
                };
 
                if ($("#finicio33").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin33").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn34").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio34").val(),
                    "ffin": $("#ffin34").val(),
                    "valorReal": $("#vreal34").val(),
                    "kpiCode": $("#kpicode34").val(),
                };
 
                if ($("#finicio34").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin34").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn35").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio35").val(),
                    "ffin": $("#ffin35").val(),
                    "valorReal": $("#vreal35").val(),
                    "kpiCode": $("#kpicode35").val(),
                };
 
                if ($("#finicio35").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin35").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn36").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio3").val(),
                    "ffin": $("#ffin36").val(),
                    "valorReal": $("#vreal36").val(),
                    "kpiCode": $("#kpicode36").val(),
                };
 
                if ($("#finicio36").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin36").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn37").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio37").val(),
                    "ffin": $("#ffin37").val(),
                    "valorReal": $("#vreal37").val(),
                    "kpiCode": $("#kpicode37").val(),
                };
 
                if ($("#finicio37").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin37").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn38").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio38").val(),
                    "ffin": $("#ffin38").val(),
                    "valorReal": $("#vreal38").val(),
                    "kpiCode": $("#kpicode38").val(),
                };
 
                if ($("#finicio38").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin38").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
$("#dialog-btn39").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio39").val(),
                    "ffin": $("#ffin39").val(),
                    "valorReal": $("#vreal39").val(),
                    "kpiCode": $("#kpicode39").val(),
                };
 
                if ($("#finicio39").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin39").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn40").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio40").val(),
                    "ffin": $("#ffin40").val(),
                    "valorReal": $("#vreal40").val(),
                    "kpiCode": $("#kpicode40").val(),
                };
 
                if ($("#finicio40").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin40").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn41").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio41").val(),
                    "ffin": $("#ffin41").val(),
                    "valorReal": $("#vreal41").val(),
                    "kpiCode": $("#kpicode41").val(),
                };
 
                if ($("#finicio41").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin41").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn42").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio42").val(),
                    "ffin": $("#ffin42").val(),
                    "valorReal": $("#vreal42").val(),
                    "kpiCode": $("#kpicode42").val(),
                };
 
                if ($("#finicio42").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin42").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn43").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio43").val(),
                    "ffin": $("#ffin43").val(),
                    "valorReal": $("#vreal43").val(),
                    "kpiCode": $("#kpicode43").val(),
                };
 
                if ($("#finicio43").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin43").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn44").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio44").val(),
                    "ffin": $("#ffin44").val(),
                    "valorReal": $("#vreal44").val(),
                    "kpiCode": $("#kpicode44").val(),
                };
 
                if ($("#finicio44").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin44").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn45").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio45").val(),
                    "ffin": $("#ffin45").val(),
                    "valorReal": $("#vreal45").val(),
                    "kpiCode": $("#kpicode45").val(),
                };
 
                if ($("#finicio45").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin45").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn46").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio46").val(),
                    "ffin": $("#ffin46").val(),
                    "valorReal": $("#vreal46").val(),
                    "kpiCode": $("#kpicode46").val(),
                };
 
                if ($("#finicio46").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin46").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn47").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio47").val(),
                    "ffin": $("#ffin47").val(),
                    "valorReal": $("#vreal47").val(),
                    "kpiCode": $("#kpicode47").val(),
                };
 
                if ($("#finicio47").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin47").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn48").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio48").val(),
                    "ffin": $("#ffin48").val(),
                    "valorReal": $("#vreal48").val(),
                    "kpiCode": $("#kpicode48").val(),
                };
 
                if ($("#finicio48").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin48").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn49").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio49").val(),
                    "ffin": $("#ffin49").val(),
                    "valorReal": $("#vreal49").val(),
                    "kpiCode": $("#kpicode49").val(),
                };
 
                if ($("#finicio49").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin49").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn50").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio50").val(),
                    "ffin": $("#ffin50").val(),
                    "valorReal": $("#vreal50").val(),
                    "kpiCode": $("#kpicode50").val(),
                };
 
                if ($("#finicio50").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin50").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn51").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio51").val(),
                    "ffin": $("#ffin51").val(),
                    "valorReal": $("#vreal51").val(),
                    "kpiCode": $("#kpicode51").val(),
                };
 
                if ($("#finicio51").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin51").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn52").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio52").val(),
                    "ffin": $("#ffin52").val(),
                    "valorReal": $("#vreal52").val(),
                    "kpiCode": $("#kpicode52").val(),
                };
 
                if ($("#finicio52").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin52").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn53").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio53").val(),
                    "ffin": $("#ffin53").val(),
                    "valorReal": $("#vreal53").val(),
                    "kpiCode": $("#kpicode53").val(),
                };
 
                if ($("#finicio53").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin53").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });

$("#dialog-btn54").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio54").val(),
                    "ffin": $("#ffin54").val(),
                    "valorReal": $("#vreal54").val(),
                    "kpiCode": $("#kpicode54").val(),
                };
 
                if ($("#finicio54").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }
 
                if ($("#ffin54").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }
 
                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
                    type: 'post',
                    success: function(response) {
                        window.alert(response);
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
//    for(i=1 ; i <= 54 ; i++) {
//        $("#dialog-btn" + i).dialog({
//            autoOpen: false,
//            height: 230,
//            width: 350,
//            modal: true,
//            buttons: {
//                "Aceptar": function() {
//                    var parametros = {
//                        "finicio": $("#finicio" + i).val(),
//                        "ffin": $("#ffin" + i).val(),
//                        "valorReal": $("#vreal" + i).val(),
//                        "kpiCode": $("#kpicode" + i).val(),
//                    };
//
//                    if ($("#finicio" + i).val() == "") {
//                        window.alert("Se necesita fecha de inicio")
//                        return false;
//                    }
//
//                    if ($("#finicio" + i).val() == "") {
//                        window.alert("Se necesita fecha fin")
//                        return false;
//                    }
//
//                    $.ajax({
//                        data: parametros,
//                        url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/recuperarIndicador',
//                        type: 'post',
//                        success: function(response) {
//                            window.alert(response);
////                            location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
//                        }
//                    });
//                    console.log("----------------------------");
//                },
//                "Cancelar": function() {
//                    $(this).dialog("close");
//                }
//            }
//        });
//    }
    
    for(j=1 ; j <= 54 ; j++) {
        $("#formbtn" + j).click(function() {
            $("#dialog-form" + j).dialog("open");
        });
    }
    
    
    for(i = 1; i <= 54 ; i++){
        $("#finicio" + i).datepicker({
            showButtonPanel: true
        });
        $("#ffin" + i).datepicker({
            showButtonPanel: true
        });
    }
    
//    for(k=1 ; k <= 54 ; k++) {
//        $("#btn" + k).click(function(){
//        $("#dialog-btn" + k).dialog("open");
//    });
//    }
    
    
    $("#btn1").click(function(){
        $("#dialog-btn1").dialog("open");
    });
    
    $("#btn2").click(function(){
        $("#dialog-btn2").dialog("open");
    });
    
    $("#btn3").click(function(){
        $("#dialog-btn3").dialog("open");
    });
    
    $("#btn4").click(function(){
        $("#dialog-btn4").dialog("open");
    });
    
    $("#btn5").click(function(){
        $("#dialog-btn5").dialog("open");
    });
    
    $("#btn6").click(function(){
        $("#dialog-btn6").dialog("open");
    });
    
    $("#btn7").click(function(){
        $("#dialog-btn7").dialog("open");
    });
    
    $("#btn8").click(function(){
        if($("#entidad").val() != "02"){
            window.alert("Cambie de entidad para ver resultados");
            return;
        }
        $("#dialog-btn8").dialog("open");
    });
    
    $("#btn9").click(function(){
        if($("#entidad").val() != "01"){
            window.alert("Cambie de entidad para ver resultados");
            return;
        }
        $("#dialog-btn9").dialog("open");
    });
    
    $("#btn10").click(function(){
        $("#dialog-btn10").dialog("open");
    });
    
    $("#btn11").click(function(){
        $("#dialog-btn11").dialog("open");
    });
    
    $("#btn12").click(function(){
        $("#dialog-btn12").dialog("open");
    });
    
    $("#btn13").click(function(){
        $("#dialog-btn13").dialog("open");
    });
    
    $("#btn14").click(function(){
        $("#dialog-btn14").dialog("open");
    });
    
    $("#btn15").click(function(){
        $("#dialog-btn15").dialog("open");
    });
    
    $("#btn16").click(function(){
        $("#dialog-btn16").dialog("open");
    });
    
    $("#btn17").click(function(){
        $("#dialog-btn16").dialog("open");
    });
    
    $("#btn18").click(function(){
        $("#dialog-btn16").dialog("open");
    });
    
    $("#btn19").click(function(){
        $("#dialog-btn16").dialog("open");
    });
    
    $("#btn20").click(function(){
        $("#dialog-btn16").dialog("open");
    });
    
//    $("#btn1").click(function() {
//        $('#graf_container').html("");
//        $('#graf_container').highcharts({
//            
//            chart: {
//                type: 'line',
//                marginRight: 130,
//                marginBottom: 25
//            },
//            title: {
//                text: 'Monthly Average Temperature',
//                x: -20 //center
//            },
//            subtitle: {
//                text: 'Source: WorldClimate.com',
//                x: -20
//            },
//            xAxis: {
//                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
//                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
//            },
//            yAxis: {
//                title: {
//                    text: 'Temperature (°C)'
//                },
//                plotLines: [{
//                    value: 0,
//                    width: 1,
//                    color: '#808080'
//                }]
//            },
//            tooltip: {
//                valueSuffix: '°C'
//            },
//            legend: {
//                layout: 'vertical',
//                align: 'right',
//                verticalAlign: 'top',
//                x: -10,
//                y: 100,
//                borderWidth: 0
//            },
//            series: [{
//                name: 'Tokyo',
//                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5]
//            }, {
//                name: 'New York',
//                data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1]
//            }, {
//                name: 'Berlin',
//                data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9]
//            }, {
//                name: 'London',
//                data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
//            }]
//        });
//    });
    
//    $("#btn2").click(function() {
//        $('#graf_container').html("");
//        $('#graf_container').highcharts({
//            exporting: {
//                enabled: false
//            },
//            chart: {
//                type: 'spline',
//                inverted: true,
//                width: 500,
//                style: {
//                    margin: '0 auto'
//                }
//            },
//            title: {
//                text: 'Atmosphere Temperature by Altitude'
//            },
//            subtitle: {
//                text: 'According to the Standard Atmosphere Model'
//            },
//            xAxis: {
//                reversed: false,
//                title: {
//                    enabled: true,
//                    text: 'Altitude'
//                },
//                labels: {
//                    formatter: function() {
//                        return this.value +'km';
//                    }
//                },
//                maxPadding: 0.05,
//                showLastLabel: true
//            },
//            yAxis: {
//                title: {
//                    text: 'Temperature'
//                },
//                labels: {
//                    formatter: function() {
//                        return this.value + 'Â°';
//                    }
//                },
//                lineWidth: 2
//            },
//            legend: {
//                enabled: false
//            },
//            tooltip: {
//                headerFormat: '<b>{series.name}</b><br/>',
//                pointFormat: '{point.x} km: {point.y}Â°C'
//            },
//            plotOptions: {
//                spline: {
//                    marker: {
//                        enable: false
//                    }
//                }
//            },
//            series: [{
//                name: 'Temperature',
//                data: [[0, 15], [10, -50], [20, -56.5], [30, -46.5], [40, -22.1],
//                    [50, -2.5], [60, -27.7], [70, -55.7], [80, -76.5]]
//            }]
//        });
//    });
});


