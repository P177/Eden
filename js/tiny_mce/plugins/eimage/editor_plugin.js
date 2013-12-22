/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function(tinymce) {
	tinymce.create('tinymce.plugins.EimagePlugin', {
		init : function(ed, url, eurl) {
			// Register commands
			ed.addCommand('mceEimage', function() {
				var furl = tinyMCE.activeEditor.getParam('plugin_eimage_url');
				ed.windowManager.open({
					file : url + '/eimage.php' + furl,
					width : 400 + parseInt(ed.getLang('eimage.delta_width', 0)),
					height : 350 + parseInt(ed.getLang('eimage.delta_height', 0)),
					inline : "no"
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('eimage', {title : 'eimage.eimage_desc', image : url + '/img/eimage.gif', cmd : 'mceEimage'});
		},

		getInfo : function() {
			return {
				longname : 'Eimage',
				author : 'Petr Hota, edencms.com',
				authorurl : 'http://www.edencms.eu',
				infourl : 'http://www.edencms.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('eimage', tinymce.plugins.EimagePlugin);
})(tinymce);