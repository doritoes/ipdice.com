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
