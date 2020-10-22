/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function filter() {
    var filter, table, tr, td, i;
    filter = $("#unidad").val();
    table = document.getElementById("preguntas");
    tr = table.getElementsByTagName("tr");
    if(filter === 'all'){
        for (i = 0; i < tr.length; i++) {
          td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                tr[i].style.display = "";
            } 
        }
    }
    else{
        for (i = 0; i < tr.length; i++) {
          td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                console.log(filter +" === "+ idunidades[i]);
                if (idunidades[i]===filter) {
                    tr[i].style.display = "";
                } 
                else {
                    tr[i].style.display = "none";
                }
            } 
        }
    }
}

function search() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("buscar");
    filter = input.value.toUpperCase();
    table = document.getElementById("preguntas");
    tr = table.getElementsByTagName("tr");
    //alert("a");
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[1];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } 
            else {
                tr[i].style.display = "none";
            }
        } 
    }
}
function sure(value){
    //alert("fui presionado\n"+value);
    if(confirm("¿Está seguro de que quiere eliminar este registro?")){
        location.href = value;
    }   
}

