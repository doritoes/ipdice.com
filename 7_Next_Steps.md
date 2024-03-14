# Next Steps

## Deploy updated container image
You will need to repeat this for each region you are deployed in.

### Push the latest container to ECR

### Update Task Definition
Is it a new image URI and/or a new tag? If so, do this:
1. In the AWS console search bar enter "ECS" and click **Elastic Container Service**
2. From the left menu click **Task definitions**
3. Click on your task (e.g. ipdice-app)
4. Click on the active revision (e.g., ipdice-app:5)
5. In the container definition section of new revision, update the image URI to point to your newly uploaded image in ECR (including the new tag)
6. Click "Create" to save the new revision.

### Update Services
1. In the AWS console search bar enter "ECS" and click **Elastic Container Service**
2. Click **Clusters** then click your cluster (i.e., ipdice-cluster)
3. In the lower pane click **Services**
4. Select your service (i.e. ipdice-service) and click **Update**
5. Is this a new image URI and/or new tag?
    - YES: Select the new revision
    - NO: Check Force new deployment
6. Click **Update**

### Confirm
1. Watch the deployment progress to complete (new task spun up, old task spun down)
2. Click on the new task, find the Public IP and open it wiht port 8080 (e.g. http://3.81.118.133:8080)

## Learnings

## Learn More
