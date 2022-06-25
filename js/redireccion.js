function comprobacion(){
  var  numResAten = document.getElementById("num_rese_ate").value;
  var  numTotalRese = document.getElementById("num_total_rese").value - 1;
  var id_solicitud = document.getElementById("id_solicitud").value ;
  var id_admin = document.getElementById("id_admin").value;
  
  if (numTotalRese != numResAten) {
    alert('exiten aun reservas sin ser atendidas');
  } else {
    $.ajax({
      data:  { "idSolicitud" : id_solicitud, "idAdmin" : id_admin }, 
      url:   "cambioEstSoli.php", //archivo que recibe la peticion
      type:  "POST",
      datatyoe: "json",
    })
    window.location.replace("vistaDetpend.php");
  }
}
function url(uri) {
    location.href = uri; 
}