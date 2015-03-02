<div id="snapshot"><div id="loading">Snapshot loading... <img src="./img/lightbox/loading.gif"></div></div>
<script language="javascript">
var xmlhttp = new XMLHttpRequest();
var url = "./snapshot_orgs.php";
var done = 0;
var xmlhttp2 = new XMLHttpRequest();
var url2 = "./snapshot_layout.php";
var orgs;
var layout;

function drawBorders(focusList, orgList, snapshotDiv, width) {
  //TODO: Populate
  var baseTop = snapshotDiv.style.top;
  var baseLeft = snapshotDiv.style.left;

  var sizer = parseInt (width / focusList.length / 3);
  var cellWidth = sizer * 3;
  var height = cellWidth * (orgList.length);

  var orgBackground = document.createElement("div");
  snapshotDiv.appendChild(orgBackground);
  orgBackground.style.zIndex=1;
  orgBackground.style.position="absolute";
  orgBackground.style.margin="0px";
  orgBackground.style.width= cellWidth + "px";
  orgBackground.style.left = (baseLeft) + "px";
  orgBackground.style.top=(baseTop - cellWidth) + "px";
  orgBackground.style.height=((orgList.length + 2) * cellWidth) + "px";
  orgBackground.style.backgroundColor = "#4674a2";

  var dataBackground = document.createElement("div");
  snapshotDiv.appendChild(dataBackground);
  dataBackground.style.zIndex=1;
  dataBackground.style.position="absolute";
  dataBackground.style.margin="0px";
  dataBackground.style.width= (focusList.length * cellWidth) + "px";
  dataBackground.style.left = (baseLeft + cellWidth) + "px";
  dataBackground.style.top=(baseTop - cellWidth) + "px";
  dataBackground.style.height=((orgList.length + 1) * cellWidth) + "px";
  dataBackground.style.backgroundColor = "#ffffff";

  var focusBackground = document.createElement("div");
  snapshotDiv.appendChild(focusBackground);
  focusBackground.style.zIndex=1;
  focusBackground.style.position="absolute";
  focusBackground.style.margin="0px";
  focusBackground.style.width= (focusList.length * cellWidth) + "px";
  focusBackground.style.left = (baseLeft + cellWidth) + "px";
  focusBackground.style.top= ((orgList.length) * cellWidth) + "px";
  focusBackground.style.height=(cellWidth) + "px";
  focusBackground.style.backgroundColor = "#919191";

}

function drawLabels(focusList, orgList, snapshotDiv, width) {
  var baseTop = snapshotDiv.style.top;
  var baseLeft = snapshotDiv.style.left;

  var sizer = parseInt (width / focusList.length / 3);
  var cellWidth = sizer * 3;
  var height = cellWidth * (orgList.length);
  snapshotDiv.style.height = height + "px";

  for (j=0;j<focusList.length;j++) {
    var focusLabel = document.createElement("div");
    focusLabel.id="" + focusList[j] + "_div";
    snapshotDiv.appendChild(focusLabel);
    focusLabel.style.position = "absolute";
    focusLabel.style.margin = "0px";
    focusLabel.style.width = cellWidth + "px";
    focusLabel.style.left = (j * cellWidth  + baseLeft) + "px";
    focusLabel.style.top = (baseTop + height) +  "px";
    focusLabel.style.zIndex = 2;

    var headingLabel = document.createElement("h4");
    headingLabel.id=="" + focusList[j] + "_h4";
    focusLabel.appendChild(headingLabel);
    headingLabel.innerHTML = focusList[j];
  }

  for (i=0;i<orgList.length;i++) {
    var orgLabel = document.createElement("div");
    orgLabel.id="" + orgList[i] + "_div";
    snapshotDiv.appendChild(orgLabel);
    orgLabel.style.position = "absolute";
    orgLabel.style.margin = "0px";
    orgLabel.style.width = cellWidth + "px";
    orgLabel.style.left = baseLeft + "px";
    orgLabel.style.top = baseTop + i * cellWidth + "px";
    orgLabel.style.zIndex = 2;

    headingLabel = document.createElement("h4");
    headingLabel.id=="" + orgList[i] + "_h4";
    orgLabel.appendChild(headingLabel);
    headingLabel.innerHTML = orgList[i];
  }
}

function drawSnapshot() {
  var snapshotDiv = document.getElementById("snapshot");
  var width = window.innerWidth;

  //Handle the template width (GAR!)
  var containerWidth = document.getElementsByClassName("container")[0].clientWidth;
  if (containerWidth < width) {
    console.log("Adjusting width to:" + containerWidth);
    width = containerWidth;
  } else {
    console.log("Keeping width at " + width + " instead of " + containerWidth);
  }
  console.log("width:" + width);

  var orgCount = Object.keys(orgs).length;

  var orgList = layout["orgs"];
  var focusList = layout["focus_types"];
  var tableLayout = layout["layout"];

  var sizer = parseInt (width / focusList.length / 3);
  var cellWidth = sizer * 3;
  var height = cellWidth * (orgList.length);
  snapshotDiv.style.height = height + "px";

  var baseTop = snapshotDiv.style.top;
  var baseLeft = snapshotDiv.style.left;

  drawBorders(focusList, orgList, snapshotDiv, width);

  drawLabels(focusList, orgList, snapshotDiv, width);

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
      var totalInCell = cellLayout[0];

      var cellSizer = sizer;

      printCell(cellLayout, cellLeft, cellTop, cellSizer, snapshotDiv);
    }
  }
  var loadingDiv = document.getElementById("loading").innerHTML="";
}

function printCell(cellLayout,  left, top, sizer, addTo) {
  var totalInCell = cellLayout[0];

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
        putLogo(newLeft, newTop, "./localimage.php?org=" + value + "&width=" + sizer, "hovertext", addTo)
      }
    }
  }
}

function putLogo(left, top, src, hoverText, addTo) {

  var logoImage = document.createElement("img");
  logoImage.src = src;

  var miniDiv = document.createElement("div");
  miniDiv.style.left = left + "px";
  miniDiv.style.top = top + "px";
  miniDiv.style.position = "absolute";
  miniDiv.style.zIndex = 2;


  addTo.appendChild(miniDiv);

  var anchor = document.createElement("a");
  anchor.title = hoverText;
  miniDiv.appendChild(anchor);

  var logoImage = document.createElement("img");
  logoImage.src = src;
  logoImage.style.border = "0";
  anchor.appendChild(logoImage);

  //TODO: This is where we need to add the hover div.
}

xmlhttp.onreadystatechange = function() {
  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {  

    try {
      orgs = JSON.parse(xmlhttp.responseText);
    } catch (e) {
      alert (e);
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
      alert("2");
      alert(e);
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
