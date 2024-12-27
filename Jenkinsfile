pipeline {
    agent any

    environment {
        GITHUB_REPO = 'https://github.com/shahinrahmannaim/api-sym-docker.git'
        DOCKER_IMAGE = '699475946478.dkr.ecr.us-east-1.amazonaws.com/symfony-api'
        AWS_REGION = 'us-east-1'
        AWS_CREDENTIALS = '699475946478'  // Replace with your Jenkins AWS credentials ID
        CLUSTER_NAME = 'jenkins-symfony'       // Replace with your ECS cluster name
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: "${GITHUB_REPO}"
            }
        }

        stage('Create ECS Cluster') {
            steps {
                script {
                    echo 'Checking or creating ECS cluster...'
                    withCredentials([aws(credentialsId: "${AWS_CREDENTIALS}", accessKeyVariable: 'AWS_ACCESS_KEY_ID', secretKeyVariable: 'AWS_SECRET_ACCESS_KEY')]) {
                        sh """
                            # Check if the cluster exists
                            CLUSTER_STATUS=$(aws ecs describe-clusters --clusters ${CLUSTER_NAME} --query 'clusters[0].status' --output text --region ${AWS_REGION} || echo 'MISSING')

                            if [ "$CLUSTER_STATUS" = "MISSING" ]; then
                                echo "Cluster does not exist. Creating ECS cluster..."
                                aws ecs create-cluster --cluster-name ${CLUSTER_NAME} --region ${AWS_REGION}
                                echo "Cluster ${CLUSTER_NAME} created successfully."
                            else
                                echo "Cluster ${CLUSTER_NAME} exists with status: $CLUSTER_STATUS"
                            fi
                        """
                    }
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    echo 'Building Docker image...'
                    sh """
                        docker build -t ${DOCKER_IMAGE}:latest .
                    """
                }
            }
        }

        stage('Push to ECR') {
            steps {
                script {
                    echo 'Pushing Docker image to ECR...'
                    withCredentials([aws(credentialsId: "${AWS_CREDENTIALS}", accessKeyVariable: 'AWS_ACCESS_KEY_ID', secretKeyVariable: 'AWS_SECRET_ACCESS_KEY')]) {
                        sh """
                            aws ecr get-login-password --region ${AWS_REGION} | docker login --username AWS --password-stdin ${DOCKER_IMAGE}
                            docker push ${DOCKER_IMAGE}:latest
                        """
                    }
                }
            }
        }

        stage('Deploy to ECS') {
            steps {
                script {
                    echo 'Deploying to ECS...'
                    withCredentials([aws(credentialsId: "${AWS_CREDENTIALS}", accessKeyVariable: 'AWS_ACCESS_KEY_ID', secretKeyVariable: 'AWS_SECRET_ACCESS_KEY')]) {
                        sh """
                            ecs-cli configure --region ${AWS_REGION} --cluster ${CLUSTER_NAME}
                            ecs-cli compose --file docker-compose.yml --project-name symfony-api service up
                        """
                    }
                }
            }
        }
    }

    post {
        always {
            echo 'Cleaning up workspace...'
            deleteDir()
        }

        success {
            echo 'Pipeline completed successfully!'
        }

        failure {
            echo 'Pipeline failed. Check logs for details.'
        }
    }
}
