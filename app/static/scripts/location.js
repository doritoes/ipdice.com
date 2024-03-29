// Function to fetch location data
async function fetchLocationData(ip, apikey) {
  const response = await fetch(`https://pro.ip-api.com/json/${ip}?fields=status,message,city,regionName,isp,org&key=${apikey}`);
  if (response.ok) { // Check if the request was successful
    const locationData = await response.json();
    return locationData;
  } else {
    return null; // Return null in case of an error
  }
}

// Function to create and display the details element
function displayLocationData(locationData) {
  if (!locationData) {
    return;
  }
  const city = document.createElement('div');
  city.textContent = `City: ${locationData.city}`;
  city.classList.add('details');
  const state = document.createElement('div');
  state.textContent = `State: ${locationData.regionName}`;
  state.classList.add('details');
  const isp = document.createElement('div');
  isp.textContent = `ISP: ${locationData.isp} - ${locationData.org}`;
  isp.classList.add('details');

  // Find the existing details element for positioning
  const existingDetails = document.querySelector('.details');
  if (existingDetails) {
    existingDetails.parentNode.insertBefore(state, existingDetails.nextSibling);
    existingDetails.parentNode.insertBefore(city, existingDetails.nextSibling);
    existingDetails.parentNode.insertBefore(isp, existingDetails.nextSibling);
  } else {
    // If no existing .details element, append directly to main
    document.querySelector('main').appendChild(isp);
    document.querySelector('main').appendChild(city);
    document.querySelector('main').appendChild(state);
  }
}

// Get the user's IP address
const ipAddress = document.getElementById('ip-address').textContent;
// Authentication
const key = atob("VnNDUXlkTG10SHhUbU9q");
// Fetch data and display on success
fetchLocationData(ipAddress, key)
  .then(locationData => {
    if (locationData) {  // Proceed if we have data
      displayLocationData(locationData);
    }
  })
  .catch(error => console.error('Error fetching location:', error));
