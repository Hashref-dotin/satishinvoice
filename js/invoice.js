 $(document).ready(function(){
	$(document).on('click', '#checkAll', function() {          	
		$(".itemRow").prop("checked", this.checked);
	});	
	$(document).on('click', '.itemRow', function() {  	
		if ($('.itemRow:checked').length == $('.itemRow').length) {
			$('#checkAll').prop('checked', true);
		} else {
			$('#checkAll').prop('checked', false);
		}
	});  
	var count = $(".itemRow").length;
	$(document).on('click', '#addRows', function() { 
		calculateTotal();
		count++;
		var htmlRows = '';
		htmlRows += '<tr>';
		htmlRows += '<td><input class="itemRow" type="checkbox"></td>';        
		htmlRows += '<td><textarea class="form-control" rows="6" class="col-md-12"  name="productName[]" id="productName_'+count+'" placeholder="Product Name"></textarea></td>';	
		htmlRows += '<td><input type="text" name="productCode[]" id="productCode_'+count+'" class="form-control" autocomplete="off"></td>';   
		htmlRows += '<td><input type="number" name="quantity[]" id="quantity_'+count+'" class="form-control quantity" autocomplete="off" value="1"></td>';   		
		htmlRows += '<td><input type="number" name="price[]" id="price_'+count+'" class="form-control price" autocomplete="off"></td>';		 
		htmlRows += '<td class="cgst_text"><input type="number" name="cgst[]" id="cgst_'+count+'" class="form-control price cgst_input" autocomplete="off" class="cgst_text"></td>';		 
		htmlRows += '<td class="sgst_text"><input type="number" name="sgst[]" id="sgst_'+count+'" class="form-control price sgst_input" autocomplete="off" class="sgst_text"></td>';		 
		htmlRows += '<td class="igst_text"><input type="number" name="igst[]" id="igst_'+count+'" class="form-control price igst_input" autocomplete="off" class="igst_text"></td>';		 
		htmlRows += '<td><input type="number" name="total[]" id="total_'+count+'" readonly class="form-control total" autocomplete="off"></td>';          
		htmlRows += '</tr>';
		$('#invoiceItem').append(htmlRows);
		checkgstcolumns();
	}); 
	$(document).on('click', '#removeRows', function(){
		$(".itemRow:checked").each(function() {
			$(this).closest('tr').remove();
		});
		$('#checkAll').prop('checked', false);
		calculateTotal();
	});		
	$(document).on('keyup', "[id^=quantity_]", function(){
		calculateTotal();
	});	
	$(document).on('keyup', "[id^=price_]", function(){
		calculateTotal();
	});	
	$(document).on('keyup', "[id^=sgst_]", function(){
		calculateTotal();
	});	
	$(document).on('keyup', "[id^=cgst_]", function(){
		calculateTotal();
	});	
	$(document).on('keyup', "[id^=igst_]", function(){
		calculateTotal();
	});	
	$(document).on('keyup', "[id^=total_]", function(){
		calculateTotal();
	});
	$(document).on('keypress', "[id^=total_]", function(){
		calculateTotal();
	});
	$(document).on('blur', "[id^=total_]", function(){
		calculateTotal();
	});		

	$(document).on('click', '.deleteInvoice', function(){
		var id = $(this).attr("id");
		if(confirm("Are you sure you want to remove this?")){
			$.ajax({
				url:"action.php",
				method:"POST",
				dataType: "json",
				data:{id:id, action:'delete_invoice'},				
				success:function(response) {
					if(response.status == 1) {
						$('#'+id).closest("tr").remove();
					}
				}
			});
		} else {
			return false;
		}
	});

	

	$(document).on('click', '.deleteSuper', function(){
		var id = $(this).attr("id");
		if(confirm("Are you sure you want to remove this?")){
			$.ajax({
				url:"action_super.php",
				method:"POST",
				dataType: "json",
				data:{id:id, action:'delete_invoice'},				
				success:function(response) {
					if(response.status == 1) {
						$('#'+id).closest("tr").remove();
					}
				}
			});
		} else {
			return false;
		}
	});


	$(document).on('click', '.deleteSsv', function(){
		var id = $(this).attr("id");
		if(confirm("Are you sure you want to remove this?")){
			$.ajax({
				url:"action_ssv.php",
				method:"POST",
				dataType: "json",
				data:{id:id, action:'delete_invoice'},				
				success:function(response) {
					if(response.status == 1) {
						$('#'+id).closest("tr").remove();
					}
				}
			});
		} else {
			return false;
		}
	});

	$( "#order_date" ).datepicker({ dateFormat: 'yy-mm-dd' });

	$("#termstrue").on('click', function(){
		if($(this).prop("checked") == true){
			$("#terms").show();
		}

		else if($(this).prop("checked") == false){
			$("#terms").hide();
		}
	});

	
	$("#enable_igst").on('click', function(){
		$("#enable_csgst").prop("checked", false);
		checkgstcolumns();
	});
	$("#enable_csgst").on('click', function(){
		$("#enable_igst").prop("checked", false);
		checkgstcolumns();
	});
	
	var textlimit = 180;

        $('#terms').keyup(function() {
            var tlength = $(this).val().length;
            $(this).val($(this).val().substring(0, textlimit));
            var tlength = $(this).val().length;
			remain = textlimit - parseInt(tlength);
			$("#showtotalterms").text(tlength);
            $('#terms').text(remain);
		 });  
		 
		 
		 var textlimit = 160;

        $('.declarationtext').keyup(function() {
            var tlength = $(this).val().length;
            $(this).val($(this).val().substring(0, textlimit));
			var tlength = $(this).val().length;
			$("#showtotal").text(tlength);
            remain = textlimit - parseInt(tlength);
            $('.declarationtext').text(remain);
         });  

});	
function calculateTotal(){
	var totalAmount = 0; 
	$("[id^='price_']").each(function() {
		var id = $(this).attr('id');
		id = id.replace("price_",'');
		var price = $('#price_'+id).val();
		var cgst = $('#cgst_'+id).val();
		var sgst = $('#sgst_'+id).val();
		var igst = $('#igst_'+id).val();
		var quantity  = $('#quantity_'+id).val();
		if(!quantity) {
			quantity = 1;
		}
		var total = price*quantity;

   
		if(cgst == '')
		{
			cgst = 0;
		}
		if(sgst == '')
		{
			sgst = 0;
		}
		if(igst == '')
		{
			igst = 0;
		}
		var totaltax = parseFloat(cgst) + parseFloat(sgst) + parseFloat(igst);

        var newtotal = total + parseFloat(total*totaltax)/100;
		$('#total_'+id).val(parseFloat(newtotal));
		totalAmount += newtotal;			
	});

	var totalAftertax = parseFloat(totalAmount);
	$('#totalAftertax').val(totalAftertax);	
			
		var amountPaid = $('#amountPaid').val();
		var totalAftertax = $('#totalAftertax').val();	
		var amountdue = totalAftertax-amountPaid;			
		$('#amountDue').val(amountdue);
		
	return true;
}

function checkgstcolumns()
{
	
	if($("#enable_csgst").prop("checked") == true){
		$("body").find(".cgst_text").show();
		$("body").find(".sgst_text").show();
	}
	else if($("#enable_csgst").prop("checked") == false){
		$("body").find(".cgst_text").hide();
		$("body").find(".sgst_text").hide();
		$("body").find(".cgst_input").val('0');
		$("body").find(".sgst_input").val('0');
		calculateTotal();
	}

	if($("#enable_igst").prop("checked") == true){
		$("body").find(".igst_text").show();
	}
	else if($("#enable_igst").prop("checked") == false){
		$("body").find(".igst_text").hide();
		$("body").find(".igst_input").val('0');
		calculateTotal();
	}
}





 
