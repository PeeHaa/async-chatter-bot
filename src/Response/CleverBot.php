<?php declare(strict_types=1);

namespace PeeHaa\AsyncChatterBot\Response;

class CleverBot
{
    private $text;

    private $sessionId;

    private $logUrl;

    private $prevRef;

    private $vText2;

    private $vText3;

    private $vText4;

    private $vText5;

    private $vText6;

    private $vText7;

    private $vText8;

    public function __construct(string $response)
    {
        $parsedResponse = explode("\r", $response);

        $this->text      = $parsedResponse[0];
        $this->sessionId = $parsedResponse[1];
        $this->logUrl    = $parsedResponse[2];
        $this->prevRef   = $parsedResponse[10];
        $this->vText2    = $parsedResponse[9];
        $this->vText3    = $parsedResponse[8];
        $this->vText4    = $parsedResponse[7];
        $this->vText5    = $parsedResponse[6];
        $this->vText6    = $parsedResponse[5];
        $this->vText7    = $parsedResponse[4];
        $this->vText8    = $parsedResponse[3];
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getParameters(): array
    {
        return [
            'sessionid' => $this->sessionId,
            'logurl'    => $this->logUrl,
            'vText8'    => $this->vText8,
            'vText7'    => $this->vText7,
            'vText6'    => $this->vText6,
            'vText5'    => $this->vText5,
            'vText4'    => $this->vText4,
            'vText3'    => $this->vText3,
            'vText2'    => $this->vText2,
            'prevref'   => $this->prevRef,
        ];
    }
}
