# Testing and Monitoring
Your site should be up and running (allow time for DNS propagation)

## Browser Testing
Point your Browser to your site to test each way the site can be accessed
- HTTP to the domain name (i.e., http://ipdice.com which should redirect to https)
- HTTPS to the domain name (i.e., https://ipdice.com)
- HTTP to the subdomain (i.e., http://www.ipdice.com which should redirect to https)
- HTTPS to the subdomain (i.e., https://www.ipdice.com)

## nslookup
From a command line test both DNS lookups. For example:
- ```nslookup ipdice.com```
- ```nslookup www.ipdice.com```

## DNS global propagation
- Visit https://dnschecker.org/
- Enter your domain name and click Search
  - Examples:
    - https://dnschecker.org/#A/ipdice.com
    - https://dnschecker.org/#A/www.ipdice.com
   
## Monitoring
Feel free to experiment with CloudWatch (search for "CloudWatch" in the AWS console). Be aware that configuring monitoring will generate additional traffic (load).

Start with *view automatic dashboards* and work from there.

CloudWatch > Dashboards > Automatic dashboards
- Elastic Container Service

CloudWatch > Metrics > All Metrics
- ECS - View automatic dashboard
- ApplicationELB - View automatic dashboard
- CloudFront - View automatic dashboard
  - Request count
  - Error rates

CloudWatch > Logs > Log Groups > /ecs/ipdice-app
- search for events containing words like "scaling", "target tracking", or "capacity"
