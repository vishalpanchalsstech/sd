
    function delete_row(row){
        
        var box = $("#mb-remove-row");
        box.addClass("open");
        
        box.find(".mb-control-yes").on("click",function(){
            box.removeClass("open");
            $("#"+row).hide("slow",function(){
                $(this).remove();
            });
        });
        
    }
	/***********delete data custom js**************/
	 function delete_row_new(row){
   
    var box = $("#mb-remove-row");
	debugger;
        box.addClass("open");
       var x = $('#delete-data').attr('controller'); 
        //var urln = x+'/'+row;
        var urln = x
		
		var lastChar = urln.substr(-1); // Selects the last character
		if (lastChar != '/') {         // If the last character is not a slash
		   urln = urln +'/'+row;           // Append a slash to it.
		}
		else{
			urln = urln + row;  
		}		
		
        box.find(".mb-control-yes").on("click",function(){
            //window.location.href= x+'/'+row;
            window.location.href= urln;
            });
	 }


	/***** Restrict File Extensions While upload File 1 ******/	
	function validatefile(oInput) {
    var _validFileExtensions = [".jpg", ".jpeg",".png"]; 
    if (oInput.type == "file")
	{
        var sFileName = oInput.value;
         if (sFileName.length > 0) 
		 {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) 
			{
                var sCurExtension = _validFileExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) 
				{
                    blnValid = true;
					$('#shfilerr').html('');
                    break;
                }
            }
             
            if (!blnValid) {
				
                //alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
				$('#shfilerr').html('<label id="Name-error" class="error" for="Name"><strong>Sorry file is invalid, allowed extensions are: .jpg, .jpeg, .png  </strong></label>');
				oInput.value = "";
                return false;
            }
        }
    }
    return true;
	}
	
   
/**** Country_state_suburb Auto complete textbox ajax ***/
	$(function() 
	{
		var mycountry = $('#country').val();
		var options = {
			url: function(phrase) 
			{
				return base_url+"general/get_suburb_json";
			},
			getValue: function(element) 
			{
				return element.stateName+', '+element.Suburb+', '+$('#country').val()+', '+element.PostCode;
			},
			ajaxSettings: 
			{
				dataType: "json",
				method: "POST",
				beforeSend: function( ) 
				{
				   $('#city').addClass('Loading-inputbox');
				},
				success : function(data)
				{
					if(data == 0 )
					{
						$('#city').attr('style',' ');    
						$('#city').addClass('error-message');
						if($('#city_id-errors').length == 0)
						{
							$('<p id="city_id-errors" class="error-message"> No result found</p>').insertAfter('#city_id');
						}
							$('#city').removeClass('valid');
					}
					else
					{
					   $('#city').attr('style',' ');
					   $('#city').removeClass('error-message');
					   $('#city_id-errors').remove();
					   $('#city_id-error').remove();
					   $('#city').addClass('valid');
					}
				},
				complete: function(data) 
				{
					// $('#city_id-errors').remove();
					$('#city_id-error').remove();
					$('#city').removeClass('Loading-inputbox');
				},
				data: 
				{
				  dataType: "json"
				}
			},
			preparePostData: function(data) 
			{
				data.country =$('#country').val();
				data.keyword = $("#city").val();
				return data;
			},
			focus: function(e, ui) 
			{
				return false; // don't fill input with highlighted value
			},
			list: 
			{
			  match: 
			  {
				enabled: true
			  },  
				onSelectItemEvent: function() 
				{
					var subrub = $("#city").getSelectedItemData().Suburb;
					var postcode =  $("#city").getSelectedItemData().PostCode;
					var state = $("#city").getSelectedItemData().stateName;
					$('#postalcode').val(postcode);
					$('#state').val(state);
					$('#city').val(subrub).trigger("change");
					$('#city_id-error').remove();
				},
				onChooseEvent: function() 
				{
					var selectedItemValue = $("#city").getSelectedItemData().Suburb;
					//var selectedItemValue2 = $("#autocomplete").getSelectedItemData().airportid;
					$('#city').val(selectedItemValue).trigger("change");;
					$('#city_id-error').remove();
				},
			}   
		};
		
		$("#city").easyAutocomplete(options);
			
		$('#state, #postalcode').on('change, blur',function()
		{
			var country = $('#country').val();
			var city = $('#city').val();
			var state = $('#state').val();
			var postcode = $('#postalcode').val();

			$.ajax({
					url: base_url+"general/validate_address",
					type:"POST",
					data:
					{	selectcountry:country, 
						selectcity:city,
						selectstate:state,
						selectpostcode:postcode 
					},
				success: function(data)
				{
				    if(data ==1)
					{
						if($('#city_id-error').length == 0)
						{
							$('<p id="city_id-error" class="error-message"> Invalid suburb please search and select suburb from dropdown</p>').insertAfter('#city');
							$('#city').addClass('error-message');
							$('#city').val('');
							$('#state').val('');
							$('#postalcode').val(''); 
							$('#city').focus(); 
							$('#city_id-errors').remove();
						}
					}
					else
					{
						$('#city').removeClass('error-message');
						$('#city_id-error').remove();
						$('#city_id-errors').remove();
						//$('#city_id').removeClass('error-message');
					}
				}
			});
		});
	});

	
/******************************************* Search through suburb  ***************************************************/
$('#city').on('keypress keyup',function(){
  var keyword = $(this).val();
    if(keyword){   
        var country = $("#country").val();
        var url= baseurl+"index.php/general/get_suburb"+"/"+country;

        $.ajax({
        url:url,
        type:'post',
        data:{keyword:keyword},
        success:function(res){
                    if(!res){
                           $("#suburb-div ul").html('not found');
                            $("#suburb-div").show();
                    }else{
                    var total_record = res.length;
                    var result = $.parseJSON(res); 
                    var state = result.stateName;
                    var Suburb = result.Suburb;
                    var PostCode = result.PostCode;					
                        var x = 0;
                        var text  = '';
                        for(x=0; x < result.length; x++){
                        if(x==(result.length -1)){
                            text  += "<li><a id='searchsub'>"+result[x].Suburb+', '+result[x].stateName+', ' +country+', ' +result[x].PostCode+"</a></li>";
                        }else{
                            text  += "<li><a id='searchsub'>"+result[x].Suburb+', '+result[x].stateName+', ' +country+', ' +result[x].PostCode+"</a></li>";
                        }
                    }
                    $("#suburb-div ul").html(text);
                    $("#suburb-div").show();
                    }
            }
        });
    }else{ 
            $("#suburb-div ul").html(''); 
			$('#suburb-div').css('display','none');
            $("#state").val('');
            $("#city").val('');
            $("#postalcode").val('');
    } 
});

/********************************************** on click suburb   ********************************************/
$(function(){
	$("div#suburb-div ul").delegate('a','click',function(){
		$("#suburb").val('');
		var subur =  $(this).text();
		var temp = new Array();
		temp = subur.split(", ");
		var state = temp[1];
		var city = temp[0];
		var country =  temp[2];
		var postcode = temp[3];
		$("#country").attr('value',country);
		$("#state").val(state);
		$("#city").val(city);
		$("#postalcode").val(postcode);
		$('div#suburb-div').hide('slow');
		/*********  enable disable input box on chnage *************/
		$('#postalcode').next('p.error-message').css('display','none');
		$('#postalcode').removeClass("error-message").addClass('valid');
	});
/******************************************** onchnage country event   ********************************************/
	$('#country').on('change',function(){
		$("#state").val('');
		$("#city").val('');
		$("#postalcode").val('');
	});
	$(document).click(function(){
		$('div#suburb-div').hide('slow');
	});
});
/*************************************************************************************/