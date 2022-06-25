const id_materia = document.getElementById('id_materia'), 
id_docente = document.getElementById('id_docente'), 
id_grupo = document.getElementById('grupo')
//console.log(hora_inicio)
const id_materia_compartido = document.getElementById('id_materia_compartido')
if (id_materia) {
    id_materia.addEventListener('change', mostrarGrupo)

} 
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
if(id_materia_compartido){
    id_materia_compartido.addEventListener('change', mostrarDocenteGrupo)
}
function mostrarDocenteGrupo(e){
 console.log(e.target.value, id_materia_compartido.value)
 requerirDocenteGrupo(e.target.value, id_materia_compartido.value)
}
const requerirDocenteGrupo= async (id_materia_compartido) => {
    try {
        const body={"id_materia":id_materia_compartido}
        const request=await fetch('http://asignaciondeaulas/controlador/mostrarDocenteGrupo.php?opcion=getDocenteGrupo', {
            method:'POST',
            body:JSON.stringify(body)
        })
        const response=await request.json()
        //console.log(response)
        $('#grupo').empty();
        let plantilla = '';
        console.log(response);
        if(response.length==0){
            plantilla = '<option value="">Seleccionar docentes...</option>';
        }
        response.forEach(grupo => {
            plantilla += `<option value='${grupo.id_grupo}'>${grupo.nombre_docente} - ${grupo.id_grupo}</option>`
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
function validationForm(e) {
    var valida = false;
    var valida2 = false;
    $('#id_materia_compartido').find("option:selected").each(function() {
        if ($(this).val().trim() == '') {
            var styles = {
                border: "1px solid red",
            };
            valida = false;
            $('#id_materia_compartido').css(styles);
            
        } else {
            valida = true;
        }
    });
    $('#grupo').find("option:selected").each(function() {
        if ($(this).val().trim() == '') {
            var styles = {
                border: "1px solid red",
            };
            valida2 = false;
            $('#grupo').css(styles);
            
        } else {
            valida2 = true;
        }
    });
    if (valida) {
        var styles = {
            border: "1px solid green",
        };
        $('#id_materia_compartido').css(styles);
    } else {
        var styles = {
            border: "1px solid red",
        };
        $('#id_materia_compartido').css(styles);
        alert("Error!!\nDebe seleccionar una materia!");
    }
    if (valida2) {
        var styles = {
            border: "1px solid green",
        };
        $('#grupo').css(styles);
    } else {
        var styles = {
            border: "1px solid red",
        };
        $('#grupo').css(styles);
        if(valida){
            alert("Error!!\nDebe seleccionar un grupo!");
        }
    }
    var validate= valida && valida2;
    if (validate) {
        console.log(valida, valida2);
        
        return true;
    }else
    {
        return false;
    }
}
$('.grupoMultiple').click(function() {
    $('#grupo').empty().append('<option value="">Seleccionar docentes...</option>');
});

