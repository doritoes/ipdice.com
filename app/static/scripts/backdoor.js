function checkPort(port) {
  const img = document.createElement('img');
  img.src = `http://localhost:${port}`; // Use template literal for easy port changes

  img.onload = function() { 
    console.log(`Something seems to be running on port ${port}`);
  };

  img.onerror = function() {
    console.log(`Port ${port} might be closed or nothing is listening`);
  };

  document.body.appendChild(img);
}

// Check port 5800 as an example 
checkPort(5800); 
