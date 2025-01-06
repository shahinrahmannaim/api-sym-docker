pipeline {
    agent any

    environment {
        GITHUB_REPO = 'https://github.com/shahinrahmannaim/api-sym-docker.git'
        DOCKER_IMAGE = 'symfony-api'  // Docker image name for local build
        HEROKU_APP_NAME = 'symfony-heroki'  // Replace with your Heroku app name
        HEROKU_API_KEY = credentials('HEROKU_API_KEY')  // Add Heroku API key to Jenkins credentials
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: "${GITHUB_REPO}"
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    echo 'Building Docker image...'
                    sh '''
                        docker build -t ${DOCKER_IMAGE}:latest .
                    '''
                }
            }
        }

        stage('Login to Heroku') {
            steps {
                script {
                    echo 'Logging into Heroku container registry...'
                    sh '''
                        echo ${HEROKU_API_KEY} | docker login --username=_ --password-stdin registry.heroku.com
                    '''
                }
            }
        }

        stage('Tag and Push Docker Image to Heroku') {
            steps {
                script {
                    echo 'Tagging and pushing Docker image to Heroku...'
                    sh '''
                        docker tag ${DOCKER_IMAGE}:latest registry.heroku.com/${HEROKU_APP_NAME}/web
                        docker push registry.heroku.com/${HEROKU_APP_NAME}/web
                    '''
                }
            }
        }

        stage('Release Heroku App') {
            steps {
                script {
                    echo 'Releasing Heroku app...'
                    sh '''
                        heroku container:release web --app ${HEROKU_APP_NAME}
                    '''
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
            echo 'Pipeline completed successfully! Heroku app is deployed.'
        }

        failure {
            echo 'Pipeline failed. Check logs for details.'
        }
    }
}
