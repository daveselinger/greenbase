<div id="snapshot" class="snapshot"><div id="loading">Snapshot loading... [This takes a while--please be patient we're building the climate change universe just for you!] <img src="./img/lightbox/loading.gif"></div></div>
<script language="javascript">
var xmlhttp = new XMLHttpRequest();
var url = "./snapshot_orgs.php";
var done = 0;
var xmlhttp2 = new XMLHttpRequest();
var url2 = "./snapshot_layout.php";
var orgs;
var layout;

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
    focusLabel.id="" + focusList[j] + "_div";
    snapshotDiv.appendChild(focusLabel);
    focusLabel.className = "heading-div";
    focusLabel.style.width = cellWidth + "px";
    focusLabel.style.left = (j * cellWidth  + baseLeft) + "px";
    focusLabel.style.height = cellWidth + "px";

    focusLabel.style.top = (baseTop + height + cellWidth / 5) +  "px";

    var headingLabel = document.createElement("h4");
    headingLabel.id="" + focusList[j] + "_h4";
    headingLabel.className = "heading-label";
    focusLabel.appendChild(headingLabel);
    headingLabel.innerHTML = focusList[j];
  }

  for (i=0;i<orgList.length;i++) {
    var orgLabel = document.createElement("div");
    orgLabel.id="" + orgList[i] + "_div";
    snapshotDiv.appendChild(orgLabel);
    orgLabel.className = "heading-div";
    orgLabel.style.width = cellWidth + "px";
    orgLabel.style.height = cellWidth + "px";
    orgLabel.style.left = baseLeft + "px";
    orgLabel.style.top = baseTop + i * cellWidth + "px";

    headingLabel = document.createElement("h4");
    headingLabel.id="" + orgList[i] + "_h4";
    headingLabel.className = "heading-label";
    orgLabel.appendChild(headingLabel);
    headingLabel.innerHTML = orgList[i];
  }
}

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

  //TODO: Need to change this from being hard-coded to 3
  var cellWidth = parseInt (width / focusList.length);
  var height = cellWidth * (orgList.length);
  snapshotDiv.style.height = height + "px";

  var baseTop = snapshotDiv.offsetTop;
  var baseLeft = snapshotDiv.offsetLeft;

  drawBorders(focusList, orgList, snapshotDiv, cellWidth);

  drawLabels(focusList, orgList, snapshotDiv, cellWidth);

  for (i=0;i<orgList.length;i++) {
    var org = orgList[i];
    for (j=0;j<focusList.length;j++) {
      var focus = focusList[j];
      var cellLayout = tableLayout[org][focus];
      if (cellLayout == null) {
        //no items
        continue;
      }
      var cellLeft = j * cellWidth + baseLeft;
      var cellTop = i * cellWidth + baseTop;

      printCell(cellLayout, cellLeft, cellTop, cellWidth, snapshotDiv);
    }
  }
  var loadingDiv = document.getElementById("loading").innerHTML="";
}

function printCell(cellLayout,  left, top, cellWidth, addTo) {
  var sizer = cellWidth / 3;
  var totalInCell = cellLayout[0];
  if (totalInCell < 4) {
    sizer = cellWidth / 2;
  } else if (totalInCell > 9 && totalInCell < 16) {
    sizer = cellWidth / 4;
  } else {
    console.log ("TOO MINY ITEMS IN CELL: " + cellLayout);
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
  var id = event.target.id;
  setToolTipVisible(id, "hidden");
}

function showToolTip(event) {
  var id = event.target.id;
  setToolTipVisible(id, "visible");
  console.log("showing:" + event.target.id);
}

function setToolTipVisible(id, visible){
  var toolTip = document.getElementById("" + id + "_tooltip");
  toolTip.style.visibility = visible;
}

function putLogo(left, top, id, size, addTo, cellWidth) {
  var src= "./localimage.php?org=" + id + "&";
  var org = orgs[id];
  var ending = "";
  var tooltipending = "";
  var tooltipWidth = cellWidth * 5 / 2;
  var tooltipHeight = cellWidth * 2;

  if (org["orientation"] == "square") {
    ending = "width=" + size;
    tooltipending = "width=" + cellWidth + "&height=" +  cellWidth;
  } else if (org["orientation"] == "horizontal") {
    console.log("horizontal:"  + id);
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
  toolTip.id = id + "_tooltip" ;
  toolTip.className= "hover-box";
  addTo.appendChild(toolTip);
  var toolTipImage = document.createElement("img");
  toolTip.appendChild(toolTipImage);
  toolTipImage.className="tooltip-left";
  var logosrc="./localimage.php?org=" + id + "&" + tooltipending;
  toolTipImage.src = logosrc;
  toolTip.innerHTML = toolTip.innerHTML + org["description"];
//  var toolTipText = document.createElement("div");
//  toolTip.appendChild(toolTipText);
//  toolTipText.innerHTML=org["description"];

  //Compute its position relative to the midpoint.
  if (left < hMidpoint) {
    toolTip.style.left = (left + (size * 5/4)) + "px";
  } else {
    toolTip.style.left = (left - (size / 4) - tooltipWidth) + "px";
  }

  if (top < vMidpoint) {
    toolTip.style.top = (top) + "px";
  } else {
    toolTip.style.top = (top - tooltipHeight + (size * 3 / 2)) + "px";
  }
  toolTip.style.width = tooltipWidth + "px";
  toolTip.style.height = tooltipHeight + "px";

  var anchor = document.createElement("a");
  anchor.href="./single_org.php?org=" + id;
  miniDiv.appendChild(anchor);

  var logoImage = document.createElement("img");
  logoImage.id = id;
  logoImage.src = src;
  logoImage.style.border = "0";
  logoImage.onmouseover = showToolTip;
  logoImage.onmouseout = hideToolTip;
  anchor.appendChild(logoImage);
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

window.onload = function() {
  xmlhttp.open("GET", url, true);
  xmlhttp.send();

  xmlhttp2.open("GET", url2, true);
  xmlhttp2.send();
};
</script>
