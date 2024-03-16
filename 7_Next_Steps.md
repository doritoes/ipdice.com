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
6. Click **Create** to save the new revision

### Update Services
1. In the AWS console search bar enter "ECS" and click **Elastic Container Service**
2. Click **Clusters** then click your cluster (i.e., ipdice-cluster)
3. In the lower pane click **Services**
4. <u>Select</u> your service (i.e. ipdice-service) and click **Update**
5. Is this a new image URI and/or new tag?
    - YES: Select the new revision
    - NO: Check <u>Force new deployment</u>
6. Click **Update**

### Confirm
1. Watch the deployment progress to complete (new task spun up, old task spun down)
2. Click on the new task, find the Public IP and open it wiht port 8080 (e.g. http://3.81.118.133:8080)

## Reducing Costs

### Turn off logging
Turn off logging after the application is working, if you don't need the logs.
1. In the AWS console search bar enter "ECS" and click **Elastic Container Service**
2. From the left menu click **Task definitions**
3. Click on your Task definition
4. Click on the active Revision
5. Click **Create new revision** > **Create new revision** 
6. *Under Logging - optional* find *Log collection*
7. <u>Uncheck</u> **Use log collection**
8. Under container-1 find Log collection and turn it **Off**
9. Click **Create**
10. From the left menu click **Clusters** then click your cluster (i.e., ipdice-cluster)
11. In the lower pane click **Services**
12. <u>Select</u> your service (i.e. ipdice-service) and click **Update**
13. Revision: *select the new revision from the dropdown*
14. Click **Update**

### Turn off HealthCheck
Turning off HealthCheck reduces traffic to your container at the cost of a) losing auto-restarts for unhealthy containers and b) waiting until the container is healthy prior to sending traffic

1. In the AWS console search bar enter "ECS" and click **Elastic Container Service**
2. From the left menu click **Task definitions**
3. Click on your Task definition
4. Click on the active Revision
5. Click **Create new revision** > **Create new revision** 
6. *Under HealthCheck - optional* find *HealthCheck*
7. Command: **delete the health check command and make it blank**
8. Click **Create**
9. From the left menu click **Clusters** then click your cluster (i.e., ipdice-cluster)
10. In the lower pane click **Services**
11. <u>Select</u> your service (i.e. ipdice-service) and click **Update**
12. Revision: *select the new revision from the dropdown*
13. Click **Update**


## Learnings

## Learn More
