<?php
namespace App\Http\Controllers;

use App\Library\Utils;
use Illuminate\Http\Request;

class MainController extends Controller
{
    
    
    /**
     * Displays main index page along with results if form has been posted
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        
        $search = '';
        $items = null;
        
        if($request->isMethod('POST'))
        {
            
            //TODO allow other search types
            
            $postFields = $request->all();
            
            $search = $postFields['q'];
            
            //asset type also exists for querying specific data entry
            $apiData = $this->getNasaApiData($this->formatFormInfo($postFields), 'search');
            
            $items = $apiData['collection']['items'];
            
        }
            
        return view('index', [
            'search' => $search,
            'items' => $items
        ]);
        
    }
    
    /**
     * Display specific asset info
     *
     * @param string $nasaId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function asset(string $nasaId)
    {
        
        //Asset data does not contain entry information so get info via nasa id search
        $assetInfo = $this->getNasaApiData(['nasa_id' => $nasaId], 'search');
        $assetManifest = $this->getNasaApiData(['id' => $nasaId], 'asset');
        
        $assetData = $this->formatAssetInfo($assetInfo, $assetManifest);
        
        return view('asset',[
            'asset' => $assetData
        ]);
        
    }
    
    /**
     * @param array $postFields
     * @return array
     */
    private function formatFormInfo(array $postFields): array
    {
        
        //CSRF token present so needs to be removed
        unset($postFields['_token']);
        
        $mediaTypes = ['image', 'audio', 'video'];
        $mediaSearch = '';
        
        //split out media types as they need to be comma delimited in api call
        foreach($mediaTypes as $mediaType)
        {
            
            if(isset($postFields[$mediaType]))
            {
                $mediaSearch .= $mediaType . ',';
                unset($postFields[$mediaType]);
            }
            
        }
        
        $mediaSearch = rtrim($mediaSearch, ',');
        $postFields['media_type'] = $mediaSearch;
        
        return $postFields;
        
    }
    
    /**
     * Generate url for NASA API and format returned data
     *
     * @param array $dataFields
     * @param string $type
     *
     * @throws \Exception
     * @return array
     */
    private function getNasaApiData(array $dataFields,  string $type): array
    {
        
        $apiUrl = 'https://images-api.nasa.gov/';
        
        if($type === 'asset')
        {
            $apiUrl .= 'asset/' . $dataFields['id'];
        }
        else
        {
            $apiUrl .= 'search?' . http_build_query($dataFields);
        }
        
        $apiData = json_decode(Utils::retrieveApiInformation($apiUrl), true);
        
        return $apiData;
        
    }
    
    /**
     * Combine asset std info and manifest info into one array for easier extraction in asset page
     *
     * @param array $info
     * @param array $manifest
     *
     * @return array
     */
    private function formatAssetInfo(array $info, array $manifest): array
    {
        
        $formattedData = [];
        
        $formattedData['info'] = $info['collection']['items'][0]['data'][0];
        
        //get mp3 audio source for max compatability
        if($formattedData['info']['media_type'] === 'image')
        {
            //first image seems to be set to default size
            $formattedData['assetSource'] = $manifest['collection']['items'][0]['href'];
            
        }
        elseif($formattedData['info']['media_type'] === 'audio')
        {
            $audioFile = null;
        
            foreach($manifest['collection']['items'] as $file)
            {
                
                $fileInfo = pathinfo($file['href']);
            
                if($fileInfo['extension'] === 'mp3')
                {
                    $audioFile = $file['href'];
                    break;
                }
            
            }
        
            $formattedData['assetSource'] = $audioFile;
        
        }
        else
        {
    
            $videoFile = null;
    
            foreach($manifest['collection']['items'] as $file)
            {
        
                $fileInfo = pathinfo($file['href']);
        
                if($fileInfo['extension'] === 'mp4')
                {
                    $videoFile = $file['href'];
                    break;
                }
        
            }
    
            $formattedData['assetSource'] = $videoFile;
            
        }
    
        return $formattedData;
        
    }
    
    
}