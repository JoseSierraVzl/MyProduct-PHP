function removeProduct(id){

    var confirmarEliminar = confirm('¿Esta seguro de eliminar este producto?'+id);

    if (!confirmarEliminar) { return; }
    
    $.ajax({
      method: "POST",
      url: "db/removeProduct.php",
      data: { id : id}
    })
    .then(respuesta => window.location.reload());

}

