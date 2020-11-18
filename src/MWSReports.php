<?php

namespace Looxis\LaravelAmazonMWS;

class MWSReports
{
    const VERSION = '2009-01-01';

    protected $client;
    protected $type;

    public function __construct(MWSClient $client)
    {
        $this->client = $client;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function request($options = [], $startDate = null, $endDate = null)
    {
        $params = [
            'ReportType' => $this->type,
        ];

        if (!empty($options)) {
            $params['ReportOptions'] = $options;
        }
        if ($startDate !== null) {
            $params['StartDate'] = $startDate;
        }
        if ($endDate !== null) {
            $params['EndDate'] = $endDate;
        }

        $response = $this->client->post('RequestReport', '/', self::VERSION, $params);
        return $this->parseRequestReportResponse($response);
    }

    protected function parseRequestReportResponse($response)
    {
        $requestId = data_get($response, 'ResponseMetadata.RequestId');
        $feed = data_get($response, 'RequestReportResult.ReportRequestInfo');

        return [
            'request_id' => $requestId,
            'data' => $feed,
        ];
    }

    public function getFeedSubmissionResult($amazonFeedSubmissionId)
    {
        $params = [
            'FeedSubmissionId' => $amazonFeedSubmissionId,
        ];

        $response = $this->client->post('GetFeedSubmissionResult', '/', self::VERSION, $params);

        return $this->parseSubmissionResultResponse($response);
    }

    protected function parseSubmissionResultResponse($response)
    {
        return [
            'status_code' => data_get($response, 'Message.ProcessingReport.StatusCode'),
            'processing_summary' => data_get($response, 'Message.ProcessingReport.ProcessingSummary'),
            'result' => data_get($response, 'Message.ProcessingReport.Result'),
        ];
    }
}
