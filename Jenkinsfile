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
                    // Install PHP and necessary extensions without sudo
                    sh '''
                    apt-get update
                    apt-get install -y php php-cli php-mbstring php-xml php-curl
                    '''

                    // Install Composer
                    sh 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer'

                    // Verify Composer installation
                    sh 'composer --version'
                }
            }
        }

        stage('Build Symfony Backend') {
            steps {
                echo 'Building Symfony Backend...'
            }
        }

        stage('Deploy Backend') {
            steps {
                echo 'Deploying Symfony Backend...'
            }
        }
    }
}
