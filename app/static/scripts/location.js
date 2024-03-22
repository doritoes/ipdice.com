// Function to fetch location data 
async function fetchLocationData(ipAddress) {
  const response = await fetch(`https://iploc8.com/api/v1/ip?ip=${ipAddress}`);
  if (response.ok) { // Check if the request was successful
    const locationData = await response.json(); 
    return locationData;
  } else {
    return null; // Return null in case of an error
  }
}
// Function to create and display the details element
function displayLocationData(locationData) {
  const detailsDiv = document.createElement('details');
  const summary = document.createElement('summary');
  summary.textContent = 'Location Details';
  detailsDiv.appendChild(summary);
  // Add Country to details
  const country = document.createElement('p');
  country.textContent = `City: ${locationData.country_long}`;
  detailsDiv.appendChild(country);
  
  // Find the existing details element for positioning
  const existingDetails = document.querySelector('.details'); 
  if (existingDetails) {
    existingDetails.parentNode.insertBefore(detailsDiv, existingDetails.nextSibling); 
  } else {
    // If no existing .details element, append directly to main
    document.querySelector('main').appendChild(detailsDiv);   
  }  
}

// Get the user's IP address
const ipAddress = document.getElementById('ip-address').textContent;

// Fetch data and display on success
fetchLocationData(ipAddress)
  .then(locationData => {
    if (locationData) {  // Proceed if we have data
      displayLocationData(locationData);
    } 
  })
  .catch(error => console.error('Error fetching location:', error));