# Adding Regions
Our test application https://www.ipdice.com is running just fine. How can we make it more globally accessible and performant?

*See https://aws.amazon.com/blogs/networking-and-content-delivery/latency-based-routing-leveraging-amazon-cloudfront-for-a-multi-region-active-active-architecture/*

⚠️This section needs to be re-written from Lambda to ECS.
## Primary and Failover Regions
In this example we are doing to use:
- us-east-1 (N. Virginia)
- us-west-2 (Oregon)
- eu-central-1 (Frankfurt)

Overview:
- add secrets to each region
- add security groups to each region
- add clusters to each region
- add load balancer to each region
- add Load Balancer function URL to CloudFront
- update Route 53

### Steps
1. Create Secret for the Repository in the addtional regions
2. Create ECR repository in the new regions
3. Configure the AWI CLI for the region, tag & push the image to the ECR; repeat each region
4. Create VPC in the addional regions
5. Create Security Groups (ECS and ALB) in the additional regions
6. Create TLS certificate in the additional regions
7. Create Application Load Balancer (ALB) in the additional regions
8. Create ECS cluster in the additonal regions
9. Create Task Definition in the additional regions
10. Create a Service in the additional regions
11. Configure CloudFront to add the origin
12. Route 53 Setup


#### Route 53 Setup
1. Browse to (https://console.aws.amazon.com) and log in
2. In the search bar enter "route" and click on **Route 53**
3. Note that Route 53 is in the *Global* region
4. Click **Hosted zones**
5. Click on your domain name (in my example, ipdice.com)
6. Click **Create record**
    - Record Name: **app** (or your designed subdomain)
    - Record Type: Leave as **A - IPv4 Address**
    - Alias: **YES**
    - Route traffic to:
      - Select **API Gateway REST APIs** from the dropdown menu
      - Region: Chooose the region
    - Value: Enter the domain name of your API gateway in this region (my example is 59o1ou36kb.execute-api.us-east-1.amazonaws.com)
    - Routing Policy: **Latency**
    - Region: Select your first region (in my case us-east-1)
    - Healtch check ID - options: **Leave blank**
    - Evaluate target health: **NO** (default) to save cost, or enable to allow Route 53 to monitor your API Gateway endpoint
    - Record ID: **app-us-east-1**
7. Click **Add another record** and repeat for your second region (in my case us-west-2)
    - As this is an advanced section of the lab, I am allowing you to determine the settings
8. Click **Create records**

#### CloudFront Setup
1. Browse to (https://console.aws.amazon.com) and log in
2. In the search bar enter "CloudFront" and click on **CloudFront**
3. Note that CloudFront is in the *Global* region
4. Find and click on your distribution
5. Click **Edit**
6. Alternate Domain Names - add the *app* subdomain record (see my example below)
    - add **app.ipdice.com**
7. We recreated the Custom SSL certificate. Just make sure it is selected.
8. Click **Save changes**

### Testing
First we will generate traffic source from different global regions, followed by confirming traffic is reaching all regions.
#### Generate traffic with Online Speed Test Tools
These have limited locations, but can quickly renerate traffic to your different regions.
- Pingdom: https://tools.pingdom.com/ (select from *Test from*)
- WebPageTest: https://www.webpagetest.org/ (advanced options let you select more locations)

####  Generate traffic with VPN Services
Use a VPN service (some have free options) that lets you connect through servers in various countries. In this way you can access the site from, say, Seattle.

#### Validating Lambda funtion usage
1. In the search bar enter "CloudWatch" and click on **CloudWatch**
2. From the left Menu, expand **Metrics** and click **All metrics**
3. ___ which metrics for ECS and CloudFront?
4. Use region dropdown to confirm you have invocations in each region

# Learning More
- Think about pricing model of CloudFront affects your ability to add your application to regious outside US and Europe
- What do you think the capacity for each container is at 0.25vPC and 0.5Gb of memory at 80% target CPU?
- What do you think the maximum capability of the max 10 scale size is?
- How can you increase the capacity of each container to support more traffic?
  
