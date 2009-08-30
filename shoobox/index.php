<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>the Wizard's Ledger</title>
<link href="css/reset.css" rel="stylesheet" type="text/css" />
<link href="css/shoobox.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/jquery-1.3.1.min.js" />

<script type="text/javascript" src="http://code.google.com/apis/gears/gears_init.js" ></script>


<script type="text/javascript" src="js/jquery.simplyscroll-1.0.4.min.js"></script>
<link rel="stylesheet" href="css/jquery.simplyscroll.css" media="all" type="text/css">
<link rel="stylesheet" href="css/shoobox1/jquery-ui-1.7.2.custom.css" media="all" type="text/css">
<script type="text/javascript" src="css/shoobox/js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>

<style type="text/css">
	#draggable { width: 150px; height: 150px; padding: 0.5em; }
	</style>
	<script type="text/javascript">
  function manageDocument(id,box,src){
    image = src.substr(0,11) + src.substr(18);

    $("#dialog").dialog('open');
    $("#docID").attr("value",id);
    $("#boxID").attr("value",box);
    $("#formImage").attr("src",image);
    $("#ui-datepicker-div").css("z-index",3000);
    

     
  }
	$(function() {
  
  var amount = $("#amount"),
			type = $("#type"),
			bookdate = $("#bookdate"),
			allFields = $([]).add(amount).add(type).add(bookdate),
			tips = $("#validateTips");

		function updateTips(t) {
			tips.text(t).effect("highlight",{},1500);
		}

		function checkLength(o,n,min,max) {

			if ( o.val().length > max || o.val().length < min ) {
				o.addClass('ui-state-error');
				updateTips("Length of " + n + " must be between "+min+" and "+max+".");
				return false;
			} else {
				return true;
			}

		}

		function checkRegexp(o,regexp,n) {

			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass('ui-state-error');
				updateTips(n);
				return false;
			} else {
				return true;
			}

		}
		$("#amount").focus(
 function(e,u)
 {
 this.select();
 }
)
    
    
    $("#dialog2").dialog({
   
      autoOpen: false});
     $("#dialog").dialog({
			bgiframe: true,
			autoOpen: false,
			height: 600,
      width:565,
			modal: false,
			buttons: {
				'shoo': function() {
				alert('submit');
				},
				'nah': function() {
					$("#dialog").dialog('close');
				}
			},
			close: function() {
				allFields.val('').removeClass('ui-state-error');
			}
		}); 
    
    $('#uploaddoc').click(function() {
      $('#upload').load('upload.php');
		})

		.hover(
			function(){ 
				$(this).addClass("ui-state-hover"); 
			},
			function(){ 
				$(this).removeClass("ui-state-hover"); 
			}
		).mousedown(function(){
			$(this).addClass("ui-state-active"); 
		})
		.mouseup(function(){
				$(this).removeClass("ui-state-active");
		});
    $("#bookdate").datepicker();
    
		$(".document").draggable({
        start: function() { $(this).attr("width","50")}
    });
    
	  $("#inbox").droppable({
        over: function() { $("#inbox").attr("src","img/inbox-active.png") },
        out: function() { $("#inbox").attr("src","img/inbox.png") },
        drop: function(e,u) { u.draggable.hide();
                              $("#inbox").attr("src","img/inbox.png");
                              manageDocument(u.draggable.attr("id"),1,u.draggable.attr("src"));
                              }
    });
  
     $("#outbox").droppable({
        over: function() { $("#outbox").attr("src","img/outbox-active.png") },
        out: function() { $("#outbox").attr("src","img/outbox.png") },
        drop: function(e,u) { u.draggable.hide();
                              $("#outbox").attr("src","img/outbox.png");
                              manageDocument(u.draggable.attr("id"),-1,u.draggable.attr("src"));
                              }
    });
    
    
   
  });
  
  
	</script>

<script type="text/javascript">
  
	function lookup(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			$.post("rcp.php", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				}
			});
		}
	} // lookup
	
	function fill(thisValue) {
		$('#inputString').val(thisValue);
		setTimeout("$('#suggestions').hide();", 200);
	}
  
  
  


</script>




</head>

<body>
<div id="header">
  <a href="index.php"><img alt="shoobox" src="img/shoobox_logo.png" border="0" /></a><br />
  

</div>
  
<div id="boxbar">
  <div id="boxes">
     <a href="javascript:inbox()"><img id="inbox" class="box" alt="inbox" src="img/inbox.png" border="0" /></a>
     <a href="javascript:inbox()"><img id="outbox" class="box" alt="outbox" src="img/outbox.png" border="0" /></a>
     <a href="javascript:inbox()"><img class="box" alt="outbox" src="img/floppy.png" border="0" /></a>
  </div>

  
  
</div>
<div align="center">
<button id="uploaddoc" class="ui-button ui-state-default ui-corner-all">Upload Documents</button>
<button id="create-user" class="ui-button ui-state-default ui-corner-all">Upload Documents</button>
</div>
<div id="upload">
hier
</div>

<ul class="documents">

    <a href="#"><img class='document' id="1" src="shoobox/01/thumbs/000001.jpg" /></a>
    <a href="#"><img class='document' id="2" src="shoobox/01/thumbs/000002.jpg" /></a>
    <a href="#"><img class='document' id="3" src="shoobox/01/thumbs/000003.jpg" /></a>
    <a href="#"><img class='document' id="4" src="shoobox/01/thumbs/000004.jpg" /></a>
    <a href="#"><img class='document' id="5" src="shoobox/01/thumbs/000005.jpg" /></a>
  
 
    

</ul>




<div id="dialog" align='right' title="Store in shoebox(STO_S)">


	<form>
   <a href=""><img align='left' id="formImage" width="300px" src="" /></a>
	<fieldset>
    <input type="text" name="boxID" id="boxID" value="1" class="boxID text ui-widget-content ui-corner-all" />
    <input type="text" name="docID" id="docID" value="1" class="docID text ui-widget-content ui-corner-all" />
		<label for="name">Type</label>
		<input type="text" name="type id="type" class="text ui-widget-content ui-corner-all" />
    <br />
		<label for="bookdate">Bookdate</label>
		<input type="text" name="bookdate" id="bookdate" value="" class="text ui-widget-content ui-corner-all" />
		<br />
    <label for="amount">Amount</label>
		<input type="amount" name="amount" id="amount" value="0,00" class="text ui-widget-content ui-corner-all" />
    <br />
    <label for"vat">tax</label>   
    
    <input type="radio" name="vat" checked value="0">0 %</radio> 
    <input type="radio" name="vat" value="6">6 %</radio>  
    <input type="radio" name="vat" value="6">19 %</radio> <br />
    <label for="description">Description</label>
    <textarea id="description" class="text ui-widget-content ui-corner-all" name="description" rows="3" cols="25" >
    <br />
   

	</fieldset>
	</form>
 
</div>



</body>

</html>
