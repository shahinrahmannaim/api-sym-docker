pipeline {
    agent any

    environment {
        GITHUB_REPO = 'https://github.com/shahinrahmannaim/api-sym-docker.git'
        DOCKER_IMAGE = '699475946478.dkr.ecr.us-east-1.amazonaws.com/symfony-api'
        AWS_REGION = 'us-east-1'
        AWS_CREDENTIALS = 'AWS_CREDENTIAL_ID'  // Replace with your Jenkins AWS credentials ID
        CLUSTER_NAME = 'jenkins-symfony'      // Replace with your ECS cluster name
    }

    stages {
        stage('Checkout') {
            steps {
                // Checkout your GitHub repository
                git branch: 'main', url: "${GITHUB_REPO}"
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    // Build Docker image using Dockerfile
                    // Build Docker image using Dockerfile
                    // Build Docker image using Dockerfile
                    sh 'docker build -t ${DOCKER_IMAGE} .'
                }
            }
        }

        stage('Push to ECR') {
            steps {
                script {
                    // Login to AWS ECR
                    withCredentials([aws(credentialsId: "${AWS_CREDENTIALS}", accessKeyVariable: 'AWS_ACCESS_KEY_ID', secretKeyVariable: 'AWS_SECRET_ACCESS_KEY')]) {
                        sh '''
                            aws ecr get-login-password --region ${AWS_REGION} | docker login --username AWS --password-stdin ${DOCKER_IMAGE}
                            docker push ${DOCKER_IMAGE}:latest
                        '''
                    }
                }
            }
        }

        stage('Deploy to ECS') {
            steps {
                script {
                    // Configure ECS CLI and deploy the service
                    withCredentials([aws(credentialsId: "${AWS_CREDENTIALS}", accessKeyVariable: 'AWS_ACCESS_KEY_ID', secretKeyVariable: 'AWS_SECRET_ACCESS_KEY')]) {
                        sh '''
                            ecs-cli configure --region ${AWS_REGION} --cluster ${CLUSTER_NAME}
                            ecs-cli compose --file docker-compose.yml --project-name symfony-api service up
                        '''
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
            echo 'Pipeline failed.'
        }
    }
}
