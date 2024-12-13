pipeline {
    agent any

    environment {
        BACKEND_DIR = 'backend'
    }

    stages {
        stage('Install Dependencies') {
            steps {
                dir(BACKEND_DIR) {
                    sh 'composer install --no-interaction --prefer-dist'
                }
            }
        }

        stage('Build Symfony Backend') {
            steps {
                dir(BACKEND_DIR) {
                    sh 'composer build'
                }
            }
        }

        stage('Test Symfony Backend') {
            steps {
                dir(BACKEND_DIR) {
                    sh 'php bin/phpunit'
                }
            }
        }

        stage('Deploy Backend') {
            steps {
                // Deploy to your preferred environment (e.g., Docker, cloud)
                echo 'Deploying Symfony Backend...'
            }
        }
    }

    post {
        always {
            cleanWs()
        }
    }
}
