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
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('flags');

	tinymce.create('tinymce.plugins.FlagsPlugin', {
		init : function(ed, url, eurl) {
			// Register commands
			ed.addCommand('mceFlags', function() {
				var furl = tinyMCE.activeEditor.getParam('plugin_flags_url');
				ed.windowManager.open({
					file : url + '/flags.php' + furl,
					width : 260 + parseInt(ed.getLang('flags.delta_width', 0)),
					height : 120 + parseInt(ed.getLang('flags.delta_height', 0)),
					inline : 1,
					resizable : "no"
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('flags', {title : 'flags.desc', image : url + '/img/flag.gif', cmd : 'mceFlags'});
		},

		getInfo : function() {
			return {
				longname : 'Flags',
				author : 'Petr Hota, Edencms.com',
				authorurl : 'http://www.edencms.com',
				infourl : 'http://www.edencms.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('flags', tinymce.plugins.FlagsPlugin);
})(tinymce);