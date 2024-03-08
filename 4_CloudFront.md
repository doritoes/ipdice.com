# Configuring CloudFront
The next step is to create the CloudFront distribution. This acts as a global content delivery network (CDN), caching API Gateway responses closer to end-users, reducing latency and improving the overall performance. We will not incur the cost of enabling the WAF (Web Application Firweall) as the app simply returns HTML. There is no API to protect. Another reason we are using Cloudfront is that in later steps we will be adding more instances to additional AWS regions. CloudFront will distribute traffic to the nearest region (latency-based routing).

It is possible to  configure health checks within CloudFront to automatically route traffic away from unhealthy regions should an issue arrise. **IMPORTANT** - be mindful of potential cross-region data transfer costs when using multiple origins in different regions.

## Log in to AWS Console and Navigate to CloudFront
1. Browse to (https://console.aws.amazon.com) and log in
2. In the search bar enter "CloudFront" and click on **CloudFront**
   
## Create a CloudFront Distribution
If prompted for a distribution type, select "Web" distribution and proceed.

:worried: Need to rework the instructions for ECS and ALB

1. Click **Create a CloudFront distribution**
  - Origin name: *paste your invoke URL here*
    - You can find this in your API Gateway console
    - Example: (https://59o1ou36kb.execute-api.us-east-1.amazonaws.com/Prod)
  - Origin path - optional: *leave blank*
  - Name: **myIP-backend-prod**
  - Leave the remaining Origin settings at their default values
  - Default cache behavior leave at default values
  - Click **Create Origin**
  - Viewer
    - **Redirect HTTP to HTTPS**
    - Allowed HTTP methods: **GET, HEAD**
  - Caching (Important):
    - Choose CachingDisabled or set a very short TTL (Time-to-Live) to ensure the latest IP address is always retrieved.
    - Leave the remaining settings at their default values
  - Web Application Firewall (WAF)
    - **Do not enable security protections**
    - Our function is not an API (just simple HTML) and doesn't need this expensive add-on
  - Settings
    - Price class: To reduce cost, set to **Use only North America and Europe**
    - Altername domain name (CNAME) - optional
      - Click **Add item**
          - add the domain names you will use, see my examples
          - ipgiraffe.com
          - www.ipgiraffe.com
    - Custom SSL certificate - optional
      - Click **Request certificate**
      - Certificate type *Public*, click **Next**
      - Enter the domain names you will use (e.g., ipgiraffe.com and www.ipgiraffe.com using the button *Add another name to this certificate*)
      - Validation method: **DNS validation**
      - Click **Request**
      - Click **List certificates**, click view your certificate
      - The status will be *Pending validation*
      - Click **Create Records in Route 53**
      - Click **Create records** (This allows the certificate to successfully validate; it shouldn't take long)
    - Back in the CloudFront distribution screen click the refresh arrow
    - From the drop-down select your new certificate
    - Click **Create distribution**. Be patient as the deployment completes.
    - **TAKE NOTE** of the *distribution domain name* - you will need this (my example: https://d3tv86pu6k48e5.cloudfront.net)

## Fix the incorrect IP address
The source IP that the Lamba function sees will be the last cloudfront host in the path. That's not what we want. We want the client IP address.

We will configure the X-Forwarded-For header to get the client's real IP address.

On the left menu bar below Distributions there is Policies.

1. Click on **Policies**
2. Click **Origin Request** from the ribbon menu
3. In the *Custom policies* pane, click **Create origin request policy**
    - Name: **IP-Forwarding-Policy**
    - Headers
      - Select **Include the following headers**
      - Add header > Click Add custom > X-Forwarded-For
    - Click **Save changes**

The correct list IP address will now be returned.
