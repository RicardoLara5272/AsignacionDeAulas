document.addEventListener("DOMContentLoaded", function() {
        
    let tabla1 = $("#tablaarticulos").DataTable({
      "ajax": {
        url: "http://asignaciondeaulas/vista/datosReserva.php?accion=listar",
        dataSrc: ""
      },
      "columns": [{
          
          "data": "id_pendientes"
        },
        {
          "data": "nombre_materia"
        },
        {
          "data": "prueba"
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
        "defaultContent": "<button class='btn btn-sm btn-secondary botonborrar'>BORRAR</button>",
        data: null
      }],
      "language": {
        "url": "http://asignaciondeaulas/vista/DataTables/spanish.json",
      },
    });

    //Eventos de botones de la aplicación
    $('#BotonAgregar').click(function() {
      $('#ConfirmarAgregar').show();
      limpiarFormulario();
      $("#FormularioArticulo").modal('show');
    });

    $('#ConfirmarAgregar').click(function() {
      let registro = recuperarDatosFormulario();
      validarCampos(registro);
      
    });

    $('#tablaarticulos tbody').on('click', 'button.botonborrar', function() {
      if (confirm("¿Realmente quiere borrar la reserva?")) {
        let registro = tabla1.row($(this).parents('tr')).data();
        borrarRegistro(registro.id_pendientes);
      }
    });

    $('#btnReserva').click(function() {
      mostrarMensaje(id_doc);
    });

    $('#btnCancelReserva').click(function() {
      mostrarMensajeCancelCompartida(id_doc);
    });

    $('.cancelModal').click(function() {
      $("#FormularioArticulo").modal('hide');
      $('#hora_fin').empty().append('<option value="">Primero seleccione hora inicio...</option>');
    });

    function mostrarMensaje(id_doc){
      $.ajax({
        url:"http://asignaciondeaulas/controlador/tablaVacia.php",
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
                      redireccionA("http://asignaciondeaulas/vista/homeDocente.php");
                    }
                  })
                }
              })
            } else {
              Swal.fire({
                title: 'Error',
                text: '¡Debe ingresar al menos 1 reserva antes de enviar la solicitud!',
                icon: 'error',
                confirmButtonText: 'OK',
              })
            }
          } 
        }
      });
    }

    function mostrarMensajeCancelCompartida(id_doc){
      Swal.fire({
        title: 'Cancelar solicitud #' + nro_solicitud,
        text: `Las reservas se eliminaran automaticamente.
              ¿Desea cancelar la solicitud?
              `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SI',
        cancelButtonText: 'NO',
      }).then((result) => {
        if (result.isConfirmed) {
          borrarReservaPendientes(id_doc);
          Swal.fire({
            title: 'Cancelado',
            text: '¡La solicitud se cancelo con exito!',
            icon: 'success',
            confirmButtonText: 'OK',
            }).then((result) => {
              if (result.isConfirmed) {
                redireccionA("http://asignaciondeaulas/vista/homeDocente.php");
              }
            })
        }
      });
    }

    function borrarReservaPendientes(id_doc){
      console.log(id_doc); 
      $.ajax({
        type: 'POST',
        dataType: "json",
        url: "http://asignaciondeaulas/vista/datosReserva.php?accion=borrarReservaPendientes",
        data: {id_doc:id_doc},
        success: function(msg) {
          tabla1.ajax.reload();
        },
        error: function() {
          alert("Hay un problema aaqui");
        }
      });
    }

    function agregarSolicitud(id_doc){
      console.log(id_doc); 
      $.ajax({
        type: 'POST',
        dataType: "json",
        url: "http://asignaciondeaulas/vista/datosReserva.php?accion=agregarSolicitud",
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
      
    function validarCampos(registro){
      var hora_inicio = $("#hora_inicio").val();
      var hora_fin = $("#hora_fin").val();
      fecha_reserva = $.trim(registro.fecha_reserva);
      capEstudiantes = $.trim(registro.capEstudiantes);
      detalle = $.trim(registro.detalle);

      if (fecha_reserva != '') {
        if (hora_inicio != '') {
          if (hora_fin != '') {
            if(capEstudiantes!='' && capEstudiantes >=1 && capEstudiantes<=1000 ){
              if (detalle != '' && detalle.length <= 200 ) {
                agregarCompartido(registro);
                window.location.reload(true);
              } else {
                alert("Error!!\nDebe introducir un detalle maximo de 200 caracteres");    
              }
            }else{
              alert("Error!!\nDebe introducir una capacidad de estudiantes entre 1 y 1000");
            }
          }else{
            alert("Error!!\nDebe introducir una hora de inicio de reserva de aula");
          }
        }else{
          alert("Error!!\nDebe introducir una hora de inicio de reserva de aula");
        }
      }else{
        alert("Error!!\nDebe introducir una fecha de reserva de aula");
      }
        
      $('#ConfirmarAgregar').show();
      $("#FormularioArticulo").modal('show');
      registro = recuperarDatosFormulario();
    }

    function agregarCompartido(registro) {
      $.ajax({
        type: 'POST',
        url: 'http://asignaciondeaulas/vista/datosReserva.php?accion=agregarCompartido',
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
        url: 'http://asignaciondeaulas/vista/datosReserva.php?accion=borrar&id_pendientes=' + id_pendientes,
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
  
