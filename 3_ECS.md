# Configuring ECS
Here will will use Amazon Elastic Container Service (ECS) to host our container application. We will use Amazon Elastic Container Registry (ECR) to store and replicate the Docker image we built.

It is also important that we will be using the launch type **AWS Fargate**, a serverless model where AWS manages the underlying infrastructure for your. It's simpler but with less flexibility.

Tip: Make sure you are in the desired region (e.g., `us-east-1`)

*See https://aws.amazon.com/ecr/pricing/ for details about pricing after the first year of Free Tier*
## Create IAM User and Access Keys
1.  In the AWS console search bar enter "IAM" and click on **IAM**
2.  Click **Users** in the left sidebar
3.  Click **Add users** or **Create user** (click Next to proceed through each step)
    - User name: **ecs-admin**
    - Permissions options: **Attach policies directly**
    - Search and select the following policies:
      - **AmazonEC2ContainerRegistryFullAccess**: Gives full access to ECR actions.
      - **AmazonECS_FullAccess**: Provides permissions to interact with ECS.
      - **CloudWatchLogsFullAccess**: Grants permissions for logging (important for monitoring your application)
    - Click **Create user**
4. Click on the new user **ecs-admin**
5. Click **Security credentials**
6. Click **Create access key**
7. Use case: **Command Line Interface (CLI)**
    - check the confirmation box and click **Next**
8. Description: **ECS Administration**
9. Click **Download .csv file**
    - Store this key securely!
    - Easy user can only have 2 active access keys at a time
    - This is your <u>last chance to save</u> information about the access key
10. Click **Done**

## Create Secret for the Repository
1. In the AWS console, search for "Secrets" and click on **Secrets Manager**
2. Click **Store a new secret**
3. Secret type: **Other type of secrets**
4. Key-value pairs: *use the information in the CSV file you downloaded*
    - Key: username
    - Value: the access key ID from your ECR access keys
    - Key: password
    - Value: the secret access keyfrom your ECR access keys
5. Secret Namee: **ecr-image-pull-credentials**
6. Description: **credentials for pulling images from ECR**
7. <u>Do not configure automatic rotation</u>
    - Once the application is up and running first try rotating the secret manually
    - Once you have successfully rotated the secret manually, look into enabling and configuration automatic rotation
8. Click **Store**
9. View the new secret *ecr-image-pull-credentials* and note the Secret ARN; you will need this later!

## Create an ECR Repository
1. In the search bar enter "ECR" and click on **Elastic Container Registry**
2. Click **Get Started** or **Create repository**
    - Visibility: **Private** (for image pulls)
    - Repository name: **ipdice**
    - Leave the rest at defaults
2. Click **Create repository**

## Configure the AWS CLI
1. Open a commmand line where will will use the AWS CLI
2. Authenticate and provide your AWS access keys
    - `aws configure`
    - Copy the access key ID from the CSV file you downloaded
    - Copy the secret access key from the CSV file you downloaded
    - Default region name: *your region* (e.g., us-east-1)
    - Default output formation: **json**

## Push Image
### Get ECR Login and Docker Working
This step authenticates the Docker client with Amazon ECR. It generates a temporary token (12 hours). It provides seamless Docker Login. The URL of your ECR repository is used for the last part.
~~~
aws ecr get-login-password --region <your-region> | docker login --username AWS --password-stdin https://<your-account-id>.dkr.ecr.<your-region>.amazonaws.com
~~~
Validate: `aws ecr describe-images --repository-name ipdice`

### Tag Image
1. List images: `docker images`
2. Tag image
    - `docker tag <image_repository_name>:latest <your_repository_url>:latest
    - no "https" in the tag
### Push Image
1. Push: `docker push <your_repository_url>:latest`
2. Verify: `aws ecr describe-images --repository-name ipdice`

## Create VPCs
You need to configure VPCs for the networking in each region. The following example uses the `us-east-1` region.
1. In the AWS console search for "VPN" and click **VPC**
2. Click **Start VPC Wizard** or **Create VPC**
    - Resources: **VPC and more**
    - Most settings can be left at default
    - Number of availability zones: **1** (default is 2; can always add more later)
    - Number of public subnets: **1** (for your load balancers)
    - Number of private subnets: **0** (can add private subnets later for ECS tasks if desired, providing extra security isolation)
    - NAT gateways: **None** for now; NAT gateways are mainly used for private subnet instances to access the internet, which we don't strictly need in this setup
    - VPC Endpoints: **None** (S3 Gateway endpoints offer optimization but can add complexity for initial setup)
    - DNS: Enable both options, **DNS hostnames** and **DNS resolution**
    - Click **Create VPC**
3. :zap:Thoroughly document your new VPC information
    - VPC ID
    - Subnet ID (public)
    - Internet Gateway ID
    - Route Table ID (automatically created)

## Create TLS Certificate using AWS Certificate Manager (ACM)
1. In the AWS console search for "Certificate" and click **Certificate Manager**
2. Click **Request**
    - Request a public certificate is selcted
    - Click **Next**
    - Fully qualified domain name (see my example below, including the www subdomain)
      - ipdice.com
      - www.ipdice.com (used the *Add another name to this certificate* button)
    - Validation method: **DNS validation**
    - Key algorithm: RSA 2048
    - Click **Request**
3. Refresh the list of certificates until your new request is listed, *Pending validation*
4. Click on the request ID for the new certifcate
5. Click **Create records in Route 53**, then click **Create records**
6. Refresh the list of certificates until the new certificate is validated and the status changes to *Issued*

## Create Aplication Load Balancer (ALB)
1. In the AWS console search for "EC2" and click **EC2**
2. From then left menu, under *Load Balancing* click **Load Balancers**
3. Click **Create load balancer**

😟 CONTINUE HERE

- ALB Creation: Create the ALB in your public subnet.
- Listener: Configure an HTTPS listener (port 443) on the ALB and attach a valid SSL/TLS certificate for your domain.
- Target Group: Create a target group to direct traffic to your ECS tasks (we'll set this up later when you deploy your ECS service).

## Create an ECS Cluster
1.  In the AWS console search bar enter "ECS" and click on **Elastic Container Service**
2.  Click **Create cluster**
3.  Cluster name: **ipdice-cluster**
4.  Infrastructure: **AWS Fargate (serverless)**
5.  Click **Create**

## Create Role ecsTaskExecutionRole
This role allows ECS tasks to pull images from ECR and perform other necessary AWS actions.

1. In another browser tab, open the AWS console and navigate to **IAM**
2. Click **Roles**
3. Search for *ecsTaskExecutionRole*
4. If it does not exist
    - Click **Create role**
    - Type: **AWS service**
    - Use case: **Elastic Container Service** > **Elastic Container Service Task** (ECS tasks will use role)
    - Click **Next**
    - Search for the **AmazonECSTaskExecutionRolePolicy** policy and check the box next to it. This is a managed policy by AWS with the appropriate permissions.
    - Click **Next**
    - Role name: **ecsTaskExecutionRole**
    - Description: *Allows ECS tasks to call AWS services on your behalf*
    - Click **Create role**

## Define a Task Definition
1. Switch back to the ECS console, the window with your cluster `ipdice-cluster`
2. Click **Task Definition** > **Create new task definition** from the left menu (not with JSON)
    - Task definition family: **ipdice-app**
    - Launch type: **AWS Fargate**
    - OS, Architecture, Network mode: **Linux/X86_64**
    - Network mode: automatically set to *awsvpc* for Fargate
    - CPU: **0.25 vCPU**
    - Memory: **0.5 GB**
3. Launch Type Compatibility: **Fargate**
4. Task Definition Name: **ipdice-task-def**
5. Task Role - grants your task's containers permissions to call other AWS services on your behalf (e.g., accessing an S3 bucket, sending a message to SNS) - **Leave Blank for Now**
6. Task Execution role - gives the ECS agent (running on the Fargate infrastructure) permissions to manage your tasks. It needs permissions like pulling container images from ECR and writing logs to CloudWatch - **ecsTaskExecutionRole**
7. Container - 1
    - Name: **ipdice-container**
    - Image URI: *your image URI* (mine is 702745267684.dkr.ecr.us-east-1.amazonaws.com/ipdice:latest)
    - Essential container: **Yes**
    - Private registry authentication: **No**
    - Port Mappings
      - Container port: **8080** (our applcation listens on port 8080)
      - Protocol: **TCP**
      - Port name: *leave blank*
      - App protocol: **HTTP**
    - Read only root file system: **Leave off** (our application can run with Read Only enabled)
    - Resource allocation limits
      - CPU: 1 vCPU
      - GPU: 1 (can't change)
      - Memory hard limit: 3GB (very generous)
      - Memory soft limit: 1GB
    - Log collection: **On** for testing, **Off** to reduce costs
    - HealthCheck - Optional: (incurs small costs)
      - Command: `CMD-SHELL, curl -f http://localhost/health.php || exit 1`
      - Interval: 30 seconds (default)
      - Timeout: 5 seconds (default)
      - Start period: 0 seconds (no need for grace period here)
      - Retries: 2 (one or two retriess before making the container unhealthy)
11. Click **Create**

## Create a Service
1. Back in the ECS console, the go to your cluster `ipdice-cluster`
2. In the lower pane, find the **Services** tab (probably already selected)
3. Click **Create**
    - Most settings will be left at defaults
    - Family: **ipdice-app**
    - Service name: **ipdice-service**
    - Desired tasks: **1** start with 1 for initial testing; can scale later
    - Deployment type: **Rolling updates**
      - After you have the lab up and running, you can experiment with the Blue/green deployment type, which uses AWS CodeDeploy
    - Launch type: **Fargate**
    - Task defintion: **ipdice-task-def**
    - Networking
    - 
    - 
    
    - Networking: configre your load balancer and security groups here
    - Auto scaling
    - service discovery
    - create
