<?php

namespace Fab\Domain\Country\Controller;
use \Fab\Domain\Country\View\countryOptions as countryOptions;
use \lw_response as lwResponse;

class Controller extends \LWddd\Controller
{
    public function __construct(lwResponse $response)
    {
        parent::__construct($response);
        $this->defaultAction = "getCountryOptions";
    }
    
    public function getCountryOptions()
    {
        $options = new countryOptions($this->domainEvent);
        $this->response->addOutputByName('FabOutput', $options->render());
    }
}