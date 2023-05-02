<?php
// -- BEGIN LICENSE BLOCK ----------------------------------
//
// This file is a plugin for Dotclear 2.
// 
// Copyright (c) 2013 FredM
// Licensed under the GPL version 2.0 license.
// A copy of this license is available in LICENSE file or at
// http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
//
// -- END LICENSE BLOCK ------------------------------------
if (!defined('DC_CONTEXT_ADMIN')) { return; }

require_once dirname(__FILE__).'/providers.php';

#Settings
dcCore::app()->blog->settings->addNamespace('videoinsert');
$s =& dcCore::app()->blog->settings->videoinsert; 
//$core->blog->settings->addNamespace('videoinsert');
//$s =& $core->blog->settings->videoinsert;

# Init var
$p_url = 'plugin.php?p='.basename(dirname(__FILE__)).'&popup=1';

# Saving configurations
$param_action = !empty($_POST['param_action']) ? $_POST['param_action'] : null;
if ($param_action == 'save') {
	$s->put('default_width', $_POST['width'], 'integer');
	$s->put('default_height', $_POST['height'], 'integer');
	$s->put('default_align', $_POST['align'], 'string');
	$s->put('default_start', $_POST['start'], 'integer');
	$s->put('default_password', $_POST['password'], 'string');
	http::redirect($p_url.'&saveok=1');
}
if ($param_action == 'restore') {
	$s->drop('default_width');
	$s->drop('default_height');
	$s->drop('default_align');
	$s->drop('default_start');
	$s->drop('default_password');
	http::redirect($p_url.'&restoreok=1');
}

?>
<html>
<head>
  <title>-= Video Insert =-</title>
  
  <script type="text/javascript">
//<![CDATA[
$(function() {
		// FERMETURE DU POPUP
	$('#cancel_btn').click(function() {
		window.close();
		return false;
	});
	
		// AJOUT DE LA VIDEO
	$('#add_video_btn').click(function() {
		var add_video_form = $('#add_video_form').get(0);
		add_video_form.param_action.value = 'addvideo';
		add_video_form.submit();
		return false;
	});

		// SAUVEGARDE LES OPTIONS
	$('#save_param_btn').click(function() {
		var add_video_form = $('#add_video_form').get(0);
		add_video_form.param_action.value = 'save';
		add_video_form.submit();
		return false;
	});
	
		// RESTAURE LES OPTIONS
	$('#restore_param_btn').click(function() {
		var add_video_form = $('#add_video_form').get(0);
		add_video_form.param_action.value = 'restore';
		add_video_form.submit();
		return false;
	});
	
		// AJOUT DU BOUTON DEROULER POUR LES OPTIONS
	//$('h3.options').toggleWithLegend($('div.options'),{cookie:'dcx_videoinsert_options',speed:200,legend_click:true});
	//$('h4.help').toggleWithLegend($('div.help'),{speed:200});
	$('#videoinsertoptions h3:first').toggleWithLegend($('#videoinsertoptions div:first'),{cookie:'dcx_videoinsert_options',speed:200,legend_click:true});
	$('#videoinserthelp h4:first').toggleWithLegend($('#videoinserthelp div:first'),{speed:200,legend_click:true});
	
});

//]]>
  </script>
  
</head>
<body>
<h2>-= Video Insert =-</h2>

<?php

if ($param_action == 'addvideo') {
	
	if (empty($_REQUEST['videoID'])) {
	
		echo '<p class="clear form-note warning">'.__('You have to enter a video ID or URL').'</p>';
		
	} else {
		
		$given_text = $_REQUEST['videoID'];
		$provid = '';
		$videoID = '';
		
			// Tente de reconnaitre l'url et d'extraire l'ID
		foreach ($provider as $k => $v)
			foreach ($v['urlidextract'] as $i => $j)
				if (preg_match($j, $given_text, $matches)) {
					$provid = $k;
					$videoID = $matches[1];
					break 2;
				}
		
			// Tente de reconnaitre l'ID
		if (empty($videoID))
			foreach ($provider as $k => $v)
				if (preg_match($v['idrecognize'], $given_text)) {
					$provid = $k;
					$videoID = $given_text;
					break;
				}
		
		if (!empty($videoID)) {
			
			echo '<p class="clear form-note info">' . sprintf(__('Adding %s video id=%s.'),$provid,$videoID) . '</p>';
			
			$options = '';
			if ($_REQUEST['width'] != 425)
				$options .= ' width=\'' . $_REQUEST['width'] . '\'';
			if ($_REQUEST['height'] != 350)
				$options .= ' height=\'' . $_REQUEST['height'] . '\'';
			if ($_REQUEST['start'] != 0)
				$options .= ' start=\'' . $_REQUEST['start'] . '\'';
			if ($_REQUEST['align'] != 'center')
				$options .= ' align=\'' . $_REQUEST['align'] . '\'';
			if ($_REQUEST['password'] != '')
				$options .= ' password=\'' . $_REQUEST['password'] . '\'';
			
			$texte = '::video ' . $provid . ' id=\'' . $videoID . '\'' . $options .'::';
			
			echo '<script type="text/javascript">
				var tb = window.opener.the_toolbar;
				var data = tb.elements.VideoInsert.data;
				data.texte = "'.$texte.'";
				tb.elements.VideoInsert.fncall[tb.mode].call(tb);
				window.close();
			</script>';
			
			
		} else {
			echo '<p class="clear form-note warning">'.__('Unable to recognize video provider or video ID.').'</p>';
		}
	}
}

	// Enregistrement / Restauration des options
if (!empty($_REQUEST['saveok'])) {
	echo '<p class="message">'.__('Default options saved.').'</p>';
}
if (!empty($_REQUEST['restoreok'])) {
	echo '<p class="message">'.__('Default options restored.').'</p>';
}

// DEBUT DU FORMULAIRE //

echo '<form id="add_video_form" action="'.$p_url.'&popup=1" method="post">'."\n";

echo '<h3>'.__('Insert video URL or video ID').'</h3>'."\n";
$videoid = isset($_REQUEST['videoID']) ? $_REQUEST['videoID'] : '';
echo '<p>' . form::field('videoID',70,255,$videoid) . '</p>'."\n";

echo '<div id="videoinsertoptions">'."\n";
echo '<h3>'.__('Options').'</h3>'."\n";
echo '<div>'."\n";

$default_width = isset($_REQUEST['width']) ? $_REQUEST['width'] : $s->default_width;
if (empty($default_width)) $default_width = 425;
echo '<p><label class="classic">'.__('Width').' : ' . form::field('width',5,5,$default_width) . '</label> x <label class="classic">'."\n";

$default_height = isset($_REQUEST['height']) ? $_REQUEST['height'] : $s->default_height;
if (empty($default_height)) $default_height = 350;
echo __('Height'). ' : ' . form::field('height',5,5,$default_height) . '</label></p>'."\n";

$default_align = isset($_REQUEST['align']) ? $_REQUEST['align'] : $s->default_align;
if (empty($default_align)) $default_align = 'center';
echo '<p>'.__('Alignment').' : <label class="classic">' . form::radio(array('align'),'left',($default_align=='left')) . ' '.__('left').'</label> - <label class="classic">';
echo form::radio(array('align'),'center',($default_align=='center')) . ' '.__('center').'</label> - <label class="classic">';
echo form::radio(array('align'),'right',($default_align=='right')) . ' '.__('right').'</label></p>'."\n";

$default_start = isset($_REQUEST['start']) ? $_REQUEST['start'] : $s->default_start;
if (empty($default_start)) $default_start = '0';
echo '<p><label class="classic">'.__('Video start time (in seconds)').' : ' . form::field('start',4,4,$default_start) . '</label></p>'."\n";

$default_password = isset($_REQUEST['password']) ? $_REQUEST['password'] : $s->default_password;
if (empty($default_password)) $default_password = '';
echo '<p><label class="classic">'.__('Password for video protection'). ' : ' . form::field('password',20,255,$default_password) . '</label></p>'."\n";
echo '<p class="clear form-note info">'. __('Let empty for no private video management') . '</p>'."\n";

echo '<p><a id="save_param_btn" class="default" href="#">'.__('Save default options').'</a> - ';
echo '<a id="restore_param_btn" class="default" href="#">'.__('Restore default options').'</a></p>'."\n";
echo form::hidden(array('param_action'),'');
//echo $core->formNonce();
echo dcCore::app()->formNonce(); 

echo '</div>'."\n";
echo '</div>'."\n";

echo '<p>'."\n";
echo '<a id="add_video_btn" class="submit" href="#">'.__('Add video').'</a> - ';
echo '<a id="cancel_btn" class="button" href="#">'.__('Close').'</a>'."\n";
echo '</p>'."\n";
echo '</form>'."\n";

// FIN DU FORMULAIRE //

echo '<div id="videoinserthelp">'."\n";
//echo '<h4 class="help">'.__('Help').'</h4>';
echo '<h4>'.__('Help').'</h4>'."\n";
echo '<div>'."\n";
echo '<p>'.__('This plugin will automatically recognize video provider and video ID from your given data.').'</p>'."\n";
echo '<p>'.__('Here is the list of providers and data recognized. If your data doesn\'t match any of this example it is possible that it will not be recognized.').'</p>'."\n";

echo __('Video url example :')."\n<ul>\n";
foreach ($provider as $k => $v) {
	$favicon = ($v['favicon'] != '') ? '<img src="' . $v['favicon'] . '" alt="" width="16px" height="16px" /> ' : '';
	//echo '<li>'.$favicon.$k.'</li><ul>';
	echo '<li>'.$favicon.$k.'<ul>';
	foreach ($v['urlexample'] as $i => $j) {
		echo '<li>'.$j.'</li>';
	}
	//echo '</ul>'."\n";
	echo '</ul></li>'."\n";
}
echo '</ul>'."\n";

echo __('Video ID example :')."\n<ul>\n";
foreach ($provider as $k => $v) {
	$favicon = ($v['favicon'] != '') ? '<img src="' . $v['favicon'] . '" alt="" width="16px" height="16px" /> ' : '';
	//echo '<li>'.$favicon.$k.'</li><ul>';
	echo '<li>'.$favicon.$k.'<ul>';
	foreach ($v['idexample'] as $i => $j) {
		echo '<li>'.$j.'</li>';
	}
	//echo '</ul>'."\n";
	echo '</ul></li>'."\n";
}
echo '</ul>'."\n";

echo '</div>'."\n";
echo '</div>';

?>

</body>
</html>