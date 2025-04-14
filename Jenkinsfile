pipeline {
    agent any
    
    stages {
        stage('Checkout') {
            steps {
                checkout scm
                githubNotify context: 'continuous-integration/jenkins/checkout', 
                           description: 'Checkout successful', 
                           status: 'SUCCESS'
            }
        }
        
        stage('Install Dependencies') {
            steps {
                githubNotify context: 'continuous-integration/jenkins/dependencies', 
                           description: 'Installing dependencies', 
                           status: 'PENDING'
                sh 'composer install --no-interaction --no-progress --no-suggest'
            }
            post {
                success {
                    githubNotify context: 'continuous-integration/jenkins/dependencies', 
                              description: 'Dependencies installed', 
                              status: 'SUCCESS'
                }
                failure {
                    githubNotify context: 'continuous-integration/jenkins/dependencies', 
                              description: 'Failed to install dependencies', 
                              status: 'FAILURE'
                }
            }
        }
        
        stage('Build Frontend Assets') {
            steps {
                githubNotify context: 'continuous-integration/jenkins/frontend-build', 
                           description: 'Building frontend assets', 
                           status: 'PENDING'
                sh 'npm install'
                sh 'npm run build'
            }
            post {
                success {
                    githubNotify context: 'continuous-integration/jenkins/frontend-build', 
                              description: 'Frontend assets built', 
                              status: 'SUCCESS'
                }
                failure {
                    githubNotify context: 'continuous-integration/jenkins/frontend-build', 
                              description: 'Frontend build failed', 
                              status: 'FAILURE'
                }
            }
        }
        
        stage('Setup Environment') {
            steps {
                githubNotify context: 'continuous-integration/jenkins/environment-setup', 
                           description: 'Setting up environment', 
                           status: 'PENDING'
                sh 'cp .env.example .env'
                sh 'php artisan key:generate'
                sh 'php artisan migrate'
            }
            post {
                success {
                    githubNotify context: 'continuous-integration/jenkins/environment-setup', 
                              description: 'Environment setup complete', 
                              status: 'SUCCESS'
                }
                failure {
                    githubNotify context: 'continuous-integration/jenkins/environment-setup', 
                              description: 'Environment setup failed', 
                              status: 'FAILURE'
                }
            }
        }
        
        stage('Check Database') {
            steps {
                githubNotify context: 'continuous-integration/jenkins/database-check', 
                           description: 'Checking database connection', 
                           status: 'PENDING'
                sh 'php artisan tinker --execute="DB::connection()->getPdo(); echo \'✅ DB OK\n\';"'
            }
            post {
                success {
                    githubNotify context: 'continuous-integration/jenkins/database-check', 
                              description: 'Database connection OK', 
                              status: 'SUCCESS'
                }
                failure {
                    githubNotify context: 'continuous-integration/jenkins/database-check', 
                              description: 'Database connection failed', 
                              status: 'FAILURE'
                }
            }
        }
        
        stage('Serve & Check') {
            steps {
                githubNotify context: 'continuous-integration/jenkins/serve-check', 
                           description: 'Testing application server', 
                           status: 'PENDING'
                sh 'php artisan serve &'
                sh 'sleep 5'
                sh 'curl --fail --silent http://127.0.0.1:8000'
            }
            post {
                success {
                    githubNotify context: 'continuous-integration/jenkins/serve-check', 
                              description: 'Application server test passed', 
                              status: 'SUCCESS'
                }
                failure {
                    githubNotify context: 'continuous-integration/jenkins/serve-check', 
                              description: 'Application server test failed', 
                              status: 'FAILURE'
                }
            }
        }
        
        stage('Deploy') {
            steps {
                githubNotify context: 'continuous-integration/jenkins/deployment', 
                           description: 'Deployment in progress', 
                           status: 'PENDING'
                sshagent(['jenkins-ssh']) {
                    sh '''
# Set project path di satu tempat
DEPLOY_PATH="/var/www/laravel-tmp"
# Buat folder project
ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "mkdir -p $DEPLOY_PATH"
# Upload project
rsync -avz --exclude ".git" --exclude "node_modules" --exclude "tests" ./ www-data@192.168.1.101:$DEPLOY_PATH/
# Install dependensi Laravel
ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "cd $DEPLOY_PATH && composer install --no-dev --optimize-autoloader"
# Siapkan SQLite jika pakai sqlite
ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "[[ -f $DEPLOY_PATH/database/database.sqlite ]] || mkdir -p $DEPLOY_PATH/database && touch $DEPLOY_PATH/database/database.sqlite"
# Deploy ke live path setelah semuanya siap
ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "mv /var/www/laravel /var/www/laravel-old || true"
ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "mv $DEPLOY_PATH /var/www/laravel"
ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "rm -rf /var/www/laravel-old || true"
ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "chmod -R 775 /var/www/laravel/storage"
# Sekarang jalankan perintah Laravel setelah pindah ke live path
ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "cd /var/www/laravel && php artisan optimize:clear"
ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "cd /var/www/laravel && php artisan optimize"
ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "cd /var/www/laravel && php artisan migrate --force"
ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "cd /var/www/laravel && php artisan config:cache"
ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "cd /var/www/laravel && php artisan route:cache"
ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "cd /var/www/laravel && php artisan view:cache"
'''
                }
            }
            post {
                success {
                    githubNotify context: 'continuous-integration/jenkins/deployment', 
                              description: 'Deployment successful', 
                              status: 'SUCCESS'
                }
                failure {
                    githubNotify context: 'continuous-integration/jenkins/deployment', 
                              description: 'Deployment failed', 
                              status: 'FAILURE'
                }
            }
        }
    }
    
    post {
        success {
            echo 'Deployment successful!'
            githubNotify context: 'continuous-integration/jenkins/build', 
                       description: 'Build and deployment completed successfully', 
                       status: 'SUCCESS'
        }
        failure {
            echo 'Deployment failed!'
            githubNotify context: 'continuous-integration/jenkins/build', 
                       description: 'Build or deployment failed', 
                       status: 'FAILURE'
        }
    }
}
