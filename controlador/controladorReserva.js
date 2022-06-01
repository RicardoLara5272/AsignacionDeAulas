document.addEventListener("DOMContentLoaded", function() {
        
    let tabla1 = $("#tablaarticulos").DataTable({
      "ajax": {
        url: "../vista/datosReserva.php?accion=listar",
        dataSrc: ""
      },
      "columns": [{
          
          "data": "id_pendientes"
        },
        {
          "data": "nombre_materia"
        },
        {
          "data": "grupo"
        },
        {
          "data": "fecha_reserva"
        },
        {
          "data": "hora_inicio"
        },
        {
          "data": "hora_fin"
        },
        {
            "data": "capEstudiantes"
        },
        {
            "data": "detalle"
        },
        {
          "data": null,
          "orderable": false
        }
      ],
      "columnDefs": [{
        targets: 8,
        "defaultContent": "<button class='btn btn-sm btn-danger botonborrar'>BORRAR</button>",
        data: null
      }],
      "language": {
        "url": "../vista/DataTables/spanish.json",
      },
    });

    //Eventos de botones de la aplicación
    $('#BotonAgregar').click(function() {
      $('#ConfirmarAgregar').show();
      limpiarFormulario();
      $("#FormularioArticulo").modal('show');
    });

    $('#ConfirmarAgregar').click(function() {
      $("#FormularioArticulo").modal('hide');
      let registro = recuperarDatosFormulario();
      agregarRegistro(registro);
    });

    $('#tablaarticulos tbody').on('click', 'button.botonborrar', function() {
      if (confirm("¿Realmente quiere borrar la reserva?")) {
        let registro = tabla1.row($(this).parents('tr')).data();
        borrarRegistro(registro.id_pendientes);
      }
    });

    $('#btnReserva').click(function() {
      console.log(id_doc); 
      mostrarMensaje(id_doc);
      
    });
    function mostrarMensaje(id_doc){
      $.ajax({
        url:"../controlador/tablaVacia.php",
        type: "POST",
        dataType: "json",
        data: {id_doc:id_doc},
        success: function(fila){
          if (fila!=null) {
            if (!fila) {
              Swal.fire({
                title: 'Solicitud #' + nro_solicitud,
                text: "¿Está seguro que desea enviar la solicitud?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO',
              }).then((result) => {
                if (result.isConfirmed) {

                  agregarSolicitud(id_doc);

                  Swal.fire({
                    title: 'Enviado',
                    text: 'La solicitud ha sido enviada con exito!',
                    icon: 'success',
                    confirmButtonText: 'OK',
                  }).then((result) => {
                    if (result.isConfirmed) {
                      redireccionA("../vista/homeDocente.php");
                    }
                  })
                }
              })
            } else {
              Swal.fire({
                title: 'Error',
                text: 'Debe ingresar al menos 1 reserva antes de enviar!',
                icon: 'error',
                confirmButtonText: 'OK',
              })
            }
          } 
        }
      });

    }

    function agregarSolicitud(id_doc){
      console.log(id_doc); 
      $.ajax({
        type: 'POST',
        dataType: "json",
        url: "../vista/datosReserva.php?accion=agregarSolicitud",
        data: {id_doc:id_doc},
        success: function(msg) {
          tabla1.ajax.reload();
        },
        error: function() {
          alert("Hay un problema aaqui");
        }
      });
    }


    function redireccionA(url) {
      window.location.href = url;
    }

    // funciones que interactuan con el formulario de entrada de datos
    function limpiarFormulario() {
      $('#id_materia').val('');
      $('#grupo').val('');
      $('#fecha_reserva').val('');
      $('#hora_inicio').val('');
      $('#hora_fin').val('');
      $('#capEstudiantes').val('');
      $('#detalle').val('');
    }

    function recuperarDatosFormulario() {
      let hora_ini = $('#hora_inicio').val();
      let hora_f = $('#hora_fin').val();
      let ini_valor = "";
      let fin_valor = "";

      if (hora_ini == 1) {
        ini_valor = "08:15";
        if(hora_f == 0){
          fin_valor = "09:45";
        }else{
          fin_valor = "11:45";
        }
      } else { 
        if (hora_ini == 2) {
          ini_valor = "09:45";
          if(hora_f == 0){
            fin_valor = "11:15";
          }else{
            fin_valor = "12:45";
          }
        } else { 
          if (hora_ini == 3) {
            ini_valor = "11:15";
            if(hora_f == 0){
              fin_valor = "12:45";
            }else{
              fin_valor = "14:15";
            }
          } else { 
            if (hora_ini == 4) {
              ini_valor = "12:45";
              if(hora_f == 0){
                fin_valor = "14:15";
              }else{
                fin_valor = "15:45";
              }
            } else { 
              if (hora_ini == 5) {
                ini_valor = "14:15";
                if(hora_f == 0){
                  fin_valor = "15:45";
                }else{
                  fin_valor = "17:15";
                }
              } else { 
                if (hora_ini == 6) {
                  ini_valor = "15:45";
                  if(hora_f == 0){
                    fin_valor = "17:15";
                  }else{
                    fin_valor = "18:45";
                  }
                } else {
                  if(hora_ini == 7){
                    ini_valor = "17:15";
                    if(hora_f == 0){
                      fin_valor = "18:45";
                    }
                  }
                }
              } 
            }
          }
        }
      }
      
      let registro = {
        id_materia: $('#id_materia').val(),
        grupo: $('#grupo').val(),
        fecha_reserva: $('#fecha_reserva').val(),
        hora_inicio : ini_valor,
        hora_fin : fin_valor,
        capEstudiantes: $('#capEstudiantes').val(),
        detalle: $('#detalle').val(),
        id_doc: id_doc
      };
      return registro;
    }

    // funciones para comunicarse con el servidor via ajax
    function agregarRegistro(registro) {
      $.ajax({
        type: 'POST',
        url: '../vista/datosReserva.php?accion=agregar',
        data: registro,
        success: function(msg) {
          tabla1.ajax.reload();
        },
        error: function() {
          alert("Hay un problema");
        }
      });
    }

    function borrarRegistro(id_pendientes) {
      $.ajax({
        type: 'GET',
        url: '../vista/datosReserva.php?accion=borrar&id_pendientes=' + id_pendientes,
        data: '',
        success: function(msg) {
          tabla1.ajax.reload();
        },
        error: function() {
          alert("Hay un problema");
        }
      });
    }
  });
  
