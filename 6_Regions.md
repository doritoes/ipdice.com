# Adding Regions
Our test application https://www.ipdice.com is running just fine. How can we make it more globally accessible and performant?

*See https://aws.amazon.com/blogs/networking-and-content-delivery/latency-based-routing-leveraging-amazon-cloudfront-for-a-multi-region-active-active-architecture/*

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

## Steps
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

#### Configure CloudFront to add the origin
1. Browse to (https://console.aws.amazon.com) and log in
2. In the search bar enter "cloudfront" and click on **CloudFront**
3. Click on your distribution (i.e., for ipdice.com, www.ipdice.com)
4. Click the **Origins** tab
5. Click **Create Origin**
    - Origin domain: *start typing and you will have a list to choose from* (i.e., ipdice-alb-us-west-2)
    - Origin path - optional: *leave blank*
    - Name:  *leave it as is, this is the domain name CloudFront will point to* (i.e., ipdice-alb-us-west-2-1531905465.us-west-2.elb.amazonaws.com)
    - Click **Create origin**
6. Click **Create origin group**
    - Origins: Select each origin and click **Add** (re-order as needed)
    - Name: **ipdice-origin-group**
    - Failover criteria (required)
      - 502
      - 503
      - 504
    - **Click Create origin group**
    - Edit Default behavior
      - Click **Behaviors** tab
      - Select the *Default* behavior and click **Edit**
      - Change Origin and origin groups to **ipdice-origin-group**
      - Click **Save** changes
    - Repeat for any remening behaviors (e.g., `/static/*`

#### Route 53 Setup
We are not going to set up another DNS name to use latency based routing to direct traffic to the ALBs.

### Testing
First we will generate traffic source from different global regions, followed by confirming traffic is reaching all regions.

#### Generate traffic with Online Speed Test Tools
These have limited locations, but can quickly renerate traffic to your different regions.
- Pingdom: https://tools.pingdom.com/ (select from *Test from*)
- WebPageTest: https://www.webpagetest.org/ (advanced options let you select more locations)

####  Generate traffic with VPN Services
Use a VPN service (some have free options) that lets you connect through servers in various countries. In this way you can access the site from, say, Seattle.

#### Validating Regional ECS usage
Logs
1. In the search bar enter "CloudWatch" and click on **CloudWatch**
2. Select the region you want to see logs for from the region drop-down
3. From the left Menu, expand **Logs** and click **Log groups**
4. Click on the ecs log group (e.g., /ecs/ipdice-app-us-west-2)
5. Use region dropdown to confirm you have invocations in each region

Dashboard
1. In the search bar enter "CloudWatch" and click on **CloudWatch**
2. Select the region you want to see logs for from the region drop-down
3. From the left Menu click **Dashboards** > **Automatic dashboards**
4. Click **Application ELB**

⚠️ In testing with 2 regions, not seeing the second region be hit. Need to build the third region and test.

# Learning More
- Think about pricing model of CloudFront affects your ability to add your application to regious outside US and Europe
- What do you think the capacity for each container is at 0.25vPC and 0.5Gb of memory at 80% target CPU?
- What do you think the maximum capability of the max 10 scale size is?
- How can you increase the capacity of each container to support more traffic?
