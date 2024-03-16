const mutationObserver = new MutationObserver((mutations) => {
  mutations.forEach((mutation) => {
   console.log(mutation);
  });
});

mutationObserver.observe(document.body, { 
    childList: true, // Monitor child node changes
    subtree: true,   // Monitor changes in the entire subtree
    attributes: true // Monitor changes to attributes
});
