# Configuring ECS
Here will will use Amazon Elastic Container Service (ECS) to host our container application. We will use Amazon Elastic Container Registry (ECR) to store and replicate our Docker image we built.

*See https://aws.amazon.com/ecr/pricing/ for details about pricing after the first year of Free Tier*

## Create an ECR Repository
### Log in to AWS Console and Navigate to ECR
1. Browse to (https://console.aws.amazon.com) and log in
2. In the search bar enter "ECR" and click on **Elastic Container Registry**

### Create repository
1. Click **Get Started**
    - General settings:
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

### Push Image to ECR
At this point you will need:
- An existing Docker image (e.g. mine is https://hub.docker.com/repository/docker/doritoes/ipdice.com)
- Your AWS account
- the AWS CLI installed and configured

1. Open a commmand line where will will use the AWS CLI
2. Authenticate and provide your AWS access keys
    - `aws configure`
  
CONTINUE HERE
