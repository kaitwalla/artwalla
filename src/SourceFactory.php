<?php

namespace kaitwalla\artwall;

use kaitwalla\artwall\sources\Pexels;
use kaitwalla\artwall\sources\Pixabay;
use kaitwalla\artwall\sources\Unsplash;

class SourceFactory
{
    public static function getNewArt($getFromAPIRandom = false): Art
    {
        $randomNumber = ($getFromAPIRandom) ? rand(1, 3) : 3;
        switch ($randomNumber) {
            case 1:
                $data = json_decode(file_get_contents('https://pixabay.com/api?editors_choice=true&order=latest&orientation=vertical&per_page=20&min_height=1200&key=' . $_ENV['pixaBayKey']));
                $additions = [];
                foreach ($data->hits as $item) {
                    if (!Database::sourceIdExists(Pixabay::$sourceName, $item->id)) {
                        array_push($additions, new Pixabay($item));
                    } else {
                        break;
                    }
                }
                if (count($additions) > 0) {
                    return $additions[0]->art;
                } else {
                    return self::getNewArt(true);
                }
                break;
            case 2:
                return Unsplash::createNewArt();
                break;
            case 3:
                $opts = [
                    "http" => [
                        "method" => "GET",
                        "header" => "Authorization: " . $_ENV['pexelsApiKey']
                    ]
                ];
                $additions = [];
                $context = stream_context_create($opts);
                $data = json_decode(file_get_contents('https://api.pexels.com/v1/curated?orientation=portrait', false, $context));
                foreach ($data->photos as $item) {
                    if (!Database::sourceIdExists(Pexels::$sourceName, $item->id)) {
                        array_push($additions, new Pexels($item));
                    } else {
                        break;
                    }
                }
                if (count($additions) > 0) {
                    return $additions[0]->art;
                } else {
                    return self::getNewArt(true);
                }
                break;
        }
    }
}
