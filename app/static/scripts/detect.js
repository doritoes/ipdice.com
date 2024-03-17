const mutationObserver = new MutationObserver((mutations) => {
  mutations.forEach((mutation) => {
    if (!mutation.target.classList.contains('matrix-line')) {
      if (mutation.type === 'childList' && mutation.addedNodes.length > 0) { 
        console.log("Nodes added:", mutation.addedNodes);
        console.log(mutation.addedNodes[0]);
      }    
    }
  });
});

mutationObserver.observe(document.body, { 
  childList: true, 
  subtree: true,  
  attributes: true 
});
