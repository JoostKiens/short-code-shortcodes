(function() {
	"use strict;"
	tinymce.create('tinymce.plugins.precode', {
		init : function(ed, url) {
			ed.addButton('scsc_precode', {
				title : 'Wrap multi-line code',
				image : url + '/../img/scsc_precode.png',
				onclick : function() {
					 ed.selection.setContent('[precode]' + ed.selection.getContent() + '[/precode]');
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : "Short Code Shortcode - precode",
				author : 'Joost Kiens',
				authorurl : 'http://joostkiens.com/',
				infourl : 'http://joostkiens.com/',
				version : "0.2.1"
			};
		}
	});
	tinymce.PluginManager.add('scsc_precode', tinymce.plugins.precode);
})();
