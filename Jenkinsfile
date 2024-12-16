pipeline {
    agent any

    environment {
        // Set environment variables for your project
        GITHUB_REPO = 'https://github.com/shahinrahmannaim/api-sym-docker.git'
        DOCKER_IMAGE = 'coderscafe/symfony-api'
        AWS_ECR = '699475946478.dkr.ecr.us-east-1.amazonaws.com/symfony-api'
        AWS_REGION = 'N. Verginia'
        AWS_CREDENTIALS = '699475946478'  // The ID for your Jenkins AWS credentials
        DOCKER_HUB_CREDENTIALS = 'coderscafe'  // Jenkins credential ID for Docker Hub
    }

    stages {
        stage('Checkout') {
            steps {
                // Clone the GitHub repository
                git branch: 'main', url: "${GITHUB_REPO}"
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    // Build the Docker image
                    sh 'docker build -t ${DOCKER_IMAGE} .'
                }
            }
        }

        stage('Push to Docker Hub') {
            steps {
                script {
                    // Login to Docker Hub
                    withCredentials([usernamePassword(credentialsId: "${DOCKER_HUB_CREDENTIALS}", usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                        sh '''
                            echo $DOCKER_PASSWORD | docker login -u $DOCKER_USERNAME --password-stdin
                            docker push ${DOCKER_IMAGE}
                        '''
                    }
                }
            }
        }

        stage('AWS Login') {
            steps {
                // Login to AWS ECR
                withCredentials([aws(credentialsId: "${AWS_CREDENTIALS}")]) {
                    sh '''
                        aws ecr get-login-password --region ${AWS_REGION} | docker login --username AWS --password-stdin ${AWS_ECR}
                    '''
                }
            }
        }

        stage('Push to ECR') {
            steps {
                script {
                    // Tag the Docker image for AWS ECR
                    sh '''
                        docker tag ${DOCKER_IMAGE}:latest ${AWS_ECR}:latest
                        docker push ${AWS_ECR}:latest
                    '''
                }
            }
        }

        stage('Deploy to AWS EC2 / ECS') {
            steps {
                script {
                    // Deploy to AWS EC2 or ECS depending on your setup

                    // Example for ECS:
                    sh '''
                        ecs-cli configure --region ${AWS_REGION} --access-key $AWS_ACCESS_KEY_ID --secret-key $AWS_SECRET_ACCESS_KEY --cluster your-cluster-name
                        ecs-cli compose --file docker-compose.yml --project-name symfony-app service up
                    '''
                    // Or EC2:
                    // You can SSH into EC2 and deploy Docker there.
                    // Example:
                    // sh 'ssh -i /path/to/key.pem ec2-user@your-ec2-ip "docker pull ${DOCKER_IMAGE} && docker run -d -p 80:80 ${DOCKER_IMAGE}"'
                }
            }
        }
    }

    post {
        always {
            // Cleanup actions (if needed)
            echo 'Cleaning up...'
        }

        success {
            // Notify success or perform other actions after success
            echo 'Pipeline completed successfully!'
        }

        failure {
            // Notify failure or perform other actions after failure
            echo 'Pipeline failed.'
        }
    }
}
