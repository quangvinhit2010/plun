var t = setInterval(function(){
    if(tinymce.editors.length > 0){
        tinymce.init({
            plugins: 'paste_txt',
            theme_advanced_buttons1: 'paste_txt'
        });
        tinymce.create('tinymce.plugins.Paste_txt',{
            init: function(ed){
                var t = this;

                ed.onPaste.add(function (pl, o) {

                    console.log('test');

                });
            }
        });
        tinymce.PluginManager.add('paste_txt',tinymce.plugins.Paste_txt);
        clearInterval(t);
    }
},1000);

