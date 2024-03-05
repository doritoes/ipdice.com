# Configuring ECS
Here will will use Amazon Elastic Container Service (ECS) to host our container application. We will use Amazon Elastic Container Registry (ECR) to store and replicate the Docker image we built.

It is also important that we will be using the launch type **AWS Fargate**, a serverless model where AWS manages the underlying infrastructure for your. It's simpler but with less flexibility.

*See https://aws.amazon.com/ecr/pricing/ for details about pricing after the first year of Free Tier*

## Create an ECR Repository
### Log in to AWS Console and Navigate to ECR
1. Browse to (https://console.aws.amazon.com) and log in
2. Make sure you are in the desired region (e.g., `us-east-1`)
3. In the search bar enter "ECR" and click on **Elastic Container Registry**

### Create repository
1. Click **Get Started**
    - Visibility: **Private** (for image pulls)
    - Repository name: **ipdice**
    - Leave the rest at defaults
2. Click **Create repository**

### Create access key(s)
1.  In the AWS console search bar enter "IAM" and click on **IAM**
2.  Click on "Users" in the left sidebar
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
8. Description: **ECS Administration**
9. Click **Download .csv file**
    - Store this key securely!
    - Easy user can only have 2 active access keys at a time
    - This is your <u>last chance to save</u> information about the access key
10. Click Done

### Configure the AWS CLI
At this point you will need:
- An existing Docker image (e.g. mine is https://hub.docker.com/repository/docker/doritoes/ipdice.com)
- Your AWS account
- the AWS CLI installed and configured

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
1. List images `docker images`
2. Tag image
    - `docker tag <image_repository_name>:latest <your_repository_url>:latest
    - no "https" in the tag
### Push Image
1. Push: `docker push <your_repository_url>:latest`
2. Verify: `aws ecr describe-images --repository-name ipdice`

## Create an ECS Cluster
1.  In the AWS console search bar enter "ECS" and click on **Elastic Container Service**
2.  Click **Create cluster**
3.  Cluster name: **ipdice-cluster**
4.  Infrastructure: **AWS Fargate (serverless)**
5.  Click **Create**

## Define a Task Definition
1. Click **Task Definition** > **Create new task definition** from the left menu (not with JSON)
    - Task definition family: **ipdice-app**
    - Launch type: **AWS Fargate**
    - OS, Architecture, Network mode: **Linux/X86_64**
    - Network mode: automatically set to *awsvpc* for Fargate
    - CPU: **0.25 vCPU**
    - Memory: **0.5 GB**
3. Launch Type Compatibility: **Fargate**
4. Task Definition Name: **ipdice-task-def**
5. Task Role - grants your task's containers permissions to call other AWS services on your behalf (e.g., accessing an S3 bucket, sending a message to SNS) - **Leave Blank for Now**
6. Task Execution role - gives the ECS agent (running on the Fargate infrastructure) permissions to manage your tasks. It needs permissions like pulling container images from ECR and writing logs to CloudWatch - **WHATNOW**
7. 
8.  ?? Create a new IAM role for your task if needed (or choose an existing one). This role will need permissions to pull from your ECR repository. ??
9. Add Container
    - Container name: **ipdice-app**
    - Image: URI to your image (e.g., 702745267684.dkr.ecr.us-east-1.amazonaws.com/ipdice:latest)
    - Memory Limits: soft limts are fine initially
    - Port Mappings:  If your app exposes ports, add mappings (e.g., container port 80 to host port 80).
10. Click Create

