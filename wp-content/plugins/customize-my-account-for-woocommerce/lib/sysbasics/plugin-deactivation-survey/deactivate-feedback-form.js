(function($) {
	
	if(!window.sysbasics)
		window.sysbasics = {};
	
	if(sysbasics.DeactivateFeedbackForm)
		return;
	
	sysbasics.DeactivateFeedbackForm = function(plugin)
	{
		var self = this;
		var strings = sysbasics_deactivate_feedback_form_strings;
		
		this.plugin = plugin;
		
		// Dialog HTML
		var element = $('\
			<div class="sysbasics-deactivate-dialog" data-remodal-id="' + plugin.slug + '">\
				<form>\
					<input type="hidden" name="plugin"/>\
					<h2>' + strings.quick_feedback + '</h2>\
					<p>\
						' + strings.foreword + '\
					</p>\
					<ul class="sysbasics-deactivate-reasons"></ul>\
					<input name="comments" style="display:none;" class="sysbasics-extra-info" placeholder="' + strings.brief_description + '"/>\
					<br>\
					<p class="sysbasics-deactivate-dialog-buttons">\
						<input type="submit" class="button confirm" value="' + strings.skip_and_deactivate + '"/>\
						<button data-remodal-action="cancel" class="button button-primary">' + strings.cancel + '</button>\
					</p>\
				</form>\
			</div>\
		')[0];
		this.element = element;
		
		$(element).find("input[name='plugin']").val(JSON.stringify(plugin));
		
		$(element).on("click", "input[name='reason']", function(event) {
			$(element).find("input[type='submit']").val(
				strings.submit_and_deactivate
			);

			var elevalue = $(this).val();

			if (elevalue) {
                switch(elevalue) {
                    
                    case "other":
                    case "suddenly-stopped-working":
                    case "plugin-broke-site":
                       $('.sysbasics-extra-info').show();
                       $('.sysbasics-extra-info').attr("placeholder", sysbasics_deactivate_feedback_form_strings.please_tell_us);
                      
                    break;

                    case "found-better-plugin":


                    
                       $('.sysbasics-extra-info').show();
                       $('.sysbasics-extra-info').attr("placeholder", sysbasics_deactivate_feedback_form_strings.better_plugins_name);
                      
                    break;


                    case "missing-feature":


                    
                       $('.sysbasics-extra-info').show();
                       $('.sysbasics-extra-info').attr("placeholder", sysbasics_deactivate_feedback_form_strings.missing_feature);
                      
                    break;
                    
                    
                    

                    default:
                       $('.sysbasics-extra-info').hide();
                       $('.sysbasics-extra-info').attr("placeholder", sysbasics_deactivate_feedback_form_strings.please_tell_us);
                    break;
                }
            }
		});
		
		$(element).find("form").on("submit", function(event) {
			self.onSubmit(event);
		});
		
		// Reasons list
		var ul = $(element).find("ul.sysbasics-deactivate-reasons");
		for(var key in plugin.reasons)
		{
			var li = $("<li><input type='radio' name='reason'/> <span></span></li>");
			
			$(li).find("input").val(key);
			$(li).find("span").html(plugin.reasons[key]);
			
			$(ul).append(li);
		}
		
		// Listen for deactivate
		$("#the-list [data-slug='" + plugin.slug + "'] .deactivate>a").on("click", function(event) {
			self.onDeactivateClicked(event);
		});
	}
	
	sysbasics.DeactivateFeedbackForm.prototype.onDeactivateClicked = function(event)
	{
		this.deactivateURL = event.target.href;
		
		event.preventDefault();
		
		if(!this.dialog)
			this.dialog = $(this.element).remodal();
		this.dialog.open();
	}
	
	sysbasics.DeactivateFeedbackForm.prototype.onSubmit = function(event)
	{   
		var element = this.element;
		var strings = sysbasics_deactivate_feedback_form_strings;
		var self = this;
		var data = $(element).find("form").serialize();
		
		$(element).find("button, input[type='submit']").prop("disabled", true);
		
		if($(element).find("input[name='reason']:checked").length)
		{
			$(element).find("input[type='submit']").val(strings.thank_you);
			
			$.ajax({
				type:		"POST",
				url:		"https://updates.sysbasics.com/feedback.php",
				data:		{
                    
                    reason  : $("input[name='reason']:checked").val(),
                    plugin  : sysbasics_deactivate_feedback_form_plugins[0].slug,
                    comment : $(".sysbasics-extra-info").val(),
                    version : sysbasics_deactivate_feedback_form_plugins[0].version
                   
                    
                    
                },
				complete:	function(response) {
					console.log(response);
					window.location.href = self.deactivateURL;
				}
			});
		} else {
			$(element).find("input[type='submit']").val(strings.please_wait);
			window.location.href = self.deactivateURL;
		}
		
		event.preventDefault();
		return false;
	}
	
	$(document).ready(function() {
		
		for(var i = 0; i < sysbasics_deactivate_feedback_form_plugins.length; i++)
		{
			var plugin = sysbasics_deactivate_feedback_form_plugins[i];
			new sysbasics.DeactivateFeedbackForm(plugin);
		}
		
	});
	
})(jQuery);
