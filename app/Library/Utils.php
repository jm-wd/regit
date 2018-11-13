<?php

namespace App\Library;

class Utils
{
    
    /**
     * Get data via API endpoint using cURL
     *
     * @param string $url
     * @param array $extraOptions
     *
     * @return string $content
     * @throws \Exception
     */
    public static function retrieveApiInformation(string $url, $extraOptions = array()): string
    {
        
        $curlOptions = [
            CURLOPT_RETURNTRANSFER => TRUE,     // return output to var
            CURLOPT_VERBOSE        => 0,        // do not return connection info
            CURLOPT_USERAGENT      => "spider",
        ];
        
        $ch = curl_init($url);
        curl_setopt_array($ch, $curlOptions);
        
        if(!empty($extraOptions))
        {
            foreach($extraOptions as $optionKey => $optionValue)
                $curlOptions[$optionKey] = $optionValue;
        }
        
        $content = curl_exec($ch);
        $errorNum = curl_errno($ch);
        $errorMsg = curl_error($ch);
        
        curl_close($ch);
        
        if($errorNum > 0)
        {
            
            throw new \Exception($errorNum . ' ' . $errorMsg);
            
        }
        
        return $content;
        
    }
    
}