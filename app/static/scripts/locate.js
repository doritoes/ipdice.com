// Function to fetch location data 
async function fetchLocationData(apiKey, ipAddress) {
  try {
    // Login request
    const loginResponse = await fetch('https://iploc8.com/api/v2/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ "api_key": apiKey })
    });

    if (!loginResponse.ok) {
      throw new Error("Login failed"); // Handle login errors
    }  
    const loginData = await loginResponse.json(); 
    const jwtToken = loginData.access_token;

    // Location fetch with JWT
    const locationResponse = await fetch('https://iploc8.com/api/v2/ip', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${jwtToken}` // Set the Authorization header
      },
      body: JSON.stringify({ ip: ipAddress }) 
    });

    if (!locationResponse.ok) {
      throw new Error("Location fetch failed"); 
    }  
    return await locationResponse.json(); 

  } catch (error) {
    console.error('Error fetching location:', error); 
    // Handle errors appropriately, maybe display a message to the user
  }
}

// Function to display the results
function displayLocationData(locationData) {
  // Create the new div element
  const newDiv = document.createElement('div');
  newDiv.className = 'location-data'; // Add a class for styling

  // Populate the div with location information
  newDiv.textContent = `
    <p>City: ${locationData.city}</p>
    <p>State: ${locationData.state}</p> <p>Country: ${locationData.country_long}</p>
  `;

  // Insert the div into the DOM
  const outputBlock = document.querySelector('.output-block');
  const fingerprintDiv = document.querySelector('.fingerprint'); 
  outputBlock.insertBefore(newDiv, fingerprintDiv.nextSibling); 
}

// Get the user's IP address
const ipAddress = document.getElementById('ip-address').textContent;

// Get your API key (replace with how you'll securely store and retrieve it)
const apiKey = 'e95b186d-3677-4466-9cb2-20a549ab1d85'; 

// Fetch and display the location data
fetchLocationData(apiKey, ipAddress)
  .then(locationData => displayLocationData(locationData)) 
