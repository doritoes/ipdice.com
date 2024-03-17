FingerprintJS.load()
  .then(fp => fp.get())
  .then(result => {
    const visitorId = result.visitorId;
    console.log(JSON.stringify(result, null, 2)); 
  });
