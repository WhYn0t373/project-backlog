<?php
/**
 * Loki Monolog Handler
 *
 * Sends logs to Loki via the HTTP Push API.
 *
 * @package App\Logging
 */

namespace App\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class LokiHandler extends AbstractProcessingHandler
{
    /**
     * Guzzle HTTP client.
     *
     * @var Client
     */
    private Client $httpClient;

    /**
     * Loki endpoint URL.
     *
     * @var string
     */
    private string $endpoint;

    /**
     * Loki labels (e.g., job name).
     *
     * @var string
     */
    private string $labels;

    /**
     * LokiHandler constructor.
     *
     * @param string $endpoint
     * @param string $labels
     * @param int    $level
     * @param bool   $bubble
     */
    public function __construct(
        string $endpoint,
        string $labels = '{job="app"}',
        int $level = Logger::DEBUG,
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);
        $this->endpoint = $endpoint;
        $this->labels = $labels;
        $this->httpClient = new Client([
            'timeout' => 5.0,
            'http_errors' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record): void
    {
        $payload = [
            'streams' => [
                [
                    'labels' => $this->labels,
                    'entries' => [
                        [
                            'ts' => gmdate('Y-m-d\TH:i:s.v\Z'),
                            'line' => $record['formatted'],
                        ],
                    ],
                ],
            ],
        ];

        try {
            $response = $this->httpClient->post($this->endpoint, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() >= 400) {
                \Log::warning('Loki push failed', [
                    'status' => $response->getStatusCode(),
                    'body'   => (string)$response->getBody(),
                ]);
            }
        } catch (GuzzleException $e) {
            \Log::error('Error pushing log to Loki: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
        }
    }
}