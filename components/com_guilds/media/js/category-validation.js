$(document).ready(function() {
    // Form validation for Add Character field
    $('option').click(function() {
        var form = $(this).parents('form');
        var parent = $(this).attr('data-parent');
        var children = $(this).attr('data-children');
        selectParents(form,parent);
    });
    // Recrusive function for form validation
    function selectParents(form,parent_id) {
        // get all options on the form and iteratte over them
        var options = form.find('option').each(function(index,element){
            if($(element).val() == parent_id){
                $(element).parent('select').val(parent_id);
                var new_parent = $(element).attr('data-parent');
                if(new_parent != "0") {
                    selectParents(form,new_parent);
                }
            } 
        });
    }
});