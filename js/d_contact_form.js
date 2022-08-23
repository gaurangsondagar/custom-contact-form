/**
 * JS code for Custom Contacts Plugin
 */
jQuery(document).ready(function() {

	jQuery("#custom-contact-frm").validate({
	  	rules: {
	  		name: {
	  			required: true,
	  		},
	  		email: {
	  			required: true,
	  			email: true,
	  			// accept: true
	  		},
	  		subject: {
	  			required: true
	  		},
	  		message: {
	  			required: true
	  		}
	  	},
	  	messages: {
	  		email: {
	  			required: "Enter email address",
	  			email: "Enter a valid email address"
	  		}
	  	},
	  	submitHandler: function(form) {

	  		jQuery('.d_contact_msg').removeClass('error');
			jQuery('.d_contact_msg').html("");

	  		jQuery.ajax({
	  			url: DContactJS.adminurl,
	  			type: 'post',
	  			data: jQuery("#custom-contact-frm").serialize(),
	  			beforeSend: function () { 
					jQuery('.dfnz_btn_save').prop('disabled', true);
				},
	  			success: function(data) {
	  				jQuery('.dfnz_btn_save').prop('disabled', false);
	  				var res = JSON.parse(data);

	  				jQuery('.d_contact_msg').html(res['msg']);
	  				if (res['status'] == 1) {
	  					jQuery('#custom-contact-frm')[0].reset();
	  				} else if(res['status'] == 0){
	  					jQuery('.d_contact_msg').addClass('error');
	  				}
	  				jQuery('.d_contact_msg').show();
	  			},
	  			error: function (jqXHR, textStatus, errorThrown) { 
					jQuery('.dfnz_btn_save').prop('disabled', false);
				},
				complete: function () { }
	  		});
	  		return false;
	  	}
	  });

});
