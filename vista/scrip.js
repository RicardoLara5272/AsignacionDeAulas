const id_materia = document.getElementById('id_materia'), 
id_docente = document.getElementById('id_docente'), 
id_grupo = document.getElementById('grupo')
//console.log(hora_inicio)
 
id_materia.addEventListener('change', mostrarGrupo)

function mostrarGrupo(e){
 console.log(e.target.value, id_docente.value)
 requerirGrupo(e.target.value, id_docente.value)
}

const requerirGrupo= async (id_materia, id_docente) => {
    try {
        const body={"id_materia":id_materia, "id_docente":id_docente}
        const request=await fetch('http://asignaciondeaulas/controlador/mostrarGrupo.php?opcion=getGrupo', {
            method:'POST',
            body:JSON.stringify(body)
        })
        const response=await request.json()
        //console.log(response)
        let plantilla = ''
        response.forEach(grupo => {
            plantilla += `<option value='${grupo.id_grupo}'>${grupo.id_grupo}</option>`
        });
        id_grupo.innerHTML=plantilla
    } catch (error) {
        console.log(error)
    }
}


function obtenerhorainicio() {
    var i, cont, tamano;
    
    var select = document.getElementById('hora_fin');
    var option = select.options[select.selectedIndex];
    
    var mostrar = document.getElementById('hora_inicio');
    var opciones = mostrar.options[mostrar.selectedIndex];
    
    var valor = opciones.value;
    tamano = Number(valor);
    
    if (select.options.length != 0 || tamano == "") {
        for ( i = 0; i <= 1; i += 1 ) {
            select.remove( option );
        }
    }
    if(tamano == "")
    {
        option=document.createElement('option');
        option.value = opciones.value;
        option.text = "Primero seleccione hora inicio..."; 
        select.add( option );
    }
    if(tamano  < 9 && tamano > 0){
        cont = Number(valor)+1;
        for ( i = 0; i <= 1; i += 1 ) {    
            option=document.createElement('option');
            option.value = i;
            option.text = mostrar.options[cont].innerText; 
            select.add( option );
            cont++;
        }
    }
}

