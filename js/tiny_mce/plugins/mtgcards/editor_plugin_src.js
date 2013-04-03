/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('mtgcards');

	tinymce.create('tinymce.plugins.MtgcardsPlugin', {
		init : function(ed, url, eurl) {
			// Register commands
			ed.addCommand('mceMtgcards', function() {
				var furl = tinyMCE.activeEditor.getParam('plugin_mtgcards_url');
				ed.windowManager.open({
					file : url + '/mtgcards.php' + furl,
					width : 500 + parseInt(ed.getLang('mtgcards.delta_width', 0)),
					height : 220 + parseInt(ed.getLang('mtgcards.delta_height', 0)),
					inline : 1,
					resizable : "no"
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('mtgcards', {title : 'mtgcards.desc', image : url + '/img/mtgcards.gif', cmd : 'mceMtgcards'});
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
	tinymce.PluginManager.add('mtgcards', tinymce.plugins.MtgcardsPlugin);
})();