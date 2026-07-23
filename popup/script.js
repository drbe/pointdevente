// Get the modal
var modal = document.getElementById("myModal");
var modal_client = document.getElementById("myMod_client");

// Get the button that opens the modal
//var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

/* When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}*/

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
function edit(id)
{
  modal.style.display = "block";
  
  jQuery('#nom_produit_popup').html(jQuery('#nom_produit_'+id).html());
  jQuery('#id_produit_popup').html(id);
  jQuery('.form-control').val("");
  jQuery('#p_prix_u').html(jQuery('#pu_'+id).html());
  jQuery('#p_prix_t').html(jQuery('#pu_'+id).html());
  jQuery('.form-control').val(jQuery('#qte_'+id).html());
}
function close_pop()
{
	id=$("#id_produit_popup").html();
  modal.style.display = "none";
  jQuery('#produit_a_'+id).fadeOut( 1000 );
  jQuery('#produit_a_'+id).remove();
  total_tableau();
}
function close_pop_edit()
{
	id=$("#id_produit_popup").html();
    modal.style.display = "none";
	jQuery('#qte_'+id).html( jQuery('#input_qte').val() );
	q_total= parseFloat(jQuery('#pu_'+id).html()*jQuery('#qte_'+id).html()) ;
	q_total.toFixed(3);
	jQuery('#pt_'+id).html( trois_(q_total) );

	total_tableau();
}
function cal_qte()
{
	id=$("#id_produit_popup").html();
	jQuery('#p_prix_t').html(trois_(parseFloat(jQuery('#input_qte').val())*parseFloat(jQuery('#pu_'+id).html())));
}