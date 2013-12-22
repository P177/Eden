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
	tinymce.create('tinymce.plugins.EmbedPlugin', {
		init : function(ed, url, furl) {
			// Register commands
			ed.addCommand('mceEmbed', function() {
				var furl = tinyMCE.activeEditor.getParam('plugin_embed_url');
				ed.windowManager.open({
					file : url + '/embed.php' + furl,
					width : 500 + parseInt(ed.getLang('embed.delta_width', 0)),
					height : 300 + parseInt(ed.getLang('embed.delta_height', 0)),
					inline : "no"
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('embed', {title : 'embed.embed_desc', image : url + '/img/embed.gif', cmd : 'mceEmbed'});
		},

		getInfo : function() {
			return {
				longname : 'Embed',
				author : 'Petr Hota, edencms.com',
				authorurl : 'http://www.edencms.eu',
				infourl : 'http://www.edencms.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('embed', tinymce.plugins.EmbedPlugin);
})(tinymce);