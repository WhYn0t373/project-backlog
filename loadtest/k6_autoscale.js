import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
  stages: [
    { duration: '1m', target: 1000 },
    { duration: '2m', target: 1000 },
    { duration: '1m', target: 0 },
  ],
  thresholds: {
    http_req_duration: ['p(95)<500'],
  },
};

export default function () {
  const res = http.get('https://api.example.com/health');
  check(res, {
    'status was 200': (r) => r.status === 200,
  });
  sleep(1);
}