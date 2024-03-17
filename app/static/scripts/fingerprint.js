// https://github.com/fingerprintjs/fingerprintjs
const fpPromise = import('https://openfpcdn.io/fingerprintjs/v4')
    .then(FingerprintJS => FingerprintJS.load())

fpPromise
    .then(fp => fp.get())
    .then(result => {
      console.log('Fingerprinted visitorId ' + result.visitorId + ' with confidence ' + result.confidence.score);
      console.log(JSON.stringify(result, null, 2));
      const fingerprintDiv = document.getElementById('fingerprint'); 
        if (fingerprintDiv) {
          if (result.confidence.score < 0.5) {
            fingerprintDiv.style.color = 'orange';
          }
          fingerprintDiv.style.display = 'block';
        }
    })
