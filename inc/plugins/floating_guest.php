<?php
/* 
 * Plataforma: MyBB 1.6.x
 * Autor: Dark Neo
 * Plugin: Ventana Flotante
 * version: 1.2
 * 
 */

// Inhabilitar acceso directo a este archivo
if(!defined("IN_MYBB"))
{
	die("You can't access this file directly. Make sure IN_MYBB is defined");
}

// Añadir hooks
$plugins->add_hook("global_start", "floating_guest");

// Plugin Info
function floating_guest_info(){
	
	global $mybb, $cache, $db, $lang;

    $lang->load("floating_guest", false, true);	
    
	$new_config_link = '';

	$query = $db->simple_select('settinggroups', '*', "name='floating_guest'");

	if (count($db->fetch_array($query)))
	{
		$new_config_link = '(<a href="index.php?module=config&action=change&search=floating_guest" style="color:#035488;">'.$lang->vfi_configure.'</a>)';
	}
	
	return array(
		"name"			=> $lang->vfi_name,
		"description"	=> $lang->vfi_descrip . " " . $new_config_link,
		"website"		=> "http://www.forosmybb.es",
		"author"		=> "Dark Neo",
		"authorsite"	=> "http://darkneo.skn1.com",
		"version"		=> "1.2",
		"guid" 			=> "6ae72ff74acefbbb2a6a4a9716d91078",
		"compatibility" => "16*"
	);
}


//Se ejecuta al activar el plugin
function floating_guest_activate(){
    //Variables que vamos a utilizar
   	global $mybb, $cache, $db, $lang;

    $lang->load("floating_guest", false, true);
    
    // Crear el grupo de opciones
    $query = $db->simple_select("settinggroups", "COUNT(*) as rows");
    $rows = $db->fetch_field($query, "rows");

    $new_groupconfig = array(
        'name' => 'floating_guest',
        'title' => $lang->vfi_title,
        'description' => $lang->vfi_title_descrip,
        'disporder' => $rows+1,
        'isdefault' => 0
    );

    $group['gid'] = $db->insert_query("settinggroups", $new_groupconfig);

    // Crear las opciones del plugin a utilizar
    $new_config = array();

    $new_config[] = array(
        'name' => 'floating_guest_active',
        'title' => $lang->vfi_power,
        'description' => $lang->vfi_power_descrip,
        'optionscode' => 'yesno',
        'value' => '1',
        'disporder' => 10,
        'gid' => $group['gid']
    );

    $new_config[] = array(
        'name' => 'floating_guest_css',
        'title' => $lang->vfi_cssdiv,
        'description' => $lang->vfi_cssdiv_descrip,
        'optionscode' => 'textarea',
        'value' => 'opacity:0.85;
filter: alpha(opacity=85);
border:2px solid #2266AA;  
border-radius: 10px;
-ms-border-radius: 10px;
-moz-border-radius: 10px;
-webkit-border-radius: 10px;
-ms-box-shadow: inset 0 0 10px #000000;
-moz-box-shadow: inset 0 0 10px #000000;
-webkit-box-shadow: inset 0 0 10px #000000;
box-shadow: inset 0 0 10px #000000;',
        'disporder' => 20,
        'gid' => $group['gid']
    );

    $new_config[] = array(
        'name' => 'floating_guest_text1',
        'title' => $lang->vfi_text1,
        'description' => $lang->vfi_text1_descrip,
        'optionscode' => 'text',
        'value' => 'Hello, Guest!',
        'disporder' => 30,
        'gid' => $group['gid']
    );
    
    $new_config[] = array(
        'name' => 'floating_guest_text1css',
        'title' => $lang->vfi_csstext1,
        'description' => $lang->vfi_csstext1_descrip,
        'optionscode' => 'textarea',
        'value' => $db->escape_string('color:blue; 
font-family:\'Lucida Console\';
font-size:16px;
text-shadow:0.3em 0.3em 0.2em #060;'),
        'disporder' => 40,
        'gid' => $group['gid']
    );    
    
    $new_config[] = array(
        'name' => 'floating_guest_text2',
        'title' => $lang->vfi_text2,
        'description' => $lang->vfi_text2_descrip,
        'optionscode' => 'textarea',
        'value' => 'Please <a href="member.php?action=register">Register</a>, or <a href="member.php?action=login">Login</a><br />
to see entire forum content...<br />',
        'disporder' => 50,
        'gid' => $group['gid']
    );    

    $new_config[] = array(
        'name' => 'floating_guest_text2css',
        'title' => $lang->vfi_csstext2,
        'description' => $lang->vfi_csstext2_descrip,
        'optionscode' => 'textarea',
        'value' => $db->escape_string('color:black; 
font-family:\'Georgia\';
font-size:14px;
text-shadow:0.3em 0.3em 0.2em #600;'),
        'disporder' => 60,
        'gid' => $group['gid']
    );

    $new_config[] = array(
        'name' => 'floating_guest_img',
        'title' => $lang->vfi_img,
        'description' => $lang->vfi_img_descrip,
        'optionscode' => 'text',
        'value' => 'images/floating_guest/background.jpg',
        'disporder' => 70,
        'gid' => $group['gid']
    );        

    $new_config[] = array(
        'name' => 'floating_guest_width',
        'title' => $lang->vfi_width,
        'description' => $lang->vfi_width_descrip,
        'optionscode' => 'text',
        'value' => '300',
        'disporder' => 80,
        'gid' => $group['gid']
    );

    $new_config[] = array(
        'name' => 'floating_guest_height',
        'title' => $lang->vfi_height,
        'description' => $lang->vfi_height_descrip,
        'optionscode' => 'text',
        'value' => '70',
        'disporder' => 90,
        'gid' => $group['gid']
    );
    
    $new_config[] = array(
        'name' => 'floating_guest_align',
        'title' => $lang->vfi_aligny,
        'description' => $lang->vfi_aligny_descrip,
        'optionscode' => 'select \n 1='.$lang->vfi_up.' \n 2='.$lang->vfi_down,
        'value' => '1',
        'disporder' => 100,
        'gid' => $group['gid']
    );      
    
    $new_config[] = array(
        'name' => 'floating_guest_align_hv',
        'title' => $lang->vfi_alignx,
        'description' => $lang->vfi_alignx_descrip,        
        'optionscode' => 'select \n 1='.$lang->vfi_left.' \n 2='.$lang->vfi_right,
        'value' => '1',
        'disporder' => 110,
        'gid' => $group['gid']
    );                
        
    foreach($new_config as $array => $content)
    {
        $db->insert_query("settings", $content);
    }

    rebuild_settings();

    //Archivo requerido para reemplazo de plantillas
	require "../inc/adminfunctions_templates.php";
    //Reemplazos que vamos a hacer en las plantillas 1.- Platilla 2.- Contenido a Reemplazar 3.- Contenido que reemplaza lo anterior
	find_replace_templatesets('headerinclude', '#^(.*)$#s', "\\1\r\n" . '<script type="text/javascript" src="{\$mybb->settings[\'bburl\']}/jscripts/floating_guest.js"></script>');
    find_replace_templatesets("header_welcomeblock_guest", '#^(.*)$#s', '{\$floating_guest}' . "\\1\r\n");
    //Se actualiza la info de las plantillas
	$cache->update_forums();

    return true;

}

// Deactivate The Plugin
function floating_guest_deactivate(){
    //Variables que vamos a utilizar
	global $mybb, $cache, $db;
    // Borrar el grupo de opciones
    $query = $db->simple_select("settinggroups", "gid", "name = \"floating_guest\"");
    $rows = $db->fetch_field($query, "gid");
	if($rows){
    //Eliminamos el grupo de opciones
    $db->delete_query("settinggroups", "gid = {$rows}");        
    // Borrar las opciones
    $db->delete_query("settings", "gid = {$rows}");
    rebuild_settings();
	}

    //Archivo requerido para reemplazo de templates
	require "../inc/adminfunctions_templates.php";
    //Reemplazos que vamos a hacer en las plantillas 1.- Platilla 2.- Contenido a Reemplazar 3.- Contenido que reemplaza lo anterior
	find_replace_templatesets('headerinclude', '#'.preg_quote('<script type="text/javascript" src="{$mybb->settings[\'bburl\']}/jscripts/floating_guest.js"></script>').'#', '', 0);
    find_replace_templatesets("header_welcomeblock_guest", '#'.preg_quote('{$floating_guest}').'#', '',0);
    //Se actualiza la info de las plantillas
	$cache->update_forums();

    return true;

}

function floating_guest()
{
  	global $mybb, $floating_guest;
  	
  	if($mybb->settings['floating_guest_active'] == '0'){
		return false;
		}

    $vf_css = $mybb->settings['floating_guest_css'];
    $vf_texto1 = $mybb->settings['floating_guest_text1'];
    $vf_texto1_css = $mybb->settings['floating_guest_text1css'];
    $vf_texto2 = $mybb->settings['floating_guest_text2'];
    $vf_texto2_css = $mybb->settings['floating_guest_text2css'];
    $vf_imagen = $mybb->settings['floating_guest_img'];    
    $vf_ancho = $mybb->settings['floating_guest_width'];
    $vf_cerrar = $mybb->settings['floating_guest_width'] + 15;
    $vf_alto = $mybb->settings['floating_guest_height'];
    $vf_arriba = $mybb->settings['floating_guest_align'];
    $vf_izquierda = $mybb->settings['floating_guest_align_hv'];

if( $vf_arriba == 1 && $vf_izquierda == 1){    
    $vf_align = "top: 20px;
        left: 20px;";
    }
if( $vf_arriba == 1 && $vf_izquierda == 2){        
    $vf_align = "top: 20px;
        right: 20px;";
}
if( $vf_arriba == 2 && $vf_izquierda == 1){            
    $vf_align = "bottom: 20px;
        left: 20px;";       
}
if( $vf_arriba == 2 && $vf_izquierda == 2){        
    $vf_align = "bottom: 20px;
        right: 20px;";       
}           
  	$floating_guest = "<div id=\"floating_guest\" style=\"  
        position:fixed;  
        width:{$vf_ancho}px;
        height:{$vf_alto}px;
        {$vf_align} 
        padding: 16px;
        background-image:url('{$vf_imagen}');
        {$vf_css} 
        z-index:100\">  
    <div id=\"close\" style=\"
       position:absolute; 
        left:{$vf_cerrar}px; 
        top:-5px;\">
    <a id=\"imagen\" href=\"javascript:cerrar('floating_guest','imagen');\"><img src=\"images/floating_guest/close.png\"></a></div>
    <span style=\"{$vf_texto1_css}\">
    <b>{$vf_texto1}</b></span><br />
    <span style=\"{$vf_texto2_css}\">
    {$vf_texto2}
    </span>
    </div>";
  			 	
}

?>
