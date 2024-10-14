<script type="text/javascript" charset="utf-8">

$(document).ready(function()
{
	setDatePicker('p5_from_pet_date');
	setDatePicker('p5_to_pet_date');
	
	$("#p5_Save").click(function(){
		p5_PetitionProcess();
	});
	
	$('#p5_pageNoList').change(function(){
		p5_loadGrid($('#p5_pageNoList').val(), $('#p5_pageSize').val());
	});
	
	$('#p5_pageSize').change(function(){
		p5_loadGrid(1, $('#p5_pageSize').val());
	});
	
	$("#p5_search").click(function(){
		$('#p5_dataGrid').empty();
		//document.getElementById("t4_loadmessage").style.display='';
		p5_loadGrid(1, $('#p5_pageSize').val());
	});
	
	$("#p5_clear").click(function(){
		p5_clearSerachParams();
	});
	
	if ($('#desig_role').val() == 5) {
		//document.getElementById("p5_Save").disabled = true;
	} else {
		document.getElementById("p5_Save").disabled = false;
	}
	//p5_loadGrid(1, $('#p5_pageSize').val()); search_off
});
function populateReplyGivenOfficer() {
	var dept_id = $('#p5_dept').val();
	if (dept_id == '') {
		alert("Please select a Department");
		//return false;
	} else {
		openForm("Search_officer_Form.php?open_form=P1&dept_id="+dept_id, "office_design_search");
	}
}
function p5_searchParams(){
	var param="&p_from_pet_date="+$('#p5_from_pet_date').val();
	param+="&p_to_pet_date="+$('#p5_to_pet_date').val();
	param+="&p_petition_no="+$('#p5_petition_no').val();
	param+="&p_source="+$('#p5_source').val(); 
	/* param+="&dept="+$('#p5_dept').val();  */
	param+="&gtype="+$('#p5_gtype').val(); 
	param+="&ptype="+$('#p5_petition_type').val(); 
	/* param+="&conc_off="+$('#conc_off_id').val();  */
	param+="&form_tocken="+$('#formptoken').val();
	return param;
}

function p5_clearSerachParams(){
	$('#p5_from_pet_date').val('');
	$('#p5_to_pet_date').val('');
	$('#p5_petition_no').val('');
	$('#p5_source').val('');
	/* $('#p5_dept').val(''); */
	$('#p5_gtype').val('');
	$('#p5_petition_type').val('');
	/* $('#conc_off').val('');
	$('#conc_off_id').val(''); */
	$('#p5_dataGrid').empty();
	p5_loadGrid(1, $('#p5_pageSize').val());
}
function p5_loadGrid(pageNo, pageSize){
	document.getElementById("t4_loadmessage").style.display='';
	var param = "mode=p5_search"
		+"&page_size="+pageSize
		+"&page_no="+pageNo
		+p5_searchParams();

	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "t4_ProcessDisposalAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			// we have the response 
			 p5_createGrid(xml);
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
function p5_createGrid(xml){
	//alert(xml);
	$('#p5_dataGrid').empty();
	document.getElementById("t4_loadmessage").style.display='none';
	var desig_role=document.getElementById("desig_role").value;
	setPetitionCount("p5_count", $(xml).find('count').eq(0).text());
	$(xml).find('pet_action_id').each(function(i)
	{
		var pet_action_id = $(xml).find('pet_action_id').eq(i).text();
		var petition_id = $(xml).find('petition_id').eq(i).text();
		var action_entby = $(xml).find('action_entby').eq(i).text();
		var action_type_name=$(xml).find('action_type_name').eq(i).text();
		var griev_subtype_id = $(xml).find('griev_sub_type_id').eq(i).text();
		var remark = '';
		var remarks = $(xml).find('fwd_remarks').eq(i).text();
		var link_stat = $(xml).find('link_stat').eq(i).text();
		if(link_stat>0){
			var link_msg="<b><em>"+link_stat+' petition(s) clubbed with this petition is/are already closed please take appropriate action to close this petition.</em></b><br><br>';
		}else{
			var link_msg='';
		}
		var first_action_remarks = $(xml).find('first_action_remarks').eq(i).text();
		var gri_address = $(xml).find('gri_address').eq(i).text();
		if (gri_address == null || gri_address == '') {
			gri_address = '-';
		}
		var petition_no = $(xml).find('petition_no').eq(i).text();
		var comm_mobile = $(xml).find('comm_mobile').eq(i).text();
		var comm_email = $(xml).find('comm_email').eq(i).text();
		var source_id = $(xml).find('source_id').eq(i).text();
		var lnk_docs = $(xml).find('lnk_docs').eq(i).text();
		//alert("lnk_docs:::::"+lnk_docs);
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
		if (first_action_remarks != '') {
			first_action_label = '<br><br><b>First action and initial instruction: </b>'+first_action_remarks;
		} else {
			first_action_label = '';
		}
		
		if (lnk_docs != '') {
			lnk_docs_label = '<br><br><b>Petition linked with: </b>'+lnk_docs;
			lnk_docs_label = '';
		} else {
			lnk_docs_label = '';
		}
		//alert("lnk_docs:::::"+lnk_docs_label);
		var msgid='popup_'+petition_id;
		/**Petition action taken from review from TOP level user, if action type code is N then that petition to be rejected or forwarded to another office. 
		Otherwise, shown action types A, R or Q*/
		
		var actTypeCodeOption= "<option value=''>-- Select Action Type --</option>";
		
		$(xml).find('acttype_code_'+petition_id).each(function(i)
		{
			actTypeCodeOption += "<option value='"+$(xml).find('acttype_code_'+petition_id).eq(i).text()+"'>"+$(xml).find('acttype_desc_'+petition_id).eq(i).text()+"</option>";
		});
		
		$('#p5_dataGrid')
		.append("<tr>"+
		"<td>"+$(xml).find('rownum').eq(i).text()+"</id>"+
		"<td><b>Source:</b> "+$(xml).find('source_name').eq(i).text()+"<br>"+
		"<b>Petition Type: </b>"+$(xml).find('pet_type_name').eq(i).text()+
		"<br><br><b>Petition No. & Date:</b><br>"+
			"<input type='hidden' name='p5_pet_action_id' id='"+pet_action_id+"' value='"+pet_action_id+"'/>"+
			"<input type='hidden' name='p5_pet_id' id='p5_pet_id_"+pet_action_id+"' value='"+petition_id+"'/>"+
			
	"<input type='hidden' name='p5_pet_no' id='p5_pet_no_"+pet_action_id+"' value='"+petition_no+"'/>"+
	"<input type='hidden' name='p5_comm_mobile' id='p5_comm_mobile_"+pet_action_id+"' value='"+comm_mobile+"'/>"+
	"<input type='hidden' name='p5_comm_email' id='p5_comm_email_"+pet_action_id+"' value='"+comm_email+"'/>"+
	"<input type='hidden' name='p5_source_id' id='p5_source_id_"+pet_action_id+"' value='"+source_id+"'/>"+			
			
	"<input type='hidden' name='p5_griev_type_id' id='p5_griev_type_id_"+pet_action_id+"' value='"+$(xml).find('griev_type_id').eq(i).text()+"'/>"+	
	"<input type='hidden' name='p5_griev_subtype_id' id='p5_griev_subtype_id_"+pet_action_id+"' value='"+$(xml).find('griev_subtype_id').eq(i).text()+"'/>"+
	"<input type='hidden' name='p5_dept_id' id='p5_dept_id_"+pet_action_id+"' value='"+$(xml).find('dept_id').eq(i).text()+"'/>"+
	"<input type='hidden' name='p5_griev_district_id' id='p5_griev_district_id_"+pet_action_id+"' value='"+$(xml).find('griev_district_id').eq(i).text()+"'/>"+
	"<input type='hidden' name='p5_off_loc_id' id='p5_off_loc_id_"+pet_action_id+"' value='"+$(xml).find('off_loc_id').eq(i).text()+"'/>"+
	"<input type='hidden' name='p5_petition_id' id='p5_petition_id_"+pet_action_id+"' value='"+petition_id+"'/>"+
	"<input type='hidden' name='p5_remark_old' id='p5_remark_old_"+pet_action_id+"' value='"+$(xml).find('fwd_remarks').eq(i).text()+"'/>"+
	"<input type='hidden' name='p5_action_taken_by' id='p5_action_taken_by_"+pet_action_id+"' value='"+$(xml).find('action_entby').eq(i).text()+"'/>"+
	
	"<input type='hidden' name='p5_pet_loc_id' id='p5_pet_loc_id_"+pet_action_id+"' value='"+$(xml).find('pet_loc_id').eq(i).text()+"'/>"+
	"<input type='hidden' name='p5_off_level_id' id='p5_off_level_id_"+pet_action_id+"' value='"+$(xml).find('off_level_id').eq(i).text()+"'/>"+
	"<input type='hidden' name='p5_dept_off_level_pattern_id' id='p5_dept_off_level_pattern_id_"+pet_action_id+"' value='"+$(xml).find('dept_off_level_pattern_id').eq(i).text()+"'/>"+
	"<input type='hidden' name='p5_off_level_dept_id' id='p5_off_level_dept_id_"+pet_action_id+"' value='"+$(xml).find('off_level_dept_id').eq(i).text()+"'/>"+
			
	"<a href='javascript:openPetitionStatusReport5("+petition_id+");' title='Petition Process Report'>"+
		$(xml).find('petition_no').eq(i).text()+"<br>Dt.&nbsp;"+ $(xml).find('petition_date').eq(i).text()+
	"</a>"+lnk_docs_label+"</td>"+
		"<td>"+$(xml).find('pet_address').eq(i).text()+"<br><br><b>Mobile :</b>"+$(xml).find('comm_mobile').eq(i).text()+"</td>"+	
		"<td><b>Petition Category and Sub Category :</b>"+$(xml).find('griev_type_name').eq(i).text()+", "+$(xml).find('griev_subtype_name').eq(i).text()+"<br><b>Office: </b>"+gri_address+"<br>"+
		"<b>Petition:</b>"+$(xml).find('grievance').eq(i).text()+"</td>"+		
		"<td style='text-align: left;'><b>Last action: </b>"+action_type_name+"\n" +" by "+$(xml).find('off_location_design').eq(i).text()+" on "+$(xml).find('fwd_date').eq(i).text()+" "
		
		+remark+"&nbsp;&nbsp;&nbsp;<a onclick='showMessage("+petition_id+")' style='cursor: pointer;' >"+linkforpopup+"</a>"+
		first_action_label+
		"<div class='popup'>"+"<span class='popuptext' name='popup' id='popup_"+petition_id+"' onclick='hidePopup("+petition_id+")'>"+remarks+"</span></div>"+
		
		"</td>"		
		+"<td>"+link_msg+
		"<select name='p5_act_type_code' id='p5_act_type_code_"+pet_action_id+"' style='width: 160px;' onchange='p5_action_type_code("+pet_action_id+","+action_entby+","+petition_id+");'>"+actTypeCodeOption+"</select>"+
		" "+
		"<br><br><br><span name='p5_fwd_reply' id='p5_fwd_reply_"+petition_id+"'></span>"+
		"<input type='hidden' name='p1_user_sno' id='p1_user_sno_"+petition_id+"'/>"+
		"<input type='text' name='p1_design' id='p1_design_"+petition_id+"' style='display:none'/>"+
		
		"<div name='p1_off_design' id='p1_off_design_"+pet_action_id+"' style='display:none'>"+
		"&nbsp;&nbsp;<a href='javascript:p5_searchOfficeDesignation("+petition_id+","+pet_action_id+","+$(xml).find('off_loc_id').eq(i).text()+");'>"+
			"Get All Officer List</a></div>"+
		"</td>"+
		
		 "<td><input type='text' style='width:170px' name='p5_file_no' id='p5_file_no_"+pet_action_id+"' maxlength='50' onKeyPress='return checkFileNoProcessing(event);' value='"+$(xml).find('file_no').eq(i).text()+"'/>  "+  "\n" +"  <input type='text' style='width:120px' name='p5_file_date' id='p5_file_date_"+pet_action_id+"' maxlength='25' value='"+$(xml).find('file_date').eq(i).text()+"' onchange='return validatedate1(p5_file_date_"+pet_action_id+","+pet_action_id+");' /> "+
		 
		"<br><textarea name='p5_remark' id='p5_remark_"+pet_action_id+"' onKeyPress='return characters_numsonly_grievance(event);'></textarea></td>"+
		"</tr>");
		
		setDatePicker('p5_file_date_'+pet_action_id);
		addDate();
		if (desig_role == 5) {
			//document.getElementById('p5_act_type_code_'+pet_action_id).disabled=true;
			//document.getElementById('p5_file_no_'+pet_action_id).disabled=true;
		//	document.getElementById('p5_file_date_'+pet_action_id).disabled=true;
		//$("#p5_file_date_"+pet_action_id).datepick("disable")
		//	document.getElementById('p5_remark_'+pet_action_id).disabled=true;
		}
		//$('#td_'+petition_id).append("<input type='radio' name='p5_id' onClick='javascript:petition_edit("+petition_id+")'/>");		
	});
	
	var pageNo = $(xml).find('pageNo').eq(0).text();
	var pageSize = $(xml).find('pageSize').eq(0).text();
	var noOfPage = $(xml).find('noOfPage').eq(0).text();
	$("[name=p5_design]").attr('disabled','disabled');
	drawPagination('p5_pageFooter1', 'p5_pageFooter2','p5_pageSize', 'p5_pageNoList', 'p5_next', 'p5_previous', 'p5_noOfPageSpan', 'p5_loadGrid', pageNo, pageSize, noOfPage);
}

function p5_action_type_code(pet_action_id, action_entby,petition_id){
	
	var act_type_code = $("#p5_act_type_code_"+pet_action_id).val();
	dept_id= document.getElementById("p5_dept_id_"+pet_action_id).value;
	off_loc_id= document.getElementById("p5_off_loc_id_"+pet_action_id).value;
	griev_district_id= document.getElementById("p5_griev_district_id_"+pet_action_id).value;
	
	$("#p5_fwd_reply_"+petition_id).empty();
	$("#p5_remark_"+pet_action_id).val(""); 
	
	pet_loc_id= document.getElementById("p5_pet_loc_id_"+pet_action_id).value;
	off_level_id= document.getElementById("p5_off_level_id_"+pet_action_id).value;
	dept_off_level_pattern_id= document.getElementById("p5_dept_off_level_pattern_id_"+pet_action_id).value;
	off_level_dept_id= document.getElementById("p5_off_level_dept_id_"+pet_action_id).value;
	
	if($("#p5_act_type_code_"+pet_action_id).val()== ""){
		return false;	
	}
	
	if(act_type_code=="Q" || act_type_code=="F"){
		$("#p5_remark_"+pet_action_id).val(""); 
		var param = "mode=p5_act_type"
			+"&p5_act_type_code="+act_type_code
			+"&p5_action_entby="+action_entby
			+"&p5_petition_id="+$("#p5_petition_id_"+pet_action_id).val()
			+"&p5_action_taken_by="+$("#p5_action_taken_by_"+pet_action_id).val()
			+"&p5_dept_id="+dept_id
			+"&p5_off_loc_id="+off_loc_id
			+"&p5_griev_district_id="+griev_district_id
			+"&pet_loc_id="+pet_loc_id
			+"&off_level_id="+off_level_id
			+"&dept_off_level_pattern_id="+dept_off_level_pattern_id
			+"&off_level_dept_id="+off_level_dept_id		
			+"&form_tocken="+$('#formptoken').val();
		//load address to details
		p5_address_to(pet_action_id, act_type_code,petition_id, param );
	}
	else{
		//alert("act_type_code::"+act_type_code);
		if (act_type_code == 'A') {
			if ($("#p5_remark_old_"+pet_action_id).val() == '') {
				$("#p5_remark_"+pet_action_id).val('Petition is accepted');
			} else {
				$("#p5_remark_"+pet_action_id).val($("#p5_remark_old_"+pet_action_id).val());
			}
		} else {
			if ($("#p5_remark_old_"+pet_action_id).val() == '') {
				alert("Enter the remarks for rejection");
			} else {
			$("#p5_remark_"+pet_action_id).val($("#p5_remark_old_"+pet_action_id).val());
		}
		 
	}
	}
}

function p5_address_to(pet_action_id, act_type_code,petition_id, param ){	
	$.ajax({
			type: "POST",
			dataType: "xml",
			url: "t4_ProcessDisposalAction.php",  
			data: param,  
			
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){
				// we have the response 
				$("#p5_fwd_reply_"+petition_id).empty();
				
				if(act_type_code=="Q"){
					var selectBox = "<select name='p5_fwd_ur_reply' id='p5_fwd_ur_reply_"+pet_action_id+"'></select>";
					$("#p5_fwd_reply_"+petition_id).append(selectBox);
					populateComboBoxPlain(xml, "p5_fwd_ur_reply_"+pet_action_id, 'dept_user_id', 'off_location_design', $(xml).find('dept_user_id').eq(0).text());
				}
				else if(act_type_code=="F"){
					var selectBox = "<select name='p5_fwd_ur_reply' id='p5_fwd_ur_reply_"+pet_action_id+"'></select>";
					$("#p5_fwd_reply_"+petition_id).append(selectBox);
					var temp = $(xml).find('off_location_design').eq(0).text();
					populateComboBoxOfficer(xml, "p5_fwd_ur_reply_"+pet_action_id, (temp.charCodeAt(0)>=2944 && temp.charCodeAt(0)<=3071) ? 'பணியிடம் / பதவி':"Office Location / Designation", 'dept_user_id', 'off_location_design', "");
				}
			},  
			error: function(e){
				//alert('Error: ' + e.response);  
			}
		});//ajax end
}

function p5_clearOfficeDesign(pet_action_id){
	$('#p5_user_sno_'+pet_action_id).val('');
	$('#p5_off_loc_design_'+pet_action_id).val('');
}
function addDate(){
	var date = new Date();
	var newdate = new Date(date);
	setDateFormat(date, "#p5_file_date");
  
}
//save to processing petition
function p5_PetitionProcess(){
	document.getElementById("p5_Save").value="Wait";
	$("#p5_Save").attr("disabled", true);
	var status=false;
	var param="mode=p5_disposal_save"+"&form_tocken="+$('#formptoken').val();
	
	for(var i=0;i<$("[name='p5_pet_action_id']").size(); i++){
		var pet_act_element_id=$('input[name=p5_pet_action_id]')[i].id;	
		var pet_act_id=$("#"+pet_act_element_id).val(); //***
		var pet_id = $("#p5_pet_id_"+pet_act_id).val(); 
		document.getElementById("p1_off_design_"+pet_act_id).disabled = false;
		var fwd_reply = $("#p1_off_design_"+pet_act_id).val();
		
		   /*if($("#p5_act_type_code_"+pet_act_id).val()=='A' || $("#p5_act_type_code_"+pet_act_id).val()=='P')
			{
				if($("#p5_file_no_"+pet_act_id).val()==""){
					alert("Enter File No. & Date to Action Taken the Petition");
					$("#p5_Save").attr("disabled", false);
					document.getElementById("p5_Save").value="Save";					
					return false;
				}
				if($("#p5_file_date_"+pet_act_id).val()==""){
					//$("#p5_Save").attr("disabled", false);
					alert("Select File Date.");
					$("#p5_Save").attr("disabled", false);
					document.getElementById("p5_Save").value="Save";
					return false;
				}
			}*/
			
			if($("#p5_act_type_code_"+pet_act_id).val()=='A' || $("#p5_act_type_code_"+pet_act_id).val()=='R')
			{
				if($("#p5_remark_"+pet_act_id).val()==""){
					//$("#p5_Save").attr("disabled", false);
					alert("Remarks not entered for accepted/rejected petition(s). Please enter it.");
					$("#p5_Save").attr("disabled", false);
					document.getElementById("p5_Save").value="Save";
					return false;
				}
			} 		
		
		if($("#p5_act_type_code_"+pet_act_id).val()!=""){
			status=true;
			//Action Type Code is Q
			if($("#p5_act_type_code_"+pet_act_id).val()=='Q' || $("#p5_act_type_code_"+pet_act_id).val()=='F'){
				if (+$("#p5_fwd_ur_reply_"+pet_act_id).val() != '') {
					param += "&p5_fwd_ur_reply[]="+$("#p5_fwd_ur_reply_"+pet_act_id).val();
				} else {
					if($("#p1_user_sno_"+pet_id).val()!="") {
						param += "&p5_fwd_ur_reply[]="+$("#p1_user_sno_"+pet_id).val();
					} else {
						alert("Please select Forwarded To/ Reply To Office Location");						
						$("#p5_Save").attr("disabled", false);
						document.getElementById("p5_Save").value="Save";						
						return false;
					}
				}
			}
			else{//Action Type Code is A, R
				param += "&p5_fwd_ur_reply[]=";
			}
			var fdate = $("#p5_file_date_"+pet_act_id).val();
			if (fdate != "") {
				var datearray = fdate.split("/");
				var newdate = datearray[1] + '/' + datearray[0] + '/' + datearray[2];
			} else {
				var newdate = "";	
			}
			
			param += "&p5_pet_act_sno[]="+pet_act_id;
			param += "&p5_pet_sno[]="+$("#p5_petition_id_"+pet_act_id).val();
			param += "&p5_act_type_code[]="+$("#p5_act_type_code_"+pet_act_id).val();
			param += "&p5_file_no[]="+ $("#p5_file_no_"+pet_act_id).val();
			
			param += "&p5_pet_no[]="+ $("#p5_pet_no_"+pet_act_id).val();
			param += "&p5_comm_mobile[]="+ $("#p5_comm_mobile_"+pet_act_id).val();
			
			param += "&p5_file_date[]="+ newdate;
			param += "&p5_remark[]="+$("#p5_remark_"+pet_act_id).val();
			//alert(param);
		}
	}
	if(status){
		
		$.ajax({
			type: "POST",
			dataType: "xml",
			url: "t4_ProcessDisposalAction.php",
			data: param,  
			
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){

				var status = $(xml).find('status').eq(0).text();
				if(status=='S'){
					alert($(xml).find('tot').eq(0).text()+"\n\n"+
							$(xml).find('a').eq(0).text()+"\n"+
							$(xml).find('r').eq(0).text()+"\n"+
							$(xml).find('q').eq(0).text()+"\n"+
							$(xml).find('f').eq(0).text()+"\n"+
							"processed successfully.\n\n"+
							$(xml).find('fc').eq(0).text()+
							"  (If any, might already have been processed.)");
					//reload disposal petition grid
					p5_loadGrid(1, $('#p5_pageSize').val());
					$("#p5_Save").attr("disabled", false);
					document.getElementById("p5_Save").value="Save";	
				}
				else{
					alert($(xml).find('fc').eq(0).text() + $(xml).find('msg').eq(0).text()+ "  (If any, might already have been processed.)");
					p5_loadGrid(1, $('#p5_pageSize').val());
					$("#p5_Save").attr("disabled", false);
					document.getElementById("p5_Save").value="Save";	
				}
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			} 
		});//ajax end
	}
	else{
		if($("#login_lvl").val()=="TOP"){
			//$("#p5_Save").attr("disabled", false);
			alert("Please fill atleast one petition to Further Action Require To/ Petition Accepted/ Petition Rejected Action");
			$("#p5_Save").attr("disabled", false);
			document.getElementById("p5_Save").value="Save";
		}
		else{
			//$("#p5_Save").attr("disabled", false);
			alert("Please fill atleast one petition to Forwarded To/Replied To/Temporary Action");
			$("#p5_Save").attr("disabled", false);
			document.getElementById("p5_Save").value="Save";
		}
		return false;	 
	}
}

function changeDateFormat(fdate) {
	var dateToConvert = Date.parse(fdate);
    convertedDateString = (dateToConvert .toString('mm/dd/yyyy'));
}
function t1_checkForActionType(pet_act_id) {
	var act_type_code = $("#p5_act_type_code_"+pet_act_id).val();
	//alert(act_type_code);
	if (act_type_code == 'F') {
		document.getElementById("p1_off_design_"+pet_act_id).style.display = "block";
	} else {
		document.getElementById("p1_off_design_"+pet_act_id).style.display = "none";
		
	}
}

function p5_searchOfficeDesign(pet_act_id, griev_type_id){
	openForm("p1_OfficeDesignSearchForm.php?open_form=p5&petition_id="+pet_act_id+"&griev_type_id="+griev_type_id, "office_design_search");
}

function p5_searchOfficeDesignation(pet_id,pet_action_id, off_loc_id) {
	var griev_type_id = $('#p5_griev_type_id_'+pet_action_id).val();
	var griev_sub_type_id = $('#p5_griev_subtype_id_'+pet_action_id).val();
	var dept_id = $('#p5_dept_id_'+pet_action_id).val();
	openForm("p1_OfficeDesignSearchForm.php?open_form=P1&petition_id="+pet_id+"&griev_type_id="+griev_type_id+"&griev_sub_type_id="+griev_sub_type_id+"&off_loc_id="+off_loc_id+"&dept_id="+dept_id, "office_design_search");
}
function p5_returnDesignationSearch(pet_act_id, userID, offLoc_designName){
	$('#p5_user_sno_'+pet_act_id).val(userID);
	$('#p5_off_loc_design_'+pet_act_id).val(offLoc_designName);
}

function validatedate1(inputText,pet_act_id){
     //alert(">>>>"+pet_act_id);
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
  document.getElementById("p5_file_date_"+pet_act_id).value=""; 
  return false;  
  }  
}
/////////////////////////////
 function openPetitionStatusReport5(petition_id){
	document.getElementById("petition_id5").value=petition_id;
	document.petition_process5.target = "Map";
	document.petition_process5.method="post";  
	document.petition_process5.action = "p_PetitionProcessDetails.php";
	map = window.open("", "Map", "status=0,title=0,fullscreen=yes,scrollbars=1,resizable=0");
	if(map){
		document.petition_process5.submit();
	}  
}
 

function p1_returnDesignationSearch(petition_id, userID, offLoc_designName){
	$('#p1_user_sno_'+petition_id).val(userID);
	$('#p1_design_'+petition_id).val(offLoc_designName);
	document.getElementById('p1_design_'+petition_id).style.display = "block";
	document.getElementById('p5_fwd_reply_'+petition_id).style.display = "none";
	document.getElementById('p1_design_'+petition_id).disabled = true;
}

function p1_returnOfficerSearch(userID, offLoc_designName){
	$('#conc_off_id').val(userID);
	$('#conc_off').val(offLoc_designName);
	document.getElementById('conc_off').style.readOnly = true; 
	}

</script>
<form method="post" name="petition_process5" id="petition_process5">

<table class="searchTbl" style="border-top: 1px solid #000000;">
      <tbody>
	  <tr>
	  <th style="width:20%;"><?PHP echo $label_name[19];//Petition Period?></th>
	  <th style="width:20%;"><?PHP echo $label_name[22];//Petition No.?></th>
	  <th style="width:20%;"><?PHP echo $label_name[35];//Petition Type?></th>
	  <th style="width:20%;"><?PHP echo $label_name[23];//Source?></th>
	  <th style="width:20%;"><?PHP echo $label_name[33];//Petition Main Category?></th>
	  <!--<th style="width:10%;"><?PHP echo $label_name[34];//Department?></th>
	  <th style="width:20%;"><a href="javascript:populateReplyGivenOfficer();"><?PHP //echo 'Concerned Officer';//Department?></a></th>-->
	 
	 
	  </tr>
	  <tr>

	  </tr>
      <tr>
     <td class="from_to_dt" style="width:20%;">
        	<?PHP echo $label_name[20];//From?>&nbsp;<input type="text" name="p5_from_pet_date" id="p5_from_pet_date" maxlength="12" 
            style="width: 90px;" onchange="return validatedate(p5_from_pet_date,'p5_from_pet_date'); "/>
        	&nbsp;<?PHP echo $label_name[21];//To?>&nbsp;<input type="text" name="p5_to_pet_date" id="p5_to_pet_date" maxlength="12" 
            style="width: 90px;" onchange="return validatedate(p5_from_pet_date,'p5_from_pet_date'); "/>
        </td>
      <td style="width:12%;"><input type="text" name="p5_petition_no" id="p5_petition_no" onKeyPress="return checkPetNo(event);" maxlength="25" style="width:280px;"/></td>
	  
	  <td style="width:20%;">
		<select name="p5_petition_type" id="p5_petition_type" style="width:280px;">
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
        	<select name="p5_source" id="p5_source" style="width:280px;">
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
      <td style="width:20%;">
          <select name="p5_gtype" id="p5_gtype" style="width:280px;">
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
	 <!-- 
	  <td style="width:12%;">
          <select name="p5_dept" id="p5_dept" style="width:240px;">
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
			 } /*else  {
					$dept_sql = "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
					FROM usr_dept WHERE dept_id=".$userProfile->getDept_id()." ORDER BY dept_name";
			 }*/				
				/*$res = $db->query($dept_sql);
				$row_arr = $res->fetchall(PDO::FETCH_ASSOC);
				foreach($row_arr as $row) {
					$dept_name=$row[dept_name];
					echo "<option value='".$row[dept_id]."'>$dept_name</option>";	
				} */
          ?>
          </select>
      </td>
	  -->
<!--
		<td style="width:6%;">
		<input type="text" name="conc_off" id="conc_off" maxlength="10" style="width: 200px;"/>&nbsp;&nbsp;
		<input type="hidden" name="conc_off_id" id="conc_off_id"/>
		</td>
-->      

	  
	  
      </tr>
	  <tr>
	  	  <td colspan="7">
          <input type="button" name="p5_search" id="p5_search" value="<?PHP echo $label_name[24];//Search?>" class="button"/>
          <input type="button" name="p5_search" id="p5_clear" value="<?PHP echo $label_name[25];//Clear?>" class="button"/>
		</tr>  
      </td>
      </tbody>
      </table>
	  


<table class="existRecTbl">
	<thead>

	<tr>
		<th><?PHP echo $label_name[7];//Existing Details?></th>
		<th><?PHP echo $label_name[8];//Page&nbsp;Size?><select name="p5_pageSize" id="p5_pageSize" class="pageSize">
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
		<th style="width: 15%;"><?PHP echo $label_name[11]. ', '.$label_name[35].' and '.$label_name[9];//Source,Petition Type and Petition No. & Date?></th>        	
		<th style="width: 15%;"><?PHP echo $label_name[10];//Petitioner's Address and Mobile?></th>            
		<th style="width: 15%;"><?PHP echo $label_name[27].' and '.$label_name[12];//Grievance?></th>
		<th style="width: 18%;"><?PHP echo $label_name[28];//Last Action Taken, Date & Remarks?></th>
		<th style="width: 23%;"><?PHP echo $label_name[15].' and ';//Current Action?><span id="p5_TH_reply"><?PHP echo $label_name[16];//Addressed To?></span></th>
		<th style="width: 13%;"> <?PHP echo $label_name[29].' and '.$label_name[17]; //File No. & File Date ?></th>
		</tr>
	</thead>
	

	<tbody id="p5_dataGrid"></tbody>
</table>
<div id="t4_loadmessage" div align="center" style="display:none"><img src="images/wait.gif" width="100" height="90" alt=""/></div>
<table class="paginationTbl">
	<tbody>
		<tr id="p5_pageFooter1" style="display: none;">
			<td id="p5_previous"></td>
			<td>Page<select id="p5_pageNoList" name="p5_pageNoList" class="pageNoList"></select><span id="p5_noOfPageSpan"></span></td>
			<td id="p5_next"></td>
		</tr>
		<tr id="p5_pageFooter2" style="display: none;"><td colspan="3" class="emptyTR"></td>
		</tr>
        <tr>
        	<td colspan="3" class="emptyTR">
            	<input type="button" class="button" value="<?PHP echo $label_name[26];//Save?>" id="p5_Save" name="p5_Save">
            <?php
            $ptoken = md5(session_id() . $_SESSION['salt']);
            $_SESSION['formptoken']=$ptoken;
            ?>
            <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" />
            <input type="hidden" name="petition_id5" id="petition_id5" />
            </td>
		</tr>
	</tbody>
</table>
</form>

