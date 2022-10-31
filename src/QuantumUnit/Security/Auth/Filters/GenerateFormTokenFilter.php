<?php
/*
 *  This file is part of the Quantum Unit Solutions development package.
 *
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

/**
 * Created by PhpStorm.
 * User: user
 * Date: 2/3/2018
 * Time: 9:20 PM
 */

namespace Gossamer\Ra\Security\Filters;


use Gossamer\Horus\Filters\AbstractFilter;
use Gossamer\Horus\Filters\FilterChain;
use Gossamer\Horus\Http\HttpRequest;
use Gossamer\Horus\Http\HttpResponse;
use Gossamer\Ra\Security\FormToken;
use Gossamer\Ra\Security\Traits\FormTokenTrait;


class GenerateFormTokenFilter extends AbstractFilter
{
    use FormTokenTrait;

    public function execute(HttpRequest &$request, HttpResponse &$response, FilterChain &$chain) {
        $sessionToken = $this->getDefaultToken();

        $token = $sessionToken->generateTokenString();
        $response->setAttribute('FORM_SECURITY_TOKEN', $token);

        $this->storeFormToken($sessionToken);

        parent::execute($request, $response, $chain); // TODO: Change the autogenerated stub
    }

    /**
     * saves the token into session so we can check in on POST
     *
     * @param FormToken $token
     */
    private function storeFormToken(FormToken $token) {
        setSession('_form_security_token', serialize($token));
    }
}