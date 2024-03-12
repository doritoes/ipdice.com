document.addEventListener('DOMContentLoaded', function() { // Ensure page loads before executing
    const copyButton = document.getElementById('copy-button');
    const ipAddressElement = document.getElementById('ip-address');

    copyButton.addEventListener('click', () => {
        const ipAddress = ipAddressElement.textContent;

        navigator.clipboard.writeText(ipAddress)
            .then(() => {
                copyButton.style.backgroundColor = '#4CAF50';
                // Optionally reset color after a delay
                setTimeout(() => {
                    copyButton.style.backgroundColor = '#A0A0A0';
                }, 1000); // 1 second delay
            })
            .catch(err => {
                console.error("Failed to copy IP address:", err);
            });
    });
});
