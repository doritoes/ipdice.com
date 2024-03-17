const mutationObserver = new MutationObserver((mutations) => {
  mutations.forEach((mutation) => {
    if (!mutation.target.classList.contains('matrix-line')) {
      if (mutation.type === 'childList' && mutation.addedNodes.length > 0) { 
        console.log("Nodes added:", mutation.addedNodes);
      }    
    }
    const allElements = document.getElementsByTagName('*');
    const elementsWithNord = [];
    for (let i = 0; i < allElements.length; i++) {
      const element = allElements[i];
      if (element.id && element.id.toLowerCase().includes('nord') ||
        element.name && element.name.toLowerCase().includes('nord') || 
        element.classList.contains('nord')) { 
        elementsWithNord.push(element);
      }
    }
  });
});

mutationObserver.observe(document.body, { 
  childList: true, 
  subtree: true,  
  attributes: true 
});

const allElements = document.getElementsByTagName('*');
const elementsWithNord = [];

for (let i = 0; i < allElements.length; i++) {
  const element = allElements[i];
  if (element.id && element.id.toLowerCase().includes('nord') || // Add guard clause
    element.name && element.name.toLowerCase().includes('nord') || 
    element.classList.contains('nord')) { 
    elementsWithNord.push(element);
  }
}

console.log(elementsWithNord); 
