function checkDoNotTrack() {
  if (navigator.doNotTrack === "1" || navigator.doNotTrack === "yes") {
    return;
  }
  const donottrackDiv = document.getElementById('donottrack'); 
  if (donottrackDiv) {
    if (navigator.doNotTrack === "0" || navigator.doNotTrack === "no") {
      console.log("Do Not Track is disabled");
      donottrackDiv.style.display = 'block';
    } else {
      console.log("Do Not Track status is unknown");
      donottrackDiv.style.color = 'orange';
      donottrackDiv.style.display = 'block';
    }
  }
}
checkDoNotTrack(); 
