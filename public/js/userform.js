/*
* Userform Js - Handling the add more fucntionality
* Author : Elangovan.Sundar
*/

jQuery(document).ready(function () {
    jQuery('.add-another-collection-widget').click(function (e) {
        var list = jQuery(jQuery(this).attr('data-list'));
        var counter = list.data('widget-counter') | list.children().length;
        
        if (!counter) { counter = list.children().length; }

        var newWidget = list.attr('data-prototype');
        newWidget = newWidget.replace(/__name__/g, counter);
        counter++;
        list.data(' widget-counter', counter);

        var newElem = jQuery(list.attr('data-widget-tags')).html(newWidget);
        newElem.append('<a href="#" class="remove-tag btn btn-danger">x</a>');
        newElem.appendTo(list);

        // handle the removal, just for this example
        jQuery('.remove-tag').click(function(e) {
            e.preventDefault();            
            jQuery(this).parent().remove();            
            return false;
        });
    });
});