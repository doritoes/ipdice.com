# ipdice.com
Building a container-based IP address web site on Amazon ECS.

Please be forewarned that this lab is fairly expensive. AWS promises to save you up to 50% on compute costs by utilizing autonomous scaling, provisioning, and usage-based pricing. However the costs of even a lab environment add up quickly. Here the top costs:
- VPC costs
  - includes public IP address pricing (Elastic IP); reduce by removing the public IP addresses on the load balancers
  - ❓is this even possible
  - is the internet gateway this cost?
- ELB costs - you pay for AWS resources to run the load balancer(s)
  - per application load balancer-hour
  - per LCU-hour (load)
- EC2 costs - I can find no explanation why these costs increased
  - EC2-Instances / EC2 Running Hours ran up a large bill
  - We configure Load balancers in EC2, but then we get ELB costs broken out. Target group costs would seem to be under ECS. What am I being billed for here?
  - I tagged all my resources (e.g., "ipdice" with no value) but cannot use the tage in the Cost Explorer
- ECS costs - you pay for the memory and vCPU resourcs the containers use
  - reduce costs by useing the minimum required CPU and memory
- WAF costs
- Scaling costs - use step scaling and reduce the maximum number of containers

*Learn more at https://www.appsdevpro.com/blog/aws-fargate-pricing/*

[![Docker Pulls](https://img.shields.io/docker/pulls/doritoes/ipdice.com.svg)](https://hub.docker.com/r/doritoes/ipdice.com/)
![nginx 1.24](https://img.shields.io/badge/nginx-1.24-brightgreen.svg)
![php 8.3](https://img.shields.io/badge/php-8.3-brightgreen.svg)
![License MIT](https://img.shields.io/badge/license-MIT-blue.svg)

# Overview and Genesis
After building a number of container web apps and deploying them to on-premise Kubernetes (k8), I wanted to try a real-world use case. Patterning after https://github.com/doritoes/ipgiraffe.com, I am creating a new site to return a more complete and playful IP Address web site experience.

Amazon Elastic Container Service (ECS) offers a simplified experience with tight integration with AWS services. This is just what I want for my lab.
- I haven't used ECS before
- Use Route 53 and CloudFront for multi-regions support
- Supports service auto scaling (container instances)
- Supports capacity provider scaling

This demonstration site has the following features:
* No ads
* No SQL backend
* Serverless computing on Fargate
* Small container based on Alpine Linux
* Demonstrate global autoscaling container applications without breaking the bank (don't want to cost too much for this free site)
* Gradually release new experiences to the web application
* Integration with external geo-location API
* Fun easter eggs to find

# Project Goals
Here are the goals I have for this project. If you would like to encourage me to add additional goals or to complete these goals, I'm open to [contributions](https://account.venmo.com/u/unclenuc) to pay my Cloud bills.

This is mean to be a step-by-step Lab exercise that you can follow along to.

## In Scope
### Completed
- Register domain name
- Bootstrapping repo on Github
- Bootstrapping image on Docker Hub
- Basic IP address experience
- One region built
- Multi-region
- Added first two easter eggs
- Multi-region
- Add anti-snoop protection
- Integration with https://www.ip-api.com for geo-location

### Working on
- Improvement and testing
### Will Do
- No major work at this point
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

IP Lookup API options
- https://medium.com/@ipdata_co/what-is-the-best-commercial-ip-geolocation-api-d8195cda7027
- https://ip-api.com
  - Free rate-limited http-only lookup without API key
  - Paid unlimited https lookups with API key
  - Demonstrated on [IPloc8.com](https://github.com/doritoes/iploc8.com)
- https://db-ip.com/
  - Great data including threat level, "isCrawler", weatherCode, and a somewhat unreliable "useageType"
- https://ipstack.com/
  - Moderate value, addes continent, language, currency, threat level
  - Breaks out proxy, TOR, and crawler
  - <ins>very</ins> limited free option 100 queries/month

My IP address checking web sites:
- https://ipgiraffe.com
- https://ipdice.com
- https://iploc8.com (API)

My other web sites:
- https://unclenuc.com
- https://systems-monitor.com/
- https://www.cottagewifi.com/ (I have a lot of content that I want to share here)

To find your private LAN IP address:
- https://www.avast.com/c-how-to-find-ip-address
- https://nordvpn.com/what-is-my-ip/
