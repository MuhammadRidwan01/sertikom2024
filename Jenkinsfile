pipeline {
    agent any
    
    stages {
        stage('Checkout') {
            steps {
                checkout scm
                // Menggunakan updateGitHubCommitStatus sebagai alternatif
                updateGitHubCommitStatus name: 'checkout', state: 'SUCCESS'
            }
        }
        
        stage('Install Dependencies') {
            steps {
                updateGitHubCommitStatus name: 'dependencies', state: 'PENDING'
                sh 'composer install --no-interaction --no-progress --no-suggest'
                updateGitHubCommitStatus name: 'dependencies', state: 'SUCCESS'
            }
            post {
                failure {
                    updateGitHubCommitStatus name: 'dependencies', state: 'FAILURE'
                }
            }
        }
        
        stage('Build Frontend Assets') {
            steps {
                updateGitHubCommitStatus name: 'frontend-build', state: 'PENDING'
                sh 'npm install'
                sh 'npm run build'
                updateGitHubCommitStatus name: 'frontend-build', state: 'SUCCESS'
            }
            post {
                failure {
                    updateGitHubCommitStatus name: 'frontend-build', state: 'FAILURE'
                }
            }
        }
        
        stage('Setup Environment') {
            steps {
                updateGitHubCommitStatus name: 'environment-setup', state: 'PENDING'
                sh 'cp .env.example .env'
                sh 'php artisan key:generate'
                sh 'php artisan migrate'
                updateGitHubCommitStatus name: 'environment-setup', state: 'SUCCESS'
            }
            post {
                failure {
                    updateGitHubCommitStatus name: 'environment-setup', state: 'FAILURE'
                }
            }
        }
        
        stage('Check Database') {
            steps {
                updateGitHubCommitStatus name: 'database-check', state: 'PENDING'
                sh 'php artisan tinker --execute="DB::connection()->getPdo(); echo \'✅ DB OK\n\';"'
                updateGitHubCommitStatus name: 'database-check', state: 'SUCCESS'
            }
            post {
                failure {
                    updateGitHubCommitStatus name: 'database-check', state: 'FAILURE'
                }
            }
        }
        
        stage('Serve & Check') {
            steps {
                updateGitHubCommitStatus name: 'serve-check', state: 'PENDING'
                sh 'php artisan serve &'
                sh 'sleep 5'
                sh 'curl --fail --silent http://127.0.0.1:8000'
                updateGitHubCommitStatus name: 'serve-check', state: 'SUCCESS'
            }
            post {
                failure {
                    updateGitHubCommitStatus name: 'serve-check', state: 'FAILURE'
                }
            }
        }
        
        stage('Deploy') {
            steps {
                updateGitHubCommitStatus name: 'deployment', state: 'PENDING'
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
                updateGitHubCommitStatus name: 'deployment', state: 'SUCCESS'
            }
            post {
                failure {
                    updateGitHubCommitStatus name: 'deployment', state: 'FAILURE'
                }
            }
        }
    }
    
    post {
        success {
            echo 'Deployment successful!'
            updateGitHubCommitStatus name: 'build', state: 'SUCCESS'
        }
        failure {
            echo 'Deployment failed!'
            updateGitHubCommitStatus name: 'build', state: 'FAILURE'
        }
    }
}
