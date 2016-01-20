<?php
/**
 * Created by PhpStorm.
 * User: Vuong
 * Date: 21-Jan-16
 * Time: 2:15 AM
 */

namespace Src\Utils;

use GuzzleHttp\Client;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

class GetPage
{
    protected $client;

    protected $count = 0;

    protected $zipName;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get($response)
    {
        $me = $response->getBody();
        $data = json_decode($me);


        echo '<pre>';
        foreach ($data->data as $object) {
            foreach ($object->images as $image) {
                $this->count++;

                $fileName = $this->getZipName() . '/' . $this->count . '.jpg';

                $this->system()->put($fileName, file_get_contents($image->source));

//                echo '<img src="' . $image->source . '" /> <br>' ;
                break;
            }
        }
        echo '</pre>';

        if (isset($data->paging->next)) {
            $response = $this->client->get($data->paging->next);
            $this->get($response);
        } else {
            echo $this->count;

        }
    }

    public function system()
    {
        $adapter = new Local(__DIR__.'/../../storage/');
        $filesystem = new Filesystem($adapter);
        $filesystem->createDir($this->getZipName());
        return $filesystem;
    }

    /**
     * @return mixed
     */
    public function getZipName()
    {
        return $this->zipName;
    }

    /**
     * @param mixed $zipName
     */
    public function setZipName($zipName)
    {
        $this->zipName = $zipName;
    }
}