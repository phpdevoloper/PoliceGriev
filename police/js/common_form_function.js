function setDatePicker(elementID){
	$('#'+elementID).datepick({
		showTrigger: '#calImg',
		changeMonth: true,
		changeYear: true,
		//yearRange: "-100:-10",
		yearRange: "-100:+10",
		selectWeek: true,
		/*minDate: '01/01/2012',*/
		maxDate: new Date,
		inline: true,
		dateFormat: 'dd/mm/yyyy'
	});
	$('#'+elementID).addClass("embed");
}
//set date object to string format of DD/MM/YYYY
function setDateFormat(dateObj, elementId){
	var day = dateObj.getDate();
	if(day<=9)
		day='0'+day;
  	var month = dateObj.getMonth();month++;
	if(month<=9)
		month='0'+month;
  	var year = dateObj.getFullYear();
	$(elementId).val('');
	$(elementId).val(day+'/'+month+'/'+year);
}

function setCheckBox(elementId_or_class, val){
	if(val==1){
		$(elementId_or_class).prop('checked', true);
	}
	else{
		$(elementId_or_class).prop('checked', false);
	}
}

function setDisableBtn(elementId_or_class, val){
	if(val){
		$(elementId_or_class).attr("disabled",val);
		$( elementId_or_class ).removeClass('input[type="button"]' );
		$( elementId_or_class ).addClass( 'input[type="button"]:disabled' );
	}
	else{
		$(elementId_or_class).removeAttr("disabled"); 
		$( elementId_or_class ).addClass( 'input[type="button"]:disabled' );
		$( elementId_or_class ).addClass( 'input[type="button"]' );		
	}
}

function setDisable(elementId_or_class, val){
	if(val){
		$(elementId_or_class).attr("disabled",val);
	}
	else{
		$(elementId_or_class).removeAttr("disabled");
	}
}

function setUncheckRadio(element_name){
	$(':radio[name='+element_name+']').prop('checked', false);
}

function getRadioValue(radioBtnName) {
    if( $('input[name='+radioBtnName+']:radio:checked').length > 0 ) {
        return $('input[name='+radioBtnName+']:radio:checked').val();
    }
    else {
        return 0;
    }
}

function createCombobox(elementId, desc){
	$("#"+elementId).empty().append("<option value=''>-- Select "+ desc + " -- </option>");
}

function populateComboBox(xml, elementId, defaultDesc, snoTag, descTag, editVal){
	//alert("---->>>>");
	$('#'+elementId).empty().append('<option value="">-- '+defaultDesc+' --</option>');
	//alert("/////"+snoTag);
	$(xml).find(snoTag).each(function(i){
		//alert("******");
		var sno = $(xml).find(snoTag).eq(i).text();
		var desc = $(xml).find(descTag).eq(i).text();
		 //alert(desc);
		$('#'+elementId).append('<option value='+sno+'>'+desc+'</option>');
	});
	$('#'+elementId).val(editVal);
}

function populateComboBoxOfficer(xml, elementId, defaultDesc, snoTag, descTag, editVal){
	//alert("---->>>>"+xml);
	var prev_dept_id= 0;
	$('#'+elementId).empty().append('<option value="">-- '+defaultDesc+' --</option>');
	//alert("/////"+snoTag);
	$(xml).find(snoTag).each(function(i){
		//alert("******");
		if (prev_dept_id !== $(xml).find('off_level_dept_id').eq(i).text()) {
			$('#'+elementId).append ("<optgroup label='"+$(xml).find('off_level_name').eq(i).text()+"'>");  
		}
		var sno = $(xml).find(snoTag).eq(i).text();
		var desc = $(xml).find(descTag).eq(i).text();
		 //alert(desc);
		$('#'+elementId).append('<option value='+sno+'>'+desc+'</option>');
		prev_dept_id=$(xml).find('off_level_dept_id').eq(i).text();
	});
	$('#'+elementId).val(editVal);
}

function populateComboBoxPlain(xml, elementId, snoTag, descTag, editVal){
	$('#'+elementId).empty();
	var sno="";
	$(xml).find(snoTag).each(function(i){
		sno = $(xml).find(snoTag).eq(i).text();
		var desc = $(xml).find(descTag).eq(i).text();
		$('#'+elementId).append('<option value='+sno+'>'+desc+'</option>');
	});
	$('#'+elementId).val(sno);
}

function openForm(formUrl, winName)
{
	var win;
	win= window.open( formUrl,"view_" + winName ,"status=1,height=550,width=1020,resizable=YES,scrollbars=yes");
	win.moveTo(150,150);
	win.focus();
}
function printwindow()
{
	window.print();
}

function printReportToPdf()
{	

	var mymenu = document.getElementById('menu');  //common_form_function.js
	if (mymenu === null) {		
	} else {
		document.getElementById("menu").style.display='none';
	}
	var bak_btn1 = document.getElementById('bak_btn1');  //common_form_function.js
	if (bak_btn1 === null) {		
	} else {
		document.getElementById("bak_btn1").style.display='none';
	}	
	var myusr = document.getElementById('usr_detail');
	if (myusr === null) {		
	} else {
		document.getElementById("usr_detail").style.display='none';
	}
	//document.getElementById("header").style.visibility='hidden';
	document.getElementById("header").style.display='none';
	document.getElementById("header_report").style.display='block'; 	
	document.getElementById("dontprint1").style.visibility='hidden';
	document.getElementById("bak_btn").style.display='none'; 
	window.print();
	//document.getElementById("header").style.visibility='visible';
	//document.getElementById("header_report").style.visibility='visible'; 	
	document.getElementById("header").style.display='';
	document.getElementById("header_report").style.display='none'; 		
	if (mymenu === null) { } else {
		document.getElementById("menu").style.display='block';
	}
	if (myusr === null) { } else {
		document.getElementById("usr_detail").style.display='block';
	}
	if (bak_btn1 === null) {		
	} else {
		document.getElementById("bak_btn1").style.display='';
	}
	document.getElementById("dontprint1").style.visibility='visible';
	document.getElementById("bak_btn").style.display='';
}

function form_exit()
{
	self.close();
}

function Minimize() 
{
	window.resizeTo(0,0);
	window.screenX = screen.width;
	window.screenY = screen.height;
	opener.window.focus();
	window.close();
}
/**
*	Start Pagination Controls
*/
function drawPagination(pageFooter1, pageFooter2, pageSizeElenemtId, pageNoListElenemtId, nextElenemtId, previousElenemtId, noOfPageSpanId, loadGridFun, pageNo, pageSize, noOfPage){
	//alert("1111");
	if(parseInt(noOfPage)>parseInt(1)){
		$("#"+pageFooter1).show();
		$("#"+pageFooter2).hide();
		
		//show no. of pages
		$("#"+noOfPageSpanId).empty();
		$('#'+noOfPageSpanId).append(" / "+parseInt(noOfPage));

		//load no of page combo box
		$("#"+pageNoListElenemtId).empty();
		for(var x=1;x<=noOfPage;x++){
			$("#"+pageNoListElenemtId).append('<option value='+x+'>'+x+'</option>');
		}
		$("#"+pageNoListElenemtId).val(pageNo);
		
		//create next
		$("#"+nextElenemtId).empty();
		if(parseInt($('#'+pageNoListElenemtId).val())<parseInt(noOfPage)){
			var param = "mode=t1_search"
				+"&t1_page_size="+$('#'+pageSizeElenemtId).val()
				+"&t1_pageNoList="+$('#'+pageNoListElenemtId).val();
			//set next page
			$('#'+nextElenemtId).append("<a href='javascript:"+loadGridFun+"("+(parseInt($('#'+pageNoListElenemtId).val())+1)+","+$('#'+pageSizeElenemtId).val()+")"+"'>Next >></a>");//<img src='images/next.png' style='height: 18px;'/>
		}
		
		//create previous
		$("#"+previousElenemtId).empty();
		if(parseInt($('#'+pageNoListElenemtId).val())>1){
		
			var param = "mode=t1_search"
				+"&t1_page_size="+$('#'+pageSizeElenemtId).val()
				+"&t1_pageNoList="+$('#'+pageNoListElenemtId).val();
			//set next page
			$('#'+previousElenemtId).append("<a href='javascript:"+loadGridFun+"("+(parseInt($('#'+pageNoListElenemtId).val())-1)+","+$('#'+pageSizeElenemtId).val()+")"+"'><< Previous</a>");
		}
		
	}
	else{
		$("#"+pageFooter1).hide();
		$("#"+pageFooter2).show();
	}
}

function charactersonly(e) 
	{ 	
	 
		var unicode=e.charCode? e.charCode : e.keyCode;
		if (unicode!=8 && unicode!=9 && unicode!=46 )
		{
		if ((unicode >64 && unicode<123 && unicode!=34 && unicode!=41 && unicode!=96 && unicode!=95 && 
		unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 ) || (unicode==32))
				return true
		else
				return false
		}
	}
	function characters_numsonly(e) 
	{ 	
		var unicode=e.charCode? e.charCode : e.keyCode;
		if (unicode!=8 && unicode!=9 && unicode!=46)
		{
		if ((unicode >65 && unicode<123 && unicode!=96 && unicode!=94 && 
		unicode!=93 && unicode!=92 && unicode!=91 ) || (unicode==32 || unicode==40 || unicode==41 || unicode==45  || unicode>=47 && unicode<57)) //&& unicode!=95 (_)
				return true
		else
				return false
		}
	}
	
	function checkFileNo(e) 
	{ 	
		//alert("Check");
		var unicode=e.charCode? e.charCode : e.keyCode;
		//alert(unicode);
		if (unicode!=8 && unicode!=9 && unicode!=46)
		{
		if ((unicode >64 && unicode<123)  || (unicode>=47 && unicode<=57)  || (unicode==32 || unicode==45 ) && (unicode!=96 && unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 )) //&& unicode!=95 (_)
				return true
		else
				alert("Only alphabets, numbers, Special characters / and - are allowed");
				return false
		}
	}
	
	function checkFileNoProcessing(e) 
	{ 	
		//alert("Check");
		var unicode=e.charCode? e.charCode : e.keyCode;
		//alert(unicode);
		if (unicode!=8 && unicode!=9 && unicode!=46)
		{
		if ((unicode >64 && unicode<123)  || (unicode>=47 && unicode<=57)  || (unicode==32 || unicode==45 ) || (unicode >=2304 && unicode<=3583) && (unicode!=96 && unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 )) //&& unicode!=95 (_)
				return true
		else
				alert("Only alphabets, numbers, Special characters / and - are allowed");
				return false
		}
	}
	
	function characters_numsonly_grievance(e) 
	{ 	
		var unicode=e.charCode? e.charCode : e.keyCode;
		//alert(unicode);
		if (unicode!=8 && unicode!=9 && unicode!=46)
		{
//if ( (unicode >64 && unicode<123 && unicode!=96 && unicode!=95 && unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 ) || (unicode==32 || unicode>=45 || unicode>=47 && unicode<=57))
		if ( (((unicode >64 && unicode<123) || (unicode >=2304 && unicode<=3583)) && unicode!=96 && unicode!=95 && unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 ) || unicode==32 || unicode>=45 && unicode<=57)
				return true
		else
				return false
		}
	}
	
 function numbersonly(e,t)
{
    var unicode=e.charCode? e.charCode : e.keyCode;
	if(unicode==13)
	{
		try{t.blur();}catch(e){}
		return true;
	}
	if (unicode!=8 && unicode !=9)
	{
		if(unicode<48||unicode>57)
		return false
	}
}

function numbersonly_ph(e,t)
{
    var unicode=e.charCode? e.charCode : e.keyCode;
	if(unicode==13)
	{
		try{t.blur();}catch(e){}
		return true;
	}
	if (unicode!=8 && unicode !=9 && unicode !=43)
	{
		if(unicode<48||unicode>57)
		return false
	}
}

function changeDateFormat(dt){
	var datearray = dt.split("/");
	var ndt = datearray[1] + '/' + datearray[0] + '/' + datearray[2];
	return ndt;
}
//Validate Function for File No
/**
*	End Pagination Controls
*/