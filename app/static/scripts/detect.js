const mutationObserver = new MutationObserver((mutations) => {
  mutations.forEach((mutation) => {
    if (!mutation.target.classList.contains('matrix-line')) {
      if (mutation.type === 'childList' && mutation.addedNodes.length > 0) { 
        console.log("Nodes added:", mutation.addedNodes);
        const tamperDiv = document.getElementById('tamper'); 
        if (tamperDiv) {
          tamperDiv.style.display = 'block';
        }
      }    
    }
  });
});
mutationObserver.observe(document.body, { 
  childList: true, 
  subtree: true,  
  attributes: true 
});
