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

// DEFINITION DU BOUTON DE LA BARRE D'EDITION
jsToolBar.prototype.elements.VideoInsert = {
	type: 'button',
	title: 'Video Insert',
	icon: 'index.php?pf=VideoInsert/icon.png',
	fn:{},
	fncall:{},
	open_url:'plugin.php?p=VideoInsert&popup=1',
	data:{},
	popup: function() {
		window.the_toolbar = this;
		this.elements.VideoInsert.data = {};
		
		var p_win = window.open(this.elements.VideoInsert.open_url,'dc_popup',
		'alwaysRaised=yes,dependent=yes,toolbar=yes,height=470,width=600,'+
		'menubar=no,resizable=yes,scrollbars=yes,status=no');
	},
};
// OUVERTURE DU POPUP POUR LES TROIS MODES
jsToolBar.prototype.elements.VideoInsert.fn.wiki = function() {
	this.elements.VideoInsert.popup.call(this);
};
jsToolBar.prototype.elements.VideoInsert.fn.xhtml = function() {
	this.elements.VideoInsert.popup.call(this);
};
jsToolBar.prototype.elements.VideoInsert.fn.wysiwyg = function() {
	this.elements.VideoInsert.popup.call(this);
};
// AJOUT DU TEXTE AU POST POUR LES TROIS MODES
jsToolBar.prototype.elements.VideoInsert.fncall.wiki = function() {
	var d = this.elements.VideoInsert.data;
	this.encloseSelection('','',function() {
		return d.texte;
	});
};
jsToolBar.prototype.elements.VideoInsert.fncall.xhtml = function() {
	var d = this.elements.VideoInsert.data;
	this.encloseSelection('','',function() {
		return d.texte;
	});
};
jsToolBar.prototype.elements.VideoInsert.fncall.wysiwyg = function() {
	var d = this.elements.VideoInsert.data;
	temp = document.createTextNode(d.texte);
	this.insertNode(temp);
};