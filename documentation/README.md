# Project Backlog Deployment – Autoscaling

This document describes the Kubernetes Horizontal Pod Autoscaler (HPA) configuration that is automatically applied to the `project-backlog` deployment.

## HPA Overview

- **Kind**: `HorizontalPodAutoscaler`
- **Target**: Deployment `project-backlog`
- **Min Replicas**: 2
- **Max Replicas**: 10
- **CPU Utilization Target**: 60 %

The HPA monitors the average CPU usage of all pods in the deployment and adjusts the replica count so that the target utilization stays near 60 %. When the average CPU usage exceeds this threshold, the HPA scales up; when it falls below, it scales down (but never below the minimum replicas).

## How to View the HPA

```bash
kubectl get hpa project-backlog-hpa
```

You should see output similar to:

```
NAME                 DESIRED   CURRENT   MIN   MAX   REFERENCE
project-backlog-hpa   4         2         2     10    deployment/project-backlog
```

`DESIRED` shows the number of pods the HPA wants to run.

## Adjusting the HPA

If you need to change the scaling behavior:

1. Edit the `k8s/hpa.yaml` file (or the Helm values in `k8s/values.yaml`).
2. Update the `minReplicas`, `maxReplicas`, or `targetCPUUtilizationPercentage`.
3. Apply the changes:

```bash
kubectl apply -f k8s/hpa.yaml
```

## Resource Limits

Each pod is configured with the following resource requests and limits:

- **Requests**: 250 m CPU, 512 MiB Memory
- **Limits**: 500 m CPU, 1 GiB Memory

These settings enforce a baseline resource footprint and cap the maximum usage to prevent runaway pods.

## Monitoring Scaling

You can watch the HPA in action with:

```bash
kubectl get hpa project-backlog-hpa -w
```

Or, to see detailed metrics:

```bash
kubectl top pods -l app=project-backlog
```

## Running Load Tests

A sample K6 script (`loadtest/k6_autoscale.js`) is provided to generate sustained CPU load and trigger the HPA. To run it:

```bash
k6 run loadtest/k6_autoscale.js
```

This script targets `https://api.example.com/health` and ramps up to 1000 virtual users over a 4‑minute period.

--- 

*Note*: Ensure the cluster has the metrics server installed (`kube-state-metrics` & `metrics-server`) so that the HPA can read CPU metrics.