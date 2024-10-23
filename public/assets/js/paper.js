const baseUrl = new URL('/', location.href).href;
function urli(segment){
	var url = $(location).attr('href');
	var fn = url.split('/').reverse()[segment];
	return fn;
}
var collapsedGroups = {};
const pageURL = $(location).attr("href")
const actualPageArray = pageURL.split("/");
var actualPage = actualPageArray[3];
var xid = 1;
var nid = 0;
var dnd = xid+100000;
var dataCards = new Array();
var toaddCart = new Array();
var checked = 0;
var Table;
if(actualPage == ""){
	actualPage = "home";
}

const checkVal = (arr, val) => {
    let valExist = arr.some(value => value === val);
};



if($("#TabelcpanelStock").length){
	if(typeof $("#TabelcpanelStock").attr('data-status') == 'undefined'){
		var status = '0';
	}
	else {
		var status = $("#TabelcpanelStock").attr('data-status');
	}
	var sectionid = actualPageArray[4];
	Table = $("#TabelcpanelStock").DataTable({
		lengthChange: false,
	    processing: false,
	    bSortable: false,
	    serverSide:true,
	    ajax: {
	        url: baseUrl+'/'+actualPage+'/fetchTableCpanel',
	        type:'POST',
	        data:function(d){
	        	d.hashed = $('input[name="hashed"]').val();
	        	d.id = sectionid;
	        	d.status = status;
	        },
	        dataSrc: function (json) {
                $('input[id="crtoken"]').val(json.csrft);
                return json.data;
            } 
	    },
	    columnDefs: [{
	        className: 'text-left',
	        targets: "_all",
	    }],
	    ordering: false,
	    dom: "ltrip",
	    responsive: true,
	    scrollCollapse: true,	    
	});
}

if($("#TabelrdpStock").length){
	if(typeof $("#TabelrdpStock").attr('data-status') == 'undefined'){
		var status = '0';
	}
	else {
		var status = $("#TabelrdpStock").attr('data-status');
	}
	var sectionid = actualPageArray[4];
	Table = $("#TabelrdpStock").DataTable({
		lengthChange: false,
	    processing: false,
	    bSortable: false,
	    serverSide:true,
	    ajax: {
	        url: baseUrl+'/'+actualPage+'/fetchTableRdp',
	        type:'POST',
	        data:function(d){
	        	d.hashed = $('input[name="hashed"]').val();
	        	d.id = sectionid;
	        	d.status = status;
	        },
	        dataSrc: function (json) {
                $('input[id="crtoken"]').val(json.csrft);
                return json.data;
            } 
	    },
	    columnDefs: [{
	        className: 'text-left',
	        targets: "_all",
	    }],
	    ordering: false,
	    dom: "ltrip",
	    responsive: true,
	    scrollCollapse: true,	    
	});
}

if($("#TabelsmtpStock").length){
	if(typeof $("#TabelsmtpStock").attr('data-status') == 'undefined'){
		var status = '0';
	}
	else {
		var status = $("#TabelsmtpStock").attr('data-status');
	}
	var sectionid = actualPageArray[4];
	Table = $("#TabelsmtpStock").DataTable({
		lengthChange: false,
	    processing: false,
	    bSortable: false,
	    serverSide:true,
	    ajax: {
	        url: baseUrl+'/'+actualPage+'/fetchTableSmtp',
	        type:'POST',
	        data:function(d){
	        	d.hashed = $('input[name="hashed"]').val();
	        	d.id = sectionid;
	        	d.status = status;
	        },
	        dataSrc: function (json) {
                $('input[id="crtoken"]').val(json.csrft);
                return json.data;
            } 
	    },
	    columnDefs: [{
	        className: 'text-left',
	        targets: "_all",
	    }],
	    ordering: false,
	    dom: "ltrip",
	    responsive: true,
	    scrollCollapse: true,	    
	});
}

if($("#TabelOtherStock").length){
	if(typeof $("#TabelOtherStock").attr('data-status') == 'undefined'){
		var status = '0';
	}
	else {
		var status = $("#TabelOtherStock").attr('data-status');
	}
	var sectionid = actualPageArray[4];
	Table = $("#TabelOtherStock").DataTable({
		lengthChange: false,
	    processing: false,
	    bSortable: false,
	    serverSide:true,
	    ajax: {
	        url: baseUrl+'/'+actualPage+'/fetchOthersTable',
	        type:'POST',
	        data:function(d){
	        	d.hashed = $('input[name="hashed"]').val();
	        	d.id = sectionid;
	        	d.status = status;
	        },
	        dataSrc: function (json) {
                $('input[id="crtoken"]').val(json.csrft);
                return json.data;
            } 
	    },
	    columnDefs: [{
	        className: 'text-left',
	        targets: "_all",
	    }],
	    ordering: false,
	    dom: "ltrip",
	    responsive: true,
	    scrollCollapse: true,	    
	});
}

if($("#TabelcardsStock").length){
	if(typeof $("#TabelcardsStock").attr('data-status') == 'undefined'){
		var status = '0';
	}
	else {
		var status = $("#TabelcardsStock").attr('data-status');
	}
	var sectionid = actualPageArray[4];
	Table = $("#TabelcardsStock").DataTable({
		lengthChange: false,
	    processing: false,
	    bSortable: false,
	    serverSide:true,
	    ajax: {
	        url: baseUrl+'/'+actualPage+'/fetchTable',
	        type:'POST',
	        data:function(d){
	        	d.hashed = $('input[name="hashed"]').val();
	        	d.id = sectionid;
	        	d.status = status;
	        },
	        dataSrc: function (json) {
                $('input[id="crtoken"]').val(json.csrft);
                return json.data;
            } 
	    },
	    columnDefs: [{
	        className: 'text-left',
	        targets: "_all",
	    }],
	    ordering: false,
	    dom: "ltrip",
	    responsive: true,
	    scrollCollapse: true,	    
	});
}

/**if($("#TabellistsStock").length){
	if(typeof $("#TabellistsStock").attr('data-status') == 'undefined'){
		var status = '0';
	}
	else {
		var status = $("#TabellistsStock").attr('data-status');
	}
	var sectionid = actualPageArray[4];
	Table = $("#TabellistsStock").DataTable({
		lengthChange: false,
	    processing: false,
	    bSortable: false,
	    serverSide:true,
	    ajax: {
	        url: baseUrl+'/'+actualPage+'/fetchTable',
	        type:'POST',
	        data:function(d){
	        	d.hashed = $('input[name="hashed"]').val();
	        	d.id = sectionid;
	        	d.status = status;
	        },
	        dataSrc: function (json) {
                $('input[id="crtoken"]').val(json.csrft);
                return json.data;
            } 
	    },
	    columnDefs: [{
	        className: 'text-left',
	        targets: "_all",
	    }],
	    ordering: false,
	    dom: "ltrip",
	    responsive: true,
	    scrollCollapse: true,	    
	});
}**/

if($("#Tabellists").length){
	Table = $("#Tabellists").DataTable({
		lengthChange: false,
	    processing: false,
	    ajax: {
	        url: baseUrl+'baselist/fetchTable?base='+$("#baseName").val(),
	    },
	    columnDefs: [{
	        className: 'text-left',
	        targets: "_all",
	    },],
	    ordering: false,
	    dom: "ltrip",
	    responsive: true,
	    scrollCollapse: true,	    
	});
}

if($("#TabelStock").length){
	if(actualPageArray.length == 5){
		var toUrl = baseUrl+'/'+actualPage+'/fetchTable?id='+actualPageArray[4];
		var typeRequest = 'GET';
	}
	else {
		var toUrl = baseUrl+'/'+actualPage+'/fetchTable';
		var typeRequest = 'POST';
	}
	if(actualPage == 'cards' || actualPage == 'Cards' || 
		actualPage == 'cpanel' || actualPage == 'Cpanel' ||
		actualPage == 'Rdp' || actualPage == 'rdp' ||
		actualPage == 'Smtp' || actualPage == 'smtp' ||
		actualPage == 'Shell' || actualPage == 'shell' ||
		actualPage == 'sellerdashboard' || actualPage == 'Sellerdashboard' || 
		actualPage == 'myreferals' || actualPage == 'Myreferals' || 
		actualPage == 'codes' || actualPage == 'Codes'
		){
		Table = $("#TabelStock").DataTable({
			lengthChange: false,
		    processing: false,
		    bSortable: false,
		    serverSide:true,
		    ajax: {
		        url: toUrl,
		        type:typeRequest,
		        data:function(d){
		        	d.hashed = $('input[name="hashed"]').val()
		        },
		        dataSrc: function (json) {
	                $('input[id="crtoken"]').val(json.csrft);
	                return json.data;
	            } 
		    },
		    columnDefs: [{
		        className: 'text-left',
		        targets: "_all",
		    }],
		    ordering: false,
		    dom: "ltrip",
		    responsive: true,
		    scrollCollapse: true,	    
		});
	}
	else {
		Table = $("#TabelStock").DataTable({
			lengthChange: false,
		    processing: false,
		    ajax: {
		        url: toUrl,
		    },
		    columnDefs: [{
		        className: 'text-left',
		        targets: "_all",
		    },],
		    ordering: false,
		    dom: "ltrip",
		    responsive: true,
		    scrollCollapse: true,	    
		});
	}
	/**$('table').on('click', function(e){
      	if($('[data-bs-toggle="popover"]').length>1){
	      	$('[data-bs-toggle="popover"]').popover({
	      	 	html: true, 
				content: function() {
		          	return $('#popover-content').html();
		        }
      		});
      	}
    });**/
    /**$(document).on('blur','button[data-bs-toggle="popover"]', function() {
	   $(this).popover('hide');
	});**/
}
else {
	if(actualPageArray[3] == 'market'){
		$.ajax({
			url: baseUrl+'/market/fetchCards?id='+actualPageArray[4],
			type: 'GET',
			dataType: 'json',
			success: function(response){
				$("#productsCards").html(response.html);
			}
		})
	}
}

function is_searchable(Table, htmlTable){
	Table.destroy();
	if(actualPageArray.length == 5){
		var toUrl = baseUrl+'/'+actualPage+'/fetchTable?id='+actualPageArray[4];
		var typeRequest = 'GET';
	}
	else {
		var toUrl = baseUrl+'/'+actualPage+'/fetchTable';
		var typeRequest = 'POST';
	}
	if(actualPage == 'cards' || actualPage == 'Cards' || 
		actualPage == 'sellerdashboard' || actualPage == 'Sellerdashboard' || 
		actualPage == 'myreferals' || actualPage == 'Myreferals' || 
		actualPage == 'codes' || actualPage == 'Codes'
	){
		Table = $(htmlTable).DataTable({
			lengthChange: false,
		    processing: false,
		    bSortable: false,
		    serverSide:true,
		    ajax: {
		        url: toUrl,
		        type:typeRequest,
		        data:function(d){
		        	d.hashed = $('input[name="hashed"]').val()
		        },
		        dataSrc: function (json) {
	                $('input[id="crtoken"]').val(json.csrft);
	                return json.data;
	            } 
		    },
		    columnDefs: [{
		        className: 'text-left',
		        targets: "_all",
		    }],
		    ordering: false,
		    dom: "ltrip",
		    responsive: true,
		    scrollCollapse: true,	    
		});
	}
	else {
		Table = $(htmlTable).DataTable({
			lengthChange: false,
		    processing: false,
		    ajax: {
		        url: toUrl,
		    },
		    columnDefs: [{
		        className: 'text-left',
		        targets: "_all",
		    },],
		    ordering: false,
		    dom: "ltrip",
		    responsive: true,
		    scrollCollapse: true,	    
		});
	}
	/**else {
		if(actualPageArray[3] == 'market'){
			$.ajax({
				url: baseUrl+'/market/fetchCards?id='+actualPageArray[4],
				type: 'GET',
				dataType: 'json',
				success: function(response){
					$("#productsCards").html(response.html);
				}
			})
		}
	}**/
}

var tocheck = new Array();

function removeDuplicates(arr) {
    return arr.filter((item, index) => arr.indexOf(item) === index);
}

function getCheckRecords() {
	$('.chk').each(function(){
		if ($(this).prop('checked')) {
			tocheck.push($(this).attr("data-id"))
		}
		else {
			var removeItem = $(this).attr("data-id");
			tocheck = $.grep(tocheck, function(value) {
			  	return value != removeItem;
			});
		}
	});

	tocheck = removeDuplicates(tocheck);
	if($('.chk:checked').length != 0){
		var checkbtn = '<button type="btn" class="btn btn-sm btn-success" id="checkerbtn" data-api="checkselected">Check Selected</button>';
		$("#checkplace").html(checkbtn);
	}
	else {
		$("#checkplace").html('');
	}
}

$("#chekall").change(function () {
	if ($(this).prop('checked')) {
		$('input[type="checkbox"]').not(this).prop('checked', true);
	} 
	else {
		$('input[type="checkbox"]').not(this).prop('checked', false);
	}
	getCheckRecords();
})

$('table').on('change', '.chk', function () {

	if ($('.chk:checked').length == $('.chk').length) {
		$('#chekall').prop('checked', true);
	} 
	else {
		$('#chekall').prop('checked', false);
	}
	getCheckRecords();
	});

function checkselected(){
	$("#checkerbtn").remove()
	var prgs = '<div class="card pd-20">\
				<h6 class="tx-12 tx-uppercase tx-inverse tx-bold mg-b-15 statuschecks">Checker Status</h6>\
				<div class="d-flex mg-b-10">\
					<div class="bd-r pd-r-10">\
						<label class="tx-12">Total to check</label>\
						<p class="tx-lato tx-inverse tx-bold" id="totaltocheknum">...</p>\
					</div>\
					<div class="bd-r pd-x-10">\
						<label class="tx-12">Checked</label>\
						<p class="tx-lato tx-inverse tx-bold" id="checkeds">...</p>\
					</div>\
					<div class="bd-r pd-x-10">\
						<label class="tx-12">Remaining</label>\
						<p class="tx-lato tx-inverse tx-bold" id="remi">...</p>\
					</div>\
					<div class="bd-r pd-x-10">\
						<label class="tx-12">Approved</label>\
						<p class="tx-lato tx-inverse tx-bold" id="approved">...</p>\
					</div>\
					<div class="bd-r pd-x-10">\
						<label class="tx-12">Refunded</label>\
						<p class="tx-lato tx-inverse tx-bold" id="refunded">...</p>\
					</div>\
				</div><!-- d-flex -->\
				<div class="progress mg-b-10">\
					<div class="progress-bar progress-bar-striped progress-bar-animated" id="chekprgs" style="width:0px"  role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>\
				</div>\
			</div>';
	$("#prgs").html(prgs);
	let lastResponseLength = false;
	$(".chk:checked").parent().parent().find('.ccstatus').removeClass('btn-info');
	$(".chk:checked").parent().parent().find('.ccstatus').addClass('btn-warning');
	$(".chk:checked").parent().parent().find('.ccstatus').html('Cheking...<span class="bx bx-sync bx-spin"></span>');
	$(".statuschecks").html('Cheking...<span class="bx bx-sync bx-spin"></span>')

	xhr = new XMLHttpRequest();

	xhr.open("POST", baseUrl+'/'+actualPage+'/checker', true);
	xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhr.setRequestHeader("Accept", "application/json");
	xhr.onprogress = function(e) {
   let progressResponse;
   let response = e.currentTarget.response;

   progressResponse = lastResponseLength ?  response.substring(lastResponseLength) : response;

   lastResponseLength = response.length;
   let parsedResponse = JSON.parse(progressResponse);
   //let parsedResponse = parsedResponse;
   if(parsedResponse.result == "1"){
   		$("#"+parsedResponse.id).removeClass('btn-warning');
   		$("#"+parsedResponse.id).addClass('btn-success');
   		$("#"+parsedResponse.id).html('Approved');
   		$("#"+parsedResponse.id).parent().parent().find('input[type="checkbox"]').remove();
   		$("#ubalance").html(parsedResponse.balance);
   }
   else if(parsedResponse.result == "3"){
   		$("#"+parsedResponse.id).removeClass('btn-warning');
   		$("#"+parsedResponse.id).addClass('btn-info');
   		$("#"+parsedResponse.id).html('Network Error');
   }
   else {
   		$("#"+parsedResponse.id).removeClass('btn-warning');
   		$("#"+parsedResponse.id).addClass('btn-danger');
   		$("#"+parsedResponse.id).html('Refunded');
   		$("#"+parsedResponse.id).parent().parent().find('input[type="checkbox"]').remove();
   		var balance =  $("#ubalance").html();
   		//var newbalace = parseFloat(balance)+parseFloat(parsedResponse.balance);
   		//$("#ubalance").html(newbalace.toLocaleString('en-US'));
   		$("#ubalance").html(parsedResponse.balance);
   }
   //console.log(parsedResponse);
   $("#chekprgs").attr('aria-valuenow',parsedResponse.progress);
   $("#chekprgs").css('width',parsedResponse.progress+'%');
   $("#chekprgs").html(parsedResponse.progress+'%');
   $("#totaltocheknum").html(parsedResponse.total);
   $("#checkeds").html(parsedResponse.x+" ("+parsedResponse.progress+"%)");
   $("#remi").html(parseFloat(parsedResponse.total) - parseFloat(parsedResponse.x));
   $("#approved").html(parsedResponse.good);
   $("#refunded").html(parsedResponse.bad);
   		
   
   //console.log(parsedResponse.id);

   //if(Object.prototype.hasOwnProperty.call(parsedResponse, 'success')) {
       // handle process success
   //}
}
	xhr.onreadystatechange = function() {
   if (xhr.readyState == 4 && this.status == 200) {
   		$("#chekprgs").removeClass('progress-bar-animated');
       	$("#done").html('Checker Done.');
       	$(".statuschecks").html('Cheking Done.')
   }
}
	xhr.send("ids="+JSON.stringify(tocheck));
}

if($("#TabelSelled").length){
	if(actualPageArray.length == 5){
		var toUrl = baseUrl+'/'+actualPage+'/fetchTable?id='+actualPageArray[4];
	}
	else {
		var toUrl = baseUrl+'/'+actualPage+'/fetchTableSelled';
	}
	var TabelSelled = $("#TabelSelled").DataTable({
		lengthChange: false,
	    processing: false,
	    ajax: {
	        url: toUrl,
	    },
	    //dom: 'Pfrtip',
	    columnDefs: [{
	        className: 'text-center',
	        targets: "_all",
	    }],
	    ordering: false,
	    dom: "ltrip",
	    responsive: true,
	    scrollCollapse: true,
	});
}

if($("#TabelRefunded").length){
	if(actualPageArray.length == 5){
		var toUrl = baseUrl+'/'+actualPage+'/fetchTable?id='+actualPageArray[4];
	}
	else {
		var toUrl = baseUrl+'/'+actualPage+'/fetchTableRefunded';
	}
	var TabelRefunded = $("#TabelRefunded").DataTable({
		lengthChange: false,
	    processing: true,
	    ajax: {
	        url: toUrl,
	    },
	    //dom: 'Pfrtip',
	    columnDefs: [{
	        className: 'text-center',
	        targets: "_all",
	    }],
	    ordering: false,
	    dom: "ltrip",
	    responsive: true,
	    scrollCollapse: true,
	});
}

function calc() {
	if(actualPage == 'cart'){
		var totals = Table.column(2).data().sum();
		$("#sum").html('$'+totals+'.00');
	}
}

$(document).on("click","button, a, span", function () {
	
	if ($(this).attr('data-api')) {
	    var dataAPI = $(this).data("api");
	    if(dataAPI.indexOf('-') !== -1){
	       if(dataAPI.split('-')[1] == 'this'){
	            eval(dataAPI.split('-')[0])(this);	 
	       }
	       else {
                eval(dataAPI.split('-')[0])(dataAPI.split('-')[1]);	    
	       }
	    }
	    else if(dataAPI.indexOf('-') !== -1){
	    }
	    else {
	    	eval(dataAPI)();	
	    }
	}
});

$(document).on("keyup","input", function () {
	if ($(this).attr('data-api')) {
	    var dataAPI = $(this).data("api");
	    if(dataAPI.indexOf('-') !== -1){
	    	eval(dataAPI.split('-')[0])(dataAPI.split('-')[1]);	
	    }
	    else {
	    	eval(dataAPI)();	
	    }	
	}
	else {
		$(this).removeClass("is-invalid");
		var mclass= $(this).attr("id");
		$("."+mclass).removeClass("is-invalid");
		$("."+mclass).html("");
	}
});

$(document).on("click","div", function () {
	if ($(this).attr('data-api')) {
	    var dataAPI = $(this).data("api");
	    if(dataAPI.indexOf('-') !== -1){
	    	eval(dataAPI.split('-')[0])(dataAPI.split('-')[1]);	
	    }
	    else {
	    	eval(dataAPI)();	
	    }	
	}
});

$(document).on("change","select", function () {
	if ($(this).attr('data-api')) {
	    var dataAPI = $(this).data("api");
	    if(dataAPI.indexOf('-') !== -1){
	    	if(dataAPI.split('-')[1] == 'this'){
	            eval(dataAPI.split('-')[0])(this);	 
	       }
	       else {
                eval(dataAPI.split('-')[0])(dataAPI.split('-')[1]);	    
	       }
	    }
	    else if(dataAPI.indexOf('-') !== -1){
	    }
	    else {
	    	eval(dataAPI)();	
	    }
	   	
	}
});

$(document).on("click",'input[type="checkbox"]', function () {
	if ($(this).attr('data-api')) {
	    var dataAPI = $(this).data("api");
	    if(dataAPI.indexOf('-') !== -1){
	    	if(dataAPI.split('-')[1] == 'this'){
	            eval(dataAPI.split('-')[0])(this);	 
	       }
	       else {
                eval(dataAPI.split('-')[0])(dataAPI.split('-')[1]);	    
	       }
	    }
	    else if(dataAPI.indexOf('-') !== -1){
	    }
	    else {
	    	eval(dataAPI)();	
	    }
	   	
	}
});


function getStoks(){
	if(actualPage == 'pages'){
		$.ajax({
			url : baseUrl+actualPage+'/fetchTable',
			dataType: 'json',
			type: 'GET',
			success: function(html){
				$("#prods").html(html);
			}
		})
	}
}

function getNews(){
	if(actualPage == 'home' || actualPage == 'faq'){
		$.ajax({
			url : baseUrl+actualPage+'/fetchTable',
			dataType: 'json',
			type: 'GET',
			success: function(html){
				$("#news").html(html);
			}
		})
	}
}

function refreshCart(){
	if(actualPage == 'cart'){
		$.ajax({
			url : baseUrl+actualPage+'/refreshCart',
			dataType: 'json',
			type: 'GET',
			success: function(html){
				$("#sum").html(html.total);
			}
		})
	}
}

function addvalidruls(c){
	var s = c.split('|')[0];
	var n = c.split('|')[1];
	if(!$("#fieldsnames"+n).length){
		var forfieldname = 'ChangeMe'
	}
	else {
		var forfieldname = $("#fieldsnames"+n).val();
	}
	var selectdelem = $('#'+s+' option:selected').val();	
	var critrarynames = ['exact_length',
							'greater_than',
							'greater_than_equal_to',
							'less_than',
							'less_than_equal_to',
							'max_length',
							'min_length',
							'regex_match',
							'matches',
							'is_not_unique',
							'is_unique',
							'uploaded',
							'max_size',
							'max_dims',
							'mime_in',
							'ext_in',
							'is_image',
						];
	if(critrarynames.indexOf(selectdelem) != -1){
		switch(selectdelem){
			case 'exact_length' :
				var textreg = '[50]';
			break;
			case 'greater_than' :
				var textreg = '[50]';
			break;
			case 'greater_than_equal_to' :
				var textreg = '[50]';
			break;
			case 'less_than' :
				var textreg = '[50]';
			break;
			case 'less_than_equal_to' :
				var textreg = '[50]';
			break;
			case 'max_length' :
				var textreg = '[50]';
			break;
			case 'min_length' :
				var textreg = '[50]';
			break;
			case 'regex_match' :
				var textreg = '[/^([a-zA-Z0-9])+$/i]';
			break;
			case 'matches' :
				var textreg = '[Anyother]';
			break;
			case 'is_not_unique' :
				var textreg = '['+$("#sectionname").val().toLowerCase()+'.'+forfieldname+']';
			break;
			case 'is_unique' :
				var textreg = '['+$("#sectionname").val().toLowerCase()+'.'+forfieldname+']';
			break;
			case 'is_image' :
				var textreg = '['+forfieldname+']';
			break;
			case 'ext_in' :
				var textreg = '['+forfieldname+',png,jpg,gif]';
			break;
			case 'mime_in' :
				var textreg = '['+forfieldname+', image/png, image/jpg, image/jpeg]';
			break;
			case 'max_dims' :
				var textreg = '['+forfieldname+',300,150]';
			break;
			case 'max_size' :
				var textreg = '['+forfieldname+',4096]';
			break;
			case 'uploaded' :
				var textreg = '['+forfieldname+']';
			break;



		} 
		//var html = '<input type="text" name="inputs[][r_'+critrarynames[critrarynames.indexOf(selectdelem)]+']" class="form-control" value="'+textreg+'">';
		//console.log($('#'+s).parent().parent().children(1).children().children('input[type="text"]'))
		$('#'+s).parent().parent().children(1).children().children('input[type="text"]').prop('disabled', false);
		$('#'+s).parent().parent().children(1).children().children('input[type="text"]').removeClass('disabled');
		$('#'+s).parent().parent().children(1).children().children('input[type="text"]').val(textreg);
	}
	else {
		$('#'+s).parent().parent().children(1).children().children('input[type="text"]').prop('disabled', true);
		$('#'+s).parent().parent().children(1).children().children('input[type="text"]').addClass('disabled');
		$('#'+s).parent().parent().children(1).children().children('input[type="text"]').val('');
	}
}

function setval(d){
	$('#inputsvalidation'+d+' option:selected').val($('#inputsvalidation'+d+' option:selected').data('ruls')+'['+ $('#'+d).val()+']');
}

function MyBeforSend(){
	$(":input,:button,:checkbox").addClass('disabled');
    $(":input,:button,:checkbox").prop('disabled', true);
    $('input').removeClass('is-valid');
    $('input').removeClass('is-invalid');
}

function MyAfterSend(QuerySuccessType, response, additionals = []){
	$(":input,:button,:checkbox").removeClass('disabled');
    $(":input,:button,:checkbox").prop('disabled', false);
	switch(QuerySuccessType){
		case 'bsmodel':
			if(typeof(response.fieldslist) != 'undefined'){
				MyArrayOfFields = response.fieldslist;
				ArrayOfFields = Object.keys(MyArrayOfFields);
				ArrayOfValues = Object.values(MyArrayOfFields);
				$.each(ArrayOfFields, function(key, fieldlist){
				    if(fieldlist.indexOf(".*") != -1){
				        fieldlist = fieldlist.replace(".*", "");
				    }
					$("#"+fieldlist).addClass('is-invalid');
					$("."+fieldlist).html(ArrayOfValues[key]);

					var html = '<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">'+
									'<div class="d-flex align-items-center">'+
										'<div class="font-35 text-white"><i class="bx bxs-message-square-x"></i>'+
										'</div>'+
										'<div class="ms-3">'+
											'<h6 class="mb-0 text-white">Error !</h6>'+
											'<div class="text-white">'+ArrayOfValues[key]+'</div>'+
										'</div>'+
									'</div>'+
									'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'+
								'</div>';
					$(".ErrorLogin").html(html);
					$("#ns").html(response.nn);
					$("#cap").attr('placeholder', response.nn);
					$("#cap").val('');
				});
				if(actualPage.split('#')[0] == 'wizzard' || actualPage == 'Wizzard'){
					$('.modal-backdrop').remove();
			        $('<div>').attr({id: 'modalcontiner'}).appendTo("body");
			        $("#modalcontiner").html(response.modal);
			        if($("#bsModal").length){
			            var myModal = new bootstrap.Modal(document.getElementById('bsModal'), {
	    	  				keyboard: false,
	    	  				backdrop: "static",
	    				});
	    				myModal.show(); 
			        }
				}	
			}
			else {
		        $('.modal-backdrop').remove();
		        $('<div>').attr({id: 'modalcontiner'}).appendTo("body");
		        $("#modalcontiner").html(response.modal);
		        if($("#bsModal").length){
		        	
		            var myModal = new bootstrap.Modal(document.getElementById('bsModal'), {
    	  				keyboard: false,
    	  				backdrop: "static",
    				});
    				myModal.show(); 
		        }
				if(additionals[0] == "1"){
					if(actualPage != 'pages' && actualPage != 'home'){
						Table.ajax.reload(null, false);
					}
					else if(actualPage == 'pages'){
						getStoks();	
					}
					else if(actualPage == 'home' || actualPage == 'faq'){
						getNews();
					}
					
				}
				if(additionals[1] == "1"){
				    var divName = additionals[2];
				    $("#"+divName).html(response.html);
				}
			}
        break;
        case 'sweetalert':

        	if(typeof(response.fieldslist) != 'undefined'){
				MyArrayOfFields = response.fieldslist;
				ArrayOfFields = Object.keys(MyArrayOfFields);
				ArrayOfValues = Object.values(MyArrayOfFields);
				$.each(ArrayOfFields, function(key, fieldlist){
				    if(fieldlist.indexOf(".*") != -1){
				        fieldlist = fieldlist.replace(".*", "");
				    }
					$("#"+fieldlist).addClass('is-invalid');
					$("."+fieldlist).html(ArrayOfValues[key]);
				});
			}
			else {
				/**Lobibox.notify(''+response.typemsg+'', {
					pauseDelayOnHover: true,
					size: response.size,
					icon: response.icone,
					continueDelayOnInactiveTab: false,
					position: response.position,
					msg: response.message,
					sound: response.sounds
				});**/
				if(additionals[0] == "1"){

					if(actualPage != 'pages'){
						Table.ajax.reload(null, false);
					}
					if(additionals[1]){
						eval(additionals[1])();
					}
					if(actualPage != 'history'){
						if(response.signal == '0'){
							$("#sum").html('$0.00');
							$("#clearer").remove();
    			        	$("#nbcrt").remove();
    			        	$("#acart").removeClass('with-cart');
						}
						else {
							$("#sum").html(response.total);
							getStoks();
						}
					}	
				}
				else {

					var countcart = $(".cart-count").html();
					var actions = response.html.split('|');
					if(actions[0] == "-1"){
						var nowcount = parseFloat(countcart) - 1;
						$("."+actions[1]).remove();
					}
					else {
						var nowcount = parseFloat(countcart) + 1;
						$(".cart-list").append(response.html);
					}		
					$(".cart-count").html(nowcount);
					var html = '<p class="msg-header-title">My Cart</p><a href="javascript:void(0);" class="msg-header-clear ms-auto" data-api="clearCart">Remove All</a>';
					$(".msg-header").html(html);
					//var chekoutbtn = '<a href="'+baseUrl+'cart"><div class="text-center msg-footer">To Check Out</div></a>';	
					//$("#checkoutdiv").html(chekoutbtn);
					//calc();
					getStoks();
					refreshCart();	
				}
			}
				
        break;
        case 'return' :
            if(typeof(response.fieldslist) != 'undefined'){
				MyArrayOfFields = response.fieldslist;
				ArrayOfFields = Object.keys(MyArrayOfFields);
				ArrayOfValues = Object.values(MyArrayOfFields);
				$.each(ArrayOfFields, function(key, fieldlist){
				    if(fieldlist.indexOf(".*") != -1){
				       fieldlist = fieldlist.replace(".*", "");
				    }
					$("#"+fieldlist).addClass('is-invalid');
				});
			}
			else {
			    var mstyle = response.mstyle
    			$("#tempName").val(response.tempName);
    			$("#speach").val(response.speech);
    			$('#lang option[value='+response.lang+']').prop('selected', true);
    			getstyles(mstyle);
    			//$('#styles option[data-id='+mstyle+']').prop('selected', true);
    			$('#styles option[data-id='+mstyle+']').attr("selected", "selected");
    			//console.log($('#styles option[data-id='+mstyle+']').prop('selected', true));
			}
        break;
        case 'msg':
        	if(typeof(response.fieldslist) != 'undefined'){
				MyArrayOfFields = response.fieldslist;
				ArrayOfFields = Object.keys(MyArrayOfFields);
				ArrayOfValues = Object.values(MyArrayOfFields);
				$.each(ArrayOfFields, function(key, fieldlist){
				    if(fieldlist.indexOf(".*") != -1){
				       fieldlist = fieldlist.replace(".*", "");
				    }
					$("#"+fieldlist).addClass('is-invalid');
					$("."+fieldlist).html(ArrayOfValues[key]);
				});
			}
			else {
				$("#message").removeClass('is-invalid')
	        	var html = '<li class="p-3" style="border-bottom: 1px solid #ccc;">\
			        <div class="media">\
			            <div class="media-body">\
			                <p class="media-heading mb-0">\
			                    '+response.username+'\
			                    <i class="fa fa-circle text-success mr-1 pull-left pt-1"></i>\
			                    <small class="pull-right text-muted">\
			                        '+response.thedate+'\
			                    </small>\
			                </p>\
			                <br/>\
			                <p>'+response.message+'</p>\
			            </div>\
			        </div>\
			    </li>';
				$(".receptmsg").append(html);
				$("#message").val('');
				var thedivscroll = document.getElementById('boxscroll2');
				thedivscroll.scrollTop = thedivscroll.scrollHeight;	
			}
        break;
        case 'div' :
            if(typeof(response.fieldslist) != 'undefined'){
				MyArrayOfFields = response.fieldslist;
				ArrayOfFields = Object.keys(MyArrayOfFields);
				ArrayOfValues = Object.values(MyArrayOfFields);
				$.each(ArrayOfFields, function(key, fieldlist){
				    if(fieldlist.indexOf(".*") != -1){
				       fieldlist = fieldlist.replace(".*", "");
				    }
					$("#"+fieldlist).addClass('is-invalid');
					//$("."+fieldlist).html(ArrayOfValues[key]);
				});
			}
			else {
    			if(additionals[0] == "1"){
    				if(typeof Table !== 'undefined'){
    					Table.ajax.reload(null, false);
    				}
    				else {
    					window.location.reload();
    				}
    			}
    			if(additionals[1] == "1"){
    			    var divName = additionals[2];
    			    $("#"+divName).html(response.html);
    			    if(response.signal == '1'){
    			        var phtml = '<span class="square-8 bg-danger pos-absolute t-15 r-0 rounded-circle" id="nbcrt"></span>';
    			        var ahtml = '<a href="javascript:void(0);" class="" data-api="clearCart" id="clearer">Remove All</a>';
    			        $("#carticon").html(phtml);
    			        $("#clearme").html(ahtml);
    			        $("#chekall").prop('checked', false);
    			        $("#addtocartdiv").children().remove();
    			        $("#addtocartdiv").parent().addClass('d-none');
    			        if(typeof $("#sum") !== 'undefined'){
    			        	$("#sum").html('$0.00');
    			        }
    			        toaddCart = new Array();
    			        if(typeof Table !== 'undefined'){
	    					Table.ajax.reload(null, false);
	    				}
	    				else {
	    					window.location.reload();
	    				}
    			    }
    			    else if(response.html == '') {
    			    	$("#clearer").remove();
    			        $("#nbcrt").remove();
    			        $("#chekall").prop('checked', false);
    			        $("#addtocartdiv").children().remove();
    			        $("#addtocartdiv").parent().addClass('d-none');
    			        toaddCart = new Array();
    			        if(typeof $("#sum") !== 'undefined'){
    			        	$("#sum").html('$0.00');
    			        }
    			        $("#acart").removeClass('with-cart');
						if(typeof Table !== 'undefined'){
	    					Table.ajax.reload(null, false);
	    				}
	    				else {
	    					window.location.reload();
	    				}
    			    }
    			}
			}
	    break;
	    case 'redirect' :
            if(typeof(response.fieldslist) != 'undefined'){
				MyArrayOfFields = response.fieldslist;
				ArrayOfFields = Object.keys(MyArrayOfFields);
				ArrayOfValues = Object.values(MyArrayOfFields);
				
				$.each(ArrayOfFields, function(key, fieldlist){
				    if(fieldlist.indexOf(".*") != -1){
				       fieldlist = fieldlist.replace(".*", "");
				    }
				    if(fieldlist == "captcha"){
				    	$('label[for="captcha"]').html(response.fieldslist.newcaptcha);
				    }
					$("#"+fieldlist).addClass('is-invalid');
					$("."+fieldlist).html(ArrayOfValues[key]);
					$("."+fieldlist).addClass('invalid-feedback');
				});
			}
			else {
				$("input").removeClass('is-invalid');
				$("input").addClass('is-valid');
				g = document.createElement('div');
				g.setAttribute("id", "modalcontiner");
				$("body").append(g);
				$('.modal-backdrop').remove();
		        $("#modalcontiner").html(response.modal);
		        if($("#bsModal").length){
		            var myModal = new bootstrap.Modal(document.getElementById('bsModal'), {
    	  				keyboard: false,
    	  				backdrop: "static",
    				});
    				myModal.show(); 
		        }
		        setTimeout(function(){
		        	window.location.assign(baseUrl);	
		        }, 3000);
    			
			}
	    break;
	    case 'alertlogin':
	    	var html = '<div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">'+
							'<div class="d-flex align-items-center">'+
								'<div class="font-35 text-white"><i class="bx bxs-check-circle"></i>'+
								'</div>'+
								'<div class="ms-3">'+
									'<h6 class="mb-0 text-white">Success !</h6>'+
									'<div class="text-white">'+response.html+'</div>'+
								'</div>'+
							'</div>'+
							'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'+
						'</div>';
			$(".ErrorLogin").html(html);
			//$("#ns").html(response.nn);
			//$("#cap").attr('placeholder', response.nn);
			//$("#cap").val('');

	}
}

function Myajax(AjaxType = '' ,QuaryType ="", QueryUrl="", QueryForm="", QueryDataType="", QuerySuccessType ="", additionals = []){
	var data = '';
	if(QueryForm != ""){
		switch(AjaxType){
			case 'Serialize':
				data = $("#"+QueryForm).serialize();
			break;
			case 'FileUpload':
				var form = $("#"+QueryForm)[0];
				data = new FormData(form);
				data.append("hashed",$('input[name="hashed"]').val());
			break;
			case 'Aserialize':
				data = $("#"+QueryForm).serializeArray();
				data.push({name: "hashed", value:$('input[name="hashed"]').val()});
			break;
		}
	}
	if(QuaryType == ''){
		QuaryType = 'GET';
	}
	MyBeforSend();
	switch(AjaxType){
		case 'FileUpload':
			$.ajax({
				type: QuaryType,
				url: QueryUrl,
				data: data,
				dataType: "json",
		        cache: !1,
		        contentType: !1,
		        processData: !1,
				dataType: QueryDataType,
				success:function(response){
					$('input[name="hashed"]').val(response.csrft);
					MyAfterSend(QuerySuccessType, response, additionals);	        
			        
				}
			});		
        break;
        case 'Serialize' :
        case 'Aserialize' :
    		$.ajax({
				type: QuaryType,
				url: QueryUrl,
				data: data,
				dataType: QueryDataType,
				success:function(response){
					$('input[name="hashed"]').val(response.csrft);
					MyAfterSend(QuerySuccessType, response, additionals);        
			        
				}
			});
        break;
        default :
        	$.ajax({
				type: QuaryType,
				url: QueryUrl,
				data: AjaxType,
				dataType: QueryDataType,
				success:function(response){
					$('input[name="hashed"]').val(response.csrft);
					MyAfterSend(QuerySuccessType, response, additionals);
				}
			});
		break;
	}
}

/**-------------------Script Start----------------------**/
function offnotif(){
	var data = 'hashed='+$('input[name="hashed"]').val();		
	$.ajax({
		type:'POST',
		url:baseUrl+'/notifications/offnotifs',
		data:data,
		dataType:'json',
		success: function(success){
			if(success.notifs == "1"){
				$("#nbnt").remove();
			}
			$('input[name="hashed"]').val(success.csrft);
		}
	});	
}

$('.closer').click(function(){
	$('.notiftoggle').dropdown('hide');
})
$('.closer').click(function(){
	$('.notiftoggle').dropdown('hide');
})
/**function getToken(){
	Myajax('Serialize','GET', baseUrl+'/Getoken', '', 'JSON', 'bsmodel');
}**/
$(document).ready(function() {
	//console.log($('.tox-tinymce-aux').html())
	$('.tox-tinymce-aux').remove();
    $('.tox-notifications-container').remove();
    if(actualPage.split('?')[0] == 'mytiket'){
        new PerfectScrollbar('#ps-mid');
    }

	initselect();
	if(actualPage == 'cards' || actualPage == 'Cards' || actualPage == 'sellerdashboard' || actualPage == 'Sellerdashboard'){
		
		$('#TabelStock tbody').on('click', 'tr.group-start', function () {
			var name = $(this).data('name');
			collapsedGroups[name] = !collapsedGroups[name];
			Table.draw(false);
		}); 

		$("#pricerange").ionRangeSlider({
	        type: "double",
	        grid: true,
	        min: 0,
	        max: 100,
	        from: 0,
	        to: 100,
	        skin: "square",
	        prefix: "$",
	    });
	}
		
	if(actualPage.split('#')[0] == 'wizzard' || actualPage.split('#')[0] == 'Wizzard'){
		initWiz();
		//$('#s2c').floatingScroll();
		//$('#s3c').floatingScroll();
		//$('#s2c').perfectScrollbar();
		//$('#s3c').perfectScrollbar();
		var ps = new PerfectScrollbar('#s2c');
		var ps3 = new PerfectScrollbar('#s3c');
	}
    if(actualPage == "settings" || actualPage == 'Settings'){
    	$('.list-group-item').click(function(){
    		$('.list-group-item').removeClass('bg-success');
			$('.list-group-item').addClass('bg-transparent');
			$(this).addClass('bg-success');
			$(this).removeClass('bg-transparent');
    	});
		var checkertouse = $("#checkerused").val();
		switch(checkertouse){
			case '1':
				$(".luxo").removeClass('d-none');
				$("#luxorcheckeruser").attr('disabled', false);
				$("#luxorchecjerapi").attr('disabled', false);
				$(".chk").addClass('d-none');
				$("#ccdotcheckapi").attr('disabled', true);
			break;
			case '2':
				$(".chk").removeClass('d-none');
				$("#ccdotcheckapi").attr('disabled', false);
				$(".luxo").addClass('d-none');
				$("#luxorcheckeruser").attr('disabled', true);
				$("#luxorchecjerapi").attr('disabled', true);
			break;
			case '3':
				$(".luxo").removeClass('d-none');
				$("#luxorcheckeruser").attr('disabled', false);
				$("#luxorchecjerapi").attr('disabled', false);
				$(".chk").removeClass('d-none');
				$("#ccdotcheckapi").attr('disabled', false);
			break;
		}
		var getwaypayment = $("#payementgetway").val();
		switch(getwaypayment){
			case '1':
				$("#nowp").removeClass('d-none');
				$("#nowpayementapikey").attr('disabled', false);
				$("#nowpaymentaccept").attr('disabled', false);
				$("#coinp").addClass('d-none');
				$("#blokp").addClass('d-none');
				$("#coinpayementmerchen").attr('disabled', true);
				$("#coinpayementipn").attr('disabled', true);
				$("#coinpayementapi").attr('disabled', true);
				$("#coinpayementsecret").attr('disabled', true);
				$("#blockonomicsapi").attr('disabled', true);
			break;
			case '2':
				$("#coinp").removeClass('d-none');
				$("#coinpayementmerchen").attr('disabled', false);
				$("#coinpayementipn").attr('disabled', false);
				$("#coinpayementapi").attr('disabled', false);
				$("#coinpayementsecret").attr('disabled', false);
				$("#nowp").addClass('d-none');
				$("#blokp").addClass('d-none');
				$("#nowpayementapikey").attr('disabled', true);
				$("#nowpaymentaccept").attr('disabled', true);
				$("#blockonomicsapi").attr('disabled', true);
			break;
			case '3':
				$("#blokp").removeClass('d-none');
				$("#blockonomicsapi").attr('disabled', false);
				$("#coinp").addClass('d-none');
				$("#nowp").addClass('d-none');
				$("#coinpayementmerchen").attr('disabled', true);
				$("#coinpayementipn").attr('disabled', true);
				$("#coinpayementapi").attr('disabled', true);
				$("#coinpayementsecret").attr('disabled', true);
				$("#nowpayementapikey").attr('disabled', true);
				$("#nowpaymentaccept").attr('disabled', true);
			break;
		}
	}

	if(actualPage == 'myItems'){
		setTimeout(function(){
			Myajax('hashed='+$('input[name="hashed"]').val(),'GET',  baseUrl+actualPage+'/offnew', '', 'JSON', 'bsmodel', ["1"]);
		}, 10000);
	}
	
	if($("#news").length){
	    getNews();
	}
	//getStoks();
	//getNews();

	/**if(actualPage == "cart"){
		var sum = $('#TabelStock').DataTable().column(2).data().sum();
		$(".mtotals").html('$'+sum)
	}**/
	//getnotifs();
	//getMessages();
});

function getMessages(){
	$.ajax({
		type:'GET',
		url:baseUrl+'/messagesnotifis/getMessagesnotifs',
		dataType:'json',
		success: function(success){
			if(success.notifs == "1"){
				$("#msgnotifs").html(success.results);
				if(success.notfisunseen == "1"){
					$(".msgnotifscontiner").html('<div class="pulse-css"></div>');
				}
				
			}
			else {
				$("#msgnotifs").html(success.results);
			}			
		}
	})
}

function offnotifmsg(){
	var data = 'hashed='+$('input[name="hashed"]').val();
	$.ajax({
		type:'POST',
		url:baseUrl+'/messagesnotifis/offnotifs',
		data:data,
		dataType:'json',
		success: function(success){
			if(success.notifs == "1"){
				$(".bg-msg").remove();
			}
			$('input[name="hashed"]').val(success.csrft);			
		}
	});	
}

function initselect(){
	$('select').select2({
		width: '100%',
    });
}

function archivenotifs(d){
	Myajax("id="+d+"&hashed="+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/archivenotifs', '', 'JSON', 'sweetalert', ["1"]);
}

function toggleSelect(){
	if(checked == 0){
		$('.selected').attr("checked", true);
		checked = 1;
			if(typeof(actualPageArray[4])  != 'undefined'){
				if(!$('button[data-api="massiniteditt-'+actualPageArray[4]+'"]').length){
					var html = '<button type="button" data-api="massinitedit-'+actualPageArray[4]+'" class="btn btn-warning btn-sm">\
					<i class="bx bx-edit"></i> Edit\
					</button>\
					<button type="button" data-api="massinitdelete-'+actualPageArray[4]+'" class="btn btn-danger btn-sm">\
						<i class="bx bx-trash"></i> Delete\
					</button>';
				}
				
			}
			else{
				if(!$('button[data-api="massiniteditt"]').length){
					var html = '<button type="button" data-api="massinitedit" class="btn btn-warning btn-sm">\
						<i class="bx bx-edit"></i> Edit\
					</button>\
					<button type="button" data-api="massinitdelete" class="btn btn-danger btn-sm">\
						<i class="bx bx-trash"></i> Delete\
					</button>';
				}
			}
			
			$('<div>').attr({id: 'btnsContiner', class: 'float-start'}).prependTo("#DivTabelStock");
			$("#btnsContiner").html(html);
		
	}
	else {
		$('.selected').attr("checked", false);
		checked = 0;
		$("#btnsContiner").remove();
	}
}

var countchecked = 0;
function toggleSingle(d){
	var checkbox = $(d).attr('id');
	var checkboxs;
	var second;
	if(checkbox.indexOf('|') != -1){
		checkboxs = checkbox.split('|')[0];
		second = checkbox.split('-')[1];
		var isChecked = $("#"+checkboxs+'-'+second+':checked');
	}
	else {
		checkboxs = checkbox;
		var isChecked = $("#"+checkboxs+':checked');
	}
	if(isChecked.length == 1){
		countchecked += 1;
	}
	else {
		countchecked -= 1;
	}
	
	if(countchecked >= 2){
	    
		if(typeof(actualPageArray[4]) !== 'undefined'){
			if(!$('#massiniteditts').length){
				var html = '<button id="massiniteditts" type="button" data-api="massinitedit-'+actualPageArray[4]+'" class="btn btn-warning btn-sm">\
					<i class="bx bx-edit"></i> Edit\
				</button>\
				<button type="button" data-api="massinitdelete-'+actualPageArray[4]+'" class="btn btn-danger btn-sm">\
					<i class="bx bx-trash"></i> Delete\
				</button>';
			}
		}
		else{
		    
			if(!$('#massiniteditts').length){
				var html = '<button id="massiniteditts" type="button" data-api="massinitedit" class="btn btn-warning btn-sm">\
					<i class="bx bx-edit"></i> Edit\
				</button>\
				<button type="button" data-api="massinitdelete" class="btn btn-danger btn-sm">\
					<i class="bx bx-trash"></i> Delete\
				</button>';
			}
		}
		$('<div>').attr({id: 'btnsContiner', class: 'float-start'}).prependTo("#DivTabelStock");
		$("#btnsContiner").html(html);
	}
	else {
		if(countchecked < 2){
			$("#btnsContiner").remove();
		}
	}
};

var checkcheck = 0;
function toggleBunche(d){
	var checkbox = $(d).attr('id');
	var checkboxs;
	var second;
	var buytype;
	if(checkbox.indexOf('|') != -1){
		checkboxs = checkbox.split('|')[0];
		second = checkbox.split('-')[1];
		var isChecked = $("#"+checkboxs+'-'+second+':checked');
	}
	else {
		checkboxs = checkbox.split('-')[0];
		buytype = checkbox.split('-')[1];
		var isChecked = $("#"+checkboxs+'-'+buytype+':checked');
	}
	if(isChecked.length == 1){
		checkcheck += 1;
	}
	else {
		checkcheck -= 1;
	}
	
	if(checkcheck >= 2){
	    
		if(typeof(actualPageArray[4]) !== 'undefined'){
			if(!$('#massbuy').length){
				var html = '<button id="massbuy" type="button" data-api="addtocartbunche-'+actualPageArray[4]+'" class="btn btn-success btn-sm">Add selected to my cart <i class="bx bx-cart-alt"></i></button>'
			}
		}
		else{
		    
			if(!$('#massbuy').length){
				var html = '<button id="massbuy" type="button" data-api="addtocartbunche" class="btn btn-success btn-sm">\
					Add selected to my cart <i class="bx bx-cart-alt"></i>\
				</button>'
			}
		}
		//$("#DivTabelStock").append(html)	
		$('<div>').attr({id: 'btnsContiner', class: 'float-start'}).prependTo("#DivTabelStock");
		$("#btnsContiner").html(html);
	}
	else {
		if(checkcheck < 2){
			$("#btnsContiner").remove();
		}
		
	}
	
	//$('.selected').attr("checked", true);	
};

function getnotifs(){
	$.ajax({
		type:'GET',
		url:baseUrl+'/notifications/getnotifs',
		dataType:'json',
		success: function(success){
			//if(success.notifs == "1"){
			$("#notifcontainer").html(success.results);
			$(".alert-count").html('0');
	
			//}
			//else {
			//	$("#giftnotifs").html(success.results);
			//}
			
		}
	})
}

/**function offnotif(){
		var data = 'hashed='+$('input[name="hashed"]').val();   		
		$.ajax({
			type:'POST',
			url:baseUrl+'/notifications/offnotifs',
			data:data,
			dataType:'json',
			success: function(success){
				if(success.notifs == "1"){
					$(".alert-count").html('0');
				}
				$('input[name="hashed"]').val(success.csrft);
			}
		});	
}**/

function massinitedit(d = ''){
	if(d != ''){
		if(d == 'cpanel'){
			Myajax('hashed='+$('input[name="hashed"]').val(), 'POST',  baseUrl+'/sellerdashboard/massinitEditcapanel', '', 'JSON', 'bsmodel', ["0"]);	
		}
		else if(d == 'rdp'){
			Myajax('hashed='+$('input[name="hashed"]').val(), 'POST',  baseUrl+'/sellerdashboard/massinitEditrdp', '', 'JSON', 'bsmodel', ["0"]);	
		}
		else if(d == 'smtp'){
			Myajax('hashed='+$('input[name="hashed"]').val(), 'POST',  baseUrl+'/sellerdashboard/massinitEditsmtp', '', 'JSON', 'bsmodel', ["0"]);	
		}
		else if(d == 'shell'){
			Myajax('hashed='+$('input[name="hashed"]').val(), 'POST',  baseUrl+'/sellerdashboard/massinitEditshell', '', 'JSON', 'bsmodel', ["0"]);	
		}
		else {
			Myajax('buytype='+d+'&hashed='+$('input[name="hashed"]').val(), 'POST',  baseUrl+'/sellerdashboard/massinitEditOther', '', 'JSON', 'bsmodel', ["0"]);	
		}
	}
	else {
		Myajax('hashed='+$('input[name="hashed"]').val(), 'POST',  baseUrl+actualPage+'/massinitEdit', '', 'JSON', 'bsmodel', ["0"]);	
	}
}

function dosearche(){
	var data = $("#Filter").serializeArray();
	data.push({name: "hashed", value:$('input[name="hashed"]').val()});
	$('#TabelStock').DataTable().clear().destroy();
	Table = $("#TabelStock").DataTable({
			lengthChange: false,
		    processing: false,
		    bSortable: false,
		    serverSide:true,
		    ajax: {
		        url: baseUrl+actualPage+'/dosearche',
		        type:typeRequest,
		        data:function(d){
		        	d.hashed = $('input[name="hashed"]').val();
		        	d.data = data;
		        },
		        dataSrc: function (json) {
	                $('input[id="crtoken"]').val(json.csrft);
	                return json.data;
	            } 
		    },
		    columnDefs: [{
		        className: 'text-center',
		        targets: "_all",
		    }],
		    ordering: false,
		    dom: "ltrip",
		    responsive: true,
		    scrollCollapse: true,	    
		});
}

function getccHeaders(d){
	$('#TabelStock').DataTable().clear().destroy();
	$('#TabelStock').children().children();
	var headers = '<th><input type="checkbox" id="selectall" data-api="toggleSelect"> </th>4\
					<th>ID</th>\
					<th>Base</th>\
					<th>BIN</th>\
					<th>Brand</th>\
					<th>Level</th>\
					<th>Exp</th>\
					<th>Refundable</th>\
					<th data-priority="1"> Price</th>\
					<th data-priority="1">Tools</th>';
	$("#editBase").removeClass("d-none");
	$("#instokbtn").remove();
	$("#selledbtn").remove();
	$("#refundedbtn").remove();
	$("#add").remove();

	var newhtml = '<button type="button" id="instokbtn" class="btn  btn-info btn-sm text-white" data-api="getStoksStatus-0"><i class="text-white bx bx-package"></i> In Stock</button>\
					<button type="button" id="selledbtn" class="btn  btn-success btn-sm" data-api="getStoksStatus-1"><i class="bx bx-diamond"></i> Sold</button>\
					<button type="button" id="refundedbtn" class="btn  btn-danger btn-sm" data-api="getStoksStatus-2"><i class="bx bx-redo"></i> Refunded</button>\
					<button type="button" id="add" class="btn  btn-primary btn-sm" data-api="initcreatelog"><i class="bx bx-upload"></i> Upload CC</button>';
	$('#inbtn').prepend(newhtml);
	$('#TabelStock').children().children().html(headers);
	$(this).css('background-color', '#000')
	getStoksStatus(d);
}

function getStoksStatus(d){
	$.ajax({
		url:baseUrl+actualPage+'/getStoksCounts',
		type:'GET',
		dataType:'json',
		success:function(reposne){
			$("#ttstok").html(reposne.stok);
			$("#ttselled").html(reposne.selled);
			$("#ttrefunded").html(reposne.refunded);			
		}
	})
	var data = ({'hashed': $('input[name="hashed"]').val()}, {'status': d});
	$('#TabelStock').DataTable().clear().destroy();
	Table = $("#TabelStock").DataTable({
			lengthChange: false,
		    processing: false,
		    bSortable: false,
		    serverSide:true,
		    ajax: {
		        url: baseUrl+actualPage+'/fetchTable',
		        type:typeRequest,
		        data:function(d){
		        	d.hashed = $('input[name="hashed"]').val();
		        	d.data = data;
		        },
		        dataSrc: function (json) {
	                $('input[id="crtoken"]').val(json.csrft);
	                return json.data;
	            } 
		    },
		    columnDefs: [{
		        className: 'text-center',
		        targets: "_all",
		    }],
		    ordering: false,
		    dom: "ltrip",
		    responsive: true,
		    scrollCollapse: true,	    
		});
}

function getOthersMarketsHeaders(d){
	$.ajax({
		url:baseUrl+actualPage+'/getOthersMarketsHeaders?id='+d,
		type:'GET',
		dataType:'json',
		success: function(response){
			$('#TabelStock').DataTable().clear().destroy();
			$('#TabelStock').children().children().html(response.html);
			$('input[id="crtoken"]').val(response.csrft);
			$("#editBase").addClass("d-none");
			$("#instokbtn").remove();
			$("#selledbtn").remove();
			$("#refundedbtn").remove();
			$("#add").remove();

			var newhtml = '<button type="button" id="instokbtn" class="btn  btn-info btn-sm text-white" data-api="getStoksStatusOthers-0|'+d+'"><i class="text-white bx bx-package"></i> In Stok</button>\
							<button type="button" id="selledbtn" class="btn  btn-success btn-sm" data-api="getStoksStatusOthers-1|'+d+'"><i class="bx bx-diamond"></i> Sold</button>\
							<button type="button" id="refundedbtn" class="btn  btn-danger btn-sm" data-api="getStoksStatusOthers-2|'+d+'"><i class="bx bx-redo"></i> Refunded</button>\
							<button type="button" id="add" class="btn  btn-primary btn-sm" data-api="initcreate-'+d+'"><i class="bx bx-upload"></i> Add Stuff</button>';
			$('#inbtn').prepend(newhtml);
			getStoksStatusOthers(d);
		}
	});
}

			

function getStoksStatusOthers(a){
	if(a.indexOf('|') != -1){
		var data = ({'hashed': $('input[name="hashed"]').val(), 'id':a.split('|')[1], 'status':a.split('|')[0]});
		$('#TabelStock').DataTable().clear().destroy();
		var toruls = baseUrl+actualPage+'/getStoksCounts?id='+a;
	}
	else {
		var data = ({'hashed': $('input[name="hashed"]').val(), 'id':a});
		var toruls = baseUrl+actualPage+'/getStoksCounts?id='+a;
	}
	$.ajax({
		url:toruls,
		type:'GET',
		dataType:'json',
		success:function(reposne){
			$("#ttstok").html(reposne.stok);
			$("#ttselled").html(reposne.selled);
			$("#ttrefunded").html(reposne.refunded);				
		}
	})
	Table = $("#TabelStock").DataTable({
			lengthChange: false,
		    processing: false,
		    bSortable: false,
		    serverSide:true,
		    ajax: {
		        url: baseUrl+actualPage+'/fetchOthersTable',
		        type:'POST',
		        data:function(a){
		        	a.hashed = $('input[name="hashed"]').val();
		        	//a.id = a;
		        	a.data = data;
		        },
		        dataSrc: function (json) {
	                $('input[id="crtoken"]').val(json.csrft);
	                return json.data;
	            } 
		    },
		    columnDefs: [{
		        className: 'text-center',
		        targets: "_all",
		    }],
		    ordering: false,
		    dom: "ltrip",
		    responsive: true,
		    scrollCollapse: true,	    
		});
}

function GetState(){
	$("#state").find('option').remove();
	$("#city").find('option').remove();
	var country = $("#country").val();
	if(country !== 'all' && country !== 'All'){
		$.ajax({
			url:baseUrl+actualPage+'/getStates',
			type: 'POST',
			dataType: 'json',
			data:'country='+country+'&hashed='+$('input[name="hashed"]').val(),
			success : function(response){
				$("#state").removeClass('disabled');
				$("#state").attr('disabled', false);
				$("#state").append(response.html);

				$("#city").removeClass('disabled');
				$("#city").attr('disabled', false);
				$("#city").append(response.citys);
				$('input[name="hashed"]').val(response.csrft);
			}
		})
	}
	else {
		$("#state").attr('disabled', true);
		$("#state").addClass('disabled');
		$("#state").find('option').remove();
		$("#state").html('<option>Select Country First</option>');

		$("#city").attr('disabled', true);
		$("#city").addClass('disabled');
		$("#city").find('option').remove();
		$("#city").html('<option>Select State First</option>');
		//$('input[name="hashed"]').val(response.csrft);
	}
	
}

function editBase(){
	Myajax('hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/editBase', '', 'JSON', 'bsmodel', ["0"]);
}

function edibasePrice(){
	var price = $("#BasePrice").val();
	var base = $("#baseSelect").val();
	var refun = $("#isrefun").val();
	Myajax('base='+base+'&price='+price+'&refun='+refun+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/edibasePrice', '', 'JSON', 'bsmodel', ["1"]);
	initselect();

}

function GetCitys(){
	$("#city").find('option').remove();
	var state = $("#state").val();
	var country = $("#country").val();
	if(state !== 'all' && state !== 'All'){
		$.ajax({
			url:baseUrl+actualPage+'/getCitys',
			type: 'POST',
			dataType: 'json',
			data:'country='+country+'&state='+state+'&hashed='+$('input[name="hashed"]').val(),
			success : function(response){
				$("#city").removeClass('disabled');
				$("#city").attr('disabled', false);
				$("#city").append(response.citys);
				$('input[name="hashed"]').val(response.csrft);
			}
		})
	}
	else {
		$("#city").attr('disabled', true);
		$("#city").addClass('disabled');
		$("#city").find('option').remove();
		$("#city").html('<option>Select Country First</option>');
		//$('input[name="hashed"]').val(response.csrft);
	}	
}

function initcreate(id){
	Myajax('id='+id+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/initCreate', '', 'JSON', 'bsmodel', ["0"]);
}

function signup(){
	Myajax('Serialize', 'POST', baseUrl+'signup/signupme', 'signupForm', 'JSON', 'bsmodel');
}

function dosave(){
	Myajax('FileUpload', 'POST', baseUrl+'sellerdashboard/doCreate', 'createForm', 'JSON', 'bsmodel', ["1"]);
}

function login(){
	Myajax('Serialize', 'POST', baseUrl+'login/logmein', 'loginForm', 'JSON', 'redirect');
}

function saveGlobale(){
	Myajax('FileUpload','POST', baseUrl+actualPage+'/saveBasic', 'globalSettingsForm', 'JSON', 'sweetalert');
}

function savemysettings(d){
	switch(d){
		case 'reg':
			var pageto = '/saveRegistration';
			var formto = 'RegsettingsForm';
		break
		case 'ref':
			var pageto = '/saveReferals';
			var formto = 'RefettingsForm';
		break
		case 'sell':
			var pageto = '/saveSellers';
			var formto = 'sellsettingsForm';
		break
		case 'checkers':
			var pageto = '/saveCheckers';
			var formto = 'checkersettingsForm';
		break
		case 'getways':
			var pageto = '/saveGetways';
			var formto = 'getwayssettingsForm';
		break
		case 'telegram':
			var pageto = '/saveTelegram';
			var formto = 'TelegramForm';
		break
	}
	Myajax('Aserialize','POST', baseUrl+actualPage+pageto, formto, 'JSON', 'sweetalert');
}

function setgetway(){
	var getwaypayment = $("#payementgetway").val();
	switch(getwaypayment){
		case '1':
			$("#nowp").removeClass('d-none');
			$("#nowpayementapikey").attr('disabled', false);
			$("#nowpaymentaccept").attr('disabled', false);
			$("#coinp").addClass('d-none');
			$("#blokp").addClass('d-none');
			$("#coinpayementmerchen").attr('disabled', true);
			$("#coinpayementipn").attr('disabled', true);
			$("#coinpayementapi").attr('disabled', true);
			$("#coinpayementsecret").attr('disabled', true);
			$("#blockonomicsapi").attr('disabled', true);
		break;
		case '2':
			$("#coinp").removeClass('d-none');
			$("#coinpayementmerchen").attr('disabled', false);
			$("#coinpayementipn").attr('disabled', false);
			$("#coinpayementapi").attr('disabled', false);
			$("#coinpayementsecret").attr('disabled', false);
			$("#nowp").addClass('d-none');
			$("#blokp").addClass('d-none');
			$("#nowpayementapikey").attr('disabled', true);
			$("#nowpaymentaccept").attr('disabled', true);
			$("#blockonomicsapi").attr('disabled', true);
		break;
		case '3':
			$("#blokp").removeClass('d-none');
			$("#blockonomicsapi").attr('disabled', false);
			$("#coinp").addClass('d-none');
			$("#nowp").addClass('d-none');
			$("#coinpayementmerchen").attr('disabled', true);
			$("#coinpayementipn").attr('disabled', true);
			$("#coinpayementapi").attr('disabled', true);
			$("#coinpayementsecret").attr('disabled', true);
			$("#nowpayementapikey").attr('disabled', true);
			$("#nowpaymentaccept").attr('disabled', true);
		break;
	}
}

function setchecker(){
	var checkertouse = $("#checkerused").val();
	switch(checkertouse){
		case '1':
			$(".luxo").removeClass('d-none');
			$("#luxorcheckeruser").attr('disabled', false);
			$("#luxorchecjerapi").attr('disabled', false);
			$(".chk").addClass('d-none');
			$("#ccdotcheckapi").attr('disabled', true);
		break;
		case '2':
			$(".chk").removeClass('d-none');
			$("#ccdotcheckapi").attr('disabled', false);
			$(".luxo").addClass('d-none');
			$("#luxorcheckeruser").attr('disabled', true);
			$("#luxorchecjerapi").attr('disabled', true);
		break;
		case '3':
			$(".luxo").removeClass('d-none');
			$("#luxorcheckeruser").attr('disabled', false);
			$("#luxorchecjerapi").attr('disabled', false);
			$(".chk").removeClass('d-none');
			$("#ccdotcheckapi").attr('disabled', false);
		break;
	}
}

function initcreatelog(){
	Myajax('Serialize','GET', baseUrl+actualPage+'/createLogInit', 'addForm', 'JSON', 'bsmodel');
}

function initcreatelogSinge(){
	Myajax('Serialize','GET', baseUrl+actualPage+'/singleAdd', 'addForm', 'JSON', 'bsmodel');
}

function initcreateNews(){
	Myajax('Serialize','GET', baseUrl+'home/createLogInit', 'addForm', 'JSON', 'bsmodel');
}

function initcreateFaq(){
	Myajax('Serialize','GET', baseUrl+'faq/createLogInit', 'addForm', 'JSON', 'bsmodel');
}


function addSingle(){
	Myajax('FileUpload','POST', baseUrl+actualPage+'/addSingle', 'addCont', 'JSON', 'bsmodel', ["1"]);
}

function check(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+'cards/check', 'addCont', 'JSON', 'bsmodel', ["0"]);
}

function createlog(){
	Myajax('Aserialize','POST', baseUrl+actualPage+'/createLog', 'addCont', 'JSON', 'bsmodel', ["1"]);
}

function editinit(d){

	if(d.indexOf('|') != -1){
		var parts = d.split('|');
		var ds =  parts[1];
		var dist = 'initEdit'+parts[0];
		console.log(dist)
	}
	else {
		var ds = d;
		var dist = '/initEdit';
	}

	Myajax('id='+ds+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/'+dist, 'addCont', 'JSON', 'bsmodel', ["0"]);
}

function editinitm(d){
	var ds = d.split('|');
	Myajax('id='+ds[0]+'&buytype='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'/sellerdashboard/initEditm', 'addCont', 'JSON', 'bsmodel', ["0"]);
}


function doedit(d){
	Myajax('FileUpload','POST', baseUrl+'/sellerdashboard/doEdit', 'createForm', 'JSON', 'bsmodel', ["1"]);
}

function edit(d){
	Myajax('Aserialize','POST', baseUrl+actualPage+'/edit', 'edittForm', 'JSON', 'sweetalert', ["1", initSelectIcons()]);
}

function editCard(d){
	var reload ='';
	if(actualPage == 'cards' || actualPage == 'Crds' || actualPage == 'Sellerdashboard' || actualPage == 'sellerdashboard'){
		var reload = '1'
	}
	Myajax('Aserialize','POST', baseUrl+actualPage+'/edit', 'edittForm', 'JSON', 'bsmodel', [reload]);
}

function edititem(d){
	if(d.indexOf('|') !== -1){
		var part = d.split('|');
		var dest = part[0];
	}
	Myajax('Aserialize','POST', baseUrl+actualPage+'/'+dest, 'edittForm', 'JSON', 'bsmodel', [1]);
}

function rminit(d){
	if(d.indexOf('|') !== -1 ){
		
		var parts = d.split('|');
		var ds =  parts[1];
		var dist = 'rminit'+parts[0];
	}
	else {
		var ds = d;
		var dist = '/rminit';
	}
	Myajax('id='+ds+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/'+dist, '', 'JSON', 'bsmodel', ["0"]);
}


function getDetail(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/getDetail', '', 'JSON', 'bsmodel', ["0"]);
}


function rm(d){
	if(d.indexOf('|') !== -1){
		var parts = d.split('|');
		var ds =  parts[1];
		var dist = 'rm'+parts[0];
	}
	else {
		var ds = d;
		var dist = '/rm';
	}
	Myajax('id='+ds+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/'+dist, '', 'JSON', 'bsmodel', ["1"]);
}




function report(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/reportItem', '', 'JSON', 'bsmodel', ["1"]);
}

function buyinit(){
	Myajax('hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/checkoutinit', '', 'JSON', 'bsmodel', ["0"]);
}

function addtocart(d){
	var ds = d.split('|');
	Myajax('id='+ds[0]+'&buytype='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/addtoCart', '', 'JSON', 'div', ["1","1", "cartContents"]);
	$("#acart").addClass('with-cart');
	$(":input,:button,:checkbox").removeClass('disabled');
    $(":input,:button,:checkbox").prop('disabled', false);
}

function clearCart(){
	Myajax('hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/clearCart', '', 'JSON', 'div', ["0","1", "cartContents"]);
	$("#acart").removeClass('with-cart');
}

function removeCartProd(d){
	var ds = d.split('|');
	if(actualPage == 'cart' || actualPage == 'Cart'){
		Myajax('id='+ds[0]+'&buytype='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/removeCartProd', '', 'JSON','sweetalert', ["1"]);
		$('.'+ds[0]+'-'+ds[1]).parent().parent().parent().parent().remove();
	}
	else {
		Myajax('id='+ds[0]+'&buytype='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/removeCartProd', '', 'JSON', 'div', ["1","1", "cartContents"]);
		var dataapi = 'addtocart-'+ds[0]+'|'+ds[1];
		$('#buybtn-'+ds[0]).data('api', dataapi);
		$('#buybtn-'+ds[0]).removeClass('btn-danger');
		$('#buybtn-'+ds[0]).addClass('btn-success');
		$('#buybtn-'+ds[0]).html('Add to cart <span class="bx bx bx-cart"></span>');
		$(":input,:button,:checkbox").removeClass('disabled');
	    $(":input,:button,:checkbox").prop('disabled', false);
	}
}

function buyconfirm(d){
	var ds = d.split('|');
	Myajax('id='+ds[0]+'&buytype='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/buyconfirm', '', 'JSON', 'bsmodel', ["1"]);
}

function downloadcc(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/downloadcc', '', 'JSON', 'bsmodel', ["0"]);
}

function checkerr(d){
	$('button[data-api="checkerr-'+d+'"]').html('CHECKING...');
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/checkerr', '', 'JSON', 'sweetalert', ["1"]);
}

function depoinit(){
	Myajax('hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/depoinit', '', 'JSON', 'bsmodel', ["0"]);
}

function depoconfirm(){
	Myajax('Aserialize','POST', baseUrl+'Finances/depoconfirm', 'depoForm', 'JSON', 'bsmodel', ["0"]);
}

function vocherinit(){
	Myajax('hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/vocherinit', '', 'JSON', 'bsmodel', ["0"]);
}

function vocherconf(){
	Myajax('Aserialize','POST', baseUrl+'Finances/vocherconf', 'depoForm', 'JSON', 'bsmodel', ["0"]);
}

function withdrawinit(){
	Myajax('hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/withdrawinit', '', 'JSON', 'bsmodel', ["0"]);
}

function view(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/view', '', 'JSON', 'bsmodel', ["0"]);
}

function reportinit(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/reportinit', '', 'JSON', 'bsmodel', ["0"]);
}

function reportconfirm(){
	Myajax('Serialize' ,'POST', baseUrl+actualPage+'/reportconfirm', 'reportForm', 'JSON', 'bsmodel', ["1"]);
}

function sendMessage(){
	Myajax('Serialize' ,'POST', baseUrl+'mytikets/insmessage', 'sendmsg', 'JSON', 'msg', ["0"]);
}

function close(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'mytikets/closetiket', '', 'JSON', 'bsmodel', ["0"]);
}

function refund(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'mytikets/refundtiket', '', 'JSON', 'bsmodel', ["0"]);
}

function edituserinit(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'users/initEdit', '', 'JSON', 'bsmodel', ["0"]);
}

function edituser(d){
	Myajax('Serialize','POST', baseUrl+'users/edit', 'editUserForm', 'JSON', 'bsmodel', ["1"]);
}

function rmuserinit(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'users/rmuserinit', '', 'JSON', 'bsmodel', ["0"]);
}

function rmuser(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'users/rmuser', '', 'JSON', 'bsmodel', ["1"]);
}

function updateglob(){
	Myajax('Serialize' ,'POST', baseUrl+'sitesettings/edit', 'globSettingForm', 'JSON', 'bsmodel', ["0"]);
}

function accptdecline(d){
	var ds = d.split('|');
	Myajax('id='+ds[0]+'&type='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/accptdecline', '', 'JSON','sweetalert', ["1"]);
	$('.'+ds[0]+'-'+ds[1]).parent().parent().parent().parent().remove();
}

function updatebase(d){
	Myajax('Serialize','POST', baseUrl+'bases/edit', 'editform', 'JSON', 'bsmodel', ["1"]);
}

function discount(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/initdiscount', 'addCont', 'JSON', 'bsmodel', ["0"]);
}

function updatediscount(d){
	Myajax('Serialize','POST', baseUrl+'bases/discount', 'editform', 'JSON', 'bsmodel', ["1"]);
}

function initbasedelete(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/initDelete', 'addCont', 'JSON', 'bsmodel', ["0"]);
}

function deletebase(){
	Myajax('Serialize','POST', baseUrl+'bases/deletebase', 'editform', 'JSON', 'bsmodel', ["1"]);
}



function getSeller(){
	Myajax('hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'sellerrequests/createrequestInit', '', 'JSON', 'bsmodel', ["0"]);
}

function confirmWithdraw(){
	Myajax('hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/withdrawConfirm', '', 'JSON', 'bsmodel', ["0"]);
}


function creatrequest(){
	Myajax('Aserialize' ,'POST', baseUrl+'sellerrequests/createrequest', 'addCont', 'JSON', 'bsmodel', ["0"]);
}

function updatecon(){
	Myajax('Serialize' ,'POST', baseUrl+'sitesettings/editc', 'cform', 'JSON', 'bsmodel', ["0"]);
}

function updateperso(){
	Myajax('Serialize' ,'POST', baseUrl+'profile/updateperso', 'persoform', 'JSON', 'bsmodel', ["0"]);
}

function updatepass(){
	Myajax('Serialize' ,'POST', baseUrl+'profile/updatepass', 'pwdform', 'JSON', 'bsmodel', ["0"]);
}

function restpwd(){
	Myajax('Serialize' ,'POST', baseUrl+'pwdrec/confirmrest', 'pwdrecs', 'JSON', 'bsmodel', ["0"]);
}

$(".search-control").on("keyup", function() {  
 	if(typeof Table != 'undefined' ){
		Table.search(this.value).draw();
	}
});

function adddfeatures(){
    var html = '<div><div class="mb-20">'+
					'<div class="input-group mt-2">'+
                        '<input type="text" class="form-control" name="features[]" id="features" />'+
                        '<span class="input-group-append">'+
                            '<button type="button" class="btn  btn-danger" data-api="rmdigits-this">-</button>'+
                        '</span>'+
                    '</div>'+
                '</div>'+
                '</div>'
    $("#featuresDiv").append(html);
}


function rmdigits(d){
    $(d).parent().parent().parent().remove();
}

//wizzard

function addrulcritary(d,nnid){
	sd = nnid+101;
	zid = $(d).attr('id').split('-')[1];
	html = '<div class="form-group">\
	            <div class="row">\
	                <div class="col-6">\
        				<select class="select2" id="inputsvalidation'+sd+'" name="inputs['+zid+'][validationrull][]" tabindex="-1" aria-hidden="true" data-api="addvalidruls-inputsvalidation'+sd+'|'+sd+'">\
        					<option selected>No validation</option>\
							<option value="alpha">alpha</option>\
							<option value="alpha_space">alpha_space</option>\
							<option value="alpha_dash">alpha_dash</option>\
							<option value="alpha_numeric">alpha_numeric</option>\
							<option value="alpha_numeric_space">alpha_numeric_space</option>\
							<option value="alpha_numeric_punct">alpha_numeric_punct</option>\
							<option value="decimal">decimal</option>\
							<option value="exact_length" data-ruls="exact_length">exact_length</option>\
							<option value="greater_than" data-ruls="greater_than">greater_than</option>\
							<option value="greater_than_equal_to" data-ruls="greater_than_equal_to">greater_than_equal_to</option>\
							<option value="integer">integer</option>\
							<option value="is_natural">is_natural</option>\
							<option value="is_natural_no_zero">is_natural_no_zero</option>\
							<option value="is_not_unique" data-ruls="is_not_unique">is_not_unique</option>\
							<option value="is_unique" data-ruls="is_unique">is_unique</option>\
							<option value="less_than" data-ruls="less_thane">less_than</option>\
							<option value="less_than_equal_to" data-ruls="less_than_equal_to">less_than_equal_to</option>\
							<option value="matches"  data-ruls="matches">matches</option>\
							<option value="max_length" data-ruls="max_length">max_length</option>\
							<option value="min_length" data-ruls="min_length">min_length</option>\
							<option value="numeric">numeric</option>\
							<option value="regex_match" data-ruls="regex_match">regex_match</option>\
							<option value="permit_empty">permit_empty</option>\
							<option value="required">required</option>\
							<option value="valid_email">valid_email</option>\
							<option value="valid_ip">valid_ip</option>\
							<option value="valid_url">valid_url</option>\
							<option value="valid_date">valid_date</option>\
							<option value="valid_cc_number">valid_cc_number</option>\
							<option value="uploaded">uploaded</option>\
							<option value="max_size">max_size</option>\
							<option value="max_dims">max_dims</option>\
							<option value="mime_in">mime_in</option>\
							<option value="ext_in">ext_in</option>\
							<option value="is_image">is_image</option>\
        				</select>\
        			</div>\
            		<div class="col-6">\
            		    <div class="input-group">\
            				<input type="text" disabled id="'+sd+'" class="form-control disabled" data-api="setval-'+sd+'">\
            				<button type="button" id="removefield'+sd+'" class="btn btn-outline-danger btn-sm" data-api="removerulcritary-removefield'+sd+'"><i class="bx bx-trash"></i></button>\
        				</div>\
        			</div>\
        		</div>\
			</div>';
		$(d).parent().parent().parent().append(html);
		$(d).attr('onclick', "addrulcritary(this,"+sd+");")
		initSelectIcons();
		initselect();
}

function addfield(){
	var html = '<div class="row pt-2">\
					<div class="col-sm">\
						<label class="pb-2 text-white" for="fieldsnames'+xid+'">Field name</label>\
						<input type="text" id="fieldsnames'+xid+'" name="fields['+xid+'][fieldsNames][]" class="form-control">\
					</div>\
					<div class="col-sm">\
						<label class="pb-2" for="fieldstypes'+xid+'">Field Type</label>\
						<select class="select2" id="fieldstypes'+xid+'" name="fields['+xid+'][fieldTypes][]" tabindex="-1" aria-hidden="true">\
							<option value="int">Integer</option>\
							<option value="varchar">Varchar</option>\
							<option value="date">Date</option>\
							<option value="datetime">DateTime</option>\
							<option value="longtext">Long text</option>\
						</select>\
					</div>\
					<div class="col-sm">\
						<label class="pb-2 text-white" for="fieldssize'+xid+'">Field Size</label>\
						<input type="number" id="fieldssize'+xid+'" name="fields['+xid+'][fieldSsize][]" class="form-control">\
					</div>\
					<div class="col-sm">\
						<label class="pb-2 text-white" for="fieldsempty'+xid+'">Empty by Default</label>\
						<div class="form-group d-flex">\
							<select class="form-select" id="fieldsempty'+xid+'" name="fields['+xid+'][fieldsempty][]" tabindex="-1" aria-hidden="true">\
								<option value="1">No</option>\
								<option value="0">Yes</option>\
							</select>\
							<button type="button" id="remove'+xid+'" class="btn btn-outline-danger btn-sm" data-api="removefield-'+xid+'">Remove <i class="bx bx-trash"></i></button>\
						</div>\
					</div>\
				</div>';
	$("#fieldrecept").append(html);
	initselect();
	xid += 1;
}

function doselect(d){
	var value = $("#inputtypes"+d).val();
	if(value == 'select'){
		var html = '<div id="lists'+d+'"><label class="mt-2 mb-1 text-white">Set the custom values<br/> vlue1,Text1<br/>vlue2,Text2...</label>\
		<textarea class="form-control" id="vinputtypes'+d+'" name="inputs['+d+'][inputsTypes]"></textarea></div>';
		$("#inputtypes"+d).parent().append(html);
		$("#inputtypes"+d).attr('name', '');

	}
	else {
		if($("#lists"+d).length){

			$("#lists"+d).remove();
			$("#inputtypes"+d).attr('name', 'inputs['+d+'][inputsTypes]');
		}
	}
}

function initWiz(){
	var btnFinish = $("<button></button>").text("Finish").addClass('btn disabled').attr({'disabled':true, 'id':"finish", 'data-api':'createnewsection'}),
	addFields = $('<button></button>').text('Add New').addClass('btn disabled btn-danger').attr({'disabled':true, 'id':"addfield", 'data-api':'addfield'});
	$("#smartwizard").on("showStep", function (e, anchorObject, stepNumber, stepDirection, stepPosition) {
		initSelectIcons();
		$("#prev-btn").removeClass('disabled');
		$("#next-btn").removeClass('disabled');
		if (stepPosition === 'first') {
			$("#prev-btn").addClass('disabled');
		} else if (stepPosition === 'last') {
			$("#next-btn").addClass('disabled');
			$("#finish").removeClass('disabled');
			$("#finish").attr('disabled', false);
		}
		else {
			$("#prev-btn").removeClass('disabled');
			$("#next-btn").removeClass('disabled');
			$("#finish").attr('disabled', false);
		}
		if(stepNumber == "1"){
			$("#addfield").removeClass('disabled');
			$("#addfield").attr('disabled', false);
		}
		else {
			$("#addfield").addClass('disabled');
			$("#addfield").attr('disabled', true);
		}
		if(stepNumber == "2"){
			var steptwoinputs = $("#step-2").find(':input[id^="fieldsnames"]');
			var htmlr = '';
			var thead = '<table class="table table-bordered"><thead><tr>';
			if(htmlr == ''){
				$.each(steptwoinputs, function(key, val){
					if(val.value != ""){
						htmlr += '<div class="row pt-2" >\
									<div class="col-sm-2">\
										<label class="pb-2 text-white" for="fieldlabel'+nid+'">Input Lable</label>\
										<input type="hidden" id="fieldsnames'+nid+'" class="form-control disable" name="inputs['+nid+'][inputName]" value="'+val.value+'">\
										<input type="text" id="fieldlabel'+nid+'" name="inputs['+nid+'][inputLable]" class="form-control" value="'+val.value+'">\
									</div>\
									<div class="col-sm-2">\
										<label class="pb-2  text-white" for="inputtypes'+nid+'">Input Type</label>\
										<select class="select3 form-control" id="inputtypes'+nid+'" name="inputs['+nid+'][inputsTypes]" tabindex="-1" aria-hidden="true" data-api="doselect-'+nid+'">\
											<option selected value="text">Text</option>\
											<option value="email">Email</option>\
											<option value="password">Password</option>\
											<option value="number">Number</option>\
											<option value="checkbox">Checkbox</option>\
											<option value="imageupload">Image Upload</option>\
											<option value="textaria">Text aria</option>\
											<option value="select">Select</option>\
											<option value="yesno">Yes/No</option>\
										</select>\
									</div>\
									<div class="col-sm-6">\
										<label class="pb-2 text-white" for="inputsvalidation'+nid+'">Validations</label>\
										<div class="form-group">\
										    <div class="row">\
    										    <div class="col-6">\
        											<select class="select2" id="inputsvalidation'+nid+'" name="inputs['+nid+'][validationrull][]" tabindex="-1" aria-hidden="true" data-api="addvalidruls-inputsvalidation'+nid+'|'+nid+'">\
        												<option selected>No validation</option>\
        												<option value="alpha">alpha</option>\
        												<option value="alpha_space">alpha_space</option>\
        												<option value="alpha_dash">alpha_dash</option>\
        												<option value="alpha_numeric">alpha_numeric</option>\
        												<option value="alpha_numeric_space">alpha_numeric_space</option>\
        												<option value="alpha_numeric_punct">alpha_numeric_punct</option>\
        												<option value="decimal">decimal</option>\
        												<option value="exact_length" data-ruls="exact_length">exact_length</option>\
        												<option value="greater_than" data-ruls="greater_than">greater_than</option>\
        												<option value="greater_than_equal_to" data-ruls="greater_than_equal_to">greater_than_equal_to</option>\
        												<option value="integer">integer</option>\
        												<option value="is_natural">is_natural</option>\
        												<option value="is_natural_no_zero">is_natural_no_zero</option>\
        												<option value="is_not_unique" data-ruls="is_not_unique">is_not_unique</option>\
        												<option value="is_unique" data-ruls="is_unique">is_unique</option>\
        												<option value="less_than" data-ruls="less_thane">less_than</option>\
        												<option value="less_than_equal_to" data-ruls="less_than_equal_to">less_than_equal_to</option>\
        												<option value="matches"  data-ruls="matches">matches</option>\
        												<option value="max_length" data-ruls="max_length">max_length</option>\
        												<option value="min_length" data-ruls="min_length">min_length</option>\
        												<option value="numeric">numeric</option>\
        												<option value="regex_match" data-ruls="regex_match">regex_match</option>\
        												<option value="permit_empty">permit_empty</option>\
        												<option value="required">required</option>\
        												<option value="valid_email">valid_email</option>\
        												<option value="valid_ip">valid_ip</option>\
        												<option value="valid_url">valid_url</option>\
        												<option value="valid_date">valid_date</option>\
        												<option value="valid_cc_number">valid_cc_number</option>\
        												<option value="uploaded">uploaded</option>\
        												<option value="max_size">max_size</option>\
        												<option value="max_dims">max_dims</option>\
        												<option value="mime_in">mime_in</option>\
        												<option value="ext_in">ext_in</option>\
        												<option value="is_image">is_image</option>\
        											</select>\
        										</div>\
        										<div class="col-6">\
        										    <div class="input-group">\
            											<input type="text" id="'+nid+'" disabled data-api="setval-'+nid+'" class="form-control disabled">\
            											    <button type="button" id="addFields-'+nid+'" class="btn btn-outline-success btn-sm" onclick="addrulcritary(this,'+nid+');" data-apizz="addrulcritary-addFields'+nid+'"><i class="bx bx-plus"></i></button>\
            											</div>\
        										</div>\
    										</div>\
										</div>\
									</div>\
									<div class="col-sm-2">\
										<label class="pb-2 text-white" for="sectionicone">Icon</label>\
										<select class="iconselect" id="sectionicone'+nid+'" name="inputs['+nid+'][inputsIcone]" tabindex="-1" aria-hidden="true">\
										</select>\
									</div>\
								</div>';
					}						
					nid += 1;
				});
			}

			$("#s3").html(htmlr);
			//initSelect2('select3');
			initselect();
			initSelectIcons();
		}
		if(stepNumber == "3"){
			var stepthreeinputs = $("#s3").find(':input[id^="fieldsnames"]');
			var tid = 0;
			var thtml = '';
			var bodyml = '';
			var tlabels = '';
			$.each(stepthreeinputs, function(k,v){
				thtml += '<th>'+v.value+'</th>';
				bodyml += '<td><select class="select2" name="tables['+xid+'][cellsTypes][]">\
							<option value="show">Show to users</option>\
							<option value="hide">Hide from users</option>\
							<option value="sumdollar">Show Sum with $ signe</option>\
							<option value="sumpound">Show Sum with £ signe</option>\
							<option value="sumeur">Show Sum with € signe</option>\
							<option value="btnsuccess">Show Button Green</option>\
							<option value="btnwarning">Show Button Yellow</option>\
							<option value="btndanger">Show Button Red</option>\
							<option value="btnlight">Show Button Light</option>\
							<option value="btninfo">Show Button Info</option>\
							<option value="btndefault">Show Button Default</option>\
							<option value="btnimage">Show Image</option>\
							<option value="btncheck">Show Icon check</option>\
							<option value="btnclose">Show Icone Close</option>\
							<option value="image">Show Image (from Link)</option>\
							<option value="imageupload">Show Upload image</option>\
							<option value="link">Show Link</option>\
							<option value="yesno">Show Yes Or No</option>\
							<option value="existnot">Show Exist or Not Exist</option>\
						</select></td>';
				tlabels +='<td><label>Table Label:</label><input name="tables['+xid+'][cellsName][]"  value="'+v.value+'"  type="hidden"><input type="text" name="tables['+xid+'][cellsHeads][]" class="form-control" value="'+v.value+'" ></td>';
			});
			$('#thTable').html(tlabels);
			$('#tbodyTable').html(bodyml);
			initselect();
		}
	});
	$('#smartwizard').smartWizard({
		selected: 0,
		theme: 'dark',
		autoAdjustHeight: true,
		transition: {
			animation: 'slide-horizontal',
		},
		toolbarSettings: {
			toolbarPosition: 'bottom',
			toolbarExtraButtons: [addFields, btnFinish]
		},
		keyboardSettings: {
	      	keyNavigation: false
	  	}
	});


    tinymce.init({
      selector: '#sectionmessage',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        },
        height: 500,
        menubar: false,
        plugins: [
          'advlist autolink lists link image charmap print preview anchor',
          'searchreplace visualblocks code fullscreen',
          'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image | link'
    });

    document.addEventListener('focusin', (e) => {
      if (e.target.closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
        e.stopImmediatePropagation();
      }
    });
}

function removefield(d){
	$("#remove"+d).fadeOut(300, function(){ $(this).parent().parent().parent().remove();});
	$("#remove"+d).parent().parent().parent().fadeOut(300, function(){($(this).parent().parent().parent().remove())});
}

function removerulcritary(d){
	$('#'+d).fadeOut(300, function(){ $(this).parent().parent().parent().parent().remove();});
	$('#'+d).parent().fadeOut(300, function(){($(this).parent().remove())});
}

function initSelect2(selectClass){
	$('.'+selectClass).select2({
		width: '100%',
	});
}


function checkerformatter(){
    var delim = $("#delim").val();
    var textArray = $("#cards").val().split("\n");
	if(delim.length != 0 && textArray.length != 0){
		var html = '';
		var htmls = '';
		alldatas = [];
		$.each(textArray, function(k,v){
			var datas = [];
			var vals = v.split(delim);
			$.each(vals, function(c,vc){
				if(vc.indexOf(':') != -1){
					datas.push(vc.split(':')[1]);
				}
				else {
					datas.push(vc);
				}
			}) 
			alldatas.push(datas);
		});
		
		var countcells = [];
		$.each(alldatas, function(cell, cells){
			countcells.push(cells.length); 
			htmls += '<tr class="infosTR">';
			$.each(cells, function(c, va){
				htmls += '<td><p>'+va+'</p></td>';
			})
			htmls += '</tr>';
			
		})
		countcells.sort((a, b) => b- a);
		var heads = '<tr>';
		for (var i = 1; i <= countcells[0]; i++) {
			heads += '<th><select id="sus'+i+'" class="form-control select2" data-api="setinput-'+i+'">\
			<option value="">Unknow</option>\
			<option value="cc">CC</option>\
			<option value="expm">Exp M</option>\
			<option value="expy">Exp Y</option>\
			<option value="expmy">Exp MM/YY</option>\
			<option value="cvv">CVV</option>\
			<option value="fname">First Name</option>\
			<option value="lname">Last Name</option>\
			<option value="fullname">Full Name</option>\
			<option value="address">Address</option>\
			<option value="city">City</option>\
			<option value="state">State</option>\
			<option value="country">Country</option>\
			<option value="zip">Zip</option>\
			<option value="phone">Phone</option>\
			<option value="dob">Dob</option>\
			<option value="email">Email</option>\
			<option value="zip">IP</option>\
			<option value="other">Other Info</option>\
			</select></th>';
		}
		heads += '</tr>';

		html +='<p class="mt-3">Please setup your fields and indicate which is the right information in the right row</p>\
		<p><span class="text-warning">Warning: </span>If cellular is Unknown, the informations will be skipped and the line will be classed as error.</p>\
		<table class="table table-striped table-bordered mt-3" style="width:100%" id="formatter">';
		html += heads;
		html += htmls;
		html +='</table>';
		$("#rz").html(html);
		$("#rz").removeClass("d-none");
		$("#cr").addClass("d-none");
		$("#cards").attr("name", "");
		var nbtn = '<div class="row">\
		            <div class="col-md-6">\
		                <div class="d-grid">\
		                    <button type="button" class="btn btn-danger" onclick="cancelcheckme()">Cancel</button>\
		                </div>\
		            </div>\
		            <div class="col-md-6">\
		                <div class="d-grid">\
		                    <button type="button" class="btn btn-success" onclick="checkmecards()">Start Check</button>\
		                </div>\
		            </div>\
		           </div>';
		$("#msavebtn").remove();
		$("#btns").html(nbtn);
	}
	else {
		alert('Wrong Input, Please correct your inputs');	
	}
}

function cancelcheckme(){
    $("#rz").children().remove();
    $("#rz").addClass("d-none");
    $("#cr").removeClass("d-none");
    $("#btns").html('<button type="button" class="btn btn-info" data-api="checkerformatter">Format</button>')
}

function checkmecards(){
    data = $("#addCont").serializeArray();
	//data.push({name: "hashed", value:$('input[name="hashed"]').val()});
    var xhr = new XMLHttpRequest();
    xhr.open('POST', baseUrl+'/checker/startchecker', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 1) {
            resp = xhr.response;
            arrs = resp;
            if (xhr.readyState == XMLHttpRequest.LOADING) {
                var responseArray = xhr.responseText.split('*-')
                responseArray.pop()
                var arrindx = responseArray.length-1;
            }
            if (xhr.readyState == XMLHttpRequest.DONE) {
                alert('Done')
            }
        }
    }
    xhr.send(btoa(JSON.stringify({data})));
}


/**function format(){
	var delim = $("#delimit").val();
	var textArray = $("#cc").val().split("\n");
	if(delim.length != 0 && textArray.length != 0){
		var html = '';
		var htmls = '';
		alldatas = [];
		$.each(textArray, function(k,v){
			var datas = [];
			var vals = v.split(delim);
			$.each(vals, function(c,vc){
				if(vc.indexOf(':') != -1){
					datas.push(vc.split(':')[1]);
				}
				else {
					datas.push(vc);
				}
			}) 
			alldatas.push(datas);
		});
		
		var countcells = [];
		$.each(alldatas, function(cell, cells){
			countcells.push(cells.length); 
			htmls += '<tr class="infosTR">';
			$.each(cells, function(c, va){
				htmls += '<td><p>'+va+'</p></td>';
			})
			htmls += '</tr>';
			
		})
		countcells.sort((a, b) => b- a);
		var heads = '<tr>';
		for (var i = 1; i <= countcells[0]; i++) {
			heads += '<th><select id="sus'+i+'" class="form-control select2" data-api="setinput-'+i+'">\
			<option value="">Unknow</option>\
			<option value="cc">CC</option>\
			<option value="expm">Exp M</option>\
			<option value="expy">Exp Y</option>\
			<option value="expmy">Exp MM/YY</option>\
			<option value="cvv">CVV</option>\
			<option value="fname">First Name</option>\
			<option value="lname">Last Name</option>\
			<option value="fullname">Full Name</option>\
			<option value="address">Address</option>\
			<option value="city">City</option>\
			<option value="state">State</option>\
			<option value="country">Country</option>\
			<option value="zip">Zip</option>\
			<option value="phone">Phone</option>\
			<option value="dob">Dob</option>\
			<option value="zip">IP</option>\
			<option value="other">Other Info</option>\
			</select></th>';
		}
		heads += '</tr>';

		html +='<p class="mt-3">Please setup your fields and indicate which is the right information in the right row</p>\
		<p><span class="text-warning">Warning: </span>If cellular is Unknown, the informations will be skipped and the line will be classed as error.</p>\
		<table class="table table-striped table-bordered mt-3" style="width:100%" id="formatter">';
		html += heads;
		html += htmls;
		html +='</table>';
		$("#cardstext").html(html);
		var nbtn = '<button type="button" class="btn btn-success btn-sm" data-api="createlog">Upload</button>';
		$("#msavebtn").remove();
		$(".modal-footer").append(nbtn);
	}
	else {
		alert('Wrong Input, Please correct your inputs');	
	}
	
}

function setinput(d){

	var ht;
	$('#formatter tr').each(function(index, tr) {
	  $(tr).find('td').eq(d-1).each(function(index, td) {
	  	var text = $(td).children('p').html();
	  	var inputName = $('#sus'+d+' :selected').val();
	    ht = '<input id="hidden'+d+'" class="hidden'+d+'" type="hidden" name="'+inputName+'[]" value="'+text+'">';
	    var inputhid = $(td).children('input[type="hidden"]').val();
	    if(typeof(inputhid) === 'undefined'){
	    	$(td).append(ht);
	    }
	    else {
	    	$("#hidden"+d).remove();
	    	setTimeout(function(){
	    		$(td).append(ht);
	    	},500)
	    }
	  });
	});	
}**/
            

function initSelectIcons(){
	$.get(baseUrl+'/assets/css/box.yml', function(data) {
		var parsedYaml = jsyaml.load(data);
		var options = new Array();
		$.each(parsedYaml.icons, function(index, icon){
			options.push({
				id: icon.id,
				text: '<i class="bx ' + icon.id + '"></i> ' + icon.id
			});
			
		});
		$('.iconselect').select2({
			data:options,

			escapeMarkup: function(markup) {
				return markup;
			},
			width: '100%',
		});
	});
}


if($('.select2').length){
	initselect();
}

if($('.select3').length){
	initselect();
}

if($('.iconselect').length){
	initSelectIcons();
}

function createnewsection(){
	Myajax('Serialize','POST', baseUrl+actualPage+'/createNewSection', 'wizrdForm', 'JSON', 'bsmodel');
}


function refreshstatus(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/getstatus', '', 'JSON', 'sweetalert', ["1"]);
}

function initDelete(d){
	var ds = d.split('|');
	Myajax('id='+ds[0]+'&buytype='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/initDelete', '', 'JSON', 'bsmodel', ["0"]);
}

function updatestatus(d){
	var ds = d.split('|');
	Myajax('id='+ds[0]+'&status='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'mytiket/updatestatus', '', 'JSON', 'sweetalert', ["0"]);
}

function updatesTikettatus(d){
	var ds = d.split('|');
	Myajax('id='+ds[0]+'&status='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'mytiket/updatestatus', '', 'JSON', 'bsmodel', ["0"]);
	$("#msgsupport").remove();
}

function dodelete(d){
	var ds = d.split('|');
	Myajax('id='+ds[0]+'&buytype='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/doDelete', '', 'JSON', 'bsmodel', ["1"]);
}

function InitemptyItems(){
	Myajax('hashed='+$('input[name="hashed"]').val(),'GET', baseUrl+actualPage+'/InitemptyItems', '', 'JSON', 'bsmodel', ["1"]);
}

function InitemptyItems(){
	Myajax('hashed='+$('input[name="hashed"]').val(),'GET', baseUrl+actualPage+'/InitemptyItems', '', 'JSON', 'bsmodel', ["1"]);
}

function emtyitems(){
	Myajax('hashed='+$('input[name="hashed"]').val(),'GET', baseUrl+actualPage+'/emptyItems', '', 'JSON', 'bsmodel', ["1"]);
}


$("#msgsupport").on('submit', function(e){
    e.preventDefault();
    var data = $("#msgsupport").serializeArray();
    data.push({name: "hashed", value:$('input[name="hashed"]').val()});
    $.ajax({
        url: baseUrl+'/mytiket/addresponse',
        type:'POST',
        dataType:'json',
        data:data,
        beforeSend: MyBeforSend(),
        success: function(response){
            $(".chatcontents").append(response.responses);
            $("#msg").val('');
            MyAfterSend();
            $('input[id="crtoken"]').val(response.csrft);
            const chatContainer = document.querySelector('.chatconntainer');
            chatContainer.scrollTop = chatContainer.scrollHeight;
            $("#msg").focus();
        }
    })
    
})

function copy(d){
	$(d).removeClass('btn-warning');
	$(d).addClass('btn-success');
	$(d).attr('data-api', '');
	$(d).children().removeClass('bx-copy');
	$(d).children().addClass('bx-check');

	var text = $(d).parent().parent().children().eq(1).html();
	//text.select();
  	//text.setSelectionRange(0, 99999);
	navigator.clipboard.writeText(text);
	setTimeout(function(){
		$(d).removeClass('btn-success');
		$(d).addClass('btn-warning');
		$(d).attr('data-api', 'copy-this');
		$(d).children().removeClass('bx-check');
		$(d).children().addClass('bx-copy');
	},1000);
}

function startFormat(cctext = null){
	if(cctext == null){
		cctext = $("#cards").val().split("\n");
	}
	//cctext = $("#cards").val().split("\n");
	var delimiter = $("#delimiter").val();
	var x = 0;
	$.each(cctext, function(k,v){
		var line = v.split(delimiter);
		if(line != ''){
			dataCards[x] = line;
			x += 1;
		}
	})
	var countIndexs = dataCards[0].length;
	Tableheaders ='<div class="table-responsive tableFixHead"><table class="table table-bordered" id="cardsTable"><thead><tr>';
	for(var i = 1; i <= countIndexs; i++){
		Tableheaders +='<th>\
							<select class="form-control select2" data-prev="" id="selectformat-'+i+'" data-api="setData-'+i+'">\
								<option value="0">Unset</option>\
								<option value="cc">CC</option>\
								<option value="expm">Exp M</option>\
								<option value="expy">Exp Y</option>\
								<option value="expmy">Exp MM/YY</option>\
								<option value="cvv">CVV</option>\
								<option value="fname">First Name</option>\
								<option value="lname">Last Name</option>\
								<option value="fullname">Full Name</option>\
								<option value="address">Address</option>\
								<option value="city">City</option>\
								<option value="state">State</option>\
								<option value="country">Country</option>\
								<option value="zip">Zip</option>\
								<option value="phone">Phone</option>\
								<option value="dob">Dob</option>\
								<option value="ssn">ssn</option>\
								<option value="email">Email</option>\
								<option value="ip">IP</option>\
								<option value="other">Other Info</option>\
							</select>\
						</th>';
	}
	Tableheaders +='</tr></thead><tbody>';
	$.each(dataCards, function(kl, vl){
		Tableheaders +='<tr>';
		$.each(vl, function(kld, vld){
			Tableheaders +='<td style="min-width:15% !important;">'+vld+'</td>';	
		});
		Tableheaders +='</tr>';
	})
	Tableheaders += '</tbody></table></div>';
	$("#cardsinfodiv").addClass('d-none');
	$(".tablediv").removeClass('d-none');
	$(".tablediv").html(Tableheaders);
	var btnshtml = '<div class="form-group d-grid">\
						<button type="button" class="btn btn-success active" data-api="startUpload" id="startuploadbtn">Start Upload <span class="bx bx-upload"></span></button>\
					</div>\
	 				<div class="form-group d-grid">\
						<button type="button" class="btn btn-warning active" data-api="back">Cancel <span class="bx bx-close"></span></button>\
					</div>';
	$(".controls").html(btnshtml);
	initselect();
}

var datacc = {};
function setData(d){
	var type = $("#selectformat-"+d).val();
	
	var prev = $("#selectformat-"+d).attr("data-prev");
	var prevSelected = $('select[data-prev="'+type+'"]').attr('id');
	$("#"+prevSelected).attr("data-prev", "");
	$("#"+prevSelected).val(0).trigger('change.select2');
	if(type == '0'){
		var key = type;
		Object.keys(datacc)
	    .filter(key => key.startsWith(prev))
	    .forEach(key => {
	        delete datacc[key];
	    });
	}
	else if(prev !== ''){
		var key = type;
		Object.keys(datacc)
	    .filter(key => key.startsWith(prev))
	    .forEach(key => {
	        let newKey = key.replace(prev, type);
	        datacc[newKey] = datacc[key];
	        delete datacc[key];
	        delete datacc[0]
	    });
	}
	var typedata = new Array();
	$('#cardsTable tbody tr td:nth-child('+d+')').each(function(){
		if(type == 'password'){
			typedata += unescape(encodeURIComponent($(this).html()))+"|";
		}
		else {
			typedata += unescape(encodeURIComponent($(this).html()))+"|";
		}
	   	
	});
	datacc[type] = typedata;
	console.log(datacc);
	$("#selectformat-"+d).attr("data-prev", type);	
}

function back(){
	datacc = {};
	dataCards = [];
	$(".tablediv").html('');
	$(".tablediv").addClass('d-none');
	$("#cardsinfodiv").removeClass('d-none');
	var btnshtml = '<div class="form-group d-grid">\
						<button type="button" class="btn btn-danger active" data-api="startFormat">Format Cards <span class="bx bx-upload"></span></button>\
					</div>';
	$(".controls").html(btnshtml);
}

function fnBlink() {
  $(".blink").fadeOut(1000);
  $(".blink").fadeIn(1000);
}

function startUpload(){
	var databaseName = $("#dbname").val();
	var price = $("#price").val();
	var refund = $("#refund").val();
	var timeprocess = 0;
	
	if(databaseName.length > 0){
		let lastResponseLength = false;
		$("#startuploadbtn").remove()
		var prgs = '<div class="card pd-20">\
					<h6 id="uploadstatus" class="tx-12 tx-uppercase tx-inverse tx-bold mg-b-15">Upload Status</h6>\
					<div class="d-flex mg-b-10">\
						<div class="bd-r pd-r-10">\
							<label class="tx-12">Total to Upload </label>\
							<p class="tx-lato tx-inverse tx-bold" id="total">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Valid</label>\
							<p class="tx-lato tx-inverse tx-bold" id="valid">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Invalid</label>\
							<p class="tx-lato tx-inverse tx-bold" id="invalid">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Duplicat</label>\
							<p class="tx-lato tx-inverse tx-bold" id="duplicat">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Time</label>\
							<p class="tx-lato tx-inverse tx-bold" id="counter">...</p>\
						</div>\
					</div><!-- d-flex -->\
					<div class="progress mg-b-10">\
						<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="chekprgs" style="width:0px" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><b id="prgper"></b></div>\
					</div>\
				</div>';
		$(".controls").html(prgs);
		var counter = setInterval(function(){
			timeprocess +=1;
			$("#counter").html(timeprocess+" s");
		},1000);
		endhtml = 'Uploading... <span class="blink text-info" style="font-size:25px"><i class="bx bx-cloud-upload"></i></span>';
       	$("#uploadstatus").html(endhtml);
		setInterval(fnBlink,1000);
		xhr = new XMLHttpRequest();
		xhr.open("POST", baseUrl+'/'+actualPage+'/uploader', true);
		xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xhr.setRequestHeader("Accept", "application/json");
		xhr.onprogress = function(e) {
			try {
				let progressResponse;
			   	let response = e.currentTarget.response;
			   	progressResponse = lastResponseLength ?  response.substring(lastResponseLength) : response;
			   	lastResponseLength = response.length;
			   	try {
			   		let parsedResponse = JSON.parse(progressResponse);
				   	$("#chekprgs").attr('aria-valuenow',parsedResponse.progress);
				   	$("#chekprgs").css('width',parsedResponse.progress+'%');
				   	$("#prgper").html(parsedResponse.progress+'%');
				   	$("#total").html(parsedResponse.total);
				   	$("#valid").html(parsedResponse.valid);
				   	$("#invalid").html(parsedResponse.invalid);
				   	$("#duplicat").html(parsedResponse.duplicate);
				   	if(parsedResponse.line.length > 0){
				   		$("#cardlog").removeClass('d-none');
				   		if(parsedResponse.line !== "|||||||||||"){
				   			$("#log").html(parsedResponse.line);
				   			const logContainer = document.querySelector('#log');
            				logContainer.scrollTop = logContainer.scrollHeight;
				   		}
				   	}
			   	}
			   	catch(e){
			   		var jsonArray = progressResponse.split("}{");
			   		var duplicateds = "";
			   		$.each(jsonArray, function(k,v){
			   			if (v.indexOf("{") !== -1){
						  	v = v+"}"
						}
			   			else if(v.indexOf("}") !== -1 && v.indexOf("{") === -1){
			   				v = "{"+v;
			   			}
			   			else {
			   				v = "{"+v+"}";
			   			}
		   				var parsedResponse = JSON.parse(v);
		   				$("#chekprgs").attr('aria-valuenow',parsedResponse.progress);
					   	$("#chekprgs").css('width',parsedResponse.progress+'%');
					   	$("#prgper").html(parsedResponse.progress+'%');
					   	$("#total").html(parsedResponse.total);
					   	$("#valid").html(parsedResponse.valid);
					   	$("#invalid").html(parsedResponse.invalid);
					   	$("#duplicat").html(parsedResponse.duplicate);
					   	if(parsedResponse.line.length > 0){
					   		$("#cardlog").removeClass('d-none');
					   		if(parsedResponse.line !== "|||||||||||"){
					   			duplicateds += parsedResponse.line;
					   		}
					   	}
			   		});

			   		$("#log").html(duplicateds);
			   		const logContainer = document.querySelector('#log');
    				logContainer.scrollTop = logContainer.scrollHeight;
			   	}
		   	}
		   	catch(error){
		   		//console.log(error)
		   	}
		}
		xhr.onreadystatechange = function() {
		   if (xhr.readyState == 4 && this.status == 200) {
		       	clearInterval(counter);
		       	var btnshtml = '<div class="form-group d-grid">\
		       			<div class="aler bg-warning text-white px-2 py-2 mb-2"><p><b>Your base has been uploaded and watting approval, you will get notification when your base is Approved/Declined.</b></p></div>\
						<button type="button" class="btn btn-success active" data-api="restart">Start New Upload <span class="bx bx-reply"></span></button>\
					</div>';
				$(".controls").append(btnshtml);

		       endhtml = 'Uploading Finished <span class="blink text-success" style="font-size:25px"><i class="bx bx-check"></i></span>';
		       $("#uploadstatus").html(endhtml);
		       setInterval(fnBlink,1000);
		       $("#chekprgs").removeClass('progress-bar-animated')

		   }
		}
		xhr.send("base="+databaseName+"&price="+price+"&refund="+refund+"&data="+JSON.stringify(datacc));
	}
	else {
		alert("Please set the databse name first");
	}

}

function restart(){
	datacc = {};
	dataCards = [];
	$(".tablediv").html('');
	$(".tablediv").addClass('d-none');
	$("#cardsinfodiv").removeClass('d-none');
	var btnshtml = '<div class="form-group d-grid">\
						<button type="button" class="btn btn-danger active" data-api="startFormat">Format Cards <span class="bx bx-upload"></span></button>\
					</div>';
	$("#cards").val('');
	$(".controls").html(btnshtml);
	$("#cardlog").addClass('d-none');
	$('table').remove();
}

function copyInvalid(d){
	$(d).removeClass('btn-info');
	$(d).addClass('btn-success');
	$(d).attr('data-api', '');
	$(d).children().removeClass('bx-copy');
	$(d).children().addClass('bx-check');
	var text = $("#log").html();
	var texttoarray = text.split('<br>');
	var cleaned = "";
	$.each(texttoarray, function(k,v){
		cleaned +=v+"\r";
	})
	navigator.clipboard.writeText(cleaned);
	setTimeout(function(){
		$(d).removeClass('btn-success');
		$(d).addClass('btn-info');
		$(d).attr('data-api', 'copyInvalid-this');
		$(d).children().removeClass('bx-check');
		$(d).children().addClass('bx-copy');
	},1000);
}

$("#cards").bind('paste', function(e) {
    var elem = $(this);
    setTimeout(function() {
        var text = elem.val().split("\n"); 
        startFormat(text);
    }, 10);
});


function removeDuplicates(arr) {
    return arr.filter((item, index) => arr.indexOf(item) === index);
}

function getAddRecords() {
	$('.addtocart').each(function(){
		if ($(this).prop('checked')) {
			toaddCart.push($(this).attr("data-id"))
		}
		else {
			var removeItem = $(this).attr("data-id");
			toaddCart = $.grep(toaddCart, function(value) {
			  	return value != removeItem;
			});
		}
	});
	toaddCart = removeDuplicates(toaddCart);
	if($('.addtocart:checked').length > 1){
		if(actualPage != 'sellerdashboard'){
			var checkbtn = '<button type="btn" class="btn btn-sm btn-success" id="addtocartbtn" data-api="addtocartbunche">Add Selected to My Cart <span class="bx bx-cart"></span></button>';
			$("#addtocartdiv").html(checkbtn);
			$("#addtocartdiv").parent().removeClass('d-none');
		}
		else {
			if(typeof actualPageArray[4] !== 'undefined' && atob(actualPageArray[4]) == '1'){
				var controls = '<div id="btnsContiner" class="float-start">\
								<button type="button" data-api="massinitedit" class="btn btn-warning btn-sm">\
									<i class="bx bx-edit"></i> Edit\
								</button>\
								<button type="button" data-api="massinitdelete" class="btn btn-danger btn-sm">\
									<i class="bx bx-trash"></i> Delete\
								</button>\
							</div>';	
			}
			else if(typeof actualPageArray[4] !== 'undefined' && atob(actualPageArray[4]) == '2'){
				var controls = '<div id="btnsContiner" class="float-start">\
								<button type="button" data-api="massinitedit-cpanel" class="btn btn-warning btn-sm">\
									<i class="bx bx-edit"></i> Edit\
								</button>\
								<button type="button" data-api="massinitdelete-cpanel" class="btn btn-danger btn-sm">\
									<i class="bx bx-trash"></i> Delete\
								</button>\
							</div>';	
			}
			else if(typeof actualPageArray[4] !== 'undefined' && atob(actualPageArray[4]) == '3'){
				var controls = '<div id="btnsContiner" class="float-start">\
								<button type="button" data-api="massinitedit-rdp" class="btn btn-warning btn-sm">\
									<i class="bx bx-edit"></i> Edit\
								</button>\
								<button type="button" data-api="massinitdelete-rdp" class="btn btn-danger btn-sm">\
									<i class="bx bx-trash"></i> Delete\
								</button>\
							</div>';	
			}
			else if(typeof actualPageArray[4] !== 'undefined' && atob(actualPageArray[4]) == '4'){
				var controls = '<div id="btnsContiner" class="float-start">\
								<button type="button" data-api="massinitedit-smtp" class="btn btn-warning btn-sm">\
									<i class="bx bx-edit"></i> Edit\
								</button>\
								<button type="button" data-api="massinitdelete-smtp" class="btn btn-danger btn-sm">\
									<i class="bx bx-trash"></i> Delete\
								</button>\
							</div>';	
			}
			else if(typeof actualPageArray[4] !== 'undefined' && atob(actualPageArray[4]) == '5'){
				var controls = '<div id="btnsContiner" class="float-start">\
								<button type="button" data-api="massinitedit-shell" class="btn btn-warning btn-sm">\
									<i class="bx bx-edit"></i> Edit\
								</button>\
								<button type="button" data-api="massinitdelete-shell" class="btn btn-danger btn-sm">\
									<i class="bx bx-trash"></i> Delete\
								</button>\
							</div>';	
			}
			else if(typeof actualPageArray[4] !== 'undefined') {
				var controls = '<div id="btnsContiner" class="float-start">\
								<button type="button" data-api="massinitedit-'+actualPageArray[4]+'" class="btn btn-warning btn-sm">\
									<i class="bx bx-edit"></i> Edit\
								</button>\
								<button type="button" data-api="massinitdelete-'+actualPageArray[4]+'" class="btn btn-danger btn-sm">\
									<i class="bx bx-trash"></i> Delete\
								</button>\
							</div>';
			}
			else {
				//var typePage = actualPageArray[4] ? actualPageArray[4] :  null;
				var controls = '<div id="btnsContiner" class="float-start">\
								<button type="button" data-api="massinitedit" class="btn btn-warning btn-sm">\
									<i class="bx bx-edit"></i> Edit\
								</button>\
								<button type="button" data-api="massinitdelete" class="btn btn-danger btn-sm">\
									<i class="bx bx-trash"></i> Delete\
								</button>\
							</div>';
			}
			
			$("#DivTabelStockTools").html(controls);
		}
		
	}
	else {
		if(actualPage != 'sellerdashboard'){
			$("#addtocartdiv").html('');
			$("#addtocartdiv").parent().addClass('d-none');
		}
		else {
			$("#DivTabelStockTools").html('');
		}
	}
	//console.log(toaddCart);
}

$("#chekall").change(function () {
	if ($(this).prop('checked')) {
		$('input[type="checkbox"]').not(this).prop('checked', true);
	} 
	else {
		$('input[type="checkbox"]').not(this).prop('checked', false);
	}
	getAddRecords();
})

$('table').on('change', '.addtocart', function () {
	if ($('.addtocart:checked').length == $('.addtocart').length) {
		$('#chekall').prop('checked', true);
	} 
	else {
		$('#chekall').prop('checked', false);
	}
	getAddRecords();
});

function addtocartbunche(){
    $.ajax({
        url: baseUrl+'/finances/addtoBunchCart',
        type:'POST',
        dataType:'json',
        data:'ids='+JSON.stringify(toaddCart),
        success:function(response){
            $("#cartContents").html(response.html);
		    if(response.signal.length && response.signal == '1'){
		        var phtml = '<span class="square-8 bg-danger pos-absolute t-15 r-0 rounded-circle" id="nbcrt"></span>';
		        $("#carticon").html(phtml);
		    }
		    else if(response.html == "0") {
		        $("#nbcrt").remove();
		    }
            $z = 0;
            $.each(toaddCart, function(k,v){
            	var parts = v.split('-');
               	var dataapi = 'removeCartProd-'+parts[0]+'|'+parts[1];
            	$('#buybtn-'+parts[0]).data('api', dataapi);
            	$('#buybtn-'+parts[0]).removeClass('btn-success');
            	$('#buybtn-'+parts[0]).addClass('btn-danger');
            	$('#buybtn-'+parts[0]).children('span').removeClass('bx bx bx-cart');
            	$('#buybtn-'+parts[0]).children('span').addClass('bx bx-message-x');
            	$('#buybtn-'+parts[0]).html('Remove from cart <span class="bx bx-message-x"></span>');
            	$('#'+parts[0]+'-'+parts[1]).remove();
            })
            var ahtml = '<a href="javascript:void(0);" class="" data-api="clearCart" id="clearer">Remove All</a>';
            $("#clearme").html(ahtml);
            $(":input,:button,:checkbox").removeClass('disabled');
            $(":input,:button,:checkbox").prop('disabled', false); 
            $("#chekall").prop('checked', false);
	        $("#addtocartdiv").children().remove();
	        $("#addtocartdiv").parent().addClass('d-none');
	        $("#acart").addClass('with-cart');
            toaddCart = new Array();
        }
    })
}
							
function massedit(){
	var id = [];
	var buyType = [];
	$('.addtocart').each(function(){
		if ($(this).prop('checked')) {
			if($(this).prop('id').indexOf('-') != -1){
				id.push($(this).prop('id').split('-')[0]);
				buyType.push($(this).prop('id').split('-')[1]);
			}
			else {
				id.push($(this).prop('id'));
			}
		}
		else {
			var removeItem = $(this).prop('id');
			toaddCart = $.grep(toaddCart, function(value) {
			  	return value != removeItem;
			});
		}
	});
	
	if(buyType.length){
		html = '<input type="hidden" name="id" value="'+id+'">';
		html += '<input type="hidden" name="buytype" value="'+buyType[0]+'">';
		$("#MasseditUserForm").append(html);
		Myajax('Serialize','POST',  baseUrl+'/sellerdashboard/masseditOther', 'MasseditUserForm', 'JSON', 'bsmodel', ["1"]);
	}
	else {
		html = '<input type="hidden" name="id" value="'+id+'">';
		$("#MasseditUserForm").append(html);
		Myajax('Serialize','POST',  baseUrl+'/sellerdashboard/massedit', 'MasseditUserForm', 'JSON', 'bsmodel', ["1"]);
	}
	$('#chekall').prop('checked', false);
	$("#DivTabelStockTools").html('');
	toaddCart = new Array();
}

function massedits(d){
	var id = [];
	var buyType = [];
	$('.addtocart').each(function(){
		if ($(this).prop('checked')) {
			id.push($(this).prop('id').split('-')[0]);
			buyType.push($(this).prop('id').split('-')[1]);
		}
		else {
			var removeItem = $(this).prop('id');
			toaddCart = $.grep(toaddCart, function(value) {
			  	return value != removeItem;
			});
		}
	});
	html = '<input type="hidden" name="id" value="'+id+'">';
	$("#MasseditUserForm").append(html);
	var dest;
	switch(d){
		case 'cpanel':
			dest = 'cpanel';
		break;
		case 'rdp':
			dest = 'rdp';
		break;
		case 'smtp':
			dest = 'smtp';
		break;
		case 'shell':
			dest = 'shell';
		break;
	}
	Myajax('Serialize','POST',  baseUrl+'/sellerdashboard/massedit'+dest, 'MasseditUserForm', 'JSON', 'bsmodel', ["1"]);
	$('#chekall').prop('checked', false);
	$("#DivTabelStockTools").html('');
	toaddCart = new Array();
}

function massinitdelete(d = ''){
	var id = [];
	var dest;
	$('.addtocart').each(function(){
		if ($(this).prop('checked')) {
			var dataelem = $(this);
			if($(this).prop('id').indexOf('-') != -1){
				id.push($(this).prop('id').split('-')[0]);
				switch(d){
					case 'cpanel':
						dest = $(this).prop('id').split('-')[1];
					break;
					case 'rdp':
						dest = $(this).prop('id').split('-')[1];
					break;
					case 'smtp':
						dest = $(this).prop('id').split('-')[1];
					break;
					case 'shell':
						dest = $(this).prop('id').split('-')[1];
					break;
					default :
						dest = '';
					break;
				}
			}
			else {
				id.push($(this).prop('id'));
				dest = '';
			}
		}
		else {
			var removeItem = $(this).prop('id');
			toaddCart = $.grep(toaddCart, function(value) {
			  	return value != removeItem;
			});
		}
	});
	Myajax('id='+id+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/massrmuserinit'+dest, '', 'JSON', 'bsmodel', ["0"]);
}

function massrmuser(){
	var id = [];
	var buyType = [];
	$('.addtocart').each(function(){
		if ($(this).prop('checked')) {
			var dataelem = $(this);
			if($(this).prop('id').indexOf('-') != -1){
				id.push($(this).prop('id').split('-')[0]);
				buyType.push($(this).prop('id').split('-')[1]);
			}
			else {
				id.push($(this).prop('id'));
			}
		}
		else {
			var removeItem = $(this).prop('id');
			toaddCart = $.grep(toaddCart, function(value) {
			  	return value != removeItem;
			});
		}
	});
	if(buyType.length){
		html = '<input type="hidden" name="id" value="'+id+'">';
		html += '<input type="hidden" name="buytype" value="'+buyType[0]+'">';
		$("#MassDeleteForm").append(html);
		Myajax('Serialize','POST',  baseUrl+'/sellerdashboard/massrmuserothe', 'MassDeleteForm', 'JSON', 'bsmodel', ["1"]);
	}
	else {
		html = '<input type="hidden" name="id" value="'+id+'">';
		$("#MassDeleteForm").append(html);
		Myajax('Serialize','POST',  baseUrl+'/sellerdashboard/massrmuser', 'MassDeleteForm', 'JSON', 'bsmodel', ["1"]);
	}
	$('#chekall').prop('checked', false);
	$("#DivTabelStockTools").html('');
	toaddCart = new Array();

	/**if(buyType[0] == '1'){
		Myajax('id='+id+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/massrmuser', '', 'JSON', 'bsmodel', ["1"]);
	}
	else {
		Myajax('id='+id+'&buytype=1&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+'/sellerdashboard/massrmuserother', '', 'JSON', 'bsmodel', ["1"]);
	}**/
}

function massinitdeletes(d){
	var id = [];
	$('.addtocart').each(function(){
		if ($(this).prop('checked')) {
			var dataelem = $(this);
			if($(this).prop('id').indexOf('-') != -1){
				id.push($(this).prop('id').split('-')[0]);
			}
			else {
				id.push($(this).prop('id'));
			}
		}
		else {
			var removeItem = $(this).prop('id');
			toaddCart = $.grep(toaddCart, function(value) {
			  	return value != removeItem;
			});
		}
	});
	var dest;
	switch(d){
		case 'cpanel':
			dest = 'cpanel';
		break;
		case 'rdp':
			dest = 'rdp';
		break;
		case 'smtp':
			dest = 'smtp';
		break;
		case 'shell':
			dest = 'shell';
		break;
	}
	Myajax('id='+id+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/massrmuserinits'+dest, '', 'JSON', 'bsmodel', ["0"]);
}

function massrms(d){
	var id = [];
	var buyType = [];
	$('.addtocart').each(function(){
		if ($(this).prop('checked')) {
			var dataelem = $(this);
			id.push($(this).prop('id').split('-')[0]);
			buyType.push($(this).prop('id').split('-')[1]);
		}
		else {
			var removeItem = $(this).prop('id');
			toaddCart = $.grep(toaddCart, function(value) {
			  	return value != removeItem;
			});
		}
	});

	html = '<input type="hidden" name="id" value="'+id+'">';
	$("#MassDeleteForm").append(html);
	var dest;
	switch(d){
		case 'cpanel':
			dest = 'cpanel';
		break;
		case 'rdp':
			dest = 'rdp';
		break;
		case 'smtp':
			dest = 'smtp';
		break;
		case 'shell':
			dest = 'shell';
		break;
	}
	Myajax('Serialize','POST',  baseUrl+'/sellerdashboard/massrm'+dest, 'MassDeleteForm', 'JSON', 'bsmodel', ["1"]);
	$('#chekall').prop('checked', false);
	$("#DivTabelStockTools").html('');
	toaddCart = new Array();

	/**if(buyType[0] == '1'){
		Myajax('id='+id+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/massrmuser', '', 'JSON', 'bsmodel', ["1"]);
	}
	else {
		Myajax('id='+id+'&buytype=1&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+'/sellerdashboard/massrmuserother', '', 'JSON', 'bsmodel', ["1"]);
	}**/
}

/**function massinitdelete(){
	var elsc = $(".selected:checkbox:checked");
	var id = [];
	$.each(elsc, function(index, elem){
		if(elem.id.indexOf('-') != -1){
			id.push(elem.id.split('-')[0]);
		}
		else {
			id.push(elem.id);
		}
	})
	Myajax('id='+id+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/massrmuserinit', '', 'JSON', 'bsmodel', ["0"]);
}

function massrmuser(){
	var  a = [];
	var elsc = $(".selected:checkbox:checked");
	var buytypes = [];
	var id = [];
	$.each(elsc, function(index, elem){
		if(elem.id.indexOf('-') != -1){
			var theid = elem.id.split('-')[0];
			var buytype = elem.id.split('-')[1];
			id.push(theid);
			buytypes.push(buytype);
			var aa = '1';
			a.push(aa);

		}
		else {
			id.push(elem.id);
			var aa = '0';
			a.push(aa);

		}
	})
	if(a[0] == 1){
		Myajax('id='+id+'&buytype='+buytypes[0]+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/massrmuser', '', 'JSON', 'bsmodel', ["1"]);
	}
	else {
		Myajax('id='+id+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/massrmuser', '', 'JSON', 'bsmodel', ["1"]);
	}
}**/
//cpanels
function startFormatcpanels(cctext = null){
	if(cctext == null){
		cctext = $("#cpanels").val().split("\n");
	}
	var delimiter = $("#delimiter").val();
	var x = 0;
	$.each(cctext, function(k,v){
		var line = v.split(delimiter);
		if(line != ''){
			dataCards[x] = line;
			x += 1;
		}
	})
	var countIndexs = dataCards[0].length;
	Tableheaders ='<div class="table-responsive tableFixHead"><table class="table table-bordered" id="cardsTable"><thead><tr>';
	for(var i = 1; i <= countIndexs; i++){
		Tableheaders +='<th>\
							<select class="form-control select2" data-prev="" id="selectformat-'+i+'" data-api="setData-'+i+'">\
								<option value="0">Unset</option>\
								<option value="host">Host</option>\
								<option value="username">Username</option>\
								<option value="password">Password</option>\
							</select>\
						</th>';
	}
	Tableheaders +='</tr></thead><tbody>';
	$.each(dataCards, function(kl, vl){
		Tableheaders +='<tr>';
		$.each(vl, function(kld, vld){
			Tableheaders +='<td style="min-width:15% !important;">'+vld+'</td>';	
		});
		Tableheaders +='</tr>';
	})
	Tableheaders += '</tbody></table></div>';
	$("#cardsinfodiv").addClass('d-none');
	$(".tablediv").removeClass('d-none');
	$(".tablediv").html(Tableheaders);
	var btnshtml = '<div class="form-group d-grid">\
						<button type="button" class="btn btn-success active" data-api="startUploadcpanel" id="startuploadbtn">Start Upload <span class="bx bx-upload"></span></button>\
					</div>\
	 				<div class="form-group d-grid">\
						<button type="button" class="btn btn-warning active" data-api="backcpanel">Cancel <span class="bx bx-close"></span></button>\
					</div>';
	$(".controls").html(btnshtml);
	initselect();
}

function backcpanel(){
	datacc = {};
	dataCards = [];
	$(".tablediv").html('');
	$(".tablediv").addClass('d-none');
	$("#cardsinfodiv").removeClass('d-none');
	var btnshtml = '<div class="form-group d-grid">\
						<button type="button" class="btn btn-danger active" data-api="startFormatcpanels">Format cpanels List <span class="bx bx-upload"></span></button>\
					</div>';
	$(".controls").html(btnshtml);
}
function startUploadcpanel(){
	var price = $("#price").val();
	var timeprocess = 0;
	
	let lastResponseLength = false;
	$("#startuploadbtn").remove()
	var prgs = '<div class="card pd-20">\
				<h6 id="uploadstatus" class="tx-12 tx-uppercase tx-inverse tx-bold mg-b-15">Upload Status</h6>\
				<div class="d-flex mg-b-10">\
					<div class="bd-r pd-r-10">\
						<label class="tx-12">Total to Upload </label>\
						<p class="tx-lato tx-inverse tx-bold" id="total">...</p>\
					</div>\
					<div class="bd-r pd-x-10">\
						<label class="tx-12">Valid</label>\
						<p class="tx-lato tx-inverse tx-bold" id="valid">...</p>\
					</div>\
					<div class="bd-r pd-x-10">\
						<label class="tx-12">Invalid</label>\
						<p class="tx-lato tx-inverse tx-bold" id="invalid">...</p>\
					</div>\
					<div class="bd-r pd-x-10">\
						<label class="tx-12">Duplicat</label>\
						<p class="tx-lato tx-inverse tx-bold" id="duplicat">...</p>\
					</div>\
					<div class="bd-r pd-x-10">\
						<label class="tx-12">Time</label>\
						<p class="tx-lato tx-inverse tx-bold" id="counter">...</p>\
					</div>\
				</div><!-- d-flex -->\
				<div class="progress mg-b-10">\
					<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="chekprgs" style="width:0px" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><b id="prgper"></b></div>\
				</div>\
			</div>';
	$(".controls").html(prgs);
	var counter = setInterval(function(){
		timeprocess +=1;
		$("#counter").html(timeprocess+" s");
	},1000);
	endhtml = 'Uploading... <span class="blink text-info" style="font-size:25px"><i class="bx bx-cloud-upload"></i></span>';
   	$("#uploadstatus").html(endhtml);
	setInterval(fnBlink,1000);
	xhr = new XMLHttpRequest();
	xhr.open("POST", baseUrl+'/'+actualPage+'/uploader', true);
	xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhr.setRequestHeader("Accept", "application/json");
	xhr.onprogress = function(e) {
		try {
			let progressResponse;
		   	let response = e.currentTarget.response;
		   	progressResponse = lastResponseLength ?  response.substring(lastResponseLength) : response;
		   	lastResponseLength = response.length;
		   	try {
		   		let parsedResponse = JSON.parse(progressResponse);
			   	$("#chekprgs").attr('aria-valuenow',parsedResponse.progress);
			   	$("#chekprgs").css('width',parsedResponse.progress+'%');
			   	$("#prgper").html(parsedResponse.progress+'%');
			   	$("#total").html(parsedResponse.total);
			   	$("#valid").html(parsedResponse.valid);
			   	$("#invalid").html(parsedResponse.invalid);
			   	$("#duplicat").html(parsedResponse.duplicate);
			   	if(parsedResponse.line.length > 0){
			   		$("#cardlog").removeClass('d-none');
			   		if(parsedResponse.line !== "|||||||||||"){
			   			$("#log").html(parsedResponse.line);
			   			const logContainer = document.querySelector('#log');
        				logContainer.scrollTop = logContainer.scrollHeight;
			   		}
			   	}
		   	}
		   	catch(e){
		   		var jsonArray = progressResponse.split("}{");
		   		var duplicateds = "";
		   		$.each(jsonArray, function(k,v){
		   			if (v.indexOf("{") !== -1){
					  	v = v+"}"
					}
		   			else if(v.indexOf("}") !== -1 && v.indexOf("{") === -1){
		   				v = "{"+v;
		   			}
		   			else {
		   				v = "{"+v+"}";
		   			}
	   				var parsedResponse = JSON.parse(v);
	   				$("#chekprgs").attr('aria-valuenow',parsedResponse.progress);
				   	$("#chekprgs").css('width',parsedResponse.progress+'%');
				   	$("#prgper").html(parsedResponse.progress+'%');
				   	$("#total").html(parsedResponse.total);
				   	$("#valid").html(parsedResponse.valid);
				   	$("#invalid").html(parsedResponse.invalid);
				   	$("#duplicat").html(parsedResponse.duplicate);
				   	if(parsedResponse.line.length > 0){
				   		$("#cardlog").removeClass('d-none');
				   		if(parsedResponse.line !== "|||||||||||"){
				   			duplicateds += parsedResponse.line;
				   		}
				   	}
		   		});

		   		$("#log").html(duplicateds);
		   		const logContainer = document.querySelector('#log');
				logContainer.scrollTop = logContainer.scrollHeight;
		   	}
	   	}
	   	catch(error){
	   		//console.log(error)
	   	}
	}
	xhr.onreadystatechange = function() {
	   if (xhr.readyState == 4 && this.status == 200) {
	       	clearInterval(counter);
	       	var btnshtml = '<div class="form-group d-grid">\
					<button type="button" class="btn btn-success active" data-api="restartcpanel">Start New Upload <span class="bx bx-reply"></span></button>\
				</div>';
			$(".controls").append(btnshtml);

	       endhtml = 'Uploading Finished <span class="blink text-success" style="font-size:25px"><i class="bx bx-check"></i></span>';
	       $("#uploadstatus").html(endhtml);
	       setInterval(fnBlink,1000);
	       $("#chekprgs").removeClass('progress-bar-animated')

	   }
	}
	//xhr.send("price="+price+"&data="+JSON.stringify(datacc));
	xhr.send("price="+price+"&data="+btoa(JSON.stringify(datacc)));
}
function restartcpanel(){
	datacc = {};
	dataCards = [];
	$(".tablediv").html('');
	$(".tablediv").addClass('d-none');
	$("#cardsinfodiv").removeClass('d-none');
	var btnshtml = '<div class="form-group d-grid">\
						<button type="button" class="btn btn-danger active" data-api="startFormatcpanels">Format Cards <span class="bx bx-upload"></span></button>\
					</div>';
	$("#cpanels").val('');
	$(".controls").html(btnshtml);
	$("#cardlog").addClass('d-none');
	$('table').remove();
}

$("#cpanels").bind('paste', function(e) {
    var elem = $(this);
    setTimeout(function() {
        var text = elem.val().split("\n"); 
        startFormatcpanels(text);
    }, 10);
});


//RDP
	function startFormatrdp(cctext = null){
		if(cctext == null){
			cctext = $("#rdp").val().split("\n");
		}
		var delimiter = $("#delimiter").val();
		var x = 0;
		$.each(cctext, function(k,v){
			var line = v.split(delimiter);
			if(line != ''){
				dataCards[x] = line;
				x += 1;
			}
		})
		var countIndexs = dataCards[0].length;
		Tableheaders ='<div class="table-responsive tableFixHead"><table class="table table-bordered" id="cardsTable"><thead><tr>';
		for(var i = 1; i <= countIndexs; i++){
			Tableheaders +='<th>\
								<select class="form-control select2" data-prev="" id="selectformat-'+i+'" data-api="setData-'+i+'">\
									<option value="0">Unset</option>\
									<option value="host">Host</option>\
									<option value="user">Username</option>\
									<option value="pass">Password</option>\
									<option value="system">System</option>\
									<option value="ram">RAM</option>\
									<option value="hddsize">HDD Size</option>\
								</select>\
							</th>';
		}
		Tableheaders +='</tr></thead><tbody>';
		$.each(dataCards, function(kl, vl){
			Tableheaders +='<tr>';
			$.each(vl, function(kld, vld){
				Tableheaders +='<td style="min-width:15% !important;">'+vld+'</td>';	
			});
			Tableheaders +='</tr>';
		})
		Tableheaders += '</tbody></table></div>';
		$("#cardsinfodiv").addClass('d-none');
		$(".tablediv").removeClass('d-none');
		$(".tablediv").html(Tableheaders);
		var btnshtml = '<div class="form-group d-grid">\
							<button type="button" class="btn btn-success active" data-api="startUploadrdp" id="startuploadbtn">Start Upload <span class="bx bx-upload"></span></button>\
						</div>\
		 				<div class="form-group d-grid">\
							<button type="button" class="btn btn-warning active" data-api="backrdp">Cancel <span class="bx bx-close"></span></button>\
						</div>';
		$(".controls").html(btnshtml);
		initselect();
	}


	function backrdp(){
		datacc = {};
		dataCards = [];
		$(".tablediv").html('');
		$(".tablediv").addClass('d-none');
		$("#cardsinfodiv").removeClass('d-none');
		var btnshtml = '<div class="form-group d-grid">\
							<button type="button" class="btn btn-danger active" data-api="startFormatrdp">Format RDP List <span class="bx bx-upload"></span></button>\
						</div>';
		$(".controls").html(btnshtml);
	}

	function startUploadrdp(){
		var price = $("#price").val();
		var timeprocess = 0;
		
		let lastResponseLength = false;
		$("#startuploadbtn").remove()
		var prgs = '<div class="card pd-20">\
					<h6 id="uploadstatus" class="tx-12 tx-uppercase tx-inverse tx-bold mg-b-15">Upload Status</h6>\
					<div class="d-flex mg-b-10">\
						<div class="bd-r pd-r-10">\
							<label class="tx-12">Total to Upload </label>\
							<p class="tx-lato tx-inverse tx-bold" id="total">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Valid</label>\
							<p class="tx-lato tx-inverse tx-bold" id="valid">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Invalid</label>\
							<p class="tx-lato tx-inverse tx-bold" id="invalid">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Duplicat</label>\
							<p class="tx-lato tx-inverse tx-bold" id="duplicat">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Time</label>\
							<p class="tx-lato tx-inverse tx-bold" id="counter">...</p>\
						</div>\
					</div><!-- d-flex -->\
					<div class="progress mg-b-10">\
						<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="chekprgs" style="width:0px" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><b id="prgper"></b></div>\
					</div>\
				</div>';
		$(".controls").html(prgs);
		var counter = setInterval(function(){
			timeprocess +=1;
			$("#counter").html(timeprocess+" s");
		},1000);
		endhtml = 'Uploading... <span class="blink text-info" style="font-size:25px"><i class="bx bx-cloud-upload"></i></span>';
	   	$("#uploadstatus").html(endhtml);
		setInterval(fnBlink,1000);
		xhr = new XMLHttpRequest();
		xhr.open("POST", baseUrl+'/'+actualPage+'/uploader', true);
		xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xhr.setRequestHeader("Accept", "application/json");
		xhr.onprogress = function(e) {
			try {
				let progressResponse;
			   	let response = e.currentTarget.response;
			   	progressResponse = lastResponseLength ?  response.substring(lastResponseLength) : response;
			   	lastResponseLength = response.length;
			   	try {
			   		let parsedResponse = JSON.parse(progressResponse);
				   	$("#chekprgs").attr('aria-valuenow',parsedResponse.progress);
				   	$("#chekprgs").css('width',parsedResponse.progress+'%');
				   	$("#prgper").html(parsedResponse.progress+'%');
				   	$("#total").html(parsedResponse.total);
				   	$("#valid").html(parsedResponse.valid);
				   	$("#invalid").html(parsedResponse.invalid);
				   	$("#duplicat").html(parsedResponse.duplicate);
				   	if(parsedResponse.line.length > 0){
				   		$("#cardlog").removeClass('d-none');
				   		if(parsedResponse.line !== "|||||||||||"){
				   			$("#log").html(parsedResponse.line);
				   			const logContainer = document.querySelector('#log');
	        				logContainer.scrollTop = logContainer.scrollHeight;
				   		}
				   	}
			   	}
			   	catch(e){
			   		var jsonArray = progressResponse.split("}{");
			   		var duplicateds = "";
			   		$.each(jsonArray, function(k,v){
			   			if (v.indexOf("{") !== -1){
						  	v = v+"}"
						}
			   			else if(v.indexOf("}") !== -1 && v.indexOf("{") === -1){
			   				v = "{"+v;
			   			}
			   			else {
			   				v = "{"+v+"}";
			   			}
		   				var parsedResponse = JSON.parse(v);
		   				$("#chekprgs").attr('aria-valuenow',parsedResponse.progress);
					   	$("#chekprgs").css('width',parsedResponse.progress+'%');
					   	$("#prgper").html(parsedResponse.progress+'%');
					   	$("#total").html(parsedResponse.total);
					   	$("#valid").html(parsedResponse.valid);
					   	$("#invalid").html(parsedResponse.invalid);
					   	$("#duplicat").html(parsedResponse.duplicate);
					   	if(parsedResponse.line.length > 0){
					   		$("#cardlog").removeClass('d-none');
					   		if(parsedResponse.line !== "|||||||||||"){
					   			duplicateds += parsedResponse.line;
					   		}
					   	}
			   		});

			   		$("#log").html(duplicateds);
			   		const logContainer = document.querySelector('#log');
					logContainer.scrollTop = logContainer.scrollHeight;
			   	}
		   	}
		   	catch(error){
		   		//console.log(error)
		   	}
		}
		xhr.onreadystatechange = function() {
		   if (xhr.readyState == 4 && this.status == 200) {
		       	clearInterval(counter);
		       	var btnshtml = '<div class="form-group d-grid">\
						<button type="button" class="btn btn-success active" data-api="restartrdp">Start New Upload <span class="bx bx-reply"></span></button>\
					</div>';
				$(".controls").append(btnshtml);

		       endhtml = 'Uploading Finished <span class="blink text-success" style="font-size:25px"><i class="bx bx-check"></i></span>';
		       $("#uploadstatus").html(endhtml);
		       setInterval(fnBlink,1000);
		       $("#chekprgs").removeClass('progress-bar-animated')

		   }
		}
		xhr.send("price="+price+"&data="+JSON.stringify(datacc));
	}

	function restartrdp(){
		datacc = {};
		dataCards = [];
		$(".tablediv").html('');
		$(".tablediv").addClass('d-none');
		$("#cardsinfodiv").removeClass('d-none');
		var btnshtml = '<div class="form-group d-grid">\
							<button type="button" class="btn btn-danger active" data-api="startFormatrdp">Format SMTP List <span class="bx bx-upload"></span></button>\
						</div>';
		$("#cpanels").val('');
		$(".controls").html(btnshtml);
		$("#cardlog").addClass('d-none');
		$('table').remove();
	}

	$("#rdp").bind('paste', function(e) {
	    var elem = $(this);
	    setTimeout(function() {
	        var text = elem.val().split("\n"); 
	        startFormatrdp(text);
	    }, 10);
	});
//end RDP

//smtp
	function startFormatsmtp(cctext = null){
		if(cctext == null){
			cctext = $("#smtp").val().split("\n");
		}
		var delimiter = $("#delimiter").val();
		var x = 0;
		$.each(cctext, function(k,v){
			var line = v.split(delimiter);
			if(line != ''){
				dataCards[x] = line;
				x += 1;
			}
		})
		var countIndexs = dataCards[0].length;
		Tableheaders ='<div class="table-responsive tableFixHead"><table class="table table-bordered" id="cardsTable"><thead><tr>';
		for(var i = 1; i <= countIndexs; i++){
			Tableheaders +='<th>\
								<select class="form-control select2" data-prev="" id="selectformat-'+i+'" data-api="setData-'+i+'">\
									<option value="0">Unset</option>\
									<option value="host">Host</option>\
									<option value="port">Port</option>\
									<option value="user">Username</option>\
									<option value="pass">Password</option>\
								</select>\
							</th>';
		}
		Tableheaders +='</tr></thead><tbody>';
		$.each(dataCards, function(kl, vl){
			Tableheaders +='<tr>';
			$.each(vl, function(kld, vld){
				Tableheaders +='<td style="min-width:15% !important;">'+vld+'</td>';	
			});
			Tableheaders +='</tr>';
		})
		Tableheaders += '</tbody></table></div>';
		$("#cardsinfodiv").addClass('d-none');
		$(".tablediv").removeClass('d-none');
		$(".tablediv").html(Tableheaders);
		var btnshtml = '<div class="form-group d-grid">\
							<button type="button" class="btn btn-success active" data-api="startUploadsmtp" id="startuploadbtn">Start Upload <span class="bx bx-upload"></span></button>\
						</div>\
		 				<div class="form-group d-grid">\
							<button type="button" class="btn btn-warning active" data-api="backsmtp">Cancel <span class="bx bx-close"></span></button>\
						</div>';
		$(".controls").html(btnshtml);
		initselect();
	}

	function backsmtp(){
		datacc = {};
		dataCards = [];
		$(".tablediv").html('');
		$(".tablediv").addClass('d-none');
		$("#cardsinfodiv").removeClass('d-none');
		var btnshtml = '<div class="form-group d-grid">\
							<button type="button" class="btn btn-danger active" data-api="startFormatsmtp">Format SMTP List <span class="bx bx-upload"></span></button>\
						</div>';
		$(".controls").html(btnshtml);
	}

	function startUploadsmtp(){
		var price = $("#price").val();
		var timeprocess = 0;
		
		let lastResponseLength = false;
		$("#startuploadbtn").remove()
		var prgs = '<div class="card pd-20">\
					<h6 id="uploadstatus" class="tx-12 tx-uppercase tx-inverse tx-bold mg-b-15">Upload Status</h6>\
					<div class="d-flex mg-b-10">\
						<div class="bd-r pd-r-10">\
							<label class="tx-12">Total to Upload </label>\
							<p class="tx-lato tx-inverse tx-bold" id="total">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Valid</label>\
							<p class="tx-lato tx-inverse tx-bold" id="valid">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Invalid</label>\
							<p class="tx-lato tx-inverse tx-bold" id="invalid">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Duplicat</label>\
							<p class="tx-lato tx-inverse tx-bold" id="duplicat">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Time</label>\
							<p class="tx-lato tx-inverse tx-bold" id="counter">...</p>\
						</div>\
					</div><!-- d-flex -->\
					<div class="progress mg-b-10">\
						<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="chekprgs" style="width:0px" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><b id="prgper"></b></div>\
					</div>\
				</div>';
		$(".controls").html(prgs);
		var counter = setInterval(function(){
			timeprocess +=1;
			$("#counter").html(timeprocess+" s");
		},1000);
		endhtml = 'Uploading... <span class="blink text-info" style="font-size:25px"><i class="bx bx-cloud-upload"></i></span>';
	   	$("#uploadstatus").html(endhtml);
		setInterval(fnBlink,1000);
		xhr = new XMLHttpRequest();
		xhr.open("POST", baseUrl+'/'+actualPage+'/uploader', true);
		xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xhr.setRequestHeader("Accept", "application/json");
		xhr.onprogress = function(e) {
			try {
				let progressResponse;
			   	let response = e.currentTarget.response;
			   	progressResponse = lastResponseLength ?  response.substring(lastResponseLength) : response;
			   	lastResponseLength = response.length;
			   	try {
			   		let parsedResponse = JSON.parse(progressResponse);
				   	$("#chekprgs").attr('aria-valuenow',parsedResponse.progress);
				   	$("#chekprgs").css('width',parsedResponse.progress+'%');
				   	$("#prgper").html(parsedResponse.progress+'%');
				   	$("#total").html(parsedResponse.total);
				   	$("#valid").html(parsedResponse.valid);
				   	$("#invalid").html(parsedResponse.invalid);
				   	$("#duplicat").html(parsedResponse.duplicate);
				   	if(parsedResponse.line.length > 0){
				   		$("#cardlog").removeClass('d-none');
				   		if(parsedResponse.line !== "|||||||||||"){
				   			$("#log").html(parsedResponse.line);
				   			const logContainer = document.querySelector('#log');
	        				logContainer.scrollTop = logContainer.scrollHeight;
				   		}
				   	}
			   	}
			   	catch(e){
			   		var jsonArray = progressResponse.split("}{");
			   		var duplicateds = "";
			   		$.each(jsonArray, function(k,v){
			   			if (v.indexOf("{") !== -1){
						  	v = v+"}"
						}
			   			else if(v.indexOf("}") !== -1 && v.indexOf("{") === -1){
			   				v = "{"+v;
			   			}
			   			else {
			   				v = "{"+v+"}";
			   			}
		   				var parsedResponse = JSON.parse(v);
		   				$("#chekprgs").attr('aria-valuenow',parsedResponse.progress);
					   	$("#chekprgs").css('width',parsedResponse.progress+'%');
					   	$("#prgper").html(parsedResponse.progress+'%');
					   	$("#total").html(parsedResponse.total);
					   	$("#valid").html(parsedResponse.valid);
					   	$("#invalid").html(parsedResponse.invalid);
					   	$("#duplicat").html(parsedResponse.duplicate);
					   	if(parsedResponse.line.length > 0){
					   		$("#cardlog").removeClass('d-none');
					   		if(parsedResponse.line !== "|||||||||||"){
					   			duplicateds += parsedResponse.line;
					   		}
					   	}
			   		});

			   		$("#log").html(duplicateds);
			   		const logContainer = document.querySelector('#log');
					logContainer.scrollTop = logContainer.scrollHeight;
			   	}
		   	}
		   	catch(error){
		   		//console.log(error)
		   	}
		}
		xhr.onreadystatechange = function() {
		   if (xhr.readyState == 4 && this.status == 200) {
		       	clearInterval(counter);
		       	var btnshtml = '<div class="form-group d-grid">\
						<button type="button" class="btn btn-success active" data-api="restartsmtp">Start New Upload <span class="bx bx-reply"></span></button>\
					</div>';
				$(".controls").append(btnshtml);

		       endhtml = 'Uploading Finished <span class="blink text-success" style="font-size:25px"><i class="bx bx-check"></i></span>';
		       $("#uploadstatus").html(endhtml);
		       setInterval(fnBlink,1000);
		       $("#chekprgs").removeClass('progress-bar-animated')

		   }
		}
		xhr.send("price="+price+"&data="+JSON.stringify(datacc));
	}

	function restartsmtp(){
		datacc = {};
		dataCards = [];
		$(".tablediv").html('');
		$(".tablediv").addClass('d-none');
		$("#cardsinfodiv").removeClass('d-none');
		var btnshtml = '<div class="form-group d-grid">\
							<button type="button" class="btn btn-danger active" data-api="startFormtsmtp">Format SMTP List <span class="bx bx-upload"></span></button>\
						</div>';
		$("#cpanels").val('');
		$(".controls").html(btnshtml);
		$("#cardlog").addClass('d-none');
		$('table').remove();
	}

	$("#smtp").bind('paste', function(e) {
	    var elem = $(this);
	    setTimeout(function() {
	        var text = elem.val().split("\n"); 
	        startFormtsmtp(text);
	    }, 10);
	});
//end Smtp

//shell
	function startFormatshell(cctext = null){
		if(cctext == null){
			cctext = $("#shell").val().split("\n");
		}
		var delimiter = $("#delimiter").val();
		var x = 0;
		$.each(cctext, function(k,v){
			var line = v.split(delimiter);
			if(line != ''){
				dataCards[x] = line;
				x += 1;
			}
		})
		var countIndexs = dataCards[0].length;
		Tableheaders ='<div class="table-responsive tableFixHead"><table class="table table-bordered" id="cardsTable"><thead><tr>';
		for(var i = 1; i <= countIndexs; i++){
			Tableheaders +='<th>\
								<select class="form-control select2" data-prev="" id="selectformat-'+i+'" data-api="setData-'+i+'">\
									<option value="0">Unset</option>\
									<option value="host">Host</option>\
									<option value="username">password</option>\
								</select>\
							</th>';
		}
		Tableheaders +='</tr></thead><tbody>';
		$.each(dataCards, function(kl, vl){
			Tableheaders +='<tr>';
			$.each(vl, function(kld, vld){
				Tableheaders +='<td style="min-width:15% !important;">'+vld+'</td>';	
			});
			Tableheaders +='</tr>';
		})
		Tableheaders += '</tbody></table></div>';
		$("#cardsinfodiv").addClass('d-none');
		$(".tablediv").removeClass('d-none');
		$(".tablediv").html(Tableheaders);
		var btnshtml = '<div class="form-group d-grid">\
							<button type="button" class="btn btn-success active" data-api="startUploadshell" id="startuploadbtn">Start Upload <span class="bx bx-upload"></span></button>\
						</div>\
		 				<div class="form-group d-grid">\
							<button type="button" class="btn btn-warning active" data-api="backshell">Cancel <span class="bx bx-close"></span></button>\
						</div>';
		$(".controls").html(btnshtml);
		initselect();
	}


	function backshell(){
		datacc = {};
		dataCards = [];
		$(".tablediv").html('');
		$(".tablediv").addClass('d-none');
		$("#cardsinfodiv").removeClass('d-none');
		var btnshtml = '<div class="form-group d-grid">\
							<button type="button" class="btn btn-danger active" data-api="startFormatshell">Format Shell List <span class="bx bx-upload"></span></button>\
						</div>';
		$(".controls").html(btnshtml);
	}

	function startUploadshell(){
		var price = $("#price").val();
		var timeprocess = 0;
		
		let lastResponseLength = false;
		$("#startuploadbtn").remove()
		var prgs = '<div class="card pd-20">\
					<h6 id="uploadstatus" class="tx-12 tx-uppercase tx-inverse tx-bold mg-b-15">Upload Status</h6>\
					<div class="d-flex mg-b-10">\
						<div class="bd-r pd-r-10">\
							<label class="tx-12">Total to Upload </label>\
							<p class="tx-lato tx-inverse tx-bold" id="total">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Valid</label>\
							<p class="tx-lato tx-inverse tx-bold" id="valid">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Invalid</label>\
							<p class="tx-lato tx-inverse tx-bold" id="invalid">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Duplicat</label>\
							<p class="tx-lato tx-inverse tx-bold" id="duplicat">...</p>\
						</div>\
						<div class="bd-r pd-x-10">\
							<label class="tx-12">Time</label>\
							<p class="tx-lato tx-inverse tx-bold" id="counter">...</p>\
						</div>\
					</div><!-- d-flex -->\
					<div class="progress mg-b-10">\
						<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="chekprgs" style="width:0px" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><b id="prgper"></b></div>\
					</div>\
				</div>';
		$(".controls").html(prgs);
		var counter = setInterval(function(){
			timeprocess +=1;
			$("#counter").html(timeprocess+" s");
		},1000);
		endhtml = 'Uploading... <span class="blink text-info" style="font-size:25px"><i class="bx bx-cloud-upload"></i></span>';
	   	$("#uploadstatus").html(endhtml);
		setInterval(fnBlink,1000);
		xhr = new XMLHttpRequest();
		xhr.open("POST", baseUrl+'/'+actualPage+'/uploader', true);
		xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xhr.setRequestHeader("Accept", "application/json");
		xhr.onprogress = function(e) {
			try {
				let progressResponse;
			   	let response = e.currentTarget.response;
			   	progressResponse = lastResponseLength ?  response.substring(lastResponseLength) : response;
			   	lastResponseLength = response.length;
			   	try {
			   		let parsedResponse = JSON.parse(progressResponse);
				   	$("#chekprgs").attr('aria-valuenow',parsedResponse.progress);
				   	$("#chekprgs").css('width',parsedResponse.progress+'%');
				   	$("#prgper").html(parsedResponse.progress+'%');
				   	$("#total").html(parsedResponse.total);
				   	$("#valid").html(parsedResponse.valid);
				   	$("#invalid").html(parsedResponse.invalid);
				   	$("#duplicat").html(parsedResponse.duplicate);
				   	if(parsedResponse.line.length > 0){
				   		$("#cardlog").removeClass('d-none');
				   		if(parsedResponse.line !== "|||||||||||"){
				   			$("#log").html(parsedResponse.line);
				   			const logContainer = document.querySelector('#log');
	        				logContainer.scrollTop = logContainer.scrollHeight;
				   		}
				   	}
			   	}
			   	catch(e){
			   		var jsonArray = progressResponse.split("}{");
			   		var duplicateds = "";
			   		$.each(jsonArray, function(k,v){
			   			if (v.indexOf("{") !== -1){
						  	v = v+"}"
						}
			   			else if(v.indexOf("}") !== -1 && v.indexOf("{") === -1){
			   				v = "{"+v;
			   			}
			   			else {
			   				v = "{"+v+"}";
			   			}
		   				var parsedResponse = JSON.parse(v);
		   				$("#chekprgs").attr('aria-valuenow',parsedResponse.progress);
					   	$("#chekprgs").css('width',parsedResponse.progress+'%');
					   	$("#prgper").html(parsedResponse.progress+'%');
					   	$("#total").html(parsedResponse.total);
					   	$("#valid").html(parsedResponse.valid);
					   	$("#invalid").html(parsedResponse.invalid);
					   	$("#duplicat").html(parsedResponse.duplicate);
					   	if(parsedResponse.line.length > 0){
					   		$("#cardlog").removeClass('d-none');
					   		if(parsedResponse.line !== "|||||||||||"){
					   			duplicateds += parsedResponse.line;
					   		}
					   	}
			   		});

			   		$("#log").html(duplicateds);
			   		const logContainer = document.querySelector('#log');
					logContainer.scrollTop = logContainer.scrollHeight;
			   	}
		   	}
		   	catch(error){
		   		//console.log(error)
		   	}
		}
		xhr.onreadystatechange = function() {
		   if (xhr.readyState == 4 && this.status == 200) {
		       	clearInterval(counter);
		       	var btnshtml = '<div class="form-group d-grid">\
						<button type="button" class="btn btn-success active" data-api="restartshell">Start New Upload <span class="bx bx-reply"></span></button>\
					</div>';
				$(".controls").append(btnshtml);

		       endhtml = 'Uploading Finished <span class="blink text-success" style="font-size:25px"><i class="bx bx-check"></i></span>';
		       $("#uploadstatus").html(endhtml);
		       setInterval(fnBlink,1000);
		       $("#chekprgs").removeClass('progress-bar-animated')

		   }
		}
		xhr.send("price="+price+"&data="+JSON.stringify(datacc));
	}

	function restartshell(){
		datacc = {};
		dataCards = [];
		$(".tablediv").html('');
		$(".tablediv").addClass('d-none');
		$("#cardsinfodiv").removeClass('d-none');
		var btnshtml = '<div class="form-group d-grid">\
							<button type="button" class="btn btn-danger active" data-api="startFormatshell">Format Shell List <span class="bx bx-upload"></span></button>\
						</div>';
		$("#cpanels").val('');
		$(".controls").html(btnshtml);
		$("#cardlog").addClass('d-none');
		$('table').remove();
	}

	$("#shell").bind('paste', function(e) {
	    var elem = $(this);
	    setTimeout(function() {
	        var text = elem.val().split("\n"); 
	        startFormatshell(text);
	    }, 10);
	});
//end Shell

function removeCpanelDuplicates(arr) {
    return arr.filter((item, index) => arr.indexOf(item) === index);
}

function getChakedCpanelTocheck() {
	$('.addtocheck-cpanel').each(function(){
		if ($(this).prop('checked')) {
			tocheck.push($(this).attr("data-id"))
		}
		else {
			var removeItem = $(this).attr("data-id");
			tocheck = $.grep(tocheck, function(value) {
			  	return value != removeItem;
			});
		}
	});

	tocheck = removeCpanelDuplicates(tocheck);
	if($('.addtocheck-cpanel:checked').length > 1){
		var checkbtn = '<div class="input-group"><button type="btn" class="btn btn-sm btn-indigo" id="checkerbtn" data-api="checkselected-cpanel">Check Selected</button>';
		checkbtn += '<button type="btn" class="btn btn-sm btn-success" id="addtocartbtn" data-api="addtocartprods">Add to Cart <span class="bx bx-cart"></span></button></div>';
		$(".addtocartmain").removeClass('d-none');
		$("#addtocartdiv").html(checkbtn);
	}
	else if($('.addtocheck-cpanel:checked').length == 1){
		var checkbtn = '<div class="input-group"><button type="btn" class="btn btn-sm btn-indigo" id="checkerbtn" data-api="checkselected-cpanel">Check Selected</button>';
		$(".addtocartmain").removeClass('d-none');
		$("#addtocartdiv").html(checkbtn);
	}
	else {
		$("#addtocartdiv").html('');
		$(".addtocartmain").addClass('d-none');
	}
}

$("#chekall-cpanel").change(function () {

	if ($(this).prop('checked')) {
		$('input[type="checkbox"]').not(this).prop('checked', true);
	} 
	else {
		$('input[type="checkbox"]').not(this).prop('checked', false);
	}
	getChakedCpanelTocheck();
})

$('table').on('change', '.addtocheck-cpanel', 
	function () {

		if ($('.addtocheck-cpanel:checked').length == $('.addtocheck-cpanel').length) {
			$('#chekall-cpanel').prop('checked', true);
		} 
		else {
			$('#chekall-cpanel').prop('checked', false);
		}
		getChakedCpanelTocheck();
	}
);

function checkselected(d){
	$("#checkerbtn").remove()
	var prgs = '<div class="card pd-20">\
				<h6 class="tx-12 tx-uppercase tx-inverse tx-bold mg-b-15 statuschecks">Checker Status</h6>\
				<div class="progress mg-b-10">\
					<div class="progress-bar progress-bar-striped progress-bar-animated" id="chekprgs" style="width:0px"  role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>\
				</div>\
			</div>';
	$("#addtocartdiv").html(prgs);
	let lastResponseLength = false;
	var old = $(".addtocheck-cpanel:checked").parent().html();
	$(".addtocheck-cpanel:checked").parent().html('<span class="btn btn-sm btn-warning addtocheck-cpanel ">Cheking...<i class="bx bx-sync bx-spin"></i></span>');
	$(".statuschecks").html('Cheking...<span class="bx bx-sync bx-spin"></span>')

	xhr = new XMLHttpRequest();

	xhr.open("POST", baseUrl+'/'+actualPage+'/checker', true);
	xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhr.setRequestHeader("Accept", "application/json");
	xhr.onprogress = function(e) {
	let progressResponse;
	let response = e.currentTarget.response;
	progressResponse = lastResponseLength ?  response.substring(lastResponseLength) : response;
	lastResponseLength = response.length;
	let parsedResponse = JSON.parse(progressResponse);
	if(parsedResponse.result == "1"){
		//$("."+parsedResponse.id).parent().parent().children(':first-child').html('<span class="btn btn-sm rounded-3 btn-success"><i class="bx bx-check"></i></spann>');
		$("."+parsedResponse.id).parent().parent().children(':first-child').html(old);
	}
	else {
		$("."+parsedResponse.id).parent().parent().remove();
	}
	//console.log(parsedResponse);
	$("#chekprgs").attr('aria-valuenow',parsedResponse.progress);
	$("#chekprgs").css('width',parsedResponse.progress+'%');
	$("#chekprgs").html(parsedResponse.progress+'%');
	$("#totaltocheknum").html(parsedResponse.total);
	$("#checkeds").html(parsedResponse.x+" ("+parsedResponse.progress+"%)");
	$("#remi").html(parseFloat(parsedResponse.total) - parseFloat(parsedResponse.x));
		
   
   //console.log(parsedResponse.id);

   //if(Object.prototype.hasOwnProperty.call(parsedResponse, 'success')) {
       // handle process success
   //}
}
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && this.status == 200) {
			$("#chekprgs").removeClass('progress-bar-animated');
		   	$("#done").html('Checker Done.');
		   	$(".statuschecks").html('Cheking Done.');
		   	$('input[type="checkbox"]').prop('checked', false);
		   	tocheck = new Array();
		}
	}

	xhr.send("ids="+JSON.stringify(tocheck));
}

function addtocartprods(){
    $.ajax({
        url: baseUrl+'/finances/addtoBunchCart',
        type:'POST',
        dataType:'json',
        data:'ids='+JSON.stringify(tocheck),
        success:function(response){
            $("#cartContents").html(response.html);
		    if(response.signal.length && response.signal == '1'){
		        var phtml = '<span class="square-8 bg-danger pos-absolute t-15 r-0 rounded-circle" id="nbcrt"></span>';
		        $("#carticon").html(phtml);
		    }
		    else if(response.html == "0") {
		        $("#nbcrt").remove();
		    }
            $z = 0;
            $.each(tocheck, function(k,v){
            	var parts = v.split('-');
               	var dataapi = 'removeCartProd-'+parts[0]+'|'+parts[1];
            	$('#buybtn-'+parts[0]).data('api', dataapi);
            	$('#buybtn-'+parts[0]).removeClass('btn-success');
            	$('#buybtn-'+parts[0]).addClass('btn-danger');
            	$('#buybtn-'+parts[0]).children('span').removeClass('bx bx bx-cart');
            	$('#buybtn-'+parts[0]).children('span').addClass('bx bx-message-x');
            	$('#buybtn-'+parts[0]).html('Remove from cart <span class="bx bx-message-x"></span>');
            	$('#'+parts[0]+'-'+parts[1]).remove();
            })
            var ahtml = '<a href="javascript:void(0);" class="" data-api="clearCart" id="clearer">Remove All</a>';
            $("#clearme").html(ahtml);
            $(":input,:button,:checkbox").removeClass('disabled');
            $(":input,:button,:checkbox").prop('disabled', false); 
            $("#chekall").prop('checked', false);
	        $("#addtocartdiv").children().remove();
	        $("#addtocartdiv").parent().addClass('d-none');
	        $("#acart").addClass('with-cart');
            tocheck = new Array();
        }
    })
}

function removeRdpDuplicates(arr) {
    return arr.filter((item, index) => arr.indexOf(item) === index);
}

function getChakedRdpTocheck() {
	$('.addtocheck-rdp').each(function(){
		if ($(this).prop('checked')) {
			tocheck.push($(this).attr("data-id"))
		}
		else {
			var removeItem = $(this).attr("data-id");
			tocheck = $.grep(tocheck, function(value) {
			  	return value != removeItem;
			});
		}
	});

	tocheck = removeRdpDuplicates(tocheck);
	if($('.addtocheck-rdp:checked').length > 1){
		var checkbtn = '<div class="input-group"><button type="btn" class="btn btn-sm btn-indigo" id="checkerbtn" data-api="checkselected-rdp">Check Selected</button>';
		checkbtn += '<button type="btn" class="btn btn-sm btn-success" id="addtocartbtn" data-api="addtocartprods">Add to Cart <span class="bx bx-cart"></span></button></div>';
		$(".addtocartmain").removeClass('d-none');
		$("#addtocartdiv").html(checkbtn);
	}
	else if($('.addtocheck-rdp:checked').length == 1){
		var checkbtn = '<div class="input-group"><button type="btn" class="btn btn-sm btn-indigo" id="checkerbtn" data-api="checkselected-rdp">Check Selected</button>';
		$(".addtocartmain").removeClass('d-none');
		$("#addtocartdiv").html(checkbtn);
	}
	else {
		$("#addtocartdiv").html('');
		$(".addtocartmain").addClass('d-none');
	}
}

$("#chekall-rdp").change(function () {

	if ($(this).prop('checked')) {
		$('input[type="checkbox"]').not(this).prop('checked', true);
	} 
	else {
		$('input[type="checkbox"]').not(this).prop('checked', false);
	}
	getChakedRdpTocheck();
})

$('table').on('change', '.addtocheck-rdp', 
	function () {

		if ($('.addtocheck-rdp:checked').length == $('.addtocheck-rdp').length) {
			$('#chekall-rdp').prop('checked', true);
		} 
		else {
			$('#chekall-rdp').prop('checked', false);
		}
		getChakedRdpTocheck();
	}
);

function removeSmtpDuplicates(arr) {
    return arr.filter((item, index) => arr.indexOf(item) === index);
}

function getChakedSmtpTocheck() {
	$('.addtocheck-smtp').each(function(){
		if ($(this).prop('checked')) {
			tocheck.push($(this).attr("data-id"))
		}
		else {
			var removeItem = $(this).attr("data-id");
			tocheck = $.grep(tocheck, function(value) {
			  	return value != removeItem;
			});
		}
	});

	tocheck = removeSmtpDuplicates(tocheck);
	if($('.addtocheck-smtp:checked').length > 1){
		var checkbtn = '<div class="input-group"><button type="btn" class="btn btn-sm btn-indigo" id="checkerbtn" data-api="checkselected-smtp">Check Selected</button>';
		checkbtn += '<button type="btn" class="btn btn-sm btn-success" id="addtocartbtn" data-api="addtocartprods">Add to Cart <span class="bx bx-cart"></span></button></div>';
		$(".addtocartmain").removeClass('d-none');
		$("#addtocartdiv").html(checkbtn);
	}
	else if($('.addtocheck-smtp:checked').length == 1){
		var checkbtn = '<div class="input-group"><button type="btn" class="btn btn-sm btn-indigo" id="checkerbtn" data-api="checkselected-smtp">Check Selected</button>';
		$(".addtocartmain").removeClass('d-none');
		$("#addtocartdiv").html(checkbtn);
	}
	else {
		$("#addtocartdiv").html('');
		$(".addtocartmain").addClass('d-none');
	}
}

$("#chekall-smtp").change(function () {

	if ($(this).prop('checked')) {
		$('input[type="checkbox"]').not(this).prop('checked', true);
	} 
	else {
		$('input[type="checkbox"]').not(this).prop('checked', false);
	}
	getChakedSmtpTocheck();
})

$('table').on('change', '.addtocheck-smtp', 
	function () {

		if ($('.addtocheck-smtp:checked').length == $('.addtocheck-smtp').length) {
			$('#chekall-smtp').prop('checked', true);
		} 
		else {
			$('#chekall-smtp').prop('checked', false);
		}
		getChakedSmtpTocheck();
	}
);


function dispute(d= null){
	data = 'id='+d;
	$.ajax({
		url:baseUrl+'/myorders/dispute',
		type:'POST',
		dataType:'JSON',
		data:data,
		success:(response)=>{
			if(typeof response.content == 'object'){
				$.each(response.content, (k,v) => {
					$("#"+k).parent().find('span').removeClass('bg-secondary');
					$("#"+k).parent().find('span').addClass('bg-warning');
				})
            	$(":input,:button,:checkbox").removeClass('disabled');
	            $(":input,:button,:checkbox").prop('disabled', false);
            }
            else {
            	g = document.createElement('div');
				g.setAttribute("id", "modalcontiner");
				$("body").append(g);
            	$("#modalcontiner").html('');
            	$(".modal-backdrop").remove();
				$("#modalcontiner").html(response.modal);
		        if($("#bsModal").length){
		            var myModal = new bootstrap.Modal(document.getElementById('bsModal'), {
    	  				keyboard: false,
    	  				backdrop: "static",
    				});
    				myModal.show(); 
		        }
				$(":input,:button,:checkbox").removeClass('disabled');
	            $(":input,:button,:checkbox").prop('disabled', false);
            }
		}
	})
}

function confirmdispute(d= null){
	data = $("#form").serializeArray();
	data.push({name:'id', value:d});
	$.ajax({
		url:baseUrl+'/myorders/confirmdispute',
		type:'POST',
		dataType:'JSON',
		data:data,
		success:(response)=>{
			if(typeof response.content == 'object'){
				$.each(response.content, (k,v) => {
					$("#"+k).parent().find('span').removeClass('bg-secondary');
					$("#"+k).parent().find('span').addClass('bg-warning');
				})
            	$(":input,:button,:checkbox").removeClass('disabled');
	            $(":input,:button,:checkbox").prop('disabled', false);
            }
            else {
            	g = document.createElement('div');
				g.setAttribute("id", "modalcontiner");
				$("body").append(g);
            	$("#modalcontiner").html('');
            	$(".modal-backdrop").remove();
				$("#modalcontiner").html(response.modal);
		        if($("#bsModal").length){
		            var myModal = new bootstrap.Modal(document.getElementById('bsModal'), {
    	  				keyboard: false,
    	  				backdrop: "static",
    				});
    				myModal.show(); 
		        }
				$(":input,:button,:checkbox").removeClass('disabled');
	            $(":input,:button,:checkbox").prop('disabled', false);
            }
		}
	})
}

function zchat(d){
	data = $("#chatform").serializeArray();
	data.push({name:'id', value:d});
	$.ajax({
		url:baseUrl+'/chat',
		type:'POST',
		data:data,
		dataType:'JSON',
		success:(response)=>{
			if(typeof response.content == 'object'){
				$.each(response.content, (k,v) => {
					$("#"+k).parent().find('span').removeClass('bg-secondary');
					$("#"+k).parent().find('span').addClass('bg-warning');
				})
            	$(":input,:button,:checkbox").removeClass('disabled');
	            $(":input,:button,:checkbox").prop('disabled', false);
            }
            else {
        		if(typeof response.error !== 'undefined'){
					var html = `<div class="card bg-gray-200">
									<div class="card-body">
										<p class="card-title ${response.class}"><b>${response.username}</b></p>							 
										<p class="card-subtitle">${response.date}</p>
										<p class="card-text">${response.message}</p>
									</div>
								</div>`;
	                $("#reportimeline").append(html);
		            $("#DZ_W_TimeLine").animate({ scrollTop: $('#DZ_W_TimeLine').prop("scrollHeight")}, 1000);
		            $("#chattext").val('');
				}
				else{
					$("#modalcontent").html('');
					$("#modalcontent").html(response.modal);
					let modal = new bootstrap.Modal(document.getElementById('bsModal'), {
						keyboard: false,
		  				backdrop: "static",
					});
					modal.show();	
				}
				$(":input,:button,:checkbox").removeClass('disabled');
	            $(":input,:button,:checkbox").prop('disabled', false);
	            $('input').removeClass('is-valid');
	            $('input').removeClass('is-invalid');
            }
		}
	})
}

function approvebase(d){
	var data = 'base='+d;
	$.ajax({
		url:baseUrl+actualPage+'/approveBase',
		type:'POST',
		data:data,
		dataType:'JSON',
		success:(response)=>{
			if(typeof response.content == 'object'){
				$.each(response.content, (k,v) => {
					$("#"+k).parent().find('span').removeClass('bg-secondary');
					$("#"+k).parent().find('span').addClass('bg-warning');
				})
            	$(":input,:button,:checkbox").removeClass('disabled');
	            $(":input,:button,:checkbox").prop('disabled', false);
            }
            else {
        		Table.ajax.reload(null);
				$(":input,:button,:checkbox").removeClass('disabled');
	            $(":input,:button,:checkbox").prop('disabled', false);
	            $('input').removeClass('is-valid');
	            $('input').removeClass('is-invalid');
            }
		}
	})
}

function declinebaseinit(d){
	var data = 'base='+d;
	$.ajax({
		url:baseUrl+actualPage+'/declineBaseInit',
		type:'POST',
		data:data,
		dataType:'JSON',
		success:(response)=>{
			if(typeof response.content == 'object'){
				$.each(response.content, (k,v) => {
					$("#"+k).parent().find('span').removeClass('bg-secondary');
					$("#"+k).parent().find('span').addClass('bg-warning');
				})
            	$(":input,:button,:checkbox").removeClass('disabled');
	            $(":input,:button,:checkbox").prop('disabled', false);
            }
            else {
        		g = document.createElement('div');
				g.setAttribute("id", "modalcontiner");
				$("body").append(g);
            	$("#modalcontiner").html('');
            	$(".modal-backdrop").remove();
				$("#modalcontiner").html(response.modal);
		        if($("#bsModal").length){
		            var myModal = new bootstrap.Modal(document.getElementById('bsModal'), {
    	  				keyboard: false,
    	  				backdrop: "static",
    				});
    				myModal.show(); 
		        }
				$(":input,:button,:checkbox").removeClass('disabled');
	            $(":input,:button,:checkbox").prop('disabled', false);
	            $('input').removeClass('is-valid');
	            $('input').removeClass('is-invalid');
            }
		}
	})
}

function declinebase(d){
	var data = 'base='+d+'&additionals='+$("#addtionals").val();
	$.ajax({
		url:baseUrl+actualPage+'/declineBase',
		type:'POST',
		data:data,
		dataType:'JSON',
		success:(response)=>{
			if(typeof response.content == 'object'){
				$.each(response.content, (k,v) => {
					$("#"+k).parent().find('span').removeClass('bg-secondary');
					$("#"+k).parent().find('span').addClass('bg-warning');
				})
            	$(":input,:button,:checkbox").removeClass('disabled');
	            $(":input,:button,:checkbox").prop('disabled', false);
            }
            else {
            	closeModal();
        		Table.ajax.reload(null);
				$(":input,:button,:checkbox").removeClass('disabled');
	            $(":input,:button,:checkbox").prop('disabled', false);
	            $('input').removeClass('is-valid');
	            $('input').removeClass('is-invalid');
            }
		}
	})
}



/**function checkselected(d){
	$("#checkerbtn").remove()
	var prgs = '<div class="card pd-20">\
				<h6 class="tx-12 tx-uppercase tx-inverse tx-bold mg-b-15 statuschecks">Checker Status</h6>\
				<div class="progress mg-b-10">\
					<div class="progress-bar progress-bar-striped progress-bar-animated" id="chekprgs" style="width:0px"  role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>\
				</div>\
			</div>';
	$("#addtocartdiv").html(prgs);
	let lastResponseLength = false;
	var old = $(".addtocheck-Smtp:checked").parent().html();
	$(".addtocheck-rdp:checked").parent().html('<span class="btn btn-sm btn-warning addtocheck-rdp ">Cheking...<i class="bx bx-sync bx-spin"></i></span>');
	$(".statuschecks").html('Cheking...<span class="bx bx-sync bx-spin"></span>')

	xhr = new XMLHttpRequest();

	xhr.open("POST", baseUrl+'/'+actualPage+'/checker', true);
	xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhr.setRequestHeader("Accept", "application/json");
	xhr.onprogress = function(e) {
	let progressResponse;
	let response = e.currentTarget.response;
	progressResponse = lastResponseLength ?  response.substring(lastResponseLength) : response;
	lastResponseLength = response.length;
	let parsedResponse = JSON.parse(progressResponse);
	if(parsedResponse.result == "1"){
		//$("."+parsedResponse.id).parent().parent().children(':first-child').html('<span class="btn btn-sm rounded-3 btn-success"><i class="bx bx-check"></i></spann>');
		$("."+parsedResponse.id).parent().parent().children(':first-child').html(old);
	}
	else {
		$("."+parsedResponse.id).parent().parent().remove();
	}
	//console.log(parsedResponse);
	$("#chekprgs").attr('aria-valuenow',parsedResponse.progress);
	$("#chekprgs").css('width',parsedResponse.progress+'%');
	$("#chekprgs").html(parsedResponse.progress+'%');
	$("#totaltocheknum").html(parsedResponse.total);
	$("#checkeds").html(parsedResponse.x+" ("+parsedResponse.progress+"%)");
	$("#remi").html(parseFloat(parsedResponse.total) - parseFloat(parsedResponse.x));
		
   
   //console.log(parsedResponse.id);

   //if(Object.prototype.hasOwnProperty.call(parsedResponse, 'success')) {
       // handle process success
   //}
}
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && this.status == 200) {
			$("#chekprgs").removeClass('progress-bar-animated');
		   	$("#done").html('Checker Done.');
		   	$(".statuschecks").html('Cheking Done.');
		   	$('input[type="checkbox"]').prop('checked', false);
		   	tocheck = new Array();
		}
	}

	xhr.send("ids="+JSON.stringify(tocheck));
}**/

/**function addtocartprods(){
    $.ajax({
        url: baseUrl+'/finances/addtoBunchCart',
        type:'POST',
        dataType:'json',
        data:'ids='+JSON.stringify(tocheck),
        success:function(response){
            $("#cartContents").html(response.html);
		    if(response.signal.length && response.signal == '1'){
		        var phtml = '<span class="square-8 bg-danger pos-absolute t-15 r-0 rounded-circle" id="nbcrt"></span>';
		        $("#carticon").html(phtml);
		    }
		    else if(response.html == "0") {
		        $("#nbcrt").remove();
		    }
            $z = 0;
            $.each(tocheck, function(k,v){
            	var parts = v.split('-');
               	var dataapi = 'removeCartProd-'+parts[0]+'|'+parts[1];
            	$('#buybtn-'+parts[0]).data('api', dataapi);
            	$('#buybtn-'+parts[0]).removeClass('btn-success');
            	$('#buybtn-'+parts[0]).addClass('btn-danger');
            	$('#buybtn-'+parts[0]).children('span').removeClass('bx bx bx-cart');
            	$('#buybtn-'+parts[0]).children('span').addClass('bx bx-message-x');
            	$('#buybtn-'+parts[0]).html('Remove from cart <span class="bx bx-message-x"></span>');
            	$('#'+parts[0]+'-'+parts[1]).remove();
            })
            var ahtml = '<a href="javascript:void(0);" class="" data-api="clearCart" id="clearer">Remove All</a>';
            $("#clearme").html(ahtml);
            $(":input,:button,:checkbox").removeClass('disabled');
            $(":input,:button,:checkbox").prop('disabled', false); 
            $("#chekall").prop('checked', false);
	        $("#addtocartdiv").children().remove();
	        $("#addtocartdiv").parent().addClass('d-none');
	        $("#acart").addClass('with-cart');
            tocheck = new Array();
        }
    })
}**/



function closeModal(){
	$("#modalcontiner").html('');
	$(".modal-backdrop").remove();
}