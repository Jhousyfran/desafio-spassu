<?php

namespace App\Http\Controllers;

use App\Http\Requests\Topic\StoreTopicRequest;
use App\Http\Requests\Topic\UpdateTopicRequest;
use App\Models\Topic;

class TopicController extends ApiBaseController
{
    protected string $model = Topic::class;
    protected $storeRequestClass = StoreTopicRequest::class;
    protected $updateRequestClass = UpdateTopicRequest::class;
    protected string $resource = 'Assunto';
}
