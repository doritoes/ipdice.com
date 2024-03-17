# Create and Build the Web App Container
Overview:
- Clone the application from Github
- Optionally customize the web application
- Build the image
- Test the image in Docker

## Clone the Repository
1. Open a command prompt
2. Change directory to where you want to create your project (i.e., `C:\docker`)
3. `git clone https://github.com/doritoes/ipdice.com`
4. Example the new subdirectory `ipdice.com` (i.e., `c:\docker\ipdice.com`)
    - `app` directory contains the web application

## Customize the ipdice.com App
Optionally, customize the application.

## Build the image
1. From the command line change directory for your project folder
    - i.e., `cd c:\docker\ipdice.com`
2. Build the image using
    - `docker build -t ipdice .`
    - Be sure you have the dot at the end

## Test the image in Docker
1. Run the container locally:
    - `docker run -p 8080:8080 ipdice`
    - If Docker asks to if you want to permit the required network access, Accept it
2. Launch a browser and point to: http://127.0.0.1:8080
    - You are running the container on your local host, so the IP address is 127.0.0.1
    - The application port is 8080 and are mapping it to your host port 8080, hence the `:8080` at the end
    - Note the IP address displayed is your host's IP address on the Docker bridge network
      - this network does not show in your Windows IP addresses
      - open Docker Desktop, click on the running container, and click the Inspect tab then Networks to learn more
3. Back at the command line, press **Control-C** to stop the container
4. You can delete the container if you want, but keep the image as we will be pushing it to AWS ECR using in the next step
