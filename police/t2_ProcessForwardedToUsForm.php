<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	setDatePicker('p2_from_pet_date');
	setDatePicker('p2_to_pet_date');
		
	$("#p2_Save").click(function(){
		p2_PetitionProcess();
	});
	
	$("#p2_search").click(function(){
		$('#p2_dataGrid').empty();
		p2_loadGrid(1, $('#p2_pageSize').val());
	});
	
	$('#p2_pageNoList').change(function(){
		p2_loadGrid($('#p2_pageNoList').val(), $('#p2_pageSize').val());
	});
	
	$('#p2_pageSize').change(function(){
		p2_loadGrid(1, $('#p2_pageSize').val());
	});
	
	$("#p2_clear").click(function(){
		p2_clearSerachParams();
	});
	/* if ($('#desig_role').val() == 5) {
		document.getElementById("p2_Save").disabled = true;
	} else {
		document.getElementById("p2_Save").disabled = false;
	} */
	//alert("===="+$('#desig_role').val());
	
});

function p2_searchParams(){
	var param="&p_from_pet_date="+$('#p2_from_pet_date').val();
	param+="&p_to_pet_date="+$('#p2_to_pet_date').val();
	param+="&p_petition_no="+$('#p2_petition_no').val();
	param+="&p_source="+$('#p2_source').val(); 
	/* param+="&dept="+$('#p2_dept').val(); */ 
	param+="&gtype="+$('#p2_gtype').val(); 
	param+="&petition_type="+$('#p2_petition_type').val(); 
	param+="&form_tocken="+$('#formptoken').val();
	return param;
}

function p2_clearSerachParams(){
	$('#p2_from_pet_date').val('');
	$('#p2_to_pet_date').val('');
	$('#p2_petition_no').val('');
	$('#p2_source').val('');
	/* $('#p2_dept').val(''); */
	$('#p2_gtype').val('');
	$('#p2_petition_type').val('');
	$('#p2_dataGrid').empty();
	p2_loadGrid(1, $('#p2_pageSize').val());
}

function p2_loadGrid(pageNo, pageSize){
	document.getElementById("t2_loadmessage").style.display='';
	var param = "mode=p2_search"
		+"&page_size="+pageSize
		+"&page_no="+pageNo
		+p2_searchParams();

	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "t2_ProcessForwardedToUsAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			// we have the response 
			 p2_createGrid(xml);
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


function p2_createGrid(xml){
	$('#p2_dataGrid').empty();
	var desig_role=document.getElementById("desig_role").value;
	document.getElementById("t2_loadmessage").style.display='none';

	setPetitionCount("p2_count", $(xml).find('count').eq(0).text());
	var actTypeCodeOption= "<option value=''>-- Select Action Type --</option>";
	$(xml).find('acttype_code').each(function(i)
	{
		actTypeCodeOption += "<option value='"+$(xml).find('acttype_code').eq(i).text()+"'>"+$(xml).find('acttype_desc').eq(i).text()+"</option>";
	});
	k = 0;
	$(xml).find('pet_action_id').each(function(i)
	{
		var remark = '';
		var pet_action_id = $(xml).find('pet_action_id').eq(i).text();
		var petition_id = $(xml).find('petition_id').eq(i).text();
		var action_entby = $(xml).find('action_entby').eq(i).text();
		var action_type_name=$(xml).find('action_type_name').eq(i).text();
		var dept_id=$(xml).find('dept_id').eq(i).text();
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
		//alert("first_action_remarks::::"+first_action_remarks)
		if (remarks != '')  {
			if (remarks.length > 100) {
				remark = "<br><b>Remarks: </b>" + remarks.substr(0,100);
				linkforpopup = ' more..';
			} else {
				remark = "<br><b>Remarks: </b>"  + remarks;
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
		
		$('#p2_dataGrid')
		.append("<tr>"+
		"<td>"+$(xml).find('rownum').eq(i).text()+"</id>"+
		"<td><b>Source: </b>"+$(xml).find('source_name').eq(i).text()+"<br><b>Petition Type: </b>"+
		$(xml).find('pet_type_name').eq(i).text()+"<br><br>"+
		"<b>Petition No. & Date:</b><br>"+
			"<input type='hidden' name='p2_pet_action_id' id='"+pet_action_id+"' value='"+pet_action_id+"'/>"+
			"<input type='hidden' name='p2_petition_id' id='p2_petition_id_"+pet_action_id+"' value='"+petition_id+"'/>"+
			"<input type='hidden' name='p2_griev_type_id' id='p2_griev_type_id_"+pet_action_id+"' value='"+$(xml).find('griev_type_id').eq(i).text()+"'/>"+	
			"<input type='hidden' name='p2_griev_subtype_id' id='p2_griev_subtype_id_"+pet_action_id+"' value='"+$(xml).find('griev_subtype_id').eq(i).text()+"'/>"+
			"<input type='hidden' name='p2_dept_id' id='p2_dept_id_"+pet_action_id+"' value='"+$(xml).find('dept_id').eq(i).text()+"'/>"+
			"<input type='hidden' name='p2_griev_district_id' id='p2_griev_district_id_"+pet_action_id+"' value='"+$(xml).find('griev_district_id').eq(i).text()+"'/>"+
			"<input type='hidden' name='p2_off_loc_id' id='p2_off_loc_id_"+pet_action_id+"' value='"+$(xml).find('off_loc_id').eq(i).text()+"'/>"+
			"<input type='hidden' name='p2_pet_id' id='p2_pet_id_"+pet_action_id+"' value='"+petition_id+"'/>"+
			"<input type='hidden' name='p2_remark_old' id='p5_remark_old_"+pet_action_id+"' value='"+$(xml).find('fwd_remarks').eq(i).text()+"'/>"+
			
			"<input type='hidden' name='p2_pet_loc_id' id='p2_pet_loc_id_"+pet_action_id+"' value='"+$(xml).find('pet_loc_id').eq(i).text()+"'/>"+
			"<input type='hidden' name='p2_off_level_id' id='p2_off_level_id_"+pet_action_id+"' value='"+$(xml).find('off_level_id').eq(i).text()+"'/>"+
			"<input type='hidden' name='p2_dept_off_level_pattern_id' id='p2_dept_off_level_pattern_id_"+pet_action_id+"' value='"+$(xml).find('dept_off_level_pattern_id').eq(i).text()+"'/>"+
			"<input type='hidden' name='p2_off_level_dept_id' id='p2_off_level_dept_id_"+pet_action_id+"' value='"+$(xml).find('off_level_dept_id').eq(i).text()+"'/>"+
			
			"<input type='hidden' name='p2_action_taken_by' id='p2_action_taken_by_"+pet_action_id+"' value='"+$(xml).find('action_entby').eq(i).text()+"'/>"+
			
			"<a href='javascript:openPetitionStatusReport2("+petition_id+");' title='Petition Process Report'>"+
			$(xml).find('petition_no').eq(i).text()+"<br>Dt.&nbsp;"+ $(xml).find('petition_date').eq(i).text()+
			"</a>"+
		"</td>"+
		"<td>"+$(xml).find('pet_address').eq(i).text()+"<br><br><b>Mobile :</b>"+$(xml).find('comm_mobile').eq(i).text()+"</td>"+	
		"<td><b>Petition Category and Sub Category: </b>"+$(xml).find('griev_type_name').eq(i).text()+", "+$(xml).find('griev_subtype_name').eq(i).text()+ ",<br><b>Office: </b>"+gri_address+"<br>"+		
		"<b>Petition:</b>"+$(xml).find('grievance').eq(i).text()+"</td>"+
		
		"<td style='text-align: left;'><b>Last action: </b>"+action_type_name+"\n" +" by "+$(xml).find('off_location_design').eq(i).text()
		+" on "+$(xml).find('fwd_date').eq(i).text()+""
		+remark+"&nbsp;&nbsp;&nbsp;<a onclick='showMessage("+petition_id+")' style='cursor: pointer;' >"+linkforpopup+"</a>"+first_action_label
		
		+"<div class='popup'>"+"<span class='popuptext' name='popup' id='popup_"+petition_id+"' onclick='hidePopup("+petition_id+")'>"+remarks+"</span></div>"+
		"<td>"+link_msg+
		"<select name='p2_act_type_code' id='p2_act_type_code_"+pet_action_id+"' style='width: 160px;' onchange='p2_action_type_code("+pet_action_id+","+action_entby+","+$(xml).find('griev_subtype_id').eq(i).text()+","+$(xml).find('off_loc_id').eq(i).text()+");'>"+actTypeCodeOption+"</select>"+
		" "+
		"<br><br><br><span name='p2_fwd_reply' id='p2_fwd_reply_"+pet_action_id+"'></span>"+
		"<input type='hidden' name='p1_user_sno' id='p1_user_sno_"+petition_id+"'/>"+
		"<input type='text' name='p1_design' id='p1_design_"+petition_id+"' style='display:none'/>"+		
		"<div name='p1_off_design' id='p1_off_design_"+pet_action_id+"' style='display:none'>"+
		"&nbsp;&nbsp;<a href='javascript:p2_searchOfficeDesignation("+petition_id+","+pet_action_id+","+action_entby+","+$(xml).find('off_loc_id').eq(i).text()+");'>"+
			"Get All Officer List</a></div>"+
		"<span id='p2_fir_csr_dist_"+pet_action_id+"' style='display:none;'><p style='vertical-align:middle;text-align:center;display:table-cell;'>__________________________________________________</p><b>FIR/CSR Details:</b><select name='p2_fir_csr_dist' id='p2_fir_dist_"+pet_action_id+"' onchange='load_ext_ps("+pet_action_id+");'><option>--Select District--</option></select><br><br><select name='p2_fir_circle' id='p2_fir_circle_"+pet_action_id+"'><option>--Select Police Station--</option></select><br><br><input name='p2_fir_year' id='p2_fir_year_"+pet_action_id+"' style='width:30%' Placeholder='Year of FIR/CSR' maxlength='4'>&emsp;<input name='p2_fir_no' id='p2_fir_no_"+pet_action_id+"' style='width:60%' Placeholder='FIR/CSR Number' maxlength='150'>&emsp;</span>"+	
		"</td>"+
		
		"<td><input type='text' style='width:170px' name='p2_file_no' id='p2_file_no_"+pet_action_id+"' onKeyPress='return checkFileNoProcessing(event);' maxlength='50' value='"+$(xml).find('file_no').eq(i).text()+"'/>  "+  "\n" +" <input type='text' style='width:120px' name='p2_file_date' id='p2_file_date_"+pet_action_id+"'  maxlength='25' value='"+$(xml).find('file_date').eq(i).text()+"' onchange='return validatedate1(p2_file_date_"+pet_action_id+","+pet_action_id+");' />"+
		
		"<br><textarea name='p2_remark' id='p2_remark_"+pet_action_id+"' onKeyPress='return characters_numsonly_grievance(event);'></textarea></td>"+
		"</tr>");
		
		setDatePicker('p2_file_date_'+pet_action_id);
		addDate();
		if (desig_role == 5) {
			//document.getElementById('p2_act_type_code_'+pet_action_id).disabled=true;
			//document.getElementById('p2_file_no_'+pet_action_id).disabled=true;
			//document.getElementById('p2_file_date_'+pet_action_id).disabled=true;
			//$("#p2_file_date_"+pet_action_id).datepick("disable")
			//document.getElementById('p2_remark_'+pet_action_id).disabled=true;
		}
		//alert($(xml).find('off_level_dept_id').eq(i).text());
		
		//if ($(xml).find('off_level_dept_id').eq(i).text() == $('#h_off_level_dept_id').val()) {
			//alert("Both are same");
			//$("#p2_act_type_code_"+pet_action_id option[value='F']).remove();
			//document.getElementById("p2_act_type_code_"+pet_action_id).remove(1);
		//}
		
	
	}); //$(action_type_name).css("Font-Weight","Bold")
	
	var pageNo = $(xml).find('pageNo').eq(0).text();
	var pageSize = $(xml).find('pageSize').eq(0).text();
	var noOfPage = $(xml).find('noOfPage').eq(0).text();
	$("[name=p2_design]").attr('disabled','disabled');
	drawPagination('p2_pageFooter1', 'p2_pageFooter2','p2_pageSize', 'p2_pageNoList', 'p2_next', 'p2_previous', 'p2_noOfPageSpan', 'p2_loadGrid', pageNo, pageSize, noOfPage);
}


function load_ext_dist(pet_action_id) {
	ef_off=$('#dept_user_id').val();
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'load_ext_dist',ef_off:ef_off},
		error:function(){ alert("") },
		success: function(html){
			document.getElementById("p2_fir_dist_"+pet_action_id).innerHTML=html;			
		}
	});	
	
}

function load_fir_csr(pet_action_id) {
	act_id=$('#p2_act_type_code_'+pet_action_id).val();
	if(act_id=='I'){
		act=1;
	}else if(act_id=='S'){
		act=2;
	}
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'load_fir_csr_ext',act_id:act,pet_action_id11:pet_action_id},
		error:function(){ alert("") },
		success: function(html){
			x=html.split(',');document.getElementById("p2_fir_dist_"+pet_action_id).value=x[1];
			if(x[1]!=''){load_ext_ps(pet_action_id);
			 }else{document.getElementById("p2_fir_circle_"+pet_action_id).innerHTML="<option>--Select District--</option>";}
			if(x[3]!=''){
			$('#p2_fir_year_'+pet_action_id).val(x[3]);
			}else{
				document.getElementById("p2_fir_year_"+pet_action_id).value="";
				document.getElementById("p2_fir_year_"+pet_action_id).Placeholder="FIR/CSR Year";
				}
			if(x[4]!=''){
				$('#p2_fir_no_'+pet_action_id).val(x[4]);
			}else{
				document.getElementById("p2_fir_no_"+pet_action_id).value="";
				document.getElementById("p2_fir_no_"+pet_action_id).Placeholder="FIR/CSR Number";
				}
					if(x[2]!=''){setTimeout(function(){document.getElementById("p2_fir_circle_"+pet_action_id).value=x
				[2];},500);
			
			}else{document.getElementById("p2_fir_circle_"+pet_action_id).innerHTML="<option>--Select Police Station--</option>";
			}
		}
	});	
	
}
function load_ext_ps(pet_action_id) {
	district=$('#p2_fir_dist_'+pet_action_id).val();
	ef_off=$('#dept_user_id').val();
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'load_police_station',district:district,ef_off:ef_off},
		error:function(){ alert("") },
		success: function(html){
			document.getElementById("p2_fir_circle_"+pet_action_id).innerHTML=html;			
		}
	});	
	
}

function p2_checkForActType(pet_act_id) {
	
	
	var act_type_code = $("#p2_act_type_code_"+pet_act_id).val();
	if (act_type_code == 'F') {
		document.getElementById("p1_off_design_"+pet_act_id).style.display = "block";
	} else {
		document.getElementById("p1_off_design_"+pet_act_id).style.display = "none";
		
	}
}
function p2_searchOfficeDesignation(pet_id,pet_action_id,action_entby,off_loc_id) {
	//alert("off_loc_id:::"+off_loc_id);
	var griev_type_id = $('#p2_griev_type_id_'+pet_action_id).val();
	var griev_sub_type_id = $('#p2_griev_subtype_id_'+pet_action_id).val();
	var dept_id = $('#p2_dept_id_'+pet_action_id).val();
	openForm("p1_OfficeDesignSearchForm.php?open_form=P2&petition_id="+pet_id+"&griev_type_id="+griev_type_id+"&griev_sub_type_id="+griev_sub_type_id+"&off_loc_id="+off_loc_id+"&dept_id="+dept_id+"&pet_action_id="+pet_action_id+"&action_entby="+action_entby, "office_design_search");
}
function p2_returnDesignationSearch(petition_id,p_act_id, userID, offLoc_designName){
	$('#p1_user_sno_'+petition_id).val(userID);
	$('#p1_design_'+petition_id).val(offLoc_designName);
	document.getElementById('p1_design_'+petition_id).style.display = "block";
	document.getElementById('p2_fwd_reply_'+p_act_id).style.display = "none";
	
	document.getElementById('p1_design_'+petition_id).disabled = true;
}
function p2_action_type_code(pet_action_id, action_entby, griev_sub_type_id, off_loc_id){
	//alert($("#desig_role").val());
	//document.getElementById("p2_fir_csr_"+pet_action_id).style.display="none";
	document.getElementById('p2_fir_csr_dist_'+pet_action_id).style.display = "none";
	griev_district_id= document.getElementById("p2_griev_district_id_"+pet_action_id).value;
	
	pet_loc_id= document.getElementById("p2_pet_loc_id_"+pet_action_id).value;
	off_level_id= document.getElementById("p2_off_level_id_"+pet_action_id).value;
	dept_off_level_pattern_id= document.getElementById("p2_dept_off_level_pattern_id_"+pet_action_id).value;
	off_level_dept_id= document.getElementById("p2_off_level_dept_id_"+pet_action_id).value;
	
	$("#p2_fwd_reply_"+pet_action_id).empty();
	if($("#p2_act_type_code_"+pet_action_id).val()== "" || $("#p2_act_type_code_"+pet_action_id).val()== "T"){
		$("#p2_fwd_reply_"+pet_action_id).empty();
		return false;
	}
	//alert("pet_loc_id:::"+pet_loc_id);
	var act_type_code = $("#p2_act_type_code_"+pet_action_id).val();
	var param = "mode=p2_act_type"
		+"&p2_act_type_code="+act_type_code
		+"&p2_action_entby="+action_entby
		+"&griev_sub_type_id="+griev_sub_type_id
		+"&off_loc_id="+off_loc_id
		+"&p2_griev_district_id="+griev_district_id		
		+"&pet_loc_id="+pet_loc_id
		+"&off_level_id="+off_level_id
		+"&dept_off_level_pattern_id="+dept_off_level_pattern_id
		+"&off_level_dept_id="+off_level_dept_id		
		+"&dept_id="+$("#p2_dept_id_"+pet_action_id).val()
		+"&p2_petition_id="+$("#p2_petition_id_"+pet_action_id).val()
		+"&form_tocken="+$('#formptoken').val();

	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "t2_ProcessForwardedToUsAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			//alert("==="+xml);
			// we have the response 
			var selectBox = "<select name='p2_fwd_ur_reply' id='p2_fwd_ur_reply_"+pet_action_id+"'></select>";
			$("#p2_fwd_reply_"+pet_action_id).append(selectBox);
			
			if(act_type_code=='N' || act_type_code == 'C' || act_type_code == 'T' || act_type_code == 'I' || act_type_code == 'S'){
				//alert(act_type_code);
				populateComboBoxPlain(xml, "p2_fwd_ur_reply_"+pet_action_id, 'dept_user_id', 'off_location_design', $(xml).find('dept_user_id').eq(0).text());
				//alert("act_type_code:::"+act_type_code);
				if (act_type_code == 'I' || act_type_code == 'S') {
					//alert("Hai");
					//document.getElementById("p2_fir_csr_"+pet_action_id).style.display="";
					document.getElementById("p2_fir_csr_dist_"+pet_action_id).style.display = "";
					load_ext_dist(pet_action_id);
					load_fir_csr(pet_action_id);
	//alert();
					
					//document.getElementById('p2_fwd_reply_'+p_act_id).style.display = "none";
				}
				//$("#p2_remark_"+pet_action_id).val('');
			}
			else{
				var temp = $(xml).find('off_location_design').eq(0).text();
				
		populateComboBoxOfficer(xml, "p2_fwd_ur_reply_"+pet_action_id, (temp.charCodeAt(0)>=2944 && temp.charCodeAt(0)<=3071) ? 'பணியிடம் / பதவி':'Office Location / Designation', 'dept_user_id', 'off_location_design','');
				//alert("Hai::::"+document.getElementById("p2_fwd_ur_reply_"+pet_action_id).options.length);
				var p2_fwd_ur_reply_length = document.getElementById("p2_fwd_ur_reply_"+pet_action_id).options.length;
				if (p2_fwd_ur_reply_length == 1) {
					alert("There is no further forward for this petition. Please reply yourself from here");
					return false;
				}
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
	setDateFormat(date, "#p2_file_date");
  
}

function p2_PetitionProcess(){
	document.getElementById("p2_Save").value="Wait";
	$("#p2_Save").attr("disabled", true);
	
	var status=false;
	var param="mode=p2_fwd_reply_temp_save"+"&form_tocken="+$('#formptoken').val();
	var pet_act_sno=[], pet_sno=[], act_type_code=[], file_no=[], file_date=[], remark=[], fwd_ur_reply=[], j=0;
	
	for(var i=0;i<$("[name='p2_pet_action_id']").size(); i++){		
		var pet_act_element_id=$('input[name=p2_pet_action_id]')[i].id;	
		var pet_act_id=$("#"+pet_act_element_id).val(); //***
		
		var pet_id = $("#p2_pet_id_"+pet_act_id).val(); 
		
		if($("#p2_act_type_code_"+pet_act_id).val()!=""){
			status=true;
			if($("#p2_act_type_code_"+pet_act_id).val()=='F'){
				if (+$("#p2_fwd_ur_reply_"+pet_act_id).val() != '') {
					param += "&p2_fwd_ur_reply[]="+$("#p2_fwd_ur_reply_"+pet_act_id).val();
				} else {
					if($("#p1_user_sno_"+pet_id).val()!="") {
						param += "&p2_fwd_ur_reply[]="+$("#p1_user_sno_"+pet_id).val();
					} else {
						alert("Please select Forwarded To/ Reply To Office Location");
						$("#p2_Save").attr("disabled", false);
						document.getElementById("p2_Save").value="Save";
						return false;
					}
				}
			}
			else if($("#p2_fwd_ur_reply_"+pet_act_id).val()=="" && ($("#p2_act_type_code_"+pet_act_id).val() =="N" || $("#p2_act_type_code_"+pet_act_id).val() =="C"|| $("#p2_act_type_code_"+pet_act_id).val() =="I"|| $("#p2_act_type_code_"+pet_act_id).val() =="S")){
					
				alert("Please select Forwarded To/ Reply To Office Location");
				$("#p2_Save").attr("disabled", false);
				document.getElementById("p2_Save").value="Save";
				return false;
			}
			else if($("#p2_act_type_code_"+pet_act_id).val() =="T"){
				param += "&p2_fwd_ur_reply[]=";
			}
			else{			
				param += "&p2_fwd_ur_reply[]="+$("#p2_fwd_ur_reply_"+pet_act_id).val();	
			}
			
			
			
			if($("#p2_act_type_code_"+pet_act_id).val()=='A' || $("#p2_act_type_code_"+pet_act_id).val()=='E'
			|| $("#p2_act_type_code_"+pet_act_id).val()=='C')
			{
				
				if($("#p2_file_no_"+pet_act_id).val()==""){
					alert("Enter File No. & Date, If No file number, Enter as No File");
					$("#p2_Save").attr("disabled", false);
					document.getElementById("p2_Save").value="Save";
					return false;
				}
				var filename = $("#p2_file_no_"+pet_act_id).val();
				if ((filename.replace(/\s/g,'')).toLowerCase() != 'nofile') {
					if($("#p2_file_date_"+pet_act_id).val()==""){
						alert("Select File Date.");
						$("#p2_Save").attr("disabled", false);
						document.getElementById("p2_Save").value="Save";
						return false;
					}
				}
			}
		// p2_fir_csr_dist_,p2_fir_circle_,p2_fir_no_,p2_fir_year_
			//alert("pet_act_id:::::::::;"+pet_act_id)
			if($("#p2_act_type_code_"+pet_act_id).val()=='I'|| $("#p2_act_type_code_"+pet_act_id).val()=='S')
			{
				if($("#p2_fir_dist_"+pet_act_id).val()==""){					
					alert("Select FIR/SCR Disttrict");
					$("#p2_Save").attr("disabled", false);
					document.getElementById("p2_Save").value="Save";
					return false;
				}
				
				if($("#p2_fir_circle_"+pet_act_id).val()==""){
					alert("Select FIR/SCR Police Station");
					$("#p2_Save").attr("disabled", false);
					document.getElementById("p2_Save").value="Save";
					return false;
				}
				
				if($("#p2_fir_year_"+pet_act_id).val()==""){
					alert("Enter  FIR/CSR Year");
					$("#p2_Save").attr("disabled", false);
					document.getElementById("p2_Save").value="Save";
					return false;
				}
				
				if($("#p2_fir_no_"+pet_act_id).val()==""){
					alert("Enter  FIR/CSR Number");
					$("#p2_Save").attr("disabled", false);
					document.getElementById("p2_Save").value="Save";
					return false;
				}

			} 																										 
			
			if($("#p2_act_type_code_"+pet_act_id).val()=='R')
			{
				if($("#p2_remark_"+pet_act_id).val()==""){
					alert("Enter Remarks");
					$("#p2_Save").attr("disabled", false);
					document.getElementById("p2_Save").value="Save";
					return false;
				}
			}
			
		if($("#p2_act_type_code_"+pet_act_id).val()=='N')
		{
			if($("#p2_remark_"+pet_act_id).val()==""){
				alert("Enter Remarks");
				$("#p2_Save").attr("disabled", false);
				document.getElementById("p2_Save").value="Save";
				return false;
			}
		}
			
			var fdate = $("#p2_file_date_"+pet_act_id).val();
			
			if (fdate != "") {
				var datearray = fdate.split("/");
				var newdate = datearray[1] + '/' + datearray[0] + '/' + datearray[2];
			} else {
				var newdate = "";	
			}	
			param += "&p2_pet_act_sno[]="+pet_act_id;
			param += "&p2_pet_sno[]="+$("#p2_petition_id_"+pet_act_id).val();
			param += "&p2_act_type_code[]="+$("#p2_act_type_code_"+pet_act_id).val();
			param += "&p2_file_no[]="+ $("#p2_file_no_"+pet_act_id).val();
			param += "&p2_file_date[]="+ newdate;
			param += "&p2_remark[]="+$("#p2_remark_"+pet_act_id).val();	
			param += "&p2_fir_no[]="+$("#p2_fir_no_"+pet_act_id).val();	
			param += "&p2_fir_csr_dist[]="+$("#p2_fir_dist_"+pet_act_id).val();	
			param += "&p2_fir_circle[]="+$("#p2_fir_circle_"+pet_act_id).val();	
			param += "&p2_fir_year[]="+$("#p2_fir_year_"+pet_act_id).val();	
			j++;
		}
	}
	if(status){
		
		document.getElementById("t2_loadmessage").style.display='';
		$.ajax({
			type: "POST",
			dataType: "xml",
			url: "t2_ProcessForwardedToUsAction.php",
			data: param,  
			
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){
				// we have the response 			
				//$("#p2_Save").attr("disabled", false);	 - Old Scenario
				var status = $(xml).find('status').eq(0).text();

				if(status=='S'){
					alert($(xml).find('tot').eq(0).text()+"\n\n"+
						($('#login_lvl').val()=="BOTTOM"? "":$(xml).find('f').eq(0).text()+"\n")+
						$(xml).find('n').eq(0).text()+"\n"+
						$(xml).find('c').eq(0).text()+"\n"+
						$(xml).find('t').eq(0).text()+"\n"+

						$(xml).find('ir').eq(0).text()+"\n"+
						$(xml).find('s').eq(0).text()+"\n"+			  
						"processed successfully \n\n" +
						$(xml).find('fc').eq(0).text()+
							"  (If any, might already have been processed.)");
					p2_loadGrid( 1, $('#p2_pageSize').val());
					$("#p2_Save").attr("disabled", false);
					document.getElementById("p2_Save").value="Save";
					
				}
				else{					
					alert($(xml).find('fc').eq(0).text() +" , "+$(xml).find('msg').eq(0).text()+ "  (If any, might already have been processed.)");
					p2_loadGrid( 1, $('#p2_pageSize').val());
					$("#p2_Save").attr("disabled", false);
					document.getElementById("p2_Save").value="Save";
				}
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			} 
		});//ajax end
		$("#p2_Save").text('Save');
	}
	else{
		alert("Please fill atleast one petition to Forwarded To/Replied To/Temporary Action");
		$("#p2_Save").attr("disabled", false);
		document.getElementById("p2_Save").value="Save";
		return false;	 
	}
}
function changeDateFormat(fdate) {
	
var datearray = fdate.split("/");

var newdate = datearray[1] + '/' + datearray[0] + '/' + datearray[2];
	return newdate;
}
function validatedate1(inputText,pet_act_id){
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
  document.getElementById("p2_file_date_"+pet_act_id).value=""; 
  return false;  
  }  
}
/////////////////////////////
  function openPetitionStatusReport2(petition_id){
	document.getElementById("petition_id2").value=petition_id;
	document.petition_process2.target = "Map";
	document.petition_process2.method="post";  
	document.petition_process2.action = "p_PetitionProcessDetails.php";
	map = window.open("", "Map", "status=0,title=0,fullscreen=yes,scrollbars=1,resizable=0");
	if(map){
		document.petition_process2.submit();
	}  
}

</script>

<?php /*?><?php echo $_SESSION[LOGIN_LVL]; ?><?php */?>
<?php $_SESSION['key'] = mt_rand(1, 1000); ?>

<form method="post" name="petition_process2" id="petition_process2">
<table class="searchTbl" style="border-top: 1px solid #000000;">
	<tbody>
	<tr>
	  <th style="width:20%;"><?PHP echo $label_name[19];//Petition Period?></th>
	  <th style="width:20%;"><?PHP echo $label_name[22];//Petition No.?></th>
	  <th style="width:20%;"><?PHP echo $label_name[35];//Petition Type?></th>
	  <th style="width:20%;"><?PHP echo $label_name[23];//Source?></th>
	  <!--<th style="width:12%;"><?PHP //echo $label_name[34];//Department?></th> -->
	  <th style="width:20%;"><?PHP echo $label_name[33];//Petition Main Category?></th>
	 
	</tr>
	
	<tr>
		<td class="from_to_dt"  style="width:20%;">
        	<?PHP echo $label_name[20];//From?>&nbsp;<input type="text" name="p2_from_pet_date" id="p2_from_pet_date" maxlength="12" 
            style="width: 90px;" onchange="return validatedate(p2_from_pet_date,'p2_from_pet_date'); "/>
        	&nbsp;<?PHP echo $label_name[21];//To?>&nbsp;<input type="text" name="p2_to_pet_date" id="p2_to_pet_date" maxlength="12" 
            style="width: 90px;" onchange="return validatedate(p2_to_pet_date,'p2_to_pet_date'); "/>
        </td>
		<td style="width:20%;"><input type="text" name="p2_petition_no" id="p2_petition_no" onKeyPress="return checkPetNo(event);" maxlength="25" style="width:280px;"/></td>
		
		<td style="width:20%;">
		<select name="p2_petition_type" id="p2_petition_type" style="width:280px;">
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
		
        <td  style="width:20%;">
        	<select name="p2_source" id="p2_source" style="width:280px;">
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
		<td  style="width:12%;">
          <select name="p2_dept" id="p2_dept" style="width:240px;">
          <option value="">-- Select Department --</option>
          <?PHP 
 /*          $dept_sql = "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
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
/* 				$res = $db->query($dept_sql);
				$row_arr = $res->fetchall(PDO::FETCH_ASSOC);
				foreach($row_arr as $row) {
					$dept_name=$row[dept_name];
					echo "<option value='".$row[dept_id]."'>$dept_name</option>";	
				} */
          ?>
          </select>
      </td>
	  -->
	  <td  style="width:20%;">
          <select name="p2_gtype" id="p2_gtype" style="width:280px;">
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
        	<input type="button" name="p2_search" id="p2_search" value="<?PHP echo $label_name[24];//Search?>" class="button"/>
        	<input type="button" name="p2_search" id="p2_clear" value="<?PHP echo $label_name[25];//Clear?>" class="button"/>
        </td>
	</tr>
	</tbody>
</table>
<table class="existRecTbl">
	<thead>
	<tr>
		<th><?PHP echo $label_name[7];//Existing Details?></th>
		<th><?PHP echo $label_name[8];//Page&nbsp;Size?><select name="p2_pageSize" id="p2_pageSize" class="pageSize">
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
	<tbody id="p2_dataGrid"></tbody>
</table>
<div id="t2_loadmessage" div align="center" style="display:none"><img src="images/wait.gif" width="100" height="90" alt=""/></div>

<table class="paginationTbl">
	<tbody>
		<tr id="p2_pageFooter1" style="display: none;">
			<td id="p2_previous"></td>
			<td>Page<select id="p2_pageNoList" name="p2_pageNoList" class="pageNoList"></select><span id="p2_noOfPageSpan"></span></td>
			<td id="p2_next"></td>
		</tr>
		<tr id="p2_pageFooter2" style="display: none;"><td colspan="3" class="emptyTR"></td>
		</tr>
        <tr>
        	<td colspan="3" class="emptyTR">
		<input type="button" class="button" value="<?PHP echo $label_name[26];//Save?>" id="p2_Save" name="p2_Save">	
            <?php
            $ptoken = md5(session_id() . $_SESSION['salt']);
            $_SESSION['formptoken']=$ptoken;
            ?>
            
            <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" />
            <input type="hidden" name="petition_id2" id="petition_id2" />
			<input type="hidden" name="key" value="<?php echo $_SESSION['key'] ?>" />
			<input type="hidden" id="h_off_level_dept_id" name="h_off_level_dept_id" value="<?php echo $userProfile->getOff_level_dept_id(); ?>" />
            </td>
		</tr>
	</tbody>
</table>
</form>

