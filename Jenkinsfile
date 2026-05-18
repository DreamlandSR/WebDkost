pipeline {
    agent any

    environment {
        IMAGE_NAME = "ryanzputra/webdkost"
        IMAGE_TAG = "latest"
        KUBE_NAMESPACE = "webdkost"
        DEPLOYMENT_NAME = "webdkost-app"
        KUBECONFIG = "/var/lib/jenkins/.kube/config"
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t $IMAGE_NAME:$IMAGE_TAG .'
            }
        }

        stage('Login Docker Hub') {
            steps {
                withCredentials([usernamePassword(
                    credentialsId: 'dockerhub-credentials',
                    usernameVariable: 'DOCKER_USERNAME',
                    passwordVariable: 'DOCKER_PASSWORD'
                )]) {
                    sh 'echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin'
                }
            }
        }

        stage('Push Docker Image') {
            steps {
                sh 'docker push $IMAGE_NAME:$IMAGE_TAG'
            }
        }

        stage('Deploy to Kubernetes') {
            steps {
                sh 'kubectl apply -f k8s/namespace.yaml'
                sh 'kubectl apply -f k8s/mysql.yaml'
                sh 'kubectl apply -f k8s/deployment.yaml'
                sh 'kubectl apply -f k8s/service.yaml'

                sh 'kubectl rollout restart deployment/$DEPLOYMENT_NAME -n $KUBE_NAMESPACE'
                sh 'kubectl rollout status deployment/$DEPLOYMENT_NAME -n $KUBE_NAMESPACE'
            }
        }

        stage('Verify Deployment') {
            steps {
                sh 'kubectl get pods -n $KUBE_NAMESPACE'
                sh 'kubectl get svc -n $KUBE_NAMESPACE'
            }
        }
    }

    post {
        success {
            echo 'Pipeline berhasil: image berhasil di-build, push, dan deploy ke Kubernetes.'
        }

        failure {
            echo 'Pipeline gagal. Cek Console Output Jenkins untuk detail error.'
        }
    }
}