pipeline {
    agent any

    stages {
        stage('Checkout SCM') {
            steps {
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                script {
                    // Install Composer in Docker
                    sh 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer'

                    // Install PHP dependencies
                    sh 'apt-get update && apt-get install -y php php-cli php-mbstring php-xml php-curl'

                    // Run Composer install
                    sh 'composer install --no-interaction --prefer-dist'
                }
            }
        }

        stage('Build Symfony Backend') {
            steps {
                // Add build commands here
            }
        }

        stage('Deploy Backend') {
            steps {
                // Add deployment commands here
            }
        }
    }
}
