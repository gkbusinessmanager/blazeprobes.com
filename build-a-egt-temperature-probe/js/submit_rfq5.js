jQuery(document).ready(function(){
	// Suubmit Request for quote form 
	jQuery("#rfq_submit").click(function(event){
		event.preventDefault();
		var formData = jQuery('#formrfq').serialize();
		var rfqconf = document.getElementById('rfqconf').value;
		var rfqcompany = document.getElementById('rfqcompany').value;
		var rfqname = document.getElementById('rfqname').value;
		var rfqphone = document.getElementById('rfqphone').value;
		var rfqemail = document.getElementById('rfqemail').value;            	
		var rfqqty = document.getElementById('rfqqty').value;            	
		var rfqmsg = document.getElementById('rfqmsg').value;            	
		if (rfqconf&&rfqcompany&&rfqname&&rfqphone&&rfqemail&&rfqqty){
			jQuery.ajax({type: "POST",url: "https://blazeprobes.com/build-a-egt-temperature-probe/?page=send_rfq_email",
				data: formData,
				success: function(msg){
					if(msg == "sent")
					{
						jQuery("#myModalll").modal("hide");
						alert( "Thank you for contacting us. We will be in touch with you very soon.");
					}
					else if(msg == "wrong")
					{
						alert( "Something went wrong, Please try again later.");
					}
					else if(msg == "fail")
					{
						alert( "Something went wrong, Please try again later.");
					}
				}
			});
		}
		else
		{
			alert("Please provide all the required information");
		}
	});
});