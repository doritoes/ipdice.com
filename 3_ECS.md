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

### Create Secret for the Repository
1. In the AWS console, search for "Secrets" and click on **Secrets Manager**
2. Click **Store a new secret**
3. Secret type: **Other type of secrets**
4. Key-value pairs:
    - Key: username
    - Value: the access key ID from your ECR access keys
    - Key: password
    - Value: the secret access keyfrom your ECR access keys
5. Secret Namee: **ecr-image-pull-credentials**
6. Descrition: **credentials for pulling images from ECR**
7. Click **Store**
8. View the new secret *ecr-image-pull-credentials* and note the Secret ARN; you will need this later!

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

## Create Role ecsTaskExecutionRole
1. In another browser tab, open the AWS console and navigate to **IAM**
2. Click **Roles**
3. Search for *ecsTaskExecutionRole*
4. If it does not exist
    - Click **Create role**
    - Type: **AWS service**
    - Use case: **Elastic Container Service** > **Elastic Container Service Task** (ECS tasks will use role)
    - Search for the **AmazonECSTaskExecutionRolePolicy** policy and check the box next to it. This is a managed policy by AWS with the appropriate permissions.
    - Role name: **ecsTaskExecutionRole**
    - Description: *Allows ECS tasks to call AWS services on your behalf*
    - Click **Create role**

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
6. Task Execution role - gives the ECS agent (running on the Fargate infrastructure) permissions to manage your tasks. It needs permissions like pulling container images from ECR and writing logs to CloudWatch - **ecsTaskExecutionRole**
7. Container - 1
    - Name: **ipdice-container**
    - Image URI: *your image URI* (mine is 702745267684.dkr.ecr.us-east-1.amazonaws.com/ipdice:latest)
    - Essential container: **Yes**
    - Private registry authentication: **Yes**
      - Secrets Manager ARN or name: *Copy the secret's ARN from the AWS Secrets Manager*
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
8. Click **Create**
10. Add Container
    - Container name: **ipdice-app**
    - Image: URI to your image (e.g., 702745267684.dkr.ecr.us-east-1.amazonaws.com/ipdice:latest)
    - Memory Limits: soft limts are fine initially
    - Port Mappings:  If your app exposes ports, add mappings (e.g., container port 80 to host port 80).
11. Click Create

ERROR
~~~~
Private repository credentials are not a supported authentication method for ECR repositories.
~~~~
