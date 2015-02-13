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
  var table = document.createElement("table");
  var row = table.insertRow(0);
  var cell = row.insertCell(0);
  var cell2 = row.insertCell(1);
  cell.innerHtml="Cell 1";
  cell2.innerHtml = "Cell 2";

  snapshotDiv.appendChild(table);
  var width = window.innerWidth;
  var orgCount = Object.keys(orgs).length;

  var orgList = layout["orgs"];
  var focusList = layout["focus_types"];
  var tableLayout = layout["layout"];

  var sizer = parseInt (width / focusList.length / 3);
  console.log("Max size:" + sizer);
  snapshotDiv.style.height = "" + width + "px";

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
