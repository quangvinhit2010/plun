$(document).ready(function(){
    var containerDrag = $('#masonry_box'),
        item = containerDrag.find('>li'),
        valColItem = 0,
        valRowItem = 0,
        wItem = 245,
        hItem = 245,
        lengItem = item.length;
    item.each(function(i){
        var _this = $(this);
        valColItem = _this.data('col');
        valRowItem = _this.data('row');
//        _this.find('a').text(i);
        if(i+1 == lengItem){
            var $container = $('#masonry_box').packery({
                columnWidth: 245,
                rowHeight: 245
            });

            $container.find('.item').each( function( i, itemElem ) {
                var draggie = new Draggabilly( itemElem );
                $container.packery( 'bindDraggabillyEvents', draggie );
                console.log(1);
            });

            $container.packery( 'on', 'dragItemPositioned', function(pckryInstance, draggedItem ){
            	var itemElems = pckryInstance.getItemElements();
                for ( var i=0, len = itemElems.length; i < len; i++ ) {
                  var elem = itemElems[i];
                  $(elem).attr('data-order',i + 1);                  
                }
                Home.order();
            });

        }
    });

});