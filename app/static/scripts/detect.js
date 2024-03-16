const mutationObserver = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        if (!mutation.target.classList.contains('matrix-line')) { 
            console.log(mutation);
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

const allElements = document.getElementsByTagName('*');
const elementsWithNord = [];

for (let i = 0; i < allElements.length; i++) {
    const element = allElements[i];
    if (element.id.toLowerCase().includes('nord') ||
        element.name.toLowerCase().includes('nord') || 
        element.classList.contains('nord')) { 
        elementsWithNord.push(element);
    }
}

console.log(elementsWithNord); 
