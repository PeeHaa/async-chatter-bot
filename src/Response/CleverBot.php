<?php declare(strict_types=1);

namespace PeeHaa\AsyncChatterBot\Response;

class CleverBot
{
    private $conversationState;

    private $interactionCount;

    private $input;

    private $output;

    private $conversationId;

    public function __construct(string $response)
    {
        $parsedResponse = json_decode($response, true);

        $this->conversationState = $parsedResponse['cs'];
        $this->interactionCount  = (int) $parsedResponse['interaction_count'];
        $this->input             = $parsedResponse['input'];
        $this->output            = $parsedResponse['output'];
        $this->conversationId    = $parsedResponse['conversation_id'];
    }

    public function getText(): string
    {
        return $this->output;
    }

    public function getConversationId(): string
    {
        return $this->conversationId;
    }

    public function getConversationState(): string
    {
        return $this->conversationState;
    }
}
