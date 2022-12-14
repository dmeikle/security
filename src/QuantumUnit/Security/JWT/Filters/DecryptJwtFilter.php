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
 * Date: 2/8/2018
 * Time: 8:36 PM
 */

namespace QuantumUnit\Security\Auth\JWT\Filters;


use Gossamer\Core\Components\Security\Core\Client;
use Gossamer\Essentials\Configuration\Exceptions\KeyNotSetException;
use Gossamer\Horus\Filters\AbstractFilter;
use Gossamer\Horus\Filters\FilterChain;
use Gossamer\Horus\Http\HttpRequest;
use Gossamer\Horus\Http\HttpResponse;
use Gossamer\Pesedget\Extensions\Couchbase\Exceptions\KeyNotFoundException;
use QuantumUnit\Security\Auth\Exceptions\TokenExpiredException;
use QuantumUnit\Security\Auth\JWT\TokenManager;
use QuantumUnit\Security\Auth\SecurityToken;

class DecryptJwtFilter extends AbstractFilter
{

    const KEY = 'Authorization';

    const IDENTIFIER = 'JWT ';

    /**
     * @param HttpRequest $request
     * @param HttpResponse $response
     * @param FilterChain $chain
     * @throws TokenExpiredException
     */
    public function execute(HttpRequest &$request, HttpResponse &$response, FilterChain &$chain) {

        try{
echo "decrypt filter<br>getting jwt cookie<br>";
           // $jwt = $this->getJwtHeader();
            $jwt = null;
            try {
                $jwt = $this->getJwtCookie();
            }catch(\Exception $e) {
                echo "exception<BR>";
                die($e->getMessage());
            }
            echo "<br>decrypted jwt $jwt<br>";
//$jwt='JWT eyJhbGciOiJBMjU2S1ciLCJlbmMiOiJBMjU2Q0JDLUhTNTEyIiwiemlwIjoiREVGIn0.d-tGrBsXpTdnUr-t4CJBQaD72H7WnlEOrHQZedUU15ceWUIqHv-WTBE_RVp-VFco6hB78qU9saLDTx76YL76CAJCUv8wUNWf.lqzdFgGM5tkrJDsH7gBryQ.wy5YwTvDcCbtXAO_wbOIXTxRivJ14uTcacEXidxUcqV1iTz01YJMybimLwIt-sMambNP6kUJv1OPwosFRrBLBPnlr9dEPjDZVp0xWO-goCByrV5Bb43HXntVwRU-nLVA.vNX-3hwdNrM5QAa02j5fU5-FDcl6ULYf2e3GTmEUqi0';
            $manager = new TokenManager($this->httpRequest);

            $item = $manager->getDecryptedJwtToken($jwt);
            echo "decrypt<br>";
pr($item);

            $_SESSION = $item;
            echo "session<br>";
            pr($_SESSION);
        }catch(KeyNotSetException $e) {
            //if we're coming from a login we may not have a JWT yet so perhaps don't kill the request just yet
//die('KeyNotSetException');
            //let's create a cache ID manually

            $this->setCacheId();
        }catch(TokenExpiredException $e) {

            throw new TokenExpiredException("JWT token expired", 401);
        }

        parent::execute($request, $response, $chain); // TODO: Change the autogenerated stub
    }

    /**
     * @return mixed
     * @throws KeyNotSetException
     */
    private function getJwtHeader() {
        $headers = getallheaders();

        if(!array_key_exists(self::KEY, $headers)) {
            throw new KeyNotSetException('JWT missing from request headers');
        }

        //return it but strip the identifier from it - only the token string left
        return substr($headers[self::KEY], strlen(self::IDENTIFIER));
    }

    private function checkExpirationTime() {

    }

    private function getJwtCookie() {
        if(array_key_exists('Authorization', $_COOKIE)) {
            return $_COOKIE['Authorization'];
        }

        throw new KeyNotSetException();
    }

    /**
     * all we needed from the JWT is the cache ID...
     */
    private function setCacheId() {

            $id = uniqid();
            setSession('CACHE_ID', $id);

    }
}