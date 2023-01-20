jQuery(document).ready(function(){
    jQuery('#'+rm_pass_warnings.fieldID).password({
        shortPass: rm_pass_warnings.shortPass,
        badPass:rm_pass_warnings.badPass,
        goodPass:rm_pass_warnings.goodPass,
        strongPass: rm_pass_warnings.strongPass,
    });
})