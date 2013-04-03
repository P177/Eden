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
	tinymce.PluginManager.requireLangPack('cards');
	
	tinymce.create('tinymce.plugins.CardsPlugin', {
		init : function(ed, url, eurl) {
			// Register commands
			ed.addCommand('mceCards', function() {
				var furl = tinyMCE.activeEditor.getParam('plugin_cards_url');
				ed.windowManager.open({
					file : url + '/cards.php' + furl,
					width : 350 + parseInt(ed.getLang('cards.delta_width', 0)),
					height : 150 + parseInt(ed.getLang('cards.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('cards', {
				title : 'cards.desc', 
				image : url + '/img/card.gif', 
				cmd : 'mceCards'
			});
		},

		getInfo : function() {
			return {
				longname : 'Cards',
				author : 'Petr Hota, Edencms.com',
				authorurl : 'http://www.edencms.com',
				infourl : 'http://www.edencms.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('cards', tinymce.plugins.CardsPlugin);
})();
