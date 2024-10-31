$ = jQuery;

function formatState(state) {
    if (!state.id) {
        return state.text;
    }
    var baseUrl = "/user/pages/images/flags";
    var $state = $(
        '<div class="select2-font-color-format"><div class="color_pallete" style="background-color:#' + state.element.id + ';">&nbsp; </div><div style="display: inline-block;" >' + state.text + '</div></div>'
    );
    return $state;
};
$(document).ready(function () {
    jQuery(".personalization_options").hide();
    $(".font_color").select2({
        templateResult: formatState,
        templateSelection: formatState

    });
    /*
personalization
*/
    jQuery(".btn_personalize_remove").click(function () {

        jQuery(".personalization_content").remove();
        jQuery(".personalization_options").hide();
        jQuery(".personalization_checkbox_input").removeAttr("checked");
    });
    jQuery(".btn_personalize").click(function () {
        var id = jQuery(this).attr("id");
        id = id.replace("btn-person-", "");
        setPersonalisationAttributes(id);
        var obj = {};
        obj.printed_name = jQuery("#printed_name-" + id).val();
        obj.font = jQuery("#font-" + id).val();
        obj.font_color = jQuery("#font_color-" + id).val();
        // obj.icon=jQuery("#icon-"+id).val();
        obj.hash_value = jQuery("#font_color-" + id).attr("hash");
        if (obj.font == "None" || obj.font_color == "" || obj.printed_name == "") {
            var c = confirm("Do you want to personalize your product? Please select the required fields");
            if (!c) {
                jQuery(".personalisation_check").removeAttr("checked");
                jQuery(".personalisation_fields").toggle();
            }
        } else {
            jQuery(".personalisation_check").attr("checked", "checked");
        }
    })
    jQuery(".personalization_checkbox_input").click(function () {
        jQuery(".personalization_options").toggle();
        jQuery("#personalization_content").remove();
    })

    function setPersonalisationAttributes(id) {
        var obj = {};
        obj.printed_name = jQuery("#printed_name-" + id).val();
        obj.product_id = jQuery("#printed_name-" + id).val();
        obj.font = jQuery("#font-" + id).val();
        obj.font_color = jQuery("#font_color-" + id).val();
        // obj.icon=jQuery("#icon-"+id).val();
        obj.hash_value = jQuery("#font_color-" + id).attr("hash");
        // personalisedAreaFill(obj);

        if (obj.font == "None" || obj.font_color == "" || obj.printed_name == "") {
            jQuery("#font-" + id).removeClass("font-selected");
            jQuery("a[data-product_id='" + id + "']").removeAttr('data-printed_name')
            jQuery("a[data-product_id='" + id + "']").removeAttr('data-font');
            jQuery("a[data-product_id='" + id + "']").removeAttr('data-font_color');
            jQuery(".personalization_selected").html("");
        } else {
            if (jQuery(".personalization_content").length > 0) {
                jQuery(".personalization_content").remove();
            }
            jQuery("#font-" + id).addClass("font-selected");
            jQuery("a[data-product_id='" + id + "']").attr('data-printed_name', obj.printed_name)
            jQuery("a[data-product_id='" + id + "']").attr('data-font', obj.font);
            jQuery("a[data-product_id='" + id + "']").attr('data-font_color', obj.font_color);
            var html = "";
            html += "<table class='personalization_content'>";
            html += "<tr><th>Printed Name</th><td>" + obj.printed_name + "<input type='hidden' name='data_product_id' value='"+obj.product_id+"'><input type='hidden'name='printed_name'value='" + obj.printed_name + "'></td></tr>"
            html += "<tr><th>Font</th><td>" + obj.font + "<input type='hidden'name='font' value='" + obj.font + "'></td></tr>"
            html += "<tr><th>Font Color</th><td>" + obj.font_color + "<input type='hidden'name='font_color' value='" + obj.font_color + "'></td></tr>"
            html += "</table>"
            jQuery(".personalization_selected").html(html);
        }
    }

    /*
    Personalization End
     */
})

function setTextColor(picker) {
    document.getElementsByTagName('body')[0].style.color = '#' + picker.toString()
}