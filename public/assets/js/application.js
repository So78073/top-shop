
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
if(actualPage == ""){
	actualPage = "home";
}

const checkVal = (arr, val) => {
    let valExist = arr.some(value => value === val);
};

var checked = 0;
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
		actualPage == 'sellerdashboard' || actualPage == 'Sellerdashboard' || 
		actualPage == 'myreferals' || actualPage == 'Myreferals' || 
		actualPage == 'codes' || actualPage == 'Codes'
		){
		var Table = $("#TabelStock").DataTable({
			//lengthChange: false,
		    processing: true,
		    bSortable: false,
		    serverSide:true,
		    pageLength: 100,
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
		        className: 'text-center',
		        targets: "_all",
		    }],
		    ordering: false,
		    dom: "ltrip",
		    responsive: {
	            details: {
	                display: $.fn.dataTable.Responsive.display.modal( {
	                    header: function ( row ) {
	                        var data = row.data();
	                        return 'Details';
	                    }
	                } ),
	                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
	                    tableClass: 'restab table-bordered',

	                })
	            }
	        },
		    scrollCollapse: true,	    
		});
	}
	else {
		var Table = $("#TabelStock").DataTable({
			lengthChange: false,
		    processing: true,
		    ajax: {
		        url: toUrl,
		    },
		    columnDefs: [{
		        className: 'text-center',
		        targets: "_all",
		    },],
		    ordering: false,
		    dom: "ltrip",
		    responsive: {
	            details: {
	                display: $.fn.dataTable.Responsive.display.modal( {
	                    header: function ( row ) {
	                        var data = row.data();
	                        return 'Details';
	                    }
	                } ),
	                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
	                    tableClass: 'restab table-bordered',

	                })
	            }
	        },
		    scrollCollapse: true,	    
		});
	}
	$('table').on('click', function(e){
      	if($('[data-bs-toggle="popover"]').length>1){
	      	$('[data-bs-toggle="popover"]').popover({
	      	 	html: true, 
				content: function() {
		          	return $('#popover-content').html();
		        }
      		});
      	}
    });
    $(document).on('blur','button[data-bs-toggle="popover"]', function() {
	   $(this).popover('hide');
	});
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

if($("#TabelSelled").length){
	if(actualPageArray.length == 5){
		var toUrl = baseUrl+'/'+actualPage+'/fetchTable?id='+actualPageArray[4];
	}
	else {
		var toUrl = baseUrl+'/'+actualPage+'/fetchTableSelled';
	}
	var TabelSelled = $("#TabelSelled").DataTable({
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
	    responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return 'Details';
                    }
                } ),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                    tableClass: 'restab table-bordered'
                } )
            }
        },
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
	    responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return 'Details';
                    }
                } ),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                    tableClass: 'restab table-bordered'
                } )
            }
        },
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

function addvalidruls(s){
	var selectdelem = $('#'+s+' option:selected').val();	
	var critrarynames = ['exact_length',
							'greater_than',
							'greater_than_equal_to',
							'is_not_unique',
							'is_unique',
							'less_than',
							'less_than_equal_to',
							'matches',
							'max_length',
							'min_length',
							'regex_match'
						];
	if(critrarynames.indexOf(selectdelem) != -1){
		var html = '<input type="text" name="inputs[][r_'+critrarynames[critrarynames.indexOf(selectdelem)]+']" class="form-control">';
		$('#'+s).parent().children('input[type="text"]').prop('disabled', false);
		$('#'+s).parent().children('input[type="text"]').removeClass('disabled');
	}
	else {
		$('#'+s).parent().children('input[type="text"]').prop('disabled', true);
		$('#'+s).parent().children('input[type="text"]').addClass('disabled');
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
				Lobibox.notify(''+response.typemsg+'', {
					pauseDelayOnHover: true,
					size: response.size,
					icon: response.icone,
					continueDelayOnInactiveTab: false,
					position: response.position,
					msg: response.message,
					sound: response.sounds
				});
				if(additionals[0] == "1"){
					if(actualPage != 'pages'){
						Table.ajax.reload();
					}
					if(additionals[1]){
						eval(additionals[1])();
					}
					if(actualPage != 'history'){

						if(response.html == 'empty'){
							$(".cart-list").html('');	
							$(".cart-count").html('0');
							var html = '<p class="msg-header-title">Cart is Empty</p>';
							$(".msg-header").html(html);
							$("#checkoutdiv").html('');
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
							var chekoutbtn = '<a href="'+baseUrl+'cart"><div class="text-center msg-footer">To Check Out</div></a>';	
							$("#checkoutdiv").html(chekoutbtn);
							//calc();
							getStoks();
							refreshCart();
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
					var chekoutbtn = '<a href="'+baseUrl+'cart"><div class="text-center msg-footer">To Check Out</div></a>';	
					$("#checkoutdiv").html(chekoutbtn);
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
    				Table.ajax.reload();
    			}
    			if(additionals[1] == "1"){
    			    var divName = additionals[2];
    			    $("#"+divName).html(response.html);
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
				console.log(data);
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
function getToken(){
	Myajax('Serialize','GET', baseUrl+'/Getoken', '', 'JSON', 'bsmodel');
}
$(document).ready(function() {
    if(actualPage.split('?')[0] == 'mytiket'){
        //$('#ps-mid').perfectScrollbar();
        new PerfectScrollbar('#ps-mid');
    }

	initselect();
	if(actualPage == 'cards' || actualPage == 'Cards' || actualPage == 'sellerdashboard' || actualPage == 'Sellerdashboard'){
		
		/**Table.column(1).data().unique().each( function ( d, j ) {
			collapsedGroups[d] = !collapsedGroups[d];
		} );
		Table.draw(false);**/
		$('#TabelStock tbody').on('click', 'tr.group-start', function () {
			var name = $(this).data('name');
			collapsedGroups[name] = !collapsedGroups[name];
			Table.draw(false);
		}); 

		$("#pricerange").ionRangeSlider({
	        type: "double",
	        grid: true,
	        min: 1,
	        max: 100,
	        from: 1,
	        to: 20,
	        skin: "square",
	        prefix: "$",
	        onChange: function (data) {
	            console.dir(data);
	        }
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
				if(typeof($('button[data-api="massiniteditt-'+actualPageArray[4]+'"]')) != 'undefined'){
					var html = '<button type="button" data-api="massinitedit-'+actualPageArray[4]+'" class="btn btn-warning btn-sm">\
					<i class="bx bx-edit"></i> Edit\
					</button>\
					<button type="button" data-api="massinitdelete-'+actualPageArray[4]+'" class="btn btn-danger btn-sm">\
						<i class="bx bx-trash"></i> Delete\
					</button>';
				}
				
			}
			else{
				if(typeof($('button[data-api="massiniteditt"]')) != 'undefined'){
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
	
	if(countchecked == 2){
		if(typeof(actualPageArray[4]) !== 'undefined'){
			if(typeof($('button[data-api="massiniteditt-'+actualPageArray[4]+'"]')) != 'undefined'){
				var html = '<button type="button" data-api="massinitedit-'+actualPageArray[4]+'" class="btn btn-warning btn-sm">\
					<i class="bx bx-edit"></i> Edit\
				</button>\
				<button type="button" data-api="massinitdelete-'+actualPageArray[4]+'" class="btn btn-danger btn-sm">\
					<i class="bx bx-trash"></i> Delete\
				</button>';
			}
		}
		else{
			if(typeof($('button[data-api="massiniteditt"]')) != 'undefined'){
				var html = '<button type="button" data-api="massinitedit" class="btn btn-warning btn-sm">\
					<i class="bx bx-edit"></i> Edit\
				</button>\
				<button type="button" data-api="massinitdelete" class="btn btn-danger btn-sm">\
					<i class="bx bx-trash"></i> Delete\
				</button>';
			}
		}
		//$("#DivTabelStock").append(html)	
		$('<div>').attr({id: 'btnsContiner', class: 'float-start'}).prependTo("#DivTabelStock");
		$("#btnsContiner").html(html);
	}
	else {
		if(countchecked < 2){
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
		Myajax('buytype='+d+'&hashed='+$('input[name="hashed"]').val(), 'POST',  baseUrl+actualPage+'/massinitEdit', '', 'JSON', 'bsmodel', ["0"]);
	}
	else {
		Myajax('hashed='+$('input[name="hashed"]').val(), 'POST',  baseUrl+actualPage+'/massinitEdit', '', 'JSON', 'bsmodel', ["0"]);	
	}
	
}

function massedit(){
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
	html = '<input type="hidden" name="id" value="'+id+'">';
	$("#MasseditUserForm").append(html);
	//console.log(html)
	//$('<div>').attr({id: 'idContiner'}).prependTo("#MasseditUserForm");
	//$("#idContiner").html(html);
	Myajax('Serialize','POST',  baseUrl+actualPage+'/massedit', 'MasseditUserForm', 'JSON', 'bsmodel', ["1"]);
}

function dosearche(){
	var data = $("#Filter").serializeArray();
	data.push({name: "hashed", value:$('input[name="hashed"]').val()});
	$('#TabelStock').DataTable().clear().destroy();

	var Table = $("#TabelStock").DataTable({
			lengthChange: false,
		    processing: true,
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
		    responsive: {
	            details: {
	                display: $.fn.dataTable.Responsive.display.modal( {
	                    header: function ( row ) {
	                        var data = row.data();
	                        return 'Details';
	                    }
	                } ),
	                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
	                    tableClass: 'restab table-bordered',

	                })
	            }
	        },
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

	var newhtml = '<button type="button" id="instokbtn" class="btn  btn-info btn-sm text-white" data-api="getStoksStatus-0"><i class="text-white bx bx-package"></i> In Stok</button>\
					<button type="button" id="selledbtn" class="btn  btn-success btn-sm" data-api="getStoksStatus-1"><i class="bx bx-diamond"></i> Selled</button>\
					<button type="button" id="refundedbtn" class="btn  btn-danger btn-sm" data-api="getStoksStatus-2"><i class="bx bx-redo"></i> Refunded</button>\
					<button type="button" id="add" class="btn  btn-light btn-sm" data-api="initcreatelog"><i class="bx bx-upload"></i> Upload CC</button>';
	$('#inbtn').prepend(newhtml);
	$('#TabelStock').children().children().html(headers);
	
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
	//var data = 'status='+d+'&hashed='+$('input[name="hashed"]').val();
	$('#TabelStock').DataTable().clear().destroy();
	var Table = $("#TabelStock").DataTable({
			lengthChange: false,
		    processing: true,
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
		    responsive: {
	            details: {
	                display: $.fn.dataTable.Responsive.display.modal( {
	                    header: function ( row ) {
	                        var data = row.data();
	                        return 'Details';
	                    }
	                } ),
	                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
	                    tableClass: 'restab table-bordered',

	                })
	            }
	        },
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
							<button type="button" id="selledbtn" class="btn  btn-success btn-sm" data-api="getStoksStatusOthers-1|'+d+'"><i class="bx bx-diamond"></i> Selled</button>\
							<button type="button" id="refundedbtn" class="btn  btn-danger btn-sm" data-api="getStoksStatusOthers-2|'+d+'"><i class="bx bx-redo"></i> Refunded</button>\
							<button type="button" id="add" class="btn  btn-light btn-sm" data-api="initcreate-'+d+'"><i class="bx bx-upload"></i> Add Stuff</button>';
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
	var Table = $("#TabelStock").DataTable({
			lengthChange: false,
		    processing: true,
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
		    responsive: {
	            details: {
	                display: $.fn.dataTable.Responsive.display.modal( {
	                    header: function ( row ) {
	                        var data = row.data();
	                        return 'Details';
	                    }
	                } ),
	                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
	                    tableClass: 'restab table-bordered',

	                })
	            }
	        },
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

		$("#city").attr('disabled', true);
		$("#city").addClass('disabled');
		$("#city").find('option').remove();
		$('input[name="hashed"]').val(response.csrft);
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
	if(state !== 'all' && state !== 'All'){
		$.ajax({
			url:baseUrl+actualPage+'/getCitys',
			type: 'POST',
			dataType: 'json',
			data:'state='+state+'&hashed='+$('input[name="hashed"]').val(),
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
		$('input[name="hashed"]').val(response.csrft);
	}	
}

function massinitdelete(){
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
}

function initcreate(id){
	Myajax('id='+id+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/initCreate', '', 'JSON', 'bsmodel', ["0"]);
}


function signup(){
	Myajax('Serialize', 'POST', baseUrl+'signup/signupme', 'signupForm', 'JSON', 'bsmodel');
}

function dosave(){
	Myajax('FileUpload', 'POST', baseUrl+'market/doCreate', 'createForm', 'JSON', 'bsmodel', ["1"]);
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
	
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST',  baseUrl+actualPage+'/initEdit', 'addCont', 'JSON', 'bsmodel', ["0"]);
}

function editinitm(d){
	var ds = d.split('|');
	Myajax('id='+ds[0]+'&buytype='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'/market/initEdit', 'addCont', 'JSON', 'bsmodel', ["0"]);
}


function doedit(d){
	Myajax('FileUpload','POST', baseUrl+'/market/doEdit', 'createForm', 'JSON', 'bsmodel', ["1"]);
}

function edit(d){
	Myajax('Aserialize','POST', baseUrl+actualPage+'/edit', 'edittForm', 'JSON', 'sweetalert', ["1", initSelectIcons()]);
}

function editCard(d){
	var reload ='';
	if(actualPage == 'cards' || actualPage == 'Crds'){
		var reload = '1'
	}
	Myajax('Aserialize','POST', baseUrl+actualPage+'/edit', 'edittForm', 'JSON', 'bsmodel', [reload]);
}

function rminit(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/rminit', '', 'JSON', 'bsmodel', ["0"]);
}


function getDetail(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/getDetail', '', 'JSON', 'bsmodel', ["0"]);
}


function rm(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/rm', '', 'JSON', 'bsmodel', ["1"]);
}


function report(d){
	Myajax('id='+d+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+actualPage+'/reportItem', '', 'JSON', 'bsmodel', ["1"]);
}

function buyinit(){
	Myajax('hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/checkoutinit', '', 'JSON', 'bsmodel', ["0"]);
}

function addtocart(d){
	var ds = d.split('|');
	Myajax('id='+ds[0]+'&buytype='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/addtoCart', '', 'JSON', 'sweetalert', ["0"]);
	$('button[data-api="addtocart-'+ds[0]+'|'+ds[1]+'"]').attr('style', 'background-color:rgba(58, 174, 50, 0.5) !important;');
	//$('button[data-api="addtocart-'+ds[0]+'|'+ds[1]+'"]').attr('style', '');
	$('button[data-api="addtocart-'+ds[0]+'|'+ds[1]+'"]').children('span').removeClass('bx bx bx-cart');
	$('button[data-api="addtocart-'+ds[0]+'|'+ds[1]+'"]').children('span').addClass('bx bx-message-x');
	$('button[data-api="addtocart-'+ds[0]+'|'+ds[1]+'"]').data('api', 'removeCartProd-'+ds[0]+'|'+ds[1]+'');
	$(":input,:button,:checkbox").removeClass('disabled');
    $(":input,:button,:checkbox").prop('disabled', false);
}

function clearCart(){
	Myajax('hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/clearCart', '', 'JSON','sweetalert', ["1"]);
}

function removeCartProd(d){
	var ds = d.split('|');
	if(actualPage == 'cart' || actualPage == 'Cart'){
		Myajax('id='+ds[0]+'&buytype='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/removeCartProd', '', 'JSON','sweetalert', ["1"]);
		$('.'+ds[0]+'-'+ds[1]).parent().parent().parent().parent().remove();
	}
	else {
		Myajax('id='+ds[0]+'&buytype='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'Finances/removeCartProd', '', 'JSON','sweetalert', ["0"]);
	}
	
	$('button[data-api="addtocart-'+ds[0]+'|'+ds[1]+'"]').attr('style', '');
	$('button[data-api="addtocart-'+ds[0]+'|'+ds[1]+'"]').addClass('btn-light');
	$('button[data-api="addtocart-'+ds[0]+'|'+ds[1]+'"]').children('span').removeClass('bx bx-message-x');
	$('button[data-api="addtocart-'+ds[0]+'|'+ds[1]+'"]').children('span').addClass('bx bx bx-cart');
	$('button[data-api="addtocart-'+ds[0]+'|'+ds[1]+'"]').data('api', 'addtocart-'+ds[0]+'|'+ds[1]+'');
	$(":input,:button,:checkbox").removeClass('disabled');
    $(":input,:button,:checkbox").prop('disabled', false);

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
	//alert(22)
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
	html = '<div class="input-group">\
				<select class="form-select" id="inputsvalidation'+sd+'" name="inputs['+zid+'][validationrull][]" tabindex="-1" aria-hidden="true" data-api="addvalidruls-inputsvalidation'+sd+'">\
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
					<option value="regex_match"  data-ruls="regex_match">regex_match</option>\
					<option value="permit_empty">permit_empty</option>\
					<option value="required">required</option>\
					<option value="valid_email">valid_email</option>\
					<option value="valid_ip">valid_ip</option>\
					<option value="valid_url">valid_url</option>\
					<option value="valid_date">valid_date</option>\
					<option value="valid_cc_number">valid_cc_number</option>\
				</select>\
				<input type="text" disabled id="'+sd+'" class="form-control disabled" data-api="setval-'+sd+'">\
				<button type="button" id="removefield'+sd+'" class="btn btn-outline-danger btn-sm" data-api="removerulcritary-removefield'+sd+'"><i class="bx bx-trash"></i></button>\
		</div>';
		$(d).parent().append(html);
		$(d).attr('onclick', "addrulcritary(this,"+sd+");")
}

function addfield(){
	var html = '<div class="row pt-2">\
					<div class="col-sm">\
						<label class="pb-2" for="fieldsnames'+xid+'">Field name</label>\
						<input type="text" id="fieldsnames'+xid+'" name="fields['+xid+'][fieldsNames][]" class="form-control">\
					</div>\
					<div class="col-sm">\
						<label class="pb-2" for="fieldstypes'+xid+'">Field Type</label>\
						<select class="form-select" id="fieldstypes'+xid+'" name="fields['+xid+'][fieldTypes][]" tabindex="-1" aria-hidden="true">\
							<option value="int">Integer</option>\
							<option value="varchar">Varchar</option>\
							<option value="date">Date</option>\
							<option value="datetime">DateTime</option>\
							<option value="longtext">Long text</option>\
						</select>\
					</div>\
					<div class="col-sm">\
						<label class="pb-2" for="fieldssize'+xid+'">Field Size</label>\
						<input type="number" id="fieldssize'+xid+'" name="fields['+xid+'][fieldSsize][]" class="form-control">\
					</div>\
					<div class="col-sm">\
						<label class="pb-2" for="fieldsempty'+xid+'">Empty by Default</label>\
						<div class="input-group">\
							<select class="form-select" id="fieldsempty'+xid+'" name="fields['+xid+'][fieldsempty][]" tabindex="-1" aria-hidden="true">\
								<option value="1">No</option>\
								<option value="0">Yes</option>\
							</select>\
							<button type="button" id="remove'+xid+'" class="btn btn-outline-danger btn-sm" data-api="removefield-'+xid+'">Remove <i class="bx bx-trash"></i></button>\
						</div>\
					</div>\
				</div>';
	$("#fieldrecept").append(html);
	xid += 1;
}

function doselect(d){
	var value = $("#inputtypes"+d).val();
	if(value == 'select'){
		var html = '<div id="lists'+d+'"><label class="mt-2 mb-1">Set the custom values<br/> vlue1,Text1<br/>vlue2,Text2...</label>\
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
						htmlr += '<div class="row" pt-2>\
									<div class="col-sm-3">\
										<label class="pb-2" for="fieldlabel'+nid+'">Input Lable</label>\
										<input type="hidden" id="fieldsnames'+nid+'" class="form-control disable" name="inputs['+nid+'][inputName]" value="'+val.value+'">\
										<input type="text" id="fieldlabel'+nid+'" name="inputs['+nid+'][inputLable]" class="form-control" value="'+val.value+'">\
									</div>\
									<div class="col-sm-3">\
										<label class="pb-2" for="inputtypes'+nid+'">Input Type</label>\
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
									<div class="col-sm-3">\
										<label class="pb-2" for="inputsvalidation'+nid+'">Validations</label>\
										<div class="input-group">\
											<select class="form-select form-control" id="inputsvalidation'+nid+'" name="inputs['+nid+'][validationrull][]" tabindex="-1" aria-hidden="true" data-api="addvalidruls-inputsvalidation'+nid+'">\
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
											</select>\
											<input type="text" id="'+nid+'" disabled data-api="setval-'+nid+'" class="form-control disabled">\
											<button type="button" id="addFields-'+nid+'" class="btn btn-outline-success btn-sm" onclick="addrulcritary(this,'+nid+');" data-apizz="addrulcritary-addFields'+nid+'"><i class="bx bx-plus"></i></button>\
										</div>\
									</div>\
									<div class="col-sm-3">\
										<label class="pb-2" for="sectionicone">Icon</label>\
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
				bodyml += '<td><select class="form-select" name="tables['+xid+'][cellsTypes][]">\
							<option value="show">Show to users</option>\
							<option value="hide">Hide from users</option>\
							<option value="btnsuccess">Button Green</option>\
							<option value="btnwarning">Button Yellow</option>\
							<option value="btndanger">Button Red</option>\
							<option value="btnlight">Button Light</option>\
							<option value="btnimage">Show Image</option>\
							<option value="btncheck">Button check</option>\
							<option value="btnclose">Button Close</option>\
							<option value="image">Show Image (from Link)</option>\
							<option value="imageupload">Show Upload image</option>\
							<option value="link">Link</option>\
							<option value="yesno">Yes/No</option>\
							<option value="existnot">Exist/Not Exist</option>\
						</select></td>';
				tlabels +='<td><label>Table Label:</label><input name="tables['+xid+'][cellsName][]"  value="'+v.value+'"  type="hidden"><input type="text" name="tables['+xid+'][cellsHeads][]" class="form-control" value="'+v.value+'" ></td>';
			});
			$('#thTable').html(tlabels);
			$('#tbodyTable').html(bodyml);
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
	$('#'+d).fadeOut(300, function(){ $(this).parent().remove();});
	$('#'+d).parent().fadeOut(300, function(){($(this).parent().remove())});
}

function initSelect2(selectClass){
	$('.'+selectClass).select2({
		width: '100%',
	});
}



function format(){
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
	  	
	  	//var inputName = $("#"+d).children("option:selected");
	  	var inputName = $('#sus'+d+' :selected').val();
	  	//var inputName = $("#"+d).find(":selected").val();
	  	console.log(inputName);
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
}
            

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
	Myajax('id='+ds[0]+'&buytype='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'market/initDelete', '', 'JSON', 'bsmodel', ["0"]);
}

function updatestatus(d){
	var ds = d.split('|');
	Myajax('id='+ds[0]+'&status='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'mytiket/updatestatus', '', 'JSON', 'sweetalert', ["0"]);
}

function dodelete(d){
	var ds = d.split('|');
	Myajax('id='+ds[0]+'&buytype='+ds[1]+'&hashed='+$('input[name="hashed"]').val(),'POST', baseUrl+'market/doDelete', '', 'JSON', 'bsmodel', ["1"]);
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
        }
    })
    
})






