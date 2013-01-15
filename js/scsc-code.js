(function() {
	"use strict;"
	tinymce.create('tinymce.plugins.code', {
		init : function(ed, url) {
			ed.addButton('scsc_code', {
				title : 'Wrap inline code',
				image : url + '/../img/scsc_code.png',
				onclick : function() {
					 ed.selection.setContent('[code]' + ed.selection.getContent() + '[/code]');
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : "Short Code Shortcode - code",
				author : 'Joost Kiens',
				authorurl : 'http://joostkiens.com/',
				infourl : 'http://joostkiens.com/',
				version : "0.2.1"
			};
		}
	});
	tinymce.PluginManager.add('scsc_code', tinymce.plugins.code);
})();
