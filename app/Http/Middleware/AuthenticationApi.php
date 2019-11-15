<?php
/**
 * AuthenticationApi File Doc Comment
 *  PHP version 7
 *
 * @category Authentication
 * @package  Laranjaapi
 * @author   Mario Gonçalves <mario.goncalves@acin.pt>
 * @license  http://acin.pt MIT
 * @link     http://acin.pt
 */
namespace App\Http\Middleware;

// use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use ResponseUtils;

/**
 * AuthenticationApi class
 *
 * @category Authentication
 * @package  Laranjaapi
 * @author   Mario Gonçalves <mario.goncalves@acin.pt>
 * @license  http://acin.pt MIT
 * @link     http://acin.pt
 */
class AuthenticationApi
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request - Traz o corpo do pedido
     * @param \Closure                 $next    - envia o callback do pedido
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->bearerToken()) {
            return ResponseUtils::responseApi(401, 'Acesso Negado. Por favor,inicie sessao.', false);
        }
        try {
            if (!JWTAuth::parseToken()->authenticate()) {
                return ResponseUtils::responseApi(401, 'Acesso Negado.Token expirada ou mal formada.Por favor,inicie sessão', false);
            }
        } catch (JWTException $e) {
                return  ResponseUtils::responseApi(401, 'Acesso Negado.Token Inválida.Por favor,inicie sessão', false);
        }

        return $next($request);
    }
}
