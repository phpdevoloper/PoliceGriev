<script type="text/javascript" charset="utf-8">
$(document).ready(function()
{
	setDatePicker('p4_from_pet_date');
	setDatePicker('p4_to_pet_date');
	
	$("#p4_Save").click(function(){
		p4_PetitionProcess();
	});
	
	$("#p4_search").click(function(){
		$('#p4_dataGrid').empty();
		p4_loadGrid(1, $('#p4_pageSize').val());
	});
	
	$('#p4_pageNoList').change(function(){
		p4_loadGrid($('#p4_pageNoList').val(), $('#p4_pageSize').val());
	});
	
	$('#p4_pageSize').change(function(){
		p4_loadGrid(1, $('#p4_pageSize').val());
	});
	
	$("#p4_clear").click(function(){
		p4_clearSerachParams();
	});
	if ($('#desig_role').val() == 5) {
		//document.getElementById("p4_Save").disabled = true;
	} else {
		document.getElementById("p4_Save").disabled = false;
	}
});

function p4_searchParams(){
	var param="&p_from_pet_date="+$('#p4_from_pet_date').val();
	param+="&p_to_pet_date="+$('#p4_to_pet_date').val();
	param+="&p_petition_no="+$('#p4_petition_no').val();
	param+="&p_source="+$('#p4_source').val(); 
	/* param+="&dept="+$('#p4_dept').val();  */
	param+="&gtype="+$('#p4_gtype').val(); 
	param+="&ptype="+$('#p4_petition_type').val(); 
	param+="&form_tocken="+$('#formptoken').val();
	return param;
}

function p4_clearSerachParams(){
	$('#p4_from_pet_date').val('');
	$('#p4_to_pet_date').val('');
	$('#p4_petition_no').val('');
	$('#p4_source').val('');
	/* $('#p4_dept').val(''); */
	$('#p4_gtype').val('');
	$('#p4_petition_type').val('');
	$('#p4_dataGrid').empty();
	p4_loadGrid(1, $('#p4_pageSize').val());
}

function p4_loadGrid(pageNo, pageSize){
	document.getElementById("t5_loadmessage").style.display='';
	var param = "mode=p4_search"
		+"&page_size="+pageSize
		+"&page_no="+pageNo
		+p4_searchParams();

	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "t5_ProcessTempReplyAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			// we have the response 
			 p4_createGrid(xml);
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		}
	});//ajax end
	
}

function showMessage(pet_id) {
	document.getElementById('popup_'+pet_id).style.display='';
	var popup = document.getElementById('popup_'+pet_id);
    popup.classList.toggle("show");
}

function hidePopup(pet_id) {
	document.getElementById('popup_'+pet_id).style.display='none';
}

function p4_createGrid(xml){
	$('#p4_dataGrid').empty();
	document.getElementById("t5_loadmessage").style.display='none';
	setPetitionCount("p4_count", $(xml).find('count').eq(0).text());
	var desig_role=document.getElementById("desig_role").value;
	
	$(xml).find('pet_action_id').each(function(i)
	{
		var pet_action_id = $(xml).find('pet_action_id').eq(i).text();
		var petition_id = $(xml).find('petition_id').eq(i).text();
		var action_entby = $(xml).find('action_entby').eq(i).text();
		var action_type_name=$(xml).find('action_type_name').eq(i).text();
		var dept_id=$(xml).find('dept_id').eq(i).text();
		var griev_district_id=$(xml).find('griev_district_id').eq(i).text();
		var off_loc_id=$(xml).find('off_loc_id').eq(i).text();
		//alert(griev_district_id);
		var remark = '';
		var remarks = $(xml).find('fwd_remarks').eq(i).text();
		var link_stat = $(xml).find('link_stat').eq(i).text();
		if(link_stat>0){
			var link_msg="<b><em>"+link_stat+' petition(s) clubbed with this petition is/are already closed please take appropriate action to close this petition.</em></b><br><br>';
		}else{
			var link_msg='';
		}
		var first_action_remarks = $(xml).find('first_action_remarks').eq(i).text();
		
		var pet_owner_var=$(xml).find('pet_owner').eq(i).text();
		var gri_address = $(xml).find('gri_address').eq(i).text();
		if (gri_address == null || gri_address== '') {
			gri_address = '-';
		} 
		if (remarks != '')  {
			if (remarks.length > 100) {
				remark = "<br><b>Remarks: </b>" + remarks.substr(0,100);
				linkforpopup = ' more..';
			} else {
				remark = "<br><b>Remarks: </b>" + remarks;
				linkforpopup = '';
			}
		} else {
			linkforpopup = '';	
		}
		var msgid='popup_'+petition_id;
		if (first_action_remarks != '') {
			first_action_label = '<br><br><b>First action and initial instruction: </b>'+first_action_remarks;
		} else {
			first_action_label = '';
		}
		var actTypeCodeOption= "<option value=''>-- Select Action Type --</option>";
		$(xml).find('acttype_code_'+petition_id).each(function(i)
		{
			actTypeCodeOption += "<option value='"+$(xml).find('acttype_code_'+petition_id).eq(i).text()+"'>"+$(xml).find('acttype_desc_'+petition_id).eq(i).text()+"</option>";
		});
		
		$('#p4_dataGrid')
		.append("<tr>"+
		"<td>"+$(xml).find('rownum').eq(i).text()+"</id>"+
		"<td><b>Source: </b>"+$(xml).find('source_name').eq(i).text()+"<br>"+
		"<b>Petition Type: </b>"+$(xml).find('pet_type_name').eq(i).text()+
		"<br><br><b>Petition No. & Date:</b><br>"+
			"<input type='hidden' name='p4_pet_action_id' id='"+pet_action_id+"' value='"+pet_action_id+"'/>"+
			"<input type='hidden' name='p4_dept_id' id='p4_dept_id_"+pet_action_id+"' value='"+dept_id+"'/>"+
			"<input type='hidden' name='p4_griev_district_id' id='p4_griev_district_id_"+pet_action_id+"' value='"+griev_district_id+"'/>"+
			"<input type='hidden' name='p4_off_loc_id' id='p4_off_loc_id_"+pet_action_id+"' value='"+off_loc_id+"'/>"+
			"<input type='hidden' name='p4_petition_id' id='p4_petition_id_"+pet_action_id+"' value='"+petition_id+"'/>"+
			"<input type='hidden' name='p4_griev_type_id' id='p4_griev_type_id_"+pet_action_id+"' value='"+$(xml).find('griev_type_id').eq(i).text()+"'/>"+	
			"<input type='hidden' name='p4_griev_subtype_id' id='p4_griev_subtype_id_"+pet_action_id+"' value='"+$(xml).find('griev_subtype_id').eq(i).text()+"'/>"+
			"<input type='hidden' name='p4_to_whom' id='p4_to_whom_"+pet_action_id+"' value='"+$(xml).find('to_whom').eq(i).text()+"'/>"+
			"<input type='hidden' name='pet_owner' id='pet_owner_"+pet_action_id+"' value='"+$(xml).find('pet_owner').eq(i).text()+"'/>"+
			
			"<input type='hidden' name='p4_pet_loc_id' id='p4_pet_loc_id_"+pet_action_id+"' value='"+$(xml).find('pet_loc_id').eq(i).text()+"'/>"+
			"<input type='hidden' name='p4_off_level_id' id='p4_off_level_id_"+pet_action_id+"' value='"+$(xml).find('off_level_id').eq(i).text()+"'/>"+
			"<input type='hidden' name='p4_dept_off_level_pattern_id' id='p4_dept_off_level_pattern_id_"+pet_action_id+"' value='"+$(xml).find('dept_off_level_pattern_id').eq(i).text()+"'/>"+
			"<input type='hidden' name='p4_off_level_dept_id' id='p4_off_level_dept_id_"+pet_action_id+"' value='"+$(xml).find('off_level_dept_id').eq(i).text()+"'/>"+
	
			"<input type='hidden' name='p4_source_id' id='p4_source_id_"+pet_action_id+"' value='"+$(xml).find('source_id').eq(i).text()+"'/>"+
			
			"<a href='javascript:openPetitionStatusReport4("+petition_id+");' title='Petition Process Report'>"+
			$(xml).find('petition_no').eq(i).text()+"<br>Dt.&nbsp;"+ $(xml).find('petition_date').eq(i).text()+
			"</a>"+
		"</td>"+
		"<td>"+$(xml).find('pet_address').eq(i).text()+"<br><br><b>Mobile :</b>"+$(xml).find('comm_mobile').eq(i).text()+"</td>"+
		
		"<td><b>Petition Category and Sub Category: </b>"+$(xml).find('griev_type_name').eq(i).text()+", "+$(xml).find('griev_subtype_name').eq(i).text()+"<br><b>Office: </b>"+gri_address+"<br>"+
		"<b>Petition: </b>"+$(xml).find('grievance').eq(i).text()+"</td>"+
		
		"<td style='text-align: left;'><b>Last action: </b>"+action_type_name+" on "+$(xml).find('fwd_date').eq(i).text()+" "
		+remark+"&nbsp;&nbsp;&nbsp;<a onclick='showMessage("+petition_id+")' style='cursor: pointer;' >"+linkforpopup+"</a>"+
		first_action_label+
		"<div class='popup'>"+"<span class='popuptext' name='popup' id='popup_"+petition_id+"' onclick='hidePopup("+petition_id+")'>"+remarks+"</span></div>"+
		"</td>"+
		"<td>"+link_msg+
		"<select name='p4_act_type_code' id='p4_act_type_code_"+pet_action_id+"' style='width: 160px;' onchange='p4_action_type_code("+pet_action_id+","+action_entby+","+$(xml).find('griev_subtype_id').eq(i).text()+","+$(xml).find('off_loc_id').eq(i).text()+");alert_remark("+pet_action_id+");'>"+actTypeCodeOption+"</select>"+
		" "+
		"<br><br><span name='p4_fwd_reply' id='p4_fwd_reply_"+pet_action_id+"'></span>"+"<span id='p4_fir_csr_dist_"+pet_action_id+"' style='display:none;'><p style='vertical-align:middle;text-align:center;display:table-cell;'>__________________________________________________</p><b>FIR/CSR Details:</b><select name='p4_fir_csr_dist' id='p4_fir_dist_"+pet_action_id+"' onchange='load_ext_pst5("+pet_action_id+");'><option>--Select District--</option></select><br><br><select name='p4_fir_circle' id='p4_fir_circle_"+pet_action_id+"'><option>--Select Police Station--</option></select><br><br><input name='p4_fir_year' id='p4_fir_year_"+pet_action_id+"' style='width:30%' Placeholder='Year of FIR/CSR' maxlength='4'>&emsp;<input name='p4_fir_no' id='p4_fir_no_"+pet_action_id+"' style='width:60%' Placeholder='FIR/CSR Number' maxlength='150'>&emsp;</span>"+	
		"</td>"+
		"<input type='hidden' name='p4_user_sno' id='p4_user_sno_"+petition_id+"'/>"+
		"<input type='text' name='p4_design' id='p4_design_"+petition_id+"' style='display:none'/>"+
		
		"<div name='p4_off_design' id='p4_off_design_"+pet_action_id+"' style='display:none'>"+
		"&nbsp;&nbsp;<a href='javascript:p4_searchOfficeDesignation("+petition_id+","+pet_action_id+","+$(xml).find('off_loc_id').eq(i).text()+");'>"+
			
		"</td>"+
		"</td>"+
		
		 "<td><input type='text' style='width:170px' name='p4_file_no' id='p4_file_no_"+pet_action_id+"' maxlength='50'  onKeyPress='return checkFileNoProcessing(event);' value='"+$(xml).find('file_no').eq(i).text()+"'/>  "+  "\n" +"  <input type='text' style='width:120px' name='p4_file_date' id='p4_file_date_"+pet_action_id+"' maxlength='25'  value='"+$(xml).find('file_date').eq(i).text()+"' onchange='return validatedate1(p4_file_date_"+pet_action_id+","+pet_action_id+");' />"+
		 
		"<br><textarea name='p4_remark' maxlength='2400' id='p4_remark_"+pet_action_id+"' onKeyPress='return characters_numsonly_grievance(event);'></textarea></td>"+
		"</tr>");
		setDatePicker('p4_file_date_'+pet_action_id);
		addDate();	
		if (desig_role == 5) {
		//	document.getElementById('p4_act_type_code_'+pet_action_id).disabled=true;
			//document.getElementById('p4_file_no_'+pet_action_id).disabled=true;
			//document.getElementById('p4_file_date_'+pet_action_id).disabled=true;
			//$("#p4_file_date_"+pet_action_id).datepick("disable");
			//document.getElementById('p4_remark_'+pet_action_id).disabled=true;
		}	
	});
	
	var pageNo = $(xml).find('pageNo').eq(0).text();
	var pageSize = $(xml).find('pageSize').eq(0).text();
	var noOfPage = $(xml).find('noOfPage').eq(0).text();
	$("[name=p4_design]").attr('disabled','disabled');
	drawPagination('p4_pageFooter1', 'p4_pageFooter2','p4_pageSize', 'p4_pageNoList', 'p4_next', 'p4_previous', 'p4_noOfPageSpan', 'p4_loadGrid', pageNo, pageSize, noOfPage);
}
function p4_searchOfficeDesignation(pet_id,pet_action_id, off_loc_id) {
	var griev_type_id = $('#p4_griev_type_id_'+pet_action_id).val();
	var griev_sub_type_id = $('#p4_griev_subtype_id_'+pet_action_id).val();
	var dept_id = $('#p4_dept_id_'+pet_action_id).val();
	//alert(">>>>"+dept_id);
	openForm("p1_OfficeDesignSearchForm.php?open_form=P5&petition_id="+pet_id+"&griev_type_id="+griev_type_id+"&griev_sub_type_id="+griev_sub_type_id+"&off_loc_id="+off_loc_id+"&dept_id="+dept_id+"&pet_action_id="+pet_action_id, "office_design_search");
}

function p5_returnDesignationSearch(petition_id,p_act_id, userID, offLoc_designName){
	$('#p4_user_sno_'+petition_id).val(userID);
	$('#p4_design_'+petition_id).val(offLoc_designName);
	document.getElementById('p4_design_'+petition_id).style.display = "block";
	document.getElementById('p4_fwd_reply_'+p_act_id).style.display = "none";
	document.getElementById('p4_design_'+petition_id).disabled = true;	
}

function alert_remark(pet_action_id){
	if($('#pet_owner_'+pet_action_id).val()=='SELF' && $('#off_level_id').val()==46){
	if($("#p4_act_type_code_"+pet_action_id).val()== "R"){
		alert("Enter Remarks for Rejection");
		}
	}
}

function p4_action_type_code(pet_action_id, action_entby, griev_sub_type_id, off_loc_id){
	//alert($("#p4_griev_district_id_"+pet_action_id).val());
	//document.getElementById("p4_fir_csr_"+pet_action_id).style.display="none";
	if($('#pet_owner_'+pet_action_id).val()=='SELF' && $('#off_level_id').val()==46){
	if($("#p4_act_type_code_"+pet_action_id).val()== "C"){
	return false;
	}
		}
	document.getElementById('p4_fir_csr_dist_'+pet_action_id).style.display = "none";
	$("#p4_fwd_reply_"+pet_action_id).empty();
	if($("#p4_act_type_code_"+pet_action_id).val()== "" || $("#p4_act_type_code_"+pet_action_id).val()== "T"){
		$("#p4_fwd_reply_"+pet_action_id).empty();
		return false;	
	}
	var act_type_code = $("#p4_act_type_code_"+pet_action_id).val();
	var p4_to_whom = $("#p4_to_whom_"+pet_action_id).val();
	var p4_griev_district_id = $("#p4_griev_district_id_"+pet_action_id).val()
	
	pet_loc_id= document.getElementById("p4_pet_loc_id_"+pet_action_id).value;
	off_level_id= document.getElementById("p4_off_level_id_"+pet_action_id).value;
	dept_off_level_pattern_id= document.getElementById("p4_dept_off_level_pattern_id_"+pet_action_id).value;
	off_level_dept_id= document.getElementById("p4_off_level_dept_id_"+pet_action_id).value;
	var pet_own_t5=$('#pet_owner_'+pet_action_id).val();
	//alert($('#pet_owner_'+pet_action_id).val());
	var param = "mode=p4_act_type"
		+"&p4_act_type_code="+act_type_code
		+"&p4_action_entby="+p4_to_whom
		+"&griev_sub_type_id="+griev_sub_type_id
		+"&off_loc_id="+off_loc_id
		+"&dept_id="+$("#p4_dept_id_"+pet_action_id).val()
		+"&p4_griev_district_id="+p4_griev_district_id
		+"&p4_petition_id="+$("#p4_petition_id_"+pet_action_id).val()
		+"&pet_loc_id="+pet_loc_id
		+"&off_level_id="+off_level_id
		+"&dept_off_level_pattern_id="+dept_off_level_pattern_id
		+"&off_level_dept_id="+off_level_dept_id
		+"&form_tocken="+$('#formptoken').val()		
		+"&pet_owner="+pet_own_t5;
	//alert(p4_griev_district_id);
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "t5_ProcessTempReplyAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			// we have the response 
			if(act_type_code=='N' || act_type_code == 'C' || act_type_code == 'E' || act_type_code == 'Q' || act_type_code == 'I' || act_type_code == 'S'){
				var selectBox = "<select name='p4_fwd_ur_reply' id='p4_fwd_ur_reply_"+pet_action_id+"'></select>";
				$("#p4_fwd_reply_"+pet_action_id).append(selectBox);
			
				populateComboBoxPlain(xml, "p4_fwd_ur_reply_"+pet_action_id, 'dept_user_id', 'off_location_design', $(xml).find('dept_user_id').eq(0).text());		
if (act_type_code == 'I' || act_type_code == 'S') {
					//alert("Hai");
					document.getElementById("p4_fir_csr_dist_"+pet_action_id).style.display = "";t5_load_ext_dist(pet_action_id);
					t5_load_fir_csr(pet_action_id);
					if($('#pet_owner_'+pet_action_id).val()=='SELF' && $('#off_level_id').val()==46){
						document.getElementById('p4_fwd_ur_reply_'+pet_action_id).style.display='none';
						}
					//document.getElementById('p2_fwd_reply_'+p_act_id).style.display = "none";
				}				
			}
			else if(act_type_code == 'F'){
				var selectBox = "<select name='p4_fwd_ur_reply' id='p4_fwd_ur_reply_"+pet_action_id+"'></select>";
				$("#p4_fwd_reply_"+pet_action_id).append(selectBox);
				
				var temp = $(xml).find('off_location_design').eq(0).text();
				populateComboBoxOfficer(xml, "p4_fwd_ur_reply_"+pet_action_id, (temp.charCodeAt(0)>=2944 && temp.charCodeAt(0)<=3071) ? 'பணியிடம் / பதவி':'Office Location / Designation','dept_user_id', 'off_location_design', '');				
			}
			else{
				var temp = $(xml).find('off_location_design').eq(0).text();
				populateComboBox(xml, "p4_fwd_ur_reply_"+pet_action_id, (temp.charCodeAt(0)>=2944 && temp.charCodeAt(0)<=3071) ? 'பணியிடம் / பதவி':'Office Location / Designation', 'dept_user_id', 'off_location_design','');				
			}
			
		},  
		error: function(e){
			//alert('Error: ' + e);  
		}
	});//ajax end
}
function addDate(){
	var date = new Date();
	var newdate = new Date(date);
	setDateFormat(date, "#p4_file_date");
  
}
function checkForActionType(pet_act_id) {
	var act_type_code = $("#p4_act_type_code_"+pet_act_id).val();	
	if (act_type_code == 'F') {
		document.getElementById("p4_off_design_"+pet_act_id).style.display = "block";
	} else {
		document.getElementById("p4_off_design_"+pet_act_id).style.display = "none";
		
	}
}

function p4_PetitionProcess(){
	document.getElementById("p4_Save").value="Wait";
	$("#p4_Save").attr("disabled", true);
	var status=false;
	var param="mode=p4_fwd_reply_temp_save"+"&form_tocken="+$('#formptoken').val();
	var pet_act_sno=[], pet_sno=[], act_type_code=[], file_no=[], file_date=[], remark=[], fwd_ur_reply=[], pet_owner=[], j=0;
	for(var i=0;i<$("[name='p4_pet_action_id']").size(); i++){
		
		var pet_act_id=$('input[name=p4_pet_action_id]')[i].id;
		var pet_id = $("#p4_petition_id_"+pet_act_id).val(); 
				
		if($("#p4_act_type_code_"+pet_act_id).val()!=""){
			status=true;
			if(($("#p4_fwd_ur_reply_"+pet_act_id).val()=="" && $("#p4_user_sno_"+pet_id).val()=="") && ($("#p4_act_type_code_"+pet_act_id).val()=='F' || $("#p4_act_type_code_"+pet_act_id).val() =="N" || $("#p4_act_type_code_"+pet_act_id).val() =="C"|| $("#p4_act_type_code_"+pet_act_id).val() =="I"|| $("#p4_act_type_code_"+pet_act_id).val() =="S")){
				alert("Please select Address to Office Location");
				$("#p4_Save").attr("disabled", false);
				document.getElementById("p4_Save").value="Save";
				return false;
			}
			
			if($("#p4_act_type_code_"+pet_act_id).val()=='I'|| $("#p4_act_type_code_"+pet_act_id).val()=='S')
			{
				if($("#p4_fir_dist_"+pet_act_id).val()==""){					
					alert("Select FIR/SCR Disttrict");
					$("#p4_Save").attr("disabled", false);
					document.getElementById("p4_Save").value="Save";
					return false;
				}
				
				if($("#p4_fir_circle_"+pet_act_id).val()==""){
					alert("Select FIR/SCR Police Station");
					$("#p4_Save").attr("disabled", false);
					document.getElementById("p4_Save").value="Save";
					return false;
				}
				
				if($("#p4_fir_year_"+pet_act_id).val()==""){
					alert("Enter  FIR/CSR Year");
					$("#p4_Save").attr("disabled", false);
					document.getElementById("p4_Save").value="Save";
					return false;
				}
				
				if($("#p4_fir_no_"+pet_act_id).val()==""){
					alert("Enter  FIR/CSR Number");
					$("#p4_Save").attr("disabled", false);
					document.getElementById("p4_Save").value="Save";
					return false;
				}

			}
			
			if($("#p4_act_type_code_"+pet_act_id).val()=='A' || $("#p4_act_type_code_"+pet_act_id).val()=='P')
			{
				if($("#p4_file_no_"+pet_act_id).val()==""){
					alert("Enter File No. & Date");
					$("#p4_Save").attr("disabled", false);
					document.getElementById("p4_Save").value="Save";
					return false;
				}
				if($("#p4_file_date_"+pet_act_id).val()==""){
					alert("Select File Date.");
					$("#p4_Save").attr("disabled", false);
					document.getElementById("p4_Save").value="Save";
					return false;
				}
			}
			
			if($("#p4_act_type_code_"+pet_act_id).val()=='R')
			{
				if($("#p4_remark_"+pet_act_id).val()==""){
					alert("Enter Remarks");
					$("#p4_Save").attr("disabled", false);
					document.getElementById("p4_Save").value="Save";
					return false;
				}
			}
			
			if($("#p4_act_type_code_"+pet_act_id).val()=='N')
			{
				if($("#p4_remark_"+pet_act_id).val()==""){
					alert("Enter Remarks");
					$("#p4_Save").attr("disabled", false);
					document.getElementById("p4_Save").value="Save";
					return false;
				}
			}
			
			var fdate = $("#p4_file_date_"+pet_act_id).val();
			if (fdate != "") {
				var datearray = fdate.split("/");
				var newdate = datearray[1] + '/' + datearray[0] + '/' + datearray[2];
			} else {
				var newdate = "";	
			}
			pet_act_self=$("#p4_act_type_code_"+pet_act_id).val();
			if($('#pet_owner_'+pet_act_id).val()=='SELF' && $('#off_level_id').val()==46){
				if($("#p4_act_type_code_"+pet_act_id).val()=='C'||$("#p4_act_type_code_"+pet_act_id).val()=='R'){
					//pet_act_self='R';
					pet_act_self=$("#p4_act_type_code_"+pet_act_id).val();
					if($("#p4_remark_"+pet_act_id).val()==""){
						alert("Enter Remarks");
						$("#p4_Save").attr("disabled", false);
						document.getElementById("p4_Save").value="Save";
						return false;
					}
				}
			}
			param += "&p4_pet_act_sno[]="+pet_act_id;
			param += "&pet_owner[]="+$("#pet_owner_"+pet_act_id).val();
			param += "&p4_pet_sno[]="+$("#p4_petition_id_"+pet_act_id).val();
			param += "&p4_act_type_code[]="+pet_act_self;
			if($("#p4_act_type_code_"+pet_act_id).val()=='A' || $("#p4_act_type_code_"+pet_act_id).val()=='R' || $("#p4_act_type_code_"+pet_act_id).val()=='T'){
				param += "&p4_fwd_ur_reply[]=";
			}else if (act_type_code == 'I' || act_type_code == 'S') {
					//alert("Hai");
			document.getElementById("t4_fir_csr_dist_"+pet_action_id).style.display = "";t5_load_ext_dist(pet_action_id);
			t5_load_fir_csr(pet_action_id);
					//document.getElementById('p2_fwd_reply_'+p_act_id).style.display = "none";
				}
			else{
				if (+$("#p4_fwd_ur_reply_"+pet_act_id).val() != '') {
					if($('#pet_owner_'+pet_act_id).val()=='SELF' && $('#off_level_id').val()==46){
						param += "&p4_fwd_ur_reply[]=";
					}else{
						param += "&p4_fwd_ur_reply[]="+$("#p4_fwd_ur_reply_"+pet_act_id).val();
					}
				} else {
					if($("#p4_user_sno_"+pet_id).val()!="") {
						param += "&p4_fwd_ur_reply[]="+$("#p4_user_sno_"+pet_id).val();
					} else {
						alert("Please select Forwarded To/ Reply To Office Location");
						$("#p4_Save").attr("disabled", false);
						document.getElementById("p4_Save").value="Save";
						return false;
					}
				}				
				
			}
			param += "&p4_file_no[]="+ $("#p4_file_no_"+pet_act_id).val();
			param += "&p4_file_date[]="+ newdate;
			param += "&p4_remark[]="+ $("#p4_remark_"+pet_act_id).val();
			param += "&p4_fir_csr[]="+$("#p4_fir_csr_"+pet_act_id).val();
			
			param += "&p4_fir_no[]="+$("#p4_fir_no_"+pet_act_id).val();	
			param += "&p4_fir_csr_dist[]="+$("#p4_fir_dist_"+pet_act_id).val();	
			param += "&p4_fir_circle[]="+$("#p4_fir_circle_"+pet_act_id).val();	
			param += "&p4_fir_year[]="+$("#p4_fir_year_"+pet_act_id).val();
			j++;
		}
	 
	}
	if(status){
		$.ajax({
			type: "POST",
			dataType: "xml",
			url: "t5_ProcessTempReplyAction.php",
			data: param,  
			
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){
				// we have the response 			
				
				var status = $(xml).find('status').eq(0).text();
				
				if(status=='S'){
					alert($(xml).find('tot').eq(0).text()+"\n"+
						$(xml).find('f').eq(0).text()+"\n"+
						$(xml).find('n').eq(0).text()+"\n"+
						$(xml).find('c').eq(0).text()+"\n"+
						$(xml).find('e').eq(0).text()+"\n"+
						$(xml).find('t').eq(0).text()+"\n"+
						$(xml).find('a').eq(0).text()+"\n"+
						$(xml).find('r').eq(0).text()+"\n"+
						$(xml).find('ir').eq(0).text()+"\n"+
						$(xml).find('s').eq(0).text()+"\n"+
						"processed successfully\n\n"+
						$(xml).find('fc').eq(0).text()+
						"  (If any, might already have been processed.)");
					p4_loadGrid(1, $('#p4_pageSize').val());
					$("#p4_Save").attr("disabled", false);
					document.getElementById("p4_Save").value="Save";
				} else {
					alert($(xml).find('fc').eq(0).text() + $(xml).find('msg').eq(0).text()+ "  (If any, might already have been processed.)");
					p4_loadGrid(1, $('#p4_pageSize').val());
					$("#p4_Save").attr("disabled", false);
					document.getElementById("p4_Save").value="Save";
				}
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			} 
		});//ajax end
	}
	else{
		alert("Please fill atleast one petition to Current Action & Addressed to !!!");
		$("#p4_Save").attr("disabled", false);
		document.getElementById("p4_Save").value="Save";
		return false;	 
	}
}


function t5_load_fir_csr(pet_action_id) {
	act=$('#p4_act_type_code_'+pet_action_id).val();
	if(act=='I'){
		act=1;
	}else if(act=='S'){
		act=2;
	}
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'load_fir_csr_ext',pet_action_id11:pet_action_id,act_id:act},
		error:function(){ alert("") },
		success: function(html){
			x=html.split(',');
			 document.getElementById("p4_fir_dist_"+pet_action_id).value=x[1];
			if(x[1]!=''){
				load_ext_pst5(pet_action_id);
			}else{document.getElementById("p4_fir_circle_"+pet_action_id).innerHTML="<option>--Select District--</option>";}
			if(x[3]!=''){
			$('#p4_fir_year_'+pet_action_id).val(x[3]);
			}else{
				document.getElementById("p4_fir_year_"+pet_action_id).value="";
				document.getElementById("p4_fir_year_"+pet_action_id).Placeholder="FIR/CSR Year";
				}
			if(x[4]!=''){
				$('#p4_fir_no_'+pet_action_id).val(x[4]);
			}else{
				document.getElementById("p4_fir_no_"+pet_action_id).value="";
				document.getElementById("p4_fir_no_"+pet_action_id).Placeholder="FIR/CSR Number";
				}if(x[2]!=''){
			 setTimeout(function() {document.getElementById("p4_fir_circle_"+pet_action_id).value=x[2];
			 }, 500);
			}else{document.getElementById("p4_fir_circle_"+pet_action_id).innerHTML="<option>--Select Police Station--</option>";}
		}
	});	
	
}
function load_ext_pst5(pet_action_id) {
	district=$('#p4_fir_dist_'+pet_action_id).val();
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'load_police_station',district:district},
		error:function(){ alert("") },
		success: function(html){
			document.getElementById("p4_fir_circle_"+pet_action_id).innerHTML=html;			
		}
	});	
	
}



function t5_load_ext_dist(pet_action_id) {
	ef_off=$('#dept_user_id').val();
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'load_ext_dist',ef_off:ef_off},
		error:function(){ alert("") },
		success: function(html){
			document.getElementById("p4_fir_dist_"+pet_action_id).innerHTML=html;			
		}
	});	
	
}

function validatedate1(inputText,pet_act_id){     
     var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/; 
	 
	  
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
  document.getElementById("p4_file_date_"+pet_act_id).value=""; 
  return false;  
  }  
}
/////////////////////////////

 function openPetitionStatusReport4(petition_id){	
	document.getElementById("petition_id4").value=petition_id;
	document.petition_process4.target = "Map";
	document.petition_process4.method="post";  
	document.petition_process4.action = "p_PetitionProcessDetails.php";
	
	map = window.open("", "Map", "status=0,title=0,fullscreen=yes,scrollbars=1,resizable=0");
	if(map){
		document.petition_process4.submit();
	}  
}
 

</script>
<form method="post" name="petition_process4" id="petition_process4">
<table class="searchTbl" style="border-top: 1px solid #000000;">
	<tbody>
	
	<tr>
	 <th style="width:20%;"><?PHP echo $label_name[19];//Petition Period?></th>
	  <th style="width:20%;"><?PHP echo $label_name[22];//Petition No.?></th>
	  <th style="width:20%;"><?PHP echo $label_name[35];//Petition Type?></th>
	  <th style="width:20%;"><?PHP echo $label_name[23];//Source?></th>
	 <!-- <th style="width:12%;"><?PHP //echo $label_name[34];//Department?></th> -->
	  <th style="width:20%;"><?PHP echo $label_name[33];//Petition Main Category?></th>
	 
	</tr>
	
	<tr>
		<td class="from_to_dt" style="width:20%;">
        	<?PHP echo $label_name[20];//From?>&nbsp;
            <input type="text" name="p4_from_pet_date" id="p4_from_pet_date" 
            style="width: 90px;" maxlength="12" onchange="return validatedate(p4_from_pet_date,'p4_from_pet_date'); "/>
        	&nbsp;<?PHP echo $label_name[21];//To?>&nbsp;
            <input type="text" name="p4_to_pet_date" id="p4_to_pet_date" style="width: 90px;" maxlength="12" 
            onchange="return validatedate(p4_to_pet_date,'p4_to_pet_date'); "/>
        </td>
		<td style="width:20%;"><input type="text" name="p4_petition_no" id="p4_petition_no" onKeyPress="return checkPetNo(event);" maxlength="25" style="width:280px;"/></td>
		
		<td style="width:10%;">
		<select name="p4_petition_type" id="p4_petition_type" style="width:280px;">
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
		
        <td style="width:20%;">
        	<select name="p4_source" id="p4_source" style="width:280px;">
            	<option value="">-- Select Source --</option>
                <?PHP 
					$query="SELECT source_id, source_name,source_tname FROM lkp_pet_source WHERE enabling ORDER BY source_name";
					
					$result = $db->query($query);
					$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray as $row){
						//echo "<option value='$row[source_id]'>$row[source_name]</option>";
						if($_SESSION["lang"]=='E'){
						echo "<option value='".$row['source_id']."'>".$row['source_name']."</option>";
						}else{
						echo "<option value='".$row['source_id']."'>".$row['source_tname']."</option>";	
						}
					}
				?>
            </select>
        </td>
		<!--
		<td style="width:12%;">
          <select name="p4_dept" id="p4_dept" style="width:240px;">
          <option value="">-- Select Department --</option>
          <?PHP 
         /*  $dept_sql = "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
						FROM usr_dept where dept_id>0 ORDER BY dept_name";
			if ($userProfile->getOff_level_id() == 1 || $userProfile->getOff_level_id() == 3 || $userProfile->getOff_level_id() == 4) { 					
					$dept_sql ="SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
					FROM usr_dept WHERE dept_id=".$userProfile->getDept_id()." ORDER BY dept_name";
			 } else //if($userProfile->getDept_coordinating()&& $userProfile->getOff_coordinating()) 
			 {
					//01/11/2017  Registration Dept Officials to be moved from Miscellaneous Dept to IGR Dept(ID = 12) and 
					//the condition 'and dept_id<12' is to be deleted later
					$dept_sql = "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
					FROM usr_dept where dept_id>0 and dept_id<12 ORDER BY dept_name";	
					$dept_sql = "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
					FROM usr_dept where dept_id>0 ORDER BY dept_name";					
			 } */ /*else  {
					$dept_sql = "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
					FROM usr_dept WHERE dept_id=".$userProfile->getDept_id()." ORDER BY dept_name";
			 }*/				
				/* $res = $db->query($dept_sql);
				$row_arr = $res->fetchall(PDO::FETCH_ASSOC);
				foreach($row_arr as $row) {
					$dept_name=$row[dept_name];
					echo "<option value='".$row[dept_id]."'>$dept_name</option>";	
				} */
          ?>
          </select>
      </td>
	  -->
	  <td style="width:20%;">
          <select name="p4_gtype" id="p4_gtype" style="width:280px;">
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
        	<input type="button" name="p4_search" id="p4_search" value="<?PHP echo $label_name[24];//Search?>" class="button"/>
        	<input type="button" name="p4_search" id="p4_clear" value="<?PHP echo $label_name[25];//Clear?>" class="button"/>
        </td>
	</tr>
	</tbody>
</table>
<table class="existRecTbl">
	<thead>

	<tr>
		<th><?PHP echo $label_name[7];//Existing Details?></th>
		<th><?PHP echo $label_name[8];//Page&nbsp;Size?><select name="p4_pageSize" id="p4_pageSize" class="pageSize">		
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
	<th style="width: 15%;"><?PHP echo $label_name[11]. ', '.$label_name[35].' and '.$label_name[9];//Source, Petition No. & Date and Petition Type?></th>        	
<th style="width: 15%;"><?PHP echo $label_name[10];//Petitioner's Address and Mobile?></th>             
	<th style="width: 15%;"><?PHP echo $label_name[27].' and '.$label_name[12];//Grievance?></th>
	<th style="width: 18%;"><?PHP echo $label_name[28];//Last Action Taken, Date & Remarks?></th>
	<th style="width: 23%;"><?PHP echo $label_name[15].' and ';//Current Action?><span id="p5_TH_reply"><?PHP echo $label_name[16];//Addressed To?></span></th>
	<th style="width: 13%;"> <?PHP echo $label_name[29].' and '.$label_name[17]; //File No. & File Date ?></th>
		</tr>
	</thead>
	<tbody id="p4_dataGrid"></tbody>
</table>
<div id="t5_loadmessage" div align="center" style="display:none"><img src="images/wait.gif" width="100" height="90" alt=""/></div>

<table class="paginationTbl">
	<tbody>
		<tr id="p4_pageFooter1" style="display: none;">
			<td id="p4_previous"></td>
			<td>Page<select id="p4_pageNoList" name="p4_pageNoList" class="pageNoList"></select><span id="p4_noOfPageSpan"></span></td>
			<td id="p4_next"></td>
		</tr>
		<tr id="p4_pageFooter2" style="display: none;"><td colspan="3" class="emptyTR"></td>
		</tr>
        <tr>
        	<td colspan="3" class="emptyTR">
            	<input type="button" class="button" value="<?PHP echo $label_name[26];//Save?>" id="p4_Save" name="p4_Save">
             <?php
            $ptoken = md5(session_id() . $_SESSION['salt']);
            $_SESSION['formptoken']=$ptoken;
            ?>
            <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" />
            <input type="hidden" name="petition_id4" id="petition_id4" />
            </td>
		</tr>
	</tbody>
</table>
</form>

