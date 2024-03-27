# app Directory Contents

## Core Files
### index.php
PHP home page that renders client's IP address and some information about the client based on the HTTP headers.
- BrowserDetection.ph from https://github.com/foroco/php-browser-detection
- Custom javascript (.js) files format the page and enable external lookup of IP address information from ip-api.com
- Custom CSS files
### details.php
PHP easter egg page triggered by a certain series of interactions
- uses client-side javascript to detect potential privacy concerns (all information stays in local browser; not exposed)
  - Detects DOM tampering (often seen from browser extensions)
  - Uses client-client javascript to attempt to fingerprint the browser
    - https://github.com/fingerprintjs/fingerprintjs
    - Brave browser and other privacy-conscious browsers block this by default
### health.php
Health check page for use by Amazon ECS to determine if the container is healthy (PHP-FPM can create web pages)
## Fonts
The fonts in /app/static/fonts can be used for personal puroposes. For commercial purposes, you will need to license your own fonts.
## Images
Midjourney was used to create most of it the images. The dice local is a create-commmons licensed SVG.
