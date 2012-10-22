<?php
/*
Plugin Name: EELV : Ressources
Plugin URI: http://ecolosites.eelv.fr
Description:  Display the server ressources on the network dashboard
Version: 0.2
Author: Bastien Ho //  EELV
License: CC
*/

function eelv_ressources_add_sadmin_box() { 
 wp_add_dashboard_widget('eelv_view_ressources_mem', 'Memory','eelv_view_ressources_mem' );
 wp_add_dashboard_widget('eelv_view_ressources_disk', 'Disk','eelv_view_ressources_disk' );
 wp_add_dashboard_widget('eelv_view_ressources_cpu', 'Process','eelv_view_ressources_cpu' );
}
add_action('wp_network_dashboard_setup', 'eelv_ressources_add_sadmin_box' );

function eelv_view_ressources_mem(){
  ?>
  <style>
  .ressource_total{
	  width:95%;
	  margin:0 auto;
	  height:12px;
	  border-radius:4px;
	  background:#FFF;
	  border:#EEE 1px inset;
	  position:relative;
	  box-shadow:#CCC 0px 0px 3px inset;
  }
   .ressource_total div{
	  background:#0C3;
	  border:#3F5 1px outset;
	  border-radius:4px;
	  height:9px;
  }
  </style>
<?php 
exec('free -mo', $ram);
preg_match_all('/\s+([0-9]+)/', $ram[1], $matches);
list($total, $used, $free, $shared, $buffers, $cached) = $matches[1];
$prc = $used/$total*100;
?>
<table class="widefat sortable" style="margin-top: 1em;">
            <thead>
            	<tr>
                	<th>Memory</th>
                	<th>Size</th>
                	<th>Used</th>
                	<th>Use%</th>
                </tr>
            </thead>
            <tbody>
            <tr>
    	<td>RAM</td>
        <td><?=$total?>M</td>
        <td><?=$used?>M</td>
        <td width='40%'>
<div class="ressource_total"><div style="width:<?=$prc?>%;"></div></div>
</td>
</tbody></table>
<?php }






function eelv_view_ressources_disk(){
  ?>
  <?php 
$chem=str_replace(array('wp-admin/','network'),'',realpath(".")).'/wp-content';
exec('du -h --max-depth=1 '.$chem, $che);
$info = explode("\t",array_pop($che));
?>
<p>space used by wp-content : <b><?=$info[0]?></b></p>
<table class="widefat sortable" style="margin-top: 1em;">
            <thead>
            	<tr>
                	<th>Filesystem</th>
                	<th>Size</th>
                	<th>Used</th>
                	<th>Use%</th>
                </tr>
            </thead>
            <tbody>
<?php exec('df -h', $out);
foreach($out as $res){ 

	$res=preg_replace('/[ \t\r\n\v\f]+/i',' ',$res);
	$ressources=explode(' ',$res,6);
	$nom=$ressources[0];
	$total=$ressources[1];
	$used=$ressources[2];
	$prc=$ressources[4];
if($nom!='Filesystem' && $nom!=''){ ?>
	<tr>
    	<td><?=$nom?></td>
        <td><?=$total?></td>
        <td><?=$used?></td>
        <td width='40%'><div class="ressource_total"><div style="width:<?=$prc?>;"></div></div></td>
    </tr>
<?php 
} }
?>
</tbody></table>
<?php }




function eelv_view_ressources_cpu(){
  ?>  
<table class="widefat sortable" style="margin-top: 1em;">
            <thead>
            	<tr>
                	<th>USER</th>
                	<th>%CPU</th>
                	<th>%MEM</th>
                	<th>TIME</th>
                	<th>COMMAND</th>
                </tr>
            </thead>
            <tbody>
<?php exec('ps aux | sort',$cpu);
foreach($cpu as $res){ 

	$res=preg_replace('/[ \t\r\n\v\f]+/i',' ',$res);
	$res=str_replace('USER PID','USERPID',$res);
	$ressources=explode(' ',$res);
if($ressources[0]!='USERPID' && $ressources[0]!=''){ ?>
	<tr>
    	<td><?=$ressources[0]?></td>
    	<td><div class="ressource_total"><div style="width:<?=round(str_replace('%','',$ressources[2]))?>%;"></div></div></td>
    	<td><div class="ressource_total"><div style="width:<?=round(str_replace('%','',$ressources[3]))?>%;"></div></div></td>
    	<td><?=$ressources[9]?></td>
    	<td><?=$ressources[10]?></td>
    </tr>
<?php 
} }
?>
</tbody></table>
    <?php
	
	
}