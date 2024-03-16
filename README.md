# ipdice.com
Building a container-based IP address web site on Amazon ECS.

[![Docker Pulls](https://img.shields.io/docker/pulls/doritoes/ipdice.com.svg)](https://hub.docker.com/r/doritoes/ipdice.com/)
![nginx 1.24](https://img.shields.io/badge/nginx-1.24-brightgreen.svg)
![php 8.3](https://img.shields.io/badge/php-8.3-brightgreen.svg)
![License MIT](https://img.shields.io/badge/license-MIT-blue.svg)

# Overview and Genesis
After building a number of container web apps and deploying them to on-premise Kubernetes (k8), I wanted to try a real-world use case. Patterning after https://github.com/doritoes/ipgiraffe.com, I am creating a new site to return a more complete and playful IP Address web site experience.

Amazon Elastic Container Servicve (ECS) offers a simplified experience with tight integration with AWS services. This is just what I want for my lab.
- I haven't used ECS before
- Use Route 53 and Cloudfront for multi-regions support
- Supports service auto scaling (container instances)
- Supports capacity provider scaling

This demonstration site has the following features:
* No ads
* Small container based on Alpine Linux
* Serverless computing on Fargate
* Gradually release new experiences to the web application
* Demonstrate global autoscaling container applicationss without breaking the bank (don't want to cost too much for this free site)
* No SQL backend
* Fun easter eggs to find

# Project Goals
Here are the goals I have for this project. If you would like to encourage me to add additional goals or to complete these goals,  I'm open to [contributions](https://account.venmo.com/u/unclenuc) to pay my Cloud bills.

## In Scope
### Completed
- Register domain name
- Bootstrapping repo on github
- Bootstrapping image on docker hub
- One region built
### Working on
- Multi-region
### Will Do
- Basic IP address experience
- Feature: IP address information experience
### Might Do
- Feature: Dice rolling experience
- Feature: API for dice rolls (using custom dice rolling algorithm)
- Feature: API for physical dice roles (rolled and captured by Raspberry Pi-powered physical device)
## Out of Scope
- Amazon Elastic Kubernetes Service (EKS) doesn't meet my criteria for integrating a new technology for me. I have done other Kubernetes labs ([here](https://www.unclenuc.com/lab:kubernetes_app:start) and [here](https://www.unclenuc.com/lab:stack_of_nucs:start)).
- Azure and GCP are out of scope for this lab

# Step-by-Step
1. [Pre-Requisites](1_Prerequisites.md)
2. [Create and Build the Web App Container](2_ipdice.md)
3. [Configuring ECS](3_ECS.md)
4. [Configuring CloudFront](4_CloudFront.md)
5. [Testing and Monitoring](5_Testing_and_Monitoring.md)
6. [Adding Regions](6_Regions.md)
7. [Next Steps](7_Next_Steps.md)

# References
My favorite IP address checking web sites:
- https://ipgoat.com
- https://ipchicken.com
- http://icanhazip.com (just the IP address, great from command line: `curl http://icanhazip.com`)
- https://api.ipify.org/ (another bare IP address service)
- http://www.ipdragon.com
- http://ipturtle.com
- https://ip.me
- https://whatismyip.org/

More "IP Animals":
- https://ipmonkey.com
- https://ipfish.com (redirects to ipchicken.com)

My IP address checking web sites:
- https://ipgiraffe.com
- https://ipdice.com

My other web sites:
- https://unclenuc.com
- https://systems-monitor.com/
- https://www.cottagewifi.com/ (I have a lot of content that I want to share here)

To find your private LAN IP address:
- https://www.avast.com/c-how-to-find-ip-address
- https://nordvpn.com/what-is-my-ip/
