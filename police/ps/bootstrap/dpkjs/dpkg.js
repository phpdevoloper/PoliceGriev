	
$(document).ready(function()
{
	 
	
	$('.prev-complaint').on('click', function(event) 
	{
		$('#spincomp').show();
		var tableData = '';
		var articleData = '';
		
		var panel='';
		var header = '<table class="prevcomtb"><tr style="background: gainsboro;height: 5px;"><td class="prevcomtd" style="width: 15%;" >Ticket Number</td><td  class="prevcomtd" style="width: 40%;">Problem</td><td class="prevcomtd" style="width: 10%;">Current State</td><td class="prevcomtd" style="width: 20%;">Support Queue</td><td  class="prevcomtd" style="width: 15%;">Creation Time</td></tr></table>';
		var dpk = 0;				
		$.ajax({
				url:"azxfunction.php", 
				type: "POST",
				data: {type : 'get_complaint'},
				dataType: "json",					
				success:function(data)
				{	console.log(data);
					
				$.each(data, function (key, val) 
				{		
				tableData = '<table class="prevcomtb"><tr class="prevcomtr"><td class="prevcomtd" style="width: 15%;" >'+val.tn+'</td><td  class="prevcomtd" style="width: 40%;">'+val.title+'</td><td  class="prevcomtd" style="width: 10%;">'+val.state+'</td><td  class="prevcomtd" style="width: 20%;">'+val.queue+'</td><td  class="prevcomtd" style="width: 15%;">'+val.creation+'</td></tr></table>';
				panel += '<div class="panel panel-page1" ><div class="box-header outer-header"><a data-toggle="collapse" data-parent="#accordion" href="#collapse'+key+'" data-id="collapse'+key+'" aria-expanded="false" class="collapsed">'+tableData+'</a></div>';
				var subpanel = '<div class="box-group" id="accordion'+key+'">';
						
						$.each(val.Body, function (key1, val1) 
						{
							tableData2 = '';
							articleData = '<table class="prevatrctb" style="width:80%;margin-bottom: 2px;"><tr class="prevatrctr comp"><td class="prevatrctd" style="width: 15%;" >'+val1.type+'</td><td  class="prevatrctd" style="width: 15%;">'+val1.ufrom+'</td><td  class="prevatrctd" style="width: 50%;">'+val1.subject+'</td><td  class="prevatrctd" style="width: 20%;">'+val1.created+'</td></tr></table>';
							tableData2 += '<table  style="width:80%;margin-bottom: 2px;"><tr><td>'+val1.Body+'</td></tr></table>';
							subpanel += '<div class="panel panel-page1" ><div class="box-header outer-header"><a data-toggle="collapse" data-parent="#accordion'+key+'" href="#subcollapse'+dpk+'" data-id="subcollapse'+dpk+'" aria-expanded="false" class="collapsed">'+articleData+'</a></div>';
							subpanel += '<div id="subcollapse'+dpk+'" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">'+tableData2+'</div>';
							subpanel += '</div>';	
							dpk += 1;
						});
				articleHeader = '<table class="prevatrctb" style="width:80%;"><tr class="prevatrctr" style="background:coral;color:#686868"><td class="prevatrctd" style="width: 15%;" >Type</td><td  class="prevatrctd" style="width: 15%;">From</td><td  class="prevatrctd" style="width: 50%;">Subject</td><td  class="prevatrctd" style="width: 20%;">Created</td></tr></table>';
									
				subpanel += '</div>';	
				panel += '<div id="collapse'+key+'" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">'+articleHeader+subpanel+'</div>';
				panel += '</div>';					
				});		
					
					$('#spincomp').hide();
					$('#prev_complant').html(header);
					$('#accordion').html(panel);									
				}
			});
		
	   event.preventDefault();
	});
})



	$(document).ready(function(){
		$("#adduser").click(function(){
			
	var name 		= document.ContactForm.uName;
	var email 		= document.ContactForm.uEmail;
	var usermobile 	= document.ContactForm.uNumber;
	var phone 		= document.ContactForm.oNumber;
	var designation = document.ContactForm.desg;
	var state 		= document.ContactForm.state_id;
	var city 		= document.ContactForm.city_id;
	var ministry 	= document.ContactForm.ministry_id;
	var department 	= document.ContactForm.department_id;
	var office 		= document.ContactForm.office_id
	var locate 		= document.ContactForm.locate;
	var usercat     = document.ContactForm.usercat_id;
	var stdeptt     = document.ContactForm.statedeptt_id;
	var stoffice    = document.ContactForm.stateoffice_id;
	var organization= document.ContactForm.organization_id;
	var otp 		= document.ContactForm.otp;
	var otpflag		= document.ContactForm.otp_type_flag;
	
    if (name.value == "")
    {
        window.alert("Please enter your name");
        name.focus();
        return false;
    }
    if (email.value == "")
    {
        window.alert("Please enter a valid e-mail address");
        email.focus();
        return false;
    }
    if (email.value.indexOf("@", 0) < 0)
    {
        window.alert("Please enter a valid e-mail address");
        email.focus();
        return false;
    }
    if (email.value.indexOf(".", 0) < 0)
    {
        window.alert("Please enter a valid e-mail address");
        email.focus();
        return false;
    }

	if (usermobile.value == "")
    {
        window.alert("Please enter a valid mobile number");
        usermobile.focus();
        return false;
    }

	if (state.selectedIndex < 1)
    {
        window.alert("Please select a state");
        state.focus();
        return false;
    }
	if (city.selectedIndex < 1)
    {
        window.alert("Please select a district");
        city.focus();
        return false;
    }


	if (usercat.selectedIndex < 1)
	{
		window.alert("Please select a User Category");
		usercat.focus();
		return false;
	}


	if (usercat.value == 2 || usercat.value == 3 )
		{
			if (ministry.selectedIndex < 1)
		    {
		        window.alert("Please select a ministry");
		        ministry.focus();
		        return false;
		    }
			if (department.selectedIndex < 1)
		    {
		        window.alert("Please select a department");
		        department.focus();
		        return false;
		    }
			if (office.selectedIndex < 1)
		    {
		        window.alert("Please select a office");
		        office.focus();
		        return false;
		    }
			
		}
	if (usercat.value == 4 || usercat.value == 5)
		{

			if (stdeptt.selectedIndex < 1)
		    {
		        window.alert("Please select a state department");
		        stdeptt.focus();
		        return false;
		    }
			if (stoffice.selectedIndex < 1)
		    {
		        window.alert("Please select a state office");
		        stoffice.focus();
		        return false;
		    }

		}

   if (usercat.value >= 51 && usercat.value <= 150)
		{
			if (organization.selectedIndex < 1)
			{
				window.alert("Please select a organization");
				organization.focus();
				return false;
			}
		}	
	$('#spinprofile').show();	
			$.ajax({
				url:"azxfunction.php",
				type: "POST",
				data: {type : 'add_user', mobile : usermobile.value,email : email.value ,name : name.value,designation : designation.value,otp : otp.value,state : state.value,city : city.value,ministry : ministry.value,department : department.value,office : office.value,locate : locate.value,usercat : usercat.value,stdeptt : stdeptt.value,stoffice : stoffice.value,organization : organization.value ,phone : phone.value ,otpflag : otpflag.value},
				dataType: "html",
				success:function(data)
					{  
						obj = JSON.parse(data);
						if(obj.success == 1)
						{
							
								$('#profilemoddiv font').html('Dear Ms./Mr. '+name.value+', profile added successfully!!!!');
								$('#profileMod').modal('show');
								$('#profilemoddiv').css('backgroundColor','#5cb85c');
								$('#profilemoddiv').css('color','white' );
								$('#updateuser').show();
								$('#adduser').hide();
								$('#spinprofile').hide();
						}
						else if(obj.error)
						{
								$('#profilemoddiv font').html(""+obj.failure+"");
								$('#profilemoddiv').show();	
								$('#profilemoddiv').css('backgroundColor','#f44336');
								$('#profilemoddiv').css('color','white' );
								$('#spinprofile').hide();
						}
						
					
					}
			});
		});     
	})
$(document).ready(function(){
		$("#updateuser").click(function(){
			
	var name 		= document.ContactForm.uName;
	var email 		= document.ContactForm.uEmail;
	var usermobile 	= document.ContactForm.uNumber;
	var phone 		= document.ContactForm.oNumber;
	var designation = document.ContactForm.desg;
	var state 		= document.ContactForm.state_id;
	var city 		= document.ContactForm.city_id;
	var ministry 	= document.ContactForm.ministry_id;
	var department 	= document.ContactForm.department_id;
	var office 		= document.ContactForm.office_id
	var locate 		= document.ContactForm.locate;
	var usercat     = document.ContactForm.usercat_id;
	var stdeptt     = document.ContactForm.statedeptt_id;
	var stoffice    = document.ContactForm.stateoffice_id;
	var organization= document.ContactForm.organization_id;
	var otp 		= document.ContactForm.otp;
	var otpflag		= document.ContactForm.otp_type_flag;
	
    if (name.value == "")
    {
        window.alert("Please enter your name");
        name.focus();
        return false;
    }
    if (email.value == "")
    {
        window.alert("Please enter a valid e-mail address");
        email.focus();
        return false;
    }
    if (email.value.indexOf("@", 0) < 0)
    {
        window.alert("Please enter a valid e-mail address");
        email.focus();
        return false;
    }
    if (email.value.indexOf(".", 0) < 0)
    {
        window.alert("Please enter a valid e-mail address");
        email.focus();
        return false;
    }

	if (usermobile.value == "")
    {
        window.alert("Please enter a valid mobile number");
        usermobile.focus();
        return false;
    }

	if (state.selectedIndex < 1)
    {
        window.alert("Please select a state");
        state.focus();
        return false;
    }
	if (city.selectedIndex < 1)
    {
        window.alert("Please select a district");
        city.focus();
        return false;
    }


	if (usercat.selectedIndex < 1)
	{
		window.alert("Please select a User Category");
		usercat.focus();
		return false;
	}


	if (usercat.value == 2 || usercat.value == 3 )
		{
			if (ministry.selectedIndex < 1)
		    {
		        window.alert("Please select a ministry");
		        ministry.focus();
		        return false;
		    }
			if (department.selectedIndex < 1)
		    {
		        window.alert("Please select a department");
		        department.focus();
		        return false;
		    }
			if (office.selectedIndex < 1)
		    {
		        window.alert("Please select a office");
		        office.focus();
		        return false;
		    }
			
		}
	if (usercat.value == 4 || usercat.value == 5)
		{

			if (stdeptt.selectedIndex < 1)
		    {
		        window.alert("Please select a state department");
		        stdeptt.focus();
		        return false;
		    }
			if (stoffice.selectedIndex < 1)
		    {
		        window.alert("Please select a state office");
		        stoffice.focus();
		        return false;
		    }

		}

   if (usercat.value >= 51 && usercat.value <= 150)
		{
			if (organization.selectedIndex < 1)
			{
				window.alert("Please select a organization");
				organization.focus();
				return false;
			}
		}	
	$('#spinprofile').show();	
			$.ajax({
				url:"azxfunction.php",
				type: "POST",
				data: {type : 'update_user',mobile : usermobile.value,email : email.value ,name : name.value,designation : designation.value,otp : otp.value,state : state.value,city : city.value,ministry : ministry.value,department : department.value,office : office.value,locate : locate.value,usercat : usercat.value,stdeptt : stdeptt.value,stoffice : stoffice.value,organization : organization.value ,phone : phone.value,otpflag: otpflag.value},
				dataType: "html",
				success:function(data)
					{  
						obj = JSON.parse(data);
						if(obj.success == 1)
						{
							
								$('#profilemoddiv font').html('Dear Ms./Mr. '+name.value+', profile updated successfully!!!!');
								$('#profileMod').modal('show');
								$('#profilemoddiv').css('backgroundColor','#5cb85c');
								$('#profilemoddiv').css('color','white' );
								$('#updateuser').show();
								$('#adduser').hide();
								$('#spinprofile').hide();
						}
						if(obj.success == 2)
						{
							
								$('#profilemoddiv font').html('Dear Ms./Mr. '+name.value+', no changes in your profile');
								$('#profileMod').modal('show');
								$('#profilemoddiv').css('backgroundColor','#5cb85c');
								$('#profilemoddiv').css('color','white' );
								$('#updateuser').show();
								$('#adduser').hide();
								$('#spinprofile').hide();
						}
						else if(obj.error)
						{
								$('#profilemoddiv font').html(""+obj.failure+"");
								$('#profilemoddiv').show();	
								$('#profilemoddiv').css('backgroundColor','#f44336');
								$('#profilemoddiv').css('color','white' );
								$('#spinprofile').hide();
						}
						
					
					}
			});
		});     
	})
	

	$(document).ready(function()
	{
		var oldMobile = '';
	$('input[name=pre_mobile_no]').on('change', function()
	{
		var value = $('input[name=pre_mobile_no]:checked', '#ContactForm').val();
		if(!oldMobile)
		{
			oldMobile = $('#uNumber').val();
			$('#oldMobile').val(oldMobile)
		}
		$('#uNumber').val($('input[name=pre_mobile_no]:checked', '#ContactForm').val());

		if(value)
		{$('#uNumber').attr("readonly", true);}
		else{$('#uNumber').attr("readonly", false);}
	});



	$('.user-mobile-del-btn').on('click', function(event)
	{
		var email = document.ContactForm.uEmail;
		var mobile = $(this).attr('user_mobile');
		var mobile_div = $(this).closest('.mobiledlt');
		var x = confirm ("Are you sure you want to delete your alternate number?");
		if (x)
		{
			$.ajax(
				{
					url:"azxfunction.php",
					type: "POST",
					data: {type : 'delete_mobile', mobile : mobile,email : email.value},
					dataType: "html",
					success:function(data)
						{
							if(data)
								mobile_div.remove();
							else
								alert(data);
						}
				});
		}
		return false;
	   event.preventDefault();
	});

	var oldEmail = '';
	$('input[name=pre_email_id]').on('change', function()
	{
		var value = $('input[name=pre_email_id]:checked', '#ContactForm').val();

		if(!oldEmail)
		{
			oldEmail = $('#uEmail').val();
			$('#oldEmail').val(oldEmail)
		}
		$('#uEmail').val($('input[name=pre_email_id]:checked', '#ContactForm').val());

		if(value)
		{$('#uEmail').attr("readonly", true);}
		else{$('#uEmail').attr("readonly", false);}
	});




	$('.user-email-del-btn').on('click', function(event)
	{
	   var mobile = document.ContactForm.uNumber;
	   var email = $(this).attr('user_email');
	   var email_div = $(this).closest('.emaildlt');
	   var x = confirm ("Are you sure you want to delete your alternate email address?");
	   if (x)
		  {
		   $.ajax(
				{
					url:"azxfunction.php",
					type: "POST",
					data: {type : 'delete_email', email : email, mobile:mobile.value},
					dataType: "html",
					success:function(data)
					{
						if(data)
							email_div.remove();
						else
							alert(data);
					}
				});
		  }
			return false;
			event.preventDefault();
	});


	$('.user-email-up-btn').on('click', function(event)
	{

		$('#emailupdate').modal('show');
		$('#emailotp').val('');
		$('#usernewEmail').val('');

	   event.preventDefault();
	});

	$('#submit_emailotp').on('click', function(event)
	{
		var emailnew 	= document.emailForm.usernewEmail;
		var uotp 	= document.emailForm.emailotp;

		if (!(emailnew.value))
			{
				if(!ValidateEmail(emailnew.value)){
					$('#alertDivnew font').html("Please enter an email address ");
					$('#alertDivnew').show();
					emailnew.focus();
					return false;
				}
			}
		else{
			if (emailnew.value)
			{
				if(!ValidateEmail(emailnew.value)){
					//window.alert("Please enter a valid email address");
					$('#alertDivnew font').html("Please enter a valid email address ");
					$('#alertDivnew').show();
					emailnew.focus();
					return false;
				}
				else
				{
				$('#alertDivnew').hide();
				}
			}
		}


		if(authuser != '0')
		{
			$.ajax({
				url:"azxfunction.php",
				type: "POST",
				data: {type : 'emailup_regip', email : emailnew.value},
				dataType: "html",
				success:function(data)
					{
						if(data)
						{      $('#emailupdate').modal('hide');
								$('#uEmail').val(emailnew.value);
								$('#regemail').val(emailnew.value);
								$('.form_email_btn span').html(emailnew.value);

						}
					else
							alert('Kindly refresh your page');
					}
			});
		}


		else if($('#emailotp').val())
		{
			$.ajax({
				url:"azxfunction.php",
				type: "POST",
				data: {type : 'email_update', email : emailnew.value ,otp : uotp.value},
				dataType: "html",
				success:function(data)
					{
						obj = JSON.parse(data);
						if(obj.sucsmsg == 1)
						{

							$('#emailotpDiv').show();
							$('#emailotpDiv font').html("Email Address Updated Successfully");
							$("#emailotpDiv").css('backgroundColor','#5cb85c');
							$('#emailotpDiv').css('color','white' );
							$('#alertDivnew').hide();
							$('#uEmail').val(emailnew.value);
							$('.form_email_btn span').html(emailnew.value);
							$('#regemail').val(emailnew.value);
							$(".emailsubmit").attr("disabled", true);
						}
						else if(obj.sucsmsg == 2)
						{

							$('#emailotpDiv').show();
							$('#emailotpDiv font').html("OTP Invalid");
							$("#emailotpDiv").css('backgroundColor','#f44336');
							$('#emailotpDiv').css('color','white' );
							$('#alertDivnew').hide();

						}
						else if(obj.sucsmsg == 3)
						{

							$('#emailotpDiv').show();
							$('#emailotpDiv font').html("OTP Expired");
							$("#emailotpDiv").css('backgroundColor','#f44336');
							$('#emailotpDiv').css('color','white' );
							$('#alertDivnew').hide();

						}
						else
							alert(data);
					}
			});
		}
		else{
			$.ajax({
				url:"azxfunction.php",
				type: "POST",
				data: {type : 'get_otp_emailupdate', email : emailnew.value },
				dataType: "html",
				success:function(data)
					{
						obj = JSON.parse(data);
						if(obj.eotp == 1)
						{
							$('.emailotp-div').show();
							$('#emailotpDiv').show();
							$('#emailotpDiv font').html("OTP sent sucessfully.  ");
							$("#emailotpDiv").css('backgroundColor','#5cb85c');
							$('#emailotpDiv').css('color','white' );
							$('#alertDivnew').hide();
						}
						else if(obj.eotp == 2)
						{
							$('.emailotp-div').show();
							$('#emailotpDiv').show();
							$('#emailotpDiv font').html("You have a valid OTP. Kindly use the same. ");
							$('#emailotpDiv').css('color','white' );
							$("#emailotpDiv").css('backgroundColor','#5cb85c');
							$('#alertDivnew').hide();
						}

							else
								alert(data);
					}
			});
		}

	   event.preventDefault();
	});

	/*   mobile update     */

	$('.user-mobile-up-btn').on('click', function(event)
	{
		$('#mobileupdate').modal('show');
		$('#mobileotp').val('');
		$('#usernewMobile').val('');

	   event.preventDefault();
	});

	$('#submit_mobileotp').on('click', function(event)
	{
		var mobilenew 	= document.mobileForm.usernewMobile;

		var uotp 	= document.mobileForm.mobileotp;

		if (!(mobilenew.value))
			{
				if(!validateMobile(mobilenew.value)){
					$('#alertDivnewmo font').html("Please enter an mobile number");
					$('#alertDivnewmo').show();
					mobilenew.focus();
					return false;
				}
			}
		else{
			if (mobilenew.value)
			{
				if(!validateMobile(mobilenew.value)){
					$('#alertDivnewmo font').html("Please enter a valid mobile number ");
					$('#alertDivnewmo').show();
					mobilenew.focus();
					return false;
				}
			}
			else
				{
				$('#alertDivnewmo').hide();
				}
		}


		if(authuser != '0')
		{
			$.ajax({
				url:"azxfunction.php",
				type: "POST",
				data: {type : 'mobileup_regip', mobile : mobilenew.value},
				dataType: "html",
				success:function(data)
					{
						if(data)
						{      $('#mobileupdate').modal('hide');
								$('#uNumber').val(mobilenew.value);
								$('.form_mobile_btn span').html(mobilenew.value);
								$('#regmobile').val(mobilenew.value);

						}
					else
							alert('Kindly refresh your page');
					}
			});
		}


		else if($('#mobileotp').val())
		{
			$.ajax({
				url:"azxfunction.php",
				type: "POST",
				data: {type : 'mobile_update', mobile : mobilenew.value ,otp : uotp.value},
				dataType: "html",
				success:function(data)
					{
						obj = JSON.parse(data);
						if(obj.sucsmsg == 1)
						{

							$('#mobileotpDiv').show();
							$('#mobileotpDiv font').html("Mobile Number Updated Successfully");
							$("#mobileotpDiv").css('backgroundColor','#5cb85c');
							$('#mobileotpDiv').css('color','white' );
							$('#alertDivnewmo').hide();
							$('#uNumber').val(mobilenew.value);
							$('.form_mobile_btn span').html(mobilenew.value);
							$('#regmobile').val(mobilenew.value);
							$(".mobilesubmit").attr("disabled", true);
						}
						else if(obj.sucsmsg == 2)
						{

							$('#mobileotpDiv').show();
							$('#mobileotpDiv font').html("OTP Invalid");
							$("#mobileotpDiv").css('backgroundColor','#f44336');
							$('#mobileotpDiv').css('color','white' );
							$('#alertDivnewmo').hide();

						}
						else if(obj.sucsmsg == 3)
						{

							$('#mobileotpDiv').show();
							$('#mobileotpDiv font').html("OTP Expired");
							$("#mobileotpDiv").css('backgroundColor','#f44336');
							$('#mobileotpDiv').css('color','white' );
							$('#alertDivnewmo').hide();

						}
						else
							alert('Kindly refresh your page');
					}
			});
		}
		else{
			$.ajax({
				url:"azxfunction.php",
				type: "POST",
				data: {type : 'get_otp_mobileupdate', mobile : mobilenew.value },
				dataType: "html",
				success:function(data)
					{
						obj = JSON.parse(data);
						if(obj.eotp == 1)
						{
							$('.mobileotp-div').show();
							$('#mobileotpDiv').show();
							$('#mobileotpDiv font').html("OTP sent sucessfully.  ");
							$('#mobileotpDiv').css('color','white' );
							$("#mobileotpDiv").css('backgroundColor','#5cb85c');
							$('#alertDivnewmo').hide();
						}
						else if(obj.eotp == 2)
						{
							$('.mobileotp-div').show();
							$('#mobileotpDiv').show();
							$('#mobileotpDiv font').html("You have a valid OTP. Kindly use the same. ");
							$("#mobileotpDiv").css('backgroundColor','#5cb85c');
							$('#mobileotpDiv').css('color','white' );
							$('#alertDivnewmo').hide();
						}

							else
								alert(data);
					}
			});
		}

	   event.preventDefault();
	});





		$('#userNumber').on('keypress', function(event){
		   return (event.charCode >= 48 && event.charCode <= 57) || event.keyCode == 8 || event.keyCode == 13 || event.keyCode == 9;
		});
		$('#uNumber').on('keypress', function(event){
		   return (event.charCode >= 48 && event.charCode <= 57) || event.keyCode == 8 || event.keyCode == 9;
		});
		$('#onumber').on('keypress', function(event){
		   return (event.charCode >= 48 && event.charCode <= 57) || event.keyCode == 8 || event.keyCode == 9;
		});
		$('#otp').on('keypress', function(event){
		   return (event.charCode >= 48 && event.charCode <= 57) || event.keyCode == 8 || event.keyCode == 13;
		});
		$('#uName').on('keypress', function(event){
		   return (event.charCode >= 65 && event.charCode <= 122) || event.charCode == 32 || event.keyCode == 8|| event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 13 || event.keyCode == 9;
		});
		$('#desg').on('keypress', function(event){
		   return (event.charCode >= 65 && event.charCode <= 122) || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 32 || event.keyCode == 8|| event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 13 || event.keyCode == 9;
		});
		$('#locate').on('keypress', function(event){
		   return (event.charCode >= 65 && event.charCode <= 122) || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 32 || event.keyCode == 8|| event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 13 || event.keyCode == 9;
		});
		



		$('.otp-div').hide();
				$('#userNumber').on('blur', function()
				{
					if($('#userNumber').val().length > 0)
					{
						$('#userEmail').attr("disabled", true);
						$('#alertDiv').hide();
					}
					else{
						$('#userEmail').attr("disabled", false);
					}
				});
				$('#userEmail').on('blur', function()
				{
					if($('#userEmail').val().length > 0)
					{
						$('#userNumber').attr("disabled", true);
						$('#alertDiv').hide();
					}
					else{
						$('#userNumber').attr("disabled", false);

					}
				});
			})




	$(document).ready(function(){
		$('.otp-div').hide();
				$('#userNumber').on('keyup', function() {
					if($('#userNumber').val().length > 0)
					{
						$('#userEmail').html("disabled", true);
					}
					else{
						$('#userEmail').attr("disabled", false);
					}
				});
				$('#userEmail').on('keyup', function() {
					if($('#userEmail').val().length > 0)
					{
						$('#userNumber').attr("disabled", true);
					}
					else{
						$('#userNumber').attr("disabled", false);
					}
				});
			})




	 $(document).ready(function(){
					$('#state_id').on('change', function() {
						$('#city_id').dropdown('clear');
						});

					$('#city_id').on('change', function() {
						$('#usercat_id').dropdown('clear');
						});

					$('#problem_id').on('change', function() {
						$('#subproblem_id').dropdown('clear');
						$('#service_id').dropdown('clear');
						});

					$('#ministry_id').on('change', function() {
						$('#department_id').dropdown('clear');
						$('#office_id').dropdown('clear');
						});

					$('#department_id').on('change', function() {
						$('#office_id').dropdown('clear');
					});

					$('#statedeptt_id').on('change', function() {
						$('#stateoffice_id').dropdown('clear');
					});
					$('#usercat_id').on('change', function() {
						$('#department_id').dropdown('clear');
						$('#ministry_id').dropdown('clear');
						$('#office_id').dropdown('clear');
						$('#organization_id').dropdown('clear');
						$('#statedeptt_id').dropdown('clear');
						$('#stateoffice_id').dropdown('clear');
						$('#problem_id').dropdown('clear');
				});

	})



		$(document).ready(function(){
				$('#state_id').on('change', function() {
				  $.ajax({
					url:"getState.php",
					type: "POST",
					data: {id : this.value},
					dataType: "html",
					success:function(data) {
						$('#city_id').html(data);
					}
				  });
				});
			})


			$(document).ready(function(){
				  var ucity = document.ContactForm.city_id;
				$('#usercat_id').on('change', function() {
				  $.ajax({
					url:"getMinistry.php",
					type: "POST",
					data: {id : this.value , ucity : ucity.value},
					dataType: "html",
					success:function(data) {
						$('#ministry_id').html(data);
					}
				  });
				});
			})


			$(document).ready(function(){
				  var ucity = document.ContactForm.city_id;
				$('#usercat_id').on('change', function() {
				  $.ajax({
					url:"getStatedept.php",
					type: "POST",
					data: {id : this.value , ucity : ucity.value},
					dataType: "html",
					success:function(data) {
						$('#statedeptt_id').html(data);
					}
				  });
				});
			})


			$(document).ready(function(){
				  var ucity = document.ContactForm.city_id;
				$('#statedeptt_id').on('change', function() {
				  $.ajax({
					url:"getStateofc.php",
					type: "POST",
					data: {id : this.value , ucity : ucity.value},
					dataType: "html",
					success:function(data) {
						$('#stateoffice_id').html(data);
					}
				  });
				});
			})


			$(document).ready(function(){
				  var ucity = document.ContactForm.city_id;
				$('#usercat_id').on('change', function() {
				  $.ajax({
					url:"getOrganization.php",
					type: "POST",
					data: {id : this.value , ucity : ucity.value},
					dataType: "html",
					success:function(data) {
						$('#organization_id').html(data);
					}
				  });
				});
			})

			$(document).ready(function(){
				var ucity = document.ContactForm.city_id;
				$('#ministry_id').on('change', function() {

				  $.ajax({
					url:"getDepartment.php",
					type: "POST",
					data: {id : this.value, ucity : ucity.value},
					dataType: "html",
					success:function(data) {
						$('#department_id').html(data);
					}
				  });
				});
			})


			$(document).ready(function(){
				var ucity = document.ContactForm.city_id;
				$('#department_id').on('change', function() {
				  $.ajax({
					url:"getOffice.php",
					type: "POST",
					data: {id : this.value , ucity : ucity.value},
					dataType: "html",
					success:function(data) {
						$('#office_id').html(data);
					}
				  });
				});
			})



		  $(document).ready(function(){
				$('#usercat_id').on('change', function() {
				  $.ajax({
					url:"getProblem.php",
					type: "POST",
					data: {id : this.value},
					dataType: "html",
					success:function(data) {
						$('#problem_id').html(data);

					}
				  });
				});
			})






	$(document).ready(function(){
				$('#usercat_id').on('change', function() {

					if($('#usercat_id').val() == 1 )
					{

						$('#locate').show();

						$('#mnhide').hide();
						$('#dthide').hide();
						$('#ofhide').hide();
						$('#sphide').hide();
						$('#orhide').hide();
						$('#sdthide').hide();
						$('#sofhide').hide();

						$('#ministry_id').attr("disabled", true);
						$('#department_id').attr("disabled", true);
						$('#office_id').attr("disabled", true);
						$('#organization_id').attr("disabled", true);
						$('#subproblem_id').attr("disabled", true);
						$('#service_id').attr("disabled", true);
						$('#statedeptt_id').attr("disabled", true);
						$('#stateoffice_id').attr("disabled", true);


					}
				else{

						//$('#institute_id').attr("disabled", false);
						//$('#organization_id').attr("disabled", false);
				}
				});
					})


	$(document).ready(function(){
				$('#usercat_id').on('change', function() {

					if($('#usercat_id').val() == 2 || $('#usercat_id').val() == 3 )
					{
						$('#mnhide').show();
						$('#dthide').show();
						$('#ofhide').show();
						$('#locate').show();
						$('#sphide').show();

						$('#orhide').hide();
						$('#sdthide').hide();
						$('#sofhide').hide();

						$('#statedeptt_id').attr("disabled", true);
						$('#stateoffice_id').attr("disabled", true);
						$('#organization_id').attr("disabled", true);

						$('#subproblem_id').attr("disabled", false);
						$('#department_id').attr("disabled", false);
						$('#office_id').attr("disabled", false);
						$('#ministry_id').attr("disabled", false);
					}
				else{

						//$('#institute_id').attr("disabled", false);
						//$('#organization_id').attr("disabled", false);
					}
				});
					})

	$(document).ready(function(){
				$('#usercat_id').on('change', function() {

					if($('#usercat_id').val() == 4 || $('#usercat_id').val() == 5)
					{
						$('#sdthide').show();
						$('#sofhide').show();
						$('#locate').show();
						$('#sphide').show();

						$('#mnhide').hide();
						$('#ofhide').hide();
						$('#dthide').hide();
						$('#orhide').hide();

						$('#organization_id').attr("disabled", true);
						$('#ministry_id').attr("disabled", true);
						$('#department_id').attr("disabled", true);
						$('#office_id').attr("disabled", true);

						$('#statedeptt_id').attr("disabled", false);
						$('#stateoffice_id').attr("disabled", false);
						$('#subproblem_id').attr("disabled", false);



					}
				else{

						//$('#institute_id').attr("disabled", false);
						//$('#organization_id').attr("disabled", false);
					}
				});
					})


			$(document).ready(function(){
				$('#usercat_id').on('change', function() {

					if($('#usercat_id').val() >= 51 && $('#usercat_id').val() <= 150)
					{

						$('#locate').show();
						$('#sphide').show();
						$('#orhide').show();

						$('#mnhide').hide();
						$('#dthide').hide();
						$('#ofhide').hide();
						$('#sdthide').hide();
						$('#sofhide').hide();



						$('#subproblem_id').attr("disabled", false);
						$('#organization_id').attr("disabled", false);


						$('#ministry_id').attr("disabled", true);
						$('#department_id').attr("disabled", true);
						$('#office_id').attr("disabled", true);
						$('#statedeptt_id').attr("disabled", true);
						$('#stateoffice_id').attr("disabled", true);
					}
				else{

					}
				});
					})






	//Start--- script for change Subproblem with Problem
	$(document).ready(function(){
		$('#problem_id').on('change', function() {
		 // alert( this.value ); // or $(this).val()
		  $.ajax({
			url:"getsubProblem.php",
			type: "POST",
			data: {id : this.value},
			dataType: "html",
			success:function(data) {
				$('#subproblem_id').html(data);
			}
		  });
		});




	$(document).ready(function(){
				$('#subproblem_id').on('change', function() {
				  $.ajax({
					url:"getRegdiv.php",
					type: "POST",
					data: {id : this.value},
					dataType: "html",
					success:function(data) {
					if(data)
						{
							$('#reglabel').html(data)
							$('#service_name').show();
						}
					else
						{	$('.reg-div').hide();	}
					}
				  });
				});
			})


		$(document).ready(function(){
				$('#usercat_id').on('change', function() {
				  $.ajax({
					url:"getOrglabel.php",
					type: "POST",
					data: {id : this.value},
					dataType: "html",
					success:function(data) {
					if(data)
						{
							$('.orglabel').html(data)

						}

					}
				  });
				});
			})

		$(document).ready(function(){
				$('#subproblem_id').on('change', function() {

				  $.ajax({
					url:"getServiceCheck.php",
					type: "POST",
					data: {id : this.value},
					dataType: "html",
					success:function(data) {
					if(data)
						{
							$('#servcicelabel').html(data)
							$('#service_list').show();
							$('#service_id').attr("disabled", false);
						}
					else
						{	$('.ser-div').hide();
							$('#service_id').attr("disabled", true);
					}
					}
				  });
				});
			})

			$(document).ready(function(){
				$('#subproblem_id').on('change', function() {
				  $.ajax({
					url:"getServiceList.php",
					type: "POST",
					data: {id : this.value},
					dataType: "html",
					success:function(data)
					{
						$('#service_id').html(data);
					}
				  });
				});
			})


/*	$(".edit").click(function(){
							$('#compdiv').addClass('col-md-4');
						   $("#bgcon").show( 'slow', function(){
								$("#bgloc").show( 'slow', function(){

								});
							});
						});

*/
$(".nxt").click(function(){
							$('#userpfile').removeClass('active');
							$('#firstli').removeClass('active');
							$('#secondli').addClass('active');
							$('#home').addClass('active');
						});

	$('.complaint-dismiss').on('click', function(event)
		{
			$('#userpfile').addClass('active');
			$('#firstli').addClass('active');
			$('#secondli').removeClass('active');
			$('#home').removeClass('active');
		});


	$('.complaint-submit').on('click', function(event)
	{
		document.ContactForm.submit();
		$(".complaint-submit").attr("disabled", true);
		 $(".complaint-dismiss").attr("disabled", true);
		 event.preventDefault();
	});



})

//Start --- For valdiation on complaintform
function ValidateContactForm()
{
    var name 		= document.ContactForm.uName;
	var email 		= document.ContactForm.uEmail;
	var usermobile 	= document.ContactForm.uNumber;
	var designation = document.ContactForm.desg;
	var state 		= document.ContactForm.state_id;
	var city 		= document.ContactForm.city_id;
	var ministry 	= document.ContactForm.ministry_id;
	var department 	= document.ContactForm.department_id;
	var office 		= document.ContactForm.office_id;
	var problem 	= document.ContactForm.problem_id;
	var subproblem 	= document.ContactForm.subproblem_id;
	var descp 		= document.ContactForm.description;
	var locate 		= document.ContactForm.locate;
	var term		= document.ContactForm.term;
	var usercat     = document.ContactForm.usercat_id;
	var stdeptt     = document.ContactForm.statedeptt_id;
	var stoffice    = document.ContactForm.stateoffice_id;
	var organization= document.ContactForm.organization_id;



    if (name.value == "")
    {
        window.alert("Please enter your name");
        name.focus();
        return false;
    }
    if (email.value == "")
    {
        window.alert("Please enter a valid e-mail address");
        email.focus();
        return false;
    }
    if (email.value.indexOf("@", 0) < 0)
    {
        window.alert("Please enter a valid e-mail address");
        email.focus();
        return false;
    }
    if (email.value.indexOf(".", 0) < 0)
    {
        window.alert("Please enter a valid e-mail address");
        email.focus();
        return false;
    }

	if (usermobile.value == "")
    {
        window.alert("Please enter a valid mobile number");
        usermobile.focus();
        return false;
    }

	if (state.selectedIndex < 1)
    {
        window.alert("Please select a state");
        state.focus();
        return false;
    }
	if (city.selectedIndex < 1)
    {
        window.alert("Please select a district");
        city.focus();
        return false;
    }


	if (usercat.selectedIndex < 1)
	{
		window.alert("Please select a User Category");
		usercat.focus();
		return false;
	}


	if (usercat.value == 2 || usercat.value == 3 )
		{
			if (ministry.selectedIndex < 1)
		    {
		        window.alert("Please select a ministry");
		        ministry.focus();
		        return false;
		    }
			if (department.selectedIndex < 1)
		    {
		        window.alert("Please select a department");
		        department.focus();
		        return false;
		    }
			if (office.selectedIndex < 1)
		    {
		        window.alert("Please select a office");
		        office.focus();
		        return false;
		    }
			if (problem.selectedIndex < 1)
			{
				window.alert("Please select a problem");
				problem.focus();
				return false;
			}
			if (subproblem.selectedIndex < 1)
			{
				window.alert("Please select a subproblem");
				subproblem.focus();
				return false;
			}
		}
	if (usercat.value == 4 || usercat.value == 5)
		{

			if (stdeptt.selectedIndex < 1)
		    {
		        window.alert("Please select a state department");
		        stdeptt.focus();
		        return false;
		    }
			if (stoffice.selectedIndex < 1)
		    {
		        window.alert("Please select a state office");
		        stoffice.focus();
		        return false;
		    }

			if (problem.selectedIndex < 1)
			{
				window.alert("Please select a problem");
				problem.focus();
				return false;
			}

			if (subproblem.selectedIndex < 1)
			{
				window.alert("Please select a subproblem");
				subproblem.focus();
				return false;
			}
		}

   if (usercat.value >= 51 && usercat.value <= 150)
		{
			if (organization.selectedIndex < 1)
			{
				window.alert("Please select a organization");
				organization.focus();
				return false;
			}

			if (problem.selectedIndex < 1)
			{
				window.alert("Please select a problem");
				problem.focus();
				return false;
			}
			if (subproblem.selectedIndex < 1)
			{
				window.alert("Please select a subproblem");
				subproblem.focus();
				return false;
			}

		}


	if (problem.selectedIndex < 1)
    {
        window.alert("Please select a problem");
        problem.focus();
        return false;
    }

	if (descp.value == "")
    {
        window.alert("Please describe the issue");
        descp.focus();
        return false;
    }
	if(term.checked==false )
	{
		alert('You have to agree terms & conditions');
		return false;
	}


	$('.confmname').html('Dear Ms./Mr. '+name.value+', please confirm your contact and location details for quick resolution of the issue.')
	$('.Email').html('Email Address: '+email.value+' ')
	$('.Mobile').html('Mobile Number: '+usermobile.value+' ')
	if (usercat.value == 1)
		{
			$('.Office').html('Problem: '+$("#problem_id option:selected" ).text()+' ')
		}

	if (usercat.value >= 51 && usercat.value <= 150)
		{
			$('.Office').html(''+$('.orglabel').text()+': '+$("#organization_id option:selected" ).text()+'')
		}
	if(usercat.value == 2 || usercat.value == 3){
			$('.Office').html('Office: '+$("#office_id option:selected" ).text()+'')

	}
	if(usercat.value == 4 ||  usercat.value == 5){
			$('.Office').html('Office: '+$("#stateoffice_id option:selected" ).text()+'')

	}
	$('.Location').html('Address: '+locate.value+'')
	$('#complaintConfirm').modal('show');


	//$('.ui.small.modal').modal('show', 'active');

	return false;

}

function disableDropDown()
{


	var ministry 	= document.ContactForm.ministry_id;
	var department 	= document.ContactForm.department_id;
	var office 		= document.ContactForm.office_id;
	var usercat = document.getElementById("usercat_id").value;

	if(usercat == 1)
	{

		document.getElementById("ministry_id").disabled = true;
		document.getElementById("department_id").disabled = true;
		document.getElementById("office_id").disabled = true;
		document.getElementById("statedeptt_id").disabled = true;
		document.getElementById("stateoffice_id").disabled = true;
		document.getElementById("organization_id").disabled = true;
		document.getElementById("subproblem_id").disabled = true;
		document.getElementById("service_id").disabled = true;

			$('#ofhide').hide();
			$('#sphide').hide();
	}
	if(usercat == 2 || usercat == 3 ){

		document.getElementById("organization_id").disabled = true;
		document.getElementById("statedeptt_id").disabled = true;
		document.getElementById("stateoffice_id").disabled = true;
		document.getElementById("service_id").disabled = true;
	}
	if(usercat == 4 || usercat == 5){
		document.getElementById("ministry_id").disabled = true;
		document.getElementById("department_id").disabled = true;
		document.getElementById("office_id").disabled = true;
		document.getElementById("organization_id").disabled = true;
		document.getElementById("service_id").disabled = true;
	}

	if(usercat >= 51 && usercat <= 150)
	{
		document.getElementById("ministry_id").disabled = true;
		document.getElementById("department_id").disabled = true;
		document.getElementById("office_id").disabled = true;
		document.getElementById("statedeptt_id").disabled = true;
		document.getElementById("stateoffice_id").disabled = true;
		document.getElementById("service_id").disabled = true;

	$(document).ready(function(){
			//var catg 		= document.ContactForm.usercat_id;
				  $.ajax({
					url:"getOrglabel.php",
					type: "POST",
					data: {id : usercat},
					dataType: "html",
					success:function(data) {
					if(data)
						{
							$('.orglabel').html(data)

						}

					}
				  });

			})


	}
}

function ValidateotpForm()
{

		var number 	= document.getElementById("petition_no").value;
		$('#petition_no').focus();
		var userEmail 	= document.getElementById("security_code").value;
		
		
    if (number=="" )
    {
       // window.alert("Please enter your moblie number or email address");
		$('#alertDiv font').html("Please Enter Petition Number ");
		
		$('#alertDiv').show();
        return false;
    }
	else{
		if (userEmail=="")
		{
				//window.alert("Please enter a valid email address");
				$('#userEmail1').focus();
				$('#alertDiv font').html("Please Enter Security Code. ");
				$('#alertDiv').show();
				email.focus();
				return false;
			
		}
		else
		{
			if(!validateMobile(number.value))
			{
				//window.alert("Please enter a valid mobile number ");
				//$('#alertDiv font').html("Please enter your Enter Security Code.");
				$('#alertDiv').hide();
				number.focus();
				return false;
			}
		}




		if(authuser != '0'){
						document.otpForm.submit();
					}


		else if($('#otp').val())
		{
			$.ajax({
				url:"azxfunction.php",
				type: "POST",
				data: {type : 'check_otp',mobile : number.value, email : email.value , otp :otp.value},
				dataType: "html",
				success:function(data) {
					obj = JSON.parse(data);
					//alert(obj.total)
					if(obj.req == 2)
					{
						$('#otpsentDiv').show();
						$('#otpsentDiv font').html("Invalid OTP");
						$("#otpsentDiv").css('backgroundColor','#f44336');
						$('#otpsentDiv').css('color','white' );
						$('#alertDiv').hide();
							
					}

					else
					{
						document.otpForm.submit();
						$('#otpsentDiv').show();
						$('#otpsentDiv font').html("Authentication Successful ");
						$("#otpsentDiv").css('backgroundColor','#5cb85c');
						$('#otpsentDiv').css('color','white' );
						$('#alertDiv').hide();

					}
				}
			});
	return false;

		}
		else
		{
			$.ajax({
				url:"azxfunction.php",
				type: "POST",
				data: {type : 'get_otp',mobile : number.value, email : email.value},
				dataType: "html",
				success:function(data) {
					obj = JSON.parse(data);
					//alert(obj.total)
					$("#countOTP").html('Resend OTP ('+obj.total+' of 4)')

					if(obj.req == 2)
					{
						$('.otp-div').show();
						$('#otpsentDiv font').html("OTP sent sucessfully.  ");
						$('#otpsentDiv').css('color','white' );
						//$('#countOTP').css('color','white' );
						$('#otpsentDiv').show();
						$('#alertDiv').hide();
					}
					else if(obj.req == 1)
					{
						//window.alert('You have a valid OTP. Kindly use the same.');
						$('#otpsentDiv font').html("You have a valid OTP. Kindly use the same. ");
						$('#otpsentDiv').css('backgroundColor','#5cb85c');
						$('#otpsentDiv').css('color','white' );
						//$('#countOTP').css('color','white' );
						$('.otp-div').show();
						$('#otpsentDiv').show();
						$('#alertDiv').hide();
						$('#otpexcd').hide();
					}
					/* for onload button color change */
					if (obj.total == 4){
										$("#countOTP").css('backgroundColor','#f44336');
										$('#countOTP').css('color','white' );
										}

					else
					{
						//alert(data);
					}
				}
			});
			return false;
		}
	}
}

//Start -- Validation on otp form <index>
function reSendOTP()
{
    var number = document.otpForm.userNumber;
	var email = document.otpForm.userEmail;
    if (!(number.value || email.value))
    {
        //window.alert("Please enter your moblie number or email address");
		$('#alertDiv font').html("Please enter your moblie number or email address.  ");
		$('#alertDiv').show();
        email.focus();
        return false;
    }
	else{
		if (email.value)
		{
			if(!ValidateEmail(email.value)){
				//window.alert("Please enter a valid email address");
				$('#alertDiv font').html("Please enter a valid email address ");
				$('#alertDiv').show();
				email.focus();
				return false;
			}
		}
		else
		{
			if(!validateMobile(number.value))
			{
				//window.alert("Please enter a valid mobile number ");
				$('#alertDiv font').html("Please enter a valid mobile number");
				$('#alertDiv').show();
				number.focus();
				return false;
			}
		}
		//End -- Validation on otp form <index>

		if($('#otp').val())
		{
			return true;
		}
		else
		{
			$.ajax({
				url:"azxfunction.php",
				type: "POST",
				data: {type : 'resend_otp',mobile : number.value, email : email.value},
				dataType: "html",
				success:function(data) {
					obj = JSON.parse(data);
					if(obj.error)
					{
						//window.alert('You have exceeded your limit for today. Please try with another mobile number or email address.');
						$('#otpsentDiv font').html("You have consumed your OTP limit for today. Please use the OTP already sent or try with another mobile number or email address.");
						$('#otpsentDiv').show();
						$('#otpsentDiv').css('backgroundColor','#f44336');
						$('#countOTP').css('backgroundColor','#f44336');
						$('#countOTP').css('color','white' );
						$('#countOTP').css('cursor', 'not-allowed' );
						$('#countOTP').css('opacity', '0.85' );


						//$('.otp-div').show();
					}
					else
					{
						$("#countOTP").html('Resend OTP ('+obj.total+' of 4)');
						if (obj.total == 2){
								$('#otpsentDiv font').html(" "+obj.total+" out of 4 OTP sent successfully. A maximum of 4 OTPs are allowed per mobile number/email address per day.");
								$('#otpsentDiv').show();
								$('#otpsentDiv').css('backgroundColor','yellow');
								$('#otpsentDiv').css('color','black' );
								//$('#countOTP').css('color','white' );
						}
						if (obj.total == 3){
								$('#otpsentDiv font').html(" "+obj.total+" out of 4 OTP sent successfully. A maximum of 4 OTPs are allowed per mobile number/email address per day.");
								$('#otpsentDiv').show();
								$('#otpsentDiv').css('backgroundColor','orange');
								$('#otpsentDiv').css('color','white' );
								//$('#countOTP').css('color','white' );
						}
						if (obj.total == 4){
								$('#otpsentDiv font').html(" "+obj.total+" out of 4 OTP sent successfully. A maximum of 4 OTPs are allowed per mobile number/email address per day.");
								$('#otpsentDiv').show();
								$('#otpsentDiv').css('backgroundColor','coral');
								$('#otpsentDiv').css('color','white' );
								//$('#countOTP').css('color','white' );
						}
					}
				}
			});

			return false;

		}

	}

}
function reSendOTPemail()
{
		var emailnew 	= document.emailForm.usernewEmail;

		var uotp 	= document.emailForm.emailotp;

		if (!(emailnew.value))
			{
				if(!ValidateEmail(emailnew.value)){
					$('#alertDivnew font').html("Please enter an email address ");
					$('#alertDivnew').show();
					emailnew.focus();
					return false;
				}
			}
		else{
			if (emailnew.value)
			{
				if(!ValidateEmail(emailnew.value)){
					//window.alert("Please enter a valid email address");
					$('#alertDivnew font').html("Please enter a valid email address ");
					$('#alertDivnew').show();
					emailnew.focus();
					return false;
				}
				else
				{
				$('#alertDivnew').hide();
				}
			}
		}

		//End -- Validation on otp form <index>

		if($('#emailotp').val())
		{
			return true;
		}
		else
		{
			$.ajax({
				url:"azxfunction.php",
				type: "POST",
				data: {type : 'resend_emailotp', email : emailnew.value},
				dataType: "html",
				success:function(data) {
					obj = JSON.parse(data);
					if(obj.error)
					{
						//window.alert('You have exceeded your limit for today. Please try with another mobile number or email address.');
						$('#emailotpDiv font').html("You have consumed your OTP limit for today. Please use the OTP already sent or try with another mobile number or email address.");
						$('#emailotpDiv').show();
						$('#emailotpDiv').css('backgroundColor','#f44336');

					}
					else
					{
						$("#countOTP").html('Resend OTP ('+obj.total+' of 4)');
						if (obj.total == 2){
								$('#emailotpDiv font').html(" "+obj.total+" out of 4 OTP sent successfully. A maximum of 4 OTPs are allowed per mobile number/email address per day.");
								$('#emailotpDiv').show();
								$('#emailotpDiv').css('backgroundColor','yellow');
								$('#emailotpDiv').css('color','black' );
								//$('#countOTP').css('color','white' );
						}
						if (obj.total == 3){
								$('#emailotpDiv font').html(" "+obj.total+" out of 4 OTP sent successfully. A maximum of 4 OTPs are allowed per mobile number/email address per day.");
								$('#emailotpDiv').show();
								$('#emailotpDiv').css('backgroundColor','orange');
								$('#emailotpDiv').css('color','white' );
								//$('#countOTP').css('color','white' );
						}
						if (obj.total == 4){
								$('#emailotpDiv font').html(" "+obj.total+" out of 4 OTP sent successfully. A maximum of 4 OTPs are allowed per mobile number/email address per day.");
								$('#emailotpDiv').show();
								$('#emailotpDiv').css('backgroundColor','coral');
								$('#emailotpDiv').css('color','white' );
								//$('#countOTP').css('color','white' );
						}
					}
				}
			});

			return false;

		}

	}


function reSendOTPmobile()
{
		var mobilenew 	= document.mobileForm.usernewMobile;

		var uotp 	= document.mobileForm.mobileotp;

		if (!(mobilenew.value))
			{
				if(!validateMobile(mobilenew.value)){
					$('#alertDivnewmo font').html("Please enter an mobile number");
					$('#alertDivnewmo').show();
					mobilenew.focus();
					return false;
				}
			}
		else{
			if (mobilenew.value)
			{
				if(!validateMobile(mobilenew.value)){
					$('#alertDivnewmo font').html("Please enter a valid mobile number ");
					$('#alertDivnewmo').show();
					mobilenew.focus();
					return false;
				}
			}
			else
				{
				$('#alertDivnewmo').hide();
				}
		}
		//End -- Validation on otp form <index>

		if($('#mobileotp').val())
		{
			return true;
		}
		else
		{
			$.ajax({
				url:"azxfunction.php",
				type: "POST",
				data: {type : 'resend_mobileotp', mobile : mobilenew.value},
				dataType: "html",
				success:function(data) {
					obj = JSON.parse(data);
					if(obj.error)
					{
						//window.alert('You have exceeded your limit for today. Please try with another mobile number or email address.');
						$('#mobileotpDiv font').html("You have consumed your OTP limit for today. Please use the OTP already sent or try with another mobile number or email address.");
						$('#mobileotpDiv').show();
						$('#mobileotpDiv').css('backgroundColor','#f44336');

					}
					else
					{
						$("#countOTP").html('Resend OTP ('+obj.total+' of 4)');
						if (obj.total == 2){
								$('#mobileotpDiv font').html(" "+obj.total+" out of 4 OTP sent successfully. A maximum of 4 OTPs are allowed per mobile number/email address per day.");
								$('#mobileotpDiv').show();
								$('#mobileotpDiv').css('backgroundColor','yellow');
								$('#mobileotpDiv').css('color','black' );
								//$('#countOTP').css('color','white' );
						}
						if (obj.total == 3){
								$('#mobileotpDiv font').html(" "+obj.total+" out of 4 OTP sent successfully. A maximum of 4 OTPs are allowed per mobile number/email address per day.");
								$('#mobileotpDiv').show();
								$('#mobileotpDiv').css('backgroundColor','orange');
								$('#mobileotpDiv').css('color','white' );
								//$('#countOTP').css('color','white' );
						}
						if (obj.total == 4){
								$('#mobileotpDiv font').html(" "+obj.total+" out of 4 OTP sent successfully. A maximum of 4 OTPs are allowed per mobile number/email address per day.");
								$('#mobileotpDiv').show();
								$('#mobileotpDiv').css('backgroundColor','coral');
								$('#mobileotpDiv').css('color','white' );
								//$('#countOTP').css('color','white' );
						}
					}
				}
			});

			return false;

		}

	}



//Validation for Mobile number
function validateMobile(mobile) {

    var re = /^\d{10}$/;
    return re.test(mobile);
}
function ValidateEmail(inputText)
{
var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
return mailformat.test(inputText);
}
