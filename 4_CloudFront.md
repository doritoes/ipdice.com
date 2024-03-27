# Configuring CloudFront
The next step is to create the CloudFront distribution. This acts as a global content delivery network (CDN), caching API Gateway responses closer to end-users, reducing latency and improving the overall performance. We will not incur the cost of enabling the WAF (Web Application Firewall) as the app simply returns HTML. There is no API to protect. Another reason we are using CloudFront is that in later steps we will be adding more instances to additional AWS regions. CloudFront will distribute traffic to the nearest region (latency-based routing).

**THIS WILL COST YOU MONEY** - read more at https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/CloudFrontPricing.html

It is possible to configure health checks within CloudFront to automatically route traffic away from unhealthy regions should an issue arise. **IMPORTANT** - be mindful of potential cross-region data transfer costs when using multiple origins in different regions.

https://www.stormit.cloud/blog/cloudfront-distribution-for-amazon-ec2-alb/

## Log in to AWS Console and Navigate to CloudFront
1. Browse to (https://console.aws.amazon.com) and log in
2. In the search bar enter "CloudFront" and click on **CloudFront**
   
## Create a CloudFront Distribution
1. Click **Create a CloudFront distribution**
  - Origin name: *click in this box, and select your ALB from the list* (i.e., ipdice-alb-us-east-1)
  - Protocol: **HTTPS only** on port **443** with **TLSv1.2**
  - Origin path - optional: *leave blank* :!:
  - Name: *leave it as is, this is the domain name CloudFront will point to* (i.e., ipdice-alb-us-east-1-701553201.us-east-1.elb.amazonaws.com)
  - Viewer > Viewer protocol policy: **Redirect HTTP to HTTPS**
  - Leave the remaining Origin settings at their default values
  - Default cache behavior leave at default values
  - Viewer
    - **Redirect HTTP to HTTPS**
    - Allowed HTTP methods: **GET, HEAD**
  - Caching (Important):
    - Choose **CachingDisabled**
    - Origin Request policy: (<ins>important</ins>)
      - **AllViewer**
    - Response headers policy - **NONE**
  - Web Application Firewall (WAF)
    - **Do not enable security protections**
    - Our function is not an API (just simple HTML) and doesn't need this expensive add-on
  - Settings
    - Price class: To reduce cost, set to **Use only North America and Europe**
    - Alternate domain name (CNAME) - optional
      - Click **Add item**
          - add the domain names you will use, see my examples
          - ipdice.com
          - www.ipdice.com
    - Custom SSL certificate - optional
      - From the drop-down select your new certificate
    - Default root object - **index.php** (if you have issues, change back to **/**)
    - Click **Create distribution**. Be patient as the deployment completes.
    - **TAKE NOTE** of the *distribution domain name* - you will need this (my example: https://dch6676csc92k.cloudfront.net)
2. ⚠️ You cannot test browsing to the distribution domain

## Reconfigure Route 53
Modify your existing Route 53 "A" records for both "ipdice.com" and "www.ipdice.com".
- Record type stays "**A**"
- Alias stays "**On**"
- Route traffic to: **Alias to CloudFront distribution**
- Region: **the region where your CloudFront distribution is located**
- *Select your distribution from the dropdown list*
- Routing policy: **Simple routing**
- Click **Save**

## Test
Allow for DNS entries to propagate (e.g., https://dnschecker.org/#A/www.ipdice.com)

Test each variation:
- http://www.ipdice.com
- https://www.ipdice.com
- http://ipdice.com
- https://ipdice.com

The client IP address should be returned by the application.
