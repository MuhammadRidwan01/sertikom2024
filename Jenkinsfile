pipeline {
    agent any
    
    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }
        
        stage('Install Dependencies') {
            steps {
                sh 'composer install --no-interaction --no-progress --no-suggest'
            }
        }
        
        stage('Build Frontend Assets') {
            steps {
                sh 'npm install'
                sh 'npm run build'
            }
        }
        
        stage('Setup Environment') {
            steps {
                sh 'cp .env.example .env'
                sh 'php artisan key:generate'
            }
        }
        
        stage('Run Tests') {
            steps {
                sh 'php artisan test'
            }
        }
        
        stage('Deploy') {
            steps {
                sshagent(['jenkins-ssh']) {
                    sh '''
                        ssh -o StrictHostKeyChecking=no www-data@web.local "mkdir -p /var/www/laravel-tmp"
                        rsync -avz --exclude ".git" --exclude "node_modules" --exclude "tests" ./ www-data@web.local:/var/www/laravel-tmp/
                        ssh -o StrictHostKeyChecking=no www-data@web.local "cd /var/www/laravel-tmp && composer install --no-dev --optimize-autoloader"
                        ssh -o StrictHostKeyChecking=no www-data@web.local "cd /var/www/laravel-tmp && php artisan migrate --force"
                        ssh -o StrictHostKeyChecking=no www-data@web.local "cd /var/www/laravel-tmp && php artisan config:cache"
                        ssh -o StrictHostKeyChecking=no www-data@web.local "cd /var/www/laravel-tmp && php artisan route:cache"
                        ssh -o StrictHostKeyChecking=no www-data@web.local "cd /var/www/laravel-tmp && php artisan view:cache"
                        ssh -o StrictHostKeyChecking=no www-data@web.local "mv /var/www/laravel /var/www/laravel-old || true"
                        ssh -o StrictHostKeyChecking=no www-data@web.local "mv /var/www/laravel-tmp /var/www/laravel"
                        ssh -o StrictHostKeyChecking=no www-data@web.local "rm -rf /var/www/laravel-old || true"
                        ssh -o StrictHostKeyChecking=no www-data@web.local "chmod -R 775 /var/www/laravel/storage"
                    '''
                }
            }
        }
    }
    
    post {
        success {
            echo 'Deployment successful!'
        }
        failure {
            echo 'Deployment failed!'
        }
    }
}
