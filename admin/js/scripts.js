$=jQuery;
jQuery(document).ready(function () {
    jQuery("#product_categories").select2();
    validateForm();
})
function validateForm() {
    $("#paone_wc_admin_setting").validate({
        // ignore: [],
        rules: {
            name: "required",
            "fonts[Name][]": "required",
            "colors[Code][]": "required",
            "colors[Name][]": "required",
            max_characters: "required",
        },
        messages: {
            name: "Please enter the Printed Name",
            fonts: "Please enter available font names",
            colors: "Please enter available font colors",
            max_characters: "Please enter number of characters allowed to be printed.",
        },
        checkForm: function() {
            this.prepareForm();
            for ( var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++ ) {
                if (this.findByName( elements[i].name ).length != undefined && this.findByName( elements[i].name ).length > 1) {
                    for (var cnt = 0; cnt < this.findByName( elements[i].name ).length; cnt++) {
                        this.check( this.findByName( elements[i].name )[cnt] );
                    }
                } else {
                    this.check( elements[i] );
                }
            }
            return this.valid();
        }
    });
}