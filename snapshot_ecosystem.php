<?php
namespace greenbase;

include 'get_config.php';

?>
<html lang="">
<head>
  <meta charset="utf-8">
	<title>Snapshot Ecosystem</title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="robots" content="" />

	<script src="<?php echo Config::$greenbase_root ?>/js/jquery.min.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src="<?php echo Config::$greenbase_root ?>/js/masonry.pkgd.min.js"></script>
  <script src="<?php echo Config::$greenbase_root ?>/js/imagesloaded.pkgd.min.js"></script>
	<link rel="stylesheet" href="<?php echo Config::$greenbase_root ?>/css/reset.css">
	<link rel="stylesheet" href="<?php echo Config::$greenbase_root ?>/css/snapshot.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
  <link href='<?php echo Config::$greenbase_root ?>/css/Roboto.css' rel='stylesheet' type='text/css'>
</head>
<body>

<ul id="nav">
  <li class="navItem navOn" id="all">All</li>
</ul>
<div id="ecosystem"></div>
<div class="se-pre-con"></div>

<script>
function makeNavClicks(){
  $( "#nav .navItem" ).click(function() {

    clickedFocus = $(this).attr("id");

    $( "#nav .navItem" ).removeClass("navOn");
    $(this).addClass("navOn");

    $.each( $('.eco'), function( key, value ) {
      if (clickedFocus==="all"){
        $(this).css("display","block");
      } else if ($(this).data('focusid')==clickedFocus){
        $(this).css("display","block");
      }else{
        $(this).css("display","none");
      }
    });
    makeEcosystem(clickedFocus);
  });
}  

function makeToolTips(){
  $( '.eco' ).tooltip({
    items: "[data-details]",
    position: {
      my: "center bottom-20",
      at: "center top",
      using: function( position, feedback ) {
        $( this ).css( position );
        $( "<div>" )
          .addClass( "arrow" )
          .addClass( feedback.vertical )
          .addClass( feedback.horizontal )
          .appendTo( this );
      }
    },
    content: function() {
      var element = $( this );
      if ( element.is( "[data-details]" ) ) {
        var str = $(this).data("details");
        str = decodeURIComponent(str);
        data = JSON.parse(str);
         
        name =  data["name"];
        description =  data["description"];
        focus =  data["focus"];
        org_type =  data["org_type"];
        
        b = '<b>'+ name +'</b><br><br>';
        b += '<p>'+ description +'</p><br>';
        b += 'Focus: ' + focus + '<br>';
        b += 'Org Type: ' + org_type + '<br>';
        b += '';

        return b;
      }
    }
  });
}

function makeEcosystem(focusId){
  if(focusId=="all"){
    $("#ecosystem").css(
      {"width":"90%","margin":"24px auto","border":"1px solid grey","padding":"20px"}
    );
  }else{
    $("#ecosystem").css(
      {"width":"50%","margin":"24px auto","border":"1px solid grey","padding":"20px"}
    );
  }  

  var container = document.querySelector('#ecosystem');
  var msnry;
  // initialize Masonry after all images have loaded
  imagesLoaded( container, function() {
    msnry = new Masonry( container, {
      itemSelector: '.eco',
      columnWidth: 50
    });
    $(".se-pre-con").fadeOut("slow");;
    $("#ecosystem div.eco img.logo").css("opacity","1");  
  });
  $('#ecosystem').append('<div style="clear:both"></div>');
}
  
$(function() {
  focusIds=[];
  orgTypeIds=[];

  // perform request for list of orgs
  var jqxhr = $.getJSON( "<?php echo Config::$greenbase_root ?>/snapshot_orgs.php", function(data) {
  }).done(function(data) {
    // for each org, create a focus id, org type id, and push each into an array
    $.each( data, function( key, value ) {

      focusId = value.focus.replace(/\W/g,""); // strip non-alphas
      focusIds.push(focusId);

      orgTypeId = value.org_type.replace(/\W/g,""); // strip non-alphas
      orgTypeIds.push(orgTypeId);
    
      // strip single quotes - breaks quoted vals - fix later
      var details = JSON.stringify(value);
      details = details.replace("'","");
      details = encodeURIComponent(details);    

      // create org elemtns with data type set for focus id 
      b = '<div data-focusid="' + focusId + '" data-orgtype="' + orgTypeId + '" class="eco" data-details="' + details + '">';
      b += '<img class="logo" style="opacity:0;" src="<?php echo Config::$greenbase_root ?>/remoteimages/snapshot/logo_' + value.logo.replace("./localimage.php?org_id=","") + '.png" />';
      b += '</div>';

      $( '#ecosystem' ).append( b );
    });

    // unique the array of focus ids
    focusIds = focusIds.filter(function(elem, pos) {
      return focusIds.indexOf(elem) == pos;
    }); 
  
    // unique the array of org types
    orgTypeIds = orgTypeIds.filter(function(elem, pos) {
      return orgTypeIds.indexOf(elem) == pos;
      }); 
    
    makeEcosystem("all");

    $.each( focusIds, function( key, value ) {
      $('#nav').append('<li class="navItem" id="'+ value +'">' + value + '</li>');
    });
    
    $('#nav').append('<div style="clear:both"></div>');
          
    makeNavClicks();
    
    makeToolTips();
  });
});
</script>


</body>
</html>
