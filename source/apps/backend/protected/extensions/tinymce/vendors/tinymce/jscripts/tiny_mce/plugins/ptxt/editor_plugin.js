tinymce.create('tinymce.plugins.Ptxt',{
    init: function(ed){
        var t = this;

        ed.onPaste.add(function (pl, o) {

            console.log('test');

        });
    }
});
tinymce.PluginManager.add('ptxt',tinymce.plugins.Paste_txt);