import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
  thresholds: {
    http_req_duration: ['avg<200'],            // Average latency must be below 200 ms
    http_req_failed: ['rate<0.01'],           // Error rate must be below 1 %
  },
  scenarios: {
    default: {
      executor: 'ramping-arrival-rate',
      startRate: 10,
      timeUnit: '1s',
      preAllocatedVUs: 50,
      maxVUs: 100,
      stages: [
        { target: 50, duration: '1m' },   // ramp up to 50 requests per second
        { target: 50, duration: '2m' },   // hold at 50 requests per second
        { target: 10, duration: '30s' },   // ramp down to 10 requests per second
      ],
    },
  },
};

export default function () {
  const baseUrl = __ENV.API_URL || 'http://localhost:8000';
  const url = `${baseUrl}/api/health`;
  const authHeader = __ENV.API_TOKEN ? { Authorization: `Bearer ${__ENV.API_TOKEN}` } : {};

  const res = http.get(url, { headers: authHeader });

  check(res, {
    'status is 200': (r) => r.status === 200,
  });

  sleep(1);
}