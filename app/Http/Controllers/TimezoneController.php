<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimezoneController extends Controller
{
    /**
     * Detecta la zona horaria a partir de la IP del usuario
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detectTimezone(Request $request)
    {
        // Obtener la IP del usuario
        $ip = $request->ip();
        
        // Para desarrollo local o IPs privadas, intentar determinar si estamos en España por otros medios
        if ($ip == '127.0.0.1' || $this->isPrivateIP($ip)) {
            // Verificar si el idioma del navegador sugiere España
            $userLang = $request->server('HTTP_ACCEPT_LANGUAGE');
            if ($userLang && (strpos($userLang, 'es-ES') !== false || strpos($userLang, 'ca-ES') !== false)) {
                // Es probable que esté en España
                return response()->json([
                    'timezone' => 'Europe/Madrid',
                    'offset' => 1, // UTC+1 (2 en verano con DST)
                    'etcGmtFormat' => 'Etc/GMT-1' // Para España (UTC+1)
                ]);
            }
        }
        
        // Intentar detectar la zona horaria usando un servicio externo
        try {
            // Usamos ip-api.com que es un servicio gratuito para geolocación de IPs
            $response = file_get_contents("http://ip-api.com/json/{$ip}?fields=status,timezone,offset,countryCode");
            $data = json_decode($response, true);
            
            if ($data && isset($data['status']) && $data['status'] === 'success') {
                // Verificar si estamos en España explícitamente
                if (isset($data['countryCode']) && $data['countryCode'] === 'ES') {
                    return response()->json([
                        'timezone' => 'Europe/Madrid',
                        'offset' => 1, // UTC+1 (2 en verano con DST)
                        'etcGmtFormat' => 'Etc/GMT-1' // Para España (UTC+1)
                    ]);
                }
                
                // Para otros países, usar la información del API
                if (isset($data['timezone']) && isset($data['offset'])) {
                    $timezone = $data['timezone'];
                    $offset = $data['offset']; // offset en segundos
                    
                    // Convertir el offset a horas (dividir por 3600 segundos)
                    $offsetHours = $offset / 3600;
                    
                    // Formatear según la convención Etc/GMT± que usa el componente Calendar
                    // Nota: en la convención Etc/GMT, el signo es inverso al usual
                    $sign = $offsetHours >= 0 ? '-' : '+';
                    $absOffset = abs($offsetHours);
                    $etcGmtFormat = "Etc/GMT{$sign}{$absOffset}";
                    
                    return response()->json([
                        'timezone' => $timezone,
                        'offset' => $offsetHours,
                        'etcGmtFormat' => $etcGmtFormat
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Si hay algún error, fallback a la detección por JavaScript o un valor por defecto para España
            error_log('Error al detectar zona horaria: ' . $e->getMessage());
        }
        
        // Si llegamos aquí, intentamos una última validación para España
        // Verificar si el idioma del navegador sugiere España como último recurso
        $userLang = $request->server('HTTP_ACCEPT_LANGUAGE');
        if ($userLang && (strpos($userLang, 'es-ES') !== false || strpos($userLang, 'ca-ES') !== false)) {
            return response()->json([
                'timezone' => 'Europe/Madrid',
                'offset' => 1, // UTC+1 (2 en verano con DST)
                'etcGmtFormat' => 'Etc/GMT-1' // Para España (UTC+1)
            ]);
        }
        
        // En caso de error o si no se detectó, devolvemos un valor por defecto
        return response()->json([
            'timezone' => null,
            'offset' => 0,
            'etcGmtFormat' => 'Etc/GMT+0' // UTC por defecto
        ]);
    }
    
    /**
     * Comprueba si una IP es privada
     * 
     * @param string $ip
     * @return bool
     */
    private function isPrivateIP($ip)
    {
        $privateRanges = [
            '10.0.0.0|10.255.255.255',     // 10.0.0.0/8
            '172.16.0.0|172.31.255.255',   // 172.16.0.0/12
            '192.168.0.0|192.168.255.255', // 192.168.0.0/16
            '169.254.0.0|169.254.255.255', // 169.254.0.0/16
            '127.0.0.0|127.255.255.255'    // 127.0.0.0/8
        ];
        
        $ipLong = ip2long($ip);
        
        if ($ipLong === false) {
            return false;
        }
        
        foreach ($privateRanges as $range) {
            list($start, $end) = explode('|', $range);
            if ($ipLong >= ip2long($start) && $ipLong <= ip2long($end)) {
                return true;
            }
        }
        
        return false;
    }
} 