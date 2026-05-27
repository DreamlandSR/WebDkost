pipeline {
    agent any

    environment {
        IMAGE_NAME = "ryanzputra/webdkost"
        IMAGE_TAG = "latest"
        KUBE_NAMESPACE = "webdkost"
        DEPLOYMENT_NAME = "webdkost-app"
        SCHEDULER_NAME = "webdkost-scheduler"
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
                sh '''
                kubectl apply -f k8s/namespace.yaml

                kubectl apply -f k8s/storage-pvc.yaml

                kubectl apply -f k8s/deployment.yaml
                kubectl apply -f k8s/service.yaml

                kubectl apply -f k8s/scheduler.yaml

                kubectl apply -f k8s/letsencrypt-prod.yaml
                kubectl apply -f k8s/ingress.yaml

                kubectl apply -f k8s/hpa.yaml
                '''
            }
        }

        stage('Restart Deployment') {
            steps {
                sh '''
                kubectl rollout restart deployment/$DEPLOYMENT_NAME -n $KUBE_NAMESPACE
                kubectl rollout restart deployment/$SCHEDULER_NAME -n $KUBE_NAMESPACE

                kubectl rollout status deployment/$DEPLOYMENT_NAME -n $KUBE_NAMESPACE
                kubectl rollout status deployment/$SCHEDULER_NAME -n $KUBE_NAMESPACE
                '''
            }
        }

        stage('Run Migration') {
            steps {
                sh '''
                kubectl exec -n $KUBE_NAMESPACE deployment/$DEPLOYMENT_NAME -- php artisan migrate --force
                kubectl exec -n $KUBE_NAMESPACE deployment/$DEPLOYMENT_NAME -- php artisan config:clear
                kubectl exec -n $KUBE_NAMESPACE deployment/$DEPLOYMENT_NAME -- php artisan cache:clear
                kubectl exec -n $KUBE_NAMESPACE deployment/$DEPLOYMENT_NAME -- php artisan route:clear
                kubectl exec -n $KUBE_NAMESPACE deployment/$DEPLOYMENT_NAME -- php artisan view:clear
                '''
            }
        }

        stage('Verify Deployment') {
            steps {
                sh '''
                kubectl get pods -n $KUBE_NAMESPACE
                kubectl get svc -n $KUBE_NAMESPACE
                kubectl get ingress -n $KUBE_NAMESPACE
                kubectl get hpa -n $KUBE_NAMESPACE
                '''
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