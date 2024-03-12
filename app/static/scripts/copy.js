document.addEventListener('DOMContentLoaded', function() { // Ensure page loads before executing
    const copyButton = document.getElementById('copy-button');
    const ipAddressElement = document.getElementById('ip-address');

    copyButton.addEventListener('click', () => {
        const ipAddress = ipAddressElement.textContent;

        navigator.clipboard.writeText(ipAddress)
            .then(() => {
                alert("IP Address copied!");
            })
            .catch(err => {
                console.error("Failed to copy IP address:", err);
            });
    });
});
