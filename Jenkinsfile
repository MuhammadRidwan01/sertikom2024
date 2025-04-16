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
            // Perbarui semua status menjadi success saat build berhasil
            script {
                // Status utama build dan deploy
                sh """
                curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                -d '{
                    "state": "success",
                    "target_url": "'${BUILD_URL}'",
                    "description": "✅ CI/CD berhasil! Build ${BUILD_VERSION} berhasil di-deploy ke server ${BUILD_ENV} pada ${BUILD_TIME}",
                    "context": "Jenkins/build-and-deploy"
                }'
            """
                // Perbarui status lainnya menjadi success juga
                sh """
                curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                -d '{
                    "state": "success",
                    "target_url": "'${BUILD_URL}'",
                    "description": "✅ Instalasi dependensi PHP berhasil",
                    "context": "Jenkins/dependencies"
                }'
            """
                sh """
                curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                -d '{
                    "state": "success",
                    "target_url": "'${BUILD_URL}'",
                    "description": "✅ Build frontend berhasil",
                    "context": "Jenkins/frontend"
                }'
            """
                sh """
                curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                -d '{
                    "state": "success",
                    "target_url": "'${BUILD_URL}'",
                    "description": "✅ Koneksi database berhasil terverifikasi",
                    "context": "Jenkins/database"
                }'
            """
                sh """
                curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                -d '{
                    "state": "success",
                    "target_url": "'${BUILD_URL}'",
                    "description": "✅ Deployment ke server ${BUILD_ENV} berhasil",
                    "context": "Jenkins/deployment"
                }'
            """
            }
        }
        failure {
            echo 'Deployment failed!'
            // Mengirim status "failure" untuk semua konteks
            script {
                def failureStage = currentBuild.result ? "unknown": env.STAGE_NAME
                // Status utama build dan deploy
                sh """
                curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                -d '{
                    "state": "failure",
                    "target_url": "'${BUILD_URL}'",
                    "description": "❌ Build gagal pada tahap: ${failureStage}. Lihat log Jenkins untuk detail error.",
                    "context": "Jenkins/build-and-deploy"
                }'
            """
                // Perbarui semua status konteks lainnya menjadi failure juga
                sh """
                curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                -d '{
                    "state": "failure",
                    "target_url": "'${BUILD_URL}'",
                    "description": "❌ Build gagal pada tahap: ${failureStage}.",
                    "context": "Jenkins/dependencies"
                }'
            """
                sh """
                curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                -d '{
                    "state": "failure",
                    "target_url": "'${BUILD_URL}'",
                    "description": "❌ Build gagal pada tahap: ${failureStage}.",
                    "context": "Jenkins/frontend"
                }'
            """
                sh """
                curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                -d '{
                    "state": "failure",
                    "target_url": "'${BUILD_URL}'",
                    "description": "❌ Build gagal pada tahap: ${failureStage}.",
                    "context": "Jenkins/database"
                }'
            """
                sh """
                curl -XPOST -H "Authorization: token ${GITHUB_TOKEN}" \
                https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/\$(git rev-parse HEAD) \
                -d '{
                    "state": "failure",
                    "target_url": "'${BUILD_URL}'",
                    "description": "❌ Build gagal pada tahap: ${failureStage}.",
                    "context": "Jenkins/deployment"
                }'
            """
            }
        }
        always {
            // Tambahkan cleanup atau notifikasi lain yang selalu dijalankan
            emailext (
                subject: "Build ${currentBuild.currentResult}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'",
                body: """<p>Build ${currentBuild.currentResult}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]':</p>
                <p>Check console output at <a href='${env.BUILD_URL}'>${env.JOB_NAME} [${env.BUILD_NUMBER}]</a></p>""",
                recipientProviders: [[$class: 'DevelopersRecipientProvider']]
            )
        }
    }
}
