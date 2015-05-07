<?php
namespace greenbase;
include_once 'get_config.php';

$greenbase_root = Config::$greenbase_root;

?>

<link rel="stylesheet" href="<?php echo Config::$greenbase_root ?>/css/snapshot.css">
<div id="snapshot" class="snapshot"><div id="loading">Snapshot loading... [This takes a while--please be patient we're building the climate change universe just for you!] <img src="<?php echo $greenbase_root; ?>/img/loading.gif"></div></div>
<script language="javascript">
var xmlhttp = new XMLHttpRequest();
var url = "<?php echo $greenbase_root; ?>/snapshot_orgs.php";
var done = 0;
var xmlhttp2 = new XMLHttpRequest();
var url2 = "<?php echo $greenbase_root; ?>/snapshot_layout.php";
var orgs;
var layout;

/**
 * The main snapshot function.
 * This function assumes that snapshot_orgs and snapshot_layout have already been called populating "layout" and "orgs" as global variables
 *
 * First, it finds the snapshot div within the document to orient itself to the width available
 * Then it draws the borders (the gray boxes under the titles) and the titles themselves
 * Finally it iterates through the list of organizations by focus-types and prints each individual cell ("PrintCell");
 */
function drawSnapshot() {
  var snapshotDiv = document.getElementById("snapshot");
  var width = window.innerWidth;

  //Handle the template width (GAR!)
  var containerWidth = snapshotDiv.clientWidth;
  if (containerWidth < width) {
    width = containerWidth;
  }

  var orgCount = Object.keys(orgs).length;

  var orgList = layout["orgs"];
  var focusList = layout["focus_types"];
  var tableLayout = layout["layout"];

  var cellWidth = parseInt (width / (focusList.length+1));
  var height = cellWidth * ((orgList.length+1));
  snapshotDiv.style.height = height + "px";

  var baseTop = snapshotDiv.offsetTop;
  var baseLeft = snapshotDiv.offsetLeft;

  var canvas = drawCanvas(width, height, snapshotDiv);

  drawBorders(focusList, orgList, snapshotDiv, cellWidth);

  drawLabels(focusList, orgList, snapshotDiv, cellWidth);

  for (i=0;i<orgList.length;i++) {
    var org = orgList[i][0];
    for (j=0;j<focusList.length;j++) {
      var focus = focusList[j][0];
      var cellLayout = tableLayout[org][focus];
      if (cellLayout == null) {
        //no items
        continue;
      }
      var cellLeft = (j+1) * cellWidth + baseLeft;
      var cellTop = i * cellWidth + baseTop;

      printCell(cellLayout, cellLeft, cellTop, cellWidth, snapshotDiv);
    }
  }
  var loadingDiv = document.getElementById("loading").innerHTML="";
}

function drawCanvas(width, height, snapshotDiv) {
  //First add the canvas at the very back.
//  console.log("Drawing canvas (" + width + "," + height + ")");
  var canvas = document.createElement("canvas");
  canvas.style.left = snapshotDiv.offsetLeft + "px";
  canvas.style.top = snapshotDiv.offsetTop + "px";
  canvas.width = width;
  canvas.height = height;
  canvas.style.position = "absolute";
  canvas.style.zIndex = -1;

  snapshotDiv.appendChild(canvas);

  return canvas;
}

/**
 * Draws the gray borders around the snapshot
 * @param focusList
 * @param orgList
 * @param snapshotDiv
 * @param cellWidth
 */
function drawBorders(focusList, orgList, snapshotDiv, cellWidth) {
  var baseTop = snapshotDiv.offsetTop;
  var baseLeft = snapshotDiv.offsetLeft;

  var orgBackground = document.createElement("div");
  snapshotDiv.appendChild(orgBackground);
  orgBackground.id="org-labels-background";
  orgBackground.style.width= cellWidth + "px";
  orgBackground.style.left = (baseLeft) + "px";
  orgBackground.style.top=(baseTop) + "px";
  orgBackground.style.height=((orgList.length + 1) * cellWidth) + "px";

  var dataBackground = document.createElement("div");
  snapshotDiv.appendChild(dataBackground);
  dataBackground.id="data-background";
  dataBackground.style.width= (focusList.length * cellWidth) + "px";
  dataBackground.style.left = (baseLeft + cellWidth) + "px";
  dataBackground.style.top=(baseTop) + "px";
  dataBackground.style.height=(orgList.length * cellWidth) + "px";

  var focusBackground = document.createElement("div");
  snapshotDiv.appendChild(focusBackground);
  focusBackground.id="focus-labels-background";
  focusBackground.style.width= (focusList.length * cellWidth) + "px";
  focusBackground.style.left = (baseLeft + cellWidth) + "px";
  focusBackground.style.top= (baseTop + (orgList.length) * cellWidth) + "px";
  focusBackground.style.height=(cellWidth) + "px";
}

function drawLabels(focusList, orgList, snapshotDiv, cellWidth) {
  var baseTop = snapshotDiv.offsetTop;
  var baseLeft = snapshotDiv.offsetLeft;

  var height = cellWidth * (orgList.length);
  snapshotDiv.style.height = height + "px";

  for (j=0;j<focusList.length;j++) {
    var focusLabel = document.createElement("div");
    focusLabel.id="focus_" + j;
    snapshotDiv.appendChild(focusLabel);
    focusLabel.className = "heading-div";
    focusLabel.style.width = cellWidth + "px";
    focusLabel.style.left = ((j+1) * cellWidth  + baseLeft) + "px";
    focusLabel.style.height = cellWidth + "px";
    focusLabel.style.top = (baseTop + height + cellWidth / 5) +  "px";
    focusLabel.onmouseover = showToolTip;
    focusLabel.onmouseout = hideToolTip;

    var headingLabel = document.createElement("span");
    headingLabel.id="focus_" + j + "_heading";
    headingLabel.className = "heading-label";
    focusLabel.appendChild(headingLabel);
    //Name of the focus
    headingLabel.innerHTML = focusList[j][0];

    createLabelToolTip(j, focusList[j][1], focusLabel.offsetLeft, focusLabel.offsetTop, cellWidth, snapshotDiv, "focus");
  }

  for (i=0;i<orgList.length;i++) {
    var orgLabel = document.createElement("div");
    orgLabel.id="org_" + [i];
    snapshotDiv.appendChild(orgLabel);
    orgLabel.className = "heading-div";
    orgLabel.style.width = cellWidth + "px";
    orgLabel.style.height = cellWidth + "px";
    orgLabel.style.left = baseLeft + "px";
    orgLabel.style.top = baseTop + i * cellWidth + "px";
    orgLabel.onmouseover = showToolTip;
    orgLabel.onmouseout = hideToolTip;

    headingLabel = document.createElement("h5");
    headingLabel.id="org_" + i + "_heading";
    headingLabel.className = "heading-label";
    orgLabel.appendChild(headingLabel);
    headingLabel.innerHTML = orgList[i][0];

    createLabelToolTip(i, orgList[i][1], orgLabel.offsetLeft, orgLabel.offsetTop, cellWidth, snapshotDiv, "org");
  }
}

function createLabelToolTip (id, text, left, top, cellSize, addTo, focusOrType) {
  //Create the tooltip
  var hMidpoint = addTo.offsetLeft + addTo.offsetWidth / 2;
  var vMidpoint = addTo.offsetTop + addTo.offsetHeight/ 2;
  var tooltipWidth = cellSize * 2;
  var tooltipHeight = cellSize;

  if (text == null || text.length == 0) {
    tooltipWidth = tooltipHeight = 0;
  } else if (text.length < 75) {
    tooltipWidth = cellSize * 3 / 2;
    tooltipHeight = cellSize * 3 / 4;
  }

  var toolTip = document.createElement("div");
  toolTip.id = focusOrType + "_" + id + "_tooltip" ;
  toolTip.className= "hover-box-light";
  addTo.appendChild(toolTip);

  toolTip.innerHTML = text;

  //Compute its position relative to the midpoint.
  if (left < hMidpoint) {
    toolTip.style.left = parseInt(left + (cellSize * 10 / 9)) + "px";
  } else {
    toolTip.style.left = parseInt(left - (cellSize / 4) - tooltipWidth) + "px";
  }

  if (top < vMidpoint) {
    toolTip.style.top = parseInt(top) + "px";
  } else {
    toolTip.style.top = parseInt(top - tooltipHeight) + "px";
  }
  toolTip.style.width = tooltipWidth.toString() + "px";
  toolTip.style.height = tooltipHeight.toString() + "px";
}

function printCell(cellLayout,  left, top, cellWidth, addTo) {
  var sizer = cellWidth / 3;
  var totalInCell = cellLayout[0];
  if (totalInCell <= 4) {
    sizer = cellWidth / 2;
  } else if (totalInCell <=9) {
    sizer = cellWidth / 3;
  }else if (totalInCell > 9 && totalInCell < 16) {
    sizer = cellWidth / 4;
  } else {
    console.log ("TOO MANY ITEMS IN CELL: " + cellLayout);
    sizer = cellWidth / 5;
  }

  var rowNum;
  // Start at 1 to skip the total in the first array value
  for (rowNum = 0; rowNum< cellLayout.length - 1; rowNum++) {
    var row = cellLayout[rowNum + 1];
    if (row == null || !Array.isArray(row)) {
      continue;
    }
    for (var colNum = 0; colNum < row.length; colNum++) {
      var value = parseInt(row[colNum]);
      var newLeft = parseInt(left) + colNum * sizer;
      var newTop = parseInt(top) + rowNum * sizer;
      if (value > 0) {
        putLogo(newLeft, newTop, value, sizer, addTo, cellWidth)
      }
    }
  }
}

function hideToolTip(event) {
  setToolTipVisible(event.target, "hidden");
}

function showToolTip(event) {
  setToolTipVisible(event.target, "visible");
}

/**
 * This function assumes that the "id" of the elements of valid objects with tooltips ends with a digit and those
 * which do not have tooltips end with a non-numeric character. This makes it easy to traverse the DOM to find the parent
 * which has the tooltip. In this way, we can also ensure that we can add children without worrying about still having the tooltips work.
 */
function findValidToolTipParent(element) {
  while (element != null) {
    if (element.id != null && element.id.length >= 1) {
      var substring = element.id.substr(element.id.length - 1);
      if (!isNaN(parseInt(substring))){
        return element;
      }
    }
    if (element.parentNode == document) {
      //At the top and didn't make it
      return null;
    }
    element = element.parentNode;
  }
  return element;
}

/**
 * Searches for the first parent with a numeric last element of the id and then makes its tooltip visible.
 * @param id
 * @param visible
 */
function setToolTipVisible(element, visible){
  targetElement = findValidToolTipParent(element);
  if (targetElement == null) {
    console.log("Unable to find a valid parent for element");
    console.log(element);
    return;
  }

  var target_id = targetElement.id + "_tooltip";
  var toolTip = document.getElementById(target_id);
  toolTip.style.visibility = visible;
}

function putLogo(left, top, id, size, addTo, cellWidth) {
  var src= "<?php echo $greenbase_root; ?>/localimage.php?org_id=" + id + "&";
  var org = orgs[id];
  var ending = "";
  var tooltipending = "";
  var tooltipWidth = cellWidth * 5 / 2;
  var tooltipHeight = cellWidth * 2;

  if (org["orientation"] == "square") {
    ending = "width=" + size;
    tooltipending = "width=" + cellWidth + "&height=" +  cellWidth;
  } else if (org["orientation"] == "horizontal") {
    ending = "width=" + size;
    tooltipending = "height=" + (tooltipHeight/3) + "&width=" + (tooltipWidth * 3 / 4) ;
  } else if (org["orientation"] == "vertical") {
    ending = "height=" + size;
    tooltipending = "width=" + (tooltipWidth / 3) + "&height=" + (tooltipHeight * 3 / 4) ;
  } else {
    console.log( "INVALID ORIENTATION:" + org["orientation"]);
    ending = "width="+size;
    tooltipending = "width=" + cellWidth + "&height=" +  cellWidth;
  }
  src = src + ending;

  var miniDiv = document.createElement("div");
  miniDiv.style.left = left + "px";
  miniDiv.style.top = top + "px";
  miniDiv.style.position = "absolute";
  miniDiv.style.zIndex = 2;

  addTo.appendChild(miniDiv);

  //Create the tooltip
  var hMidpoint = addTo.offsetLeft + addTo.offsetWidth / 2;
  var vMidpoint = addTo.offsetTop + addTo.offsetHeight/ 2;

  var toolTip = document.createElement("div");
  toolTip.id = "org_" + id + "_tooltip" ;
  toolTip.className= "hover-box";
  addTo.appendChild(toolTip);

  var toolTipImage = document.createElement("img");
  toolTip.appendChild(toolTipImage);
  toolTipImage.className="tooltip-logo";
  var logosrc="<?php echo $greenbase_root; ?>/localimage.php?org_id=" + id + "&" + tooltipending;
  toolTipImage.src = logosrc;
  toolTip.innerHTML = toolTip.innerHTML + "<h3>" + org["name"] + "</h3>";
  toolTip.innerHTML = toolTip.innerHTML + org["description"];

  //Create the tooltip header
  var toolTipHeader = document.createElement("table");
  toolTip.appendChild(toolTipHeader);
  toolTipHeader.className = "toolTipHeader";
  var headerRow = document.createElement("tr");
  toolTipHeader.appendChild(headerRow);
  var firstCell = document.createElement("td");
  headerRow.appendChild(firstCell);
  firstCell.style.width="50%";
  var firstFont = document.createElement("span");
  firstCell.appendChild(firstFont);
  firstFont.innerHTML = "<b>Focus:</b><br>" + org["focus"];
  var secondCell = document.createElement("td");
  headerRow.appendChild(secondCell);
  secondCell.style.width="50%";
  var secondFont = document.createElement("span");
  secondCell.appendChild(secondFont);
  secondFont.innerHTML = "<b>Org-Type:</b><br>" + org["org_type"];

  //Compute its position relative to the midpoint.
  if (left < hMidpoint) {
    toolTip.style.left = parseInt(left + (size * 5/4)) + "px";
  } else {
    toolTip.style.left = parseInt(left - (size / 4) - tooltipWidth) + "px";
  }

  if (top < vMidpoint) {
    toolTip.style.top = parseInt(top) + "px";
  } else {
    toolTip.style.top = parseInt(top - tooltipHeight + (size * 3 / 2)) + "px";
  }
  toolTip.style.width = tooltipWidth.toString() + "px";
  toolTip.style.height = tooltipHeight.toString() + "px";

  var anchor = document.createElement("a");
  anchor.href="/organization-detail-page/?org_id=" + id;
  miniDiv.appendChild(anchor);

  var logoImage = document.createElement("img");
  logoImage.id = "org_" + id;
  logoImage.src = src;
  logoImage.style.border = "0";
  logoImage.onmouseover = showToolTip;
  logoImage.onmouseout = hideToolTip;
  anchor.appendChild(logoImage);

  //correct tooltip header now...
  toolTipHeader.style.top = (toolTip.clientHeight + - toolTipHeader.clientHeight) + "px";
}

xmlhttp.onreadystatechange = function() {
  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {  

    try {
      orgs = JSON.parse(xmlhttp.responseText);
    } catch (e) {
      alert("Apologies, there is a problem connecting with the database.");
      console.log(e);
   }

    done++;
    if (done == 2) {
      drawSnapshot();
    }
  }
}

xmlhttp2.onreadystatechange = function() {
  if (xmlhttp2.readyState == 4 && xmlhttp2.status == 200) {
    try {
      layout = JSON.parse(xmlhttp2.responseText);
    } catch (e) {
      alert("Apologies, there is a problem connecting with the database. (errorno: 2)");
      console.log(e);
    }
    done++;
    if (done == 2) {
      drawSnapshot();
    }
  }
}

function snapshotLoad() {
  xmlhttp.open("GET", url, true);
  xmlhttp.send();

  xmlhttp2.open("GET", url2, true);
  xmlhttp2.send();
};

if (window.onload == null) {
   window.onload = function() {
    snapshotLoad();
   }
} else {
  window.onload = window.onload + ";\n" + "shapshotLoad()";
}
</script>
