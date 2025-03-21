pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                // Récupération du code depuis GitHub (branche nom_prenom_burger)
                checkout([
                    $class: 'GitSCM',
                    branches: [[name: '*/Burger_isi']],
                    extensions: [],
                    userRemoteConfigs: [[
                        url: 'https://github.com/ndeyelikagueye/Burger_isi.git',
                        credentialsId: 'github-credentials'
                    ]]
                ])
            }
        }

        stage('Install Dependencies') {
            steps {
                // Installation des dépendances Laravel
                sh 'composer install --no-interaction --no-progress --prefer-dist --optimize-autoloader'
                sh 'cp .env.example .env'
                sh 'php artisan key:generate'
                sh 'chmod -R 777 storage bootstrap/cache'
            }
        }

        stage('Run Tests') {
            steps {
                // Exécute les tests (si vous en avez)
                sh 'php artisan test'
            }
        }

        stage('Build Docker Image') {
            steps {
                // Construction de l'image Docker
                sh 'docker build -t isi-burger:${BUILD_NUMBER} .'
                sh 'docker tag isi-burger:${BUILD_NUMBER} isi-burger:latest'
            }
        }

        stage('Push Docker Image') {
            steps {
                // Si vous avez un registre Docker (optionnel)
                withCredentials([string(credentialsId: 'docker-password', variable: 'DOCKER_PASSWORD')]) {
                    sh 'echo $DOCKER_PASSWORD | docker login -u ndeyelikagueye --password-stdin'
                    sh 'docker push ndeyelikagueye/isi-burger:${BUILD_NUMBER}'
                    sh 'docker push ndeyelikagueye/isi-burger:latest'
                }
            }
        }

        stage('Deploy') {
            steps {
                // Déploiement (à adapter selon votre environnement)
                sh 'docker-compose down'
                sh 'docker-compose up -d'
            }
        }
    }

    post {
        always {
            // Nettoyage
            sh 'docker image prune -f'
        }
        success {
            echo 'Déploiement réussi!'
        }
        failure {
            echo 'Le déploiement a échoué!'
        }
    }
}
