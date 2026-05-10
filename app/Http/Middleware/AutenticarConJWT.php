<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;


class AutenticarConJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $datosToken = JWTAuth::parseToken()->getPayload();

            $request->merge([
                'usuario_id'        => $datosToken->get('sub'),
                'usuario_email'     => $datosToken->get('email'),
                'usuario_nombre'    => $datosToken->get('name'),

            ]);
        } catch (TokenExpiredException $e){
            return response()->json([
                'mensaje' => 'El token ha expirado. Inicia nuevamente.',
            ],401);
        } catch (TokenInvalidException $e){
            return response()->json([
                'mensaje' => 'Token invalido',
            ],401);
        } catch (JWTException $e){
            return response()->json([
                'mensaje' => 'Aún no ha proporcionado el token',
            ]);
        }


        return $next($request);
    }
}
