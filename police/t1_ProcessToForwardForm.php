<script type="text/javascript" charset="utf-8">
$(document).ready(function() {	
	setDatePicker('p1_from_pet_date');
	setDatePicker('p1_to_pet_date');

	$("#p1_Save").click(function(){
		p1_ForwardProcess();
	});

	$('#p1_pageNoList').change(function(){
		p1_loadGrid($('#p1_pageNoList').val(), $('#p1_pageSize').val());
	});

	$('#p1_pageSize').change(function(){
		p1_loadGrid(1, $('#p1_pageSize').val());
	});

	$("#p1_search").click(function(){
		$('#p1_dataGrid').empty();
		p1_loadGrid(1, $('#p1_pageSize').val());
	});

	$("#p1_clear").click(function(){
		p1_clearSerachParams();
	});	
	if ($('#desig_role').val() == 5) {
		//document.getElementById("p1_Save").disabled = true;
	} else {
		document.getElementById("p1_Save").disabled = false;
	}
});

function p1_searchParams(){
	var param="&p_from_pet_date="+$('#p1_from_pet_date').val();
	param+="&p_to_pet_date="+$('#p1_to_pet_date').val();
	param+="&p_petition_no="+$('#p1_petition_no').val();
	param+="&p_source="+$('#p1_source').val(); 
	param+="&ptype="+$('#p1_petition_type').val(); 
	param+="&dept="+$('#dept').val(); 
	param+="&gtype="+$('#gtype').val(); 
	param+="&form_tocken="+$('#formptoken').val();
	return param;
}

function p1_clearSerachParams(){
	$('#p1_from_pet_date').val('');
	$('#p1_to_pet_date').val('');
	$('#p1_petition_no').val('');
	$('#p1_source').val('');
	$('#dept').val('');
	$('#gtype').val('');
	$('#p1_dataGrid').empty();
	p1_loadGrid(1, $('#p1_pageSize').val());
}

function p1_loadGrid(pageNo, pageSize){
	$("#p1_Save").attr("disabled", false);
	document.getElementById("t1_loadmessage").style.display='';
	var param = "mode=p1_search"
			+"&page_size="+pageSize
			+"&page_no="+pageNo;
	param+=p1_searchParams();
	
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "t1_ProcessToForwardAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			// we have the response 
			 p1_createGrid(xml);
			 //alert("============"+xml);
		},  
		error: function(e){ 
			//alert('Error: ' + e);  
		}
	});//ajax end
}
function showMessage(pet_id) {
	//alert(popid);
	document.getElementById('popup_'+pet_id).style.display='';
	var popup = document.getElementById('popup_'+pet_id);
    popup.classList.toggle("show");
}

function hidePopup(pet_id) {
	document.getElementById('popup_'+pet_id).style.display='none';
}
function p1_createGrid(xml){
	$('#p1_dataGrid').empty();
	document.getElementById("t1_loadmessage").style.display='none';
	var desig_role=document.getElementById("desig_role").value;
	setPetitionCount("p1_count", $(xml).find('count').eq(0).text());
	var actTypeCodeOption1= "<option value=''>-- Select Department --</option>";
	var actTypeCodeOption= "<option value=''>-- Select Action Type --</option>";
	$(xml).find('acttype_code').each(function(i)
	{
		actTypeCodeOption += "<option value='"+$(xml).find('acttype_code').eq(i).text()+"'>"+$(xml).find('acttype_desc').eq(i).text()+"</option>";
	});
	
	$(xml).find('petition_id').each(function(i)
	{
		var petition_id = $(xml).find('petition_id').eq(i).text();
		var petition_no = $(xml).find('petition_no').eq(i).text();
		var off_level_pattern_id = $(xml).find('off_level_pattern_id').eq(i).text();
		var off_loc_id = $(xml).find('off_loc_id').eq(i).text();
		var griev_district_id = $(xml).find('griev_district_id').eq(i).text();
		var grievance = $(xml).find('grievance').eq(i).text();
		off_loc_id = (off_loc_id == '') ? -99 : off_loc_id;
		pattern=$(xml).find('dept_off_level_pattern_id').eq(i).text();
		off_level_id=$(xml).find('off_level_id').eq(i).text();
		if(pattern==2) { pattern=3; }else{pattern=pattern;}
		if((griev_district_id==off_loc_id) & off_level_id==7){
			off_level_id=13;
		}
		
		//alert(griev_district_id);
		//griev_district_id
		
		if (grievance != '')  {
			if (grievance.length > 100) {
				//remark = "&amp;" + grievance.substr(0,100);
				remark = grievance.substr(0,100);
				linkforpopup = ' more..';
			} else {
				//remark = "&amp;" + grievance;
				remark = grievance;
				linkforpopup = '';
			}
		} else {
			linkforpopup = '';	
		}
		var link_stat = $(xml).find('link_stat').eq(i).text();
		if(link_stat>0){
			var link_msg="<b><em>"+link_stat+' petition(s) clubbed with this petition is/are already closed please take appropriate action to close this petition.</em></b><br><br>';
		}else{
			var link_msg='';
		}
		var msgid='popup_'+petition_id;
		var pet_loc_id=$(xml).find('pet_loc_id').eq(i).text();
		$('#p1_dataGrid')
		.append("<tr>"+
		"<td>"+(i+1)+"</id>"+
		"<td><b>Source:</b> "+$(xml).find('source_name').eq(i).text()+"<br><br>"+
		"<b>Petition No. & Date:</b><br>"+
			"<input type='hidden' name='p1_petition_id' id='"+petition_id+"'/>"+
			"<input type='hidden' name='p1_off_loc_id' id='p1_off_loc_id_"+petition_id+"' value="+off_loc_id+">"+
					
			"<a href='javascript:openPetitionStatusReport1("+petition_id+");' title='Petition Process Report'>"+
			$(xml).find('petition_no').eq(i).text()+"<br>Dt.&nbsp;"+ $(xml).find('petition_date').eq(i).text()+
			"</a>"+
		"</td>"+
		"<td>"+$(xml).find('pet_address').eq(i).text()+"</td>"+		
		
		"<td><b>Address:</b>&nbsp"+$(xml).find('gri_address').eq(i).text()+"<br><br>"+"<b>Petition:</b>&nbsp;"+remark+
		"&nbsp;&nbsp;&nbsp;<a onclick='showMessage("+petition_id+")' style='cursor: pointer;' >"+linkforpopup+"</a>"+
		"<div class='popup'>"+"<span class='popuptext' name='popup' id='popup_"+petition_id+"' onclick='hidePopup("+petition_id+")'>"+grievance+"</span></div>"+
		"</td>"+
		"<td>Petition Cagetory : <select name='p1_gri_type' id='p1_gri_type_"+petition_id+"' onchange='return get_sub_type(p1_gri_type_"+petition_id+","+petition_id+");' disabled></select> "+  "\n" + 
		'<br>Sub Category :' +"<select name='p1_gri_sub_type' id='p1_gri_sub_type_"+petition_id+"' onchange='return get_dept(p1_gri_sub_type_"+petition_id+","+petition_id+");' disabled></select>"+  "\n" + 
		'<br>Department :' +"<select name='p1_dept' id='p1_dept_"+petition_id+"'  disabled>"+actTypeCodeOption1+" </select></td>"+
		"<td>"+""+link_msg+"<select name='p1_act_type_code' id='p1_act_type_code_"+petition_id+"' style='width: 160px;' onchange='p1_action_type_code("+petition_id+");p1_searchOfficeDesign2("+petition_id+","+off_loc_id+","+griev_district_id+");'>"+actTypeCodeOption+"</select>"+
		"<br><br><br>"+
		
		"<input type='hidden' name='p1_pet_loc_id' id='p1_pet_loc_id_"+petition_id+"' value='"+pet_loc_id+"'/>"+
		"<input type='hidden' name='p1_off_level_id' id='p1_off_level_id_"+petition_id+"' value='"+off_level_id+"'/>"+
		"<input type='hidden' name='p1_dept_off_level_pattern_id' id='p1_dept_off_level_pattern_id_"+petition_id+"' value='"+pattern+"'/>"+
		"<input type='hidden' name='p1_off_level_dept_id' id='p1_off_level_dept_id_"+petition_id+"' value='"+$(xml).find('off_level_dept_id').eq(i).text()+"'/>"+
			
		"<span id='p1_fwd_off_"+petition_id+"' style='display:none'>"+	
		"<input type='hidden' name='p1_user_sno' id='p1_user_sno_"+petition_id+"'/>"+
		"<input type='hidden' name='p1_officer' id='p1_officer_"+petition_id+"'/>"+
		"<input type='hidden' name='hid_pattern' id='hid_pattern_"+petition_id+"' value="+$(xml).find('off_level_pattern_id').eq(i).text()+">"+
		"<input type='text' name='p1_design' id='p1_design_"+petition_id+"' style='display:none'/>"+
		"<div name='p1_off_design' id='p1_off_design_"+petition_id+"' ></div>"+
		"</span><br><br>"+
		
		"<span id='p1_enq_off_"+petition_id+"' style='display:none'>"+	
		"<input type='hidden' name='p1_enq_user_sno' id='p1_enq_user_sno_"+petition_id+"'/>"+
		"<input type='hidden' name='p1_enq_officer' id='p1_enq_officer_"+petition_id+"'/>"+
		"<input type='hidden' name='hid_enq_pattern' id='hid_enq_pattern_"+petition_id+"' value="+$(xml).find('off_level_pattern_id').eq(i).text()+">"+
		"<input type='text' name='p1_enq_design' id='p1_enq_design_"+petition_id+"' style='display:none'/>"+
		"<div name='p1_enq_off_design' id='p1_enq_off_design_"+petition_id+"' ></div>"+
		"</span>"+
		
		"</td>"+
		
		
		 "<td><input type='text' style='width:170px' name='p1_file_no' id='p1_file_no_"+petition_id+"' maxlength='50' onKeyPress='return checkFileNoProcessing(event);' value=''/>  "+  "\n" +"  <input type='text' style='width:120px' name='p1_file_date' id='p1_file_date_"+petition_id+"' maxlength='25' value='' onchange='return validatedate1(p1_file_date_"+petition_id+","+petition_id+");' /> "+
		
		"<br><br><textarea name='p1_remark' id='p1_remark_"+petition_id+"' onKeyPress='return characters_numsonly_grievance(event);'></textarea></td>"+
		"</tr>");
		
		setDatePicker('p1_file_date_'+petition_id);
		addDate();
		if (desig_role == 5) {
			//document.getElementById('p1_act_type_code_'+petition_id).disabled=true;
		}
		
		//Populate grivence sub type combo box.
		var temp = $(xml).find('source_name').eq(0).text();
		
		//Populate grivence type combo box.
		 populateComboBox(xml, 'p1_gri_type_'+petition_id, (temp.charCodeAt(0)>=2944 && temp.charCodeAt(0)<=3071) ? 'கோரிக்கை பெற்ற வழி':'Grievence Type', 'gre_id_'+$(xml).find('rownum').eq(i).text(), 'gre_desc_'+$(xml).find('rownum').eq(i).text(), $(xml).find('griev_type_id').eq(i).text());
		 
		populateComboBox(xml, 'p1_gri_sub_type_'+petition_id, (temp.charCodeAt(0)>=2944 && temp.charCodeAt(0)<=3071) ? 'கோரிக்கை பெற்ற வழி':'Grievence Sub Type', 'id_'+$(xml).find('rownum').eq(i).text(), 'desc_'+$(xml).find('rownum').eq(i).text(), $(xml).find('griev_subtype_id').eq(i).text());
		
		populateComboBox(xml, 'p1_dept_'+petition_id, 'Department', 'dept_id_'+$(xml).find('rownum').eq(i).text(), 'dept_desc_'+$(xml).find('rownum').eq(i).text(), $(xml).find('dept_id').eq(i).text());
	   
	});
 
 
	var pageNo = $(xml).find('pageNo').eq(0).text();
	var pageSize = $(xml).find('pageSize').eq(0).text();
	var noOfPage = $(xml).find('noOfPage').eq(0).text();
	$("[name=p1_design]").attr('disabled','disabled');
	drawPagination('p1_pageFooter1', 'p1_pageFooter2','p1_pageSize', 'p1_pageNoList', 'p1_next', 'p1_previous', 'p1_noOfPageSpan', 'p1_loadGrid', pageNo, pageSize, noOfPage);
}

function p1_clearOfficeDesign(petition_id){
	$('#p1_user_sno_'+petition_id).val('');
	$('#p1_design_'+petition_id).val('');
}

function p1_action_type_code(petition_id){
	if($('#p1_act_type_code_'+petition_id).val()=="A" || $('#p1_act_type_code_'+petition_id).val()=="R"){
		$('#p1_fwd_off_'+petition_id).val('');
		$('#p1_fwd_off_'+petition_id).hide();
		$('#p1_enq_off_'+petition_id).val('');
		$('#p1_enq_off_'+petition_id).hide();
	}
	else if ($('#p1_act_type_code_'+petition_id).val()=="F" || $('#p1_act_type_code_'+petition_id).val()=="D") {
		$('#p1_fwd_off_'+petition_id).show();
		$('#p1_enq_off_'+petition_id).show();
	} else {
		$('#p1_fwd_off_'+petition_id).hide();
		$('#p1_enq_off_'+petition_id).hide();
	}
}

function addDate(){
	var date = new Date();
	var newdate = new Date(date);
	setDateFormat(date, "#p1_file_date");
  
}
function get_sub_type(griev_id,petition_id)
{
	var param = "mode=p1_get_sub_type"
		+"&griev_id="+griev_id.value;
		param+="&form_tocken="+$('#formptoken').val();
		 
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "t1_ProcessToForwardAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			populateComboBox(xml, "p1_gri_sub_type_"+petition_id, 'GrievenceSubType', 
			 'griev_subtype_id','griev_subtype_name', '');

		},  
		error: function(e){
			//alert('Error: ' + e);  
		}
	});//ajax end
	
	
}

function get_dept(griev_sub_id,petition_id)
{
   var param = "mode=p1_get_dept"
		+"&griev_sub_id="+griev_sub_id.value;
		param+="&form_tocken="+$('#formptoken').val();
		 
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "t1_ProcessToForwardAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			
		 $(xml).find('dept_id').each(function(i)
		 {
			// alert(">>>>>>"+$(xml).find('count').eq(i).text());
			 var cnt = $(xml).find('count').eq(i).text();
			if(cnt > 1)
				populateComboBox(xml, "p1_dept_"+petition_id, 'Department', 'dept_id','dept_name', '');
			else
				populateComboBoxPlain(xml, "p1_dept_"+petition_id, 'dept_id', 'dept_name');
				
		 });
		},  
		error: function(e){
			//alert('Error: ' + e);  
		}
	});//ajax end 
}

function p1_ForwardProcess(){
	document.getElementById("p1_Save").value="Wait";
	$("#p1_Save").attr("disabled", true);
	var param="mode=p1_Fwd"+"&form_tocken="+$('#formptoken').val();
	var status=false;
	var pet_sno=[], user_id=[], remark=[], action_type_code=[], file_no=[], file_date=[], j=0;
	for(var i=0;i<$("[name=p1_petition_id]").size(); i++){
		var pet_id=$('input[name="p1_petition_id"]')[i].id;

		if($('#p1_act_type_code_'+pet_id).val()!=""){
			if($("#p1_gri_type_"+pet_id).val()==""){
				alert("Please select Grievence Type");
				$("#p1_Save").attr("disabled", false);
				document.getElementById("p1_Save").value="Save";
				return false;
			}
			if($("#p1_gri_sub_type_"+pet_id).val()==""){
				alert("Please select Grievence Sub Type");
				$("#p1_Save").attr("disabled", false);
				document.getElementById("p1_Save").value="Save";
				return false;
			}
			if($('#p1_act_type_code_'+pet_id).val()=="F" 
			   && ($('#select_p1_off_design_'+pet_id).val()=="" && $("#p1_user_sno_"+pet_id).val() =="")){
				alert("Please select Address To");
				$("#p1_Save").attr("disabled", false);
				document.getElementById("p1_Save").value="Save";
				return false;
			}
			if($("#p1_act_type_code_"+pet_id).val()=='A' || $("#p1_act_type_code_"+pet_id).val()=='R')
			{
				if($("#p1_file_no_"+pet_id).val()==""){
					alert("Enter File No. & Date");
					$("#p1_Save").attr("disabled", false);
					document.getElementById("p1_Save").value="Save";
					return false;
				}
				if($("#p1_file_date_"+pet_id).val()==""){
					alert("Select File Date.");
					$("#p1_Save").attr("disabled", false);
					document.getElementById("p1_Save").value="Save";
					return false;
				}
			}
			
			if($("#p1_act_type_code_"+pet_id).val()=='R')
			{
				if($("#p1_remark_"+pet_id).val()==""){
					alert("Enter Remarks");
					$("#p1_Save").attr("disabled", false);
					document.getElementById("p1_Save").value="Save";
					return false;
				}
			}
			  
			if ($('#select_p1_off_design_'+pet_id).val() != '') {  //select_p1_enq_off_design_
				user_assign = $('#select_p1_off_design_'+pet_id).val();
			} else {
				user_assign = $("#p1_user_sno_"+pet_id).val();
			}
			
			if ($('#select_p1_enq_off_design_'+pet_id).val() != '') {  //select_p1_enq_off_design_
				enq_user_assign = $('#select_p1_enq_off_design_'+pet_id).val();
			} else {
				enq_user_assign='';
			}
			
			if ($('#select_p1_enq_off_design_'+pet_id).val() == '') {
				msg = "The Enquiry Officer is not selected, Supervisory Officer will be the Enquiry Officer also. Do you want to continue?";
				reply = confirm(msg);
				if (reply == false) {
					$("#p1_Save").attr("disabled", false);
					document.getElementById("p1_Save").value="Save";
					return false;
				}
			}
			var fdate = $("#p1_file_date_"+pet_id).val();
			if (fdate != "") {
				var datearray = fdate.split("/");
				var newdate = datearray[1] + '/' + datearray[0] + '/' + datearray[2];
			} else {
				var newdate = "";	
			}
			
			if($("#p1_gri_sub_type_"+pet_id).val()!="" && $('#p1_act_type_code_'+pet_id).val()!=""){
				status=true;
				param += "&pet_sno[]="+pet_id;
				param += "&p1_user_sno[]="+user_assign;
				param += "&p1_enq_user_sno[]="+enq_user_assign;
				param += "&p1_file_no[]="+ $("#p1_file_no_"+pet_id).val();
				param += "&p1_file_date[]="+ newdate;
				param += "&p1_remark[]="+$("#p1_remark_"+pet_id).val();
				param += "&p1_gri_type[]="+$("#p1_gri_type_"+pet_id).val();
				param += "&p1_gri_sub_type[]="+$("#p1_gri_sub_type_"+pet_id).val();
				param += "&p1_dept[]="+$("#p1_dept_"+pet_id).val();
				param += "&p1_act_type_code[]="+$('#p1_act_type_code_'+pet_id).val();
				j++;
			}
		}
		
	}
	if(status){
		$.ajax({
			type: "POST",
			dataType: "xml",
			url: "t1_ProcessToForwardAction.php",
			data: param,  
			
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){
				var status = $(xml).find('status').eq(0).text();
				//alert("===="+status);
				if(status=='S'){
					alert($(xml).find('tot').eq(0).text()+"\n\n"+
						$(xml).find('f').eq(0).text()+"\n"+
						$(xml).find('d').eq(0).text()+"\n"+
						$(xml).find('a').eq(0).text()+"\n"+
						$(xml).find('r').eq(0).text()+"\n"+
						"processed successfully.\n\n"+
						$(xml).find('fc').eq(0).text()+
						"  (If any, might already have been processed.)");
					p1_loadGrid(1, $('#p1_pageSize').val());//Reload grid
					$("#p1_Save").attr("disabled", false);
					document.getElementById("p1_Save").value="Save";
				}
				else{
					alert($(xml).find('fc').eq(0).text() + "("+$(xml).find('msg').eq(0).text()+ " (If any, might already have been processed.)");
					p1_loadGrid(1, $('#p1_pageSize').val());//Reload grid
					$("#p1_Save").attr("disabled", false);
					document.getElementById("p1_Save").value="Save";
				}
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			} 
		});//ajax end
	}
	else{
		alert("Please fill atleast one petition to Forward Action");
		$("#p1_Save").attr("disabled", false);
		document.getElementById("p1_Save").value="Save";
		return false;	 
	}
	
}

function p1_searchOfficeDesign2(petition_id,off_loc_id,griev_district_id){
      
	var griev_type_id = $('#p1_gri_type_'+petition_id).val();
	var griev_sub_type_id = $('#p1_gri_sub_type_'+petition_id).val();
	var dept_id = $('#p1_dept_'+petition_id).val(); 
	var act_type_code = $('#p1_act_type_code_'+petition_id).val(); 
	
	pet_loc_id= document.getElementById("p1_pet_loc_id_"+petition_id).value;
	off_level_id= document.getElementById("p1_off_level_id_"+petition_id).value;
	dept_off_level_pattern_id= document.getElementById("p1_dept_off_level_pattern_id_"+petition_id).value;
	off_level_dept_id= document.getElementById("p1_off_level_dept_id_"+petition_id).value;
	
	 var param =  "mode=p1_search"
		+"&petition_id="+petition_id
		+"&off_loc_id="+off_loc_id
		+"&griev_district_id="+griev_district_id
		+"&griev_type_id="+griev_type_id
		+"&griev_sub_type_id="+griev_sub_type_id
		+"&dept_id="+dept_id
		+"&pet_loc_id="+pet_loc_id
		+"&off_level_id="+off_level_id
		+"&dept_off_level_pattern_id="+dept_off_level_pattern_id
		+"&off_level_dept_id="+off_level_dept_id
		+"&act_type_code="+act_type_code
		+"&form_tocken="+$('#formptoken').val();
//alert (param);
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "p1_OfficeDesignSearchDropdown.php", 
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
		 var prev_dept_id= 0;	
		 var optionTag1= "<select name='select_p1_off_design_' id='select_p1_off_design_"+petition_id+"' onchange='concernedOfficer("+petition_id+")'>";	
		 optionTag1+= "<option value=''>-- Select Enquiry Filing Officer--</option>";
		 $(xml).find('dept_desig_id').each(function(i)
		 {	
			  if (prev_dept_id !== $(xml).find('off_level_dept_id').eq(i).text()) {
				 optionTag1 += "<optgroup label='"+$(xml).find('off_level_name').eq(i).text()+"'>";  
			  }	
			  var desg_name = $(xml).find('dept_desig_name').eq(i).text() +"-" + $(xml).find('off_location').eq(i).text();
			  var dept_user_id = $(xml).find('dept_user_id').eq(i).text();
			  optionTag1 += "<option value='"+dept_user_id+"'>"+desg_name+"</option>";
			  prev_dept_id=$(xml).find('off_level_dept_id').eq(i).text();
				
		 });
			//p1_enq_off_design ,select_p1_enq_off_design_ concernedOfficer(petition_id)
		    optionTag1+="</select>";
			document.getElementById('p1_off_design_'+petition_id).innerHTML=optionTag1;
			var optionTag2= "<select name='select_p1_enq_off_design_' id='select_p1_enq_off_design_"+petition_id+"'>";	
			optionTag2+= "<option value=''>-- Select Enquiry Officer--</option>";			
			optionTag2+="</select>";
			document.getElementById('p1_enq_off_design_'+petition_id).innerHTML=optionTag2;
			 
		},  
		error: function(e){
			//alert('Error: ' + e);  
		}
	});//ajax end
	 
	 
 }

function concernedOfficer(petid) {
	var sup_officer = document.getElementById("select_p1_off_design_"+petid).value;
	document.getElementById("p1_officer_"+petid).value = sup_officer;
	populateEnquiryOfficer(petid,sup_officer);
}

function populateEnquiryOfficer(petition_id,sup_officer=1) { //p1_enq_search
	var griev_type_id = $('#p1_gri_type_'+petition_id).val();
	var griev_sub_type_id = $('#p1_gri_sub_type_'+petition_id).val();
	var dept_id = $('#p1_dept_'+petition_id).val(); 
	var act_type_code = $('#p1_act_type_code_'+petition_id).val(); 
	var off_loc_id = $('#p1_off_loc_id_'+petition_id).val()
	
	pet_loc_id= document.getElementById("p1_pet_loc_id_"+petition_id).value;
	off_level_id= document.getElementById("p1_off_level_id_"+petition_id).value;
	dept_off_level_pattern_id= document.getElementById("p1_dept_off_level_pattern_id_"+petition_id).value;
	off_level_dept_id= document.getElementById("p1_off_level_dept_id_"+petition_id).value;
	
	 var param =  "mode=p1_enq_search"
		+"&petition_id="+petition_id
		+"&off_loc_id="+off_loc_id
		+"&griev_type_id="+griev_type_id
		+"&griev_sub_type_id="+griev_sub_type_id
		+"&dept_id="+dept_id
		+"&pet_loc_id="+pet_loc_id
		+"&off_level_id="+off_level_id
		+"&dept_off_level_pattern_id="+dept_off_level_pattern_id
		+"&off_level_dept_id="+off_level_dept_id
		+"&act_type_code="+act_type_code
		+"&sup_officer="+sup_officer
		+"&form_tocken="+$('#formptoken').val();
//alert (param);
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "p1_OfficeDesignSearchDropdown.php", 
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			//alert($(xml).find('off_level_dept_id').eq(0).text());
		 var prev_dept_id= 0;	
		 var optionTag2= "<select name='select_p1_enq_off_design_' id='select_p1_enq_off_design_"+petition_id+"'>";	
		optionTag2+= "<option value=''>-- Select Enquiry Officer--</option>";
		 $(xml).find('dept_desig_id').each(function(i)
		 {	
			  if (prev_dept_id !== $(xml).find('off_level_dept_id').eq(i).text()) {
				 optionTag2 += "<optgroup label='"+$(xml).find('off_level_name').eq(i).text()+"'>";  
			  }	
			  var desg_name = $(xml).find('dept_desig_name').eq(i).text() +"-" + $(xml).find('off_location').eq(i).text();
			  var dept_user_id = $(xml).find('dept_user_id').eq(i).text();
			  optionTag2 += "<option value='"+dept_user_id+"'>"+desg_name+"</option>";
			  prev_dept_id=$(xml).find('off_level_dept_id').eq(i).text();
				
		 });
			//p1_enq_off_design ,select_p1_enq_off_design_ concernedOfficer(petition_id)
		    optionTag2+="</select>";
			document.getElementById('p1_enq_off_design_'+petition_id).innerHTML=optionTag2;
				 
		},  
		error: function(e){
			//alert('Error: ' + e);  
		}
	});//ajax end
}

//Can be removed
function p1_searchOfficeDesign222(petition_id,off_loc_id,griev_district_id){
      
	var griev_type_id = $('#p1_gri_type_'+petition_id).val();
	var griev_sub_type_id = $('#p1_gri_sub_type_'+petition_id).val();
	var dept_id = $('#p1_dept_'+petition_id).val(); 
	var act_type_code = $('#p1_act_type_code_'+petition_id).val(); 
	
	pet_loc_id= document.getElementById("p1_pet_loc_id_"+petition_id).value;
	off_level_id= document.getElementById("p1_off_level_id_"+petition_id).value;
	dept_off_level_pattern_id= document.getElementById("p1_dept_off_level_pattern_id_"+petition_id).value;
	off_level_dept_id= document.getElementById("p1_off_level_dept_id_"+petition_id).value;
	
	 var param =  "mode=p1_act_type"
		+"&petition_id="+petition_id
		+"&off_loc_id="+off_loc_id
		+"&griev_district_id="+griev_district_id
		+"&griev_type_id="+griev_type_id
		+"&griev_sub_type_id="+griev_sub_type_id
		+"&dept_id="+dept_id
		+"&pet_loc_id="+pet_loc_id
		+"&off_level_id="+off_level_id
		+"&dept_off_level_pattern_id="+dept_off_level_pattern_id
		+"&off_level_dept_id="+off_level_dept_id
		+"&act_type_code="+act_type_code
		+"&form_tocken="+$('#formptoken').val();
//alert ("petition_office_loc_id:::"+pet_loc_id);
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "t1_ProcessToForwardAction.php", 
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			if (act_type_code == 'F') {
				var selectBox = "<select name='p1_fwd_ur_reply_' id='p1_fwd_ur_reply_"+petition_id+"'></select>";
				$("#p1_fwd_reply_"+petition_id).append(selectBox);
				var temp = $(xml).find('off_location_design').eq(0).text();
				populateComboBoxOfficer(xml, "p1_fwd_ur_reply_"+petition_id, (temp.charCodeAt(0)>=2944 && temp.charCodeAt(0)<=3071) ? 'பணியிடம் / பதவி':'Office Location / Designation', 'dept_user_id', 'off_location_design','');
			}
 
			 
		},  
		error: function(e){
			//alert('Error: ' + e);  
		}
	});//ajax end
	 
	 
 }


function p1_searchOfficeDesign1(petition_id,off_loc_id){
	 var griev_type_id = $('#p1_gri_type_'+petition_id).val();
	 var griev_sub_type_id = $('#p1_gri_sub_type_'+petition_id).val();
	 var dept_id = $('#p1_dept_'+petition_id).val();

	if($('#p1_act_type_code_'+petition_id).val()==""){
		alert("Select current action to Get Officer List!!!");
		 
	}
	else if($('#p1_gri_sub_type_'+petition_id).val()==""){
		alert("Select Grievance Sub Type to Get Officer List");
		}
	else{
		openForm("p1_OfficeDesignSearchForm1.php?open_form=P1&petition_id="+petition_id+"&griev_type_id="+griev_type_id+"&griev_sub_type_id="+griev_sub_type_id+"&off_loc_id="+off_loc_id+"&dept_id="+dept_id, "office_design_search");	
	}
}

function t1_searchOfficeDesign(petition_id,off_loc_id){
	 var griev_type_id = $('#p1_gri_type_'+petition_id).val();
	 var griev_sub_type_id = $('#p1_gri_sub_type_'+petition_id).val();
	 var dept_id = $('#p1_dept_'+petition_id).val();
	 var act_type_code = $('#p1_act_type_code_'+petition_id).val();
	 //alert("act_type_code>>>>"+act_type_code);
	$('#p1_off_design_'+petition_id).hide();
	$('#p1_design_'+petition_id).show();
	
	if($('#p1_act_type_code_'+petition_id).val()==""){
		alert("Select current action to Get Officer List!!!");
		 
	}
	else if($('#p1_gri_sub_type_'+petition_id).val()==""){
		alert("Select Grievance Sub Type to Get Officer List");
		}
	else{
		openForm("p1_OfficeDesignSearchForm.php?open_form=PT1&petition_id="+petition_id+"&griev_type_id="+griev_type_id+"&griev_sub_type_id="+griev_sub_type_id+"&off_loc_id="+off_loc_id+"&dept_id="+dept_id+"&act_type_code="+act_type_code, "office_design_search");	
	}
}

function pt1_returnDesignationSearch(petition_id, userID, offLoc_designName){
	$('#p1_user_sno_'+petition_id).val(userID);
	$('#p1_design_'+petition_id).val(offLoc_designName);
}

function validatedate1(inputText,pet_id){
     var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/; 
	 // Match the date format through regular expression  
	  
 if(inputText.value.match(dateformat))  
{  
	  document.form1.text1.focus();  
	  //Test which seperator is used '/' or '-'  
	  var opera1 = inputText.value.split('/');  
	  var opera2 = inputText.value.split('-');  
	  lopera1 = opera1.length;  
	  lopera2 = opera2.length;  
	  // Extract the string into month, date and year  
	  if (lopera1>1)  
	  {  
	  var pdate = inputText.value.split('/');  
	  }  
	  else if (lopera2>1)  
	  {  
	  var pdate = inputText.value.split('-');  
	  }  
	  var mm  = parseInt(pdate[0]);  
	  var dd = parseInt(pdate[1]);  
	  var yy = parseInt(pdate[2]);  
	  // Create list of days of a month [assume there is no leap year by default]  
	  var ListofDays = [31,28,31,30,31,30,31,31,30,31,30,31];  
	  if (mm==1 || mm>2)  
	  {  
	  if (dd>ListofDays[mm-1])  
	  {  
	  alert('Invalid date format!');  
	  return false;  
	  }  
	  }  
	  if (mm==2)  
	  {  
	  var lyear = false;  
	  if ( (!(yy % 4) && yy % 100) || !(yy % 400))   
	  {  
	  lyear = true;  
	  }  
	  if ((lyear==false) && (dd>=29))  
	  {  
	  alert('Invalid date format!');  
	  return false;  
	  }  
	  if ((lyear==true) && (dd>29))  
	  {  
	  alert('Invalid date format!');  
	  return false;  
	  }  
	  }  
} 
else  
  {  
  
  alert("Invalid date format!");  
   document.getElementById("p1_file_date_"+pet_id).value=""; 
  return false;  
  }  
}
/////////////////////////////
 function openPetitionStatusReport1(petition_id){
	document.getElementById("petition_id1").value=petition_id;
	document.petition_process1.target = "Map";
	document.petition_process1.method="post";  
	document.petition_process1.action = "p_PetitionProcessDetails.php";
	map = window.open("", "Map", "status=0,title=0,fullscreen=yes,scrollbars=1,resizable=0");
	if(map){
		document.petition_process1.submit();
	}  
}
 
</script>
<form method="post" name="petition_process1" id="petition_process1">
<table class="searchTbl" style="border-top: 1px solid #000000;">
	<tbody>
	
	<tr>
	  <th style="width:14%;"><?PHP echo $label_name[19];//Petition Period?></th>
	  <th style="width:12%;"><?PHP echo $label_name[22];//Petition No.?></th>
	  <th style="width:10%;"><?PHP echo $label_name[35];//Petition Type?></th>
	  <th style="width:12%;"><?PHP echo $label_name[23];//Source?></th>
	  <th style="width:12%;"><?PHP echo $label_name[34];//Department?></th>
	  <th style="width:12%;"><?PHP echo $label_name[33];//Petition Main Category?></th>
	 
	</tr>
	  
	<tr>
	    <td class="from_to_dt" style="width:14%;">
        	<?PHP echo $label_name[20];//From?>&nbsp;<input type="text" name="p1_from_pet_date" id="p1_from_pet_date" maxlength="12" 
            style="width: 90px;" onchange="return validatedate(p1_from_pet_date,'p1_from_pet_date'); "/>
        	&nbsp;<?PHP echo $label_name[21];//To?>&nbsp;
            <input type="text" name="p1_to_pet_date" id="p1_to_pet_date" maxlength="12" 
            style="width: 90px;" onchange="return validatedate(p1_to_pet_date,'p1_to_pet_date'); "/>
        </td>
		<td style="width:12%;"><input type="text" name="p1_petition_no" id="p1_petition_no" onKeyPress="return checkPetNo(event);" maxlength="25"/></td>
		<td style="width:10%;">
		<select name="p1_petition_type" id="p1_petition_type" style="width:130px;">
            	<option value="">-- Select --</option>
                <?PHP 
					if ($userProfile->getDesig_roleid() == 5) {
						$query="SELECT pet_type_id, pet_type_name, pet_type_tname, enabling, ordering FROM lkp_pet_type where pet_type_id!=4 order by pet_type_id";
					} else {
						$query="SELECT pet_type_id, pet_type_name, pet_type_tname, enabling, ordering FROM lkp_pet_type order by pet_type_id";
					}
					$result = $db->query($query);
					$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray as $row){
						if($_SESSION["lang"]=='E'){
						echo "<option value='".$row['pet_type_id']."'>".$row['pet_type_name']."</option>";
						}else{
						echo "<option value='".$row['pet_type_id']."'>".$row['pet_type_tname']."</option>";
						}
					}
				?>
            </select>
		</td>
        <td style="width:12%;">
        	<select name="p1_source" id="p1_source" style="width:140px;">
            	<option value="">-- Select Source --</option>
                <?PHP 
					$query="SELECT source_id, source_name,source_tname FROM lkp_pet_source WHERE enabling ORDER BY source_name";
				    
					$result = $db->query($query);
					$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray as $row){
						if($_SESSION["lang"]=='E'){
						echo "<option value='".$row['source_id']."'>".$row['source_name']."</option>";
						}else{
						echo "<option value='".$row['source_id']."'>".$row['source_tname']."</option>";	
						}
					}
				?>
            </select>
        </td>
		
		<td style="width:12%;">
          <select name="dept" id="dept">
          <option value="">-- Select Department --</option>
          <?PHP 
          $dept_sql = "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
						FROM usr_dept where dept_id>0 ORDER BY dept_name";
			  if ($userProfile->getOff_level_id() == 1) { 					
					$dept_sql ="SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
					FROM usr_dept WHERE dept_id=".$userProfile->getDept_id()." ORDER BY dept_name";
			 } else if($userProfile->getDept_coordinating()&& $userProfile->getOff_coordinating()) {
					//01/11/2017  Registration Dept Officials to be moved from Miscellaneous Dept to IGR Dept(ID = 12) and 
					//the condition 'and dept_id<12' is to be deleted later
					$dept_sql = "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
					FROM usr_dept where dept_id>0 and dept_id<12 ORDER BY dept_name";	
					$dept_sql = "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
					FROM usr_dept where dept_id>0 ORDER BY dept_name";
			 } else  {
					$dept_sql = "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
					FROM usr_dept WHERE dept_id=".$userProfile->getDept_id()." ORDER BY dept_name";
			 }				
				$res = $db->query($dept_sql);
				$row_arr = $res->fetchall(PDO::FETCH_ASSOC);
				foreach($row_arr as $row) {
					$dept_name=$row['dept_name'];
					echo "<option value='".$row['dept_id']."'>$dept_name</option>";	
				}
          ?>
          </select>
      </td>
	  
	  <td style="width:12%;">
          <select name="gtype" id="gtype">
          <option value="">-- Select Category --</option>
          <?PHP 
   			$gre_sql = "SELECT DISTINCT(griev_type_id), griev_type_code, 
						griev_type_name, griev_type_tname FROM vw_usr_dept_griev_subtype WHERE 
						dept_id = ".$userProfile->getDept_id()." ORDER BY griev_type_name";	
			
			$gre_rs=$db->query($gre_sql);
			while($row = $gre_rs->fetch(PDO::FETCH_BOTH)) {
				$grename=$row["griev_type_name"];
				$gretname = $row["griev_type_tname"];
				if($_SESSION["lang"]=='E'){
				$gre_name=$grename;
				}else{
				$gre_name=$gretname;	
				}
				print("<option value='".$row["griev_type_id"]."' >".$gre_name."</option>");	
			}
          ?>
          </select>
      </td>
	  </tr>
	  <tr>
		<td colspan="6">
        	<input type="button" name="p1_search" id="p1_search" value="<?PHP echo $label_name[24];//Search?>" class="button"/>
        	<input type="button" name="p1_search" id="p1_clear" value="<?PHP echo $label_name[25];//Clear?>" class="button"/>
        </td>
	</tr>
	</tbody>
</table>
<table class="existRecTbl">
	<thead>
	<tr>
		<th><?PHP echo $label_name[7];//Existing Details?></th>
		<th><?PHP echo $label_name[8];//Page&nbsp;Size?><select name="p1_pageSize" id="p1_pageSize" class="pageSize">
				<option value="15" selected="selected">15</option>
				<option value="30">30</option>
				<option value="50">50</option>
			</select>
		</th>
	</tr>
	</thead>
</table>

<table class="gridTbl">
	<thead>
		<tr>
        	<th style="width: 3%;"><?PHP echo $label_name[32];//Sl. No?></th>
			<th style="width: 24%;"><?PHP echo $label_name[11].' and '.$label_name[9];//Source?></th>
			<th style="width: 15%;"><?PHP echo $label_name[10];//Petitioner's Communication Address?></th>            
            <th style="width: 15%;"><?PHP echo $label_name[13].' and ';//Grievance Type & Address?><?PHP echo $label_name[12];//Grievance?></th>                 
            <th style="width: 18%;"><?PHP echo $label_name[30];//Grievance Sub Type?></th>
            <th style="width: 21%;"><?PHP echo $label_name[15].' and ';//Current Action?><?PHP echo $label_name[16];//Addressed To?></th>
            <th style="width: 13%;"><?PHP echo $label_name[29].' and '; //File No. & File Date ?><?PHP echo $label_name[17];//Current Remarks?></th>
		</tr>
	</thead>
	<tbody id="p1_dataGrid"></tbody>
</table>
<div id="t1_loadmessage" div align="center" style="display:none"><img src="images/wait.gif" width="100" height="90" alt=""/></div>
<table class="paginationTbl">
	<tbody>
		<tr id="p1_pageFooter1" style="display: none;">
			<td id="p1_previous"></td>
			<td>Page<select id="p1_pageNoList" name="p1_pageNoList" class="pageNoList"></select><span id="p1_noOfPageSpan"></span></td>
			<td id="p1_next"></td>
		</tr>
		<tr id="p1_pageFooter2" style="display: none;"><td colspan="3" class="emptyTR"></td>
		</tr>
        <tr>
        	<td colspan="3" class="emptyTR">
            	<input type="button" class="button" value="<?PHP echo $label_name[26];//Save?>" id="p1_Save" name="p1_Save">
            <?php
            $ptoken = md5(session_id() . $_SESSION['salt']);
            $_SESSION['formptoken']=$ptoken;
            ?>
            <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" />
            <input type="hidden" name="petition_id1" id="petition_id1" />
            </td>
		</tr>
	</tbody>
</table>
</form>

