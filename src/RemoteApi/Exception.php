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

namespace Techworker\IOTA\RemoteApi;

/**
 * Class Exception.
 *
 * An exception class for errors related with the api.
 */
class Exception extends \DomainException
{
    /**
     * The response that led to the exception.
     *
     * @var AbstractResult
     */
    protected $response;

    /**
     * Exception constructor.
     *
     * @param AbstractResult $response
     */
    public function __construct(AbstractResult $response)
    {
        $this->response = $response;
        $raw = $response->getRawData();
        // TODO: maybe add error code
        $message = $raw['message'] ?? 'Unknown api error';
        if (isset($raw['error'])) {
            $message = $this->code.': '.$raw['error'];
        }

        parent::__construct($message);
    }
}
