pipeline {
    agent any

    environment {
        COMPOSER_HOME = '/root/.composer'
        NETLIFY_AUTH_TOKEN = 'nfp_f3EV6r8ii3VM1GPrQaD5rXH688gzvMAN3492' // Your Netlify token
        NETLIFY_SITE_ID = credentials('netlify-site-id') // Still using Jenkins credentials for site ID
    }

    stages {
        stage('Checkout SCM') {
            steps {
                echo 'Checking out the repository...'
                checkout scm
            }
        }

        stage('Prepare Environment') {
            steps {
                echo 'Copying Jenkins-specific .env file...'
                sh 'cp .env.jenkins .env'
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
                    sh '''
                    if [ -f .env ]; then
                        php bin/console cache:clear --env=prod
                        php bin/console assets:install --env=prod
                    else
                        echo ".env file not found. Skipping cache clear."
                    fi
                    '''
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
