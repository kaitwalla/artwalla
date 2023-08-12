<?php

namespace kaitwalla\artwall;

class Art
{
    public function __construct(
        public string $title,
        public string $description,
        public string $artist,
        public string $source,
        public string $url,
        public bool $favorited,
        public string|int $sourceId,
        public int $id
    ) {
    }
}
