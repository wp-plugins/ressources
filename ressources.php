<?php
/*
  Plugin Name: Ressources
  Plugin URI: http://ecolosites.eelv.fr
  Description:  Display the server ressources on the dashboard
  Version: 0.4.0
  Author: bastho
  License: GPLv2
  Domain Path: /languages/
 */
$Ressources = new Ressources();

class Ressources {

    function Ressources() {

	load_plugin_textdomain( 'ressources', false, 'ressources/languages' );

	$this->gb_to = microtime(true);
	add_action('wp_network_dashboard_setup', array($this, 'dashboard_setup'));
	add_action('wp_dashboard_setup', array($this, 'dashboard_setup'));
	add_action('admin_bar_menu', array($this, 'adminbar'), 100);
	add_action('admin_enqueue_scripts', array($this, 'scripts'));
	add_action('wp_ajax_ressources_widget_content', array($this, 'ajax'));
    }
    function scripts($hook){
	if('index.php'!==$hook){
	    return;
	}
	wp_enqueue_style('ressources', plugins_url('/ressources.css', __FILE__), false, null);
	wp_enqueue_script('ressources', plugins_url('/ressources.js', __FILE__), array('jquery'), '', true);
    }

    function dashboard_setup() {
	if (is_super_admin()) {
	    wp_add_dashboard_widget('ressources_machine', __('Technical informations','ressources'), array($this, 'widget_machine'));
	    wp_add_dashboard_widget('ressources_memory', __('Memory','ressources'), array($this, 'widget_memory'));
	    wp_add_dashboard_widget('ressources_disk', __('Disk usage','ressources'), array($this, 'widget_disk'));
	    wp_add_dashboard_widget('ressources_top', __('Process','ressources'), array($this, 'widget_top'));
	    wp_add_dashboard_widget('ressources_cpu', __('CPU Load average','ressources'), array($this, 'widget_cpu'));
	}
    }
    /*
     * Dashboard Widgets
     */
    function widget_machine() {
	exec('cat /etc/issue',$system);
	?>
	<p class=twice"><?php printf(__('Blog: %s','ressources'),'<b>#'.get_current_blog_id().'</b>') ?></p>
	<p class=twice"><?php printf(__('On machine: %s','ressources'),'<b>'.gethostname().'</b>') ?></b></p>
	<p><?php printf(__('System: %s','ressources'),'<b>'.str_replace('\n \l','',$system[0]).'</b>') ?></p>
	<p><?php printf(__('Web server: %s, PHP %s','ressources'),'<b>'.$_SERVER['SERVER_SOFTWARE'].'</b>','<b>'.phpversion().'</b>') ?></p>
	<p><?php printf(__('SQL version: %s','ressources'),'<b>'.$this->getMySQLVersion().'</b>') ?></p>
	<?php
    }
    function widget_memory() {
	?>
	<table class="widefat sortable" style="margin-top: 1em;">
	    <thead>
		<tr>
		    <th><?php _e('Memory','ressources') ?></th>
		    <th><?php _e('Size','ressources') ?></th>
		    <th><?php _e('Used','ressources') ?></th>
		    <th><?php _e('Use %','ressources') ?></th>
		</tr>
	    </thead>
	    <tbody class="ressources-widget-content" data-id="memory" data-refresh="5">
	    </tbody>
	</table>
    <?php
    }

    function widget_disk() {
	?>
	<table class="widefat sortable" style="margin-top: 1em;">
	    <thead>
		<tr>
		    <th><?php _e('Filesystem','ressources') ?></th>
		    <th><?php _e('Size','ressources') ?></th>
		    <th><?php _e('Used','ressources') ?></th>
		    <th><?php _e('Use %','ressources') ?></th>
		</tr>
	    </thead>
	    <tbody class="ressources-widget-content" data-id="disk" data-refresh="0">
	    </tbody>
	</table>
    <?php
    }

    function widget_top() {
	?>
	<table class="widefat sortable" style="margin-top: 1em;">
	    <thead>
		<tr>
		    <th><?php _e('User','ressources') ?></th>
		    <th><?php _e('%CPU','ressources') ?></th>
		    <th><?php _e('%MEM','ressources') ?></th>
		    <th><?php _e('TIME','ressources') ?></th>
		    <th><?php _e('COMMAND','ressources') ?></th>
		</tr>
	    </thead>
	    <tbody class="ressources-widget-content" data-id="top" data-refresh="5">
	    </tbody>
	</table>
	<?php
    }
    function widget_cpu() {
	?>
	<table class="widefat sortable" style="margin-top: 1em;">
	    <thead>
		<tr>
		    <th><?php _e('TIME','ressources') ?></th>
		    <th><?php _e('CPU Load average','ressources') ?></th>
		</tr>
	    </thead>
	    <tbody class="ressources-widget-content" data-id="cpu" data-refresh="20">
	    </tbody>
	</table>
	<?php
    }
    /*
     * Dashboard widgets content
     */
    function content_memory(){
	exec('free -mo', $ram);
	preg_match_all('/\s+([0-9]+)/', $ram[1], $matches);
	list($total, $used, $free, $shared, $buffers, $cached) = $matches[1];
	$prc = $used / $total * 100;
	?>
	<tr>
	    <td>RAM</td>
	    <td><?php echo  $total ?>M</td>
	    <td><?php echo  $used ?>M</td>
	    <td width='40%'>
		<div class="progress-container"><div class="progress-bar" style="width:<?php echo $prc ?>%;"></div></div>
	    </td>
	</tr>
	<?php
    }
    function content_disk(){
	$chem = ABSPATH . '/wp-content';
	exec('du -h --max-depth=1 ' . $chem, $che);
	$info = explode("\t", array_pop($che));
	?>
	<tr>
	    <td colspan="3"><?php _e('space used by wp-content:','ressources') ?></td>
	    <td><b><?php echo  $info[0];  ?></b></td>
	</tr>
	<?php
	exec('df -h', $out);
	foreach ($out as $res) {

	    $res = preg_replace('/[ \t\r\n\v\f]+/i', ' ', $res);
	    $ressources = explode(' ', $res, 6);
	    $nom = $ressources[0];
	    $total = $ressources[1];
	    $used = $ressources[2];
	    $prc = $ressources[4];
	    if ($nom != 'Filesystem' && $nom != '') {
		?>
		<tr>
		    <td><?php echo  $nom ?></td>
		    <td><?php echo  $total ?></td>
		    <td><?php echo  $used ?></td>
		    <td width='40%'><div class="progress-container"><div class="progress-bar" style="width:<?php echo  $prc ?>;"></div></div></td>
		</tr>
		<?php
	    }
	}
    }
    function content_top(){
	exec('ps aux | sort', $cpu);
	$limit = 10;
	$l=0;
	foreach ($cpu as $res) {
	    $l++;
	    if($l>=$limit){
		return;
	    }
	    $res = preg_replace('/[ \t\r\n\v\f]+/i', ' ', $res);
	    $res = str_replace('USER PID', 'USERPID', $res);
	    $ressources = explode(' ', $res);
	    if ($ressources[0] != 'USERPID' && $ressources[0] != '') {
		?>
		<tr>
		    <td><?php echo  $ressources[0] ?></td>
		    <td><div class="progress-container"><div class="progress-bar" style="width:<?php echo  round(str_replace('%', '', $ressources[2])) ?>%;"></div></div></td>
		    <td><div class="progress-container"><div class="progress-bar" style="width:<?php echo  round(str_replace('%', '', $ressources[3])) ?>%;"></div></div></td>
		    <td><?php echo  $ressources[9] ?></td>
		    <td><?php echo  $ressources[10] ?></td>
		</tr>
		<?php
	    }
	}
    }
    function content_cpu(){
	exec('cat /proc/loadavg', $cpuloadavg);
	if($cpuloadavg){
	    $load = explode(' ',$cpuloadavg[0]);
	    ?>
	    <tr>
		<td>1 MIN</td>
		<td><?php echo  $load[0] ?></td>
	    </tr>
	    <tr>
		<td>5 MIN</td>
		<td><?php echo  $load[1] ?></td>
	    </tr>
	    <tr>
		<td>15 MIN</td>
		<td><?php echo  $load[2] ?></td>
	    </tr>
	    <?php
	}
    }
    function getMySQLVersion() {
	$output = shell_exec('mysql -V');
	preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
	return $version[0];
    }
    function ajax(){
	$datas = \filter_input(INPUT_GET, 'datas');
	if(method_exists($this, 'content_'.$datas)){
	    call_user_method('content_'.$datas, $this);
	}
	exit;
    }



    function adminbar($admin_bar) {
	if (is_super_admin()) {
	    $dif = round((microtime(true) - $this->gb_to),2);

	    $hn = gethostname();
	    $admin_bar->add_menu(array(
		'id' => 'wichmachin',
		'title' => '<span class="ab-icon dashicons dashicons-flag"></span> <span class="ab-label">' . $hn . '</span>',
		'href' => '#',
		'meta' => array(
		    'title' => sprintf(__('#%1$s on %2$s in %3$s s','ressources'),get_current_blog_id(),$hn,$dif),
		    'class' => 'machine-' . $hn . ''
		),
	    ));
	}
    }
}