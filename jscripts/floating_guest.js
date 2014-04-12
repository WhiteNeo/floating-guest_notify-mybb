/* 
 * Plataforma: MyBB 1.6.x
 * Autor: Dark Neo
 * Plugin: Ventana Flotante
 * version: 1.2
 * 
 */

function cerrar(showHideDiv, switchTextDiv) {

	var ele = document.getElementById(showHideDiv);
	var text = document.getElementById(switchTextDiv);
	if(ele.style.display == "block") {
        ele.style.display = "block";
		img.innerHTML = "close.png";
  	}
	else {
		ele.style.display = "none";
		img.innerHTML = "show.png";
	}
}
