<?php

namespace Looxis\LaravelAmazonMWS;

class MWSFeeds
{
    const VERSION = '2009-01-01';

    protected $client;
    protected $content;
    protected $type;

    public function __construct(MWSClient $client)
    {
        $this->client = $client;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
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

    public function submit($purgeAndReplace = false, $amazonOrderId = null, $documentType = null)
    {
        $contentmd5Hash = base64_encode(md5($this->getContent(), true));
        $params = [
            'FeedType' => $this->type,
            'PurgeAndReplace' => $purgeAndReplace,
            'ContentMD5Value' => $contentmd5Hash,
        ];

        $response = $this->client->post('SubmitFeed', '/', self::VERSION, $params, $this->getContent());

        return $this->parseSubmitFeedResponse($response);
    }

    protected function parseSubmitFeedResponse($response)
    {
        $requestId = data_get($response, 'ResponseMetadata.RequestId');
        $feed = data_get($response, 'SubmitFeedResult.FeedSubmissionInfo');

        return [
            'request_id' => $requestId,
            'data' => $feed,
        ];
    }

    public function getFeedSubmissionResult($amazonFeedSubmissionId)
    {
        $params = [
            'FeedSubmissionId' => $amazonFeedSubmissionId
        ];

        $response = $this->client->post('GetFeedSubmissionResult', '/', self::VERSION, $params);

        return $this->parseSubmissionResultResponse($response);
    }

    protected function parseSubmissionResultResponse($response)
    {
        return [
            'status_code' => data_get($response, 'Message.ProcessingReport.StatusCode'),
            'processing_summary' => data_get($response, 'Message.ProcessingReport.ProcessingSummary'),
            'result' => data_get($response, 'Message.ProcessingReport.Result')
        ];
    }
}
