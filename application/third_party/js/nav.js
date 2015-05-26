function toggleFieldset(t) {
	if(t.style.display!="none") { 
		t.style.display="none";
	} else {
		t.style.display="";
	}
}

$(document).ready(function() {

	$('ul.sf-menu').superfish();
	$('.del').click(function(){
	    var answer = confirm('Are you sure to delete : '+jQuery(this).attr('title')+' ?');
	    return answer; 
	});
	$('.delpgl').click(function(){
	    var answer = confirm('This action will delete all NPK and Formula below them. \r\n Are you sure to delete : '+jQuery(this).attr('title')+' ?');
	    return answer; 
	});  
	$('.refresh').click(function(){
	    var answer = confirm('Are you sure to refresh : '+jQuery(this).attr('title')+' ?');
	    return answer; 
	}); 
	$('.lock').click(function(){
	    var answer = confirm('Are you sure to lock : '+jQuery(this).attr('title')+' ?');
	    return answer; 
	}); 
	$('.unlock').click(function(){
	    var answer = confirm('Are you sure to un-lock : '+jQuery(this).attr('title')+' ?');
	    return answer; 
	}); 
	$('.setasfee').click(function(){
	    var answer = confirm('Are you sure to set this component as fee ?');
	    return answer; 
	}); 
	$('.npksave').click(function(){
	    var answer = confirm('Are you sure to save this NPK :'+jQuery(this).attr('title')+' ?');
	    return answer; 
	}); 
	
	var $targetfml;
	$("textarea[name='str_formula[]']").focus(function(){
		$targetfml = jQuery(this);
	});
	$("textarea[name^='str_formula_exist']").focus(function(){
		$targetfml = jQuery(this);
	});
	$(".btnfml").click(function(){
		if($targetfml) {
			$targetfml.val($targetfml.val()+jQuery(this).text());
		} else {
			alert("Click text input first then click component's button.");
		}
		return false;
	});
	$("input[name^='cell']").focus(function(){
		$targetfml = jQuery(this);
	});
	$(".btntbl").click(function(){
		if($targetfml) {
			$targetfml.val(jQuery(this).text());
		} else {
			alert("Click text input first then click component's button.");
		}
		return false;
	});
	$(".pgl_to_ten").change(function() { 
		var sendto = "../../index.php/asyncreq/dropdownten/"+jQuery(this).val();
		var dataString = "";
		$.ajax({  
			type: "POST",  
			url: sendto,  
			data: dataString,
			dataType: "string",
			success: function(data) {
	            		alert(data);
	        	}
		});
		$(".ct_ten_of_pgl").load(encodeURI(sendto)); 
		return false;
		
	});
	$(".ten_of_pgl").change(function() { 
		alert('changeten');
		/*var sendto = "../../index.php/asyncreq/dropdownperiodten/"+jQuery(this).val();
		var dataString = "";
		$.ajax({  
			type: "POST",  
			url: sendto,  
			data: dataString,
			dataType: "string",
			success: function(data) {
		    		alert(data);
			}
		});
		$(".ct_period_of_ten").load(encodeURI(sendto)); 
		return false;*/

	});		

	
	$(".datepicker").datepicker({dateFormat: 'dd/mm/yy'});
        $('#submit-mou').formValidator({
            scope	: '#form-mou',
            errorDiv	: '#error-1'
        });

});
