<?php
namespace greenbase;

include 'get_config.php';

?>
  <script src="js/jquery.min.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src="js/masonry.pkgd.min.js"></script>
  <script src="js/imagesloaded.pkgd.min.js"></script>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/snapshot.css">
	
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<div id="snapshot" class="snapshot">
  <div id="orgTypes"></div>
  <div id="orgTypeDetails"></div>
  <div id="orgs"></div>
</div>

<script>
function makeToolTips(){
  $( '.org' ).tooltip({
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

$(function() {

  focusIds=[];
  orgTypeIds=[];

  // setting random colors to make it pretty
  var colors = ["#3F7CAC","#95AFBA","#BDC4A7","#D5E1A3","#E2F89C"];

  // perform request for list of orgs
  var jqxhr = $.getJSON( "./snapshot_orgs.php", function(data) {    
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
      b = '<div data-focusid="' + focusId + '" data-orgtype="' + orgTypeId + '" class="org" data-details="' + details + '">';
//      b += '<img class="logo" src="' + value.logo + '" />';
      b += '<img style="width:150px;" src="<?php echo Config::$greenbase_root ?>/' + value.logo + '" />';
      b += '</div>';
      
      $( '#orgs' ).append( b );
    });

  // unique the array of focus ids
  focusIds = focusIds.filter(function(elem, pos) {
    return focusIds.indexOf(elem) == pos;
  }); 

  // unique the array of org types
  orgTypeIds = orgTypeIds.filter(function(elem, pos) {
    return orgTypeIds.indexOf(elem) == pos;
    }); 
  });


  // perform request for list of focuses and types
  var jqxhr = $.getJSON( "./snapshot_layout.php", function(data) {
  }).done(function(data) {
i=0;
    // for each focus, create a display box with title and description
    $.each( data.focus_types, function( key, value ) {
      
      focusId = value[0].replace(/\W/g,"").replace("amp",""); // eep stripping out ampersand code      
      $('#orgTypes').append('<div class="orgType" id="' + focusId + (i+1) + '"data-description="' + value[1] + '" data-focusid="' + focusId + '"><h1 class="title">' + value[0] + '</h1></div>');
      
    });

var timer;
var delay = 500;

$('div#orgTypes .orgType').hover(function() {
    // on mouse in, start a timeout

  var VarObject = {};
  VarObject.$obj = $(this);

  timer = setTimeout(function() {

    var rand = Math.floor(Math.random()*colors.length);           
    $ ( VarObject.$obj ).css( "background-color", colors[rand] );
      
    contents = $ ( VarObject.$obj ).data("description");
    $( '#orgTypeDetails' ).html(contents);
  
    currentFocusID = $(VarObject.$obj).data('focusid');

    $("#orgs div").hide();
    $("#orgs div[data-focusid='" + currentFocusID +"']").fadeIn("fast");

    imgLogo = $("#orgs div[data-focusid='" + currentFocusID +"'] img.logo");

    $.each( imgLogo, function( key,value ) {
      $( this ).attr("src",$(this).data("src"));
    });


var container = document.querySelector('#orgs');
var msnry;
// initialize Masonry after all images have loaded
//imagesLoaded( container, function() {
  msnry = new Masonry( container, {
      // options...
      itemSelector: '.org',
      columnWidth: 150
    });

    makeToolTips();

  }, delay);
}, function() {
    clearTimeout(timer);
});





//});

/*
    var container = document.querySelector('#orgs');
    var msnry = new Masonry( container, {
      // options...
      itemSelector: '.org',
      columnWidth: 100
    });
*/

});



});


</script>
