pipeline {
    agent any

    environment {
        COMPOSER_HOME = '/root/.composer'
    }

    stages {
        stage('Checkout SCM') {
            steps {
                echo 'Checking out the repository...'
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                script {
                    echo 'Installing PHP and necessary extensions...'
                    sh '''
                    apt-get update
                    apt-get install -y php php-cli php-mbstring php-xml php-curl unzip
                    '''

                    echo 'Installing Composer...'
                    sh '''
                    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
                    composer --version
                    '''

                    echo 'Installing Symfony dependencies...'
                    sh 'composer install --no-interaction --prefer-dist'
                }
            }
        }

        stage('Install Netlify CLI') {
            steps {
                script {
                    echo 'Installing Node.js and Netlify CLI...'
                    sh '''
                    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
                    apt-get install -y nodejs
                    npm install -g netlify-cli
                    '''

                    echo 'Verifying Netlify CLI installation...'
                    sh 'netlify --version'
                }
            }
        }

        stage('Build Symfony Backend') {
            steps {
                script {
                    echo 'Building Symfony Backend...'
                    sh 'php bin/console cache:clear --env=prod'
                    sh 'php bin/console assets:install --env=prod'
                }
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    echo 'Running tests...'
                    sh 'php bin/phpunit'
                }
            }
        }

        stage('Deploy to Netlify') {
            steps {
                script {
                    echo 'Deploying to Netlify...'
                    sh '''
                    # Netlify deployment (update with your Netlify site ID and authentication token)
                    netlify deploy --prod --dir=public --auth=$NETLIFY_AUTH_TOKEN --site=$NETLIFY_SITE_ID
                    '''
                }
            }
        }

        stage('Deploy Backend') {
            steps {
                script {
                    echo 'Deploying Symfony Backend...'
                    sh '''
                    # Example deployment command (update with your server details)
                    rsync -avz --exclude="var/cache/*" ./ user@yourserver:/var/www/symfony_app
                    ssh user@yourserver "cd /var/www/symfony_app && php bin/console cache:clear --env=prod"
                    '''
                }
            }
        }
    }

    post {
        success {
            echo 'Build and deployment completed successfully!'
        }
        failure {
            echo 'Build or deployment failed.'
        }
    }
}
