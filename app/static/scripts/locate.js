// Function to fetch location data
async function fetchLocationData(ip, apikey) {
  const response = await fetch(`https://pro.ip-api.com/json/${ip}?fields=status,message,city,regionName,isp,org,country,mobile,proxy,hosting&key=${apikey}`);
  if (response.ok) { // Check if the request was successful
    const locationData = await response.json();
    return locationData;
  } else {
    return null; // Return null in case of an error
  }
}

// Function to display the results
function displayLocationData(locationData) {
  // Create the new div element
  const newDiv = document.createElement('div');
  newDiv.className = 'location-data'; // Add a class for styling
  // Create individual <p> elements
  const name = document.createElement('p');
  name.textContent = '[+] Location:';
  const city = document.createElement('p');
  city.textContent = `City: ${locationData.city}`;
  city.className = 'location-data-details';
  const state = document.createElement('p');
  state.textContent = `State: ${locationData.regionName}`;
  state.className = 'location-data-details';
  const country = document.createElement('p');
  country.textContent = `Country: ${locationData.country}`;
  country.className = 'location-data-details';
  const isp = document.createElement('p');
  isp.textContent = `ISP: ${locationData.isp} - ${locationData.org}`;
  isp.className = 'location-data-details';
  // Append the <p> elements to the div
  newDiv.appendChild(name);
  newDiv.appendChild(city);
  newDiv.appendChild(state);
  newDiv.appendChild(country);
  newDiv.appendChild(isp);
  if (locationData.mobile) {
    const mobile = document.createElement('p');
    mobile.textContent = 'Mobile: True';
    mobile.className = 'location-data-details';
    newDiv.appendChild(mobile);
  }
  if (locationData.proxy) {
    const proxy = document.createElement('p');
    proxy.textContent = 'Proxy (Proxy/VPN/TOR): True';
    proxy.className = 'location-data-details';
    newDiv.appendChild(proxy);
  }
  if (locationData.hosting) {
    const hosting = document.createElement('p');
    hosting.textContent = 'Hosting evironment: True';
    hosting.className = 'location-data-details';
    newDiv.appendChild(hosting);
  }
  // Insert the div into the DOM
  const outputBlock = document.querySelector('.output-block');
  const fingerprintDiv = document.querySelector('.fingerprint'); 
  outputBlock.insertBefore(newDiv, fingerprintDiv.nextSibling); 
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
