(function(){tinymce.PluginManager.requireLangPack('piwigomedia');tinymce.create('tinymce.plugins.piwigomedia',{init:function(ed,url){ed.addCommand('mcePiwigoMedia',function(){ed.windowManager.open({file:url+'/popup.php',width:580,height:480,inline:1},{plugin_url:url})});ed.addButton('piwigomedia',{title:'piwigomedia.title',cmd:'mcePiwigoMedia',image:url+'/img/piwigomedia.gif'})},getInfo:function(){return{longname:'PiwigoMedia',author:'João C.',authorurl:'http://joaoubaldo.com',infourl:'http://joaoubaldo.com',version:'1.0'}}});tinymce.PluginManager.add('piwigomedia',tinymce.plugins.piwigomedia)})();