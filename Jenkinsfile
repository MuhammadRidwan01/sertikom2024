pipeline {
    agent any
    environment {
        GITHUB_TOKEN = credentials('25ae89e1-778f-45b2-b3e5-51b6c43f2bf2')
        GITHUB_ORG = "MuhammadRidwan01"
        GITHUB_REPO = "sertikom2024"
        BUILD_VERSION = sh(script: 'git describe --tags --always || echo "v0.0.1"', returnStdout: true).trim()
        BUILD_TIME = sh(script: 'date "+%Y-%m-%d %H:%M:%S"', returnStdout: true).trim()
        BUILD_ENV = "production"
    }
    stages {
        stage('Checkout') {
            steps {
                checkout scm
                script {
                    def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                    sh """
                        curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                        https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                        -d '{
                            "state": "pending",
                            "target_url": "${BUILD_URL}",
                            "description": "Jenkins CI/CD dimulai pada ${BUILD_TIME} untuk versi ${BUILD_VERSION} [${BUILD_ENV}]",
                            "context": "Jenkins/build-and-deploy"
                        }'
                    """
                }
            }
            post {
                success {
                    script {
                        def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                        sh """
                            curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                            https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                            -d '{
                                "state": "success",
                                "target_url": "${BUILD_URL}",
                                "description": "Checkout berhasil",
                                "context": "Jenkins/build-and-deploy"
                            }'
                        """
                    }
                }
                failure {
                    script {
                        def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                        sh """
                            curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                            https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                            -d '{
                                "state": "failure",
                                "target_url": "${BUILD_URL}",
                                "description": "Checkout gagal",
                                "context": "Jenkins/build-and-deploy"
                            }'
                        """
                    }
                }
            }
        }
        stage('Install Dependencies') {
            steps {
                sh 'composer install --no-interaction --no-progress --no-suggest'
                script {
                    def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                    sh """
                        curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                        https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                        -d '{
                            "state": "pending",
                            "target_url": "${BUILD_URL}",
                            "description": "Instalasi dependensi PHP selesai, melanjutkan ke tahap build frontend",
                            "context": "Jenkins/dependencies"
                        }'
                    """
                }
            }
            post {
                success {
                    script {
                        def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                        sh """
                            curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                            https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                            -d '{
                                "state": "success",
                                "target_url": "${BUILD_URL}",
                                "description": "Instalasi dependensi PHP berhasil",
                                "context": "Jenkins/dependencies"
                            }'
                        """
                    }
                }
                failure {
                    script {
                        def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                        sh """
                            curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                            https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                            -d '{
                                "state": "failure",
                                "target_url": "${BUILD_URL}",
                                "description": "Instalasi dependensi PHP gagal",
                                "context": "Jenkins/dependencies"
                            }'
                        """
                    }
                }
            }
        }
        stage('Build Frontend Assets') {
            steps {
                sh 'npm install'
                sh 'npm run build'
                script {
                    def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                    sh """
                        curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                        https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                        -d '{
                            "state": "pending",
                            "target_url": "${BUILD_URL}",
                            "description": "Build frontend berhasil, mempersiapkan environment",
                            "context": "Jenkins/frontend"
                        }'
                    """
                }
            }
            post {
                success {
                    script {
                        def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                        sh """
                            curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                            https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                            -d '{
                                "state": "success",
                                "target_url": "${BUILD_URL}",
                                "description": "Build frontend berhasil",
                                "context": "Jenkins/frontend"
                            }'
                        """
                    }
                }
                failure {
                    script {
                        def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                        sh """
                            curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                            https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                            -d '{
                                "state": "failure",
                                "target_url": "${BUILD_URL}",
                                "description": "Build frontend gagal",
                                "context": "Jenkins/frontend"
                            }'
                        """
                    }
                }
            }
        }
        stage('Setup Environment') {
            steps {
                sh 'cp .env.example .env'
                sh 'php artisan key:generate'
                sh 'php artisan migrate'
            }
            post {
                success {
                    // Tidak perlu mengirim status success karena ini bagian dari build-and-deploy
                }
                failure {
                    script {
                        def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                        sh """
                            curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                            https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                            -d '{
                                "state": "failure",
                                "target_url": "${BUILD_URL}",
                                "description": "Setup environment gagal",
                                "context": "Jenkins/build-and-deploy"
                            }'
                        """
                    }
                }
            }
        }
        stage('Check Database') {
            steps {
                sh 'php artisan tinker --execute="DB::connection()->getPdo(); echo \'DB OK\n\';"'
                script {
                    def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                    sh """
                        curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                        https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                        -d '{
                            "state": "pending",
                            "target_url": "${BUILD_URL}",
                            "description": "Koneksi database berhasil diverifikasi",
                            "context": "Jenkins/database"
                        }'
                    """
                }
            }
            post {
                success {
                    script {
                        def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                        sh """
                            curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                            https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                            -d '{
                                "state": "success",
                                "target_url": "${BUILD_URL}",
                                "description": "Koneksi database berhasil diverifikasi",
                                "context": "Jenkins/database"
                            }'
                        """
                    }
                }
                failure {
                    script {
                        def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                        sh """
                            curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                            https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                            -d '{
                                "state": "failure",
                                "target_url": "${BUILD_URL}",
                                "description": "Verifikasi koneksi database gagal",
                                "context": "Jenkins/database"
                            }'
                        """
                    }
                }
            }
        }
        stage('Serve & Check') {
            steps {
                sh 'php artisan serve &'
                sh 'sleep 5'
                sh 'curl --fail --silent http://127.0.0.1:8000'
            }
            post {
                success {
                    // Tidak perlu mengirim status success secara terpisah, bagian dari build-and-deploy
                }
                failure {
                    script {
                        def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                        sh """
                            curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                            https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                            -d '{
                                "state": "failure",
                                "target_url": "${BUILD_URL}",
                                "description": "Pengujian server gagal",
                                "context": "Jenkins/build-and-deploy"
                            }'
                        """
                    }
                }
            }
        }
        stage('Deploy') {
            steps {
                script {
                    def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                    sh """
                        curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                        https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                        -d '{
                            "state": "pending",
                            "target_url": "${BUILD_URL}",
                            "description": "Proses deployment ke server ${BUILD_ENV} dimulai...",
                            "context": "Jenkins/deployment"
                        }'
                    """
                }
                sshagent(['jenkins-ssh']) {
                    sh '''
                        DEPLOY_PATH="/var/www/laravel-tmp"
                        ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "mkdir -p $DEPLOY_PATH"
                        rsync -avz --exclude=".git" --exclude="node_modules" --exclude="tests" ./ www-data@192.168.1.101:$DEPLOY_PATH/
                        ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "cd $DEPLOY_PATH && composer install --no-dev --optimize-autoloader"
                        ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "[[ -f $DEPLOY_PATH/database/database.sqlite ]] || mkdir -p $DEPLOY_PATH/database && touch $DEPLOY_PATH/database/database.sqlite"
                        ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "mv /var/www/laravel /var/www/laravel-old || true"
                        ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "mv $DEPLOY_PATH /var/www/laravel"
                        ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "rm -rf /var/www/laravel-old || true"
                        ssh -o StrictHostKeyChecking=no www-data@192.168.1.101 "chmod -R 775 /var/www/laravel/storage"
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
                    script {
                        def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                        sh """
                            curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                            https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                            -d '{
                                "state": "success",
                                "target_url": "${BUILD_URL}",
                                "description": "Deployment ke server ${BUILD_ENV} berhasil",
                                "context": "Jenkins/deployment"
                            }'
                        """
                    }
                }
                failure {
                    script {
                        def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                        sh """
                            curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                            https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/statuses/${sha} \
                            -d '{
                                "state": "failure",
                                "target_url": "${BUILD_URL}",
                                "description": "Deployment ke server ${BUILD_ENV} gagal",
                                "context": "Jenkins/deployment"
                            }'
                        """
                    }
                }
            }
        }
    }
    post {
        success {
            script {
                def sha = sh(script: 'git rev-parse HEAD', returnStdout: true).trim()
                def deployId = sh(
                    script: """
                        curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                        -H "Accept: application/vnd.github.v3+json" \
                        https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/deployments \
                        -d '{
                            "ref": "${sha}",
                            "environment": "${BUILD_ENV}",
                            "description": "Deploying build ${BUILD_VERSION}",
                            "auto_merge": false,
                            "required_contexts": []
                        }' | jq -r '.id'
                    """,
                    returnStdout: true
                ).trim()
                sh """
                    curl -s -X POST -H "Authorization: token ${GITHUB_TOKEN}" \
                    -H "Accept: application/vnd.github.v3+json" \
                    https://api.github.com/repos/${GITHUB_ORG}/${GITHUB_REPO}/deployments/${deployId}/statuses \
                    -d '{
                        "state": "success",
                        "log_url": "${BUILD_URL}",
                        "description": "Deployment berhasil untuk versi ${BUILD_VERSION}",
                        "environment": "${BUILD_ENV}",
                        "environment_url": "https://cicd.ridwan-porto.my.id",
                        "auto_inactive": true
                    }'
                """
            }
        }
        failure {
            echo 'Build failed!'
        }
    }
}
