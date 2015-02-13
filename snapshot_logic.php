<div id="snapshot">Snapshot loading... <img src="./img/lightbox/loading.gif"></div>
<script language="javascript">
var xmlhttp = new XMLHttpRequest();
var url = "./snapshot_orgs.php";
var done = 0;
var xmlhttp2 = new XMLHttpRequest();
var url2 = "./snapshot_layout.php";
var orgs;
var layout;

function drawSnapshot() {
  var snapshotDiv = document.getElementById("snapshot");
  var width = window.innerWidth;
  var orgCount = Object.keys(orgs).length;

  var orgList = layout["orgs"];
  var focusList = layout["focus_types"];
  var tableLayout = layout["layout"];

  var sizer = parseInt (width / focusList.length / 3);
  var cellWidth = sizer * 3;
  var height = cellWidth * (orgList.length +1);
  snapshotDiv.style.height = height + "px";

  var baseTop = snapshotDiv.style.top;
  var baseLeft = snapshotDiv.style.left;

  for (j=0;j<focusList.length;j++) {
    var focusLabel = document.createElement("div");
    focusLabel.id="" + focusList[j] + "_div";
    snapshotDiv.appendChild(focusLabel);
    focusLabel.style.position = "absolute";
    focusLabel.style.margin = "0px";
    focusLabel.innerHTML = focusList[j];
    focusLabel.style.width = cellWidth + "px";
    focusLabel.style.left = j * cellWidth  + baseLeft + "px";
    focusLabel.style.top = baseTop + height +  "px";
  }

  for (i=0;i<orgList.length;i++) {
    var orgLabel = document.createElement("div");
    orgLabel.id="" + orgList[j] + "_div";
    snapshotDiv.appendChild(orgLabel);
    orgLabel.style.position = "absolute";
    orgLabel.style.margin = "0px";
    orgLabel.innerHTML = orgList[i];
    orgLabel.style.width = cellWidth + "px";
    orgLabel.style.left = baseLeft + "px";
    orgLabel.style.top = baseTop + i * cellWidth + "px";
  }

  for (i=0;i<orgList.length;i++) {
    var org = orgList[i];
    for (j=0;j<focusList.length;j++) {
      var focus = focusList[j];
      var cellLayout = tableLayout[org][focus];
      if (cellLayout == null) {
        continue;
      }
      console.log("Org:" + org + "; focus:" + focus + "Layout:");
      console.log(cellLayout);
      var cellLeft = j * cellWidth + baseLeft;
      var cellTop = i * cellWidth + baseTop;
      var logoImage = document.createElement("div");
      var totalInCell = cellLayout[0];
      logoImage.innerHTML = "org: " + org + ";<br>focus: " + focus + ";<br>Total:"+totalInCell;
      logoImage.style.left = cellLeft + "px";
      logoImage.style.top = cellTop + "px";
      logoImage.style.position = "absolute";
      snapshotDiv.appendChild(logoImage);
    }
  }
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
