pipeline {
    agent any
    environment {
        // Simpan token GitHub Anda sebagai credential di Jenkins
        GITHUB_TOKEN = credentials('25ae89e1-778f-45b2-b3e5-51b6c43f2bf2')
        GITHUB_ORG = "MuhammadRidwan01"
        GITHUB_REPO = "sertikom2024"
        // Tambahkan variabel untuk menampung informasi build
        BUILD_VERSION = sh(script: 'git describe --tags --always || echo "v0.0.1"', returnStdout: true).trim()
        BUILD_TIME = sh(script: 'date "+%Y-%m-%d %H:%M:%S"', returnStdout: true).trim()
        BUILD_ENV = "production" // atau staging, development, sesuai kebutuhan
    }
    stages {
        stage('Checkout') {
            steps {
                checkout scm
                script {
                    // Mengirim status "pending" ke GitHub di awal build dengan deskripsi lebih informatif
                    sh """
                        curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                        https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                        -d '{
                            "state": "pending",
                            "target_url": "'${BUILD_URL}'",
                            "description": "Jenkins CI/CD dimulai pada ${BUILD_TIME} untuk versi ${BUILD_VERSION} [${BUILD_ENV}]",
                            "context": "Jenkins/build-and-deploy"
                        }'
                    """
                }
            }
        }
        // Stage lainnya tetap sama...
        stage('Install Dependencies') {
            steps {
                sh 'composer install --no-interaction --no-progress --no-suggest'
                script {
                    sh """
                        curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                        https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                        -d '{
                            "state": "pending",
                            "target_url": "'${BUILD_URL}'",
                            "description": "Instalasi dependensi PHP selesai, melanjutkan ke tahap build frontend",
                            "context": "Jenkins/dependencies"
                        }'
                    """
                }
            }
        }
        stage('Build Frontend Assets') {
            steps {
                sh 'npm install'
                sh 'npm run build'
                script {
                    sh """
                        curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                        https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                        -d '{
                            "state": "pending",
                            "target_url": "'${BUILD_URL}'",
                            "description": "Build frontend berhasil, mempersiapkan environment",
                            "context": "Jenkins/frontend"
                        }'
                    """
                }
            }
        }
        // Stages lainnya tetap sama...
        stage('Setup Environment') {
            steps {
                sh 'cp .env.example .env'
                sh 'php artisan key:generate'
                sh 'php artisan migrate'
            }
        }
        stage('Check Database') {
            steps {
                sh 'php artisan tinker --execute="DB::connection()->getPdo(); echo \'DB OK\n\';"'
                script {
                    sh """
                        curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                        https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                        -d '{
                            "state": "pending",
                            "target_url": "'${BUILD_URL}'",
                            "description": "Koneksi database berhasil diverifikasi",
                            "context": "Jenkins/database"
                        }'
                    """
                }
            }
        }
        stage('Serve & Check') {
            steps {
                sh 'php artisan serve &'
                sh 'sleep 5'
                sh 'curl --fail --silent http://127.0.0.1:8000'
            }
        }
        stage('Deploy') {
            steps {
                script {
                    sh """
                        curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                        https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                        -d '{
                            "state": "pending",
                            "target_url": "'${BUILD_URL}'",
                            "description": "Proses deployment ke server ${BUILD_ENV} dimulai...",
                            "context": "Jenkins/deployment"
                        }'
                    """
                }
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
        }
    }
    post {
        success {
            echo 'Deployment successful!'
            script {
                def SHA = sh(script: "git rev-parse HEAD", returnStdout: true).trim()
                // Create GitHub deployment
                def DEPLOYMENT_ID = sh(
                    script: """
                    curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                    -H "Accept: application/vnd.github.v3+json" \
                    https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/deployments \
                    -d '{
                        "ref": "${SHA}",
                        "environment": "${BUILD_ENV}",
                        "description": "Deploying build ${BUILD_VERSION}",
                        "auto_merge": false,
                        "required_contexts": []
                    }' | jq -r '.id'
                """,
                    returnStdout: true
                ).trim()
                // Update deployment status to success
                sh """
                curl -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                -H "Accept: application/vnd.github.v3+json" \
                https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/deployments/${DEPLOYMENT_ID}/statuses \
                -d '{
                    "state": "success",
                    "log_url": "${BUILD_URL}",
                    "description": "✅ Build ${BUILD_VERSION} berhasil dideploy ke ${BUILD_ENV}",
                    "environment": "${BUILD_ENV}",
                    "environment_url": "https://your-deployment-url.com",
                    "auto_inactive": true
                }'
            """
                // Kirim context status ke commit
                sh """
                curl -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${SHA} \
                -d '{
                    "state": "success",
                    "target_url": "${BUILD_URL}",
                    "description": "✅ Deployment sukses di ${BUILD_ENV}",
                    "context": "deployment/${BUILD_ENV}"
                }'
            """
            }
        }
        failure {
            echo 'Deployment failed!'
            script {
                def SHA = sh(script: "git rev-parse HEAD", returnStdout: true).trim()
                def failureStage = currentBuild.result ?: env.STAGE_NAME
                // Update status gagal
                sh """
                curl -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${SHA} \
                -d '{
                    "state": "failure",
                    "target_url": "${BUILD_URL}",
                    "description": "❌ Gagal pada tahap: ${failureStage}",
                    "context": "deployment/${BUILD_ENV}"
                }'
            """
            }
        }
        always {
            emailext (
                subject: "Build ${currentBuild.currentResult}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'",
                body: """<p>Build ${currentBuild.currentResult}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'</p>
<p>Check console output at <a href='${env.BUILD_URL}'>${env.BUILD_URL}</a></p>""",
                recipientProviders: [[$class: 'DevelopersRecipientProvider']]
            )
        }
    }
}
