function comprobacion(){
  var  numResAten = document.getElementById('actual').value;
  console.log(numResAten);
  if (numResAten === 1) {
    alert('funciono');
  } else {
    document.getElementById('boton').disabled=true;
  }
}

function url(uri) {
    location.href = uri; 
}