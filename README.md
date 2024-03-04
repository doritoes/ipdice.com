# ipdice.com
Building a container-based IP address web site on Amazon ECS.

# Overview and Genesis
After building a number of container web apps and deploying them to on-premise Kubernetes (k8), I wanted to try a real-world use case. Patterning after https://github.com/doritoes/ipgiraffe.com, I am creating a new site to return a more complete and playful IP Address web site experience.

Amazon Elastic Container Servicve (ECS) offers a simplified experience with tight integration with AWS services. This is just what I want for my lab.
- I haven't used ECS before
- Use Route 53 and Cloudfront for multi-regions support
- Supports service auto scaling (container instances)
- Supports capacity provider scaling

This demonstration site has the following features:
* No ads
* Gradually release new experiences to the web application
* Demonstrate global autoscaling container applicationss without breaking the bank (don't want to cost too much for this free site)
* No SQL backend

# Project Goals
Here are the goals I have for this project. If you would like to encourage me to add additional goals or to complete these goals,  I'm open to [contributions](https://account.venmo.com/u/unclenuc) to pay my Cloud bills.

## In Scope
### Completed
- Register domain name
### Working on
- Bootstrapping repo on github
- Bootstrapping image on docker hub
### Will Do
- Basic IP address experience
- Feature: IP address information experience
### Might Do
- Feature: Dice rolling experience
- Feature: API for dice rolls (using custom dice rolling algorithm)
- Feature: API for physical dice roles (rolled and captured by Raspberry Pi-powered physical device)
## Out of Scope
- Amazon Elastic Kubernetes Service (EKS) is doesn't meet my criteria for integrating a new technology for me. I have done other Kubernetes labs ([here](https://www.unclenuc.com/lab:kubernetes_app:start) and [here](https://www.unclenuc.com/lab:stack_of_nucs:start))
- Azure and GCP are out of scope for this lab

# Step-by-Step
1. [Pre-Requisites](1_Prerequisites.md)
2. [Creating the Web App](2_ipdice.md)
3. [Building the Container](3_Building_container.md)
4. [Testing in Docker](4_Testing_docker.md)
5. [Configuring ECS](5_ECS.md)
6. [Create CloudFront](6_CloudFront.md)
7. [Update DNS](7_Route_53.md)
8. [Testing and Monitoring](8_Testing_and_Monitoring.md)
9. [Adding Regions](9_Regions.md)
10. [Next Steps](10_Next_Steps.md)

# References
My favorite IP address checking web sites:
- https://ipgoat.com
- https://ipchicken.com
- http://icanhazip.com (just the IP address, great from command line: `curl http://icanhazip.com`)
- http://www.ipdragon.com
- http://ipturtle.com
- https://ip.me

My IP address checking web sites:
- https://ipgiraffe.com
- https://ipdice.com (under construction)

My other web sites:
- https://unclenuc.com
- https://systems-monitor.com/
- https://www.cottagewifi.com/ (I have a lot of content that I want to share here)
