<?php

declare(strict_types=1);

/*
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IOTA\Cryptography\Keccak384;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

/**
 * Class NodeJS.
 *
 * A class that calls a node js service to retrieve a keccak384 hash from the
 * given hashes.
 *
 * see https://github.com/Techworker/iota-php/commit/592327021d4d2e91bc3ef38790437c8f32acd1fd
 * to get an idea on what was used before.
 */
class NodeJS implements Keccak384Interface
{
    /**
     * The url to the node signing webservice.
     *
     * @var string
     */
    protected $url;

    /**
     * NodeJS constructor.
     *
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function digest(array $hashes): string
    {
        $data = ['hashes' => json_encode($hashes)];
        $client = new Client();
        $response = $client->post($this->url, [
            RequestOptions::FORM_PARAMS => $data,
        ]);

        return trim((string) $response->getBody());
    }
}
